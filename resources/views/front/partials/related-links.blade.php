{{-- Hand-picked related pages (App\Support\InternalLinks). $links: [['url', 'text'], ...]; $heading optional.
     Renders nothing without links. --}}
@if(!empty($links))
<section class="ahg-related-links" aria-labelledby="ahg-related-links-h">
    <div class="container">
        <h2 id="ahg-related-links-h">{{ $heading ?? 'Related pages' }}</h2>
        <ul>
            @foreach($links as $link)
                <li><a href="{{ $link['url'] }}">{{ $link['text'] }}</a></li>
            @endforeach
        </ul>
    </div>
</section>
@once
<style>
    .ahg-related-links { padding: 48px 0; }
    .ahg-related-links h2 { font-size: 1.4rem; font-weight: 700; margin: 0 0 16px; }
    .ahg-related-links ul { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: 10px; }
    .ahg-related-links a { display: inline-block; padding: 10px 16px; border: 1px solid #cfdcdd; border-radius: 999px; color: #0e2427; text-decoration: none; font-size: .95rem; line-height: 1.3; }
    .ahg-related-links a:hover, .ahg-related-links a:focus-visible { border-color: #006e72; color: #006e72; }
</style>
@endonce
@endif
