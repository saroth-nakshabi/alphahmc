<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogRevision;
use App\Models\Scopes\PublishedScope;
use App\Support\HtmlSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

/**
 * Draft-safe blog API for the SEO agent (brief alphahmc-blog-api.md, section 6). The token can
 * read one post's state, create a draft, update a draft, and stage a revision of a live post.
 * It can never publish, delete or list. Only a person publishes, in /manage-blog.
 */
class AgentBlogController extends Controller
{
    public const PREVIEW_DAYS = 14;

    public function show(string $slug): JsonResponse
    {
        $blog = $this->find($slug);
        if (!$blog) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        return response()->json([
            'slug'               => $blog->slug,
            'status'             => $blog->status,
            'hasPendingRevision' => (bool) $blog->revision,
            'previewUrl'         => $blog->isDraft()
                ? self::previewUrl($blog)
                : ($blog->revision ? self::previewUrl($blog, true) : null),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        if ($this->find($data['slug'])) {
            return response()->json(['message' => 'Slug already exists.'], 409);
        }

        $blog = new Blog($this->contentFields($data) + [
            'slug'           => $data['slug'],
            'published_date' => null,
            'updated_date'   => null,
            'image'          => null,
            'featured'       => 0,
            'sort_order'     => (int) Blog::withoutGlobalScope(PublishedScope::class)->max('sort_order') + 1,
            'author_name'    => Blog::DEFAULT_AUTHOR,   // blog author brief, 30 Oct 2026
        ] + $this->reviewerFields($data, null));
        $blog->forceFill(['status' => Blog::STATUS_DRAFT, 'agent_written_at' => now()]);
        $blog->save();

        return response()->json([
            'slug'       => $blog->slug,
            'status'     => Blog::STATUS_DRAFT,
            'previewUrl' => self::previewUrl($blog),
        ], 201);
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        $data = $this->validated($request);

        if ($data['slug'] !== $slug) {
            return response()->json(['message' => 'The slug in the body must match the URL. A slug is never changed.', 'errors' => ['slug' => ['The slug in the body must match the URL.']]], 422);
        }

        return DB::transaction(function () use ($data, $slug) {
            $blog = Blog::withoutGlobalScope(PublishedScope::class)->where('slug', $slug)->lockForUpdate()->first();
            if (!$blog) {
                return response()->json(['message' => 'Not found.'], 404);
            }

            if ($blog->isDraft()) {
                if (self::editedByPerson($blog)) {
                    return self::editedByPersonResponse();
                }
                $blog->fill($this->contentFields($data) + $this->reviewerFields($data, $blog));
                $blog->forceFill(['agent_written_at' => now()]);
                $blog->save();

                return response()->json(['slug' => $blog->slug, 'status' => Blog::STATUS_DRAFT, 'previewUrl' => self::previewUrl($blog)]);
            }

            // Published post: stage or update its one revision. The live row is not touched.
            $revision = BlogRevision::where('blog_id', $blog->id)->lockForUpdate()->first() ?? new BlogRevision(['blog_id' => $blog->id]);
            if ($revision->exists && self::editedByPerson($revision)) {
                return self::editedByPersonResponse();
            }
            $revision->fill($this->contentFields($data) + $this->reviewerFields($data, $revision->exists ? $revision : null));
            $revision->forceFill(['agent_written_at' => now()]);
            $revision->save();

            return response()->json(['slug' => $blog->slug, 'status' => 'revision', 'previewUrl' => self::previewUrl($blog, true)]);
        });
    }

    /** Signed, 14-day preview link for a draft, or for the pending revision of a live post. */
    public static function previewUrl(Blog $blog, bool $revision = false): string
    {
        return URL::temporarySignedRoute($revision ? 'blogs.preview.revision' : 'blogs.preview', now()->addDays(self::PREVIEW_DAYS), ['id' => $blog->id]);
    }

    private function find(string $slug): ?Blog
    {
        return Blog::withoutGlobalScope(PublishedScope::class)->with('revision')->where('slug', $slug)->first();
    }

    /** A person saved the draft or revision after the agent's last write: never overwrite it. */
    private static function editedByPerson($model): bool
    {
        return $model->admin_edited_at !== null
            && ($model->agent_written_at === null || $model->admin_edited_at->gt($model->agent_written_at));
    }

    private static function editedByPersonResponse(): JsonResponse
    {
        return response()->json([
            'error'   => 'edited_by_person',
            'message' => "A person edited this draft in the admin after the agent's last write.",
        ], 409);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'slug'                => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/'],
            'site'                => 'nullable|in:alphahmc.com',
            'url'                 => ['nullable', 'string', 'max:2048', function ($attribute, $value, $fail) use ($request) {
                if (!str_ends_with($value, '/blog_single/' . $request->input('slug'))) {
                    $fail('The url must end with /blog_single/<slug>.');
                }
            }],
            'change'              => 'nullable|in:new,edit',
            'title'               => 'required|string|max:255',
            'description'         => 'required|string|max:1000',
            'html'                => 'required|string|max:200000',
            'sources'             => 'nullable|array|max:30',
            'sources.*'           => 'url:http,https|max:2048',
            'reviewer'            => 'nullable|array',
            'reviewer.name'       => 'nullable|string|max:255',
            'reviewer.credential' => 'nullable|string|max:255',
            'reviewer.url'        => 'nullable|url:http,https|max:2048',
            'lastReviewed'        => 'nullable|date_format:Y-m-d',
            'timeBound'           => 'nullable|boolean',
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string|max:320',
            'meta_keywords'       => 'nullable|string|max:1000',
        ]);
    }

    /** Title, description, sanitised body, meta fields and sources (replaced on every write). */
    private function contentFields(array $data): array
    {
        $title = HtmlSanitizer::text($data['title']);

        return [
            'title'            => $title,
            'description'      => HtmlSanitizer::text($data['description']),
            'content'          => HtmlSanitizer::clean($data['html'], $title),
            'meta_title'       => HtmlSanitizer::text($data['meta_title'] ?? null) ?: null,
            'meta_description' => HtmlSanitizer::text($data['meta_description'] ?? null) ?: null,
            'meta_keywords'    => HtmlSanitizer::text($data['meta_keywords'] ?? null) ?: null,
            'sources'          => array_values($data['sources'] ?? []) ?: null,
        ];
    }

    /** Reviewer data: a non-empty value from the agent overwrites; an empty one never erases. */
    private function reviewerFields(array $data, $current): array
    {
        $incoming = [
            'reviewer_name'       => HtmlSanitizer::text($data['reviewer']['name'] ?? null),
            'reviewer_credential' => HtmlSanitizer::text($data['reviewer']['credential'] ?? null),
            'reviewer_url'        => $data['reviewer']['url'] ?? null,
            'last_reviewed'       => $data['lastReviewed'] ?? null,
        ];

        $fields = [];
        foreach ($incoming as $field => $value) {
            if ($value !== null && $value !== '') {
                $fields[$field] = $value;
            } elseif ($current === null) {
                $fields[$field] = null;
            }
        }

        return $fields;
    }
}
