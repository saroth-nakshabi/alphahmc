{{-- One stylesheet: with critical CSS for this template, inline the first-screen rules here and load
     the full file without blocking (same position, so the cascade is unchanged); otherwise a normal link.
     Params: $href, $bundle (critical file name), $criticalTemplate (null = no critical CSS). --}}
@php $criticalRules = \App\Support\CriticalCss::inline($criticalTemplate ?? null, $bundle); @endphp
@if($criticalRules !== null)
    <style data-critical="{{ $bundle }}">{!! $criticalRules !!}</style>
    <link rel="preload" as="style" href="{{ $href }}" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ $href }}"></noscript>
@else
    <link rel="stylesheet" href="{{ $href }}">
@endif
