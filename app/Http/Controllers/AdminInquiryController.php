<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminInquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::with('service')->latest();

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $inquiries = $query->paginate(20)->withQueryString();

        $services = Service::orderBy('name')->get(['id', 'name']);

        $stats = [
            'total'   => Inquiry::count(),
            'pending' => Inquiry::where('status', 'pending')->orWhereNull('status')->count(),
            'replied' => Inquiry::where('status', 'replied')->count(),
            'closed'  => Inquiry::where('status', 'closed')->count(),
        ];

        return view('dashboard.inquiries.index', compact('inquiries', 'services', 'stats'));
    }

    public function show($id)
    {
        $inquiry = Inquiry::with('service')->findOrFail($id);
        return view('dashboard.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['reply_message' => 'required|string|max:5000']);

        $inquiry = Inquiry::findOrFail($id);

        $history   = $inquiry->reply_history ?? [];
        $history[] = [
            'message' => $request->reply_message,
            'sent_at' => now()->toISOString(),
        ];

        $inquiry->update([
            'reply_history' => $history,
            'status'        => 'replied',
        ]);

        try {
            \Mail::raw($request->reply_message, function ($msg) use ($inquiry) {
                $msg->to($inquiry->email, $inquiry->name)
                    ->subject('Re: Your Inquiry – Alpha Health Group');
            });
            return back()->with('success', 'Reply sent to ' . $inquiry->email);
        } catch (\Exception $e) {
            \Log::error('Inquiry reply email failed: ' . $e->getMessage());
            return back()->with('success', 'Reply saved (email delivery failed — check mail config).');
        }
    }

    public function destroy($id)
    {
        Inquiry::findOrFail($id)->delete();
        return back()->with('success', 'Inquiry deleted.');
    }
}
