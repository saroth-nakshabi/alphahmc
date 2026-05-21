@extends('front/layout-2')

@section('custom_css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
<style>
/* ─── DESIGN TOKENS ────────────────────────────────────────────────── */
:root {
  --cr:          #009095;   /* Alpha HMC Teal accent */
  --cr-light:    #f0fdfa;   /* teal tint bg */
  --cr-mid:      rgba(0,144,149,0.12);
  --cr-glow:     rgba(0,144,149,0.18);

  --slate:       #0b1f3a;   /* Alpha Navy text */
  --slate-2:     #162e4a;
  --slate-3:     #2d4561;
  --muted:       #64748b;
  --ghost:       #94a3b8;

  --bg:          #FAFAF8;   /* warm off-white */
  --bg-2:        #F3F2EF;   /* light warm grey */
  --bg-3:        #ECEAE5;
  --white:       #FFFFFF;

  --border:      rgba(11,31,58,0.08);
  --border-md:   rgba(11,31,58,0.13);

  --shadow-xs:   0 1px 3px rgba(11,31,58,0.06);
  --shadow-sm:   0 4px 16px rgba(11,31,58,0.07);
  --shadow-md:   0 12px 40px rgba(11,31,58,0.1);
  --shadow-lg:   0 24px 64px rgba(11,31,58,0.12);
  --shadow-xl:   0 40px 100px rgba(11,31,58,0.14);

  --r-xs:        6px;
  --r-sm:        10px;
  --r-md:        18px;
  --r-lg:        28px;
  --r-xl:        40px;

  --ease-expo:   cubic-bezier(0.16, 1, 0.3, 1);
  --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
  --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
}

/* ─── MODAL OVERRIDES ───────────────────────────────────────────────── */
.modal { z-index: 999999 !important; }
.modal-backdrop { z-index: 999998 !important; }

/* ─── BASE ──────────────────────────────────────────────────────────── */
.pdl *, .pdl *::before, .pdl *::after { box-sizing: border-box; }

.pdl {
  font-family: 'Inter', sans-serif;
  font-weight: 400;
  background: var(--bg);
  color: var(--slate);
  line-height: 1.65;
  overflow-x: hidden;
  margin-top: -140px;
  padding-top: 140px;
  -webkit-font-smoothing: antialiased;
}

.pdl h1, .pdl h2, .pdl h3, .pdl h4 {
  font-family: 'Inter', sans-serif;
  font-weight: 700;
  line-height: 1.08;
  color: var(--slate);
  letter-spacing: -0.02em;
}

.pdl-wrap { max-width: 1360px; margin: 0 auto; padding: 0 48px; }
.pd-container { max-width: 1280px; margin: 0 auto; padding: 0 40px; }

/* ─── REVEAL ANIMATIONS ─────────────────────────────────────────────── */
@keyframes rise {
  from { opacity: 0; transform: translateY(36px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes slideRight {
  from { opacity: 0; transform: translateX(-24px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes expandLine {
  from { transform: scaleX(0); transform-origin: left; }
  to   { transform: scaleX(1); transform-origin: left; }
}
@keyframes countUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

.pdl-reveal { opacity: 0; }
.pdl-reveal.on { animation: rise 0.85s var(--ease-expo) forwards; }
.pdl-reveal.on.d1 { animation-delay: 0.1s; }
.pdl-reveal.on.d2 { animation-delay: 0.2s; }
.pdl-reveal.on.d3 { animation-delay: 0.32s; }
.pdl-reveal.on.d4 { animation-delay: 0.44s; }

/* ─── SECTION CHROME ────────────────────────────────────────────────── */
.pdl-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 24px;
}
.pdl-eyebrow__tick {
  width: 28px; height: 2px;
  background: var(--cr);
  display: block;
  animation: expandLine 0.6s var(--ease-expo) 0.4s both;
}
.pdl-eyebrow__text {
  font-family: 'Inter', sans-serif;
  font-size: 0.68rem;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--cr);
}

/* ─── HERO ──────────────────────────────────────────────────────────── */

 /* --- Hero Section --- */
        .pd-hero {
            padding: 130px 0 100px;
            /* background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.05), transparent 40%); */
            width: 75%;
            margin: 0 auto;
            border-radius: 20px;
        }

        .pd-hero-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 60px;
            align-items: center;
        }

        .pd-hero-tagline {
            display: inline-block;
            background: #054B59;
            color: white;
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .pd-hero h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 24px;
            letter-spacing: -0.02em;
        }

        .pd-hero-desc-wrapper {
            margin-bottom: 40px;
            max-width: 550px;
        }

        .pd-hero-desc-content {
            font-size: 1.25rem;
            color: var(--pd-text-light);
            line-height: 1.6;
            margin-bottom: 0 !important;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .pd-hero-desc-content.expanded {
            -webkit-line-clamp: unset;
            display: block;
        }

        .pd-hero-read-more {
            background: none;
            border: none;
            color: var(--pd-accent);
            font-weight: 700;
            padding: 8px 0;
            margin-top: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .pd-hero-read-more:hover {
            opacity: 0.8;
            gap: 10px;
        }

        .pd-hero-read-more svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }

        .pd-hero-read-more.active svg {
            transform: rotate(180deg);
        }

        .pd-hero-ctas {
            display: flex;
            gap: 16px;
        }

        .pd-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.01em;
            text-decoration: none;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .pd-btn-primary {
            background: linear-gradient(135deg, #009095 0%, #054B59 100%);
            color: #fff;
            box-shadow: 0 4px 20px rgba(0, 144, 149, 0.35), 0 1px 3px rgba(0,0,0,0.12);
        }

        .pd-btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #00b0b6 0%, #076778 100%);
            opacity: 0;
            transition: opacity 0.35s;
            border-radius: inherit;
        }

        .pd-btn-primary:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 32px rgba(0, 144, 149, 0.45), 0 2px 8px rgba(0,0,0,0.15);
        }

        .pd-btn-primary:hover::after { opacity: 1; }
        .pd-btn-primary i,
        .pd-btn-primary span { position: relative; z-index: 1; }
        .pd-btn-primary svg { position: relative; z-index: 1; transition: transform 0.35s cubic-bezier(0.16,1,0.3,1); }
        .pd-btn-primary:hover svg { transform: scale(1.15) rotate(-8deg); }

        .pd-hero-image-container {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--pd-shadow-lg);
            height: 500px;
        }

        /* single static image */
        .pd-hero-image-container > img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 8s ease;
        }
        .pd-hero-image-container:hover > img { transform: scale(1.04); }

        /* ── Hero Slider ── */
        .pd-hero-slider { width: 100%; height: 100%; position: relative; }

        .pd-hero-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.75s ease;
            pointer-events: none;
        }
        .pd-hero-slide.active {
            opacity: 1;
            pointer-events: auto;
        }
        .pd-hero-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 8s ease;
            transform: scale(1);
        }
        .pd-hero-slide.active img { transform: scale(1.04); }

        .pd-hslider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate);
            transition: all 0.25s var(--ease-expo);
            box-shadow: 0 2px 12px rgba(0,0,0,0.14);
        }
        .pd-hslider-btn:hover {
            background: #fff;
            color: var(--cr);
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 20px rgba(0,0,0,0.18);
        }
        .pd-hslider-prev { left: 14px; }
        .pd-hslider-next { right: 14px; }

        .pd-hslider-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .pd-hslider-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.35s var(--ease-expo);
            border: none;
            padding: 0;
        }
        .pd-hslider-dot.active {
            background: #fff;
            width: 22px;
            border-radius: 4px;
        }

        .pd-hslider-counter {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 10;
            background: rgba(0,0,0,0.38);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            padding: 4px 11px;
            border-radius: 100px;
        }

        @media (max-width: 768px) {
            .pd-hero-image-container { height: 320px; }
            .pd-hslider-btn { width: 34px; height: 34px; }
        }

        .pd-play-button {
            position: absolute;
            right: 32px;
            bottom: 32px;
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--pd-shadow-lg);
            border: none;
            transition: transform 0.3s;
        }

        .pd-play-button:hover {
            transform: scale(1.1);
        }

        .pd-play-button i {
            color: var(--pd-accent);
            fill: var(--pd-accent);
            margin-left: 4px;
        }
/* .pdl-hero {
  display: grid;
  grid-template-columns: 1fr 1fr;
  max-height: 105vh;
  position: relative;
  overflow: hidden;
}

.pdl-hero__left {
  background: var(--bg);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 60px 72px 60px 48px;
  position: relative;
  z-index: 2;
}


.pdl-hero__left::before {
  content: attr(data-letter);
  position: absolute;
  right: -10px; top: 50%;
  transform: translateY(-50%);
  font-family: 'Instrument Serif', serif;
  font-size: 28rem;
  color: var(--bg-3);
  line-height: 1;
  pointer-events: none;
  user-select: none;
  z-index: 0;
  letter-spacing: -0.05em;
}

.pdl-hero__content { position: relative; z-index: 1; }

.pdl-hero__title {
  font-size: clamp(3rem, 4.5vw, 5.2rem);
  line-height: 1.02;
  margin-bottom: 28px;
  letter-spacing: -0.03em;
}

.pdl-hero__title em {
  font-style: italic;
  color: var(--cr);
}

.pdl-hero__desc {
  font-size: 1.05rem;
  color: var(--muted);
  line-height: 1.8;
  max-width: 460px;
  margin-bottom: 10px;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  transition: all 0.5s var(--ease-expo);
} */
/* .pdl-hero__desc.expanded {
  -webkit-line-clamp: unset;
  display: block;
}

.pdl-readmore {
  background: none; border: none;
  font-family: 'Inter', sans-serif;
  font-size: 0.65rem;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--cr);
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 4px 0;
  margin-bottom: 48px;
  transition: gap 0.3s;
}
.pdl-readmore:hover { gap: 12px; }
.pdl-readmore svg { transition: transform 0.3s; }
.pdl-readmore.open svg { transform: rotate(180deg); }

.pdl-hero__ctas {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
} */

/* ─── BUTTONS ───────────────────────────────────────────────────────── */
/* .pdl-btn {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  padding: 14px 28px;
  border-radius: var(--r-sm);
  font-family: 'Inter', sans-serif;
  font-size: 0.68rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: all 0.35s var(--ease-expo);
  position: relative;
  overflow: hidden;
}

.pdl-btn-primary {
  background: var(--cr);
  color: #fff;
  box-shadow: 0 6px 24px rgba(0, 144, 149, 0.3);
}
.pdl-btn-primary:hover {
  background: #004852ff;
  transform: translateY(-2px);
  box-shadow: 0 12px 32px rgba(0, 72, 82, 0.4);
  color: #fff;
}

.pdl-btn-outline {
  background: transparent;
  color: var(--slate);
  border: 1.5px solid var(--border-md);
}
.pdl-btn-outline:hover {
  border-color: var(--cr);
  color: var(--cr);
  transform: translateY(-2px);
} */

/* ─── HERO RIGHT (Image) ────────────────────────────────────────────── */
/* .pdl-hero__right {
  position: relative;
  overflow: hidden;
}

.pdl-hero__img {
  width: 100%; height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 10s ease;
  filter: saturate(0.9) brightness(0.96);
}

.pdl-hero__right:hover .pdl-hero__img { transform: scale(1.04); }

.pdl-hero__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(90deg, var(--bg) 0%, transparent 18%),
              linear-gradient(to top, var(--bg) 0%, transparent 22%);
} */

/* Floating meta card */
/* .pdl-hero__meta {
  position: absolute;
  bottom: 48px; left: -1px;
  background: var(--white);
  border-radius: 0 var(--r-md) var(--r-md) 0;
  padding: 24px 32px;
  box-shadow: var(--shadow-lg);
  display: flex;
  gap: 36px;
  border-top: 3px solid var(--cr);
}

.pdl-hero__meta-item {}
.pdl-meta-lbl {
  font-family: 'Inter', sans-serif;
  font-size: 0.6rem;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--cr);
  margin-bottom: 4px;
}
.pdl-meta-val {
  font-size: 0.88rem;
  font-weight: 500;
  color: var(--slate);
} */

/* ─── DIVIDER ───────────────────────────────────────────────────────── */
/* .pdl-divider {
  height: 1px;
  background: var(--border);
  margin: 0;
} */

/* ─── OVERVIEW ──────────────────────────────────────────────────────── */
.pdl-overview {
  background: var(--white);
  padding: 140px 0;
  position: relative;
}

.pdl-overview::after {
  content: '';
  position: absolute;
  bottom: 0; left: 10%; right: 10%;
  height: 1px;
  background: var(--border);
}

.pdl-overview__grid {
  display: grid;
  grid-template-columns: 1.15fr 0.85fr;
  gap: 100px;
  align-items: start;
}

.pdl-overview__title {
  font-size: clamp(2.6rem, 3.5vw, 4rem);
  margin-bottom: 28px;
}

.pdl-overview__body {
  font-size: 1rem;
  color: var(--slate-3);
  line-height: 1.85;
}
.pdl-overview__body p { margin-bottom: 18px; }

.pdl-stat-list {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.pdl-stat-row {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 22px 24px;
  background: var(--bg);
  border-radius: var(--r-sm);
  border: 1px solid var(--border);
  transition: all 0.4s var(--ease-expo);
  position: relative;
  overflow: hidden;
}

.pdl-stat-row::after {
  content: '';
  position: absolute;
  right: 0; top: 0; bottom: 0;
  width: 0;
  background: var(--cr);
  transition: width 0.4s var(--ease-expo);
  border-radius: 0 var(--r-xs) var(--r-xs) 0;
}

.pdl-stat-row:hover { background: var(--white); box-shadow: var(--shadow-sm); border-color: rgba(0,144,149,0.2); transform: translateX(-4px); }
.pdl-stat-row:hover::after { width: 3px; }

.pdl-stat-row__icon {
  width: 40px; height: 40px;
  background: var(--cr-light);
  border-radius: var(--r-xs);
  display: flex; align-items: center; justify-content: center;
  color: var(--cr);
  flex-shrink: 0;
  transition: transform 0.4s var(--ease-spring);
}
.pdl-stat-row:hover .pdl-stat-row__icon { transform: scale(1.1) rotate(-5deg); }

.pdl-stat-row__label {
  font-family: 'Inter', sans-serif;
  font-size: 0.6rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 3px;
}
.pdl-stat-row__val { font-size: 0.92rem; font-weight: 500; color: var(--slate); }

/* ─── SERVICES DELIVERED ──────────────────────────────────────────── */
.pdl-svc-block {
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid var(--border);
}
.pdl-svc-block__lbl {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 0.59rem;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 12px;
}
.pdl-svc-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.pdl-svc-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 15px;
  border-radius: 100px;
  background: var(--white);
  border: 1px solid var(--border-md);
  font-size: 0.82rem;
  font-weight: 500;
  color: var(--slate-3);
  text-decoration: none;
  transition: all 0.25s var(--ease-expo);
  white-space: nowrap;
}
.pdl-svc-pill svg { transition: transform 0.25s var(--ease-expo); flex-shrink: 0; }
.pdl-svc-pill:hover {
  background: var(--cr-light);
  border-color: rgba(0,144,149,0.3);
  color: var(--cr);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,144,149,0.1);
}
.pdl-svc-pill:hover svg { transform: translateX(3px); }

/* ─── FEATURES ──────────────────────────────────────────────────────── */
.pdl-features {
  background: var(--bg);
  padding: 140px 0;
}

.pdl-features__head {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: end;
  margin-bottom: 80px;
}

.pdl-features__title { font-size: clamp(2.6rem, 3.5vw, 4rem); }
.pdl-features__sub { font-size: 1rem; color: var(--slate-3); line-height: 1.8; }

.pdl-features__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.pdl-feat {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  padding: 48px 40px;
  position: relative;
  overflow: hidden;
  transition: all 0.5s var(--ease-expo);
}

.pdl-feat__corner {
  position: absolute;
  top: 0; right: 0;
  width: 80px; height: 80px;
  background: var(--cr-light);
  border-radius: 0 var(--r-lg) 0 100%;
  transition: all 0.5s var(--ease-expo);
  overflow: hidden;
}

.pdl-feat:hover .pdl-feat__corner {
  width: 120px; height: 120px;
}

.pdl-feat__num {
  font-family: 'Inter', sans-serif;
  font-size: 0.62rem;
  letter-spacing: 0.2em;
  color: var(--ghost);
  margin-bottom: 28px;
  display: block;
}

.pdl-feat__icon {
  width: 52px; height: 52px;
  background: var(--cr-light);
  border-radius: var(--r-sm);
  display: flex; align-items: center; justify-content: center;
  color: var(--cr);
  margin-bottom: 28px;
  transition: transform 0.4s var(--ease-spring), background 0.3s;
}

.pdl-feat:hover { box-shadow: var(--shadow-xl); border-color: transparent; transform: translateY(-8px); }
.pdl-feat:hover .pdl-feat__icon { transform: scale(1.1) rotate(-6deg); background: var(--cr); color: #fff; }

.pdl-feat__title { font-size: 1.5rem; margin-bottom: 14px; }
.pdl-feat__body { font-size: 0.92rem; color: var(--slate-3); line-height: 1.78; }

/* ─── TECH STACK ────────────────────────────────────────────────────── */
.pdl-tech {
  background: var(--white);
  padding: 64px 0;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
}

.pdl-tech__row {
  display: flex;
  align-items: center;
  gap: 56px;
  flex-wrap: wrap;
}

.pdl-tech__lbl {
  font-family: 'Inter', sans-serif;
  font-size: 0.62rem;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--muted);
  white-space: nowrap;
}

.pdl-tech__sep {
  width: 1px; height: 36px;
  background: var(--border-md);
  flex-shrink: 0;
}

.pdl-tech__items {
  display: flex;
  gap: 32px;
  align-items: center;
  flex-wrap: wrap;
}

.pdl-tech__item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  cursor: default;
  transition: transform 0.35s var(--ease-spring);
}
.pdl-tech__item:hover { transform: translateY(-5px); }

.pdl-tech__bubble {
  width: 58px; height: 58px;
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.3s;
}
.pdl-tech__item:hover .pdl-tech__bubble {
  background: var(--cr-light);
  border-color: rgba(0,144,149,0.2);
  box-shadow: 0 8px 24px rgba(0,144,149,0.12);
}

.pdl-tech__name {
  font-family: 'Inter', sans-serif;
  font-size: 0.58rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
}

/* ─── GALLERY ───────────────────────────────────────────────────────── */
.pdl-gallery {
  background: var(--bg);
  padding: 140px 0;
}

.pdl-gallery__head {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 56px;
  flex-wrap: wrap;
  gap: 32px;
}

.pdl-gallery__title { font-size: clamp(2.4rem, 3.2vw, 3.8rem); }

.pdl-filter-bar {
  display: flex;
  gap: 6px;
  background: var(--white);
  padding: 6px;
  border-radius: 100px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-xs);
}

.pdl-filter-btn {
  padding: 9px 20px;
  border-radius: 100px;
  background: transparent;
  border: none;
  color: var(--muted);
  font-family: 'Inter', sans-serif;
  font-size: 0.63rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  cursor: pointer;
  transition: all 0.3s var(--ease-expo);
  white-space: nowrap;
}
.pdl-filter-btn:hover { color: var(--slate); }
.pdl-filter-btn.active {
  background: var(--cr);
  color: #fff;
  box-shadow: 0 4px 16px rgba(0,144,149,0.25);
}

.pdl-gallery__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 20px;
}

.pdl-gcard {
  position: relative;
  border-radius: var(--r-lg);
  overflow: hidden;
  background: var(--white);
  border: 1px solid var(--border);
  cursor: pointer;
  height: 300px;
  transition: all 0.5s var(--ease-expo);
  box-shadow: var(--shadow-xs);
}

.pdl-gcard:hover {
  transform: translateY(-8px) scale(1.01);
  box-shadow: var(--shadow-xl);
  border-color: transparent;
}

.pdl-gcard__media {
  position: absolute; inset: 0;
}

.pdl-gcard__media img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.8s var(--ease-expo);
}
.pdl-gcard:hover .pdl-gcard__media img { transform: scale(1.08); }

.pdl-gcard__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top,
    rgba(15,25,35,0.82) 0%,
    rgba(15,25,35,0.2) 55%,
    transparent 100%);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 26px;
  opacity: 0;
  transition: opacity 0.4s;
}
.pdl-gcard:hover .pdl-gcard__overlay { opacity: 1; }

.pdl-gcard__badge {
  position: absolute;
  top: 14px; left: 14px;
  padding: 4px 12px;
  border-radius: 4px;
  background: rgba(255,255,255,0.9);
  font-family: 'Inter', sans-serif;
  font-size: 0.58rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--slate-3);
  backdrop-filter: blur(8px);
  z-index: 2;
}

.pdl-gcard__type {
  position: absolute;
  top: 12px; right: 12px;
  width: 36px; height: 36px;
  background: rgba(255,255,255,0.9);
  backdrop-filter: blur(8px);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: var(--cr);
  z-index: 2;
}

.pdl-gcard__info h4 {
  color: #fff;
  font-size: 1.1rem;
  margin-bottom: 4px;
  transform: translateY(12px);
  transition: transform 0.4s var(--ease-expo);
}
.pdl-gcard__info p {
  color: rgba(255,255,255,0.7);
  font-size: 0.82rem;
  margin: 0;
  transform: translateY(12px);
  transition: transform 0.4s 0.05s var(--ease-expo);
}
.pdl-gcard:hover .pdl-gcard__info h4,
.pdl-gcard:hover .pdl-gcard__info p { transform: translateY(0); }

.pdl-doc-thumb {
  width: 100%; height: 100%;
  background: linear-gradient(135deg, var(--bg-2) 0%, var(--bg-3) 100%);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 16px;
}
.pdl-doc-thumb i { color: var(--cr); opacity: 0.5; width: 64px; height: 64px; }
.pdl-doc-thumb span {
  font-family: 'Inter', sans-serif;
  font-size: 0.6rem;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--muted);
}

/* ─── CHALLENGES ────────────────────────────────────────────────────── */
.pdl-challenges {
  background: var(--white);
  padding: 140px 0;
}

.pdl-challenges__title {
  font-size: clamp(2.6rem, 3.5vw, 4rem);
  margin-bottom: 72px;
}

.pdl-challenges__grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

.pdl-ccard {
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  padding: 40px 44px;
  position: relative;
  overflow: hidden;
  transition: box-shadow 0.4s, border-color 0.4s;
  display: flex;
  flex-direction: column;
  height: 100%;
}
.pdl-ccard:hover { box-shadow: var(--shadow-md); border-color: rgba(232,80,58,0.15); }

.pdl-ccard__top-bar {
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
}
.pdl-ccard--problem .pdl-ccard__top-bar { background: linear-gradient(90deg, #E8503A, #f08060); }
.pdl-ccard--solution .pdl-ccard__top-bar { background: linear-gradient(90deg, #27B074, #5ecfa0); }

.pdl-cpill {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 5px 14px;
  border-radius: 4px;
  font-family: 'Inter', sans-serif;
  font-size: 0.6rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  margin-bottom: 20px;
}
.pdl-cpill--problem { background: rgba(232,80,58,0.08); color: var(--cr); }
.pdl-cpill--solution { background: rgba(39,176,116,0.08); color: #1a9060; }

.pdl-ccard h3 { font-size: 1.45rem; margin-bottom: 12px; line-height: 1.3; }
.pdl-ccard p {
  font-size: 0.92rem;
  color: var(--slate-3);
  line-height: 1.8;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 5;
  -webkit-box-orient: vertical;
  flex: 1;
}

.pdl-ccard__rule {
  height: 1px;
  background: var(--border);
  margin: 20px 0;
  flex-shrink: 0;
}

.pdl-carousel-wrap {
  position: relative;
  width: 100%;
}

.pdl-carousel {
  display: flex;
  gap: 24px;
  overflow: hidden;
  position: relative;
}

.pdl-carousel-inner {
  display: flex;
  gap: 24px;
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.pdl-ccard-slider {
  flex: 0 0 calc(50% - 12px);
  min-width: calc(50% - 12px);
  height: 440px;
}

.pdl-carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: var(--white);
  border: 1px solid var(--border);
  width: 44px;
  height: 44px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.25s ease;
  z-index: 10;
}

.pdl-carousel-btn:hover {
  background: var(--cr);
  border-color: var(--cr);
  color: white;
}

.pdl-carousel-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pdl-carousel-btn--prev {
  left: -60px;
}

.pdl-carousel-btn--next {
  right: -60px;
}

.pdl-carousel-indicators {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 24px;
}

.pdl-carousel-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--border);
  cursor: pointer;
  transition: all 0.3s ease;
}

.pdl-carousel-dot.active {
  background: var(--cr);
  width: 24px;
  border-radius: 4px;
}

@media (max-width: 768px) {
  .pdl-carousel { overflow: visible; }
  .pdl-carousel-inner {
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding-bottom: 4px;
    gap: 16px;
  }
  .pdl-carousel-inner::-webkit-scrollbar { display: none; }
  .pdl-ccard-slider {
    flex: 0 0 85%;
    min-width: 85%;
    height: 480px;
    scroll-snap-align: start;
  }
  .pdl-carousel-btn { display: none; }
  .pdl-carousel-indicators { margin-top: 20px; }
}

/* ─── RESULTS ───────────────────────────────────────────────────────── */
.pdl-results {
  background: var(--slate);
  padding: 140px 0;
  position: relative;
  overflow: hidden;
}

/* Big decorative circle */
.pdl-results::before {
  content: '';
  position: absolute;
  top: -300px; right: -200px;
  width: 800px; height: 800px;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.04);
  pointer-events: none;
}
.pdl-results::after {
  content: '';
  position: absolute;
  top: -200px; right: -100px;
  width: 600px; height: 600px;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.04);
  pointer-events: none;
}

.pdl-results__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 80px;
  flex-wrap: wrap;
  gap: 24px;
}

.pdl-results__title {
  font-size: clamp(2.6rem, 3.5vw, 4rem);
  color: #fff;
}

.pdl-results__sub {
  font-size: 0.95rem;
  color: rgba(255,255,255,0.45);
  max-width: 320px;
  line-height: 1.7;
}

.pdl-results__grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1px;
  background: rgba(255,255,255,0.06);
  border-radius: var(--r-lg);
  overflow: hidden;
}

.pdl-rstat {
  background: var(--slate);
  padding: 56px 44px;
  text-align: center;
  position: relative;
  transition: background 0.4s;
}
.pdl-rstat:hover { background: var(--slate-2); }

.pdl-rstat__num {
  font-family: 'Inter', sans-serif;
  font-size: 4.5rem;
  font-weight: 700;
  letter-spacing: -0.04em;
  line-height: 1;
  margin-bottom: 14px;
  color: var(--cr);
  animation: countUp 0.9s var(--ease-expo) forwards;
}

.pdl-rstat__lbl {
  font-family: 'Inter', sans-serif;
  font-size: 0.62rem;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.45);
}

/* ─── RELATED ───────────────────────────────────────────────────────── */
.pdl-related {
  background: var(--bg);
  padding: 140px 0;
}

.pdl-related__head {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 56px;
  flex-wrap: wrap;
  gap: 24px;
}

.pdl-related__title { font-size: clamp(2.4rem, 3.2vw, 3.8rem); }

.pdl-related__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.pdl-rcard {
  display: block;
  text-decoration: none;
  border-radius: var(--r-lg);
  overflow: hidden;
  aspect-ratio: 5/4;
  position: relative;
  background: var(--bg-3);
  transition: all 0.5s var(--ease-expo);
  box-shadow: var(--shadow-sm);
}
.pdl-rcard:hover { transform: translateY(-6px); box-shadow: var(--shadow-xl); }

.pdl-rcard img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.8s var(--ease-expo), filter 0.4s;
  filter: saturate(0.8) brightness(0.9);
}
.pdl-rcard:hover img { transform: scale(1.06); filter: saturate(1) brightness(0.85); }

.pdl-rcard__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(15,25,35,0.82) 0%, transparent 50%);
  display: flex; flex-direction: column;
  justify-content: flex-end;
  padding: 28px;
}

.pdl-rcard__cat {
  font-family: 'Inter', sans-serif;
  font-size: 0.58rem;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--cr);
  margin-bottom: 8px;
}

.pdl-rcard__name {
  font-family: 'Inter', sans-serif;
  font-size: 1.25rem;
  color: #fff;
  line-height: 1.2;
}

.pdl-rcard__arrow {
  position: absolute;
  top: 20px; right: 20px;
  width: 38px; height: 38px;
  background: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: var(--cr);
  opacity: 0;
  transform: translate(6px, -6px);
  transition: all 0.4s var(--ease-spring);
}
.pdl-rcard:hover .pdl-rcard__arrow { opacity: 1; transform: translate(0, 0); }

/* ─── MODALS ────────────────────────────────────────────────────────── */
.modal-content.pdl-modal {
  background: var(--white);
  border: 1px solid var(--border-md);
  border-radius: var(--r-lg);
  box-shadow: var(--shadow-xl);
}

.modal-content{
    padding-bottom: 0 !important;
}

.pdl-modal .modal-header {
  border-bottom: 1px solid var(--border);
  padding: 10px 20px;
}

.pdl-modal .modal-footer {
  border-top: 1px solid var(--border);
  padding: 10px 20px;
  justify-content: center;
  gap: 16px;
}

.pdl-modal .modal-title {
  font-family: 'Inter', sans-serif;
  font-size: 1.4rem;
  color: var(--slate);
  font-weight: 700;
}

.pdl-modal-icon {
  width: 40px; height: 40px;
  background: var(--cr-light);
  border-radius: var(--r-xs);
  display: flex; align-items: center; justify-content: center;
  color: var(--cr);
}

#modalImage, #modalVideo {
  max-height: 80vh;
  max-width: 100%;
  object-fit: contain;
  border-radius: var(--r-md);
  display: block;
  margin: 0 auto;
  box-shadow: var(--shadow-lg);
}

#flipbookContainer {
  width: 100%;
  height: 74vh;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  background: var(--bg-2);
}

#flipbook { box-shadow: var(--shadow-xl); }

.pdl-pdf-loading {
  position: absolute; inset: 0;
  background: rgba(250,250,248,0.9);
  backdrop-filter: blur(6px);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 16px;
  z-index: 10;
  border-radius: var(--r-lg);
}

.pdl-pdf-loading .spinner-border { color: var(--cr) !important; }

.pdl-pdf-loading p {
  font-family: 'Inter', sans-serif;
  font-size: 0.65rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
}

.pdl-page-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 22px;
  background: var(--bg);
  border: 1px solid var(--border-md);
  border-radius: var(--r-sm);
  color: var(--slate-3);
  font-family: 'Inter', sans-serif;
  font-size: 0.62rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  cursor: pointer;
  transition: all 0.3s;
}
.pdl-page-btn:hover { border-color: var(--cr); color: var(--cr); background: var(--cr-light); }

.pdl-page-indicator {
  padding: 8px 20px;
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: 100px;
  font-family: 'Inter', sans-serif;
  font-size: 0.68rem;
  color: var(--muted);
}
.pdl-page-indicator span { color: var(--slate); font-weight: 500; }

/* ─── RESPONSIVE ────────────────────────────────────────────────────── */
@media (max-width: 1024px) {
  .pdl-wrap { padding: 0 24px; }
  
  /* Hero Responsive */
  .pd-hero { width: 100%; padding: 100px 20px 60px; border-radius: 0; }
  .pd-hero-grid { grid-template-columns: 1fr; gap: 40px; }
  .pd-hero-text { order: 2; text-align: center; }
  .pd-hero-image-container { order: 1; }
  .pd-hero-image-container img { height: 400px; }
  .pd-hero h1 { font-size: 3rem; }
  .pd-hero-desc-wrapper { margin: 0 auto 32px; }
  .pd-hero-ctas { justify-content: center; }

  .pdl-overview { padding: 80px 0; }
  .pdl-overview__grid { grid-template-columns: 1fr; gap: 48px; }
  .pdl-overview__title { font-size: 2.8rem; }
  
  .pdl-features { padding: 80px 0; }
  .pdl-features__head { grid-template-columns: 1fr; gap: 24px; margin-bottom: 48px; }
  .pdl-features__grid { grid-template-columns: 1fr 1fr; }
  
  .pdl-gallery { padding: 80px 0; }
  .pdl-gallery__head { flex-direction: column; align-items: flex-start; gap: 24px; }
  
  .pdl-challenges { padding: 80px 0; }
  .pdl-challenges__title { font-size: 2.8rem; margin-bottom: 48px; }
  
  .pdl-results { padding: 80px 0; }
  .pdl-results__grid { grid-template-columns: repeat(2, 1fr); }
  
  .pdl-related { padding: 80px 0; }
  .pdl-related__grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 768px) {
  .pd-hero h1 { font-size: 2.5rem; }
  .pd-hero-image-container img { height: 320px; }
  .pdl-features__grid { grid-template-columns: 1fr; }
  .pdl-rstat__num { font-size: 3.5rem; }
}

@media (max-width: 600px) {
  .pdl { margin-top: -85px; padding-top: 110px; }
  .pd-hero h1 { font-size: 2.2rem; }
  .pd-hero-desc-content { font-size: 1.1rem; }
  .pd-hero-ctas { flex-direction: column; width: 100%; gap: 12px; }
  .pd-btn { width: 100%; justify-content: center; }
  
  .pdl-overview__title, .pdl-features__title, .pdl-gallery__title, .pdl-challenges__title, .pdl-results__title, .pdl-related__title {
    font-size: 2.2rem;
  }
  
  .pdl-stat-row { padding: 16px; gap: 16px; }
  .pdl-stat-row__icon { width: 32px; height: 32px; }
  
  .pdl-results__grid { grid-template-columns: 1fr; }
  .pdl-rstat { padding: 40px 24px; }
  
  .pdl-filter-bar { width: 100%; overflow-x: auto; white-space: nowrap; padding: 4px; border-radius: 12px; }

  .pdl-tech__row { flex-direction: column; gap: 20px; text-align: center; }
  .pdl-tech__sep { display: none; }
  .pdl-tech__items { justify-content: center; gap: 24px; }
}

.page { background: white; overflow: hidden; }

/* ─── PROJECT DETAILS MODAL ─────────────────────────────────── */
.pdl-details-modal .modal-content {
  border-radius: var(--r-lg);
  border: 1px solid var(--border-md);
  overflow: hidden;
}
.pdl-details-modal .modal-header {
  background: var(--slate);
  padding: 24px 32px;
  border-bottom: none;
}
.pdl-details-modal .modal-title {
  color: #fff;
  font-family: 'Inter', sans-serif;
  font-size: 1.4rem;
  font-weight: 700;
}
.pdl-details-modal .btn-close { filter: invert(1); opacity: 0.7; }
.pdl-details-modal .btn-close:hover { opacity: 1; }
.pdl-details-modal .modal-body { padding: 32px; background: var(--bg); }

.pdl-dm-cat {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 5px 14px;
  border-radius: 100px;
  background: var(--cr-mid);
  color: var(--cr);
  font-family: 'Inter', sans-serif;
  font-size: 0.63rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  margin-bottom: 24px;
}

.pdl-dm-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 20px;
}
@media (max-width: 600px) { .pdl-dm-grid { grid-template-columns: 1fr; } }

.pdl-dm-item {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: var(--r-sm);
  padding: 16px 20px;
  display: flex;
  align-items: flex-start;
  gap: 14px;
}
.pdl-dm-item__icon {
  width: 36px; height: 36px;
  background: var(--cr-light);
  border-radius: var(--r-xs);
  display: flex; align-items: center; justify-content: center;
  color: var(--cr);
  flex-shrink: 0;
}
.pdl-dm-item__lbl {
  font-family: 'Inter', sans-serif;
  font-size: 0.58rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 4px;
}
.pdl-dm-item__val {
  font-size: 0.9rem;
  font-weight: 500;
  color: var(--slate);
  line-height: 1.4;
  word-break: break-word;
}
.pdl-dm-item__val a {
  color: var(--cr);
  text-decoration: none;
}
.pdl-dm-item__val a:hover { text-decoration: underline; }

.pdl-dm-full {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: var(--r-sm);
  padding: 16px 20px;
  margin-bottom: 12px;
}
.pdl-dm-full__lbl {
  font-family: 'Inter', sans-serif;
  font-size: 0.58rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.pdl-dm-full__lbl svg { color: var(--cr); }
.pdl-dm-full__body {
  font-size: 0.9rem;
  color: var(--slate-3);
  line-height: 1.7;
}

.pdl-dm-services {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 4px;
}
.pdl-dm-svc-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  border-radius: 100px;
  background: var(--bg-2);
  border: 1px solid var(--border);
  font-size: 0.8rem;
  color: var(--slate-3);
  text-decoration: none;
  transition: all 0.25s;
}
.pdl-dm-svc-pill:hover {
  background: var(--cr-light);
  border-color: var(--cr);
  color: var(--cr);
}

.pdl-dm-assets {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}
.pdl-dm-asset-badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 6px 16px;
  border-radius: var(--r-xs);
  background: var(--bg-2);
  border: 1px solid var(--border);
  font-size: 0.82rem;
  color: var(--slate-3);
}
.pdl-dm-asset-badge svg { color: var(--cr); }
</style>
@endsection


@section('content')
<div class="pdl">

  {{-- ═══════════════════════════════════════════════════ HERO --}}
  <section class="pd-hero">
                <div class="pd-container">
                    <div class="pd-hero-grid">
                        <div class="pd-hero-text pd-reveal pd-active">
                            <span class="pd-hero-tagline">{{ $project->project_category->name }} </span> <!--Case Study-->
                            <h1>{{ $project->name }}</h1>
                            <div class="pd-hero-desc-wrapper">
                                <p class="pd-hero-desc-content" id="heroDesc">{!! strip_tags($project->description) !!}</p>
                                <button id="heroReadMore" class="pd-hero-read-more" style="display: none;">
                                    <span>Read More</span> <i data-lucide="chevron-down"></i>
                                </button>
                            </div>
                            @if($project->client_name || $project->client_website)
                            <div class="pd-hero-client" style="display:flex; align-items:center; gap:16px; margin-bottom:28px; flex-wrap:wrap;">
                                @if($project->client_name)
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <i data-lucide="building-2" style="width:16px;height:16px;color:var(--cr);flex-shrink:0;"></i>
                                    <span style="font-size:0.92rem; font-weight:600; color:var(--slate);">{{ $project->client_name }}</span>
                                </div>
                                @endif
                                @if($project->client_name && $project->client_website)
                                <span style="width:1px;height:18px;background:var(--border-md);display:inline-block;"></span>
                                @endif
                                @if($project->client_website)
                                <a href="{{ $project->client_website }}" target="_blank" rel="noopener" style="display:flex; align-items:center; gap:6px; font-size:0.88rem; color:var(--cr); text-decoration:none; font-weight:500;">
                                    <i data-lucide="external-link" style="width:14px;height:14px;flex-shrink:0;"></i>
                                    {{ preg_replace('#^https?://(www\.)?#i', '', rtrim($project->client_website, '/')) }}
                                </a>
                                @endif
                            </div>
                            @endif
                            @if($project->projects_images->count() > 0 || $project->projects_videos->count() > 0 || $project->projects_documents->count() > 0)
                            <div class="pd-hero-ctas" style="display: flex; gap: 15px; margin-top: 0; flex-wrap: wrap;">
                                <a href="#gallery" class="pd-btn pd-btn-primary">
                                    <i data-lucide="layout-grid" style="width:17px;height:17px;"></i>
                                    <span>View Assets</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="pd-hero-image-container pd-reveal pd-active pd-reveal-delay-1">
                            @php $heroImages = $project->projects_images; @endphp
                            @if($heroImages->count() > 1)
                                {{-- Multi-image slider --}}
                                <div class="pd-hero-slider" id="heroSlider">
                                    @foreach($heroImages as $i => $img)
                                    <div class="pd-hero-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                                        <img src="{{ asset('public/' . $img->image) }}" alt="{{ $project->name }} — {{ $i + 1 }}">
                                    </div>
                                    @endforeach
                                </div>
                                <button class="pd-hslider-btn pd-hslider-prev" id="heroPrev" aria-label="Previous image">
                                    <i data-lucide="chevron-left" style="width:18px;height:18px;"></i>
                                </button>
                                <button class="pd-hslider-btn pd-hslider-next" id="heroNext" aria-label="Next image">
                                    <i data-lucide="chevron-right" style="width:18px;height:18px;"></i>
                                </button>
                                <div class="pd-hslider-dots" id="heroDots">
                                    @foreach($heroImages as $i => $img)
                                    <button class="pd-hslider-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" aria-label="Go to image {{ $i + 1 }}"></button>
                                    @endforeach
                                </div>
                                <div class="pd-hslider-counter">
                                    <span id="heroSliderCurrent">1</span>&thinsp;/&thinsp;{{ $heroImages->count() }}
                                </div>
                            @elseif($heroImages->count() === 1)
                                <img src="{{ asset('public/' . $heroImages[0]->image) }}" alt="{{ $project->name }}">
                            @else
                                <img src="{{ asset('public/front-new/assets/images/section-3-1st-image.jpg') }}" alt="{{ $project->name }}">
                            @endif
                        </div>
                    </div>
                </div>
            </section>
  
  <!-- <section class="pdl-hero">

    {{-- LEFT --}}
    <div class="pdl-hero__left" data-letter="{{ substr($project->name, 0, 1) }}">
      <div class="pdl-hero__content">

        <div class="pdl-eyebrow pdl-reveal on">
          <span class="pdl-eyebrow__tick"></span>
          <span class="pdl-eyebrow__text">{{ $project->project_category->name }} — Case Study</span>
        </div>

        <h1 class="pdl-hero__title pdl-reveal on d1">
          {{ $project->name }}
        </h1>

        <p class="pdl-hero__desc pdl-reveal on d2" id="heroDesc">{!! strip_tags($project->description) !!}</p>
        <button id="heroReadMore" class="pdl-readmore pdl-reveal on d2" style="display:none;">
          <span>Read more</span>
          <i data-lucide="chevron-down" style="width:13px;"></i>
        </button>

        <div class="pdl-hero__ctas pdl-reveal on d3">
          <a href="#gallery" class="pdl-btn pdl-btn-primary">
            <i data-lucide="grid" style="width:14px;"></i> View Assets
          </a>
          
        </div>

      </div>
    </div>

    {{-- RIGHT --}}
    <div class="pdl-hero__right">
      @if(isset($project->projects_images[0]))
        <img class="pdl-hero__img" src="{{ asset('public/'.$project->projects_images[0]->image) }}" alt="{{ $project->name }}">
      @else
        <img class="pdl-hero__img" src="{{ asset('public/front-new/assets/images/section-3-1st-image.jpg') }}" alt="Project">
      @endif
      <div class="pdl-hero__overlay"></div>

      <div class="pdl-hero__meta pdl-reveal on d4">
        <div class="pdl-hero__meta-item">
          <div class="pdl-meta-lbl">Sector</div>
          <div class="pdl-meta-val">{{ $project->project_category->name }}</div>
        </div>
        <div class="pdl-hero__meta-item">
          <div class="pdl-meta-lbl">Status</div>
          <div class="pdl-meta-val">Live &amp; Scaled</div>
        </div>
        <div class="pdl-hero__meta-item">
          <div class="pdl-meta-lbl">Client</div>
          <div class="pdl-meta-val">Healthcare</div>
        </div>
      </div>
    </div>

  </section> -->

  {{-- ════════════════════════════════════════════════ OVERVIEW --}}
  <section id="overview" class="pdl-overview">
    <div class="pdl-wrap">
      <div class="pdl-overview__grid">
        <div class="pdl-reveal">
          <div class="pdl-eyebrow">
            <span class="pdl-eyebrow__tick"></span>
            <span class="pdl-eyebrow__text">01 — Overview</span>
          </div>
          <h2 class="pdl-overview__title">{{ $project->name }}</h2>
          <div class="pdl-overview__body">{!! $project->description !!}</div>
        </div>
        <div class="pdl-reveal d2">
          <div class="pdl-stat-list">
            <div class="pdl-stat-row">
              <div class="pdl-stat-row__icon"><i data-lucide="target" style="width:18px;"></i></div>
              <div>
                <div class="pdl-stat-row__label">Sector</div>
                <div class="pdl-stat-row__val">{{ $project->project_category->name }}</div>
              </div>
            </div>
            @if($project->client_name)
            <div class="pdl-stat-row">
              <div class="pdl-stat-row__icon"><i data-lucide="building-2" style="width:18px;"></i></div>
              <div>
                <div class="pdl-stat-row__label">Client</div>
                <div class="pdl-stat-row__val">{{ $project->client_name }}</div>
              </div>
            </div>
            @endif
            @if($project->project_location)
            <div class="pdl-stat-row">
              <div class="pdl-stat-row__icon"><i data-lucide="map-pin" style="width:18px;"></i></div>
              <div>
                <div class="pdl-stat-row__label">Location</div>
                <div class="pdl-stat-row__val">{{ $project->project_location }}</div>
              </div>
            </div>
            @endif
            @if($project->project_duration)
            <div class="pdl-stat-row">
              <div class="pdl-stat-row__icon"><i data-lucide="clock" style="width:18px;"></i></div>
              <div>
                <div class="pdl-stat-row__label">Duration</div>
                <div class="pdl-stat-row__val">{{ $project->project_duration }}</div>
              </div>
            </div>
            @endif
            @if($project->regulatory_authority)
            <div class="pdl-stat-row">
              <div class="pdl-stat-row__icon"><i data-lucide="shield-check" style="width:18px;"></i></div>
              <div>
                <div class="pdl-stat-row__label">Regulatory Authority</div>
                <div class="pdl-stat-row__val">{{ $project->regulatory_authority }}</div>
              </div>
            </div>
            @endif
          </div>

          @if(isset($projectServices) && $projectServices->count() > 0)
          <div class="pdl-svc-block">
            <div class="pdl-svc-block__lbl">
              <i data-lucide="briefcase" style="width:13px;height:13px;"></i>
              Services Delivered
            </div>
            <div class="pdl-svc-pills">
              @foreach($projectServices as $svc)
              <a href="{{ route('front.service', $svc->slug) }}" class="pdl-svc-pill">
                <i data-lucide="arrow-right" style="width:12px;height:12px;"></i>
                {{ $svc->name }}
              </a>
              @endforeach
            </div>
          </div>
          @endif

        </div>
      </div>
    </div>
  </section>

  {{-- ════════════════════════════════════════════════ GALLERY --}}
  @if($project->projects_images->count() > 0 || $project->projects_videos->count() > 0 || $project->projects_documents->count() > 0)
  <section id="gallery" class="pdl-gallery">
    <div class="pdl-wrap">
      <div class="pdl-gallery__head">
        <div class="pdl-reveal">
          <div class="pdl-eyebrow">
            <span class="pdl-eyebrow__tick"></span>
            <span class="pdl-eyebrow__text">03 — Assets</span>
          </div>
          <h2 class="pdl-gallery__title">Project<br><em>Gallery</em></h2>
        </div>
        <div class="pdl-filter-bar pdl-reveal d1">
          <button class="pdl-filter-btn active" data-filter="all">All <span style="opacity:0.6;font-size:0.56rem;margin-left:2px;">{{ $project->projects_images->count() + $project->projects_videos->count() + $project->projects_documents->count() }}</span></button>
          @if($project->projects_images->count() > 0)
          <button class="pdl-filter-btn" data-filter="images">Images</button>
          @endif
          @if($project->projects_videos->count() > 0)
          <button class="pdl-filter-btn" data-filter="videos">Videos</button>
          @endif
          @if($project->projects_documents->count() > 0)
          <button class="pdl-filter-btn" data-filter="docs">Docs</button>
          @endif
        </div>
      </div>

      <div class="pdl-gallery__grid">

        @forelse($project->projects_images as $index => $image)
        <div class="pdl-gcard pdl-reveal" data-category="images"
             onclick="openGalleryModal('image','{{ asset('public/'.$image->image) }}','Project Visual Asset')">
          <span class="pdl-gcard__badge">Image</span>
          <span class="pdl-gcard__type"><i data-lucide="image" style="width:15px;"></i></span>
          <div class="pdl-gcard__media"><img src="{{ asset('public/'.$image->image) }}" alt="Image {{ $index+1 }}" loading="lazy"></div>
          <div class="pdl-gcard__overlay">
            <div class="pdl-gcard__info"><h4>Visual Asset</h4><p>Snapshot {{ $index+1 }}</p></div>
          </div>
        </div>
        @empty
        @endforelse

        @foreach($project->projects_videos as $video)
        <div class="pdl-gcard pdl-reveal" data-category="videos"
             onclick="openGalleryModal('video','{{ asset('public/'.$video->video) }}','{{ $video->title ?? 'Project Video' }}')">
          <span class="pdl-gcard__badge" style="color:#4f8ef7;">Video</span>
          <span class="pdl-gcard__type"><i data-lucide="play-circle" style="width:15px;"></i></span>
          <div class="pdl-gcard__media">
            <img src="{{ $video->thumbnail ? asset('public/'.$video->thumbnail) : ($project->projects_images->count() > 0 ? asset('public/'.$project->projects_images[0]->image) : asset('public/front-new/assets/images/section-3-1st-image.jpg')) }}" alt="Thumb">
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(15,25,35,0.3);">
              <div style="width:56px;height:56px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow-lg);">
                <i data-lucide="play" style="color:var(--cr);fill:var(--cr);width:22px;margin-left:3px;"></i>
              </div>
            </div>
          </div>
          <div class="pdl-gcard__overlay">
            <div class="pdl-gcard__info"><h4>{{ $video->title ?? 'Project Video' }}</h4><p>Click to watch</p></div>
          </div>
        </div>
        @endforeach

        @foreach($project->projects_documents as $document)
        @php $isPdf = strtolower(pathinfo($document->document, PATHINFO_EXTENSION)) === 'pdf'; @endphp
        <div class="pdl-gcard pdl-reveal" data-category="docs"
             onclick="openFlipbook('{{ asset('public/'.$document->document) }}','{{ $document->title ?? 'Project Document' }}')">
          <span class="pdl-gcard__badge">{{ strtoupper(pathinfo($document->document, PATHINFO_EXTENSION)) }}</span>
          <span class="pdl-gcard__type"><i data-lucide="{{ $isPdf ? 'book-open' : 'file-text' }}" style="width:15px;"></i></span>
          <div class="pdl-gcard__media">
            <div class="pdl-doc-thumb">
              <i data-lucide="{{ $isPdf ? 'book-open' : 'file-text' }}"></i>
              <span>{{ $document->title ?? 'Document' }}</span>
            </div>
          </div>
          <div class="pdl-gcard__overlay">
            <div class="pdl-gcard__info">
              <h4>{{ $document->title ?? 'Document' }}</h4>
              <p>{{ $isPdf ? 'View Flipbook' : 'Download' }}</p>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>

    {{-- Media Modal --}}
    <div class="modal fade" id="galleryModal" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content pdl-modal">
          <div class="modal-header">
            <div style="display:flex;align-items:center;gap:14px;">
              <div class="pdl-modal-icon" id="modalTypeIconWrap"><i data-lucide="image" style="width:17px;"></i></div>
              <h5 class="modal-title" id="galleryTitle">Preview</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-4" style="min-height:180px;display:flex;align-items:center;justify-content:center;">
            <img id="modalImage" class="d-none" />
            <video id="modalVideo" class="d-none" controls><source id="modalVideoSource"></video>
          </div>
        </div>
      </div>
    </div>

    {{-- PDF Modal --}}
    <div class="modal fade" id="pdfModal" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content pdl-modal">
          <div class="modal-header">
            <div style="display:flex;align-items:center;gap:14px;">
              <div class="pdl-modal-icon"><i data-lucide="book-open" style="width:17px;"></i></div>
              <div>
                <h5 class="modal-title" id="pdfTitle">Document Preview</h5>
                <small style="font-family:'Roboto Mono',monospace;font-size:0.6rem;letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);" id="pdfPageCounter">Preparing…</small>
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-0" style="position:relative;">
            <div id="pdfLoading" class="pdl-pdf-loading">
              <div class="spinner-border" role="status" style="width:2.4rem;height:2.4rem;"></div>
              <p>Rendering pages…</p>
            </div>
            <div id="flipbookContainer"><div id="flipbook"></div></div>
          </div>
          <div class="modal-footer">
            <button class="pdl-page-btn" onclick="prevPage()">
              <i data-lucide="chevron-left" style="width:13px;"></i> Prev
            </button>
            <div class="pdl-page-indicator">
              <span id="currentPage">1</span> / <span id="totalPages">1</span>
            </div>
            <button class="pdl-page-btn" onclick="nextPage()">
              Next <i data-lucide="chevron-right" style="width:13px;"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

  </section>
  @endif

  {{-- Project Description Modal (always in DOM for Read More button) --}}
  <div class="modal fade" id="descriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content pdl-modal">
        <div class="modal-header">
          <div style="display:flex;align-items:center;gap:14px;">
            <div class="pdl-modal-icon"><i data-lucide="file-text" style="width:17px;"></i></div>
            <h5 class="modal-title">Project Overview</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="pdl-overview__body" style="font-size: 1rem; line-height: 1.8;">
            {!! $project->description !!}
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════ CHALLENGES --}}
  @php
    $challengeItems = $project->challenges ?? [];
    if (!is_array($challengeItems) && $challengeItems) {
        $challengeItems = json_decode($challengeItems, true) ?: [];
    }
    $hasChallenges = $project->challenge_heading || !empty($challengeItems) || $project->challenge_title || $project->challenge || $project->resolution;
  @endphp
  @if($hasChallenges)
  <section class="pdl-challenges">
    <div class="pdl-wrap">
      <div class="pdl-reveal">
        @if($project->challenge_heading)
        <h2 class="pdl-challenges__title">{!! nl2br(e($project->challenge_heading)) !!}</h2>
        @endif
      </div>

      @if (!empty($challengeItems))
        <div class="pdl-carousel-wrap">
          <button class="pdl-carousel-btn pdl-carousel-btn--prev" id="challengePrev" aria-label="Previous challenge">
            <i data-lucide="chevron-left" style="width:20px;"></i>
          </button>
          <div class="pdl-carousel">
            <div class="pdl-carousel-inner" id="challengeSlider" style="transform: translateX(0);">
              @foreach ($challengeItems as $item)
                <div class="pdl-ccard-slider">
                  <div class="pdl-ccard pdl-ccard--problem pdl-reveal">
                    <div class="pdl-ccard__top-bar"></div>
                    <h3>{{ $item['challenge_title'] ?? 'Project Challenge' }}</h3>
                    <p>{!! $item['challenge'] ?? '' !!}</p>
                    <div class="pdl-ccard__rule"></div>
                    <p>{!! $item['resolution'] ?? '' !!}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
          <button class="pdl-carousel-btn pdl-carousel-btn--next" id="challengeNext" aria-label="Next challenge">
            <i data-lucide="chevron-right" style="width:20px;"></i>
          </button>
        </div>
        @if (count($challengeItems) > 2)
          <div class="pdl-carousel-indicators">
            @for ($i = 0; $i < count($challengeItems); $i++)
              <div class="pdl-carousel-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></div>
            @endfor
          </div>
        @endif
      @elseif ($project->challenge_title || $project->challenge || $project->resolution)
        <div class="pdl-challenges__grid">
          <div class="pdl-ccard pdl-ccard--problem pdl-reveal">
            <div class="pdl-ccard__top-bar"></div>
            <h3>{{ $project->challenge_title ?? 'Project Challenge' }}</h3>
            <p>{!! $project->challenge ?? '' !!}</p>
            <div class="pdl-ccard__rule"></div>
            <p>{!! $project->resolution ?? '' !!}</p>
          </div>
        </div>
      @endif
    </div>
  </section>
  @endif

  {{-- ══════════════════════════════════════════════ RELATED --}}
  @if($relatedProjects->count() > 0)
  <section class="pdl-related">
    <div class="pdl-wrap">
      <div class="pdl-related__head pdl-reveal">
        <div>
          <div class="pdl-eyebrow">
            <span class="pdl-eyebrow__tick"></span>
            <span class="pdl-eyebrow__text">06 — Portfolio</span>
          </div>
          <h2 class="pdl-related__title">More<br><em>Case Studies</em></h2>
        </div>
      </div>
      <div class="pdl-related__grid">
        @foreach($relatedProjects as $rp)
        <a href="{{ route('front.project_details', $rp->slug) }}" class="pdl-rcard pdl-reveal">
          @if(isset($rp->projects_images[0]))
            <img src="{{ asset('public/'.$rp->projects_images[0]->image) }}" alt="{{ $rp->name }}">
          @else
            <img src="{{ asset('public/front-new/assets/images/section-3-2nd-image.jpg') }}" alt="Project">
          @endif
          <div class="pdl-rcard__overlay">
            <span class="pdl-rcard__cat">{{ $rp->project_category->name ?? 'Project' }}</span>
            <span class="pdl-rcard__name">{{ $rp->name }}</span>
          </div>
          <div class="pdl-rcard__arrow"><i data-lucide="arrow-up-right" style="width:15px;"></i></div>
        </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ══════════════════════════════════════ PROJECT DETAILS MODAL --}}
  <div class="modal fade pdl-details-modal" id="projectDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title">Project Details</h5>
            <div style="font-family:'Roboto Mono',monospace;font-size:0.6rem;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.45);margin-top:4px;">
              {{ $project->name }}
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          {{-- Category tag --}}
          <div class="pdl-dm-cat">
            <i data-lucide="tag" style="width:11px;"></i>
            {{ $project->project_category->name }}
          </div>

          {{-- 2-column meta grid --}}
          <div class="pdl-dm-grid">
            @if($project->client_name)
            <div class="pdl-dm-item">
              <div class="pdl-dm-item__icon"><i data-lucide="building-2" style="width:16px;"></i></div>
              <div>
                <div class="pdl-dm-item__lbl">Client</div>
                <div class="pdl-dm-item__val">{{ $project->client_name }}</div>
              </div>
            </div>
            @endif

            @if($project->project_location)
            <div class="pdl-dm-item">
              <div class="pdl-dm-item__icon"><i data-lucide="map-pin" style="width:16px;"></i></div>
              <div>
                <div class="pdl-dm-item__lbl">Location</div>
                <div class="pdl-dm-item__val">{{ $project->project_location }}</div>
              </div>
            </div>
            @endif

            @if($project->project_duration)
            <div class="pdl-dm-item">
              <div class="pdl-dm-item__icon"><i data-lucide="clock" style="width:16px;"></i></div>
              <div>
                <div class="pdl-dm-item__lbl">Duration</div>
                <div class="pdl-dm-item__val">{{ $project->project_duration }}</div>
              </div>
            </div>
            @endif

            @if($project->regulatory_authority)
            <div class="pdl-dm-item">
              <div class="pdl-dm-item__icon"><i data-lucide="shield-check" style="width:16px;"></i></div>
              <div>
                <div class="pdl-dm-item__lbl">Regulatory Authority</div>
                <div class="pdl-dm-item__val">{{ $project->regulatory_authority }}</div>
              </div>
            </div>
            @endif

            @if($project->client_website)
            <div class="pdl-dm-item">
              <div class="pdl-dm-item__icon"><i data-lucide="globe" style="width:16px;"></i></div>
              <div>
                <div class="pdl-dm-item__lbl">Client Website</div>
                <div class="pdl-dm-item__val">
                  <a href="{{ $project->client_website }}" target="_blank" rel="noopener">
                    {{ preg_replace('#^https?://#', '', $project->client_website) }}
                    <i data-lucide="external-link" style="width:11px;vertical-align:middle;"></i>
                  </a>
                </div>
              </div>
            </div>
            @endif

            {{-- Assets summary --}}
            @php $totalAssets = $project->projects_images->count() + $project->projects_videos->count() + $project->projects_documents->count(); @endphp
            @if($totalAssets > 0)
            <div class="pdl-dm-item">
              <div class="pdl-dm-item__icon"><i data-lucide="folder-open" style="width:16px;"></i></div>
              <div>
                <div class="pdl-dm-item__lbl">Project Assets</div>
                <div class="pdl-dm-item__val">
                  <div class="pdl-dm-assets">
                    @if($project->projects_images->count() > 0)
                    <span class="pdl-dm-asset-badge">
                      <i data-lucide="image" style="width:13px;"></i>
                      {{ $project->projects_images->count() }} Image{{ $project->projects_images->count() > 1 ? 's' : '' }}
                    </span>
                    @endif
                    @if($project->projects_videos->count() > 0)
                    <span class="pdl-dm-asset-badge">
                      <i data-lucide="video" style="width:13px;"></i>
                      {{ $project->projects_videos->count() }} Video{{ $project->projects_videos->count() > 1 ? 's' : '' }}
                    </span>
                    @endif
                    @if($project->projects_documents->count() > 0)
                    <span class="pdl-dm-asset-badge">
                      <i data-lucide="file-text" style="width:13px;"></i>
                      {{ $project->projects_documents->count() }} Document{{ $project->projects_documents->count() > 1 ? 's' : '' }}
                    </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>

          {{-- Project Scope (full-width) --}}
          @if($project->project_scope)
          <div class="pdl-dm-full">
            <div class="pdl-dm-full__lbl">
              <i data-lucide="layout-list" style="width:13px;"></i> Project Scope
            </div>
            <div class="pdl-dm-full__body">{{ $project->project_scope }}</div>
          </div>
          @endif

          {{-- Related Services --}}
          @if($projectServices->count() > 0)
          <div class="pdl-dm-full">
            <div class="pdl-dm-full__lbl">
              <i data-lucide="layers" style="width:13px;"></i> Services Involved
            </div>
            <div class="pdl-dm-services">
              @foreach($projectServices as $svc)
              <a href="{{ route('front.service', $svc->slug) }}" class="pdl-dm-svc-pill">
                <i data-lucide="arrow-right" style="width:11px;"></i>
                {{ $svc->name }}
              </a>
              @endforeach
            </div>
          </div>
          @endif

        </div>
      </div>
    </div>
  </div>

</div><!-- /pdl -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  /* ── Icons ─────────────────────────────────────── */
  if (typeof lucide !== 'undefined') lucide.createIcons();

  /* ── Scroll reveal ─────────────────────────────── */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('on'); });
  }, { threshold: 0, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.pdl-reveal').forEach(el => io.observe(el));

  /* ── Read More ─────────────────────────────────── */
  const desc = document.getElementById('heroDesc');
  const btn  = document.getElementById('heroReadMore');
  const descModalEl = document.getElementById('descriptionModal');
  const descModal = descModalEl ? new bootstrap.Modal(descModalEl) : null;
  if (desc && btn && descModal) {
    const check = () => btn.style.display = desc.scrollHeight > desc.clientHeight + 2 ? 'inline-flex' : 'none';
    check(); window.addEventListener('resize', check);
    btn.addEventListener('click', () => descModal.show());
  }

  /* ── Hero Image Slider ─────────────────────────── */
  const heroSlider = document.getElementById('heroSlider');
  if (heroSlider) {
    const hSlides = heroSlider.querySelectorAll('.pd-hero-slide');
    const hDots   = document.querySelectorAll('#heroDots .pd-hslider-dot');
    const hCount  = document.getElementById('heroSliderCurrent');
    const hPrev   = document.getElementById('heroPrev');
    const hNext   = document.getElementById('heroNext');
    let hCurrent = 0, hTimer;

    const hGoTo = (idx) => {
      hSlides[hCurrent].classList.remove('active');
      hDots[hCurrent].classList.remove('active');
      hCurrent = (idx + hSlides.length) % hSlides.length;
      hSlides[hCurrent].classList.add('active');
      hDots[hCurrent].classList.add('active');
      if (hCount) hCount.textContent = hCurrent + 1;
    };

    const hStartAuto = () => {
      clearInterval(hTimer);
      hTimer = setInterval(() => hGoTo(hCurrent + 1), 5000);
    };

    hPrev?.addEventListener('click', () => { hGoTo(hCurrent - 1); hStartAuto(); });
    hNext?.addEventListener('click', () => { hGoTo(hCurrent + 1); hStartAuto(); });
    hDots.forEach((dot, i) => dot.addEventListener('click', () => { hGoTo(i); hStartAuto(); }));

    /* pause on hover, resume on leave */
    heroSlider.closest('.pd-hero-image-container')?.addEventListener('mouseenter', () => clearInterval(hTimer));
    heroSlider.closest('.pd-hero-image-container')?.addEventListener('mouseleave', hStartAuto);

    /* touch swipe support */
    let hTouchX = 0;
    heroSlider.addEventListener('touchstart', e => { hTouchX = e.touches[0].clientX; }, { passive: true });
    heroSlider.addEventListener('touchend', e => {
      const dx = e.changedTouches[0].clientX - hTouchX;
      if (Math.abs(dx) > 40) { dx < 0 ? hGoTo(hCurrent + 1) : hGoTo(hCurrent - 1); hStartAuto(); }
    }, { passive: true });

    hStartAuto();
  }

  /* ── Gallery Filter ────────────────────────────── */
  document.querySelectorAll('.pdl-filter-btn').forEach(b => {
    b.addEventListener('click', () => {
      document.querySelectorAll('.pdl-filter-btn').forEach(x => x.classList.remove('active'));
      b.classList.add('active');
      const f = b.getAttribute('data-filter');
      document.querySelectorAll('.pdl-gcard').forEach(c => {
        if (f === 'all' || f === c.getAttribute('data-category')) {
          c.style.display = '';
          setTimeout(() => { c.style.opacity = '1'; c.style.transform = ''; }, 10);
        } else {
          c.style.opacity = '0'; c.style.transform = 'scale(0.92)';
          setTimeout(() => { c.style.display = 'none'; }, 380);
        }
      });
    });
  });

  /* ── Challenge Carousel ────────────────────────── */
  const slider = document.getElementById('challengeSlider');
  const prevBtn = document.getElementById('challengePrev');
  const nextBtn = document.getElementById('challengeNext');
  const dots = document.querySelectorAll('.pdl-carousel-dot');

  if (slider && prevBtn && nextBtn) {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.pdl-ccard-slider');
    const totalSlides = slides.length;
    const isMobile = () => window.innerWidth <= 768;

    const updateCarousel = () => {
      if (isMobile()) {
        slider.style.transform = 'none';
        prevBtn.disabled = true;
        nextBtn.disabled = true;
        return;
      }
      const maxSlide = Math.max(0, totalSlides - 2);
      if (currentSlide > maxSlide) currentSlide = maxSlide;
      slider.style.transform = `translateX(${-currentSlide * (50 + 1.2)}%)`;
      prevBtn.disabled = currentSlide === 0;
      nextBtn.disabled = currentSlide >= maxSlide;
      dots.forEach((dot, i) => dot.classList.toggle('active', i === currentSlide));
    };

    prevBtn.addEventListener('click', () => {
      if (!isMobile() && currentSlide > 0) { currentSlide--; updateCarousel(); }
    });
    nextBtn.addEventListener('click', () => {
      const maxSlide = Math.max(0, totalSlides - 2);
      if (!isMobile() && currentSlide < maxSlide) { currentSlide++; updateCarousel(); }
    });
    dots.forEach((dot, i) => dot.addEventListener('click', () => {
      if (!isMobile()) { currentSlide = i; updateCarousel(); }
    }));

    /* sync dots with native mobile scroll */
    slider.addEventListener('scroll', () => {
      if (!isMobile()) return;
      const slideW = slides[0] ? slides[0].offsetWidth + 16 : 1;
      const idx = Math.round(slider.scrollLeft / slideW);
      dots.forEach((dot, i) => dot.classList.toggle('active', i === idx));
    });

    window.addEventListener('resize', () => { currentSlide = 0; updateCarousel(); });
    updateCarousel();
  }
});

/* ── Gallery Modal ───────────────────────────────── */
let galleryModal = null, pdfModalInst = null;

function openGalleryModal(type, url, title) {
  const el = document.getElementById('galleryModal');
  if (!el) return;
  if (!galleryModal) galleryModal = new bootstrap.Modal(el);
  $('#modalImage,#modalVideo').addClass('d-none');
  $('#galleryTitle').text(title);
  const wrap = document.getElementById('modalTypeIconWrap');
  if (type === 'image') {
    $('#modalImage').attr('src', url).removeClass('d-none');
    if (wrap) wrap.innerHTML = '<i data-lucide="image" style="width:17px;"></i>';
  } else {
    const vs = document.getElementById('modalVideoSource');
    const vv = document.getElementById('modalVideo');
    if (vs) vs.src = url;
    if (vv) vv.load();
    $('#modalVideo').removeClass('d-none');
    if (wrap) wrap.innerHTML = '<i data-lucide="play-circle" style="width:17px;"></i>';
  }
  if (typeof lucide !== 'undefined') lucide.createIcons();
  galleryModal.show();
}

$('#galleryModal').on('hidden.bs.modal', () => {
  const v = document.getElementById('modalVideo');
  if (v) { v.pause(); v.currentTime = 0; }
});

/* ── PDF Flipbook ────────────────────────────────── */
let pageFlip = null, loadId = 0;
if (typeof pdfjsLib !== 'undefined') {
  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
}

async function openFlipbook(url, title) {
  loadId++;
  const lid = loadId;
  const el = document.getElementById('pdfModal');
  if (!el) return;
  if (!pdfModalInst) pdfModalInst = new bootstrap.Modal(el);
  $('#pdfTitle').text(title);
  $('#pdfLoading').show(); $('#pdfPageCounter').text('Preparing…');
  $('#totalPages').text('…'); $('#currentPage').text('1');
  $('#flipbook').remove(); $('#flipbookContainer').append('<div id="flipbook"></div>');
  pdfModalInst.show();
  try {
    const pdf = await pdfjsLib.getDocument(url).promise;
    if (lid !== loadId) return;
    const total = pdf.numPages; $('#totalPages').text(total);
    const fb = document.getElementById('flipbook');
    for (let i = 1; i <= total; i++) {
      if (lid !== loadId) return;
      const page = await pdf.getPage(i);
      const vp = page.getViewport({ scale: 1.5 });
      const c = document.createElement('canvas');
      c.width = vp.width; c.height = vp.height;
      await page.render({ canvasContext: c.getContext('2d'), viewport: vp }).promise;
      const d = document.createElement('div'); d.className = 'page'; d.appendChild(c); fb.appendChild(d);
    }
    if (lid !== loadId) return;
    $(el).one('shown.bs.modal', () => {
      if (lid !== loadId) return;
      if (pageFlip) pageFlip.destroy();
      const PageFlipClass = (typeof St !== 'undefined') ? St.PageFlip : null;
      if (!PageFlipClass) { $('#pdfPageCounter').text('Viewer unavailable'); $('#pdfLoading').hide(); return; }
      pageFlip = new PageFlipClass(fb, {
        width: 450, height: 600, size: 'stretch',
        minWidth: 200, maxWidth: 1000, minHeight: 300, maxHeight: 1200,
        maxShadowOpacity: 0.3, showCover: true, mobileScrollSupport: false,
        usePortrait: window.innerWidth < 768
      });
      pageFlip.loadFromHTML(fb.querySelectorAll('.page'));
      pageFlip.on('flip', e => $('#currentPage').text(e.data + 1));
      if (typeof lucide !== 'undefined') lucide.createIcons();
      $('#pdfLoading').fadeOut(300); $('#pdfPageCounter').text(total + ' pages');
    });
    if ($(el).hasClass('show')) $(el).trigger('shown.bs.modal');
  } catch(e) {
    console.error(e); $('#pdfPageCounter').text('Load failed'); $('#pdfLoading').hide();
  }
}

function nextPage() { if (pageFlip) pageFlip.flipNext(); }
function prevPage() { if (pageFlip) pageFlip.flipPrev(); }

$('#pdfModal').on('hidden.bs.modal', () => {
  loadId++;
  if (pageFlip) { pageFlip.destroy(); pageFlip = null; }
  $('#flipbook').remove(); $('#flipbookContainer').append('<div id="flipbook"></div>');
});
</script>
@endsection