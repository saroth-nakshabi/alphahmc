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
        'featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags');
    }
}
