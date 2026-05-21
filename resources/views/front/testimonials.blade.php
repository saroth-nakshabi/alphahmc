@extends('front/layout-2')

@push('page_title', 'Client Reviews & Testimonials | Alpha Health Group')

@section('meta_description', 'Read verified reviews and testimonials from healthcare clients who have worked with Alpha Health Group across the UAE and GCC.')

@push('meta')
<link rel="preload" as="style"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
@endpush

@section('content')
<style>
    :root {
        --teal: #066D77;
        --navy: #0f172a;
        --star: #FBBC04;
        --border: #e8eaed;
        --soft: #f8f9fa;
        --muted: #70757a;
        --text: #202124;
    }
    .reviews-page { font-family: 'Inter', sans-serif; background: #fff; overflow-x: hidden; }

    /* ── Hero ───────────────────────── */
    .reviews-hero {
        background: var(--navy);
        padding: 160px 0 80px;
        margin-top: -120px;
        position: relative;
        overflow: hidden;
    }
    .reviews-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 70% 50%, rgba(6,109,119,0.25), transparent 60%);
    }
    .reviews-hero-inner { position: relative; z-index: 2; }
    .reviews-hero-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 2.5px;
        text-transform: uppercase; color: #66d9e8;
        margin-bottom: 20px;
    }
    .reviews-hero-label span { width: 28px; height: 1px; background: #66d9e8; display: inline-block; }
    .reviews-hero h1 {
        font-size: clamp(2rem, 4vw, 3rem); font-weight: 600; color: #fff;
        letter-spacing: -0.02em; margin-bottom: 8px; line-height: 1.2;
    }
    .reviews-hero-msg {
        font-size: 1.05rem; color: rgba(255,255,255,0.65); max-width: 520px; line-height: 1.7;
    }
    .reviews-hero-cta {
        display: inline-flex; align-items: center; gap: 8px;
        margin-top: 32px; padding: 13px 28px;
        background: var(--teal); color: #fff; border-radius: 100px;
        font-size: 0.88rem; font-weight: 600; text-decoration: none;
        transition: background 0.25s ease, transform 0.25s ease;
    }
    .reviews-hero-cta:hover { background: #088a95; color: #fff; transform: translateY(-2px); }

    /* ── Summary Card (Google Maps style) ──── */
    .reviews-summary-section { padding: 56px 0 0; background: #fff; }
    .reviews-summary-card {
        display: flex; align-items: flex-start; gap: 48px;
        padding: 40px 48px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    }
    /* Left: big number */
    .rs-score {
        display: flex; flex-direction: column; align-items: center;
        gap: 8px; min-width: 100px;
    }
    .rs-big-num {
        font-size: 4rem; font-weight: 400; color: var(--text); line-height: 1;
    }
    .rs-stars { display: flex; gap: 2px; }
    .rs-stars i { font-size: 1.1rem; color: var(--star); }
    .rs-count { font-size: 0.82rem; color: var(--muted); }

    /* Divider */
    .rs-divider { width: 1px; background: var(--border); align-self: stretch; flex-shrink: 0; }

    /* Bars */
    .rs-bars { flex: 1; display: flex; flex-direction: column; gap: 6px; justify-content: center; }
    .rs-bar-row { display: flex; align-items: center; gap: 10px; }
    .rs-bar-lbl { font-size: 0.8rem; color: var(--muted); width: 32px; flex-shrink: 0; white-space: nowrap; }
    .rs-bar-track {
        flex: 1; height: 8px; background: #e8eaed; border-radius: 4px; overflow: hidden;
    }
    .rs-bar-fill { height: 100%; background: var(--star); border-radius: 4px; transition: width 0.8s ease; }
    .rs-bar-count { font-size: 0.8rem; color: var(--muted); width: 28px; text-align: right; flex-shrink: 0; }

    /* Write review CTA */
    .rs-action { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; min-width: 160px; }
    .rs-write-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 24px;
        border: 1px solid var(--border); border-radius: 100px;
        font-size: 0.88rem; font-weight: 600; color: var(--teal);
        text-decoration: none; background: #fff;
        transition: all 0.25s ease; white-space: nowrap;
    }
    .rs-write-btn:hover { background: var(--teal); color: #fff; border-color: var(--teal); }
    .rs-total-txt { font-size: 0.78rem; color: var(--muted); text-align: center; }

    @media (max-width: 767px) {
        .reviews-summary-card { flex-direction: column; padding: 24px; gap: 24px; }
        .rs-divider { width: 100%; height: 1px; }
        .rs-action { flex-direction: row; justify-content: flex-start; }
    }

    /* ── Reviews carousel ───────────── */
    .reviews-carousel-section { padding: 40px 0 80px; }
    .reviews-carousel-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 32px; padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }
    .reviews-carousel-header h2 { font-size: 1.2rem; font-weight: 600; color: var(--text); margin: 0; }
    .reviews-count-badge {
        font-size: 0.82rem; color: var(--muted); background: var(--soft);
        padding: 4px 12px; border-radius: 100px; border: 1px solid var(--border);
    }

    /* Swiper wrapper */
    .rc-swiper-wrap { position: relative; }
    .rc-swiper { overflow: hidden; padding-bottom: 48px !important; }

    /* Individual review card */
    .review-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        background: #fff;
        height: auto;
        transition: box-shadow 0.25s ease;
        display: flex; flex-direction: column; gap: 14px;
    }
    .review-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }

    /* Top row: avatar + name + date */
    .rc-top { display: flex; align-items: center; gap: 12px; }
    .rc-avatar {
        width: 42px; height: 42px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; font-weight: 600; color: #fff; flex-shrink: 0;
        overflow: hidden;
    }
    .rc-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .rc-meta { flex: 1; min-width: 0; }
    .rc-name { font-size: 0.9rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .rc-date { font-size: 0.75rem; color: var(--muted); margin-top: 1px; }

    /* Stars */
    .rc-stars { display: flex; gap: 2px; }
    .rc-stars i { font-size: 0.85rem; color: var(--star); }
    .rc-stars i.empty { color: #d4d4d4; }

    /* Service tag */
    .rc-service-tag {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.72rem; font-weight: 600; color: var(--teal);
        background: rgba(6,109,119,0.07); border-radius: 100px;
        padding: 3px 10px; width: fit-content;
    }

    /* Review text */
    .rc-text { font-size: 0.88rem; color: #3c4043; line-height: 1.65; flex: 1; }

    /* Source badge */
    .rc-source { font-size: 0.7rem; color: var(--muted); display: flex; align-items: center; gap: 4px; }
    .rc-source i { font-size: 0.7rem; }

    /* Swiper pagination */
    .rc-swiper .swiper-pagination-bullet { background: #ccc; opacity: 1; width: 8px; height: 8px; }
    .rc-swiper .swiper-pagination-bullet-active { background: var(--teal); width: 24px; border-radius: 4px; }

    /* Nav arrows */
    .rc-nav-btn {
        width: 40px; height: 40px; border-radius: 50%;
        border: 1px solid var(--border); background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s ease; color: var(--text);
        font-size: 0.85rem; flex-shrink: 0;
    }
    .rc-nav-btn:hover { background: var(--teal); border-color: var(--teal); color: #fff; }
    .rc-nav-btn.swiper-button-disabled { opacity: 0.3; pointer-events: none; }
    .rc-nav-row { display: flex; align-items: center; justify-content: flex-end; gap: 8px; margin-bottom: 20px; }

    /* ── Empty state ─── */
    .reviews-empty { text-align: center; padding: 80px 20px; color: var(--muted); }
    .reviews-empty i { font-size: 3rem; margin-bottom: 16px; display: block; opacity: 0.3; }

    @media (max-width: 767px) {
        .reviews-hero { padding: 140px 0 60px; margin-top: -85px; }
    }

    /* ── Write Review Modal ─────────── */
    .wr-modal .modal-content {
        border-radius: 16px; border: none;
        box-shadow: 0 24px 64px rgba(0,0,0,0.18);
        font-family: 'Inter', sans-serif;
    }
    .wr-modal .modal-header {
        padding: 24px 28px 0; border-bottom: none;
    }
    .wr-modal .modal-title {
        font-size: 1.2rem; font-weight: 600; color: var(--navy);
    }
    .wr-modal .btn-close { opacity: 0.4; }
    .wr-modal .btn-close:hover { opacity: 0.8; }
    .wr-modal .modal-body { padding: 20px 28px 28px; }

    .wr-field { margin-bottom: 18px; }
    .wr-label { display: block; font-size: 0.82rem; font-weight: 600; color: #3c4043; margin-bottom: 6px; }
    .wr-label .req { color: #ea4335; }
    .wr-input {
        width: 100%; padding: 11px 14px; border: 1px solid var(--border);
        border-radius: 8px; font-size: 0.9rem; color: #202124;
        outline: none; transition: border-color 0.2s ease;
        font-family: 'Inter', sans-serif;
    }
    .wr-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(6,109,119,0.1); }
    .wr-input.is-error { border-color: #ea4335; }
    .wr-error { font-size: 0.75rem; color: #ea4335; margin-top: 3px; display: none; }

    .wr-stars { display: flex; gap: 6px; flex-direction: row-reverse; justify-content: flex-end; }
    .wr-stars input { display: none; }
    .wr-stars label {
        font-size: 1.8rem; color: #d4d4d4; cursor: pointer;
        transition: color 0.15s ease; line-height: 1;
    }
    .wr-stars input:checked ~ label,
    .wr-stars label:hover,
    .wr-stars label:hover ~ label { color: var(--star); }

    .wr-submit {
        width: 100%; padding: 13px; background: var(--teal); color: #fff;
        border: none; border-radius: 100px; font-size: 0.92rem; font-weight: 600;
        cursor: pointer; transition: background 0.25s ease;
        font-family: 'Inter', sans-serif; margin-top: 4px;
    }
    .wr-submit:hover { background: #088a95; }
    .wr-submit:disabled { opacity: 0.6; cursor: not-allowed; }

    .wr-success {
        display: none; text-align: center; padding: 32px 16px;
    }
    .wr-success i { font-size: 2.8rem; color: #34a853; margin-bottom: 14px; display: block; }
    .wr-success h3 { font-size: 1.15rem; font-weight: 600; color: var(--navy); margin-bottom: 6px; }
    .wr-success p { color: #70757a; font-size: 0.88rem; }
    .wr-privacy { font-size: 0.75rem; color: #70757a; margin-bottom: 16px; }
</style>

<div class="reviews-page">

    {{-- Hero --}}
    <section class="reviews-hero">
        <div class="container reviews-hero-inner">
            <div class="reviews-hero-label"><span></span> Client Reviews</div>
            <h1>{{ $settings->hero_message }}</h1>
            <p class="reviews-hero-msg">Verified experiences from healthcare professionals and facility leaders across the UAE and GCC who have partnered with Alpha Health Group.</p>
            <button type="button" class="reviews-hero-cta" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                <i class="fa-solid fa-pen-to-square"></i> Share Your Experience
            </button>
        </div>
    </section>

    {{-- Summary --}}
    <section class="reviews-summary-section">
        <div class="container">
            <div class="reviews-summary-card">

                {{-- Big score --}}
                <div class="rs-score">
                    <div class="rs-big-num">{{ $totalCount ? number_format($avgRating, 1) : '—' }}</div>
                    <div class="rs-stars">
                        @for ($s = 1; $s <= 5; $s++)
                            @if ($s <= round($avgRating))
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star" style="color:#d4d4d4"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="rs-count">{{ $totalCount }} {{ Str::plural('review', $totalCount) }}</div>
                </div>

                <div class="rs-divider"></div>

                {{-- Breakdown bars --}}
                <div class="rs-bars">
                    @foreach ($breakdown as $star => $data)
                    <div class="rs-bar-row">
                        <span class="rs-bar-lbl">{{ $star }} <i class="fa-solid fa-star" style="font-size:0.65rem;color:var(--star)"></i></span>
                        <div class="rs-bar-track">
                            <div class="rs-bar-fill" style="width: {{ $data['pct'] }}%"></div>
                        </div>
                        <span class="rs-bar-count">{{ $data['count'] }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="rs-divider"></div>

                {{-- Write review --}}
                <div class="rs-action">
                    <button type="button" class="rs-write-btn" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                        <i class="fa-solid fa-pen-to-square"></i> Write a review
                    </button>
                    <p class="rs-total-txt">Your feedback helps us<br>serve you better</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Reviews carousel --}}
    <section class="reviews-carousel-section">
        <div class="container">
            <div class="reviews-carousel-header">
                <h2>All Reviews</h2>
                <span class="reviews-count-badge">{{ $totalCount }} {{ Str::plural('review', $totalCount) }}</span>
            </div>

            @if ($testimonials->count())
            <div class="rc-nav-row">
                <button class="rc-nav-btn" id="rc-prev"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="rc-nav-btn" id="rc-next"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
            <div class="rc-swiper-wrap">
                <div class="swiper rc-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($testimonials as $t)
                        @php
                            $initials = collect(explode(' ', $t->author_name))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                            $colors = ['#1a73e8','#34a853','#ea4335','#fa7b17','#a142f4','#066D77','#e37400'];
                            $color  = $colors[crc32($t->author_name) % count($colors)];
                        @endphp
                        <div class="swiper-slide">
                            <div class="review-card">
                                <div class="rc-top">
                                    <div class="rc-avatar" style="background: {{ $color }}">
                                        @if ($t->author_image)
                                            <img src="{{ asset('public/uploads/testimonials/' . $t->author_image) }}" alt="{{ $t->author_name }}">
                                        @else
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <div class="rc-meta">
                                        <div class="rc-name">{{ $t->author_name }}</div>
                                        <div class="rc-date">{{ $t->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>

                                <div class="rc-stars">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <i class="fa-solid fa-star {{ $s <= $t->rating ? '' : 'empty' }}"></i>
                                    @endfor
                                </div>

                                @if ($t->service)
                                <div class="rc-service-tag">
                                    <i class="fa-solid fa-stethoscope" style="font-size:0.65rem"></i>
                                    {{ $t->service->name }}
                                </div>
                                @endif

                                <div class="rc-text">{{ $t->content }}</div>

                                @if ($t->source === 'customer')
                                <div class="rc-source">
                                    <i class="fa-solid fa-circle-check" style="color:var(--teal)"></i> Verified client
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            @else
            <div class="reviews-empty">
                <i class="fa-regular fa-star"></i>
                <p>No reviews yet. <button type="button" class="btn btn-link p-0" style="color:var(--teal);font-weight:600;vertical-align:baseline" data-bs-toggle="modal" data-bs-target="#writeReviewModal">Be the first to share your experience.</button></p>
            </div>
            @endif
        </div>
    </section>

</div>

{{-- Write a Review Modal --}}
<div class="modal fade wr-modal" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="writeReviewModalLabel">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div id="wr-form-wrap">
                    <form id="wr-form" novalidate>
                        @csrf
                        <div class="row g-0">
                            <div class="col-md-6 pe-md-2">
                                <div class="wr-field">
                                    <label class="wr-label">Your Name <span class="req">*</span></label>
                                    <input type="text" name="author_name" class="wr-input" placeholder="Dr. John Smith">
                                    <div class="wr-error" id="wr-err-author_name">Please enter your name.</div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-md-2">
                                <div class="wr-field">
                                    <label class="wr-label">Email Address <span class="req">*</span></label>
                                    <input type="email" name="email" class="wr-input" placeholder="you@example.com">
                                    <div class="wr-error" id="wr-err-email">Please enter a valid email.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col-md-6 pe-md-2">
                                <div class="wr-field">
                                    <label class="wr-label">Job Title</label>
                                    <input type="text" name="position" class="wr-input" placeholder="e.g. Quality Manager">
                                </div>
                            </div>
                            <div class="col-md-6 ps-md-2">
                                <div class="wr-field">
                                    <label class="wr-label">Company / Facility</label>
                                    <input type="text" name="company_name" class="wr-input" placeholder="Your organisation">
                                </div>
                            </div>
                        </div>

                        <div class="wr-field">
                            <label class="wr-label">Service You Used</label>
                            <select name="service_id" class="wr-input">
                                <option value="">— Select a service (optional) —</option>
                                @foreach ($all_services as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="wr-field">
                            <label class="wr-label">Your Rating <span class="req">*</span></label>
                            <div class="wr-stars">
                                @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="wr-star{{ $i }}" value="{{ $i }}">
                                <label for="wr-star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">&#9733;</label>
                                @endfor
                            </div>
                            <div class="wr-error" id="wr-err-rating">Please select a rating.</div>
                        </div>

                        <div class="wr-field">
                            <label class="wr-label">Your Review <span class="req">*</span></label>
                            <textarea name="content" class="wr-input" rows="4"
                                placeholder="Tell us about your experience working with Alpha Health Group..."></textarea>
                            <div class="wr-error" id="wr-err-content">Please write your review.</div>
                        </div>

                        <p class="wr-privacy">
                            <i class="fa-solid fa-shield-halved" style="color:var(--teal)"></i>
                            Your review will be visible after approval. We do not share your email address.
                        </p>

                        <button type="submit" class="wr-submit" id="wr-submit-btn">
                            <span id="wr-btn-text"><i class="fa-solid fa-paper-plane"></i> Submit Review</span>
                            <span id="wr-btn-loading" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i> Submitting...</span>
                        </button>
                    </form>
                </div>

                <div class="wr-success" id="wr-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <h3>Thank you for your review!</h3>
                    <p>Your feedback has been submitted and is pending approval.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// Reviews carousel
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.rc-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        pagination: { el: '.rc-swiper .swiper-pagination', clickable: true },
        navigation: { prevEl: '#rc-prev', nextEl: '#rc-next' },
        breakpoints: {
            640:  { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
});

// Reset modal form when closed
document.getElementById('writeReviewModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('wr-form').reset();
    document.getElementById('wr-form-wrap').style.display = 'block';
    document.getElementById('wr-success').style.display   = 'none';
    document.getElementById('wr-submit-btn').disabled     = false;
    document.getElementById('wr-btn-text').style.display  = 'inline';
    document.getElementById('wr-btn-loading').style.display = 'none';
    document.querySelectorAll('#wr-form .wr-error').forEach(el => el.style.display = 'none');
    document.querySelectorAll('#wr-form .wr-input').forEach(el => el.classList.remove('is-error'));
});

document.getElementById('wr-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    let valid = true;

    form.querySelectorAll('.wr-error').forEach(el => el.style.display = 'none');
    form.querySelectorAll('.wr-input').forEach(el => el.classList.remove('is-error'));

    const name    = form.querySelector('[name=author_name]');
    const email   = form.querySelector('[name=email]');
    const rating  = form.querySelector('[name=rating]:checked');
    const content = form.querySelector('[name=content]');

    if (!name.value.trim()) { wrShowError('author_name', name); valid = false; }
    if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) { wrShowError('email', email); valid = false; }
    if (!rating) { document.getElementById('wr-err-rating').style.display = 'block'; valid = false; }
    if (!content.value.trim()) { wrShowError('content', content); valid = false; }
    if (!valid) return;

    const btn = document.getElementById('wr-submit-btn');
    btn.disabled = true;
    document.getElementById('wr-btn-text').style.display    = 'none';
    document.getElementById('wr-btn-loading').style.display = 'inline';

    try {
        const res  = await fetch('{{ route("front.testimonial.submit") }}', {
            method: 'POST', body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (json.success) {
            document.getElementById('wr-form-wrap').style.display = 'none';
            document.getElementById('wr-success').style.display   = 'block';
        }
    } catch {
        btn.disabled = false;
        document.getElementById('wr-btn-text').style.display    = 'inline';
        document.getElementById('wr-btn-loading').style.display = 'none';
    }
});

function wrShowError(field, el) {
    document.getElementById('wr-err-' + field).style.display = 'block';
    if (el) el.classList.add('is-error');
}
</script>
@endsection
