@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <h4 class="fw-semibold mb-1"><i class="ti ti-file-text me-2"></i>Pages &amp; SEO</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pages &amp; SEO</li>
            </ol>
        </nav>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <p class="text-muted small mb-3">
            Manage the SEO tags for each standard page, and the hero (image + heading) for pages that have one.
            Detail pages (services, categories, service groups) keep their own SEO inside their own editors.
        </p>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Page</th>
                        <th>Meta Title</th>
                        <th class="text-center" style="width:90px">Hero</th>
                        <th style="width:190px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <td class="fw-semibold">{{ $page->label() }}</td>
                            <td class="text-muted small">{{ Str::limit($page->meta_title, 70) ?: '—' }}</td>
                            <td class="text-center">
                                @if($page->hasHero())
                                    <span class="badge bg-light-success text-success">Yes</span>
                                @else
                                    <span class="badge bg-light-secondary text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pages.edit', $page->page_key) }}" class="btn btn-sm btn-info">
                                    <i class="ti ti-edit"></i> Edit
                                </a>
                                @if($page->publicUrl())
                                    <a href="{{ $page->publicUrl() }}" target="_blank" rel="noopener"
                                       class="btn btn-sm btn-outline-secondary" title="Open the live page in a new tab">
                                        <i class="ti ti-external-link"></i> View
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
