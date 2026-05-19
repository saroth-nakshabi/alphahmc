@extends('front/layout-2')

@push('page_title', (empty($blog->meta_title) ? $blog->name : $blog->meta_title) . ' | Alpha Health Group')

@section('meta_description', empty($blog->meta_description) ? Str::limit(strip_tags($blog->content ?? ''), 155) : $blog->meta_description)

@push('og_tags')
    <meta property="og:type" content="article" />
    <meta property="og:site_name" content="Alpha Health Group" />
    <meta property="og:title" content="{{ empty($blog->meta_title) ? $blog->name : $blog->meta_title }}" />
    <meta property="og:description" content="{{ empty($blog->meta_description) ? Str::limit(strip_tags($blog->content ?? ''), 155) : $blog->meta_description }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    @if($blog->image)
    <meta property="og:image" content="{{ asset('public/uploads/blog_images/' . $blog->image) }}" />
    @endif
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ empty($blog->meta_title) ? $blog->name : $blog->meta_title }}" />
    <meta name="twitter:description" content="{{ empty($blog->meta_description) ? Str::limit(strip_tags($blog->content ?? ''), 155) : $blog->meta_description }}" />
    <script type="application/ld+json">
    {!!
        json_encode([
            '@context'        => 'https://schema.org',
            '@type'           => 'Article',
            'headline'        => empty($blog->meta_title) ? $blog->name : $blog->meta_title,
            'description'     => empty($blog->meta_description) ? Str::limit(strip_tags($blog->content ?? ''), 155) : $blog->meta_description,
            'url'             => url()->current(),
            'datePublished'   => $blog->created_at?->toIso8601String(),
            'dateModified'    => $blog->updated_at?->toIso8601String(),
            'publisher'       => [
                '@type' => 'Organization',
                'name'  => 'Alpha Health Group',
                'logo'  => ['@type' => 'ImageObject', 'url' => asset('public/front-new/assets/images/alpha-logo.svg')],
            ],
            'image' => $blog->image ? asset('public/uploads/blog_images/' . $blog->image) : null,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    !!}
    </script>
@endpush

@section('custom_css')
<style>
    :root {
        --primary-navy: #003358;
        --accent-teal: #1ea7a1;
        --accent-red: #e50303;
        --font-serif: 'Libre Baskerville', serif;
        --font-sans: 'Inter', sans-serif;
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    /* SEARCH & FILTER BAR */
    .blog-filter-bar {
        background: #fff;
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .filter-pill {
        background: var(--primary-navy);
        color: #fff;
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 800;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .blog-search-pill {
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        padding: 8px 15px;
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 300px;
    }

    .blog-search-pill input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* ARTICLE CONTENT */
    .article-main {
        padding: 80px 0;
        background: #fff;
    }

    .article-header {
        max-width: 800px;
        margin: 0 auto 50px;
        text-align: center;
    }

    .article-badge {
        color: var(--accent-red);
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 20px;
    }

    .article-title-centered {
        font-family: var(--font-serif);
        font-size: 3.5rem;
        color: #212427;
        line-height: 1.2;
        margin-bottom: 30px;
    }

    .article-desc-centered {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #4b5563;
        max-width: 700px;
        margin: 0 auto;
    }

    .article-meta-modern {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--primary-navy);
        flex-wrap: wrap;
    }

    .meta-part {
        display: flex;
        align-items: center;
    }

    .article-main-image {
        width: 100%;
        max-height: 600px;
        object-fit: cover;
        border-radius: 0;
        margin-bottom: 60px;
    }

    .article-body-text {
        max-width: 800px;
        margin: 0 auto;
        font-size: 1.25rem;
        line-height: 2;
        color: #1f2937;
    }

    /* GRID SECTION - OUR LATEST THINKING */
    .thinking-grid-section {
        padding: 100px 0;
        background: #fff;
    }

    .thinking-title {
        font-family: var(--font-serif);
        font-size: 2.5rem;
        color: #212427;
        margin-bottom: 50px;
    }

    .thinking-title span {
        color: var(--accent-teal);
    }

    .thinking-card {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
        border-radius: 8px;
        display: flex;
        align-items: flex-end;
        padding: 30px;
        text-decoration: none !important;
        transition: var(--transition);
    }

    .thinking-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 60%);
        transition: var(--transition);
    }

    .thinking-card img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
        transition: transform 1s ease;
    }

    .thinking-card:hover img {
        transform: scale(1.1);
    }

    .thinking-card:hover::after {
        background: linear-gradient(to top, rgba(30, 167, 161, 0.8) 0%, rgba(0, 0, 0, 0.2) 100%);
    }

    .thinking-card-content {
        position: relative;
        z-index: 10;
    }

    .thinking-card-title {
        color: #fff;
        font-weight: 800;
        font-size: 1.2rem;
        line-height: 1.4;
        margin: 0;
    }

    /* CLIENTS SECTION */
    .clients-section {
        padding: 80px 0;
        background: #fff;
        text-align: center;
    }

    .clients-title {
        font-family: var(--font-serif);
        font-size: 2rem;
        color: #212427;
        margin-bottom: 10px;
    }

    .logo-row {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 60px;
        flex-wrap: wrap;
        margin-top: 50px;
        opacity: 0.6;
    }

    .logo-row img {
        max-height: 40px;
        filter: grayscale(1);
        transition: var(--transition);
    }

    .logo-row img:hover {
        filter: grayscale(0);
        opacity: 1;
    }

    @media (max-width: 991px) {
        .article-title-centered {
            font-size: 2.5rem;
        }

        .thinking-title {
            font-size: 2rem;
        }
    }
</style>

@endSection

@section('content')
    {{-- Sticky Filter & Search Bar --}}
    <!-- <div class="blog-filter-bar">
            <div class="container d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('front.new_blog') }}" class="filter-pill">INSIGHTS</a>
                </div>
                <div class="blog-search-pill">
                    <input type="text" placeholder="Search insights...">
                    <i class="fas fa-search opacity-30"></i>
                </div>
            </div>
        </div> -->

    {{-- Main Article Section --}}
    <article class="article-main">
        <div class="container">
            <header class="article-header">
                @if($blog->tags->first())
                    <span class="article-badge">{{ $blog->tags->first()->name }}</span>
                @endif
                <h1 class="article-title-centered">{{ $blog->title }}</h1>
                <p class="article-desc-centered">{{ $blog->description }}</p>

                <div class="article-meta-modern mt-5">
                    <div class="meta-part">
                        <i class="far fa-user me-2" style="color: var(--accent-red);"></i>
                        <span>{{ $blog->tags->first()->name ?? 'Alpha Team' }}</span>
                    </div>
                    <div class="meta-part">
                        <i class="far fa-calendar-alt me-2" style="color: var(--accent-red);"></i>
                        <span>Published: {{ $blog->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="meta-part">
                        <i class="fas fa-history me-2" style="color: var(--accent-red);"></i>
                        <span>Updated: {{ $blog->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    <button class="btn btn-outline-dark rounded-pill px-4" id="read-trigger">READ MORE</button>
                </div>
            </header>

            <img src="{{ asset('public/uploads/blog_images/' . $blog->image) }}" class="article-main-image"
                alt="{{ $blog->title }}">

            <div class="article-body-text">
                {!! $blog->content !!}
            </div>
        </div>
    </article>

    {{-- Our Latest Thinking Grid --}}
    <section class="thinking-grid-section">
        <div class="container text-center">
            <h6 class="article-badge mb-3">RECOMMENDED READING</h6>
            <h2 class="thinking-title">Our Latest Thinking <span>Based on Your Interests</span></h2>

            <div class="row g-4 text-start">
                @foreach ($recent_blogs as $rb)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('view_blog', $rb->slug) }}" class="thinking-card">
                            <img src="{{ asset('public/uploads/blog_images/' . $rb->image) }}" alt="{{ $rb->title }}">
                            <div class="thinking-card-content">
                                <span class="text-white opacity-75 fw-bold mb-2 d-block"
                                    style="font-size: 0.7rem; letter-spacing: 1px;">
                                    {{ $rb->tags->first()->name ?? 'INSIGHT' }}
                                </span>
                                <h4 class="thinking-card-title">{{ $rb->title }}</h4>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                <a href="{{ route('front.new_blog') }}" class="btn btn-outline-teal rounded-pill px-5 py-3 fw-bold">CONTINUE
                    TO
                    INSIGHTS</a>
            </div>
        </div>
    </section>

    {{-- Clients Slider --}}
    <section class="clients-section border-top">
        <div class="container">
            <h2 class="clients-title">Meet Our Clients</h2>
            <p class="opacity-50">Trusted by leading healthcare facilities across the Middle East</p>

            <div class="logo-row">
                <img src="{{ asset('public/front/assets/img/moh-logo.png') }}" alt="MOH logo">
                <img src="{{ asset('public/front/assets/img/dha-logo.png') }}" alt="DHA logo">
                <img src="{{ asset('public/front/assets/img/doh-logo.png') }}" alt="DOH logo">
                <img src="{{ asset('public/front-new/assets/images/about/alpha-logo1.png') }}" alt="Client logo">
            </div>
        </div>
    </section>

    <script>
        document.getElementById('read-trigger')?.addEventListener('click', function () {
            window.scrollTo({
                top: document.querySelector('.article-main-image').offsetTop - 100,
                behavior: 'smooth'
            });
        });
    </script>
@endsection

@section('custom_js')

@endSection