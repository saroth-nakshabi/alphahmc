<?php

namespace App\Http\Controllers;

use App\Models\PageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageSettingController extends Controller
{
    /** List every managed standard page. */
    public function index()
    {
        $pages = [];
        foreach (array_keys(PageSetting::REGISTRY) as $key) {
            $pages[] = PageSetting::for($key);
        }
        return view('dashboard.pages.index', compact('pages'));
    }

    public function edit($key)
    {
        abort_unless(isset(PageSetting::REGISTRY[$key]), 404);
        $page = PageSetting::for($key);
        return view('dashboard.pages.edit', compact('page'));
    }

    public function update(Request $request, $key)
    {
        abort_unless(isset(PageSetting::REGISTRY[$key]), 404);
        $page = PageSetting::for($key);

        $request->validate([
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords'    => 'nullable|string|max:1000',
            'og_image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'hero_eyebrow'     => 'nullable|string|max:255',
            'hero_title'       => 'nullable|string|max:255',
            'hero_subtitle'    => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $data = $request->only([
            'meta_title', 'meta_description', 'meta_keywords',
            'hero_eyebrow', 'hero_title', 'hero_subtitle', 'hero_description',
        ]);

        $data['og_image']   = $this->handleImage($request, 'og_image', $page->og_image);
        $data['hero_image'] = $this->handleImage($request, 'hero_image', $page->hero_image);

        $page->update($data);

        return back()->with('success', $page->label() . ' page settings saved.');
    }

    /** Upload a new image to uploads/page_images (replacing the old), else keep current. */
    private function handleImage(Request $request, string $field, ?string $current): ?string
    {
        if (!$request->hasFile($field)) {
            return $current;
        }
        if ($current && file_exists(public_path('uploads/page_images/' . $current))) {
            @unlink(public_path('uploads/page_images/' . $current));
        }
        $file = $request->file($field);
        $name = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/page_images'), $name);
        return $name;
    }
}
