{{-- Hero LCP image for service, group and category pages. $hero = Img::hero(...) = [desktop, phone].
     Each viewport preloads only its own file; on phones the media query swaps the background
     to the small file (the inline style keeps the desktop URL), so only one image downloads. --}}
@php [$heroLg, $heroSm] = $hero; @endphp
<link rel="preload" as="image" href="{{ $heroSm }}" media="(max-width: 767px)" fetchpriority="high">
<link rel="preload" as="image" href="{{ $heroLg }}" media="(min-width: 768px)" fetchpriority="high">
@if($heroSm !== $heroLg)
<style>@media (max-width: 767px) { .service-hero .hero-background { background-image: linear-gradient(to right, rgba(0, 0, 0, 25%), rgba(202, 202, 202, 0.163)), url('{{ $heroSm }}') !important; } }</style>
@endif
