<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Inquiry;

class AdminInquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with('service')->latest()->paginate(15);
        return view('dashboard.inquiries.index', compact('inquiries'));
    }

    public function update(Request $request, $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->update(['status' => $request->status]);
        return back()->with('success', 'Inquiry status updated.');
    }

    public function destroy($id)
    {
        Inquiry::findOrFail($id)->delete();
        return back()->with('success', 'Inquiry deleted.');
    }
}