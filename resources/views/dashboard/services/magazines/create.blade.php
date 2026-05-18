@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Add Magazine Item</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('services.index') }}">Services</a></li>
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('services.edit', $service->id) }}">Edit: {{ $service->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Magazine</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('service.magazines.store', $service->id) }}" method="POST"
                        enctype="multipart/form-data" id="mag-create-form">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="mag_title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" placeholder="Magazine title">
                            <div class="invalid-feedback d-none" id="title-error">Title is required.</div>
                            @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            {{-- NOTE: NO "required" attribute — TinyMCE hides the real textarea, browser cannot validate it --}}
                            <textarea name="description" id="mag_description_create" rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Magazine description">{{ old('description') }}</textarea>
                            <div class="text-danger small mt-1 d-none" id="desc-error">Description is required.</div>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                            <small class="text-muted">Max 4MB. Formats: JPG, PNG, GIF, WEBP.</small>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-5" id="mag-save-btn">
                                <span class="spinner-border spinner-border-sm d-none me-2" id="mag-spinner"></span>
                                Save Magazine Item
                            </button>
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-secondary px-5">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: '#mag_description_create',
        plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons fullscreen',
        toolbar: "code undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
        height: 350,
        promotion: false,
        branding: false,
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/upload-image',
        setup: function(editor) {
            // When TinyMCE is ready, remove browser-native validation from textarea
            editor.on('init', function() {
                document.getElementById('mag_description_create').removeAttribute('required');
            });
        }
    });

    document.getElementById('mag-create-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // 1. Sync TinyMCE content back to textarea
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

        // 2. Manual validation
        let valid = true;

        const titleVal = document.getElementById('mag_title').value.trim();
        const titleError = document.getElementById('title-error');
        if (!titleVal) {
            titleError.classList.remove('d-none');
            document.getElementById('mag_title').classList.add('is-invalid');
            valid = false;
        } else {
            titleError.classList.add('d-none');
            document.getElementById('mag_title').classList.remove('is-invalid');
        }

        const descVal = document.getElementById('mag_description_create').value.trim();
        const descError = document.getElementById('desc-error');
        // Strip HTML tags to check for meaningful content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = descVal;
        const plainText = tempDiv.textContent || tempDiv.innerText || '';
        if (!plainText.trim()) {
            descError.classList.remove('d-none');
            valid = false;
        } else {
            descError.classList.add('d-none');
        }

        if (!valid) return;

        // 3. Show loading spinner
        document.getElementById('mag-spinner').classList.remove('d-none');
        document.getElementById('mag-save-btn').disabled = true;

        // 4. Submit
        this.submit();
    });
</script>
@endsection
