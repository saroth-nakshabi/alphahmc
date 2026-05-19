@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        .status-badge   { font-size: .75rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; letter-spacing: .3px; }
        .badge-published { background: #e6f9f0; color: #1a8a4a; border: 1px solid #a3e6c3; }
        .badge-draft     { background: #fff8e1; color: #b07d00; border: 1px solid #ffe082; }
        .action-btn { border-radius: 8px; }
        #items-table td, #items-table th { vertical-align: middle; }

        /* Category tags */
        .cat-tag {
            display: inline-block;
            font-size: .72rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            margin: 2px 2px 2px 0;
            white-space: nowrap;
        }
        .cat-tag-sub  { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .cat-tag-main { background: #f3f0ff; color: #6d28d9; border: 1px solid #ddd6fe; }
        .cat-tag-none { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; }
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
                        <a href="{{ route('services.create') }}" class="btn btn-success action-btn">
                            <i class="ti ti-plus me-1"></i> Add New Service
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
                            <h5 class="mb-0 fw-semibold">Services List</h5>
                            <span class="badge bg-light-primary text-primary ms-2">{{ count($services) }} total</span>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <div class="input-group" style="width:280px">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="ti ti-search text-muted" style="font-size:.95rem"></i>
                                    </span>
                                    <input type="text" id="service-search" class="form-control border-start-0 ps-0"
                                        placeholder="Search by name, category…" style="font-size:.875rem">
                                    <button class="btn btn-outline-secondary border-start-0" id="search-clear"
                                        title="Clear" style="display:none">
                                        <i class="ti ti-x" style="font-size:.85rem"></i>
                                    </button>
                                </div>
                                <select id="status-filter" class="form-select" style="width:130px;font-size:.875rem">
                                    <option value="">All Status</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table table-hover border table-bordered display">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Main Category</th>
                                        <th>Sub-category</th>
                                        <th style="width:130px">Status</th>
                                        <th style="width:100px;text-align:center">Featured</th>
                                        <th style="width:120px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        @php
                                            $cats     = $service->categories;
                                            $mainCats = $cats->map(fn($c) => optional($c->mainCategory)->name)
                                                             ->filter()->unique()->values();
                                            $subCats  = $cats->pluck('name')->unique()->values();
                                        @endphp
                                        <tr data-id="{{ $service->id }}">
                                            <td class="fw-semibold" style="color:#2d3a4a;min-width:200px">
                                                {{ $service->name }}
                                            </td>

                                            {{-- Main Category column --}}
                                            <td style="min-width:150px">
                                                @forelse ($mainCats as $mc)
                                                    <span class="cat-tag cat-tag-main">{{ $mc }}</span>
                                                @empty
                                                    <span class="cat-tag cat-tag-none">—</span>
                                                @endforelse
                                            </td>

                                            {{-- Sub-category column --}}
                                            <td style="min-width:160px">
                                                @forelse ($subCats as $sc)
                                                    <span class="cat-tag cat-tag-sub">{{ $sc }}</span>
                                                @empty
                                                    <span class="cat-tag cat-tag-none">—</span>
                                                @endforelse
                                            </td>

                                            <td>
                                                <span class="status-badge {{ $service->status === 'published' ? 'badge-published' : 'badge-draft' }} status-label"
                                                      data-id="{{ $service->id }}">
                                                    @if ($service->status === 'published')
                                                        <i class="ti ti-circle-check me-1"></i>Published
                                                    @else
                                                        <i class="ti ti-pencil me-1"></i>Draft
                                                    @endif
                                                </span>
                                            </td>

                                            <td style="text-align:center">
                                                <div class="form-check form-switch d-inline-block mb-0">
                                                    <input class="form-check-input featured-input" type="checkbox" role="switch"
                                                        data-id="{{ $service->id }}" value="1"
                                                        {{ $service->featured ? 'checked' : '' }}>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="btn-group">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm action-btn"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                        aria-expanded="false">
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
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Main Category</th>
                                        <th>Sub-category</th>
                                        <th>Status</th>
                                        <th style="text-align:center">Featured</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
    <script>
    $(document).ready(function () {

        var items_table = $("#items-table").DataTable({
            dom: "lrtip",
            order: [[0, 'asc']],
            pageLength: 25,
            columnDefs: [
                { orderable: false, targets: [4, 5] },
                { searchable: false, targets: [4, 5] },
            ],
        });

        /* ─── Custom search ─── */
        $('#service-search').on('input', function () {
            items_table.search(this.value).draw();
            $('#search-clear').toggle(this.value.length > 0);
        });

        $('#search-clear').on('click', function () {
            $('#service-search').val('');
            items_table.search('').draw();
            $(this).hide();
        });

        /* ─── Status filter ─── */
        $.fn.dataTable.ext.search.push(function (settings, data) {
            if (settings.nTable.id !== 'items-table') return true;
            var val = $('#status-filter').val();
            if (!val) return true;
            return data[3].toLowerCase().indexOf(val) !== -1;
        });

        $('#status-filter').on('change', function () {
            items_table.draw();
        });

        /* ─── Toggle publish/draft ─── */
        $(document).on('click', '.toggle-status', function () {
            const id            = $(this).data('id');
            const currentStatus = $(this).data('status');
            const $btn          = $(this);
            const $row          = $btn.closest('tr');
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
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        Toast.fire({ icon: 'success', title: response.message });
                        const $badge = $row.find('.status-label');
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
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) { Toast.fire({ icon: 'success', title: response.message }); },
                error:   function () { Swal.fire({ icon: 'error', title: 'Failed to update featured status.' }); }
            });
        });

        /* ─── Delete ─── */
        $(document).on('click', '.delete', function () {
            const id  = $(this).data('id');
            const url = '{{ route('services.destroy', '') }}/' + id;
            const row = $(this).closest('tr');
            Swal.fire({
                title: 'Are you sure?', text: "You won't be able to recover this!",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: url, method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function () {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        items_table.row(row).remove().draw();
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Failed to delete', text: 'An error occurred. Please try again.' });
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

        $("#document_upload_form").validate({
            submitHandler: function (form) {
                let formData = new FormData(form);
                $.ajax({
                    url: form.action, method: form.method,
                    data: formData, processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function () {
                        Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        $('#uploadDocumentsModal').modal('hide');
                        $('#document_upload_form')[0].reset();
                    },
                    error: function (xhr) {
                        Swal.close();
                        let msg = 'Something went wrong.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(function(v){ return v[0]; }).join('<br>');
                        }
                        Swal.fire({ icon: 'error', title: 'Upload Failed', html: msg });
                    }
                });
            }
        });

    });
    </script>
@endsection
