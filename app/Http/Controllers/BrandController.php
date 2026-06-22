<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('sort_order')->orderBy('id')->get();
        $brandHero = BrandHero::first();
        return view('dashboard.brands.index', compact('brands', 'brandHero'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:brands,id',
        ]);

        foreach ($request->order as $position => $id) {
            Brand::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order saved!']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'logo'        => 'required|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'address'     => 'required|string|max:255',
            'description' => 'required|string',
            'what_we_do'  => 'required|string',
            'google_location' => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
            'areaServed'       => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'address', 'description', 'what_we_do', 'google_location', 'meta_title', 'meta_description', 'meta_keywords', 'areaServed']);
        $data['slug'] = Str::slug($request->name);
        $data['sort_order'] = (int) Brand::max('sort_order') + 1; // new brands go to the end

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/brands'), $filename);
            $data['logo'] = $filename;
        }

        $brand = Brand::create($data);

        return response()->json([
            'message' => 'Brand added successfully',
            'data'    => $brand
        ]);
    }

    public function getBrand(Request $request)
    {
        $brand = Brand::findOrFail($request->id);

        return response()->json([
            'data' => $brand
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'logo'        => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'address'     => 'required|string|max:255',
            'description' => 'required|string',
            'what_we_do'  => 'required|string',
            'google_location' => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
            'areaServed'       => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $brand->name            = $request->name;
        $brand->slug            = Str::slug($request->name);
        $brand->address         = $request->address;
        $brand->description     = $request->description;
        $brand->what_we_do      = $request->what_we_do;
        $brand->google_location = $request->google_location;
        $brand->meta_title       = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->meta_keywords    = $request->meta_keywords;
        $brand->areaServed       = $request->areaServed;

        if ($request->hasFile('logo')) {
            if ($brand->logo && file_exists(public_path('uploads/brands/' . $brand->logo))) {
                unlink(public_path('uploads/brands/' . $brand->logo));
            }

            $file     = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/brands'), $filename);
            $brand->logo = $filename;
        }

        $brand->save();

        return response()->json([
            'message' => 'Brand updated successfully',
            'data'    => $brand
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->logo && file_exists(public_path('uploads/brands/' . $brand->logo))) {
            unlink(public_path('uploads/brands/' . $brand->logo));
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully'
        ]);
    }
    public function updateHero(Request $request)
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
        ]);

        $brandHero = BrandHero::first() ?? new BrandHero();

        if ($request->hasFile('hero_image')) {
            if ($brandHero->image && file_exists(public_path('uploads/brands/' . $brandHero->image))) {
                unlink(public_path('uploads/brands/' . $brandHero->image));
            }

            $file = $request->file('hero_image');
            $filename = 'hero_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/brands'), $filename);
            $brandHero->image = $filename;
        }

        $brandHero->save();

        return back()->with('success', 'Hero image updated successfully');
    }
}