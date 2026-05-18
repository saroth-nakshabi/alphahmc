<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class about_quote extends Model
{
    use HasFactory;

    protected $fillable  = [
        'About_quote',
        'quote_title',
        // 'subtitle',
        'company_name',
        // 'image',
    ];
}
