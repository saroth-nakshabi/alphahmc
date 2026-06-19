<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'hero_image',
        'sliding_image',
        'overview',
        'content',
        'info_one',
        'info_two',
        'info_three',
        'info_four',
        'related_services',
        'announcement_id',
        'featured',
        'status',
        'published_date',
        'updated_date',
        'agent_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'areaServed',
        'serviceType',
        'inq_officer_name',
        'inq_officer_phone',
        'show_testimonials',
        'sort_order',
    ];


    

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    protected $casts = [
        'related_services' => 'array',
        'published_date'   => 'date',
        'updated_date'     => 'date',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'service_categories');
    }

    public function images()
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function ServiceTab()
    {
        return $this->hasMany(TapService::class);
    }

    public function upComingSchedules()
    {
        return $this->schedules()->upcoming();
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function faq()
    {
        return $this->hasMany(Faq::class);
    }

    public function magazines()
    {
        return $this->hasMany(ServiceMagazine::class);
    }
}
