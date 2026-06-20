<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A promotional slide shown in the main mega-menu's default (open) panel.
 * Up to 3 active promos auto-rotate as a slider — managed in the dashboard.
 */
class MenuPromo extends Model
{
    protected $fillable = [
        'eyebrow',
        'title',
        'text',
        'cta_label',
        'cta_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Max number of promos that can exist / show in the menu. */
    public const MAX = 3;

    /** Active promos for the menu, ordered. */
    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', 1)->orderBy('sort_order')->orderBy('id');
    }
}
