<?php

namespace App\Http\Controllers;

use App\Models\globaltag;
use Illuminate\Http\Request;

class globalController extends Controller
{
    // List all global tags
    public function globaltag()
    {
        $globaltags = globaltag::all();
        return view('dashboard.global_Tag.globaltag', compact('globaltags'));
    }

    

    // Store new global tag
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'tags' => 'required|string',
        ]);

        $globaltag = globaltag::create([
            'globaltag_name' => $request->name,
            'tags'           => html_entity_decode($request->tags),
        ]);

        return response()->json([
            'message' => 'Global tag added successfully!',
            'data'    => [
                'id'          => $globaltag->id,
                'globaltag_name' => $globaltag->globaltag_name,
                'tags'        => $globaltag->tags,
            ],
        ]);
    }

    // Get single global tag (for edit modal)
    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:globaltags,id',
        ]);

        $globaltag = globaltag::findOrFail($request->id);

        return response()->json([
            'data' => [
                'id'          => $globaltag->id,
                'globaltag_name' => $globaltag->globaltag_name,
                'tags'        => $globaltag->tags,
            ],
        ]);
    }

    // Update global tag
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:255',
            'tags' => 'required|string',
        ]);

        $globaltag = globaltag::findOrFail($id);

        $globaltag->update([
            'globaltag_name' => $request->name,
            'tags'           => html_entity_decode($request->tags),
        ]);

        return response()->json([
            'message' => 'Global tag updated successfully!',
            'data'    => [
                'id'          => $globaltag->id,
                'globaltag_name' => $globaltag->globaltag_name,
                'tags'        => $globaltag->tags,
            ],
        ]);
    }

    // Delete global tag
    public function destroy($id)
    {
        $globaltag = globaltag::findOrFail($id);
        $globaltag->delete();

        return response()->json([
            'message' => 'Global tag deleted successfully!',
        ]);
    }
}