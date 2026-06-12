<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\Blog;
use App\Models\Project;
use App\Models\Category;
use App\Models\ServiceGroup;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('s', '');
        $results = collect();

        if (strlen($query) >= 2) {

            // ✅ Services — using correct columns: name, overview, content
            $services = Service::published()->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('overview', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
                ->get()
                ->map(function ($item) {
                    return [
                        'type'    => 'Service',
                        'title'   => $item->name,
                        'url'     => route('front.service', $item->slug),
                        'excerpt' => Str::limit(strip_tags($item->overview ?? $item->content ?? ''), 120),
                    ];
                });

            // Categories
            $categories = Category::whereNotNull('slug')->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('overview', 'LIKE', "%{$query}%");
            })
                ->get()
                ->map(function ($item) {
                    return [
                        'type'    => 'Category',
                        'title'   => $item->name,
                        'url'     => route('front.service-category', $item->slug),
                        'excerpt' => Str::limit(strip_tags($item->description ?? $item->overview ?? ''), 120),
                    ];
                });

            // Service groups (packages)
            $groups = ServiceGroup::published()->whereNotNull('slug')->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('overview', 'LIKE', "%{$query}%");
            })
                ->get()
                ->map(function ($item) {
                    return [
                        'type'    => 'Service Package',
                        'title'   => $item->name,
                        'url'     => route('service-packages', $item->slug),
                        'excerpt' => Str::limit(strip_tags($item->description ?? $item->overview ?? ''), 120),
                    ];
                });

            // ⚠️ Blogs — update column names after checking
            $blogs = Blog::where('title', 'LIKE', "%{$query}%")
                ->get()
                ->map(function ($item) {
                    return [
                        'type'    => 'Blog',
                        'title'   => $item->title,
                        'url'     => route('front.new_blog'),
                        'excerpt' => Str::limit(strip_tags($item->content ?? ''), 120),
                    ];
                });

            // ⚠️ Projects — update column names after checking
            $projects = Project::where('name', 'LIKE', "%{$query}%")
                ->get()
                ->map(function ($item) {
                    return [
                        'type'    => 'Project',
                        'title'   => $item->name,
                        'url'     => route('front.search'),
                        'excerpt' => Str::limit(strip_tags($item->description ?? ''), 120),
                    ];
                });

            $results = $services->merge($groups)->merge($categories)->merge($blogs)->merge($projects);
        }

        return view('front.search', compact('results', 'query'));
    }

    public function live(Request $request)
    {
        $query = $request->get('s', '');
        $results = [];

        if (strlen($query) >= 2) {

            $services = Service::published()->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('overview', 'LIKE', "%{$query}%");
            })->limit(4)
                ->get()
                ->map(function ($item) {
                    return [
                        'type'  => 'Service',
                        'title' => $item->name,
                        'url'   => route('front.service', $item->slug),
                        'icon'  => 'fa-stethoscope',
                        'color' => '#009095',
                    ];
                });

            $groups = ServiceGroup::published()->whereNotNull('slug')->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('overview', 'LIKE', "%{$query}%");
            })->limit(3)
                ->get()
                ->map(function ($item) {
                    return [
                        'type'        => 'Service Package',
                        'type_plural' => 'Service Packages',
                        'title'       => $item->name,
                        'url'         => route('service-packages', $item->slug),
                        'icon'        => 'fa-layer-group',
                        'color'       => '#066D77',
                    ];
                });

            $categories = Category::whereNotNull('slug')->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })->limit(3)
                ->get()
                ->map(function ($item) {
                    return [
                        'type'        => 'Category',
                        'type_plural' => 'Categories',
                        'title'       => $item->name,
                        'url'         => route('front.service-category', $item->slug),
                        'icon'        => 'fa-folder-open',
                        'color'       => '#6d28d9',
                    ];
                });

            $blogs = Blog::where('title', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get()
                ->map(function ($item) {
                    return [
                        'type'  => 'Blog',
                        'title' => $item->title,
                        'url'   => route('front.new_blog'),
                        'icon'  => 'fa-newspaper',
                        'color' => '#4CAF50',
                    ];
                });

            $projects = Project::where('name', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get()
                ->map(function ($item) {
                    return [
                        'type'  => 'Project',
                        'title' => $item->name,
                        'url'   => route('front.project'),
                        'icon'  => 'fa-diagram-project',
                        'color' => '#0056a6',
                    ];
                });

            $results = $services->merge($groups)->merge($categories)->merge($blogs)->merge($projects)->values()->all();
        }

        return response()->json($results);
    }
}