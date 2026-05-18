@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        .sub-cat-item { cursor: pointer; border-radius: 8px; transition: background .15s; }
        .sub-cat-item:hover { background: #f1f5f9; }
        .sub-cat-item.active { background: #e0f2fe !important; color: #0369a1; }
        .sub-cat-item.active .text-muted { color: #0369a1 !important; }
        .sub-cat-item.active i { color: #0369a1 !important; }
        .service-item, .group-item { border-radius: 8px; font-size: .875rem; }
        .panel-label { font-size: .68rem; font-weight: 700; letter-spacing: .6px; color: #94a3b8; text-transform: uppercase; margin-bottom: .5rem; }
        #sub-cat-panel { border-right: 1px solid #e2e8f0; overflow-y: auto; max-height: 520px; background: #fafbfc; }
        #services-panel { overflow-y: auto; max-height: 520px; }
    </style>
@endsection

@section('content')

    @php
        $catData = $categories->map(function ($mc) {
            return [
                'id'   => $mc->id,
                'name' => $mc->name,
                'sub_categories' => $mc->mergedCategories->map(function ($cat) {
                    return [
                        'id'   => $cat->id,
                        'name' => $cat->name,
                        'services' => $cat->services->map(function ($s) {
                            return ['id' => $s->id, 'name' => $s->name];
                        })->values()->toArray(),
                        'service_groups' => $cat->serviceGroups->map(function ($g) {
                            return ['id' => $g->id, 'name' => $g->name];
                        })->values()->toArray(),
                    ];
                })->values()->toArray(),
            ];
        })->keyBy('id')->toArray();
    @endphp

    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-category me-2"></i>Main Categories</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active">Main Categories</li>
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
                            <h5 class="mb-0">Main Categories</h5>
                            @can('create main categories')
                                <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                    <i class="ti ti-plus me-1"></i> Add New
                                </button>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th style="width:140px;text-align:center">Sub-categories</th>
                                        <th style="width:100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr data-id="{{ $category->id }}">
                                            <td>{{ $category->name }}</td>
                                            <td style="text-align:center">
                                                @php $subCount = $category->mergedCategories->count(); @endphp
                                                <span class="badge rounded-pill bg-primary" style="font-size:.8rem;padding:4px 12px">
                                                    {{ $subCount }}
                                                </span>
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
                                                            <a class="dropdown-item view-main-cat" href="javascript:void(0);"
                                                                data-id="{{ $category->id }}">
                                                                <i class="ti ti-eye me-1"></i> View
                                                            </a>
                                                        </li>
                                                        @can('edit main categories')
                                                            <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                    data-id="{{ $category->id }}">
                                                                <i class="ti ti-edit me-1"></i> Edit
                                                            </a></li>
                                                        @endcan
                                                        @can('delete main categories')
                                                            <li><a class="dropdown-item delete text-danger" href="javascript:void(0);"
                                                                    data-id="{{ $category->id }}">
                                                                <i class="ti ti-trash me-1"></i> Delete
                                                            </a></li>
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
                                        <th style="text-align:center">Sub-categories</th>
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

    {{-- ═══════════════════════ VIEW MODAL ═══════════════════════ --}}
    <div class="modal fade" id="viewMainCatModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:960px">
            <div class="modal-content" style="border-radius:14px;overflow:hidden">
                <div class="modal-header" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:.9rem 1.25rem">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="viewMainCatName">-</h5>
                        <small class="text-muted" id="viewMainCatSubCount"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="d-flex" style="min-height:460px">

                        {{-- Left panel: sub-categories --}}
                        <div id="sub-cat-panel" style="width:300px;min-width:300px" class="p-3">
                            <p class="panel-label mb-2">Sub-categories</p>
                            <div id="sub-cat-list"></div>
                        </div>

                        {{-- Right panel: services --}}
                        <div id="services-panel" class="p-4" style="flex:1">
                            <div id="services-placeholder" class="text-center text-muted" style="padding-top:80px">
                                <i class="ti ti-hand-click" style="font-size:2.8rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                                <p class="mb-0 fw-semibold" style="color:#94a3b8">Click a sub-category on the left</p>
                                <small style="color:#cbd5e1">to view its services and packages</small>
                            </div>
                            <div id="services-content" class="d-none">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <h6 class="fw-bold mb-0" id="selected-cat-name"></h6>
                                    <span class="badge bg-light text-dark border" id="selected-cat-total"></span>
                                </div>
                                <div id="services-body"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ ADD MODAL ═══════════════════════ --}}
    <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="{{ route('main_categories.store') }}" method="POST" id="add_form">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">Add New Main Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="control-label mb-1">Name <span class="text-danger">*</span></label>
                        <input type="text" id="new_name" name="name" class="form-control"
                            placeholder="Category Name" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════ EDIT MODAL ═══════════════════════ --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="#" method="POST" id="edit_form">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">Edit Main Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="control-label mb-1">Name <span class="text-danger">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control"
                            placeholder="Category Name" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>

    <script>
    /* ─── Embedded data from server ─── */
    const mainCatData = @json($catData);

    $(document).ready(function () {

        /* ─── DataTable ─── */
        var items_table = $("#items-table").DataTable({
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            columnDefs: [{ orderable: false, targets: [1, 2] }],
        });
        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
            .addClass("btn btn-primary mr-1");

        /* ─── View main category ─── */
        $(document).on('click', '.view-main-cat', function () {
            const id   = $(this).data('id');
            const mc   = mainCatData[id];
            if (!mc) return;

            const n = mc.sub_categories.length;
            $('#viewMainCatName').text(mc.name);
            $('#viewMainCatSubCount').text(n + ' sub-categor' + (n === 1 ? 'y' : 'ies'));

            /* Reset right panel */
            $('#services-placeholder').removeClass('d-none');
            $('#services-content').addClass('d-none');

            renderSubCatList(mc);
            $('#viewMainCatModal').modal('show');
        });

        function renderSubCatList(mc) {
            const $list = $('#sub-cat-list');
            $list.empty();

            if (!mc.sub_categories || mc.sub_categories.length === 0) {
                $list.html(
                    '<div class="text-center text-muted py-4">' +
                    '<i class="ti ti-folder-off" style="font-size:2rem;display:block;margin-bottom:.4rem"></i>' +
                    '<small>No sub-categories yet</small></div>');
                return;
            }

            mc.sub_categories.forEach(function (cat, idx) {
                const total = cat.services.length + cat.service_groups.length;
                const $item = $(
                    '<div class="sub-cat-item p-2 mb-1 d-flex align-items-center justify-content-between" data-cat-idx="' + idx + '">' +
                        '<div>' +
                            '<div class="fw-semibold" style="font-size:.875rem">' + escHtml(cat.name) + '</div>' +
                            '<small class="text-muted">' + total + ' item' + (total !== 1 ? 's' : '') + '</small>' +
                        '</div>' +
                        '<i class="ti ti-chevron-right text-muted"></i>' +
                    '</div>'
                );
                $item.on('click', function () {
                    $('.sub-cat-item').removeClass('active');
                    $(this).addClass('active');
                    showServices(cat);
                });
                $list.append($item);

                /* Auto-select first item */
                if (idx === 0) { setTimeout(function () { $item.trigger('click'); }, 60); }
            });
        }

        function showServices(cat) {
            $('#selected-cat-name').text(cat.name);
            const total = cat.services.length + cat.service_groups.length;
            $('#selected-cat-total').text(total + ' item' + (total !== 1 ? 's' : ''));
            $('#services-placeholder').addClass('d-none');
            $('#services-content').removeClass('d-none');

            const $body = $('#services-body').empty();

            /* Services */
            if (cat.services.length > 0) {
                $body.append('<p class="panel-label">Services</p>');
                cat.services.forEach(function (s) {
                    $body.append(
                        '<div class="service-item d-flex align-items-center gap-2 p-2 mb-1 border rounded" style="background:#fff">' +
                            '<i class="ti ti-file-description text-primary flex-shrink-0"></i>' +
                            '<span>' + escHtml(s.name) + '</span>' +
                        '</div>'
                    );
                });
            }

            /* Service Groups / Packages */
            if (cat.service_groups.length > 0) {
                $body.append('<p class="panel-label' + (cat.services.length > 0 ? ' mt-3' : '') + '">Service Packages</p>');
                cat.service_groups.forEach(function (g) {
                    $body.append(
                        '<div class="group-item d-flex align-items-center gap-2 p-2 mb-1 border rounded" style="background:#fff">' +
                            '<i class="ti ti-collection text-success flex-shrink-0"></i>' +
                            '<span>' + escHtml(g.name) + '</span>' +
                        '</div>'
                    );
                });
            }

            if (cat.services.length === 0 && cat.service_groups.length === 0) {
                $body.html(
                    '<div class="text-center text-muted py-4">' +
                    '<i class="ti ti-inbox" style="font-size:2rem;display:block;margin-bottom:.4rem"></i>' +
                    '<small>No services under this sub-category</small></div>');
            }
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        /* ─── Add form ─── */
        $("#add_form").validate({
            rules:    { name: { required: true } },
            messages: { name: { required: "Category name is required" } },
            submitHandler: function (form) {
                let formData = new FormData(form);
                $.ajax({
                    url: form.action, method: form.method,
                    data: formData, processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function () {
                        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });

                        /* Add to embedded data */
                        mainCatData[response.data.id] = { id: response.data.id, name: response.data.name, sub_categories: [] };

                        const newRow = `<tr data-id="${response.data.id}">
                            <td>${response.data.name}</td>
                            <td style="text-align:center"><span class="badge rounded-pill bg-primary" style="font-size:.8rem;padding:4px 12px">0</span></td>
                            <td><div class="btn-group">
                                <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"><i class="bi bi-three-dots"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item view-main-cat" href="javascript:void(0);" data-id="${response.data.id}"><i class="ti ti-eye me-1"></i> View</a></li>
                                    <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}"><i class="ti ti-edit me-1"></i> Edit</a></li>
                                    <li><a class="dropdown-item delete text-danger" href="javascript:void(0);" data-id="${response.data.id}"><i class="ti ti-trash me-1"></i> Delete</a></li>
                                </ul>
                            </div></td></tr>`;
                        items_table.row.add($(newRow)).draw();
                        $('#addNewModal').modal('hide');
                        $('#add_form')[0].reset();
                    },
                    error: function (xhr) {
                        Swal.close();
                        let msg = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (k, v) { msg += `<p class='text-danger'>${v}</p>`; });
                        } else { msg = 'Something went wrong. Please try again.'; }
                        Swal.fire({ icon: 'error', title: 'Failed to add', html: `<div>${msg}</div>`, customClass: { popup: 'swal-wide' } });
                    }
                });
            }
        });

        /* ─── Edit form ─── */
        $("#edit_form").validate({
            rules:    { name: { required: true } },
            messages: { name: { required: "Category name is required" } },
            submitHandler: function (form) {
                let formData = new FormData(form);
                $.ajax({
                    url: form.action, method: form.method,
                    data: formData, processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function () {
                        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });

                        /* Update embedded data name */
                        if (mainCatData[response.data.id]) mainCatData[response.data.id].name = response.data.name;

                        const $row = $(`#items-table tr[data-id='${response.data.id}']`);
                        const subBadge = $row.find('td:eq(1)').html();
                        $row.html(
                            `<td>${response.data.name}</td>` +
                            `<td style="text-align:center">${subBadge}</td>` +
                            `<td><div class="btn-group">
                                <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"><i class="bi bi-three-dots"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item view-main-cat" href="javascript:void(0);" data-id="${response.data.id}"><i class="ti ti-eye me-1"></i> View</a></li>
                                    <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}"><i class="ti ti-edit me-1"></i> Edit</a></li>
                                    <li><a class="dropdown-item delete text-danger" href="javascript:void(0);" data-id="${response.data.id}"><i class="ti ti-trash me-1"></i> Delete</a></li>
                                </ul>
                            </div></td>`
                        );
                        items_table.destroy();
                        items_table = $("#items-table").DataTable({
                            dom: "Bfrtip",
                            buttons: ["copy", "csv", "excel", "pdf", "print"],
                            columnDefs: [{ orderable: false, targets: [1, 2] }],
                        });
                        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                            .addClass("btn btn-primary mr-1");
                        $('#editModal').modal('hide');
                    },
                    error: function (xhr) {
                        Swal.close();
                        let msg = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (k, v) { msg += `<p class='text-danger'>${v}</p>`; });
                        } else { msg = 'Something went wrong. Please try again.'; }
                        Swal.fire({ icon: 'error', title: 'Failed to update', html: `<div>${msg}</div>`, customClass: { popup: 'swal-wide' } });
                    }
                });
            }
        });

        /* ─── Edit button ─── */
        $(document).on('click', '.edit', function () {
            const id = $(this).data('id');
            $('#edit_form').attr('action', `{{ route('main_categories.update', '') }}/${id}`);
            $.ajax({
                url: '{{ route('main_categories.get') }}', method: 'POST',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    $('#edit_name').val(response.data.name);
                    $('#editModal').modal('show');
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Could not load category data.' });
                }
            });
        });

        /* ─── Delete button ─── */
        $(document).on('click', '.delete', function () {
            const id  = $(this).data('id');
            const url = `{{ route('main_categories.destroy', '') }}/${id}`;
            const row = $(this).closest('tr');
            Swal.fire({
                title: 'Are you sure?', text: "You won't be able to recover this!",
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
                            delete mainCatData[id];
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
