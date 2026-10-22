{{-- Hero image as a real <img> (the LCP element), cropped like the old CSS background (object-fit: cover).
     $pic = Img::heroPicture(...): phones get the 412/828 files, larger screens 1280/1920, WebP first.
     $class: the img class. $deferred = true leaves the sources in data-* attributes until the page load
     event (later slides of the home slider; see index-2), so only the first image competes for bandwidth. --}}
@php
    $deferred = $deferred ?? false;
    $attr = $deferred ? 'data-srcset' : 'srcset';
@endphp
<picture>
    @if($pic['phone']['webp'] !== '')
    <source type="image/webp" media="(max-width: 767px)" {{ $attr }}="{{ $pic['phone']['webp'] }}" sizes="100vw">
    @endif
    @if($pic['phone']['img'] !== '')
    <source media="(max-width: 767px)" {{ $attr }}="{{ $pic['phone']['img'] }}" sizes="100vw">
    @endif
    @if($pic['desktop']['webp'] !== '')
    <source type="image/webp" {{ $attr }}="{{ $pic['desktop']['webp'] }}" sizes="100vw">
    @endif
    <img class="{{ $class }}" alt="" {{ $deferred ? 'data-src' : 'src' }}="{{ $pic['src'] }}"
         @if($pic['desktop']['img'] !== '') {{ $attr }}="{{ $pic['desktop']['img'] }}" sizes="100vw" @endif
         @if($pic['width']) width="{{ $pic['width'] }}" height="{{ $pic['height'] }}" @endif
         @if($deferred) loading="lazy" @else fetchpriority="high" @endif decoding="async">
</picture>
