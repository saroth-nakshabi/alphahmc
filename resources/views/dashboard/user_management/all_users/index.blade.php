@extends('dashboard/layout')

@section('custom_css')
    <!-- --------------------------------------------------- -->
    <!-- Prism Js -->
    <!-- --------------------------------------------------- -->
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
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
                    <h4 class="fw-semibold mb-8">All Users</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Users</a></li>
                            <li class="breadcrumb-item" aria-current="page">Users</li>
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
                            <h5 class="mb-0">Users List</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @if (isset($users) && count($users) > 0)
                                        @foreach ($users as $user)
                                            <!-- start row -->
                                            <tr data-id="{{ $user->id }}">
                                                <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>
                                                    {{ $user->getRoleNames()->first() ?? 'No role assigned' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('all_users.permissions', $user->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-shield-lock me-1"></i> Manage Access
                                                    </a>
                                                </td>
                                            </tr>
                                            <!-- end row -->
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
    <!-- int tele input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"
        integrity="sha512-+gShyB8GWoOiXNwOlBaYXdLTiZt10Iy6xjACGadpqMs20aJOoh+PJt3bwUVA6Cefe7yF7vblX6QwyXZiVwTWGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            var items_table = $("#items-table").DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");


            // const phoneInputField_add = document.querySelector("#phone_add");
            // const iti_add = window.intlTelInput(phoneInputField_add, {
            //     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            //     initialCountry: "AE",
            //     preferredCountries: ['AE'],
            //     hiddenInput: "phone",
            // });

            // jQuery.validator.addMethod("phoneNumValidationAdd", function(value, element) {
            //     return this.optional(element) || iti_add.isValidNumber();
            // }, 'Please enter a valid number');

            // const phoneInputField_edit = document.querySelector("#phone_edit");
            // const iti_edit = window.intlTelInput(phoneInputField_edit, {
            //     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            //     initialCountry: "AE",
            //     preferredCountries: ['AE'],
            //     hiddenInput: "phone",
            // });

            // jQuery.validator.addMethod("phoneNumValidationEdit", function(value, element) {
            //     return this.optional(element) || iti_edit.isValidNumber();
            // }, 'Please enter a valid number');

            // // add form handle
            // $("#add_form").validate({
            //     rules: {
            //         first_name: {
            //             required: true,
            //         },
            //         last_name: {
            //             required: true,
            //         },
            //         email: {
            //             required: true,
            //         },
            //         phone_add: {
            //             required: true,
            //             phoneNumValidationAdd: true
            //         },
            //         password: {
            //             required: true,
            //         },
            //         confirm_password: {
            //             required: true,
            //             equalTo: '#password'
            //         },
            //     },
            //     messages: {

            //     },
            //     submitHandler: function(form) {
            //         // Collect the form data
            //         let formData = new FormData(form);
            //         // Send AJAX request
            //         $.ajax({
            //             url: form.action, // Replace with your server-side endpoint
            //             method: form.method,
            //             data: formData,
            //             processData: false, // Prevent jQuery from automatically processing the data
            //             contentType: false, // Let the browser set the content type (required for FormData)
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
            //                     'content') // Send CSRF token via header
            //             },
            //             beforeSend: function() {
            //                 // Show loading animation using SweetAlert
            //                 Swal.fire({
            //                     title: 'Processing...',
            //                     text: 'Please wait while we add',
            //                     allowOutsideClick: false,
            //                     didOpen: () => {
            //                         Swal.showLoading();
            //                     }
            //                 });
            //             },
            //             success: function(response) {
            //                 // Close the loading Swal
            //                 Swal.close();

            //                 // Show success message using SweetAlert mixin
            //                 Toast.fire({
            //                     icon: 'success',
            //                     title: `${response.message}`
            //                 });
            //                 const newRow = `<tr data-id='${response.data.id}'>
            //                                 <td>${response.data.user.first_name} ${response.data.user.last_name}</td>
            //                                 <td>${response.data.user.email}</td>
            //                                 <td>${response.data.user.phone}</td>
            //                                 <td><div class="btn-group">
            //                                             <button class="dropdown-toggle btn btn-primary btn-sm"
            //                                                 data-bs-toggle="dropdown" data-bs-auto-close="true"
            //                                                 aria-expanded="false">
            //                                                 <i class="bi bi-three-dots"></i>
            //                                             </button>
            //                                             <ul class="dropdown-menu">
            //                                                 <li><a class="dropdown-item edit" href="javascript:void(0);"
            //                                                         data-id="${response.data.id}">Edit</a></li>
            //                                                 <li><a class="dropdown-item delete" href="javascript:void(0);"
            //                                                         data-id="${response.data.id}">Delete</a></li>
            //                                             </ul>
            //                                         </div></td>
            //                                 </tr>`;
            //                 // Add new row to DataTable
            //                 items_table.row.add($(newRow)).draw();

            //                 // Close the modal
            //                 $('#addNewModal').modal(
            //                     'hide'); // Replace #addNewModal with your modal ID

            //                 // Optionally, clear the form
            //                 $('#add_form')[0].reset();
            //             },
            //             error: function(xhr) {
            //                 // Close the loading Swal
            //                 Swal.close();

            //                 // Extract and display validation errors from the server
            //                 let errorMessages = '';
            //                 if (xhr.responseJSON && xhr.responseJSON.errors) {
            //                     $.each(xhr.responseJSON.errors, function(key, value) {
            //                         errorMessages +=
            //                             `<p class='text-danger'>${value}</p>`;
            //                     });
            //                 } else {
            //                     errorMessages = 'Something went wrong. Please try again.';
            //                 }

            //                 // Show error message with custom SweetAlert template
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Failed to add',
            //                     html: `<div>${errorMessages}</div>`, // Display multiple error messages
            //                     customClass: {
            //                         popup: 'swal-wide'
            //                     }
            //                 });
            //             }
            //         });
            //     },
            //     errorPlacement: function(error, element) {
            //         if (element.is(".phone")) {
            //             error.insertAfter(element.parent());
            //         } else if (element.is("select")) {
            //             error.insertAfter(element.parent());
            //         } else {
            //             error.insertAfter(element);
            //         }
            //     }
            // });

            // // add form handle
            // $("#edit_form").validate({
            //     rules: {
            //         first_name: {
            //             required: true,
            //         },
            //         last_name: {
            //             required: true,
            //         },
            //         email: {
            //             required: true,
            //         },
            //         phone_edit: {
            //             required: true,
            //             phoneNumValidationEdit: true
            //         },
            //     },
            //     messages: {
            //         name: {
            //             required: "Facility name is required",
            //         }
            //     },
            //     submitHandler: function(form) {
            //         // Collect the form data
            //         let formData = new FormData(form);
            //         // Send AJAX request
            //         $.ajax({
            //             url: form.action, // Replace with your server-side endpoint
            //             method: form.method,
            //             data: formData,
            //             processData: false, // Prevent jQuery from automatically processing the data
            //             contentType: false, // Let the browser set the content type (required for FormData)
            //             headers: {
            //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
            //                     'content') // Send CSRF token via header
            //             },
            //             beforeSend: function() {
            //                 // Show loading animation using SweetAlert
            //                 Swal.fire({
            //                     title: 'Processing...',
            //                     text: 'Please wait while we add',
            //                     allowOutsideClick: false,
            //                     didOpen: () => {
            //                         Swal.showLoading();
            //                     }
            //                 });
            //             },
            //             success: function(response) {
            //                 // Close the loading Swal
            //                 Swal.close();

            //                 // Show success message using SweetAlert mixin
            //                 Toast.fire({
            //                     icon: 'success',
            //                     title: `${response.message}`
            //                 });

            //                 let row = $('#items-table').find(
            //                     `tr[data-id='${response.data.id}']`);
            //                 row.html(`
            //                  <td>${response.data.user.first_name} ${response.data.user.last_name}</td>
            //                                 <td>${response.data.user.email}</td>
            //                                 <td>${response.data.user.phone}</td>
            //                 <td><div class="btn-group">
            //                                             <button class="dropdown-toggle btn btn-primary btn-sm"
            //                                                 data-bs-toggle="dropdown" data-bs-auto-close="true"
            //                                                 aria-expanded="false">
            //                                                 <i class="bi bi-three-dots"></i>
            //                                             </button>
            //                                             <ul class="dropdown-menu">
            //                                                 <li><a class="dropdown-item edit" href="javascript:void(0);"
            //                                                         data-id="${response.data.id}">Edit</a></li>
            //                                                 <li><a class="dropdown-item delete" href="javascript:void(0);"
            //                                                         data-id="${response.data.id}">Delete</a></li>
            //                                             </ul>
            //                                         </div></td>
            //                 `);
            //                 // destroy the table to reinitialize
            //                 items_table.destroy();
            //                 // re initialize the table
            //                 items_table = $("#items-table").DataTable({
            //                     dom: "Bfrtip",
            //                     buttons: ["copy", "csv", "excel", "pdf", "print"],
            //                 });
            //                 $(
            //                     ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            //                 ).addClass("btn btn-primary mr-1");

            //                 // Close the modal
            //                 $('#editModal').modal('toggle');
            //             },
            //             error: function(xhr) {
            //                 // Close the loading Swal
            //                 Swal.close();

            //                 // Extract and display validation errors from the server
            //                 let errorMessages = '';
            //                 if (xhr.responseJSON && xhr.responseJSON.errors) {
            //                     $.each(xhr.responseJSON.errors, function(key, value) {
            //                         errorMessages +=
            //                             `<p class='text-danger'>${value}</p>`;
            //                     });
            //                 } else {
            //                     errorMessages = 'Something went wrong. Please try again.';
            //                 }

            //                 // Show error message with custom SweetAlert template
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Failed to add',
            //                     html: `<div>${errorMessages}</div>`, // Display multiple error messages
            //                     customClass: {
            //                         popup: 'swal-wide'
            //                     }
            //                 });
            //             }
            //         });
            //     },
            //     errorPlacement: function(error, element) {
            //         if (element.is(".phone")) {
            //             error.insertAfter(element.parent());
            //         } else if (element.is("select")) {
            //             error.insertAfter(element.parent());
            //         } else {
            //             error.insertAfter(element);
            //         }
            //     }
            // });

            // $(document).on('click', '.edit', function() {
            //     const id = $(this).data('id');
            //     const udpateUrl = `#`;
            //     $('#edit_form').attr('action', udpateUrl);

            //     $.ajax({
            //         url: `#`,
            //         method: 'POST',
            //         data: {
            //             'id': id,
            //         },
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
            //                 'content') // Send CSRF token via header
            //         },
            //         success: function(response) {
            //             $('#edit_form').find('[name="first_name"]').val(response.data.user
            //                 .first_name);
            //             $('#edit_form').find('[name="last_name"]').val(response.data.user
            //                 .last_name);
            //             $('#edit_form').find('[name="email"]').val(response.data.user.email);
            //             $('#edit_form').find('[name="phone_edit"]').val(response.data.user
            //                 .phone);
            //             $('#editModal').modal('toggle');
            //         },
            //         error: function(xhr) {
            //             Swal.close();
            //             // Show error message
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Failed to delete',
            //                 text: 'An error occurred while trying to delete the item. Please try again.',
            //             });
            //         }
            //     });
            // });

            // $(document).on('click', '.delete', function() {
            //     const id = $(this).data('id');
            //     const deleteUrl = `#`;
            //     const row = $(this).closest('tr'); // Get the closest table row

            //     handleDelete(deleteUrl, items_table, row);
            // });

            // Function to handle deletion
            // function handleDelete(delete_url, table, row) {
            //     Swal.fire({
            //         title: 'Are you sure?',
            //         text: "You won't be able to recover this item!",
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Yes, delete it!',
            //         cancelButtonText: 'Cancel'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             // If confirmed, proceed with deletion
            //             $.ajax({
            //                 url: delete_url,
            //                 method: 'DELETE',
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
            //                         'content') // Send CSRF token via header
            //                 },
            //                 beforeSend: function() {
            //                     Swal.fire({
            //                         title: 'Deleting...',
            //                         text: 'Please wait while we delete the item',
            //                         allowOutsideClick: false,
            //                         didOpen: () => {
            //                             Swal.showLoading();
            //                         }
            //                     });
            //                 },
            //                 success: function(response) {
            //                     Swal.close();
            //                     // Show success message
            //                     Toast.fire({
            //                         icon: 'success',
            //                         title: `${response.message}`
            //                     });
            //                     // Remove the row from the DataTable
            //                     table.row(row).remove().draw();
            //                 },
            //                 error: function(xhr) {
            //                     Swal.close();
            //                     // Show error message
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Failed to delete',
            //                         text: 'An error occurred while trying to delete the item. Please try again.',
            //                     });
            //                 }
            //             });
            //         }
            //     });
            // }
        });
    </script>
@endsection
