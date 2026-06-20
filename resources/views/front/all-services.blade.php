@extends('front/layout-2')

@push('page_title', 'All Healthcare Consultancy Services | Alpha Health Group')

@section('meta_description')Browse all healthcare consultancy services by Alpha Health Group — DOH licensing, JCIA accreditation, quality assurance, infection control, patient safety, and more for UAE facilities.@endsection

@section('custom_css')
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ─── DESIGN TOKENS ────────────────────────────────────────────────── */
:root {
  --as-teal:     #009095;
  --as-navy:     #0b1f3a;
  --as-navy-light:#162e4a;
  --as-bg:       #f8fafc;
  --as-white:    #ffffff;
  --as-muted:    #64748b;
  --as-border:   rgba(11,31,58,0.08);
  --as-shadow:   0 12px 40px rgba(11,31,58,0.08);
  --as-radius:   16px;
  --as-ease:     cubic-bezier(0.16, 1, 0.3, 1);
}

body {
    margin-top: 140px !important;
    background-color: var(--as-bg);
}

.as-page {
    font-family: 'Outfit', sans-serif;
    color: var(--as-navy);
}

/* ─── HERO SECTION ─────────────────────────────────────────────────── */
.as-hero {
    background: var(--as-navy);
    padding: 100px 0 250px;
    position: relative;
    overflow: hidden;
    color: var(--as-white);
    margin-top: -140px;
}

.as-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: radial-gradient(circle at 80% 20%, rgba(0, 144, 149, 0.15) 0%, transparent 50%);
    pointer-events: none;
}

.as-hero__title {
    font-family: 'Libre Baskerville', serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 700;
    margin-bottom: 24px;
    line-height: 1.1;
}

.as-hero__title span {
    color: var(--as-teal);
    display: block;
    font-size: 0.4em;
    text-transform: uppercase;
    letter-spacing: 0.3em;
    font-family: 'Roboto Mono', monospace;
    margin-bottom: 12px;
}

.as-breadcrumb {
    display: flex;
    gap: 8px;
    font-size: 0.85rem;
    font-family: 'Roboto Mono', monospace;
    opacity: 0.7;
    margin-top: 40px;
}

.as-breadcrumb a {
    color: var(--as-white);
    text-decoration: none;
    transition: color 0.3s;
}

.as-breadcrumb a:hover {
    color: var(--as-teal);
}

/* ─── SEARCH & NAV WRAP ────────────────────────────────────────────── */
.as-sticky-tools {
    position: sticky;
    top: 80px;
    z-index: 1000;
    background: rgba(248, 250, 252, 0.8);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--as-border);
    padding: 15px 0;
    margin-top: -40px;
}

.as-search-box {
    background: var(--as-white);
    padding: 8px;
    border-radius: 100px;
    box-shadow: 0 10px 30px rgba(11, 31, 58, 0.05);
    display: flex;
    align-items: center;
    border: 1px solid var(--as-border);
    transition: all 0.3s;
}

.as-search-box:focus-within {
    border-color: var(--as-teal);
    box-shadow: 0 15px 40px rgba(0, 144, 149, 0.1);
}

.as-search-input {
    flex: 1;
    border: none;
    padding: 0 20px;
    font-size: 1rem;
    outline: none;
    background: transparent;
}

.as-search-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--as-teal);
    color: #fff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Category Quick Nav */
.as-quick-nav {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding: 10px 0;
    scrollbar-width: none;
}
.as-quick-nav::-webkit-scrollbar { display: none; }

.as-quick-nav-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.as-quick-nav-arrow {
    width: 36px;
    height: 36px;
    border: 1px solid var(--as-border);
    border-radius: 50%;
    background: var(--as-white);
    color: var(--as-navy);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s var(--as-ease);
}

.as-quick-nav-arrow:hover:not(:disabled) {
    background: var(--as-teal);
    color: #fff;
    border-color: var(--as-teal);
}

.as-quick-nav-arrow:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

.as-nav-item {
    padding: 8px 20px;
    background: var(--as-white);
    border: 1px solid var(--as-border);
    border-radius: 100px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    color: var(--as-muted);
    white-space: nowrap;
    transition: all 0.3s;
}

.as-nav-item:hover, .as-nav-item.active {
    background: var(--as-teal);
    color: #fff;
    border-color: var(--as-teal);
    box-shadow: 0 8px 20px rgba(0, 144, 149, 0.2);
}

/* ─── SERVICES GRID ────────────────────────────────────────────────── */
.as-section { padding: 60px 0; }

.as-cat-header {
    margin-bottom: 32px;
    scroll-margin-top: 200px;
}

.as-cat-title {
    font-family: 'Libre Baskerville', serif;
    font-size: 1.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.as-cat-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--as-border);
}

.as-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 24px;
    margin-bottom: 60px;
}

.as-card {
    background: var(--as-white);
    border-radius: var(--as-radius);
    padding: 28px;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: var(--as-navy);
    border: 1px solid var(--as-border);
    transition: all 0.4s var(--as-ease);
    position: relative;
}

.as-card:hover {
    transform: translateY(-8px);
    border-color: var(--as-teal);
    box-shadow: 0 20px 40px rgba(11, 31, 58, 0.1);
}

.as-card__media {
    position: relative;
    margin: -28px -28px 18px;          /* full-bleed over the card padding */
    height: 168px;
    overflow: hidden;
    border-radius: var(--as-radius) var(--as-radius) 0 0;
    background: #eef3f4;
}
.as-card__media img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.5s var(--as-ease);
}
.as-card:hover .as-card__media img { transform: scale(1.06); }
.as-card__media .as-card__badge { position: absolute; top: 12px; left: 12px; }
.as-card__tag {
    position: absolute; top: 12px; right: 12px;
    background: rgba(255,255,255,0.92); color: #009095;
    padding: 4px 10px; border-radius: 6px;
    font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
}

.as-card__top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.as-card__badge {
    padding: 4px 10px;
    background: #066D77;
    color:white;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    border-radius: 4px;
    letter-spacing: 0.05em;
}

.as-card__title {
    font-size: 1.2rem;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: 12px;
    transition: color 0.3s;
}

.as-card:hover .as-card__title { color: var(--as-teal); }

.as-card__desc {
    font-size: 0.9rem;
    color: var(--as-muted);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 24px;
}

.as-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid var(--as-border);
}

.as-card__link {
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--as-navy);
    padding: 10px 18px;
    border-radius: 10px;
    background: #f1f5f9;
    transition: all 0.3s var(--as-ease);
}

.as-card__link i {
    font-size: 0.7rem;
    transition: transform 0.3s;
}

.as-card__link:hover {
    background: var(--as-navy);
    color: #fff;
    text-decoration: none;
}

.as-card__link:hover i {
    transform: translateX(5px);
}

.as-card__inquiry {
    padding: 10px 20px;
    background: var(--as-teal);
    color: #fff;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 700;
    transition: all 0.3s;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 144, 149, 0.2);
}

.as-card__inquiry:hover {
    background: var(--as-navy);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(11, 31, 58, 0.2);
}

/* ─── MODAL ─────────────────────────────────────────────────────────── */
.as-modal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 40px 100px rgba(11, 31, 58, 0.2);
}

.as-modal .modal-header {
    background: var(--as-navy);
    color: #fff;
    border-radius: 20px 20px 0 0;
    padding: 24px;
}

.as-modal .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

.as-form-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--as-navy);
    margin-bottom: 8px;
}

.as-form-control {
    border-radius: 10px;
    padding: 12px 16px;
    border: 1px solid var(--as-border);
    font-size: 0.95rem;
}

.as-form-control:focus {
    border-color: var(--as-teal);
    box-shadow: 0 0 0 4px rgba(0, 144, 149, 0.1);
}

@media (max-width: 768px) {
    .as-sticky-tools { top: 70px; }
    .as-grid { grid-template-columns: 1fr; }
    .as-quick-nav-arrow { display: none; }
}
</style>
@endsection

@section('content')
@php
    $serviceCategories = $main_categories
        ->flatMap(function ($mainCategory) {
            return $mainCategory->mergedCategories ?? collect();
        })
        ->unique('id')
        ->values();
@endphp
<div class="as-page">
    {{-- HERO SECTION --}}
    <section class="as-hero">
        <div class="container">
            <div class="row align-items-center" style="margin-top: 140px;">
                <div class="col-lg-8">
                    <h1 class="as-hero__title">
                        <span>Our Expertise</span>
                        Healthcare<br>Consultancy Services
                    </h1>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="as-breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <span>/</span>
                        <a href="#">Services</a>
                        {{-- <a id="share-icon" href="#" class="ms-2"><i class="fa-solid fa-share-nodes"></i></a> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STICKY TOOLS (Search & Nav) --}}
    <div class="as-sticky-tools">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <form method="GET" action="{{ route('front.all-services') }}" class="as-search-box">
                        <input type="text" id="servicesSearch" name="servicesSearch" 
                               class="as-search-input" 
                               placeholder="Search services..." 
                               value="{{ request('servicesSearch') }}" 
                               autocomplete="off">
                        <button class="as-search-btn" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
                <div class="col-lg-8">
                    <div class="as-quick-nav-wrap">
                        <button type="button" class="as-quick-nav-arrow" id="asQuickNavPrev" aria-label="Scroll categories left">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <div class="as-quick-nav" id="asQuickNav">
                            @foreach ($serviceCategories as $category)
                                <a href="#cat-{{ $category->id }}" class="as-nav-item">{{ $category->name }}</a>
                            @endforeach
                        </div>
                        <button type="button" class="as-quick-nav-arrow" id="asQuickNavNext" aria-label="Scroll categories right">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SERVICES LISTING --}}
    <section class="as-section">
        <div id="servicesContainer">
            @foreach ($serviceCategories as $category)
                <div class="container service-category mb-5" id="cat-{{ $category->id }}">
                    <div class="as-cat-header">
                        <h2 class="as-cat-title">{{ $category->name }}</h2>
                    </div>
                    <div class="as-grid">
                        @foreach ($category->services as $service)
                            <div class="as-card single-service-cart">
                                <div class="as-card__media">
                                    <img src="{{ $service->hero_image ? asset('public/uploads/service_images/' . $service->hero_image) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}"
                                         alt="{{ $service->name }}" loading="lazy">
                                    @if($service->featured)
                                        <span class="as-card__badge">Featured</span>
                                    @endif
                                </div>
                                <h3 class="as-card__title service-name">{{ $service->name }}</h3>
                                <p class="as-card__desc">{{ strip_tags($service->overview ?? 'Expert consultancy tailored to your specific healthcare requirements and organizational goals.') }}</p>
                                <div class="as-card__footer">
                                    <a href="{{ route('front.service', $service->slug) }}" class="as-card__link">
                                        Learn More <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                    <button class="as-card__inquiry" data-service="{{ $service->name }}" data-id="{{ $service->id }}">
                                        Enquire
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        @foreach ($category->serviceGroups as $group)
                            <div class="as-card single-service-cart">
                                <div class="as-card__media">
                                    <img src="{{ $group->hero_image ? asset('public/' . ltrim($group->hero_image, '/')) : ($group->image ? asset('public/uploads/service_group_images/' . $group->image) : asset('public/front/assets/img/hero/service-details-bg.jpg')) }}"
                                         alt="{{ $group->name }}" loading="lazy">
                                    @if($group->is_featured)
                                        <span class="as-card__badge">Featured</span>
                                    @endif
                                    <span class="as-card__tag"><i class="fa-solid fa-layer-group me-1"></i>Service Group</span>
                                </div>
                                <h3 class="as-card__title service-name">{{ $group->name }}</h3>
                                <p class="as-card__desc">{{ strip_tags($group->description ?? 'A comprehensive group of healthcare consultancy services.') }}</p>
                                <div class="as-card__footer">
                                    <a href="{{ route('service-packages', $group->slug) }}" class="as-card__link">
                                        Learn More <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                    <button class="as-card__inquiry" data-service="{{ $group->name }}" data-id="sg_{{ $group->id }}">
                                        Enquire
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div id="noResults" class="as-no-results container" style="display:none;">
            <i class="fa-solid fa-face-frown"></i>
            <h3>No services found</h3>
            <p class="text-muted">Try searching with a different keyword</p>
        </div>
    </section>
</div>

{{-- Default site inquiry / "Book a Consultation" modal (with contact consent) --}}
@push('inquiry_modal')
    @include('front.partials.inquiry-modal')
@endpush

<script>
    // Open the default "Book a Consultation" modal with the chosen service pre-filled.
    function openInquiry(serviceName, serviceId) {
        var isGroup = typeof serviceId === 'string' && serviceId.indexOf('sg_') === 0;
        if (typeof window.ahgPrefillInquiry === 'function') {
            if (isGroup) {
                window.ahgPrefillInquiry({ categoryName: serviceName });   // groups aren't in the service dropdown
            } else {
                window.ahgPrefillInquiry({ serviceId: serviceId, serviceName: serviceName });
            }
        }
        var modalEl = document.getElementById('inquiryModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (modalEl) {
            $(modalEl).modal('show');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Event delegation for inquiry buttons
        document.body.addEventListener('click', (e) => {
            const btn = e.target.closest('.as-card__inquiry');
            if (btn) {
                e.preventDefault();
                const name = btn.getAttribute('data-service');
                const id = btn.getAttribute('data-id');
                openInquiry(name, id);
            }
        });

        // Share functionality
        const shareData = {
            title: 'Alpha Health Group Services',
            text: 'Explore our comprehensive healthcare consultancy services.',
            url: window.location.href
        };

        /*const shareIcon = document.getElementById('share-icon');
        if (shareIcon) {
            shareIcon.addEventListener('click', async (e) => {
                e.preventDefault();
                if (navigator.share) {
                    try {
                        await navigator.share(shareData);
                    } catch (err) {
                        console.error('Error sharing:', err);
                    }
                } else {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(window.location.href)
                            .then(() => alert('Link copied to clipboard!'))
                            .catch(() => alert('Unable to copy link automatically. Please copy the URL manually.'));
                    } else {
                        alert('Unable to copy link automatically. Please copy the URL manually.');
                    }
                }
            });
        }*/

        // Live search & Sticky Nav highlighting
        const searchInput = document.getElementById('servicesSearch');
        const noResults = document.getElementById('noResults');
        const quickNavEl = document.getElementById('asQuickNav');
        const quickNavPrevBtn = document.getElementById('asQuickNavPrev');
        const quickNavNextBtn = document.getElementById('asQuickNavNext');
        const categories = Array.from(document.querySelectorAll('.service-category'));
        const navItemMap = new Map(
            Array.from(document.querySelectorAll('.as-nav-item')).map(item => [item.getAttribute('href'), item])
        );
        let activeSectionCache = [];

        const updateQuickNavArrows = () => {
            if (!quickNavEl || !quickNavPrevBtn || !quickNavNextBtn) return;

            const maxScrollLeft = quickNavEl.scrollWidth - quickNavEl.clientWidth;
            quickNavPrevBtn.disabled = quickNavEl.scrollLeft <= 0;
            quickNavNextBtn.disabled = quickNavEl.scrollLeft >= maxScrollLeft - 1;
        };

        const rebuildSectionOffsets = () => {
            activeSectionCache = categories
                .filter(cat => cat.style.display !== 'none')
                .map(cat => ({
                    id: cat.id,
                    top: cat.offsetTop
                }));
            updateQuickNavArrows();
        };

        rebuildSectionOffsets();
        window.addEventListener('resize', rebuildSectionOffsets);

        if (quickNavEl && quickNavPrevBtn && quickNavNextBtn) {
            quickNavPrevBtn.addEventListener('click', () => {
                quickNavEl.scrollBy({ left: -220, behavior: 'smooth' });
            });

            quickNavNextBtn.addEventListener('click', () => {
                quickNavEl.scrollBy({ left: 220, behavior: 'smooth' });
            });

            quickNavEl.addEventListener('scroll', updateQuickNavArrows, { passive: true });
            updateQuickNavArrows();
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                let anyVisible = false;

                categories.forEach(cat => {
                    let catVisible = false;
                    const cards = cat.querySelectorAll('.single-service-cart');
                    
                    cards.forEach(card => {
                        const name = card.querySelector('.service-name').textContent.toLowerCase();
                        if (name.includes(term)) {
                            card.style.display = 'flex';
                            catVisible = true;
                            anyVisible = true;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    cat.style.display = catVisible ? 'block' : 'none';
                    
                    const navItem = navItemMap.get(`#${cat.id}`);
                    if(navItem) navItem.style.display = catVisible ? 'inline-block' : 'none';
                });

                rebuildSectionOffsets();
                noResults.style.display = anyVisible ? 'none' : 'block';
            });
        }

        // Scroll Spy for Nav Highlighting
        const runScrollSpy = () => {
            let current = "";
            activeSectionCache.forEach((section) => {
                if (window.pageYOffset >= section.top - 250) {
                    current = section.id;
                }
            });

            navItemMap.forEach((item) => {
                item.classList.remove("active");
            });

            const activeNavItem = navItemMap.get(`#${current}`);
            if (activeNavItem) {
                activeNavItem.classList.add("active");
            }
        };

        let isScrollTicking = false;
        window.addEventListener('scroll', () => {
            if (isScrollTicking) return;

            isScrollTicking = true;
            window.requestAnimationFrame(() => {
                runScrollSpy();
                isScrollTicking = false;
            });
        });

        runScrollSpy();

        // Inquiry submission is handled by the shared default modal (partials/inquiry-modal).
    });
</script>
@endsection