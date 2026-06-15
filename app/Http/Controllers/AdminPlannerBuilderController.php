<?php

namespace App\Http\Controllers;

use App\Models\PlannerWorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminPlannerBuilderController extends Controller
{
    public function index()
    {
        $steps = PlannerWorkflowStep::ordered()->get();
        return view('dashboard.planner.builder', compact('steps'));
    }

    /** Persist drag-and-drop order. */
    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:planner_workflow_steps,id']);
        foreach ($request->order as $position => $id) {
            PlannerWorkflowStep::where('id', $id)->update(['sort_order' => $position + 1]);
        }
        $this->flush();
        return response()->json(['success' => true, 'message' => 'Workflow order saved!']);
    }

    /** Update a single step. */
    public function update(Request $request, $id)
    {
        $step = PlannerWorkflowStep::findOrFail($id);
        $data = $request->validate([
            'label'         => 'required|string|max:160',
            'help_text'     => 'nullable|string|max:255',
            'icon'          => 'nullable|string|max:80',
            'enabled'       => 'nullable|boolean',
            'options_text'  => 'nullable|string',
            'admin_content' => 'nullable|string',
        ]);

        $options = null;
        if (in_array($step->kind, ['choice', 'multichoice'], true) && $step->source === 'custom' || $step->source === 'regions') {
            $options = collect(preg_split('/\r\n|\r|\n/', (string) ($data['options_text'] ?? '')))
                ->map(fn ($l) => trim($l))->filter()->values()->all();
        }

        $step->update([
            'label'         => $data['label'],
            'help_text'     => $data['help_text'] ?? null,
            'icon'          => $data['icon'] ?: $step->icon,
            'enabled'       => $request->boolean('enabled'),
            'options'       => $options !== null ? $options : $step->options,
            'admin_content' => $step->kind === 'admin' ? ($data['admin_content'] ?? null) : $step->admin_content,
        ]);
        $this->flush();
        return back()->with('success', 'Step updated.');
    }

    /** Add a custom step. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string|max:160',
            'kind'  => 'required|in:choice,multichoice,text,admin',
            'options_text' => 'nullable|string',
            'admin_content' => 'nullable|string',
        ]);

        $options = null;
        if (in_array($data['kind'], ['choice', 'multichoice'], true)) {
            $options = collect(preg_split('/\r\n|\r|\n/', (string) ($data['options_text'] ?? '')))
                ->map(fn ($l) => trim($l))->filter()->values()->all();
        }

        PlannerWorkflowStep::create([
            'step_key'      => 'custom_' . Str::random(6),
            'label'         => $data['label'],
            'icon'          => $data['kind'] === 'admin' ? 'fa-solid fa-note-sticky' : 'fa-solid fa-circle-dot',
            'kind'          => $data['kind'],
            'source'        => $data['kind'] === 'admin' ? 'none' : 'custom',
            'options'       => $options,
            'admin_content' => $data['kind'] === 'admin' ? ($data['admin_content'] ?? null) : null,
            'enabled'       => true,
            'is_core'       => false,
            'sort_order'    => (int) PlannerWorkflowStep::max('sort_order') + 1,
        ]);
        $this->flush();
        return back()->with('success', 'Step added.');
    }

    public function destroy($id)
    {
        $step = PlannerWorkflowStep::findOrFail($id);
        if ($step->is_core) {
            return back()->with('error', 'Core steps cannot be deleted — disable them instead.');
        }
        $step->delete();
        $this->flush();
        return back()->with('success', 'Step removed.');
    }

    private function flush(): void
    {
        Cache::forget('planner_workflow_v1');
    }
}
