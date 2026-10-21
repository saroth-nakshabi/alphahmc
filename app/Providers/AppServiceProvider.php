<?php

namespace App\Providers;

use App\Models\MainCategory;
use App\Models\ServiceGroup;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\globaltag;
use App\Models\googletag;
use App\Support\SiteCache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super-admin safety net: anyone with the "Admin" role passes every
        // permission/gate check, so adding permission: middleware can never
        // lock the top administrator out of the dashboard. Returning null lets
        // non-admins fall through to the normal Spatie permission check.
        Gate::before(function ($user, $ability) {
            return (method_exists($user, 'hasRole') && $user->hasRole('Admin')) ? true : null;
        });

        // Clear the 10-minute site cache whenever an admin saves or deletes page data.
        foreach (self::SITE_CACHE_MODELS as $model) {
            $model::saved(fn () => SiteCache::flush());
            $model::deleted(fn () => SiteCache::flush());
        }

        // Menu data shared with every view (mega menu, home service picker), cached for
        // 10 minutes: about 20 queries per page, including the Schema checks.
        foreach (SiteCache::remember('nav', fn () => $this->navData()) as $name => $value) {
            View::share($name, $value);
        }

    // These composers run for every view, including each partial, so each value is loaded once
    // per request (memo keyed by the request object, so long-running workers never reuse it).
    View::composer('*', function ($view) {
        static $memo = [];
        $id = spl_object_id(request());
        if (!isset($memo[$id])) {
            $memo = [$id => globaltag::all()];
        }
        $view->with('globaltags', $memo[$id]);
    });

    View::composer('*', function ($view) {
        static $memo = [];
        $id = spl_object_id(request());
        if (!isset($memo[$id])) {
            $memo = [$id => googletag::all()];
        }
        $view->with('googletags', $memo[$id]);
    });

    // Share the managed page settings (SEO + hero) for the current standard page.
    if (Schema::hasTable('page_settings')) {
        View::composer('*', function ($view) {
            static $memo = [];
            $id = spl_object_id(request());
            if (!array_key_exists($id, $memo)) {
                $routeName = request()->route() ? request()->route()->getName() : null;
                $key = \App\Models\PageSetting::keyForRoute($routeName);
                $memo = [$id => $key ? \App\Models\PageSetting::for($key) : null];
            }
            $view->with('pageMeta', $memo[$id]);
        });
    }
        require_once app_path('Helpers/TimezoneHelper.php'); // make the helper to available
    }

    /** Models whose save/delete changes cached page data (see App\Support\SiteCache). */
    private const SITE_CACHE_MODELS = [
        MainCategory::class, \App\Models\Category::class, \App\Models\Service::class,
        \App\Models\ServiceImage::class, ServiceGroup::class, \App\Models\MenuPromo::class,
        \App\Models\Tag::class, \App\Models\BlogTag::class, \App\Models\Blog::class,
        \App\Models\Project::class, \App\Models\ProjectCategory::class, \App\Models\ProjectImage::class,
        \App\Models\ProjectVideo::class, \App\Models\ProjectDocument::class, \App\Models\HomeSlider::class,
        \App\Models\Announcement::class, \App\Models\client::class,
    ];

    /** Data for the mega menu and the home service picker, shared with every view. */
    private function navData(): array
    {
        $data = [];

        if (Schema::hasTable('main_categories')) {
            $published = fn ($q) => $q->where('status', 'published');
            // Only the columns the menu, home picker and all-services page read: the full rows
            // (info_*, meta_*, content) made the cached payload 5 MB.
            $publishedServices = fn ($q) => $q->where('status', 'published')->select([
                'services.id', 'services.name', 'services.slug', 'services.overview',
                'services.hero_image', 'services.featured', 'services.status',
                // layout-2's service schema block also runs on home (the picker loop leaves $service set)
                'services.meta_description', 'services.content', 'services.areaServed',
            ]);
            $eagerLoads = [
                'categories.services'           => $publishedServices,
                'allCategories.services'        => $publishedServices,
                // the home service picker shows each service's first image
                'categories.services.images',
                'allCategories.services.images',
            ];

            if (Schema::hasTable('category_service_group')) {
                $eagerLoads['categories.serviceGroups']    = $published;
                $eagerLoads['allCategories.serviceGroups'] = $published;
            }

            $data['main_categories'] = MainCategory::with($eagerLoads)->orderBy('sort_order')->get();
        }

        if (Schema::hasTable('service_groups')) {
            $data['service_groups'] = ServiceGroup::all();
        }

        // Mega-menu default panel: a curated set of featured services (admin curates via the
        // `featured` flag + `sort_order`). Capped so the open-menu panel stays editorial.
        if (Schema::hasTable('services')) {
            $data['nav_featured_services'] = \App\Models\Service::where('status', 'published')
                ->where('featured', 1)
                ->orderBy('sort_order')->orderBy('id')
                ->take(6)->get();
        }

        // Mega-menu default panel: auto-rotating promo slides (dashboard-managed, max 3).
        if (Schema::hasTable('menu_promos')) {
            $data['nav_menu_promos'] = \App\Models\MenuPromo::activeOrdered()
                ->take(\App\Models\MenuPromo::MAX)->get();
        }

        // Mega-menu: Knowledge Base (blog tags → blogs) and Case Studies (project categories → projects)
        if (Schema::hasTable('tags') && Schema::hasTable('blog_tags')) {
            $data['nav_blog_tags'] = \App\Models\Tag::has('blogs')
                ->whereRaw('LOWER(name) != ?', ['ahg updates']) // shown separately under "About Alpha Health Group"
                ->with(['blogs' => fn ($q) => $q->select(['blogs.id', 'blogs.title', 'blogs.slug', 'blogs.sort_order'])
                    ->orderBy('sort_order')->orderBy('id')])
                ->orderBy('name')->get();
        }
        if (Schema::hasTable('project_categories')) {
            $data['nav_project_categories'] = \App\Models\ProjectCategory::has('projects')
                ->with(['projects' => fn ($q) => $q->select(['id', 'name', 'slug', 'project_category_id'])])
                ->orderBy('name')->get();
        }

        return $data;
    }
}