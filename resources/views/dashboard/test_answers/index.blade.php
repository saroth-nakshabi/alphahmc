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
                    <h4 class="fw-semibold mb-8">All Test answer</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">question</li>
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
                            <h5 class="mb-0">Test answer List</h5>
                            @can('create test answers')
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
                                        <th>Answer</th>
                                        <th>Question</th>
                                        <th>is correct</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @if (isset($test_answers) && count($test_answers) > 0)
                                        @foreach ($test_answers as $test_answer)
                                            <!-- start row -->
                                            <tr data-id="{{ $test_answer->id }}">
                                                <td>{{ $test_answer->answer }}</td>
                                                <td>{{ $test_answer->test_question->question }}</td>
                                                <td>{{ $test_answer->is_correct ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @can('edit test answers')
                                                                <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                        data-id="{{ $test_answer->id }}">Edit</a></li>
                                                            @endcan
                                                            @can('delete test answers')
                                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                        data-id="{{ $test_answer->id }}">Delete</a></li>
                                                            @endcan
                                                        </ul>
                                                    </div>
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

    <!-- add new modal -->
    <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="{{ route('test_answers.store') }}" method="POST" id="add_form">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Add New answer
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Select Question<span class="text-danger">*</span></label>
                                <select name="question_id" class="form-control select2" data-placeholder="Select question"
                                    required>
                                    <option></option>
                                    @if (isset($questions) && count($questions) > 0)
                                        @foreach ($questions as $question)
                                            <option value="{{ $question->id }}">{{ $question->question }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3">
                                <label class="control-label mb-1">answer <span class="text-danger">*</span></label>
                                <textarea type="text" name="answer" class="form-control" placeholder="answer" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3">
                                <label class="control-label mb-1">is correct ?</label>
                                <div class="form-check">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Yes
                                    </label>
                                    <input class="form-check-input" type="checkbox" name="is_correct" value="1"
                                        id="flexCheckDefault">
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
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="#" method="POST" id="edit_form">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Edit answer
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Select Question<span
                                        class="text-danger">*</span></label>
                                <select name="question_id" class="form-control select2"
                                    data-placeholder="Select question" required>
                                    <option></option>
                                    @if (isset($questions) && count($questions) > 0)
                                        @foreach ($questions as $question)
                                            <option value="{{ $question->id }}">{{ $question->question }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3">
                                <label class="control-label mb-1">answer <span class="text-danger">*</span></label>
                                <textarea type="text" name="answer" class="form-control" placeholder="answer" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3">
                                <label class="control-label mb-1">is correct ?</label>
                                <div class="form-check">
                                    <label class="form-check-label" for="checkbox">
                                        Yes
                                    </label>
                                    <input class="form-check-input" type="checkbox" name="is_correct" value="1"
                                        id="checkbox">
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
    <!-- ---------------------------------------------- -->
    <!-- core files -->
    <!-- ---------------------------------------------- -->
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>

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
                    question_id: {
                        required: true,
                    },
                    answer: {
                        required: true,
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
                            console.log(response);

                            // Close the loading Swal
                            Swal.close();

                            // Show success message using SweetAlert mixin
                            Toast.fire({
                                icon: 'success',
                                title: `${response.message}`
                            });
                            const newRow = `<tr data-id='${response.data.id}'>
                                            <td>${response.data.answer}</td>
                                            <td>${response.data.question}</td>
                                            <td>${response.data.is_correct ? 'Yes' : 'No'}</td>
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
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }
            });

            // add form handle
            $("#edit_form").validate({
                rules: {
                    question_id: {
                        required: true,
                    },
                    answer: {
                        required: true,
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
                            <td>${response.data.answer}</td>
                            <td>${response.data.question}</td>
                            <td>${response.data.is_correct ? 'Yes' : 'No'}</td>
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
                const udpateUrl = `{{ route('test_answers.update', '') }}/${id}`;
                $('#edit_form').attr('action', udpateUrl);

                $.ajax({
                    url: `{{ route('test_answers.get') }}`,
                    method: 'POST',
                    data: {
                        'id': id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Send CSRF token via header
                    },
                    success: function(response) {
                        $('#edit_form textarea[name="answer"]').val(response.data.answer);
                        $('#edit_form .select2').val(response.data.test_question_id).trigger(
                            'change');
                        if (response.data.is_correct) {
                            $('#edit_form #checkbox').prop('checked', true);
                        }
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
                const deleteUrl = `{{ route('test_answers.destroy', '') }}/${id}`;
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
@endsection
