<?php

namespace App\Http\Controllers;

use App\Models\ProjectPlannerSession;
use App\Models\User;
use App\Services\ProjectPlannerAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminPlannerController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->get('q', ''));
        $from   = $request->get('from');
        $to     = $request->get('to');
        $goal   = $request->get('goal');
        $status = $request->get('status');

        $query = ProjectPlannerSession::query();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                foreach (['name', 'email', 'phone', 'intent', 'region', 'facility_type', 'free_text'] as $col) {
                    $w->orWhere($col, 'like', "%{$q}%");
                }
            });
        }
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to)   $query->whereDate('created_at', '<=', $to);
        if ($goal && $goal !== 'all')     $query->where('intent', $goal);
        if ($status && $status !== 'all') $query->where('status', $status);

        $sessions = $query->latest()->paginate(20)->withQueryString();

        // Distinct goals (step-1 intent) to drive the goal filter dropdown.
        $goals = ProjectPlannerSession::whereNotNull('intent')->where('intent', '!=', '')
            ->distinct()->orderBy('intent')->pluck('intent');

        return view('dashboard.planner.index', compact('sessions', 'goals', 'q', 'from', 'to', 'goal', 'status'));
    }

    public function show($id, ProjectPlannerAI $ai)
    {
        $session = ProjectPlannerSession::with('staff')->findOrFail($id);
        $brief = json_decode((string) $session->brief, true) ?: ['summary' => '', 'phases' => []];
        $services = $ai->hydrateServices((array) $session->recommended_service_ids);
        $staffList = User::orderBy('first_name')->get();
        return view('dashboard.planner.show', compact('session', 'brief', 'services', 'staffList'));
    }

    /** Confirm a requested consultation and email the client (cc the chosen staff). */
    public function confirmMeeting(Request $request, $id)
    {
        $data = $request->validate([
            'meeting_at'       => 'required|date',
            'meeting_link'     => 'nullable|url',
            'calendar_link'    => 'nullable|url',
            'meeting_staff_id' => 'nullable|exists:users,id',
        ]);

        $session = ProjectPlannerSession::findOrFail($id);
        if (!$session->email) {
            return back()->with('error', 'This planner session has no client email to send to.');
        }

        $staff = !empty($data['meeting_staff_id']) ? User::find($data['meeting_staff_id']) : null;
        $session->update([
            'meeting_at'        => $data['meeting_at'],
            'meeting_link'      => $data['meeting_link'] ?? null,
            'calendar_link'     => $data['calendar_link'] ?? null,
            'meeting_staff_id'  => $data['meeting_staff_id'] ?? null,
            'meeting_confirmed' => true,
            'status'            => 'contacted',
        ]);

        try {
            $when = \Illuminate\Support\Carbon::parse($session->meeting_at)->format('l, M d, Y · h:i A');
            $staffName = $staff ? trim($staff->first_name . ' ' . $staff->last_name) : null;
            $body = "Hi {$session->name},\n\n"
                . "Great news — your consultation with Alpha Health Group is confirmed.\n\n"
                . "Date & time: {$when}\n"
                . ($session->meeting_link ? "Join the meeting: {$session->meeting_link}\n" : '')
                . ($session->calendar_link ? "Add to your calendar: {$session->calendar_link}\n" : '')
                . ($staffName ? "{$staffName} from our team will be joining you.\n" : '')
                . "\nIf you need to reschedule, just reply to this email.\n\n"
                . "We look forward to speaking with you,\nAlpha Health Group";

            Mail::raw($body, function ($m) use ($session, $staff) {
                $m->to($session->email)->subject('Your consultation is confirmed — Alpha Health Group');
                if ($staff && $staff->email) {
                    $m->cc($staff->email);
                }
            });
        } catch (\Throwable $e) {
            Log::warning('Meeting confirm mail failed', ['msg' => $e->getMessage()]);
            return back()->with('success', 'Meeting saved — but the confirmation email could not be sent (check mail settings).');
        }

        return back()->with('success', 'Consultation confirmed and a confirmation email was sent to the client.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:new,contacted,closed']);
        ProjectPlannerSession::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        ProjectPlannerSession::findOrFail($id)->delete();
        return back()->with('success', 'Planner session deleted.');
    }
}
