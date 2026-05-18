<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    // Display all testimonials
    public function index()
    {
        $Projects = Testimonial::all(); // matches your Blade variable
        return view('dashboard.contact.testimonial', compact('Projects'));
    }

    // Store a new testimonial
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'author_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'sometimes|boolean',
            'author_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

       $data = $request->only([
    'author_name',
    'position',
    'company_name',
    'content',
    'rating',    
]);

$data['featured'] = $request->has('featured') ? 1 : 0;

if ($request->hasFile('author_image')) {
    $file = $request->file('author_image');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads/testimonials'), $filename);
    $data['author_image'] = $filename;
}

$testimonial = Testimonial::create($data);

        return response()->json([
            'message' => 'Testimonial added successfully',
            'data' => $testimonial
        ]);
    }

    // Get a testimonial for editing
    public function getTestimonial(Request $request)
    {
        $testimonial = Testimonial::findOrFail($request->id);

        return response()->json([
            'data' => $testimonial
        ]);
    }

    // Update a testimonial
    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'author_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'sometimes|boolean',
            'author_image' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $testimonial->author_name = $request->author_name;
        $testimonial->position = $request->position;
        $testimonial->company_name = $request->company_name;
        $testimonial->content = $request->content;
        $testimonial->rating = $request->rating;
        $testimonial->featured = $request->has('featured') ? 1 : 0;

        if ($request->hasFile('author_image')) {
            $file = $request->file('author_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/testimonials'), $filename);
            $testimonial->author_image = $filename;
        }

        $testimonial->save();

        return response()->json([
            'message' => 'Testimonial updated successfully',
            'data' => $testimonial
        ]);
    }

    // Delete a testimonial
    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial deleted successfully'
        ]);
    }

    // Toggle featured
    public function toggleFeatured(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->featured = !$testimonial->featured;
        $testimonial->save();

        return response()->json([
            'message' => 'Featured status updated',
            'featured' => $testimonial->featured
        ]);
    }
}