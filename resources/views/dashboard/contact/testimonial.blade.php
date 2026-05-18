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
                    <h4 class="fw-semibold mb-8">All Testimonials</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Other</li>
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
                            <h5 class="mb-0">Testimonials List</h5>

                            <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                <i class="ti ti-plus me-1"></i>
                                Add New
                            </button>

                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>Author Name</th>
                                        <th>Featured</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @if (isset($Projects) && count($Projects) > 0)
                                        @foreach ($Projects as $Project)
                                            <!-- start row -->
                                            <tr data-id="{{ $Project->id }}">
                                                <td>{{ $Project->author_name }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input featured-input" type="checkbox" role="switch"
                                                            id="featured-{{ $Project->id }}" name="featured-{{ $Project->id }}"
                                                            value="1" data-id="{{ $Project->id }}" {{ $Project->featured ? 'checked' : '' }}>
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
                                                            @can('edit tags')
                                                                <li><a class="dropdown-item edit" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal"
                                                                        data-id="{{ $Project->id }}">Edit</a></li>
                                                            @endcan
                                                            @can('delete tags')
                                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                        data-id="{{ $Project->id }}">Delete</a></li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- end row -->
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <!-- start row -->
                                    <tr>
                                        <th>Author Name</th>
                                        <th>Featured</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- add new modal -->
    <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="{{ route('testimonial.store') }}" method="POST" id="add_form"
                enctype="multipart/form-data">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Add New Testimonial
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        {{-- <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Testimonial Author Name <span
                                        class="text-danger">*</span></label>
                                <select name="project_category" class="form-control select2"
                                    data-placeholder="Select project category" required>
                                    <option></option>
                                    @if (isset($projectsCategories) && $projectsCategories->count())
                                        @foreach ($projectsCategories as $projectsCategory)
                                            <option value="{{ $projectsCategory->id }}"
                                                {{ isset($selectedCategoryId) && $selectedCategoryId == $projectsCategory->id ? 'selected' : '' }}>
                                                {{ $projectsCategory->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div> --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Testimonial Author Name <span class="text-danger">*</span></label>
                                <input type="text" id="author_name" name="author_name" class="form-control"
                                    placeholder="Author Name" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-check-label mb-1" for="featured">Set as Featured
                                                Service</label>
                                            <div class="form-check">
                                                <input type="checkbox" name="featured" class="form-check-input" value="1" />
                                                <label class="form-check-label" for="featured">Yes</label>
                                            </div>
                                        </div>
                                    </div>

                        <div class="col-md-12">
                            <div class="mb-6">
                                <label class="control-label mb-1">Author Image <span class="text-danger">*</span></label>
                                <input type="file" name="author_image" class="form-control" multiple required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Position <span class="text-danger">*</span></label>
                                <input type="text" id="position" name="position" class="form-control"
                                    placeholder="Author position" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">company Name <span class="text-danger">*</span></label>
                                <input type="text" id="company_name" name="company_name" class="form-control"
                                    placeholder="Author company Name" required />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Testimonial Content <span class="text-danger">*</span></label>
                                <textarea type="textarea" id="content" name="content" rows="5" class="rich-textarea form-control"
                                    placeholder="type here..." required></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Rating <span class="text-danger">*</span></label>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}">
                                        <label for="star{{ $i }}">&#9733;</label>
                                    @endfor
                            </div>
                        </div>

                        <style>
                        .rating {
                        direction: rtl;
                        display: inline-flex;
                        }
                        .rating input { display: none; }
                        .rating label {
                        font-size: 24px;
                        color: #ccc;
                        cursor: pointer;
                        }
                        .rating input:checked ~ label,
                        .rating label:hover,
                        .rating label:hover ~ label {
                        color: gold;
                        }
                        </style>

                        {{-- <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Images <span class="text-danger">*</span></label>
                                <input type="file" name="image[]" class="form-control" multiple required />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Videos</label>
                                <input type="file" name="video[]" class="form-control" multiple />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Video Covers</label>
                                <input type="file" name="video_thumbnail[]" class="form-control" multiple />
                                <small class="text-muted">Select covers in the same order as your videos.</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Documents</label>
                                <input type="file" name="document[]" class="form-control" multiple />
                            </div>
                        </div> --}}

                        {{-- <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">project Title <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="project Title Name" required />
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Description <span class="text-danger">*</span></label>
                                <textarea type="textarea" id="description" name="description" rows="5" class="rich-textarea form-control"
                                    placeholder="type here..." required></textarea>
                            </div>
                        </div> --}}
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
</div>
    <!-- edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" method="POST" id="edit_form" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h4 class="modal-title">Edit Testimonial</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Author Name</label>
                            <input type="text" id="edit_author_name" name="author_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Position</label>
                            <input type="text" id="edit_position" name="position" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Company Name</label>
                            <input type="text" id="edit_company_name" name="company_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Rating</label>
                            <div class="rating">
                                @for($i=5; $i>=1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="edit_star{{ $i }}">
                                    <label for="edit_star{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>Featured</label><br>
                            <input type="checkbox" id="edit_featured" name="featured" value="1">
                        </div>

                        <div class="col-md-6">
                            <label>Author Image</label>
                            <input type="file" name="author_image" class="form-control">
                        </div>

                        <div class="col-12">
                            <label>Content</label>
                            <textarea id="edit_content" name="content" class="rich-textarea form-control" rows="5" required></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('custom_js')
    <!-- ---------------------------------------------- -->
    <!-- core files -->
    <!-- ---------------------------------------------- -->
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>

    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->

    <script>
        $(document).ready(function() {
            var items_table = $("#items-table").DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");

            tinymce.init({
                selector: '.rich-textarea', // Target textareas by their class
                plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
                toolbar: "code undo redo print spellcheckdialog | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
                image_title: true,
                automatic_uploads: true,
                images_upload_url: '/uploads/tinymce-image',
            });

            // select2 init
            $('#addNewModal .select2').select2({
                dropdownParent: '#addNewModal',
                minimumResultsForSearch: 8,
            });
            // select2 init
            $('#editModal .select2').select2({
                dropdownParent: '#editModal',
                minimumResultsForSearch: 8,
            });


            // add form handle
            $("#add_form").validate({
                rules: {
                    author_name: {
                        required: true,
                    },

                    content: {
                        required: true,
                    }
                },
                messages: {
                    author_name: {
                        required: "tag Author name is required",
                    },

                    content: {
                        required: "Content is required",
                    }
                },
                submitHandler: function(form) {
                    // Collect the form data
                    let formData = new FormData(form);
                    // Send AJAX request
                    $.ajax({
                        url: form.action, // Replace with your server-side endpoint
                        method: form.method,
                        data: formData,
                        processData: false, // Prevent jQuery from automatically processing the data
                        contentType: false, // Let the browser set the content type (required for FormData)
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Send CSRF token via header
                        },
                        beforeSend: function() {
                            // Show loading animation using SweetAlert
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we add',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            // Close the loading Swal
                            Swal.close();

                            // Show success message using SweetAlert mixin
                            Toast.fire({
                                icon: 'success',
                                title: `${response.message}`
                            });
                            const newRow = `<tr data-id="${response.data.id}">
    <td>${response.data.author_name}</td>
    <td>
        <div class="form-check form-switch">
            <input class="form-check-input featured-input" type="checkbox"
                data-id="${response.data.id}"
                ${response.data.featured ? 'checked' : ''}>
        </div>
    </td>
    <td>
        <div class="btn-group">
            <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}">Edit</a></li>
                <li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${response.data.id}">Delete</a></li>
            </ul>
        </div>
    </td>
</tr>`;
                            // Add new row to DataTable
                            items_table.row.add($(newRow)).draw();

                            // Close the modal
                            $('#addNewModal').modal(
                                'hide'); // Replace #addNewModal with your modal ID

                            // Optionally, clear the form
                            $('#add_form')[0].reset();
                        },
                        error: function(xhr) {
                            // Close the loading Swal
                            Swal.close();

                            // Extract and display validation errors from the server
                            let errorMessages = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessages +=
                                        `<p class='text-danger'>${value}</p>`;
                                });
                            } else {
                                errorMessages = 'Something went wrong. Please try again.';
                            }

                            // Show error message with custom SweetAlert template
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to add',
                                html: `<div>${errorMessages}</div>`, // Display multiple error messages
                                customClass: {
                                    popup: 'swal-wide'
                                }
                            });
                        }
                    });
                }
            });

            // add form handle
            $("#edit_form").validate({
                rules: {
                    author_name: {
                        required: true,
                    }
                },
                messages: {
                    authorname: {
                        required: "tag author name is required",
                    }
                },
                submitHandler: function(form) {
                    // Collect the form data
                    let formData = new FormData(form);
                    // Send AJAX request
                    $.ajax({
                        url: form.action, // Replace with your server-side endpoint
                        method: form.method,
                        data: formData,
                        processData: false, // Prevent jQuery from automatically processing the data
                        contentType: false, // Let the browser set the content type (required for FormData)
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Send CSRF token via header
                        },
                        beforeSend: function() {
                            // Show loading animation using SweetAlert
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we add',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            // Close the loading Swal
                            Swal.close();

                            // Show success message using SweetAlert mixin
                            Toast.fire({
                                icon: 'success',
                                title: `${response.message}`
                            });

                            let row = $('#items-table').find(
                                `tr[data-id='${response.data.id}']`);
                            row.html(`
<td>${response.data.author_name}</td>
<td>
    <div class="form-check form-switch">
        <input class="form-check-input featured-input"
            type="checkbox"
            data-id="${response.data.id}"
            ${response.data.featured ? 'checked' : ''}>
    </div>
</td>
<td>
    <div class="btn-group">
        <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown">
            <i class="bi bi-three-dots"></i>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}">Edit</a></li>
            <li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${response.data.id}">Delete</a></li>
        </ul>
    </div>
</td>
`);
                            // destroy the table to reinitialize
                            items_table.destroy();
                            // re initialize the table
                            items_table = $("#items-table").DataTable({
                                dom: "Bfrtip",
                                buttons: ["copy", "csv", "excel", "pdf", "print"],
                            });
                            $(
                                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
                            ).addClass("btn btn-primary mr-1");

                            // Close the modal
                            $('#editModal').modal('toggle');
                        },
                        error: function(xhr) {
                            // Close the loading Swal
                            Swal.close();

                            // Extract and display validation errors from the server
                            let errorMessages = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessages +=
                                        `<p class='text-danger'>${value}</p>`;
                                });
                            } else {
                                errorMessages = 'Something went wrong. Please try again.';
                            }

                            // Show error message with custom SweetAlert template
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to add',
                                html: `<div>${errorMessages}</div>`, // Display multiple error messages
                                customClass: {
                                    popup: 'swal-wide'
                                }
                            });
                        }
                    });
                }
            });

$(document).on('click', '.edit', function() {
    const id = $(this).data('id');
    const updateUrl = `{{ route('testimonial.update', '') }}/${id}`;

    $('#edit_form').attr('action', updateUrl);

    $.ajax({
        url: `{{ route('testimonial.get') }}`,
        method: 'POST',
        data: { id: id }, // important
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Fill fields
            $('#edit_author_name').val(response.data.author_name);
            $('#edit_position').val(response.data.position);
            $('#edit_company_name').val(response.data.company_name);
            $('#edit_featured').prop('checked', response.data.featured == 1);

            // Rating
            $(`#edit_form input[name="rating"]`).prop('checked', false); // clear previous
            $(`#edit_form input[name="rating"][value="${response.data.rating}"]`).prop('checked', true);

            // TinyMCE content
            if (tinymce.get('edit_content')) {
                tinymce.get('edit_content').setContent(response.data.content);
            } else {
                // fallback: in case TinyMCE not initialized yet
                $('#edit_content').val(response.data.content);
            }

            // Open modal **after setting all fields**
            $('#editModal').modal('show');
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch data'
            });
        }
    });
});

            $(document).on('click', '.delete', function() {
                const id = $(this).data('id');
                const deleteUrl = `{{ route('testimonial.destroy', '') }}/${id}`;
                const row = $(this).closest('tr'); // Get the closest table row

                handleDelete(deleteUrl, items_table, row);
            });

            // // Function to handle deletion
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
                        // If confirmed, proceed with deletion
                        $.ajax({
                            url: delete_url,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // Send CSRF token via header
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Deleting...',
                                    text: 'Please wait while we delete the item',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(response) {
                                Swal.close();
                                // Show success message
                                Toast.fire({
                                    icon: 'success',
                                    title: `${response.message}`
                                });
                                // Remove the row from the DataTable
                                table.row(row).remove().draw();
                            },
                            error: function(xhr) {
                                Swal.close();
                                // Show error message
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
