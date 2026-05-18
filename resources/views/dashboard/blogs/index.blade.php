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
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>Name</th>
                                        {{-- <th>Featured</th> --}}
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @if (isset($blogs) && count($blogs) > 0)
                                        @foreach ($blogs as $blog)
                                            <!-- start row -->
                                            <tr data-id="{{ $blog->id }}">
                                                <td>{{ $blog->title }}</td>
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
                                            <!-- end row -->
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <!-- start row -->
                                    <tr>
                                        <th>Name</th>
                                        {{-- <th>Featured</th> --}}
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <form class="modal-content" action="{{ route('blogs.store') }}" method="POST" id="add_form"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="addNewModal">
                        Add New Blog
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Blog Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Blog Title"
                                    required />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Blog Image <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" placeholder="12n" required />
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Slug<span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" placeholder="Enter slug"
                                    required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Author Name<span class="text-danger">*</span></label>
                                <select name="tags[]" class="form-control select2" data-placeholder="Select Category"
                                    required multiple>
                                    <option></option>
                                    @if (isset($tags) && count($tags) > 0)
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Blog Short Description</label>
                                <textarea type="textarea" name="description" rows="5" class="form-control" placeholder="type here..."></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-check-label mb-1" for="featured">Set as Featured
                                    Blog</label>
                                <div class="form-check">
                                    <input type="checkbox" name="featured" class="form-check-input" value="1" />
                                    <label class="form-check-label" for="featured">Yes</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--/row-->
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Content<span class="text-danger">*</span></label>
                                <textarea id="editor" type="textarea" name="content" rows="5" class="rich-textarea form-control"
                                    placeholder="Write a brief content..." required></textarea>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <hr>
                    <h5>Meta Details</h6>
                        <div class="row pt-2">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="control-label mb-1">Meta Title</label>
                                    <input type="text" name="meta_title" rows="5" class="form-control"
                                        placeholder="type here..." />
                                </div>
                                <div class="mb-3">
                                    <label class="control-label mb-1">Meta Description</label>
                                    <textarea type="textarea" name="meta_description" rows="5" class="form-control" placeholder="type here..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="control-label mb-1">Meta Keywords</label>
                                    <textarea type="textarea" name="meta_keywords" rows="5" class="form-control" placeholder="type here..."></textarea>
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
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <form class="modal-content" action="#" method="POST" id="edit_form">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Edit Service
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Blog Title<span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Enter Blog Title" required />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Blog Image <span class="text-danger">*</span></label>
                                <input type="file" id="image" name="image" class="form-control"
                                    placeholder="Blog Image" />
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Slug<span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" placeholder="Enter slug"
                                    required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Author Name<span class="text-danger">*</span></label>
                                <select name="tags[]" class="form-control select2" data-placeholder="Select Category"
                                    required multiple>
                                    <option></option>
                                    @if (isset($tags) && count($tags) > 0)
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Blog Short Description</label>
                                <textarea type="textarea" name="description" rows="5" class="form-control" placeholder="type here..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-check-label mb-1" for="featured">Set as Featured
                                    Blog</label>
                                <div class="form-check">
                                    <input type="checkbox" name="featured" class="form-check-input" value="1" />
                                    <label class="form-check-label" for="featured">Yes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/row-->
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Content<span class="text-danger">*</span></label>
                                <textarea id="editor-edit" type="textarea" name="content" rows="5" class="rich-textarea form-control"
                                    placeholder="Write a brief content..." required></textarea>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <hr>
                    <h5>Meta Details</h6>
                        <div class="row pt-2">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="control-label mb-1">Meta Title</label>
                                    <input type="text" name="meta_title" rows="5" class="form-control"
                                        placeholder="type here..." />
                                </div>
                                <div class="mb-3">
                                    <label class="control-label mb-1">Meta Description</label>
                                    <textarea type="textarea" name="meta_description" rows="5" class="form-control" placeholder="type here..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="control-label mb-1">Meta Keywords</label>
                                    <textarea type="textarea" name="meta_keywords" rows="5" class="form-control" placeholder="type here..."></textarea>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- ---------------------------------------------- -->
    <!-- core files -->
    <!-- ---------------------------------------------- -->
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>

    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->
    <script src="public/dashboard/dist/libs/tinymce/tinymce.min.js"></script>
    <script>
        $(document).ready(function() {
            var items_table = $("#items-table").DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");

            // tinymce.init({
            //     selector: '.rich-textarea', // Target textareas by their class
            //     plugins: 'code searchreplace autolink directionality visualblocks visualchars image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
            //     toolbar: "code undo redo print spellcheckdialog | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link image | alignleft aligncenter alignright alignjustify | code",
            //     image_title: true,
            //     automatic_uploads: true,
            //     images_upload_url: '/upload-image',

            //     // Custom file picker for images
            //     file_picker_types: 'image',
            //     file_picker_callback: function(callback, value, meta) {
            //         var input = document.createElement('input');
            //         input.setAttribute('type', 'file');
            //         input.setAttribute('accept', 'image/*');

            //         input.onchange = function() {
            //             var file = this.files[0];
            //             var reader = new FileReader();

            //             reader.onload = function() {
            //                 var id = 'blobid' + (new Date()).getTime();
            //                 var blobCache = tinymce.activeEditor.editorUpload.blobCache;
            //                 var base64 = reader.result.split(',')[1];
            //                 var blobInfo = blobCache.create(id, file, base64);
            //                 blobCache.add(blobInfo);

            //                 // Call the callback with the image URL
            //                 callback(blobInfo.blobUri(), {
            //                     title: file.name
            //                 });
            //             };

            //             reader.readAsDataURL(file);
            //         };

            //         input.click();
            //     },

            //     // Image Upload Handler
            //     images_upload_handler: function(blobInfo, success, failure) {
            //         var formData = new FormData();
            //         formData.append('file', blobInfo.blob(), blobInfo.filename());

            //         // Use fetch to send the file to your backend
            //         fetch('/upload-image', {
            //                 method: 'POST',
            //                 body: formData
            //             })

            //             .then(response => {
            //                 if (!response.ok) {
            //                     throw new Error(
            //                         'Network response was not ok'); // Handle network errors
            //                 }
            //                 return response.json(); // Ensure that we get a JSON response

            //                 console.log(response);
            //             })
            //             .then(json => {
            //                 if (json.location) {
            //                     success(json.location); // Pass the image URL back to TinyMCE
            //                 } else {
            //                     failure(
            //                         'Invalid JSON response: No location field found'
            //                     ); // Handle missing 'location'
            //                 }
            //             })
            //             .catch(error => {
            //                 failure('Image upload failed: ' + error
            //                     .message); // Handle errors and display message
            //             });
            //     }
            // });

            // ck editor

            // class MyUploadAdapter {
            //     constructor(loader) {
            //         this.loader = loader;
            //     }

            //     upload() {
            //         return this.loader.file.then(file => {
            //             return new Promise((resolve, reject) => {
            //                 const data = new FormData();
            //                 data.append('upload', file);
            //                 data.append('_token', '{{ csrf_token() }}');

            //                 fetch('{{ route('ckeditor.upload') }}', {
            //                         method: 'POST',
            //                         body: data
            //                     })
            //                     .then(response => response.json())
            //                     .then(result => {
            //                         resolve({
            //                             default: result.url
            //                         });
            //                     })
            //                     .catch(error => {
            //                         reject(error.message);
            //                     });
            //             });
            //         });
            //     }


            //     abort() {
            //         // abort logic if needed
            //     }
            // }

            // function MyCustomUploadAdapterPlugin(editor) {
            //     editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            //         return new MyUploadAdapter(loader);
            //     };
            // }

            // ClassicEditor.create(document.querySelector("#editor"), {
            //     extraPlugins: [MyCustomUploadAdapterPlugin],
            //     toolbar: [
            //         "heading",
            //         "|",
            //         "bold",
            //         "italic",
            //         "link",
            //         "bulletedList",
            //         "numberedList",
            //         "blockQuote",
            //         "|",
            //         "insertTable",
            //         "undo",
            //         "redo",
            //         "imageUpload",
            //     ],
            // }).catch(error => {
            //     console.error(error);
            // });


            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }

                upload() {
                    return this.loader.file.then(file => new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('upload', file);
                        data.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('ckeditor.upload') }}', {
                                method: 'POST',
                                body: data
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(error => {
                                        throw error;
                                    });
                                }
                                return response.json();
                            })
                            .then(result => {
                                if (result.uploaded) {
                                    resolve({
                                        default: result.url
                                    });
                                } else {
                                    throw new Error(result.error?.message || 'Upload failed');
                                }
                            })
                            .catch(error => {
                                reject(error.message);
                            });
                    }));
                }

                abort() {
                    // Reject the promise if the upload is aborted
                    return Promise.reject('Upload aborted');
                }
            }

            function MyCustomUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
            }

            // Initialize the editor
            ClassicEditor
                .create(document.querySelector("#editor"), {
                    extraPlugins: [MyCustomUploadAdapterPlugin],
                    // Your toolbar configuration
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                        'insertTable', 'undo', 'redo', 'imageUpload'
                    ],
                    // Image configuration
                    image: {
                        toolbar: [
                            'imageTextAlternative',
                            'imageStyle:inline',
                            'imageStyle:block',
                            'imageStyle:side'
                        ],
                        // This is important for the upload adapter to work
                        upload: {
                            types: ['jpeg', 'png', 'jpg', 'gif', 'webp']
                        }
                    }
                })
                .catch(error => {
                    console.error('There was a problem initializing the editor.', error);
                });

            // Initialize the editor for the edit modal

            let editorInstance; // Global reference

            ClassicEditor
                .create(document.querySelector("#editor-edit"), {
                    extraPlugins: [MyCustomUploadAdapterPlugin],
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                        'insertTable', 'undo', 'redo', 'imageUpload'
                    ],
                    image: {
                        toolbar: [
                            'imageTextAlternative',
                            'imageStyle:inline',
                            'imageStyle:block',
                            'imageStyle:side'
                        ],
                        upload: {
                            types: ['jpeg', 'png', 'jpg', 'gif', 'webp']
                        }
                    }
                })
                .then(editor => {
                    editorInstance = editor; // Save the instance for later
                })
                .catch(error => {
                    console.error('There was a problem initializing the editor.', error);
                });

            // end ck editor

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
                    title: {
                        required: true,
                    },
                    content: {
                        required: true, // Content is required
                    },
                    image: {
                        required: true, // Image is required
                    },
                },
                messages: {
                    title: {
                        required: "Service name is required", // Message for name
                    },
                    content: {
                        required: "Content is required", // Message for content
                    },
                    image: {
                        required: "Image is required", // Message for image
                    },
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
                            const newRow = `<tr data-id='${response.data.id}'>
                                            <td>${response.data.title}</td>
                                            <td><div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Edit</a></li>
                                                            <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Delete</a></li>
                                                        </ul>
                                                    </div></td>
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
                    title: {
                        required: true,
                    },
                    content: {
                        required: true, // Content is required
                    },
                    // image: {
                    //     required: true, // Image is required
                    // },
                },
                messages: {
                    title: {
                        required: "Service name is required", // Message for name
                    },
                    content: {
                        required: "Content is required", // Message for content
                    },
                    // image: {
                    //     required: "Image is required", // Message for image
                    // },
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
                            <td>${response.data.title}</td>
                            <td><div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Edit</a></li>
                                                            <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Delete</a></li>
                                                        </ul>
                                                    </div></td>
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
                const udpateUrl = `{{ route('blogs.update', '') }}/${id}`;
                $('#edit_form').attr('action', udpateUrl);

                $.ajax({
                    url: `{{ route('blogs.get') }}`,
                    method: 'POST',
                    data: {
                        'id': id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Send CSRF token via header
                    },
                    success: function(response) {
                        console.log(response);
                        $('#edit_form').find('[name="title"]').val(response.data.title);
                        $('#edit_form').find('[name="slug"]').val(response.data.slug);

                        $('#edit_form').find('[name="description"]').val(response.data
                            .description);


                        //CKEditor
                        if (editorInstance) {
                            editorInstance.setData(response.data.content); // Set new content
                        }

                        // tinymce.get('content').setContent(response.data
                        //     .content); // Set content for the content editor
                        if (response.data.featured) {
                            $('#edit_form').find('[name="featured"]').prop('checked', true);
                        } else {
                            $('#edit_form').find('[name="featured"]').prop('checked', false);
                        }

                        // meta details
                        $('#edit_form').find('[name="meta_title"]').val(response.data
                            .meta_title);

                        $('#edit_form').find('[name="meta_description"]').val(response.data
                            .meta_description);
                        $('#edit_form').find('[name="meta_keywords"]').val(response.data
                            .meta_keywords);

                        // Clear existing selections
                        $('#edit_form').find('[name="tags[]"]').val(null).trigger(
                            'change');

                        // // Handle categories
                        var selectedTags = response.data.tags.map(tag => tag.id);
                        // Set the selected categories in the select2 dropdown
                        $('#edit_form').find('[name="tags[]"]').val(selectedTags)
                            .trigger('change'); // Trigger change for select2

                        $('#editModal .select2').select2({
                            dropdownParent: '#editModal',
                            minimumResultsForSearch: 8,
                        });
                        $('#editModal').modal('toggle');
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
            });

            $(document).on('click', '.delete', function() {
                const id = $(this).data('id');
                const deleteUrl = `{{ route('blogs.destroy', '') }}/${id}`;
                const row = $(this).closest('tr'); // Get the closest table row

                handleDelete(deleteUrl, items_table, row);
            });

            // Function to handle deletion
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


    <script>
        $(document).ready(function() {
            // Slug auto-generation for Add Modal
            var $addTitleInput = $("#addNewModal input[name='title']");
            var $addSlugInput = $("#addNewModal input[name='slug']");

            function generateSlug(text) {
                return text
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // replace spaces with -
                    .replace(/-+/g, '-'); // collapse multiple -
            }

            $addTitleInput.on("input", function() {
                var slug = generateSlug($addTitleInput.val());
                $addSlugInput.val(slug);
            });

            // Slug auto-generation for Edit Modal
            var $editTitleInput = $("#editModal input[name='title']");
            var $editSlugInput = $("#editModal input[name='slug']");

            $editTitleInput.on("input", function() {
                var slug = generateSlug($editTitleInput.val());
                $editSlugInput.val(slug);
            });
        });
    </script>
@endsection