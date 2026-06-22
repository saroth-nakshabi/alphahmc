@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">
                        Access for: {{ trim($user->first_name . ' ' . $user->last_name) ?: $user->email }}
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('all_users.index') }}">Users</a></li>
                            <li class="breadcrumb-item" aria-current="page">Manage Access</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('public/dashboard/dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('all_users.permissions.update', $user->id) }}" method="POST" id="user_permissions_form">
                            <div class="mb-4 d-flex align-items-center">
                                <h5 class="mb-0">Roles &amp; Permissions</h5>
                                <button class="btn btn-success ms-auto" type="submit">
                                    <i class="bi bi-floppy me-1"></i> Save
                                </button>
                            </div>

                            {{-- Roles --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Assigned roles</label>
                                <select name="roles[]" id="user_roles" class="form-select" multiple data-placeholder="Select roles">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    Roles grant a baseline set of permissions. Permissions ticked below are granted
                                    <strong>directly</strong> to this user, on top of whatever their role(s) already provide.
                                    Permissions shown as <span class="badge bg-light text-dark border">via role</span> are inherited
                                    and stay active even if not ticked here.
                                </small>
                            </div>

                            <hr>

                            <div class="mb-3 d-flex">
                                <h6 class="mb-0">Direct permissions by section</h6>
                            </div>

                            <div class="accordion accordion-flush" id="permAccordion">
                                @if (isset($categories) && count($categories) > 0)
                                    @foreach ($categories as $category)
                                        @continue($category->permissions->isEmpty())
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#perm_cat_{{ $category->id }}"
                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                    aria-controls="perm_cat_{{ $category->id }}">
                                                    {{ $category->name }}
                                                </button>
                                            </h2>
                                            <div id="perm_cat_{{ $category->id }}"
                                                class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                data-bs-parent="#permAccordion">
                                                <div class="accordion-body">
                                                    <div class="d-flex flex-wrap">
                                                        <div class="form-check m-2">
                                                            <input type="checkbox" id="cat-{{ $category->id }}-select-all" class="form-check-input select-all">
                                                            <label class="form-check-label fw-semibold" for="cat-{{ $category->id }}-select-all">Select All</label>
                                                        </div>
                                                        @foreach ($category->permissions as $permission)
                                                            @php
                                                                $isDirect = in_array($permission->name, $directPermissions);
                                                                $viaRole  = in_array($permission->name, $viaRolePermissions);
                                                            @endphp
                                                            <div class="form-check m-2">
                                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                                    id="perm-{{ $permission->id }}" class="form-check-input perm-checkbox"
                                                                    {{ $isDirect ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                    @if ($viaRole)
                                                                        <span class="badge bg-light text-dark border ms-1" title="Granted through an assigned role">via role</span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-gray">- No permissions available -</div>
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
        $(document).ready(function () {
            if ($.fn.select2) {
                $('#user_roles').select2({ width: '100%' });
            }

            // Per-section "Select All"
            $('.select-all').on('change', function () {
                $(this).closest('.accordion-body').find('input.perm-checkbox').prop('checked', this.checked);
            });
            $('.perm-checkbox').on('change', function () {
                let body = $(this).closest('.accordion-body');
                let all = body.find('input.perm-checkbox').length === body.find('input.perm-checkbox:checked').length;
                body.find('.select-all').prop('checked', all);
            });

            $("#user_permissions_form").validate({
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        method: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        beforeSend: function () {
                            Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        },
                        success: function (response) {
                            Swal.close();
                            Toast.fire({ icon: 'success', title: response.message });
                            setTimeout(() => window.location.reload(), 900);
                        },
                        error: function (xhr) {
                            Swal.close();
                            let msg = '';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, (k, v) => msg += `<p class='text-danger'>${v}</p>`);
                            } else {
                                msg = 'Something went wrong. Please try again.';
                            }
                            Swal.fire({ icon: 'error', title: 'Failed to save', html: `<div>${msg}</div>` });
                        }
                    });
                }
            });
        });
    </script>
@endsection
