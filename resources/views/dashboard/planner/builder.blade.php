@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none mb-4">
        <div class="card-body px-4 py-3">
            <h4 class="fw-semibold mb-1"><i class="ti ti-layout-grid-add me-2"></i>AI Planner Builder</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Planner Builder</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <h5 class="mb-0 fw-semibold">Workflow steps</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addStepModal"><i class="ti ti-plus"></i> Add step</button>
                            <button class="btn btn-sm btn-success" id="saveOrderBtn" style="display:none"><i class="ti ti-arrows-sort me-1"></i>Save order</button>
                        </div>
                    </div>
                    <p class="text-muted small mb-3"><i class="ti ti-info-circle"></i>
                        Drag steps to set the order visitors experience. <strong>Customer steps</strong> are shown in the planner;
                        <strong>internal blocks</strong> (e.g. Process, Cost &amp; timeline) are never shown — they guide the AI to produce a richer, unique plan.
                    </p>

                    <ul id="stepList" class="list-unstyled mb-0">
                        @foreach($steps as $step)
                            <li class="builder-step" data-id="{{ $step->id }}">
                                <div class="bs-head">
                                    <span class="bs-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>
                                    <span class="bs-icon"><i class="{{ $step->icon ?: 'fa-solid fa-circle-dot' }}"></i></span>
                                    <div class="bs-meta">
                                        <div class="bs-label">{{ $step->label }}
                                            @if(!$step->enabled)<span class="badge bg-light-secondary text-muted ms-1">disabled</span>@endif
                                        </div>
                                        <div class="bs-sub">
                                            <span class="badge {{ $step->kind === 'admin' ? 'bg-light-warning text-warning' : 'bg-light-primary text-primary' }}">
                                                {{ $step->kind === 'admin' ? 'Internal block' : 'Customer · '.$step->kind }}
                                            </span>
                                            @if(in_array($step->source, ['categories','services']))
                                                <span class="text-muted small ms-1">options auto-loaded from your {{ $step->source }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#edit-{{ $step->id }}"><i class="ti ti-edit"></i></button>
                                </div>

                                <div class="collapse" id="edit-{{ $step->id }}">
                                    <form action="{{ route('admin.planner.builder.update', $step->id) }}" method="POST" class="bs-form">
                                        @csrf
                                        <div class="row g-2">
                                            <div class="col-md-8">
                                                <label class="form-label small fw-semibold mb-1">Label (the question / title)</label>
                                                <input type="text" name="label" class="form-control form-control-sm" value="{{ $step->label }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold mb-1">Icon (Font Awesome class)</label>
                                                <input type="text" name="icon" class="form-control form-control-sm" value="{{ $step->icon }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold mb-1">Help text</label>
                                                <input type="text" name="help_text" class="form-control form-control-sm" value="{{ $step->help_text }}">
                                            </div>

                                            @if($step->kind === 'admin')
                                                <div class="col-12">
                                                    <label class="form-label small fw-semibold mb-1">Internal guidance (shapes the AI plan — never shown to visitors)</label>
                                                    <textarea name="admin_content" rows="4" class="form-control form-control-sm">{{ $step->admin_content }}</textarea>
                                                </div>
                                            @elseif(in_array($step->source, ['custom','regions']) && in_array($step->kind, ['choice','multichoice']))
                                                <div class="col-12">
                                                    <label class="form-label small fw-semibold mb-1">Options (one per line)</label>
                                                    <textarea name="options_text" rows="5" class="form-control form-control-sm">{{ implode("\n", $step->options ?? []) }}</textarea>
                                                </div>
                                            @endif

                                            <div class="col-12 d-flex align-items-center justify-content-between mt-1">
                                                <div class="form-check form-switch mb-0">
                                                    <input type="checkbox" class="form-check-input" name="enabled" value="1" id="en-{{ $step->id }}" {{ $step->enabled ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="en-{{ $step->id }}">Enabled</label>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    @unless($step->is_core)
                                                        <button type="button" class="btn btn-sm btn-light-danger text-danger" onclick="delStep({{ $step->id }})">Delete</button>
                                                    @endunless
                                                    <button type="submit" class="btn btn-sm btn-primary">Save step</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @unless($step->is_core)
                                        <form id="delForm-{{ $step->id }}" action="{{ route('admin.planner.builder.destroy', $step->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                    @endunless
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold">How it works</h6>
                    <p class="text-muted small">Visitors answer the <strong>customer steps</strong> in order. The first inputs
                        (scope, location, areas, services) plus your <strong>internal blocks</strong> feed the AI to produce a
                        unique plan with a process, recommended services, and cost &amp; timeline.</p>
                    <p class="text-muted small mb-0"><a href="{{ route('planner.page') }}" target="_blank">Preview the live planner <i class="ti ti-external-link"></i></a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Add step modal --}}
    <div class="modal fade" id="addStepModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('admin.planner.builder.add') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add a step</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label</label>
                        <input type="text" name="label" class="form-control" required placeholder="e.g. Your budget range">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="kind" class="form-select" id="addKind">
                            <option value="choice">Customer — single choice</option>
                            <option value="multichoice">Customer — multiple choice</option>
                            <option value="text">Customer — free text</option>
                            <option value="admin">Internal block (AI guidance only)</option>
                        </select>
                    </div>
                    <div class="mb-3" id="addOptionsWrap">
                        <label class="form-label fw-semibold">Options (one per line)</label>
                        <textarea name="options_text" rows="4" class="form-control" placeholder="Option A&#10;Option B"></textarea>
                    </div>
                    <div class="mb-3 d-none" id="addAdminWrap">
                        <label class="form-label fw-semibold">Internal guidance</label>
                        <textarea name="admin_content" rows="4" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Add step</button></div>
            </form>
        </div>
    </div>

    <style>
        .builder-step { background:#fff; border:1px solid #e8eef2; border-radius:14px; margin-bottom:12px; }
        .bs-head { display:flex; align-items:center; gap:14px; padding:16px 18px; }
        .bs-handle { cursor:grab; color:#c2cdd4; font-size:1.1rem; }
        .builder-step:hover .bs-handle { color:#66787f; }
        .bs-icon { width:40px; height:40px; border-radius:10px; background:#eef6f6; color:#066D77; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .bs-meta { flex:1; }
        .bs-label { font-weight:600; color:#0d2126; }
        .bs-sub { margin-top:3px; }
        .bs-form { padding:0 18px 18px; border-top:1px solid #f1f5f7; padding-top:16px; }
        .bs-placeholder { height:64px; border:2px dashed #cfe0e2; border-radius:14px; margin-bottom:12px; }
        .bs-dragging { opacity:.85; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            $('#stepList').sortable({
                handle: '.bs-handle', items: '> .builder-step', placeholder: 'bs-placeholder',
                helper: 'clone', forcePlaceholderSize: true,
                start: (e, ui) => ui.item.addClass('bs-dragging'),
                stop:  (e, ui) => ui.item.removeClass('bs-dragging'),
                update: () => $('#saveOrderBtn').show(),
            });
            $('#saveOrderBtn').on('click', function () {
                const order = $('#stepList .builder-step').map(function(){ return $(this).data('id'); }).get();
                const $b = $(this).prop('disabled', true);
                $.ajax({ url: '{{ route('admin.planner.builder.reorder') }}', method: 'POST',
                    data: { order }, headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') || '{{ csrf_token() }}' },
                    success: r => { Toast.fire ? Toast.fire({icon:'success',title:r.message}) : Swal.fire({icon:'success',title:r.message,timer:2000}); $b.prop('disabled',false).hide(); },
                    error: () => { Swal.fire({icon:'error',title:'Could not save order'}); $b.prop('disabled',false); } });
            });
            // Add-step modal field toggle
            $('#addKind').on('change', function () {
                const admin = this.value === 'admin';
                const hasOptions = this.value === 'choice' || this.value === 'multichoice';
                $('#addAdminWrap').toggleClass('d-none', !admin);
                $('#addOptionsWrap').toggleClass('d-none', !hasOptions);
            }).trigger('change');

            @if(session('success')) Swal.fire({ title:'Done', text:@json(session('success')), icon:'success', timer:2500, timerProgressBar:true }); @endif
            @if(session('error')) Swal.fire({ title:'Heads up', text:@json(session('error')), icon:'error' }); @endif
        });
        function delStep(id){ Swal.fire({title:'Remove this step?',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33'}).then(r=>{ if(r.isConfirmed) document.getElementById('delForm-'+id).submit(); }); }
    </script>
@endsection
