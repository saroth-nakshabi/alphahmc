<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // // Authorize the action
        // $this->authorize('view blogs'); // Check if user has permission to view blogs

        $data = [];
        $data['tags'] = Tag::all();
        $data['blogs'] = Blog::all();

        return view('dashboard.blogs.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required|string',
            'description' => 'required|string',
            'tags' => 'required',
        ]);

        // Handle image uploads
        $image = $request->file('image');
        if (isset($image)) {
            $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/blog_images'), $image_name);
        }
        $blog = Blog::create([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'featured' => $request->input('featured') ?? 0,
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'image' => $image_name ?? null,
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
        ]);

        $blog->tags()->attach($request->input('tags')); // Assuming 'tags' is an array of tag IDs

        return response()->json([
            'success' => true,
            'message' => 'created successfully!',
            'data' => $blog,
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:' . Blog::class . ',slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required|string',
            'description' => 'required|string',
            'tags' => 'required',
        ]);

        $blog = Blog::findOrFail($id);

        // Update blog attributes
        $blog->update([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'featured' => $request->input('featured') ?? 0,
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
        ]);

        // Handle image uploads if a new image is provided
        if ($request->hasFile('image')) {
            $existing_image = $blog->image; // Assuming images is a relation

            // Delete the existing image file if it exists
            if ($existing_image && file_exists(public_path('uploads/blog_images/' . $existing_image))) {
                unlink(public_path('uploads/blog_images/' . $existing_image));
            }

            // Store the new image
            $image = $request->file('image');
            $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/blog_images'), $image_name);

            $blog->update([
                'image' => $image_name
            ]);
        }

        $blog->tags()->sync($request->input('tags'));


        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $blog,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the blog
        $blog = Blog::findOrFail($id);

        // Delete the image file if it exists
        if ($blog->image && file_exists(public_path('uploads/blog_images/' . $blog->image))) {
            unlink(public_path('uploads/blog_images/' . $blog->image));
        }

        // Delete the blog
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getBlog(Request $request)
    {
        $id = $request->input('id');
        $blog = Blog::with(["tags"])->findOrFail($id); // Assuming Blog has a 'tags' relation

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $blog,
        ], 201);
    }
}
