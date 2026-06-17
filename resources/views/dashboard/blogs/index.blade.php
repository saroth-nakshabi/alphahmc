@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        /* Allow multi-select tags container to grow beyond the layout's fixed 36px */
        .select2-multi-tags.select2 { height: auto !important; min-height: 36px; }
        .select2-multi-tags .select2-selection--multiple { min-height: 34px; }
        /* Give CKEditor a decent height inside the modal */
        .ck-editor__editable_inline { min-height: 280px; }

        /* Tag chips (used as the blog's tags) */
        .cat-tag {
            display: inline-block; font-size: .72rem; font-weight: 600;
            padding: 2px 8px; border-radius: 20px; margin: 2px 2px 0 0; white-space: nowrap;
        }
        .cat-tag-sub  { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .cat-tag-none { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; }

        /* ── Drag list (matches Brands / Categories page) ── */
        #sortable-list { list-style: none; padding: 0; margin: 0; }
        .sort-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 18px; margin-bottom: 8px;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            cursor: grab; transition: box-shadow .15s, border-color .15s; user-select: none;
        }
        .sort-item:active { cursor: grabbing; }
        .sort-item.ui-sortable-helper { box-shadow: 0 8px 24px rgba(0,51,88,0.12); border-color: #94a3b8; }
        .sort-item.ui-sortable-placeholder {
            border: 2px dashed #cbd5e1; background: #f8fafc;
            visibility: visible !important; border-radius: 10px;
        }
        .drag-handle { color: #cbd5e1; font-size: 1.2rem; flex-shrink: 0; transition: color .15s; cursor: grab; }
        .sort-item:hover .drag-handle { color: #64748b; }
        .sort-rank {
            width: 28px; height: 28px; background: #f1f5f9; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 800; color: #64748b; flex-shrink: 0;
        }
        .sort-name { flex: 1; min-width: 0; }
        .sort-name .blog-title {
            font-weight: 600; font-size: .95rem; color: #0f172a; display: block;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sort-name .blog-tags { display: block; margin-top: 2px; }
        .featured-cell { display: flex; align-items: center; gap: 7px; flex-shrink: 0; min-width: 112px; }
        .featured-cell .form-check { margin: 0; min-height: auto; }
        .featured-cell .form-check-input { cursor: pointer; }
        .featured-label { font-size: .72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .4px; }
        .sort-item.is-featured .featured-label { color: #b45309; }
        .sort-item.is-featured { border-left: 3px solid #f59e0b; }
        .sort-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions .btn { padding: 5px 10px; font-size: .78rem; }

        #save-order-btn { transition: all .2s; }
        #save-order-btn.changed { animation: pulse-btn .6s ease-in-out infinite alternate; }
        @keyframes pulse-btn {
            from { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
            to   { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
        }
        .order-hint { font-size: .8rem; color: #94a3b8; display: flex; align-items: center; gap: 6px; }

        .blog-search-wrap { position: relative; width: 260px; }
        .blog-search-wrap .ti-search {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: .9rem; pointer-events: none;
        }
        .blog-search-wrap input { padding-left: 30px; border-radius: 8px; }
        .sort-item.search-hidden { display: none; }
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
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            <i class="ti ti-plus me-1"></i> Add New Blog
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        {{-- Header row --}}
                        <div class="mb-3 d-flex align-items-center gap-3 flex-wrap">
                            <h5 class="mb-0 fw-semibold">Blogs List</h5>
                            <span class="badge bg-light-primary text-primary">{{ count($blogs) }} total</span>
                            @can('edit blogs')
                                <span class="order-hint">
                                    <i class="ti ti-drag-drop"></i>
                                    Drag rows to reorder — click <strong>Save Order</strong> to apply
                                </span>
                            @endcan
                            <div class="blog-search-wrap ms-auto">
                                <i class="ti ti-search"></i>
                                <input type="search" id="blog-search" class="form-control form-control-sm"
                                    placeholder="Search by title or tag..." autocomplete="off" />
                            </div>
                            @can('edit blogs')
                                <button id="save-order-btn" class="btn btn-primary btn-sm">
                                    <i class="ti ti-device-floppy me-1"></i> Save Order
                                </button>
                            @endcan
                        </div>

                        {{-- Sortable list --}}
                        <ul id="sortable-list">
                            @if (isset($blogs) && count($blogs) > 0)
                                @foreach ($blogs as $blog)
                                    <li class="sort-item {{ $blog->featured ? 'is-featured' : '' }}" data-id="{{ $blog->id }}">
                                        <span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>
                                        <span class="sort-rank">{{ $loop->iteration }}</span>
                                        <span class="sort-name">
                                            <span class="blog-title">{{ $blog->title }}</span>
                                            <span class="blog-tags">
                                                @forelse ($blog->tags as $tag)
                                                    <span class="cat-tag cat-tag-sub">{{ $tag->name }}</span>
                                                @empty
                                                    <span class="cat-tag cat-tag-none">No tags</span>
                                                @endforelse
                                            </span>
                                        </span>
                                        <span class="featured-cell" title="Featured blog">
                                            <span class="featured-label">Featured</span>
                                            <span class="form-check form-switch">
                                                <input class="form-check-input featured-input" type="checkbox" role="switch"
                                                    data-id="{{ $blog->id }}" value="1"
                                                    {{ $blog->featured ? 'checked' : '' }}
                                                    @cannot('edit blogs') disabled @endcannot>
                                            </span>
                                        </span>
                                        <div class="sort-actions">
                                            <a class="btn btn-light btn-sm" href="{{ route('front.singleBlog', $blog->slug) }}"
                                                target="_blank" title="Open page">
                                                <i class="ti ti-external-link"></i>
                                            </a>
                                            @can('edit blogs')
                                                <button class="btn btn-light btn-sm edit" data-id="{{ $blog->id }}" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                            @endcan
                                            @can('delete blogs')
                                                <button class="btn btn-light-danger btn-sm delete text-danger" data-id="{{ $blog->id }}" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <div id="search-empty-state" class="text-center py-5 text-muted d-none">
                            <i class="ti ti-zoom-cancel" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                            <p class="mb-0">No blogs match "<span id="search-empty-term" class="fw-semibold"></span>"</p>
                        </div>

                        @if (!isset($blogs) || count($blogs) === 0)
                            <div class="text-center py-5 text-muted" id="blogs-empty-state">
                                <i class="ti ti-article-off" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                                <p>No blogs yet. Add one to get started.</p>
                            </div>
                        @endif

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
    <script src="{{ asset('public/dashboard/dist/libs/jquery-ui/dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script>
        const REORDER_URL = '{{ route('blogs.reorder') }}';
        const BLOG_IMG_BASE = '{{ asset('public/uploads/blog_images') }}';
        const OPEN_BASE = '{{ url('/blog_single') }}';
        const CSRF = $('meta[name="csrf-token"]').attr('content');

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

            function tagChips(tags) {
                if (!tags || !tags.length) return '<span class="cat-tag cat-tag-none">No tags</span>';
                return tags.map(function(t) {
                    return '<span class="cat-tag cat-tag-sub">' + escHtml(t.name) + '</span>';
                }).join('');
            }

            function blogRow(d) {
                const checked = d.featured ? 'checked' : '';
                const feat = d.featured ? 'is-featured' : '';
                const openUrl = OPEN_BASE + '/' + d.slug;
                return '<li class="sort-item ' + feat + '" data-id="' + d.id + '">' +
                    '<span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>' +
                    '<span class="sort-rank">0</span>' +
                    '<span class="sort-name">' +
                        '<span class="blog-title">' + escHtml(d.title) + '</span>' +
                        '<span class="blog-tags">' + tagChips(d.tags) + '</span>' +
                    '</span>' +
                    '<span class="featured-cell" title="Featured blog">' +
                        '<span class="featured-label">Featured</span>' +
                        '<span class="form-check form-switch">' +
                            '<input class="form-check-input featured-input" type="checkbox" role="switch" data-id="' + d.id + '" value="1" ' + checked + '>' +
                        '</span>' +
                    '</span>' +
                    '<div class="sort-actions">' +
                        '<a class="btn btn-light btn-sm" href="' + openUrl + '" target="_blank" title="Open page"><i class="ti ti-external-link"></i></a>' +
                        '<button class="btn btn-light btn-sm edit" data-id="' + d.id + '" title="Edit"><i class="ti ti-edit"></i></button>' +
                        '<button class="btn btn-light-danger btn-sm delete text-danger" data-id="' + d.id + '" title="Delete"><i class="ti ti-trash"></i></button>' +
                    '</div>' +
                '</li>';
            }

            /* ── Drag-and-drop sort (same as Brands) ── */
            $("#sortable-list").sortable({
                handle: '.drag-handle',
                placeholder: 'sort-item ui-sortable-placeholder',
                tolerance: 'pointer',
                update: function () {
                    updateRanks();
                    $('#save-order-btn').addClass('changed');
                }
            });

            /* ── Search filter ── */
            $('#blog-search').on('input', function () {
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

            /* ── Save Order ── */
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
                    success: function (response) {
                        Swal.close();
                        $('#save-order-btn').removeClass('changed');
                        Toast.fire({ icon: 'success', title: response.message || 'Order saved! This order is used wherever blogs are listed.' });
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not save order. Please try again.' });
                    }
                });
            });

            /* ── Featured toggle (no edit page needed) ── */
            $(document).on('change', '.featured-input', function() {
                const id       = $(this).data('id');
                const featured = $(this).is(':checked') ? 1 : 0;
                const $input   = $(this);
                $input.closest('.sort-item').toggleClass('is-featured', featured === 1);
                $.ajax({
                    url: '{{ route('blogs.featured.change') }}',
                    method: 'POST',
                    data: { id: id, featured: featured },
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    success: function(response) { Toast.fire({ icon: 'success', title: response.message }); },
                    error:   function() {
                        $input.prop('checked', !$input.is(':checked'));   // revert on failure
                        $input.closest('.sort-item').toggleClass('is-featured', $input.is(':checked'));
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
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        $('#blogs-empty-state').remove();
                        $('#sortable-list').append(blogRow(response.data));
                        updateRanks();
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
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        var d = response.data;
                        var $li = $('#sortable-list .sort-item[data-id="' + d.id + '"]');
                        if ($li.length) {
                            $li.find('.blog-title').text(d.title);
                            $li.find('.blog-tags').html(tagChips(d.tags));
                            $li.find('.featured-input').prop('checked', !!d.featured);
                            $li.toggleClass('is-featured', !!d.featured);
                        }
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
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    success: function(response) {
                        var d = response.data;
                        $('#edit_title').val(d.title);
                        $('#edit_slug').val(d.slug);
                        $('#edit_author').val(d.author_name || '');
                        $('#edit_read_time').val(d.read_time || '');
                        $('#edit_news_focus').val(d.news_focus || '').trigger('input');
                        if (d.image) {
                            $('#edit_image_preview').attr('src', BLOG_IMG_BASE + '/' + d.image).show();
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
                        var tagIds = d.tags ? d.tags.map(function(t) { return String(t.id); }) : [];
                        $('#edit_tags').val(tagIds).trigger('change');
                        // date cast serialises as full ISO; <input type="date"> needs YYYY-MM-DD
                        $('#edit_published_date').val(d.published_date ? String(d.published_date).substring(0, 10) : '');
                        $('#edit_updated_date').val(d.updated_date ? String(d.updated_date).substring(0, 10) : '');
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
                var $li = $(this).closest('.sort-item');
                Swal.fire({
                    title: 'Are you sure?', text: "This blog will be deleted permanently.",
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl, method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': CSRF },
                            success: function(response) {
                                Toast.fire({ icon: 'success', title: response.message });
                                $li.remove();
                                updateRanks();
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
