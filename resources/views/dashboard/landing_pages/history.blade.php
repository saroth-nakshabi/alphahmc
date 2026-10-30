@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <h4 class="fw-semibold mb-1"><i class="ti ti-history me-2"></i>History: {{ $page->name }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.landing-pages.index') }}">Landing pages</a></li>
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.landing-pages.edit', $page->id) }}">{{ $page->name }}</a></li>
                <li class="breadcrumb-item active">History</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <p class="text-muted small">Every save keeps a full copy of the page: copy, SEO fields, status and hero image. Restoring a version saves it as the newest one, so the version you replace stays in this list.</p>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Saved</th><th>By</th><th>Note</th><th>Status then</th><th class="text-end">Restore</th></tr></thead>
                <tbody>
                @foreach($revisions as $rev)
                    <tr>
                        <td class="text-nowrap">{{ $rev->created_at->format('j M Y, H:i') }} @if($loop->first && $revisions->onFirstPage())<span class="badge bg-light-info text-info ms-1">Current</span>@endif</td>
                        <td class="small">{{ $rev->user_name ?: 'Unknown' }}</td>
                        <td class="small">{{ $rev->note }}</td>
                        <td class="small">{{ ($rev->snapshot['status'] ?? '') === 'published' ? 'Published' : 'Draft' }}</td>
                        <td class="text-end">
                            @unless($loop->first && $revisions->onFirstPage())
                            @can('edit landing pages')
                            <form method="POST" action="{{ route('admin.landing-pages.restore', [$page->id, $rev->id]) }}" onsubmit="return confirm('Restore the version of {{ $rev->created_at->format('j M Y, H:i') }}? It replaces the current copy, SEO fields, status and hero image.');">
                                @csrf
                                <button class="btn btn-sm btn-outline-info"><i class="ti ti-arrow-back-up me-1"></i>Restore</button>
                            </form>
                            @endcan
                            @endunless
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $revisions->links() }}</div>
    </div>
</div>
@endsection
