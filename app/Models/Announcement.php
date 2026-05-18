<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_link',
        'image',
        'status',
        'feature',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
