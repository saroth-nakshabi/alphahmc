<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About_us;

class About_UsController extends Controller
{
    public function index()
    {
        $about_us = About_us::all();
        return view('dashboard.About_us.Hero', compact('about_us'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload logo
        $logo_name = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logo_name = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/about_us_logos'), $logo_name);
        }

        // Upload image
        $image_name = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about_us_images'), $image_name);
        }

        $item = About_us::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'logo' => $logo_name,
            'image' => $image_name,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Successfully added',
                'data' => $item
            ]);
        }

        return redirect()->route('about_us.index')->with('success', 'About Us entry created successfully.');
    }

    public function get(Request $request)
    {
        $item = About_us::find($request->id);
        if (!$item) return response()->json(['message' => 'Item not found'], 404);

        return response()->json(['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = About_us::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logo_name = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/about_us_logos'), $logo_name);
            $item->logo = $logo_name;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about_us_images'), $image_name);
            $item->image = $image_name;
        }

        $item->title = $request->input('title');
        $item->description = $request->input('description');
        $item->save();

        return response()->json([
            'message' => 'Successfully updated',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = About_us::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Successfully deleted']);
    }
}
