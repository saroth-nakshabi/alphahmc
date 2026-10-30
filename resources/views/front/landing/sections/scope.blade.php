{{-- Scope rows, grouped by their group name in the order they are listed. --}}
@php
    $groups = [];
    foreach ($d['rows'] as $row) {
        $groups[$row['group']][] = $row;
    }
@endphp
<section class="lp-section" id="scope">
    <div class="lp-wrap lp-scope-grid">
        <div class="lp-scope-intro">
            <h2>{{ $d['heading'] }}</h2>
            @if($d['text'] !== '')<p class="lp-lede lp-mt">{{ $d['text'] }}</p>@endif
            <figure>
                <picture>
                    <source type="image/webp" srcset="{{ asset('public/front/assets/images/landing/scope-engineers.webp') }}">
                    <img src="{{ asset('public/front/assets/images/landing/scope-engineers.jpg') }}" alt="{{ $d['image_alt'] }}" width="800" height="357" loading="lazy" decoding="async">
                </picture>
            </figure>
        </div>
        <div>
            @foreach($groups as $group => $rows)
            <div class="lp-group">
                @if($group !== '')<h3>{{ $group }}</h3>@endif
                <ul class="lp-rows">
                    @foreach($rows as $row)<li><b>{{ $row['title'] }}</b><span>{{ $row['text'] }}</span></li>@endforeach
                </ul>
            </div>
            @endforeach
            @if($d['optional'] !== '')<p class="lp-optional">{{ $d['optional'] }}</p>@endif
        </div>
    </div>
</section>
