<?php

namespace App\Providers;

use App\Models\MainCategory;
use App\Models\ServiceGroup;
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

            View::share('main_categories', MainCategory::with($eagerLoads)->get());
        }

        // Share service groups with all views
        if (Schema::hasTable('service_groups')) {
            View::share('service_groups', ServiceGroup::all());
        }

         View::composer('*', function ($view) {
        $globaltags = globaltag::all();
        $view->with('globaltags', $globaltags);


    });

    View::composer('*', function ($view) {
        $googletags = googletag::all();
        $view->with('googletags', $googletags);


    });
        require_once app_path('Helpers/TimezoneHelper.php'); // make the helper to available
    }
}