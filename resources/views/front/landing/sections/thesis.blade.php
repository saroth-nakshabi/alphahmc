{{-- Quote with the planning points. --}}
<section class="lp-section lp-thesis">
    <div class="lp-wrap lp-thesis-grid">
        <blockquote>{!! $highlight($d['quote_before'], $d['quote_highlight'], $d['quote_after']) !!}</blockquote>
        @if(count($d['items']))
        <dl class="lp-losses">
            @foreach($d['items'] as $item)<div><dt>{{ $item['title'] }}</dt><dd>{{ $item['text'] }}</dd></div>@endforeach
        </dl>
        @endif
    </div>
</section>
