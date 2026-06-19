@extends('dashboard/layout')

@section('custom_css')
    <style>
        #sortable-list { list-style: none; padding: 0; margin: 0; }
        .sort-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 18px; margin-bottom: 8px;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            transition: box-shadow .15s, border-color .15s; user-select: none;
        }
        .sort-item.ui-sortable-helper { box-shadow: 0 8px 24px rgba(0,51,88,0.12); border-color: #94a3b8; }
        .sort-item.ui-sortable-placeholder { border: 2px dashed #cbd5e1; background: #f8fafc; visibility: visible !important; border-radius: 10px; }
        .drag-handle { color: #cbd5e1; font-size: 1.2rem; flex-shrink: 0; cursor: grab; transition: color .15s; }
        .sort-item:hover .drag-handle { color: #64748b; }
        .sort-rank { width: 28px; height: 28px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .78rem; font-weight: 800; color: #64748b; flex-shrink: 0; }
        .staff-photo { width: 52px; height: 52px; object-fit: cover; border-radius: 50%; background: #f1f5f9; border: 1px solid #e2e8f0; flex-shrink: 0; }
        .staff-meta { flex: 1; min-width: 0; }
        .staff-meta .staff-name { font-weight: 700; color: #0f172a; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .staff-meta .staff-title { font-size: .8rem; color: #64748b; }
        .sort-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions .btn { padding: 5px 10px; font-size: .78rem; }
        #save-order-btn { transition: all .2s; }
        #save-order-btn.changed { animation: pulse-btn .6s ease-in-out infinite alternate; }
        @keyframes pulse-btn { from { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); } to { box-shadow: 0 0 0 8px rgba(59,130,246,0); } }
        .order-hint { font-size: .8rem; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
        #edit_current_photo img { width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
    </style>
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-1"><i class="ti ti-users me-2"></i>About — Leadership Team</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leadership Team</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center gap-3 flex-wrap">
                        <h5 class="mb-0">Executive &amp; Quality Leadership Team</h5>
                        <span class="order-hint">
                            <i class="ti ti-drag-drop"></i> Drag to reorder — click <strong>Save Order</strong> to apply
                        </span>
                        <div class="ms-auto d-flex gap-2">
                            <button id="save-order-btn" class="btn btn-primary btn-sm"><i class="ti ti-device-floppy me-1"></i> Save Order</button>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNewModal"><i class="ti ti-plus me-1"></i> Add Staff</button>
                        </div>
                    </div>

                    <ul id="sortable-list">
                        @foreach($staff as $item)
                            <li class="sort-item" data-id="{{ $item->id }}">
                                <span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>
                                <span class="sort-rank">{{ $loop->iteration }}</span>
                                <img class="staff-photo" src="{{ $item->image ? asset('public/uploads/about_staff/' . $item->image) : asset('public/dashboard/dist/images/profile/user-1.jpg') }}" alt="{{ $item->name }}">
                                <span class="staff-meta">
                                    <span class="staff-name">{{ $item->name }}</span>
                                    <span class="staff-title">{{ $item->title ?: '—' }}</span>
                                </span>
                                <div class="sort-actions">
                                    <button class="btn btn-light btn-sm edit" data-id="{{ $item->id }}" title="Edit"><i class="ti ti-edit"></i></button>
                                    <button class="btn btn-light-danger btn-sm delete text-danger" data-id="{{ $item->id }}" title="Delete"><i class="ti ti-trash"></i></button>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    @if(count($staff) === 0)
                        <div class="text-center py-5 text-muted" id="staff-empty-state">
                            <i class="ti ti-users" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                            <p>No staff yet. Add a leadership team member to get started.</p>
                        </div>
                    @endif

                    <p class="text-muted small mt-3 mb-0">
                        <i class="ti ti-info-circle me-1"></i>
                        This team appears on the About page as a carousel. Each card opens a profile popup on click.
                        The section is hidden automatically when there are no staff members.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="addNewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" action="{{ route('about_staff.store') }}" method="POST" id="add_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-header"><h4 class="modal-title">Add Staff Member</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="control-label mb-1">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Full name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label mb-1">Title / Role</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Chief Quality Officer">
                    </div>
                    <div class="col-12">
                        <label class="control-label mb-1">Photo <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <div class="field-hint text-muted small mt-1">Square images look best. Max 2MB.</div>
                    </div>
                    <div class="col-12">
                        <label class="control-label mb-1">Short Description</label>
                        <textarea name="short_description" rows="4" class="form-control" placeholder="A short professional bio shown on the profile popup..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" action="" method="POST" id="edit_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-header"><h4 class="modal-title">Edit Staff Member</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="control-label mb-1">Name <span class="text-danger">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label mb-1">Title / Role</label>
                        <input type="text" id="edit_title" name="title" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="control-label mb-1">Photo <small class="text-muted">(leave empty to keep current)</small></label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div id="edit_current_photo" class="mt-2"></div>
                    </div>
                    <div class="col-12">
                        <label class="control-label mb-1">Short Description</label>
                        <textarea id="edit_short_description" name="short_description" rows="4" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script src="{{ asset('public/dashboard/dist/libs/jquery-ui/dist/jquery-ui.min.js') }}"></script>
<script>
const CSRF      = $('meta[name="csrf-token"]').attr('content');
const PHOTO_BASE = '{{ asset('public/uploads/about_staff') }}';
const FALLBACK   = '{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}';

$(document).ready(function () {

    function escHtml(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function photoUrl(img) { return img ? (PHOTO_BASE + '/' + img) : FALLBACK; }
    function updateRanks() { $('#sortable-list .sort-item').each(function (i) { $(this).find('.sort-rank').text(i + 1); }); }

    function staffRow(d) {
        return '<li class="sort-item" data-id="' + d.id + '">' +
            '<span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>' +
            '<span class="sort-rank">0</span>' +
            '<img class="staff-photo" src="' + photoUrl(d.image) + '" alt="' + escHtml(d.name) + '">' +
            '<span class="staff-meta"><span class="staff-name">' + escHtml(d.name) + '</span>' +
            '<span class="staff-title">' + (escHtml(d.title) || '—') + '</span></span>' +
            '<div class="sort-actions">' +
                '<button class="btn btn-light btn-sm edit" data-id="' + d.id + '" title="Edit"><i class="ti ti-edit"></i></button>' +
                '<button class="btn btn-light-danger btn-sm delete text-danger" data-id="' + d.id + '" title="Delete"><i class="ti ti-trash"></i></button>' +
            '</div></li>';
    }

    $("#sortable-list").sortable({
        handle: '.drag-handle', placeholder: 'sort-item ui-sortable-placeholder', tolerance: 'pointer',
        update: function () { updateRanks(); $('#save-order-btn').addClass('changed'); }
    });

    $('#save-order-btn').on('click', function () {
        const order = [];
        $('#sortable-list .sort-item').each(function () { order.push($(this).data('id')); });
        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: '{{ route('about_staff.reorder') }}', method: 'POST',
            data: JSON.stringify({ order: order }), contentType: 'application/json', headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) { Swal.close(); $('#save-order-btn').removeClass('changed'); Toast.fire({ icon:'success', title: r.message || 'Order saved!' }); },
            error: function () { Swal.close(); Swal.fire({ icon:'error', title:'Failed', text:'Could not save order.' }); }
        });
    });

    $('#add_form').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        $.ajax({
            url: form.action, method: 'POST', data: new FormData(form), processData: false, contentType: false, headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) {
                $('#staff-empty-state').remove();
                $('#sortable-list').append(staffRow(r.data));
                updateRanks();
                $('#addNewModal').modal('hide'); form.reset();
                Toast.fire({ icon:'success', title: r.message || 'Added' });
            },
            error: function (xhr) {
                let er = xhr.responseJSON?.errors || { error:'Something went wrong' };
                Swal.fire({ icon:'error', title:'Failed', html: Object.values(er).map(e=>`<p>${e}</p>`).join('') });
            }
        });
    });

    $(document).on('click', '.edit', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ route('about_staff.get') }}', method: 'POST', data: { id }, headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) {
                $('#edit_form').attr('action', '{{ route('about_staff.update', '') }}/' + id);
                $('#edit_name').val(r.data.name);
                $('#edit_title').val(r.data.title || '');
                $('#edit_short_description').val(r.data.short_description || '');
                $('#edit_current_photo').html(r.data.image ? ('<img src="' + photoUrl(r.data.image) + '" alt="current"><small class="text-muted ms-2">Current photo</small>') : '');
                $('#editModal').modal('show');
            }
        });
    });

    $('#edit_form').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        $.ajax({
            url: form.action, method: 'POST', data: new FormData(form), processData: false, contentType: false, headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) {
                const $li = $(`#sortable-list .sort-item[data-id='${r.data.id}']`);
                $li.find('.staff-name').text(r.data.name);
                $li.find('.staff-title').text(r.data.title || '—');
                $li.find('.staff-photo').attr('src', photoUrl(r.data.image));
                $('#editModal').modal('hide');
                Toast.fire({ icon:'success', title: r.message || 'Updated' });
            },
            error: function (xhr) {
                let er = xhr.responseJSON?.errors || { error:'Something went wrong' };
                Swal.fire({ icon:'error', title:'Failed', html: Object.values(er).map(e=>`<p>${e}</p>`).join('') });
            }
        });
    });

    $(document).on('click', '.delete', function () {
        const id = $(this).data('id');
        const $li = $(this).closest('.sort-item');
        Swal.fire({ title: 'Delete this staff member?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!' })
        .then(function (res) {
            if (!res.isConfirmed) return;
            $.ajax({
                url: '{{ route('about_staff.destroy', '') }}/' + id, method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (r) { $li.remove(); updateRanks(); Toast.fire({ icon:'success', title: r.message || 'Deleted' }); },
                error: function () { Swal.fire({ icon:'error', title:'Failed to delete' }); }
            });
        });
    });

});
</script>
@endsection
