@extends('dashboard/layout')

@section('content')
<style>
    /* ── Planner CRM — refined, fluid table ───────────────────────────── */
    .pl-crm { --pl-ink:#0b0f14; --pl-muted:#7a838c; --pl-line:#edeff2; --pl-accent:#066D77; --pl-accent-soft:#eef6f6; }
    .pl-head { display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:14px; margin-bottom:18px; }
    .pl-title { font-weight:700; letter-spacing:-.02em; color:var(--pl-ink); margin:0; font-size:1.35rem; }
    .pl-title .pl-count { font-size:.8rem; font-weight:600; color:var(--pl-accent); background:var(--pl-accent-soft); padding:3px 10px; border-radius:100px; margin-left:8px; vertical-align:middle; }
    .pl-sub { color:var(--pl-muted); font-size:.85rem; margin:2px 0 0; }

    /* Filter toolbar */
    .pl-filters { background:#fff; border:1px solid var(--pl-line); border-radius:16px; padding:14px; margin-bottom:18px; }
    .pl-filters .row { --bs-gutter-x:.75rem; row-gap:.75rem; }
    .pl-field { position:relative; }
    .pl-field label { display:block; font-size:.7rem; font-weight:600; letter-spacing:.04em; text-transform:uppercase; color:var(--pl-muted); margin-bottom:5px; }
    .pl-input, .pl-select { width:100%; border:1px solid #e2e6ea; border-radius:11px; padding:9px 12px; font-size:.9rem; color:var(--pl-ink); background:#fbfcfd; transition:border-color .15s, box-shadow .15s, background .15s; }
    .pl-input:focus, .pl-select:focus { outline:none; border-color:var(--pl-accent); background:#fff; box-shadow:0 0 0 3px rgba(6,109,119,.12); }
    .pl-search-wrap { position:relative; }
    .pl-search-wrap .bi { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--pl-muted); pointer-events:none; }
    .pl-search-wrap .pl-input { padding-left:34px; }
    .pl-filter-actions { display:flex; gap:8px; align-items:flex-end; height:100%; }
    .pl-btn { border:none; border-radius:11px; padding:9px 16px; font-size:.88rem; font-weight:600; cursor:pointer; transition:transform .12s ease, filter .15s ease, background .15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; white-space:nowrap; }
    .pl-btn:active { transform:scale(.97); }
    .pl-btn-primary { background:var(--pl-accent); color:#fff; }
    .pl-btn-primary:hover { filter:brightness(1.08); color:#fff; }
    .pl-btn-ghost { background:#f1f3f5; color:var(--pl-ink); }
    .pl-btn-ghost:hover { background:#e7eaed; color:var(--pl-ink); }

    /* Table */
    .pl-card { background:#fff; border:1px solid var(--pl-line); border-radius:16px; overflow:hidden; }
    .pl-table { width:100%; border-collapse:separate; border-spacing:0; }
    .pl-table thead th { position:sticky; top:0; z-index:2; background:#f8f9fb; color:var(--pl-muted); font-size:.7rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; padding:13px 16px; text-align:left; border-bottom:1px solid var(--pl-line); }
    .pl-table tbody td { padding:14px 16px; border-bottom:1px solid var(--pl-line); vertical-align:middle; font-size:.9rem; color:var(--pl-ink); }
    .pl-table tbody tr { transition:background .14s ease; cursor:pointer; }
    .pl-table tbody tr:hover { background:#f6fafa; }
    .pl-table tbody tr:last-child td { border-bottom:none; }
    .pl-goal { font-weight:600; color:var(--pl-ink); display:flex; align-items:center; gap:8px; }
    .pl-where { color:var(--pl-muted); font-size:.8rem; margin-top:3px; }
    .pl-date { font-weight:600; color:var(--pl-ink); white-space:nowrap; }
    .pl-date small { display:block; font-weight:400; color:var(--pl-muted); font-size:.74rem; }
    .pl-chip { display:inline-block; font-size:.72rem; font-weight:600; color:var(--pl-accent); background:var(--pl-accent-soft); padding:3px 9px; border-radius:100px; margin:0 4px 4px 0; }
    .pl-chip-more { color:var(--pl-muted); background:#f1f3f5; }
    .pl-eng { font-size:.66rem; font-weight:700; letter-spacing:.04em; padding:2px 7px; border-radius:6px; }
    .pl-eng-ai { background:#e7f6ee; color:#1a7f47; }
    .pl-eng-rules { background:#eef0f2; color:#7a838c; }
    .pl-contact b { font-size:.85rem; font-weight:600; display:block; }
    .pl-contact span { color:var(--pl-muted); font-size:.78rem; display:block; }
    .pl-meet { display:inline-flex; align-items:center; gap:5px; font-size:.74rem; font-weight:600; color:#b26a00; background:#fff5e6; padding:3px 8px; border-radius:7px; margin-top:5px; }
    .pl-status-sel { border:1px solid #e2e6ea; border-radius:9px; padding:6px 10px; font-size:.82rem; font-weight:600; background:#fbfcfd; cursor:pointer; }
    .pl-status-sel:focus { outline:none; border-color:var(--pl-accent); box-shadow:0 0 0 3px rgba(6,109,119,.12); }
    .pl-status-new { color:#066D77; }
    .pl-status-contacted { color:#1a7f47; }
    .pl-status-closed { color:#7a838c; }
    .pl-actions { display:flex; gap:6px; }
    .pl-ico { width:34px; height:34px; border-radius:9px; border:1px solid var(--pl-line); background:#fff; display:inline-flex; align-items:center; justify-content:center; color:var(--pl-ink); transition:all .14s ease; cursor:pointer; text-decoration:none; }
    .pl-ico:hover { transform:translateY(-1px); }
    .pl-ico-view:hover { background:var(--pl-accent); color:#fff; border-color:var(--pl-accent); }
    .pl-ico-inq:hover { background:#0d6efd; color:#fff; border-color:#0d6efd; }
    .pl-ico-del:hover { background:#dc3545; color:#fff; border-color:#dc3545; }
    .pl-empty { text-align:center; padding:50px 20px; color:var(--pl-muted); }
    .pl-empty .bi { font-size:2rem; opacity:.5; display:block; margin-bottom:10px; }
    @media (max-width:991px){ .pl-table thead { display:none; } .pl-table, .pl-table tbody, .pl-table tr, .pl-table td { display:block; width:100%; }
        .pl-table tr { padding:8px 4px; border-bottom:1px solid var(--pl-line); } .pl-table td { border:none; padding:6px 16px; }
        .pl-table td::before { content:attr(data-label); display:block; font-size:.66rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:var(--pl-muted); margin-bottom:2px; } }
</style>

<div class="pl-crm">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <h4 class="fw-semibold mb-1"><i class="ti ti-wand me-2"></i>Project Planner Leads</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Project Planner</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="pl-head">
        <div>
            <h2 class="pl-title">Planner Sessions <span class="pl-count">{{ $sessions->total() }}</span></h2>
            <p class="pl-sub">Leads captured by Alpha Blueprint AI — search, filter and follow up.</p>
        </div>
    </div>

    {{-- Filter toolbar --}}
    <form method="GET" action="{{ route('admin.planner.index') }}" id="plFilters" class="pl-filters">
        <div class="row align-items-end">
            <div class="col-lg-4 col-md-12">
                <div class="pl-field">
                    <label>Search</label>
                    <div class="pl-search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" name="q" value="{{ $q }}" class="pl-input" placeholder="Name, email, phone, goal, region…">
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="pl-field"><label>From date</label>
                    <input type="date" name="from" value="{{ $from }}" class="pl-input pl-auto" max="{{ now()->toDateString() }}">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="pl-field"><label>To date</label>
                    <input type="date" name="to" value="{{ $to }}" class="pl-input pl-auto" max="{{ now()->toDateString() }}">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-7">
                <div class="pl-field"><label>Goal</label>
                    <select name="goal" class="pl-select pl-auto">
                        <option value="all">All goals</option>
                        @foreach($goals as $g)
                            <option value="{{ $g }}" {{ $goal === $g ? 'selected' : '' }}>{{ \Illuminate\Support\Str::limit($g, 40) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-5">
                <div class="pl-field"><label>Status</label>
                    <select name="status" class="pl-select pl-auto">
                        <option value="all">All</option>
                        <option value="new" {{ $status==='new'?'selected':'' }}>New</option>
                        <option value="contacted" {{ $status==='contacted'?'selected':'' }}>Contacted</option>
                        <option value="closed" {{ $status==='closed'?'selected':'' }}>Closed</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="pl-btn pl-btn-primary"><i class="bi bi-funnel-fill"></i> Apply filters</button>
            <a href="{{ route('admin.planner.index') }}" class="pl-btn pl-btn-ghost"><i class="bi bi-x-lg"></i> Clear</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="pl-card">
        <div class="table-responsive">
            <table class="pl-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Goal &amp; project</th>
                        <th>Areas</th>
                        <th>Contact</th>
                        <th style="width:140px">Status</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sessions as $s)
                        <tr data-href="{{ route('admin.planner.show', $s->id) }}">
                            <td data-label="Date">
                                <span class="pl-date">{{ $s->created_at->format('M d, Y') }}<small>{{ $s->created_at->format('h:i A') }}</small></span>
                            </td>
                            <td data-label="Goal & project">
                                <div class="pl-goal">
                                    {{ \Illuminate\Support\Str::limit($s->intent ?? '—', 48) }}
                                    <span class="pl-eng {{ $s->engine === 'ai' ? 'pl-eng-ai' : 'pl-eng-rules' }}">{{ strtoupper($s->engine) }}</span>
                                </div>
                                <div class="pl-where">
                                    {{ $s->region ?: '—' }}@if($s->facility_type) · {{ $s->facility_type }}@endif
                                </div>
                            </td>
                            <td data-label="Areas">
                                @php $areas = array_values(array_filter((array) $s->selected_services)); @endphp
                                @forelse(array_slice($areas, 0, 3) as $area)
                                    <span class="pl-chip">{{ \Illuminate\Support\Str::limit($area, 22) }}</span>
                                @empty
                                    <span class="text-muted small">—</span>
                                @endforelse
                                @if(count($areas) > 3)<span class="pl-chip pl-chip-more">+{{ count($areas) - 3 }}</span>@endif
                            </td>
                            <td data-label="Contact">
                                @if($s->email)
                                    <div class="pl-contact"><b>{{ $s->name }}</b><span>{{ $s->email }}</span><span>{{ $s->phone }}</span></div>
                                @else
                                    <span class="text-muted small">No contact</span>
                                @endif
                                @if($s->meeting_at)
                                    <span class="pl-meet"><i class="bi bi-calendar-event"></i> {{ \Illuminate\Support\Carbon::parse($s->meeting_at)->format('M d, h:i A') }}</span>
                                @endif
                            </td>
                            <td data-label="Status">
                                <form action="{{ route('admin.planner.update', $s->id) }}" method="POST" class="pl-no-row">
                                    @csrf
                                    <select name="status" class="pl-status-sel pl-status-{{ $s->status }}" onchange="this.form.submit()">
                                        <option value="new" {{ $s->status=='new'?'selected':'' }}>New</option>
                                        <option value="contacted" {{ $s->status=='contacted'?'selected':'' }}>Contacted</option>
                                        <option value="closed" {{ $s->status=='closed'?'selected':'' }}>Closed</option>
                                    </select>
                                </form>
                            </td>
                            <td data-label="Actions">
                                <div class="pl-actions pl-no-row">
                                    <a href="{{ route('admin.planner.show', $s->id) }}" class="pl-ico pl-ico-view" title="View"><i class="bi bi-eye"></i></a>
                                    @if($s->inquiry_id)
                                        <a href="{{ route('admin.inquiries.show', $s->inquiry_id) }}" class="pl-ico pl-ico-inq" title="Linked inquiry"><i class="bi bi-envelope"></i></a>
                                    @endif
                                    <button type="button" class="pl-ico pl-ico-del" title="Delete" onclick="plannerDelete({{ $s->id }})"><i class="bi bi-trash"></i></button>
                                    <form id="plannerDel-{{ $s->id }}" action="{{ route('admin.planner.destroy', $s->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="pl-empty"><i class="bi bi-inbox"></i>No planner sessions match your filters.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $sessions->links() }}</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Auto-submit when a date or dropdown filter changes (fluid filtering).
    document.querySelectorAll('#plFilters .pl-auto').forEach(function(el){
        el.addEventListener('change', function(){ document.getElementById('plFilters').submit(); });
    });
    // Make whole rows clickable to open the session — but not when clicking controls.
    document.querySelectorAll('.pl-table tbody tr[data-href]').forEach(function(tr){
        tr.addEventListener('click', function(e){
            if (e.target.closest('.pl-no-row, a, button, select, form')) return;
            window.location = tr.getAttribute('data-href');
        });
    });
    function plannerDelete(id) {
        Swal.fire({ title: 'Delete this session?', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc3545', confirmButtonText: 'Yes, delete' })
            .then(r => { if (r.isConfirmed) document.getElementById('plannerDel-' + id).submit(); });
    }
    @if(session('success'))
        Swal.fire({ title: 'Done', text: @json(session('success')), icon: 'success', timer: 2500, timerProgressBar: true });
    @endif
</script>
@endsection
