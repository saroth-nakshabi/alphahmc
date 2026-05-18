<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $data = [];
        $data['tags'] = Tag::all();
        return view('dashboard.blogs.tags', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $item = Tag::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $item = Tag::findOrFail($id);
        $item->update([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    public function destroy($id)
    {
        $item = Tag::findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getTag(Request $request)
    {
        $id = $request->input('id');
        $item = Tag::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }
}