<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Collection;

/**
 * Case studies and the related article for service and package pages.
 *
 * Only related items are returned; an empty result hides the section, so a licensing
 * page never shows a wellness-centre case study just because it is first by sort order.
 */
class RelatedContent
{
    /**
     * Projects that list one of $serviceIds, else ones that list another published service
     * from $categoryIds.
     *
     * @param  array<int|string> $serviceIds
     * @param  array<int|string> $categoryIds
     */
    public static function projects(array $serviceIds, array $categoryIds, int $limit = 2): Collection
    {
        $own = array_map('strval', $serviceIds);
        $siblings = $categoryIds
            ? Service::published()
                ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
                ->pluck('id')->map(fn ($id) => (string) $id)->all()
            : [];

        $all = Project::with('projects_images', 'project_category')->orderBy('sort_order')->orderBy('id')->get();
        $lists = fn ($project, array $ids) => (bool) array_intersect(array_map('strval', (array) $project->service_ids), $ids);

        $matches = $all->filter(fn ($p) => $lists($p, $own));
        if ($matches->isEmpty() && $siblings) {
            $matches = $all->filter(fn ($p) => $lists($p, $siblings));
        }

        return $matches->take($limit)->values();
    }

    /**
     * The blog in $categoryIds whose title best matches $pageText, or null.
     *
     * @param  array<int|string> $categoryIds
     */
    public static function blog(string $pageText, array $categoryIds): ?Blog
    {
        if (!$categoryIds) {
            return null;
        }
        $candidates = Blog::whereIn('category_id', $categoryIds)->orderBy('sort_order')->orderBy('id')->get()->keyBy('id');
        $best = Related::bestTitleMatch($pageText, $candidates->map(fn ($b) => $b->title)->all());

        return $best !== null ? $candidates[$best] : null;
    }
}
