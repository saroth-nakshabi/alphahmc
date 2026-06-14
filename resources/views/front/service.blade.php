@extends('front/layout-2')
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/service-pages-shared.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/service-detail.css') }}?v=3">
@endsection
@push('page_title')
    {!! $service->name !!}
@endpush
@push('meta')
    <meta name="description" content="{{ $service->meta_description }}">
    <meta name="keywords" content="{{ $service->meta_keywords }}">
    {{-- Non-blocking Inter font (moved out of body to prevent render-block) --}}
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
    {{-- Preload hero image so browser fetches it at highest priority --}}
    @php
        $heroPreload = $service->hero_image
            ? asset('public/uploads/service_images/' . $service->hero_image)
            : asset('public/front/assets/img/hero/service-details-bg.jpg');
    @endphp
    <link rel="preload" as="image" href="{{ $heroPreload }}" fetchpriority="high">
@endpush
@push('og_tags')
    {{-- <link rel="canonical" href="{{ url('/' . $service->slug) }}"> --}}
    <meta name="author" content="Alpha Health Group">
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="{{ $service->meta_title }}" />
    <meta property="og:description" content="{{ $service->meta_description }}" />
    <meta property="og:image" content="{{ asset('public/uploads/service_images/' . $service->hero_image) }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $service->meta_title }}" />
    <meta name="twitter:description" content="{{ $service->meta_description }}" />
@endpush
@section('content')
    

    <div class="service-page-wrapper">
        <!-- Hero Section -->
        <section class="service-hero">
            <div class="hero-background"
                style="background-image: linear-gradient(to right, rgba(0, 0, 0, 85%), rgba(202, 202, 202, 0.363)),url('{{ $service->hero_image ? asset('public/uploads/service_images/' . $service->hero_image) : (isset($service->images[0]) ? asset('public/uploads/service_images/' . $service->images[0]->image) : asset('public/front/assets/img/hero/service-details-bg.jpg')) }}');">
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $service->name }}</h1>
                        <div class="hero-desc-wrapper">
                            {!! $service->content !!}
                        </div>
                        <div class="hero-meta mt-4" style="display: flex; gap: 25px; font-size: 0.9rem; color: rgba(255,255,255,0.8); border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; max-width: 500px;">
                            <span title="Date this service was listed">Published: {{ ($service->published_date ?? $service->created_at)->format('M d, Y') }}</span>
                            <span title="Date this service was last modified"> Updated: {{ ($service->updated_date ?? $service->updated_at)->format('M d, Y') }}</span>
                        </div>
                        <div class="hero-actions">
                            <button type="button"
    class="glass-btn"
    id="scrollToHelp">
    <span>Contact Us</span>
    <i class="fa-solid fa-arrow-right"></i>
</button>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="hero-breadcrumb" aria-label="Breadcrumb">
                <div class="container">
                    <a href="{{ route('home') }}">Home</a>
                    <span class="bc-sep">›</span>
                    <a href="{{ url('/services') }}">Services</a>
                    <span class="bc-sep">›</span>
                    <span class="bc-current">{{ $service->name }}</span>
                </div>
            </nav>
        </section>

        
        <script>
            document.getElementById('scrollToHelp').addEventListener('click', function () {
    const target = document.getElementById('how-alpha-can-help');

    if (target) {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
});

document.getElementById('scrollToHelp').addEventListener('click', function () {
    const target = document.getElementById('how-alpha-can-help');

    if (target) {
        const offset = 100; // adjust based on navbar height
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = window.pageYOffset + elementPosition - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
        });
    }
});
</script>



        <!-- Quote Section -->
       <section class="quote-section" data-aos="fade-up">
    <div class="container">
        <div class="quote-inner">
            {{-- <span class="quote-eyebrow">Our Approach</span> --}}
            <div class="quote-text">
                {!! $service->overview !!}
            </div>
            <div class="quote-divider"></div>
        </div>
    </div>
</section>

        <!-- Transformation Section (Dark) -->
        <section class="transformation-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4" data-aos="fade-right">
                        <div class="large-info">
                            {!! $service->info_one !!}
                        </div>
                        {{-- Left Read more removed as requested --}}
                    </div>
                    <div class="col-lg-7 offset-lg-1" data-aos="fade-left">
                        <div class="transformation-desc-wrapper" id="transformation-desc-wrapper">
                            <div class="transformation-desc">
                                {!! $service->info_two !!}
                            </div>
                        </div>
                        <button class="transformation-read-more" id="transformation-toggle"
                            onclick="toggleTransformationDesc()">
                            Read more <i class="fa-solid fa-arrow-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <script>
            function toggleTransformationDesc() {
    const wrapper = document.getElementById('transformation-desc-wrapper');
    const button = document.getElementById('transformation-toggle');

    wrapper.classList.toggle('active');

    if (wrapper.classList.contains('active')) {
        button.innerHTML = 'Read less <i class="fa-solid fa-arrow-up"></i>';
    } else {
        button.innerHTML = 'Read more <i class="fa-solid fa-arrow-down"></i>';
    }
}
        </script>

        <!-- Dynamic Magazine / Insights Section -->
        <section class="magazine-section" id="magazine-insights">
            <div class="container">
                @php
                    $displayMagazines = $service->magazines;
                    if ($displayMagazines->count() == 0) {
                        $displayMagazines = collect([
                            (object) [
                                'title' => 'Strategic Foundation',
                                'description' => 'We begin by understanding the healthcare model, service mix, patient volumes, and future growth strategy. This information is translated into high-level planning concepts.',
                                'image' => 'https://images.unsplash.com/photo-1559839734-2b71f1536785?q=80&w=1200'
                            ],
                            (object) [
                                'title' => 'Strategic Approach',
                                'description' => 'Transform vision into structured, compliant, and scalable healthcare concepts setting the direction for successful projects.',
                                'image' => 'https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=1200'
                            ],
                            (object) [
                                'title' => 'Excellence in Care',
                                'description' => 'Our designs prioritize efficient patient and staff movement, separation of clean and dirty flows, emergency access, and compliance.',
                                'image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=1200'
                            ]
                        ]);
                    }

                    $firstMag = $displayMagazines->first();
                    $hasFirstImg = $firstMag && $firstMag->image;
                @endphp

                <div class="mag-container {{ !$hasFirstImg ? 'no-img-active' : '' }}">

                    <!-- Left Side: Large Image -->
                    <div class="mag-image-side" data-aos="fade-right">
                        <div class="mag-image-container">
                            @php
                                $firstMag = $displayMagazines->first();
                                $firstImg = ($firstMag && $firstMag->image)
                                    ? ((strpos($firstMag->image, 'http') === 0)
                                        ? $firstMag->image
                                        : asset('public/uploads/magazines/' . $firstMag->image))
                                    : '';
                            @endphp
                            <img src="{{ $firstImg }}" alt="Magazine Feature" id="mag-main-image">

                            <!-- Manual Nav Buttons -->
                            <div class="mag-img-nav">
                                <button class="mag-img-btn" id="mag-img-prev" aria-label="Previous image">
                                    &#8249;
                                </button>
                                <button class="mag-img-btn" id="mag-img-next" aria-label="Next image">
                                    &#8250;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Teal Slider Container -->
                    <div class="mag-content-side" data-aos="fade-left">
                        <div class="swiper mag-swiper-container">
                           <div class="swiper-wrapper">
    {{-- Original slides --}}

    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : asset('public/uploads/magazines/' . $mag->image)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <span class="mag-card-eyebrow">{{ $mag->title }}</span>
                <div class="mag-desc">{!! strip_tags($mag->description) !!}</div>
            </div>
        </div>
    @endforeach

    {{-- Duplicate slides so loop never runs dry (repeat twice) --}}
    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : asset('public/uploads/magazines/' . $mag->image)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <span class="mag-card-eyebrow">{{ $mag->title }}</span>
                <div class="mag-desc">{!! strip_tags($mag->description) !!}</div>
            </div>
        </div>
    @endforeach

    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : asset('public/uploads/magazines/' . $mag->image)) : '';
        @endphp
        <div class="swiper-slide">
            <div class="mag-card" data-img="{{ $currImg }}">
                <span class="mag-card-eyebrow">{{ $mag->title }}</span>
                <div class="mag-desc">{!! strip_tags($mag->description) !!}</div>
            </div>
        </div>
    @endforeach
</div>
                        </div>
                        <div class="swiper-pagination mag-pagination"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Slider Script (Screenshot Style) -->
  <script>
document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('mag-main-image');

    // Collect only ORIGINAL images (first set before duplicates)
    const allCards = document.querySelectorAll('.mag-card');
    const totalOriginal = allCards.length / 3;
    const magazineImages = [];
    allCards.forEach((card, i) => {
        if (i < totalOriginal) {
            magazineImages.push(card.getAttribute('data-img') || '');
        }
    });

    function syncImage(realIndex) {
        if (!mainImage) return;
        const idx = ((realIndex % magazineImages.length) + magazineImages.length) % magazineImages.length;
        const newImg = magazineImages[idx];
        if (!newImg) return;

        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.src = newImg;
            mainImage.style.opacity = '1';
        }, 400);
    }

    const magSwiper = new Swiper('.mag-swiper-container', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        loopedSlides: totalOriginal,
        speed: 800,
        grabCursor: true,
        allowTouchMove: true,
        centeredSlides: true,
        watchSlidesProgress: true,
        pagination: {
            el: '.mag-pagination',
            clickable: true,
        },
        breakpoints: {
            0: {
                centeredSlides: false,
                spaceBetween: 20,
            },
            992: {
                centeredSlides: true,
                spaceBetween: 8,
            }
        },
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
            stopOnLastSlide: false,
        },
        on: {
            realIndexChange: function () {
                syncImage(this.realIndex);
            }
        }
    });

    // Keep autoplay alive after manual nav
    const imgPrevBtn = document.getElementById('mag-img-prev');
    const imgNextBtn = document.getElementById('mag-img-next');

    if (imgNextBtn) imgNextBtn.addEventListener('click', () => {
        magSwiper.slideNext(800);
        magSwiper.autoplay.start(); // restart autoplay after manual click
    });
    if (imgPrevBtn) imgPrevBtn.addEventListener('click', () => {
        magSwiper.slidePrev(800);
        magSwiper.autoplay.start();
    });

    if (mainImage) mainImage.style.transition = 'opacity 0.4s ease';
});

function toggleTransformationDesc() {
    const wrapper = document.getElementById('transformation-desc-wrapper');
    const btn = document.getElementById('transformation-toggle');
    const isExpanded = wrapper.classList.contains('expanded');
    if (isExpanded) {
        wrapper.classList.remove('expanded');
        btn.innerHTML = 'Read more <i class="fa-solid fa-arrow-down"></i>';
    } else {
        wrapper.classList.add('expanded');
        btn.innerHTML = 'Read less <i class="fa-solid fa-arrow-up"></i>';
    }
}
</script>


<!-- How Alpha Can Help Section - EY Style Refined -->
        <section class="help-section" id="how-alpha-can-help">
            <div class="container">
                <div class="help-flex">
                    <div class="help-info" data-aos="fade-up">
                        {{-- <h2>How Alpha can help</h2> --}}

                        <div class="help-text-block">
                            {{-- <h4>{{ $service->name }} solution</h4> --}}
                            @if($service->info_three && trim($service->info_three) != '')
                                {!! $service->info_three !!}
                            @else
                                <p>Our multidisciplinary team combines architectural excellence with deep clinical insights to
                                    deliver projects that are not just buildings, but platforms for healing.</p>
                            @endif

                            @if($service->info_four && trim($service->info_four) != '')
                                <div class="mt-4">
                                    {!! $service->info_four !!}
                                </div>
                            @endif
                            <button type="button" class="help-enquiry-btn" data-bs-toggle="modal"
                                data-bs-target="#inquiryModal">
                                <i class="fas fa-envelope me-2"></i> Send Enquiry
                            </button>
                        </div>
                    </div>

                    <div class="leader-sidebar" data-aos="fade-left">
                        <span class="leader-label">Service Leader</span>
                        <hr class="leader-hr">
                        <div class="leader-profile">
                            <img src="{{ isset($service->agent) && $service->agent->image ? asset('public/uploads/agent_images/' . $service->agent->image) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
                                alt="Leader" class="leader-circle-img">
                            <div class="leader-meta">
                                <div class="leader-name-bold">
                                    {{ isset($service->agent) ? ($service->agent->user->first_name . ' ' . $service->agent->user->last_name) : 'Dr. Vikram Singh' }}
                                </div>
                                <div class="leader-job-title">
                                    {{ isset($service->agent) ? $service->agent->title : 'Global Healthcare Leader, Alpha' }}
                                </div>

                                <div class="leader-contact-links">
                                    @php
                                        $agentUser = isset($service->agent) ? $service->agent->user : null;
                                        $phone = $agentUser ? $agentUser->phone : '+971 4 272 4064';
                                        $email = $agentUser ? $agentUser->email : 'info@alphahealth.com';
                                        $whatsappPhone = preg_replace('/[^0-9]/', '', $phone);
                                    @endphp

                                    <a href="tel:{{ $phone }}" class="contact-link-icon" title="Call">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="contact-link-icon" title="Email"
                                        data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="https://wa.me/{{ $whatsappPhone }}" target="_blank"
                                        class="contact-link-icon whatsapp-color" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!--FAQ Section-->
        <section class="help-list-section" id="faq-section">
            <div class="container">
                <header class="mb-5" data-aos="fade-up">
                    <h2 class="mb-3">Frequently Asked Questions</h2>
                    <p class="text-muted">Common questions about {{ $service->name }} and our approach.</p>
                </header>

                <div class="faq-accordion">
                    @php
                        $displayFaqs = $service->faq ?? collect();
                        if ($displayFaqs->count() == 0) {
                            $displayFaqs = collect([
                                (object) [
                                    'faq_question' => 'What is the primary focus of ' . $service->name . '?',
                                    'faq_answer' => 'Our ' . $service->name . ' service focuses on delivering comprehensive, state-of-the-art solutions tailored to unique healthcare environments, ensuring operational efficiency and patient-centric care.'
                                ],
                                (object) [
                                    'faq_question' => 'How does Alpha Healthcare approach project timelines?',
                                    'faq_answer' => 'We utilize agile methodologies and deep clinical expertise to ensure projects are delivered on time without compromising on the high standards of safety and quality expected in healthcare.'
                                ],
                                (object) [
                                    'faq_question' => 'Can these services be customized for specific facility needs?',
                                    'faq_answer' => 'Absolutely. Every healthcare facility is different. Our multidisciplinary team works closely with stakeholders to adapt our strategies to your specific operational goals and site constraints.'
                                ]
                            ]);
                        }
                    @endphp

                    @foreach ($displayFaqs as $index => $faq)
                        <div class="faq-item {{ $index === 0 ? 'active' : '' }}" data-aos="fade-up"
                            data-aos-delay="{{ min($index * 100, 500) }}">
                            <button class="faq-header" onclick="toggleFaq(this)">
                                <h4 class="faq-question">{{ $faq->faq_question }}</h4>
                                <span class="faq-icon"><i class="fa-solid fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-content">
                                <div class="faq-body">
                                    {!! $faq->faq_answer !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <script>
            function toggleFaq(el) {
                const item = el.closest('.faq-item');
                item.classList.toggle('active');
                const content = item.querySelector('.faq-content');
                if (item.classList.contains('active')) {
                    content.style.maxHeight = content.scrollHeight + "px";
                } else {
                    content.style.maxHeight = null;
                }
            }

            // Initialize active FAQ content height
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.faq-item.active .faq-content').forEach(el => {
                    el.style.maxHeight = el.scrollHeight + "px";
                });
            });
        </script>

        <!-- Case Studies & Related Content Split Section -->
        <section class="split-section-ey">
            <div class="container">
                <div class="row">
                    <!-- Left Column: Case Studies -->
                    <div class="col-lg-7" data-aos="fade-right">
                        <h2 class="ey-section-title">Case Studies</h2>
                        <div class="row g-4">
                            @foreach ($projects->take(2) as $project)
                                <div class="col-md-6">
                                    <div class="ey-case-card">
                                        <div class="ey-case-img-wrapper">
                                            @if(isset($project->projects_images[0]))
                                                <img src="{{ asset('public/' . $project->projects_images[0]->image) }}"
                                                    class="ey-case-img" alt="{{ $project->name }}">
                                            @endif
                                        </div>
                                        <div class="ey-case-body">
                                            <span class="ey-case-cat">{{ $project->project_category->name }}</span>
                                            <h4 class="ey-case-title">{{ $project->name }}</h4>
                                            <div class="ey-case-meta" style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 15px; display: flex; gap: 10px;">
                                                <span><i class="far fa-calendar-alt"></i> {{ $project->created_at->format('M d, Y') }}</span>
                                                <span><i class="fas fa-history"></i> {{ $project->updated_at->format('M d, Y') }}</span>
                                            </div>
                                            <a href="{{ route('front.project_details', $project->slug) }}" class="text-dark fw-bold text-decoration-none">
                                VIEW CASE STUDY <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Column: Blog Posts -->
                    <div class="col-lg-5" data-aos="fade-left">
                        <div class="related-content-block">
                            <h2 class="ey-section-title">Related Article</h2>
                            @php
                                $blog = $latest_blogs->first();
                            @endphp
                            @if($blog)
                                <a href="{{ route('front.singleBlog', $blog->slug) }}" class="ey-related-card">
                                    <img src="{{ isset($blog->image) && $blog->image ? asset('public/uploads/blog_images/' . $blog->image) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
                                        class="rc-bg-img" alt="{{ $blog->title }}">

                                    <div class="ey-related-content">
                                        <h3 class="rc-title">{{ $blog->title }}</h3>
                                        <div class="rc-desc">
                                            {!! Str::limit(strip_tags($blog->content), 120) !!}
                                        </div>
                                        <div class="rc-meta">
                                            <span class="rc-author">
                                                <i class="far fa-user"></i> By {{ $blog->tags->first()->name ?? 'Alpha Team' }}
                                            </span>
                                            <div class="rc-divider"></div>
                                            <span class="rc-date" title="Published Date">
                                                <i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('M d, Y') }}
                                            </span>
                                            <div class="rc-divider"></div>
                                            <span class="rc-date" title="Last Updated">
                                                <i class="fas fa-history"></i> {{ $blog->updated_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="text-center py-5">
                                    <p class="text-muted">No related content available.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>



        {{-- ── Testimonials (conditional) ────────────────────── --}}
        @if($service->show_testimonials)
            @include('front.partials.testimonial-pills')
        @endif

        <!-- Bottom Interests -->
        <section class="bottom-interests">
            <div class="container">
                <h3 class="mb-5 text-center" style="font-size: 2.8rem; font-weight: 700; color: #1a1a1a;">You might be
                    interested in</h3>
                <div class="row g-4">
                    @php
                        $displayServices = isset($featuredServices) ? $featuredServices->take(3) : collect();
                    @endphp
                    @foreach ($displayServices as $related)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <a href="{{ route('front.service', $related->slug) }}" class="interest-card-link">
                                <div class="interest-card">
                                    <div class="interest-img-wrapper">
                                        <img src="{{ $related->hero_image ? asset('public/uploads/service_images/' . $related->hero_image) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}"
                                            alt="{{ $related->name }}">
                                    </div>
                                    <div class="interest-content">
                                        <span class="text-uppercase small fw-bold text-muted mb-2 d-block">
                                            Featured Service
                                        </span>
                                        <h4>{{ $related->name }}</h4>
                                        <div class="interest-meta">
                                            <span>View Service</span>
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        @include('front.view.announcement')

        <!-- Inquiry Modal -->
        <div class="modal fade inquiry-modal" id="inquiryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="row g-0">
                        <!-- Left Panel: Brand Context -->
                        <div class="col-lg-4 d-none d-lg-block"
                            style="background: linear-gradient(135deg, #1a1a1a 0%, #000 100%); padding: 50px 20px; color: #fff;">
                            <div class="mb-5">
                                <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Alpha"
                                    style="width: 100px; filter: brightness(0) invert(1);">
                            </div>
                            <h3 class="fw-bold mb-4" style="font-size: 1.8rem; line-height: 1.2;">Elevate your healthcare
                                standards.</h3>
                            <p class="opacity-75 mb-5" style="font-size: 0.95rem; line-height: 1.6;">Our experts are ready
                                to
                                partner with you. Share your requirements and we'll craft a bespoke solution.</p>

                            {{-- Agent info inside modal --}}
                            @if(isset($service->agent))
                                <div class="d-flex align-items-center gap-3 mb-4 p-3"
                                    style="background:rgba(255,255,255,0.1); border-radius:12px;">
                                    <img src="{{ $service->agent->image ? asset('public/uploads/agent_images/' . $service->agent->image) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
                                        alt="Agent"
                                        style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.4);">
                                    <div>
                                        <div style="font-weight:700;font-size:1rem;">
                                            {{ $service->agent->user->first_name . ' ' . $service->agent->user->last_name }}
                                        </div>
                                        <div style="opacity:0.7;font-size:0.85rem;">
                                            {{ $service->agent->title ?? 'Service Leader' }}</div>
                                    </div>
                                </div>
                            @endif

                            <div class="inquiry-steps">
                                <div class="inquiry-step mb-4">
                                    <div class="step-num">STEP 01</div>
                                    <div class="step-text">Contact Information</div>
                                </div>
                                <div class="inquiry-step mb-4 opacity-50">
                                    <div class="step-num">STEP 02</div>
                                    <div class="step-text">Service Selection</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Form -->
                        <div class="col-lg-8 p-4 p-md-5 bg-white position-relative">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-4"
                                data-bs-dismiss="modal" aria-label="Close"></button>

                            <div class="mb-5">
                                <span class="text-uppercase tracking-wider fw-bold text-muted small d-block mb-2">Connect
                                    with us</span>
                                <h2 class="fw-bold text-dark" style="letter-spacing: -0.5px;">Service Inquiry</h2>
                                @if(session('success'))
                                    <div class="alert alert-success mt-3" style="border-radius: 12px;">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>

                            <form id="inquiryForm" action="{{ route('front.inquiry.submit') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="name" class="form-control bg-light border-0"
                                                id="inqName" placeholder="Name" required>
                                            <label for="inqName">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" name="email" class="form-control bg-light border-0"
                                                id="inqEmail" placeholder="Email" required>
                                            <label for="inqEmail">Email Address</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="tel" name="phone" class="form-control bg-light border-0"
                                                id="inqPhone" placeholder="Phone" required>
                                            <label for="inqPhone">Phone Number</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select name="service_id" class="form-select bg-light border-0" id="inqService">
                                                <option selected disabled>Choose a specialization</option>
                                                @foreach($all_services as $s)
                                                    <option value="{{ $s->id }}" {{ $s->id == $service->id ? 'selected' : '' }}>
                                                        {{ $s->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="inqService">Requested Service</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea name="message" class="form-control bg-light border-0"
                                                placeholder="Message" id="inqMessage" style="height: 250px"></textarea>
                                            <label for="inqMessage">How can we help you?</label>
                                        </div>
                                    </div>
                                    <div class="col-12 pt-3">
                                        <button type="submit" class="btn btn-dark w-100 py-3 fw-bold shadow-sm"
                                            style="border-radius: 12px; letter-spacing: 0.5px;">
                                            SEND INQUIRY
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Share functionality (Global)
                const shareBtn = document.getElementById('share-icon');
                if (shareBtn) {
                    shareBtn.addEventListener('click', async (e) => {
                        e.preventDefault();
                        if (navigator.share) {
                            try {
                                await navigator.share({
                                    title: '{{ $service->name }}',
                                    text: 'Professional Healthcare Consultancy',
                                    url: window.location.href
                                });
                            } catch (err) { }
                        } else {
                            navigator.clipboard.writeText(window.location.href);
                            alert('Link copied to clipboard!');
                        }
                    });
                }


                // New 4th Item Slider
                new Swiper('.gallery-slider-4', {
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        // disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                });
            }

                        // AOS init
                        if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 50
                });
            }

            // Show inquiry modal on hash
            if (window.location.hash === '#inquiryModal' || window.location.hash === '#inquiry_success') {
                const inquiryModalNode = document.getElementById('inquiryModal');
                if (inquiryModalNode) {
                    const inquiryModal = new bootstrap.Modal(inquiryModalNode);
                    inquiryModal.show();
                }
            }
                    });

            // FAQ Toggle Function (outside document.ready so it's globally available to onclick attrs)
            function toggleFaq(button) {
                const item = button.parentElement;
                const isActive = item.classList.contains('active');

                // Close all other items
                document.querySelectorAll('.faq-item').forEach(el => {
                    el.classList.remove('active');
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            }
        </script>
    @endpush

    {{-- Page context for conversion tracker --}}
    @push('scripts')
    <script>
        window._ahgPage = {
            service_name: @json($service->name ?? ''),
            service_slug: @json($service->slug ?? ''),
            page_type:    'service',
        };
    </script>
    @endpush
@endsection