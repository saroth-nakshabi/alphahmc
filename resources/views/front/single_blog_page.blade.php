@extends('front/layout-2')

@php
    // Dynamic SEO from the blog record: meta_title falls back to the post title;
    // keywords fall back to the blog's comma-joined tags when meta_keywords is empty.
    $blogMetaTitle = empty($blog->meta_title) ? $blog->title : $blog->meta_title;
    $blogKeywords  = $blog->meta_keywords;
    if (empty($blogKeywords)) {
        $blogKeywords = optional($blog->tags)->pluck('name')->filter()->implode(', ');
    }
    $blogMetaDesc = $blog->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($blog->description ?? $blog->content ?? ''), 160);
    // <title>: cut to 60 at a word; the brand suffix only when it still fits.
    $blogSeoTitle = \App\Support\Seo::title($blog->meta_title, $blog->title, 'Alpha Health Group');
    // Dates shown on the page and in the JSON-LD (one source for both).
    $blogPublished = $blog->published_date ?? $blog->created_at;
    $blogModified  = $blog->updated_date ?? $blog->published_date ?? $blog->updated_at;
    // "Reviewed by" only with both a name and a date; the URL is re-checked before it is used.
    $blogReviewed    = trim((string) $blog->reviewer_name) !== '' && $blog->last_reviewed;
    $blogReviewerUrl = \App\Support\BlogPublishGuard::safeUrl($blog->reviewer_url);
    $blogSources     = collect($blog->sources ?? [])->filter(fn ($u) => is_string($u) && \App\Support\BlogPublishGuard::safeUrl($u))->values();
    $preview = $preview ?? false;
@endphp
@if($preview)
    @section('robots', 'noindex, nofollow')
@endif
@push('page_title', e($blogSeoTitle))
@section('og_type', 'article')
@section('meta_description', trim(preg_replace('/\s+/u', ' ', html_entity_decode($blogMetaDesc, ENT_QUOTES | ENT_HTML5, 'UTF-8'))))
@push('meta')
    @if(!empty($blogKeywords))<meta name="keywords" content="{{ $blogKeywords }}">@endif
    @php
        // BlogPosting from the blog row. Author is the named author when filled in,
        // otherwise the organisation that publishes the article.
        $blogPosting = array_filter([
            '@context'         => 'https://schema.org',
            '@type'            => 'BlogPosting',
            'headline'         => \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($blog->title), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 110, ''),
            'description'      => html_entity_decode($blogMetaDesc, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'image'            => $blog->image ? asset('public/uploads/blog_images/' . $blog->image) : null,
            'datePublished'    => optional($blogPublished)->toAtomString(),
            'dateModified'     => optional($blogModified)->toAtomString(),
            'author'           => trim((string) $blog->author_name) !== ''
                ? ['@type' => 'Person', 'name' => trim($blog->author_name)]
                : ['@type' => 'Organization', 'name' => 'Alpha Health Group', 'url' => config('app.url')],
            // reviewedBy is defined on WebPage in schema.org; emitted here as the brief plans.
            'reviewedBy'       => $blogReviewed ? array_filter([
                '@type'    => 'Person',
                'name'     => trim($blog->reviewer_name),
                'jobTitle' => trim((string) $blog->reviewer_credential) ?: null,
                'url'      => $blogReviewerUrl,
            ]) : null,
            'citation'         => $blogSources->isNotEmpty() ? $blogSources->all() : null,
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => 'Alpha Health Group',
                'logo'  => ['@type' => 'ImageObject', 'url' => asset('public/front-new/assets/images/alpha-logo.svg')],
            ],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
        ], fn ($v) => $v !== null && $v !== '');
    @endphp
    <script type="application/ld+json">{!! json_encode($blogPosting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
@push('og_tags')
    {{-- og:title/description/url come from the layout (one of each per page). --}}
    <meta name="twitter:title" content="{{ $blogSeoTitle }}" />
@endpush

@section('custom_css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;800&display=swap');

    :root {
        --primary-navy: #00233d;
        --accent-teal: #1ea7a1;
        --accent-red: #d90429;
        --soft-bg: #f8fafc;
        --text-main: #1a1a1a;
        --text-muted: #64748b;
        --font-serif: 'Playfair Display', serif;
        --font-sans: 'Inter', sans-serif;
        --transition-smooth: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    }

    body {
        background-color: #fff;
        color: var(--text-main);
        font-family: var(--font-sans);
        -webkit-font-smoothing: antialiased;
    }

    /* READING PROGRESS BAR */
    #progress-container {
        position: fixed;
        top: 0;
        width: 100%;
        height: 4px;
        z-index: 2000;
        background: transparent;
    }

    #progress-bar {
        height: 100%;
        background: var(--accent-teal);
        width: 0%;
        transition: width 0.1s ease;
    }

    /* ARTICLE HEADER - PREMIUM EDITORIAL */
    .article-main {
        padding: 135px 0;
    }

    .article-header {
        max-width: 900px;
        margin: 0 auto 60px;
        text-align: center;
    }

    .article-badge {
        display: inline-block;
        color: var(--accent-teal);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 25px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--accent-teal);
    }

    .article-title-centered {
        font-family: var(--font-serif);
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        font-weight: 700;
        color: var(--primary-navy);
        line-height: 1.1;
        margin-bottom: 35px;
        letter-spacing: -1px;
    }

    .article-desc-centered {
        font-size: 1.3rem;
        line-height: 1.6;
        color: var(--text-muted);
        font-weight: 300;
        max-width: 750px;
        margin: 0 auto 40px;
    }

    .article-meta-editorial {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
        padding-top: 30px;
        border-top: 1px solid #eee;
        max-width: 500px;
        margin: 0 auto;
    }

    .meta-divider-vertical {
        width: 1px;
        height: 15px;
        background: #ddd;
    }

    .meta-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 2px;
    }

    /* IMAGE STYLING */
    .image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        margin-bottom: 80px;
    }

    .article-main-image {
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: cover;
        transform: scale(1.02);
        transition: var(--transition-smooth);
    }

    /* BODY TYPOGRAPHY */
    .article-body-text {
        max-width: 780px;
        margin: 0 auto;
        font-size: 1.2rem;
        line-height: 2.1;
        color: #2d3748;
        font-family: var(--font-sans);
    }

    .article-body-text p {
        margin-bottom: 2rem;
    }

    /* Internal/content links blend with body text — no color change, no underline. */
    .article-body-text a,
    .article-body-text a:hover,
    .article-body-text a:focus {
        color: inherit;
        text-decoration: none;
    }

    /* GRID SECTION - OUR LATEST THINKING */
    .thinking-grid-section {
        padding: 120px 0;
        background: var(--soft-bg);
    }

    .thinking-title {
        font-family: var(--font-serif);
        font-size: 3rem;
        color: var(--primary-navy);
        margin-bottom: 60px;
    }

    .thinking-card {
        position: relative;
        display: block;
        height: 450px;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none !important;
        transition: var(--transition-smooth);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .thinking-card-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .thinking-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,35,61,0.95) 100%);
        z-index: 1;
        transition: var(--transition-smooth);
    }

    .thinking-card-content {
        position: absolute;
        bottom: 0;
        left: 0;
        padding: 40px;
        z-index: 2;
        width: 100%;
    }

    .thinking-card-title {
        color: #fff;
        font-family: var(--font-serif);
        font-size: 1.6rem;
        line-height: 1.3;
        margin-top: 10px;
        transform: translateY(10px);
        transition: var(--transition-smooth);
    }

    .thinking-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px -15px rgba(0,0,0,0.3);
    }

    .thinking-card:hover .thinking-card-img {
        transform: scale(1.1);
    }

    .thinking-card:hover .thinking-card-overlay {
        background: linear-gradient(180deg, rgba(30, 167, 161, 0.2) 0%, rgba(0, 35, 61, 0.95) 100%);
    }

    .thinking-card:hover .thinking-card-title {
        transform: translateY(0);
    }

    /* CLIENTS SECTION */
    .clients-section {
        padding: 100px 0;
        background: #fff;
    }

    .blog-client-swiper-outer { margin-top: 40px; overflow: hidden; }
    .blogClientSwiper .swiper-wrapper { align-items: center; transition-timing-function: linear !important; }
    /* Even, evenly-spaced columns so the logo strip reads as an organized row */
    .blog-client-slide { width: 150px; display: flex; align-items: center; justify-content: center; }
    .blog-client-logo { display: flex; align-items: center; justify-content: center; width: 100%; padding: 0 10px; }
    .blog-client-logo img {
        max-height: 46px; max-width: 100%; width: auto; object-fit: contain;
        filter: grayscale(1); opacity: 0.55;
        transition: var(--transition-smooth);
    }
    .blog-client-logo img:hover {
        filter: grayscale(0); opacity: 1; transform: scale(1.05);
    }

    /* BUTTONS */
    .btn-premium {
        background: var(--primary-navy);
        color: #fff;
        padding: 16px 40px;
        border-radius: 100px;
        font-weight: 600;
        letter-spacing: 1px;
        transition: var(--transition-smooth);
        border: 2px solid var(--primary-navy);
    }

    .btn-premium:hover {
        background: transparent;
        color: var(--primary-navy);
    }

    @media (max-width: 768px) {
        .article-title-centered { font-size: 2.2rem; }
        .logo-row { gap: 40px; }
        .thinking-card { height: 350px; }
    }
</style>
@endSection

@section('content')
    {{-- Top Progress Bar --}}
    <div id="progress-container"><div id="progress-bar"></div></div>

    @if($preview ?? false)
        {{-- Signed preview for the reviewer: not public, not indexed --}}
        <div role="status" style="position:fixed;left:0;right:0;bottom:0;z-index:10000;background:#7a4b00;color:#fff;text-align:center;font-size:.85rem;font-weight:600;padding:10px 16px;">
            Preview, not published. Only people with this link can see this page.
        </div>
    @endif

    {{-- Main Article Section --}}
    <article class="article-main">
        <div class="container">
            <header class="article-header">
                @if($blog->tags->first())
                    <span class="article-badge">{{ $blog->tags->first()->name }}</span>
                @endif
                <h1 class="article-title-centered">{{ $blog->title }}</h1>
                <p class="article-desc-centered">{{ $blog->description }}</p>

                @php
                    $focusItems = collect(explode(',', $blog->news_focus ?? ''))
                        ->map(fn ($x) => trim($x))->filter()->take(3);
                @endphp
                @if($focusItems->isNotEmpty())
                    <div class="article-focus-area"
                        style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;align-items:center;margin:18px 0 6px;">
                        <span style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">Focus Area</span>
                        @foreach($focusItems as $focus)
                            <span style="display:inline-flex;align-items:center;gap:6px;font-size:.8rem;font-weight:600;color:#0056a6;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;padding:4px 12px;">
                                <i class="fas fa-check-circle" style="font-size:.75rem;"></i> {{ $focus }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="article-meta-editorial">
                    <div class="meta-item-box">
                        <span class="meta-label">Author</span>
                        <span>{{ $blog->author_name ?? 'Alpha Team' }}</span>
                    </div>
                    <div class="meta-divider-vertical"></div>
                    <div class="meta-item-box">
                        <span class="meta-label">Published</span>
                        <span>{{ optional($blogPublished)->format('M d, Y') }}</span>
                    </div>
                    <div class="meta-divider-vertical"></div>
                    <div class="meta-item-box">
                        <span class="meta-label">Last Updated</span>
                        <span>{{ optional($blogModified)->format('M d, Y') }}</span>
                    </div>
                </div>
                @if($blogReviewed)
                    <p class="article-reviewed-by" style="margin:14px 0 0;font-size:.85rem;color:#64748b;">
                        Reviewed by
                        @if($blogReviewerUrl)<a href="{{ $blogReviewerUrl }}" target="_blank" rel="noopener" style="color:inherit;text-decoration:underline;">{{ $blog->reviewer_name }}</a>@else{{ $blog->reviewer_name }}@endif{{ trim((string) $blog->reviewer_credential) !== '' ? ', ' . $blog->reviewer_credential : '' }}, {{ $blog->last_reviewed->format('j M Y') }}
                    </p>
                @endif

                <div class="mt-5">
                    <a href="#read" class="text-dark fw-bold text-decoration-none" id="scroll-trigger">
                        <small class="d-block mb-2">SCROLL TO READ</small>
                        <i class="fas fa-chevron-down animate-bounce"></i>
                    </a>
                </div>
            </header>

            @if($blog->image)
            <div class="image-wrapper" id="read">
                {{-- LCP image: right-sized files, fetched first, never lazy --}}
                @php
                    $mainImgPath = 'uploads/blog_images/' . $blog->image;
                    $mainImg     = \App\Support\Img::thumb($mainImgPath, 1200);
                    $mainImgSize = \App\Support\Img::size($mainImg);
                @endphp
                <img src="{{ $mainImg }}"
                     srcset="{{ \App\Support\Img::srcset($mainImgPath, [480, 800, 1200]) }}"
                     sizes="(max-width: 1320px) 100vw, 1296px"
                     @if($mainImgSize) width="{{ $mainImgSize[0] }}" height="{{ $mainImgSize[1] }}" @endif
                     fetchpriority="high"
                     class="article-main-image"
                     alt="{{ $blog->title }}">
            </div>
            @else
            <div id="read"></div>
            @endif

            <div class="article-body-text">
                {!! \App\Support\Html::fixLinks($blog->content) !!}
            </div>

            @if($blogSources->isNotEmpty())
                <section class="article-sources" style="max-width:780px;margin:40px auto 0;">
                    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:10px;">Sources</h2>
                    <ul style="padding-left:1.2rem;margin:0;">
                        @foreach($blogSources as $src)
                            <li><a href="{{ $src }}" target="_blank" rel="noopener nofollow">{{ preg_replace('/^www\./', '', parse_url($src, PHP_URL_HOST) ?: $src) }}</a></li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </article>

    {{-- Thinking Grid --}}
    <section class="thinking-grid-section">
        <div class="container">
            <div class="row align-items-end mb-5">
                <div class="col-md-8">
                    <h6 class="article-badge">Our Blogs</h6>
                    <h2 class="thinking-title mb-0">Our Latest Insights</h2>
                </div>
                <div class="col-md-4 text-md-end d-none d-md-block">
                    <a href="{{ route('front.new_blog') }}" class="btn-premium">VIEW ALL INSIGHTS</a>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($blogsByTag as $bt)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('front.singleBlog', $bt->slug) }}" class="thinking-card">
                            <img src="{{ \App\Support\Img::thumb('uploads/blog_images/' . $bt->image, 800) }}" class="thinking-card-img" alt="{{ $bt->title }}" loading="lazy" decoding="async">
                            <div class="thinking-card-overlay"></div>
                            <div class="thinking-card-content">
                                <span class="badge bg-teal" style="background: var(--accent-teal)">{{ $bt->tags->first()->name ?? 'INSIGHT' }}</span>
                                <h4 class="thinking-card-title">{{ $bt->title }}</h4>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services you might be interested in (same split design as the Projects page) --}}
    @if(($featuredServices ?? collect())->count())
    <style>
        .premium-blog-section { padding: 100px 0; background: #ffffff; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; overflow-x: hidden; }
        .kb-main-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 32px; padding: 60px; box-shadow: 0 4px 24px rgba(0,0,0,0.04); }
        .blog-split-wrapper { display: grid; grid-template-columns: 1fr 1.5fr; gap: 80px; align-items: center; }
        .blog-info-panel h2 { font-size: 2.8rem; font-weight: 800; color: #0f172a; margin: 16px 0 18px; letter-spacing: -1px; }
        .blog-info-panel p { color: #64748b; font-size: 1rem; line-height: 1.75; }
        .sub-badge { display: inline-block; text-transform: uppercase; font-weight: 700; font-size: 0.75rem; letter-spacing: 2px; color: #066D77; background: rgba(6,109,119,0.08); padding: 6px 16px; border-radius: 100px; border: 1px solid rgba(6,109,119,0.18); }
        .kb-btn { display: inline-flex; align-items: center; gap: 10px; margin-top: 28px; padding: 13px 30px; background: #0f172a; color: #fff; border-radius: 100px; font-weight: 600; font-size: 0.9rem; transition: all 0.3s ease; text-decoration: none; }
        .kb-btn:hover { background: #066D77; color: #fff; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(6,109,119,0.25); }
        .blog-list-panel { display: flex; flex-direction: column; gap: 16px; }
        .blog-list-item { display: flex; justify-content: space-between; align-items: center; gap: 20px; padding: 20px 24px; border-radius: 16px; background: #fff; border: 1px solid #e2e8f0; transition: all 0.3s ease; text-decoration: none; }
        .blog-list-item:hover { border-color: #066D77; box-shadow: 0 6px 20px rgba(6,109,119,0.08); transform: translateX(6px); }
        .blog-item-content { flex: 1; min-width: 0; }
        .blog-item-content h3 { color: #1e293b; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; line-height: 1.4; transition: color 0.25s ease; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .blog-list-item:hover h3 { color: #066D77; }
        .blog-item-meta { display: flex; align-items: center; gap: 8px; color: #94a3b8; font-size: 0.8rem; }
        .meta-dot { width: 5px; height: 5px; background: #066D77; border-radius: 50%; flex-shrink: 0; }
        .blog-item-thumb { width: 88px; height: 88px; border-radius: 12px; overflow: hidden; flex-shrink: 0; }
        .blog-item-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .blog-list-item:hover .blog-item-thumb img { transform: scale(1.08); }
        @media (max-width: 991px) { .blog-split-wrapper { grid-template-columns: 1fr; gap: 36px; } }
        @media (max-width: 767px) { .premium-blog-section { padding: 60px 0; } .kb-main-card { padding: 28px; border-radius: 22px; } .blog-info-panel h2 { font-size: 2rem; } .blog-list-item { padding: 16px; } .blog-item-thumb { width: 70px; height: 70px; } }
    </style>
    <section class="premium-blog-section">
        <div class="container">
            <div class="kb-main-card">
                <div class="blog-split-wrapper">
                    {{-- Left Info Panel --}}
                    <div class="blog-info-panel">
                        <h2>Services you might be interested in</h2>
                        <p>
                            Explore Alpha Health Group's healthcare consultancy services, from DOH licensing and
                            accreditation to quality, compliance, and operational excellence, tailored to your facility.
                        </p>
                        <a href="{{ route('front.all-services') }}" class="kb-btn">
                            <span>View All Services</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    {{-- Right Service List --}}
                    <div class="blog-list-panel">
                        @foreach ($featuredServices->take(3) as $svc)
                            <a href="{{ route('front.service', $svc->slug) }}" class="blog-list-item">
                                <div class="blog-item-content">
                                    <h3>{{ $svc->name }}</h3>
                                    <div class="blog-item-meta">
                                        <span class="meta-dot"></span>
                                        <span>Alpha Health</span>
                                        <span>{{ optional($svc->categories->first())->name ?? 'Healthcare Consultancy' }}</span>
                                    </div>
                                </div>
                                <div class="blog-item-thumb">
                                    <img src="{{ $svc->hero_image ? \App\Support\Img::thumb('uploads/service_images/' . $svc->hero_image, 200) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}"
                                        alt="{{ $svc->name }}" width="88" height="88" loading="lazy" decoding="async">
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Alpha Blueprint AI launcher --}}
    @include('front.partials.planner-cta', ['variant' => 'light'])

    {{-- Clients (dynamic logo carousel) --}}
    @if(($clients ?? collect())->count())
    <section class="clients-section">
        <div class="container text-center">
            <h2 class="thinking-title" style="font-size: 2rem;">Trusted by Industry Leaders</h2>
        </div>
        <div class="blog-client-swiper-outer">
            <div class="swiper blogClientSwiper">
                <div class="swiper-wrapper">
                    @foreach($clients as $client)
                        <div class="swiper-slide blog-client-slide">
                            <div class="blog-client-logo">
                                @php $logoUrl = \App\Support\Img::thumb('uploads/clients/' . $client->logo, 300); $logoSize = \App\Support\Img::size($logoUrl); @endphp
                                <img src="{{ $logoUrl }}"
                                     @if($logoSize) width="{{ $logoSize[0] }}" height="{{ $logoSize[1] }}" @endif
                                     alt="{{ $client->name }}" loading="lazy" decoding="async">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <script>
        // Smooth Scroll
        document.getElementById('scroll-trigger')?.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({
                top: document.getElementById('read').offsetTop - -550,
                behavior: 'smooth'
            });
        });

        // Reading Progress Logic
        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            document.getElementById("progress-bar").style.width = scrolled + "%";
        };

        // Client logo carousel — continuous marquee-style scroll
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swiper !== 'undefined' && document.querySelector('.blogClientSwiper')) {
                new Swiper('.blogClientSwiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 12,
                    loop: true,
                    speed: 3500,
                    allowTouchMove: true,
                    grabCursor: true,
                    autoplay: { delay: 0, disableOnInteraction: false, pauseOnMouseEnter: true },
                });
            }
        });
    </script>
@endsection