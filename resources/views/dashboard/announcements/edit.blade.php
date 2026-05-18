@extends('dashboard.layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Edit Announcement</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a class="text-muted"
                                    href="{{ route('announcements.index') }}">Announcements</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('announcements.update', $announcement->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                {{-- We use POST with hidden update method for safety if needed, but controller usually expects POST/PUT
                --}}
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $announcement->title) }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4"
                            required>{{ old('description', $announcement->description) }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" class="form-control"
                            value="{{ old('button_text', $announcement->button_text) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Button Link</label>
                        <input type="text" name="button_link" class="form-control"
                            value="{{ old('button_link', $announcement->button_link) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branding/Logo Image</label>
                        <input type="file" name="image" class="form-control mb-2">
                        @if($announcement->image)
                            <img src="{{ asset('public/uploads/announcements/' . $announcement->image) }}" alt="current"
                                width="100" class="rounded border">
                        @endif
                    </div>
                    <div class="col-md-3 mb-3">
    <label class="form-label d-block">Status</label>
    <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" name="status" id="statusSwitch"
            value="1" {{ old('status', $announcement->status) ? 'checked' : '' }}>
        <label class="form-check-label" for="statusSwitch">Active</label>
    </div>
</div>

<div class="col-md-3 mb-3">
    <label class="form-label d-block">Feature</label>
    <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" name="feature" id="featureswitch"
            value="1" {{ old('feature', $announcement->feature) ? 'checked' : '' }}>
        <label class="form-check-label" for="featureswitch">Active</label>
    </div>
</div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-4">Update Announcement</button>
                        <a href="{{ route('announcements.index') }}" class="btn btn-light-danger ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection