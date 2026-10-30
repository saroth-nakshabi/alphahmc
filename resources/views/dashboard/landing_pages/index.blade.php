@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3 d-flex flex-wrap align-items-center gap-2">
        <div class="me-auto">
            <h4 class="fw-semibold mb-1"><i class="ti ti-file-text me-2"></i>Landing pages</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Landing pages</li>
                </ol>
            </nav>
        </div>
        @can('create landing pages')
            <a class="btn btn-info btn-sm" href="{{ route('admin.landing-pages.create') }}"><i class="ti ti-plus me-1"></i>New landing page</a>
        @endcan
    </div>
</div>

@foreach(['success' => 'success', 'error' => 'danger'] as $key => $class)
    @if(session($key))
        <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">
            {{ session($key) }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach

<div class="card shadow-sm border-0">
    <div class="card-body">
        <p class="text-muted small">Ad landing pages at <code>/facility-setup/…</code>. Edit the copy, SEO fields and hero image here; the layout and conversion tracking stay in code. Drafts are visible only to signed-in admins.</p>
        @if($pages->isEmpty())
            <p class="mb-0">No landing pages yet.</p>
        @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Page</th><th>URL</th><th>Status</th><th>Conversions</th><th>Last saved</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @foreach($pages as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->name }}</td>
                        <td><a href="{{ $p->url() }}" target="_blank" rel="noopener" class="small">/facility-setup/{{ $p->slug }}</a></td>
                        <td>
                            @if($p->isPublished())<span class="badge bg-light-success text-success">Published</span>
                            @else<span class="badge bg-light-secondary text-muted">Draft</span>@endif
                            @unless($p->indexable)<span class="badge bg-light-warning text-warning">Noindex</span>@endunless
                        </td>
                        <td class="small">{{ $p->lead_type === 'facility' ? 'Facility' : 'Professional' }}</td>
                        <td class="small text-muted">{{ $p->updated_at->format('j M Y, H:i') }}</td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-info" href="{{ route('admin.landing-pages.edit', $p->id) }}"><i class="ti ti-edit me-1"></i>Edit</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.landing-pages.history', $p->id) }}" title="History" aria-label="History of {{ $p->name }}"><i class="ti ti-history"></i></a>
                            @can('create landing pages')
                            <form class="d-inline" method="POST" action="{{ route('admin.landing-pages.duplicate', $p->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary" title="Duplicate" aria-label="Duplicate {{ $p->name }}"><i class="ti ti-copy"></i></button>
                            </form>
                            @endcan
                            @can('delete landing pages')
                            @unless($p->isPublished())
                            <form class="d-inline" method="POST" action="{{ route('admin.landing-pages.destroy', $p->id) }}" onsubmit="return confirm('Delete {{ addslashes($p->name) }} and its history? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete {{ $p->name }}"><i class="ti ti-trash"></i></button>
                            </form>
                            @endunless
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
