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
        .image-preview-thumb { width: 90px; height: 75px; object-fit: cover; border-radius: 6px; border: 2px solid #dee2e6; }
        .sidebar-card { border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 1rem; }
        .sidebar-card .sidebar-card-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: .7rem 1rem; font-weight: 600; font-size: .85rem; display: flex; align-items: center; gap: .4rem; }
        .sidebar-card .sidebar-card-body { padding: 1rem; }
        .featured-banner { border-radius: 10px; padding: .9rem 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: .75rem; }
        .featured-banner.is-featured { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .featured-banner.not-featured { background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; }
        .status-banner { border-radius: 10px; padding: .9rem 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: .75rem; }
        .status-banner.published { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .status-banner.draft { background: #fef9c3; border: 1px solid #fde68a; color: #92400e; }
        .field-hint { font-size: .78rem; color: #6b7280; margin-top: .25rem; }
        .required-star { color: #ef4444; }
        label.form-label, .control-label { font-weight: 500; font-size: .85rem; margin-bottom: .3rem; display: block; }
        .item-count-badge { background: #e0f2fe; color: #0369a1; border-radius: 20px; padding: 2px 10px; font-size: .73rem; font-weight: 600; }
        .existing-img-badge { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 4px 10px; font-size: .78rem; color: #166534; display: inline-flex; align-items: center; gap: .4rem; }
        .body-wrapper > .container-fluid { padding-left: 0 !important; padding-right: 0 !important; }
        .select2-container { display: block !important; }
        .select2-container--default .select2-selection--multiple { height: auto !important; }
    </style>
@endsection

@section('content')

    @php
        $coreHeaders      = is_array($service_group->core_service_header)      ? $service_group->core_service_header      : (json_decode($service_group->core_service_header, true)      ?: []);
        $coreDescriptions = is_array($service_group->core_service_description) ? $service_group->core_service_description : (json_decode($service_group->core_service_description, true) ?: []);
        $processHeaders   = is_array($service_group->process_header)           ? $service_group->process_header           : (json_decode($service_group->process_header, true)           ?: []);
        $processDescs     = is_array($service_group->process_description)      ? $service_group->process_description      : (json_decode($service_group->process_description, true)      ?: []);
        $processServiceIdsArr = $service_group->process_service_ids ?? [];
        if (!is_array($processServiceIdsArr)) {
            $decoded = json_decode($processServiceIdsArr, true);
            $processServiceIdsArr = is_array($decoded) ? $decoded : [];
        }
        if (!count($coreHeaders))    $coreHeaders      = [''];
        if (!count($coreDescriptions)) $coreDescriptions = [''];
        if (!count($processHeaders)) $processHeaders   = [''];
        if (!count($processDescs))   $processDescs     = [''];

        // Services selectable per process step: the group's own services,
        // falling back to all services when none are linked yet.
        $stepServices = $service_group->services->count() ? $service_group->services : $services;
    @endphp

    {{-- Breadcrumb --}}
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-edit me-2"></i>Edit Service Group</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('service-group.index') }}">Service Groups</a></li>
                            <li class="breadcrumb-item active">{{ Str::limit($service_group->name, 40) }}</li>
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

            <form action="{{ route('service-group.update', $service_group->id) }}" method="POST"
                  id="sg_edit_form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── Section 1 · Basic Information ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-primary text-white">1</span>
                        <h6 class="mb-0 fw-semibold">Basic Information</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="control-label">Service Group Name <span class="required-star">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ $service_group->name }}" placeholder="Service Group Name" required />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">URL Slug <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:.8rem">/service-group/</span>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ $service_group->slug }}" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Sub-categories</label>
                                <select name="category_ids[]" id="category_ids" class="form-control select2"
                                    data-placeholder="Select one or more sub-categories" multiple>
                                    <option></option>
                                    @foreach ($mainCategories as $mc)
                                        @if($mc->categories->count())
                                            <optgroup label="{{ $mc->name }}">
                                                @foreach ($mc->categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ $service_group->categories->contains($cat->id) ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="field-hint">Select one or more sub-categories. This group will appear under all selected.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Services <span class="required-star">*</span></label>
                                <select name="service_ids[]" class="form-control select2"
                                    data-placeholder="Select services to include" required multiple>
                                    @foreach ($services as $svc)
                                        <option value="{{ $svc->id }}"
                                            {{ $service_group->services->contains($svc->id) ? 'selected' : '' }}>
                                            {{ $svc->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="field-hint">Choose all services that belong to this group.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Hero Description <span class="required-star">*</span>
                                    <span class="text-muted fw-normal">(banner)</span></label>
                                <textarea name="content" rows="6" class="rich-textarea form-control"
                                    required>{{ $service_group->content }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Intro Description <span class="required-star">*</span>
                                    <span class="text-muted fw-normal">(overview — shown on the right of the service details band)</span></label>
                                <textarea name="overview" rows="6" class="rich-textarea form-control"
                                    required>{{ $service_group->overview }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Service Details Header
                                    <span class="text-muted fw-normal">(shown on the left of the service details band)</span></label>
                                <textarea name="service_details_header" rows="4" class="rich-textarea form-control"
                                    placeholder="A short, bold lead statement for the service details band...">{{ $service_group->service_details_header }}</textarea>
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
                                @if($service_group->hero_image)
                                    <div class="mb-2 d-flex align-items-center gap-2">
                                        <img src="{{ asset('public/' . ltrim($service_group->hero_image, '/')) }}" class="image-preview-thumb" alt="Hero">
                                        <span class="existing-img-badge"><i class="ti ti-check"></i> Current</span>
                                    </div>
                                @endif
                                <input type="file" name="hero_image" class="form-control" accept="image/*" />
                                <div class="field-hint">Leave blank to keep current.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Service Group Image</label>
                                @if($service_group->image)
                                    <div class="mb-2 d-flex align-items-center gap-2">
                                        <img src="{{ asset('uploads/service_group_images/' . $service_group->image) }}" class="image-preview-thumb" alt="Group">
                                        <span class="existing-img-badge"><i class="ti ti-check"></i> Current</span>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*" />
                                <div class="field-hint">Leave blank to keep current.</div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ── Section 4 · Page Content ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-success text-white">4</span>
                        <h6 class="mb-0 fw-semibold">Page Content</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="control-label">Service Header <span class="required-star">*</span></label>
                                <input type="text" name="service_header" class="form-control"
                                    value="{{ $service_group->service_header }}" placeholder="Service header text" required />
                            </div>
                            <div class="col-12">
                                <label class="control-label">Service Group Description <span class="required-star">*</span></label>
                                <textarea name="description" rows="5" class="rich-textarea form-control"
                                    placeholder="Describe this service group...">{{ $service_group->description }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">CTA Description <span class="text-muted fw-normal">(why choose us)</span></label>
                                <textarea name="info_four" rows="4" class="rich-textarea form-control"
                                    >{{ $service_group->info_four }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 5 · Process Steps ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-white" style="background:#0891b2!important">5</span>
                            <h6 class="mb-0 fw-semibold">Process Steps</h6>
                            <span class="item-count-badge" id="process-count">{{ count($processHeaders) }} {{ count($processHeaders) == 1 ? 'item' : 'items' }}</span>
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
                                placeholder="e.g. From first call to license in hand — describe your process approach...">{{ $service_group->process_intro }}</textarea>
                        </div>
                        <div id="process-accordion" class="accordion cst-accordion">
                            @foreach ($processHeaders as $index => $header)
                                <div class="accordion-item process-section-item" id="process-item-{{ $index }}">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#process-collapse-{{ $index }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                            <span class="badge me-2 text-white" style="background:#0891b2;min-width:26px">#{{ $index + 1 }}</span>
                                            <span class="process-item-title text-truncate" style="max-width:300px">{{ $header ?: 'Process Step ' . ($index + 1) }}</span>
                                        </button>
                                    </h2>
                                    <div id="process-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="control-label">Process Header</label>
                                                    <input type="text" name="process_header[]"
                                                        class="form-control process-header-input"
                                                        value="{{ $header }}"
                                                        placeholder="e.g. Initial Assessment" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="control-label">Process Description</label>
                                                    <textarea id="process_desc_{{ $index }}" name="process_description[]"
                                                        rows="4" class="form-control"
                                                        placeholder="Process step description...">{{ $processDescs[$index] ?? '' }}</textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="control-label">Related Service <span class="text-muted fw-normal">(optional)</span></label>
                                                    @php
                                                        $stepSvcId = $processServiceIdsArr[$index] ?? null;
                                                        $stepOpts = $stepServices;
                                                        if (!empty($stepSvcId) && !$stepOpts->contains('id', $stepSvcId)) {
                                                            $extra = $services->firstWhere('id', $stepSvcId);
                                                            if ($extra) $stepOpts = $stepOpts->concat([$extra]);
                                                        }
                                                    @endphp
                                                    <select name="process_service_ids[]" class="form-control">
                                                        <option value="">— No service —</option>
                                                        @foreach ($stepOpts as $svc)
                                                            <option value="{{ $svc->id }}" {{ (string)($stepSvcId ?? '') === (string)$svc->id ? 'selected' : '' }}>{{ $svc->name }}</option>
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

                {{-- ── Section 6 · FAQ ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-dark" style="background:#fbbf24!important">6</span>
                            <h6 class="mb-0 fw-semibold">Frequently Asked Questions</h6>
                            <span class="item-count-badge" id="faq-count" style="background:#fef9c3;color:#854d0e">
                                {{ $service_group->faqs->count() }} {{ $service_group->faqs->count() == 1 ? 'item' : 'items' }}
                            </span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-warning" id="addFaqBtn">
                            <i class="ti ti-plus me-1"></i> Add FAQ
                        </button>
                    </div>
                    <div class="section-body p-3">
                        @if($service_group->faqs->count() === 0)
                            <div id="faq-empty-state" class="empty-state">
                                <i class="ti ti-help-circle"></i>
                                <p class="mb-1 fw-semibold">No FAQs added yet</p>
                                <small>Click <strong>Add FAQ</strong> to add questions &amp; answers.</small>
                            </div>
                        @else
                            <div id="faq-empty-state" class="empty-state d-none"></div>
                        @endif
                        <div id="faq-accordion" class="accordion cst-accordion">
                            @foreach ($service_group->faqs as $index => $faq)
                                <div class="accordion-item faq-section" id="faq-item-e{{ $index }}">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-collapse-e{{ $index }}"
                                            aria-expanded="false">
                                            <span class="badge me-2 text-dark" style="background:#fbbf24;min-width:26px">Q{{ $index + 1 }}</span>
                                            <span class="faq-item-question text-truncate" style="max-width:300px">{{ Str::limit($faq->faq_question, 60) ?: 'FAQ Question' }}</span>
                                        </button>
                                    </h2>
                                    <div id="faq-collapse-e{{ $index }}" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                <label class="control-label">Question <span class="required-star">*</span></label>
                                                <input type="text" name="faqs[{{ $index }}][question]"
                                                    class="form-control faq-question-input"
                                                    value="{{ $faq->faq_question }}" placeholder="FAQ question..." required />
                                            </div>
                                            <div class="mb-3">
                                                <label class="control-label">Answer <span class="required-star">*</span></label>
                                                <textarea id="faq_ans_e{{ $index }}"
                                                    name="faqs[{{ $index }}][answer]"
                                                    rows="4" class="form-control"
                                                    placeholder="FAQ answer...">{{ $faq->faq_answer }}</textarea>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-faq-section">
                                                    <i class="ti ti-trash me-1"></i> Remove this FAQ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── Section 7 · SEO / Meta ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-secondary text-white">7</span>
                        <h6 class="mb-0 fw-semibold">SEO / Meta Details</h6>
                        <small class="text-muted ms-1">— all optional</small>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="control-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    value="{{ $service_group->meta_title }}" placeholder="Page title for search engines" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-control"
                                    placeholder="Short description for search results">{{ $service_group->meta_description }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Keywords</label>
                                <textarea name="meta_keywords" rows="3" class="form-control"
                                    placeholder="Comma-separated keywords">{{ $service_group->meta_keywords }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </form>{{-- /sg_edit_form --}}

        </div>{{-- /col-md-9 --}}

        {{-- ═══════════════════════════ RIGHT SIDEBAR ═══════════════════════════ --}}
        <div class="col-md-3">
            <div class="sidebar-sticky">

                {{-- Status banner --}}
                <div class="status-banner {{ ($service_group->status ?? 'published') === 'published' ? 'published' : 'draft' }}" id="status-banner">
                    @if(($service_group->status ?? 'published') === 'published')
                        <i class="ti ti-circle-check fs-5"></i>
                        <div><div class="fw-semibold">Currently Published</div><small>Visible to visitors</small></div>
                    @else
                        <i class="ti ti-pencil fs-5"></i>
                        <div><div class="fw-semibold">Currently a Draft</div><small>Hidden from the website</small></div>
                    @endif
                </div>

                {{-- Featured banner --}}
                <div class="featured-banner {{ $service_group->is_featured ? 'is-featured' : 'not-featured' }}" id="featured-banner">
                    @if($service_group->is_featured)
                        <i class="ti ti-star-filled fs-5"></i>
                        <div><div class="fw-semibold">Featured</div><small>Shown in featured sections</small></div>
                    @else
                        <i class="ti ti-star fs-5"></i>
                        <div><div class="fw-semibold">Not Featured</div><small>Not in featured sections</small></div>
                    @endif
                </div>

                {{-- Save card --}}
                <div class="sidebar-card" style="border-color:#3b82f6!important">
                    <div class="sidebar-card-header" style="background:#eff6ff;color:#1e40af">
                        <i class="ti ti-device-floppy"></i> Save Changes
                    </div>
                    <div class="sidebar-card-body">
                        <div id="save-notification" class="d-none mb-2 p-2 rounded small fw-semibold" role="alert" style="word-break:break-word"></div>
                        <div class="d-grid gap-2">
                            @if(($service_group->status ?? 'published') === 'published')
                                <button type="button" class="btn btn-success" id="saveBtn" data-action="published">
                                    <i class="ti ti-device-floppy me-2"></i> Update &amp; Keep Published
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="saveDraftBtn" data-action="draft">
                                    <i class="ti ti-eye-off me-2"></i> Update &amp; Move to Draft
                                </button>
                            @else
                                <button type="button" class="btn btn-success" id="publishBtn" data-action="published">
                                    <i class="ti ti-world-upload me-2"></i> Update &amp; Publish
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="saveBtn" data-action="draft">
                                    <i class="ti ti-device-floppy me-2"></i> Update &amp; Keep Draft
                                </button>
                            @endif
                            <a href="{{ route('service-group.index') }}" class="btn btn-light">
                                <i class="ti ti-arrow-left me-1"></i> Back to Groups
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
                                <div class="fw-semibold" style="font-size:.875rem">Featured Group</div>
                                <div class="field-hint mb-0">Show in featured sections</div>
                            </div>
                            <div class="form-check form-switch mb-0 ms-3">
                                <input type="checkbox" name="featured" class="form-check-input"
                                    id="featured_toggle" role="switch" value="1"
                                    {{ $service_group->is_featured ? 'checked' : '' }} />
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-1 mb-3 border-bottom pb-3">
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">Show Testimonials</div>
                                <div class="field-hint mb-0">Display client reviews on this group page</div>
                            </div>
                            <div class="form-check form-switch mb-0 ms-3">
                                <input type="checkbox" name="show_testimonials" class="form-check-input"
                                    id="show_testimonials_toggle" role="switch" value="1"
                                    {{ $service_group->show_testimonials ? 'checked' : '' }} />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Connected Agent <span class="required-star">*</span></label>
                            <select name="agent_id" id="agent_id" class="form-control select2-sidebar"
                                data-placeholder="Select staff member">
                                <option></option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ $service_group->agent_id == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->user->first_name . ' ' . $agent->user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="control-label">Inquiry Officer Name</label>
                            <input type="text" id="inq_officer_name" class="form-control"
                                value="{{ $service_group->inq_officer_name }}" placeholder="Officer name" />
                        </div>
                        <div>
                            <label class="control-label">Inquiry Officer Phone</label>
                            <input type="text" id="inq_officer_phone" class="form-control"
                                value="{{ $service_group->inq_officer_phone }}" placeholder="e.g. 94774702259" />
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
                                    {{ $service_group->announcement_id == $ann->id ? 'selected' : '' }}>
                                    {{ $ann->title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint mt-1">Shown in the announcement banner.</div>
                    </div>
                </div>

                {{-- Dates card --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <i class="ti ti-calendar"></i> Publication Dates
                    </div>
                    <div class="sidebar-card-body">
                        <div class="mb-3">
                            <label class="control-label mb-1" for="published_date">Published Date <span class="required-star">*</span></label>
                            <input type="date" id="published_date" name="published_date"
                                class="form-control form-control-sm"
                                value="{{ ($service_group->published_date ?? $service_group->created_at)->format('Y-m-d') }}" required />
                            <small class="field-hint">Date shown as "Published" on the page.</small>
                        </div>
                        <div class="mb-1">
                            <label class="control-label mb-1" for="updated_date">Last Updated Date</label>
                            <input type="date" id="updated_date" name="updated_date"
                                class="form-control form-control-sm"
                                value="{{ ($service_group->updated_date ?? $service_group->updated_at)->format('Y-m-d') }}" />
                            <small class="field-hint">Date shown as "Updated" on the page.</small>
                        </div>
                    </div>
                </div>

                {{-- Tips card --}}
                <div class="sidebar-card" style="border-color:#bfdbfe!important">
                    <div class="sidebar-card-header" style="background:#eff6ff;color:#1e40af">
                        <i class="ti ti-bulb"></i> Tips
                    </div>
                    <div class="sidebar-card-body" style="font-size:.82rem;color:#374151">
                        <ul class="mb-0 ps-3">
                            <li class="mb-1">Click any <strong>accordion header</strong> to expand and edit.</li>
                            <li class="mb-1">You'll be asked to <strong>confirm</strong> before removing an item.</li>
                            <li class="mb-1">Leave image fields <strong>blank</strong> to keep existing images.</li>
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
            const id = selector.replace('#', '');
            if (typeof tinymce !== 'undefined') {
                if (tinymce.get(id)) { try { tinymce.get(id).destroy(); } catch(e) {} }
                tinymce.init(Object.assign({
                    selector: selector,
                    plugins: 'code searchreplace autolink directionality visualblocks link media codesample table charmap nonbreaking anchor insertdatetime advlist lists wordcount help emoticons autosave fullscreen',
                    toolbar: 'code undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | fullscreen',
                    // Keep links exactly as entered — don't rewrite absolute internal links into broken relative ones.
                    relative_urls: false,
                    convert_urls: false,
                    menubar: true,
                    height: 240,
                    automatic_uploads: true,
                    images_upload_url: '/upload-image',
                }, extraCfg));
            }
        }

        /* Init static rich-textareas */
        initTinyMCE('.rich-textarea', { height: 240 });
        initTinyMCE('#process_intro', { height: 180, menubar: false });

        /* Init existing accordion items */
        @foreach ($service_group->faqs as $index => $faq)
            initTinyMCE('#faq_ans_e{{ $index }}', { height: 180, menubar: false });
        @endforeach
        @foreach ($coreHeaders as $index => $header)
            initTinyMCE('#core_desc_{{ $index }}', { height: 180 });
        @endforeach
        @foreach ($processHeaders as $index => $header)
            initTinyMCE('#process_desc_{{ $index }}', { height: 180 });
        @endforeach

        /* ─── Select2 ─── */
        $('.select2').select2({ minimumResultsForSearch: 8 });
        $('.select2-sidebar').select2({ minimumResultsForSearch: 8, dropdownParent: $('body') });

        /* ─── Slug auto-fill ─── */
        $('#name').on('input', function () {
            $('#slug').val($(this).val().toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-'));
        });

        /* ─── Counters ─── */
        let newFaqIdx     = {{ $service_group->faqs->count() }};
        let newCoreIdx    = {{ count($coreHeaders) }};
        let newProcessIdx = {{ count($processHeaders) }};

        function updateFaqCount()     { const n=$('#faq-accordion .accordion-item').length; $('#faq-count').text(n+' '+(n===1?'item':'items')); n===0?$('#faq-empty-state').removeClass('d-none'):$('#faq-empty-state').addClass('d-none'); }
        function updateCoreCount()    { const n=$('#core-accordion .accordion-item').length; $('#core-count').text(n+' '+(n===1?'item':'items')); }
        function updateProcessCount() { const n=$('#process-accordion .accordion-item').length; $('#process-count').text(n+' '+(n===1?'item':'items')); }

        /* ─── Live header sync ─── */
        $(document).on('input', '.faq-question-input',  function () { $(this).closest('.accordion-item').find('.faq-item-question').text(($(this).val().trim() || 'FAQ Question').substring(0, 60)); });
        $(document).on('input', '.core-header-input',   function () { $(this).closest('.accordion-item').find('.core-item-title').text($(this).val().trim() || 'Core Service'); });
        $(document).on('input', '.process-header-input',function () { $(this).closest('.accordion-item').find('.process-item-title').text($(this).val().trim() || 'Process Step'); });

        /* ─── Item builders ─── */
function buildFaqItem(idx) {
            return `<div class="accordion-item faq-section" id="faq-item-n${idx}">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-n${idx}" aria-expanded="true">
                    <span class="badge me-2 text-dark" style="background:#fbbf24;min-width:26px">Q</span>
                    <span class="faq-item-question">New FAQ</span>
                </button></h2>
                <div id="faq-collapse-n${idx}" class="accordion-collapse collapse show"><div class="accordion-body">
                    <div class="mb-3"><label class="control-label">Question <span class="required-star">*</span></label>
                        <input type="text" name="faqs[${idx}][question]" class="form-control faq-question-input" placeholder="FAQ question..." required /></div>
                    <div class="mb-3"><label class="control-label">Answer <span class="required-star">*</span></label>
                        <textarea id="faq_ans_n${idx}" name="faqs[${idx}][answer]" rows="4" class="form-control" placeholder="FAQ answer..." required></textarea></div>
                    <div class="d-flex justify-content-end"><button type="button" class="btn btn-sm btn-outline-danger remove-faq-section"><i class="ti ti-trash me-1"></i> Remove this FAQ</button></div>
                </div></div></div>`;
        }

        function buildCoreItem(idx) {
            return `<div class="accordion-item core-service-section-item" id="core-item-n${idx}">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#core-collapse-n${idx}" aria-expanded="true">
                    <span class="badge me-2 text-white" style="background:#059669;min-width:26px">#</span>
                    <span class="core-item-title">New Core Service</span>
                </button></h2>
                <div id="core-collapse-n${idx}" class="accordion-collapse collapse show"><div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="control-label">Core Service Header <span class="required-star">*</span></label>
                            <input type="text" name="core_service_header[]" class="form-control core-header-input" placeholder="e.g. Quality Management" required /></div>
                        <div class="col-12"><label class="control-label">Core Service Description <span class="required-star">*</span></label>
                            <textarea id="core_desc_n${idx}" name="core_service_description[]" rows="4" class="form-control" placeholder="Core service description..." required></textarea></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3"><button type="button" class="btn btn-sm btn-outline-danger remove-core-section"><i class="ti ti-trash me-1"></i> Remove</button></div>
                </div></div></div>`;
        }

        function buildProcessItem(idx) {
            return `<div class="accordion-item process-section-item" id="process-item-n${idx}">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#process-collapse-n${idx}" aria-expanded="true">
                    <span class="badge me-2 text-white" style="background:#0891b2;min-width:26px">#</span>
                    <span class="process-item-title">New Process Step</span>
                </button></h2>
                <div id="process-collapse-n${idx}" class="accordion-collapse collapse show"><div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="control-label">Process Header</label>
                            <input type="text" name="process_header[]" class="form-control process-header-input" placeholder="e.g. Initial Assessment" /></div>
                        <div class="col-12"><label class="control-label">Process Description</label>
                            <textarea id="process_desc_n${idx}" name="process_description[]" rows="4" class="form-control" placeholder="Process step description..."></textarea></div>
                        <div class="col-12"><label class="control-label">Related Service <span class="text-muted fw-normal">(optional)</span></label>
                            <select name="process_service_ids[]" class="form-control">${processServiceOptions('')}</select>
                            <div class="field-hint">The service name and its short description are shown under this step on the website.</div></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3"><button type="button" class="btn btn-sm btn-outline-danger remove-process-section"><i class="ti ti-trash me-1"></i> Remove</button></div>
                </div></div></div>`;
        }

        const PROCESS_SERVICES = @json($stepServices->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values());
        function escAttrPS(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function processServiceOptions(selectedId) {
            let html = '<option value="">— No service —</option>';
            PROCESS_SERVICES.forEach(function (s) {
                html += '<option value="' + s.id + '"' + (String(selectedId) === String(s.id) ? ' selected' : '') + '>' + escAttrPS(s.name) + '</option>';
            });
            return html;
        }

        /* ─── Add buttons ─── */
        $('#addFaqBtn').on('click', function ()      { $('#faq-accordion').append(buildFaqItem(newFaqIdx)); initTinyMCE(`#faq_ans_n${newFaqIdx}`, { height: 180, menubar: false }); newFaqIdx++; updateFaqCount(); });
        $('#addCoreServiceBtn').on('click', function () { $('#core-accordion').append(buildCoreItem(newCoreIdx)); initTinyMCE(`#core_desc_n${newCoreIdx}`, { height: 180 }); newCoreIdx++; updateCoreCount(); });
        $('#addProcessBtn').on('click', function ()  { $('#process-accordion').append(buildProcessItem(newProcessIdx)); initTinyMCE(`#process_desc_n${newProcessIdx}`, { height: 180 }); newProcessIdx++; updateProcessCount(); });

        /* ─── Remove helpers ─── */
        function confirmRemove(title, label, $item, afterRemove) {
            Swal.fire({
                title: title, html: `Remove <strong>${label}</strong>? This cannot be undone.`,
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove it', cancelButtonText: 'Keep it', reverseButtons: true,
            }).then(function (r) {
                if (r.isConfirmed) {
                    const tid = $item.find('textarea[id]').attr('id');
                    if (tid && typeof tinymce !== 'undefined' && tinymce.get(tid)) { try { tinymce.get(tid).destroy(); } catch(e){} }
                    $item.slideUp(200, function () { $(this).remove(); afterRemove(); });
                }
            });
        }

        $(document).on('click', '.remove-faq-section',      function () { confirmRemove('Remove FAQ?', $(this).closest('.accordion-item').find('.faq-item-question').text(), $(this).closest('.accordion-item'), updateFaqCount); });
        $(document).on('click', '.remove-process-section',  function () { confirmRemove('Remove process step?', $(this).closest('.accordion-item').find('.process-item-title').text(), $(this).closest('.accordion-item'), updateProcessCount); });
        $(document).on('click', '.remove-core-section', function () {
            if ($('#core-accordion .accordion-item').length <= 1) { Toast.fire({ icon: 'warning', title: 'At least one core service is required.' }); return; }
            confirmRemove('Remove core service?', $(this).closest('.accordion-item').find('.core-item-title').text(), $(this).closest('.accordion-item'), updateCoreCount);
        });

        /* ─── AJAX Submit ─── */
        function triggerSgSave(status) {
            /* Sync TinyMCE */
            if (typeof tinymce !== 'undefined') {
                try { tinymce.triggerSave(); } catch(e) {}
            }

            const formData = new FormData($('#sg_edit_form')[0]);

            /* Status from button action */
            formData.append('status', status || 'published');

            /* Append sidebar fields (outside the form element) */
            if ($('#featured_toggle').is(':checked')) formData.append('featured', '1');
            if ($('#show_testimonials_toggle').is(':checked')) formData.append('show_testimonials', '1');
            const agentVal = $('#agent_id').val();
            if (agentVal) formData.append('agent_id', agentVal);
            formData.append('inq_officer_name',  $('#inq_officer_name').val()  || '');
            formData.append('inq_officer_phone', $('#inq_officer_phone').val() || '');
            formData.append('published_date', $('#published_date').val() || '');
            formData.append('updated_date',   $('#updated_date').val()   || '');
            const annVal = $('#announcement_id').val();
            if (annVal) formData.append('announcement_id', annVal);

            const $notif = $('#save-notification');
            $notif.addClass('d-none');

            Swal.fire({ title: 'Updating...', text: 'Please wait while we save changes.', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });

            $.ajax({
                url: $('#sg_edit_form').attr('action'),
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
                    $('#server-error-box').addClass('d-none');

                    /* Inline notification */
                    $notif.removeClass('d-none alert-danger')
                        .addClass('alert alert-success')
                        .html('<i class="ti ti-circle-check me-1"></i> ' + (response.message || 'Saved successfully!'));
                    setTimeout(function () { $notif.addClass('d-none'); }, 5000);

                    Toast.fire({ icon: 'success', title: response.message || 'Saved successfully!' });

                    /* Update status banner */
                    const newStatus = (response.data && response.data.status) ? response.data.status : status;
                    const $sBanner = $('#status-banner');
                    if (newStatus === 'published') {
                        $sBanner.removeClass('draft').addClass('published')
                            .html('<i class="ti ti-circle-check fs-5"></i><div><div class="fw-semibold">Currently Published</div><small>Visible to visitors</small></div>');
                    } else {
                        $sBanner.removeClass('published').addClass('draft')
                            .html('<i class="ti ti-pencil fs-5"></i><div><div class="fw-semibold">Currently a Draft</div><small>Hidden from the website</small></div>');
                    }

                    /* Update featured banner */
                    const isFeatured = $('#featured_toggle').is(':checked');
                    const $banner = $('#featured-banner');
                    if (isFeatured) {
                        $banner.removeClass('not-featured').addClass('is-featured')
                            .html('<i class="ti ti-star-filled fs-5"></i><div><div class="fw-semibold">Featured</div><small>Shown in featured sections</small></div>');
                    } else {
                        $banner.removeClass('is-featured').addClass('not-featured')
                            .html('<i class="ti ti-star fs-5"></i><div><div class="fw-semibold">Not Featured</div><small>Not in featured sections</small></div>');
                    }
                },
                error: function (xhr) {
                    Swal.close();

                    let errorMsg = 'Something went wrong. Please try again.';
                    let listHtml = '';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (k, v) {
                                const msg = Array.isArray(v) ? v[0] : v;
                                listHtml += '<li>' + msg + '</li>';
                            });
                            errorMsg = 'Please fix the errors below.';
                        } else if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                            listHtml = '<li>' + errorMsg + '</li>';
                        }
                    }

                    /* Inline notification */
                    $notif.removeClass('d-none alert-success')
                        .addClass('alert alert-danger')
                        .html('<i class="ti ti-alert-circle me-1"></i> ' + errorMsg);
                    setTimeout(function () { $notif.addClass('d-none'); }, 8000);

                    if (listHtml) {
                        $('#server-error-list').html(listHtml);
                        $('#server-error-box').removeClass('d-none');
                        $('html, body').animate({ scrollTop: 0 }, 300);
                    }
                }
            });
        }

        $('#saveBtn').on('click',    function () { triggerSgSave($(this).data('action') || 'draft'); });
        $('#saveDraftBtn').on('click', function () { triggerSgSave('draft'); });
        $('#publishBtn').on('click',  function () { triggerSgSave('published'); });

    });
    </script>
@endsection
