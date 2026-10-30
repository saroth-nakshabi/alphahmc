<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A card in "Set up any type of healthcare facility" on the facility licensing category page (SEO brief
 * "alphahmc-facility-types", 30 Oct 2026). target is "category:ID", "service:ID" or "package:ID"; only indexable
 * pages can be targets, so never a /facility-setup/ landing page. A card whose target is gone or unpublished hides.
 */
class FacilityTypeCard extends Model
{
    /** The category page that shows the cards. */
    public const HOST_CATEGORY = 'facility-licensing-setup';

    protected $fillable = ['name', 'line', 'target', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];

    /** Active cards whose target is live, in order, each with its url. */
    public static function visible()
    {
        $cards = static::where('active', true)->orderBy('sort_order')->orderBy('id')->get();
        $targets = static::targets();

        return $cards->filter(fn ($c) => isset($targets[$c->target]))
            ->each(fn ($c) => $c->setAttribute('url', $targets[$c->target]['url']))->values();
    }

    /**
     * Pages a card may link to: every category, published services and published packages.
     *
     * @return array<string, array{label: string, url: string}>
     */
    public static function targets(): array
    {
        return once(function () {
            $out = [];
            foreach (Category::orderBy('name')->get(['id', 'name', 'slug']) as $c) {
                if ($c->slug) {
                    $out["category:{$c->id}"] = ['label' => 'Category: ' . $c->name, 'url' => route('front.service-category', $c->slug)];
                }
            }
            foreach (Service::published()->orderBy('name')->get(['id', 'name', 'slug']) as $s) {
                $out["service:{$s->id}"] = ['label' => 'Service: ' . $s->name, 'url' => route('front.service', $s->slug)];
            }
            foreach (ServiceGroup::published()->orderBy('name')->get(['id', 'name', 'slug']) as $g) {
                $out["package:{$g->id}"] = ['label' => 'Package: ' . $g->name, 'url' => route('service-packages', $g->slug)];
            }

            return $out;
        });
    }
}
