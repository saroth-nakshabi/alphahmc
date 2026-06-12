{{-- One batch of featured-category cards for the home "Our Latest Thinking" grid.
     A full batch of 8 reuses the div1–div8 mosaic; a smaller final batch uses a uniform auto grid. --}}
@php $isFullBatch = $categories->count() === 8; @endphp
<div class="parent{{ $isFullBatch ? '' : ' parent-auto' }}">
    @foreach ($categories as $index => $category)
        <div class="article-card {{ $isFullBatch ? 'div' . ($index + 1) : '' }}"
            style="background-image: url('{{ $category->card_image ? asset('public/' . ltrim($category->card_image, '/')) : asset('public/front/assets/img/hero/service-details-bg.jpg') }}')">
            <div class="article-content">
                <h4>{{ $category->name }}</h4>
                <p>{{ Str::limit(strip_tags($category->description), 100) }}</p>
                <a href="{{ route('front.service-category', $category->slug) }}" class="btn-premium-read-more" aria-label="Read more about {{ $category->name }}">
                    Read More <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    @endforeach
</div>
