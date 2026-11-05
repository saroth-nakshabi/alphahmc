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
                    <source type="image/webp" srcset="{{ asset('public/front/assets/images/landing/scope-engineers.webp') }}?v={{ substr(md5_file(public_path('front/assets/images/landing/scope-engineers.webp')), 0, 10) }}">
                    <img src="{{ asset('public/front/assets/images/landing/scope-engineers.jpg') }}?v={{ substr(md5_file(public_path('front/assets/images/landing/scope-engineers.jpg')), 0, 10) }}" alt="{{ $d['image_alt'] }}" width="418" height="359" loading="lazy" decoding="async">
                </picture>
            </figure>
        </div>
        <div>
            @foreach($groups as $group => $rows)
            <div class="lp-group">
                @if($group !== '')<h3>{{ $group }}</h3>@endif
                <ul class="lp-rows">
                    @foreach($rows as $row)
                        @php
                            // Optional link on words already in the text (the official authority, SEO agent 5 Nov 2026).
                            $rowText = e($row['text']);
                            $linkText = $row['link_text'] ?? ''; $linkUrl = $row['link_url'] ?? '';
                            if ($linkText !== '' && preg_match('#^https?://#', $linkUrl) && str_contains($rowText, e($linkText))) {
                                $pos = strpos($rowText, e($linkText));
                                $rowText = substr_replace($rowText, '<a href="' . e($linkUrl) . '">' . e($linkText) . '</a>', $pos, strlen(e($linkText)));
                            }
                        @endphp
                        <li><b>{{ $row['title'] }}</b><span>{!! $rowText !!}</span></li>
                    @endforeach
                </ul>
            </div>
            @endforeach
            @if($d['optional'] !== '')<p class="lp-optional">{{ $d['optional'] }}</p>@endif
        </div>
    </div>
</section>
