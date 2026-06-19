<?php

namespace App\Http\Controllers;

use App\Models\AboutCounter;
use Illuminate\Http\Request;

class AboutCounterController extends Controller
{
    public function index()
    {
        $counters = AboutCounter::orderBy('sort_order')->orderBy('id')->get();
        return view('dashboard.About_us.counters', compact('counters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'value'  => 'required|integer|min:0',
            'suffix' => 'nullable|string|max:12',
            'label'  => 'required|string|max:255',
        ]);

        $item = AboutCounter::create([
            'value'      => (int) $request->value,
            'suffix'     => $request->suffix,
            'label'      => $request->label,
            'sort_order' => (int) AboutCounter::max('sort_order') + 1,
        ]);

        return response()->json(['message' => 'Successfully added', 'data' => $item]);
    }

    public function get(Request $request)
    {
        $item = AboutCounter::find($request->id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = AboutCounter::findOrFail($id);

        $request->validate([
            'value'  => 'required|integer|min:0',
            'suffix' => 'nullable|string|max:12',
            'label'  => 'required|string|max:255',
        ]);

        $item->update([
            'value'  => (int) $request->value,
            'suffix' => $request->suffix,
            'label'  => $request->label,
        ]);

        return response()->json(['message' => 'Successfully updated', 'data' => $item]);
    }

    public function destroy($id)
    {
        AboutCounter::findOrFail($id)->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }

    /** Persist drag-and-drop order. */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:about_counters,id',
        ]);

        foreach ($request->order as $position => $id) {
            AboutCounter::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order saved!']);
    }
}
