<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_category_id',
        'name',
        'description',
        'slug',
        'client_name',
        'project_duration',
        'project_location',
        'regulatory_authority',
        'client_website',
        'project_scope',
        'service_ids',
        'featured',
        'challenge_heading',
        'challenge_title',
        'challenge',
        'resolution',
        'challenges',
    ];

    protected $casts = [
        'featured'    => 'boolean',
        'challenges'  => 'array',
        'service_ids' => 'array',
    ];

    public function projects_images()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function projects_videos()
    {
        return $this->hasMany(ProjectVideo::class);
    }

    public function projects_documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function project_category()
    {
        return $this->belongsTo(ProjectCategory::class);
    }
}
