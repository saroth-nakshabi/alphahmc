@extends('front/layout-2')

@section('meta_title', 'Blog Page')
@section('meta_description', 'About Page Description')
@section('meta_keywords', 'About,Alpha,Education')

@section('meta_tags')
    <!-- Additional meta tags (if necessary) -->
    <meta property="og:title" content="About Page - My Website">
    <meta property="og:description" content="This is the about page of My Website.">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('custom_css')
    <style>
        :root {
            --primary-navy: #003358;
            --accent-red: #e50303;
            --text-gray: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        body {
            background-color: #f8fafc;
        }

        /* HERO SECTION */
        .service-banner {
            background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), 
                              url('{{ asset('public/front-new/assets/images/bg6.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 160px 0 100px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }

        .floating-nav-pill {
            background: white;
            padding: 10px 30px;
            border-radius: 100px;
            display: inline-flex;
            align-items: center;
            gap: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            margin-bottom: 50px;
            border: 1px solid #f1f5f9;
        }

        .pill-btn {
            background: #0f172a;
            color: white;
            padding: 8px 18px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .service-banner .title {
            font-family: 'Outfit', sans-serif;
            font-size: 5rem;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 25px;
            letter-spacing: -2px;
            line-height: 1.1;
        }

        .service-banner .hero-desc {
            font-family: 'Inter', sans-serif;
            font-size: 1.25rem;
            line-height: 1.8;
            color: #1e3a8a;
            max-width: 820px;
            margin: 0 auto;
            font-weight: 500;
            opacity: 0.85;
        }

        /* CARD STYLING */
        .blog-listing-wrapper {
            padding: 100px 0;
            background: #f8fafc;
        }

        .premium-blog-card {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            margin-bottom: 50px;
            transition: var(--transition);
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
        }

        .premium-blog-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 51, 88, 0.08);
            border-color: #e2e8f0;
        }

        .card-img-area {
            height: 480px;
            position: relative;
            overflow: hidden;
        }

        .card-img-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.2s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .premium-blog-card:hover .card-img-area img {
            transform: scale(1.08);
        }

        .card-content-area {
            padding: 60px;
        }

        .card-meta-top {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            color: var(--text-gray);
            font-weight: 600;
        }

        .meta-tag-premium {
            background: #f1f5f9;
            color: var(--primary-navy);
            padding: 5px 15px;
            border-radius: 6px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
        }

        .card-title-premium {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 25px;
            line-height: 1.2;
            transition: var(--transition);
        }

        .premium-blog-card:hover .card-title-premium {
            color: var(--accent-red);
        }

        .card-excerpt-premium {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--text-gray);
            margin-bottom: 40px;
        }

        .btn-premium-read {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            color: var(--primary-navy);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .btn-premium-read i {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .premium-blog-card:hover .btn-premium-read i {
            background: var(--accent-red);
            color: white;
            transform: translateX(5px);
        }

        /* SIDEBAR STYLING */
        .premium-sidebar {
            position: sticky;
            top: 120px;
        }

        .sidebar-section-premium {
            background: white;
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 40px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .sidebar-title-premium {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-box-pill {
            position: relative;
        }

        .search-box-pill input {
            width: 100%;
            padding: 18px 25px;
            border-radius: 100px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-weight: 500;
            transition: var(--transition);
        }

        .search-box-pill input:focus {
            outline: none;
            border-color: var(--accent-red);
            background: white;
            box-shadow: 0 10px 25px rgba(229, 3, 3, 0.1);
        }

        .search-box-pill button {
            position: absolute;
            right: 8px;
            top: 7px;
            width: 45px;
            height: 45px;
            background: var(--primary-navy);
            color: white;
            border: none;
            border-radius: 50%;
            transition: var(--transition);
        }

        .search-box-pill button:hover {
            background: var(--accent-red);
            transform: scale(1.1);
        }

        .tag-list-premium {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tag-item-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0;
            border-bottom: 2px solid #f8fafc;
            color: var(--primary-navy);
            font-weight: 700;
            font-size: 1.05rem;
            transition: var(--transition);
        }

        .tag-item-link:hover {
            color: var(--accent-red);
            border-bottom-color: var(--accent-red);
            padding-left: 5px;
        }

        .tag-item-link i {
            font-size: 0.9rem;
            color: var(--accent-red);
            opacity: 0.3;
            transition: var(--transition);
        }

        .tag-item-link:hover i {
            opacity: 1;
            transform: translateX(3px);
        }

        @media (max-width: 991px) {
            .service-banner .title { font-size: 3.5rem; }
            .service-banner { padding: 100px 20px; }
            .card-img-area { height: 350px; }
            .card-content-area { padding: 40px; }
            .card-title-premium { font-size: 2rem; }
        }

        @media (max-width: 767px) {
            .service-banner .title { font-size: 2.8rem; }
            .service-banner .hero-desc { font-size: 1.1rem; }
            .card-img-area { height: 260px; }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endSection

@section('content')
    {{-- HERO SECTION --}}
    <section class="container-fluid service-banner">
        <div class="container">
            <div class="floating-nav-pill">
                <span class="pill-btn">Discover</span>
                <span style="font-weight: 700; color: var(--primary-navy); font-size: 0.85rem;">Alpha Health Group Insights</span>
            </div>
            <div class="breadcum-content text-center">
                <h2 class="title" style="color: #003358;">Alpha Blog</h2>
                <p class="hero-desc">
                    Explore our blog for inspiring client success stories, insightful employee spotlights, 
                    valuable tips for candidates, and highlights from the events Alpha has attended and 
                    more.
                </p>
            </div>
        </div>
    </section>

    {{-- MAIN LISTING AREA --}}
    <section class="blog-listing-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @if ($blogs->count() > 0)
                        <div class="blog-grid-premium">
                            @foreach ($blogs as $blog)
                                <article class="premium-blog-card">
                                    <div class="card-img-area">
                                        @if($blog->tags->first())
                                            <span style="position: absolute; top: 30px; left: 30px; z-index: 10;" class="meta-tag-premium">
                                                {{ $blog->tags->first()->name }}
                                            </span>
                                        @endif
                                        <img src="{{ asset('public/uploads/blog_images/' . $blog->image) }}" alt="{{ $blog->title }}" />
                                    </div>
                                    <div class="card-content-area">
                                        <div class="card-meta-top">
                                            <div class="card-user">
                                                <i class="far fa-user" style="color: var(--accent-red); margin-right: 8px;"></i>
                                                By {{ $blog->tags->first()->name ?? 'Alpha Team' }}
                                            </div>
                                            <div style="width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%;"></div>
                                            <div class="card-time" title="Published Date">
                                                <i class="far fa-calendar-alt" style="color: var(--accent-red); margin-right: 8px;"></i>
                                                Published: {{ $blog->created_at->format('M d, Y') }}
                                            </div>
                                            <div style="width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%;"></div>
                                            <div class="card-time" title="Last Updated">
                                                <i class="fas fa-history" style="color: var(--accent-red); margin-right: 8px;"></i>
                                                Updated: {{ $blog->updated_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                        <h2 class="card-title-premium">
                                            <a href="{{ route('view_blog', $blog->slug) }}" style="color: inherit; text-decoration: none;">
                                                {{ $blog->title }}
                                            </a>
                                        </h2>
                                        <div class="card-excerpt-premium">
                                            {!! Str::limit(strip_tags($blog->content), 240) !!}
                                        </div>
                                        <a href="{{ route('view_blog', $blog->slug) }}" class="btn-premium-read" style="text-decoration: none;">
                                            <span>Read Insight</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-5">
                            {{ $blogs->links('vendor.pagination.custom') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-4x mb-4" style="color: #e2e8f0;"></i>
                            <h3 class="fw-bold">No Insights Found</h3>
                            <p class="text-muted">Stay tuned while we prepare fresh content for you.</p>
                        </div>
                    @endif
                </div>

                {{-- SIDEBAR --}}
                <div class="col-lg-4">
                    <aside class="premium-sidebar">
                        {{-- Search --}}
                        <div class="sidebar-section-premium">
                            <h3 class="sidebar-title-premium">Search Insights</h3>
                            <div class="search-box-pill">
                                <form method="GET" action="">
                                    <input type="search" placeholder="Type keyword..." name="search" value="{{ $search ?? '' }}" />
                                    <button type="submit"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        {{-- Tags / topics --}}
                        <div class="sidebar-section-premium">
                            <h3 class="sidebar-title-premium">Services you may explore</h3>
                            <p class="text-muted small mb-4">From our latest insights, these services might be helpful for your journey.</p>
                            @if ($tags?->count() > 0)
                                <nav class="tag-list-premium">
                                    @foreach ($tags as $t)
                                        <a href="#" class="tag-item-link" style="text-decoration: none;">
                                            {{ $t->name }}
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @endforeach
                                </nav>
                            @else
                                <p class="text-muted small">Topics being curated...</p>
                            @endif
                        </div>

                        {{-- Quick Card --}}
                        <div class="sidebar-section-premium">
                            <h3 class="sidebar-title-premium" style="margin-bottom: 20px;">Article you may interested</h3>
                            <div class="mini-featured-post" style="display: flex; gap: 15px; margin-top: 20px;">
                                <div style="flex: 0 0 80px; height: 80px; border-radius: 12px; overflow: hidden;">
                                    <img src="{{ asset('public/front-new/assets/images/bg6.jpg') }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--primary-navy); line-height: 1.4; margin-bottom: 5px;">Strategic Growth in Healthcare 2026</h4>
                                    <span style="font-size: 0.8rem; color: var(--accent-red); font-weight: 700;">READ INSIGHT</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
@endSection