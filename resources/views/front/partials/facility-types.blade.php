{{-- "Set up any type of healthcare facility" (SEO brief "alphahmc-facility-types", 30 Oct 2026): cards managed in
     Dashboard > Facility types, server-rendered links to indexable pages only. --}}
@php $facilityCards = \App\Models\FacilityTypeCard::visible(); @endphp
@if($facilityCards->isNotEmpty())
<section class="ft-section" aria-labelledby="ft-heading">
    <div class="container">
        <h2 id="ft-heading">Set up any type of healthcare facility</h2>
        <p class="ft-intro">Choose your facility type to see what it takes to plan, license and open it in the UAE.</p>
        <ul class="ft-grid">
            @foreach($facilityCards as $card)
                <li>
                    <a class="ft-card" href="{{ $card->url }}">
                        <h3>{{ $card->name }}</h3>
                        @if($card->line)<p>{{ $card->line }}</p>@endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
<style>
    .ft-section { padding: 72px 0; }
    .ft-section h2 { font-size: clamp(1.8rem, 1.4rem + 1.4vw, 2.6rem); margin: 0 0 12px; }
    .ft-intro { color: #4a5c60; font-size: 1.05rem; margin: 0 0 32px; max-width: 60ch; }
    .ft-grid { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px; }
    .ft-card { display: block; height: 100%; padding: 22px 22px 20px; border: 1px solid #dfe7e8; border-radius: 16px; background: #fff; color: #0e2427; text-decoration: none; transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease; }
    .ft-card:hover, .ft-card:focus-visible { border-color: #066d77; box-shadow: 0 12px 30px rgba(6,109,119,.12); transform: translateY(-2px); color: #0e2427; }
    .ft-card h3 { font-size: 1.12rem; margin: 0 0 8px; line-height: 1.35; }
    .ft-card p { margin: 0; color: #4a5c60; font-size: .95rem; line-height: 1.55; }
</style>
@endif
