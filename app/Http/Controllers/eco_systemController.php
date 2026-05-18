<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\about_eco;

class eco_systemController extends Controller
{
    // ✅ INDEX: List all eco systems
    public function index()
    {
        $about_systems = about_eco::latest()->get();
        return view('dashboard.About_us.Eco_System', compact('about_systems'));
    }

    // ✅ STORE: Add new eco system
    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|max:255',
            'title' => 'nullable|max:255',
            'description' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logo_name = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logo_name = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/about_eco'), $logo_name);
        }

        $item = about_eco::create([
            'heading' => $request->heading,
            'eco_sub_title' => $request->title,
            'description' => $request->description,
            'logo' => $logo_name,
        ]);

        return response()->json([
            'message' => 'Successfully added',
            'data' => $item
        ]);
    }

    // ✅ GET: Fetch single eco system for edit
    public function get(Request $request)
    {
        $item = about_eco::find($request->id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['data' => $item]);
    }

    // ✅ UPDATE: Update existing eco system
    public function update(Request $request, $id)
    {
        $item = about_eco::findOrFail($id);

        $request->validate([
            'heading' => 'required|max:255',
            'title' => 'nullable|max:255',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($item->logo && file_exists(public_path('uploads/about_eco/' . $item->logo))) {
                unlink(public_path('uploads/about_eco/' . $item->logo));
            }
            $logo = $request->file('logo');
            $logo_name = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/about_eco'), $logo_name);
            $item->logo = $logo_name;
        }

        $item->heading = $request->heading;
        $item->eco_sub_title = $request->title;
        $item->description = $request->description;
        $item->save();

        return response()->json([
            'message' => 'Successfully updated',
            'data' => $item
        ]);
    }

    // ✅ DELETE: Delete eco system
    public function destroy($id)
    {
        $item = about_eco::findOrFail($id);

        if ($item->logo && file_exists(public_path('uploads/about_eco/' . $item->logo))) {
            unlink(public_path('uploads/about_eco/' . $item->logo));
        }

        $item->delete();

        return response()->json(['message' => 'Successfully deleted']);
    }
}