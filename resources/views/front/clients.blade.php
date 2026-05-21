@if(isset($clients) && $clients->count())
<style>
    .clients-carousel-section {
        padding: 64px 0 52px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .clients-carousel-header {
        text-align: center;
        margin-bottom: 36px;
    }
    .clients-carousel-tag {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #066D77;
        background: rgba(6,109,119,0.08);
        padding: 5px 16px;
        border-radius: 100px;
        margin-bottom: 14px;
    }
    .clients-carousel-title {
        font-size: clamp(1.5rem, 3vw, 2.1rem);
        color: #0f172a;
        font-weight: 700;
        margin: 0;
    }

    /* Swiper strip */
    .clients-swiper-outer {
        overflow: hidden;
        padding: 8px 0 10px;
        cursor: grab;
    }
    .clients-swiper-outer:active { cursor: grabbing; }
    .clientsSwiper {
        overflow: visible !important;
    }
    .clientsSwiper .swiper-wrapper {
        align-items: stretch !important;
    }
    .clientsSwiper .swiper-slide {
        width: 178px !important;
        height: auto !important;
    }

    /* Card */
    .client-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px 16px 16px;
        height: 100%;
        box-sizing: border-box;
        transition: box-shadow 0.28s ease, border-color 0.28s ease, transform 0.28s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .client-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(6,109,119,0.04) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.28s ease;
        pointer-events: none;
        border-radius: inherit;
    }
    .client-card:hover {
        box-shadow: 0 10px 32px rgba(6,109,119,0.16);
        border-color: #066D77;
        transform: translateY(-4px) scale(1.03);
    }
    .client-card:hover::before { opacity: 1; }

    /* Logo image */
    .client-card img {
        width: 130px;
        height: 130px;
        object-fit: contain;
        display: block;
        transition: filter 0.28s ease, transform 0.28s ease;
        filter: grayscale(20%);
    }
    .client-card:hover img {
        filter: grayscale(0%) drop-shadow(0 3px 8px rgba(6,109,119,0.22));
        transform: scale(1.06);
    }

    /* Name */
    .client-card .client-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.9px;
        line-height: 1.3;
        max-width: 146px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: color 0.28s ease;
    }
    .client-card:hover .client-label { color: #066D77; }

    /* Footer link */
    .clients-carousel-footer {
        text-align: center;
        margin-top: 36px;
    }
    .clients-view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid #0f172a;
        color: #0f172a;
        border-radius: 100px;
        padding: 10px 28px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-decoration: none;
        transition: background 0.22s ease, color 0.22s ease, border-color 0.22s ease, transform 0.22s ease;
    }
    .clients-view-all:hover {
        background: #066D77;
        color: #fff;
        border-color: #066D77;
        transform: translateY(-2px);
    }
</style>

<section class="clients-carousel-section">
    <div class="container">
        <div class="clients-carousel-header" data-aos="fade-up">
            <span class="clients-carousel-tag">Trusted By</span>
            <h2 class="clients-carousel-title">Healthcare Leaders Who Trust Us</h2>
        </div>
    </div>

    <div class="clients-swiper-outer">
        <div class="swiper clientsSwiper">
            <div class="swiper-wrapper">
                @foreach($clients as $client)
                <div class="swiper-slide">
                    <div class="client-card">
                        <img src="{{ asset('public/uploads/clients/' . $client->logo) }}"
                             alt="{{ $client->name }}" loading="lazy">
                        <span class="client-label">{{ $client->name }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container">
        <div class="clients-carousel-footer" data-aos="fade-up">
            <a href="{{ route('front.our-clients') }}" class="clients-view-all">
                View All Clients <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<script>
(function () {
    function initClientsSwiper() {
        if (typeof Swiper === 'undefined' || !document.querySelector('.clientsSwiper')) return;
        new Swiper('.clientsSwiper', {
            slidesPerView: 'auto',
            spaceBetween: 18,
            loop: true,
            speed: 4000,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            allowTouchMove: true,
            grabCursor: true,
            freeMode: {
                enabled: true,
                momentum: true,
                momentumRatio: 0.6,
            },
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initClientsSwiper);
    } else {
        initClientsSwiper();
    }
})();
</script>
@endif
