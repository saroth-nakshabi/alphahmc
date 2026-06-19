<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStaff extends Model
{
    use HasFactory;

    protected $table = 'about_staff';

    protected $fillable = [
        'name',
        'title',
        'image',
        'short_description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
