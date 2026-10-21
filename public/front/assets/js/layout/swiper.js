/* Moved out of front/layout-2.blade.php so browsers cache it. Loaded at the same place in the page. */
        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            loop: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            spaceBetween: 0,
            speed: 600,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {

                1024: {
                    slidesPerView: "auto",
                    spaceBetween: 60,
                    coverflowEffect: {
                        depth: 0,
                    },
                },
            },

            on: {
                init: function () {
                    this.slides.forEach((slide) => {
                        slide.classList.remove("active-slide");
                        slide.style.transform = "scale(0.85)";
                        slide.style.opacity = "0.7";
                        slide.querySelector(".img-content")?.classList.remove("show");
                        slide.querySelector(".category-1")?.classList.remove("show");
                    });
                    const activeSlide = this.slides[this.activeIndex];
                    activeSlide.classList.add("active-slide");
                    activeSlide.style.transform = "scale(1.2)";
                    activeSlide.style.opacity = "1";
                    activeSlide.querySelector(".img-content")?.classList.add("show");
                    activeSlide.querySelector(".category-1")?.classList.add("show");
                },
                slideChange: function () {
                    this.slides.forEach((slide) => {
                        slide.classList.remove("active-slide");
                        slide.style.transform = "scale(0.85)";
                        slide.style.opacity = "0.7";
                        slide.querySelector(".img-content")?.classList.remove("show");
                        slide.querySelector(".category-1")?.classList.remove("show");
                    });
                    const activeSlide = this.slides[this.activeIndex];
                    activeSlide.classList.add("active-slide");
                    activeSlide.style.transform = "scale(1)";
                    activeSlide.style.opacity = "1";
                    activeSlide.querySelector(".img-content")?.classList.add("show");
                    activeSlide.querySelector(".category-1")?.classList.add("show");
                },
            },
        });
