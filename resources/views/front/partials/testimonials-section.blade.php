<style>
.inline-reviews { padding: 80px 0; background: #f8f9fa; font-family: 'Inter', sans-serif; }
.inline-reviews-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 36px; flex-wrap: wrap; gap: 16px; }
.inline-reviews-header h2 { font-size: 1.6rem; font-weight: 600; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
.ir-summary { display: flex; align-items: center; gap: 12px; }
.ir-avg-num { font-size: 2.4rem; font-weight: 400; color: #202124; line-height: 1; }
.ir-stars i  { font-size: 1rem; color: #FBBC04; }
.ir-stars i.empty { color: #d4d4d4; }
.ir-count    { font-size: 0.82rem; color: #70757a; }
.ir-view-all {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.85rem; font-weight: 600; color: #066D77;
    border: 1px solid rgba(6,109,119,0.3); border-radius: 100px;
    padding: 8px 20px; text-decoration: none;
    transition: all 0.25s ease;
}
.ir-view-all:hover { background: #066D77; color: #fff; }
.ir-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
@media (max-width: 991px) { .ir-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 575px)  { .ir-grid { grid-template-columns: 1fr; } }
.ir-card { background: #fff; border: 1px solid #e8eaed; border-radius: 12px; padding: 22px; display: flex; flex-direction: column; gap: 12px; transition: box-shadow 0.25s ease; }
.ir-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.ir-top { display: flex; align-items: center; gap: 11px; }
.ir-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 600; color: #fff; flex-shrink: 0; overflow: hidden; }
.ir-avatar img { width: 100%; height: 100%; object-fit: cover; }
.ir-name  { font-size: 0.88rem; font-weight: 600; color: #202124; }
.ir-date  { font-size: 0.73rem; color: #70757a; margin-top: 1px; }
.ir-card-stars i { font-size: 0.82rem; color: #FBBC04; }
.ir-card-stars i.empty { color: #d4d4d4; }
.ir-text  { font-size: 0.85rem; color: #3c4043; line-height: 1.65; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
.ir-service-tag { font-size: 0.7rem; font-weight: 600; color: #066D77; background: rgba(6,109,119,0.07); border-radius: 100px; padding: 3px 10px; width: fit-content; }
.ir-write-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.82rem; font-weight: 600; color: #fff;
    background: #066D77; border-radius: 100px; padding: 10px 22px;
    text-decoration: none; transition: background 0.25s ease;
    white-space: nowrap; flex-shrink: 0;
}
.ir-write-btn:hover { background: #088a95; color: #fff; }
</style>

<section class="inline-reviews">
    <div class="container">
        <div class="inline-reviews-header">
            <div>
                <h2>What Our Clients Say</h2>
                <div class="ir-summary mt-2">
                    <span class="ir-avg-num">{{ $totalCount ? number_format($avgRating, 1) : '—' }}</span>
                    <div>
                        <div class="ir-stars">
                            @for($s=1;$s<=5;$s++)
                                <i class="fa-solid fa-star {{ $s <= round($avgRating) ? '' : 'empty' }}"></i>
                            @endfor
                        </div>
                        <div class="ir-count">{{ $totalCount }} verified {{ Str::plural('review', $totalCount) }}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <a href="{{ route('front.feedback') }}" class="ir-write-btn">
                    <i class="fa-solid fa-pen-to-square"></i> Write a Review
                </a>
                <a href="{{ route('front.testimonials') }}" class="ir-view-all">
                    View all reviews <i class="fa-solid fa-arrow-right" style="font-size:0.7rem"></i>
                </a>
            </div>
        </div>

        <div class="ir-grid">
            @foreach ($testimonials as $t)
            @php
                $initials = collect(explode(' ', $t->author_name))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                $colors   = ['#1a73e8','#34a853','#ea4335','#fa7b17','#a142f4','#066D77','#e37400'];
                $color    = $colors[crc32($t->author_name) % count($colors)];
            @endphp
            <div class="ir-card">
                <div class="ir-top">
                    <div class="ir-avatar" style="background:{{ $color }}">
                        @if($t->author_image)
                            <img src="{{ asset('public/uploads/testimonials/' . $t->author_image) }}" alt="{{ $t->author_name }}">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div>
                        <div class="ir-name">{{ $t->author_name }}</div>
                        <div class="ir-date">{{ $t->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div class="ir-card-stars">
                    @for($s=1;$s<=5;$s++)
                        <i class="fa-solid fa-star {{ $s <= $t->rating ? '' : 'empty' }}"></i>
                    @endfor
                </div>
                @if($t->service)
                <div class="ir-service-tag"><i class="fa-solid fa-stethoscope" style="font-size:0.6rem"></i> {{ $t->service->name }}</div>
                @endif
                <div class="ir-text">{{ $t->content }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
