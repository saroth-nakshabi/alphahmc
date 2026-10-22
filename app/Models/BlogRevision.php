<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Model;

/**
 * A staged rewrite of a live post, written by the agent API and published by a person
 * (Publish revision in /manage-blog). Never public, never renames the post.
 */
class BlogRevision extends Model
{
    protected $fillable = [
        'blog_id',
        'title',
        'description',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sources',
        'reviewer_name',
        'reviewer_credential',
        'reviewer_url',
        'last_reviewed',
    ];

    protected $casts = [
        'sources'          => 'array',
        'last_reviewed'    => 'date',
        'admin_edited_at'  => 'datetime',
        'agent_written_at' => 'datetime',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class)->withoutGlobalScope(PublishedScope::class);
    }
}
