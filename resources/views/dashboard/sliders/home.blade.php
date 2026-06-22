@extends('dashboard/layout')

@section('custom_css')
    <!-- --------------------------------------------------- -->
    <!-- Prism Js -->
    <!-- --------------------------------------------------- -->
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Home Sliders</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Service</li>
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
                            <h5 class="mb-0">Sliders List</h5>
                            @can('create home sliders')
                                <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                    <i class="ti ti-plus me-1"></i>
                                    Add New
                                </button>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($sliders) && count($sliders) > 0)
                                        @foreach ($sliders as $slider)
                                            <tr data-id="{{ $slider->id }}">
                                                <td>
                                                    @if ($slider->image)
                                                        <img src="{{ asset('public/uploads/slider_images/' . $slider->image) }}"
                                                            alt="{{ $slider->main_title }}"
                                                            style="width:90px;height:54px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td>{{ $slider->main_title }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input status-input" type="checkbox"
                                                            role="switch" id="status-{{ $slider->id }}"
                                                            name="status-{{ $slider->id }}" value="active"
                                                            data-id="{{ $slider->id }}"
                                                            {{ $slider->status === 'active' ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @can('edit home sliders')
                                                                <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                        data-id="{{ $slider->id }}">Edit</a></li>
                                                            @endcan
                                                            @can('delete home sliders')
                                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                        data-id="{{ $slider->id }}">Delete</a></li>
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
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Active</th>
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
    <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <form class="modal-content" action="{{ route('sliders.home.store') }}" method="POST" id="add_form"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="addNewModalLabel">Add New Slider</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                {{-- FIX 1: Unique IDs prefixed with "add_" to avoid duplicate ID conflicts with edit modal --}}
                                <label class="control-label mb-1" for="add_image">Banner Image<span class="text-danger">*</span></label>
                                <input type="file" id="add_image" name="image" class="form-control"
                                    placeholder="Slider Image" accept="image/*" required />
                                {{-- FIX 2: Image preview for add modal --}}
                                <div class="mt-2">
                                    <img id="add_image_preview" src="#" alt="Preview"
                                        class="img-thumbnail d-none" style="max-height: 120px;" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="add_main_title">Main Title<span class="text-danger">*</span></label>
                                <input type="text" id="add_main_title" name="main_title" class="form-control"
                                    placeholder="Slider Main Title" required />
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="add_pre_title">Description</label>
                                <textarea id="add_pre_title" name="pre_title" class="form-control" placeholder="Slider Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="add_button_text">Button Text</label>
                                <input type="text" id="add_button_text" name="button_text" class="form-control"
                                    placeholder="Slider Button Text" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="add_button_link">Button Link</label>
                                <input type="text" id="add_button_link" name="button_link" class="form-control"
                                    placeholder="Slider Button Link" />
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-check-label mb-1" for="add_status">Status</label>
                                <div class="form-check">
                                    <input type="checkbox" id="add_status" name="status" class="form-check-input" value="active" />
                                    <label class="form-check-label" for="add_status">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="#" method="POST" id="edit_form" enctype="multipart/form-data">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="editModalLabel">Edit Slider</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                {{-- FIX 1: Unique IDs prefixed with "edit_" --}}
                                <label class="control-label mb-1" for="edit_image">Banner Image</label>
                                <input type="file" id="edit_image" name="image" class="form-control"
                                    placeholder="Slider Image" accept="image/*" />
                                {{-- FIX 2: Current image preview in edit modal --}}
                                <div class="mt-2">
                                    <img id="edit_image_preview" src="#" alt="Current Image"
                                        class="img-thumbnail d-none" style="max-height: 120px;" />
                                    <small class="text-muted d-block mt-1" id="edit_image_hint">Leave blank to keep the current image.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="edit_main_title">Main Title<span class="text-danger">*</span></label>
                                <input type="text" id="edit_main_title" name="main_title" class="form-control"
                                    placeholder="Slider Main Title" required />
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="edit_pre_title">Description</label>
                                <textarea id="edit_pre_title" name="pre_title" class="form-control" placeholder="Slider Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="edit_button_text">Button Text</label>
                                <input type="text" id="edit_button_text" name="button_text" class="form-control"
                                    placeholder="Slider Button Text" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1" for="edit_button_link">Button Link</label>
                                <input type="text" id="edit_button_link" name="button_link" class="form-control"
                                    placeholder="Slider Button Link" />
                            </div>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-check-label mb-1" for="edit_status">Status</label>
                                <div class="form-check">
                                    <input type="checkbox" id="edit_status" name="status" class="form-check-input" value="active" />
                                    <label class="form-check-label" for="edit_status">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>

    <script>
        // FIX 3: Pass permission flags from Blade to JS to control which buttons appear in AJAX-rendered rows
        const canEdit   = @json(auth()->user()?->can('edit home sliders') ?? false);
        const canDelete = @json(auth()->user()?->can('delete home sliders') ?? false);
        const sliderImgBase = "{{ asset('public/uploads/slider_images') }}";
        function sliderImgCell(img, title) {
            return img
                ? `<td><img src="${sliderImgBase}/${img}" alt="${title || ''}" style="width:90px;height:54px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;"></td>`
                : `<td><span class="text-muted small">—</span></td>`;
        }

        $(document).ready(function() {
            var items_table = $("#items-table").DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            // FIX 2: Live image preview for add modal
            $('#add_image').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#add_image_preview').attr('src', e.target.result).removeClass('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#add_image_preview').addClass('d-none').attr('src', '#');
                }
            });

            // FIX 2: Live image preview for edit modal (new file chosen)
            $('#edit_image').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#edit_image_preview').attr('src', e.target.result).removeClass('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // FIX 6: Reset add form (including preview) when modal is closed
            $('#addNewModal').on('hidden.bs.modal', function () {
                $('#add_form')[0].reset();
                $('#add_image_preview').addClass('d-none').attr('src', '#');
            });

            // FIX 6: Reset edit form (including preview) when modal is closed
            $('#editModal').on('hidden.bs.modal', function () {
                $('#edit_form')[0].reset();
                $('#edit_image_preview').addClass('d-none').attr('src', '#');
            });

            // -------------------------------------------------------
            // Add form
            // -------------------------------------------------------
            $("#add_form").validate({
                rules: {
                    image:      { required: true },
                    main_title: { required: true },
                },
                messages: {
                    image:      { required: "Image is required" },
                    main_title: { required: "Main title is required" },
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url:         form.action,
                        method:      form.method,
                        data:        formData,
                        processData: false,
                        contentType: false,
                        headers:     { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we add the slider.',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            Toast.fire({ icon: 'success', title: response.message });

                            // FIX 3: Respect permissions when building the new row
                            const editBtn   = canEdit   ? `<li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}">Edit</a></li>` : '';
                            const deleteBtn = canDelete ? `<li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${response.data.id}">Delete</a></li>` : '';

                            const newRow = `
                                <tr data-id="${response.data.id}">
                                    ${sliderImgCell(response.data.image, response.data.main_title)}
                                    <td>${response.data.main_title}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-input" type="checkbox" role="switch"
                                                id="status-${response.data.id}"
                                                name="status-${response.data.id}"
                                                value="active"
                                                data-id="${response.data.id}"
                                                ${response.data.status === 'active' ? 'checked' : ''}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="dropdown-toggle btn btn-primary btn-sm"
                                                data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                ${editBtn}
                                                ${deleteBtn}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>`;

                            items_table.row.add($(newRow)).draw();
                            $('#addNewModal').modal('hide');
                            // Note: form reset is handled by the hidden.bs.modal event (FIX 6)
                        },
                        error: function(xhr) {
                            Swal.close();
                            let errorMessages = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessages += `<p class="text-danger">${value}</p>`;
                                });
                            } else {
                                errorMessages = 'Something went wrong. Please try again.';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to add',
                                html: `<div>${errorMessages}</div>`,
                                customClass: { popup: 'swal-wide' }
                            });
                        }
                    });
                }
            });

            // -------------------------------------------------------
            // Edit form
            // -------------------------------------------------------
            $("#edit_form").validate({
                rules: {
                    main_title: { required: true },
                },
                messages: {
                    main_title: { required: "Main title is required" },
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url:         form.action,
                        method:      form.method,
                        data:        formData,
                        processData: false,
                        contentType: false,
                        headers:     { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we update the slider.',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            Toast.fire({ icon: 'success', title: response.message });

                            // FIX 3: Respect permissions when rebuilding the updated row
                            const editBtn   = canEdit   ? `<li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}">Edit</a></li>` : '';
                            const deleteBtn = canDelete ? `<li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${response.data.id}">Delete</a></li>` : '';

                            let row = $('#items-table').find(`tr[data-id="${response.data.id}"]`);
                            row.html(`
                                ${sliderImgCell(response.data.image, response.data.main_title)}
                                <td>${response.data.main_title}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-input" type="checkbox" role="switch"
                                            id="status-${response.data.id}"
                                            name="status-${response.data.id}"
                                            value="active"
                                            data-id="${response.data.id}"
                                            ${response.data.status === 'active' ? 'checked' : ''}>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                            data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            ${editBtn}
                                            ${deleteBtn}
                                        </ul>
                                    </div>
                                </td>`);

                            // FIX 4: Use row().invalidate() instead of destroying and re-initializing the whole DataTable
                            items_table.row(row).invalidate().draw(false);

                            $('#editModal').modal('hide');
                            // Note: form reset is handled by the hidden.bs.modal event (FIX 6)
                        },
                        error: function(xhr) {
                            Swal.close();
                            let errorMessages = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessages += `<p class="text-danger">${value}</p>`;
                                });
                            } else {
                                errorMessages = 'Something went wrong. Please try again.';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to update',
                                html: `<div>${errorMessages}</div>`,
                                customClass: { popup: 'swal-wide' }
                            });
                        }
                    });
                }
            });

            // -------------------------------------------------------
            // Edit button click — FIX 5: Show loading state immediately
            // -------------------------------------------------------
            $(document).on('click', '.edit', function() {
                const id        = $(this).data('id');
                const updateUrl = `{{ route('sliders.home.update', '') }}/${id}`;
                $('#edit_form').attr('action', updateUrl);

                // FIX 5: Show a loading indicator while fetching slider data
                Swal.fire({
                    title: 'Loading...',
                    text: 'Fetching slider details.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url:    `{{ route('sliders.home.get') }}`,
                    method: 'POST',
                    data:   { id: id },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Swal.close(); // FIX 5: Close loading once data arrives

                        const d = response.data;
                        $('#edit_form [name="main_title"]').val(d.main_title);
                        $('#edit_form [name="pre_title"]').val(d.pre_title);
                        $('#edit_form [name="button_text"]').val(d.button_text);
                        $('#edit_form [name="button_link"]').val(d.button_link);
                        $('#edit_form [name="status"]').prop('checked', d.status === 'active');

                        // FIX 2: Show current image preview if the response includes an image URL
                        if (d.image_url) {
                            $('#edit_image_preview').attr('src', d.image_url).removeClass('d-none');
                        } else {
                            $('#edit_image_preview').addClass('d-none').attr('src', '#');
                        }

                        $('#editModal').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to load',
                            text: 'An error occurred while fetching the slider. Please try again.',
                        });
                    }
                });
            });

            // -------------------------------------------------------
            // Delete button click
            // -------------------------------------------------------
            $(document).on('click', '.delete', function() {
                const id        = $(this).data('id');
                const deleteUrl = `{{ route('sliders.home.destroy', '') }}/${id}`;
                const row       = $(this).closest('tr');
                handleDelete(deleteUrl, items_table, row);
            });

            // -------------------------------------------------------
            // Status toggle
            // -------------------------------------------------------
            $(document).on('change', '.status-input', function() {
                const id     = $(this).data('id');
                const status = $(this).is(':checked') ? 'active' : 'inactive';

                $.ajax({
                    url:    '{{ route('sliders.status.change') }}',
                    method: 'POST',
                    data:   { id, status },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Toast.fire({ icon: 'success', title: response.message });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to update',
                            text: 'An error occurred while updating the status. Please try again.'
                        });
                    }
                });
            });

            // -------------------------------------------------------
            // Delete helper
            // -------------------------------------------------------
            function handleDelete(delete_url, table, row) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to recover this item!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:    delete_url,
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Deleting...',
                                    text: 'Please wait while we delete the item.',
                                    allowOutsideClick: false,
                                    didOpen: () => { Swal.showLoading(); }
                                });
                            },
                            success: function(response) {
                                Swal.close();
                                Toast.fire({ icon: 'success', title: response.message });
                                table.row(row).remove().draw();
                            },
                            error: function() {
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed to delete',
                                    text: 'An error occurred while trying to delete the item. Please try again.',
                                });
                            }
                        });
                    }
                });
            }

        });
    </script>
@endsection