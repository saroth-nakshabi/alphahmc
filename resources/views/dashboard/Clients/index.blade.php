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
            padding: 10px 18px;
            margin-bottom: 8px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: grab;
            transition: box-shadow .15s, border-color .15s, opacity .15s;
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
        .sort-item.is-hidden-client { opacity: .55; background: #f8fafc; }
        .drag-handle { color: #cbd5e1; font-size: 1.2rem; flex-shrink: 0; transition: color .15s; }
        .sort-item:hover .drag-handle { color: #64748b; }
        .sort-rank {
            width: 28px; height: 28px;
            background: #f1f5f9; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 800; color: #64748b; flex-shrink: 0;
        }
        .client-logo {
            width: 52px; height: 44px; object-fit: contain;
            border-radius: 6px; background: #fff; border: 1px solid #f1f5f9; flex-shrink: 0;
        }
        .sort-name { flex: 1; min-width: 0; }
        .sort-name .client-title {
            font-weight: 600; font-size: .95rem; color: #0f172a;
            display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sort-name .client-sub { font-size: .75rem; color: #94a3b8; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .toggle-cell { display: flex; align-items: center; gap: 7px; flex-shrink: 0; min-width: 104px; }
        .toggle-cell .form-check { margin: 0; min-height: auto; }
        .toggle-cell .form-check-input { cursor: pointer; }
        .toggle-label { font-size: .7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .4px; }
        .sort-item.is-featured-client { border-left: 3px solid #f59e0b; }
        .sort-item.is-featured-client .toggle-label.lbl-featured { color: #b45309; }
        .sort-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions .btn { padding: 5px 10px; font-size: .78rem; }

        #save-order-btn { transition: all .2s; }
        #save-order-btn.changed { animation: pulse-btn .6s ease-in-out infinite alternate; }
        @keyframes pulse-btn {
            from { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
            to   { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
        }
        .order-hint { font-size: .8rem; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
    </style>
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-1"><i class="ti ti-building-skyscraper me-2"></i>Clients</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Clients</li>
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

<section class="mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Header row --}}
                    <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
                        <h5 class="mb-0">Clients List</h5>
                        <span class="order-hint">
                            <i class="ti ti-drag-drop"></i>
                            Drag rows to reorder — click <strong>Save Order</strong> to apply everywhere clients are listed
                        </span>
                        <div class="ms-auto d-flex gap-2">
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
                        @foreach($clients as $item)
                            <li class="sort-item {{ $item->is_featured ? 'is-featured-client' : '' }} {{ $item->status ? '' : 'is-hidden-client' }}" data-id="{{ $item->id }}">
                                <span class="drag-handle"><i class="ti ti-grip-vertical"></i></span>
                                <span class="sort-rank">{{ $loop->iteration }}</span>
                                <img src="{{ asset('public/uploads/clients/' . $item->logo) }}" alt="{{ $item->name }}" class="client-logo">
                                <span class="sort-name">
                                    <span class="client-title">{{ $item->name }}</span>
                                    <span class="client-sub">{{ Str::limit($item->short_description, 80) }}</span>
                                </span>
                                <span class="toggle-cell" title="Show in the home page clients carousel">
                                    <span class="toggle-label lbl-featured">Featured</span>
                                    <span class="form-check form-switch">
                                        <input type="checkbox" role="switch" class="form-check-input featured-toggle"
                                            data-id="{{ $item->id }}" {{ $item->is_featured ? 'checked' : '' }} />
                                    </span>
                                </span>
                                <span class="toggle-cell" title="Hide to remove this client from everywhere on the website">
                                    <span class="toggle-label">Visible</span>
                                    <span class="form-check form-switch">
                                        <input type="checkbox" role="switch" class="form-check-input status-toggle"
                                            data-id="{{ $item->id }}" {{ $item->status ? 'checked' : '' }} />
                                    </span>
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
                    </ul>

                    @if($clients->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="ti ti-building-off" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                            <p>No clients yet. Add one to get started.</p>
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
        <form class="modal-content" action="{{ route('dashboard.clients.store') }}" method="POST" id="add_form"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Add Client</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="Company Name" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Company Logo <span class="text-danger">*</span></label>
                            <input type="file" name="logo" class="form-control" accept="image/*" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Short Description <span class="text-danger">*</span></label>
                            <textarea id="short_description" name="short_description" rows="3"
                                      class="form-control" placeholder="Short description..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Description <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" rows="5"
                                      class="rich-textarea form-control" placeholder="Full description..." required></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" id="add_is_featured" name="is_featured" value="1" class="form-check-input" role="switch" />
                            <label class="form-check-label" for="add_is_featured">Featured <small class="text-muted">(home page carousel)</small></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" id="add_status" name="status" value="1" class="form-check-input" role="switch" checked />
                            <label class="form-check-label" for="add_status">Visible on website</label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Add Client</button>
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
                <h4 class="modal-title">Edit Client</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control"
                                   placeholder="Company Name" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Company Logo <small class="text-muted">(Leave empty to keep current)</small></label>
                            <input type="file" name="logo" class="form-control" accept="image/*" />
                            <div class="mt-2" id="current_logo_preview"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Short Description <span class="text-danger">*</span></label>
                            <textarea id="edit_short_description" name="short_description" rows="3"
                                      class="form-control" placeholder="Short description..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Description <span class="text-danger">*</span></label>
                            <textarea id="edit_description" name="description" rows="5"
                                      class="rich-textarea form-control" placeholder="Full description..." required></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" id="edit_is_featured" name="is_featured" value="1" class="form-check-input" role="switch" />
                            <label class="form-check-label" for="edit_is_featured">Featured <small class="text-muted">(home page carousel)</small></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" id="edit_status" name="status" value="1" class="form-check-input" role="switch" />
                            <label class="form-check-label" for="edit_status">Visible on website</label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update Client</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('public/dashboard/dist/libs/jquery-ui/dist/jquery-ui.min.js') }}"></script>

<script>
const CSRF = $('meta[name="csrf-token"]').attr('content');
const LOGO_BASE = '{{ asset('public/uploads/clients') }}/';

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

    function updateRanks() {
        $('#sortable-list .sort-item').each(function (i) {
            $(this).find('.sort-rank').text(i + 1);
        });
    }

    // ── Save order ──
    $('#save-order-btn').on('click', function () {
        const order = [];
        $('#sortable-list .sort-item').each(function () { order.push($(this).data('id')); });

        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: '{{ route('dashboard.clients.reorder') }}',
            method: 'POST',
            data: JSON.stringify({ order: order }),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function () {
                Swal.close();
                $('#save-order-btn').removeClass('changed');
                Toast.fire({ icon: 'success', title: 'Client order saved!' });
            },
            error: function () {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not save order. Please try again.' });
            }
        });
    });

    // ── Featured toggle ──
    $(document).on('change', '.featured-toggle', function () {
        const $toggle = $(this);
        const id = $toggle.data('id');
        $toggle.prop('disabled', true);
        $.ajax({
            url: '{{ url('/dashboard/clients/toggle-featured') }}/' + id,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (response) {
                $toggle.prop('disabled', false).prop('checked', response.featured);
                $toggle.closest('.sort-item').toggleClass('is-featured-client', response.featured);
                Toast.fire({ icon: 'success', title: response.message });
            },
            error: function () {
                $toggle.prop('disabled', false).prop('checked', !$toggle.prop('checked'));
                Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not update featured status.' });
            }
        });
    });

    // ── Visibility toggle ──
    $(document).on('change', '.status-toggle', function () {
        const $toggle = $(this);
        const id = $toggle.data('id');
        $toggle.prop('disabled', true);
        $.ajax({
            url: '{{ url('/dashboard/clients/toggle-status') }}/' + id,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (response) {
                $toggle.prop('disabled', false).prop('checked', response.status);
                $toggle.closest('.sort-item').toggleClass('is-hidden-client', !response.status);
                Toast.fire({ icon: 'success', title: response.message });
            },
            error: function () {
                $toggle.prop('disabled', false).prop('checked', !$toggle.prop('checked'));
                Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not update visibility.' });
            }
        });
    });

    // ── Row builder ──
    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function buildRow(d) {
        const rank = $('#sortable-list .sort-item').length + 1;
        const featured = Number(d.is_featured) === 1;
        const visible = Number(d.status) === 1;
        return `<li class="sort-item ${featured ? 'is-featured-client' : ''} ${visible ? '' : 'is-hidden-client'}" data-id="${d.id}">
            <span class="drag-handle"><i class="ti ti-grip-vertical"></i></span>
            <span class="sort-rank">${rank}</span>
            <img src="${LOGO_BASE}${d.logo}" alt="${escHtml(d.name)}" class="client-logo">
            <span class="sort-name">
                <span class="client-title">${escHtml(d.name)}</span>
                <span class="client-sub">${escHtml((d.short_description || '').substring(0, 80))}</span>
            </span>
            <span class="toggle-cell" title="Show in the home page clients carousel">
                <span class="toggle-label lbl-featured">Featured</span>
                <span class="form-check form-switch">
                    <input type="checkbox" role="switch" class="form-check-input featured-toggle" data-id="${d.id}" ${featured ? 'checked' : ''} />
                </span>
            </span>
            <span class="toggle-cell" title="Hide to remove this client from everywhere on the website">
                <span class="toggle-label">Visible</span>
                <span class="form-check form-switch">
                    <input type="checkbox" role="switch" class="form-check-input status-toggle" data-id="${d.id}" ${visible ? 'checked' : ''} />
                </span>
            </span>
            <div class="sort-actions">
                <button class="btn btn-light btn-sm edit" data-id="${d.id}" title="Edit"><i class="ti ti-edit"></i></button>
                <button class="btn btn-light-danger btn-sm delete text-danger" data-id="${d.id}" title="Delete"><i class="ti ti-trash"></i></button>
            </div>
        </li>`;
    }

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
                    Toast.fire({ icon: 'success', title: 'Client added successfully' });
                    $('#sortable-list').append(buildRow(response.data));
                    $('#addNewModal').modal('hide');
                    $('#add_form')[0].reset();
                    $('#add_status').prop('checked', true);
                    tinymce.get('description')?.setContent('');
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
        $('#edit_form').attr('action', `{{ route('dashboard.clients.update', '') }}/${id}`);

        $.ajax({
            url: `{{ route('dashboard.clients.get') }}`,
            method: 'GET',
            data: { id },
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function(response) {
                const d = response.data;

                $('#edit_name').val(d.name);
                $('#edit_short_description').val(d.short_description);
                $('#edit_is_featured').prop('checked', Number(d.is_featured) === 1);
                $('#edit_status').prop('checked', Number(d.status) === 1);

                if (tinymce.get('edit_description')) {
                    tinymce.get('edit_description').setContent(d.description ?? '');
                } else {
                    $('#edit_description').val(d.description ?? '');
                }

                if (d.logo) {
                    $('#current_logo_preview').html(
                        `<img src="${LOGO_BASE}${d.logo}" style="height:60px;object-fit:contain;border-radius:6px;" alt="Current Logo">
                        <small class="text-muted ms-2">Current logo</small>`
                    );
                } else {
                    $('#current_logo_preview').html('');
                }

                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire('Error', 'Failed to fetch client data', 'error');
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
                    Toast.fire({ icon: 'success', title: 'Client updated successfully' });
                    const d = response.data;
                    const $row = $(`#sortable-list .sort-item[data-id='${d.id}']`);
                    $row.find('.client-title').text(d.name);
                    $row.find('.client-sub').text((d.short_description || '').substring(0, 80));
                    $row.find('.client-logo').attr('src', LOGO_BASE + d.logo);
                    $row.find('.featured-toggle').prop('checked', Number(d.is_featured) === 1);
                    $row.find('.status-toggle').prop('checked', Number(d.status) === 1);
                    $row.toggleClass('is-featured-client', Number(d.is_featured) === 1);
                    $row.toggleClass('is-hidden-client', Number(d.status) !== 1);
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
        const $row = $(this).closest('.sort-item');

        Swal.fire({
            title: 'Are you sure?',
            text: "This client will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('dashboard.clients.destroy', '') }}/${id}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    beforeSend: function() {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        $row.remove();
                        updateRanks();
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error', 'Failed to delete client', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endsection
