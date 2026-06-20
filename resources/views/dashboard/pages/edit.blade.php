@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <h4 class="fw-semibold mb-1"><i class="ti ti-edit me-2"></i>Edit Page — {{ $page->label() }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.pages.index') }}">Pages &amp; SEO</a></li>
                <li class="breadcrumb-item active">{{ $page->label() }}</li>
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
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('admin.pages.update', $page->page_key) }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- SEO --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3"><i class="ti ti-search me-1"></i> SEO Tags</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ $page->meta_title }}"
                        placeholder="Page title shown in search results & browser tab">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="form-control"
                        placeholder="Short description for search engines (≈155 chars)">{{ $page->meta_description }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Meta Keywords</label>
                    <textarea name="meta_keywords" rows="3" class="form-control"
                        placeholder="Comma-separated keywords">{{ $page->meta_keywords }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Social Share / OG Image</label>
                    @if($page->og_image)
                        <div class="mb-2"><img src="{{ asset('public/uploads/page_images/' . $page->og_image) }}" alt="OG" style="max-height:90px;border-radius:8px;border:1px solid #e2e8f0;"></div>
                    @endif
                    <input type="file" name="og_image" class="form-control" accept="image/*">
                    <div class="form-text">Shown when the page is shared on social media. Leave empty to keep current.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hero (only for pages that have one) --}}
    @if($page->hasHero())
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3"><i class="ti ti-photo me-1"></i> Hero Section</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Hero Background Image</label>
                    @if($page->hero_image)
                        <div class="mb-2"><img src="{{ asset('public/uploads/page_images/' . $page->hero_image) }}" alt="Hero" style="width:100%;max-height:120px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;"></div>
                    @endif
                    <input type="file" name="hero_image" class="form-control" accept="image/*">
                    <div class="form-text">Sits behind a dark overlay. Leave empty to keep current.</div>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Eyebrow / Tag</label>
                            <input type="text" name="hero_eyebrow" class="form-control" value="{{ $page->hero_eyebrow }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="hero_title" class="form-control" value="{{ $page->hero_title }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Highlighted Subtitle</label>
                            <input type="text" name="hero_subtitle" class="form-control" value="{{ $page->hero_subtitle }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hero Description</label>
                            <textarea name="hero_description" rows="3" class="form-control">{{ $page->hero_description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="d-flex gap-2 mb-5">
        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy me-1"></i> Save</button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-light">Back</a>
    </div>
</form>
@endsection
