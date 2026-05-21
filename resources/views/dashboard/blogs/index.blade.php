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
    </style>
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">All Blogs</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Blog</li>
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

    <section class="datatables">
        <!-- File export -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex">
                            <h5 class="mb-0">Blogs List</h5>
                            @can('create blogs')
                                <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                    <i class="ti ti-plus me-1"></i>
                                    Add New
                                </button>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Tags</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($blogs) && count($blogs) > 0)
                                        @foreach ($blogs as $blog)
                                            <tr data-id="{{ $blog->id }}">
                                                <td>{{ $blog->title }}</td>
                                                <td>
                                                    @forelse ($blog->tags as $tag)
                                                        <span class="badge bg-light-primary text-primary me-1">{{ $tag->name }}</span>
                                                    @empty
                                                        <span class="text-muted small">—</span>
                                                    @endforelse
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @can('edit blogs')
                                                                <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                        data-id="{{ $blog->id }}">Edit</a></li>
                                                            @endcan
                                                            @can('delete blogs')
                                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                        data-id="{{ $blog->id }}">Delete</a></li>
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
                                        <th>Title</th>
                                        <th>Tags</th>
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
                            <input type="file" name="image" class="form-control" accept="image/*" />
                        </div>
                        <div class="col-md-12">
                            <label class="control-label mb-1">Short Description</label>
                            <textarea name="description" id="edit_description" rows="3" class="form-control" placeholder="Brief summary..."></textarea>
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
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");

            /* ── Select2 ── */
            $('.select2-add').select2({ dropdownParent: $('#addNewModal'), placeholder: 'Select tags', allowClear: true, containerCssClass: 'select2-multi-tags', width: '100%' });
            $('.select2-edit').select2({ dropdownParent: $('#editModal'), placeholder: 'Select tags', allowClear: true, containerCssClass: 'select2-multi-tags', width: '100%' });

            /* ── Slug auto-fill ── */
            function generateSlug(t) {
                return t.toLowerCase().trim().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-');
            }
            $('#add_title').on('input', function() { $('#add_slug').val(generateSlug($(this).val())); });
            $('#edit_title').on('input', function() { $('#edit_slug').val(generateSlug($(this).val())); });

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
                            return '<span class="badge bg-light-primary text-primary me-1">' + t.name + '</span>';
                        }).join('') || '<span class="text-muted small">—</span>';
                        var newRow = `<tr data-id="${response.data.id}"><td>${response.data.title}</td><td>${tagHtml}</td><td><div class="btn-group"><button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button><ul class="dropdown-menu"><li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}">Edit</a></li><li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${response.data.id}">Delete</a></li></ul></div></td></tr>`;
                        items_table.row.add($(newRow)).draw();
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
                            return '<span class="badge bg-light-primary text-primary me-1">' + t.name + '</span>';
                        }).join('') || '<span class="text-muted small">—</span>';
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
                        $('#edit_description').val(d.description || '');
                        $('#edit_featured').prop('checked', !!d.featured);
                        $('#edit_meta_title').val(d.meta_title || '');
                        $('#edit_meta_desc').val(d.meta_description || '');
                        $('#edit_meta_kw').val(d.meta_keywords || '');
                        // Tags
                        var tagIds = d.tags ? d.tags.map(function(t) { return String(t.id); }) : [];
                        $('#edit_tags').val(tagIds).trigger('change');
                        // Dates
                        $('#edit_published_date').val(d.published_date || '');
                        $('#edit_updated_date').val(d.updated_date || '');
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
