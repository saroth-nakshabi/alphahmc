<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class about_content extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_title',
        'content',
        'image',
    ];
}
