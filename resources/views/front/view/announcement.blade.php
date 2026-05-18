@if($service->announcement && $service->announcement->status)
    @php
        $ann = $service->announcement;
    @endphp
    <section class="announcement-section"
        style="{{ $ann->image ? 'background-image: url(' . asset('public/uploads/announcements/' . $ann->image) . '); background-size: cover; background-position: center;' : '' }}">
        <div class="announcement-wrapper">
            <!-- Dynamic Overlay for background images -->
            <div class="ann-overlay"></div>

            <!-- Dramatic Multi-colored Flare Effect -->
            <div class="dramatic-flare" style="{{ $ann->image ? 'opacity: 0.3;' : '' }}"></div>

            <div class="container-fluid px-md-5 h-100">
                <div class="row align-items-center h-100 position-relative py-5">
                    <!-- Left: Content Block - Occupying more space -->
                    <div class="col-lg-8 offset-lg-1 mb-5 mb-lg-0">
                        <div class="ann-text-content" data-aos="fade-up" data-aos-duration="1000">
                            <h2 class="ann-main-title mb-4">{{ $ann->title }}</h2>
                            <p class="ann-sub-description mb-5">{{ $ann->description }}</p>

                            @if($ann->button_text)
                                <a href="{{ $ann->button_link ?? '#' }}" class="ann-ghost-btn">
                                    {{ $ann->button_text }}
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(!$ann->image)
                        <!-- Middle: Brand Box (Only show if no background image to keep it clean) -->
                        <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                            <div class="purple-brand-box" data-aos="zoom-in" data-aos-delay="300">
                                <i class="bi bi-cpu-fill"></i>
                            </div>
                        </div>

                        <!-- Right: Branding Block (Only show fallback if no background image) -->
                        <div class="col-lg-4 text-center text-lg-end">
                            <div class="ann-branding-visual" data-aos="fade-left" data-aos-duration="1200">
                                <div class="ann-ey-style-fallback">
                                    <div class="ey-yellow-bar"></div>
                                    <div class="ey-branding-text">
                                        <span class="ey-primary">EY</span>
                                        <span class="ey-secondary">Studio</span>
                                        <span class="ey-plus">+</span>
                                    </div>
                                    <p class="ey-tagline">Shape the future with confidence</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Essential Google Font for Premium Look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .announcement-section {
            background-color: #000;
            position: relative;
            overflow: hidden;
            width: 100%;
            color: #fff;

            font-family: 'Inter', sans-serif;
            margin-top: 0;
            /* Removing top margin for full section look */
            background-repeat: no-repeat;
        }

        .ann-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Solid black 'boxing' on the left side that transitions out */
            background: linear-gradient(90deg, #000 0%, #000 50%, rgba(0, 0, 0, 0.7) 75%, transparent 100%);
            z-index: 2;
        }

        .announcement-wrapper {
            position: relative;
            min-height: 550px;
            /* Taller for more impact */
            display: flex;
            align-items: center;
            z-index: 5;
        }

        /* Complex flare effect matching the image's vibrant lighting */
        .dramatic-flare {
            position: absolute;
            top: 0;
            right: 0;
            width: 80%;
            height: 100%;
            background:
                radial-gradient(circle at 85% 20%, rgba(255, 140, 0, 0.25) 0%, transparent 45%),
                radial-gradient(circle at 95% 50%, rgba(147, 51, 234, 0.3) 0%, transparent 55%),
                radial-gradient(circle at 75% 85%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 60% 30%, rgba(244, 63, 94, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 75%, rgba(234, 179, 8, 0.15) 0%, transparent 35%);
            filter: blur(80px);
            z-index: 1;
            pointer-events: none;
            opacity: 0.9;
        }

        .ann-text-content {
            position: relative;
            z-index: 10;
        }

        .ann-main-title {
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 200;
            /* Thin weight as per EY style */
            line-height: 1.1;
            letter-spacing: -1px;
            color: #ffffff;
            max-width: 550px;
        }

        .ann-sub-description {
            font-size: 1rem;
            font-weight: 300;
            line-height: 1.6;
            color: #94a3b8;
            /* Slate-400 */
            max-width: 450px;
        }

        .ann-ghost-btn {
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            padding: 12px 30px;
            text-decoration: none !important;
            font-size: 0.9rem;
            font-weight: 400;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: transparent;
        }

        .ann-ghost-btn:hover {
            background: #fff;
            color: #000;
            border-color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* The specific purple brand icon box */
        .purple-brand-box {
            width: 34px;
            height: 34px;
            background: #6D28D9;
            /* Dark Purple */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 0 15px rgba(109, 40, 217, 0.4);
            z-index: 10;
        }

        .ann-hero-logo {
            max-height: 200px;
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.6));
            z-index: 10;
            position: relative;
        }

        /* Fallback styling that looks exactly like the image's logo */
        .ann-ey-style-fallback {
            text-align: left;
            display: inline-block;
            position: relative;
            z-index: 10;
        }

        .ey-yellow-bar {
            width: 130px;
            height: 13px;
            background: #FFE600;
            /* Vibrant Yellow */
            transform: skewX(-25deg);
            margin-bottom: 15px;
            margin-left: 20px;
        }

        .ey-branding-text {
            font-size: 5rem;
            font-weight: 700;
            line-height: 0.85;
            letter-spacing: -3px;
            display: flex;
            align-items: flex-end;
        }

        .ey-primary {
            color: #fff;
        }

        .ey-secondary {
            color: #fff;
            font-weight: 300;
            margin-left: 10px;
            letter-spacing: -1px;
        }

        .ey-plus {
            color: #6366F1;
            /* Indigo/Violet plus */
            font-weight: 400;
            margin-left: 5px;
            font-size: 4.5rem;
        }

        .ey-tagline {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-top: 5px;
            letter-spacing: -0.5px;
        }

        /* Responsive overhaul */
        @media (max-width: 991px) {
            .announcement-section {
                padding: 60px 0;
                text-align: center;
            }

            .ann-main-title {
                margin: 0 auto 20px;
                font-size: 2.2rem;
            }

            .ann-sub-description {
                margin: 0 auto 30px;
            }

            .ann-ey-style-fallback {
                text-align: center;
                margin-top: 40px;
            }

            .ey-yellow-bar {
                margin: 0 auto 15px;
            }

            .ey-branding-text {
                font-size: 3.5rem;
                justify-content: center;
            }

            .ey-plus {
                font-size: 3rem;
            }

            .ey-tagline {
                font-size: 1.4rem;
            }

            .dramatic-flare {
                width: 100%;
                opacity: 0.6;
            }
        }
    </style>
@endif