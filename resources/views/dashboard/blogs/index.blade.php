@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        /* Allow multi-select tags container to grow beyond the layout's fixed 36px */
        .select2-multi-tags.select2 {
            height: auto !important;
            min-height: 36px;
        }
        .select2-multi-tags .select2-selection--multiple {
            min-height: 34px;
        }
        /* Give CKEditor a decent height inside the modal */
        .ck-editor__editable_inline {
            min-height: 280px;
        }

        /* ── Listing style (matched to the Services listing) ── */
        .action-btn { border-radius: 8px; }
        #items-table td, #items-table th { vertical-align: middle; }

        /* Tag chips (used as the blog's category) */
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
        .cat-tag-none { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; }

        /* Drag-to-reorder */
        .drag-handle { cursor: grab; color: #cbd5e1; font-size: 1.05rem; }
        tr:hover .drag-handle { color: #64748b; }
        .row-dragging { background: #eff6ff !important; display: table; }
        .row-placeholder td { background: #f8fafc; height: 48px; }
    </style>
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-article me-2"></i>All Blogs</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Blog</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-end">
                    @can('create blogs')
                        <button class="btn btn-success action-btn" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            <i class="ti ti-plus me-1"></i> Add New Blog
                        </button>
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
                            <h5 class="mb-0 fw-semibold">Blogs List</h5>
                            <span class="badge bg-light-primary text-primary ms-2">{{ count($blogs) }} total</span>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <button type="button" id="save-order-btn" class="btn btn-success action-btn" style="display:none">
                                    <i class="ti ti-arrows-sort me-1"></i> Save Order
                                </button>
                                <div class="input-group" style="width:280px">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="ti ti-search text-muted" style="font-size:.95rem"></i>
                                    </span>
                                    <input type="text" id="blog-search" class="form-control border-start-0 ps-0"
                                        placeholder="Search by title, tag…" style="font-size:.875rem">
                                    <button class="btn btn-outline-secondary border-start-0" id="search-clear"
                                        title="Clear" style="display:none">
                                        <i class="ti ti-x" style="font-size:.85rem"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small mb-2">
                            <i class="ti ti-drag-drop"></i>
                            Drag rows by the <i class="ti ti-grip-vertical"></i> handle to reorder, then click <strong>Save Order</strong>. The first blog becomes the featured one on the website.
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table table-hover border table-bordered display">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:44px"></th>
                                        <th>Title</th>
                                        <th>Tags</th>
                                        <th style="width:100px;text-align:center">Featured</th>
                                        <th style="width:120px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($blogs) && count($blogs) > 0)
                                        @foreach ($blogs as $blog)
                                            <tr data-id="{{ $blog->id }}">
                                                <td class="text-center">
                                                    <span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>
                                                </td>
                                                <td class="fw-semibold" style="color:#2d3a4a;min-width:220px">
                                                    {{ $blog->title }}
                                                </td>
                                                <td style="min-width:160px">
                                                    @forelse ($blog->tags as $tag)
                                                        <span class="cat-tag cat-tag-sub">{{ $tag->name }}</span>
                                                    @empty
                                                        <span class="cat-tag cat-tag-none">—</span>
                                                    @endforelse
                                                </td>
                                                <td style="text-align:center">
                                                    <div class="form-check form-switch d-inline-block mb-0">
                                                        <input class="form-check-input featured-input" type="checkbox" role="switch"
                                                            data-id="{{ $blog->id }}" value="1"
                                                            {{ $blog->featured ? 'checked' : '' }}>
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
                                                                <a class="dropdown-item" href="{{ route('front.singleBlog', $blog->slug) }}" target="_blank">
                                                                    <i class="ti ti-external-link me-2 text-info"></i>Open Page
                                                                </a>
                                                            </li>
                                                            @can('edit blogs')
                                                                <li>
                                                                    <a class="dropdown-item edit" href="javascript:void(0);" data-id="{{ $blog->id }}">
                                                                        <i class="ti ti-edit me-2 text-primary"></i>Edit
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                            @can('delete blogs')
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $blog->id }}">
                                                                        <i class="ti ti-trash me-2 text-danger"></i>Delete
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Tags</th>
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

    <!-- add new modal -->
    <div class="modal fade" id="addNewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <form class="modal-content" action="{{ route('blogs.store') }}" method="POST" id="add_form"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">Add New Blog</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 pt-2">
                        <div class="col-md-6">
                            <label class="control-label mb-1">Blog Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="add_title" class="form-control" placeholder="Enter blog title" required />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">URL Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="add_slug" class="form-control" placeholder="auto-generated" required />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Author Name</label>
                            <input type="text" name="author_name" class="form-control" placeholder="e.g. Dr. Sarah Ahmed" />
                        </div>
                        <div class="col-md-4">
                            <label class="control-label mb-1">Tags</label>
                            <select name="tags[]" class="form-control select2-add" multiple data-placeholder="Select tags">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label mb-1">Read Time (min)</label>
                            <input type="number" name="read_time" class="form-control" placeholder="5" min="1" max="999" />
                        </div>
                        <div class="col-md-12">
                            <label class="control-label mb-1">Blog Image <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required />
                        </div>
                        <div class="col-md-12">
                            <label class="control-label mb-1">Short Description</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Brief summary shown on listing page..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label mb-1">News Focus
                                <span class="text-muted fw-normal">(comma-separated, max 3 — shown on the News &amp; Updates page)</span>
                            </label>
                            <input type="text" name="news_focus" id="add_news_focus" class="form-control"
                                placeholder="e.g. Corporate Updates, Strategic Growth, Media Releases" />
                            <small class="text-muted news-focus-hint"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-check-label mb-1">Featured Blog</label>
                            <div class="form-check">
                                <input type="checkbox" name="featured" class="form-check-input" value="1" />
                                <label class="form-check-label">Set as featured</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Published Date</label>
                            <input type="date" name="published_date" class="form-control" value="{{ date('Y-m-d') }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Updated Date</label>
                            <input type="date" name="updated_date" class="form-control" />
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-12">
                            <label class="control-label mb-1">Content <span class="text-danger">*</span></label>
                            <textarea id="editor" name="content" rows="8" class="form-control" placeholder="Write blog content..." required></textarea>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <h6 class="fw-semibold mb-3">Meta Details</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="control-label mb-1">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="Page title for search engines" />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="form-control" placeholder="150–160 chars"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Meta Keywords</label>
                            <textarea name="meta_keywords" rows="3" class="form-control" placeholder="Comma-separated keywords"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Blog</button>
                </div>
            </form>
        </div>
    </div>

    <!-- edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <form class="modal-content" action="#" method="POST" id="edit_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">Edit Blog</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 pt-2">
                        <div class="col-md-6">
                            <label class="control-label mb-1">Blog Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="Enter blog title" required />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">URL Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="edit_slug" class="form-control" required />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Author Name</label>
                            <input type="text" name="author_name" id="edit_author" class="form-control" placeholder="e.g. Dr. Sarah Ahmed" />
                        </div>
                        <div class="col-md-4">
                            <label class="control-label mb-1">Tags</label>
                            <select name="tags[]" id="edit_tags" class="form-control select2-edit" multiple data-placeholder="Select tags">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label mb-1">Read Time (min)</label>
                            <input type="number" name="read_time" id="edit_read_time" class="form-control" placeholder="5" min="1" max="999" />
                        </div>
                        <div class="col-md-12">
                            <label class="control-label mb-1">Blog Image <span class="text-muted fw-normal">(leave blank to keep current)</span></label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img id="edit_image_preview" src="" alt="Current blog image"
                                    style="display:none;width:84px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;" />
                                <span id="edit_image_none" class="text-muted small" style="display:none">No image uploaded yet.</span>
                            </div>
                            <input type="file" name="image" class="form-control" accept="image/*" />
                        </div>
                        <div class="col-md-12">
                            <label class="control-label mb-1">Short Description</label>
                            <textarea name="description" id="edit_description" rows="3" class="form-control" placeholder="Brief summary..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label mb-1">News Focus
                                <span class="text-muted fw-normal">(comma-separated, max 3 — shown on the News &amp; Updates page)</span>
                            </label>
                            <input type="text" name="news_focus" id="edit_news_focus" class="form-control"
                                placeholder="e.g. Corporate Updates, Strategic Growth, Media Releases" />
                            <small class="text-muted news-focus-hint"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-check-label mb-1">Featured Blog</label>
                            <div class="form-check">
                                <input type="checkbox" name="featured" id="edit_featured" class="form-check-input" value="1" />
                                <label class="form-check-label">Set as featured</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Published Date</label>
                            <input type="date" name="published_date" id="edit_published_date" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Updated Date</label>
                            <input type="date" name="updated_date" id="edit_updated_date" class="form-control" />
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-12">
                            <label class="control-label mb-1">Content <span class="text-danger">*</span></label>
                            <textarea id="editor-edit" name="content" rows="8" class="form-control" placeholder="Write blog content..." required></textarea>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <h6 class="fw-semibold mb-3">Meta Details</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="control-label mb-1">Meta Title</label>
                            <input type="text" name="meta_title" id="edit_meta_title" class="form-control" placeholder="Page title for search engines" />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Meta Description</label>
                            <textarea name="meta_description" id="edit_meta_desc" rows="3" class="form-control" placeholder="150–160 chars"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label mb-1">Meta Keywords</label>
                            <textarea name="meta_keywords" id="edit_meta_kw" rows="3" class="form-control" placeholder="Comma-separated keywords"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update Blog</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script>
        var pendingContent = null;

        var ckConfig = {
            height: 380,
            uploadUrl: '{{ route("ckeditor.upload") }}?_token={{ csrf_token() }}',
            toolbar: [
                { name: 'document',    items: ['Source', '-', 'Undo', 'Redo'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                { name: 'paragraph',   items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Blockquote'] },
                { name: 'links',       items: ['Link', 'Unlink'] },
                { name: 'insert',      items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                '/',
                { name: 'styles',      items: ['Format', 'Font', 'FontSize'] },
                { name: 'colors',      items: ['TextColor', 'BGColor'] },
            ],
            removePlugins: 'elementspath',
            resize_enabled: true,
        };

        function ckGet(id)  { return CKEDITOR.instances[id] ? CKEDITOR.instances[id].getData() : ''; }
        function ckSet(id, html) { if (CKEDITOR.instances[id]) CKEDITOR.instances[id].setData(html); }

        /* ── Init Add editor when modal becomes visible ── */
        document.getElementById('addNewModal').addEventListener('shown.bs.modal', function() {
            if (!CKEDITOR.instances['editor']) {
                CKEDITOR.replace('editor', ckConfig);
            }
        });

        /* ── Init Edit editor when modal becomes visible, then load pending content ── */
        document.getElementById('editModal').addEventListener('shown.bs.modal', function() {
            if (!CKEDITOR.instances['editor-edit']) {
                var ed = CKEDITOR.replace('editor-edit', ckConfig);
                ed.on('instanceReady', function() {
                    if (pendingContent !== null) { ed.setData(pendingContent); pendingContent = null; }
                });
            } else if (pendingContent !== null) {
                CKEDITOR.instances['editor-edit'].setData(pendingContent);
                pendingContent = null;
            }
        });

        $(document).ready(function() {
            var items_table = $("#items-table").DataTable({
                dom: "rt",              // table only — custom search, no paging (manual order)
                ordering: false,        // order is the manual drag order only
                paging: false,          // show all rows so a reorder covers the whole list
                info: false,
                columnDefs: [
                    { searchable: false, targets: [0, 3, 4] },
                ],
            });

            /* ── Custom search (matches Services listing) ── */
            $('#blog-search').on('input', function() {
                var v = this.value;
                items_table.search(v).draw();
                $('#search-clear').toggle(v.length > 0);
                // A filtered list can't be reliably reordered — pause dragging while searching.
                $('#items-table tbody').sortable(v.length > 0 ? 'disable' : 'enable');
            });
            $('#search-clear').on('click', function() {
                $('#blog-search').val('').trigger('input');
            });

            /* ── Drag-to-reorder (jQuery UI sortable, same lib as Categories) ── */
            $('#items-table tbody').sortable({
                handle: '.drag-handle',
                items: '> tr',
                axis: 'y',
                helper: function(e, tr) {
                    var $orig = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(i) { $(this).width($orig.eq(i).width()); });
                    return $helper.addClass('row-dragging');
                },
                placeholder: 'row-placeholder',
                start: function(e, ui) { ui.placeholder.html('<td colspan="5"></td>'); },
                update: function() { $('#save-order-btn').show(); }
            });

            /* ── Save Order ── */
            $('#save-order-btn').on('click', function() {
                var order = $('#items-table tbody tr').map(function() { return $(this).data('id'); }).get()
                    .filter(function(x) { return x !== undefined && x !== null && x !== ''; });
                var $btn = $(this);
                $.ajax({
                    url: '{{ route('blogs.reorder') }}',
                    method: 'POST',
                    data: { order: order },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function() { $btn.prop('disabled', true); },
                    success: function(response) {
                        Toast.fire({ icon: 'success', title: response.message });
                        $btn.prop('disabled', false).hide();
                    },
                    error: function() {
                        $btn.prop('disabled', false);
                        Swal.fire({ icon: 'error', title: 'Failed to save order.' });
                    }
                });
            });

            /* ── Featured toggle (no edit page needed) ── */
            $(document).on('change', '.featured-input', function() {
                const id       = $(this).data('id');
                const featured = $(this).is(':checked') ? 1 : 0;
                const $input   = $(this);
                $.ajax({
                    url: '{{ route('blogs.featured.change') }}',
                    method: 'POST',
                    data: { id: id, featured: featured },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) { Toast.fire({ icon: 'success', title: response.message }); },
                    error:   function() {
                        $input.prop('checked', !$input.is(':checked'));   // revert on failure
                        Swal.fire({ icon: 'error', title: 'Failed to update featured status.' });
                    }
                });
            });

            /* ── Select2 ── */
            $('.select2-add').select2({ dropdownParent: $('#addNewModal'), placeholder: 'Select tags', allowClear: true, containerCssClass: 'select2-multi-tags', width: '100%' });
            $('.select2-edit').select2({ dropdownParent: $('#editModal'), placeholder: 'Select tags', allowClear: true, containerCssClass: 'select2-multi-tags', width: '100%' });

            /* ── Slug auto-fill ── */
            function generateSlug(t) {
                return t.toLowerCase().trim().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-');
            }
            $('#add_title').on('input', function() { $('#add_slug').val(generateSlug($(this).val())); });
            $('#edit_title').on('input', function() { $('#edit_slug').val(generateSlug($(this).val())); });

            /* ── News Focus: live "max 3" hint (server also caps at 3) ── */
            $('#add_news_focus, #edit_news_focus').on('input', function() {
                var count = $(this).val().split(',').map(function(s){ return s.trim(); }).filter(Boolean).length;
                var $hint = $(this).siblings('.news-focus-hint');
                if (count > 3) {
                    $hint.text('Only the first 3 values will be used (' + count + ' entered).').addClass('text-danger').removeClass('text-muted');
                } else {
                    $hint.text(count + ' / 3 used').addClass('text-muted').removeClass('text-danger');
                }
            });

            /* ── Add Form ── */
            $('#add_form').on('submit', function(e) {
                e.preventDefault();
                document.querySelector('#editor').value = ckGet('editor');
                var formData = new FormData(this);
                Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                $.ajax({
                    url: this.action, method: 'POST', data: formData,
                    processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        var tagHtml = (response.data.tags || []).map(function(t) {
                            return '<span class="cat-tag cat-tag-sub">' + t.name + '</span>';
                        }).join('') || '<span class="cat-tag cat-tag-none">—</span>';
                        var openUrl = '{{ url('/blog_single') }}/' + response.data.slug;
                        var checked = response.data.featured ? 'checked' : '';
                        var newRow =
                            '<tr data-id="' + response.data.id + '">' +
                                '<td class="text-center"><span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span></td>' +
                                '<td class="fw-semibold" style="color:#2d3a4a;min-width:220px">' + response.data.title + '</td>' +
                                '<td style="min-width:160px">' + tagHtml + '</td>' +
                                '<td style="text-align:center"><div class="form-check form-switch d-inline-block mb-0">' +
                                    '<input class="form-check-input featured-input" type="checkbox" role="switch" data-id="' + response.data.id + '" value="1" ' + checked + '></div></td>' +
                                '<td><div class="btn-group">' +
                                    '<button class="dropdown-toggle btn btn-primary btn-sm action-btn" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots me-1"></i> Actions</button>' +
                                    '<ul class="dropdown-menu dropdown-menu-end shadow">' +
                                        '<li><a class="dropdown-item" href="' + openUrl + '" target="_blank"><i class="ti ti-external-link me-2 text-info"></i>Open Page</a></li>' +
                                        '<li><a class="dropdown-item edit" href="javascript:void(0);" data-id="' + response.data.id + '"><i class="ti ti-edit me-2 text-primary"></i>Edit</a></li>' +
                                        '<li><hr class="dropdown-divider"></li>' +
                                        '<li><a class="dropdown-item delete" href="javascript:void(0);" data-id="' + response.data.id + '"><i class="ti ti-trash me-2 text-danger"></i>Delete</a></li>' +
                                    '</ul></div></td>' +
                            '</tr>';
                        items_table.row.add($(newRow)).draw();
                        $('#items-table tbody').sortable('refresh');   // include the new row in drag
                        $('#addNewModal').modal('hide');
                        $('#add_form')[0].reset();
                        ckSet('editor', '');
                        $('[name="tags[]"].select2-add').val(null).trigger('change');
                    },
                    error: function(xhr) {
                        Swal.close();
                        var msgs = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(k, v) { msgs += '<p class="text-danger">' + v + '</p>'; });
                        } else { msgs = 'Something went wrong. Please try again.'; }
                        Swal.fire({ icon: 'error', title: 'Failed to save', html: msgs });
                    }
                });
            });

            /* ── Edit Form ── */
            $('#edit_form').on('submit', function(e) {
                e.preventDefault();
                document.querySelector('#editor-edit').value = ckGet('editor-edit');
                var formData = new FormData(this);
                Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                $.ajax({
                    url: this.action, method: 'POST', data: formData,
                    processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        var row = $('#items-table').find('tr[data-id="' + response.data.id + '"]');
                        row.find('td:eq(0)').text(response.data.title);
                        var tagHtml = (response.data.tags || []).map(function(t) {
                            return '<span class="cat-tag cat-tag-sub">' + t.name + '</span>';
                        }).join('') || '<span class="cat-tag cat-tag-none">—</span>';
                        row.find('td:eq(1)').html(tagHtml);
                        $('#editModal').modal('hide');
                    },
                    error: function(xhr) {
                        Swal.close();
                        var msgs = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(k, v) { msgs += '<p class="text-danger">' + v + '</p>'; });
                        } else { msgs = 'Something went wrong.'; }
                        Swal.fire({ icon: 'error', title: 'Failed to update', html: msgs });
                    }
                });
            });

            /* ── Edit click: populate form ── */
            $(document).on('click', '.edit', function() {
                var id = $(this).data('id');
                $('#edit_form').attr('action', '{{ route("blogs.update", "") }}/' + id);
                $.ajax({
                    url: '{{ route("blogs.get") }}', method: 'POST',
                    data: { id: id },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        var d = response.data;
                        $('#edit_title').val(d.title);
                        $('#edit_slug').val(d.slug);
                        $('#edit_author').val(d.author_name || '');
                        $('#edit_read_time').val(d.read_time || '');
                        $('#edit_news_focus').val(d.news_focus || '').trigger('input');
                        // Existing blog image thumbnail
                        if (d.image) {
                            $('#edit_image_preview').attr('src', '{{ asset('public/uploads/blog_images') }}/' + d.image).show();
                            $('#edit_image_none').hide();
                        } else {
                            $('#edit_image_preview').hide();
                            $('#edit_image_none').show();
                        }
                        $('#edit_description').val(d.description || '');
                        $('#edit_featured').prop('checked', !!d.featured);
                        $('#edit_meta_title').val(d.meta_title || '');
                        $('#edit_meta_desc').val(d.meta_description || '');
                        $('#edit_meta_kw').val(d.meta_keywords || '');
                        // Tags
                        var tagIds = d.tags ? d.tags.map(function(t) { return String(t.id); }) : [];
                        $('#edit_tags').val(tagIds).trigger('change');
                        // Dates
                        // date cast serialises as full ISO (e.g. 2026-03-15T00:00:00.000000Z);
                        // a <input type="date"> only accepts a bare YYYY-MM-DD, so slice it.
                        $('#edit_published_date').val(d.published_date ? String(d.published_date).substring(0, 10) : '');
                        $('#edit_updated_date').val(d.updated_date ? String(d.updated_date).substring(0, 10) : '');
                        // CKEditor — store content, applied once modal is shown
                        pendingContent = d.content || '';
                        if (CKEDITOR.instances['editor-edit']) { CKEDITOR.instances['editor-edit'].setData(pendingContent); pendingContent = null; }
                        $('#editModal').modal('show');
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Could not load blog data.' });
                    }
                });
            });

            /* ── Delete ── */
            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                var deleteUrl = '{{ route("blogs.destroy", "") }}/' + id;
                var row = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure?', text: "This blog will be deleted permanently.",
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl, method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function(response) {
                                Toast.fire({ icon: 'success', title: response.message });
                                items_table.row(row).remove().draw();
                            },
                            error: function() {
                                Swal.fire({ icon: 'error', title: 'Failed to delete.' });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
