<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerWorkflowStep extends Model
{
    protected $fillable = [
        'step_key', 'label', 'help_text', 'icon', 'kind', 'source',
        'options', 'admin_content', 'enabled', 'is_core', 'sort_order',
    ];

    protected $casts = [
        'options'  => 'array',
        'enabled'  => 'boolean',
        'is_core'  => 'boolean',
    ];

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }
}
