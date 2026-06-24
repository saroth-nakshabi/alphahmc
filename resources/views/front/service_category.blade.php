@extends('front/layout-2')
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/service-pages-shared.min.css') }}?v=3">
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/service-category.min.css') }}?v=6">
@endsection
@push('page_title')
    {!! $service->name !!}
@endpush
@push('meta')
    <meta name="description" content="{{ $service->meta_description }}">
    <meta name="keywords" content="{{ $service->meta_keywords }}">
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
    @php
        $heroPreload = $service->hero_image
            ? asset('public/' . ltrim($service->hero_image, '/'))
            : asset('public/front/assets/img/hero/service-details-bg.jpg');
    @endphp
    <link rel="preload" as="image" href="{{ $heroPreload }}" fetchpriority="high">
@endpush
@push('og_tags')
    {{-- <link rel="canonical" href="{{ url('/' . $service->slug) }}"> --}}
    <meta name="author" content="Alpha Health Management Consultancy">
    <meta property="og:title" content="{{ $service->meta_title }}" />
    <meta property="og:description" content="{{ $service->meta_description }}" />
    <meta property="og:image" content="{{ $service->hero_image ? asset('public/' . ltrim($service->hero_image, '/')) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="{{ strip_tags($service->description) }}">
    {{-- <meta name="twitter:description" content="{{ strip_tags($service->meta_description ?? $service->overview) }}"> --}}
@endpush
@section('content')
    

    <div class="service-page-wrapper">
        <!-- Hero Section -->
        <section class="service-hero">
            <div class="hero-background"
                style="background-image: linear-gradient(to right, rgba(0, 0, 0, 85%), rgba(202, 202, 202, 0.363)),url('{{ $service->hero_image ? \App\Support\Img::thumb(ltrim($service->hero_image, '/'), 1600) : (isset($service->images[0]) ? \App\Support\Img::thumb(ltrim($service->images[0]->image, '/'), 1600) : asset('public/front/assets/img/hero/service-details-bg.jpg')) }}');">
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $service->name }}</h1>
                        <div class="hero-desc-wrapper">
                            {!! $service->description !!}
                        </div>
                        <div class="hero-actions">
                            <button type="button"
    class="glass-btn"
    id="scrollToHelp">
    <span>Contact Us</span>
    <span class="btn-arrow">→</span>
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
       <!-- <section class="quote-section" data-aos="fade-up">
    <div class="container">
        <div class="quote-inner">
            {{-- <span class="quote-eyebrow">Our Approach</span> --}}
            <div class="quote-text">
                {!! $service->overview !!}
            </div>
            <div class="quote-divider"></div>
        </div>
    </div>
</section> -->

<!--About category section-->

<section class="premium-intro light-theme">
  <div class="container">
    <div class="intro-layout">
      
      <!-- Left Content -->
      <div class="intro-content">
        <!-- <span class="eyebrow">About {{ $service->categories->first()->name ?? 'This Category' }}</span> -->
         {{-- <span class="eyebrow">ABOUT THIS CATEGORY</span> --}}
        {{-- <h2 class="display-title">
          UAE regulations are <em>precise</em>. <br>
          <span class="primary-text">Preparation is everything.</span>
        </h2> --}}
        <div class="lead-text">
          {!! $service->overview !!}
        </div>
        
        {{-- <div class="stats-mini">
          <div class="stat-item"><span class="stat-num">400+</span> <small>Approved</small></div>
          <div class="stat-divider"></div>
          <div class="stat-item"><span class="stat-num">100%</span> <small>Compliance</small></div>
        </div> --}}

        <div class="cta-group">
          <a href="#services" class="btn-primary">Explore Services</a>
          {{-- <a href="/about" class="btn-outline">Our Track Record</a> --}}
        </div>
        <p class="content-updated-meta" style="margin-top:18px;font-size:0.85rem;color:#6b7280;">
          Updated: {{ ($service->updated_date ?? $service->updated_at)->format('M d, Y') }}
        </p>
      </div>

      <!-- Right Content: Auto-Scroll -->
    <div class="intro-visual">
        <div class="scroll-wrapper">
            @php
                $tabSlider = $service->ServiceTab->count() > 3;
                $coreHeaders = !empty($service->core_service_header)
                    ? (is_array($service->core_service_header) ? $service->core_service_header : [$service->core_service_header])
                    : [];
                $coreDescriptions = !empty($service->core_service_description)
                    ? (is_array($service->core_service_description) ? $service->core_service_description : [$service->core_service_description])
                    : [];
                $coreSlider = count($coreHeaders) > 3;
                $scrollTrackClass = ($tabSlider || $coreSlider) ? 'scroll-track' : 'scroll-track no-scroll';
            @endphp
          <div class="{{ $scrollTrackClass }}">
            @if($service->ServiceTab->count() > 0)
                @foreach($service->ServiceTab as $index => $tab)
                    <!-- Card {{ $index + 1 }} -->
                    <div class="feature-card">
                      <div class="card-header">
                        <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4>{{ $tab->name }}</h4>
                      </div>
                      <p>{{ $tab->description }}</p>
                    </div>
                @endforeach
                
                @if($tabSlider)
                    @foreach($service->ServiceTab as $index => $tab)
                        <div class="feature-card aria-hidden">
                          <div class="card-header">
                            <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h4>{{ $tab->name }}</h4>
                          </div>
                          <p>{{ $tab->description }}</p>
                        </div>
                    @endforeach
                @endif
            @elseif(!empty($service->core_service_header))
                @foreach($coreHeaders as $index => $header)
                    <div class="feature-card">
                      <div class="card-header">
                        <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4>{{ $header }}</h4>
                      </div>
                      <p>{!! $coreDescriptions[$index] ?? '' !!}</p>
                    </div>
                @endforeach
                @if($coreSlider)
                    @foreach($coreHeaders as $index => $header)
                        <div class="feature-card aria-hidden">
                          <div class="card-header">
                            <span class="card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h4>{{ $header }}</h4>
                          </div>
                          <p>{!! $coreDescriptions[$index] ?? '' !!}</p>
                        </div>
                    @endforeach
                @endif
            @else
                <!-- Fallback if no ServiceTabs -->
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">01</span>
                    <h4>Authority Expertise</h4>
                  </div>
                  <p>DOH, DHA, and MOH specific checklists tailored to your specialty mix.</p>
                </div>
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">02</span>
                    <h4>End-to-End Management</h4>
                  </div>
                  <p>We manage submissions, inspector coordination, and follow-ups directly.</p>
                </div>
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">03</span>
                    <h4>Predictable Timelines</h4>
                  </div>
                  <p>Realistic milestones with resubmissions handled at no extra cost.</p>
                </div>
                <div class="feature-card">
                  <div class="card-header">
                    <span class="card-num">04</span>
                    <h4>Integrated Logistics</h4>
                  </div>
                  <p>Coordination with engineering and accreditation for a turnkey launch.</p>
                </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>
</section>




<!-- ════════════════════════════════════════
  BROWSE ALL CATEGORIES SECTION
════════════════════════════════════════ -->
<section class="browse-section" id="services">
    <div class="container">
  
      @php
        $categoryServices      = $service->services ?? collect();
        $categoryServiceGroups = $service->serviceGroups ?? collect();
        $totalItems            = $categoryServices->count() + $categoryServiceGroups->count();
      @endphp

      <div class="browse-header reveal">
        <div class="browse-header-left">
          <div class="category-info mb-2">
            
          </div>
          <h2>{{ 'Services in ' . $service->name }}</h2>
          <p>Explore all services and service packages available under this category.</p>
        </div>
        <div class="browse-header-right">
          <a href="{{ route('front.all-services') }}">View All Services &nbsp;→</a>
        </div>
      </div>

      {{-- Service pills styled like the home page "Alpha Updates" cards: image left,
           name + 2-line description right, laid out two per row. --}}
      <style>
        /* grid-auto-rows:1fr + height:100% makes every pill the same height as the tallest */
        .svc-pill-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); grid-auto-rows: 1fr; gap: 18px; align-items: stretch; }
        .svc-pill {
          display: flex; gap: 16px; align-items: stretch; text-decoration: none; height: 100%;
          background: #fff; border: 1px solid #eef0f2; border-radius: 14px; overflow: hidden;
          box-shadow: 0 8px 24px rgba(0,0,0,0.04); min-height: 172px;
          transition: transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s cubic-bezier(.22,1,.36,1), border-color .35s ease;
        }
        .svc-pill:hover { transform: translateY(-4px); box-shadow: 0 16px 38px rgba(6,109,119,0.14); border-color: #cfe6e8; }
        .svc-pill-media { flex: 0 0 118px; width: 118px; overflow: hidden; background: #f3f5f6; }
        .svc-pill-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s cubic-bezier(.22,1,.36,1); }
        .svc-pill:hover .svc-pill-media img { transform: scale(1.07); }
        .svc-pill-body { flex: 1; min-width: 0; padding: 14px 16px 14px 0; display: flex; flex-direction: column; }
        .svc-pill-tag {
          text-transform: uppercase; font-size: .64rem; font-weight: 700; letter-spacing: 1px;
          color: #009095; margin-bottom: 6px; display: inline-flex; align-items: center; gap: 5px;
        }
        .svc-pill-title {
          font-size: 1rem; font-weight: 700; color: #16242a; line-height: 1.32; margin: 0 0 8px;
          /* reserve a 2-line slot so 1-line titles keep the same height as 2-line ones */
          min-height: 2.64em;
          display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .svc-pill-desc {
          font-size: .82rem; color: #5b6b72; line-height: 1.5; margin: 0 0 10px;
          display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .svc-pill-more {
          margin-top: auto; align-self: flex-start;
          display: inline-flex; align-items: center; gap: 6px;
          font-size: .74rem; font-weight: 600; letter-spacing: .2px; color: #009095;
          padding: 6px 12px; border: 1px solid #d3e6e7; border-radius: 8px; background: transparent;
          transition: background .25s ease, color .25s ease, border-color .25s ease;
        }
        .svc-pill:hover .svc-pill-more { background: #009095; color: #fff; border-color: #009095; }
        .svc-pill-more i { font-size: .66rem; transition: transform .3s ease; }
        .svc-pill:hover .svc-pill-more i { transform: translateX(3px); }
        .svc-pill--package { border-left: 3px solid #009095; }
        .svc-pill-empty {
          grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: #9aa5ad;
          border: 1px dashed #d7dee2; border-radius: 14px;
        }
        @media (max-width: 768px) { .svc-pill-grid { grid-template-columns: 1fr; } }
      </style>

      <div class="svc-pill-grid reveal">
        @if($totalItems === 0)
          <div class="svc-pill-empty">
            No services added for this category yet. Please connect services from the dashboard.
          </div>
        @else
          {{-- Individual services --}}
          @foreach($categoryServices as $categoryService)
            @php
              $svcImg = $categoryService->hero_image
                  ? \App\Support\Img::thumb('uploads/service_images/' . $categoryService->hero_image, 700)
                  : asset('public/front/assets/img/hero/service-details-bg.jpg');
              $svcDesc = trim(strip_tags($categoryService->description ?: ($categoryService->overview ?? '')));
            @endphp
            <a href="{{ route('front.service', $categoryService->slug) }}" class="svc-pill"
              aria-label="{{ $categoryService->name }}">
              <div class="svc-pill-media">
                <img src="{{ $svcImg }}" alt="{{ $categoryService->name }}" loading="lazy">
              </div>
              <div class="svc-pill-body">
                <h3 class="svc-pill-title">{{ $categoryService->name }}</h3>
                @if($svcDesc !== '')
                  <p class="svc-pill-desc">{{ Str::limit($svcDesc, 230) }}</p>
                @endif
                <span class="svc-pill-more">View details <i class="fa-solid fa-arrow-right"></i></span>
              </div>
            </a>
          @endforeach

          {{-- Service packages / groups --}}
          @foreach($categoryServiceGroups as $group)
            @php
              $grpImg = $group->hero_image
                  ? \App\Support\Img::thumb(ltrim($group->hero_image, '/'), 700)
                  : ($group->image
                      ? \App\Support\Img::thumb('uploads/service_group_images/' . $group->image, 700)
                      : asset('public/front/assets/img/hero/service-details-bg.jpg'));
              $grpDesc = trim(strip_tags($group->description ?: ($group->overview ?? '')));
            @endphp
            <a href="{{ route('service-packages', $group->slug) }}" class="svc-pill svc-pill--package"
              aria-label="{{ $group->name }}">
              <div class="svc-pill-media">
                <img src="{{ $grpImg }}" alt="{{ $group->name }}" loading="lazy">
              </div>
              <div class="svc-pill-body">
                <span class="svc-pill-tag"><i class="bi bi-collection-fill"></i> Service Package</span>
                <h3 class="svc-pill-title">{{ $group->name }}</h3>
                @if($grpDesc !== '')
                  <p class="svc-pill-desc">{{ Str::limit($grpDesc, 230) }}</p>
                @endif
                <span class="svc-pill-more">View package <i class="fa-solid fa-arrow-right"></i></span>
              </div>
            </a>
          @endforeach
        @endif
      </div>
  
    </div>
  </section>
  
  

{{-- <section class="services-section">
  <div class="container">
    <h2 class="section-title">All Services in Facility Licensing & Setup</h2>
    <p>28 specialist services — select a filter or browse all</p>
  </div>

  <div class="services-grid">
    @foreach($service_groups as $group)
        @php
            $bgImage = $group->image ? asset('public/uploads/service_groups/' . $group->image) : '';
        @endphp
        <a href="#" class="service-card {{ $group->is_featured ? 'featured' : '' }}">
          <div class="service-card-bg" style="background-image: url('{{ $bgImage }}');"></div>
          
          @if($group->is_featured)
            <span class="featured-badge">Most Requested</span>
          @endif
          
          <div class="service-card-body">
            <h3 class="service-card-title">{{ $group->name }}</h3>
            <p class="service-card-desc">
                @if($group->is_featured)
                    {{ $group->description }}
                @else
                    {{ Str::limit($group->description, 120) }}
                @endif
            </p>
          </div>
          
          <div class="service-card-footer">
            <span class="service-card-tag">{{ $group->is_featured ? 'Alpha HMC' : 'Service' }}</span>
            <div class="service-card-arrow">→</div>
          </div>
        </a>
    @endforeach

    @if($service_groups->isEmpty())
        <p class="text-muted">No service groups available.</p>
    @endif
  </div>
</section> --}}

{{--  --}}


<!--Process Section-->



@php
  $processHeaders = $service->process_header;
  $processDescriptions = $service->process_description;

  if (!is_array($processHeaders)) {
    $decodedHeaders = json_decode($service->process_header, true);
    $processHeaders = is_array($decodedHeaders)
      ? $decodedHeaders
      : (!empty($service->process_header) ? [$service->process_header] : []);
  }

  if (!is_array($processDescriptions)) {
    $decodedDescriptions = json_decode($service->process_description, true);
    $processDescriptions = is_array($decodedDescriptions)
      ? $decodedDescriptions
      : (!empty($service->process_description) ? [$service->process_description] : []);
  }

  $processCount = max(count($processHeaders), count($processDescriptions));

  $processServiceIds = $service->process_service_ids ?? [];
  if (!is_array($processServiceIds)) {
    $decodedIds = json_decode($processServiceIds, true);
    $processServiceIds = is_array($decodedIds) ? $decodedIds : [];
  }
  $linkedIds = array_values(array_filter($processServiceIds));
  $processServiceMap = count($linkedIds)
    ? \App\Models\Service::whereIn('id', $linkedIds)->get()->keyBy('id')
    : collect();
@endphp

@if ($processCount > 0)
  <section class="process-section" id="process-journey">
    <!-- Dynamic Blobs for Premium Background -->
    <div class="blob-container">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
    </div>

    <div class="container">
      <div class="process-header" data-aos="fade-up">
        <span class="process-eyebrow">Our Process</span>
        @if(!empty($service->process_intro))
            {!! $service->process_intro !!}
        @else
            <h2 class="process-title">From first call to <em>license</em> in hand</h2>
            <p class="process-subtitle">A meticulously structured {{ $processCount }}-phase engagement model designed for absolute precision and regulatory speed.</p>
        @endif
      </div>

      <div class="process-grid">
        @for ($i = 0; $i < $processCount; $i++)
          <div class="process-card" data-aos="fade-up" data-aos-delay="{{ min(($i + 1) * 100, 500) }}">
            <div class="card-shine"></div>
            <div class="step-num-wrapper">
              <span class="step-num">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
              <div class="step-icon"></div>
            </div>
            <h4>{{ $processHeaders[$i] ?? 'Process Step' }}</h4>
            <p>{!! $processDescriptions[$i] ?? '' !!}</p>
            @php $stepService = !empty($processServiceIds[$i]) ? ($processServiceMap[$processServiceIds[$i]] ?? null) : null; @endphp
            @if ($stepService)
              <a href="{{ route('front.service', $stepService->slug) }}" class="process-svc">
                <span class="process-svc-name">{{ $stepService->name }} →</span>
                @if (trim(strip_tags((string) $stepService->overview)) !== '')
                  <span class="process-svc-desc">{{ Str::limit(strip_tags($stepService->overview), 140) }}</span>
                @endif
              </a>
            @endif
          </div>
        @endfor
      </div>
    </div>
  </section>
@endif

        <!-- Transformation Section (Dark) -->
        {{-- <section class="transformation-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4" data-aos="fade-right">
                        <div class="large-info">
                            {!! $service->info_one !!}
                        </div>
                        
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
        </script> --}}

        <!-- Dynamic Magazine / Insights Section -->
        {{-- <section class="magazine-section" id="magazine-insights">
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

                   
                    <div class="mag-image-side" data-aos="fade-right">
                        <div class="mag-image-container">
                            @php
                                $firstMag = $displayMagazines->first();
                                $firstImg = ($firstMag && $firstMag->image)
                                    ? ((strpos($firstMag->image, 'http') === 0)
                                        ? $firstMag->image
                                        : \App\Support\Img::thumb('uploads/magazines/' . $firstMag->image, 720))
                                    : '';
                            @endphp
                            <img src="{{ $firstImg }}" alt="Magazine Feature" id="mag-main-image">

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

                    
                    <div class="mag-content-side" data-aos="fade-left">
                        <div class="swiper mag-swiper-container">
                           <div class="swiper-wrapper">
    
    @foreach ($displayMagazines as $mag)
        @php
            $currImg = $mag->image ? ((strpos($mag->image, 'http') === 0)
                ? $mag->image
                : \App\Support\Img::thumb('uploads/magazines/' . $mag->image, 720)) : '';
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
                : \App\Support\Img::thumb('uploads/magazines/' . $mag->image, 720)) : '';
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
                : \App\Support\Img::thumb('uploads/magazines/' . $mag->image, 720)) : '';
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


                    </div>
                </div>
            </div>
        </section> --}}
{{-- 
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
</script> --}}


<!-- How Alpha Can Help Section - EY Style Refined -->
        <section class="help-section" id="how-alpha-can-help">
            <div class="container">
                <div class="help-flex">
                    <div class="help-info" data-aos="fade-up">
                        {{-- <h2>How Alpha can help</h2> --}}

                        <div class="help-text-block">
                            {{-- <h4>{{ $service->name }} solution</h4> --}}
                            @if($service->info_four && trim($service->info_four) != '')
                                <div class="mt-4">
                                    {!! $service->info_four !!}
                                </div>
                            @endif
                            <button type="button" class="help-enquiry-btn" data-bs-toggle="modal"
                                data-bs-target="#inquiryModal">
                                Send Enquiry →
                            </button>
                        </div>
                    </div>

                    <div class="leader-sidebar" data-aos="fade-left">
                        <span class="leader-label">Service Leader</span>
                        <hr class="leader-hr">
                        <div class="leader-profile">
                            <img src="{{ isset($service->agent) && $service->agent->image ? \App\Support\Img::thumb('uploads/agent_images/' . $service->agent->image, 250) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
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
                                        $lAgent    = $service->agent ?? null;
                                        $agentUser = $lAgent?->user;
                                        $lPhone    = $agentUser?->phone ?? '+971 4 272 4064';
                                        $showCall  = !$lAgent || $lAgent->hasMode('call');
                                        $showEmail = !$lAgent || $lAgent->hasMode('email');
                                        $_waNum    = ($lAgent && !empty($lAgent->whatsapp))
                                            ? preg_replace('/[^0-9]/', '', $lAgent->whatsapp)
                                            : (\App\Models\AppSetting::where('key','whatsapp_default_number')->value('value') ?? '97142724064');
                                        $pageWaLink = $_waNum ? 'https://wa.me/' . $_waNum . '?text=' . rawurlencode("Hi, I'd like to enquire about Alpha Health Group's services.") : null;
                                        $showWa    = (!$lAgent || $lAgent->hasMode('whatsapp')) && !empty($pageWaLink);
                                    @endphp

                                    @if($showCall)
                                    <a href="tel:{{ $lPhone }}" class="contact-link-icon" title="Call">
                                        Call
                                    </a>
                                    @endif
                                    @if($showEmail)
                                    <a href="{{ route('contact') }}" class="contact-link-icon" title="Email"
                                        data-bs-toggle="modal" data-bs-target="#inquiryModal">
                                        Email
                                    </a>
                                    @endif
                                    @if($showWa)
                                    <a href="{{ $pageWaLink }}" target="_blank"
                                        class="contact-link-icon whatsapp-color" title="WhatsApp">
                                        WhatsApp
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        @php
            $displayFaqs = $service->faqs ?? collect();
        @endphp
        @if ($displayFaqs->count() > 0)
            <!--FAQ Section-->
            <section class="help-list-section" id="faq-section">
                <div class="container">
                    <header class="mb-5" data-aos="fade-up">
                        <h2 class="mb-3">Frequently Asked Questions</h2>
                        <p class="text-muted">Common questions about {{ $service->name }} and our approach.</p>
                    </header>

                    <div class="faq-accordion">
                        @foreach ($displayFaqs as $index => $faq)
                            <div class="faq-item {{ $index === 0 ? 'active' : '' }}" data-aos="fade-up"
                                data-aos-delay="{{ min($index * 100, 500) }}">
                                <button class="faq-header" onclick="toggleFaq(this)">
                                    <h4 class="faq-question">{{ $faq->faq_question }}</h4>
                                    <span class="faq-icon">+</span>
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
        @endif

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

            // Initialize active FAQ content height and icon
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.faq-item.active').forEach(item => {
                    const content = item.querySelector('.faq-content');
                    if (content) content.style.maxHeight = content.scrollHeight + "px";
                    const icon = item.querySelector('.faq-icon');
                    if (icon) icon.textContent = '−';
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
                                                <img src="{{ \App\Support\Img::thumb($project->projects_images[0]->image, 800) }}"
                                                    class="ey-case-img" alt="{{ $project->name }}">
                                            @endif
                                        </div>
                                        <div class="ey-case-body">
                                            <span class="ey-case-cat">{{ $project->project_category->name }}</span>
                                            <h4 class="ey-case-title">{{ $project->name }}</h4>
                                            <a href="{{ route('front.project_details', $project->slug) }}" class="text-dark fw-bold text-decoration-none">
                                VIEW CASE STUDY →
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
                                    <img src="{{ isset($blog->image) && $blog->image ? \App\Support\Img::thumb('uploads/blog_images/' . $blog->image, 800) : asset('public/front-new/assets/images/blog_images/Doctor-image/2.webp') }}"
                                        class="rc-bg-img" alt="{{ $blog->title }}">

                                    <div class="ey-related-content">
                                        <h3 class="rc-title">{{ $blog->title }}</h3>
                                        <div class="rc-actions">
                                            <span class="ey-related-btn">Read more →</span>
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
        <section class="bottom-interests" id="related-services">
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
                                        <img src="{{ $related->hero_image ? \App\Support\Img::thumb('uploads/service_images/' . $related->hero_image, 800) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}"
                                            alt="{{ $related->name }}">
                                    </div>
                                    <div class="interest-content">
                                        <span class="text-uppercase small fw-bold text-muted mb-2 d-block">
                                            Featured Service
                                        </span>
                                        <h4>{{ $related->name }}</h4>
                                        <div class="interest-meta">
                                            <span>View Service →</span>
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

        <!-- Inquiry Modal — shared default "Book a Consultation" (popup-blocker-safe Bootstrap modal, mobile-optimized) -->
        @include('front.partials.inquiry-modal')
        <script>
            (function () {
                var im = document.getElementById('inquiryModal');
                if (!im) return;
                im.addEventListener('show.bs.modal', function () {
                    if (typeof window.ahgPrefillInquiry === 'function') {
                        window.ahgPrefillInquiry({ categoryName: @json($service->name) });
                    }
                });
            })();
        </script>

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
                    const icon = el.querySelector('.faq-icon');
                    if (icon) icon.textContent = '+';
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                    const icon = item.querySelector('.faq-icon');
                    if (icon) icon.textContent = '−';
                }
            }
        </script>
    @endpush
@endsection