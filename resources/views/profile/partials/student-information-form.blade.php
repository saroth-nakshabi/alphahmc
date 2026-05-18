<section>
    <header>
        <h2 class="font-medium">
            {{ __('Student Information') }}
        </h2>

        @if (session('profile_incomplete'))
            <div class="alert customize-alert alert-dismissible alert-light-danger bg-danger-subtle text-danger fade show remove-close-icon"
                role="alert" id="profileAlert">
                <div class="d-flex align-items-center me-3 me-md-0">
                    <i class="ti ti-info-circle fs-5 me-2 text-danger"></i>
                    {{ session('profile_incomplete') }}
                </div>
            </div>
        @endif

    </header>

    <form id="student-info-form" action="{{ route('student.update-info') }}" method="POST" class="mt-6 space-y-6">
        <div class="mb-3 row">
            <label for="inputPrefix" class="col-sm-2 col-form-label">Title <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <select class="form-control select2" name="prefix">
                    <option value="Dr." {{ (old('prefix') ?? ($student->prefix ?? '')) == 'Dr.' ? 'selected' : '' }}>
                        Dr.</option>
                    <option value="Mr." {{ (old('prefix') ?? ($student->prefix ?? '')) == 'Mr.' ? 'selected' : '' }}>
                        Mr.</option>
                    <option value="Ms." {{ (old('prefix') ?? ($student->prefix ?? '')) == 'Ms.' ? 'selected' : '' }}>
                        Ms.</option>
                    <option value="Mrs."
                        {{ (old('prefix') ?? ($student->prefix ?? '')) == 'Mrs.' ? 'selected' : '' }}>
                        Mrs.</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputGender" class="col-sm-2 col-form-label">Gender</label>
            <div class="col-sm-10">
                <select class="form-control select2" name="gender">
                    <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male
                    </option>
                    <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>
                        Female</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputAddress" class="col-sm-2 col-form-label">Address</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="address"
                    value="{{ old('address', $student->address ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputPinCode" class="col-sm-2 col-form-label">Pin Code</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pin_code" maxlength="10" pattern="[A-Za-z0-9]{1,10}"
                    value="{{ old('pin_code', $student->pin_code ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputCity" class="col-sm-2 col-form-label">City</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="city"
                    value="{{ old('city', $student->city ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputState" class="col-sm-2 col-form-label">State</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="state"
                    value="{{ old('state', $student->state ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputCountry" class="col-sm-2 col-form-label">Country</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="country"
                    value="{{ old('country', $student->country ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Professional Category<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <div>
                    <select name="professional_category_id" class="form-control select2"
                        data-placeholder="Select Category" required>
                        <option></option>
                        @if (isset($categories) && count($categories) > 0)
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $student->professional_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputOccupation" class="col-sm-2 col-form-label">Occupation / Profession<span
                    class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="occupation"
                    value="{{ old('occupation', $student->occupation ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputInstitution" class="col-sm-2 col-form-label">Institution / Company<span
                    class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="institution"
                    value="{{ old('institution', $student->institution ?? '') }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="photo" class="col-sm-2 col-form-label">Photo
                @if (!$student->photo)
                    <span class="text-danger">*</span>
                @endif
            </label>
            <div class="col-sm-10">
                <input type="file" class="form-control" name="image" id="studentPhoto">
            </div>
        </div>
        <div class="mb-3 row">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="accept_terms"
                    name="accept_terms" required>
                <label class="form-check-label" for="accept_terms">
                    Accept Terms and Conditions
                </label>
                <a href="javascript:void(0)" class="text-decoration-underline" data-bs-target="#viewTermsModal"
                    data-bs-toggle='modal'>Read</a>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="signature" class="col-sm-2 col-form-label">Signature</label>
            <div class="col-md-6 col-sm-12">
                @if (empty($student->signature))
                    <div class="border">
                        <canvas id="studentSignatureCanvas" width="400" height="200"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="student_sign" value="<?= $student->signature ?>">
                    <button type="button" class="btn btn-info btn-secondary mt-2" id="clearStudentSignature">Clear
                        Signature</button>
                @else
                    <div class="mt-1 border-bottom">
                        <img src="<?= $student->signature ?>" alt="">
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4">
            {{-- @can('edit profile') --}}
            <button type="submit" class="btn btn-success">Save</button>
            {{-- @endcan --}}
        </div>
    </form>
</section>

<!-- View Terms and conditions Modal -->
<div class="modal fade" id="viewTermsModal" tabindex="-1" aria-labelledby="viewTermsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="viewTermsModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center">
                <div class="row">
                    <h5>Terms and Conditions</h5>
                    <div class="col-md-12">
                        <ul style="list-style: disc" class="ps-4">
                            <li>All payments must be made in full, before commencement of the service.</li>
                            <li>Admission is not guaranteed if complete payment is not made.</li>
                            <li>
                                Please carry a form of government ID at the time of attending the service or
                                training
                                session.
                            </li>
                            <li>
                                If there is a replacement candidate and the attending candidate is different
                                from the
                                initial registration, a nomination letter from the registered candidate
                                should be
                                availed, to complete the admission process.
                            </li>
                            <li>
                                Candidates arriving 30 minutes after the start of the training session will
                                not be
                                admitted and will have to reschedule the session with the training manager
                            </li>
                        </ul>
                    </div>

                    <h5>Refund Timelines</h5>

                    <div class="col-md-12">
                        <ul style="list-style: disc" class="ps-4">
                            <li>
                                90% of the service fee will be refunded for cancellation requests made 30
                                days
                                before the commencement of service.
                            </li>
                            <li>
                                75% of the service fee will be refunded for cancellation requests made 10
                                days
                                before the commencement of service.
                            </li>
                            <li>
                                Any Cancellation requests made less than 10 days of the commencement of the
                                service will not be entertained.
                            </li>
                            <li>
                                For Individual clients, candidate replacement would be allowed in the
                                scenario of a
                                non-refund.
                            </li>
                            <li>
                                The same is not applicable for Corporate Clients.
                            </li>
                            <li>
                                In the event of a training session being cancelled by ATSM, the same shall
                                be
                                rescheduled or 100% refund of the service fee shall be done.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
