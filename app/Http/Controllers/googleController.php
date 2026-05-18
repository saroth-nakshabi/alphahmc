<?php

namespace App\Http\Controllers;

use App\Models\googletag;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;

class googleController extends Controller
{
    /**
     * Display all Google tags.
     */
    public function googletag()
    {
        $googletags = googletag::all();
        return view('dashboard.Google_Tag.googletag', compact('googletags'));
    }

    /**
     * Store a new Google tag.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tags' => 'required|string',
            'noscript_tags' => 'required|string',
        ]);

        $googletag = googletag::create([
            'googletag_name' => $validated['name'],
            'tags'           => html_entity_decode($validated['tags']),
            'noscript_tags'  => html_entity_decode($validated['noscript_tags']),
        ]);

        return response()->json([
            'message' => 'Google tag added successfully!',
            'data'    => [
                'id'             => $googletag->id,
                'googletag_name' => $googletag->googletag_name,
                'tags'           => $googletag->tags,
                'noscript_tags'  => $googletag->noscript_tags,
            ],
        ], 201);
    }

    /**
     * Get a single Google tag (for edit modal).
     */
    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:googletags,id',
        ]);

        $googletag = googletag::findOrFail($request->id);

        return response()->json([
            'data' => [
                'id'             => $googletag->id,
                'googletag_name' => $googletag->googletag_name,
                'tags'           => $googletag->tags,
                'noscript_tags'  => $googletag->noscript_tags,
            ],
        ]);
    }

    /**
     * Update an existing Google tag.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'tags' => 'required|string',
            'noscript_tags' => 'nullable|string',
        ]);

        $googletag = googletag::findOrFail($id);

        $googletag->update([
            'googletag_name' => $validated['name'],
            'tags'           => html_entity_decode($validated['tags']),
            'noscript_tags'  => $validated['noscript_tags'] ?? null,
        ]);

        return response()->json([
            'message' => 'Google tag updated successfully!',
            'data'    => [
                'id'             => $googletag->id,
                'googletag_name' => $googletag->googletag_name,
                'tags'           => $googletag->tags,
                'noscript_tags'  => $googletag->noscript_tags,
            ],
        ]);
    }

    /**
     * Delete a Google tag.
     */
    public function destroy($id)
    {
        googletag::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Google tag deleted successfully!',
        ]);
    }
}