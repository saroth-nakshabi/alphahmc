@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
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
        .sidebar-sticky { position: sticky; top: 76px; }
        .cst-accordion .accordion-item { border: 1px solid #dee2e6; border-radius: 8px !important; margin-bottom: .6rem; overflow: hidden; }
        .cst-accordion .accordion-button { font-weight: 600; font-size: .875rem; background: #fff; color: #2d3a4a; }
        .cst-accordion .accordion-button:not(.collapsed) { background: #eef4ff; color: #1a56db; box-shadow: none; }
        .cst-accordion .accordion-body { background: #fdfdff; padding: 1rem 1.25rem; }
        .empty-state { text-align: center; padding: 2rem 1rem; color: #9aa5b4; }
        .empty-state i { font-size: 2.2rem; margin-bottom: .5rem; display: block; }
        .image-preview-thumb { width: 90px; height: 75px; object-fit: cover; border-radius: 6px; border: 2px solid #dee2e6; }
        .carousel-img-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .5rem; }
        .sidebar-card { border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 1rem; }
        .sidebar-card .sidebar-card-header { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: .7rem 1rem; font-weight: 600; font-size: .85rem; display: flex; align-items: center; gap: .4rem; }
        .sidebar-card .sidebar-card-body { padding: 1rem; }
        .status-banner { border-radius: 10px; padding: .9rem 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: .75rem; }
        .status-banner.published { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .status-banner.draft { background: #fef9c3; border: 1px solid #fde68a; color: #92400e; }
        .field-hint { font-size: .78rem; color: #6b7280; margin-top: .25rem; }
        .required-star { color: #ef4444; }
        label.form-label, .control-label { font-weight: 500; font-size: .85rem; margin-bottom: .3rem; display: block; }
        .item-count-badge { background: #e0f2fe; color: #0369a1; border-radius: 20px; padding: 2px 10px; font-size: .73rem; font-weight: 600; }
        .existing-img-badge { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 4px 10px; font-size: .78rem; color: #166534; display: inline-flex; align-items: center; gap: .4rem; }

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
                    <h4 class="fw-semibold mb-1"><i class="ti ti-edit me-2"></i>Edit Service</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($service->name, 40) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="ti ti-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

        {{-- ═══════════════════════════ LEFT COLUMN ═══════════════════════════ --}}
        <div class="col-md-9 pe-3">

            {{-- ── FORM 1: main fields + magazine ── --}}
            <form action="{{ route('services.update', $service->id) }}" method="POST"
                  id="edit_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" id="edit_status" value="{{ $service->status }}">

                {{-- ── Section 1 · Basic Info ── --}}
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
                                    value="{{ $service->name }}" placeholder="Service name" required />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">URL Slug <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:.8rem">/services/</span>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ $service->slug }}" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label">Categories <span class="required-star">*</span></label>
                                <select name="categories[]" class="form-control select2"
                                    data-placeholder="Select categories" required multiple>
                                    <option></option>
                                    @if (isset($categories) && count($categories) > 0)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, $service->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
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
                                            <option value="{{ $agent->id }}"
                                                {{ $service->agent && $service->agent->id == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->user->first_name . ' ' . $agent->user->last_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">{{-- spacer --}}</div>
                            <div class="col-md-6">
                                <label class="control-label">Inquiry Officer Name</label>
                                <input type="text" name="inq_officer_name" class="form-control"
                                    value="{{ $service->inq_officer_name }}" placeholder="Enter officer name" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Inquiry Officer WhatsApp</label>
                                <input type="text" name="inq_officer_phone" class="form-control"
                                    value="{{ $service->inq_officer_phone }}" placeholder="971XXXXXXXXX" />
                                <div class="field-hint">Country code without + or 00</div>
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
                                @if($service->hero_image)
                                    <div class="mb-2 d-flex align-items-center gap-2">
                                        <img src="{{ asset('public/uploads/service_images/' . $service->hero_image) }}"
                                            alt="Hero" class="image-preview-thumb">
                                        <span class="existing-img-badge"><i class="ti ti-check"></i> Current image</span>
                                    </div>
                                @endif
                                <input type="file" name="hero_image" class="form-control" accept="image/*" />
                                <div class="field-hint">Leave blank to keep current. Upload to replace.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Strategy Carousel Images</label>
                                <div id="service-images-container">
                                    <div class="carousel-img-row">
                                        <input type="file" name="images[]" class="form-control" accept="image/*" />
                                        <button type="button" class="btn btn-outline-success btn-sm add-image-btn flex-shrink-0">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="field-hint">Upload additional slider images (optional).</div>
                            </div>

                            {{-- Existing gallery --}}
                            @if($service->images->count() > 0)
                                <div class="col-12">
                                    <label class="control-label">Existing Gallery</label>
                                    <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded border">
                                        @foreach($service->images as $img)
                                            <div class="text-center">
                                                <img src="{{ asset('public/uploads/service_images/' . $img->image) }}"
                                                    alt="Gallery" class="image-preview-thumb d-block mb-1">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"
                                                        class="form-check-input me-1">
                                                    <label class="form-check-label small text-danger">Delete</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="field-hint">Check the box under an image to delete it on save.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── Section 3 · Page Content ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-success text-white">3</span>
                        <h6 class="mb-0 fw-semibold">Page Content</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="control-label">Hero Description <span class="required-star">*</span>
                                    <span class="text-muted fw-normal">(banner)</span></label>
                                <textarea name="content" rows="6" class="rich-textarea form-control"
                                    required>{{ $service->content }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Intro Description <span class="required-star">*</span>
                                    <span class="text-muted fw-normal">(overview)</span></label>
                                <textarea name="overview" rows="6" class="rich-textarea form-control"
                                    required>{{ $service->overview }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Core Service Header <span class="required-star">*</span></label>
                                <textarea name="info_one" rows="5" class="rich-textarea form-control"
                                    required>{{ $service->info_one }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">Core Service Description <span class="required-star">*</span></label>
                                <textarea name="info_two" rows="5" class="rich-textarea form-control"
                                    required>{{ $service->info_two }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">CTA Header <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="info_three" rows="4" class="rich-textarea form-control"
                                    >{{ $service->info_three }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="control-label">CTA Description <span class="text-muted fw-normal">(why choose us)</span></label>
                                <textarea name="info_four" rows="4" class="rich-textarea form-control"
                                    >{{ $service->info_four }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 4 · Magazine / Insights (accordion) ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-white" style="background:#7c3aed!important">4</span>
                            <h6 class="mb-0 fw-semibold">Magazine / Insights</h6>
                            <span class="item-count-badge" id="mag-count">{{ $service->magazines->count() }} {{ $service->magazines->count() == 1 ? 'item' : 'items' }}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addMagazineBtn">
                            <i class="ti ti-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="section-body p-3">
                        @if($service->magazines->count() === 0)
                            <div id="mag-empty-state" class="empty-state">
                                <i class="ti ti-news"></i>
                                <p class="mb-1 fw-semibold">No magazine items yet</p>
                                <small>Click <strong>Add Item</strong> to add magazine/insights cards.</small>
                            </div>
                        @else
                            <div id="mag-empty-state" class="empty-state d-none">
                                <i class="ti ti-news"></i>
                                <p class="mb-1 fw-semibold">No magazine items yet</p>
                                <small>Click <strong>Add Item</strong> to add magazine/insights cards.</small>
                            </div>
                        @endif

                        <div id="magazine-accordion" class="accordion cst-accordion">
                            @foreach ($service->magazines as $index => $mag)
                                <div class="accordion-item magazine-section-item" id="mag-item-e{{ $index }}">
                                    <input type="hidden" name="magazines[{{ $index }}][id]" value="{{ $mag->id }}">
                                    <input type="hidden" name="magazines[{{ $index }}][existing_image]" value="{{ $mag->image }}">
                                    <h2 class="accordion-header" id="mag-heading-e{{ $index }}">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#mag-collapse-e{{ $index }}"
                                            aria-expanded="false">
                                            <span class="badge me-2" style="background:#7c3aed;color:#fff;min-width:26px">#{{ $index + 1 }}</span>
                                            <span class="mag-item-title text-truncate" style="max-width:300px">{{ $mag->title ?: 'Magazine Item' }}</span>
                                        </button>
                                    </h2>
                                    <div id="mag-collapse-e{{ $index }}" class="accordion-collapse collapse"
                                        aria-labelledby="mag-heading-e{{ $index }}">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="control-label">Title <span class="required-star">*</span></label>
                                                    <input type="text"
                                                        name="magazines[{{ $index }}][title]"
                                                        class="form-control mag-title-input"
                                                        value="{{ $mag->title }}"
                                                        placeholder="Magazine title" required />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="control-label">Image</label>
                                                    @if($mag->image)
                                                        <div class="mb-2 d-flex align-items-center gap-2">
                                                            <img src="{{ asset('public/uploads/magazines/' . $mag->image) }}"
                                                                alt="Mag image" class="image-preview-thumb">
                                                            <span class="existing-img-badge"><i class="ti ti-check"></i> Current</span>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="magazines[{{ $index }}][image]"
                                                        class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
                                                    <div class="field-hint">Leave blank to keep current · Max 4MB</div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="control-label">Description <span class="required-star">*</span></label>
                                                    <textarea id="mag_desc_e{{ $index }}"
                                                        name="magazines[{{ $index }}][description]"
                                                        rows="4" class="form-control"
                                                        placeholder="Magazine description...">{{ $mag->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-magazine-section">
                                                    <i class="ti ti-trash me-1"></i> Remove this item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </form>{{-- /edit_form --}}

            {{-- ── FORM 2: FAQ + Meta + hidden update target ── --}}
            <form action="{{ route('services.update', $service->id) }}" method="POST"
                  id="edit_form_part2" enctype="multipart/form-data">
                @csrf

                {{-- ── Section 5 · FAQ ── --}}
                <div class="section-card">
                    <div class="section-header justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="section-badge text-dark" style="background:#fbbf24!important">5</span>
                            <h6 class="mb-0 fw-semibold">Frequently Asked Questions</h6>
                            <span class="item-count-badge" id="faq-count"
                                style="background:#fef9c3;color:#854d0e">
                                {{ $service->faq->count() }} {{ $service->faq->count() == 1 ? 'item' : 'items' }}
                            </span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-warning" id="addFaqBtn">
                            <i class="ti ti-plus me-1"></i> Add FAQ
                        </button>
                    </div>
                    <div class="section-body p-3">
                        @if($service->faq->count() === 0)
                            <div id="faq-empty-state" class="empty-state">
                                <i class="ti ti-help-circle"></i>
                                <p class="mb-1 fw-semibold">No FAQs added yet</p>
                                <small>Click <strong>Add FAQ</strong> to add questions &amp; answers.</small>
                            </div>
                        @else
                            <div id="faq-empty-state" class="empty-state d-none"></div>
                        @endif

                        <div id="faq-accordion" class="accordion cst-accordion">
                            @php $faqIndex = 0; @endphp
                            @foreach ($service->faq as $index => $faq)
                                <div class="accordion-item faq-section" id="faq-item-e{{ $index }}">
                                    <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faq->id }}">
                                    <h2 class="accordion-header" id="faq-heading-e{{ $index }}">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq-collapse-e{{ $index }}"
                                            aria-expanded="false">
                                            <span class="badge me-2 text-dark" style="background:#fbbf24;min-width:26px">Q{{ $index + 1 }}</span>
                                            <span class="faq-item-question text-truncate" style="max-width:300px">
                                                {{ Str::limit($faq->faq_question, 60) ?: 'FAQ Question' }}
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="faq-collapse-e{{ $index }}" class="accordion-collapse collapse"
                                        aria-labelledby="faq-heading-e{{ $index }}">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                <label class="control-label">Question <span class="required-star">*</span></label>
                                                <input type="text"
                                                    name="faqs[{{ $index }}][question]"
                                                    class="form-control faq-question-input"
                                                    value="{{ $faq->faq_question }}"
                                                    placeholder="FAQ question..." required />
                                            </div>
                                            <div class="mb-3">
                                                <label class="control-label">Answer <span class="required-star">*</span></label>
                                                <textarea id="faq_ans_e{{ $index }}"
                                                    name="faqs[{{ $index }}][answer]"
                                                    rows="4" class="form-control rich-textarea"
                                                    placeholder="FAQ answer...">{{ $faq->faq_answer }}</textarea>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-faq-section">
                                                    <i class="ti ti-trash me-1"></i> Remove this FAQ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $faqIndex = $index + 1; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── Section 6 · Related Content ── --}}
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-badge bg-secondary text-white">6</span>
                        <h6 class="mb-0 fw-semibold">Related Content</h6>
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="control-label">Related Services</label>
                                <select name="related_services[]" class="form-control select2"
                                    data-placeholder="Select related services" multiple>
                                    <option></option>
                                    @if (isset($services) && count($services) > 0)
                                        @foreach ($services as $service_item)
                                            <option value="{{ $service_item->id }}"
                                                {{ isset($service->related_services) && is_array($service->related_services) && in_array($service_item->id, $service->related_services) ? 'selected' : '' }}>
                                                {{ $service_item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="field-hint">Shown at the bottom of the service page.</div>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label">Announcement</label>
                                <select name="announcement_id" class="form-control select2"
                                    data-placeholder="Select announcement">
                                    <option value="">No Announcement</option>
                                    @if (isset($announcements) && count($announcements) > 0)
                                        @foreach ($announcements as $ann)
                                            <option value="{{ $ann->id }}"
                                                {{ $service->announcement_id == $ann->id ? 'selected' : '' }}>
                                                {{ $ann->title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="field-hint">Shown under "You might be interested in".</div>
                            </div>
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
                            <div class="col-md-12">
                                <label class="control-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    value="{{ $service->meta_title }}" placeholder="Page title for search engines" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-control"
                                    placeholder="Short description for search results">{{ $service->meta_description }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Meta Keywords</label>
                                <textarea name="meta_keywords" rows="3" class="form-control"
                                    placeholder="Comma-separated keywords">{{ $service->meta_keywords }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">areaServed</label>
                                <input type="text" name="areaServed" class="form-control"
                                    value="{{ $service->areaServed }}" placeholder="e.g. Dubai, UAE" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">serviceType</label>
                                <input type="text" name="serviceType" class="form-control"
                                    value="{{ $service->serviceType }}" placeholder="e.g. Healthcare Consulting" />
                            </div>
                        </div>
                    </div>
                </div>

            </form>{{-- /edit_form_part2 --}}

        </div>{{-- /col-md-9 --}}

        {{-- ═══════════════════════════ RIGHT SIDEBAR ═══════════════════════════ --}}
        <div class="col-md-3">
            <div class="sidebar-sticky">

                {{-- Status banner --}}
                <div class="status-banner {{ $service->status === 'published' ? 'published' : 'draft' }}" id="status-banner">
                    @if($service->status === 'published')
                        <i class="ti ti-circle-check fs-5"></i>
                        <div>
                            <div class="fw-semibold">Currently Published</div>
                            <small>Visible to visitors on the website</small>
                        </div>
                    @else
                        <i class="ti ti-pencil fs-5"></i>
                        <div>
                            <div class="fw-semibold">Currently a Draft</div>
                            <small>Hidden from the website</small>
                        </div>
                    @endif
                </div>

                {{-- Update actions card --}}
                <div class="sidebar-card border-primary" style="border-color:#3b82f6!important">
                    <div class="sidebar-card-header" style="background:#eff6ff;color:#1e40af">
                        <i class="ti ti-device-floppy"></i> Save Changes
                    </div>
                    <div class="sidebar-card-body">
                        <div id="save-notification" class="d-none mb-2 p-2 rounded small fw-semibold" role="alert" style="word-break:break-word"></div>
                        <label class="control-label mb-2">Save as</label>
                        <div class="d-grid gap-2">
                            @if($service->status === 'published')
                                <button type="button" class="btn btn-success" id="updateBtn" data-action="published">
                                    <i class="ti ti-device-floppy me-2"></i> Update &amp; Keep Published
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="saveDraftBtn" data-action="draft">
                                    <i class="ti ti-eye-off me-2"></i> Update &amp; Move to Draft
                                </button>
                            @else
                                <button type="button" class="btn btn-success" id="publishBtn" data-action="published">
                                    <i class="ti ti-world-upload me-2"></i> Update &amp; Publish
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="updateBtn" data-action="draft">
                                    <i class="ti ti-device-floppy me-2"></i> Update &amp; Keep Draft
                                </button>
                            @endif
                            <a href="{{ route('services.index') }}" class="btn btn-light">
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
                                form="edit_form"
                                value="{{ ($service->published_date ?? $service->created_at)->format('Y-m-d') }}" required />
                            <small class="field-hint">Date shown as "Published" on the service page.</small>
                        </div>
                        <div class="mb-1">
                            <label class="control-label mb-1" for="updated_date">Last Updated Date</label>
                            <input type="date" id="updated_date" name="updated_date"
                                class="form-control form-control-sm"
                                form="edit_form"
                                value="{{ ($service->updated_date ?? $service->updated_at)->format('Y-m-d') }}" />
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
                                <div class="field-hint mb-0">Show in featured section on homepage</div>
                            </div>
                            <div class="form-check form-switch mb-0 ms-3">
                                <input type="checkbox" name="featured" class="form-check-input"
                                    value="1" id="featured_toggle" role="switch"
                                    form="edit_form"
                                    {{ $service->featured ? 'checked' : '' }} />
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-1 mt-2 pt-2 border-top">
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">Show Testimonials</div>
                                <div class="field-hint mb-0">Display client reviews on this service page</div>
                            </div>
                            <div class="form-check form-switch mb-0 ms-3">
                                <input type="checkbox" name="show_testimonials" class="form-check-input"
                                    value="1" id="show_testimonials_toggle" role="switch"
                                    form="edit_form"
                                    {{ $service->show_testimonials ? 'checked' : '' }} />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick tips --}}
                <div class="sidebar-card" style="border-color:#bfdbfe!important">
                    <div class="sidebar-card-header" style="background:#eff6ff;color:#1e40af">
                        <i class="ti ti-bulb"></i> Tips
                    </div>
                    <div class="sidebar-card-body" style="font-size:.82rem;color:#374151">
                        <ul class="mb-0 ps-3">
                            <li class="mb-1">Click a <strong>magazine / FAQ accordion header</strong> to expand and edit it.</li>
                            <li class="mb-1">You'll be asked to <strong>confirm</strong> before removing an item.</li>
                            <li class="mb-1">Leave image fields <strong>blank</strong> to keep the existing image.</li>
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
    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       TinyMCE shared config
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    const tinyConfig = {
        plugins: 'code searchreplace autolink directionality visualblocks link media table charmap nonbreaking anchor advlist lists wordcount fullscreen',
        toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright | bullist numlist | fullscreen code',
        // Keep links exactly as entered — don't rewrite absolute internal links into broken relative ones.
        relative_urls: false,
        convert_urls: false,
        branding: false,
        promotion: false,
        automatic_uploads: true,
        images_upload_url: '/upload-image',
        images_upload_handler: function (blobInfo, success, failure) {
            var fd = new FormData();
            fd.append('file', blobInfo.blob(), blobInfo.filename());
            fetch('/upload-image', { method: 'POST', body: fd })
                .then(r => r.ok ? r.json() : Promise.reject(r))
                .then(j => j.location ? success(j.location) : failure('No location'))
                .catch(e => failure('Upload failed: ' + e));
        }
    };

    function initTinyMCE(selector, extra = {}) {
        return tinymce.init({ selector, ...tinyConfig, ...extra });
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       Counters
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    let newMagIndex = {{ count($service->magazines) }};
    let newFaqIndex = {{ count($service->faq) }};

    function updateMagCount() {
        const n = $('#magazine-accordion .accordion-item').length;
        $('#mag-count').text(n + (n === 1 ? ' item' : ' items'));
        n > 0 ? $('#mag-empty-state').addClass('d-none') : $('#mag-empty-state').removeClass('d-none');
    }
    function updateFaqCount() {
        const n = $('#faq-accordion .accordion-item').length;
        $('#faq-count').text(n + (n === 1 ? ' item' : ' items'));
        n > 0 ? $('#faq-empty-state').addClass('d-none') : $('#faq-empty-state').removeClass('d-none');
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       Build new magazine item HTML
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function buildMagazineItem(idx) {
        const num = $('#magazine-accordion .accordion-item').length + 1;
        return `
        <div class="accordion-item magazine-section-item" id="mag-item-n${idx}">
            <input type="hidden" name="magazines[${idx}][id]" value="">
            <h2 class="accordion-header" id="mag-heading-n${idx}">
                <button class="accordion-button" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mag-collapse-n${idx}"
                    aria-expanded="true">
                    <span class="badge me-2" style="background:#7c3aed;color:#fff;min-width:26px">#${num}</span>
                    <span class="mag-item-title text-truncate" style="max-width:300px">New Magazine Item</span>
                </button>
            </h2>
            <div id="mag-collapse-n${idx}" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="control-label">Title <span class="required-star">*</span></label>
                            <input type="text" name="magazines[${idx}][title]"
                                class="form-control mag-title-input" placeholder="Magazine title" required />
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Image</label>
                            <input type="file" name="magazines[${idx}][image]"
                                class="form-control mag-img-input"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
                            <div class="field-hint">Max 4MB</div>
                            <div class="mag-img-preview d-none mt-1">
                                <img src="" alt="Preview" class="image-preview-thumb">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="control-label">Description <span class="required-star">*</span></label>
                            <textarea id="mag_desc_n${idx}" name="magazines[${idx}][description]"
                                rows="4" class="form-control" placeholder="Magazine description..." required></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-magazine-section">
                            <i class="ti ti-trash me-1"></i> Remove this item
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       Build new FAQ item HTML
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function buildFaqItem(idx) {
        const num = $('#faq-accordion .accordion-item').length + 1;
        return `
        <div class="accordion-item faq-section" id="faq-item-n${idx}">
            <h2 class="accordion-header" id="faq-heading-n${idx}">
                <button class="accordion-button" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-collapse-n${idx}"
                    aria-expanded="true">
                    <span class="badge me-2 text-dark" style="background:#fbbf24;min-width:26px">Q${num}</span>
                    <span class="faq-item-question text-truncate" style="max-width:300px">New FAQ Question</span>
                </button>
            </h2>
            <div id="faq-collapse-n${idx}" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="mb-3">
                        <label class="control-label">Question <span class="required-star">*</span></label>
                        <input type="text" name="faqs[${idx}][question]"
                            class="form-control faq-question-input"
                            placeholder="Enter question..." required />
                    </div>
                    <div class="mb-3">
                        <label class="control-label">Answer <span class="required-star">*</span></label>
                        <textarea id="faq_ans_n${idx}" name="faqs[${idx}][answer]"
                            rows="4" class="form-control" placeholder="Enter answer..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-faq-section">
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

        /* Init TinyMCE for all existing static rich-textareas */
        initTinyMCE('.rich-textarea', { height: 240 });

        /* Init TinyMCE for existing magazine textareas */
        @foreach ($service->magazines as $index => $mag)
            initTinyMCE('#mag_desc_e{{ $index }}', { height: 200 });
        @endforeach

        /* Select2 */
        $('.select2').select2({ minimumResultsForSearch: 8 });

        /* Slug auto-gen from name */
        $('#name').on('input', function () {
            const slug = $(this).val().toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#slug').val(slug);
        });

        /* Carousel image add/remove */
        $(document).on('click', '.add-image-btn', function () {
            $('#service-images-container').append(`
                <div class="carousel-img-row">
                    <input type="file" name="images[]" class="form-control" accept="image/*" />
                    <button type="button" class="btn btn-outline-danger btn-sm remove-image-btn flex-shrink-0">
                        <i class="ti ti-minus"></i>
                    </button>
                </div>`);
        });
        $(document).on('click', '.remove-image-btn', function () {
            $(this).closest('.carousel-img-row').remove();
        });

        /* Live title sync for existing magazine items */
        @foreach ($service->magazines as $index => $mag)
            $('#mag-item-e{{ $index }} .mag-title-input').on('input', function () {
                const v = $(this).val().trim();
                $('#mag-item-e{{ $index }} .mag-item-title').text(v || 'Magazine Item');
            });
        @endforeach

        /* Live question sync for existing FAQ items */
        @foreach ($service->faq as $index => $faq)
            $('#faq-item-e{{ $index }} .faq-question-input').on('input', function () {
                const v = $(this).val().trim();
                $('#faq-item-e{{ $index }} .faq-item-question').text(v || 'FAQ Question');
            });
        @endforeach

        /* ── ADD MAGAZINE ITEM ── */
        $('#addMagazineBtn').on('click', function () {
            const idx = newMagIndex++;
            $('#magazine-accordion').append(buildMagazineItem(idx));
            updateMagCount();

            $(`#mag-item-n${idx} .mag-title-input`).on('input', function () {
                $(`#mag-item-n${idx} .mag-item-title`).text($(this).val().trim() || 'New Magazine Item');
            });
            $(`#mag-item-n${idx} .mag-img-input`).on('change', function () {
                const file = this.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    $(`#mag-item-n${idx} .mag-img-preview`).find('img').attr('src', url);
                    $(`#mag-item-n${idx} .mag-img-preview`).removeClass('d-none');
                }
            });
            initTinyMCE(`#mag_desc_n${idx}`, { height: 200 });
            setTimeout(() => document.getElementById(`mag-item-n${idx}`)?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 150);
        });

        /* ── REMOVE MAGAZINE (with confirmation) ── */
        $(document).on('click', '.remove-magazine-section', function () {
            const $item  = $(this).closest('.magazine-section-item');
            const title  = $item.find('.mag-title-input').val().trim();
            const label  = title ? `"${title}"` : 'this magazine item';

            Swal.fire({
                title: 'Remove magazine item?',
                html: `You are about to remove <strong>${label}</strong>.<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Yes, remove it',
                cancelButtonText: 'Keep it',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;
                const edId = $item.find('textarea[id]').attr('id');
                if (edId && tinymce.get(edId)) tinymce.get(edId).remove();
                $item.slideUp(200, function () { $(this).remove(); updateMagCount(); });
            });
        });

        /* ── ADD FAQ ITEM ── */
        $('#addFaqBtn').on('click', function () {
            const idx = newFaqIndex++;
            $('#faq-accordion').append(buildFaqItem(idx));
            updateFaqCount();

            $(`#faq-item-n${idx} .faq-question-input`).on('input', function () {
                $(`#faq-item-n${idx} .faq-item-question`).text($(this).val().trim() || 'New FAQ Question');
            });
            initTinyMCE(`#faq_ans_n${idx}`, { height: 180, menubar: false });
            setTimeout(() => document.getElementById(`faq-item-n${idx}`)?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 150);
        });

        /* ── REMOVE FAQ (with confirmation) ── */
        $(document).on('click', '.remove-faq-section', function () {
            const $item    = $(this).closest('.faq-section');
            const question = $item.find('.faq-question-input').val().trim();
            const label    = question ? `"${question}"` : 'this FAQ';

            Swal.fire({
                title: 'Remove FAQ?',
                html: `You are about to remove <strong>${label}</strong>.<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Yes, remove it',
                cancelButtonText: 'Keep it',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;
                const edId = $item.find('textarea[id]').attr('id');
                if (edId && tinymce.get(edId)) tinymce.get(edId).remove();
                $item.slideUp(200, function () { $(this).remove(); updateFaqCount(); });
            });
        });

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           Prevent native form submission
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $('#edit_form, #edit_form_part2').on('submit', function (e) { e.preventDefault(); });

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
           Save via AJAX
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        function triggerSave(status) {
            /* Sync TinyMCE editors to their textareas */
            if (typeof tinymce !== 'undefined') {
                try { tinymce.triggerSave(); } catch(e) {}
            }

            /* Merge both forms into one FormData */
            const formData = new FormData(document.getElementById('edit_form'));
            const fd2      = new FormData(document.getElementById('edit_form_part2'));
            for (let [k, v] of fd2.entries()) {
                if (k !== '_token') formData.append(k, v);
            }
            formData.set('status', status);

            const $notif = $('#save-notification');
            $notif.addClass('d-none');

            Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });

            $.ajax({
                url: '{{ route('services.update', $service->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN':      $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With':  'XMLHttpRequest',
                    'Accept':            'application/json',
                },
                success: function (response) {
                    Swal.close();
                    $('.is-invalid').removeClass('is-invalid');
                    $('#server-error-box').addClass('d-none');

                    /* Inline notification */
                    $notif.removeClass('d-none alert-danger')
                        .addClass('alert alert-success')
                        .html('<i class="ti ti-circle-check me-1"></i> ' + (response.message || 'Saved successfully!'));
                    setTimeout(function () { $notif.addClass('d-none'); }, 5000);

                    Toast.fire({ icon: 'success', title: response.message || 'Saved successfully!' });

                    /* Update status banner */
                    const newStatus = (response.data && response.data.status) ? response.data.status : status;
                    const $banner = $('#status-banner');
                    if (newStatus === 'published') {
                        $banner.removeClass('draft').addClass('published')
                            .html('<i class="ti ti-circle-check fs-5"></i><div><div class="fw-semibold">Currently Published</div><small>Visible to visitors on the website</small></div>');
                    } else {
                        $banner.removeClass('published').addClass('draft')
                            .html('<i class="ti ti-pencil fs-5"></i><div><div class="fw-semibold">Currently a Draft</div><small>Hidden from the website</small></div>');
                    }
                },
                error: function (xhr) {
                    Swal.close();

                    let title    = 'Could not save service';
                    let errorMsg = 'Something went wrong. Please try again.';
                    let listHtml = '';

                    if (xhr.status === 419) {
                        title    = 'Session Expired';
                        errorMsg = 'Your session has expired. Please log in again.';
                        listHtml = '<li><a href="{{ route('login') }}">Click here to log in again</a>.</li>';
                    } else if (xhr.status === 403) {
                        title    = 'Permission Denied';
                        errorMsg = 'You do not have permission to perform this action.';
                        listHtml = '<li>' + errorMsg + '</li>';
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        title    = 'Validation Errors';
                        errorMsg = 'Please fix the errors below.';
                        $.each(xhr.responseJSON.errors, function (key, msgs) {
                            const msg = Array.isArray(msgs) ? msgs[0] : msgs;
                            listHtml += '<li>' + msg + '</li>';
                            $('[name="' + key + '"], [name="' + key + '[]"]').addClass('is-invalid');
                        });
                    } else if (xhr.status >= 500) {
                        title    = 'Server Error';
                        errorMsg = xhr.responseJSON?.message || 'An unexpected server error occurred. Please try again or contact support.';
                        listHtml = '<li>' + errorMsg + '</li>';
                    } else if (xhr.status === 0) {
                        title    = 'Network Error';
                        errorMsg = 'Could not reach the server. Please check your internet connection and try again.';
                        listHtml = '<li>' + errorMsg + '</li>';
                    } else if (xhr.responseJSON?.message) {
                        errorMsg = xhr.responseJSON.message;
                        listHtml = '<li>' + errorMsg + '</li>';
                    } else {
                        listHtml = '<li>' + errorMsg + '</li>';
                    }

                    /* Inline sidebar notification */
                    $notif.removeClass('d-none alert-success')
                        .addClass('alert alert-danger')
                        .html('<i class="ti ti-alert-circle me-1"></i> ' + errorMsg);

                    /* SweetAlert popup */
                    Swal.fire({
                        icon: 'error',
                        title: title,
                        html: '<ul style="text-align:left;margin:0;padding-left:1.2rem">' + listHtml + '</ul>',
                        confirmButtonColor: '#dc3545',
                        customClass: { popup: 'swal-wide' }
                    });

                    if (listHtml) {
                        $('#server-error-list').html(listHtml);
                        $('#server-error-box').removeClass('d-none');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            });
        }

        $('#updateBtn').on('click',    function () { triggerSave($(this).data('action') || 'published'); });
        $('#saveDraftBtn').on('click', function () { triggerSave('draft'); });
        $('#publishBtn').on('click',   function () { triggerSave('published'); });

    }); // ready
    </script>
@endsection
