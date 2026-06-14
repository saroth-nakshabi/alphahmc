@extends('dashboard/layout')

@section('custom_css')
    <style>
        .inq-card { border: 1px solid #e8edf2; border-radius: 14px; background: #fff; box-shadow: 0 6px 20px rgba(0,0,0,.04); }
        .inq-head { background: #f8fafc; border-bottom: 1px solid #e8edf2; padding: 1.1rem 1.4rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
        .inq-body { padding: 1.4rem; }
        .inq-row { display:flex; gap:14px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
        .inq-row:last-child { border-bottom:none; }
        .inq-label { width:150px; flex-shrink:0; font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; padding-top:2px; }
        .inq-val { font-size:.95rem; color:#1f2d33; word-break:break-word; }
        .inq-val a { color:#066D77; text-decoration:none; font-weight:600; }
        .inq-val a:hover { text-decoration:underline; }
        .inq-msg { background:#f8fafc; border:1px solid #eef2f5; border-radius:10px; padding:16px; line-height:1.7; color:#334155; white-space:pre-wrap; }
        .inq-status-badge { font-size:.75rem; font-weight:700; padding:5px 14px; border-radius:50px; text-transform:capitalize; }
        .st-pending { background:#fff8e1; color:#b07d00; border:1px solid #ffe082; }
        .st-replied { background:#e6f9f0; color:#1a8a4a; border:1px solid #a3e6c3; }
        .st-closed  { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }
        .inq-actions { display:flex; gap:10px; flex-wrap:wrap; }
        .inq-contact-btns { display:flex; gap:10px; flex-wrap:wrap; margin-top:6px; }
        .inq-contact-btns a { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:8px; font-size:.85rem; font-weight:600; text-decoration:none; }
        .inq-btn-email { background:#066D77; color:#fff !important; }
        .inq-btn-call  { background:#eef4ff; color:#1d4ed8 !important; border:1px solid #bfdbfe; }
        .inq-btn-wa    { background:#e7f9ee; color:#128c4a !important; border:1px solid #b6ebc8; }
    </style>
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="fw-semibold mb-1"><i class="ti ti-user-circle me-2"></i>Inquiry from {{ $inquiry->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.inquiries.index') }}">CRM — Inquiries</a></li>
                            <li class="breadcrumb-item active">#{{ $inquiry->id }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        {{-- Lead details --}}
        <div class="col-lg-8">
            <div class="inq-card">
                <div class="inq-head">
                    <h5 class="mb-0 fw-semibold">Lead Details</h5>
                    @php $st = $inquiry->status ?: 'pending'; @endphp
                    <span class="inq-status-badge st-{{ $st }}">{{ $st }}</span>
                </div>
                <div class="inq-body">
                    <div class="inq-row">
                        <span class="inq-label">Name</span>
                        <span class="inq-val">{{ $inquiry->name }}</span>
                    </div>
                    <div class="inq-row">
                        <span class="inq-label">Email</span>
                        <span class="inq-val"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></span>
                    </div>
                    <div class="inq-row">
                        <span class="inq-label">Mobile</span>
                        <span class="inq-val"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $inquiry->phone) }}">{{ $inquiry->phone }}</a></span>
                    </div>
                    <div class="inq-row">
                        <span class="inq-label">Service</span>
                        <span class="inq-val">{{ $inquiry->service->name ?? 'General enquiry' }}</span>
                    </div>
                    <div class="inq-row">
                        <span class="inq-label">Received</span>
                        <span class="inq-val">{{ $inquiry->created_at->format('M d, Y · h:i A') }}</span>
                    </div>
                    <div class="inq-row" style="flex-direction:column; gap:8px;">
                        <span class="inq-label">Message</span>
                        <div class="inq-msg">{{ $inquiry->message ?: 'No message provided.' }}</div>
                    </div>

                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $inquiry->phone);
                    @endphp
                    <div class="inq-contact-btns">
                        <a href="mailto:{{ $inquiry->email }}" class="inq-btn-email"><i class="ti ti-mail"></i> Email</a>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $inquiry->phone) }}" class="inq-btn-call"><i class="ti ti-phone"></i> Call</a>
                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener" class="inq-btn-wa"><i class="ti ti-brand-whatsapp"></i> WhatsApp</a>
                    </div>
                </div>
            </div>

            {{-- Reply history --}}
            @if(!empty($inquiry->reply_history) && is_array($inquiry->reply_history))
                <div class="inq-card mt-4">
                    <div class="inq-head"><h5 class="mb-0 fw-semibold">Reply History ({{ count($inquiry->reply_history) }})</h5></div>
                    <div class="inq-body">
                        @foreach ($inquiry->reply_history as $reply)
                            <div class="inq-msg mb-3">
                                <div class="text-muted small mb-2">{{ \Carbon\Carbon::parse($reply['sent_at'] ?? now())->format('M d, Y · h:i A') }}</div>
                                {{ $reply['message'] ?? '' }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Actions sidebar --}}
        <div class="col-lg-4">
            <div class="inq-card">
                <div class="inq-head"><h5 class="mb-0 fw-semibold">Status</h5></div>
                <div class="inq-body">
                    <form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST">
                        @csrf
                        <select name="status" class="form-select mb-3" onchange="this.form.submit()">
                            <option value="pending" {{ $st == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="replied" {{ $st == 'replied' ? 'selected' : '' }}>Replied</option>
                            <option value="closed"  {{ $st == 'closed'  ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>

                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="ti ti-arrow-left me-1"></i> Back to Inquiries
                    </a>

                    <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST"
                          onsubmit="return confirm('Delete this inquiry permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light-danger text-danger w-100">
                            <i class="ti ti-trash me-1"></i> Delete Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
