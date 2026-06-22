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

        // Share categories with all views
        // Check if the categories table exists
        if (Schema::hasTable('main_categories')) {
            $eagerLoads = [
                'categories.services'    => fn($q) => $q->where('status', 'published'),
                'allCategories.services' => fn($q) => $q->where('status', 'published'),
            ];

            if (Schema::hasTable('category_service_group')) {
                $eagerLoads['categories.serviceGroups']    = fn($q) => $q->where('status', 'published');
                $eagerLoads['allCategories.serviceGroups'] = fn($q) => $q->where('status', 'published');
            }

            View::share('main_categories', MainCategory::with($eagerLoads)->orderBy('sort_order')->get());
        }

        // Share service groups with all views
        if (Schema::hasTable('service_groups')) {
            View::share('service_groups', ServiceGroup::all());
        }

        // Mega-menu default panel: a curated set of featured services (admin curates via the
        // `featured` flag + `sort_order`). Capped so the open-menu panel stays editorial.
        if (Schema::hasTable('services')) {
            View::share('nav_featured_services', \App\Models\Service::where('status', 'published')
                ->where('featured', 1)
                ->orderBy('sort_order')->orderBy('id')
                ->take(6)->get());
        }

        // Mega-menu default panel: auto-rotating promo slides (dashboard-managed, max 3).
        if (Schema::hasTable('menu_promos')) {
            View::share('nav_menu_promos', \App\Models\MenuPromo::activeOrdered()
                ->take(\App\Models\MenuPromo::MAX)->get());
        }

        // Mega-menu: Knowledge Base (blog tags → blogs) and Case Studies (project categories → projects)
        if (Schema::hasTable('tags') && Schema::hasTable('blog_tags')) {
            View::share('nav_blog_tags', \App\Models\Tag::has('blogs')
                ->whereRaw('LOWER(name) != ?', ['ahg updates']) // shown separately under "About Alpha Health Group"
                ->with(['blogs' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
                ->orderBy('name')->get());
        }
        if (Schema::hasTable('project_categories')) {
            View::share('nav_project_categories', \App\Models\ProjectCategory::has('projects')
                ->with('projects')->orderBy('name')->get());
        }

         View::composer('*', function ($view) {
        $globaltags = globaltag::all();
        $view->with('globaltags', $globaltags);


    });

    View::composer('*', function ($view) {
        $googletags = googletag::all();
        $view->with('googletags', $googletags);


    });

    // Share the managed page settings (SEO + hero) for the current standard page.
    if (Schema::hasTable('page_settings')) {
        View::composer('*', function ($view) {
            $routeName = request()->route() ? request()->route()->getName() : null;
            $key = \App\Models\PageSetting::keyForRoute($routeName);
            $view->with('pageMeta', $key ? \App\Models\PageSetting::for($key) : null);
        });
    }
        require_once app_path('Helpers/TimezoneHelper.php'); // make the helper to available
    }
}