<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['test_question_id', 'answer', 'is_correct'];

    public function test_question()
    {
        return $this->belongsTo(TestQuestion::class);
    }
}