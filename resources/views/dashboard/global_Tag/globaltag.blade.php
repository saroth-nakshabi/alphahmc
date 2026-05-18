@extends('dashboard/layout')

@section('custom_css')
    <!-- --------------------------------------------------- -->
    <!-- Prism Js -->
    <!-- --------------------------------------------------- -->
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">

@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">All Global tags</h4>
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
                            <h5 class="mb-0">Global List</h5>

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
                                        <th>globaltag Name</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @if (isset($globaltags) && count($globaltags) > 0)
                                        @foreach ($globaltags as $global)
                                            <!-- start row -->
                                            <tr data-id="{{ $global->id }}">
                                                <td>{{ $global->globaltag_name }}</td>
                                                
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
                                                                        data-id="{{ $global->id }}">Edit</a></li>
                                                            @endcan
                                                            @can('delete tags')
                                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                        data-id="{{ $global->id }}">Delete</a></li>
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
                                        <th>globaltag Name</th>
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
            <form class="modal-content" action="{{ route('global.store') }}" method="POST" id="add_form"
                enctype="multipart/form-data">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Add globaltag
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                       
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">globaltag Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="globaltag Name" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Script Tags <span class="text-danger">*</span></label>
                                <textarea id="tags" name="tags" rows="10" class="form-control" required></textarea>
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
</div>
    <!-- edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" method="POST" id="edit_form" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h4 class="modal-title">Edit Global Tags</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>global Tag Name</label>
                            <input type="text" id="edit_name" name="name" class="form-control">
                        </div>

                        


                        <div class="col-12">
                            <label>Script tags</label>
                            <textarea id="edit_tags" name="tags" class="form-control" rows="10" required></textarea>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>

    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->

    <script>
    $(document).ready(function() {

        // -----------------------------------------------
        // DataTable init
        // -----------------------------------------------
        var items_table = $("#items-table").DataTable({
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
        });
        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
            .addClass("btn btn-primary mr-1");

        // -----------------------------------------------
        // CodeMirror init (replaces TinyMCE)
        // -----------------------------------------------
        var addEditor = CodeMirror.fromTextArea(document.getElementById('tags'), {
            mode: 'htmlmixed',
            theme: 'dracula',
            lineNumbers: true,
            lineWrapping: true,
            tabSize: 2,
        });

        var editEditor = CodeMirror.fromTextArea(document.getElementById('edit_tags'), {
    mode: 'htmlmixed',
    theme: 'dracula',
    lineNumbers: true,
    lineWrapping: true,
    tabSize: 2,
});

        // Refresh CodeMirror when modals open (fixes blank editor bug)
        $('#addNewModal').on('shown.bs.modal', function() {
            addEditor.refresh();
        });
        $('#editModal').on('shown.bs.modal', function() {
            editEditor.refresh();
        });

        // Sync CodeMirror back to textarea before submit
        $('#add_form').on('submit', function() {
            addEditor.save();
        });
        $('#edit_form').on('submit', function() {
            editEditor.save();
        });

        // -----------------------------------------------
        // Select2 init
        // -----------------------------------------------
        $('#addNewModal .select2').select2({
            dropdownParent: '#addNewModal',
            minimumResultsForSearch: 8,
        });
        $('#editModal .select2').select2({
            dropdownParent: '#editModal',
            minimumResultsForSearch: 8,
        });

        // -----------------------------------------------
        // Add form
        // -----------------------------------------------
        $("#add_form").validate({
            rules: {
                tags: { required: true }
            },
            messages: {
                tags: { required: "tags is required" }
            },
            submitHandler: function(form) {
                addEditor.save(); // sync CodeMirror to textarea
                let formData = new FormData(form);
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
                            title: 'Processing...',
                            text: 'Please wait while we add',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                    },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: `${response.message}` });

                        const newRow = `<tr data-id="${response.data.id}">
                            <td>${response.data.globaltag_name}</td>
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

                        items_table.row.add($(newRow)).draw();
                        $('#addNewModal').modal('hide');
                        $('#add_form')[0].reset();
                        addEditor.setValue(''); // clear CodeMirror
                    },
                    error: function(xhr) {
                        Swal.close();
                        let errorMessages = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessages += `<p class='text-danger'>${value}</p>`;
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

        // -----------------------------------------------
        // Edit form
        // -----------------------------------------------
        $("#edit_form").validate({
            submitHandler: function(form) {
                editEditor.save(); // sync CodeMirror to textarea
                let formData = new FormData(form);
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
                            title: 'Processing...',
                            text: 'Please wait while we update',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                    },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: `${response.message}` });

                        let row = $('#items-table').find(`tr[data-id='${response.data.id}']`);
                        row.html(`
                            <td>${response.data.globaltag_name}</td>
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

                        items_table.destroy();
                        items_table = $("#items-table").DataTable({
                            dom: "Bfrtip",
                            buttons: ["copy", "csv", "excel", "pdf", "print"],
                        });
                        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                            .addClass("btn btn-primary mr-1");

                        $('#editModal').modal('hide');
                    },
                    error: function(xhr) {
                        Swal.close();
                        let errorMessages = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessages += `<p class='text-danger'>${value}</p>`;
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

        // -----------------------------------------------
        // Edit button click - fetch data and populate
        // -----------------------------------------------
        $(document).on('click', '.edit', function() {
            const id = $(this).data('id');
            const updateUrl = `{{ route('global.update', '') }}/${id}`;

            $('#edit_form').attr('action', updateUrl);

            $.ajax({
                url: `{{ route('global.get') }}`,
                method: 'POST',
                data: { id: id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
    $('#edit_name').val(response.data.globaltag_name);

    $('#editModal').modal('show'); // show modal first

    // Set CodeMirror content after modal is visible
    setTimeout(() => {
        editEditor.setValue(response.data.tags ?? '');
        editEditor.refresh();
    }, 100);
},
                error: function(xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to fetch data' });
                }
            });
        });

        // -----------------------------------------------
        // Delete button click
        // -----------------------------------------------
        $(document).on('click', '.delete', function() {
            const id = $(this).data('id');
            const deleteUrl = `{{ route('global.destroy', '') }}/${id}`;
            const row = $(this).closest('tr');
            handleDelete(deleteUrl, items_table, row);
        });

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
                        url: delete_url,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Deleting...',
                                text: 'Please wait while we delete the item',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            Toast.fire({ icon: 'success', title: `${response.message}` });
                            table.row(row).remove().draw();
                        },
                        error: function(xhr) {
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
