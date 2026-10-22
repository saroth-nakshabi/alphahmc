<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([PublishedScope::class])]
class Blog extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    // status is not fillable: only the admin Publish actions (and the migration default) set it.
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
        'reviewer_name',
        'reviewer_credential',
        'reviewer_url',
        'last_reviewed',
        'sources',
    ];

    protected $casts = [
        'published_date'   => 'date',
        'updated_date'     => 'date',
        'last_reviewed'    => 'date',
        'sources'          => 'array',
        'admin_edited_at'  => 'datetime',
        'agent_written_at' => 'datetime',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function revision()
    {
        return $this->hasOne(BlogRevision::class);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }
}
