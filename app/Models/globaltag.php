<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class globaltag extends Model
{
    use HasFactory;

     protected $table = 'globaltags'; // explicit table name

    protected $fillable = [
        'globaltag_name',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array', // if tags is stored as JSON
    ];
}
