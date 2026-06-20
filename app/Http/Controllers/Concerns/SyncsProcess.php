<?php

namespace App\Http\Controllers\Concerns;

use App\Models\ProjectProcess;

/**
 * Keeps a Category / ServiceGroup's inline process editor in sync with the
 * central project_processes table, so a process edited inside a category/group
 * also appears (and is editable) in the Project Process Manager.
 *
 * - If the record is already linked to a ProjectProcess, that process is updated.
 * - Otherwise, if there is any process content, a new ProjectProcess is created
 *   and linked to the record.
 */
trait SyncsProcess
{
    protected function syncProcess($record, ?string $intro, array $headers, array $descs, array $serviceIds, string $defaultName): void
    {
        $hasContent = trim(strip_tags((string) $intro)) !== ''
            || count(array_filter($headers, fn ($h) => trim((string) $h) !== '')) > 0;

        // Update the already-linked central process.
        if ($record->project_process_id && ($process = ProjectProcess::find($record->project_process_id))) {
            $process->update([
                'process_intro'       => $intro,
                'process_header'      => $headers,
                'process_description' => $descs,
                'process_service_ids' => $serviceIds,
            ]);
            return;
        }

        // Nothing entered and nothing linked — leave it standalone.
        if (!$hasContent) {
            return;
        }

        // Create a new central process and link this record to it.
        $process = ProjectProcess::create([
            'name'                => $defaultName,
            'process_intro'       => $intro,
            'process_header'      => $headers,
            'process_description' => $descs,
            'process_service_ids' => $serviceIds,
        ]);

        $record->project_process_id = $process->id;
        $record->save();
    }
}
