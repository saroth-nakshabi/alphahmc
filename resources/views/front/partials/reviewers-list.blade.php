{{-- "Our reviewers" on the About page: links to the six bio pages (brief "alphahmc-reviewers-and-linking"). --}}
@php $reviewersList = \App\Models\Reviewer::byKey(); @endphp
@if($reviewersList->isNotEmpty())
<section class="about-reviewers" id="reviewers" aria-labelledby="about-reviewers-h">
    <div class="container">
        <h2 id="about-reviewers-h">Our reviewers</h2>
        <p class="about-reviewers-lead">Our service pages and guides are reviewed by the consultants who do this work every day.</p>
        <ul>
            @foreach($reviewersList as $r)
                <li>
                    <a href="{{ $r->url() }}">
                        <span class="about-reviewers-initials" aria-hidden="true">{{ $r->initials() }}</span>
                        <span><strong>{{ $r->name }}</strong><small>{{ $r->title }}</small></span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
<style>
    .about-reviewers { padding: 64px 0; }
    .about-reviewers h2 { font-size: clamp(1.6rem, 1.3rem + 1vw, 2.2rem); margin: 0 0 10px; }
    .about-reviewers-lead { color: #3c5457; margin: 0 0 28px; max-width: 60ch; }
    .about-reviewers ul { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px; }
    .about-reviewers a { display: flex; gap: 14px; align-items: center; padding: 14px 16px; border: 1px solid #d9e4e4; border-radius: 14px; text-decoration: none; color: #0e2427; height: 100%; }
    .about-reviewers a:hover, .about-reviewers a:focus-visible { border-color: #006e72; }
    .about-reviewers-initials { flex: none; width: 52px; height: 52px; border-radius: 50%; background: #006e72; color: #fff; display: grid; place-items: center; font-weight: 600; }
    .about-reviewers strong { display: block; font-weight: 600; }
    .about-reviewers small { display: block; color: #3c5457; font-size: .88rem; line-height: 1.4; }
</style>
@endif
