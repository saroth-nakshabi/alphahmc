<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\LandingPageRevision;
use App\Models\Project;
use App\Support\LandingPageSchema;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Admin for landing pages (dashboard > Landing pages). Admins edit copy, SEO fields and the hero image;
 * the template, typography and tracking stay in code. Every save writes a full revision, so any earlier
 * version (copy, SEO fields and hero image together) can be restored.
 */
class AdminLandingPageController extends Controller
{
    private const HERO_DIR = 'uploads/landing_pages';

    public function index()
    {
        $pages = LandingPage::orderBy('emirate')->orderBy('name')->get();

        return view('dashboard.landing_pages.index', compact('pages'));
    }

    public function create()
    {
        $page = new LandingPage([
            'status' => 'draft', 'lead_type' => 'facility', 'indexable' => false, 'emirate' => 'Dubai',   // ads pages: out of search by default
        ]);
        // Start from the dental page's copy when it exists, so a new page is not a blank form.
        $template = LandingPage::where('slug', 'dental-centre-dubai')->first();
        $page->content = $template ? $template->content : LandingPageSchema::normalize([]);

        return $this->form($page);
    }

    public function edit($id)
    {
        return $this->form(LandingPage::findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        return $this->save($request, new LandingPage());
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->save($request, LandingPage::findOrFail($id));
    }

    public function duplicate($id)
    {
        $source = LandingPage::findOrFail($id);
        $copy = $source->replicate(['published_at', 'updated_by']);
        $copy->name = $source->name . ' (copy)';
        $copy->slug = $this->freeSlug($source->slug . '-copy');
        $copy->status = 'draft';
        $copy->canonical = null;
        $copy->updated_by = Auth::id();
        $copy->save();
        $this->recordRevision($copy, 'Duplicated from ' . $source->name);

        return redirect()->route('admin.landing-pages.edit', $copy->id)->with('success', 'Copy created as a draft. Change its URL and copy, then publish.');
    }

    public function destroy($id)
    {
        $page = LandingPage::findOrFail($id);
        if ($page->isPublished()) {
            return back()->with('error', 'Set the page to draft before you delete it, so a live ad never points at a missing page.');
        }
        DB::transaction(function () use ($page) {
            LandingPageRevision::where('landing_page_id', $page->id)->delete();
            $page->delete();
        });

        return back()->with('success', 'Landing page deleted.');
    }

    public function history($id)
    {
        $page = LandingPage::findOrFail($id);
        $revisions = $page->revisions()->paginate(30);

        return view('dashboard.landing_pages.history', compact('page', 'revisions'));
    }

    public function restore($id, $revision)
    {
        $page = LandingPage::findOrFail($id);
        $rev = LandingPageRevision::where('landing_page_id', $page->id)->findOrFail($revision);
        $snapshot = array_intersect_key($rev->snapshot ?? [], array_flip(LandingPage::SNAPSHOT_FIELDS));

        // The restored slug must still be free (another page may have taken it since).
        if (isset($snapshot['slug']) && LandingPage::where('slug', $snapshot['slug'])->where('id', '!=', $page->id)->exists()) {
            unset($snapshot['slug']);
        }

        $page->fill($snapshot);
        if ($page->isPublished() && !$page->published_at) {
            $page->published_at = now();
        }
        $page->updated_by = Auth::id();
        $page->save();
        $this->recordRevision($page, 'Restored the version of ' . $rev->created_at->format('j M Y, H:i'));

        return redirect()->route('admin.landing-pages.edit', $page->id)
            ->with('success', 'Version of ' . $rev->created_at->format('j M Y, H:i') . ' restored. The page you replaced is kept in the history.');
    }

    private function form(LandingPage $page)
    {
        return view('dashboard.landing_pages.edit', [
            'page'     => $page,
            'content'  => LandingPageSchema::normalize($page->content ?? []),
            'projects' => Project::orderBy('name')->get(['id', 'name', 'slug']),
            'pageTargets' => \App\Models\FacilityTypeCard::targets(),
            'others'   => LandingPage::orderBy('emirate')->orderBy('name')->get(['id', 'name', 'slug', 'status', 'facility_type']),
        ]);
    }

    private function save(Request $request, LandingPage $page): JsonResponse
    {
        $validator = validator($request->all(), [
            'name'             => 'required|string|max:255',
            'slug'             => ['required', 'string', 'max:120', 'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                                   Rule::unique('landing_pages', 'slug')->ignore($page->id)],
            'status'           => ['required', Rule::in(array_keys(LandingPage::STATUSES))],
            'lead_type'        => ['required', Rule::in(array_keys(LandingPage::LEAD_TYPES))],
            'facility_type'    => 'nullable|string|max:100',
            'emirate'          => ['nullable', Rule::in(LandingPageSchema::EMIRATES)],
            'seo_title'        => 'required|string|max:255',
            'meta_description' => 'required|string|max:500',
            'canonical'        => 'nullable|url|max:255',
            'hero_image'       => 'nullable|file|mimes:jpg,jpeg,png|max:6144|dimensions:min_width=1200,min_height=600',
            'hero_image_alt'   => 'required|string|max:255',
            'plan_before_image' => 'nullable|file|mimes:jpg,jpeg,png|max:6144|dimensions:min_width=800',
            'plan_after_image'  => 'nullable|file|mimes:jpg,jpeg,png|max:6144|dimensions:min_width=800',
            'plan_before_alt'   => 'nullable|string|max:255',
            'plan_after_alt'    => 'nullable|string|max:255',
            'related_ids'      => 'nullable|array',
            'related_ids.*'    => 'integer|exists:landing_pages,id',
            'content'          => 'required|json',
        ], [
            'slug.regex'            => 'Use lowercase letters, numbers and single hyphens in the URL, for example dental-centre-dubai.',
            'slug.unique'           => 'Another landing page already uses this URL.',
            'hero_image.mimes'      => 'Upload the hero image as JPG or PNG.',
            'hero_image.max'        => 'The hero image must be 6 MB or smaller.',
            'hero_image.dimensions' => 'The hero image must be at least 1200 by 600 pixels.',
            'hero_image_alt.required' => 'Describe the hero image for screen readers (alt text).',
            'plan_before_image.mimes' => 'Upload the floor plan as JPG or PNG.',
            'plan_after_image.mimes'  => 'Upload the floor plan as JPG or PNG.',
            'plan_before_image.max'   => 'Each floor plan must be 6 MB or smaller.',
            'plan_after_image.max'    => 'Each floor plan must be 6 MB or smaller.',
            'plan_before_image.dimensions' => 'Each floor plan must be at least 800 pixels wide.',
            'plan_after_image.dimensions'  => 'Each floor plan must be at least 800 pixels wide.',
        ]);

        $content = LandingPageSchema::normalize(json_decode((string) $request->input('content'), true) ?: []);
        $errors = $validator->errors()->toArray();
        foreach (LandingPageSchema::errors($content) as $path => $message) {
            $errors['content.' . $path] = [$message];
        }
        if (!$page->hero_image && !$request->hasFile('hero_image')) {
            $errors['hero_image'] = ['Upload a hero image.'];
        }
        $removePlans = $request->boolean('plan_images_remove');
        foreach (['before', 'after'] as $side) {
            $hasImage = $request->hasFile("plan_{$side}_image") || ($page->{"plan_{$side}_image"} && !$removePlans);
            if ($hasImage && trim((string) $request->input("plan_{$side}_alt")) === '') {
                $errors["plan_{$side}_alt"] = ['Describe the ' . ($side === 'before' ? 'unplanned' : 'planned') . ' layout for screen readers (alt text).'];
            }
        }
        if ($errors) {
            return response()->json(['success' => false, 'message' => 'Fix the highlighted fields, then save again.', 'errors' => $errors], 422);
        }

        $data = $validator->validated();
        $data['content'] = $content;
        $data['indexable'] = $request->boolean('indexable');
        $data['related_ids'] = array_values(array_unique(array_map('intval', $request->input('related_ids', []))));
        $data['canonical'] = $data['canonical'] ?? null;
        $data['updated_by'] = Auth::id();
        unset($data['hero_image'], $data['plan_before_image'], $data['plan_after_image']);
        if ($removePlans) {
            $data['plan_before_image'] = $data['plan_after_image'] = null;   // files kept: revisions may restore them
        }

        try {
            if ($request->hasFile('hero_image')) {
                // Earlier hero files are kept: revisions may restore them.
                $file = $request->file('hero_image');
                $name = time() . '_' . Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
                $file->move(public_path(self::HERO_DIR), $name);
                $data['hero_image'] = self::HERO_DIR . '/' . $name;
            }
            foreach (['plan_before_image', 'plan_after_image'] as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $name = time() . '_' . Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
                    $file->move(public_path(self::HERO_DIR), $name);
                    $data[$field] = self::HERO_DIR . '/' . $name;
                }
            }

            $wasNew = !$page->exists;
            $page->fill($data);
            if ($page->isPublished() && !$page->published_at) {
                $page->published_at = now();
            }
            DB::transaction(function () use ($page, $request) {
                $page->save();
                $this->recordRevision($page, trim((string) $request->input('note')) ?: null);
            });
        } catch (\Throwable $e) {
            Log::error('Landing page save failed: ' . $e->getMessage(), ['page' => $page->id]);

            return response()->json(['success' => false, 'message' => 'The page could not be saved. Try again; if it keeps failing, tell the web team.'], 500);
        }

        $warnings = LandingPageSchema::copyWarnings($content, [
            'SEO title' => $page->seo_title, 'Meta description' => $page->meta_description,
            'Page name' => $page->name, 'Hero image alt text' => $page->hero_image_alt,
            'Unplanned layout alt text' => $page->plan_before_alt, 'Planned layout alt text' => $page->plan_after_alt,
        ]);
        // Search engines cut longer ones (Saroth, 30 Oct 2026).
        if (mb_strlen($page->seo_title) > 60) {
            $warnings[] = 'SEO title is ' . mb_strlen($page->seo_title) . ' characters; keep it within 60.';
        }
        if (mb_strlen($page->meta_description) > 155) {
            $warnings[] = 'Meta description is ' . mb_strlen($page->meta_description) . ' characters; keep it within 155.';
        }

        return response()->json([
            'success'  => true,
            'message'  => $page->isPublished() ? 'Saved. The live page shows the change now.' : 'Saved as a draft.',
            'warnings' => $warnings,
            'redirect' => $wasNew ? route('admin.landing-pages.edit', $page->id) : null,
            'view_url' => $page->url(),
            'hero_url' => $page->hero_image ? asset('public/' . $page->hero_image) : null,
            'plan_urls' => [
                'before' => $page->plan_before_image ? asset('public/' . $page->plan_before_image) : null,
                'after'  => $page->plan_after_image ? asset('public/' . $page->plan_after_image) : null,
            ],
        ]);
    }

    private function recordRevision(LandingPage $page, ?string $note): void
    {
        $user = Auth::user();
        LandingPageRevision::create([
            'landing_page_id' => $page->id,
            'user_id'         => $user?->id,
            'user_name'       => $user ? (trim($user->first_name . ' ' . $user->last_name) ?: $user->email) : null,
            'note'            => $note ? Str::limit($note, 250) : null,
            'snapshot'        => $page->fresh()->snapshot(),
        ]);
    }

    private function freeSlug(string $base): string
    {
        $slug = $base;
        for ($i = 2; LandingPage::where('slug', $slug)->exists(); $i++) {
            $slug = $base . '-' . $i;
        }

        return $slug;
    }
}
