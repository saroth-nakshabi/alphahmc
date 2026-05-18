<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'main_title',
        // 'post_title',
        'pre_title',
        'button_text',
        'button_link',
        'status',
    ];
}