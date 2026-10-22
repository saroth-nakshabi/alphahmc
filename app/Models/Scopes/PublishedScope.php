<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Blog drafts are never public: every Blog query (lists, search, related blocks, relations such
 * as Tag::blogs(), the sitemap) only sees published posts. The admin, the signed preview and the
 * agent API opt out with withoutGlobalScope(PublishedScope::class), never withoutGlobalScopes().
 */
class PublishedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($model->qualifyColumn('status'), 'published');
    }
}
