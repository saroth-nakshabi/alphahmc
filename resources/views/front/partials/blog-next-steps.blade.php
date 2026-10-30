{{-- End of a guide (brief "alphahmc-reviewers-and-linking", 3b and 3c): one sentence linking its service page
     (blogs.service_link, text = that page's H1), the extra links for its reviewer, and "Related guides":
     the 3 newest published posts with the same reviewer. --}}
@php
    $serviceLink = \App\Support\InternalLinks::page($blog->service_link);
    $extraLinks = collect(\App\Support\InternalLinks::forPage('/blog_single/' . $blog->slug, $blog->reviewer))
        ->reject(fn ($l) => $serviceLink && $l['url'] === $serviceLink['url'])->values();
    $relatedGuides = $blog->reviewer
        ? \App\Models\Blog::where('reviewer', $blog->reviewer)->where('id', '!=', $blog->id)
            ->orderByDesc(\Illuminate\Support\Facades\DB::raw('COALESCE(published_date, created_at)'))->orderByDesc('id')
            ->limit(3)->get(['id', 'title', 'slug', 'image', 'published_date', 'created_at'])
        : collect();
@endphp
@if($serviceLink || $extraLinks->isNotEmpty() || $relatedGuides->isNotEmpty())
<section class="ahg-next-steps">
    @if($serviceLink)
        <p class="ahg-next-service">For help with this step, see <a href="{{ $serviceLink['url'] }}">{{ $serviceLink['text'] }}</a>.</p>
    @endif
    @if($extraLinks->isNotEmpty())
        <p class="ahg-next-extra">Licensing by authority:
            @foreach($extraLinks as $l)<a href="{{ $l['url'] }}">{{ $l['text'] }}</a>@if(!$loop->last), @endif @endforeach
        </p>
    @endif
    @if($relatedGuides->isNotEmpty())
        <h2>Related guides</h2>
        <ul>
            @foreach($relatedGuides as $g)
                <li><a href="{{ route('front.singleBlog', $g->slug) }}">{{ html_entity_decode(strip_tags($g->title), ENT_QUOTES | ENT_HTML5, 'UTF-8') }}</a></li>
            @endforeach
        </ul>
    @endif
</section>
@once
<style>
    .ahg-next-steps { max-width: 780px; margin: 40px auto 0; padding-top: 28px; border-top: 1px solid #e2e8f0; }
    .ahg-next-steps p { font-size: 1.05rem; line-height: 1.6; margin: 0 0 12px; }
    .ahg-next-steps a { color: #006e72; text-decoration: underline; text-underline-offset: 3px; }
    .ahg-next-steps h2 { font-size: 1.2rem; font-weight: 700; margin: 24px 0 10px; }
    .ahg-next-steps ul { margin: 0; padding-left: 1.2rem; }
    .ahg-next-steps li { margin-bottom: 8px; line-height: 1.5; }
</style>
@endonce
@endif
