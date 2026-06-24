<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProjectPlannerSession;
use App\Services\ProjectPlannerAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProjectPlannerController extends Controller
{
    public function __construct(private ProjectPlannerAI $ai) {}

    /** Standalone wizard page — steps come from the dashboard Planner Builder. */
    public function page()
    {
        return view('front.plan-your-project', [
            'steps'              => $this->ai->customerSteps(),
            'categoryServicesMap' => $this->ai->categoryServicesMap(),
            'aiEnabled'          => $this->ai->enabled(),
            'contactTiming'      => \App\Models\AppSetting::get('planner_contact_timing', 'before'),
            'showRaw'            => \App\Models\AppSetting::bool('planner_show_raw', false),
        ]);
    }

    /** AJAX: return service names filtered by a selected category. */
    public function servicesByCategory(Request $request)
    {
        $category = trim((string) $request->get('category', ''));
        $map = $this->ai->categoryServicesMap();
        $services = [];
        if ($category && isset($map[$category])) {
            $services = $map[$category];
        } else {
            foreach ($map as $names) {
                foreach ($names as $n) { if (!in_array($n, $services, true)) $services[] = $n; }
            }
        }
        if (!in_array('Not sure', $services, true)) $services[] = 'Not sure';
        return response()->json(['services' => array_values($services)]);
    }

    /** Light, instant per-step acknowledgement (local, no LLM cost). */
    public function step(Request $request)
    {
        $data = $request->validate([
            'step'  => 'required|string|max:60',
            'value' => 'nullable|string|max:400',
        ]);

        return response()->json([
            'understand' => $this->acknowledge($data),
        ]);
    }

    private function acknowledge(array $d): string
    {
        $val = trim((string) ($d['value'] ?? ''));
        switch ($d['step']) {
            case 'scope':
                return $val ? "Great — we'll tailor this around your goal to " . Str::lower($val) . "." : "Tell us your goal and we'll shape the plan around it.";
            case 'location':
                return $val ? "Noted — {$val}. We'll align to the local health authority's requirements there." : "We work across the UAE and wider GCC.";
            case 'category':
                return $val ? "Good — we'll prioritise " . Str::lower($val) . " and bring in the right specialists." : "Pick the areas you need help with.";
            case 'service':
                return $val ? "Noted those services. We'll factor them into your plan." : "Pick any services that are relevant — optional.";
            default:
                return $val ? "Got it — " . Str::limit($val, 60) . "." : "Got it.";
        }
    }

    /** Free-text solve + final brief + recommendations; persists the session. */
    public function analyze(Request $request)
    {
        $data = $request->validate([
            'answers'   => 'nullable|array',
            // Optional contact (sent up-front when the "contact before results" flow is on)
            'name'    => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'consent' => 'nullable|boolean',
            'meeting_date' => 'nullable|date',
            'meeting_time' => 'nullable|string|max:10',
        ]);

        $meetingAt = null;
        if (!empty($data['meeting_date'])) {
            $meetingAt = trim($data['meeting_date'] . ' ' . ($data['meeting_time'] ?? '00:00'));
        }

        // Normalise the dynamic, keyed answers into the engine's context.
        $answers = (array) ($data['answers'] ?? []);
        $flat = fn ($v) => is_array($v) ? implode(', ', array_filter($v)) : trim((string) $v);

        $selected = collect();
        foreach (['category', 'service'] as $k) {
            if (isset($answers[$k])) $selected = $selected->merge((array) $answers[$k]);
        }
        // Any other multichoice/custom steps that returned arrays also feed recommendations.
        foreach ($answers as $k => $v) {
            if (is_array($v) && !in_array($k, ['category', 'service'], true)) $selected = $selected->merge($v);
        }

        $ctx = [
            'intent'            => $flat($answers['scope'] ?? '') ?: null,
            'region'            => $flat($answers['location'] ?? '') ?: null,
            'facility_type'     => $flat($answers['facility'] ?? $answers['facility_type'] ?? '') ?: null,
            'selected_services' => $selected->map(fn ($x) => trim((string) $x))->filter()->unique()->values()->all(),
            'free_text'         => $flat($answers['details'] ?? $answers['free_text'] ?? '') ?: null,
        ];

        // One AI call does the lot — the custom plan, regulatory pathway, phases,
        // timeline, cost (only if asked) and service recommendations.
        $costWanted = $this->ai->costRequested($ctx);
        $brief = $this->ai->finalBrief($ctx);

        $recIds = collect($brief['recommended'])->pluck('service_id')->unique()->values()->all();
        $reasons = collect($brief['recommended'])->mapWithKeys(fn ($r) => [$r['service_id'] => $r['reason']]);
        $cards = collect($this->ai->hydrateServices($recIds))
            ->map(fn ($c) => $c + ['reason' => $reasons[$c['id']] ?? ''])->values()->all();

        $engine = $brief['engine'] === 'ai' ? 'ai' : 'rules';

        $aiProcessPayload = json_encode([
            'plan'        => $brief['plan'] ?? '',
            'regulatory'  => $brief['regulatory'] ?? '',
            'phases'      => $brief['phases'],
            'timeline'    => $brief['timeline'] ?? '',
            'cost'        => $costWanted ? ($brief['cost'] ?? '') : '',
            'alpha_offer' => $brief['alpha_offer'] ?? '',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $session = ProjectPlannerSession::create([
            'uuid'                    => (string) Str::uuid(),
            'intent'                  => $ctx['intent'],
            'region'                  => $ctx['region'],
            'facility_type'           => $ctx['facility_type'],
            'selected_services'       => $ctx['selected_services'],
            'free_text'               => $ctx['free_text'],
            'answers'                 => $answers,
            'ai_solution'             => $brief['plan'] ?? null,
            'brief'                   => json_encode([
                'summary'     => $brief['summary'],
                'plan'        => $brief['plan'] ?? '',
                'regulatory'  => $brief['regulatory'] ?? '',
                'phases'      => $brief['phases'],
                'alpha_offer' => $brief['alpha_offer'] ?? '',
            ]),
            'cost_estimate'           => $costWanted ? ($brief['cost'] ?? null) : null,
            'timeline_estimate'       => $brief['timeline'] ?? null,
            'recommended_service_ids' => collect($cards)->pluck('id')->all(),
            'engine'                  => $engine,
            'process_source'          => $brief['process_source'] ?? 'ai_generated',
            'ai_process_output'       => $aiProcessPayload,
            'name'                    => $data['name'] ?? null,
            'email'                   => $data['email'] ?? null,
            'phone'                   => $data['phone'] ?? null,
            'consent'                 => $request->boolean('consent'),
            'meeting_at'              => $meetingAt,
            'status'                  => !empty($data['email']) ? 'contacted' : 'new',
        ]);

        // If contact was captured up-front, mirror it into Service Inquiries + email the plan.
        if (!empty($data['email'])) {
            $this->createInquiry($session, $brief, $cards);
            $this->notifyLead($session, $cards);
        }

        return response()->json([
            'uuid'        => $session->uuid,
            'summary'     => $brief['summary'],
            'plan'        => $brief['plan'] ?? '',
            'regulatory'  => $brief['regulatory'] ?? '',
            'phases'      => $brief['phases'],
            'timeline'    => $brief['timeline'] ?? '',
            'cost'        => $costWanted ? ($brief['cost'] ?? '') : '',
            'cost_requested' => $costWanted,
            'alpha_offer' => $brief['alpha_offer'] ?? '',
            'services'    => $cards,
            'engine'      => $engine,
            // Raw model output — exposed only when the admin enables the toggle in Settings.
            'raw'         => \App\Models\AppSetting::bool('planner_show_raw', false) ? $this->ai->lastRaw() : null,
        ]);
    }

    /** Build the Service-Inquiry message body from a planner session. */
    private function buildInquiryMessage(ProjectPlannerSession $session, array $brief, array $cards): string
    {
        $services = collect($cards)->pluck('name')->take(6)->implode(', ');
        return "Submitted via Alpha Blueprint AI (Project Planner).\n"
            . 'Goal: ' . ($session->intent ?? '-') . "\n"
            . 'Region: ' . ($session->region ?? '-') . "\n"
            . 'Facility: ' . ($session->facility_type ?? '-') . "\n"
            . 'Service areas: ' . implode(', ', (array) $session->selected_services) . "\n"
            . ($session->meeting_at ? 'Requested consultation: ' . $session->meeting_at . "\n" : '')
            . 'Consent to be contacted: ' . ($session->consent ? 'Yes' : 'No') . "\n\n"
            . 'Their challenge: ' . ($session->free_text ?: '—') . "\n\n"
            . 'Plan summary: ' . ($brief['summary'] ?? '') . "\n"
            . ($services ? "Recommended services: {$services}" : '');
    }

    /** Mirror a planner lead into the Service Inquiries CRM and link it back. */
    private function createInquiry(ProjectPlannerSession $session, array $brief, array $cards): void
    {
        try {
            $inquiry = \App\Models\Inquiry::create([
                'name'       => $session->name,
                'email'      => $session->email,
                'phone'      => $session->phone,
                'service_id' => collect($cards)->pluck('id')->first(),
                'message'    => $this->buildInquiryMessage($session, $brief, $cards),
                'status'     => 'pending',
            ]);
            $session->update(['inquiry_id' => $inquiry->id]);
        } catch (\Throwable $e) {
            Log::warning('Planner→Inquiry create failed', ['msg' => $e->getMessage()]);
        }
    }

    /** Results-page follow-up: add consent / a meeting to an EXISTING lead — no re-asking name/email/phone,
     *  and edit the linked inquiry rather than creating a new one. */
    public function followup(Request $request)
    {
        $data = $request->validate([
            'uuid'         => 'required|string',
            'consent'      => 'nullable|boolean',
            'meeting_date' => 'nullable|date',
            'meeting_time' => 'nullable|string|max:10',
        ]);

        $session = ProjectPlannerSession::where('uuid', $data['uuid'])->first();
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }
        if (!$session->email) {
            return response()->json(['success' => false, 'message' => 'No contact details are on file for this plan.'], 422);
        }

        $bookedNow = !empty($data['meeting_date']);
        $meetingAt = $bookedNow
            ? trim($data['meeting_date'] . ' ' . ($data['meeting_time'] ?? '00:00'))
            : $session->meeting_at; // keep any previously requested time

        $session->update([
            'consent'    => $request->boolean('consent'),
            'meeting_at' => $meetingAt,
            'status'     => 'contacted',
        ]);

        // Edit the EXISTING inquiry (or create one only if somehow missing).
        $brief = json_decode((string) $session->brief, true) ?: ['summary' => '', 'phases' => []];
        $cards = $this->ai->hydrateServices((array) $session->recommended_service_ids);
        if ($session->inquiry_id && ($inquiry = \App\Models\Inquiry::find($session->inquiry_id))) {
            $inquiry->update([
                'message' => $this->buildInquiryMessage($session, $brief, $cards),
                'status'  => 'pending',
            ]);
        } else {
            $this->createInquiry($session, $brief, $cards);
        }
        $this->notifyLead($session, $cards);

        $message = $bookedNow
            ? 'Thank you! Your consultation request is in — our team will confirm the time shortly.'
            : 'Thank you! A consultant will reach out to you shortly.';
        return response()->json(['success' => true, 'message' => $message]);
    }

    /** Email the lead to admin + a plan copy to the customer (best-effort). */
    private function notifyLead(ProjectPlannerSession $session, array $cards): void
    {
        try {
            $admin    = config('mail.from.address') ?: 'nisath.alphatsm@gmail.com';
            $services = collect($cards)->pluck('name')->take(6)->implode(', ');
            $brief    = json_decode((string) $session->brief, true) ?: [];

            $adminBody = "New Alpha Blueprint AI lead\n\n"
                . "Name: {$session->name}\nEmail: {$session->email}\nPhone: {$session->phone}\n"
                . "Consent to be contacted: " . ($session->consent ? 'Yes' : 'No') . "\n"
                . ($session->meeting_at ? "Requested consultation: {$session->meeting_at}\n" : '')
                . "Goal: " . ($session->intent ?? '-') . " | Region: " . ($session->region ?? '-') . " | Facility: " . ($session->facility_type ?? '-') . "\n"
                . "Areas: " . implode(', ', (array) $session->selected_services) . "\n"
                . "Challenge: " . ($session->free_text ?? '-') . "\n"
                . "Recommended: {$services}\n";
            Mail::raw($adminBody, fn ($m) => $m->to($admin)->subject('New Alpha Blueprint AI lead — ' . $session->name));

            if ($session->email) {
                Mail::to($session->email)
                    ->send(new \App\Mail\PlannerConfirmationMail($session, $cards, $brief));
            }
        } catch (\Throwable $e) {
            Log::warning('Planner lead mail failed', ['msg' => $e->getMessage()]);
        }
    }

    /** Contact capture attached to a planner session ('after' flow) — full details + optional consultation. */
    public function contact(Request $request)
    {
        $data = $request->validate([
            'uuid'         => 'required|string',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:30',
            'consent'      => 'nullable|boolean',
            'meeting_date' => 'nullable|date',
            'meeting_time' => 'nullable|string|max:10',
        ]);

        $session = ProjectPlannerSession::where('uuid', $data['uuid'])->first();
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        $meetingAt = !empty($data['meeting_date'])
            ? trim($data['meeting_date'] . ' ' . ($data['meeting_time'] ?? '00:00'))
            : null;

        $session->update([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'consent'    => $request->boolean('consent'),
            'meeting_at' => $meetingAt ?: $session->meeting_at,
            'status'     => 'contacted',
        ]);

        // Mirror into Service Inquiries (once) + email the plan to client and admin.
        $brief = json_decode((string) $session->brief, true) ?: ['summary' => '', 'phases' => []];
        $cards = $this->ai->hydrateServices((array) $session->recommended_service_ids);
        if (!$session->inquiry_id) {
            $this->createInquiry($session, $brief, $cards);
        }
        $this->notifyLead($session, $cards);

        $message = $meetingAt
            ? "Thank you! Your blueprint is on its way and your consultation request is in — our team will confirm the time shortly."
            : "Thank you! Your blueprint is on its way and a consultant will reach out shortly.";

        return response()->json(['success' => true, 'message' => $message]);
    }
}
