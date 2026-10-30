<?php

namespace App\Http\Controllers;

use App\Models\RefClick;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * POST /api/ref-log (brief "alphahmc-ref-click-log"): the WhatsApp Ref script sends the visitor's Ref code
 * with its first-touch click data on every WhatsApp tap (navigator.sendBeacon). One row per Ref: the first
 * write sets the click fields, later writes only move last_seen_at, and a stored click ID is never replaced.
 * Without ad consent the row is kept with the click fields empty.
 */
class RefLogController extends Controller
{
    public function store(Request $request): Response
    {
        if (!$this->sameSite($request)) {
            return response('', 403);
        }

        $data = $request->validate([
            'ref'          => ['required', 'string', 'regex:' . RefClick::REF_PATTERN],
            'gclid'        => 'nullable|string|max:255',
            'gbraid'       => 'nullable|string|max:255',
            'wbraid'       => 'nullable|string|max:255',
            'utm_source'   => 'nullable|string|max:255',
            'utm_medium'   => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term'     => 'nullable|string|max:255',
            'utm_content'  => 'nullable|string|max:255',
            'landing_path' => 'nullable|string|max:255',
            'click_at'     => 'nullable|integer|min:0',
            'ad_consent'   => ['required', Rule::in(['granted', 'denied', 'not_required'])],
        ]);

        $now = now();
        $row = RefClick::where('ref', $data['ref'])->first();
        if ($row) {
            $row->update(['last_seen_at' => $now]);
        } else {
            $denied = $data['ad_consent'] === 'denied';
            $fields = [
                'ref'           => $data['ref'],
                'channel'       => RefClick::CHANNELS[substr($data['ref'], 0, 3)],
                'landing_path'  => isset($data['landing_path']) ? (parse_url($data['landing_path'], PHP_URL_PATH) ?: null) : null,
                'click_at'      => $denied ? null : $this->clickAt($data['click_at'] ?? null),
                'ad_consent'    => $data['ad_consent'],
                'first_seen_at' => $now,
                'last_seen_at'  => $now,
            ];
            foreach (RefClick::CLICK_FIELDS as $f) {
                $fields[$f] = $denied ? null : ($data[$f] ?? null ?: null);
            }
            foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'] as $f) {
                $fields[$f] = $data[$f] ?? null ?: null;
            }
            try {
                RefClick::create($fields);
            } catch (QueryException $e) {
                // Two taps at once: the other request created the row first; only touch it.
                RefClick::where('ref', $data['ref'])->update(['last_seen_at' => $now]);
            }
        }

        // No scheduler runs on the server, so expired rows are pruned now and then from here.
        if (random_int(1, 100) === 1) {
            RefClick::prune();
        }

        return response('', 204);
    }

    /** Only the site's own pages may write (sendBeacon sends Origin, or at least Referer, on a same-site POST). */
    private function sameSite(Request $request): bool
    {
        $from = $request->headers->get('Origin') ?: $request->headers->get('Referer');
        $host = $from ? parse_url($from, PHP_URL_HOST) : null;

        return $host !== null && strcasecmp(preg_replace('/^www\./i', '', $host), preg_replace('/^www\./i', '', $request->getHost())) === 0;
    }

    /** First-touch time in ms from the browser; ignored when it is not within the last 90 days. */
    private function clickAt($ms): ?Carbon
    {
        if (!$ms) {
            return null;
        }
        $at = Carbon::createFromTimestampMs((int) $ms);

        return $at->between(now()->subDays(90), now()->addHour()) ? $at : null;
    }
}
