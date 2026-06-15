<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A reusable "project process" (intro + aligned steps of header/description/linked-service).
 * Create it once in the Project Process Manager and assign it to many categories / service groups;
 * its content is pushed into their existing process_* columns so the public pages, the category /
 * service-group editors and the AI planner all keep working unchanged.
 */
class ProjectProcess extends Model
{
    protected $fillable = [
        'name',
        'process_intro',
        'process_header',
        'process_description',
        'process_service_ids',
    ];

    // ── JSON accessors / mutators (same shape as Category / ServiceGroup) ──
    public function getProcessHeaderAttribute($value)
    {
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return $decoded;
        return $value === null ? [] : [$value];
    }

    public function setProcessHeaderAttribute($value)
    {
        $this->attributes['process_header'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }

    public function getProcessDescriptionAttribute($value)
    {
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return $decoded;
        return $value === null ? [] : [$value];
    }

    public function setProcessDescriptionAttribute($value)
    {
        $this->attributes['process_description'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }

    public function getProcessServiceIdsAttribute($value)
    {
        if (is_array($value)) return $value;
        $decoded = json_decode($value ?? '', true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return $decoded;
        return [];
    }

    public function setProcessServiceIdsAttribute($value)
    {
        $this->attributes['process_service_ids'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }

    // ── Assignments ──
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function serviceGroups()
    {
        return $this->hasMany(ServiceGroup::class);
    }

    /** Number of process steps defined. */
    public function getStepCountAttribute(): int
    {
        return count(array_filter((array) $this->process_header, fn ($h) => trim((string) $h) !== ''))
            ?: count((array) $this->process_header);
    }
}
