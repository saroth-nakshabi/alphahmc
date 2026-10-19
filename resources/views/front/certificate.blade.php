@extends('front/layout')

@section('meta_title', 'Certificate Verification Page')
@section('meta_description', 'Certificate Verification Page Description')
@section('meta_keywords', 'Certificate,Verification,Alpha,Education')

@section('meta_tags')
    <!-- Additional meta tags (if necessary) -->
    <meta property="og:title" content="Certificate Verification">
    <meta property="og:description" content="Verify certificates and credentials on our platform quickly and securely.">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('custom_css')
@endSection

@section('content')
    <section class="breadcum">
        <div class="container">
            <div class="breadcum-content">
                <h2 class="title">Certificate Verification</h2>
                <h4 class="para"><a href="{{ route('home') }}">Home</a> / Certificate Verification</h4>
            </div>
        </div>
    </section>

    <section class="my-100 @">
        <div class="container-xl">
            <div>
                <div class="row align-items-center">
                    <div class="col-lg-8 col-sm-12">
                        <div class="section-title">
                            <div class="section-title-inner">
                                <h4 class="sub-title" data-aos="fade-down" data-aos-duration="1000">Verify Now</h4>
                                <h2 class="big-title s-color-black" data-aos="fade-up" data-aos-duration="1000">Verify Your
                                    Certificate</h2>
                                <p class="title-para sp-color-black" data-aos="fade-up" data-aos-duration="1000">Ensure the
                                    authenticity of your certificates. Use our simple verification tool to confirm<br>the
                                    credentials and trustworthiness of certified individuals or organizations.</p>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-12 p-0" data-aos="fade-left" data-aos-duration="1500">
                            <form id="verify-certificate" action="{{ route('certificates.verify') }}" method="POST">
                                <div class="my-3">
                                    <label for="certificate-no" class="form-label">Enter Certificate No:</label>
                                    <input type="text" class="form-control" name="certificate_no" id="certificate-no"
                                        placeholder="Enter certificate number" required>
                                </div>
                                <button type="submit" class="all-btn"><a class="btn-p v2 btn-blue rounded">Verify
                                        Certificate</a> </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            var assetUrl = "{{ asset('') }}";
            $('#verify-certificate').validate({
                rules: {
                    certificate_no: {
                        required: true
                    }
                },
                messages: {
                    certificate_no: {
                        required: 'Certificate number is required.'
                    }
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: form.action,
                        method: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                const {
                                    first_name,
                                    last_name
                                } = response.data.enrollment.student.user;
                                const serviceName = response.data.enrollment.service_detail
                                    .service.name;
                                const certificateNo = response.data.certificate_no;
                                const date = response.data.enrollment.service_detail
                                    .schedule.day;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Verification Successful!',
                                    html: `
                                        <div class="card">
                                            <div class="card-body text-start">
                                                <p class="card-text mb-3">${response.message}</p>
                                                <p class="card-text mb-2"><strong>Certificate No:</strong> ${certificateNo}</p>
                                                <p class="card-text mb-2"><strong>Service:</strong> ${serviceName}</p>
                                                <p class="card-text mb-2"><strong>Name:</strong> ${first_name} ${last_name}</p>
                                                <p class="card-text mb-2"><strong>Date:</strong> ${date}</p>
                                            </div>
                                        </div>
                                    `,
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Verification Failed',
                                    text: response.message,
                                    confirmButtonText: 'Try Again'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Something went wrong. Please try again later.',
                                confirmButtonText: 'Close'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endSection
