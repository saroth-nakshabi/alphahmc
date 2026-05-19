@extends('front/layout-2')

@push('page_title', 'Contact Us | Alpha Health Group')

@section('meta_description')Get in touch with Alpha Health Group for healthcare consultancy services, DOH compliance support, and accreditation assistance for healthcare facilities across the UAE.@endsection

@section('content')
    <!-- Professional Contact System -->

    <style>
        :root {
            --primary-accent: #066D77;
            --primary-dark: #066D88;
            --secondary-gold: #ff9d00;
            --text-main: #009095;
            --text-gray: #64748b;
        }

        .contact-page-wrapper {
            background-color: #fff;
            color: #333;
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        /* Hero Header Refinement */
        .contact-hero {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('public/front-new/assets/images/section-3-2nd-image.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 200px 0 160px; /* Increased bottom padding */
            color: #fff;
            position: relative;
        }

        .contact-hero .display-large {
            font-family: 'Libre Baskerville', serif;
            font-size: clamp(3.2rem, 6vw, 5.5rem);
            line-height: 1.1;
            margin-bottom: 25px;
            font-weight: 700;
        }

        /* Form Styling Refined */
        .contact-form-container {
            background: #ffffff;
            padding: 60px; /* Increased internal padding */
            border-radius: 24px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.08);
            margin-top: -80px; /* Adjusted overlap */
            position: relative;
            z-index: 10;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .contact-heading-main {
            font-family: 'Libre Baskerville', serif;
            color: #003d66;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }

        .contact-heading-main::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 3px;
            background: #066D77;
            border-radius: 2px;
        }

        .form-label {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
            color: #507b96;
            margin-bottom: 14px;
            display: block;
        }

        .form-control {
            border: 1px solid #edf2f7;
            padding: 18px 24px;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            transition: all 0.4s ease;
            background: #f8fafc;
            font-size: 1rem;
            color: #2d3748;
        }

        .form-control:focus {
            background: #fff;
            border-color: #ff9d00;
            box-shadow: 0 10px 25px rgba(255,157,0,0.1);
            outline: none;
        }

        /* Contact Details Cards Refinement */
        .office-card {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(0,61,102,0.05);
            margin-bottom: 30px;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .office-card:hover {
            border-color: #066D77;
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.05);
        }

        .office-title {
            color: #003d66;
            font-weight: 800;
            font-size: 1.4rem;
            margin-bottom: 25px;
            font-family: 'Libre Baskerville', serif;
        }

        .office-info-item {
            display: flex;
            gap: 18px;
            margin-bottom: 18px;
            color: #4a5568;
            font-size: 1rem;
            line-height: 1.6;
        }

        .office-info-item i {
            color: #066D77;
            margin-top: 5px;
            font-size: 1.1rem;
            width: 20px;
        }

        /* Global Reach - Maps */
        .map-section {
            padding: 100px 0;
            background: #f8fafc;
        }

        .map-frame {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            height: 400px;

            transition: 0.5s;
        }
        .map-frame:hover { filter: grayscale(0); }

        .btn-gold-submit {
            background: linear-gradient(to right, #066D77, #009095);
            color: #000;
            border: none;
            padding: 22px 45px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 15px 35px rgba(6,109,119,0.25);
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .btn-gold-submit:hover {
            background: #003d66;
            color: #fff;
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 45px rgba(0, 61, 102, 0.3);
        }

        /* Get in Touch Split Section */
        .touch-split-section {
            display: flex;
            min-height: 600px;
            width: 100%;
        }

        .touch-left {
            width: 50%;
            background: linear-gradient(rgba(255,255,255,0.92), rgba(255,255,255,0.92)), url('{{ asset('public/front-new/assets/images/about/staff-bg.png') }}');
            background-size: cover;
            background-position: center;
            padding: 80px 8%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .touch-right {
            width: 50%;
            background: #066D77;
            padding: 80px 8%;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-height: 1000px;
        }

        .touch-left h2 {
            color: #066D77;
            font-family: 'Libre Baskerville', serif;
            font-size: 2.8rem;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .touch-left p {
            color: #4a5568;
            font-size: 1.15rem;
            line-height: 1.7;
            margin-bottom: 25px;
            max-width: 500px;
        }

        .btn-touch-orange {
            background: #e6e6e6;
            color: #000000 !important;
            padding: 14px 40px;
            border-radius: 4px;
            text-transform: none;
            font-weight: 600;
            margin-bottom: 15px;
            border: none;
            width: fit-content;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(230, 230, 230, 0.3);
        }

        .btn-touch-orange:hover {
            background: #000000;
            color: #e6e6e6 !important;
            transform: translateX(10px);
            box-shadow: 0 10px 20px rgba(230, 230, 230, 0.2);
        }

        .office-label {
            color: #ff9d00;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.9rem;
            margin-bottom: 12px;
            margin-top: 35px;
        }

        .office-label:first-child { margin-top: 0; }

        .office-detail {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.95);
            font-family: 'Outfit', sans-serif;
            font-weight: 300;
        }

        .social-white-circle {
            display: flex;
            gap: 20px;
            margin-top: 50px;
        }

        .social-white-circle a {
            width: 55px;
            height: 55px;
            background: #fff;
            color: #507b96;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            text-decoration: none;
        }

        .social-white-circle a:hover {
            transform: scale(1.15) rotate(10deg);
            background: #ff9d00;
            color: #fff;
        }


        @media (max-width: 991px) {
            .touch-split-section { flex-direction: column; }
            .touch-left, .touch-right { width: 100%; padding: 60px 5%; }
        }
    </style>

    <div class="contact-page-wrapper">
        <!-- Hero Header -->
        <section class="contact-hero">
            <div class="container">
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">HOME</a></li>
                        <li class="breadcrumb-item active" aria-current="page">CONTACT</li>
                    </ol>
                </nav> --}}
                <h1 class="display-large" data-aos="fade-up">Let's Define the <br>Future of Health.</h1>
                <p class="fs-5 opacity-75 mt-4" style="max-width: 600px;">
                    Reach out to our global consultancy network for specialized healthcare solutions
                    tailored to your institutional goals.
                </p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="section-padding pt-5">
            <div class="container">
                <div class="row">
                    <!-- Left: Contact Form -->
                    <div class="col-lg-7" data-aos="fade-right">
                        <div class="contact-form-container">
                            <h3 class="contact-heading-main">Send us a Message</h3>
                            {{-- Add CSRF and correct action --}}
                                <form action="{{ route('contact.send') }}" method="POST" id="mainContactForm">
    @csrf

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success mb-4" style="border-radius:10px; padding:15px 20px; background:#d1fae5; border:1px solid #6ee7b7; color:#065f46;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
        <div class="alert alert-danger mb-4" style="border-radius:10px; padding:15px 20px; background:#fee2e2; border:1px solid #fca5a5; color:#991b1b;">
            <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius:10px; padding:15px 20px; background:#fee2e2; border:1px solid #fca5a5; color:#991b1b;">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="E.g. John Doe">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E.g. john@company.com">
        </div>
        <div class="col-12">
            <label class="form-label">Subject</label>
            <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" placeholder="What can we help you with?">
        </div>
        <div class="col-12">
            <label class="form-label">Message</label>
            <textarea class="form-control" name="message" rows="5" placeholder="Tell us about your project...">{{ old('message') }}</textarea>
        </div>
        <div class="col-12 mt-5">
            <button type="submit" class="btn btn-gold-submit">
                SEND MESSAGE NOW <i class="fas fa-paper-plane ms-2"></i>
            </button>
        </div>
    </div>
</form>
                        </div>
                    </div>

                    <!-- Right: Office Details -->
                    <div class="col-lg-5 ps-lg-5 mt-4 mt-lg-0" data-aos="fade-left">
                        <div class="ps-lg-4">
                            <h4 class="contact-heading-main">Connect Directly</h4>

                            <div class="office-card">
                                <div class="office-title">Corporate Head Office</div>
                                <div class="office-info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>R Floor, Building 105, Othman Bin Affan St, Al Central District, Al Ain, UAE</span>
                                </div>
                                <div class="office-info-item">
                                    <i class="fas fa-phone-alt"></i>
                                    <span>+971 3 780 2818</span>
                                </div>
                                <div class="office-info-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>info@alphatsm.com</span>
                                </div>
                            </div>

                            <div class="office-card">
                                <div class="office-title">Dubai Branch</div>
                                <div class="office-info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>1101, 11th floor, Damas Tower, Al Maktoum Road, Dubai</span>
                                </div>
                                <div class="office-info-item">
                                    <i class="fas fa-phone-alt"></i>
                                    <span>+971 4 272 4064</span>
                                </div>
                            </div>

                            {{-- <div class="d-flex gap-4 mt-5">
                                <a href="#" class="btn btn-outline-dark rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="btn btn-outline-dark rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="btn btn-outline-dark rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-instagram"></i></a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Get in Touch: Split Experience -->

<section class="ultra-modern-touch" id="contactSection">
    
    <div class="particle-canvas" id="particleCanvas"></div>

    <div class="am-container">
        
        <div class="am-content-col">
            <div class="am-reveal-wrapper">
                <span class="am-eyebrow anim-reveal">CONNECT</span>
                <h2 class="am-title anim-reveal" style="animation-delay: 0.1s;">Get in <span class="gradient-text">Touch</span></h2>
            </div>
            
            <div class="am-text-block am-reveal-wrapper">
                <p class="anim-reveal" style="animation-delay: 0.2s;">
                    If you have any questions or would like to connect with our team, 
                    we encourage you to reach out directly. Whether you're interested 
                    in exploring services, a potential partnership, or <strong class="link-style">career opportunities</strong>, 
                    we're happy to help.
                </p>
                <p class="sub-text anim-reveal" style="animation-delay: 0.3s;">
                    View current openings on our Jobs page or visit LinkedIn for more.
                </p>
            </div>

            <div class="am-action-area am-reveal-wrapper">
                <div class="magnetic-wrap anim-reveal" style="animation-delay: 0.4s;">
                    <a href="mailto:info@alphatsm.com" class="am-btn-magnetic">
                        <span>Send Email</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 1 9 22 2"></polygon></svg>
                    </a>
                </div>
                
                <div class="am-socials-minimal anim-reveal" style="animation-delay: 0.5s;">
                    <a href="https://linkedin.com/company/alphatsm" aria-label="LinkedIn" class="social-magnet"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://www.instagram.com/alpha_tsm" aria-label="Instagram" class="social-magnet"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/alphatsm" aria-label="Facebook" class="social-magnet"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/alphatsm_" target="blank" aria-label="Twitter" class="social-magnet"><i class="icofont-twitter"></i></a>
                </div>
            </div>
        </div>

        <div class="am-visual-col" id="tiltContainer">
            <div class="am-tilt-card" id="tiltCard">
                <div class="card-inner-content">
                    <div class="am-insta-header">
                        <div class="am-icon-box">
                            <i class="fab fa-instagram"></i>
                        </div>
                        <span>@ALPHATSM</span>
                    </div>
                    
                    <div class="am-iframe-container">
                        <iframe
                            src=""
                            class="snapwidget-widget"
                            allowtransparency="true"
                            frameborder="0"
                            scrolling="no">
                        </iframe>
                    </div>

                    <div class="am-insta-footer">
                        <div class="live-pulse"></div>
                        <small>Latest Feed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Modern Reset & Font */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

:root {
    --am-accent: #10c6d6; /* teal */
    --am-bg: #080808;
    --am-glass-light: rgba(255, 255, 255, 0.05);
    --am-glass-dark: rgba(0, 0, 0, 0.4);
    --am-border: rgba(255, 255, 255, 0.1);
    --am-text-muted: #888;
}

.ultra-modern-touch {
    background: var(--am-bg);
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 90px 5%;
    position: relative;
    overflow: hidden;
    perspective: 1500px; /* Crucial for 3D Tilt */
}

.am-container {
    max-width: 1300px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 100px;
    align-items: center;
    position: relative;
    z-index: 2;
}

/* === ANIMATION: Initial Reveal === */
.am-reveal-wrapper {
    overflow: hidden;
}

.anim-reveal {
    opacity: 0;
    transform: translateY(40px);
    animation: revealUp 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards;
}

@keyframes revealUp {
    0% { opacity: 0; transform: translateY(40px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* === Left Column: Content === */
.am-eyebrow {
    color: var(--am-accent);
    letter-spacing: 4px;
    font-size: 0.7rem;
    font-weight: 800;
    margin-bottom: 12px;
    display: block;
}

.am-title {
    font-size: clamp(2.8rem, 6vw, 4.2rem);
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 35px;
}

.gradient-text {
    background: linear-gradient(135deg, #024046, #0795a1 ,#10e5f8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.am-text-block p {
    font-size: 1.15rem;
    line-height: 1.7;
    color: var(--am-text-muted);
    margin-bottom: 25px;
    max-width: 550px;
}

.sub-text {
    font-size: 1rem !important;
    color: #666 !important;
}

.link-style {
    color: #fff;
    border-bottom: 1px solid var(--am-accent);
}

/* === Magnetic Button & Socials === */
.am-action-area {
    display: flex;
    align-items: center;
    gap: 40px;
    margin-top: 50px;
}

.am-btn-magnetic {
    background: #fff;
    color: #000;
    padding: 20px 40px;
    border-radius: 100px;
    font-weight: 800;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background 0.3s, color 0.3s;
    position: relative;
    z-index: 1;
}

/* We create a 'hit area' for the magnetic effect via JS */
.magnetic-wrap {
    display: flex;
    justify-content: center;
    align-items: center;
}

.am-btn-magnetic:hover {
    background: var(--am-accent);
    color: #fff;
}

.am-socials-minimal {
    display: flex;
    gap: 20px;
}

.social-magnet {
    color: #555;
    font-size: 1.3rem;
    transition: color 0.3s;
    padding: 10px; /* Larger hit area */
}

.social-magnet:hover {
    color: var(--am-accent);
}

/* === Right Column: 3D Tilt Card === */
.am-visual-col {
    display: flex;
    justify-content: center;
    align-items: center;
}

.am-tilt-card {
    background: var(--am-glass-light);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--am-border);
    border-radius: 32px;
    padding: 30px;
    width: 100%;
    max-width: 460px;
    box-shadow: 0 50px 100px rgba(0,0,0,0.6);
    transform-style: preserve-3d; /* Required for JS 3D pop-out elements */
    transition: transform 0.1s ease-out; /* Smooth tilt */
}

/* Elements inside the card can pop out */
.am-icon-box {
    transform: translateZ(50px); /* Pops the icon 'above' the card */
}

.am-insta-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 1px;
}

.am-icon-box {
    width: 44px;
    height: 44px;
    background: linear-gradient(45deg, #405de6, #833ab4, #fd1d1d, #fcaf45);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    color: white;
    font-size: 1.4rem;
}

.am-iframe-container {
    border-radius: 20px;
    overflow: hidden;
    height: 380px;
    background: #000;
    border: 1px solid var(--am-border);
    transform: translateZ(20px); /* Slight pop-out for the feed */
}

.am-iframe-container iframe {
    width: 100%;
    height: 100%;
}

.am-insta-footer {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

.live-pulse {
    width: 9px;
    height: 9px;
    background: #00ffcc;
    border-radius: 50%;
    position: relative;
}

.live-pulse::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: inherit;
    border-radius: inherit;
    animation: pulseLoop 1.5s ease-out infinite;
}

@keyframes pulseLoop {
    100% { transform: scale(3); opacity: 0; }
}

.am-insta-footer small {
    color: #555;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 0.75rem;
}

/* === Responsive Adjustments === */
@media (max-width: 1100px) {
    .am-container {
        gap: 60px;
    }
}

@media (max-width: 992px) {
    .am-container {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 80px;
    }
    
    .am-text-block p {
        margin: 0 auto 25px;
    }
    
    .am-action-area {
        flex-direction: column;
        gap: 30px;
    }
}
</style>

<script>
    // 1. 3D Tilt Functionality (for the Instagram Card)
    const tiltContainer = document.getElementById('tiltContainer');
    const tiltCard = document.getElementById('tiltCard');
    
    // Only apply tilt on desktop devices
    if (window.innerWidth > 992) {
        tiltContainer.addEventListener('mousemove', (e) => {
            const containerRect = tiltContainer.getBoundingClientRect();
            
            // Calculate center point
            const centerX = containerRect.left + containerRect.width / 2;
            const centerY = containerRect.top + containerRect.height / 2;
            
            // Calculate mouse position relative to center (normalized to -1 to 1)
            const mouseX = (e.clientX - centerX) / (containerRect.width / 2);
            const mouseY = (e.clientY - centerY) / (containerRect.height / 2);
            
            // Maximum tilt angle (in degrees)
            const maxTilt = 10;
            
            // Apply rotation (rotateY relies on mouseX, rotateX relies on mouseY)
            // Need negative sign on rotateX so it tilts 'towards' the mouse
            tiltCard.style.transform = `rotateY(${mouseX * maxTilt}deg) rotateX(${-mouseY * maxTilt}deg)`;
        });
        
        // Reset tilt when mouse leaves
        tiltContainer.addEventListener('mouseleave', () => {
            tiltCard.style.transform = 'rotateY(0deg) rotateX(0deg)';
        });
    }

    // 2. Simple Particle Background (Constellation Grid)
    function createParticles() {
        const canvas = document.getElementById('particleCanvas');
        const count = 30; // Number of dots
        
        for (let i = 0; i < count; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: absolute;
                width: ${Math.random() * 3}px;
                height: ${particle.style.width};
                background: rgba(255,255,255,${Math.random() * 0.3});
                border-radius: 50%;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                z-index: 1;
                pointer-events: none;
                animation: floatParticles ${5 + Math.random() * 10}s linear infinite;
            `;
            canvas.appendChild(particle);
        }
        
        // Add minimal CSS for the float animation dynamically
        const style = document.createElement('style');
        style.textContent = `
            @keyframes floatParticles {
                0% { transform: translateY(0) translateX(0); }
                50% { transform: translateY(-20px) translateX(10px); }
                100% { transform: translateY(0) translateX(0); }
            }
            .particle-canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
        `;
        document.head.appendChild(style);
    }
    
    // 3. Magnetic Button Effect
    function initMagneticButtons() {
        if (window.innerWidth < 992) return; // Disable on mobile

        const magnets = document.querySelectorAll('.magnetic-wrap, .am-socials-minimal a');

        magnets.forEach((magnet) => {
            magnet.addEventListener('mousemove', moveMagnet);
            magnet.addEventListener('mouseleave', function(event) {
                // Smooth reset using gsap if available, or native CSS trans
                this.querySelector('a, i').style.transform = 'translate(0px, 0px)';
            });
        });

        function moveMagnet(event) {
            const magnetButton = this.querySelector('a, i');
            const bounding = this.getBoundingClientRect();
            
            // Strength of the magnetic pull
            const strength = 15; 

            const magX = ((event.clientX - bounding.left) / this.offsetWidth) - 0.5;
            const magY = ((event.clientY - bounding.top) / this.offsetHeight) - 0.5;

            // Apply translation
            magnetButton.style.transform = `translate(${magX * strength}px, ${magY * strength}px)`;
        }
    }

    // Initialize Animations
    document.addEventListener('DOMContentLoaded', () => {
        createParticles();
        initMagneticButtons();
    });
</script>


@include('front.testimonial')


        <!-- Global Presence Maps -->
        <section class="section-padding bg-light map-section">
            <div class="container text-center mb-5">
                <span class="section-tag-light mb-4" style="background: #e6e6e6; color: #066D77; display: inline-block; padding: 8px 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; border-radius: 4px;">GLOBAL REACH</span>
                <h2 class="display-large" style="font-size: 3rem; font-family: 'Libre Baskerville', serif; color: #00767c;">Global Offices</h2>
            </div>

            <div class="map-container">
                {{-- <div class="row g-5">
                    <div class="col-lg-4" data-aos="fade-up">
                        <div class="map-frame mb-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14551.984042111582!2d55.7170937!3d24.2419183!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xc95ae8607499e5ae!2sAlpha+Training+%26+Strategic+Management!5e0!3m2!1sen!2sae!4v1566056661552!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <h5 class="fw-bold">Al Ain HQ</h5>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="map-frame mb-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14433.12595923586!2d55.3189752!3d25.2611146!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe74f1ec930743c38!2sAlpha%20Health%20Consultancies!5e0!3m2!1sen!2sae!4v1648798917779!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <h5 class="fw-bold">Dubai Office</h5>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="map-frame mb-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae!4v1692632024074!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <h5 class="fw-bold">Sri Lanka Branch</h5>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="map-frame mb-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae!4v1692632024074!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <h5 class="fw-bold">Sri Lanka Branch</h5>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="map-frame mb-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae!4v1692632024074!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <h5 class="fw-bold">Sri Lanka Branch</h5>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="map-frame mb-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae!4v1692632024074!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <h5 class="fw-bold">Sri Lanka Branch</h5>
                    </div>
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="map-frame mb-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae!4v1692632024074!5m2!1sen!2sae" width="100%" height="100%" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        <h5 class="fw-bold">Sri Lanka Branch</h5>
                    </div>
                </div> --}}

                <div class="swiper myMapSlider">
    <div class="swiper-wrapper">

        <!-- Slide 1 -->
        <div class="swiper-slide">
            <div class="glass-card">
                <h5 class="fw-bold text-center">Al Ain HQ</h5>
                <div class="map-frame mb-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14551.984042111582!2d55.7170937!3d24.2419183!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xc95ae8607499e5ae!2sAlpha+Training+%26+Strategic+Management!5e0!3m2!1sen!2sae"
                        width="100%" height="380px" style="border:0;" allowfullscreen=""></iframe>
                </div>

            </div>
        </div>

        <!-- Slide 2 -->
        <div class="swiper-slide">
            <div class="glass-card">
                <h5 class="fw-bold text-center">Dubai Office</h5>
                <div class="map-frame mb-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14433.12595923586!2d55.3189752!3d25.2611146!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe74f1ec930743c38!2sAlpha%20Health%20Consultancies!5e0!3m2!1sen!2sae"
                        width="100%" height="380px" style="border:0;" allowfullscreen=""></iframe>
                </div>

            </div>
        </div>

        <!-- Slide 3 -->
        <div class="swiper-slide">
            <div class="glass-card">
                <h5 class="fw-bold text-center">Sri Lanka Branch</h5>
                <div class="map-frame mb-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae"
                        width="100%" height="380px" style="border:0;" allowfullscreen=""></iframe>
                </div>

            </div>
        </div>

        <!--slide 4 -->
        <div class="swiper-slide">
            <div class="glass-card">
                <h5 class="fw-bold text-center">Sri Lanka Branch</h5>
                <div class="map-frame mb-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae"
                        width="100%" height="380px" style="border:0;" allowfullscreen=""></iframe>
                </div>

            </div>
        </div>

        {{-- slide 5 --}}

        <div class="swiper-slide">
            <div class="glass-card">
                <h5 class="fw-bold text-center">Sri Lanka Branch</h5>
                <div class="map-frame mb-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae"
                        width="100%" height="380px" style="border:0;" allowfullscreen=""></iframe>
                </div>

            </div>
        </div>

        {{-- slide 6 --}}

        <div class="swiper-slide">
            <div class="glass-card">
                <h5 class="fw-bold text-center">Sri Lanka Branch</h5>
                <div class="map-frame mb-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae"
                        width="100%" height="380px" style="border:0;" allowfullscreen=""></iframe>
                </div>

            </div>
        </div>

        {{-- slide 7 --}}

        <div class="swiper-slide">
            <div class="glass-card">
                <h5 class="fw-bold text-center">Sri Lanka Branch</h5>
                <div class="map-frame mb-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.9354!2d79.878342!3d6.9331885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259ef03787805%3A0x1e845f8742dc5ce4!2sInstitute%20of%20Royal%20Aesthetic!5e0!3m2!1sen!2sae"
                        width="100%" height="380px" style="border:0;" allowfullscreen=""></iframe>
                </div>

            </div>
        </div>
        <!-- Duplicate or loop your other slides same way -->

    </div>

    <!-- Pagination -->
    <div class="swiper-pagination"></div>

    <!-- Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
            </div>
        </section>

<script>
    var swiper = new Swiper(".myMapSlider", {
        slidesPerView: 6,
        spaceBetween: 30,
        loop: true,
        speed: 2000,

        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true, // ✅ pause on hover
        },

        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },

        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },

        breakpoints: {
            0: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
            1200: { slidesPerView: 4},
            1400: { slidesPreView: 5}
        }
    });
</script>

        <style>

            .glass-card {
    padding: 15px;
    border-radius: 20px;

    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    border: 1px solid rgba(255, 255, 255, 0.2);

    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);

    transition: all 0.2s ease-in-out;

    width:360px;
}

.glass-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);

}

/* .map-frame iframe {
    border-radius: 12px;

} */

/* Swiper spacing */
.swiper {

    border-radius: 15px;
                padding:20px;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
}


/* Navigation buttons glass style */
.swiper-button-next,
.swiper-button-prev {
    color: #066D77;
    background: rgba(179,230,230,0.15);
    backdrop-filter: blur(8px);
    border-radius: 50%;
    width: 45px;
    height: 45px;
}

/* Pagination dots */
.swiper-pagination-bullet {
    background: rgba(255,255,255,0.5);
}

.swiper-pagination-bullet-active {
    background: #fff;
}
            .map-section{
                padding: 50px 0;
            }
            .map-frame iframe {
                filter: grayscale(50%);
                transition: filter 0.5s ease;
                border-radius: 15px;
            }
            .map-frame:hover iframe {
                filter: grayscale(0%);
            }
            /* .swiper{
                border-radius: 15px;
                padding:20px;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
            } */
            /* .swiper-button-next, .swiper-button-prev{
                color:#066D77;
                transition: all 0.3s ease;
            } */
            </style>
    </div>

    @section('custom_js')
    <script>
        $(document).ready(function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 1200,
                    once: true
                });
            }
        });
    </script>
    @endsection
@endsection
