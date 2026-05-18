@extends('front/layout-2')

@section('content')
<style>
    html {
        scroll-behavior: smooth;
    }

    /* Modern UI Variables */
    :root {
        --premium-black: #0a0a0a;
        --premium-dark: #121212;
        --accent-color: #ffffff;
        --glass-white: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.1);
        --transition-smooth: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    /* Hero Banner - Full Height & Parallax */
    .service-banner {
        background-image: linear-gradient(rgba(0, 0, 0, 0.3), var(--premium-black)), 
                          url('{{ asset('public/front-new/assets/images/group-concentrated-surgical-doctor-team-260nw-2573615859.webp') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 85vh;
        display: flex;
        align-items: center;
        margin-top: -120px; /* Offset for transparent headers */
        padding-top: 150px;
        color: white;
    }

    .hero-title {
        font-size: clamp(2.8rem, 6vw, 4.8rem);
        font-weight: 800;
        letter-spacing: -2px;
        line-height: 1.1;
        margin-bottom: 20px;
    }

    .hero-description {
        max-width: 650px;
        font-size: 1.15rem;
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 35px;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 18px 45px;
        background: #fff;
        color: #000;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-decoration: none;
        border-radius: 100px;
        font-size: 0.9rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
    }

    .hero-btn i {
        font-size: 1.1rem;
        transition: transform 0.4s ease;
    }

    .hero-btn:hover {
        background: #000;
        color: #fff;
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        border-color: #000;
    }

    .hero-btn:hover i {
        transform: translateX(5px);
    }

    /* Project Summary Section */
    /* .project-solutions-section {
        background: var(--premium-black);
        padding: 100px 0;
    }

    .solutions-banner {
        display: flex;
        align-items: center;
        gap: 60px;
        background: var(--glass-white);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 80px;
    } */

    /* Featured Section - Asymmetric Layout */
    .featured-project-section {
        padding: 120px 0;
        background: #fdfdfd;
    }

    .featured-box {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 50px;
        align-items: center;
    }

    .featured-image-side img {
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    }

    /* Premium Project Cards */
    .premium-project-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 40px;
        padding: 60px 0;
    }

    .premium-project-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        transition: var(--transition-smooth);
    }

    .premium-project-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 40px 80px rgba(0,0,0,0.08);
    }

    .project-img-container {
        position: relative;
        height: 280px;
        overflow: hidden;
    }

    .project-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }

    .premium-project-card:hover .project-img-container img {
        transform: scale(1.1);
    }

    .project-cat-badge {
        position: absolute;
        top: 25px;
        left: 25px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 6px 18px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Modern Filter Tabs */
    .premium-filter-nav {
        display: flex;
        justify-content: center;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin-bottom: 50px;
    }

    .premium-filter-btn {
        border: 1px solid #eee;
        background: #fff;
        padding: 12px 28px;
        border-radius: 100px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .premium-filter-btn.active, .premium-filter-btn:hover {
        background: var(--premium-black);
        color: #fff;
        border-color: var(--premium-black);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    /* Inquiry Modal Styling */
    .inquiry-modal .modal-content {
        border-radius: 24px;
        overflow: hidden;
    }

    /* Responsive Fixes */
    @media (max-width: 992px) {
        .featured-box { grid-template-columns: 1fr; }
        .solutions-banner { flex-direction: column; padding: 40px; text-align: center; }
        .service-banner { height: auto; padding: 180px 0 100px 0; }
    }
</style>

<!-- Hero Section -->
<section class="service-banner">
    <div class="container">
        <div class="banner-text">
            <div class="service-nav mb-4">
                <a class="text-white-50 text-decoration-none small" href="#">HOME</a>
                <span class="mx-2 text-white-50">/</span>
                <a class="text-white-50 text-decoration-none small" href="#">PROJECTS</a>
            </div>
            <h1 class="hero-title">Architecting Digital <br>Success Stories</h1>
            <p class="hero-description">
                Explore a gallery of our most impactful success stories. From complex IT staffing solutions to custom high-performance software, we deliver excellence across various industries worldwide.
            </p>
            <a href="#build-extraordinary" class="hero-btn">
                Start Your Project <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
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

<style>
    /* Glass Showcase Section */

:root {
    --brand-blue: #2563eb;
    --deep-slate: #0f172a;
    --soft-gray: #64748b;
    --glass-white: rgba(255, 255, 255, 0.7);
}

.premium-solutions-section {
    padding: 100px 0;
    background-color: #ffffff;
    position: relative;
    overflow: hidden;
}

/* Background element for depth */
.bg-blur-circle {
    position: absolute;
    top: -10%;
    right: -5%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, transparent 70%);
    filter: blur(60px);
}

.solutions-card-wrapper {
    background: var(--glass-white);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 32px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}

.solutions-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    min-height: 400px;
}

/* Brand Panel (Left) */
.brand-panel {
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 60px;
    border-right: 1px solid #e2e8f0;
}

.brand-logo-modern {
    width: 140px;
    /* Remove invert(1) if your logo is already dark/colored for white theme */
    filter: grayscale(1) opacity(0.8); 
    margin-bottom: 40px;
}

.experience-tag {
    display: flex;
    align-items: center;
    gap: 15px;
}

.tag-number {
    font-size: 3rem;
    font-weight: 800;
    color: #066e78;
    line-height: 1;
}

.tag-text {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--deep-slate);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Content Panel (Right) */
.content-panel {
    padding: 60px 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.display-title {
    font-size: 2.8rem;
    font-weight: 800;
    color: var(--deep-slate);
    line-height: 1.1;
    margin-bottom: 25px;
    letter-spacing: -0.02em;
}

.text-gradient {
    background: linear-gradient(90deg, #066e78, #09c3d4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.text-primary {
    color: #066e78 !important;
}

.description-text {
    font-size: 1.15rem;
    line-height: 1.8;
    color: var(--soft-gray);
    max-width: 600px;
}

.btn-minimal {
    display: inline-flex;
    align-items: center;
    margin-top: 30px;
    color: var(--deep-slate);
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.btn-minimal:hover {
    color: var(--brand-blue);
    transform: translateX(5px);
}

/* Responsive Logic */
@media (max-width: 991px) {
    .solutions-grid {
        grid-template-columns: 1fr;
    }
    .brand-panel {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 40px;
    }
    .content-panel {
        padding: 40px;
        text-align: center;
    }
    .description-text {
        margin: 0 auto;
    }
}
    </style>


<section class="glass-showcase-section">
    <!-- These colorful shapes sit behind the glass card to create the effect -->
    <div class="glass-blob blob-1"></div>
    <div class="glass-blob blob-2"></div>

    <div class="container">
                <div class="featured-box">
                    <div class="featured-image-side">
                        <img src="{{ asset('public/front-new/assets/images/section-3-1st-image.jpg') }}"
                            alt="Featured Project">
                    </div>
                    <div class="featured-content-side">
                        <span class="featured-badge">Featured Case Study</span>
                        <h2 class="featured-title">Next-Gen Healthcare Management System</h2>
                        <p class="featured-desc">
                            We developed a highly specialized, enterprise-level digital ecosystem designed to streamline
                            complex clinical workflows and patient data management for multi-regional hospital networks.
                        </p>

                        <div class="featured-meta-grid">
                            <div class="meta-item">
                                <h4>Key Features</h4>
                                <ul class="meta-list">
                                    <li><i class="fas fa-check-circle"></i> AI-Driven Diagnostics</li>
                                    <li><i class="fas fa-check-circle"></i> Real-time EPR Sync</li>
                                    <li><i class="fas fa-check-circle"></i> Predictive Analytics</li>
                                </ul>
                            </div>
                            <div class="meta-item">
                                <h4>Tech Stack</h4>
                                <div class="tech-tags">
                                    <span class="tech-tag">Laravel</span>
                                    <span class="tech-tag">Vue.js</span>
                                    <span class="tech-tag">AWS Cloud</span>
                                    <span class="tech-tag">PostgreSQL</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ isset($projects[0]) ? route('front.project_details', $projects[0]->id) : route('front.project') }}"
                            class="btn-featured-cta">
                            View Full Case Study <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
</section>

<style>
:root {
    --glass-bg: rgba(255, 255, 255, 0.45);
    --glass-border: rgba(255, 255, 255, 0.7);
    --text-dark: #1e293b;
    --accent-blue: #3b82f6;
}

.glass-showcase-section {
    padding: 120px 0;
    background: #f0f4f8; /* Soft light background */
    position: relative;
    overflow: hidden;
    z-index: 1;
}

/* Background Blobs for the Glass to Blur */
.glass-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    z-index: -1;
    opacity: 0.4;
}
.blob-1 { width: 400px; height: 400px; background: #bfdbfe; top: -100px; right: 10%; }
.blob-2 { width: 300px; height: 300px; background: #ddd6fe; bottom: -50px; left: 15%; }

/* The Main Glass Card */
.glass-main-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid var(--glass-border);
    border-radius: 40px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.glass-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    align-items: stretch;
}

.featured-project-section {
            padding: 40px 0 100px;
            background: transparent;
        }

        .featured-box {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: stretch;
            min-height: 650px;
            margin: 0 15px;
        }

        .featured-image-side {
            flex: 0 0 55%;
            position: relative;
            overflow: hidden;
            background: #f8fafc;
        }

        .featured-image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .featured-box:hover .featured-image-side img {
            transform: scale(1.05);
        }

        .featured-content-side {
            flex: 1;
            padding: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .featured-badge {
            background: #ff8d1b;
            color: #fff;
            padding: 6px 18px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 25px;
            width: fit-content;
        }

        .featured-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.2rem;
            color: #0f172a;
            font-weight: 800;
            margin-bottom: 25px;
            line-height: 1.1;
        }

        .featured-desc {
            font-size: 1.15rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .featured-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 45px;
            padding-top: 35px;
            border-top: 1px solid #f1f5f9;
        }

        .meta-item h4 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .meta-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .meta-list li {
            font-size: 1.05rem;
            color: #1e293b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .meta-list li i {
            color: #ff8d1b;
            margin-right: 12px;
            font-size: 0.9rem;
        }

        .tech-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .tech-tag {
            background: #f1f5f9;
            color: #334155;
            padding: 6px 15px;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .tech-tag:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-featured-cta {
            display: inline-flex;
            align-items: center;
            gap: 15px;
            padding: 18px 40px;
            background: #0f172a;
            color: #ffffff;
            text-decoration: none !important;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: fit-content;
        }

        .btn-featured-cta:hover {
            background: #1e293b;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
            color: #fff;
        }

        @media (max-width: 1200px) {
            .featured-box {
                flex-direction: column;
                min-height: auto;
            }

            .featured-image-side {
                height: 400px;
            }

            .featured-title {
                font-size: 2.5rem;
            }
        }


/* Image Side */
/* .glass-image-side {
    padding: 40px;
}

.glass-image-inner {
    position: relative;
    height: 100%;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.glass-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 1s ease;
}

.glass-main-card:hover .glass-img {
    transform: scale(1.08);
}

.glass-tag-float {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    padding: 10px 20px;
    border-radius: 100px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-dark);
} */

/* Content Side */
/* .glass-content-side {
    padding: 60px 80px 60px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.glass-badge {
    text-transform: uppercase;
    letter-spacing: 3px;
    font-size: 0.75rem;
    font-weight: 800;
    color: #cf2732;
}

.glass-title {
    font-size: 3.5rem;
    color: var(--text-dark);
    line-height: 1.1;
    margin: 20px 0;
}

.glass-title .thin { font-weight: 300; }

.glass-text {
    font-size: 1.1rem;
    color: #475569;
    line-height: 1.7;
    margin-bottom: 40px;
} */

/* Stats Row */
/* .glass-stats-row {
    display: flex;
    gap: 40px;
    margin-bottom: 40px;
}

.glass-stat-item {
    display: flex;
    flex-direction: column;
}

.stat-val {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-dark);
}

.stat-lab {
    font-size: 0.85rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1px;
} */

/* Footer Section */
/* .glass-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid rgba(0,0,0,0.05);
    padding-top: 30px;
}

.tech-pill-stack span {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    margin-right: 15px;
}

.glass-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--text-dark);
    color: #fff;
    padding: 15px 35px;
    border-radius: 100px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-cta:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    background: var(--accent-blue);
} */

/* Responsive */
@media (max-width: 1024px) {
    .glass-grid { grid-template-columns: 1fr; }
    .glass-content-side { padding: 40px; }
}
    </style>


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
    <div class="glass-bg-blobs"></div> {{-- Decorative Background Elements --}}
    <div class="container">
        <div class="glass-main-card">
            <div class="blog-split-wrapper">
                {{-- Left Info Panel --}}
                <div class="blog-info-panel">
                    <span class="sub-badge">Stay Updated</span>
                    <h2>Our Blog</h2>
                    <p>
                        Stay in the loop with the latest company updates, industry insights, and insider tips. 
                        Stay ahead with valuable knowledge curated by our experts.
                    </p>
                    <a href="{{ route('front.new_blog') }}" class="glass-btn">
                        <span>View All Posts</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Right Blog List --}}
                <div class="blog-list-panel">
                    @php
                        $display_blogs = collect();
                        if (isset($latest_blogs) && $latest_blogs->count() > 0) $display_blogs = $latest_blogs->take(3);
                        elseif (isset($recent_blogs) && $recent_blogs->count() > 0) $display_blogs = $recent_blogs->take(3);
                        elseif (isset($blogs) && $blogs->count() > 0) $display_blogs = $blogs->take(3);
                    @endphp

                    @if ($display_blogs->count() > 0)
                        @foreach ($display_blogs as $blog)
                            <a href="{{ route('front.singleBlog', $blog->slug) }}" class="blog-list-item">
                                <div class="blog-item-content">
                                    <h3>{{ $blog->title }}</h3>
                                    <div class="blog-item-meta">
                                        <span class="meta-dot"></span>
                                        <span>Alpha Health</span>
                                        <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                        <span>• 3 min read</span>
                                    </div>
                                </div>
                                <div class="blog-item-thumb">
                                    <img src="{{ asset('public/uploads/blog_images/' . $blog->image) }}"
                                        alt="{{ $blog->title }}">
                                </div>
                            </a>
                        @endforeach
                    @else
                        {{-- Static placeholders matching screenshot design if no dynamic blogs --}}
                        <a href="#" class="blog-list-item">
                            <div class="blog-item-content">
                                <h3>Consultant Spotlight: Wes Williamson</h3>
                                <div class="blog-item-meta">
                                    <span class="meta-dot"></span>
                                    <span style="color: #066e78;">Helix Softworks</span>
                                    <span style="color: #066e78;">Feb 16, 2026</span>
                                    <span style="color: #066e78;">• 3 min read</span>
                                </div>
                            </div>
                            <div class="blog-item-thumb">
                                <img src="{{ asset('public/front-new/assets/images/section-3-1st-image.jpg') }}"
                                    alt="Blog Thumb">
                            </div>
                        </a>
                        <a href="#" class="blog-list-item">
                            <div class="blog-item-content">
                                <h3>How to Prepare for a Virtual Interview</h3>
                                <div class="blog-item-meta">
                                    <span class="meta-dot"></span>
                                    <span style="color: #066e78;">Helix Softworks</span>
                                    <span style="color: #066e78;">Jan 20, 2026</span>
                                    <span style="color: #066e78;">• 3 min read</span>
                                </div>
                            </div>
                            <div class="blog-item-thumb">
                                <img src="{{ asset('public/front-new/assets/images/section-3-2nd-image.jpg') }}"
                                    alt="Blog Thumb">
                            </div>
                        </a>
                        <a href="#" class="blog-list-item">
                            <div class="blog-item-content">
                                <h3>Client Success with Continuous Compliance</h3>
                                <div class="blog-item-meta">
                                    <span class="meta-dot"></span>
                                    <span style="color: #066e78;">Helix Softworks</span>
                                    <span style="color: #066e78;">Nov 12, 2025</span>
                                    <span style="color: #066e78;">• 2 min read</span>
                                </div>
                            </div>
                            <div class="blog-item-thumb">
                                <img src="{{ asset('public/front-new/assets/images/section-3-3rd-image.jpg') }}"
                                    alt="Blog Thumb" style="color: #066e78;">
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    <style>
:root {
    --glass-bg: rgba(16, 191, 235, 0.05);
    --glass-border: rgba(4, 95, 95, 0.856);
    --accent-color: #64e1f1;
    --text-main: #ffffff;
    --text-muted: rgba(255, 255, 255, 0.7);
}

.premium-blog-section {
    padding: 100px 0;
    /* background: radial-gradient(circle at top left, #1e1b4b, #0f172a); */
    position: relative;
    overflow: hidden;
}

/* Decorative Blobs for depth */
.glass-bg-blobs {
    position: absolute;
    width: 400px;
    height: 400px;
    background: var(--accent-color);
    filter: blur(120px);
    opacity: 0.15;
    z-index: 0;
    top: -100px;
    right: -100px;
}

.glass-main-card {
    position: relative;
    z-index: 1;
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--glass-border);
    border-radius: 32px;
    padding: 60px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.blog-split-wrapper {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 80px;
    align-items: center;
}

/* Left Panel */
.blog-info-panel h2 {
    font-size: 3rem;
    font-weight: 800;
    color: var(--text-main);
    margin: 20px 0;
    letter-spacing: -1px;
}

.sub-badge {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 0.8rem;
    letter-spacing: 2px;
    /* color: var(--accent-color); */
    background: rgba(99, 102, 241, 0.1);
    padding: 6px 16px;
    border-radius: 100px;
    border: 1px solid rgba(99, 102, 241, 0.2);
}

/* Premium Button */
.glass-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin-top: 30px;
    padding: 14px 32px;
    background: var(--text-main);
    color: #000;
    border-radius: 100px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.glass-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    background: var(--accent-color);
    color: #000000;
}

/* Blog List Panel */
.blog-list-panel {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.blog-list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid transparent;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
}

.blog-list-item:hover {
    background: rgba(255, 255, 255, 0.07);
    border-color: var(--glass-border);
    transform: translateX(10px);
}

.blog-item-content h3 {
    /* color: var(--text-main); */
    color: gray;
    font-size: 1.25rem;
    margin-bottom: 8px;
    transition: color 0.3s ease;
}

.blog-list-item:hover h3 {
    /* color: var(--accent-color); */
    color: black;
}

.blog-item-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--text-muted);
    font-size: 0.85rem;
}

.meta-dot {
    width: 6px;
    height: 6px;
    background: var(--accent-color);
    border-radius: 50%;
}

.blog-item-thumb {
    width: 100px;
    height: 100px;
    border-radius: 18px;
    overflow: hidden;
    flex-shrink: 0;
}

.blog-item-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.blog-list-item:hover img {
    transform: scale(1.1);
}

@media (max-width: 992px) {
    .blog-split-wrapper { grid-template-columns: 1fr; gap: 40px; }
    .glass-main-card { padding: 30px; }
}
</style>


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