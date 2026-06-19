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
        .sort-item.ui-sortable-placeholder {
            border: 2px dashed #cbd5e1; background: #f8fafc;
            visibility: visible !important; border-radius: 10px;
        }
        .drag-handle { color: #cbd5e1; font-size: 1.2rem; flex-shrink: 0; cursor: grab; transition: color .15s; }
        .sort-item:hover .drag-handle { color: #64748b; }
        .sort-rank {
            width: 28px; height: 28px; background: #f1f5f9; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 800; color: #64748b; flex-shrink: 0;
        }
        .ctr-value {
            min-width: 96px; flex-shrink: 0; font-weight: 800; font-size: 1.25rem;
            color: #009095; font-family: 'Libre Baskerville', serif;
        }
        .ctr-label { flex: 1; min-width: 0; font-weight: 600; color: #0f172a;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sort-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .sort-actions .btn { padding: 5px 10px; font-size: .78rem; }
        #save-order-btn { transition: all .2s; }
        #save-order-btn.changed { animation: pulse-btn .6s ease-in-out infinite alternate; }
        @keyframes pulse-btn { from { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); } to { box-shadow: 0 0 0 8px rgba(59,130,246,0); } }
        .order-hint { font-size: .8rem; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
    </style>
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-1"><i class="ti ti-chart-bar me-2"></i>About — Counters / Stats</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">About Counters</li>
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
                        <h5 class="mb-0">Stats Strip on the About Page</h5>
                        <span class="order-hint">
                            <i class="ti ti-drag-drop"></i> Drag to reorder — click <strong>Save Order</strong> to apply
                        </span>
                        <div class="ms-auto d-flex gap-2">
                            <button id="save-order-btn" class="btn btn-primary btn-sm">
                                <i class="ti ti-device-floppy me-1"></i> Save Order
                            </button>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                <i class="ti ti-plus me-1"></i> Add Counter
                            </button>
                        </div>
                    </div>

                    <ul id="sortable-list">
                        @foreach($counters as $item)
                            <li class="sort-item" data-id="{{ $item->id }}">
                                <span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>
                                <span class="sort-rank">{{ $loop->iteration }}</span>
                                <span class="ctr-value">{{ number_format($item->value) }}{{ $item->suffix }}</span>
                                <span class="ctr-label">{{ $item->label }}</span>
                                <div class="sort-actions">
                                    <button class="btn btn-light btn-sm edit" data-id="{{ $item->id }}" title="Edit"><i class="ti ti-edit"></i></button>
                                    <button class="btn btn-light-danger btn-sm delete text-danger" data-id="{{ $item->id }}" title="Delete"><i class="ti ti-trash"></i></button>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    @if(count($counters) === 0)
                        <div class="text-center py-5 text-muted" id="counters-empty-state">
                            <i class="ti ti-chart-bar" style="font-size:3rem;display:block;margin-bottom:.6rem;color:#cbd5e1"></i>
                            <p>No counters yet. Add one to get started.</p>
                        </div>
                    @endif

                    <p class="text-muted small mt-3 mb-0">
                        <i class="ti ti-info-circle me-1"></i>
                        <strong>Value</strong> is the number that counts up (digits only). <strong>Suffix</strong> is shown after it (e.g. <code>+</code>).
                        Large numbers get thousands separators automatically (e.g. 10000 → 10,000).
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="addNewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('about_counters.store') }}" method="POST" id="add_form">
            @csrf
            <div class="modal-header"><h4 class="modal-title">Add Counter</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-8">
                        <label class="control-label mb-1">Value <span class="text-danger">*</span></label>
                        <input type="number" name="value" class="form-control" min="0" placeholder="e.g. 500" required>
                    </div>
                    <div class="col-4">
                        <label class="control-label mb-1">Suffix</label>
                        <input type="text" name="suffix" class="form-control" maxlength="12" placeholder="+">
                    </div>
                    <div class="col-12">
                        <label class="control-label mb-1">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control" placeholder="e.g. Healthcare Clients" required>
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
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="" method="POST" id="edit_form">
            @csrf
            <div class="modal-header"><h4 class="modal-title">Edit Counter</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-8">
                        <label class="control-label mb-1">Value <span class="text-danger">*</span></label>
                        <input type="number" id="edit_value" name="value" class="form-control" min="0" required>
                    </div>
                    <div class="col-4">
                        <label class="control-label mb-1">Suffix</label>
                        <input type="text" id="edit_suffix" name="suffix" class="form-control" maxlength="12">
                    </div>
                    <div class="col-12">
                        <label class="control-label mb-1">Label <span class="text-danger">*</span></label>
                        <input type="text" id="edit_label" name="label" class="form-control" required>
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
const CSRF = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {

    function escHtml(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function fmtVal(v, suffix) { return Number(v).toLocaleString('en-US') + (suffix || ''); }

    function updateRanks() {
        $('#sortable-list .sort-item').each(function (i) { $(this).find('.sort-rank').text(i + 1); });
    }

    function counterRow(d) {
        return '<li class="sort-item" data-id="' + d.id + '">' +
            '<span class="drag-handle" title="Drag to reorder"><i class="ti ti-grip-vertical"></i></span>' +
            '<span class="sort-rank">0</span>' +
            '<span class="ctr-value">' + fmtVal(d.value, d.suffix) + '</span>' +
            '<span class="ctr-label">' + escHtml(d.label) + '</span>' +
            '<div class="sort-actions">' +
                '<button class="btn btn-light btn-sm edit" data-id="' + d.id + '" title="Edit"><i class="ti ti-edit"></i></button>' +
                '<button class="btn btn-light-danger btn-sm delete text-danger" data-id="' + d.id + '" title="Delete"><i class="ti ti-trash"></i></button>' +
            '</div></li>';
    }

    /* Drag sort */
    $("#sortable-list").sortable({
        handle: '.drag-handle',
        placeholder: 'sort-item ui-sortable-placeholder',
        tolerance: 'pointer',
        update: function () { updateRanks(); $('#save-order-btn').addClass('changed'); }
    });

    /* Save order */
    $('#save-order-btn').on('click', function () {
        const order = [];
        $('#sortable-list .sort-item').each(function () { order.push($(this).data('id')); });
        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: '{{ route('about_counters.reorder') }}', method: 'POST',
            data: JSON.stringify({ order: order }), contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) { Swal.close(); $('#save-order-btn').removeClass('changed'); Toast.fire({ icon:'success', title: r.message || 'Order saved!' }); },
            error: function () { Swal.close(); Swal.fire({ icon:'error', title:'Failed', text:'Could not save order.' }); }
        });
    });

    /* Add */
    $('#add_form').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        $.ajax({
            url: form.action, method: 'POST', data: new FormData(form), processData: false, contentType: false,
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) {
                $('#counters-empty-state').remove();
                $('#sortable-list').append(counterRow(r.data));
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

    /* Edit — open */
    $(document).on('click', '.edit', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ route('about_counters.get') }}', method: 'POST', data: { id }, headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) {
                $('#edit_form').attr('action', '{{ route('about_counters.update', '') }}/' + id);
                $('#edit_value').val(r.data.value);
                $('#edit_suffix').val(r.data.suffix || '');
                $('#edit_label').val(r.data.label);
                $('#editModal').modal('show');
            }
        });
    });

    /* Edit — submit */
    $('#edit_form').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        $.ajax({
            url: form.action, method: 'POST', data: new FormData(form), processData: false, contentType: false,
            headers: { 'X-CSRF-TOKEN': CSRF },
            success: function (r) {
                const $li = $(`#sortable-list .sort-item[data-id='${r.data.id}']`);
                $li.find('.ctr-value').text(fmtVal(r.data.value, r.data.suffix));
                $li.find('.ctr-label').text(r.data.label);
                $('#editModal').modal('hide');
                Toast.fire({ icon:'success', title: r.message || 'Updated' });
            },
            error: function (xhr) {
                let er = xhr.responseJSON?.errors || { error:'Something went wrong' };
                Swal.fire({ icon:'error', title:'Failed', html: Object.values(er).map(e=>`<p>${e}</p>`).join('') });
            }
        });
    });

    /* Delete */
    $(document).on('click', '.delete', function () {
        const id = $(this).data('id');
        const $li = $(this).closest('.sort-item');
        Swal.fire({
            title: 'Delete this counter?', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!'
        }).then(function (res) {
            if (!res.isConfirmed) return;
            $.ajax({
                url: '{{ route('about_counters.destroy', '') }}/' + id, method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (r) { $li.remove(); updateRanks(); Toast.fire({ icon:'success', title: r.message || 'Deleted' }); },
                error: function () { Swal.fire({ icon:'error', title:'Failed to delete' }); }
            });
        });
    });

});
</script>
@endsection
