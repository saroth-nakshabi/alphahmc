<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProjectProcess;
use App\Models\Service;
use App\Models\ServiceGroup;
use Illuminate\Http\Request;

class AdminProjectProcessController extends Controller
{
    /** View all created processes in one place. */
    public function index()
    {
        $processes = ProjectProcess::withCount(['categories', 'serviceGroups'])
            ->latest()->paginate(20);

        return view('dashboard.project_process.index', compact('processes'));
    }

    public function create()
    {
        return view('dashboard.project_process.create', [
            'process'    => new ProjectProcess(),
            'services'   => Service::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'groups'     => ServiceGroup::orderBy('name')->get(['id', 'name']),
            'assignedCategoryIds' => [],
            'assignedGroupIds'    => [],
            'processItems' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateProcess($request);
        $process = ProjectProcess::create($this->buildAttributes($request));
        $this->applyAssignments($process, $request);

        return redirect()->route('admin.project-process.index')
            ->with('success', 'Project process created and applied to the selected categories / service groups.');
    }

    public function edit($id)
    {
        $process = ProjectProcess::findOrFail($id);

        // Rebuild aligned step triplets for the editor.
        $headers  = (array) $process->process_header;
        $descs    = (array) $process->process_description;
        $svcIds   = (array) $process->process_service_ids;
        $count    = max(count($headers), count($descs), count($svcIds));
        $processItems = [];
        for ($i = 0; $i < $count; $i++) {
            $processItems[] = [
                'header'     => $headers[$i] ?? '',
                'desc'       => $descs[$i] ?? '',
                'service_id' => $svcIds[$i] ?? null,
            ];
        }

        return view('dashboard.project_process.edit', [
            'process'    => $process,
            'services'   => Service::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'groups'     => ServiceGroup::orderBy('name')->get(['id', 'name']),
            'assignedCategoryIds' => Category::where('project_process_id', $process->id)->pluck('id')->all(),
            'assignedGroupIds'    => ServiceGroup::where('project_process_id', $process->id)->pluck('id')->all(),
            'processItems' => $processItems,
        ]);
    }

    public function update(Request $request, $id)
    {
        $process = ProjectProcess::findOrFail($id);
        $this->validateProcess($request);
        $process->update($this->buildAttributes($request));
        $this->applyAssignments($process, $request);

        return redirect()->route('admin.project-process.index')
            ->with('success', 'Project process updated and re-applied to the selected categories / service groups.');
    }

    public function destroy($id)
    {
        $process = ProjectProcess::findOrFail($id);
        // Unlink (leave the already-copied content in place on each record).
        Category::where('project_process_id', $process->id)->update(['project_process_id' => null]);
        ServiceGroup::where('project_process_id', $process->id)->update(['project_process_id' => null]);
        $process->delete();

        return back()->with('success', 'Project process deleted. Linked categories / service groups keep their last content.');
    }

    // ── helpers ───────────────────────────────────────────────────────────
    private function validateProcess(Request $request): array
    {
        return $request->validate([
            'name'                  => 'required|string|max:255',
            'process_intro'         => 'nullable|string',
            'process_header'        => 'nullable|array',
            'process_header.*'      => 'nullable|string',
            'process_description'   => 'nullable|array',
            'process_description.*' => 'nullable|string',
            'process_service_ids'   => 'nullable|array',
            'process_service_ids.*' => 'nullable|exists:services,id',
            'category_ids'          => 'nullable|array',
            'category_ids.*'        => 'exists:categories,id',
            'group_ids'             => 'nullable|array',
            'group_ids.*'           => 'exists:service_groups,id',
        ]);
    }

    /** Build the process attributes from the aligned-triplet form inputs. */
    private function buildAttributes(Request $request): array
    {
        $rawHeaders  = $request->input('process_header', []);
        $rawDescs    = $request->input('process_description', []);
        $rawServices = $request->input('process_service_ids', []);
        $headers = $descs = $serviceIds = [];
        $stepCount = max(count($rawHeaders), count($rawDescs), count($rawServices));
        for ($i = 0; $i < $stepCount; $i++) {
            $h = trim((string) ($rawHeaders[$i] ?? ''));
            $d = $rawDescs[$i] ?? '';
            if ($h === '' && trim(strip_tags((string) $d)) === '') {
                continue;
            }
            $headers[]    = $h;
            $descs[]      = $d;
            $serviceIds[] = !empty($rawServices[$i]) ? (int) $rawServices[$i] : null;
        }

        return [
            'name'                => $request->input('name'),
            'process_intro'       => $request->input('process_intro'),
            'process_header'      => $headers,
            'process_description' => $descs,
            'process_service_ids' => $serviceIds,
        ];
    }

    /** Link the chosen categories / service groups and push the process content into them. */
    private function applyAssignments(ProjectProcess $process, Request $request): void
    {
        $categoryIds = array_map('intval', (array) $request->input('category_ids', []));
        $groupIds    = array_map('intval', (array) $request->input('group_ids', []));

        // Unlink records that were removed from the assignment (keep their content).
        Category::where('project_process_id', $process->id)->whereNotIn('id', $categoryIds ?: [0])
            ->update(['project_process_id' => null]);
        ServiceGroup::where('project_process_id', $process->id)->whereNotIn('id', $groupIds ?: [0])
            ->update(['project_process_id' => null]);

        // Link + push content into the selected categories.
        foreach (Category::whereIn('id', $categoryIds)->get() as $cat) {
            $cat->project_process_id  = $process->id;
            $cat->process_intro       = $process->process_intro;
            $cat->process_header      = $process->process_header;
            $cat->process_description = $process->process_description;
            $cat->process_service_ids = $process->process_service_ids;
            $cat->save();
        }

        // Link + push content into the selected service groups.
        foreach (ServiceGroup::whereIn('id', $groupIds)->get() as $grp) {
            $grp->project_process_id  = $process->id;
            $grp->process_intro       = $process->process_intro;
            $grp->process_header      = $process->process_header;
            $grp->process_description = $process->process_description;
            $grp->process_service_ids = $process->process_service_ids;
            $grp->save();
        }
    }
}
