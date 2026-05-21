@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        /* ── Layout ── */
        .section-card { border: 1px solid #e8edf2; border-radius: 10px; overflow: hidden; margin-bottom: 1.25rem; }
        .section-card .section-header {
            background: #f8fafc; border-bottom: 1px solid #e8edf2;
            padding: .85rem 1.25rem; display: flex; align-items: center; gap: .6rem;
        }
        .section-badge {
            width: 24px; height: 24px; border-radius: 50%; display: inline-flex;
            align-items: center; justify-content: center; font-size: .7rem; font-weight: 700; flex-shrink: 0;
        }
        .section-body { padding: 1.25rem; }

        /* ── Sidebar sticky ── */
        .sidebar-sticky { position: sticky; top: 76px; }

        /* ── Accordion custom ── */
        .cst-accordion .accordion-item { border: 1px solid #dee2e6; border-radius: 8px !important; margin-bottom: .6rem; overflow: hidden; }
        .cst-accordion .accordion-button { font-weight: 600; font-size: .875rem; background: #fff; color: #2d3a4a; }
        .cst-accordion .accordion-button:not(.collapsed) { background: #eef4ff; color: #1a56db; box-shadow: none; }
        .cst-accordion .accordion-button::after { background-size: 14px; }
        .cst-accordion .accordion-body { background: #fdfdff; padding: 1rem 1.25rem; }
        .item-drag-handle { cursor: grab; color: #adb5bd; margin-right: .5rem; }

        /* ── Empty states ── */
        .empty-state { text-align: center; padding: 2rem 1rem; color: #9aa5b4; }
        .empty-state i { font-size: 2.2rem; margin-bottom: .5rem; display: block; }

        /* ── Image upload preview ── */
        .image-preview-thumb { width: 80px; height: 70px; object-fit: cover; border-radius: 6px; border: 2px solid #dee2e6; }
        .carousel-img-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .5rem; }

        /* ── Sidebar cards ── */
        .sidebar-card { border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 1rem; }
        .sidebar-card .sidebar-card-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: .7rem 1rem; font-weight: 600; font-size: .85rem; display: flex; align-items: center; gap: .4rem; }
        .sidebar-card .sidebar-card-body { padding: 1rem; }

        /* ── Status selector ── */
        .status-radio-group .btn-check:checked + .btn-outline-success { background: #d1fae5; color: #065f46; border-color: #10b981; }
        .status-radio-group .btn-check:checked + .btn-outline-secondary { background: #f1f5f9; color: #475569; border-color: #94a3b8; }

        /* ── Form helpers ── */
        .field-hint { font-size: .78rem; color: #6b7280; margin-top: .25rem; }
        .required-star { color: #ef4444; }
        label.form-label, .control-label { font-weight: 500; font-size: .85rem; margin-bottom: .3rem; display: block; }
        .mce-placeholder { background: #f9fafb; border-radius: 6px; }

        /* ── Count badges ── */
        .item-count-badge { background: #e0f2fe; color: #0369a1; border-radius: 20px; padding: 2px 10px; font-size: .73rem; font-weight: 600; }

        /* ── Remove confirm guard ── */
        .remove-btn-wrap .btn { transition: opacity .15s; }

        /* ── Full-width override ── */
        .body-wrapper > .container-fluid { padding-left: 0 !important; padding-right: 0 !important; }
    </style>
@endsection

@section('content')
    {{-- Breadcrumb --}}
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-layers-linked me-2"></i>Create New Service</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('services.store') }}" method="POST" id="add_form">
        <input type="hidden" name="status" id="form_status" value="published">

        {{-- Error box --}}
        <div class="alert alert-danger d-none mb-4" id="server-error-box" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="ti ti-alert-circle mt-1 flex-shrink-0"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1" id="server-error-list"></ul>
                </div>
            </div>
        </div>

        <div class="row g-0">

            {{-- ═══════════════════════════════════════════════
                 LEFT COLUMN — Main content
            ═══════════════════════════════════════════════ --}}
            <div class="col-md-9 pe-3">

                {{-- ── Section 1 · Basic Information ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-primary text-white">1</span>
                        <h6 class="mb-0 fw-semibold">Basic Information</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="control-label">Service Name <span class="required-star">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="e.g. Healthcare Quality Assurance" required />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">URL Slug <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:.8rem">/services/</span>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="auto-generated" required />
                                </div>
                                <div class="field-hint">Auto-filled from name. Edit to customise.</div>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label">Categories <span class="required-star">*</span></label>
                                <select name="categories[]" class="form-control select2"
                                    data-placeholder="Select one or more categories" required multiple>
                                    <option></option>
                                    @if (isset($main_categories) && count($main_categories) > 0)
                                        @foreach ($main_categories as $main_category)
                                            <optgroup label="{{ $main_category->name }}">
                                                @foreach ($main_category->categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Connected Agent <span class="required-star">*</span></label>
                                <select name="agent" class="form-control select2"
                                    data-placeholder="Select staff member" required>
                                    <option></option>
                                    @if (isset($agents) && count($agents) > 0)
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}">
                                                {{ $agent->user->first_name . ' ' . $agent->user->last_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                {{-- empty spacer or future field --}}
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Inquiry Officer Name</label>
                                <input type="text" name="inq_officer_name" class="form-control"
                                    placeholder="Enter officer name" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Inquiry Officer WhatsApp</label>
                                <input type="text" name="inq_officer_phone" class="form-control"
                                    placeholder="971 X XXX XXXX" />
                                <div class="field-hint">Country code without + or 00 (e.g. 971XXXXXXXXX)</div>
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
                                <input type="file" name="hero_image" id="hero_image_input" class="form-control" accept="image/*" required />
                                <div class="field-hint">Main banner image for the service page.</div>
                                <div id="hero-preview" class="mt-2 d-none">
                                    <img id="hero-preview-img" src="" alt="Preview" class="image-preview-thumb">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Strategy Carousel Images</label>
                                <div id="service-images-container">
                                    <div class="carousel-img-row">
                                        <input type="file" name="images[]" class="form-control" accept="image/*" />
                                        <button type="button" class="btn btn-outline-success btn-sm add-image-btn flex-shrink-0" title="Add another image">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="field-hint">Left-side slider images (optional, multiple allowed).</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 3 · Page Content ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-success text-white">3</span>
                        <h6 class="mb-0 fw-semibold">Page Content</h6>
                        <small class="text-muted ms-1">Fields marked * are required</small>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="control-label">
                                    Hero Description <span class="required-star">*</span>
                                    <span class="text-muted fw-normal">(shown in banner)</span>
                                </label>
                                <textarea name="content" rows="6" class="rich-textarea form-control"
                                    placeholder="Short description shown in the hero/banner area..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">
                                    Intro Description <span class="required-star">*</span>
                                    <span class="text-muted fw-normal">(overview section)</span>
                                </label>
                                <textarea name="overview" rows="6" class="rich-textarea form-control"
                                    placeholder="Introduction paragraph shown below the hero..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">
                                    Core Service Header <span class="required-star">*</span>
                                </label>
                                <textarea name="info_one" rows="5" class="rich-textarea form-control"
                                    placeholder="Heading for the core services block..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">
                                    Core Service Description <span class="required-star">*</span>
                                </label>
                                <textarea name="info_two" rows="5" class="rich-textarea form-control"
                                    placeholder="Detail text for the core services block..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">
                                    CTA Header
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <textarea name="info_three" rows="4" class="rich-textarea form-control"
                                    placeholder="Call-to-action heading (e.g. How Alpha Can Help)..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">
                                    CTA Description
                                    <span class="text-muted fw-normal">(why choose us)</span>
                                </label>
                                <textarea name="info_four" rows="4" class="rich-textarea form-control"
                                    placeholder="Why choose us / benefits description..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 4 · Magazine / Insights ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge bg-purple text-white" style="background:#7c3aed!important">4</span>
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
                            <small>Click <strong>Add Item</strong> to add magazine/insights cards shown on this service page.</small>
                        </div>
                        <div id="magazine-accordion" class="accordion cst-accordion"></div>
                    </div>
                </div>

                {{-- ── Section 5 · FAQ ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-dark" style="background:#fbbf24!important">5</span>
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
                            <small>Click <strong>Add FAQ</strong> to add questions &amp; answers for visitors.</small>
                        </div>
                        <div id="faq-accordion" class="accordion cst-accordion"></div>
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
                            <div class="col-md-12">
                                <label class="control-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    placeholder="Page title for search engines (leave blank to use service name)" />
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
                            <div class="col-md-6">
                                <label class="control-label">areaServed</label>
                                <input type="text" name="areaServed" class="form-control"
                                    placeholder="e.g. Dubai, UAE" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">serviceType</label>
                                <input type="text" name="serviceType" class="form-control"
                                    placeholder="e.g. Healthcare Consulting" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /col-md-9 --}}

            {{-- ═══════════════════════════════════════════════
                 RIGHT COLUMN — Sidebar actions
            ═══════════════════════════════════════════════ --}}
            <div class="col-md-3">
                <div class="sidebar-sticky">

                    {{-- Publish card --}}
                    <div class="sidebar-card border-success" style="border-color:#10b981!important">
                        <div class="sidebar-card-header" style="background:#d1fae5;color:#065f46">
                            <i class="ti ti-send"></i> Publish Service
                        </div>
                        <div class="sidebar-card-body">
                            {{-- Status selector --}}
                            <label class="control-label mb-2">Visibility</label>
                            <div class="d-flex gap-2 mb-1 status-radio-group">
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="status_selector" id="status_published" value="published" checked>
                                    <label class="btn btn-outline-success w-100 py-2" for="status_published">
                                        <i class="ti ti-world d-block mb-1" style="font-size:1.2rem"></i>
                                        <span style="font-size:.8rem">Published</span>
                                    </label>
                                </div>
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="status_selector" id="status_draft" value="draft">
                                    <label class="btn btn-outline-secondary w-100 py-2" for="status_draft">
                                        <i class="ti ti-pencil d-block mb-1" style="font-size:1.2rem"></i>
                                        <span style="font-size:.8rem">Draft</span>
                                    </label>
                                </div>
                            </div>
                            <div class="field-hint mb-3" id="status-hint">
                                <i class="ti ti-info-circle me-1"></i>
                                <span id="status-hint-text">Service will be visible on the website.</span>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success btn-lg" id="publish-btn">
                                    <i class="ti ti-device-floppy me-2"></i> Save Service
                                </button>
                                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to Services
                                </a>
                            </div>
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
                                    value="{{ now()->toDateString() }}" required />
                                <small class="field-hint">Date shown as "Published" on the service page.</small>
                            </div>
                            <div class="mb-1">
                                <label class="control-label mb-1" for="updated_date">Last Updated Date</label>
                                <input type="date" id="updated_date" name="updated_date"
                                    class="form-control form-control-sm"
                                    value="{{ now()->toDateString() }}" />
                                <small class="field-hint">Date shown as "Updated" on the service page.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Options card --}}
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <i class="ti ti-adjustments-horizontal"></i> Service Options
                        </div>
                        <div class="sidebar-card-body">
                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div>
                                    <div class="fw-semibold" style="font-size:.875rem">Featured Service</div>
                                    <div class="field-hint mb-0">Highlight in featured section on homepage</div>
                                </div>
                                <div class="form-check form-switch mb-0 ms-3">
                                    <input type="checkbox" name="featured" class="form-check-input"
                                        value="1" id="featured_toggle" role="switch" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Related content card --}}
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <i class="ti ti-link"></i> Related Content
                        </div>
                        <div class="sidebar-card-body">
                            <div class="mb-3">
                                <label class="control-label">Related Services</label>
                                <select name="related_services[]" class="form-control select2"
                                    data-placeholder="Choose related services" multiple>
                                    <option></option>
                                    @if (isset($services) && count($services) > 0)
                                        @foreach ($services as $service_item)
                                            <option value="{{ $service_item->id }}">{{ $service_item->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="field-hint">Shown at the bottom of the service page.</div>
                            </div>
                            <div>
                                <label class="control-label">Announcement</label>
                                <select name="announcement_id" class="form-control select2"
                                    data-placeholder="Select announcement">
                                    <option value="">No Announcement</option>
                                    @if (isset($announcements) && count($announcements) > 0)
                                        @foreach ($announcements as $ann)
                                            <option value="{{ $ann->id }}">{{ $ann->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="field-hint">Shown under "You might be interested in".</div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick tips card --}}
                    <div class="sidebar-card" style="border-color:#bfdbfe!important">
                        <div class="sidebar-card-header" style="background:#eff6ff;color:#1e40af">
                            <i class="ti ti-bulb"></i> Quick Tips
                        </div>
                        <div class="sidebar-card-body" style="font-size:.82rem;color:#374151">
                            <ul class="mb-0 ps-3">
                                <li class="mb-1">The <strong>slug</strong> is auto-filled from the name — edit it if needed.</li>
                                <li class="mb-1"><strong>Magazine items</strong> appear as insight cards on the page.</li>
                                <li class="mb-1">Use <strong>Draft</strong> to save without making it live.</li>
                                <li class="mb-0">Required fields are marked <span class="text-danger">*</span></li>
                            </ul>
                        </div>
                    </div>

                </div>{{-- /sidebar-sticky --}}
            </div>{{-- /col-md-3 --}}

        </div>{{-- /row --}}
    </form>
@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>

    <script>
    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       TinyMCE shared config
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    const tinyConfig = {
        plugins: 'code searchreplace autolink directionality visualblocks link media table charmap nonbreaking anchor advlist lists wordcount fullscreen',
        toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright | bullist numlist | fullscreen code',
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/upload-image',
        branding: false,
        promotion: false,
        images_upload_handler: function (blobInfo, success, failure) {
            var fd = new FormData();
            fd.append('file', blobInfo.blob(), blobInfo.filename());
            fetch('/upload-image', { method: 'POST', body: fd })
                .then(r => r.ok ? r.json() : Promise.reject(r))
                .then(j => j.location ? success(j.location) : failure('No location in response'))
                .catch(e => failure('Upload failed: ' + e));
        }
    };

    function initTinyMCE(selector, extraCfg = {}) {
        return tinymce.init({ selector, ...tinyConfig, ...extraCfg });
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       Counters
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    let magIndex     = 0;   // ever-incrementing accordion IDs
    let faqIndex     = 0;
    let magCount     = 0;   // visible item count
    let faqCount     = 0;

    function updateMagCount() {
        magCount = $('#magazine-accordion .accordion-item').length;
        $('#mag-count').text(magCount + (magCount === 1 ? ' item' : ' items'));
        magCount > 0 ? $('#mag-empty-state').addClass('d-none') : $('#mag-empty-state').removeClass('d-none');
    }
    function updateFaqCount() {
        faqCount = $('#faq-accordion .accordion-item').length;
        $('#faq-count').text(faqCount + (faqCount === 1 ? ' item' : ' items'));
        faqCount > 0 ? $('#faq-empty-state').addClass('d-none') : $('#faq-empty-state').removeClass('d-none');
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       Magazine helpers
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function buildMagazineItem(idx) {
        const num = $('#magazine-accordion .accordion-item').length + 1;
        return `
        <div class="accordion-item magazine-section-item" id="mag-item-${idx}">
            <h2 class="accordion-header" id="mag-heading-${idx}">
                <button class="accordion-button" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mag-collapse-${idx}"
                    aria-expanded="true" aria-controls="mag-collapse-${idx}">
                    <span class="badge me-2" style="background:#7c3aed;color:#fff;min-width:26px">#${num}</span>
                    <span class="mag-item-title text-truncate" style="max-width:300px">New Magazine Item</span>
                </button>
            </h2>
            <div id="mag-collapse-${idx}" class="accordion-collapse collapse show"
                aria-labelledby="mag-heading-${idx}">
                <div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="control-label">Title <span class="required-star">*</span></label>
                            <input type="text"
                                name="magazines[${idx}][title]"
                                class="form-control mag-title-input"
                                placeholder="Magazine / insight card title"
                                required />
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Image <span class="required-star">*</span></label>
                            <input type="file" name="magazines[${idx}][image]"
                                class="form-control mag-img-input"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required />
                            <div class="field-hint">Max 4MB · jpg/png/webp</div>
                            <div class="mag-img-preview mt-2 d-none">
                                <img src="" alt="Preview" class="image-preview-thumb">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="control-label">Description <span class="required-star">*</span></label>
                            <textarea id="mag_desc_${idx}"
                                name="magazines[${idx}][description]"
                                rows="4" class="form-control"
                                placeholder="Write the magazine / insight content here..." required></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button"
                            class="btn btn-sm btn-outline-danger remove-magazine-section"
                            data-idx="${idx}">
                            <i class="ti ti-trash me-1"></i> Remove this item
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       FAQ helpers
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function buildFaqItem(idx) {
        const num = $('#faq-accordion .accordion-item').length + 1;
        return `
        <div class="accordion-item faq-section" id="faq-item-${idx}">
            <h2 class="accordion-header" id="faq-heading-${idx}">
                <button class="accordion-button" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-collapse-${idx}"
                    aria-expanded="true" aria-controls="faq-collapse-${idx}">
                    <span class="badge me-2 text-dark" style="background:#fbbf24;min-width:26px">Q${num}</span>
                    <span class="faq-item-question text-truncate" style="max-width:300px">New FAQ Question</span>
                </button>
            </h2>
            <div id="faq-collapse-${idx}" class="accordion-collapse collapse show"
                aria-labelledby="faq-heading-${idx}">
                <div class="accordion-body">
                    <div class="mb-3">
                        <label class="control-label">Question <span class="required-star">*</span></label>
                        <input type="text"
                            name="faqs[${idx}][question]"
                            class="form-control faq-question-input"
                            placeholder="Enter the question visitors commonly ask..."
                            required />
                    </div>
                    <div class="mb-3">
                        <label class="control-label">Answer <span class="required-star">*</span></label>
                        <textarea id="faq_ans_${idx}"
                            name="faqs[${idx}][answer]"
                            rows="4" class="form-control"
                            placeholder="Enter the answer..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button"
                            class="btn btn-sm btn-outline-danger remove-faq-section"
                            data-idx="${idx}">
                            <i class="ti ti-trash me-1"></i> Remove this FAQ
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       Document ready
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    $(document).ready(function () {

        /* Init TinyMCE for all static rich-textareas */
        initTinyMCE('.rich-textarea', { height: 240 });

        /* Select2 */
        $('.select2').select2({ minimumResultsForSearch: 8 });

        /* Status selector → update hidden input + hint text */
        $('input[name="status_selector"]').on('change', function () {
            const v = $(this).val();
            $('#form_status').val(v);
            if (v === 'published') {
                $('#status-hint-text').text('Service will be visible on the website.');
            } else {
                $('#status-hint-text').text('Service will be saved but hidden from visitors.');
            }
        });

        /* Save button */
        $('#publish-btn').on('click', function () {
            $('#form_status').val($('input[name="status_selector"]:checked').val() || 'published');
            $('#add_form').submit();
        });

        /* Slug auto-generation */
        $('#name').on('input', function () {
            const slug = $(this).val().toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#slug').val(slug);
        });

        /* Hero image preview */
        $('#hero_image_input').on('change', function () {
            const file = this.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                $('#hero-preview-img').attr('src', url);
                $('#hero-preview').removeClass('d-none');
            }
        });

        /* Carousel image add/remove */
        $(document).on('click', '.add-image-btn', function () {
            $('#service-images-container').append(`
                <div class="carousel-img-row">
                    <input type="file" name="images[]" class="form-control" accept="image/*" />
                    <button type="button" class="btn btn-outline-danger btn-sm remove-image-btn flex-shrink-0" title="Remove">
                        <i class="ti ti-minus"></i>
                    </button>
                </div>`);
        });
        $(document).on('click', '.remove-image-btn', function () {
            $(this).closest('.carousel-img-row').remove();
        });

        /* ── ADD MAGAZINE ITEM ── */
        $('#addMagazineBtn').on('click', function () {
            const idx = magIndex++;
            $('#magazine-accordion').append(buildMagazineItem(idx));
            updateMagCount();

            // Live title → header sync
            $(`#mag-item-${idx} .mag-title-input`).on('input', function () {
                const v = $(this).val().trim();
                $(`#mag-item-${idx} .mag-item-title`).text(v || 'New Magazine Item');
            });

            // Image preview
            $(`#mag-item-${idx} .mag-img-input`).on('change', function () {
                const file = this.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    const $wrap = $(`#mag-item-${idx} .mag-img-preview`);
                    $wrap.find('img').attr('src', url);
                    $wrap.removeClass('d-none');
                }
            });

            // Init TinyMCE
            initTinyMCE(`#mag_desc_${idx}`, { height: 200 });

            // Scroll to new item
            setTimeout(() => {
                document.getElementById(`mag-item-${idx}`).scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        });

        /* ── REMOVE MAGAZINE ITEM (with confirmation) ── */
        $(document).on('click', '.remove-magazine-section', function () {
            const $item   = $(this).closest('.magazine-section-item');
            const titleEl = $item.find('.mag-title-input').val().trim();
            const label   = titleEl ? `"${titleEl}"` : 'this magazine item';

            Swal.fire({
                title: 'Remove magazine item?',
                html: `You are about to remove <strong>${label}</strong>.<br>This cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Yes, remove it',
                cancelButtonText: 'Keep it',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const editorId = $item.find('textarea[id]').attr('id');
                    if (editorId && tinymce.get(editorId)) {
                        tinymce.get(editorId).remove();
                    }
                    $item.slideUp(200, function () {
                        $(this).remove();
                        updateMagCount();
                    });
                }
            });
        });

        /* ── ADD FAQ ITEM ── */
        $('#addFaqBtn').on('click', function () {
            const idx = faqIndex++;
            $('#faq-accordion').append(buildFaqItem(idx));
            updateFaqCount();

            // Live question → header sync
            $(`#faq-item-${idx} .faq-question-input`).on('input', function () {
                const v = $(this).val().trim();
                $(`#faq-item-${idx} .faq-item-question`).text(v || 'New FAQ Question');
            });

            // Init TinyMCE
            initTinyMCE(`#faq_ans_${idx}`, { height: 180, menubar: false });

            // Scroll to new item
            setTimeout(() => {
                document.getElementById(`faq-item-${idx}`).scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        });

        /* ── REMOVE FAQ ITEM (with confirmation) ── */
        $(document).on('click', '.remove-faq-section', function () {
            const $item    = $(this).closest('.faq-section');
            const questionEl = $item.find('.faq-question-input').val().trim();
            const label    = questionEl ? `"${questionEl}"` : 'this FAQ item';

            Swal.fire({
                title: 'Remove FAQ?',
                html: `You are about to remove <strong>${label}</strong>.<br>This cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Yes, remove it',
                cancelButtonText: 'Keep it',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const editorId = $item.find('textarea[id]').attr('id');
                    if (editorId && tinymce.get(editorId)) {
                        tinymce.get(editorId).remove();
                    }
                    $item.slideUp(200, function () {
                        $(this).remove();
                        updateFaqCount();
                    });
                }
            });
        });

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           Form validation & AJAX submit
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $('#add_form').validate({
            rules: {
                name:          { required: true },
                'categories[]':{ required: true },
                overview:      { required: true },
                content:       { required: true },
                info_one:      { required: true },
                info_two:      { required: true },
                hero_image:    { required: true },
            },
            messages: {
                name:          { required: 'Service name is required.' },
                'categories[]':{ required: 'Please select at least one category.' },
                overview:      { required: 'Intro description is required.' },
                content:       { required: 'Hero description is required.' },
                info_one:      { required: 'Core service header is required.' },
                info_two:      { required: 'Core service description is required.' },
                hero_image:    { required: 'Please upload a hero image.' },
            },

            submitHandler: function (form) {
                // Sync all TinyMCE 6 editors before submitting
                if (typeof tinymce !== 'undefined') {
                    document.querySelectorAll('textarea[id]').forEach(function (ta) {
                        const ed = tinymce.get(ta.id);
                        if (ed) ta.value = ed.getContent();
                    });
                }

                let formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Saving...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        const isDraft    = response.data && response.data.status === 'draft';
                        const titleLabel = isDraft ? '✏️ Saved as Draft' : '✅ Published!';

                        Swal.fire({
                            icon: 'success',
                            title: titleLabel,
                            text: response.message,
                            showCancelButton: true,
                            confirmButtonText: 'Add Another Service',
                            cancelButtonText: 'View All Services',
                            confirmButtonColor: '#1a8a4a',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Full page reset
                                form.reset();
                                if (typeof tinymce !== 'undefined') {
                                    document.querySelectorAll('textarea[id]').forEach(function (ta) {
                                        const ed = tinymce.get(ta.id);
                                        if (ed) ed.setContent('');
                                    });
                                }
                                $('.select2').val(null).trigger('change');
                                $('#slug').val('');
                                $('#hero-preview').addClass('d-none');
                                $('#service-images-container').html(`
                                    <div class="carousel-img-row">
                                        <input type="file" name="images[]" class="form-control" accept="image/*" />
                                        <button type="button" class="btn btn-outline-success btn-sm add-image-btn flex-shrink-0">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>`);
                                // Remove all magazine/FAQ accordion items + their TinyMCE instances
                                $('#magazine-accordion .accordion-item').each(function () {
                                    const edId = $(this).find('textarea[id]').attr('id');
                                    if (edId && tinymce.get(edId)) tinymce.get(edId).remove();
                                });
                                $('#magazine-accordion').empty();
                                $('#faq-accordion .accordion-item').each(function () {
                                    const edId = $(this).find('textarea[id]').attr('id');
                                    if (edId && tinymce.get(edId)) tinymce.get(edId).remove();
                                });
                                $('#faq-accordion').empty();
                                updateMagCount(); updateFaqCount();
                                $('#server-error-box').addClass('d-none');
                                // Reset status selector
                                $('#status_published').prop('checked', true);
                                $('#form_status').val('published');
                                $('#status-hint-text').text('Service will be visible on the website.');
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            } else {
                                location.href = '{{ route('services.index') }}';
                            }
                        });
                    },
                    error: function (xhr) {
                        Swal.close();
                        $('.is-invalid').removeClass('is-invalid');
                        $('#server-error-list').empty();
                        $('#server-error-box').addClass('d-none');

                        let title   = 'Could not save service';
                        let listHtml = '';

                        if (xhr.status === 419) {
                            title    = 'Session Expired';
                            listHtml = '<li>Your session has expired. Please <a href="{{ route('login') }}">log in again</a>.</li>';
                        } else if (xhr.status === 403) {
                            title    = 'Permission Denied';
                            listHtml = '<li>You do not have permission to perform this action.</li>';
                        } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            title = 'Validation Errors';
                            $.each(xhr.responseJSON.errors, function (key, value) {
                                const msg = Array.isArray(value) ? value[0] : value;
                                listHtml += '<li>' + msg + '</li>';
                                const fieldName = key.replace(/\.[0-9]+/g, '');
                                $('[name="' + fieldName + '"], [name="' + fieldName + '[]"]').addClass('is-invalid');
                            });
                        } else if (xhr.status >= 500) {
                            title    = 'Server Error';
                            listHtml = '<li>' + (xhr.responseJSON?.message || 'An unexpected server error occurred. Please try again or contact support.') + '</li>';
                        } else if (xhr.status === 0) {
                            title    = 'Network Error';
                            listHtml = '<li>Could not reach the server. Please check your internet connection and try again.</li>';
                        } else {
                            listHtml = '<li>' + (xhr.responseJSON?.message || 'Something went wrong. Please try again.') + '</li>';
                        }

                        $('#server-error-list').html(listHtml);
                        $('#server-error-box').removeClass('d-none');
                        window.scrollTo({ top: 0, behavior: 'smooth' });

                        Swal.fire({
                            icon: 'error',
                            title: title,
                            html: '<ul style="text-align:left;margin:0;padding-left:1.2rem">' + listHtml + '</ul>',
                            confirmButtonColor: '#dc3545',
                            customClass: { popup: 'swal-wide' }
                        });
                    }
                });
            },
            errorPlacement: function (error, element) {
                if (element.is('select')) {
                    error.insertAfter(element.next('.select2-container') || element);
                } else {
                    error.insertAfter(element);
                }
            }
        });

    }); // document ready
    </script>
@endsection
