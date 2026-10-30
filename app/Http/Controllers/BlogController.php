<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Scopes\PublishedScope;
use App\Models\Tag;
use App\Support\BlogPublishGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /** The admin sees drafts too (Blog's PublishedScope hides them everywhere else). */
    private function blogs()
    {
        return Blog::withoutGlobalScope(PublishedScope::class);
    }

    /** Reviewer fields shared by create and edit (validated as http(s) for the URL). */
    private const REVIEWER_RULES = [
        'reviewer_name'       => 'nullable|string|max:255',
        'reviewer_credential' => 'nullable|string|max:255',
        'reviewer_url'        => 'nullable|url:http,https|max:2048',
        'last_reviewed'       => 'nullable|date',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // // Authorize the action
        // $this->authorize('view blogs'); // Check if user has permission to view blogs

        $data = [];
        $data['tags'] = Tag::all();
        $data['blogs'] = $this->blogs()->with(['tags', 'revision'])->orderBy('sort_order')->orderBy('id')->get();

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
            $this->blogs()->where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order saved!']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \App\Support\ReviewerFields::validate($request, true);   // reviewer + review date (SEO brief 30 Oct)

        $request->validate([
            'title'       => 'required|max:255',
            'slug'        => 'required|max:255|unique:blogs,slug',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'news_focus'  => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'read_time'   => 'nullable|integer|min:1|max:999',
        ] + self::REVIEWER_RULES);

        $image = $request->file('image');
        if (isset($image)) {
            $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/blog_images'), $image_name);
        }

        // status is never set here: a post created in the admin is published (column default).
        $blog = Blog::create([
            'title'            => $request->input('title'),
            'slug'             => $request->input('slug'),
            'featured'         => $request->has('featured') ? 1 : 0,
            'sort_order'       => (int) $this->blogs()->max('sort_order') + 1, // new blogs go to the end
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
        ] + $this->reviewerInput($request));
        \App\Support\ReviewerFields::apply($request, $blog, true);

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
        \App\Support\ReviewerFields::validate($request, true);   // reviewer + review date (SEO brief 30 Oct)
        $request->validate([
            'title'       => 'required|max:255',
            'slug'        => 'required|max:255|unique:blogs,slug,' . $id,
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'news_focus'  => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:255',
            'read_time'   => 'nullable|integer|min:1|max:999',
        ] + self::REVIEWER_RULES);

        $blog = $this->blogs()->findOrFail($id);

        $blog->fill([
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
        ] + $this->reviewerInput($request));
        // A person saved this post: the agent API will not overwrite a draft edited after its last write.
        $blog->forceFill(['admin_edited_at' => now()]);
        $blog->save();
        \App\Support\ReviewerFields::apply($request, $blog, true);

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
        $blog = $this->blogs()->findOrFail($id);

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
     * Publish a draft: the only way a draft becomes public (the agent API cannot).
     */
    public function publish($id)
    {
        $blog = $this->blogs()->findOrFail($id);
        if (!$blog->isDraft()) {
            return response()->json(['success' => false, 'message' => 'This post is already published.'], 409);
        }

        // A reviewer from the reviewers list with a review date counts as the reviewer (SEO brief 30 Oct).
        $listed = \App\Models\Reviewer::forPage($blog);
        $problem = BlogPublishGuard::problem($blog->title, $blog->content, $listed?->name ?? $blog->reviewer_name,
            $listed ? $blog->reviewed_at : $blog->last_reviewed, $blog->image, true);
        if ($problem) {
            return response()->json(['success' => false, 'message' => $problem], 422);
        }

        DB::transaction(function () use ($blog) {
            $blog->forceFill(['status' => Blog::STATUS_PUBLISHED]);
            $blog->published_date = now()->toDateString();
            $blog->updated_date = null;
            $blog->save(); // Eloquent save clears the 10-minute site cache
        });

        return response()->json(['success' => true, 'message' => 'Published. The post is now live.']);
    }

    /**
     * Publish a staged revision: copy it onto the live post, keep slug, image, tags, order and
     * the original published date, then remove the revision.
     */
    public function publishRevision($id)
    {
        $blog = $this->blogs()->with('revision')->findOrFail($id);
        $rev = $blog->revision;
        if (!$rev) {
            return response()->json(['success' => false, 'message' => 'There is no pending revision for this post.'], 404);
        }

        $problem = BlogPublishGuard::problem($rev->title, $rev->content, $rev->reviewer_name, $rev->last_reviewed, null, false);
        if ($problem) {
            return response()->json(['success' => false, 'message' => $problem], 422);
        }

        DB::transaction(function () use ($blog, $rev) {
            $blog->fill([
                'title'               => $rev->title,
                'description'         => $rev->description,
                'content'             => $rev->content,
                'meta_title'          => $rev->meta_title,
                'meta_description'    => $rev->meta_description,
                'meta_keywords'       => $rev->meta_keywords,
                'sources'             => $rev->sources,
                'reviewer_name'       => $rev->reviewer_name,
                'reviewer_credential' => $rev->reviewer_credential,
                'reviewer_url'        => $rev->reviewer_url,
                'last_reviewed'       => $rev->last_reviewed,
            ]);
            $blog->updated_date = now()->toDateString();
            $blog->save();
            $rev->delete();
        });

        return response()->json(['success' => true, 'message' => 'Revision published. The live post now shows the new text.']);
    }

    /**
     * Discard a staged revision; the live post is unchanged.
     */
    public function discardRevision($id)
    {
        $blog = $this->blogs()->with('revision')->findOrFail($id);
        $blog->revision?->delete();

        return response()->json(['success' => true, 'message' => 'Revision discarded. The live post is unchanged.']);
    }

    /** Reviewer fields from the admin form (blank values clear them). */
    private function reviewerInput(Request $request): array
    {
        return [
            'reviewer_name'       => trim((string) $request->input('reviewer_name')) ?: null,
            'reviewer_credential' => trim((string) $request->input('reviewer_credential')) ?: null,
            'reviewer_url'        => trim((string) $request->input('reviewer_url')) ?: null,
            'last_reviewed'       => $request->input('last_reviewed') ?: null,
        ];
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

        $blog = $this->blogs()->findOrFail($id);
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
        $blog = $this->blogs()->with(['tags', 'category', 'revision'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $blog,
        ], 201);
    }
}
