<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPlannerSession extends Model
{
    protected $fillable = [
        'uuid', 'intent', 'region', 'facility_type', 'selected_services',
        'free_text', 'answers', 'ai_solution', 'brief', 'cost_estimate', 'timeline_estimate', 'recommended_service_ids',
        'engine', 'process_source', 'ai_process_output',
        'consultant_outcome', 'consultant_notes', 'consultant_id', 'consultant_reviewed_at',
        'name', 'email', 'phone', 'consent', 'meeting_at', 'inquiry_id', 'status',
        'meeting_link', 'calendar_link', 'meeting_staff_id', 'meeting_confirmed',
    ];

    public function inquiry()
    {
        return $this->belongsTo(\App\Models\Inquiry::class);
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\User::class, 'meeting_staff_id');
    }

    public function consultant()
    {
        return $this->belongsTo(\App\Models\User::class, 'consultant_id');
    }

    protected $casts = [
        'selected_services'        => 'array',
        'answers'                  => 'array',
        'recommended_service_ids'  => 'array',
        'consent'                  => 'boolean',
        'meeting_confirmed'        => 'boolean',
        'meeting_at'               => 'datetime',
        'consultant_reviewed_at'   => 'datetime',
    ];
}
