@extends('dashboard/layout')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
<style>
.rating { direction: rtl; display: inline-flex; }
.rating input { display: none; }
.rating label { font-size: 24px; color: #ccc; cursor: pointer; }
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label { color: gold; }
.source-badge { font-size: 0.72rem; padding: 2px 8px; border-radius: 100px; }
.badge-customer { background: #e8f5e9; color: #2e7d32; }
.badge-admin    { background: #e3f2fd; color: #1565c0; }
.settings-panel { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 12px; padding: 24px; margin-bottom: 28px; }
</style>
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Testimonials</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="#">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Testimonials</li>
                    </ol>
                </nav>
            </div>
            <div class="col-3">
                <div class="text-center mb-n5">
                    <img src="{{ asset('public/dashboard/dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Settings Panel ───────────────────────────────── --}}
<div class="settings-panel">
    <h5 class="mb-3"><i class="ti ti-settings me-1"></i> Page Settings</h5>
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label fw-semibold">Hero Message <small class="text-muted">(shown on the testimonials page)</small></label>
            <input type="text" id="hero_message_input" class="form-control"
                value="{{ $settings->hero_message }}"
                placeholder="e.g. What our clients say about us">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" id="save_settings_btn">
                <i class="ti ti-device-floppy me-1"></i> Save
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('front.testimonials') }}" target="_blank" class="btn btn-outline-secondary w-100">
                <i class="ti ti-external-link me-1"></i> View Page
            </a>
        </div>
    </div>
</div>

{{-- ── Table ────────────────────────────────────────── --}}
<section class="datatables">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <h5 class="mb-0">All Testimonials</h5>
                        <span class="badge bg-warning text-dark ms-2" id="pending-badge">
                            {{ $Projects->where('approved', false)->where('source','customer')->count() }} pending approval
                        </span>
                        <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            <i class="ti ti-plus me-1"></i> Add New
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="items-table" class="table border table-striped table-bordered display">
                            <thead>
                                <tr>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Service</th>
                                    <th>Source</th>
                                    <th>Approved</th>
                                    <th>Featured</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Projects as $t)
                                <tr data-id="{{ $t->id }}">
                                    <td>
                                        <div class="fw-semibold">{{ $t->author_name }}</div>
                                        @if($t->email)<small class="text-muted">{{ $t->email }}</small>@endif
                                    </td>
                                    <td>
                                        @for($s=1;$s<=5;$s++)
                                            <i class="fa{{ $s <= $t->rating ? 's' : 'r' }} fa-star" style="color:{{ $s <= $t->rating ? '#FBBC04' : '#ccc' }};font-size:0.8rem"></i>
                                        @endfor
                                    </td>
                                    <td>{{ $t->service->name ?? '—' }}</td>
                                    <td><span class="source-badge {{ $t->source === 'customer' ? 'badge-customer' : 'badge-admin' }}">{{ ucfirst($t->source) }}</span></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input approved-input" type="checkbox" role="switch"
                                                data-id="{{ $t->id }}" {{ $t->approved ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input featured-input" type="checkbox" role="switch"
                                                data-id="{{ $t->id }}" {{ $t->featured ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item edit" href="javascript:void(0);" data-id="{{ $t->id }}">Edit</a></li>
                                                <li><a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $t->id }}">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Add Modal ────────────────────────────────────── --}}
<div class="modal fade" id="addNewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <form class="modal-content" action="{{ route('testimonial.store') }}" method="POST" id="add_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Add New Testimonial</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 pt-2">
                    <div class="col-md-6">
                        <label class="form-label">Author Name <span class="text-danger">*</span></label>
                        <input type="text" name="author_name" class="form-control" placeholder="Dr. John Smith" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="author@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" placeholder="e.g. Quality Manager">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Organisation name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Service</label>
                        <select name="service_id" class="form-control select2">
                            <option value="">— None —</option>
                            @foreach ($services as $svc)
                            <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Author Image</label>
                        <input type="file" name="author_image" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <div class="rating">
                            @for($i=5;$i>=1;$i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="add_star{{ $i }}" required>
                            <label for="add_star{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" rows="4" class="form-control" placeholder="Testimonial text..." required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Testimonial Date</label>
                        <input type="date" name="testimonial_date" id="add_testimonial_date" class="form-control">
                        <small class="text-muted">Leave blank to use today's date</small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mt-4">
                            <input type="checkbox" name="featured" class="form-check-input" value="1" id="add_featured">
                            <label class="form-check-label" for="add_featured">Mark as Featured</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Add Testimonial</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Edit Modal ───────────────────────────────────── --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" method="POST" id="edit_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">Edit Testimonial</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Author Name</label>
                        <input type="text" id="edit_author_name" name="author_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position</label>
                        <input type="text" id="edit_position" name="position" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" id="edit_company_name" name="company_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Service</label>
                        <select id="edit_service_id" name="service_id" class="form-control select2">
                            <option value="">— None —</option>
                            @foreach ($services as $svc)
                            <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Author Image</label>
                        <input type="file" name="author_image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            @for($i=5;$i>=1;$i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="edit_star{{ $i }}">
                            <label for="edit_star{{ $i }}">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Testimonial Date</label>
                        <input type="date" name="testimonial_date" id="edit_testimonial_date" class="form-control">
                        <small class="text-muted">Leave blank to keep existing date</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" id="edit_featured" name="featured" value="1" class="form-check-input">
                            <label class="form-check-label" for="edit_featured">Featured</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <textarea id="edit_content" name="content" class="form-control" rows="5" required></textarea>
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
<script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
<script>
$(document).ready(function() {

    var items_table = $("#items-table").DataTable({
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        order: [[0, 'asc']],
    });
    $(".buttons-copy,.buttons-csv,.buttons-print,.buttons-pdf,.buttons-excel").addClass("btn btn-primary mr-1");

    // Select2
    $('#addNewModal .select2, #editModal .select2').select2({ dropdownParent: $('#addNewModal, #editModal'), minimumResultsForSearch: 8 });
    $('#addNewModal .select2').select2({ dropdownParent: $('#addNewModal') });
    $('#editModal .select2').select2({ dropdownParent: $('#editModal') });

    // ── Save Settings ──────────────────────────────────────
    $('#save_settings_btn').on('click', function() {
        const msg = $('#hero_message_input').val().trim();
        if (!msg) return;
        $.post('{{ route("testimonial.saveSettings") }}', { _token: '{{ csrf_token() }}', hero_message: msg }, function(r) {
            Toast.fire({ icon: 'success', title: r.message });
        });
    });

    // ── Approved toggle ────────────────────────────────────
    $(document).on('change', '.approved-input', function() {
        const id  = $(this).data('id');
        const url = `{{ route('testimonial.toggleApproved', '') }}/${id}`;
        $.post(url, { _token: '{{ csrf_token() }}' }, function(r) {
            Toast.fire({ icon: 'success', title: r.message });
        });
    });

    // ── Featured toggle ────────────────────────────────────
    $(document).on('change', '.featured-input', function() {
        const id  = $(this).data('id');
        const url = `{{ route('testimonial.toggleFeatured', '') }}/${id}`;
        $.post(url, { _token: '{{ csrf_token() }}' }, function(r) {
            Toast.fire({ icon: 'success', title: r.message });
        });
    });

    // ── Add form ───────────────────────────────────────────
    $("#add_form").on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: form.action, method: 'POST', data: formData,
            processData: false, contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(r) {
                Swal.close();
                Toast.fire({ icon: 'success', title: r.message });
                $('#addNewModal').modal('hide');
                $('#add_form')[0].reset();
                location.reload();
            },
            error: function(xhr) {
                Swal.close();
                let errs = '';
                if (xhr.responseJSON?.errors) $.each(xhr.responseJSON.errors, (k,v) => errs += `<p class='text-danger'>${v}</p>`);
                Swal.fire({ icon: 'error', title: 'Failed', html: errs || 'Something went wrong.' });
            }
        });
    });

    // ── Edit click ─────────────────────────────────────────
    $(document).on('click', '.edit', function() {
        const id = $(this).data('id');
        $('#edit_form').attr('action', `{{ route('testimonial.update', '') }}/${id}`);
        $.post('{{ route("testimonial.get") }}', { id: id, _token: '{{ csrf_token() }}' }, function(r) {
            const d = r.data;
            $('#edit_author_name').val(d.author_name);
            $('#edit_email').val(d.email || '');
            $('#edit_position').val(d.position);
            $('#edit_company_name').val(d.company_name);
            $('#edit_featured').prop('checked', d.featured == 1);
            $('#edit_service_id').val(d.service_id || '').trigger('change');
            $(`#edit_form input[name="rating"]`).prop('checked', false);
            $(`#edit_form input[name="rating"][value="${d.rating}"]`).prop('checked', true);
            $('#edit_content').val(d.content);
            $('#edit_testimonial_date').val(d.created_at ? d.created_at.substring(0, 10) : '');
            $('#editModal').modal('show');
        });
    });

    // ── Edit form submit ───────────────────────────────────
    $("#edit_form").on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: form.action, method: 'POST', data: formData,
            processData: false, contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(r) {
                Swal.close();
                Toast.fire({ icon: 'success', title: r.message });
                $('#editModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                Swal.close();
                let errs = '';
                if (xhr.responseJSON?.errors) $.each(xhr.responseJSON.errors, (k,v) => errs += `<p class='text-danger'>${v}</p>`);
                Swal.fire({ icon: 'error', title: 'Failed', html: errs || 'Something went wrong.' });
            }
        });
    });

    // ── Delete ─────────────────────────────────────────────
    $(document).on('click', '.delete', function() {
        const id  = $(this).data('id');
        const url = `{{ route('testimonial.destroy', '') }}/${id}`;
        const row = $(this).closest('tr');
        Swal.fire({ title: 'Are you sure?', text: "This cannot be undone.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!' }).then(result => {
            if (result.isConfirmed) {
                $.ajax({ url: url, method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(r) { Toast.fire({ icon: 'success', title: r.message }); items_table.row(row).remove().draw(); },
                    error: function() { Swal.fire({ icon: 'error', title: 'Failed to delete' }); }
                });
            }
        });
    });

});
</script>
@endsection
