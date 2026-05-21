@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        /* ── Sub-cat panel ─── */
        .sub-cat-item { cursor: pointer; border-radius: 8px; transition: background .15s; }
        .sub-cat-item:hover { background: #f1f5f9; }
        .sub-cat-item.active { background: #e0f2fe !important; color: #0369a1; }
        .sub-cat-item.active .text-muted { color: #0369a1 !important; }
        .sub-cat-item.active i { color: #0369a1 !important; }
        .service-item, .group-item { border-radius: 8px; font-size: .875rem; }
        .panel-label { font-size: .68rem; font-weight: 700; letter-spacing: .6px; color: #94a3b8; text-transform: uppercase; margin-bottom: .5rem; }
        #sub-cat-panel { border-right: 1px solid #e2e8f0; overflow-y: auto; max-height: 520px; background: #fafbfc; }
        #services-panel { overflow-y: auto; max-height: 520px; }

        /* ── Drag list ─── */
        #sortable-list { list-style: none; padding: 0; margin: 0; }
        .sort-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            margin-bottom: 8px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: grab;
            transition: box-shadow .15s, border-color .15s;
            user-select: none;
        }
        .sort-item:active { cursor: grabbing; }
        .sort-item.ui-sortable-helper {
            box-shadow: 0 8px 24px rgba(0,51,88,0.12);
            border-color: #94a3b8;
        }
        .sort-item.ui-sortable-placeholder {
            border: 2px dashed #cbd5e1;
            background: #f8fafc;
            visibility: visible !important;
            border-radius: 10px;
        }
        .drag-handle {
            color: #cbd5e1;
            font-size: 1.2rem;
            flex-shrink: 0;
            transition: color .15s;
        }
        .sort-item:hover .drag-handle { color: #64748b; }
        .sort-rank {
            width: 28px;
            height: 28px;
            background: #f1f5f9;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 800;
            color: #64748b;
            flex-shrink: 0;
        }
        .sort-name {
            flex: 1;
            font-weight: 600;
            font-size: .95rem;
            color: #0f172a;
        }
        .sort-badge {
            background: #e0f2fe;
            color: #0369a1;
            font-size: .72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
        }
        .sort-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions .btn { padding: 5px 10px; font-size: .78rem; }

        #save-order-btn {
            transition: all .2s;
        }
        #save-order-btn.changed {
            animation: pulse-btn .6s ease-in-out infinite alternate;
        }
        @keyframes pulse-btn {
            from { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
            to   { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
        }
        .order-hint {
            font-size: .8rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 6px;
        }
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

    <section class="mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Header row --}}
                        <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
                            <h5 class="mb-0">Main Categories</h5>
                            <span class="order-hint">
                                <i class="ti ti-drag-drop"></i>
                                Drag rows to reorder — click <strong>Save Order</strong> to apply to the menu
                            </span>
                            <div class="ms-auto d-flex gap-2">
                                <button id="save-order-btn" class="btn btn-primary btn-sm">
                                    <i class="ti ti-device-floppy me-1"></i> Save Order
                                </button>
                                @can('create main categories')
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                        <i class="ti ti-plus me-1"></i> Add New
                                    </button>
                                @endcan
                            </div>
                        </div>

                        {{-- Sortable list --}}
                        <ul id="sortable-list">
                            @foreach ($categories as $category)
                                @php $subCount = $category->mergedCategories->count(); @endphp
                                <li class="sort-item" data-id="{{ $category->id }}">
                                    <span class="drag-handle"><i class="ti ti-grip-vertical"></i></span>
                                    <span class="sort-rank">{{ $loop->iteration }}</span>
                                    <span class="sort-name">{{ $category->name }}</span>
                                    <span class="sort-badge">{{ $subCount }} sub-{{ $subCount === 1 ? 'category' : 'categories' }}</span>
                                    <div class="sort-actions">
                                        <button class="btn btn-light btn-sm view-main-cat" data-id="{{ $category->id }}" title="View">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        @can('edit main categories')
                                            <button class="btn btn-light btn-sm edit" data-id="{{ $category->id }}" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                        @endcan
                                        @can('delete main categories')
                                            <button class="btn btn-light-danger btn-sm delete text-danger" data-id="{{ $category->id }}" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if($categories->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="ti ti-folder-off" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                                <p>No main categories yet. Add one to get started.</p>
                            </div>
                        @endif

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
                        <div id="sub-cat-panel" style="width:300px;min-width:300px" class="p-3">
                            <p class="panel-label mb-2">Sub-categories</p>
                            <div id="sub-cat-list"></div>
                        </div>
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" action="{{ route('main_categories.store') }}" method="POST" id="add_form">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">Add New Main Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="control-label mb-1">Name <span class="text-danger">*</span></label>
                        <input type="text" id="new_name" name="name" class="form-control" placeholder="Category Name" required />
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" action="#" method="POST" id="edit_form">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">Edit Main Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="control-label mb-1">Name <span class="text-danger">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" placeholder="Category Name" required />
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
    <script src="{{ asset('public/dashboard/dist/libs/jquery-ui/dist/jquery-ui.min.js') }}"></script>

    <script>
    const mainCatData = @json($catData);
    const REORDER_URL = '{{ route('main_categories.reorder') }}';
    const CSRF = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        /* ── Drag-and-drop sort ── */
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

        /* ── Save order ── */
        $('#save-order-btn').on('click', function () {
            const order = [];
            $('#sortable-list .sort-item').each(function () {
                order.push($(this).data('id'));
            });

            Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                url: REORDER_URL,
                method: 'POST',
                data: JSON.stringify({ order: order }),
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (response) {
                    Swal.close();
                    $('#save-order-btn').removeClass('changed');
                    Toast.fire({ icon: 'success', title: 'Menu order saved!' });
                },
                error: function () {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not save order. Please try again.' });
                }
            });
        });

        /* ── View modal ── */
        $(document).on('click', '.view-main-cat', function () {
            const id = $(this).data('id');
            const mc = mainCatData[id];
            if (!mc) return;

            const n = mc.sub_categories.length;
            $('#viewMainCatName').text(mc.name);
            $('#viewMainCatSubCount').text(n + ' sub-categor' + (n === 1 ? 'y' : 'ies'));
            $('#services-placeholder').removeClass('d-none');
            $('#services-content').addClass('d-none');
            renderSubCatList(mc);
            $('#viewMainCatModal').modal('show');
        });

        function renderSubCatList(mc) {
            const $list = $('#sub-cat-list').empty();
            if (!mc.sub_categories || mc.sub_categories.length === 0) {
                $list.html('<div class="text-center text-muted py-4"><i class="ti ti-folder-off" style="font-size:2rem;display:block;margin-bottom:.4rem"></i><small>No sub-categories yet</small></div>');
                return;
            }
            mc.sub_categories.forEach(function (cat, idx) {
                const total = cat.services.length + cat.service_groups.length;
                const $item = $(
                    '<div class="sub-cat-item p-2 mb-1 d-flex align-items-center justify-content-between" data-cat-idx="' + idx + '">' +
                        '<div><div class="fw-semibold" style="font-size:.875rem">' + escHtml(cat.name) + '</div>' +
                        '<small class="text-muted">' + total + ' item' + (total !== 1 ? 's' : '') + '</small></div>' +
                        '<i class="ti ti-chevron-right text-muted"></i>' +
                    '</div>'
                );
                $item.on('click', function () {
                    $('.sub-cat-item').removeClass('active');
                    $(this).addClass('active');
                    showServices(cat);
                });
                $list.append($item);
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

            if (cat.services.length > 0) {
                $body.append('<p class="panel-label">Services</p>');
                cat.services.forEach(function (s) {
                    $body.append('<div class="service-item d-flex align-items-center gap-2 p-2 mb-1 border rounded" style="background:#fff"><i class="ti ti-file-description text-primary flex-shrink-0"></i><span>' + escHtml(s.name) + '</span></div>');
                });
            }
            if (cat.service_groups.length > 0) {
                $body.append('<p class="panel-label' + (cat.services.length > 0 ? ' mt-3' : '') + '">Service Packages</p>');
                cat.service_groups.forEach(function (g) {
                    $body.append('<div class="group-item d-flex align-items-center gap-2 p-2 mb-1 border rounded" style="background:#fff"><i class="ti ti-collection text-success flex-shrink-0"></i><span>' + escHtml(g.name) + '</span></div>');
                });
            }
            if (cat.services.length === 0 && cat.service_groups.length === 0) {
                $body.html('<div class="text-center text-muted py-4"><i class="ti ti-inbox" style="font-size:2rem;display:block;margin-bottom:.4rem"></i><small>No services under this sub-category</small></div>');
            }
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        /* ── Add form ── */
        $("#add_form").validate({
            rules: { name: { required: true } },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action, method: form.method,
                    data: new FormData(form), processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    beforeSend: function () { Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }); },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        mainCatData[response.data.id] = { id: response.data.id, name: response.data.name, sub_categories: [] };

                        const $list = $('#sortable-list');
                        const rank  = $list.children().length + 1;
                        const $li = $(`
                            <li class="sort-item" data-id="${response.data.id}">
                                <span class="drag-handle"><i class="ti ti-grip-vertical"></i></span>
                                <span class="sort-rank">${rank}</span>
                                <span class="sort-name">${escHtml(response.data.name)}</span>
                                <span class="sort-badge">0 sub-categories</span>
                                <div class="sort-actions">
                                    <button class="btn btn-light btn-sm view-main-cat" data-id="${response.data.id}" title="View"><i class="ti ti-eye"></i></button>
                                    <button class="btn btn-light btn-sm edit" data-id="${response.data.id}" title="Edit"><i class="ti ti-edit"></i></button>
                                    <button class="btn btn-light-danger btn-sm delete text-danger" data-id="${response.data.id}" title="Delete"><i class="ti ti-trash"></i></button>
                                </div>
                            </li>`);
                        $list.append($li);
                        $('#save-order-btn').addClass('changed');
                        $('#addNewModal').modal('hide');
                        $('#add_form')[0].reset();
                    },
                    error: function (xhr) {
                        Swal.close();
                        let msg = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (k, v) { msg += `<p class='text-danger'>${v}</p>`; });
                        } else { msg = 'Something went wrong.'; }
                        Swal.fire({ icon: 'error', title: 'Failed to add', html: msg });
                    }
                });
            }
        });

        /* ── Edit button ── */
        $(document).on('click', '.edit', function () {
            const id = $(this).data('id');
            $('#edit_form').attr('action', `{{ route('main_categories.update', '') }}/${id}`);
            $.ajax({
                url: '{{ route('main_categories.get') }}', method: 'POST',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (response) {
                    $('#edit_name').val(response.data.name);
                    $('#editModal').modal('show');
                },
                error: function () { Swal.fire({ icon: 'error', title: 'Error', text: 'Could not load category data.' }); }
            });
        });

        /* ── Edit form ── */
        $("#edit_form").validate({
            rules: { name: { required: true } },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action, method: form.method,
                    data: new FormData(form), processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    beforeSend: function () { Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }); },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        if (mainCatData[response.data.id]) mainCatData[response.data.id].name = response.data.name;
                        $(`#sortable-list .sort-item[data-id='${response.data.id}'] .sort-name`).text(response.data.name);
                        $('#editModal').modal('hide');
                    },
                    error: function (xhr) {
                        Swal.close();
                        let msg = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (k, v) { msg += `<p class='text-danger'>${v}</p>`; });
                        } else { msg = 'Something went wrong.'; }
                        Swal.fire({ icon: 'error', title: 'Failed to update', html: msg });
                    }
                });
            }
        });

        /* ── Delete button ── */
        $(document).on('click', '.delete', function () {
            const id  = $(this).data('id');
            const url = `{{ route('main_categories.destroy', '') }}/${id}`;
            const $li = $(this).closest('.sort-item');
            Swal.fire({
                title: 'Are you sure?', text: "You won't be able to recover this!",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: url, method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    beforeSend: function () { Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }); },
                    success: function (response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        delete mainCatData[id];
                        $li.remove();
                        updateRanks();
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Failed to delete', text: 'An error occurred.' });
                    }
                });
            });
        });

    });
    </script>
@endsection
