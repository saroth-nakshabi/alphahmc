@php
    $testimonials = \App\Models\Testimonial::latest()->get();
@endphp


{{--This for only show featured testimonials--}}
{{-- @php
    $testimonials = \App\Models\Testimonial::where('featured', true)->get();
@endphp --}} 
<section class="testimonials-section">
    <div class="testimonials-container">

        <style>

    .testimonials-section {
    padding: 80px 20px;
    background: #f8fafc;
    font-family: 'Inter', system-ui, sans-serif;
}

.testimonials-container {
    max-width: 760px;
    margin: 0 auto;
    position: relative;
}

/* Header */
.testimonials-header {
    text-align: center;
    margin-bottom: 50px;
}

.testimonials-badge {
    display: inline-block;
    background: #066D77;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 999px;
    margin-bottom: 14px;
}

.testimonials-title {
    font-size: clamp(28px, 5vw, 40px);
    font-weight: 700;
    color: #1e1b4b;
    margin: 0 0 12px;
    line-height: 1.2;
}

.testimonials-subtitle {
    font-size: 17px;
    color: #64748b;
    margin: 0;
}

/* Carousel */
.testimonials-carousel-wrapper {
    overflow: hidden;
    position: relative;
    border-radius: 20px;
}

.testimonials-track {
    display: flex;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

/* Card */
.testimonial-card {
    min-width: 100%;
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    box-sizing: border-box;
}

/* Quote icon */
.testimonial-quote-icon {
    color: rgba(66, 175, 185, 0.541)93;
    margin-bottom: 18px;
}

/* Text */
.testimonial-text {
    font-size: 17px;
    line-height: 1.75;
    color: #334155;
    margin: 0 0 24px;
    font-style: italic;
}

/* Stars */
.testimonial-stars {
    display: flex;
    gap: 4px;
    margin-bottom: 24px;
}

.star {
    width: 18px;
    height: 18px;
}

.star.filled { color: #f59e0b; }
.star.empty  { color: #e2e8f0; }

/* Author */
.testimonial-author {
    display: flex;
    align-items: center;
    gap: 14px;
}

.author-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.author-avatar-img {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.author-info strong {
    display: block;
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
}

.author-info span {
    font-size: 13px;
    color: #94a3b8;
}

/* Dots */
.testimonials-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 32px;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: none;
    background: #cbd5e1;
    cursor: pointer;
    padding: 0;
    transition: background 0.3s, transform 0.3s, width 0.3s;
}

.dot.active {
    background: #058b97;
    transform: scale(1.3);
    width: 28px;
    border-radius: 999px;
}

/* Controls */
.testimonials-controls {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 20px;
}

.control-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #475569;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
}

.control-btn:hover {
    background: #066D77;
    border-color: #01becf;
    color: #fff;
}

@media (max-width: 600px) {
    .testimonial-card { padding: 28px 20px; }
    .testimonial-text { font-size: 15px; }
}

</style>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const track   = document.getElementById('testimonialsTrack');
    const dots    = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (!track) return;

    const cards = track.querySelectorAll('.testimonial-card');
    if (cards.length === 0) return;

    let current  = 0;
    let timer    = null;
    let startX   = 0;
    const INTERVAL = 4000;
    const total  = cards.length;

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }
    function startAuto() { timer = setInterval(next, INTERVAL); }
    function resetAuto()  { clearInterval(timer); startAuto(); }

    nextBtn.addEventListener('click', () => { next(); resetAuto(); });
    prevBtn.addEventListener('click', () => { prev(); resetAuto(); });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const idx = parseInt(dot.dataset.index);
            if (idx !== current) { goTo(idx); resetAuto(); }
        });
    });

    // Touch swipe
    track.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
        clearInterval(timer);
    }, { passive: true });

    track.addEventListener('touchend', e => {
        const delta = e.changedTouches[0].clientX - startX;
        if (delta < -50) next();
        else if (delta > 50) prev();
        startAuto();
    }, { passive: true });

    goTo(0);
    startAuto();
});
</script>

        {{-- Section Header --}}
        <div class="testimonials-header">
            <span class="testimonials-badge">Testimonials</span>
            <h2 class="testimonials-title">What Our Clients Say</h2>
            <p class="testimonials-subtitle">
                Trusted by thousands of happy customers around the world.
            </p>
        </div>

        @if($testimonials->count() > 0)
        {{-- Carousel Wrapper --}}
        <div class="testimonials-carousel-wrapper">
            <div class="testimonials-track" id="testimonialsTrack">

                @foreach($testimonials as $index => $testimonial)
                <div class="testimonial-card {{ $index === 0 ? 'active' : '' }}">
                    <div class="testimonial-quote-icon">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                            <path d="M10 22c0-4.4 3.6-8 8-8V10C9.2 10 3 16.2 3 24v2h7v-4zm16 0c0-4.4 3.6-8 8-8V10c-8.8 0-15 6.2-15 14v2h7v-4z" fill="currentColor"/>
                        </svg>
                    </div>
                    <p class="testimonial-text">{{ $testimonial->content }}</p>
                    <div class="testimonial-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="star {{ $i <= $testimonial->rating ? 'filled' : 'empty' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <div class="testimonial-author">
                        @if($testimonial->author_image)
                            <img 
                                src="{{ asset('public/uploads/testimonials/' . $testimonial->author_image) }}" 
                                alt="{{ $testimonial->author_name }}"
                                class="author-avatar-img"
                            >
                        @else
                            <div class="author-avatar" style="background: {{ ['#6366f1','#10b981','#f59e0b','#ef4444','#3b82f6'][$index % 5] }};">
                                <span>{{ strtoupper(substr($testimonial->author_name, 0, 1)) }}{{ strtoupper(substr(strrchr($testimonial->author_name, ' '), 1, 1)) }}</span>
                            </div>
                        @endif
                        <div class="author-info">
                            <strong>{{ $testimonial->author_name }}</strong>
                            <span>{{ $testimonial->position }}, {{ $testimonial->company_name }}</span>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        {{-- Dots Navigation --}}
        <div class="testimonials-dots" id="testimonialsDots">
            @foreach($testimonials as $index => $testimonial)
                <button class="dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" aria-label="Testimonial {{ $index + 1 }}"></button>
            @endforeach
        </div>

        {{-- Arrow Controls --}}
        <div class="testimonials-controls">
            <button class="control-btn" id="prevBtn" aria-label="Previous">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M12 15l-5-5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="control-btn" id="nextBtn" aria-label="Next">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M8 5l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        @else
            <p class="text-center text-muted">No testimonials available yet.</p>
        @endif

    </div>
</section>