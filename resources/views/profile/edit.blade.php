@extends('dashboard/layout')

@section('custom_css')
    <!-- int tele input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"
        integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Profile</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Profile</li>
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

    <section>
        <!-- File export -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @role('Student')
                            <div>
                                @include('profile.partials.student-information-form')
                            </div>
                        @endrole
                        <div class="my-5">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        <div>
                            @include('profile.partials.update-password-form')
                        </div>
                        {{-- <div>
                            @include('profile.partials.delete-user-form')
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <!-- int tele input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"
        integrity="sha512-+gShyB8GWoOiXNwOlBaYXdLTiZt10Iy6xjACGadpqMs20aJOoh+PJt3bwUVA6Cefe7yF7vblX6QwyXZiVwTWGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>


    <script>
        $(document).ready(function() {
            <?php if (empty($student->signature)): ?>
            let studentSignatureCanvas = document.getElementById('studentSignatureCanvas');
            let studentSignaturePad = new SignaturePad(studentSignatureCanvas);

            // Clear Signature Button
            $('#clearStudentSignature').click(function() {
                studentSignaturePad.clear();
                $("#student_signature").val(""); // Clear hidden input
            });

            // Update hidden input on form submit
            $('#student-info-form').on('submit', function() {
                if (!studentSignaturePad.isEmpty()) {
                    $('#student_sign').val(studentSignaturePad.toDataURL());
                    console.log('Signature added', studentSignaturePad.toDataURL());
                }
            });
            // Custom Validation Rule
            $.validator.addMethod("validateSignaturePad", function(value, element) {
                return !studentSignaturePad.isEmpty(); // Check if the signature pad is not empty
            }, "Please provide a signature.");

            <?php endif; ?>

            // select2 init
            $('.select2').select2({
                minimumResultsForSearch: 8,
            });

            const phoneInputField = document.querySelector("#phone");
            const iti = window.intlTelInput(phoneInputField, {
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                initialCountry: "AE",
                preferredCountries: ['AE'],
                hiddenInput: "phone",
            });

            jQuery.validator.addMethod("phoneNumValidation", function(value, element) {
                return this.optional(element) || iti.isValidNumber();
            }, 'Please enter a valid number');

            // select2 init
            $('#student-info-form .select2').select2({
                dropdownParent: '#student-info-form',
                minimumResultsForSearch: 8,
            });

            const sign_required = "{{ Auth::user()->hasRole('Student') ? 'required' : '' }}";

            // add form handle
            $("#add_form").validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    phone: {
                        required: true,
                        phoneNumValidationAdd: true
                    },
                    password: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Student name is required",
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
                            const newRow = `<tr data-id='${response.data.id}'>
                                            <td>${response.data.user.first_name} ${response.data.user.last_name}</td>
                                            <td>${response.data.user.email}</td>
                                            <td>${response.data.user.phone}</td>
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

            // Student info form handle
            $("#student-info-form").validate({
                ignore: [],
                rules: {
                    prefix: {
                        required: true
                    },
                    occupation: {
                        required: true
                    },
                    institution: {
                        required: true
                    },
                    signature: {
                        required: sign_required,
                    },
                },
                messages: {
                    prefix: {
                        required: "This field is required"
                    },
                    occupation: {
                        required: "This field is required"
                    },
                    institution: {
                        required: "This field is required"
                    },
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);

                    $.ajax({
                        url: form.action,
                        method: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Submitting...',
                                text: 'Please wait while we process your request.',
                                icon: 'info',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your information has been submitted successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#profileAlert').fadeOut();
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            let errorMessage = 'An error occurred. Please try again.';

                            if (xhr.status === 422) {
                                errorMessage = 'Please check the fields and try again.';
                            } else if (xhr.status === 500) {
                                errorMessage =
                                    'Internal Server Error. Please contact support.';
                            } else if (xhr.status === 403) {
                                errorMessage =
                                    'You are not authorized to perform this action.';
                            }

                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        },
                    });
                },
                errorPlacement: function(error, element) {
                    if (element.is("select")) {
                        error.insertAfter(element.parent());
                    } else if (element.is("input[type='checkbox']")) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });




        });
    </script>
@endsection
