<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeSliderController extends Controller
{
    public function index()
    {
        $data = [];
        $data['sliders'] = HomeSlider::all();
        return view('dashboard.sliders.home', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            // 'post_title' => 'max:255',
            'pre_title' => 'required|string|max:255',
            'button_text' => 'max:255',
            'button_link' => 'max:255',
            'status' => 'in:active,inactive',
            // 'button_link' => 'url',
        ]);

        // Handle image uploads
        $image = $request->file('image');
        if (isset($image)) {
            // $filePath = $image->store('product_images', 'public/uploads'); // Save to the 'public/uploads/product_images' directory
            $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/slider_images'), $image_name);

            $item = HomeSlider::create([
                'image' => $image_name,
                'main_title' => $request->input('main_title'),
                // 'post_title' => $request->input('post_title'),
                'pre_title' => $request->input('pre_title'),
                'button_text' => $request->input('button_text'),
                'button_link' => $request->input('button_link'),
                'status' => $request->input('status') ?? 'inactive',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Created successfully!',
                'data' => $item,
            ], 201);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'main_title' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif', // Image is optional during update
            'pre_title' => 'required|string|max:255',
            'button_text' => 'max:255',
            'button_link' => 'max:255',
            'status' => 'in:active,inactive',
        ]);

        $item = HomeSlider::findOrFail($id);
        $item->update([
            'main_title' => $request->input('main_title'),
            'pre_title' => $request->input('pre_title'),
            'button_text' => $request->input('button_text'),
            'button_link' => $request->input('button_link'),
            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('image')) : $item->image,
            'status' => $request->input('status') ?? 'inactive',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    private function uploadImage($image)
    {
        $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/slider_images'), $image_name);
        return $image_name;
    }

    public function destroy($id)
    {
        $item = HomeSlider::findOrFail($id);

        $imagePath = public_path('uploads/slider_images/' . $item->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getSlider(Request $request)
    {
        $id = $request->input('id');
        $item = HomeSlider::findOrFail($id);

        $data = $item->toArray();
        $data['image_url'] = $item->image
            ? asset('public/uploads/slider_images/' . $item->image)
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $data,
        ], 201);
    }

    public function statusHandle(Request $request)
    {

        $id = $request->input('id');
        $status = $request->input('status');

        $item = HomeSlider::findOrFail($id);
        $item->update([
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

}