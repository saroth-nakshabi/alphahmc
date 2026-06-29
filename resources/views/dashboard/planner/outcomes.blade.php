@extends('dashboard/layout')

@section('content')
<style>
    .oc-accent { color:#066D77; }
    .oc-badge-ai        { background:#e9ecef; color:#495057; font-size:.7rem; padding:2px 8px; border-radius:100px; }
    .oc-badge-mapped    { background:#cfe2ff; color:#084298; font-size:.7rem; padding:2px 8px; border-radius:100px; }
    .oc-badge-reviewed  { background:#d1e7dd; color:#0a3622; font-size:.7rem; padding:2px 8px; border-radius:100px; }
</style>

<div class="container-fluid pt-3 pb-5">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-0">Planner Outcomes</h4>
            <p class="text-muted small mb-0">Review AI-generated outcomes, add consultant edits, and approve for reuse.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.planner.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-list-ul me-1"></i> All Sessions
            </a>
            <a href="{{ route('admin.planner.outcomes') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-repeat me-1"></i> Reuse Cache ({{ $cache->total() }})
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Sessions with AI output --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Sessions with AI output <span class="badge bg-light text-dark ms-1">{{ $sessions->total() }}</span></h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name / Contact</th>
                            <th>Intent / Region</th>
                            <th>Source</th>
                            <th>Reviewed</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $s)
                        <tr>
                            <td class="text-muted small">{{ $s->id }}</td>
                            <td>
                                <strong>{{ $s->name ?: '—' }}</strong>
                                @if($s->email) <div class="text-muted small">{{ $s->email }}</div> @endif
                            </td>
                            <td>
                                <div class="small">{{ $s->intent ?: '—' }}</div>
                                @if($s->region) <div class="text-muted small">{{ $s->region }}</div> @endif
                            </td>
                            <td>
                                @php $src = $s->process_source ?? 'ai_generated'; @endphp
                                @if($src === 'consultant_reviewed')
                                    <span class="oc-badge-reviewed">consultant reviewed</span>
                                @elseif($src === 'process_mapped')
                                    <span class="oc-badge-mapped">process mapped</span>
                                @else
                                    <span class="oc-badge-ai">ai generated</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                @if($s->consultant_reviewed_at)
                                    {{ $s->consultant_reviewed_at->format('d M Y') }}
                                    @if($s->consultant) <div>{{ $s->consultant->first_name }}</div> @endif
                                @else
                                    <span class="text-warning">Pending</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $s->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.planner.show', $s->id) }}" class="btn btn-sm btn-outline-primary">
                                    Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No sessions with AI output yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sessions->hasPages())
            <div class="mt-3">{{ $sessions->links('partials.pagination-numbered') }}</div>
            @endif
        </div>
    </div>

    {{-- Outcome cache --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Approved reuse cache <span class="badge bg-success text-white ms-1">{{ $cache->total() }}</span></h5>
            @if($cache->isEmpty())
                <p class="text-muted mb-0">No approved outcomes yet. Open a session above, add a consultant outcome, then click <strong>Approve for reuse</strong>.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Intent key</th>
                            <th>Region key</th>
                            <th>Categories</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cache as $c)
                        <tr>
                            <td class="text-muted small">{{ $c->id }}</td>
                            <td><code class="small">{{ $c->intent_key }}</code></td>
                            <td><code class="small">{{ $c->region_key ?: '—' }}</code></td>
                            <td class="small text-muted" style="max-width:260px;">
                                @php
                                    $cats = json_decode($c->category_fingerprint ?? '[]', true) ?: [];
                                    echo e(implode(', ', array_slice($cats, 0, 5)));
                                    if(count($cats) > 5) echo ' +' . (count($cats) - 5) . ' more';
                                @endphp
                            </td>
                            <td class="text-muted small">{{ $c->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($cache->hasPages())
            <div class="mt-3">{{ $cache->links('partials.pagination-numbered') }}</div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection
