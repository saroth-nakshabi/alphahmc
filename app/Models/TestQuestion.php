<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'question',];

    public function test_answers()
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}