@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        .service-row, .group-row {
            border-radius: 8px;
            transition: background .12s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .8rem;
            margin-bottom: .35rem;
            border: 1px solid #e2e8f0;
            background: #fff;
        }
        .service-row:hover { background: #eff6ff; border-color: #bfdbfe; }
        .group-row:hover   { background: #f0fdf4; border-color: #bbf7d0; }
        .panel-label { font-size: .68rem; font-weight: 700; letter-spacing: .6px; color: #94a3b8; text-transform: uppercase; margin-bottom: .5rem; }
        .count-badge-svc  { background: #dbeafe; color: #1d4ed8; border-radius: 20px; padding: 2px 9px; font-size: .72rem; font-weight: 600; white-space: nowrap; }
        .count-badge-grp  { background: #dcfce7; color: #15803d; border-radius: 20px; padding: 2px 9px; font-size: .72rem; font-weight: 600; white-space: nowrap; }
        .count-badge-zero { background: #f1f5f9; color: #94a3b8; border-radius: 20px; padding: 2px 9px; font-size: .72rem; font-weight: 600; }
    </style>
@endsection

@section('content')

    @php
        $catData = $categories->map(function ($cat) {
            return [
                'id'       => $cat->id,
                'name'     => $cat->name,
                'slug'     => $cat->slug,
                'cat_url'  => route('view_category', $cat->slug),
                'edit_url' => route('categories.edit', $cat->id),
                'main_cat' => optional($cat->mainCategory)->name ?? '—',
                'services' => $cat->services->map(function ($s) {
                    return [
                        'id'   => $s->id,
                        'name' => $s->name,
                        'url'  => route('view_service', $s->slug),
                    ];
                })->values()->toArray(),
                'service_groups' => $cat->serviceGroups->map(function ($g) {
                    return [
                        'id'   => $g->id,
                        'name' => $g->name,
                        'url'  => route('service-packages', $g->slug),
                    ];
                })->values()->toArray(),
            ];
        })->keyBy('id')->toArray();
    @endphp

    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-folders me-2"></i>Sub-categories</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active">Sub-categories</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('public/dashboard/dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="datatables mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center">
                            <h5 class="mb-0">Sub-categories List</h5>
                            @can('create categories')
                                <a href="{{ route('categories.create') }}" class="btn btn-success ms-auto">
                                    <i class="ti ti-plus me-1"></i> Add New
                                </a>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Main Category</th>
                                        <th>Sub-category Name</th>
                                        <th style="width:120px;text-align:center">Services</th>
                                        <th style="width:130px;text-align:center">Packages</th>
                                        <th style="width:100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        @php
                                            $svcCount = $category->services->count();
                                            $grpCount = $category->serviceGroups->count();
                                        @endphp
                                        <tr data-id="{{ $category->id }}">
                                            <td>{{ optional($category->mainCategory)->name ?? '—' }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td style="text-align:center">
                                                @if ($svcCount > 0)
                                                    <span class="count-badge-svc">{{ $svcCount }}</span>
                                                @else
                                                    <span class="count-badge-zero">0</span>
                                                @endif
                                            </td>
                                            <td style="text-align:center">
                                                @if ($grpCount > 0)
                                                    <span class="count-badge-grp">{{ $grpCount }}</span>
                                                @else
                                                    <span class="count-badge-zero">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                        aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item view-cat" href="javascript:void(0);"
                                                                data-id="{{ $category->id }}">
                                                                <i class="ti ti-eye me-1"></i> View Services
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('view_category', $category->slug) }}"
                                                                target="_blank">
                                                                <i class="ti ti-external-link me-1"></i> Open Page
                                                            </a>
                                                        </li>
                                                        @can('edit categories')
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}">
                                                                    <i class="ti ti-edit me-1"></i> Edit
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('delete categories')
                                                            <li>
                                                                <a class="dropdown-item delete text-danger" href="javascript:void(0);"
                                                                    data-id="{{ $category->id }}">
                                                                    <i class="ti ti-trash me-1"></i> Delete
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
                                        <th>Main Category</th>
                                        <th>Sub-category Name</th>
                                        <th style="text-align:center">Services</th>
                                        <th style="text-align:center">Packages</th>
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

    {{-- ═══════════════════════ VIEW SERVICES MODAL ═══════════════════════ --}}
    <div class="modal fade" id="viewCatModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:780px">
            <div class="modal-content" style="border-radius:14px;overflow:hidden">

                <div class="modal-header" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:.9rem 1.25rem">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="viewCatName">—</h5>
                        <small class="text-muted" id="viewCatMeta"></small>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-auto me-3">
                        <a id="viewCatPageLink" href="#" target="_blank"
                            class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                            <i class="ti ti-external-link" style="font-size:.85rem"></i>
                            Open Page
                        </a>
                        <a id="viewCatEditLink" href="#" target="_blank"
                            class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="ti ti-edit" style="font-size:.85rem"></i>
                            Edit
                        </a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4" style="min-height:300px">

                    {{-- Services section --}}
                    <div id="svc-section">
                        <p class="panel-label">Services</p>
                        <div id="svc-list"></div>
                    </div>

                    {{-- Service Groups section --}}
                    <div id="grp-section" class="mt-3">
                        <p class="panel-label">Service Packages</p>
                        <div id="grp-list"></div>
                    </div>

                    {{-- Empty state --}}
                    <div id="cat-empty-state" class="text-center text-muted d-none" style="padding-top:60px">
                        <i class="ti ti-inbox" style="font-size:2.8rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                        <p class="fw-semibold mb-0" style="color:#94a3b8">No services or packages yet</p>
                        <small style="color:#cbd5e1">Add services to this sub-category to see them here.</small>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>

    <script>
    const catData = @json($catData);

    $(document).ready(function () {

        /* ─── DataTable ─── */
        var items_table = $("#items-table").DataTable({
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            columnDefs: [{ orderable: false, targets: [2, 3, 4] }],
        });
        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
            .addClass("btn btn-primary mr-1");

        /* ─── View services modal ─── */
        $(document).on('click', '.view-cat', function () {
            const id  = $(this).data('id');
            const cat = catData[id];
            if (!cat) return;

            $('#viewCatName').text(cat.name);
            const total = cat.services.length + cat.service_groups.length;
            $('#viewCatMeta').text(cat.main_cat + ' · ' + total + ' item' + (total !== 1 ? 's' : ''));
            $('#viewCatPageLink').attr('href', cat.cat_url);
            $('#viewCatEditLink').attr('href', cat.edit_url);

            /* Build service list */
            const $svcList = $('#svc-list').empty();
            const $grpList = $('#grp-list').empty();

            if (cat.services.length === 0 && cat.service_groups.length === 0) {
                $('#svc-section').addClass('d-none');
                $('#grp-section').addClass('d-none');
                $('#cat-empty-state').removeClass('d-none');
            } else {
                $('#cat-empty-state').addClass('d-none');

                if (cat.services.length > 0) {
                    $('#svc-section').removeClass('d-none');
                    cat.services.forEach(function (s) {
                        $svcList.append(
                            '<a href="' + s.url + '" target="_blank" class="service-row">' +
                                '<i class="ti ti-file-description text-primary flex-shrink-0"></i>' +
                                '<span class="flex-fill fw-semibold" style="font-size:.875rem">' + escHtml(s.name) + '</span>' +
                                '<i class="ti ti-external-link text-muted flex-shrink-0" style="font-size:.8rem"></i>' +
                            '</a>'
                        );
                    });
                } else {
                    $('#svc-section').addClass('d-none');
                }

                if (cat.service_groups.length > 0) {
                    $('#grp-section').removeClass('d-none');
                    cat.service_groups.forEach(function (g) {
                        $grpList.append(
                            '<a href="' + g.url + '" target="_blank" class="group-row">' +
                                '<i class="ti ti-collection text-success flex-shrink-0"></i>' +
                                '<span class="flex-fill fw-semibold" style="font-size:.875rem">' + escHtml(g.name) + '</span>' +
                                '<i class="ti ti-external-link text-muted flex-shrink-0" style="font-size:.8rem"></i>' +
                            '</a>'
                        );
                    });
                } else {
                    $('#grp-section').addClass('d-none');
                }
            }

            $('#viewCatModal').modal('show');
        });

        function escHtml(str) {
            return String(str)
                .replace(/&/g,'&amp;').replace(/</g,'&lt;')
                .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        /* ─── Delete ─── */
        $(document).on('click', '.delete', function () {
            const id  = $(this).data('id');
            const url = `{{ route('categories.destroy', '') }}/${id}`;
            const row = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover this item!",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url, method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        beforeSend: function () {
                            Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        },
                        success: function (response) {
                            Swal.close();
                            Toast.fire({ icon: 'success', title: response.message });
                            delete catData[id];
                            items_table.row(row).remove().draw();
                        },
                        error: function () {
                            Swal.close();
                            Swal.fire({ icon: 'error', title: 'Failed to delete', text: 'An error occurred. Please try again.' });
                        }
                    });
                }
            });
        });

    });
    </script>
@endsection
