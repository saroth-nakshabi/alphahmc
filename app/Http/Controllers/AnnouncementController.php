<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all();
        return view('dashboard.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('dashboard.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/announcements'), $imageName);
            $data['image'] = $imageName;
        }

        $data['status'] = $request->input('status') ? 1 : 0;
        $data['feature'] = $request -> input('feature') ? 1 : 0;

        Announcement::create($data);

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('dashboard.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($announcement->image && file_exists(public_path('uploads/announcements/' . $announcement->image))) {
                unlink(public_path('uploads/announcements/' . $announcement->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/announcements'), $imageName);
            $data['image'] = $imageName;
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['feature'] = $request->has('feature') ? 1 : 0;

        $announcement->update($data);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image && file_exists(public_path('uploads/announcements/' . $announcement->image))) {
            unlink(public_path('uploads/announcements/' . $announcement->image));
        }
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
