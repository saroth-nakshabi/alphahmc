<?php

namespace App\Models\Concerns;

/**
 * Centralised process resolution.
 *
 * A Category / ServiceGroup / Service may be linked to a single ProjectProcess
 * (via project_process_id). When linked, the process_* attributes resolve LIVE
 * from that central ProjectProcess row — so the Project Process Manager is the
 * one source of truth and editing it updates every page at once.
 *
 * When NOT linked, the record's own legacy process_* columns are used as a
 * fallback (so older records that were edited inline before centralisation keep
 * rendering until they're linked).
 */
trait ResolvesProcess
{
    /** The linked central process, or null when the record is standalone. */
    protected function processSource()
    {
        return $this->project_process_id ? $this->projectProcess : null;
    }

    protected static function decodeProcessArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        $decoded = json_decode((string) $value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        return ($value === null || $value === '') ? [] : [$value];
    }

    public function getProcessIntroAttribute($value)
    {
        if ($src = $this->processSource()) {
            return $src->process_intro;
        }
        return $value;
    }

    public function getProcessHeaderAttribute($value): array
    {
        if ($src = $this->processSource()) {
            return (array) $src->process_header;
        }
        return static::decodeProcessArray($value);
    }

    public function getProcessDescriptionAttribute($value): array
    {
        if ($src = $this->processSource()) {
            return (array) $src->process_description;
        }
        return static::decodeProcessArray($value);
    }

    public function getProcessServiceIdsAttribute($value): array
    {
        if ($src = $this->processSource()) {
            return (array) $src->process_service_ids;
        }
        return static::decodeProcessArray($value);
    }

    // ── Legacy setters: still write the own columns (harmless fallback store) ──
    public function setProcessHeaderAttribute($value)
    {
        $this->attributes['process_header'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }

    public function setProcessDescriptionAttribute($value)
    {
        $this->attributes['process_description'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }

    public function setProcessServiceIdsAttribute($value)
    {
        $this->attributes['process_service_ids'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }
}
