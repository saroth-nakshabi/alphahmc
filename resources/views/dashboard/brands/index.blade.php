@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Brand Portfolio</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Our Brands</li>
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

{{-- Hero Image Management --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Brands Page Hero Image</h5>
                <form action="{{ route('dashboard.brands.updateHero') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Hero Image</label>
                                <div class="hero-preview mb-2">
                                    @if(isset($brandHero) && $brandHero->image)
                                        <img src="{{ asset('public/uploads/brands/' . $brandHero->image) }}" alt="Hero" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    @else
                                        <div class="p-4 bg-light text-center rounded border">
                                            <span class="text-muted">No hero image set. Default will be used.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hero_image" class="form-label">Upload New Hero Image</label>
                                <input class="form-control" type="file" id="hero_image" name="hero_image" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Hero Image</button>
                        </div>
                    </div>
                </form>
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
                        <h5 class="mb-0">Brands List</h5>
                        <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            <i class="ti ti-plus me-1"></i> Add New Brand
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Brand Name</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($brands) && count($brands) > 0)
                                    @foreach($brands as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>
                                                <img src="{{ asset('public/uploads/brands/' . $item->logo) }}"
                                                     alt="{{ $item->name }}"
                                                     style="width:50px; height:50px; object-fit:contain; border-radius:6px;">
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->address }}</td>
                                            
                                            <td>
                                                <div class="btn-group">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown"
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
                            <tfoot>
                                <tr>
                                    <th>Logo</th>
                                    <th>Brand Name</th>
                                    <th>Address</th>
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
        <form class="modal-content" action="{{ route('dashboard.brands.store') }}" method="POST" id="add_form"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Add New Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="Brand Name" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Logo <span class="text-danger">*</span></label>
                            <input type="file" name="logo" class="form-control" accept="image/*" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Corporate Address <span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control"
                                   placeholder="e.g. Dubai, UAE" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">General Description <span class="text-danger">*</span></label>
                            <textarea id="description" name="description" rows="3"
                                      class="form-control" placeholder="Brief about the brand..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">What We Do (Expertise) <span class="text-danger">*</span></label>
                            <textarea id="what_we_do" name="what_we_do" rows="5"
                                      class="rich-textarea form-control" placeholder="Describe brand expertise..." required></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save Brand</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <form class="modal-content" action="" method="POST" id="edit_form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Edit Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control"
                                   placeholder="Brand Name" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="control-label mb-1">Brand Logo <small class="text-muted">(Leave empty to keep current)</small></label>
                            <input type="file" name="logo" class="form-control" accept="image/*" />
                            <div class="mt-2" id="current_logo_preview"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">Corporate Address <span class="text-danger">*</span></label>
                            <input type="text" id="edit_address" name="address" class="form-control"
                                   placeholder="e.g. Dubai, UAE" required />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">General Description <span class="text-danger">*</span></label>
                            <textarea id="edit_description" name="description" rows="3"
                                      class="form-control" placeholder="Brief about the brand..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label mb-1">What We Do (Expertise) <span class="text-danger">*</span></label>
                            <textarea id="edit_what_we_do" name="what_we_do" rows="5"
                                      class="rich-textarea form-control" placeholder="Describe brand expertise..." required></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update Brand</button>
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

    // ── DataTable ──
    var items_table = $("#items-table").DataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
    });
    $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");

    // ── TinyMCE ──
    tinymce.init({
        selector: '.rich-textarea',
        plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
        toolbar: "code undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/uploads/tinymce-image',
    });

    // ── ADD ──
    $("#add_form").validate({
        submitHandler: function(form) {
            tinymce.triggerSave();

            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                beforeSend: function() {
                    Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                },
                success: function(response) {
                    Swal.close();
                    Toast.fire({ icon: 'success', title: 'Brand added successfully' });

                    const logoHtml = response.data.logo
                        ? `<img src="/uploads/brands/${response.data.logo}" style="width:50px;height:50px;object-fit:contain;border-radius:6px;">`
                        : '—';

                    const newRow = `<tr data-id="${response.data.id}">
                        <td>${logoHtml}</td>
                        <td>${response.data.name}</td>
                        <td>${response.data.address}</td>
                        <td>
                            <div class="btn-group">
                                <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="true">
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
                    tinymce.get('what_we_do')?.setContent('');
                },
                error: function(xhr) {
                    Swal.close();
                    let errors = xhr.responseJSON?.errors || { 'error': 'Something went wrong' };
                    let html = Object.values(errors).map(e => `<p class='text-danger'>${e}</p>`).join('');
                    Swal.fire({ icon: 'error', title: 'Failed to add', html });
                }
            });
        }
    });

    // ── EDIT — open modal ──
    $(document).on('click', '.edit', function() {
        const id = $(this).data('id');
        $('#edit_form').attr('action', `{{ route('dashboard.brands.update', '') }}/${id}`);

        $.ajax({
            url: `{{ route('dashboard.brands.get') }}`,
            method: 'GET',
            data: { id },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                const d = response.data;

                $('#edit_name').val(d.name);
                $('#edit_address').val(d.address);
                $('#edit_description').val(d.description);

                // TinyMCE
                if (tinymce.get('edit_what_we_do')) {
                    tinymce.get('edit_what_we_do').setContent(d.what_we_do ?? '');
                } else {
                    $('#edit_what_we_do').val(d.what_we_do ?? '');
                }

                // Current logo preview
                if (d.logo) {
                    $('#current_logo_preview').html(
                        `<img src="{{ asset('public/uploads/brands/') }}/${d.logo}" style="height:60px;object-fit:contain;border-radius:6px;" alt="Current Logo">
                        <small class="text-muted ms-2">Current logo</small>`
                    );
                } else {
                    $('#current_logo_preview').html('');
                }

                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire('Error', 'Failed to fetch brand data', 'error');
            }
        });
    });

    // ── EDIT — submit ──
    $("#edit_form").validate({
        submitHandler: function(form) {
            tinymce.triggerSave();

            let formData = new FormData(form);
            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                beforeSend: function() {
                    Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                },
                success: function(response) {
                    Swal.close();
                    Toast.fire({ icon: 'success', title: 'Brand updated successfully' });

                    const d = response.data;
                    const logoHtml = d.logo
                        ? `<img src="/uploads/brands/${d.logo}" style="width:50px;height:50px;object-fit:contain;border-radius:6px;">`
                        : '—';

                    let row = $(`#items-table tr[data-id='${d.id}']`);
                    items_table.row(row).data([
                        logoHtml,
                        d.name,
                        d.address,
                        `<div class="btn-group">
                            <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="true">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="${d.id}">Edit</a></li>
                                <li><a class="dropdown-item delete" href="javascript:void(0);" data-id="${d.id}">Delete</a></li>
                            </ul>
                        </div>`
                    ]).draw(false);

                    $(`#items-table tr[data-id='${d.id}']`).attr('data-id', d.id);
                    $('#editModal').modal('hide');
                },
                error: function(xhr) {
                    Swal.close();
                    let errors = xhr.responseJSON?.errors || { 'error': 'Something went wrong' };
                    let html = Object.values(errors).map(e => `<p class='text-danger'>${e}</p>`).join('');
                    Swal.fire({ icon: 'error', title: 'Failed to update', html });
                }
            });
        }
    });

    // ── DELETE ──
    $(document).on('click', '.delete', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "This brand will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('dashboard.brands.destroy', '') }}/${id}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    beforeSend: function() {
                        Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    },
                    success: function(response) {
                        Swal.close();
                        Toast.fire({ icon: 'success', title: response.message });
                        items_table.row(row).remove().draw();
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error', 'Failed to delete brand', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endsection