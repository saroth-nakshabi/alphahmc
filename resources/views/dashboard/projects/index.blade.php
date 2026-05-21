@extends('dashboard/layout')

@section('custom_css')
    <!-- --------------------------------------------------- -->
    <!-- Prism Js -->
    <!-- --------------------------------------------------- -->
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
@endsection

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">All Projects</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="./index.html">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Other</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('public/dashboard/dist/images/breadcrumb/ChatBc.png') }}" alt=""
                            class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="datatables">
        <!-- File export -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex">
                            <h5 class="mb-0">Projects List</h5>

                            <button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addNewModal">
                                <i class="ti ti-plus me-1"></i>
                                Add New
                            </button>

                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @if (isset($Projects) && count($Projects) > 0)
                                        @foreach ($Projects as $Project)
                                            <!-- start row -->
                                            <tr data-id="{{ $Project->id }}">
                                                <td>{{ $Project->name }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @can('edit tags')
                                                                <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                        data-id="{{ $Project->id }}">Edit</a></li>
                                                            @endcan
                                                            @can('delete tags')
                                                                <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                        data-id="{{ $Project->id }}">Delete</a></li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- end row -->
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <!-- start row -->
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                    <!-- end row -->
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- add new modal -->
    <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="{{ route('project.store') }}" method="POST" id="add_form"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Add New Project
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="mb-3 p-3 rounded" style="background:#fff8ed;border:1px solid #ffd080;">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="featured" id="featured" value="1">
                                    <label class="form-check-label fw-semibold" for="featured">
                                        ⭐ Feature this project
                                    </label>
                                    <div class="text-muted small mt-1">Only one project can be featured at a time. Enabling this will unfeature the current one.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">project category <span
                                        class="text-danger">*</span></label>
                                <select name="project_category" class="form-control select2"
                                    data-placeholder="Select project category" required>
                                    <option></option>
                                    @if (isset($projectsCategories) && $projectsCategories->count())
                                        @foreach ($projectsCategories as $projectsCategory)
                                            <option value="{{ $projectsCategory->id }}"
                                                {{ isset($selectedCategoryId) && $selectedCategoryId == $projectsCategory->id ? 'selected' : '' }}>
                                                {{ $projectsCategory->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Images <span class="text-danger">*</span></label>
                                <input type="file" name="image[]" class="form-control" multiple required />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Videos</label>
                                <input type="file" name="video[]" class="form-control" multiple />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Video Covers</label>
                                <input type="file" name="video_thumbnail[]" class="form-control" multiple />
                                <small class="text-muted">Select covers in the same order as your videos.</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Documents</label>
                                <input type="file" name="document[]" class="form-control" multiple />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">project Title <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="project Title Name" required />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Description <span class="text-danger">*</span></label>
                                <textarea type="textarea" id="description" name="description" rows="5" class="rich-textarea form-control"
                                    placeholder="type here..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Slug <span class="text-danger">*</span></label>
                                <input type="text" id="slug" name="slug" class="form-control"
                                    placeholder="Slug" required />
                            </div>
                        </div>

                        <div class="col-12 mt-2 mb-2">
                            <p class="fw-semibold text-muted mb-0" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;">Project Details</p>
                            <hr class="mt-1 mb-3">
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Client Name</label>
                                <input type="text" name="client_name" class="form-control" placeholder="e.g. Dubai Health Authority" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Duration</label>
                                <input type="text" name="project_duration" class="form-control" placeholder="e.g. 18 months" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Location</label>
                                <input type="text" name="project_location" class="form-control" placeholder="e.g. Dubai, UAE" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Regulatory Authority</label>
                                <input type="text" name="regulatory_authority" class="form-control" placeholder="e.g. UAE DOH / MOH" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Client Website</label>
                                <input type="text" name="client_website" class="form-control" placeholder="e.g. https://www.example.com" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Scope</label>
                                <textarea name="project_scope" rows="3" class="form-control" placeholder="Brief description of the project scope..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Services Delivered</label>
                                <select name="service_ids[]" class="form-control select2-services" multiple data-placeholder="Select services delivered">
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Challenges Section Title</label>
                                <input type="text" name="challenge_heading" class="form-control" placeholder="e.g. Challenges & Solutions" />
                                <small class="text-muted">Leave blank to hide this section entirely.</small>
                            </div>
                        </div>

                        <div id="challenge-sections-container">
                            <div class="challenge-section-item mb-3" data-challenge-index="0">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="control-label mb-1">Challenge Title</label>
                                        <input type="text" id="challenge_title_0" name="challenge_title[]" class="form-control"
                                            placeholder="Challenge Title Name" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="control-label mb-1">Challenge</label>
                                        <textarea type="textarea" id="challenge_0" name="challenge[]" rows="5" class="rich-textarea form-control"
                                            placeholder="type here..."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="control-label mb-1">Resolution</label>
                                        <textarea type="textarea" id="resolution_0" name="resolution[]" rows="5" class="rich-textarea form-control"
                                            placeholder="type here..."></textarea>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-challenge-section d-none">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-2" id="addChallengeBtn">Add More Challenge</button>
                        {{-- <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Meta Title <span class="text-danger">*</span></label>
                                <input type="text" id="meta_title" name="meta_title" class="form-control"
                                    placeholder="Meta Title" required />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Meta Description <span
                                        class="text-danger">*</span></label>
                                <textarea type="textarea" id="meta_description" name="meta_description" rows="5"
                                    class="rich-textarea form-control" placeholder="Meta Description" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Meta Keywords <span class="text-danger">*</span></label>
                                <input type="text" id="meta_keywords" name="meta_keywords" class="form-control"
                                    placeholder="Meta Keywords" required />
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <form class="modal-content" action="#" method="POST" id="edit_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">
                        Edit Project
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="mb-3 p-3 rounded" style="background:#fff8ed;border:1px solid #ffd080;">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="featured" id="edit_featured" value="1">
                                    <label class="form-check-label fw-semibold" for="edit_featured">
                                        ⭐ Feature this project
                                    </label>
                                    <div class="text-muted small mt-1">Only one project can be featured at a time. Enabling this will unfeature the current one.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Category <span
                                        class="text-danger">*</span></label>
                                {{-- <select name="edit_project_category" class="form-control select2"
                                    data-placeholder="Select project category" required>
                                    <option></option>
                                    @if (isset($projectsCategories) && count($projectsCategories) > 0)
                                        @foreach ($projectsCategories as $projectsCategory)
                                            <option value="{{ $projectsCategory->id }}">
                                                {{ $projectsCategory->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select> --}}

                                <select name="project_category_id" class="form-control select2"
                                    data-placeholder="Select project category" required>
                                    <option value="">Select project category</option>
                                    @if (isset($projectsCategories) && count($projectsCategories) > 0)
                                        @foreach ($projectsCategories as $projectsCategory)
                                            <option value="{{ $projectsCategory->id }}">
                                                {{ $projectsCategory->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>


                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Images</label>
                                <input type="file" name="image[]" class="form-control" multiple />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Videos</label>
                                <input type="file" name="video[]" class="form-control" multiple />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Video Covers</label>
                                <input type="file" name="video_thumbnail[]" class="form-control" multiple />
                                <small class="text-muted">Select covers in the same order as your videos.</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Documents</label>
                                <input type="file" name="document[]" class="form-control" multiple />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Title <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Project Title Name" required />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Description <span class="text-danger">*</span></label>
                                <textarea type="textarea" id="edit_description" name="description" rows="5"
                                    class="rich-textarea form-control" placeholder="Type here..." required></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Slug</label>
                                <input type="text" id="edit_slug" name="slug" class="form-control"
                                    placeholder="Slug" />
                            </div>
                        </div>

                        <div class="col-12 mt-2 mb-2">
                            <p class="fw-semibold text-muted mb-0" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;">Project Details</p>
                            <hr class="mt-1 mb-3">
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Client Name</label>
                                <input type="text" id="edit_client_name" name="client_name" class="form-control" placeholder="e.g. Dubai Health Authority" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Duration</label>
                                <input type="text" id="edit_project_duration" name="project_duration" class="form-control" placeholder="e.g. 18 months" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Location</label>
                                <input type="text" id="edit_project_location" name="project_location" class="form-control" placeholder="e.g. Dubai, UAE" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="control-label mb-1">Regulatory Authority</label>
                                <input type="text" id="edit_regulatory_authority" name="regulatory_authority" class="form-control" placeholder="e.g. UAE DOH / MOH" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Client Website</label>
                                <input type="text" id="edit_client_website" name="client_website" class="form-control" placeholder="e.g. https://www.example.com" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Project Scope</label>
                                <textarea id="edit_project_scope" name="project_scope" rows="3" class="form-control" placeholder="Brief description of the project scope..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Services Delivered</label>
                                <select id="edit_service_ids" name="service_ids[]" class="form-control select2-edit-services" multiple data-placeholder="Select services delivered">
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Challenges Section Title</label>
                                <input type="text" id="edit_challenge_heading" name="challenge_heading" class="form-control" placeholder="e.g. Challenges & Solutions" />
                                <small class="text-muted">Leave blank to hide this section entirely.</small>
                            </div>
                        </div>

                        <div id="edit-challenge-sections-container">
                            <div class="challenge-section-item mb-3" data-challenge-index="0">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="control-label mb-1">Challenge Title</label>
                                        <input type="text" id="edit_challenge_title_0" name="challenge_title[]" class="form-control"
                                            placeholder="Challenge Title Name" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="control-label mb-1">Challenge</label>
                                        <textarea type="textarea" id="edit_challenge_0" name="challenge[]" rows="5" class="rich-textarea form-control"
                                            placeholder="type here..."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="control-label mb-1">Resolution</label>
                                        <textarea type="textarea" id="edit_resolution_0" name="resolution[]" rows="5" class="rich-textarea form-control"
                                            placeholder="type here..."></textarea>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-challenge-section d-none">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-2" id="editAddChallengeBtn">Add More Challenge</button>
                        {{-- <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Meta Title</label>
                                <input type="text" id="meta_title" name="meta_title" class="form-control"
                                    placeholder="Meta Title" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Meta Description</label>
                                <textarea type="textarea" id="meta_description" name="meta_description" rows="5" class="form-control"
                                    placeholder="Meta Description"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Meta Keywords</label>
                                <textarea type="textarea" id="meta_keywords" name="meta_keywords" rows="5" class="form-control"
                                    placeholder="Meta Keywords"></textarea>
                            </div>
                        </div> --}}


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <!-- ---------------------------------------------- -->
    <!-- core files -->
    <!-- ---------------------------------------------- -->
    <script src="{{ asset('public/dashboard/dist/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>

    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->

    <script>
        $(document).ready(function() {
            var items_table = $("#items-table").DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");

            tinymce.init({
                selector: '.rich-textarea', // Target textareas by their class
                plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
                toolbar: "code undo redo print spellcheckdialog | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
                image_title: true,
                automatic_uploads: true,
                images_upload_url: '/uploads/tinymce-image',
            });

            // select2 init — category dropdowns
            $('#addNewModal .select2').select2({
                dropdownParent: '#addNewModal',
                minimumResultsForSearch: 8,
            });
            $('#editModal .select2').select2({
                dropdownParent: '#editModal',
                minimumResultsForSearch: 8,
            });

            // select2 init — services multi-select (reinit on modal open to avoid z-index/focus issues)
            $('#addNewModal').on('shown.bs.modal', function() {
                var $sel = $(this).find('.select2-services');
                if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
                $sel.select2({
                    dropdownParent: $(this),
                    placeholder: 'Select services delivered',
                    allowClear: true,
                });
            });

            $('#editModal').on('shown.bs.modal', function() {
                var $sel = $(this).find('.select2-edit-services');
                if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
                $sel.select2({
                    dropdownParent: $(this),
                    placeholder: 'Select services delivered',
                    allowClear: true,
                });
                // Apply any pending service IDs stored before the modal opened
                var pendingIds = $('#editModal').data('pending-service-ids');
                if (pendingIds !== undefined) {
                    $sel.val(pendingIds).trigger('change');
                    $('#editModal').removeData('pending-service-ids');
                }
            });

            let challengeIndex = 1;
            let editChallengeIndex = 1;

            function initChallengeEditor(selector) {
                tinymce.init({
                    selector: selector,
                    plugins: 'code searchreplace autolink directionality visualblocks visualchars link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap emoticons autosave fullscreen',
                    toolbar: "code undo redo print spellcheckdialog | blocks fontfamily fontsize | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist | code",
                    image_title: true,
                    automatic_uploads: true,
                    images_upload_url: '/uploads/tinymce-image',
                });
            }

            function updateChallengeRemoveButtons(containerSelector) {
                const sections = $(`${containerSelector} .challenge-section-item`);
                sections.find('.remove-challenge-section').addClass('d-none');
                if (sections.length > 1) {
                    sections.find('.remove-challenge-section').removeClass('d-none');
                }
            }

            function appendChallengeSection(containerSelector, title = '', challenge = '', resolution = '', isEdit = false) {
                const prefix = isEdit ? 'edit_' : '';
                const index = isEdit ? editChallengeIndex : challengeIndex;
                const section = `
                    <div class="challenge-section-item mb-3" data-challenge-index="${index}">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Challenge Title</label>
                                <input type="text" id="${prefix}challenge_title_${index}" name="challenge_title[]" class="form-control" placeholder="Challenge Title Name" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Challenge</label>
                                <textarea id="${prefix}challenge_${index}" name="challenge[]" rows="5" class="rich-textarea form-control" placeholder="type here..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="control-label mb-1">Resolution</label>
                                <textarea id="${prefix}resolution_${index}" name="resolution[]" rows="5" class="rich-textarea form-control" placeholder="type here..."></textarea>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-challenge-section d-none">Remove</button>
                    </div>
                `;

                $(`#${containerSelector}`).append(section);
                $(`#${prefix}challenge_title_${index}`).val(title);
                $(`#${prefix}challenge_${index}`).val(challenge);
                $(`#${prefix}resolution_${index}`).val(resolution);
                initChallengeEditor(`#${prefix}challenge_${index}`);
                initChallengeEditor(`#${prefix}resolution_${index}`);

                if (isEdit) {
                    editChallengeIndex++;
                } else {
                    challengeIndex++;
                }
                updateChallengeRemoveButtons(`#${containerSelector}`);
            }

            function renderChallengeSections(containerSelector, data, isEdit = false) {
                const prefix = isEdit ? 'edit_' : '';
                const container = $(`#${containerSelector}`);
                container.find('textarea').each(function() {
                    const editorId = $(this).attr('id');
                    if (editorId && tinymce.get(editorId)) {
                        tinymce.get(editorId).remove();
                    }
                });
                container.empty();
                const items = Array.isArray(data) ? data : [];

                if (items.length > 0) {
                    items.forEach(function(item) {
                        appendChallengeSection(containerSelector, item.challenge_title || '', item.challenge || '', item.resolution || '', isEdit);
                    });
                } else {
                    appendChallengeSection(containerSelector, '', '', '', isEdit);
                }
            }

            $('#addChallengeBtn').on('click', function() {
                appendChallengeSection('challenge-sections-container', '', '', '', false);
            });

            $('#editAddChallengeBtn').on('click', function() {
                appendChallengeSection('edit-challenge-sections-container', '', '', '', true);
            });

            $(document).on('click', '.remove-challenge-section', function() {
                const section = $(this).closest('.challenge-section-item');
                section.find('textarea').each(function() {
                    const editorId = $(this).attr('id');
                    if (editorId && tinymce.get(editorId)) {
                        tinymce.get(editorId).remove();
                    }
                });
                section.remove();
                updateChallengeRemoveButtons('#challenge-sections-container');
                updateChallengeRemoveButtons('#edit-challenge-sections-container');
            });

            // Prevent native form submission regardless of validation state
            $('#add_form, #edit_form').on('submit', function(e) { e.preventDefault(); });

            // add form handle
            $("#add_form").validate({
                rules: {
                    name: {
                        required: true,
                    },

                    description: {
                        required: true,
                    },
                    slug: {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: "tag name is required",
                    },

                    description: {
                        required: "description   is required",
                    }

                },
                submitHandler: function(form) {
                    // Sync all TinyMCE editors to their textareas before collecting FormData
                    if (typeof tinymce !== 'undefined') { try { tinymce.triggerSave(); } catch(e) {} }
                    // Collect the form data
                    let formData = new FormData(form);
                    // Send AJAX request
                    $.ajax({
                        url: form.action, // Replace with your server-side endpoint
                        method: form.method,
                        data: formData,
                        processData: false, // Prevent jQuery from automatically processing the data
                        contentType: false, // Let the browser set the content type (required for FormData)
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Send CSRF token via header
                        },
                        beforeSend: function() {
                            // Show loading animation using SweetAlert
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we add',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            // Close the loading Swal
                            Swal.close();

                            // Show success message using SweetAlert mixin
                            Toast.fire({
                                icon: 'success',
                                title: `${response.message}`
                            });
                            const newRow = `<tr data-id='${response.data.id}'>
                                            <td>${response.data.name}</td>
                                            <td><div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Edit</a></li>
                                                            <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Delete</a></li>
                                                        </ul>
                                                    </div></td>
                                            </tr>`;
                            // Add new row to DataTable
                            items_table.row.add($(newRow)).draw();

                            // Close the modal
                            $('#addNewModal').modal(
                                'hide'); // Replace #addNewModal with your modal ID

                            // Clear the form
                            $('#add_form')[0].reset();
                            $('#addNewModal .select2-services').val(null).trigger('change');
                            challengeIndex = 1;
                            renderChallengeSections('challenge-sections-container', [], false);
                        },
                        error: function(xhr) {
                            // Close the loading Swal
                            Swal.close();

                            // Extract and display validation errors from the server
                            let errorMessages = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessages +=
                                        `<p class='text-danger'>${value}</p>`;
                                });
                            } else {
                                errorMessages = 'Something went wrong. Please try again.';
                            }

                            // Show error message with custom SweetAlert template
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to add',
                                html: `<div>${errorMessages}</div>`, // Display multiple error messages
                                customClass: {
                                    popup: 'swal-wide'
                                }
                            });
                        }
                    });
                }
            });

            // add form handle
            $("#edit_form").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    slug: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "tag name is required",
                    },
                    slug: {
                        required: "tag slug is required",
                    }
                },
                submitHandler: function(form) {
                    // Sync all TinyMCE editors to their textareas before collecting FormData
                    if (typeof tinymce !== 'undefined') { try { tinymce.triggerSave(); } catch(e) {} }
                    // Collect the form data
                    let formData = new FormData(form);
                    // Send AJAX request
                    $.ajax({
                        url: form.action, // Replace with your server-side endpoint
                        method: form.method,
                        data: formData,
                        processData: false, // Prevent jQuery from automatically processing the data
                        contentType: false, // Let the browser set the content type (required for FormData)
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Send CSRF token via header
                        },
                        beforeSend: function() {
                            // Show loading animation using SweetAlert
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we add',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            // Close the loading Swal
                            Swal.close();

                            // Show success message using SweetAlert mixin
                            Toast.fire({
                                icon: 'success',
                                title: `${response.message}`
                            });

                            let row = $('#items-table').find(
                                `tr[data-id='${response.data.id}']`);
                            row.html(`
                            <td>${response.data.name}</td>
                            <td><div class="btn-group">
                                                        <button class="dropdown-toggle btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item edit" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Edit</a></li>
                                                            <li><a class="dropdown-item delete" href="javascript:void(0);"
                                                                    data-id="${response.data.id}">Delete</a></li>
                                                        </ul>
                                                    </div></td>
                            `);
                            // destroy the table to reinitialize
                            items_table.destroy();
                            // re initialize the table
                            items_table = $("#items-table").DataTable({
                                dom: "Bfrtip",
                                buttons: ["copy", "csv", "excel", "pdf", "print"],
                            });
                            $(
                                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
                            ).addClass("btn btn-primary mr-1");

                            // Close the modal
                            $('#editModal').modal('toggle');
                        },
                        error: function(xhr) {
                            // Close the loading Swal
                            Swal.close();

                            // Extract and display validation errors from the server
                            let errorMessages = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessages +=
                                        `<p class='text-danger'>${value}</p>`;
                                });
                            } else {
                                errorMessages = 'Something went wrong. Please try again.';
                            }

                            // Show error message with custom SweetAlert template
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to add',
                                html: `<div>${errorMessages}</div>`, // Display multiple error messages
                                customClass: {
                                    popup: 'swal-wide'
                                }
                            });
                        }
                    });
                }
            });

            $(document).on('click', '.edit', function() {
                const id = $(this).data('id');
                const udpateUrl = '{{ route("project.update", ["id" => "__ID__"]) }}'.replace('__ID__', id);
                $('#edit_form').attr('action', udpateUrl);

                $.ajax({
                    url: `{{ route('project.getProject') }}`,
                    method: 'POST',
                    data: {
                        'id': id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Send CSRF token via header
                    },
                    success: function(response) {

                        const categoryId = response.data.project_category ? response.data.project_category.id : '';
                        $('#edit_form').find('[name="project_category_id"]').val(null).trigger('change');
                        $('#edit_form').find('[name="project_category_id"]').val(categoryId).trigger('change');

                        $('#editModal .select2').select2({
                            dropdownParent: $('#editModal'),
                            minimumResultsForSearch: 8,
                        });

                        $('#edit_form').find('#name').val(response.data.name);
                        $('#edit_form').find('#edit_slug').val(response.data.slug);
                        const descEditor = tinymce.get('edit_description');
                        if (descEditor) {
                            descEditor.setContent(response.data.description || '');
                        } else {
                            $('#edit_description').val(response.data.description || '');
                        }

                        // New detail fields
                        $('#edit_form').find('#edit_client_name').val(response.data.client_name || '');
                        $('#edit_form').find('#edit_project_duration').val(response.data.project_duration || '');
                        $('#edit_form').find('#edit_project_location').val(response.data.project_location || '');
                        $('#edit_form').find('#edit_regulatory_authority').val(response.data.regulatory_authority || '');
                        $('#edit_form').find('#edit_client_website').val(response.data.client_website || '');
                        $('#edit_form').find('#edit_project_scope').val(response.data.project_scope || '');

                        $('#edit_form').find('#edit_featured').prop('checked', !!response.data.featured);
                        $('#edit_form').find('#edit_challenge_heading').val(response.data.challenge_heading || '');

                        // Services multi-select — stored as data attr; applied by shown.bs.modal after select2 reinit
                        const serviceIds = (response.data.service_ids || []).map(String);
                        $('#editModal').data('pending-service-ids', serviceIds);

                        const challengeItems = (Array.isArray(response.data.challenges) && response.data.challenges.length)
                            ? response.data.challenges
                            : [{
                                challenge_title: response.data.challenge_title || '',
                                challenge: response.data.challenge || '',
                                resolution: response.data.resolution || '',
                            }];
                        renderChallengeSections('edit-challenge-sections-container', challengeItems, true);

                        $('#editModal').modal('toggle');
                    },
                    error: function(xhr) {
                        Swal.close();
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to delete',
                            text: 'An error occurred while trying to delete the item. Please try again.',
                        });
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                const id = $(this).data('id');
                const deleteUrl = '{{ route("project.destroy", ["id" => "__ID__"]) }}'.replace('__ID__', id);
                const row = $(this).closest('tr'); // Get the closest table row

                handleDelete(deleteUrl, items_table, row);
            });

            // // Function to handle deletion
            function handleDelete(delete_url, table, row) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to recover this item!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, proceed with deletion
                        $.ajax({
                            url: delete_url,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // Send CSRF token via header
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Deleting...',
                                    text: 'Please wait while we delete the item',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(response) {
                                Swal.close();
                                // Show success message
                                Toast.fire({
                                    icon: 'success',
                                    title: `${response.message}`
                                });
                                // Remove the row from the DataTable
                                table.row(row).remove().draw();
                            },
                            error: function(xhr) {
                                Swal.close();
                                // Show error message
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed to delete',
                                    text: 'An error occurred while trying to delete the item. Please try again.',
                                });
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection