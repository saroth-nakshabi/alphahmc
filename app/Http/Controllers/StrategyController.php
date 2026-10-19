<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use Illuminate\Http\Request;

class StrategyController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data['strategies'] = Strategy::all();
        return view('dashboard.strategy.index', $data);
    }

    public function create(Request $request)
    {

        return view('dashboard.strategy.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image uploads
        $image = $request->file('image');
        $image_name = null;
        if (isset($image)) {
            $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/strategy_images'), $image_name);
        }

        Strategy::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $image_name ?? null,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Strategy created successfully.'
            ]);
        }

        return redirect()->route('strategy.index')->with('success', 'Strategy created successfully.');
    }

        public function edit($id)
        {
            $data = [];
            $data['strategy'] = Strategy::findOrFail($id);
            return view('dashboard.strategy.edit', $data);
        }

        public function update(Request $request, $id)
        {
            $strategy = Strategy::findOrFail($id);

            $request->validate([
                'name' => 'required|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle image uploads
            $image = $request->file('image');
            if (isset($image)) {
                $image_name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/strategy_images'), $image_name);
                // Delete old image if exists
                if ($strategy->image) {
                    @unlink(public_path('uploads/strategy_images/' . $strategy->image));
                }
                $strategy->image = $image_name;
            }

            $strategy->name = $request->input('name');
            $strategy->description = $request->input('description');
            $strategy->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Strategy updated successfully.'
                ]);
            }

            return redirect()->route('strategy.index')->with('success', 'Strategy updated successfully.');
        }

    public function destroy($id)
    {
        $strategy = Strategy::findOrFail($id);
        // Delete image if exists
        if ($strategy->image) {
            @unlink(public_path('uploads/strategy_images/' . $strategy->image));
            $strategy->image = null;
            $strategy->save();
            $strategy->delete();
            return response()->json([
                'success' => true,
                'message' => 'Strategy deleted successfully.',
            ]);
        } else {
            $strategy->delete();
            return response()->json([
                'success' => true,
                'message' => 'Strategy deleted successfully.',
            ]);
        }
    }
}
