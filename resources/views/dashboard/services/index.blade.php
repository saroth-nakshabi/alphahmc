@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        /* ── Drag list (matches Brands / Blogs / Categories pages) ── */
        #sortable-list { list-style: none; padding: 0; margin: 0; }
        .sort-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 18px; margin-bottom: 8px;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            transition: box-shadow .15s, border-color .15s; user-select: none;
        }
        .sort-item.ui-sortable-helper { box-shadow: 0 8px 24px rgba(0,51,88,0.12); border-color: #94a3b8; }
        .sort-item.ui-sortable-placeholder {
            border: 2px dashed #cbd5e1; background: #f8fafc;
            visibility: visible !important; border-radius: 10px;
        }
        .drag-handle { color: #cbd5e1; font-size: 1.2rem; flex-shrink: 0; cursor: grab; transition: color .15s; }
        .sort-item:active .drag-handle { cursor: grabbing; }
        .sort-item:hover .drag-handle { color: #64748b; }
        .sort-rank {
            width: 28px; height: 28px; background: #f1f5f9; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 800; color: #64748b; flex-shrink: 0;
        }
        .sort-name { flex: 1 1 240px; min-width: 0; }
        .sort-name .svc-title {
            font-weight: 600; font-size: .95rem; color: #0f172a; display: block;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sort-name .svc-cats { margin-top: 2px; }

        .status-badge   { font-size: .75rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; letter-spacing: .3px; white-space: nowrap; }
        .badge-published { background: #e6f9f0; color: #1a8a4a; border: 1px solid #a3e6c3; }
        .badge-draft     { background: #fff8e1; color: #b07d00; border: 1px solid #ffe082; }

        /* Category tags */
        .cat-tag {
            display: inline-block; font-size: .72rem; font-weight: 600;
            padding: 2px 8px; border-radius: 20px; margin: 2px 2px 0 0; white-space: nowrap;
        }
        .cat-tag-sub  { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .cat-tag-main { background: #f3f0ff; color: #6d28d9; border: 1px solid #ddd6fe; }
        .cat-tag-none { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; }

        .svc-status-cell  { flex: 0 0 auto; }
        .svc-featured-cell { flex: 0 0 auto; }
        .svc-actions-cell { flex: 0 0 auto; }

        #save-order-btn { transition: all .2s; }
        #save-order-btn.changed { animation: pulse-btn .6s ease-in-out infinite alternate; }
        @keyframes pulse-btn {
            from { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
            to   { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
        }
        .order-hint { font-size: .8rem; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
        .sort-item.search-hidden { display: none; }

        /* The list card must NOT clip the action dropdown (this was the "hidden to the
           right" bug when the list lived inside a .table-responsive overflow box). */
        .services-list-card .card-body { overflow: visible; }
    </style>
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-layers-subtract me-2"></i>All Services</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Services</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-end">
                    @can('view create services')
                        <a href="{{ route('services.create') }}" class="btn btn-success">
                            <i class="ti ti-plus me-1"></i> Add New Service
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <section class="mt-2">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 services-list-card">
                    <div class="card-body">

                        {{-- Header row --}}
                        <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
                            <h5 class="mb-0 fw-semibold">Services List</h5>
                            <span class="badge bg-light-primary text-primary ms-1">{{ count($services) }} total</span>
                            <span class="order-hint ms-2">
                                <i class="ti ti-drag-drop"></i>
                                Drag rows to reorder — click <strong>Save Order</strong> to apply
                            </span>
                            <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
                                <div class="input-group" style="width:260px">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="ti ti-search text-muted" style="font-size:.95rem"></i>
                                    </span>
                                    <input type="text" id="service-search" class="form-control border-start-0 ps-0"
                                        placeholder="Search by name, category…" style="font-size:.875rem">
                                </div>
                                <select id="status-filter" class="form-select" style="width:130px;font-size:.875rem">
                                    <option value="">All Status</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                                @can('edit services')
                                    <button id="save-order-btn" class="btn btn-primary btn-sm">
                                        <i class="ti ti-device-floppy me-1"></i> Save Order
                                    </button>
                                @endcan
                            </div>
                        </div>

                        {{-- Sortable list --}}
                        <ul id="sortable-list">
                            @foreach ($services as $service)
                                @php
                                    $cats     = $service->categories;
                                    $mainCats = $cats->map(fn($c) => optional($c->mainCategory)->name)
                                                     ->filter()->unique()->values();
                                    $subCats  = $cats->pluck('name')->unique()->values();
                                @endphp
                                <li class="sort-item" data-id="{{ $service->id }}" data-status="{{ $service->status }}">
                                    <span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>
                                    <span class="sort-rank">{{ $loop->iteration }}</span>

                                    <span class="sort-name">
                                        <span class="svc-title">{{ $service->name }}</span>
                                        <span class="svc-cats">
                                            @forelse ($mainCats as $mc)
                                                <span class="cat-tag cat-tag-main">{{ $mc }}</span>
                                            @empty
                                            @endforelse
                                            @forelse ($subCats as $sc)
                                                <span class="cat-tag cat-tag-sub">{{ $sc }}</span>
                                            @empty
                                                <span class="cat-tag cat-tag-none">No category</span>
                                            @endforelse
                                        </span>
                                    </span>

                                    <span class="svc-status-cell">
                                        <span class="status-badge {{ $service->status === 'published' ? 'badge-published' : 'badge-draft' }} status-label"
                                              data-id="{{ $service->id }}">
                                            @if ($service->status === 'published')
                                                <i class="ti ti-circle-check me-1"></i>Published
                                            @else
                                                <i class="ti ti-pencil me-1"></i>Draft
                                            @endif
                                        </span>
                                    </span>

                                    <span class="svc-featured-cell" title="Featured">
                                        <div class="form-check form-switch d-inline-block mb-0">
                                            <input class="form-check-input featured-input" type="checkbox" role="switch"
                                                data-id="{{ $service->id }}" value="1"
                                                {{ $service->featured ? 'checked' : '' }}>
                                        </div>
                                    </span>

                                    <span class="svc-actions-cell">
                                        <div class="btn-group">
                                            <button class="dropdown-toggle btn btn-primary btn-sm"
                                                data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                data-bs-boundary="viewport" aria-expanded="false">
                                                <i class="ti ti-dots me-1"></i> Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('front.service', $service->slug) }}" target="_blank">
                                                        <i class="ti ti-external-link me-2 text-info"></i>Open Page
                                                    </a>
                                                </li>
                                                @can('edit services')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('services.edit', $service->id) }}">
                                                            <i class="ti ti-edit me-2 text-primary"></i>Edit
                                                        </a>
                                                    </li>
                                                @endcan
                                                <li>
                                                    <a class="dropdown-item toggle-status" href="javascript:void(0);"
                                                       data-id="{{ $service->id }}" data-status="{{ $service->status }}">
                                                        @if ($service->status === 'published')
                                                            <i class="ti ti-eye-off me-2 text-warning"></i>Move to Draft
                                                        @else
                                                            <i class="ti ti-world-upload me-2 text-success"></i>Publish
                                                        @endif
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                       data-bs-target="#uploadDocumentsModal"
                                                       href="javascript:void(0);" data-id="{{ $service->id }}">
                                                        <i class="ti ti-file-upload me-2 text-info"></i>Upload Documents
                                                    </a>
                                                </li>
                                                @can('edit services')
                                                    <li>
                                                        <a class="dropdown-item move-to-group" href="javascript:void(0);"
                                                           data-id="{{ $service->id }}" data-name="{{ $service->name }}">
                                                            <i class="ti ti-arrow-move-right me-2 text-success"></i>Move to Service Group
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete services')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item delete" href="javascript:void(0);"
                                                            data-id="{{ $service->id }}">
                                                            <i class="ti ti-trash me-2 text-danger"></i>Delete
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        <div id="search-empty-state" class="text-center py-5 text-muted d-none">
                            <i class="ti ti-zoom-cancel" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                            <p class="mb-0">No services match your search / filter.</p>
                        </div>

                        @if(count($services) === 0)
                            <div class="text-center py-5 text-muted" id="services-empty-state">
                                <i class="ti ti-layers-subtract" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                                <p>No services yet. Add one to get started.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upload Documents Modal -->
    <div class="modal fade" id="uploadDocumentsModal" tabindex="-1" aria-labelledby="uploadDocumentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="#" enctype="multipart/form-data" method="POST" id="document_upload_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadDocumentsModalLabel">
                        <i class="ti ti-file-upload me-2"></i>Upload Service Documents
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Curriculum</label>
                        <input type="file" name="service_curriculum" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Panel</label>
                        <input type="file" name="service_panel" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Yearly Service Analysis</label>
                        <input type="file" name="yearly_service_analysis" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="ti ti-upload me-1"></i>Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/jquery-ui/dist/jquery-ui.min.js') }}"></script>
    <script>
    const REORDER_URL = '{{ route('services.reorder') }}';
    const CSRF        = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        function updateRanks() {
            $('#sortable-list .sort-item').each(function (i) {
                $(this).find('.sort-rank').text(i + 1);
            });
        }

        /* ─── Drag-and-drop sort (same as Brands / Blogs) ─── */
        $("#sortable-list").sortable({
            handle: '.drag-handle',
            placeholder: 'sort-item ui-sortable-placeholder',
            tolerance: 'pointer',
            update: function () {
                updateRanks();
                $('#save-order-btn').addClass('changed');
            }
        });

        /* ─── Combined search + status filter ─── */
        function applyFilters() {
            const term   = ($('#service-search').val() || '').toLowerCase().trim();
            const status = $('#status-filter').val();
            let visible  = 0;
            $('#sortable-list .sort-item').each(function () {
                const text   = $(this).find('.sort-name').text().toLowerCase();
                const matchT = !term || text.indexOf(term) !== -1;
                const matchS = !status || $(this).data('status') === status;
                const show   = matchT && matchS;
                $(this).toggleClass('search-hidden', !show);
                if (show) visible++;
            });
            const hasItems = $('#sortable-list .sort-item').length > 0;
            $('#search-empty-state').toggleClass('d-none', visible > 0 || !hasItems);
        }
        $('#service-search').on('input', applyFilters);
        $('#status-filter').on('change', applyFilters);

        /* ─── Save order ─── */
        $('#save-order-btn').on('click', function () {
            const order = [];
            $('#sortable-list .sort-item').each(function () { order.push($(this).data('id')); });

            Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            $.ajax({
                url: REORDER_URL, method: 'POST',
                data: JSON.stringify({ order: order }),
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (response) {
                    Swal.close();
                    $('#save-order-btn').removeClass('changed');
                    Toast.fire({ icon: 'success', title: response.message || 'Order saved! This order is used on the services listing.' });
                },
                error: function () {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not save order. Please try again.' });
                }
            });
        });

        /* ─── Toggle publish/draft ─── */
        $(document).on('click', '.toggle-status', function () {
            const id            = $(this).data('id');
            const currentStatus = $(this).data('status');
            const $btn          = $(this);
            const $li           = $btn.closest('.sort-item');
            const newStatus     = currentStatus === 'published' ? 'draft' : 'published';
            const actionLabel   = newStatus === 'published' ? 'Publish' : 'Move to Draft';

            Swal.fire({
                title: actionLabel + ' this service?',
                icon: 'question', showCancelButton: true,
                confirmButtonColor: newStatus === 'published' ? '#1a8a4a' : '#b07d00',
                confirmButtonText: actionLabel, cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: '/services/' + id + '/toggle-status',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    success: function (response) {
                        Toast.fire({ icon: 'success', title: response.message });
                        const $badge = $li.find('.status-label');
                        if (response.status === 'published') {
                            $badge.removeClass('badge-draft').addClass('badge-published')
                                .html('<i class="ti ti-circle-check me-1"></i>Published');
                            $btn.data('status', 'published')
                                .html('<i class="ti ti-eye-off me-2 text-warning"></i>Move to Draft');
                        } else {
                            $badge.removeClass('badge-published').addClass('badge-draft')
                                .html('<i class="ti ti-pencil me-1"></i>Draft');
                            $btn.data('status', 'draft')
                                .html('<i class="ti ti-world-upload me-2 text-success"></i>Publish');
                        }
                        $li.attr('data-status', response.status).data('status', response.status);
                        applyFilters();
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update status. Please try again.' });
                    }
                });
            });
        });

        /* ─── Featured toggle ─── */
        $(document).on('change', '.featured-input', function () {
            const id       = $(this).data('id');
            const featured = $(this).is(':checked') ? 1 : 0;
            $.ajax({
                url: '{{ route('services.featured.change') }}',
                method: 'POST',
                data: { id: id, featured: featured },
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (response) { Toast.fire({ icon: 'success', title: response.message }); },
                error:   function () { Swal.fire({ icon: 'error', title: 'Failed to update featured status.' }); }
            });
        });

        /* ─── Delete ─── */
        $(document).on('click', '.delete', function () {
            const id  = $(this).data('id');
            const url = '{{ route('services.destroy', '') }}/' + id;
            const $li = $(this).closest('.sort-item');
            Swal.fire({
                title: 'Are you sure?', text: "You won't be able to recover this!",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: url, method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    beforeSend: function () {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        $li.remove();
                        updateRanks();
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Failed to delete', text: 'An error occurred. Please try again.' });
                    }
                });
            });
        });

        /* ─── Move service → service group ─── */
        $(document).on('click', '.move-to-group', function () {
            const id   = $(this).data('id');
            const name = $(this).data('name');
            // {id} is in the middle of this route, so use a placeholder and swap it in
            const url  = '{{ route('services.move-to-group', ['id' => '__ID__']) }}'.replace('__ID__', id);
            Swal.fire({
                title: 'Copy to Service Group?',
                html: '<b>' + $('<div>').text(name).html() + '</b> will be copied into a new Service Group. The original service is <b>kept as a Draft</b> (its slug becomes <code>…_moved</code>) so you can validate the migrated data, then delete it manually.',
                icon: 'question', showCancelButton: true,
                confirmButtonColor: '#16a34a', cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, copy it'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: url, method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    beforeSend: function () {
                        Swal.fire({ title: 'Copying...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function (response) {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied to Service Groups',
                            text: response.message,
                            showCancelButton: true,
                            confirmButtonText: 'Edit the group',
                            cancelButtonText: 'Stay here'
                        }).then(function (r) {
                            if (r.isConfirmed && response.edit_url) {
                                window.open(response.edit_url, '_blank');
                            }
                            // Reload so the service now shows as Draft with its new "_moved" slug.
                            window.location.reload();
                        });
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Failed to move', text: 'An error occurred. Please try again.' });
                    }
                });
            });
        });

        /* ─── Upload documents modal ─── */
        $('#uploadDocumentsModal').on('show.bs.modal', function (event) {
            const id  = $(event.relatedTarget).data('id');
            const url = '{{ route('services.upload_documents', '') }}/' + id;
            $('#document_upload_form').attr('action', url);
        });

        $('#document_upload_form').on('submit', function (e) {
            e.preventDefault();
            const form = this;
            $.ajax({
                url: form.action, method: 'POST',
                data: new FormData(form), processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': CSRF },
                beforeSend: function () {
                    Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                },
                success: function (response) {
                    Swal.close();
                    Toast.fire({ icon: 'success', title: (response && response.message) || 'Documents uploaded.' });
                    $('#uploadDocumentsModal').modal('hide');
                    form.reset();
                },
                error: function (xhr) {
                    Swal.close();
                    let msg = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(function (v) { return v[0]; }).join('<br>');
                    }
                    Swal.fire({ icon: 'error', title: 'Upload Failed', html: msg });
                }
            });
        });

    });
    </script>
@endsection
