<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'suffix',
        'label',
        'sort_order',
    ];

    protected $casts = [
        'value'      => 'integer',
        'sort_order' => 'integer',
    ];
}
