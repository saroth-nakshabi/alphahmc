{{-- Optional services that make the project turnkey (Saroth, 30 Oct 2026): construction phase engineering,
     construction and fit-out, staff hiring, soft launch and three months of operational support after licensing.
     Replaces the one-line "Also available" note under the scope rows. --}}
@if(count($d['items']))
<section class="lp-section lp-turnkey" id="turnkey">
    <div class="lp-wrap">
        <div class="lp-turnkey-head">
            <div>
                @if($d['label'] !== '')<p class="lp-turnkey-label">{{ $d['label'] }}</p>@endif
                <h2>{{ $d['heading'] }}</h2>
            </div>
            @if($d['text'] !== '')<p class="lp-lede">{{ $d['text'] }}</p>@endif
        </div>
        <ul class="lp-turnkey-grid">
            @foreach($d['items'] as $item)
                <li>
                    <span class="lp-turnkey-num" aria-hidden="true">{{ $loop->iteration }}</span>
                    <h3>{{ $item['title'] }}</h3>
                    @if($item['text'] !== '')<p>{{ $item['text'] }}</p>@endif
                </li>
            @endforeach
        </ul>
        @if($d['note'] !== '')<p class="lp-turnkey-note">{{ $d['note'] }}</p>@endif
        <p class="lp-turnkey-cta"><a class="lp-btn lp-btn-ghost" href="#contact">{{ $hero['proposal_button'] ?: 'Request a proposal' }}</a></p>
    </div>
</section>
@endif
