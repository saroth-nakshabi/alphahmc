<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'slug',
        'description',
        'news_focus',
        'author_name',
        'category_id',
        'read_time',
        'featured',
        'sort_order',
        'published_date',
        'updated_date',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'published_date' => 'date',
        'updated_date'   => 'date',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
}
