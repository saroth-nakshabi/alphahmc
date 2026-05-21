@php
    $tpTestimonials = \App\Models\Testimonial::where('approved', true)->where('featured', true)->latest()->get();
    $tpAvg   = $tpTestimonials->count() ? round($tpTestimonials->avg('rating'), 1) : 0;
    $tpTotal = \App\Models\Testimonial::where('approved', true)->count();
    $tpColors = ['#1a73e8','#34a853','#ea4335','#fa7b17','#a142f4','#066D77','#e37400'];

    /* card height in px — shared between CSS and nothing else */
    $cardH = 180;
@endphp

@if($tpTestimonials->count())
<style>
/* ── Testimonial pill strip ──────────────────────────── */
.tp-section {
    padding: 56px 0;
    background: #f8f9fa;
    font-family: 'Inter', sans-serif;
}
.tp-layout {
    display: flex;
    align-items: flex-start;   /* both columns start at the same top edge */
    gap: 36px;
}

/* ── Left: score panel ───────────────────────────────── */
.tp-left {
    width: 152px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 0;
}
.tp-label {
    font-size: 0.68rem; font-weight: 700; letter-spacing: 2.5px;
    text-transform: uppercase; color: #066D77;
    margin: 0 0 6px;
}
.tp-avg-num {
    font-size: 3.5rem; font-weight: 300; color: #0f172a;
    line-height: 1; letter-spacing: -0.04em;
    margin: 0;
}
.tp-stars-row  { display: flex; gap: 3px; margin-top: 8px; }
.tp-stars-row i { font-size: 0.88rem; color: #FBBC04; }
.tp-stars-row i.empty { color: #d4d4d4; }
.tp-count { font-size: 0.74rem; color: #70757a; margin-top: 5px; }
.tp-view-all {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.74rem; font-weight: 600; color: #066D77;
    text-decoration: none; margin-top: 14px;
    padding: 6px 14px;
    border: 1px solid rgba(6,109,119,0.3); border-radius: 100px;
    transition: all 0.2s ease; width: fit-content;
}
.tp-view-all:hover { background: #066D77; color: #fff; border-color: #066D77; }

/* ── Divider ─────────────────────────────────────────── */
.tp-vline {
    width: 1px;
    /* stretches to match whichever column is taller */
    align-self: stretch;
    background: #e0e0e0;
    flex-shrink: 0;
}

/* ── Right: carousel ─────────────────────────────────── */
.tp-right {
    flex: 1;
    min-width: 0;
    /* right-edge fade hint that more slides exist */
    -webkit-mask-image: linear-gradient(to right, black 88%, transparent 100%);
    mask-image:         linear-gradient(to right, black 88%, transparent 100%);
}

/* Swiper: overflow HIDDEN so the flex parent measures real height.
   The partial 3rd card still peeks naturally inside .tp-right's width. */
.tp-swiper {
    overflow: hidden !important;
    padding-bottom: 32px !important;
}
.tp-swiper .swiper-wrapper { align-items: stretch; }

/* Fixed slide dimensions — overrides any Swiper JS height calculation */
.tp-swiper .swiper-slide {
    width: 256px !important;
    height: {{ $cardH }}px !important;
}

/* ── Pill card ───────────────────────────────────────── */
.tp-pill {
    width: 100%;
    height: 100%;
    background: #fff;
    border: 1px solid #e8eaed;
    border-radius: 18px;
    padding: 18px 20px 14px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    transition: box-shadow 0.25s ease, transform 0.25s ease;
    overflow: hidden;
}
.tp-pill:hover {
    box-shadow: 0 5px 18px rgba(0,0,0,0.09);
    transform: translateY(-2px);
}
.tp-quote {
    font-size: 0.83rem; color: #3c4043; line-height: 1.6;
    font-style: italic; margin: 0;
    display: -webkit-box; -webkit-line-clamp: 4;
    -webkit-box-orient: vertical; overflow: hidden;
    flex: 1;
}
.tp-footer {
    display: flex; align-items: center; gap: 9px;
    border-top: 1px solid #f1f3f4;
    padding-top: 10px; flex-shrink: 0;
}
.tp-avatar-sm {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.6rem; font-weight: 700; color: #fff;
    flex-shrink: 0; overflow: hidden;
}
.tp-avatar-sm img { width: 100%; height: 100%; object-fit: cover; }
.tp-author-info { flex: 1; min-width: 0; }
.tp-author-name {
    font-size: 0.76rem; font-weight: 600; color: #202124;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    line-height: 1.3;
}
.tp-pill-stars { display: flex; gap: 1px; margin-top: 2px; }
.tp-pill-stars i { font-size: 0.6rem; color: #FBBC04; }
.tp-pill-stars i.empty { color: #d4d4d4; }

/* ── Pagination dots ─────────────────────────────────── */
.tp-swiper .swiper-pagination { bottom: 0 !important; }
.tp-swiper .swiper-pagination-bullet {
    background: #ccc; opacity: 1; width: 6px; height: 6px;
    transition: all 0.25s ease;
}
.tp-swiper .swiper-pagination-bullet-active {
    background: #066D77; width: 20px; border-radius: 3px;
}

/* ── Mobile ──────────────────────────────────────────── */
@media (max-width: 767px) {
    .tp-section  { padding: 44px 0; }
    .tp-layout   { flex-direction: column; gap: 18px; }
    .tp-vline    { display: none; }
    .tp-left     { flex-direction: row; width: 100%; flex-wrap: wrap; align-items: center; gap: 12px; }
    .tp-avg-num  { font-size: 2.8rem; }
    .tp-right    { width: 100%; -webkit-mask-image: none; mask-image: none; }
}
</style>

<section class="tp-section">
    <div class="container">
        <div class="tp-layout">

            {{-- Left: rating summary --}}
            <div class="tp-left">
                <span class="tp-label">Reviews</span>
                <span class="tp-avg-num">{{ number_format($tpAvg, 1) }}</span>
                <div class="tp-stars-row">
                    @for($s=1;$s<=5;$s++)
                        <i class="fa-solid fa-star {{ $s <= round($tpAvg) ? '' : 'empty' }}"></i>
                    @endfor
                </div>
                <span class="tp-count">{{ $tpTotal }} verified {{ Str::plural('review', $tpTotal) }}</span>
                <a href="{{ route('front.testimonials') }}" class="tp-view-all">
                    View all <i class="fa-solid fa-arrow-right" style="font-size:0.58rem"></i>
                </a>
            </div>

            <div class="tp-vline d-none d-md-block"></div>

            {{-- Right: pill carousel --}}
            <div class="tp-right">
                <div class="swiper tp-swiper">
                    <div class="swiper-wrapper">
                        @foreach($tpTestimonials as $t)
                        @php
                            $clean  = html_entity_decode(strip_tags($t->content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $words  = preg_split('/\s+/', trim($clean));
                            $short  = implode(' ', array_slice($words, 0, 20)) . (count($words) > 20 ? '…' : '');
                            $tpInit = collect(explode(' ', $t->author_name))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                            $tpClr  = $tpColors[crc32($t->author_name) % count($tpColors)];
                        @endphp
                        <div class="swiper-slide">
                            <div class="tp-pill">
                                <p class="tp-quote">&ldquo;{{ $short }}&rdquo;</p>
                                <div class="tp-footer">
                                    <div class="tp-avatar-sm" style="background:{{ $tpClr }}">
                                        @if($t->author_image)
                                            <img src="{{ asset('public/uploads/testimonials/'.$t->author_image) }}" alt="{{ $t->author_name }}">
                                        @else
                                            {{ $tpInit }}
                                        @endif
                                    </div>
                                    <div class="tp-author-info">
                                        <div class="tp-author-name">{{ $t->author_name }}</div>
                                        <div class="tp-pill-stars">
                                            @for($s=1;$s<=5;$s++)
                                                <i class="fa-solid fa-star {{ $s <= $t->rating ? '' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
(function () {
    function initTpSwiper() {
        if (typeof Swiper === 'undefined') { setTimeout(initTpSwiper, 80); return; }
        new Swiper('.tp-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 14,
            loop: false,
            rewind: true,
            grabCursor: true,
            autoplay: { delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true },
            pagination: { el: '.tp-swiper .swiper-pagination', clickable: true },
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTpSwiper);
    } else {
        initTpSwiper();
    }
})();
</script>
@endif
