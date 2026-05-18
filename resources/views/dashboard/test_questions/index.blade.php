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
                    <h4 class="fw-semibold mb-8">All Test Question</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Course</li>
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
                            <h5 class="mb-0">Test Question List</h5>
                            @can('create test questions')
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
                                        <th>Question</th>
                                        <th>Course</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @if (isset($test_questions) && count($test_questions) > 0)
                                        @foreach ($test_questions as $test_question)
                                            <!-- start row -->
                                            <tr data-id="{{ $test_question->id }}">
                                                <td>{{ $test_question->question }}</td>
                                                <td>{{ $test_question->service->name }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @can('edit test questions')
                                                                <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                        data-id="{{ $test_question->id }}">Edit</a></li>
                                                            @endcan
                                                            @can('delete test questions')
                                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                        data-id="{{ $test_question->id }}">Delete</a></li>
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
            <form class="modal-content" action="{{ route('test_questions.store') }}" method="POST" id="add_form">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Add New Question
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="">
                                <label class="control-label mb-1">Service<span class="text-danger">*</span></label>
                                <select name="service_id" class="form-control select2" data-placeholder="Select Course"
                                    required>
                                    <option></option>
                                    @if (isset($services) && count($services) > 0)
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3">
                                <label class="control-label mb-1">Question <span class="text-danger">*</span></label>
                                <textarea type="text" id="name" name="question" class="form-control" placeholder="question" required></textarea>
                            </div>
                        </div>
                        <hr class="my-4">
                        {{-- answer inputs --}}
                        <div class="col-12" id="answers">
                            <div class="answer mb-2">
                                <div class="mb-1">
                                    <label class="control-label mb-1">Answer<span class="text-danger">*</span></label>
                                    <textarea name="answer[]" id="answer_0" class="form-control" placeholder="type answer"></textarea>
                                    {{-- is correct answer --}}
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_correct[0]"
                                            id="is_correct_0">
                                        <label class="form-check-label" for="is_correct_0">
                                            Is Correct Answer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- add answer button --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary btn-sm" id="add_answer">Add Answer</button>
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
                        Edit question
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Service<span class="text-danger">*</span></label>
                                <select name="service_id" class="form-control select2" data-placeholder="Select Course"
                                    required>
                                    <option></option>
                                    @if (isset($services) && count($services) > 0)
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Question <span class="text-danger">*</span></label>
                                <textarea type="text" id="question" name="question" class="form-control" placeholder="question" required></textarea>
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

            // handle add answer button
            let answer_count = 0;
            $('#add_answer').on('click', function() {
                answer_count++;
                const answer = `
                <div class="answer mb-2">
                    <div class="mb-1">
                        <label class="control-label mb-1">Answer <span class="text-danger">*</span></label>
                        <textarea name="answer[${answer_count}]" id="answer_${answer_count}" class="form-control" placeholder="type answer"></textarea>
                    </div>
                    <div class='d-flex justify-content-between'>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_correct[${answer_count}]"
                                            id="is_correct_${answer_count}">
                        <label class="form-check-label" for="is_correct_${answer_count}">
                        Is Correct Answer</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove_answer">Remove Answer</button>
                    </div>
                </div>
                `;
                $('#answers').append(answer);

                // apply the required rule validation to the newly added answer input element
                $(`#answer_${answer_count}`).rules('add', {
                    required: true,
                    messages: {
                        required: "Answer is required"
                    }
                });
                // $('#add_form').validate().element(`#answer_${answer_count}`);

            });

            // handle remove answer button
            $(document).on('click', '.remove_answer', function() {
                $(this).closest('.answer').remove();
            });

            // add form handle
            $("#add_form").validate({
                rules: {
                    service_id: {
                        required: true,
                    },
                    question: {
                        required: true,
                    },
                    'answer[]': {
                        required: true,
                    }
                },
                submitHandler: function(form) {
                    // Collect the form data
                    let formData = new FormData(form);

                    // append the answers to the form data
                    $('.answer').each(function(index) {
                        const answer = $(this).find('textarea').val();
                        const is_correct = $(this).find('input[type="checkbox"]').is(
                        ':checked');
                        formData.append(`answers[${index}][answer]`, answer);
                        formData.append(`answers[${index}][is_correct]`, is_correct);
                    });

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
                                            <td>${response.data.question}</td>
                                            <td>${response.data.service}</td>
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
                    name: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "test_question name is required",
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
                            <td>${response.data.question}</td>
                            <td>${response.data.service}</td>
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
                const udpateUrl = `{{ route('test_questions.update', '') }}/${id}`;
                $('#edit_form').attr('action', udpateUrl);

                $.ajax({
                    url: `{{ route('test_questions.get') }}`,
                    method: 'POST',
                    data: {
                        'id': id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Send CSRF token via header
                    },
                    success: function(response) {
                        $('#edit_form').find('#question').val(response.data.question);
                        $('#edit_form .select2').val(response.data.service_id).trigger('change');
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
                const deleteUrl = `{{ route('test_questions.destroy', '') }}/${id}`;
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
