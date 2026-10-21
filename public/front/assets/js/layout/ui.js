/* Moved out of front/layout-2.blade.php so browsers cache it. Loaded at the same place in the page. */
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 10;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Random properties
                const size = Math.random() * 4 + 1;
                const posX = Math.random() * 100;
                const delay = Math.random() * 15;
                const duration = Math.random() * 10 + 15;

                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;

                particlesContainer.appendChild(particle);
            }
        }

        // Back to top functionality for footer button
        const footerGoToTopBtn = document.getElementById('footerGoToTop');
        const goToTopBtn = document.getElementById('goToTopBtn');

        if (footerGoToTopBtn && goToTopBtn) {
            // Show/hide button based on scroll position. Batched to one check per frame
            // so scrolling never forces a layout on every scroll event.
            let goToTopTicking = false;
            const updateGoToTop = () => {
                goToTopTicking = false;
                const scrollPosition = window.pageYOffset;
                const windowHeight = window.innerHeight;
                const documentHeight = document.documentElement.scrollHeight;
                const shown = scrollPosition > 300;

                // Show button when scrolled down a bit; pulse when near bottom
                footerGoToTopBtn.classList.toggle('show', shown);
                goToTopBtn.classList.toggle('pulse', shown && documentHeight - (scrollPosition + windowHeight) < 100);
            };
            window.addEventListener('scroll', () => {
                if (!goToTopTicking) {
                    goToTopTicking = true;
                    requestAnimationFrame(updateGoToTop);
                }
            }, { passive: true });

            // Scroll to top when button is clicked
            goToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                // Add click animation
                goToTopBtn.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    goToTopBtn.style.transform = '';
                }, 200);
            });
        }

        // Animate links on hover
        const links = document.querySelectorAll('.footer-links a, .policy-link');
        links.forEach(link => {
            link.addEventListener('mouseenter', (e) => {
                const icon = e.target.querySelector('i');
                if (icon) {
                    icon.style.transform = 'translateX(3px)';
                    icon.style.transition = 'transform 0.3s ease';
                }
            });

            link.addEventListener('mouseleave', (e) => {
                const icon = e.target.querySelector('i');
                if (icon) {
                    icon.style.transform = 'translateX(0)';
                }
            });
        });

        // Social icon hover effects
        const socialIcons = document.querySelectorAll('.social-icon');
        socialIcons.forEach(icon => {
            icon.addEventListener('mouseenter', () => {
                icon.style.transform = 'translateY(-8px) rotate(8deg) scale(1.1)';
            });

            icon.addEventListener('mouseleave', () => {
                icon.style.transform = 'translateY(0) rotate(0) scale(1)';
            });
        });

        // Logo rotation
        const logoIcon = document.querySelector('.logo-icon');
        if (logoIcon) {
            logoIcon.addEventListener('mouseenter', () => {
                logoIcon.style.transform = 'rotateY(180deg) scale(1.1)';
            });

            logoIcon.addEventListener('mouseleave', () => {
                logoIcon.style.transform = 'rotateY(0) scale(1)';
            });
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();

            // Animate links on page load
            const linkItems = document.querySelectorAll('.footer-links li');
            linkItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.animation = `slideIn 0.5s forwards`;
                }, index * 100 + 300);
            });

            // Add scroll reveal animation
            const footerElements = document.querySelectorAll('.footer-top, .footer-middle, .footer-bottom');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            footerElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                observer.observe(el);
            });
        });

        // (Removed a scroll "parallax" on .bg-circle: their CSS `float` animation
        // already owns `transform`, so the inline value never showed and only cost
        // a style recalc on every scroll event.)
        $(document).ready(function () {
            $("#menu-btn").click(function () {
                $("#sidebar-menu").toggleClass("show");

                if ($("#sidebar-menu").hasClass("show")) {
                    $("#menu-icon").removeClass("la-bars las").addClass("la-times las");
                    $("#main-container").removeClass("p-md-5 p-2").addClass("p-0");
                    $(".btn-menu").removeClass("btn-menu").addClass("extend-btn");
                    $(".btn-menu-1").removeClass("btn-menu-1").addClass("extend-btn-1");

                    $("#search-input").addClass("search-input-extend");
                    $("#search").css({
                        flex: "1"
                    });
                    $("#btn-menu-border").addClass("extend-btn-menu-border");
                } else {
                    $("#menu-icon").removeClass("la-times las").addClass("la-bars las");
                    $("#main-container").removeClass("p-0").addClass("p-md-5 p-2");
                    $(".extend-btn").removeClass("extend-btn").addClass("btn-menu");
                    $(".extend-btn-1")
                        .removeClass("extend-btn-1")
                        .addClass("btn-menu-1");
                    $("#search-input").removeClass("search-input-extend");
                    $("#search").css({
                        flex: "unset"
                    });
                    $("#btn-menu-border").removeClass("extend-btn-menu-border");
                }
            });



            $(document).on('click', '.main-category-link', function (e) {
                e.preventDefault();

                const clickedId = $(this).attr('id');
                const parentId = $(this).data('parent-id-main');

                // If it's a main category
                if (typeof parentId === 'undefined') {
                    // Remove all current highlights
                    $('.main-category-link').removeClass('active-category');

                    // Highlight this main category
                    $(this).addClass('active-category');
                }
                // If it's a sub-category
                else {
                    // Remove only other sub-category selections
                    $('.main-category-link[data-parent-id-main]').removeClass('active-category');

                    // Highlight the clicked sub-category
                    $(this).addClass('active-category');

                    // Also highlight the parent main category
                    $('.main-category-link#' + parentId).addClass('active-category');
                }
            });

        });

        $(document).ready(function () {
            if ($(window).width() < 576) {
                $("#menu-btn").click(function () {
                    if ($("#sidebar-menu").hasClass("show")) {
                        $("#search").addClass("d-none");
                        $("#new-menu-icon").addClass("d-none");
                        $(".log-btn-1").addClass("ms-auto");
                    } else {
                        $("#search").removeClass("d-none");
                        $("#new-menu-icon").removeClass("d-none");
                        $(".log-btn-1").removeClass("ms-auto");
                    }

                    $(".main-category-link").click(function () {
                        $(".sidebar-content-2").removeClass("d-none").addClass(
                            "d-block");

                        $("#menu-service").addClass("d-none");
                    });

                    $(".sub-category-link").click(function () {
                        // $(".sub_categories").addClass("d-none");
                        // $("#back-btn").addClass("d-none");
                        // $("#back-btn-1").addClass("d-block").removeClass("d-none");
                        // $("#menu-service").addClass("d-none");
                    });

                    $("#back-btn").click(function () {
                        $(".sidebar-content-2").addClass("d-none").removeClass(
                            "d-block");
                        $("#menu-service").removeClass("d-none").addClass("d-block");
                    });

                    // $("#back-btn-1").click(function() {
                    //     $("#back-btn").addClass("d-block").removeClass("d-none");
                    // });
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Main Categories (top level, no data-parent-id-main)
            const mainCategoryLinks = Array.from(document.querySelectorAll('a.main-category-link'))
                .filter(link => !link.hasAttribute('data-parent-id-main'));

            // 2. Sub Categories (have data-parent-id-main)
            const subCategoryLinks = Array.from(document.querySelectorAll('a[data-parent-id-main]'));

            // 3. Services (have data-parent-id)
            const serviceLinks = Array.from(document.querySelectorAll('a[data-parent-id]'));

            // Click on Main Category => show matching Sub Categories
            mainCategoryLinks.forEach(mainLink => {
                mainLink.addEventListener('click', function (e) {
                    e.preventDefault();

                    const mainCategoryId = this.id;

                    subCategoryLinks.forEach(subLink => {
                        if (subLink.getAttribute('data-parent-id-main') ===
                            mainCategoryId) {
                            subLink.parentElement.style.display =
                                'block'; // show matching subcategories
                        } else {
                            subLink.parentElement.style.display = 'none'; // hide others
                        }
                    });

                    // Hide all services when main category is clicked
                    serviceLinks.forEach(serviceLink => {
                        serviceLink.parentElement.style.display = 'none';
                    });
                });
            });

            // Click on Sub Category => show matching Services
            subCategoryLinks.forEach(subLink => {
                subLink.addEventListener('click', function (e) {
                    e.preventDefault();

                    const subCategoryId = this.id;

                    serviceLinks.forEach(serviceLink => {
                        if (serviceLink.getAttribute('data-parent-id') === subCategoryId) {
                            serviceLink.parentElement.style.display =
                                'block'; // show matching services
                        } else {
                            serviceLink.parentElement.style.display = 'none'; // hide others
                        }
                    });
                });
            });
        });
