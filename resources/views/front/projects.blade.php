@extends('front/layout-2')

@section('content')
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/blog-base.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/projects.css') }}?v=5">
    <style>
        .service-banner { background-image: linear-gradient(rgba(0, 0, 0, 0.3), var(--premium-black)),
                          url('{{ asset('public/uploads/service_images/1777903827_hero_image_f2b72665-520c-438d-b7ab-0df3442aecce.jpg') }}'); }
    </style>
<!-- Hero Section -->
<section class="service-banner">
    <div class="container">
        <div class="banner-text">
            <h1 class="hero-title">Turnkey Healthcare <br>Facility Projects</h1>
            <p class="hero-description">
                From concept and engineering through planning, construction, and full operational readiness, we deliver complete healthcare facility projects across the UAE and GCC. Explore how our specialists solve complex challenges and bring ambitious hospitals and clinics to life, on time and to standard.
            </p>
            <a href="#build-extraordinary" class="hero-btn">
                Start Your Project <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <nav class="hero-breadcrumb container" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">›</span>
        <span class="bc-current">Projects</span>
    </nav>
</section>

<!-- Project Summary -->
<section class="premium-solutions-section">
    <!-- Decorative soft blurs to give the glass something to "catch" -->
    <div class="bg-blur-circle"></div>
    
    <div class="container">
        <div class="solutions-card-wrapper">
            <div class="solutions-grid">
                
                <!-- Left: Branding Focus -->
                <div class="brand-panel">
                    <div class="logo-container">
                        <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" 
                             alt="Alpha Logo" class="brand-logo-modern">
                    </div>
                    <div class="experience-tag">
                        <span class="tag-number">25</span>
                        <span class="tag-text">Years of <br>Excellence</span>
                    </div>
                </div>

                <!-- Right: Value Proposition -->
                <div class="content-panel">
                    <h6 class="text-uppercase tracking-widest text-primary fw-bold small mb-3">
                        Enterprise Solutions
                    </h6>
                    <h2 class="display-title">Innovation Built on <br><span class="text-gradient">Experience</span></h2>
                    <p class="description-text">
                        Our technology consultants apply enterprise-level knowledge to provide best-practice solutions, ensuring your business remains competitive in an ever-evolving digital landscape.
                    </p>
                    <div class="action-footer">
                        <a href="#" class="btn-minimal">
                            Our Methodology <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="glass-showcase-section">
    <!-- These colorful shapes sit behind the glass card to create the effect -->
    <div class="glass-blob blob-1"></div>
    <div class="glass-blob blob-2"></div>

    <div class="container">
        @if(isset($featuredProject) && $featuredProject)
        <div class="featured-box">
            <div class="featured-image-side">
                @if($featuredProject->projects_images->count() > 0)
                    <img src="{{ asset('public/' . $featuredProject->projects_images[0]->image) }}"
                         alt="{{ $featuredProject->name }}">
                @else
                    <img src="{{ asset('public/front-new/assets/images/section-3-1st-image.jpg') }}"
                         alt="{{ $featuredProject->name }}">
                @endif
            </div>
            <div class="featured-content-side">
                <span class="featured-badge">Featured Case Study</span>
                <h2 class="featured-title">{{ $featuredProject->name }}</h2>
                <p class="featured-desc">
                    {{ Str::limit(strip_tags($featuredProject->description), 220) }}
                </p>

                <div class="featured-meta-grid">
                    <div class="meta-item">
                        <h4>Services Delivered</h4>
                        @if(isset($featuredServices) && $featuredServices->count() > 0)
                        <ul class="meta-list">
                            @foreach($featuredServices->take(4) as $svc)
                            <li><i class="fas fa-check-circle"></i> {{ $svc->name }}</li>
                            @endforeach
                        </ul>
                        @else
                        <ul class="meta-list">
                            <li><i class="fas fa-check-circle"></i> {{ $featuredProject->project_category->name ?? 'Healthcare IT' }}</li>
                        </ul>
                        @endif
                    </div>
                    <div class="meta-item">
                        <h4>Project Details</h4>
                        <div class="tech-tags">
                            @if($featuredProject->project_category)
                                <span class="tech-tag">{{ $featuredProject->project_category->name }}</span>
                            @endif
                            @if($featuredProject->project_location)
                                <span class="tech-tag">{{ $featuredProject->project_location }}</span>
                            @endif
                            @if($featuredProject->project_duration)
                                <span class="tech-tag">{{ $featuredProject->project_duration }}</span>
                            @endif
                            @if($featuredProject->client_name)
                                <span class="tech-tag">{{ $featuredProject->client_name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="meta-item">
                        <h4>Last Updated</h4>
                        <div class="tech-tags">
                            <span class="tech-tag">{{ ($featuredProject->updated_date ?? $featuredProject->updated_at)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('front.project_details', $featuredProject->slug) }}"
                   class="btn-featured-cta">
                    View Full Case Study <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Main Portfolio Grid -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold text-muted small letter-spacing-1">Portfolio</span>
            <h2 class="display-5 fw-bold">Explore Our Work</h2>
        </div>

        {{-- Premium Filter Navigation --}}
        <ul class="premium-filter-nav" id="categoryTab">
            <li class="premium-filter-item">
                <button class="premium-filter-btn active" data-category="all">All Projects</button>
            </li>
            @php $categories = []; @endphp
            @foreach ($projects as $project)
                @if ($project->project_category && !in_array($project->project_category->name, $categories))
                    @php $categories[] = $project->project_category->name; @endphp
                    <li class="premium-filter-item">
                        <button class="premium-filter-btn" data-category="{{ $project->project_category->name }}">
                            {{ $project->project_category->name }}
                        </button>
                    </li>
                @endif
            @endforeach
        </ul>

        {{-- Project Cards Grid --}}
        <div class="premium-project-grid" id="projectContainer">
            @foreach ($projects as $project)
                @if ($project->project_category)
                    <div class="premium-project-card" data-category="{{ $project->project_category->name }}">
                        <div class="project-img-container">
                            <img src="{{ isset($project->projects_images[0]) ? asset('public/' . $project->projects_images[0]->image) : asset('public/front-new/assets/images/section-3-1st-image.jpg') }}" alt="{{ $project->name }}">
                            <div class="project-cat-badge">{{ $project->project_category->name }}</div>
                        </div>
                        <div class="p-4 pt-5">
                            <span class="text-muted small fw-bold text-uppercase opacity-50">Global Impact</span>
                            <h3 class="fw-bold my-2" style="font-size: 1.5rem;">{{ $project->name }}</h3>
                            <div class="text-muted small mb-4">
                                {!! Str::limit(strip_tags($project->description), 120) !!}
                            </div>
                            <a href="{{ route('front.project_details', $project->slug) }}" class="text-dark fw-bold text-decoration-none">
                                VIEW CASE STUDY <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Conversion Section -->
<section id="build-extraordinary" class="py-5" style="background: #f8f9fa;">
    <div class="container py-5 text-center">
        <div class="max-width-700 mx-auto">
            <h2 class="fw-bold mb-4">Ready to build something extraordinary?</h2>
            <p class="lead text-muted mb-5">At Alpha Health Group, we don't believe in a one-solution-fits-all approach. We take the time to listen, learn, and deliver.</p>
            <button class="btn btn-dark btn-lg px-5 py-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                Start a Conversation
            </button>
        </div>
    </div>
</section>

@include('front.clients')

<section class="premium-blog-section">
    <div class="container">
        <div class="kb-main-card">
            <div class="blog-split-wrapper">
                {{-- Left Info Panel --}}
                <div class="blog-info-panel">
                    <span class="sub-badge">Stay Updated</span>
                    <h2>Knowledge Base</h2>
                    <p>
                        Stay in the loop with the latest company updates, industry insights, and insider tips.
                        Stay ahead with valuable knowledge curated by our experts.
                    </p>
                    <a href="{{ route('front.new_blog') }}" class="kb-btn">
                        <span>View All Articles</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Right Blog List --}}
                <div class="blog-list-panel">
                    @forelse ($latest_blogs ?? [] as $blog)
                        <a href="{{ route('front.singleBlog', $blog->slug) }}" class="blog-list-item">
                            <div class="blog-item-content">
                                <h3>{{ $blog->title }}</h3>
                                <div class="blog-item-meta">
                                    <span class="meta-dot"></span>
                                    <span>Alpha Health</span>
                                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                    <span>&#8226; 3 min read</span>
                                </div>
                            </div>
                            <div class="blog-item-thumb">
                                <img src="{{ asset('public/uploads/blog_images/' . $blog->image) }}"
                                    alt="{{ $blog->title }}" loading="lazy">
                            </div>
                        </a>
                    @empty
                        <p class="text-muted text-center py-4">No articles published yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<Script>
    document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.premium-filter-btn');
    const cards = document.querySelectorAll('.premium-project-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const selectedCategory = this.getAttribute('data-category').toLowerCase().trim();
            
            // Active state toggle
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category').toLowerCase().trim();
                
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    // Modern fade-in
                    card.style.display = 'block';
                    card.animate([
                        { opacity: 0, transform: 'scale(0.95) translateY(10px)' },
                        { opacity: 1, transform: 'scale(1) translateY(0)' }
                    ], { duration: 400, easing: 'ease-out', fill: 'forwards' });
                } else {
                    // Modern fade-out
                    const anim = card.animate([
                        { opacity: 1, transform: 'scale(1)' },
                        { opacity: 0, transform: 'scale(0.95) translateY(10px)' }
                    ], { duration: 300, easing: 'ease-in', fill: 'forwards' });
                    
                    anim.onfinish = () => card.style.display = 'none';
                }
            });
        });
    });
});
</Script>

<!-- Blog Section Placeholder - Clean & Minimal -->
{{-- <section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <h2 class="fw-bold display-6">From the Blog</h2>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('front.new_blog') }}" class="text-dark fw-bold">View all posts <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
        <div class="row g-4">
            @foreach ($display_blogs ?? [] as $blog)
            <div class="col-lg-4">
                <a href="{{ route('front.singleBlog', $blog->slug) }}" class="text-decoration-none">
                    <div class="card border-0 h-100">
                        <img src="{{ asset('public/uploads/blog_images/' . $blog->image) }}" class="rounded-4 mb-3" style="height: 240px; object-fit: cover;">
                        <span class="text-muted small">{{ $blog->created_at->format('M d, Y') }} • 3 min read</span>
                        <h4 class="text-dark fw-bold mt-2">{{ $blog->title }}</h4>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section> --}}

<!-- Inquiry Modal (Included in the main code as requested) -->
<div class="modal fade inquiry-modal" id="inquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="row g-0">
                <div class="col-lg-4 d-none d-lg-block" style="background: #000; padding: 50px 40px; color: #fff;">
                    <img src="{{ asset('public/front-new/assets/images/alpha-logo.svg') }}" alt="Logo" style="width: 80px; filter: invert(1); margin-bottom: 40px;">
                    <h3 class="fw-bold mb-4">Elevate your standards.</h3>
                    <p class="opacity-75 small">Share your requirements and we'll craft a bespoke solution for your enterprise.</p>
                </div>
                <div class="col-lg-8 p-5 bg-white">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                    <h2 class="fw-bold mb-4">Let's Talk</h2>
                    <form action="{{ route('front.inquiry.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control p-3 bg-light border-0" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control p-3 bg-light border-0" placeholder="Email Address" required>
                            </div>
                            <div class="col-12">
                                <select name="service_id" class="form-select p-3 bg-light border-0">
                                    <option selected disabled>Choose a service</option>
                                    @foreach ($all_services ?? [] as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control p-3 bg-light border-0" style="height: 120px" placeholder="Your Message"></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3">SEND MESSAGE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('front.partials.testimonial-pills')

{{-- JavaScript Filtering --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.premium-filter-btn');
        const cards = document.querySelectorAll('.premium-project-card');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const selectedCategory = this.getAttribute('data-category').toLowerCase().trim();

                cards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category').toLowerCase().trim();
                    if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                        card.style.display = 'block';
                        setTimeout(() => { card.style.opacity = '1'; card.style.transform = 'translateY(0)'; }, 50);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => { card.style.display = 'none'; }, 300);
                    }
                });
            });
        });
    });
</script>
@endsection