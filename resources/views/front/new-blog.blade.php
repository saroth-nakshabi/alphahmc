@extends('front/layout-2')

@push('page_title', 'Healthcare Management Updates & Insights | Alpha Health Group')

@section('meta_description', 'Explore healthcare management updates, leadership guides, DOH compliance insights, and operational excellence strategies from Alpha Health Group experts in the UAE and GCC.')

@push('meta')
    <meta name="keywords" content="healthcare management updates, healthcare leaders guide, DOH compliance UAE, healthcare quality assurance, GCC healthcare insights, hospital management, healthcare consulting UAE, patient safety, healthcare operational excellence, Alpha Health Group blog">
    <meta name="author" content="Alpha Health Group">
    <meta name="robots" content="index, follow">
@endpush

@push('og_tags')
    <meta property="og:title" content="Healthcare Management Updates & Insights | Alpha Health Group" />
    <meta property="og:description" content="Expert articles and guides on healthcare management, DOH compliance, leadership strategies, and operational excellence for healthcare professionals across the UAE and GCC." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/healthcare-management-update-insights') }}" />
    <meta name="twitter:title" content="Healthcare Management Updates & Insights | Alpha Health Group" />
    <meta name="twitter:description" content="Expert articles and guides on healthcare management, DOH compliance, leadership strategies, and operational excellence across the UAE and GCC." />
@endpush

@section('content')
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/blog-base.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/new-blog.css') }}?v=5">
    <style>
        .blog-hero { background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.65)), url('{{ asset('public/uploads/service_images/1780592725_hero_image_5492b395-ef2a-467a-b3af-c05d3e9e1341.jpg') }}'); }
    </style>
{{-- HERO SECTION (MATCHING PROJECTS PAGE) --}}
    <section class="blog-hero">
        <div class="container">
            <div class="banner-text">
                <h1 class="hero-title">Healthcare Management Updates & Insights</h1>
                <p class="hero-description">
                    Your go-to resource for healthcare leaders — covering management updates, DOH compliance guidance, operational excellence strategies, and industry trends shaping healthcare across the UAE and GCC.
                </p>
                <a href="#" class="hero-btn" data-bs-toggle="modal" data-bs-target="#inquiryModal">Contact Us</a>
            </div>
            <nav class="hero-breadcrumb container" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="bc-sep">›</span>
                <a href="#">Insights</a>
                <span class="bc-sep">›</span>
                <span class="bc-current">Healthcare Management Updates</span>
            </nav>
        </div>
    </section>

    <div class="blog-page-wrapper">
        {{-- BLOG SUMMARY (MATCHING PROJECT SUMMARY) --}}
        {{--<section class="blog-summary-section">
            <div class="container-fluid p-0">
                <div class="summary-banner">
                    <div class="banner-left">
                        <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Alpha Logo">
                    </div>
                    <div class="banner-right">
                        <h2 class="summary-title">Blog Summary</h2>
                        <div class="summary-content">
                            <p>
                                At Alpha Innovations, we believe in the power of shared knowledge. Our blog serves as
                                a definitive resource for global business leaders, technology pioneers, and
                                healthcare professionals seeking to navigate the complexities of
                                a rapidly evolving digital world with precision and expertise.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>--}}

        {{-- FEATURED ARTICLE (MATCHING FEATURED PROJECT) --}}
        @php
            $featuredBlog = $blogs->first();   // first in the customer-defined order
        @endphp
        @if($featuredBlog)
            <section class="featured-article-section">
                <div class="container">
                    <div class="featured-box">
                        <div class="featured-image-side">
                            <img src="{{ $featuredBlog->image ? asset('public/uploads/blog_images/' . $featuredBlog->image) : asset('public/front-new/assets/images/section-3-1st-image.jpg') }}"
                                alt="{{ $featuredBlog->title }}">
                        </div>
                        <div class="featured-content-side">
                            <span class="featured-badge">Featured Insight</span>
                            <h2 class="featured-title">{{ $featuredBlog->title }}</h2>
                            <p class="featured-desc">
                                {{ Str::limit(strip_tags($featuredBlog->description), 220) }}
                            </p>

                            <div class="featured-meta-grid">
                                <div class="meta-item">
                                    <h4>Insight Focus</h4>
                                    @php
                                        $focusItems = collect(explode(',', $featuredBlog->news_focus ?? ''))
                                            ->map(fn ($x) => trim($x))->filter()->take(3);
                                        if ($focusItems->isEmpty()) {
                                            $focusItems = collect(['Industry Trends', 'Strategic Growth', 'Expert Analysis']);
                                        }
                                    @endphp
                                    <ul class="meta-list">
                                        @foreach ($focusItems as $focus)
                                            <li><i class="fas fa-check-circle"></i> {{ $focus }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="meta-item">
                                    <h4>Author</h4>
                                    <div class="meta-list">
                                        <li style="font-size: 1.2rem; color: var(--primary-navy);"><i class="far fa-user"></i> {{ $featuredBlog->author_name ?? 'Alpha Team' }}</li>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    @php $catCount = $featuredBlog->tags->isEmpty() ? 2 : $featuredBlog->tags->take(4)->count(); @endphp
                                    <h4>{{ $catCount === 1 ? 'Blog Category' : 'Blog Categories' }}</h4>
                                    <div class="tag-highlights">
                                        @foreach($featuredBlog->tags->take(4) as $tag)
                                            <span class="tag-highlight-item">{{ $tag->name }}</span>
                                        @endforeach
                                        @if($featuredBlog->tags->isEmpty())
                                            <span class="tag-highlight-item">Innovation</span>
                                            <span class="tag-highlight-item">Global Economy</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <h4>Last Updated</h4>
                                    <div class="tag-highlights">
                                        <span class="tag-highlight-item">{{ ($featuredBlog->updated_date ?? $featuredBlog->updated_at)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('front.singleBlog', $featuredBlog->slug) }}" class="btn-featured-cta">
                                Read Full Insight <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- BLOG GRID LISTING --}}
        <section class="blog-listing-section">
            <div class="container">
                <div class="listing-header">
                    <h2>Healthcare Management Updates</h2>
                    <p>Browse expert articles, leadership guides, and healthcare management insights filtered by your areas of interest.</p>
                </div>

                {{-- Filter Navigation --}}
                <div class="blog-filter-nav">
                    <button class="blog-filter-btn active" data-filter="all">All Post Collections</button>
                    @foreach($tags as $tag)
                        <button class="blog-filter-btn" data-filter="{{ strtolower($tag->name) }}">{{ $tag->name }}</button>
                    @endforeach
                </div>

                {{-- Grid --}}
                <div class="blog-grid" id="blogContainer">
                    @forelse($blogs as $blog)
                        <div class="blog-card-item" data-tags="{{ strtolower($blog->tags->pluck('name')->implode(' ')) }}">
                            <div class="blog-card-premium">
                                <div class="blog-card-img">
                                    @if($blog->tags->first())
                                        <span class="blog-tag-badge">{{ $blog->tags->first()->name }}</span>
                                    @endif
                                    <img src="{{ $blog->image ? asset('public/uploads/blog_images/' . $blog->image) : asset('public/front-new/assets/images/blog_images/blog-card-image-01.webp') }}"
                                        alt="{{ $blog->title }}">
                                </div>
                                <div class="blog-card-body">
                                    <h3>{{ $blog->title }}</h3>
                                    <div class="blog-meta-minimal">
                                        <div class="meta-author-row">
                                            <i class="far fa-user" style="color: var(--accent-orange); font-size: 0.8rem;"></i>
                                            By {{ $blog->author_name ?? 'Alpha Team' }}
                                        </div>
                                        <div class="meta-date-row">
                                            <span>Published: {{ $blog->created_at->format('M d, Y') }}</span>
                                            <div class="meta-dot-small"></div>
                                            <span>Updated: {{ $blog->updated_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <p class="blog-excerpt">
                                        {{ Str::limit(strip_tags($blog->description), 130) }}
                                    </p>
                                    <a href="{{ route('front.singleBlog', $blog->slug) }}" class="btn-read-more">
                                        Read Insight <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Stay tuned! We are currently curating more insights for you.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- CONVERSATION CTA --}}
        <section class="conversation-cta-section">
            <div class="container">
                <p class="cta-msg">
                    Inquisitive about how <span>Alpha Innovations</span> can elevate your <span>healthcare or technology
                        systems</span>? Let’s collaborate on your next breakthrough.
                </p>
                <button type="button" class="btn-conversation" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                    Connect With An Expert
                </button>
            </div>
        </section>

        {{-- RECENT PROJECTS SECTION --}}
        <section class="blog-projects-section">
            <div class="container">
                <div class="projects-split-wrapper">
                    {{-- Left Info Panel --}}
                    <div class="projects-info-panel">
                        <h2>Our Impact</h2>
                        <p>
                            Discover how we turn strategy into reality. Explore our recent case studies and project
                            milestones that demonstrate our commitment to excellence and innovation.
                        </p>
                        <a href="{{ route('front.project') }}" class="btn-projects-view">Explore Case Studies</a>
                    </div>

                    {{-- Right Projects List --}}
                    <div class="projects-list-panel">
                        @forelse($projects as $project)
                            <a href="{{ route('front.project_details', $project->id) }}" class="project-row-item">
                                <div class="project-row-content">
                                    <h4>{{ $project->name }}</h4>
                                    <div class="project-row-meta">
                                        <span
                                            style="color: var(--accent-orange);">{{ $project->project_category->name ?? 'Enterprise Solution' }}</span>
                                        <span class="meta-dot"></span>
                                        <span>Recent Achievement</span>
                                    </div>
                                </div>
                                <div class="project-row-thumb">
                                    @if($project->projects_images->first())
                                        <img src="{{ asset($project->projects_images->first()->image) }}" alt="Impact">
                                    @else
                                        <img src="{{ asset('public/front-new/assets/images/section-3-1st-image.jpg') }}"
                                            alt="Impact">
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-muted">Project archives currently being updated.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- JavaScript Filtering --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterBtns = document.querySelectorAll('.blog-filter-btn');
                const blogCards = document.querySelectorAll('#blogContainer > .blog-card-item');

                filterBtns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        // Update active class
                        filterBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');

                        const filter = this.getAttribute('data-filter').toLowerCase();

                        blogCards.forEach(card => {
                            const tags = card.getAttribute('data-tags').toLowerCase();
                            if (filter === 'all' || tags.includes(filter)) {
                                card.style.display = 'block';
                                setTimeout(() => {
                                    card.style.opacity = '1';
                                    card.style.transform = 'translateY(0)';
                                }, 50);
                            } else {
                                card.style.opacity = '0';
                                card.style.transform = 'translateY(20px)';
                                setTimeout(() => {
                                    card.style.display = 'none';
                                }, 300);
                            }
                        });
                    });
                });
            });
        </script>

    </div>

    <!-- Inquiry Modal (Consistent with Projects Page) -->
    <div class="modal fade inquiry-modal" id="inquiryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="row g-0">
                    <div class="col-lg-4 d-none d-lg-block"
                        style="background: linear-gradient(135deg, #0f172a 0%, #000 100%); padding: 60px 45px; color: #fff;">
                        <div class="mb-5">
                            <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Alpha"
                                style="width: 110px; filter: brightness(0) invert(1);">
                        </div>
                        <h3 class="fw-bold mb-4" style="font-size: 2rem; line-height: 1.2;">Innovation meets Expertise.</h3>
                        <p class="opacity-75 mb-5" style="font-size: 1.1rem; line-height: 1.6; font-weight: 300;">Ready to
                            accelerate your journey? Our experts are here to collaborate and innovate.</p>

                        <div class="inquiry-steps">
                            <div class="inquiry-step mb-4">
                                <div class="step-num" style="color: var(--accent-orange);">STEP 01</div>
                                <div class="step-text" style="font-size: 1.1rem; color: white;">Contact Details</div>
                            </div>
                            <div class="inquiry-step mb-4 opacity-50">
                                <div class="step-num" style="color: var(--accent-orange);">STEP 02</div>
                                <div class="step-text" style="font-size: 1.1rem; color: white;">Strategic Goals</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 p-5 bg-white position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal"
                            aria-label="Close"></button>

                        <div class="mb-5">
                            <span class="text-uppercase tracking-wider fw-bold text-muted small d-block mb-2"
                                style="letter-spacing: 2px;">Alpha Connect</span>
                            <h2 class="fw-bold text-dark" style="letter-spacing: -1px; font-size: 2.2rem;">Start a
                                Conversation</h2>
                        </div>

                        <form action="{{ route('front.inquiry.submit') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" class="form-control" id="inqName"
                                            placeholder="Full Name" required>
                                        <label for="inqName">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" class="form-control" id="inqEmail"
                                            placeholder="Email" required>
                                        <label for="inqEmail">Email Address</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select name="service_id" class="form-select" id="inqService">
                                            <option selected disabled>Select an area of interest</option>
                                            @if(isset($all_services))
                                                @foreach($all_services as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="inqService">Expertise Needed</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="message" class="form-control" placeholder="Tell us more..."
                                            id="inqMessage" style="height: 140px"></textarea>
                                        <label for="inqMessage">Your specific requirements</label>
                                    </div>
                                </div>
                                <div class="col-12 pt-4">
                                    <button type="submit" class="btn btn-dark w-100 py-4 fw-bold shadow-sm"
                                        style="border-radius: 12px; font-size: 1.1rem; letter-spacing: 1px;">
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
@endsection