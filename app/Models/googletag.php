<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class googletag extends Model
{

     use HasFactory;

     protected $table = 'googletags'; // explicit table name

    protected $fillable = [
        'googletag_name',
        'tags',
        'noscript_tags',
    ];

    protected $casts = [
        'tags' => 'array', // if tags is stored as JSON
        'noscript_tags' => 'array',
    ];
}
