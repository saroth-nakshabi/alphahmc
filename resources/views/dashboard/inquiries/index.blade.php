@extends('dashboard/layout')

@section('custom_css')
<style>
    /* ── Stat cards ─────────────────────────────────── */
    .inq-stat {
        border-radius: 12px;
        padding: 1.1rem 1.4rem;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid transparent;
    }
    .inq-stat .stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .inq-stat .stat-num  { font-size: 1.6rem; font-weight: 700; line-height: 1; }
    .inq-stat .stat-lbl  { font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; margin-top: 2px; }

    .stat-total   { background: #f0f7ff; border-color: #cce3ff; }
    .stat-total   .stat-icon { background: #dbeafe; color: #1d4ed8; }
    .stat-total   .stat-num  { color: #1d4ed8; }

    .stat-pending { background: #fffbeb; border-color: #fde68a; }
    .stat-pending .stat-icon { background: #fef3c7; color: #b45309; }
    .stat-pending .stat-num  { color: #b45309; }

    .stat-replied { background: #f0fdf4; border-color: #bbf7d0; }
    .stat-replied .stat-icon { background: #dcfce7; color: #15803d; }
    .stat-replied .stat-num  { color: #15803d; }

    .stat-closed  { background: #f8fafc; border-color: #e2e8f0; }
    .stat-closed  .stat-icon { background: #f1f5f9; color: #64748b; }
    .stat-closed  .stat-num  { color: #64748b; }

    .stat-spam  { background: #fff1f2; border-color: #fecdd3; }
    .stat-spam  .stat-icon { background: #fee2e2; color: #be123c; }
    .stat-spam  .stat-num  { color: #be123c; }

    /* ── Filter card ─────────────────────────────────── */
    .filter-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.1rem 1.4rem;
        margin-bottom: 1.25rem;
    }
    .filter-card .form-label { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #64748b; margin-bottom: 4px; }
    .filter-card .form-control,
    .filter-card .form-select { font-size: .88rem; border-radius: 8px; border-color: #e2e8f0; }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }

    /* ── Table ───────────────────────────────────────── */
    .inq-table-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }
    .inq-table-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: .85rem 1.3rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    #inq-table thead th {
        background: #f8fafc;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        padding: .75rem 1rem;
    }
    #inq-table tbody td {
        vertical-align: middle;
        padding: .75rem 1rem;
        font-size: .88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    #inq-table tbody tr:last-child td { border-bottom: none; }
    #inq-table tbody tr:hover { background: #f9fafb; }

    /* ── Status badges ───────────────────────────────── */
    .badge-pending { background: #fff8e1; color: #b07d00; border: 1px solid #ffe082; padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; }
    .badge-replied { background: #e6f9f0; color: #1a8a4a; border: 1px solid #a3e6c3; padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; }
    .badge-closed  { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; }
    .badge-spam    { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; }
    .badge-assigned{ background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; }
    .badge-assigned-hpl{ background: #cffafe; color: #0e7490; border: 1px solid #a5f3fc; padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; }

    /* ── Status select ───────────────────────────────── */
    .status-select { font-size: .8rem; border-radius: 6px; border: 1px solid #e2e8f0; padding: 3px 8px; cursor: pointer; min-width: 100px; }
    .status-select:focus { outline: none; border-color: #4f46e5; }

    /* ── Action buttons ──────────────────────────────── */
    .inq-actions { display: flex; gap: 5px; flex-wrap: wrap; }
    .inq-btn { display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 7px; font-size: .8rem; font-weight: 600; text-decoration: none; border: 1px solid transparent; cursor: pointer; transition: all .2s; }
    .inq-btn-view   { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .inq-btn-view:hover   { background: #dbeafe; }
    .inq-btn-reply  { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
    .inq-btn-reply:hover  { background: #dcfce7; }
    .inq-btn-delete { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
    .inq-btn-delete:hover { background: #ffe4e6; }

    /* ── Pagination ──────────────────────────────────── */
    .pagination .page-link { border-radius: 7px !important; margin: 0 2px; font-size: .85rem; }
    .pagination .page-item.active .page-link { background: #4f46e5; border-color: #4f46e5; }

    /* ── Reply modal ─────────────────────────────────── */
    .reply-modal .modal-header { background: linear-gradient(135deg,#1e3a5f,#2563eb); }
    .reply-modal .modal-footer { background: #f8fafc; border-top: 1px solid #e2e8f0; }
</style>
@endsection

@section('content')

{{-- ── Breadcrumb ── --}}
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <h4 class="fw-semibold mb-1"><i class="ti ti-mail me-2"></i>Service Inquiries</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active">Inquiries</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ── Stats row ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card mb-0">
            <div class="card-body p-0">
                <div class="inq-stat stat-total">
                    <div class="stat-icon"><i class="ti ti-inbox"></i></div>
                    <div>
                        <div class="stat-num">{{ $stats['total'] }}</div>
                        <div class="stat-lbl">Total</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card mb-0">
            <div class="card-body p-0">
                <div class="inq-stat stat-pending">
                    <div class="stat-icon"><i class="ti ti-clock"></i></div>
                    <div>
                        <div class="stat-num">{{ $stats['pending'] }}</div>
                        <div class="stat-lbl">Pending</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card mb-0">
            <div class="card-body p-0">
                <div class="inq-stat stat-replied">
                    <div class="stat-icon"><i class="ti ti-check"></i></div>
                    <div>
                        <div class="stat-num">{{ $stats['replied'] }}</div>
                        <div class="stat-lbl">Replied</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card mb-0">
            <div class="card-body p-0">
                <div class="inq-stat stat-closed">
                    <div class="stat-icon"><i class="ti ti-circle-x"></i></div>
                    <div>
                        <div class="stat-num">{{ $stats['closed'] }}</div>
                        <div class="stat-lbl">Closed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(($stats['spam'] ?? 0) > 0)
    <div class="col-6 col-xl-3">
        <div class="card mb-0">
            <div class="card-body p-0">
                <div class="inq-stat stat-spam">
                    <div class="stat-icon"><i class="ti ti-shield-x"></i></div>
                    <div>
                        <div class="stat-num">{{ $stats['spam'] }}</div>
                        <div class="stat-lbl">Spam</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ── Filter bar ── --}}
<div class="filter-card">
    <form method="GET" action="{{ route('admin.inquiries.index') }}" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-lg-3">
                <label class="form-label">Service</label>
                <select name="service_id" class="form-select">
                    <option value="">All Services</option>
                    @foreach ($services as $svc)
                        <option value="{{ $svc->id }}" {{ request('service_id') == $svc->id ? 'selected' : '' }}>
                            {{ $svc->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-12 col-sm-4 col-lg-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="closed"  {{ request('status') == 'closed'  ? 'selected' : '' }}>Closed</option>
                    <option value="spam"    {{ request('status') == 'spam'    ? 'selected' : '' }}>Spam</option>
                    <option value="assigned_hfl" {{ request('status') == 'assigned_hfl' ? 'selected' : '' }}>Assigned: HFL Group</option>
                    <option value="assigned_hpl" {{ request('status') == 'assigned_hpl' ? 'selected' : '' }}>Assigned: HPL Team</option>
                </select>
            </div>
            <div class="col-12 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="ti ti-filter me-1"></i>Apply
                </button>
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-x"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ── Table card ── --}}
<div class="inq-table-card">
    <div class="card-header">
        <h6 class="fw-semibold mb-0">
            Inquiries
            <span class="badge bg-primary ms-1" style="font-size:.75rem;">{{ $inquiries->total() }}</span>
            @if(request()->hasAny(['service_id','date_from','date_to','status']))
                <span class="badge bg-warning text-dark ms-1" style="font-size:.72rem;">Filtered</span>
            @endif
        </h6>
        <span class="text-muted" style="font-size:.82rem;">
            Showing {{ $inquiries->firstItem() ?? 0 }}–{{ $inquiries->lastItem() ?? 0 }} of {{ $inquiries->total() }}
        </span>
    </div>

    <div class="table-responsive">
        <table id="inq-table" class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th style="width:200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inquiries as $inquiry)
                    @php $st = $inquiry->status ?: 'pending'; @endphp
                    <tr>
                        <td class="text-nowrap text-muted" style="font-size:.82rem;">
                            {{ $inquiry->created_at->format('M d, Y') }}<br>
                            <span style="font-size:.75rem;">{{ $inquiry->created_at->format('h:i A') }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold d-block">{{ $inquiry->name }}</span>
                            @if($inquiry->meeting_at)
                                <small class="text-info"><i class="ti ti-calendar-event" style="font-size:.75rem;"></i> Meeting requested</small>
                            @endif
                        </td>
                        <td>
                            @if($inquiry->service)
                                <span class="badge bg-light-primary text-primary fw-semibold" style="font-size:.78rem;">
                                    {{ $inquiry->service->name }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size:.82rem;">General</span>
                            @endif
                        </td>
                        <td>
                            <a href="mailto:{{ $inquiry->email }}" class="d-block text-dark" style="font-size:.85rem;">{{ $inquiry->email }}</a>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $inquiry->phone) }}" class="text-muted" style="font-size:.82rem;">{{ $inquiry->phone }}</a>
                        </td>
                        <td>
                            <form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="status" class="status-select" onchange="this.form.submit()">
                                    <option value="pending" {{ $st == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="replied" {{ $st == 'replied' ? 'selected' : '' }}>Replied</option>
                                    <option value="closed"  {{ $st == 'closed'  ? 'selected' : '' }}>Closed</option>
                                    <option value="spam"    {{ $st == 'spam'    ? 'selected' : '' }}>Spam</option>
                                    <option value="assigned_hfl" {{ $st == 'assigned_hfl' ? 'selected' : '' }}>Assigned: HFL Group</option>
                                    <option value="assigned_hpl" {{ $st == 'assigned_hpl' ? 'selected' : '' }}>Assigned: HPL Team</option>
                                </select>
                            </form>
                            @if($st === 'spam')
                                <span class="badge-spam d-block mt-1" style="display:inline-block;font-size:.72rem;">&#9888; Flagged</span>
                            @elseif($st === 'assigned_hfl')
                                <span class="badge-assigned d-block mt-1" style="display:inline-block;font-size:.72rem;">&#10140; HFL Sales</span>
                            @elseif($st === 'assigned_hpl')
                                <span class="badge-assigned-hpl d-block mt-1" style="display:inline-block;font-size:.72rem;">&#10140; HPL Team</span>
                            @endif
                        </td>
                        <td>
                            <div class="inq-actions">
                                <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="inq-btn inq-btn-view">
                                    <i class="ti ti-eye"></i> View
                                </a>
                                <button class="inq-btn inq-btn-reply" data-bs-toggle="modal" data-bs-target="#replyModal-{{ $inquiry->id }}">
                                    <i class="ti ti-send"></i> Reply
                                </button>
                                <button class="inq-btn inq-btn-delete" onclick="confirmDelete({{ $inquiry->id }})">
                                    <i class="ti ti-trash"></i>
                                </button>
                                <form id="deleteForm-{{ $inquiry->id }}" action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Reply modal --}}
                    <div class="modal fade reply-modal" id="replyModal-{{ $inquiry->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
                                <form action="{{ route('admin.inquiries.reply', $inquiry->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header border-0 py-3 px-4">
                                        <h5 class="modal-title text-white fw-semibold">
                                            <i class="ti ti-send me-2"></i>Reply to {{ $inquiry->name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body px-4 py-3">
                                        <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0;font-size:.88rem;">
                                            <span class="text-muted">To:</span> <strong>{{ $inquiry->email }}</strong>
                                            @if($inquiry->service)
                                                &nbsp;·&nbsp; <span class="text-muted">Service:</span> <strong>{{ $inquiry->service->name }}</strong>
                                            @endif
                                        </div>
                                        <label class="form-label fw-semibold" style="font-size:.85rem;">Message <span class="text-danger">*</span></label>
                                        <textarea name="reply_message" class="form-control" rows="7" required
                                            placeholder="Type your reply here..."
                                            style="border-radius:10px;font-size:.9rem;resize:vertical;"></textarea>
                                    </div>
                                    <div class="modal-footer px-4 py-3">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success px-4">
                                            <i class="ti ti-send me-1"></i>Send Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="ti ti-inbox-off" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:.5rem;"></i>
                            No inquiries found.
                            @if(request()->hasAny(['service_id','date_from','date_to','status']))
                                <a href="{{ route('admin.inquiries.index') }}" class="d-block mt-2 small">Clear filters</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($inquiries->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $inquiries->links('partials.pagination-numbered') }}
        </div>
    @endif
</div>

@endsection

@section('custom_js')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Inquiry?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#be123c',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete',
    }).then(result => {
        if (result.isConfirmed) document.getElementById('deleteForm-' + id).submit();
    });
}

@if(session('success'))
    Toast.fire({ icon: 'success', title: @json(session('success')) });
@endif

@if(session('error'))
    Toast.fire({ icon: 'error', title: @json(session('error')) });
@endif
</script>
@endsection
