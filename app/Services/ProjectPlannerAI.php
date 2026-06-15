<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\PlannerWorkflowStep;
use App\Models\Service;
use App\Models\ServiceGroup;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Powers the "Plan Your Project" wizard.
 * Uses Anthropic Claude when a key + toggle are present; otherwise falls back to
 * deterministic, DB-grounded smart rules. Both paths recommend only REAL services
 * and surface category / service-group "process" content for richer detail.
 */
class ProjectPlannerAI
{
    private const ANTHROPIC_ENDPOINT = 'https://api.anthropic.com/v1/messages';
    private const GEMINI_ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/';

    /** Raw text of the last successful LLM response (for the "Gemini outcome" panel). */
    private ?string $lastRaw = null;
    public function lastRaw(): ?string { return $this->lastRaw; }

    /** Diagnostics from the last LLM HTTP call (for the Settings test panel). */
    private ?int $lastStatus = null;          // HTTP status, or null if the request never left
    private ?string $lastError = null;        // error body / exception message, if any
    public function lastStatus(): ?int { return $this->lastStatus; }
    public function lastError(): ?string { return $this->lastError; }

    /** AI is usable only if the toggle is on AND a key for the chosen provider is resolvable. */
    public function enabled(): bool
    {
        return AppSetting::bool('ai_planner_enabled', false) && !empty($this->apiKey());
    }

    public function engine(): string
    {
        return $this->enabled() ? 'ai' : 'rules';
    }

    /** 'gemini' (default) | 'anthropic' */
    private function provider(): string
    {
        $p = AppSetting::get('ai_provider', 'gemini');
        return in_array($p, ['gemini', 'anthropic'], true) ? $p : 'gemini';
    }

    private function apiKey(): ?string
    {
        if ($this->provider() === 'anthropic') {
            return AppSetting::getSecret('anthropic_api_key') ?: (config('services.anthropic.key') ?: null);
        }
        return AppSetting::getSecret('gemini_api_key') ?: (config('services.gemini.key') ?: null);
    }

    private function model(): string
    {
        return $this->provider() === 'anthropic'
            ? AppSetting::get('ai_model', 'claude-haiku-4-5-20251001')
            : AppSetting::get('gemini_model', 'gemini-2.5-flash');
    }

    // ── Knowledge base ────────────────────────────────────────────────
    /** Compact catalogue of published services for matching / grounding. */
    public function catalog(): array
    {
        return Cache::remember('planner_catalog_v1', 600, function () {
            // Raw queries avoid model casts/accessors (and their PHP 8 deprecation warnings)
            // that could otherwise leak into JSON responses.
            $cats = DB::table('service_categories')
                ->join('categories', 'categories.id', '=', 'service_categories.category_id')
                ->select('service_categories.service_id', 'categories.name')
                ->get()->groupBy('service_id');

            return DB::table('services')->where('status', 'published')
                ->get(['id', 'name', 'slug', 'overview'])
                ->map(function ($s) use ($cats) {
                    return [
                        'id'         => $s->id,
                        'name'       => $s->name,
                        'slug'       => $s->slug,
                        'overview'   => Str::limit(trim(strip_tags((string) $s->overview)), 220),
                        'categories' => isset($cats[$s->id]) ? $cats[$s->id]->pluck('name')->values()->all() : [],
                    ];
                })->values()->all();
        });
    }

    /** Process / approach detail from categories + service groups, keyed by lowercased name. */
    public function processKnowledge(): array
    {
        return Cache::remember('planner_process_kb_v1', 600, function () {
            $out = [];
            $rows = DB::table('categories')->get(['name', 'process_intro', 'process_description'])
                ->concat(DB::table('service_groups')->get(['name', 'process_intro', 'process_description']));
            foreach ($rows as $r) {
                $raw = $r->process_description ?: $r->process_intro;
                $detail = is_string($raw) ? trim(strip_tags($raw)) : '';
                if ($detail !== '') $out[Str::lower($r->name)] = Str::limit($detail, 240);
            }
            return $out;
        });
    }

    // ── Free-text "describe your challenge" → solution + services ──────
    public function solveFreeText(string $text, array $ctx = []): array
    {
        $text = trim($text);
        if ($text === '') {
            return ['solution' => '', 'service_ids' => [], 'engine' => 'rules'];
        }

        if ($this->enabled()) {
            $ai = $this->claudeSolve($text, $ctx);
            if ($ai) return $ai + ['engine' => 'ai'];
        }
        return $this->rulesSolve($text, $ctx) + ['engine' => 'rules'];
    }

    /** Build the ordered, enabled customer-facing steps for the front planner. */
    public function customerSteps(): array
    {
        $facilityMainId = DB::table('main_categories')->where('name', 'By Facility Type')->value('id');
        $excludeIds = collect();
        if ($facilityMainId) {
            $excludeIds = DB::table('categories')->where('main_category_id', $facilityMainId)->pluck('id');
            try {
                $excludeIds = $excludeIds->merge(DB::table('category_main_category')->where('main_category_id', $facilityMainId)->pluck('category_id'));
            } catch (\Throwable $e) {}
        }
        $categoryNames = DB::table('categories')
            ->when($excludeIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $excludeIds->unique()->all()))
            ->orderBy('sort_order')->orderBy('name')->pluck('name')->filter()->unique()->values()->all();

        $serviceNames = collect($this->catalog())->pluck('name')->filter()->unique()->take(60)->values()->all();

        return PlannerWorkflowStep::where('kind', '!=', 'admin')->where('enabled', true)->ordered()->get()
            ->map(function ($s) use ($categoryNames, $serviceNames) {
                $options = $s->options ?? [];
                if ($s->source === 'categories') $options = $categoryNames;
                if ($s->source === 'services')   $options = $serviceNames;
                return [
                    'key'     => $s->step_key,
                    'label'   => $s->label,
                    'help'    => $s->help_text,
                    'icon'    => $s->icon ?: 'fa-solid fa-circle-dot',
                    'kind'    => $s->kind,
                    'options' => array_values($options),
                ];
            })->filter(fn ($s) => $s['kind'] === 'text' || !empty($s['options']))->values()->all();
    }

    /** Admin-authored internal guidance blocks (process, cost/timeline, custom). */
    public function adminGuidance(): array
    {
        return Cache::remember('planner_admin_guidance_v1', 300, function () {
            $out = [];
            foreach (PlannerWorkflowStep::where('kind', 'admin')->where('enabled', true)->ordered()->get() as $s) {
                if (trim((string) $s->admin_content) !== '') $out[$s->step_key] = trim($s->admin_content);
            }
            return $out;
        });
    }

    // ── Final structured brief + recommendations ───────────────────────
    public function finalBrief(array $answers): array
    {
        $guidance = $this->adminGuidance();
        $costWanted = $this->costRequested($answers);
        if ($this->enabled()) {
            $ai = $this->claudeBrief($answers, $guidance, $costWanted);
            if ($ai) return $ai + ['engine' => 'ai'];
        }
        return $this->rulesBrief($answers, $guidance, $costWanted) + ['engine' => 'rules'];
    }

    /** True only when the client actually asked about money — controls whether a Cost block appears. */
    public function costRequested(array $answers): bool
    {
        $blob = Str::lower(trim(
            ($answers['intent'] ?? '') . ' ' . ($answers['region'] ?? '') . ' ' . ($answers['facility_type'] ?? '') . ' ' .
            implode(' ', (array) ($answers['selected_services'] ?? [])) . ' ' . ($answers['free_text'] ?? '')
        ));
        $keywords = ['cost', 'budget', 'price', 'pricing', 'fee', 'fees', 'invest', 'expense', 'capex', 'opex',
                  'how much', 'estimate', 'quotation', 'quote', 'aed', 'usd', 'financ', 'spend', 'afford'];
        foreach ($keywords as $kw) {
            if (!Str::contains($blob, $kw)) continue;
            // Skip an obvious negation right before the keyword ("no cost", "without budget", "don't need pricing").
            if (preg_match('/\b(no|not|without|don\'?t|do not|dont|exclude|skip|no need for|don\'?t need|isn\'?t|aren\'?t)\b[\sa-z]{0,12}' . preg_quote($kw, '/') . '/', $blob)) {
                continue;
            }
            return true;
        }
        return false;
    }

    // ── Claude calls ────────────────────────────────────────────────────
    private function claudeSolve(string $text, array $ctx): ?array
    {
        $catalog = $this->compactCatalogForPrompt();
        $system = "You are a senior healthcare-facility consultant for Alpha Health Group (UAE/GCC). "
            . "Reply ONLY with a JSON object, no prose, no markdown fences. "
            . "Schema: {\"solution\": string (2-4 sentences, practical, reassuring), "
            . "\"service_ids\": number[] (up to 4 ids chosen ONLY from the catalogue)}.";
        $user = $this->knowledgeForPrompt()
            . "Catalogue (id: name — overview):\n{$catalog}\n\n"
            . "Client context: " . json_encode($ctx) . "\n"
            . "Client challenge: \"{$text}\"";

        $json = $this->callLLM($system, $user, 500);
        if (!is_array($json) || !isset($json['solution'])) return null;
        return [
            'solution'    => (string) $json['solution'],
            'service_ids' => $this->sanitizeIds($json['service_ids'] ?? []),
        ];
    }

    private function claudeBrief(array $answers, array $guidance = [], bool $costWanted = false): ?array
    {
        $catalog = $this->compactCatalogForPrompt();
        $guide = '';
        foreach ($guidance as $k => $v) { $guide .= "[" . str_replace('_', ' ', $k) . "]: {$v}\n"; }

        // Restate the client's own answers so the model is forced to be specific, not generic.
        $facts = array_filter([
            'goal'             => $answers['intent'] ?? null,
            'location'         => $answers['region'] ?? null,
            'facility_type'    => $answers['facility_type'] ?? null,
            'services_of_interest' => !empty($answers['selected_services']) ? implode(', ', (array) $answers['selected_services']) : null,
            'their_own_words'  => $answers['free_text'] ?? null,
        ]);

        $costRule = $costWanted
            ? "The client ASKED about cost/budget, so include a \"cost\" field (indicative ranges only, framed as 'subject to a detailed scope')."
            : "The client did NOT ask about cost, so set \"cost\" to an empty string \"\" — do not mention money.";

        $system = "You are a senior healthcare-facility consultant for Alpha Health Group (UAE/GCC), advising on a real client project. "
            . "Treat the client's answers as a consultation brief and produce a CUSTOM plan that explicitly references their goal, location, facility type and their own words — never generic boilerplate. "
            . "Use the internal guidance and knowledge base to ground process, regulatory pathway, timeline and cost. {$costRule} "
            . "Reply ONLY with a JSON object, no prose, no markdown fences. "
            . "Schema: {"
            . "\"summary\": string (1-2 sentences restating THIS client's specific project in your own words), "
            . "\"plan\": string (3-5 sentences — the tailored plan: what this specific project needs and the path Alpha would take, referencing their goal/location/facility), "
            . "\"regulatory\": string (2-3 sentences — the specific licensing/accreditation pathway for their location and facility type, naming the relevant authority e.g. DHA Dubai, DoH Abu Dhabi, MOHAP, DHCC, JCI/JAWDA, and any GCC equivalent), "
            . "\"phases\": [{\"title\": one of [Research, Plan, Execute, Results], \"detail\": string (1-2 sentences tailored to THIS client)}], "
            . "\"timeline\": string (1-2 sentences, realistic phased timeline for THIS project), "
            . "\"cost\": string (per the cost rule above), "
            . "\"alpha_offer\": string (2-3 sentences — concretely what Alpha Health Group brings to THIS project: relevant experience, specialist teams, and how it de-risks their goal), "
            . "\"recommended\": [{\"service_id\": number from catalogue, \"reason\": string (1 sentence tying the service to their stated need)}] (3-5)}.";

        $user = ($guide ? "Internal guidance (do not quote verbatim):\n{$guide}\n" : '')
            . $this->knowledgeForPrompt()
            . "Catalogue (id: name — overview):\n{$catalog}\n\n"
            . "Client brief: " . json_encode($facts, JSON_UNESCAPED_UNICODE);

        $json = $this->callLLM($system, $user, 1600);
        if (!is_array($json) || !isset($json['summary'])) return null;

        $recommended = [];
        foreach (($json['recommended'] ?? []) as $r) {
            $id = (int) ($r['service_id'] ?? 0);
            if ($id) $recommended[] = ['service_id' => $id, 'reason' => (string) ($r['reason'] ?? '')];
        }
        $phases = [];
        foreach (($json['phases'] ?? []) as $p) {
            if (!empty($p['title'])) $phases[] = ['title' => (string) $p['title'], 'detail' => (string) ($p['detail'] ?? '')];
        }
        if (!$phases) $phases = $this->defaultPhases($answers);

        return [
            'summary'      => (string) $json['summary'],
            'plan'         => (string) ($json['plan'] ?? ''),
            'regulatory'   => (string) ($json['regulatory'] ?? ''),
            'phases'       => $phases,
            'timeline'     => (string) ($json['timeline'] ?? ''),
            'cost'         => $costWanted ? (string) ($json['cost'] ?? '') : '',
            'alpha_offer'  => (string) ($json['alpha_offer'] ?? ''),
            'recommended'  => $recommended ?: $this->rulesRecommend($answers),
        ];
    }

    /** HTTP client: retries transient errors; relaxes SSL verify only in local dev
     *  (Windows/XAMPP often lacks a CA bundle — production keeps verification on). */
    private function http()
    {
        $req = Http::timeout(30);
        if (app()->environment('local')) {
            $req = $req->withoutVerifying();
        }
        return $req;
    }

    /** Live connectivity test for the dashboard "Test AI connection" button. */
    public function testConnection(): array
    {
        $provider = ucfirst($this->provider());
        if (!AppSetting::bool('ai_planner_enabled', false)) {
            return ['ok' => false, 'reached' => false, 'message' => 'AI is turned off. Toggle "Enable AI" on, then test.'];
        }
        if (empty($this->apiKey())) {
            return ['ok' => false, 'reached' => false, 'message' => 'No API key saved for ' . $provider . '. Paste your key and save first.'];
        }

        $json = $this->callLLM('Reply ONLY with JSON.', 'Return exactly {"ok": true}', 50);
        $reached = $this->lastStatus !== null; // we got an HTTP status back ⇒ the request reached the provider

        if (is_array($json)) {
            return [
                'ok'      => true,
                'reached' => true,
                'status'  => $this->lastStatus,
                'model'   => $this->model(),
                'message' => $provider . ' (' . $this->model() . ') is connected and responding.',
                'raw'     => Str::limit((string) $this->lastRaw, 600),
            ];
        }

        // Couldn't parse a JSON reply — explain WHY using the captured status/error.
        $detail = $this->lastError ?: 'No error detail captured.';
        if (!$reached) {
            $message = 'Request did NOT reach ' . $provider . ' (network/SSL/timeout). Detail: ' . $detail;
        } elseif ($this->lastStatus === 429) {
            $message = $provider . ' reached, but the model is over its quota / rate limit (HTTP 429). '
                . 'Switch to a model with free quota (e.g. Gemini 2.5 Flash-Lite) or enable billing. Detail: ' . $detail;
        } else {
            $message = $provider . ' reached, but returned HTTP ' . ($this->lastStatus ?? '?') . '. Detail: ' . $detail;
        }
        return [
            'ok'      => false,
            'reached' => $reached,
            'status'  => $this->lastStatus,
            'model'   => $this->model(),
            'message' => $message,
            'error'   => $detail,
        ];
    }

    /** Provider-aware LLM call → parsed JSON object (or null). */
    private function callLLM(string $system, string $user, int $maxTokens = 600): ?array
    {
        return $this->provider() === 'anthropic'
            ? $this->callClaude($system, $user, $maxTokens)
            : $this->callGemini($system, $user, $maxTokens);
    }

    /** Google Gemini (default — e.g. gemini-2.5-flash) with forced JSON output. */
    private function callGemini(string $system, string $user, int $maxTokens = 600): ?array
    {
        try {
            $url = self::GEMINI_ENDPOINT . $this->model() . ':generateContent?key=' . urlencode((string) $this->apiKey());
            $payload = [
                'systemInstruction' => ['parts' => [['text' => $system]]],
                'contents'          => [['role' => 'user', 'parts' => [['text' => $user]]]],
                'generationConfig'  => [
                    'temperature'      => 0.4,
                    'maxOutputTokens'  => $maxTokens,
                    'responseMimeType' => 'application/json',
                ],
            ];
            // Gemini can return 503 "high demand" / 429 — retry a few times with backoff.
            $resp = null;
            for ($attempt = 1; $attempt <= 4; $attempt++) {
                $resp = $this->http()->withHeaders(['content-type' => 'application/json'])->post($url, $payload);
                if ($resp->successful()) break;
                if (!in_array($resp->status(), [429, 500, 502, 503, 504], true)) break; // non-retryable
                usleep(700000); // 0.7s backoff
            }
            $this->lastStatus = $resp?->status();
            if (!$resp || $resp->failed()) {
                $this->lastError = $this->geminiErrorMessage($resp?->body() ?? '');
                Log::warning('Planner Gemini error', ['status' => $resp?->status(), 'body' => Str::limit($resp?->body() ?? '', 300)]);
                return null;
            }
            $this->lastError = null;
            $raw = (string) $resp->json('candidates.0.content.parts.0.text', '');
            $this->lastRaw = $raw;
            return $this->extractJson($raw);
        } catch (\Throwable $e) {
            $this->lastStatus = null;
            $this->lastError = $e->getMessage();
            Log::error('Planner Gemini exception', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    private function callClaude(string $system, string $user, int $maxTokens = 600): ?array
    {
        try {
            $resp = $this->http()->withHeaders([
                'x-api-key'         => $this->apiKey(),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->post(self::ANTHROPIC_ENDPOINT, [
                'model'      => $this->model(),
                'max_tokens' => $maxTokens,
                'system'     => $system,
                'messages'   => [['role' => 'user', 'content' => $user]],
            ]);

            $this->lastStatus = $resp->status();
            if ($resp->failed()) {
                $this->lastError = $this->geminiErrorMessage($resp->body());
                Log::warning('Planner Claude error', ['status' => $resp->status()]);
                return null;
            }
            $this->lastError = null;
            $raw = (string) $resp->json('content.0.text', '');
            $this->lastRaw = $raw;
            return $this->extractJson($raw);
        } catch (\Throwable $e) {
            $this->lastStatus = null;
            $this->lastError = $e->getMessage();
            Log::error('Planner Claude exception', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    /** Pull the human-readable message out of a provider error body (falls back to a snippet). */
    private function geminiErrorMessage(string $body): string
    {
        $body = trim($body);
        if ($body === '') return 'No response body returned.';
        $j = json_decode($body, true);
        if (is_array($j) && !empty($j['error']['message'])) {
            return Str::limit((string) $j['error']['message'], 400);
        }
        return Str::limit($body, 400);
    }

    /** Compact knowledge-base block (our process/approach by area) for grounding. */
    private function knowledgeForPrompt(): string
    {
        $kb = $this->processKnowledge();
        if (!$kb) return '';
        $lines = collect($kb)->take(20)->map(fn ($v, $k) => "- {$k}: {$v}")->implode("\n");
        return "Knowledge base (Alpha's approach/process by area — use to ground your answer):\n{$lines}\n\n";
    }

    /** Pull the first JSON object out of a model response (tolerates fences/prose). */
    private function extractJson(string $raw): ?array
    {
        $raw = trim($raw);
        if ($raw === '') return null;
        $raw = preg_replace('/^```(json)?|```$/m', '', $raw);
        $start = strpos($raw, '{');
        $end = strrpos($raw, '}');
        if ($start === false || $end === false || $end <= $start) return null;
        $decoded = json_decode(substr($raw, $start, $end - $start + 1), true);
        return is_array($decoded) ? $decoded : null;
    }

    private function compactCatalogForPrompt(): string
    {
        return collect($this->catalog())
            ->map(fn ($s) => "{$s['id']}: {$s['name']} — {$s['overview']}")
            ->implode("\n");
    }

    // ── Rules fallback ──────────────────────────────────────────────────
    private function rulesSolve(string $text, array $ctx): array
    {
        $ids = $this->matchServiceIds(trim($text . ' ' . ($ctx['intent'] ?? '') . ' ' . ($ctx['facility_type'] ?? '')), 4);
        $names = collect($this->catalog())->whereIn('id', $ids)->pluck('name')->all();
        $lead = $names
            ? "Based on what you've described, our team can help through " . $this->humanList($names) . "."
            : "Our consulting team can map your challenge to the right licensing, accreditation, quality and operational support.";
        return [
            'solution'    => $lead . " We start by reviewing your current position, then build a practical, standards-aligned plan and support you through delivery.",
            'service_ids' => $ids,
        ];
    }

    private function rulesBrief(array $answers, array $guidance = [], bool $costWanted = false): array
    {
        $intent   = trim((string) ($answers['intent'] ?? '')) ?: 'your healthcare project';
        $region   = trim((string) ($answers['region'] ?? '')) ?: null;
        $type     = trim((string) ($answers['facility_type'] ?? '')) ?: null;
        $services = array_values(array_filter((array) ($answers['selected_services'] ?? [])));
        $freeText = trim((string) ($answers['free_text'] ?? ''));

        $summary = "You're looking to " . Str::lower($intent)
            . ($type ? " for a {$type}" : '')
            . ($region ? " in {$region}" : '')
            . ($services ? ", with a focus on " . $this->humanList(array_slice($services, 0, 3)) : '')
            . ". Here is a tailored plan from Alpha Health Group.";

        // Custom plan narrative woven from THEIR specific answers.
        $plan = "To " . Str::lower($intent) . ($type ? " ({$type})" : '') . ($region ? " in {$region}" : '') . ", "
            . "Alpha begins with a focused discovery of your current position and target outcome, then builds a standards-aligned roadmap"
            . ($services ? " centred on " . $this->humanList(array_slice($services, 0, 4)) : "")
            . ". ";
        if ($freeText !== '') {
            $plan .= "Specific to what you described — \"" . Str::limit($freeText, 160) . "\" — we shape the workstreams, owners and milestones so each requirement is addressed and evidenced. ";
        }
        $plan .= "A dedicated account manager coordinates the right specialist teams end-to-end, so you have a single, accountable partner from planning through to a sustained result.";

        $regulatory = $this->regulatoryPathway($region, $type, $intent, $services);

        [$cost, $timeline] = $this->rulesCostTimeline($answers, $guidance);

        $recNames = collect($this->rulesRecommend($answers))->pluck('service_id')
            ->pipe(fn ($ids) => collect($this->catalog())->whereIn('id', $ids->all())->pluck('name'))->take(4)->all();
        $alphaOffer = "Alpha Health Group has supported facilities across the UAE and wider GCC through exactly this kind of programme. "
            . ($recNames ? "For your project we would bring our " . $this->humanList($recNames) . " teams to bear, " : "We assign experienced specialists to each objective, ")
            . "combining regulatory know-how with hands-on delivery so your goal is reached with less risk and fewer surprises.";

        return [
            'summary'     => $summary,
            'plan'        => $plan,
            'regulatory'  => $regulatory,
            'phases'      => $this->defaultPhases($answers),
            'timeline'    => $timeline,
            'cost'        => $costWanted ? $cost : '',
            'alpha_offer' => $alphaOffer,
            'recommended' => $this->rulesRecommend($answers),
        ];
    }

    /** Map the client's location + facility type to the relevant regulatory pathway (rules mode). */
    private function regulatoryPathway(?string $region, ?string $type, string $intent, array $services): string
    {
        $r = Str::lower((string) $region);
        $authority = 'the Ministry of Health and Prevention (MOHAP)';
        if (Str::contains($r, 'dubai')) {
            $authority = Str::contains($r, 'healthcare city') || Str::contains($r, 'dhcc')
                ? 'the Dubai Healthcare City Authority (DHCA/DHCR)'
                : 'the Dubai Health Authority (DHA)';
        } elseif (Str::contains($r, 'abu dhabi')) {
            $authority = 'the Department of Health – Abu Dhabi (DoH)';
        } elseif (Str::contains($r, ['sharjah', 'ajman', 'ras al khaimah', 'rak', 'fujairah', 'umm al', 'uaq', 'northern'])) {
            $authority = 'the Ministry of Health and Prevention (MOHAP)';
        } elseif (Str::contains($r, ['saudi', 'ksa', 'riyadh', 'jeddah'])) {
            $authority = 'the Saudi Ministry of Health (with CBAHI accreditation)';
        } elseif (Str::contains($r, 'qatar')) {
            $authority = "Qatar's Ministry of Public Health (MoPH / DHP)";
        } elseif (Str::contains($r, ['oman', 'muscat'])) {
            $authority = "Oman's Ministry of Health";
        } elseif (Str::contains($r, ['bahrain'])) {
            $authority = 'the National Health Regulatory Authority (NHRA), Bahrain';
        } elseif (Str::contains($r, ['kuwait'])) {
            $authority = "Kuwait's Ministry of Health";
        }

        $blob = Str::lower($intent . ' ' . implode(' ', $services));
        $accredits = Str::contains($blob, ['accredit', 'jci', 'jawda', 'quality', 'standard'])
            ? " Alongside licensing, we map the accreditation route (e.g. JCI" . (Str::contains($r, 'abu dhabi') ? ' and JAWDA' : '') . ") and align your policies, KPIs and evidence to the required standards."
            : "";

        $typeNote = $type ? " for a {$type}" : '';
        return "Your project{$typeNote}" . ($region ? " in {$region}" : '') . " falls under {$authority}. "
            . "We define the exact licensing pathway — facility registration, scope of services, staffing and Hijri/CPD-compliant credentialing, fit-out and inspection readiness — and manage the submission and follow-up so approvals stay on schedule." . $accredits;
    }

    /** Derive cost & timeline lines from the admin "plan_details" guidance (rules mode). */
    private function rulesCostTimeline(array $answers, array $guidance): array
    {
        $blocks = trim(implode("\n", $guidance));
        $cost = 'Indicative only and subject to a detailed scope — our team confirms figures after a short discovery call.';
        $timeline = 'Most engagements run in clear phases; we share a realistic schedule once your scope is confirmed.';

        // Pull "Timeline:" / "Cost:" hints out of the admin guidance, each stopping at the
        // next label so the cost text never bleeds into the timeline (or vice versa).
        if (preg_match('/timeline\s*:\s*(.*?)(?=\b(?:cost|budget)\s*:|$)/is', $blocks, $m) && trim($m[1]) !== '') {
            $timeline = trim($m[1]);
        }
        if (preg_match('/(?:cost|budget)\s*:\s*(.*?)(?=\btimeline\s*:|$)/is', $blocks, $m) && trim($m[1]) !== '') {
            $cost = trim($m[1]);
        }
        // Trim a trailing internal note ("Position Alpha as…") that isn't client-facing.
        $cost = trim(preg_replace('/\.\s*Position Alpha\b.*$/is', '.', $cost));
        $timeline = trim(preg_replace('/\.\s*Position Alpha\b.*$/is', '.', $timeline));
        return [Str::limit($cost, 400, ''), Str::limit($timeline, 400, '')];
    }

    private function defaultPhases(array $answers): array
    {
        $kb = $this->processKnowledge();
        $hint = '';
        foreach (array_merge((array) ($answers['selected_services'] ?? []), [$answers['intent'] ?? '']) as $needle) {
            foreach ($kb as $name => $detail) {
                if ($needle && Str::contains(Str::lower((string) $needle), explode(' ', $name)[0])) { $hint = $detail; break 2; }
            }
        }
        return [
            ['title' => 'Research', 'detail' => 'We run point-based evaluations across the required services to find gaps between your current practice and best practice' . ($hint ? ' — e.g. ' . Str::limit($hint, 120) : '') . '.'],
            ['title' => 'Plan', 'detail' => 'A dedicated account manager builds a customised, standards-aligned plan and assigns the right specialist teams to each objective.'],
            ['title' => 'Execute', 'detail' => 'Our specialists deliver each task with continuous monitoring of short-term goals through to completion.'],
            ['title' => 'Results', 'detail' => 'Outcomes are reviewed against your initial baseline to measure success, then sustained with ongoing improvement.'],
        ];
    }

    private function rulesRecommend(array $answers): array
    {
        $blob = trim(
            ($answers['intent'] ?? '') . ' ' . ($answers['facility_type'] ?? '') . ' ' .
            implode(' ', (array) ($answers['selected_services'] ?? [])) . ' ' . ($answers['free_text'] ?? '')
        );
        $ids = $this->matchServiceIds($blob, 5);
        if (!$ids) {
            $ids = collect($this->catalog())->take(5)->pluck('id')->all();
        }
        return collect($ids)->map(fn ($id) => ['service_id' => $id, 'reason' => 'Aligned with your stated needs.'])->all();
    }

    /** Keyword-overlap match of free text against the catalogue; returns top service ids. */
    private function matchServiceIds(string $text, int $limit): array
    {
        $stop = ['the','and','for','with','our','your','you','are','from','that','this','have','need','want','will','into','a','to','of','in','on','we','i'];
        $tokens = collect(preg_split('/[^a-z0-9]+/', Str::lower($text)))
            ->filter(fn ($t) => strlen($t) >= 3 && !in_array($t, $stop, true))->unique()->values();
        if ($tokens->isEmpty()) return [];

        return collect($this->catalog())->map(function ($s) use ($tokens) {
            $hay = Str::lower($s['name'] . ' ' . $s['overview'] . ' ' . implode(' ', $s['categories']));
            $score = $tokens->sum(fn ($t) => Str::contains($hay, $t) ? 1 : 0);
            return ['id' => $s['id'], 'score' => $score];
        })->filter(fn ($r) => $r['score'] > 0)
          ->sortByDesc('score')->take($limit)->pluck('id')->all();
    }

    private function sanitizeIds($ids): array
    {
        $valid = collect($this->catalog())->pluck('id')->all();
        return collect((array) $ids)->map(fn ($i) => (int) $i)->filter(fn ($i) => in_array($i, $valid, true))->unique()->values()->all();
    }

    private function humanList(array $items): string
    {
        $items = array_values($items);
        if (count($items) <= 1) return $items[0] ?? '';
        $last = array_pop($items);
        return implode(', ', $items) . ' and ' . $last;
    }

    /** Hydrate recommended ids into displayable service cards. */
    public function hydrateServices(array $ids): array
    {
        if (!$ids) return [];
        $services = DB::table('services')->where('status', 'published')->whereIn('id', $ids)
            ->get(['id', 'name', 'slug', 'overview']);
        return collect($ids)->map(function ($id) use ($services) {
            $s = $services->firstWhere('id', $id);
            return $s ? [
                'id' => $s->id, 'name' => $s->name, 'slug' => $s->slug,
                'overview' => Str::limit(trim(strip_tags((string) $s->overview)), 160),
            ] : null;
        })->filter()->values()->all();
    }
}
