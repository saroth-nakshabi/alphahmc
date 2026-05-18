@extends('dashboard/layout')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">About Quote</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="./index.html">Dashboard</a></li>
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

<section class="datatables mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex">
                        <h5 class="mb-0">About Quote Section</h5>
                        <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            <i class="ti ti-plus me-1"></i> Add New
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Company</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($about_quotes) && count($about_quotes) > 0)
                                    @foreach($about_quotes as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>{{ $item->quote_title }}</td>
                                            <td>{{ $item->company_name }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown"
                                                            data-bs-auto-close="true" aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item edit" href="javascript:void(0);"
                                                               data-id="{{ $item->id }}">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item delete" href="javascript:void(0);"
                                                               data-id="{{ $item->id }}">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
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

<!-- Add Modal -->
<div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <form class="modal-content" action="{{ route('about_quote.store') }}" method="POST" id="add_form"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Add New About Quote</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote Title<span class="text-danger">*</span></label>
                            <input type="text" name="quote_title" class="form-control" placeholder="Quote Title" />
                        </div>
                    </div>

                    <!--This for future use-->
                    {{-- <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote Subtitle</label>
                            <input type="text" name="quote_sub_title" class="form-control" placeholder="Quote Subtitle" />
                        </div>
                    </div> --}}

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote<span class="text-danger">*</span></label>
                            <textarea name="quote" rows="5" class="rich-textarea form-control" placeholder="Type quote here..."></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Company Name<span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" placeholder="Company Name" />
                        </div>
                    </div>

                    <!--This for future use-->
                    {{-- <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote Image</label>
                            <input type="file" name="image" class="form-control" />
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <form class="modal-content" action="" method="POST" id="edit_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Edit About Quote</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote Title<span class="text-danger">*</span></label>
                            <input type="text" id="edit_title" name="quote_title" class="form-control" />
                        </div>
                    </div>

                    <!--This for future use-->
                    {{-- <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote Subtitle</label>
                            <input type="text" id="edit_subtitle" name="quote_sub_title" class="form-control" />
                        </div>
                    </div> --}}

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote<span class="text-danger">*</span></label>
                            <textarea id="edit_quote" name="quote" rows="5" class="rich-textarea form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Company Name<span class="text-danger">*</span></label>
                            <input type="text" id="edit_company_name" name="company_name" class="form-control" />
                        </div>
                    </div>

                    <!--This for future use-->
                    {{-- <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Quote Image</label>
                            <input type="file" name="image" class="form-control" />
                        </div>
                    </div> --}}

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>

<script>
$(document).ready(function() {
    var items_table = $("#items-table").DataTable();

    tinymce.init({
        selector: '.rich-textarea',
        plugins: 'code link lists table',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | code',
        image_title: true,
        automatic_uploads: true
    });

    // Add Form
    $("#add_form").validate({
        submitHandler: function(form) {
            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: form.method,
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    const newRow = `<tr data-id='${response.data.id}'>
                        <td>${response.data.quote_title}</td>
                        <td>${response.data.company_name}</td>
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
                    form.reset();
                    tinymce.get('quote')?.setContent('');
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors || {'error':'Something went wrong'};
                    Swal.fire({icon:'error', title:'Failed', html:Object.values(errors).map(e=>`<p>${e}</p>`).join('')});
                }
            });
        }
    });

    // Edit Button
    $(document).on('click', '.edit', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ route("about_quote.get") }}',
            method: 'POST',
            data: { id },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                $('#edit_form').attr('action', '{{ route("about_quote.update","") }}/'+id);
                $('#edit_title').val(response.data.quote_title);
                // $('#edit_subtitle').val(response.data.subtitle);
                $('#edit_quote').val(response.data.About_quote);
                $('#edit_company_name').val(response.data.company_name);

                try {
                    tinymce.get('edit_quote')?.setContent(response.data.About_quote);
                } catch(e){ console.log(e); }

                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire({icon:'error', title:'Error', text:'Failed to fetch data!'});
            }
        });
    });

    // Edit Form
    $("#edit_form").validate({
        submitHandler: function(form) {
            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    const row = $(`#items-table tr[data-id='${response.data.id}']`);
                    row.find('td:eq(0)').text(response.data.quote_title);
                    row.find('td:eq(1)').text(response.data.company_name);
                    $('#editModal').modal('hide');
                }
            });
        }
    });

    // Delete
    $(document).on('click', '.delete', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        if(confirm('Are you sure you want to delete this?')) {
            $.ajax({
                url: '{{ route("about_quote.destroy","") }}/'+id,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    items_table.row(row).remove().draw();
                }
            });
        }
    });
});
</script>
@endsection
