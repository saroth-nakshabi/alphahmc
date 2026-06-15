{{-- ============================================================
     Reusable "Alpha Blueprint AI" launcher band.
     Use anywhere:  @include('front.partials.planner-cta')
     Optional:      @include('front.partials.planner-cta', ['variant' => 'light'])  // 'ink' (default) | 'light'
     ============================================================ --}}
@php $pcVariant = $variant ?? 'ink'; @endphp

<section class="apc apc--{{ $pcVariant }}" aria-label="Plan your healthcare project">
    <div class="container">
        <div class="apc-inner">
            <div class="apc-copy">
                <span class="apc-eyebrow"><span class="apc-dot"></span> Alpha Blueprint AI</span>
                <h2 class="apc-title">Your strategic plan is one&nbsp;minute&nbsp;away.</h2>
                <p class="apc-sub">Tell us your goal and preview the scope, recommended services, timeline and indicative
                    investment for your healthcare project — built instantly, no commitment.</p>
                <div class="apc-actions">
                    <a href="{{ route('planner.page') }}" class="apc-btn">
                        Build your plan <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <span class="apc-meta"><i class="fa-regular fa-clock"></i> ~60 seconds</span>
                </div>
            </div>
            <div class="apc-visual" aria-hidden="true">
                <div class="apc-rule"><span></span><span></span><span></span><span></span></div>
                <div class="apc-mark"><i class="fa-solid fa-pen-ruler"></i></div>
            </div>
        </div>
    </div>
</section>

@once
<style>
    .apc { padding: clamp(54px, 7vw, 86px) 0; }
    .apc-inner {
        display: flex; align-items: center; gap: 40px; border-radius: 24px; padding: clamp(30px, 5vw, 56px);
        position: relative; overflow: hidden;
    }
    .apc--ink .apc-inner { background: #0b0f14; color: #fff; }
    .apc--light .apc-inner { background: #f6f9f9; border: 1px solid #e6efef; color: #0b0f14; }
    .apc-copy { flex: 1; min-width: 0; }
    .apc-eyebrow {
        display: inline-flex; align-items: center; gap: 8px; font-size: .76rem; font-weight: 600; letter-spacing: .4px;
        text-transform: none; margin-bottom: 16px; opacity: .9;
    }
    .apc--ink .apc-eyebrow { color: #9fb6b8; }
    .apc--light .apc-eyebrow { color: #066D77; }
    .apc-dot { width: 7px; height: 7px; border-radius: 50%; background: #1ea7a1; position: relative; }
    .apc-dot::after { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 1px solid #1ea7a1; opacity: .4; animation: apcPulse 2.2s ease-out infinite; }
    @keyframes apcPulse { 0% { transform: scale(.6); opacity: .6; } 100% { transform: scale(1.7); opacity: 0; } }
    .apc-title { font-family: 'Outfit', sans-serif; font-weight: 700; letter-spacing: -.02em; line-height: 1.08;
        font-size: clamp(1.7rem, 3.6vw, 2.7rem); margin: 0 0 14px; }
    .apc-sub { font-size: clamp(.98rem, 1.3vw, 1.08rem); line-height: 1.6; margin: 0 0 26px; max-width: 560px; }
    .apc--ink .apc-sub { color: rgba(255,255,255,.66); }
    .apc--light .apc-sub { color: #5b6b73; }
    .apc-actions { display: flex; align-items: center; gap: 18px; flex-wrap: wrap; }
    .apc-btn {
        display: inline-flex; align-items: center; gap: 11px; font-family: 'Outfit', sans-serif; font-weight: 600;
        font-size: 1rem; padding: 16px 34px; border-radius: 100px; text-decoration: none;
        transition: transform .2s cubic-bezier(.16,1,.3,1), background-color .2s ease, color .2s ease;
    }
    .apc--ink .apc-btn { background: #fff; color: #0b0f14; }
    .apc--ink .apc-btn:hover { background: #e6f4f5; color: #0b0f14; }
    .apc--light .apc-btn { background: #066D77; color: #fff; }
    .apc--light .apc-btn:hover { background: #055863; color: #fff; }
    .apc-btn:hover { transform: translateY(-2px); }
    .apc-btn i { font-size: .82rem; transition: transform .25s ease; }
    .apc-btn:hover i { transform: translateX(4px); }
    .apc-meta { font-size: .85rem; opacity: .7; }
    .apc-meta i { margin-right: 6px; }

    /* Minimal "blueprint" visual — refined line motif, not an AI cliché */
    .apc-visual { flex-shrink: 0; width: 220px; display: flex; flex-direction: column; align-items: flex-end; gap: 22px; }
    .apc-rule { display: flex; flex-direction: column; gap: 10px; width: 100%; align-items: flex-end; }
    .apc-rule span { display: block; height: 2px; border-radius: 2px; }
    .apc--ink .apc-rule span { background: rgba(255,255,255,.16); }
    .apc--light .apc-rule span { background: #cfe0e2; }
    .apc-rule span:nth-child(1) { width: 100%; }
    .apc-rule span:nth-child(2) { width: 72%; }
    .apc-rule span:nth-child(3) { width: 88%; }
    .apc-rule span:nth-child(4) { width: 56%; }
    .apc-mark {
        width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
    }
    .apc--ink .apc-mark { background: rgba(30,167,161,.16); color: #4fd1c5; }
    .apc--light .apc-mark { background: #eef6f6; color: #066D77; }

    @media (max-width: 820px) {
        .apc-inner { flex-direction: column; align-items: flex-start; gap: 28px; }
        .apc-visual { width: 100%; flex-direction: row-reverse; justify-content: space-between; align-items: center; }
        .apc-rule { width: 60%; }
    }
</style>
@endonce
