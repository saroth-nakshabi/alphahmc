@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Permissions for Role: {{ $role->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Role</li>
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
                        <form action="{{ route('role.update_permissions', $role->id) }}" method="POST"
                            id="role_permissions_form">
                            <div class="mb-3 d-flex">
                                <h5 class="mb-0">Permissions By Sections</h5>
                                <button class="btn btn-success ms-auto" type="submit">
                                    <i class="bi bi-floppy me-1"></i>
                                    Save
                                </button>
                            </div>
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                @if (isset($categories) && count($categories) > 0)
                                    @foreach ($categories as $category)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button
                                                    class="accordion-button {{ $loop->iteration == 1 ? '' : 'collapsed' }}"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#accordian_id_{{ $category->id }}"
                                                    aria-expanded="{{ $loop->iteration == 1 ? 'true' : 'false' }}"
                                                    aria-controls="accordian_id_{{ $category->id }}">
                                                    {{ $category->name }}
                                                </button>
                                            </h2>
                                            <div id="accordian_id_{{ $category->id }}"
                                                class="accordion-collapse collapse {{ $loop->iteration == 1 ? 'show' : '' }}"
                                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample"
                                                style="">
                                                <div class="accordion-body">
                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check m-2">
                                                            <input type="checkbox"
                                                                id="permission-{{ $loop->iteration }}-select-all"
                                                                class="form-check-input">
                                                            <label class="form-check-label"
                                                                for="permission-{{ $loop->iteration }}-select-all">
                                                                Select All
                                                            </label>
                                                        </div>
                                                        @foreach ($category->permissions as $permission)
                                                            <div class="form-check m-2">
                                                                <input type="checkbox" name="permissions[]"
                                                                    value="{{ $permission->name }}"
                                                                    id="permission-{{ $permission->id }}"
                                                                    class="form-check-input"
                                                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="permission-{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-gray">- No data available -</div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            // Listen for changes on the "Select All" checkboxes
            $('.form-check-input[id^="permission-"][id$="-select-all"]').on('change', function() {
                // Get the current category ID
                let categoryId = $(this).attr('id').split('-')[1];

                // Check or uncheck all checkboxes within the same accordion-body
                $(this).closest('.accordion-body').find('input[type="checkbox"]').not(this).prop('checked',
                    this.checked);
            });

            // Optional: If you want to handle the individual checkboxes and uncheck "Select All" if not all are selected
            $('input[name="permissions[]"]').on('change', function() {
                let categoryBody = $(this).closest('.accordion-body');

                // Check if all checkboxes in this category are checked
                let allChecked = categoryBody.find('input[name="permissions[]"]').length ===
                    categoryBody.find('input[name="permissions[]"]:checked').length;

                // Set the "Select All" checkbox state
                categoryBody.find('input[id^="permission-"][id$="-select-all"]').prop('checked',
                    allChecked);
            });

            // update role permissions handle
            $("#role_permissions_form").validate({
                submitHandler: function(form) {
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
        });
    </script>
@endsection
