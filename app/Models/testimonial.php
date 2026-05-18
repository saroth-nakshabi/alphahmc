<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'position',
        'content',
        'author_image',
        'featured',
        'company_name',
        'rating',
    ];
}