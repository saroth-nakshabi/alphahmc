<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\about_content;

class About_UsContentController extends Controller
{
    // INDEX
    public function index()
    {
        $about_us = about_content::latest()->get();
        return view('dashboard.About_us.content', compact('about_us'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'content_title' => 'required|max:255',
            'content_text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload Image
        $image_name = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about_content'), $image_name);
        }

        // Correct column name
        $item = about_content::create([
            'content_title' => $request->content_title, // matches database
            'content' => $request -> content_text,
            'image' => $image_name,
        ]);

        return response()->json([
            'message' => 'Successfully added',
            'data' => $item
        ]);
    }

    // GET (for edit)
    public function get(Request $request)
    {
        $item = about_content::find($request->id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['data' => $item]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $item = about_content::findOrFail($id);

        $request->validate([
            'content_title' => 'required|max:255',
            'content_text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update Image
        if ($request->hasFile('image')) {
            if ($item->image && file_exists(public_path('uploads/about_content/' . $item->image))) {
                unlink(public_path('uploads/about_content/' . $item->image));
            }
            $image = $request->file('image');
            $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/about_content'), $image_name);
            $item->image = $image_name;
        }

        // Correct column name
        $item->content_title = $request->content_title;
        $item->content = $request->content_text;
        $item->save();

        return response()->json([
            'message' => 'Successfully updated',
            'data' => $item
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $item = about_content::findOrFail($id);

        if ($item->image && file_exists(public_path('uploads/about_content/' . $item->image))) {
            unlink(public_path('uploads/about_content/' . $item->image));
        }

        $item->delete();

        return response()->json(['message' => 'Successfully deleted']);
    }
}
