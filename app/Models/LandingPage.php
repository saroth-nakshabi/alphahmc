<?php

namespace App\Models;

use App\Support\LandingPageSchema;
use Illuminate\Database\Eloquent\Model;

/**
 * A landing page served at /facility-setup/{slug} (brief "alphahmc-facility-landing-build", 30 Oct 2026).
 * Copy lives in `content` (see LandingPageSchema); `lead_type` picks the Google Ads labels
 * (App\Support\LeadType). Every save from the admin also writes a LandingPageRevision.
 */
class LandingPage extends Model
{
    public const STATUSES = ['draft' => 'Draft', 'published' => 'Published'];
    public const LEAD_TYPES = ['facility' => 'Facility (Facility WhatsApp, Facility form)', 'general' => 'Professional (WhatsApp Click, Inquiry Sent)'];

    /** Fields a revision snapshot restores (everything an admin can edit). */
    public const SNAPSHOT_FIELDS = [
        'name', 'slug', 'status', 'lead_type', 'facility_type', 'emirate', 'seo_title', 'meta_description',
        'canonical', 'indexable', 'hero_image', 'hero_image_alt', 'related_ids', 'content',
    ];

    protected $fillable = [
        'name', 'slug', 'status', 'lead_type', 'facility_type', 'emirate', 'seo_title', 'meta_description',
        'canonical', 'indexable', 'hero_image', 'hero_image_alt', 'related_ids', 'content', 'published_at', 'updated_by',
    ];

    protected $casts = [
        'indexable'    => 'boolean',
        'related_ids'  => 'array',
        'content'      => 'array',
        'published_at' => 'datetime',
    ];

    public function revisions()
    {
        return $this->hasMany(LandingPageRevision::class)->orderByDesc('id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function url(): string
    {
        return route('front.landing', $this->slug);
    }

    /** Content in the full schema shape, whatever was stored. */
    public function contentData(): array
    {
        return LandingPageSchema::normalize($this->content ?? []);
    }

    /** Pages for the facility links in the hero, in the chosen order (this page included where chosen). */
    public function relatedPages()
    {
        $ids = array_values(array_filter(array_map('intval', $this->related_ids ?? [])));
        if (!$ids) {
            return collect();
        }

        $pages = static::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(fn ($id) => $pages->get($id))
            ->filter(fn ($p) => $p && ($p->isPublished() || $p->id === $this->id))->values();
    }

    public function snapshot(): array
    {
        $data = [];
        foreach (self::SNAPSHOT_FIELDS as $field) {
            $data[$field] = $this->getAttribute($field);
        }

        return $data;
    }
}
