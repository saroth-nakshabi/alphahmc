<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'email',
        'position',
        'content',
        'author_image',
        'featured',
        'company_name',
        'rating',
        'service_id',
        'approved',
        'source',
    ];

    protected $casts = [
        'featured'  => 'boolean',
        'approved'  => 'boolean',
        'rating'    => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}