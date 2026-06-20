<?php

namespace App\Models;

use App\Models\Concerns\ResolvesProcess;
use Illuminate\Database\Eloquent\Model;

class ServiceGroup extends Model
{
    use ResolvesProcess;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'hero_image',
        'description',
        'is_featured',
        'agent_id',
        'inq_officer_name',
        'inq_officer_phone',
        'content',
        'overview',
        'service_details_header',
        'service_header',
        'core_service_header',
        'core_service_description',
        'process_header',
        'process_description',
        'process_service_ids',
        'process_intro',
        'project_process_id',
        'info_four',
        'announcement_id',
        'related_services',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_date',
        'updated_date',
        'status',
        'category_id',
        'show_testimonials',
    ];

    protected $casts = [
        'related_services' => 'array',
        'published_date'   => 'date',
        'updated_date'     => 'date',
    ];

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

    /** The shared Project Process this service group is linked to (if any). */
    public function projectProcess()
    {
        return $this->belongsTo(\App\Models\ProjectProcess::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_service_group', 'service_group_id', 'category_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_group_services', 'service_group_id', 'service_id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'service_group_id');
    }


    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}