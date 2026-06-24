<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerOutcomeCache extends Model
{
    protected $table = 'planner_outcome_cache';

    protected $fillable = [
        'intent_key',
        'region_key',
        'category_fingerprint',
        'process_output',
        'timeline_output',
        'consultant_id',
        'session_id',
    ];
}
