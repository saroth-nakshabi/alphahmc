@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        .section-card { border: 1px solid #e8edf2; border-radius: 10px; margin-bottom: 1.25rem; }
        .section-card .section-header { background: #f8fafc; border-bottom: 1px solid #e8edf2; padding: .85rem 1.25rem; display: flex; align-items: center; gap: .6rem; }
        .section-badge { width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 700; flex-shrink: 0; }
        .section-body { padding: 1.25rem; }
        .sidebar-sticky { position: sticky; top: 76px; }
        .cst-accordion .accordion-item { border: 1px solid #dee2e6; border-radius: 8px !important; margin-bottom: .6rem; overflow: hidden; }
        .cst-accordion .accordion-button { font-weight: 600; font-size: .875rem; background: #fff; color: #2d3a4a; }
        .cst-accordion .accordion-button:not(.collapsed) { background: #eef4ff; color: #1a56db; box-shadow: none; }
        .cst-accordion .accordion-body { background: #fdfdff; padding: 1rem 1.25rem; }
        .empty-state { text-align: center; padding: 2rem 1rem; color: #9aa5b4; }
        .empty-state i { font-size: 2.2rem; margin-bottom: .5rem; display: block; }
        .sidebar-card { border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 1rem; }
        .sidebar-card .sidebar-card-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: .7rem 1rem; font-weight: 600; font-size: .85rem; display: flex; align-items: center; gap: .4rem; }
        .sidebar-card .sidebar-card-body { padding: 1rem; }
        .field-hint { font-size: .78rem; color: #6b7280; margin-top: .25rem; }
        .required-star { color: #ef4444; }
        label.form-label, .control-label { font-weight: 500; font-size: .85rem; margin-bottom: .3rem; display: block; }
        .item-count-badge { background: #e0f2fe; color: #0369a1; border-radius: 20px; padding: 2px 10px; font-size: .73rem; font-weight: 600; }
        .select2-container { display: block !important; }
        .img-preview { width: 120px; height: 80px; object-fit: cover; }
        .no-img-placeholder {
            width: 120px; height: 80px;
            border: 1px dashed #cbd5e1; border-radius: 8px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            color: #94a3b8; font-size: .7rem; gap: 2px; background: #f8fafc;
        }
        .no-img-placeholder i { font-size: 1.2rem; }
        .select2-container--default .select2-selection--multiple { height: auto !important; }
    </style>
@endsection

@section('content')

    @php
        // Service Pillars
        $coreHeaders = $category->core_service_header ?? [];
        $coreDescs   = $category->core_service_description ?? [];
        if (!is_array($coreHeaders)) {
            if (is_string($coreHeaders) && $coreHeaders !== '') {
                $decoded = json_decode($coreHeaders, true);
                $coreHeaders = is_array($decoded) ? $decoded : [$coreHeaders];
            } else { $coreHeaders = []; }
        }
        if (!is_array($coreDescs)) {
            if (is_string($coreDescs) && $coreDescs !== '') {
                $decoded = json_decode($coreDescs, true);
                $coreDescs = is_array($decoded) ? $decoded : [$coreDescs];
            } else { $coreDescs = []; }
        }
        $coreItems = [];
        $maxCore = max(count($coreHeaders), count($coreDescs), 0);
        for ($i = 0; $i < $maxCore; $i++) {
            $h = $coreHeaders[$i] ?? ''; $d = $coreDescs[$i] ?? '';
            if ($h !== '' || trim(strip_tags((string)$d)) !== '') {
                $coreItems[] = ['header' => $h, 'desc' => $d];
            }
        }

        // Process Steps
        $procHeaders = $category->process_header ?? [];
        $procDescs   = $category->process_description ?? [];
        if (!is_array($procHeaders)) {
            if (is_string($procHeaders) && $procHeaders !== '') {
                $decoded = json_decode($procHeaders, true);
                $procHeaders = is_array($decoded) ? $decoded : [$procHeaders];
            } else { $procHeaders = []; }
        }
        if (!is_array($procDescs)) {
            if (is_string($procDescs) && $procDescs !== '') {
                $decoded = json_decode($procDescs, true);
                $procDescs = is_array($decoded) ? $decoded : [$procDescs];
            } else { $procDescs = []; }
        }
        $procServiceIds = $category->process_service_ids ?? [];
        if (!is_array($procServiceIds)) {
            $decoded = json_decode($procServiceIds, true);
            $procServiceIds = is_array($decoded) ? $decoded : [];
        }
        $processItems = [];
        $maxProc = max(count($procHeaders), count($procDescs), 0);
        for ($i = 0; $i < $maxProc; $i++) {
            $h = $procHeaders[$i] ?? ''; $d = $procDescs[$i] ?? '';
            if ($h !== '' || trim(strip_tags((string)$d)) !== '') {
                $processItems[] = ['header' => $h, 'desc' => $d, 'service_id' => $procServiceIds[$i] ?? null];
            }
        }

        // Services selectable per process step: this category's own services,
        // falling back to all services when the category has none linked yet.
        $stepServices = $category->services->count() ? $category->services : $services;
    @endphp

    {{-- Breadcrumb --}}
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-edit me-2"></i>Edit Category</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                            <li class="breadcrumb-item active">{{ $category->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Error box --}}
    <div class="alert alert-danger d-none mb-4" id="server-error-box" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="ti ti-alert-circle mt-1 flex-shrink-0"></i>
            <div><strong>Please fix the following errors:</strong><ul class="mb-0 mt-1" id="server-error-list"></ul></div>
        </div>
    </div>

    <div class="row g-0">

        {{-- ═══════════════════════════ LEFT COLUMN ═══════════════════════════ --}}
        <div class="col-md-9 pe-3">

            <form action="{{ route('categories.update', $category->id) }}" method="POST"
                  id="edit_form" enctype="multipart/form-data">
                @csrf

                {{-- ── Section 1 · Basic Information ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-primary text-white">1</span>
                        <h6 class="mb-0 fw-semibold">Basic Information</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="control-label">Category Name <span class="required-star">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="e.g. Healthcare Licensing"
                                    value="{{ $category->name }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">URL Slug <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:.8rem">/categories/</span>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ $category->slug }}" required />
                                </div>
                                <div class="field-hint">Edit carefully — changing this breaks existing URLs.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Main Categories <span class="required-star">*</span></label>
                                <select name="main_category_ids[]" class="form-control select2"
                                    data-placeholder="Select main categories" multiple required>
                                    @foreach ($main_categories as $mc)
                                        <option value="{{ $mc->id }}"
                                            {{ ($category->mainCategories->contains($mc->id) || $category->main_category_id == $mc->id) ? 'selected' : '' }}>
                                            {{ $mc->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="field-hint">This category appears under the selected main categories.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Category Short Description <span class="required-star">*</span></label>
                                <textarea name="description" rows="4" class="rich-textarea form-control"
                                    placeholder="Brief description of this category..." required>{{ $category->description }}</textarea>
                                <div class="field-hint">Visible under the slider on the category page and on the home page cards.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label"><i class="ti ti-link me-1"></i>Linked Services</label>
                                <select name="services[]" class="form-control select2"
                                    data-placeholder="Select services to link to this category" multiple>
                                    @foreach ($services as $svc)
                                        <option value="{{ $svc->id }}"
                                            {{ $category->services->contains($svc->id) ? 'selected' : '' }}>
                                            {{ $svc->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="field-hint">All services that belong to this category. Add or remove here — the service itself is not deleted when unlinked.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 2 · Images ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-info text-white">2</span>
                        <h6 class="mb-0 fw-semibold">Images</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="control-label">Hero Section Image</label>
                                <input type="file" name="hero_image" class="form-control img-preview-input" accept="image/*" data-preview="hero-preview" />
                                <div class="mt-2">
                                    <img id="hero-preview" alt="Hero Image"
                                        src="{{ $category->hero_image ? asset('public/' . ltrim($category->hero_image, '/')) : '' }}"
                                        class="img-preview rounded shadow-sm {{ $category->hero_image ? '' : 'd-none' }}">
                                    <div id="hero-preview-ph" class="no-img-placeholder {{ $category->hero_image ? 'd-none' : '' }}">
                                        <i class="ti ti-photo-off"></i> No image
                                    </div>
                                </div>
                                <div class="field-hint">Main banner image for the category page. Upload a new file to replace.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Category Image</label>
                                <input type="file" name="image" class="form-control img-preview-input" accept="image/*" data-preview="card-preview" />
                                <div class="mt-2">
                                    <img id="card-preview" alt="Category Image"
                                        src="{{ $category->card_image ? asset('public/' . ltrim($category->card_image, '/')) : '' }}"
                                        class="img-preview rounded shadow-sm {{ $category->card_image ? '' : 'd-none' }}">
                                    <div id="card-preview-ph" class="no-img-placeholder {{ $category->card_image ? 'd-none' : '' }}">
                                        <i class="ti ti-photo-off"></i> No image
                                    </div>
                                    @if (!$category->image && $category->hero_image)
                                        <div class="field-hint" id="card-fallback-note"><i class="ti ti-info-circle"></i> Currently using the Hero Section Image.</div>
                                    @endif
                                </div>
                                <div class="field-hint">Shows on the home page (Our Latest Thinking) if the category is featured. If not uploaded, the Hero Section Image is used instead.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 3 · Page Content ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-success text-white">3</span>
                        <h6 class="mb-0 fw-semibold">Page Content</h6>
                        <small class="text-muted ms-1">— all optional</small>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="control-label">Main Content <span class="text-muted fw-normal">(overview section)</span></label>
                                <textarea name="overview" rows="6" class="rich-textarea form-control"
                                    placeholder="Main content shown below the hero...">{{ $category->overview }}</textarea>
                                <div class="field-hint">To add a header, type it in the editor and apply a heading style from the <strong>Paragraph ▾</strong> (blocks) dropdown in the toolbar.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">CTA Content <span class="text-muted fw-normal">(why choose us)</span></label>
                                <textarea name="info_four" rows="4" class="rich-textarea form-control"
                                    placeholder="Why choose us / benefits content...">{{ $category->info_four }}</textarea>
                                <div class="field-hint">To add a header, type it in the editor and apply a heading style from the <strong>Paragraph ▾</strong> (blocks) dropdown in the toolbar.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 4 · Service Pillars ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-white" style="background:#059669!important">4</span>
                            <h6 class="mb-0 fw-semibold">Service Pillars</h6>
                            <span class="item-count-badge" id="core-count">{{ count($coreItems) }} {{ count($coreItems) === 1 ? 'item' : 'items' }}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" id="addCoreServiceBtn">
                            <i class="ti ti-plus me-1"></i> Add Service Pillar
                        </button>
                    </div>
                    <div class="section-body p-3">
                        <div id="core-empty-state" class="empty-state {{ count($coreItems) > 0 ? 'd-none' : '' }}">
                            <i class="ti ti-layout-grid"></i>
                            <p class="mb-1 fw-semibold">No service pillars added yet</p>
                            <small>Click <strong>Add Service Pillar</strong> to highlight your key offerings.</small>
                        </div>
                        <div id="core-accordion" class="accordion cst-accordion">
                            @foreach ($coreItems as $idx => $item)
                                <div class="accordion-item core-service-section-item" id="core-item-p{{ $idx }}">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ $idx > 0 ? 'collapsed' : '' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#core-collapse-p{{ $idx }}"
                                            aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
                                            <span class="badge me-2 text-white" style="background:#059669;min-width:26px">{{ $idx + 1 }}</span>
                                            <span class="core-item-title">{{ $item['header'] ?: 'Service Pillar' }}</span>
                                        </button>
                                    </h2>
                                    <div id="core-collapse-p{{ $idx }}" class="accordion-collapse collapse {{ $idx === 0 ? 'show' : '' }}">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="control-label">Service Pillar Header</label>
                                                    <input type="text" name="core_service_header[]"
                                                        class="form-control core-header-input"
                                                        placeholder="e.g. Quality Management"
                                                        value="{{ $item['header'] }}" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="control-label">Service Pillar Description</label>
                                                    <textarea id="core_desc_p{{ $idx }}" name="core_service_description[]"
                                                        rows="4" class="rich-textarea form-control"
                                                        placeholder="Service pillar description...">{{ $item['desc'] }}</textarea>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-core-section">
                                                    <i class="ti ti-trash me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── Section 5 · Process Steps ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-white" style="background:#0891b2!important">5</span>
                            <h6 class="mb-0 fw-semibold">Process Steps</h6>
                            <span class="item-count-badge" id="process-count">{{ count($processItems) }} {{ count($processItems) === 1 ? 'item' : 'items' }}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-info" id="addProcessBtn">
                            <i class="ti ti-plus me-1"></i> Add Process Step
                        </button>
                    </div>
                    <div class="section-body p-3">
                        <div class="mb-3">
                            <label class="control-label">Process Section Introduction
                                <span class="text-muted fw-normal">(section title &amp; description shown above the steps)</span>
                            </label>
                            <textarea name="process_intro" id="process_intro" rows="4" class="form-control"
                                placeholder="e.g. From first call to license in hand — describe your process approach...">{{ $category->process_intro }}</textarea>
                            <div class="field-hint">If left empty, the default section title is shown.</div>
                        </div>
                        <div id="process-empty-state" class="empty-state {{ count($processItems) > 0 ? 'd-none' : '' }}">
                            <i class="ti ti-list-numbers"></i>
                            <p class="mb-1 fw-semibold">No process steps yet</p>
                            <small>Click <strong>Add Process Step</strong> to describe your workflow.</small>
                        </div>
                        <div id="process-accordion" class="accordion cst-accordion">
                            @foreach ($processItems as $idx => $item)
                                <div class="accordion-item process-section-item" id="process-item-p{{ $idx }}">
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
                                                    <label class="control-label">Process Header</label>
                                                    <input type="text" name="process_header[]"
                                                        class="form-control process-header-input"
                                                        placeholder="e.g. Initial Assessment"
                                                        value="{{ $item['header'] }}" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="control-label">Process Description</label>
                                                    <textarea id="process_desc_p{{ $idx }}" name="process_description[]"
                                                        rows="4" class="rich-textarea form-control"
                                                        placeholder="Process step description...">{{ $item['desc'] }}</textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="control-label">Related Service <span class="text-muted fw-normal">(optional)</span></label>
                                                    @php
                                                        $stepOpts = $stepServices;
                                                        if (!empty($item['service_id']) && !$stepOpts->contains('id', $item['service_id'])) {
                                                            $extra = $services->firstWhere('id', $item['service_id']);
                                                            if ($extra) $stepOpts = $stepOpts->concat([$extra]);
                                                        }
                                                    @endphp
                                                    <select name="process_service_ids[]" class="form-control">
                                                        <option value="">— No service —</option>
                                                        @foreach ($stepOpts as $svc)
                                                            <option value="{{ $svc->id }}" {{ (string)($item['service_id'] ?? '') === (string)$svc->id ? 'selected' : '' }}>{{ $svc->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="field-hint">The service name and its short description are shown under this step on the website.</div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-process-section">
                                                    <i class="ti ti-trash me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── Section 6 · SEO / Meta ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-secondary text-white">6</span>
                        <h6 class="mb-0 fw-semibold">SEO / Meta Details</h6>
                        <small class="text-muted ms-1">— all optional</small>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="control-label">Area Served</label>
                                <input type="text" name="areaServed" class="form-control"
                                    placeholder="e.g. Worldwide" value="{{ $category->areaServed }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Service Type</label>
                                <input type="text" name="serviceType" class="form-control"
                                    placeholder="e.g. Healthcare" value="{{ $category->serviceType }}" />
                            </div>
                            <div class="col-12">
                                <label class="control-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    placeholder="Page title for search engines" value="{{ $category->meta_title }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-control"
                                    placeholder="Short description for search results (150–160 chars)">{{ $category->meta_description }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Keywords</label>
                                <textarea name="meta_keywords" rows="3" class="form-control"
                                    placeholder="Comma-separated keywords">{{ $category->meta_keywords }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </form>{{-- /edit_form --}}

        </div>{{-- /col-md-9 --}}

        {{-- ═══════════════════════════ RIGHT SIDEBAR ═══════════════════════════ --}}
        <div class="col-md-3">
            <div class="sidebar-sticky">

                {{-- Save card --}}
                <div class="sidebar-card" style="border-color:#10b981!important">
                    <div class="sidebar-card-header" style="background:#d1fae5;color:#065f46">
                        <i class="ti ti-device-floppy"></i> Update Category
                    </div>
                    <div class="sidebar-card-body">
                        <div id="save-notification" class="d-none mb-2 p-2 rounded small fw-semibold" role="alert" style="word-break:break-word"></div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" id="updateBtn">
                                <i class="ti ti-device-floppy me-2"></i> Save Changes
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Categories
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Options card --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <i class="ti ti-adjustments-horizontal"></i> Options
                    </div>
                    <div class="sidebar-card-body">
                        <div class="d-flex align-items-center justify-content-between py-1 mb-3 border-bottom pb-3">
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">Featured Category</div>
                                <div class="field-hint mb-0">Show in featured sections</div>
                            </div>
                            <div class="form-check form-switch mb-0 ms-3">
                                <input type="checkbox" id="featured_toggle" role="switch"
                                    class="form-check-input" value="1"
                                    {{ $category->featured ? 'checked' : '' }} />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Connected Agent <span class="required-star">*</span></label>
                            <select id="agent_id" class="form-control select2-sidebar"
                                data-placeholder="Select staff member">
                                <option></option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ $category->agent_id == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->user->first_name . ' ' . $agent->user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="control-label">Inquiry Officer Name</label>
                            <input type="text" id="inq_officer_name" class="form-control"
                                placeholder="Officer name" value="{{ $category->inq_officer_name }}" />
                        </div>
                        <div>
                            <label class="control-label">Inquiry Officer Phone</label>
                            <input type="text" id="inq_officer_phone" class="form-control"
                                placeholder="e.g. 94774702259" value="{{ $category->inq_officer_phone }}" />
                            <div class="field-hint">WhatsApp number with country code (no + or 00)</div>
                        </div>
                    </div>
                </div>

                {{-- Announcement card --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <i class="ti ti-speakerphone"></i> Announcement
                    </div>
                    <div class="sidebar-card-body">
                        <select id="announcement_id" class="form-control select2-sidebar"
                            data-placeholder="Select announcement">
                            <option value="">No Announcement</option>
                            @foreach ($announcements as $ann)
                                <option value="{{ $ann->id }}"
                                    {{ $category->announcement_id == $ann->id ? 'selected' : '' }}>
                                    {{ $ann->title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint mt-1">Shown in the announcement banner.</div>
                    </div>
                </div>

                {{-- Tips card --}}
                <div class="sidebar-card" style="border-color:#bfdbfe!important">
                    <div class="sidebar-card-header" style="background:#eff6ff;color:#1e40af">
                        <i class="ti ti-bulb"></i> Quick Tips
                    </div>
                    <div class="sidebar-card-body" style="font-size:.82rem;color:#374151">
                        <ul class="mb-0 ps-3">
                            <li class="mb-1">Changing the <strong>slug</strong> affects existing page URLs.</li>
                            <li class="mb-1"><strong>Connected Agent</strong> is required before saving.</li>
                            <li class="mb-1">Upload a new image to replace the existing one.</li>
                            <li class="mb-0">Required fields are marked <span class="text-danger">*</span></li>
                        </ul>
                    </div>
                </div>

            </div>{{-- /sidebar-sticky --}}
        </div>{{-- /col-md-3 --}}

    </div>{{-- /row --}}
@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>
    <script>
    $(document).ready(function () {

        /* ─── TinyMCE ─── */
        function initTinyMCE(selector, extraCfg) {
            extraCfg = extraCfg || {};
            if (typeof tinymce !== 'undefined') {
                tinymce.init(Object.assign({
                    selector: selector,
                    plugins: 'code searchreplace autolink directionality visualblocks link media table charmap nonbreaking anchor advlist lists wordcount fullscreen',
                    toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright | bullist numlist | fullscreen code',
                    menubar: true,
                    height: 240,
                    automatic_uploads: true,
                    images_upload_url: '/upload-image',
                    branding: false,
                    promotion: false,
                }, extraCfg));
            }
        }

        initTinyMCE('.rich-textarea', { height: 240 });
        initTinyMCE('#process_intro', { height: 180, menubar: false });

        /* ─── Select2 ─── */
        $('.select2').select2({ minimumResultsForSearch: 8 });
        $('.select2-sidebar').select2({ minimumResultsForSearch: 8, dropdownParent: $('body') });

        /* ─── Image previews ─── */
        $(document).on('change', '.img-preview-input', function () {
            const file = this.files && this.files[0];
            if (!file) return;
            const id = $(this).data('preview');
            $('#' + id).attr('src', URL.createObjectURL(file)).removeClass('d-none');
            $('#' + id + '-ph').addClass('d-none');
            if (id === 'card-preview') $('#card-fallback-note').addClass('d-none');
        });

        /* ─── Live accordion header sync ─── */
        $(document).on('input', '.core-header-input',    function () { $(this).closest('.accordion-item').find('.core-item-title').text($(this).val().trim() || 'Service Pillar'); });
        $(document).on('input', '.process-header-input', function () { $(this).closest('.accordion-item').find('.process-item-title').text($(this).val().trim() || 'Process Step'); });

        /* ─── Counters (start after pre-loaded items) ─── */
        let coreIdx    = {{ count($coreItems) }};
        let processIdx = {{ count($processItems) }};

        function updateCoreCount()    { const n=$('#core-accordion .accordion-item').length;     $('#core-count').text(n+' '+(n===1?'item':'items')); n===0?$('#core-empty-state').removeClass('d-none'):$('#core-empty-state').addClass('d-none'); }
        function updateProcessCount() { const n=$('#process-accordion .accordion-item').length;  $('#process-count').text(n+' '+(n===1?'item':'items')); n===0?$('#process-empty-state').removeClass('d-none'):$('#process-empty-state').addClass('d-none'); }

        /* ─── Item builders (for newly added items only) ─── */
        function buildCoreItem(idx) {
            return '<div class="accordion-item core-service-section-item" id="core-item-' + idx + '">' +
                '<h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#core-collapse-' + idx + '" aria-expanded="true">' +
                '<span class="badge me-2 text-white" style="background:#059669;min-width:26px">#</span>' +
                '<span class="core-item-title">New Service Pillar</span></button></h2>' +
                '<div id="core-collapse-' + idx + '" class="accordion-collapse collapse show"><div class="accordion-body">' +
                '<div class="row g-3">' +
                '<div class="col-12"><label class="control-label">Service Pillar Header</label>' +
                '<input type="text" name="core_service_header[]" class="form-control core-header-input" placeholder="e.g. Quality Management" /></div>' +
                '<div class="col-12"><label class="control-label">Service Pillar Description</label>' +
                '<textarea id="core_desc_' + idx + '" name="core_service_description[]" rows="4" class="form-control" placeholder="Service pillar description..."></textarea></div>' +
                '</div><div class="d-flex justify-content-end mt-3">' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-core-section"><i class="ti ti-trash me-1"></i> Remove</button>' +
                '</div></div></div></div>';
        }

        const PROCESS_SERVICES = @json($stepServices->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values());
        function escAttr(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function processServiceOptions(selectedId) {
            let html = '<option value="">— No service —</option>';
            PROCESS_SERVICES.forEach(function (s) {
                html += '<option value="' + s.id + '"' + (String(selectedId) === String(s.id) ? ' selected' : '') + '>' + escAttr(s.name) + '</option>';
            });
            return html;
        }

        function buildProcessItem(idx) {
            return '<div class="accordion-item process-section-item" id="process-item-' + idx + '">' +
                '<h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#process-collapse-' + idx + '" aria-expanded="true">' +
                '<span class="badge me-2 text-white" style="background:#0891b2;min-width:26px">#</span>' +
                '<span class="process-item-title">New Process Step</span></button></h2>' +
                '<div id="process-collapse-' + idx + '" class="accordion-collapse collapse show"><div class="accordion-body">' +
                '<div class="row g-3">' +
                '<div class="col-12"><label class="control-label">Process Header</label>' +
                '<input type="text" name="process_header[]" class="form-control process-header-input" placeholder="e.g. Initial Assessment" /></div>' +
                '<div class="col-12"><label class="control-label">Process Description</label>' +
                '<textarea id="process_desc_' + idx + '" name="process_description[]" rows="4" class="form-control" placeholder="Process step description..."></textarea></div>' +
                '<div class="col-12"><label class="control-label">Related Service <span class="text-muted fw-normal">(optional)</span></label>' +
                '<select name="process_service_ids[]" class="form-control">' + processServiceOptions('') + '</select>' +
                '<div class="field-hint">The service name and its short description are shown under this step on the website.</div></div>' +
                '</div><div class="d-flex justify-content-end mt-3">' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-process-section"><i class="ti ti-trash me-1"></i> Remove</button>' +
                '</div></div></div></div>';
        }

        /* ─── Add buttons ─── */
        $('#addCoreServiceBtn').on('click', function () {
            $('#core-accordion').append(buildCoreItem(coreIdx));
            initTinyMCE('#core_desc_' + coreIdx, { height: 180 });
            coreIdx++;
            updateCoreCount();
        });

        $('#addProcessBtn').on('click', function () {
            $('#process-accordion').append(buildProcessItem(processIdx));
            initTinyMCE('#process_desc_' + processIdx, { height: 180 });
            processIdx++;
            updateProcessCount();
        });

        /* ─── Remove helpers ─── */
        function confirmRemove(title, label, $item, afterRemove) {
            Swal.fire({
                title: title,
                html: 'Remove <strong>' + label + '</strong>? This cannot be undone.',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove it', cancelButtonText: 'Keep it', reverseButtons: true,
            }).then(function (r) {
                if (r.isConfirmed) {
                    $item.find('textarea[id]').each(function () {
                        const tid = $(this).attr('id');
                        if (tid && typeof tinymce !== 'undefined' && tinymce.get(tid)) {
                            try { tinymce.get(tid).destroy(); } catch(e) {}
                        }
                    });
                    $item.slideUp(200, function () { $(this).remove(); afterRemove(); });
                }
            });
        }

        $(document).on('click', '.remove-core-section',     function () { confirmRemove('Remove service pillar?',  $(this).closest('.accordion-item').find('.core-item-title').text(),    $(this).closest('.accordion-item'), updateCoreCount); });
        $(document).on('click', '.remove-process-section',  function () { confirmRemove('Remove process step?',  $(this).closest('.accordion-item').find('.process-item-title').text(), $(this).closest('.accordion-item'), updateProcessCount); });

        /* ─── Prevent native submit ─── */
        $('#edit_form').on('submit', function (e) { e.preventDefault(); });

        /* ─── AJAX Submit ─── */
        function triggerSave() {
            const agentVal = $('#agent_id').val();
            if (!agentVal) {
                Toast.fire({ icon: 'warning', title: 'Please select a Connected Agent.' });
                return;
            }

            if (typeof tinymce !== 'undefined') {
                try { tinymce.triggerSave(); } catch(e) {}
            }

            const formData = new FormData($('#edit_form')[0]);

            /* Sidebar fields (outside the form element) */
            if ($('#featured_toggle').is(':checked')) formData.append('featured', '1');
            formData.append('agent_id',          agentVal);
            formData.append('inq_officer_name',  $('#inq_officer_name').val()  || '');
            formData.append('inq_officer_phone', $('#inq_officer_phone').val() || '');
            const annVal = $('#announcement_id').val();
            if (annVal) formData.append('announcement_id', annVal);

            const $notif = $('#save-notification');
            $notif.addClass('d-none');

            Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });

            $.ajax({
                url: '{{ route('categories.update', $category->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN':     $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept':           'application/json',
                },
                success: function (response) {
                    Swal.close();
                    $('.is-invalid').removeClass('is-invalid');
                    $('#server-error-box').addClass('d-none');
                    $notif.removeClass('d-none alert-danger')
                        .addClass('alert alert-success')
                        .html('<i class="ti ti-circle-check me-1"></i> ' + (response.message || 'Updated successfully!'));
                    setTimeout(function () { $notif.addClass('d-none'); }, 5000);
                    Toast.fire({ icon: 'success', title: response.message || 'Updated successfully!' });
                },
                error: function (xhr) {
                    Swal.close();
                    let errorMsg = 'Something went wrong. Please try again.';
                    let listHtml = '';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (k, v) {
                                listHtml += '<li>' + (Array.isArray(v) ? v[0] : v) + '</li>';
                                $('[name="' + k + '"], [name="' + k + '[]"]').addClass('is-invalid');
                            });
                            errorMsg = 'Please fix the errors below.';
                        } else if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                            listHtml = '<li>' + errorMsg + '</li>';
                        }
                    }
                    $notif.removeClass('d-none alert-success')
                        .addClass('alert alert-danger')
                        .html('<i class="ti ti-alert-circle me-1"></i> ' + errorMsg);
                    setTimeout(function () { $notif.addClass('d-none'); }, 8000);
                    if (listHtml) {
                        $('#server-error-list').html(listHtml);
                        $('#server-error-box').removeClass('d-none');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            });
        }

        $('#updateBtn').on('click', function () { triggerSave(); });

    });
    </script>
@endsection
