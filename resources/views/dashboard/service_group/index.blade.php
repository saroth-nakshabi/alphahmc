@extends('dashboard/layout')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Service Groups</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Service Groups</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center">
                            <h5 class="mb-0">Service Groups List</h5>
                            <a href="{{ route('service-group.create') }}" class="btn btn-success ms-auto">
                                <i class="ti ti-plus me-1"></i> Add New
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <tr>
                                        {{-- <th>#</th>
                                        <th>Image</th> --}}
                                        <th>Name</th>
                                        <th>Featured</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($service_groups) && count($service_groups) > 0)
                                        @foreach ($service_groups as $index => $service_group)
                                            <tr data-id="{{ $service_group->id }}">
                                                {{-- <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if ($service_group->image)
                                                        <img src="{{ asset('public/uploads/service_group_images/' . $service_group->image) }}"
                                                            alt="{{ $service_group->name }}"
                                                            style="width:60px;height:50px;object-fit:cover;border-radius:6px;">
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td> --}}
                                                <td>{{ $service_group->name }}</td>
                                                <td>
                                                    @if($service_group->is_featured)
                                                        <span class="badge bg-success">Featured</span>
                                                    @else
                                                        <span class="badge bg-light-secondary text-secondary">No</span>
                                                    @endif
                                                </td>
                                                <td>{!! Str::limit($service_group->description, 80) !!}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('service-group.edit', $service_group->id) }}">
                                                                    <i class="ti ti-pencil me-1"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item delete text-danger"
                                                                    href="javascript:void(0);"
                                                                    data-id="{{ $service_group->id }}">
                                                                    <i class="ti ti-trash me-1"></i>Delete
                                                                </a>
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
                                        {{-- <th>#</th>
                                        <th>Image</th> --}}
                                        <th>Name</th>
                                        <th>Featured</th>
                                        <th>Description</th>
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



@endsection

@section('custom_js')
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script>
        $(document).ready(function () {

            // ── DataTable ──────────────────────────────────────────────────────
            var items_table = $("#items-table").DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
            });
            $(".buttons-copy,.buttons-csv,.buttons-print,.buttons-pdf,.buttons-excel")
                .addClass("btn btn-primary mr-1");

            // ══════════════════════════════════════════════════════════════════
            // DELETE handler
            // ══════════════════════════════════════════════════════════════════
            $(document).on('click', '.delete', function () {
                const id  = $(this).data('id');
                const url = `{{ route('service-group.destroy', '') }}/${id}`;
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
                            url: url,
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            beforeSend: () => Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }),
                            success: function (response) {
                                Swal.close();
                                Toast.fire({ icon: 'success', title: response.message });
                                items_table.row(row).remove().draw();
                            },
                            error: function () {
                                Swal.close();
                                Swal.fire({ icon: 'error', title: 'Failed to delete', text: 'Please try again.' });
                            }
                        });
                    }
                });
            });

            // ── Helper: action column HTML ─────────────────────────────────
            function actionHtml(id) {
                return `<div class="btn-group">
                    <button class="dropdown-toggle btn btn-primary btn-sm"
                        data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('service-group.edit', '') }}/${id}">
                                <i class="ti ti-pencil me-1"></i>Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item delete text-danger" href="javascript:void(0);" data-id="${id}">
                                <i class="ti ti-trash me-1"></i>Delete
                            </a>
                        </li>
                    </ul>
                </div>`;
            }

        });
    </script>
@endsection