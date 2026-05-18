@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Edit Magazine Item</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('services.index') }}">Services</a></li>
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('services.edit', $service->id) }}">Edit: {{ $service->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Magazine</li>
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

                    <form action="{{ route('service.magazines.update', [$service->id, $magazine->id]) }}"
                        method="POST" enctype="multipart/form-data" id="mag-edit-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="mag_title_edit"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $magazine->title) }}" placeholder="Magazine title">
                            <div class="invalid-feedback d-none" id="title-error-edit">Title is required.</div>
                            @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            {{-- NO "required" attribute — TinyMCE replaces this textarea in the DOM --}}
                            <textarea name="description" id="mag_description_edit" rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Magazine description">{{ old('description', $magazine->description) }}</textarea>
                            <div class="text-danger small mt-1 d-none" id="desc-error-edit">Description is required.</div>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Image</label>
                            @if ($magazine->image)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('public/uploads/magazines/' . $magazine->image) }}"
                                        alt="{{ $magazine->title }}" width="120" class="rounded border">
                                    <small class="text-muted">Current image. Upload a new one to replace it.</small>
                                </div>
                            @endif
                            <input type="file" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                            <small class="text-muted">Max 4MB. Formats: JPG, PNG, GIF, WEBP. Leave blank to keep current image.</small>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-5" id="mag-edit-btn">
                                <span class="spinner-border spinner-border-sm d-none me-2" id="mag-edit-spinner"></span>
                                Update Magazine Item
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
        selector: '#mag_description_edit',
        plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons fullscreen',
        toolbar: "code undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
        height: 350,
        promotion: false,
        branding: false,
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/upload-image',
        setup: function(editor) {
            editor.on('init', function() {
                // Remove browser required so it doesn't block form submit
                document.getElementById('mag_description_edit').removeAttribute('required');
            });
        }
    });

    document.getElementById('mag-edit-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // 1. Sync TinyMCE content → textarea
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

        // 2. Manual validation
        let valid = true;

        const titleVal = document.getElementById('mag_title_edit').value.trim();
        const titleError = document.getElementById('title-error-edit');
        if (!titleVal) {
            titleError.classList.remove('d-none');
            document.getElementById('mag_title_edit').classList.add('is-invalid');
            valid = false;
        } else {
            titleError.classList.add('d-none');
            document.getElementById('mag_title_edit').classList.remove('is-invalid');
        }

        const descVal = document.getElementById('mag_description_edit').value.trim();
        const descError = document.getElementById('desc-error-edit');
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

        // 3. Loading state
        document.getElementById('mag-edit-spinner').classList.remove('d-none');
        document.getElementById('mag-edit-btn').disabled = true;

        // 4. Submit
        this.submit();
    });
</script>
@endsection
