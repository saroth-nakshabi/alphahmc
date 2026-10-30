{{-- Beyond approval: the in-house teams. --}}
<section class="lp-section lp-beyond" id="beyond">
    <div class="lp-wrap lp-thesis-grid">
        <div>
            <h2>{{ $d['heading'] }}</h2>
            @if($d['text'] !== '')<p class="lp-lede lp-mt">{{ $d['text'] }}</p>@endif
        </div>
        @if(count($d['items']))
        <div>
            @if($d['list_label'] !== '')<p class="lp-beyond-label">{{ $d['list_label'] }}</p>@endif
            <dl class="lp-losses">
                @foreach($d['items'] as $item)<div><dt>{{ $item['title'] }}</dt><dd>{{ $item['text'] }}</dd></div>@endforeach
            </dl>
        </div>
        @endif
    </div>
</section>
