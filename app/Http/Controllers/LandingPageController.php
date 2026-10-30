<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Project;
use App\Support\LandingPageSchema;
use Illuminate\Support\Facades\Auth;

/** Public landing pages at /facility-setup/{slug}; drafts only as an admin preview. */
class LandingPageController extends Controller
{
    public function show(string $slug)
    {
        $page = LandingPage::where('slug', $slug)->firstOrFail();

        $preview = !$page->isPublished();
        if ($preview && !(Auth::check() && Auth::user()->can('view landing pages'))) {
            abort(404);
        }

        $content = $page->contentData();

        $projectsSection = LandingPageSchema::section($content, 'projects');
        $projects = $projectsSection
            ? Project::with('projects_images')
                ->whereIn('id', array_column($projectsSection['items'], 'project_id'))->get()->keyBy('id')
            : collect();

        return view('front.landing.facility', [
            'page'     => $page,
            'content'  => $content,
            'projects' => $projects,
            'related'  => $page->relatedPages(),
            'preview'  => $preview,
        ]);
    }
}
