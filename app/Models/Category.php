<?php

namespace App\Models;

use App\Models\Concerns\ResolvesProcess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use ResolvesProcess;

    protected $fillable = [
        'main_category_id',
        'name',
        'slug',
        'image',
        'hero_image',
        'description',
        'featured',
        'agent_id',
        'inq_officer_name',
        'inq_officer_phone',
        'content',
        'overview',
        'service_header',
        'core_service_header',
        'core_service_description',
        'process_header',
        'process_description',
        'process_service_ids',
        'process_intro',
        'project_process_id',
        'info_three',
        'info_four',
        'announcement_id',
        'related_services',
        'sliding_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'areaServed',
        'serviceType',
        'sort_order',
    ];

    protected $casts = [
        'related_services' => 'array',
    ];

    /**
     * Path (relative to public/) of the home page card image.
     * Normalizes the mixed `image` formats (full path vs bare filename)
     * and falls back to the hero image when no card image is set.
     */
    public function getCardImageAttribute()
    {
        if ($this->image) {
            return str_starts_with($this->image, 'uploads/')
                ? $this->image
                : 'uploads/category_images/' . $this->image;
        }

        return $this->hero_image ?: null;
    }

    public function getCoreServiceHeaderAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if ($value === null) {
            return [];
        }

        return [$value];
    }

    public function getCoreServiceDescriptionAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if ($value === null) {
            return [];
        }

        return [$value];
    }

    public function setCoreServiceHeaderAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['core_service_header'] = json_encode(array_values($value));
        } else {
            $this->attributes['core_service_header'] = $value;
        }
    }

    public function setCoreServiceDescriptionAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['core_service_description'] = json_encode(array_values($value));
        } else {
            $this->attributes['core_service_description'] = $value;
        }
    }

    // Process attributes resolve from the linked ProjectProcess — see ResolvesProcess trait.

    /** The shared Project Process this category is linked to (if any). */
    public function projectProcess()
    {
        return $this->belongsTo(\App\Models\ProjectProcess::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class);
    }


    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_categories');
    }

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function mainCategories()
    {
        return $this->belongsToMany(MainCategory::class, 'category_main_category');
    }

    public function serviceGroups()
    {
        return $this->belongsToMany(ServiceGroup::class, 'category_service_group', 'category_id', 'service_group_id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function magazines()
    {
        return $this->hasMany(ServiceMagazine::class);
    }
}