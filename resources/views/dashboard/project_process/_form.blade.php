@php
    $isEdit = $process->exists;
    $action = $isEdit ? route('admin.project-process.update', $process->id) : route('admin.project-process.store');
@endphp

<form id="pp_form" action="{{ $action }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Process content --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Process details</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Process name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                            value="{{ old('name', $process->name) }}" placeholder="e.g. New Facility Licensing Process">
                        <div class="form-text">An internal label so you can recognise this process in the manager.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Process section introduction
                            <span class="text-muted fw-normal">(shown above the steps)</span></label>
                        <textarea name="process_intro" id="process_intro" rows="4" class="form-control"
                            placeholder="e.g. From first call to licence in hand — describe your approach...">{{ old('process_intro', $process->process_intro) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Project timeframe
                            <span class="text-muted fw-normal">(internal — used only by AI planner)</span></label>
                        <input type="text" name="timeframe" class="form-control"
                            value="{{ old('timeframe', $process->timeframe) }}"
                            placeholder="e.g. 8–12 weeks depending on authority readiness">
                        <div class="form-text">Not shown to customers. Fed to the AI planner to improve timeline accuracy.</div>
                    </div>
                </div>
            </div>

            {{-- Steps --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-semibold mb-0">Process steps
                            <span class="badge bg-light-primary text-primary ms-1" id="process-count">{{ count($processItems) }} {{ count($processItems) === 1 ? 'item' : 'items' }}</span>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-info" id="addProcessBtn"><i class="ti ti-plus me-1"></i> Add step</button>
                    </div>

                    <div id="process-empty-state" class="text-center text-muted py-4 {{ count($processItems) > 0 ? 'd-none' : '' }}">
                        <i class="ti ti-list-numbers fs-7"></i>
                        <p class="mb-0 mt-2">No steps yet. Click <strong>Add step</strong> to build your process.</p>
                    </div>

                    <div id="process-accordion" class="accordion">
                        @foreach ($processItems as $idx => $item)
                            <div class="accordion-item process-section-item mb-2 border rounded" id="process-item-p{{ $idx }}">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $idx > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#process-collapse-p{{ $idx }}"
                                        aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
                                        <span class="badge me-2 text-white" style="background:#0891b2;min-width:26px">{{ $idx + 1 }}</span>
                                        <span class="process-item-title">{{ $item['header'] ?: 'Process Step' }}</span>
                                    </button>
                                </h2>
                                <div id="process-collapse-p{{ $idx }}" class="accordion-collapse collapse {{ $idx === 0 ? 'show' : '' }}">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Process header</label>
                                                <input type="text" name="process_header[]" class="form-control process-header-input"
                                                    placeholder="e.g. Initial Assessment" value="{{ $item['header'] }}" />
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Process description</label>
                                                <textarea id="process_desc_p{{ $idx }}" name="process_description[]" rows="4"
                                                    class="rich-textarea form-control" placeholder="Process step description...">{{ $item['desc'] }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Related service <span class="text-muted fw-normal">(optional)</span></label>
                                                <select name="process_service_ids[]" class="form-control">
                                                    <option value="">— No service —</option>
                                                    @foreach ($services as $svc)
                                                        <option value="{{ $svc->id }}" {{ (string)($item['service_id'] ?? '') === (string)$svc->id ? 'selected' : '' }}>{{ $svc->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-process-section"><i class="ti ti-trash me-1"></i> Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Assignment --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-1">Assign to</h5>
                    <p class="text-muted small mb-3">Pick the categories and service groups that should use this process. They'll all share these steps; editing here updates them together.</p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service categories</label>
                        <select name="category_ids[]" class="form-control select2-assign" multiple data-placeholder="Select categories…">
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" {{ in_array($c->id, $assignedCategoryIds) ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service groups</label>
                        <select name="group_ids[]" class="form-control select2-assign" multiple data-placeholder="Select service groups…">
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}" {{ in_array($g->id, $assignedGroupIds) ? 'selected' : '' }}>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Services</label>
                        <select name="service_ids[]" class="form-control select2-assign" multiple data-placeholder="Select services…">
                            @foreach ($services as $s)
                                <option value="{{ $s->id }}" {{ in_array($s->id, $assignedServiceIds ?? []) ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">The process will display on each selected service's page.</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy me-1"></i> {{ $isEdit ? 'Save & apply' : 'Create & apply' }}</button>
                    <a href="{{ route('admin.project-process.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
