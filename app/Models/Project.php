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
        'challenge_title',
        'challenge',
        'resolution',
        'challenges',
        // 'meta_title',
        // 'meta_description',
        // 'meta_keywords'
    ];

    protected $casts = [
        'challenges' => 'array',
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