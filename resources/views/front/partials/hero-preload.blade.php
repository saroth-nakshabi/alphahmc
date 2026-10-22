{{-- Preload for the hero <picture> (partials/hero-picture): the same candidates the img will pick, one
     link per viewport, so each viewport downloads exactly one hero file. $pic = Img::heroPicture(...). --}}
@php
    $heroPhone = $pic['phone']['webp'] !== '' ? $pic['phone']['webp'] : $pic['phone']['img'];
    $heroDesk  = $pic['desktop']['webp'] !== '' ? $pic['desktop']['webp'] : $pic['desktop']['img'];
@endphp
@if($heroPhone !== '')
<link rel="preload" as="image" @if($pic['phone']['webp'] !== '') type="image/webp" @endif imagesrcset="{{ $heroPhone }}" imagesizes="100vw" media="(max-width: 767px)" fetchpriority="high">
@endif
@if($heroDesk !== '')
<link rel="preload" as="image" @if($pic['desktop']['webp'] !== '') type="image/webp" @endif imagesrcset="{{ $heroDesk }}" imagesizes="100vw" media="(min-width: 768px)" fetchpriority="high">
@else
<link rel="preload" as="image" href="{{ $pic['src'] }}" media="(min-width: 768px)" fetchpriority="high">
@endif
