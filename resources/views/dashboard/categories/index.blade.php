@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        /* ── View-modal rows ─── */
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

        /* ── Drag list (matches main categories page) ─── */
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
            min-width: 0;
        }
        .sort-name .cat-title {
            font-weight: 600;
            font-size: .95rem;
            color: #0f172a;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sort-name .cat-main {
            font-size: .75rem;
            color: #94a3b8;
        }
        .sort-counts { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions .btn { padding: 5px 10px; font-size: .78rem; }

        /* ── Featured switch ─── */
        .featured-cell {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
            min-width: 112px;
        }
        .featured-cell .form-check { margin: 0; min-height: auto; }
        .featured-cell .form-check-input { cursor: pointer; }
        .featured-label {
            font-size: .72rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .sort-item.is-featured .featured-label { color: #b45309; }
        .sort-item.is-featured { border-left: 3px solid #f59e0b; }

        #save-order-btn { transition: all .2s; }
        #save-order-btn.changed { animation: pulse-btn .6s ease-in-out infinite alternate; }
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
        .featured-counter {
            font-size: .78rem;
            font-weight: 700;
            background: #fef3c7;
            color: #92400e;
            border-radius: 50px;
            padding: 4px 12px;
        }

        /* ── Search box ─── */
        .cat-search-wrap { position: relative; width: 240px; }
        .cat-search-wrap .ti-search {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: .9rem; pointer-events: none;
        }
        .cat-search-wrap input { padding-left: 30px; border-radius: 8px; }
        .sort-item.search-hidden { display: none; }
    </style>
@endsection

@section('content')

    @php
        $catData = $categories->map(function ($cat) {
            return [
                'id'       => $cat->id,
                'name'     => $cat->name,
                'slug'     => $cat->slug,
                'cat_url'  => route('front.service-category', $cat->slug),
                'edit_url' => route('categories.edit', $cat->id),
                'main_cat' => optional($cat->mainCategory)->name ?? '—',
                'services' => $cat->services->map(function ($s) {
                    return [
                        'id'   => $s->id,
                        'name' => $s->name,
                        'url'  => route('front.service', $s->slug),
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
        $featuredCount = $categories->where('featured', true)->count();
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

    <section class="mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Header row --}}
                        <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
                            <h5 class="mb-0">Sub-categories List</h5>
                            <span class="featured-counter" id="featured-counter">
                                <i class="ti ti-star-filled me-1"></i><span id="featured-count">{{ $featuredCount }}</span> featured on home page
                            </span>
                            @can('edit categories')
                                <span class="order-hint">
                                    <i class="ti ti-drag-drop"></i>
                                    Drag rows to reorder — click <strong>Save Order</strong> to apply
                                </span>
                            @endcan
                            <div class="cat-search-wrap ms-auto">
                                <i class="ti ti-search"></i>
                                <input type="search" id="cat-search" class="form-control form-control-sm"
                                    placeholder="Search categories..." autocomplete="off" />
                            </div>
                            <div class="d-flex gap-2">
                                @can('edit categories')
                                    <button id="save-order-btn" class="btn btn-primary btn-sm">
                                        <i class="ti ti-device-floppy me-1"></i> Save Order
                                    </button>
                                @endcan
                                @can('create categories')
                                    <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                                        <i class="ti ti-plus me-1"></i> Add New
                                    </a>
                                @endcan
                            </div>
                        </div>

                        {{-- Sortable list --}}
                        <ul id="sortable-list">
                            @foreach ($categories as $category)
                                @php
                                    $svcCount = $category->services->count();
                                    $grpCount = $category->serviceGroups->count();
                                @endphp
                                <li class="sort-item {{ $category->featured ? 'is-featured' : '' }}" data-id="{{ $category->id }}">
                                    <span class="drag-handle"><i class="ti ti-grip-vertical"></i></span>
                                    <span class="sort-rank">{{ $loop->iteration }}</span>
                                    <span class="sort-name">
                                        <span class="cat-title">{{ $category->name }}</span>
                                        <span class="cat-main"><i class="ti ti-category"></i> {{ optional($category->mainCategory)->name ?? '—' }}</span>
                                    </span>
                                    <span class="sort-counts">
                                        <span class="{{ $svcCount > 0 ? 'count-badge-svc' : 'count-badge-zero' }}" title="Services">{{ $svcCount }} svc</span>
                                        <span class="{{ $grpCount > 0 ? 'count-badge-grp' : 'count-badge-zero' }}" title="Service packages">{{ $grpCount }} pkg</span>
                                    </span>
                                    <span class="featured-cell" title="Show in the home page 'Our Latest Thinking' section">
                                        <span class="featured-label">Featured</span>
                                        <span class="form-check form-switch">
                                            <input type="checkbox" role="switch" class="form-check-input featured-toggle"
                                                data-id="{{ $category->id }}"
                                                {{ $category->featured ? 'checked' : '' }}
                                                @cannot('edit categories') disabled @endcannot />
                                        </span>
                                    </span>
                                    <div class="sort-actions">
                                        <button class="btn btn-light btn-sm view-cat" data-id="{{ $category->id }}" title="View services">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <a class="btn btn-light btn-sm" href="{{ route('front.service-category', $category->slug) }}"
                                            target="_blank" title="Open page">
                                            <i class="ti ti-external-link"></i>
                                        </a>
                                        @can('edit categories')
                                            <a class="btn btn-light btn-sm" href="{{ route('categories.edit', $category->id) }}" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete categories')
                                            <button class="btn btn-light-danger btn-sm delete text-danger" data-id="{{ $category->id }}" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div id="search-empty-state" class="text-center py-5 text-muted d-none">
                            <i class="ti ti-zoom-cancel" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                            <p class="mb-0">No categories match "<span id="search-empty-term" class="fw-semibold"></span>"</p>
                        </div>

                        @if($categories->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="ti ti-folder-off" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                                <p>No sub-categories yet. Add one to get started.</p>
                            </div>
                        @endif

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
    <script src="{{ asset('public/dashboard/dist/libs/jquery-ui/dist/jquery-ui.min.js') }}"></script>

    <script>
    const catData = @json($catData);
    const REORDER_URL = '{{ route('categories.reorder') }}';
    const TOGGLE_FEATURED_URL = '{{ route('categories.toggle-featured', '') }}';
    const CSRF = $('meta[name="csrf-token"]').attr('content');
    const CAN_EDIT = @json(auth()->user()->can('edit categories'));

    $(document).ready(function () {

        /* ── Drag-and-drop sort ── */
        if (CAN_EDIT) {
            $("#sortable-list").sortable({
                handle: '.drag-handle',
                placeholder: 'sort-item ui-sortable-placeholder',
                tolerance: 'pointer',
                update: function () {
                    updateRanks();
                    $('#save-order-btn').addClass('changed');
                }
            });
        }

        function updateRanks() {
            $('#sortable-list .sort-item').each(function (i) {
                $(this).find('.sort-rank').text(i + 1);
            });
        }

        /* ── Search filter ── */
        $('#cat-search').on('input', function () {
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
                success: function () {
                    Swal.close();
                    $('#save-order-btn').removeClass('changed');
                    Toast.fire({ icon: 'success', title: 'Order saved! Featured categories follow this order on the home page.' });
                },
                error: function () {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not save order. Please try again.' });
                }
            });
        });

        /* ── Featured toggle ── */
        $(document).on('change', '.featured-toggle', function () {
            const $toggle = $(this);
            const id = $toggle.data('id');
            const $item = $toggle.closest('.sort-item');
            $toggle.prop('disabled', true);

            $.ajax({
                url: TOGGLE_FEATURED_URL + '/' + id,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (response) {
                    $toggle.prop('disabled', false).prop('checked', response.featured);
                    $item.toggleClass('is-featured', response.featured);
                    const $count = $('#featured-count');
                    $count.text(parseInt($count.text(), 10) + (response.featured ? 1 : -1));
                    Toast.fire({ icon: 'success', title: response.message });
                },
                error: function () {
                    /* revert the switch on failure */
                    $toggle.prop('disabled', false).prop('checked', !$toggle.prop('checked'));
                    Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not update featured status.' });
                }
            });
        });

        /* ── View services modal ── */
        $(document).on('click', '.view-cat', function () {
            const id  = $(this).data('id');
            const cat = catData[id];
            if (!cat) return;

            $('#viewCatName').text(cat.name);
            const total = cat.services.length + cat.service_groups.length;
            $('#viewCatMeta').text(cat.main_cat + ' · ' + total + ' item' + (total !== 1 ? 's' : ''));
            $('#viewCatPageLink').attr('href', cat.cat_url);
            $('#viewCatEditLink').attr('href', cat.edit_url);

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

        /* ── Delete ── */
        $(document).on('click', '.delete', function () {
            const id  = $(this).data('id');
            const url = `{{ route('categories.destroy', '') }}/${id}`;
            const $li = $(this).closest('.sort-item');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover this item!",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel'
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
                        if ($li.find('.featured-toggle').prop('checked')) {
                            const $count = $('#featured-count');
                            $count.text(parseInt($count.text(), 10) - 1);
                        }
                        delete catData[id];
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

    });
    </script>
@endsection
