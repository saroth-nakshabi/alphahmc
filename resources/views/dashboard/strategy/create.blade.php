@extends('dashboard/layout')

@section('custom_css')
    <!-- --------------------------------------------------- -->
    <!-- Prism Js -->
    <!-- --------------------------------------------------- -->
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 46px;
            max-height: 70px;
            padding: .4rem .75rem;
            overflow-y: auto;
        }
        .select2-container--default .select2-selection--rendered {
            display: flex;
            flex-wrap: wrap;
            gap: .25rem;
            max-height: 70px;
            overflow-y: auto;
        }
        .select2-container--default .select2-search--inline {
            width: 100% !important;
        }
        .select2-container .select2-dropdown {
            z-index: 99999;
        }
    </style>
@endsection

@section('content')
   <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">
                        <i class="ti ti-plus me-2"></i>Create New Strategy
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="ti ti-home"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('strategy.index') }}">Strategies</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Create Strategy
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('public/dashboard/dist/images/breadcrumb/ChatBc.png') }}"
                            alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section>
        <!-- File export -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('strategy.store') }}" method="POST" id="add_form">
                            @csrf
                            <div class="border border-primary rounded p-3 mb-4">
                                <h5 class="mb-3">Strategy Details</h5>
                                <div class="row pt-3">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="control-label mb-1">Strategy Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Enter Strategy Name" required />
                                        </div>
                                    </div>
                                </div>

                               <div class="mb-3 pt-8">
                                    <label class="control-label pt-3">Strategy Description <span class="text-danger">*</span></label>
                                    <textarea name="description" rows="5" class="rich-textarea form-control" placeholder="Strategy Description" required></textarea>
                                </div>

                                <div class="row pt-3">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label mb-1">Strategy Image <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="image" class="form-control" required />
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                Create
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <!-- ---------------------------------------------- -->
    <!-- core files -->
    <!-- ---------------------------------------------- -->
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>

    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->
    <script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
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
                images_upload_url: '/upload-image', // Laravel backend route for image uploads

                // Custom file picker for images
                file_picker_types: 'image',
                file_picker_callback: function (callback, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function () {
                        var file = this.files[0];
                        var reader = new FileReader();

                        reader.onload = function () {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            // Call the callback with the image URL
                            callback(blobInfo.blobUri(), {
                                title: file.name
                            });
                        };

                        reader.readAsDataURL(file);
                    };

                    input.click();
                },

                // Image Upload Handler
                images_upload_handler: function (blobInfo, success, failure) {
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    // Use fetch to send the file to your backend
                    fetch('/upload-image', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(
                                    'Network response was not ok'); // Handle network errors
                            }
                            return response.json(); // Ensure that we get a JSON response
                        })
                        .then(json => {
                            if (json.location) {
                                success(json.location); // Pass the image URL back to TinyMCE
                            } else {
                                failure(
                                    'Invalid JSON response: No location field found'
                                ); // Handle missing 'location'
                            }
                        })
                        .catch(error => {
                            failure('Image upload failed: ' + error
                                .message); // Handle errors and display message
                        });
                }
            });

            // select2 init
            $('.select2').select2({
                minimumResultsForSearch: 8,
            });

            // add form handle
            $("#add_form").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    'description': {
                        required: true,
                    },
                    // image: {
                    //     required: true,
                    // }
                },

                submitHandler: function (form) {
                    // Sync TinyMCE content back to textareas before serializing
                    if (typeof tinymce !== 'undefined') {
                        tinymce.triggerSave();
                    }
                    // Collect the form data
                    let formData = new FormData(form);
                    // Send AJAX request
                    $.ajax({
                        url: form.action,
                        method: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        },
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we add',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (response) {
                            
                            Swal.close();                            
                            Toast.fire({
                                icon: 'success',
                                title: `${response.message}`
                            });

                          
                            $('#add_form')[0].reset();
                            location.href = '{{ route('strategy.index') }}';
                        },
                        error: function (xhr) {                          
                            Swal.close();                           
                            let errorMessages = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function (key, value) {
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
                },
                errorPlacement: function (error, element) {
                    if (element.is("select")) {
                        error.insertAfter(element.next());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            let faqIndex = 1;
            let magazineIndex = 1;
            let coreServiceIndex = 1;
            let processIndex = 1;

            function initEditor(selector) {
                tinymce.init({
                    selector: selector,
                    plugins: 'code searchreplace autolink directionality visualblocks visualchars image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
                    toolbar: "code undo redo print spellcheckdialog | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link image | alignleft aligncenter alignright alignjustify | code",
                    image_title: true,
                    automatic_uploads: true,
                    images_upload_url: '/upload-image',
                    menubar: false,
                    branding: false,
                    height: 250
                });
            }

            function updateSectionButtons(containerSelector, removeBtnSelector) {
                const sections = $(`${containerSelector} > div`);
                sections.find(removeBtnSelector).addClass('d-none');
                if (sections.length > 1) {
                    sections.find(removeBtnSelector).removeClass('d-none');
                }
            }
    </script>

@endsection
