@extends('front/layout-2')

@push('page_title', 'Share Your Experience | Alpha Health Group')
@section('meta_description', 'Share your experience working with Alpha Health Group. Your feedback helps us serve healthcare professionals better across the UAE and GCC.')

@section('content')
<style>
    :root { --teal: #066D77; --navy: #0f172a; --star: #FBBC04; --border: #e8eaed; }
    .feedback-page { font-family: 'Inter', sans-serif; background: #f8f9fa; min-height: 100vh; }

    .feedback-hero {
        background: var(--navy); padding: 160px 0 72px; margin-top: -120px; position: relative; overflow: hidden;
    }
    .feedback-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 30% 60%, rgba(6,109,119,0.3), transparent 60%);
    }
    .feedback-hero-inner { position: relative; z-index: 2; }
    .feedback-hero h1 { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 600; color: #fff; letter-spacing: -0.02em; margin-bottom: 10px; }
    .feedback-hero p { color: rgba(255,255,255,0.65); font-size: 1rem; max-width: 480px; line-height: 1.7; }

    .feedback-body { padding: 60px 0 100px; }

    .feedback-card {
        background: #fff; border-radius: 16px; padding: 48px;
        border: 1px solid var(--border);
        box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        max-width: 680px; margin: 0 auto;
    }

    .feedback-card h2 { font-size: 1.3rem; font-weight: 600; color: var(--navy); margin-bottom: 32px; }

    .fb-field { margin-bottom: 22px; }
    .fb-label { display: block; font-size: 0.85rem; font-weight: 600; color: #3c4043; margin-bottom: 7px; }
    .fb-label .req { color: #ea4335; }
    .fb-input {
        width: 100%; padding: 12px 16px; border: 1px solid var(--border);
        border-radius: 8px; font-size: 0.92rem; color: #202124;
        outline: none; transition: border-color 0.2s ease;
        font-family: 'Inter', sans-serif;
    }
    .fb-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(6,109,119,0.1); }
    .fb-input.is-error { border-color: #ea4335; }
    .fb-error { font-size: 0.78rem; color: #ea4335; margin-top: 4px; display: none; }

    /* Star rating */
    .star-rating { display: flex; gap: 6px; flex-direction: row-reverse; justify-content: flex-end; }
    .star-rating input { display: none; }
    .star-rating label {
        font-size: 2rem; color: #d4d4d4; cursor: pointer;
        transition: color 0.15s ease;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: var(--star); }

    .fb-submit {
        width: 100%; padding: 15px; background: var(--teal); color: #fff;
        border: none; border-radius: 100px; font-size: 0.95rem; font-weight: 600;
        cursor: pointer; transition: background 0.25s ease, transform 0.2s ease;
        font-family: 'Inter', sans-serif; margin-top: 8px;
    }
    .fb-submit:hover { background: #088a95; transform: translateY(-1px); }
    .fb-submit:disabled { opacity: 0.6; cursor: not-allowed; }

    .fb-success {
        display: none; text-align: center; padding: 40px 20px;
    }
    .fb-success i { font-size: 3rem; color: #34a853; margin-bottom: 16px; display: block; }
    .fb-success h3 { font-size: 1.3rem; font-weight: 600; color: var(--navy); margin-bottom: 8px; }
    .fb-success p { color: #70757a; font-size: 0.95rem; }
    .fb-success a { color: var(--teal); font-weight: 600; text-decoration: none; }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: rgba(255,255,255,0.7); font-size: 0.85rem; text-decoration: none;
        margin-bottom: 24px; transition: color 0.2s ease;
    }
    .back-link:hover { color: #fff; }

    @media (max-width: 767px) {
        .feedback-hero { padding: 140px 0 56px; margin-top: -85px; }
        .feedback-card { padding: 28px 22px; }
    }
</style>

<div class="feedback-page">
    <section class="feedback-hero">
        <div class="container feedback-hero-inner">
            <a href="{{ route('front.testimonials') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Reviews
            </a>
            <h1>Share Your Experience</h1>
            <p>Your honest feedback helps us improve and helps other healthcare professionals make informed decisions.</p>
        </div>
    </section>

    <section class="feedback-body">
        <div class="container">
            <div class="feedback-card">
                <div id="fb-form-wrap">
                    <h2>Write a Review</h2>
                    <form id="fb-form" novalidate>
                        @csrf
                        <div class="row g-0">
                            <div class="col-md-6 pe-md-2">
                                <div class="fb-field">
                                    <label class="fb-label">Your Name <span class="req">*</span></label>
                                    <input type="text" name="author_name" class="fb-input" placeholder="Dr. John Smith">
                                    <div class="fb-error" id="err-author_name">Please enter your name.</div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-md-2">
                                <div class="fb-field">
                                    <label class="fb-label">Email Address <span class="req">*</span></label>
                                    <input type="email" name="email" class="fb-input" placeholder="you@example.com">
                                    <div class="fb-error" id="err-email">Please enter a valid email.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col-md-6 pe-md-2">
                                <div class="fb-field">
                                    <label class="fb-label">Job Title</label>
                                    <input type="text" name="position" class="fb-input" placeholder="e.g. Quality Manager">
                                </div>
                            </div>
                            <div class="col-md-6 ps-md-2">
                                <div class="fb-field">
                                    <label class="fb-label">Company / Facility</label>
                                    <input type="text" name="company_name" class="fb-input" placeholder="Your organisation">
                                </div>
                            </div>
                        </div>

                        <div class="fb-field">
                            <label class="fb-label">Service You Used</label>
                            <select name="service_id" class="fb-input">
                                <option value="">— Select a service (optional) —</option>
                                @foreach ($all_services as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="fb-field">
                            <label class="fb-label">Your Rating <span class="req">*</span></label>
                            <div class="star-rating" id="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}">
                                <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">&#9733;</label>
                                @endfor
                            </div>
                            <div class="fb-error" id="err-rating">Please select a rating.</div>
                        </div>

                        <div class="fb-field">
                            <label class="fb-label">Your Review <span class="req">*</span></label>
                            <textarea name="content" class="fb-input" rows="5"
                                placeholder="Tell us about your experience working with Alpha Health Group..."></textarea>
                            <div class="fb-error" id="err-content">Please write your review.</div>
                        </div>

                        <p style="font-size:0.78rem;color:#70757a;margin-bottom:20px;">
                            <i class="fa-solid fa-shield-halved" style="color:var(--teal)"></i>
                            Your review will be visible after approval. We do not share your email address.
                        </p>

                        <button type="submit" class="fb-submit" id="fb-submit-btn">
                            <span id="fb-btn-text"><i class="fa-solid fa-paper-plane"></i> Submit Review</span>
                            <span id="fb-btn-loading" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i> Submitting...</span>
                        </button>
                    </form>
                </div>

                <div class="fb-success" id="fb-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <h3>Thank you for your review!</h3>
                    <p>Your feedback has been submitted and is pending approval.<br>
                    <a href="{{ route('front.testimonials') }}">View all reviews →</a></p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.getElementById('fb-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    let valid = true;

    // Clear errors
    form.querySelectorAll('.fb-error').forEach(el => el.style.display = 'none');
    form.querySelectorAll('.fb-input').forEach(el => el.classList.remove('is-error'));

    const name    = form.querySelector('[name=author_name]');
    const email   = form.querySelector('[name=email]');
    const rating  = form.querySelector('[name=rating]:checked');
    const content = form.querySelector('[name=content]');

    if (!name.value.trim()) { showError('author_name', name); valid = false; }
    if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) { showError('email', email); valid = false; }
    if (!rating) { document.getElementById('err-rating').style.display = 'block'; valid = false; }
    if (!content.value.trim()) { showError('content', content); valid = false; }
    if (!valid) return;

    const btn = document.getElementById('fb-submit-btn');
    btn.disabled = true;
    document.getElementById('fb-btn-text').style.display    = 'none';
    document.getElementById('fb-btn-loading').style.display = 'inline';

    const data = new FormData(form);
    try {
        const res = await fetch('{{ route("front.testimonial.submit") }}', { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (json.success) {
            document.getElementById('fb-form-wrap').style.display = 'none';
            document.getElementById('fb-success').style.display   = 'block';
        }
    } catch {
        btn.disabled = false;
        document.getElementById('fb-btn-text').style.display    = 'inline';
        document.getElementById('fb-btn-loading').style.display = 'none';
    }
});

function showError(field, el) {
    document.getElementById('err-' + field).style.display = 'block';
    if (el) el.classList.add('is-error');
}
</script>
@endsection
