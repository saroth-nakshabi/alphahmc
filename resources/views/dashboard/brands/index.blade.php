@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        /* ── Drag list (matches categories page) ─── */
        #sortable-list { list-style: none; padding: 0; margin: 0; }
        .sort-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 18px;
            margin-bottom: 8px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: grab;
            transition: box-shadow .15s, border-color .15s;
            user-select: none;
        }
        .sort-item:active { cursor: grabbing; }
        .sort-item.ui-sortable-helper { box-shadow: 0 8px 24px rgba(0,51,88,0.12); border-color: #94a3b8; }
        .sort-item.ui-sortable-placeholder {
            border: 2px dashed #cbd5e1;
            background: #f8fafc;
            visibility: visible !important;
            border-radius: 10px;
        }
        .drag-handle { color: #cbd5e1; font-size: 1.2rem; flex-shrink: 0; transition: color .15s; }
        .sort-item:hover .drag-handle { color: #64748b; }
        .sort-rank {
            width: 28px; height: 28px; background: #f1f5f9; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 800; color: #64748b; flex-shrink: 0;
        }
        .brand-logo {
            width: 46px; height: 46px; object-fit: contain; border-radius: 8px;
            background: #f8fafc; border: 1px solid #eef2f7; padding: 3px; flex-shrink: 0;
        }
        .sort-name { flex: 1; min-width: 0; }
        .sort-name .brand-title {
            font-weight: 600; font-size: .95rem; color: #0f172a; display: block;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sort-name .brand-addr { font-size: .75rem; color: #94a3b8; }
        .sort-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions .btn { padding: 5px 10px; font-size: .78rem; }

        #save-order-btn { transition: all .2s; }
        #save-order-btn.changed { animation: pulse-btn .6s ease-in-out infinite alternate; }
        @keyframes pulse-btn {
            from { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
            to   { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
        }
        .order-hint { font-size: .8rem; color: #94a3b8; display: flex; align-items: center; gap: 6px; }

        /* ── Search box ─── */
        .brand-search-wrap { position: relative; width: 240px; }
        .brand-search-wrap .ti-search {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: .9rem; pointer-events: none;
        }
        .brand-search-wrap input { padding-left: 30px; border-radius: 8px; }
        .sort-item.search-hidden { display: none; }
    </style>
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Brand Portfolio</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Our Brands</li>
                    </ol>
                </nav>
            </div>
            <div class="col-3">
                <div class="text-center mb-n5">
                    <img src="{{ asset('public/dashboard/dist/images/breadcrumb/ChatBc.png') }}" alt=""
                         class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hero Image Management --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Brands Page Hero Image</h5>
                <form action="{{ route('dashboard.brands.updateHero') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Hero Image</label>
                                <div class="hero-preview mb-2">
                                    @if(isset($brandHero) && $brandHero->image)
                                        <img src="{{ asset('public/uploads/brands/' . $brandHero->image) }}" alt="Hero" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    @else
                                        <div class="p-4 bg-light text-center rounded border">
                                            <span class="text-muted">No hero image set. Default will be used.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hero_image" class="form-label">Upload New Hero Image</label>
                                <input class="form-control" type="file" id="hero_image" name="hero_image" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Hero Image</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<section class="mt-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Header row --}}
                    <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
                        <h5 class="mb-0">Brands List</h5>
                        <span class="order-hint">
                            <i class="ti ti-drag-drop"></i>
                            Drag rows to reorder — click <strong>Save Order</strong> to apply
                        </span>
                        <div class="brand-search-wrap ms-auto">
                            <i class="ti ti-search"></i>
                            <input type="search" id="brand-search" class="form-control form-control-sm"
                                placeholder="Search brands..." autocomplete="off" />
                        </div>
                        <div class="d-flex gap-2">
                            <button id="save-order-btn" class="btn btn-primary btn-sm">
                                <i class="ti ti-device-floppy me-1"></i> Save Order
                            </button>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                <i class="ti ti-plus me-1"></i> Add New
                            </button>
                        </div>
                    </div>

                    {{-- Sortable list --}}
                    <ul id="sortable-list">
                        @if(isset($brands) && count($brands) > 0)
                            @foreach($brands as $item)
                                <li class="sort-item" data-id="{{ $item->id }}">
                                    <span class="drag-handle"><i class="ti ti-grip-vertical"></i></span>
                                    <span class="sort-rank">{{ $loop->iteration }}</span>
                                    <img class="brand-logo" src="{{ asset('public/uploads/brands/' . $item->logo) }}" alt="{{ $item->name }}">
                                    <span class="sort-name">
                                        <span class="brand-title">{{ $item->name }}</span>
                                        <span class="brand-addr"><i class="ti ti-map-pin"></i> {{ $item->address ?: '—' }}</span>
                                    </span>
                                    <div class="sort-actions">
                                        <button class="btn btn-light btn-sm edit" data-id="{{ $item->id }}" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-light-danger btn-sm delete text-danger" data-id="{{ $item->id }}" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <div id="search-empty-state" class="text-center py-5 text-muted d-none">
                        <i class="ti ti-zoom-cancel" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                        <p class="mb-0">No brands match "<span id="search-empty-term" class="fw-semibold"></span>"</p>
                    </div>

                    @if(!isset($brands) || count($brands) === 0)
                        <div class="text-center py-5 text-muted" id="brands-empty-state">
                            <i class="ti ti-building-store" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                            <p>No brands yet. Add one to get started.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <form class="modal-content" action="{{ route('dashboard.brands.store') }}" method="POST" id="add_form"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Add New Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="Brand Name" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Logo <span class="text-danger">*</span></label>
                            <input type="file" name="logo" class="form-control" accept="image/*" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Corporate Address <span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control"
                                   placeholder="e.g. Dubai, UAE" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Google Maps Location
                                <small class="text-muted">(optional — shown in the "Global Offices" section on the Contact page)</small>
                            </label>
                            <textarea id="google_location" name="google_location" rows="2" class="form-control"
                                      placeholder='Paste the Google Maps "Embed a map" code or its src link. Leave blank to hide this brand from Global Offices.'></textarea>
                            <small class="text-muted">In Google Maps → Share → <b>Embed a map</b> → Copy HTML, and paste it here.</small>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">General Description <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" rows="3"
                                      class="form-control" placeholder="Brief about the brand..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">What We Do (Expertise) <span class="text-danger">*</span></label>
                            <textarea id="what_we_do" name="what_we_do" rows="5"
                                      class="rich-textarea form-control" placeholder="Describe brand expertise..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12"><hr class="my-2"><h6 class="fw-semibold mb-0">SEO</h6></div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" maxlength="255">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Service Area</label>
                            <input type="text" name="areaServed" class="form-control" placeholder="e.g. United Arab Emirates">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Meta Keywords</label>
                            <textarea name="meta_keywords" rows="2" class="form-control" placeholder="comma, separated, keywords"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save Brand</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <form class="modal-content" action="" method="POST" id="edit_form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Edit Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control"
                                   placeholder="Brand Name" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Logo <small class="text-muted">(Leave empty to keep current)</small></label>
                            <input type="file" name="logo" class="form-control" accept="image/*" />
                            <div class="mt-2" id="current_logo_preview"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Corporate Address <span class="text-danger">*</span></label>
                            <input type="text" id="edit_address" name="address" class="form-control"
                                   placeholder="e.g. Dubai, UAE" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Google Maps Location
                                <small class="text-muted">(optional — shown in the "Global Offices" section on the Contact page)</small>
                            </label>
                            <textarea id="edit_google_location" name="google_location" rows="2" class="form-control"
                                      placeholder='Paste the Google Maps "Embed a map" code or its src link. Leave blank to hide this brand from Global Offices.'></textarea>
                            <small class="text-muted">In Google Maps → Share → <b>Embed a map</b> → Copy HTML, and paste it here.</small>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">General Description <span class="text-danger">*</span></label>
                            <textarea id="edit_description" name="description" rows="3"
                                      class="form-control" placeholder="Brief about the brand..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">What We Do (Expertise) <span class="text-danger">*</span></label>
                            <textarea id="edit_what_we_do" name="what_we_do" rows="5"
                                      class="rich-textarea form-control" placeholder="Describe brand expertise..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12"><hr class="my-2"><h6 class="fw-semibold mb-0">SEO</h6></div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Meta Title</label>
                            <input type="text" id="edit_meta_title" name="meta_title" class="form-control" maxlength="255">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Service Area</label>
                            <input type="text" id="edit_areaServed" name="areaServed" class="form-control" placeholder="e.g. United Arab Emirates">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Meta Description</label>
                            <textarea id="edit_meta_description" name="meta_description" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Meta Keywords</label>
                            <textarea id="edit_meta_keywords" name="meta_keywords" rows="2" class="form-control" placeholder="comma, separated, keywords"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update Brand</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('public/dashboard/dist/libs/jquery-ui/dist/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>

<script>
const REORDER_URL = '{{ route('dashboard.brands.reorder') }}';
const LOGO_BASE   = '{{ asset('public/uploads/brands') }}';
const CSRF = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function() {

    // ── TinyMCE ──
    tinymce.init({
        selector: '.rich-textarea',
        plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
        toolbar: "code undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/uploads/tinymce-image',
    });

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function updateRanks() {
        $('#sortable-list .sort-item').each(function (i) {
            $(this).find('.sort-rank').text(i + 1);
        });
    }

    function brandRow(d) {
        const logo = LOGO_BASE + '/' + d.logo;
        return '<li class="sort-item" data-id="' + d.id + '">' +
            '<span class="drag-handle"><i class="ti ti-grip-vertical"></i></span>' +
            '<span class="sort-rank">0</span>' +
            '<img class="brand-logo" src="' + logo + '" alt="' + escHtml(d.name) + '">' +
            '<span class="sort-name">' +
                '<span class="brand-title">' + escHtml(d.name) + '</span>' +
                '<span class="brand-addr"><i class="ti ti-map-pin"></i> ' + (escHtml(d.address) || '—') + '</span>' +
            '</span>' +
            '<div class="sort-actions">' +
                '<button class="btn btn-light btn-sm edit" data-id="' + d.id + '" title="Edit"><i class="ti ti-edit"></i></button>' +
                '<button class="btn btn-light-danger btn-sm delete text-danger" data-id="' + d.id + '" title="Delete"><i class="ti ti-trash"></i></button>' +
            '</div>' +
        '</li>';
    }

    // ── Drag-and-drop sort ──
    $("#sortable-list").sortable({
        handle: '.drag-handle',
        placeholder: 'sort-item ui-sortable-placeholder',
        tolerance: 'pointer',
        update: function () {
            updateRanks();
            $('#save-order-btn').addClass('changed');
        }
    });

    // ── Search filter ──
    $('#brand-search').on('input', function () {
        const term = $(this).val().toLowerCase().trim();
        let visible = 0;
        $('#sortable-list .sort-item').each(function () {
            const text = $(this).find('.sort-name').text().toLowerCase();
            const match = !term || text.indexOf(term) !== -1;
            $(this).toggleClass('search-hidden', !match);
            if (match) visible++;
        });
        $('#search-empty-term').text($(this).val().trim());
        $('#search-empty-state').toggleClass('d-none', visible > 0 || !term);
    });

    // ── Save order ──
    $('#save-order-btn').on('click', function () {
        const order = [];
        $('#sortable-list .sort-item').each(function () { order.push($(this).data('id')); });

        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: REORDER_URL,
            method: 'POST',
            data: JSON.stringify({ order: order }),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function () {
                Swal.close();
                $('#save-order-btn').removeClass('changed');
                Toast.fire({ icon: 'success', title: 'Order saved! This order is used on the Brands and About pages.' });
            },
            error: function () {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not save order. Please try again.' });
            }
        });
    });

    // ── ADD ──
    $("#add_form").validate({
        submitHandler: function(form) {
            tinymce.triggerSave();

            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': CSRF },
                beforeSend: function() {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                },
                success: function(response) {
                    Swal.close();
                    Toast.fire({ icon: 'success', title: 'Brand added successfully' });

                    $('#brands-empty-state').remove();
                    $('#sortable-list').append(brandRow(response.data));
                    updateRanks();

                    $('#addNewModal').modal('hide');
                    $('#add_form')[0].reset();
                    tinymce.get('what_we_do')?.setContent('');
                },
                error: function(xhr) {
                    Swal.close();
                    let errors = xhr.responseJSON?.errors || { 'error': 'Something went wrong' };
                    let html = Object.values(errors).map(e => `<p class='text-danger'>${e}</p>`).join('');
                    Swal.fire({ icon: 'error', title: 'Failed to add', html });
                }
            });
        }
    });

    // ── EDIT — open modal ──
    $(document).on('click', '.edit', function() {
        const id = $(this).data('id');
        $('#edit_form').attr('action', `{{ route('dashboard.brands.update', '') }}/${id}`);

        $.ajax({
            url: `{{ route('dashboard.brands.get') }}`,
            method: 'GET',
            data: { id },
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function(response) {
                const d = response.data;

                $('#edit_name').val(d.name);
                $('#edit_address').val(d.address);
                $('#edit_google_location').val(d.google_location || '');
                $('#edit_description').val(d.description);
                $('#edit_meta_title').val(d.meta_title || '');
                $('#edit_meta_description').val(d.meta_description || '');
                $('#edit_meta_keywords').val(d.meta_keywords || '');
                $('#edit_areaServed').val(d.areaServed || '');

                if (tinymce.get('edit_what_we_do')) {
                    tinymce.get('edit_what_we_do').setContent(d.what_we_do ?? '');
                } else {
                    $('#edit_what_we_do').val(d.what_we_do ?? '');
                }

                if (d.logo) {
                    $('#current_logo_preview').html(
                        `<img src="${LOGO_BASE}/${d.logo}" style="height:60px;object-fit:contain;border-radius:6px;" alt="Current Logo">
                        <small class="text-muted ms-2">Current logo</small>`
                    );
                } else {
                    $('#current_logo_preview').html('');
                }

                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire('Error', 'Failed to fetch brand data', 'error');
            }
        });
    });

    // ── EDIT — submit ──
    $("#edit_form").validate({
        submitHandler: function(form) {
            tinymce.triggerSave();

            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': CSRF },
                beforeSend: function() {
                    Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                },
                success: function(response) {
                    Swal.close();
                    Toast.fire({ icon: 'success', title: 'Brand updated successfully' });

                    const d = response.data;
                    const $li = $(`#sortable-list .sort-item[data-id='${d.id}']`);
                    if ($li.length) {
                        $li.find('.brand-title').text(d.name);
                        $li.find('.brand-addr').html('<i class="ti ti-map-pin"></i> ' + (escHtml(d.address) || '—'));
                        if (d.logo) $li.find('.brand-logo').attr('src', LOGO_BASE + '/' + d.logo);
                    }

                    $('#editModal').modal('hide');
                },
                error: function(xhr) {
                    Swal.close();
                    let errors = xhr.responseJSON?.errors || { 'error': 'Something went wrong' };
                    let html = Object.values(errors).map(e => `<p class='text-danger'>${e}</p>`).join('');
                    Swal.fire({ icon: 'error', title: 'Failed to update', html });
                }
            });
        }
    });

    // ── DELETE ──
    $(document).on('click', '.delete', function() {
        const id = $(this).data('id');
        const $li = $(this).closest('.sort-item');

        Swal.fire({
            title: 'Are you sure?',
            text: "This brand will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('dashboard.brands.destroy', '') }}/${id}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    beforeSend: function() {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        $li.remove();
                        updateRanks();
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error', 'Failed to delete brand', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endsection
