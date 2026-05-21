<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialSetting extends Model
{
    protected $fillable = ['hero_message'];

    public static function current(): self
    {
        return self::firstOrCreate([], ['hero_message' => 'What our clients say about us']);
    }
}
