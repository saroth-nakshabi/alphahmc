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
        $data['blogs'] = Blog::with('tags')->orderBy('sort_order')->orderBy('id')->get();

        return view('dashboard.blogs.index', $data);
    }

    /**
     * Persist the drag-and-drop order from the listing page.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:blogs,id',
        ]);

        foreach ($request->order as $position => $id) {
            Blog::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order saved!']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title'       => 'required|max:255',
            'slug'        => 'required|max:255|unique:blogs,slug',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'news_focus'  => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'read_time'   => 'nullable|integer|min:1|max:999',
        ]);

        $image = $request->file('image');
        if (isset($image)) {
            $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/blog_images'), $image_name);
        }

        $blog = Blog::create([
            'title'            => $request->input('title'),
            'slug'             => $request->input('slug'),
            'featured'         => $request->has('featured') ? 1 : 0,
            'content'          => $request->input('content'),
            'description'      => $request->input('description'),
            'news_focus'       => $this->normalizeNewsFocus($request->input('news_focus')),
            'author_name'      => $request->input('author_name'),
            'read_time'        => $request->input('read_time'),
            'published_date'   => $request->input('published_date') ?: now()->toDateString(),
            'updated_date'     => $request->input('updated_date') ?: null,
            'image'            => $image_name ?? null,
            'meta_title'       => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords'    => $request->input('meta_keywords'),
        ]);

        $blog->tags()->sync($request->input('tags', []));
        $blog->load('tags');

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
            'title'       => 'required|max:255',
            'slug'        => 'required|max:255|unique:blogs,slug,' . $id,
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'news_focus'  => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'read_time'   => 'nullable|integer|min:1|max:999',
        ]);

        $blog = Blog::findOrFail($id);

        $blog->update([
            'title'            => $request->input('title'),
            'slug'             => $request->input('slug'),
            'featured'         => $request->has('featured') ? 1 : 0,
            'content'          => $request->input('content'),
            'description'      => $request->input('description'),
            'news_focus'       => $this->normalizeNewsFocus($request->input('news_focus')),
            'author_name'      => $request->input('author_name'),
            'read_time'        => $request->input('read_time'),
            'published_date'   => $request->input('published_date') ?: null,
            'updated_date'     => $request->input('updated_date') ?: null,
            'meta_title'       => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords'    => $request->input('meta_keywords'),
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

        $blog->tags()->sync($request->input('tags', []));
        $blog->load('tags');

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

    /**
     * Normalise the comma-separated News Focus input: trim, drop blanks,
     * cap at 3 values, and re-join as a clean comma-separated string.
     */
    private function normalizeNewsFocus(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $items = collect(explode(',', $value))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->take(3)
            ->values();

        return $items->isEmpty() ? null : $items->implode(', ');
    }

    /**
     * Toggle the "featured" flag from the listing (no edit page needed).
     */
    public function featuredHandle(Request $request)
    {
        $id = $request->input('id');
        $featured = $request->input('featured');

        $blog = Blog::findOrFail($id);
        $blog->update([
            'featured' => $featured,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $blog,
        ], 201);
    }

    public function getBlog(Request $request)
    {
        $id = $request->input('id');
        $blog = Blog::with(['tags', 'category'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $blog,
        ], 201);
    }
}
