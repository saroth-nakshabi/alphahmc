<?php

namespace App\Http\Controllers;

use App\Models\AboutStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutStaffController extends Controller
{
    public function index()
    {
        $staff = AboutStaff::orderBy('sort_order')->orderBy('id')->get();
        return view('dashboard.About_us.staff', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'title'             => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'image'             => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $item = AboutStaff::create([
            'name'              => $request->name,
            'title'             => $request->title,
            'short_description' => $request->short_description,
            'image'             => $this->uploadImage($request),
            'sort_order'        => (int) AboutStaff::max('sort_order') + 1,
        ]);

        return response()->json(['message' => 'Staff member added', 'data' => $item]);
    }

    public function get(Request $request)
    {
        $item = AboutStaff::find($request->id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = AboutStaff::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'title'             => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($item->image);
            $item->image = $this->uploadImage($request);
        }

        $item->name              = $request->name;
        $item->title             = $request->title;
        $item->short_description = $request->short_description;
        $item->save();

        return response()->json(['message' => 'Staff member updated', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = AboutStaff::findOrFail($id);
        $this->deleteImage($item->image);
        $item->delete();

        return response()->json(['message' => 'Staff member deleted']);
    }

    /** Persist drag-and-drop order. */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:about_staff,id',
        ]);

        foreach ($request->order as $position => $id) {
            AboutStaff::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order saved!']);
    }

    private function uploadImage(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }
        $file = $request->file('image');
        $name = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/about_staff'), $name);
        return $name;
    }

    private function deleteImage(?string $image): void
    {
        if ($image && file_exists(public_path('uploads/about_staff/' . $image))) {
            @unlink(public_path('uploads/about_staff/' . $image));
        }
    }
}
