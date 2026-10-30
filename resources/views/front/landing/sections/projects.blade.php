{{-- Delivered projects, picked from /project in the admin; the cards link to their case studies. --}}
<section class="lp-section lp-projects" id="projects">
    <div class="lp-wrap">
        <div class="lp-proj-head">
            <h2>{{ $d['heading'] }}</h2>
            @if($d['see_all_label'] !== '')<a class="lp-btn lp-btn-ghost" href="{{ route('front.project') }}">{{ $d['see_all_label'] }}</a>@endif
        </div>
        <div class="lp-proj-grid">
            @foreach($d['items'] as $item)
                @php $project = $projects->get($item['project_id']); @endphp
                @continue(!$project)
                @php
                    $img  = optional($project->projects_images->first())->image;
                    $webp = $img ? \App\Support\Img::webp($img, 828) : null;
                @endphp
                <a class="lp-proj" href="{{ route('front.project_details', $project->slug) }}">
                    <figure>
                        @if($img)
                        <picture>
                            @if($webp)<source type="image/webp" srcset="{{ $webp }}">@endif
                            <img src="{{ \App\Support\Img::thumb($img, 828) }}" alt="{{ $item['image_alt'] ?: $project->name }}" width="828" height="518" loading="lazy" decoding="async">
                        </picture>
                        @endif
                    </figure>
                    <div class="lp-proj-meta">
                        @if($item['type_label'] !== '')<span class="lp-type">{{ $item['type_label'] }}</span>@endif
                        <h3>{{ $item['title'] ?: $project->name }}</h3>
                        @if($item['summary'] !== '')<p>{{ $item['summary'] }}</p>@endif
                        @if($d['card_link'] !== '')<span class="lp-go">{{ $d['card_link'] }}</span>@endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
