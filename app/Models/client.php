<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'short_description',
        'description',
        'sort_order',
        'is_featured',
        'status',
    ];

    /** Clients allowed to appear on the website, in display order. */
    public function scopeVisible($query)
    {
        return $query->where('status', 1)->orderBy('sort_order')->orderBy('id');
    }
}
