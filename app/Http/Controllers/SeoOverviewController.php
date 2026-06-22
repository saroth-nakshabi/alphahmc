<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * "SEO Overview" — one row per record showing meta_title / meta_description /
 * meta_keywords (+ service area where applicable). Supports INLINE editing of
 * just those SEO fields (so an admin can fix meta without opening each record's
 * full editor), gated by the per-type edit permission. A full-editor link is
 * also provided per row.
 */
class SeoOverviewController extends Controller
{
    /**
     * type => [Model, Label, nameField, hasArea, editPermission, viewPermission, fullEditUrl(callable)]
     */
    private function types(): array
    {
        return [
            'services' => [
                Service::class, 'Services', 'name', true, 'edit services', 'view services',
                fn ($r) => route('services.edit', $r->id),
            ],
            'service-groups' => [
                ServiceGroup::class, 'Service Groups', 'name', false, 'edit service groups', 'view service groups',
                fn ($r) => route('service-group.edit', $r->id),
            ],
            'categories' => [
                Category::class, 'Categories', 'name', false, 'edit categories', 'view categories',
                fn ($r) => route('categories.edit', $r->id),
            ],
            'blogs' => [
                Blog::class, 'Blogs', 'title', false, 'edit blogs', 'view blogs',
                fn ($r) => route('blogs.index'),
            ],
            'brands' => [
                Brand::class, 'Brands', 'name', true, 'edit brands', 'view brands',
                fn ($r) => route('dashboard.brands.index'),
            ],
        ];
    }

    public function show(string $type)
    {
        $types = $this->types();
        abort_unless(isset($types[$type]), 404);
        [$model, $label, $nameField, $hasArea, $editPerm, $viewPerm, $fullEdit] = $types[$type];
        abort_unless(Auth::user()->can($viewPerm), 403);

        $canEdit = Auth::user()->can($editPerm);

        $items = $model::orderBy($nameField)->get()->map(fn ($r) => [
            'id'               => $r->id,
            'name'             => $r->{$nameField},
            'meta_title'       => $r->meta_title,
            'meta_description' => $r->meta_description,
            'meta_keywords'    => $r->meta_keywords,
            'area'             => $hasArea ? $r->areaServed : null,
            'edit_url'         => $fullEdit($r),
        ]);

        return view('dashboard.seo_overview.index', [
            'pageTitle' => 'SEO Overview — ' . $label,
            'type'      => $type,
            'items'     => $items,
            'showArea'  => $hasArea,
            'canEdit'   => $canEdit,
        ]);
    }

    /** Inline-update ONLY the SEO fields of one record. */
    public function update(Request $request, string $type, $id)
    {
        $types = $this->types();
        abort_unless(isset($types[$type]), 404);
        [$model, , , $hasArea, $editPerm] = $types[$type];
        abort_unless(Auth::user()->can($editPerm), 403);

        $rules = [
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
        ];
        if ($hasArea) {
            $rules['areaServed'] = 'nullable|string|max:255';
        }
        $data = $request->validate($rules);

        $record = $model::findOrFail($id);
        $record->meta_title       = $data['meta_title'] ?? null;
        $record->meta_description = $data['meta_description'] ?? null;
        $record->meta_keywords    = $data['meta_keywords'] ?? null;
        if ($hasArea) {
            $record->areaServed = $data['areaServed'] ?? null;
        }
        $record->save();

        return response()->json(['success' => true, 'message' => 'SEO fields updated']);
    }
}
