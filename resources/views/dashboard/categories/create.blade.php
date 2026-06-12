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
        .body-wrapper > .container-fluid { padding-left: 0 !important; padding-right: 0 !important; }
        .select2-container { display: block !important; }
        .select2-container--default .select2-selection--multiple { height: auto !important; }
    </style>
@endsection

@section('content')

    {{-- Breadcrumb --}}
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-folder-plus me-2"></i>Create New Category</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                            <li class="breadcrumb-item active">Create</li>
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

            <form action="{{ route('categories.store') }}" method="POST"
                  id="add_form" enctype="multipart/form-data">
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
                                    placeholder="e.g. Healthcare Licensing" required />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">URL Slug <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:.8rem">/categories/</span>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="auto-generated" required />
                                </div>
                                <div class="field-hint">Auto-filled from name. Edit to customise.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Main Categories <span class="required-star">*</span></label>
                                <select name="main_category_ids[]" class="form-control select2"
                                    data-placeholder="Select main categories" multiple required>
                                    @foreach ($main_categories as $mc)
                                        <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                                    @endforeach
                                </select>
                                <div class="field-hint">This category will appear under the selected main categories.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Category Short Description <span class="required-star">*</span></label>
                                <textarea name="description" rows="4" class="rich-textarea form-control"
                                    placeholder="Brief description of this category..." required></textarea>
                                <div class="field-hint">Visible under the slider on the category page and on the home page cards.</div>
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
                                <label class="control-label">Hero Section Image <span class="required-star">*</span></label>
                                <input type="file" name="hero_image" class="form-control" accept="image/*" required />
                                <div class="field-hint">Main banner image for the category page.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Category Image <span class="required-star">*</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" required />
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
                                <label class="control-label">Service Header
                                    <span class="text-muted fw-normal">(shown after the hero, above the intro description)</span></label>
                                <input type="text" name="service_header" class="form-control"
                                    placeholder="e.g. Our Healthcare Services" />
                                <div class="field-hint">If left empty, no header is shown and the intro description starts directly.</div>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Intro Description
                                    <span class="text-muted fw-normal">(overview section)</span></label>
                                <textarea name="overview" rows="6" class="rich-textarea form-control"
                                    placeholder="Introduction paragraph shown below the hero..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">CTA Header
                                    <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="info_three" rows="4" class="rich-textarea form-control"
                                    placeholder="Call-to-action heading (e.g. How Alpha Can Help)..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">CTA Description
                                    <span class="text-muted fw-normal">(why choose us)</span></label>
                                <textarea name="info_four" rows="4" class="rich-textarea form-control"
                                    placeholder="Why choose us / benefits description..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 4 · Core Services ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-white" style="background:#059669!important">4</span>
                            <h6 class="mb-0 fw-semibold">Core Services</h6>
                            <span class="item-count-badge" id="core-count">0 items</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" id="addCoreServiceBtn">
                            <i class="ti ti-plus me-1"></i> Add Core Service
                        </button>
                    </div>
                    <div class="section-body p-3">
                        <div id="core-empty-state" class="empty-state">
                            <i class="ti ti-layout-grid"></i>
                            <p class="mb-1 fw-semibold">No core services added yet</p>
                            <small>Click <strong>Add Core Service</strong> to highlight your key offerings.</small>
                        </div>
                        <div id="core-accordion" class="accordion cst-accordion"></div>
                    </div>
                </div>

                {{-- ── Section 5 · Process Steps ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-white" style="background:#0891b2!important">5</span>
                            <h6 class="mb-0 fw-semibold">Process Steps</h6>
                            <span class="item-count-badge" id="process-count">0 items</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-info" id="addProcessBtn">
                            <i class="ti ti-plus me-1"></i> Add Process Step
                        </button>
                    </div>
                    <div class="section-body p-3">
                        <div id="process-empty-state" class="empty-state">
                            <i class="ti ti-list-numbers"></i>
                            <p class="mb-1 fw-semibold">No process steps yet</p>
                            <small>Click <strong>Add Process Step</strong> to describe your workflow.</small>
                        </div>
                        <div id="process-accordion" class="accordion cst-accordion"></div>
                    </div>
                </div>

                {{-- ── Section 6 · Magazine / Insights ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-white" style="background:#7c3aed!important">6</span>
                            <h6 class="mb-0 fw-semibold">Magazine / Insights</h6>
                            <span class="item-count-badge" id="mag-count">0 items</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addMagazineBtn">
                            <i class="ti ti-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="section-body p-3">
                        <div id="mag-empty-state" class="empty-state">
                            <i class="ti ti-news"></i>
                            <p class="mb-1 fw-semibold">No magazine items yet</p>
                            <small>Click <strong>Add Item</strong> to add magazine/insights cards.</small>
                        </div>
                        <div id="magazine-accordion" class="accordion cst-accordion"></div>
                    </div>
                </div>

                {{-- ── Section 7 · FAQ ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-dark" style="background:#fbbf24!important">7</span>
                            <h6 class="mb-0 fw-semibold">Frequently Asked Questions</h6>
                            <span class="item-count-badge" id="faq-count" style="background:#fef9c3;color:#854d0e">0 items</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-warning" id="addFaqBtn">
                            <i class="ti ti-plus me-1"></i> Add FAQ
                        </button>
                    </div>
                    <div class="section-body p-3">
                        <div id="faq-empty-state" class="empty-state">
                            <i class="ti ti-help-circle"></i>
                            <p class="mb-1 fw-semibold">No FAQs added yet</p>
                            <small>Click <strong>Add FAQ</strong> to add questions &amp; answers.</small>
                        </div>
                        <div id="faq-accordion" class="accordion cst-accordion"></div>
                    </div>
                </div>

                {{-- ── Section 8 · SEO / Meta ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-secondary text-white">8</span>
                        <h6 class="mb-0 fw-semibold">SEO / Meta Details</h6>
                        <small class="text-muted ms-1">— all optional</small>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="control-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    placeholder="Page title for search engines" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-control"
                                    placeholder="Short description for search results (150–160 chars)"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Keywords</label>
                                <textarea name="meta_keywords" rows="3" class="form-control"
                                    placeholder="Comma-separated keywords"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </form>{{-- /add_form --}}

        </div>{{-- /col-md-9 --}}

        {{-- ═══════════════════════════ RIGHT SIDEBAR ═══════════════════════════ --}}
        <div class="col-md-3">
            <div class="sidebar-sticky">

                {{-- Save card --}}
                <div class="sidebar-card" style="border-color:#10b981!important">
                    <div class="sidebar-card-header" style="background:#d1fae5;color:#065f46">
                        <i class="ti ti-folder-plus"></i> Create Category
                    </div>
                    <div class="sidebar-card-body">
                        <div id="save-notification" class="d-none mb-2 p-2 rounded small fw-semibold" role="alert" style="word-break:break-word"></div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" id="createBtn">
                                <i class="ti ti-device-floppy me-2"></i> Save Category
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
                                    class="form-check-input" value="1" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Connected Agent <span class="required-star">*</span></label>
                            <select id="agent_id" class="form-control select2-sidebar"
                                data-placeholder="Select staff member">
                                <option></option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ $agent->user->first_name . ' ' . $agent->user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="control-label">Inquiry Officer Name</label>
                            <input type="text" id="inq_officer_name" class="form-control"
                                placeholder="Officer name" />
                        </div>
                        <div>
                            <label class="control-label">Inquiry Officer Phone</label>
                            <input type="text" id="inq_officer_phone" class="form-control"
                                placeholder="e.g. 94774702259" />
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
                                <option value="{{ $ann->id }}">{{ $ann->title }}</option>
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
                            <li class="mb-1">The <strong>slug</strong> is auto-filled from the name — edit if needed.</li>
                            <li class="mb-1"><strong>Connected Agent</strong> is required before saving.</li>
                            <li class="mb-1">Core services, FAQs and magazines are <strong>optional</strong>.</li>
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

        /* ─── Select2 ─── */
        $('.select2').select2({ minimumResultsForSearch: 8 });
        $('.select2-sidebar').select2({ minimumResultsForSearch: 8, dropdownParent: $('body') });

        /* ─── Slug auto-fill ─── */
        $('#name').on('input', function () {
            $('#slug').val($(this).val().toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-'));
        });

        /* ─── Counters ─── */
        let magIdx = 0, faqIdx = 0, coreIdx = 0, processIdx = 0;

        function updateCoreCount()    { const n=$('#core-accordion .accordion-item').length; $('#core-count').text(n+' '+(n===1?'item':'items')); n===0?$('#core-empty-state').removeClass('d-none'):$('#core-empty-state').addClass('d-none'); }
        function updateProcessCount() { const n=$('#process-accordion .accordion-item').length; $('#process-count').text(n+' '+(n===1?'item':'items')); n===0?$('#process-empty-state').removeClass('d-none'):$('#process-empty-state').addClass('d-none'); }
        function updateMagCount()     { const n=$('#magazine-accordion .accordion-item').length; $('#mag-count').text(n+' '+(n===1?'item':'items')); n===0?$('#mag-empty-state').removeClass('d-none'):$('#mag-empty-state').addClass('d-none'); }
        function updateFaqCount()     { const n=$('#faq-accordion .accordion-item').length; $('#faq-count').text(n+' '+(n===1?'item':'items')); n===0?$('#faq-empty-state').removeClass('d-none'):$('#faq-empty-state').addClass('d-none'); }

        /* ─── Live header sync ─── */
        $(document).on('input', '.core-header-input',    function () { $(this).closest('.accordion-item').find('.core-item-title').text($(this).val().trim() || 'Core Service'); });
        $(document).on('input', '.process-header-input', function () { $(this).closest('.accordion-item').find('.process-item-title').text($(this).val().trim() || 'Process Step'); });
        $(document).on('input', '.mag-title-input',      function () { $(this).closest('.accordion-item').find('.mag-item-title').text($(this).val().trim() || 'Magazine Item'); });
        $(document).on('input', '.faq-question-input',   function () { $(this).closest('.accordion-item').find('.faq-item-question').text(($(this).val().trim() || 'FAQ Question').substring(0, 60)); });

        /* ─── Item builders ─── */
        function buildCoreItem(idx) {
            return `<div class="accordion-item core-service-section-item" id="core-item-${idx}">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#core-collapse-${idx}" aria-expanded="true">
                    <span class="badge me-2 text-white" style="background:#059669;min-width:26px">#</span>
                    <span class="core-item-title">New Core Service</span>
                </button></h2>
                <div id="core-collapse-${idx}" class="accordion-collapse collapse show"><div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="control-label">Core Service Header</label>
                            <input type="text" name="core_service_header[]" class="form-control core-header-input" placeholder="e.g. Quality Management" /></div>
                        <div class="col-12"><label class="control-label">Core Service Description</label>
                            <textarea id="core_desc_${idx}" name="core_service_description[]" rows="4" class="form-control" placeholder="Core service description..."></textarea></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3"><button type="button" class="btn btn-sm btn-outline-danger remove-core-section"><i class="ti ti-trash me-1"></i> Remove</button></div>
                </div></div></div>`;
        }

        function buildProcessItem(idx) {
            return `<div class="accordion-item process-section-item" id="process-item-${idx}">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#process-collapse-${idx}" aria-expanded="true">
                    <span class="badge me-2 text-white" style="background:#0891b2;min-width:26px">#</span>
                    <span class="process-item-title">New Process Step</span>
                </button></h2>
                <div id="process-collapse-${idx}" class="accordion-collapse collapse show"><div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="control-label">Process Header</label>
                            <input type="text" name="process_header[]" class="form-control process-header-input" placeholder="e.g. Initial Assessment" /></div>
                        <div class="col-12"><label class="control-label">Process Description</label>
                            <textarea id="process_desc_${idx}" name="process_description[]" rows="4" class="form-control" placeholder="Process step description..."></textarea></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3"><button type="button" class="btn btn-sm btn-outline-danger remove-process-section"><i class="ti ti-trash me-1"></i> Remove</button></div>
                </div></div></div>`;
        }

        function buildMagItem(idx) {
            const num = $('#magazine-accordion .accordion-item').length + 1;
            return `<div class="accordion-item magazine-section-item" id="mag-item-${idx}">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#mag-collapse-${idx}" aria-expanded="true">
                    <span class="badge me-2" style="background:#7c3aed;color:#fff;min-width:26px">#${num}</span>
                    <span class="mag-item-title text-truncate" style="max-width:300px">New Magazine Item</span>
                </button></h2>
                <div id="mag-collapse-${idx}" class="accordion-collapse collapse show"><div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-md-8"><label class="control-label">Title <span class="required-star">*</span></label>
                            <input type="text" name="magazines[${idx}][title]" class="form-control mag-title-input" placeholder="Magazine title" required /></div>
                        <div class="col-md-4"><label class="control-label">Image</label>
                            <input type="file" name="magazines[${idx}][image]" class="form-control" accept="image/*" />
                            <div class="field-hint">Max 4MB</div></div>
                        <div class="col-12"><label class="control-label">Description <span class="required-star">*</span></label>
                            <textarea id="mag_desc_${idx}" name="magazines[${idx}][description]" rows="4" class="form-control" placeholder="Magazine description..." required></textarea></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3"><button type="button" class="btn btn-sm btn-outline-danger remove-magazine-section"><i class="ti ti-trash me-1"></i> Remove this item</button></div>
                </div></div></div>`;
        }

        function buildFaqItem(idx) {
            const num = $('#faq-accordion .accordion-item').length + 1;
            return `<div class="accordion-item faq-section" id="faq-item-${idx}">
                <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-${idx}" aria-expanded="true">
                    <span class="badge me-2 text-dark" style="background:#fbbf24;min-width:26px">Q${num}</span>
                    <span class="faq-item-question text-truncate" style="max-width:300px">New FAQ</span>
                </button></h2>
                <div id="faq-collapse-${idx}" class="accordion-collapse collapse show"><div class="accordion-body">
                    <div class="mb-3"><label class="control-label">Question <span class="required-star">*</span></label>
                        <input type="text" name="faqs[${idx}][question]" class="form-control faq-question-input" placeholder="FAQ question..." required /></div>
                    <div class="mb-3"><label class="control-label">Answer</label>
                        <textarea id="faq_ans_${idx}" name="faqs[${idx}][answer]" rows="4" class="form-control" placeholder="FAQ answer..."></textarea></div>
                    <div class="d-flex justify-content-end"><button type="button" class="btn btn-sm btn-outline-danger remove-faq-section"><i class="ti ti-trash me-1"></i> Remove this FAQ</button></div>
                </div></div></div>`;
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

        $('#addMagazineBtn').on('click', function () {
            $('#magazine-accordion').append(buildMagItem(magIdx));
            initTinyMCE('#mag_desc_' + magIdx, { height: 200 });
            magIdx++;
            updateMagCount();
        });

        $('#addFaqBtn').on('click', function () {
            $('#faq-accordion').append(buildFaqItem(faqIdx));
            initTinyMCE('#faq_ans_' + faqIdx, { height: 180, menubar: false });
            faqIdx++;
            updateFaqCount();
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
                    const tid = $item.find('textarea[id]').attr('id');
                    if (tid && typeof tinymce !== 'undefined' && tinymce.get(tid)) { try { tinymce.get(tid).destroy(); } catch(e){} }
                    $item.slideUp(200, function () { $(this).remove(); afterRemove(); });
                }
            });
        }

        $(document).on('click', '.remove-core-section',     function () { confirmRemove('Remove core service?',  $(this).closest('.accordion-item').find('.core-item-title').text(),    $(this).closest('.accordion-item'), updateCoreCount); });
        $(document).on('click', '.remove-process-section',  function () { confirmRemove('Remove process step?',  $(this).closest('.accordion-item').find('.process-item-title').text(), $(this).closest('.accordion-item'), updateProcessCount); });
        $(document).on('click', '.remove-magazine-section', function () { confirmRemove('Remove magazine item?', $(this).closest('.accordion-item').find('.mag-item-title').text(),     $(this).closest('.accordion-item'), updateMagCount); });
        $(document).on('click', '.remove-faq-section',      function () { confirmRemove('Remove FAQ?',           $(this).closest('.accordion-item').find('.faq-item-question').text(),  $(this).closest('.accordion-item'), updateFaqCount); });

        /* ─── Prevent native submit ─── */
        $('#add_form').on('submit', function (e) { e.preventDefault(); });

        /* ─── AJAX Submit ─── */
        $('#createBtn').on('click', function () {
            const agentVal = $('#agent_id').val();
            if (!agentVal) {
                Toast.fire({ icon: 'warning', title: 'Please select a Connected Agent.' });
                return;
            }

            if (typeof tinymce !== 'undefined') {
                try { tinymce.triggerSave(); } catch(e) {}
            }

            const formData = new FormData($('#add_form')[0]);

            /* Sidebar fields (outside the form element) */
            if ($('#featured_toggle').is(':checked')) formData.append('featured', '1');
            formData.append('agent_id',         agentVal);
            formData.append('inq_officer_name',  $('#inq_officer_name').val()  || '');
            formData.append('inq_officer_phone', $('#inq_officer_phone').val() || '');
            const annVal = $('#announcement_id').val();
            if (annVal) formData.append('announcement_id', annVal);

            const $notif = $('#save-notification');
            $notif.addClass('d-none');

            Swal.fire({ title: 'Saving...', text: 'Please wait.', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });

            $.ajax({
                url: $('#add_form').attr('action'),
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Category Created!',
                        text: response.message,
                        showCancelButton: true,
                        confirmButtonText: 'Add Another',
                        cancelButtonText: 'View All Categories',
                        confirmButtonColor: '#1a8a4a',
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            /* Reset form inputs */
                            $('#add_form')[0].reset();

                            /* Clear TinyMCE content */
                            if (typeof tinymce !== 'undefined') {
                                document.querySelectorAll('.rich-textarea').forEach(function (ta) {
                                    const ed = tinymce.get(ta.id);
                                    if (ed) ed.setContent('');
                                });
                            }

                            /* Reset Select2 */
                            $('.select2, .select2-sidebar').val(null).trigger('change');
                            $('#slug').val('');

                            /* Clear dynamic accordion sections */
                            ['#core-accordion', '#process-accordion', '#magazine-accordion', '#faq-accordion'].forEach(function (sel) {
                                $(sel + ' .accordion-item').each(function () {
                                    const tid = $(this).find('textarea[id]').attr('id');
                                    if (tid && typeof tinymce !== 'undefined' && tinymce.get(tid)) { try { tinymce.get(tid).destroy(); } catch(e){} }
                                });
                                $(sel).empty();
                            });
                            coreIdx = 0; processIdx = 0; magIdx = 0; faqIdx = 0;
                            updateCoreCount(); updateProcessCount(); updateMagCount(); updateFaqCount();

                            $notif.removeClass('d-none alert-danger')
                                .addClass('alert alert-success')
                                .html('<i class="ti ti-circle-check me-1"></i> Category created. Form ready for another entry.');
                            setTimeout(function () { $notif.addClass('d-none'); }, 5000);

                            $('#server-error-box').addClass('d-none');
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else {
                            location.href = '{{ route('categories.index') }}';
                        }
                    });
                },
                error: function (xhr) {
                    Swal.close();
                    let listHtml = '';
                    let errorMsg = 'Something went wrong. Please try again.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (k, v) {
                                listHtml += '<li>' + (Array.isArray(v) ? v[0] : v) + '</li>';
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
                    Swal.fire({ icon: 'error', title: 'Could not save', html: '<ul style="text-align:left">' + (listHtml || '<li>' + errorMsg + '</li>') + '</ul>' });
                }
            });
        });

    });
    </script>
@endsection
