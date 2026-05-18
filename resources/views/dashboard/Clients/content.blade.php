@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">About Us Content</h4>
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

<section class="datatables">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex">
                        <h5 class="mb-0">Content Section</h5>
                        <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            <i class="ti ti-plus me-1"></i> Add New
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($about_us) && count($about_us) > 0)
                                    @foreach($about_us as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>{{ $item->content_title}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown"
                                                            data-bs-auto-close="true" aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @can('edit tags')
                                                            <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                   data-id="{{ $item->id }}">Edit</a></li>
                                                        @endcan
                                                        @can('delete tags')
                                                            <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                   data-id="{{ $item->id }}">Delete</a></li>
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
                                    <th>Title</th>
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

<!-- Add Modal -->
<div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <form class="modal-content" action="{{ route('about_us.content.store') }}" method="POST" id="add_form"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Add New About Us Content Section</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">About Content Title <span class="text-danger">*</span></label>
                            <input type="text" id="content_title" name="content_title" class="form-control"
                                   placeholder="content Title" required />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">About Content <span class="text-danger">*</span></label>
                            <textarea id="content" name="content_text" rows="5" class="rich-textarea form-control"
                                      placeholder="Type here..." required></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Content Image <!--<span class="text-danger">*</span>--></label>
                            <input type="file" name="image" class="form-control" />
                        </div>
                    </div>

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
                <h4 class="modal-title">Edit About Content Section</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Content Title <span class="text-danger">*</span></label>
                            <input type="text" id="edit_content_title" name="content_title" class="form-control" placeholder="content Title" required />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Content <span class="text-danger">*</span></label>
                            <textarea id="edit_content" name="content_text" rows="5" class="rich-textarea form-control"
                                      placeholder="Type here..."></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">content Image</label>
                            <input type="file" name="image" class="form-control" />
                        </div>
                    </div>

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
    var items_table = $("#items-table").DataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
    });
    $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");

    tinymce.init({
        selector: '.rich-textarea',
        plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
        toolbar: "code undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/uploads/tinymce-image',
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
                beforeSend: function() { Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen:()=>Swal.showLoading()}); },
                success: function(response) {
                    Swal.close();
                    Toast.fire({icon:'success', title:'Successfully added'});
                    const newRow = `<tr data-id='${response.data.id}'>
                        <td>${response.data.content_title}</td>
                        <td><div class="btn-group">
                            <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="true"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}">Edit</a></li>
                                <li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${response.data.id}">Delete</a></li>
                            </ul>
                        </div></td></tr>`;
                    items_table.row.add($(newRow)).draw();
                    $('#addNewModal').modal('hide');
                    $('#add_form')[0].reset();
                },
                error: function(xhr) {
                    Swal.close();
                    let errors = xhr.responseJSON?.errors || {'error':'Something went wrong'};
                    let html = Object.values(errors).map(e=>`<p class='text-danger'>${e}</p>`).join('');
                    Swal.fire({icon:'error', title:'Failed to add', html});
                }
            });
        }
    });

    // Edit Form
    $(document).on('click', '.edit', function() {
        const id = $(this).data('id');
        $('#edit_form').attr('action', `{{ route('about_us.content.update', '') }}/${id}`);
        $.ajax({
            url: `{{ route('about_us.content.get') }}`,
            method: 'POST',
            data: { id },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                $('#edit_form #edit_content_title').val(response.data.content_title);
                tinymce.get('edit_content').setContent(response.data.content);
                $('#editModal').modal('show');
            },
            error: function() { Swal.fire('Error', 'Failed to fetch data', 'error'); }
        });
    });

    $("#edit_form").validate({
        submitHandler: function(form) {
            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: form.method,
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                beforeSend: function() { Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen:()=>Swal.showLoading()}); },
                success: function(response) {
                    Swal.close();
                    Toast.fire({icon:'success', title:'Successfully updated'});
                    let row = $(`#items-table tr[data-id='${response.data.id}']`);
                    row.html(`<td>${response.data.content_title}</td>
                        <td><div class="btn-group">
                            <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="true"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${response.data.id}">Edit</a></li>
                                <li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${response.data.id}">Delete</a></li>
                            </ul>
                        </div></td>`);
                    $('#editModal').modal('hide');
                },
                error: function(xhr) {
                    Swal.close();
                    let errors = xhr.responseJSON?.errors || {'error':'Something went wrong'};
                    let html = Object.values(errors).map(e=>`<p class='text-danger'>${e}</p>`).join('');
                    Swal.fire({icon:'error', title:'Failed to update', html});
                }
            });
        }
    });

    // Delete
    $(document).on('click', '.delete', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
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
                    url: `{{ route('about_us.content.destroy', '') }}/${id}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function() { Swal.fire({title:'Deleting...', allowOutsideClick:false, didOpen:()=>Swal.showLoading()}); },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({icon:'success', title:response.message});
                        items_table.row(row).remove().draw();
                    },
                    error: function() { Swal.close(); Swal.fire('Error', 'Failed to delete', 'error'); }
                });
            }
        });
    });

});
</script>
@endsection
