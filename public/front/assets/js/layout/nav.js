/* Moved out of front/layout-2.blade.php so browsers cache it. Loaded at the same place in the page. */
     $(document).ready(function () {

    console.log("Navbar Initialized (With Mobile Description)");

    function isMobile() {
        return window.innerWidth <= 768;
    }

    // ── Third column rows (services, guides, case studies) load on first use from /nav-items, so they
    // are not links on every page (SEO brief 30 Oct 2026). Rows arrive hidden; the active filter is re-shown.
    var navItems = null;
    function loadNavItems() {
        if (navItems) return navItems;
        var $list = $(".serv-col .service-content-2");
        navItems = $.Deferred();
        if (!$list.length || !$list.data("src")) { navItems.resolve(); return navItems; }
        $.get($list.data("src")).done(function (html) {
            $list.html(html);
            var $active = $(".main-category-link-sub.active-category");
            if ($active.length && $active.attr("id")) $(".filter-" + $active.attr("id")).show();
            navItems.resolve();
        }).fail(function () {
            navItems = null;   // try again on the next open
        });
        return navItems;
    }
    $("#explore-search-toggle").on("mouseenter focus touchstart", function () { loadNavItems(); });

    function navIsOpen() {
        return $("#main-navbar").hasClass("menu-is-open");
    }

    /* =========================
       TOGGLE MENU
    ========================= */
    function closeMegaMenu() {
        const nav = $("#main-navbar");
        if (!nav.hasClass("menu-is-open")) return;
        nav.removeClass("menu-is-open");
        $("#explore-search-toggle").removeClass("is-active").find(".btn-label").text("MENU");
        $(".mobile-sub-list").remove();
        $(".mobile-service-list").remove();
        stopPromoSlider();
    }

    $("#explore-search-toggle").on("click", function () {
        loadNavItems();
        const nav = $("#main-navbar");
        nav.toggleClass("menu-is-open");

        if (nav.hasClass("menu-is-open")) {
            $(this).find(".btn-label").text("CLOSE");
            $(this).addClass("is-active");

            // Desktop: show the curated default panel (featured services + promo) on open —
            // editorial first impression instead of jumping into the first category.
            if (!isMobile()) {
                showMenuDefaultPanel();
            }
        } else {
            $(this).find(".btn-label").text("MENU");
            $(this).removeClass("is-active");

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();
        }
    });

    // Mobile bottom-left close button
    $(document).on("click", "#menuMobileClose", function () {
        closeMegaMenu();
    });

    // Close the mega-menu when clicking OUTSIDE it (not on hover-out).
    $(document).on("click", function (e) {
        if ($("#main-navbar").hasClass("menu-is-open") && !$(e.target).closest("#main-navbar").length) {
            closeMegaMenu();
        }
    });

    // Close on Escape key too.
    $(document).on("keydown", function (e) {
        if (e.key === "Escape") closeMegaMenu();
    });

    //search button click




    $(document).ready(function () {

        var searchTimer;
        var currentQuery = '';

        // ── Typing: live dropdown ──────────────────────────────
        $("#main-search-input").on("input", function () {
            var query = $(this).val().trim();
            currentQuery = query;

            clearTimeout(searchTimer);

            if (query.length < 2) {
                hideDropdown();
                return;
            }

            // Show loading state
            showLoading();

            // Debounce 300ms
            searchTimer = setTimeout(function () {
                fetchResults(query);
            }, 300);
        });

        // ── Enter key: go to results page ─────────────────────
        $("#main-search-input").on("keydown", function (e) {
            if (e.key === "Enter") {
                performSearch();
            }
            if (e.key === "Escape") {
                hideDropdown();
                $(".glass-search-container").removeClass("search-open");
            }
        });

        // ── Button click: mobile = expand the bar first, then search ──
        $(".glass-search-btn").on("click", function (e) {
            var $c = $(".glass-search-container");
            var isMobileNav = window.matchMedia("(max-width: 991px)").matches;
            if (isMobileNav && !$c.hasClass("search-open")) {
                e.preventDefault();
                e.stopPropagation();
                $c.addClass("search-open");
                setTimeout(function () {
                    var el = document.getElementById("main-search-input");
                    if (el) el.focus();
                }, 60);
                return;
            }
            performSearch();
        });

        // ── Click outside: close dropdown + collapse mobile search ──
        $(document).on("click", function (e) {
            if (!$(e.target).closest(".glass-search-container").length) {
                hideDropdown();
                $(".glass-search-container").removeClass("search-open");
            }
        });

        // ── Fetch live results ─────────────────────────────────
        function fetchResults(query) {
            $.ajax({
                url: AHG_NAV.searchLive,
                method: "GET",
                data: { s: query },
                success: function (data) {
                    renderResults(data, query);
                },
                error: function () {
                    hideDropdown();
                }
            });
        }

        // ── Render dropdown results ────────────────────────────
        function renderResults(data, query) {
            var container = $("#live-search-results");
            container.empty();

            if (data.length === 0) {
                container.html(
                    '<div style="padding: 20px 16px; text-align:center; color:#94a3b8;">' +
                    '<i class="fas fa-search" style="font-size:1.5rem; display:block; margin-bottom:8px;"></i>' +
                    'No results for <strong>"' + escapeHtml(query) + '"</strong>' +
                    '</div>'
                );
                $("#live-search-footer").hide();
            } else {
                var currentType = '';

                $.each(data, function (i, item) {

                    // Type group header
                    if (item.type !== currentType) {
                        currentType = item.type;
                        container.append(
                            '<div style="padding: 8px 16px 4px; font-size:0.7rem; font-weight:700; ' +
                            'text-transform:uppercase; letter-spacing:1px; color:#94a3b8; ' +
                            'background:#f8fafc; border-top: 1px solid #f1f5f9;">' +
                            (item.type_plural || item.type + 's') +
                            '</div>'
                        );
                    }

                    // Highlight matched text
                    var highlighted = highlightMatch(item.title, query);

                    container.append(
                        '<a href="' + item.url + '" style="' +
                        'display:flex; align-items:center; justify-content:space-between; ' +
                        'padding: 10px 16px; text-decoration:none; color:#1e293b; ' +
                        'transition: background 0.2s ease; border-bottom: 1px solid #f8fafc;' +
                        '" ' +
                        'onmouseover="this.style.background=\'#f0fdfa\'" ' +
                        'onmouseout="this.style.background=\'transparent\'">' +

                        '<span style="font-size:0.92rem; font-weight:500; flex:1;">' +
                        highlighted +
                        '</span>' +

                        '<i class="fas fa-arrow-right" style="font-size:0.7rem; color:#cbd5e1;"></i>' +
                        '</a>'
                    );
                });

                // Update footer link
                $("#view-all-link").attr(
                    "href",
                    AHG_NAV.search + "?s=" + encodeURIComponent(query)
                );
                $("#live-search-footer").show();
            }

            showDropdown();
        }

        // ── Show loading state ─────────────────────────────────
        function showLoading() {
            $("#live-search-results").html(
                '<div style="padding: 16px; text-align:center; color:#94a3b8;">' +
                '<i class="fas fa-spinner fa-spin"></i> Searching...' +
                '</div>'
            );
            $("#live-search-footer").hide();
            showDropdown();
        }

        // ── Show / hide dropdown ───────────────────────────────
        function showDropdown() {
            $("#live-search-dropdown").fadeIn(150);
        }

        function hideDropdown() {
            $("#live-search-dropdown").fadeOut(150);
        }

        // ── Go to full results page ────────────────────────────
        function performSearch() {
            var query = $("#main-search-input").val().trim();
            if (query.length >= 2) {
                window.location.href = AHG_NAV.search + "?s=" + encodeURIComponent(query);
            } else {
                $("#main-search-input").css({ "border": "1px solid red" });
                setTimeout(function () {
                    $("#main-search-input").css("border", "");
                }, 1500);
            }
        }

        // ── Highlight matched text ─────────────────────────────
        function highlightMatch(text, query) {
            var escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            var regex = new RegExp('(' + escaped + ')', 'gi');
            return escapeHtml(text).replace(regex,
                '<strong style="color:#009095;">$1</strong>'
            );
        }

        // ── Escape HTML ────────────────────────────────────────
        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }

        // ── Hex to rgba helper ─────────────────────────────────
        function hexToRgba(hex, alpha) {
            var r = parseInt(hex.slice(1, 3), 16);
            var g = parseInt(hex.slice(3, 5), 16);
            var b = parseInt(hex.slice(5, 7), 16);
            return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
        }

    });

    /* =========================
       DESKTOP
    ========================= */
    $(".main-category-link").on("mouseenter click", function (e) {
        if (!isMobile()) {
            if (e.type === "click") e.preventDefault();
            handleMainCategory($(this));
        }
    });

    // Handle Our Packages link
    $(".packages-link").on("mouseenter click", function (e) {
        if (!isMobile()) {
            if (e.type === "click") e.preventDefault();
            handlePackages($(this));
        }
    });

    // Handle About Alpha Health Group link (shows its sub-links in the right panel)
    $(".group-link").on("mouseenter click", function (e) {
        if (!isMobile()) {
            if (e.type === "click") e.preventDefault();
            handleGroup($(this));
        }
    });

    // Handle Knowledge Base / Case Studies (3-level like the categories)
    // Knowledge Base: hover reveals blog categories; CLICK navigates to the insights main page (default href).
    $(".kb-link").on("mouseenter", function (e) {
        if (!isMobile()) { handleKB($(this)); }
    });
    // Case Studies: hover reveals project categories; CLICK navigates to the Case Studies main page (default href).
    $(".cs-link").on("mouseenter", function (e) {
        if (!isMobile()) { handleCS($(this)); }
    });

    $(document).on("mouseenter click", ".main-category-link-sub:not(.service-group-link):not(.cs-flat-link):not(.kb-flat-link)", function (e) {
        if (!isMobile()) {
            if (e.type === "mouseenter") {
                handleSubCategory($(this));
            }
        }
    });

    /* =========================
       MOBILE: MAIN CATEGORY
    ========================= */
    $(".main-category-link").on("click", function (e) {
        if (isMobile()) {
            e.preventDefault();

            let mainId = $(this).attr("id");
            let title = $(this).text().trim();

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();

            $(".main-category-link").removeClass("active-category");
            $(".packages-link").removeClass("active-category");
            $(this).addClass("active-category");

            // 🔥 UPDATE HEADING + DESCRIPTION
            $("#dynamic-heading").text(title);
            $("#dynamic-desc").text("Explore our specialized " + title + " categories.");

            let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');

            $(".filter-" + mainId).each(function () {
                let clone = $(this).clone();
                clone.show();
                // Add expand chevron to expandable sub-categories (skip service-group links and disabled)
                clone.find(".main-category-link-sub:not(.service-group-link):not(.disabled)").each(function () {
                    if (!$(this).find(".mobile-expand-chevron").length) {
                        $(this).append('<span class="mobile-expand-chevron" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>');
                    }
                });
                subList.append(clone);
            });

            $(this).parent().append(subList);
        }
    });

    // Handle Our Packages on Mobile
    $(".packages-link").on("click", function (e) {
        if (isMobile()) {
            e.preventDefault();

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();

            $(".main-category-link").removeClass("active-category");
            $(".packages-link").addClass("active-category");

            // UPDATE HEADING + DESCRIPTION
            $("#dynamic-heading").text("Our Packages");
            $("#dynamic-desc").text("Explore our service packages and solutions.");

            let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');

            $(".filter-packages").each(function () {
                let clone = $(this).clone();
                clone.show();
                subList.append(clone);
            });

            $(this).parent().append(subList);
        }
    });

    // Handle About Alpha Health Group on Mobile
    $(".group-link").on("click", function (e) {
        if (isMobile()) {
            e.preventDefault();

            $(".mobile-sub-list").remove();
            $(".mobile-service-list").remove();

            $(".main-category-link").removeClass("active-category");
            $(".packages-link").removeClass("active-category");
            $(".group-link").addClass("active-category");

            $("#dynamic-heading").text("Who We Are");
            $("#dynamic-desc").text("Alpha Health Group delivers integrated consulting, compliance, operational, and engineering solutions that help healthcare organisations build, operate, and grow with confidence.");

            let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');
            $(".filter-group").each(function () {
                let clone = $(this).clone();
                clone.show();
                subList.append(clone);
            });

            $(this).parent().append(subList);
        }
    });

    // Handle Knowledge Base / Case Studies on Mobile (tap a topic → its articles/projects)
    function mobileGroupedDrilldown($trigger, filterClass, heading, desc) {
        $(".mobile-sub-list").remove();
        $(".mobile-service-list").remove();
        $(".main-category-link, .packages-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        $trigger.addClass("active-category");
        $("#dynamic-heading").text(heading);
        $("#dynamic-desc").text(desc);

        let subList = $('<ul class="mobile-sub-list list-unstyled"></ul>');
        $('.' + filterClass).each(function () {
            let clone = $(this).clone();
            clone.show();
            clone.find(".main-category-link-sub:not(.service-group-link):not(.disabled)").each(function () {
                if (!$(this).find(".mobile-expand-chevron").length) {
                    $(this).append('<span class="mobile-expand-chevron" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>');
                }
            });
            subList.append(clone);
        });
        $trigger.parent().append(subList);
    }
    // Knowledge Base on mobile: tap navigates straight to the insights main page (no drilldown).
    // Case Studies on mobile: tap navigates straight to the Case Studies main page (no drilldown).

    /* =========================
       MOBILE: SUB CATEGORY (L2 → accordion reveals L3 services)
    ========================= */

    // Disabled links: always block navigation
    $(document).on("click", ".main-category-link-sub.disabled", function (e) {
        e.preventDefault();
    });

    // Expandable sub-categories: accordion on mobile, navigate on desktop (handled by hover above)
    $(document).on("click", ".main-category-link-sub:not(.service-group-link):not(.disabled)", function (e) {
        if (!isMobile()) return; // desktop uses hover — no change needed there

        e.preventDefault(); // block page nav; user navigates via the "Visit →" link inside L3

        // Rows still loading: open this sub-category once they are in.
        var navReady = loadNavItems();
        if (navReady.state() !== "resolved") {
            var self = this;
            navReady.done(function () { $(self).trigger("click"); });
            return;
        }

        var subId    = $(this).attr("id") || "";              // "sub_5"
        var catId    = subId.replace("sub_", "");             // "5"
        var catName  = $(this).clone().children().remove().end().text().trim();
        var catUrl   = $(this).attr("href");
        var parentLi = $(this).closest("li");

        // ── Toggle: collapse if already open ──────────────────
        if (parentLi.hasClass("mobile-sub-expanded")) {
            parentLi.removeClass("mobile-sub-expanded");
            parentLi.find(".mobile-service-list").slideUp(180, function () { $(this).remove(); });
            return;
        }

        // ── Collapse any other open L3 ────────────────────────
        $(".mobile-sub-expanded").each(function () {
            $(this).removeClass("mobile-sub-expanded");
            $(this).find(".mobile-service-list").slideUp(180, function () { $(this).remove(); });
        });

        parentLi.addClass("mobile-sub-expanded");

        // ── Build L3 panel ────────────────────────────────────
        var $panel = $('<ul class="mobile-service-list list-unstyled"></ul>');

        // Actual services under this sub-category
        var $services = $(".filter-sub_" + catId);
        if ($services.length > 0) {
            $services.each(function () {
                $panel.append($(this).clone().show());
            });
        } else {
            $panel.append('<li class="mobile-no-services"><span>No services listed yet</span></li>');
        }

        // "Visit [Category] page →" — last item so users can navigate to the full page
        if (catUrl && catUrl !== "#") {
            $panel.append(
                '<li class="mobile-visit-category">' +
                '<a href="' + catUrl + '">' +
                '<i class="fas fa-arrow-right"></i>&nbsp;Visit ' + catName + ' page' +
                '</a></li>'
            );
        }

        $panel.hide();
        parentLi.append($panel);
        $panel.slideDown(250); // enter slower than exit — feels responsive (MD motion rule)
    });

    /* =========================
       SHARED FUNCTIONS
    ========================= */
    // Curated default panel (open state) ⇄ real menu content (after a trigger).
    function showMenuDefaultPanel() {
        // Use explicit display values — both containers are CSS grid, so jQuery .show() (which
        // forces display:block) would break their layout.
        $("#menu-default-panel").css("display", "grid");
        $(".categories_service").css("display", "none");
        $(".main-category-link, .packages-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        $("#dynamic-heading").text("Our Expertise");
        $("#dynamic-desc").text("Explore our most-requested services, or choose an area from the left.");
        startPromoSlider();
    }
    function revealMenuContent() {
        $("#menu-default-panel").css("display", "none");
        $(".categories_service").css("display", "grid");
        stopPromoSlider();
    }

    /* Mega-menu promo auto-slider (dashboard-managed, max 3) */
    var mpTimer = null, mpIdx = 0;
    function mpSlides() { return $("#mdp-promo-slider .mdp-promo-slide"); }
    function mpGo(i) {
        var $s = mpSlides();
        if (!$s.length) return;
        mpIdx = (i + $s.length) % $s.length;
        $s.removeClass("active").eq(mpIdx).addClass("active");
        $("#mdp-promo-slider .mdp-promo-dot").removeClass("active").eq(mpIdx).addClass("active");
    }
    function startPromoSlider() {
        stopPromoSlider();
        if (mpSlides().length <= 1) return;
        mpTimer = setInterval(function () { mpGo(mpIdx + 1); }, 9000);
    }
    function stopPromoSlider() { if (mpTimer) { clearInterval(mpTimer); mpTimer = null; } }
    // Clickable dots (jump + restart), pause on hover — interruptible, premium feel.
    $(document).on("click", "#mdp-promo-slider .mdp-promo-dot", function () {
        mpGo(parseInt($(this).attr("data-idx"), 10) || 0);
        startPromoSlider();
    });
    $(document).on("mouseenter", "#mdp-promo-slider", stopPromoSlider);
    $(document).on("mouseleave", "#mdp-promo-slider", startPromoSlider);

    function handleGroup(el) {
        revealMenuContent();
        const title = "Who We Are";

        $(".main-category-link, .packages-link, .kb-link, .cs-link").removeClass("active-category");
        $(".group-link").addClass("active-category");

        // Show only the group's sub-links in the right panel.
        $(".service-content-category").hide();
        $(".service-group-item").hide();
        $(".filter-group").show();
        $(".serv-col").hide();

        $("#dynamic-heading").text(title);
        $("#dynamic-desc").text("Alpha Health Group delivers integrated consulting, compliance, operational, and engineering solutions that help healthcare organisations build, operate, and grow with confidence.");
    }

    // Knowledge Base / Case Studies: level-1 trigger → show its sub-list (tags / project
    // categories). Hovering a sub-item then reveals level-3 (blogs / projects) via handleSubCategory.
    function handleGroupedDrilldown(filterClass, heading, desc) {
        revealMenuContent();
        $(".service-content-category").hide();
        $(".service-group-item").hide();
        $('.' + filterClass).show();
        $(".serv-col").show();
        $(".service-item").hide();
        $(".view-all-services-wrapper").hide();
        $("#dynamic-heading").text(heading);
        $("#dynamic-desc").text(desc);
    }

    function handleKB(el) {
        $(".main-category-link, .packages-link, .group-link, .cs-link").removeClass("active-category");
        $(".kb-link").addClass("active-category");
        handleGroupedDrilldown("filter-kb", "Knowledge Base", "Browse our insights by topic, then pick an article.");
    }

    function handleCS(el) {
        $(".main-category-link, .packages-link, .group-link, .kb-link").removeClass("active-category");
        $(".cs-link").addClass("active-category");
        handleGroupedDrilldown("filter-cs", "Case Studies", "Explore our projects by category, then open a case study.");
    }

    function handleMainCategory(el) {
        revealMenuContent();
        const mainId = el.attr("id");
        const title = el.text().trim();

        $(".main-category-link, .packages-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        el.addClass("active-category");

        $(".service-content-category").hide();
        $(".service-group-item").hide();
        $(".filter-" + mainId).show();

        // Show services column and hide items
        $(".serv-col").show();
        $(".service-item").hide();
        $(".view-all-services-wrapper").hide();

        $("#dynamic-heading").text(title);
        $("#dynamic-desc").text("Explore our specialized " + title + " categories.");
    }

    function handleSubCategory(el) {
        const subId = el.attr("id");
        const title = el.text().trim();
        const subCategoryUrl = el.attr("href");

        $(".main-category-link-sub").removeClass("active-category");
        el.addClass("active-category");

        // Show services column and display filtered items
        $(".serv-col").show();
        $(".service-item").hide();
        $(".filter-" + subId).show();
        $(".view-all-services-wrapper").show();

        // Context-aware "View All" button: Knowledge Base / Case Studies → "View All",
        // service categories → "View All Services".
        var $btn = $(".view-all-services-btn");
        if (subId && subId.indexOf("tag_") === 0) {
            $btn.attr("href", AHG_NAV.blog).html('View All <i class="fas fa-arrow-right ms-2"></i>');
            $("#dynamic-desc").text("Articles under " + title + ".");
        } else if (subId && subId.indexOf("pcat_") === 0) {
            $btn.attr("href", AHG_NAV.project).html('View All <i class="fas fa-arrow-right ms-2"></i>');
            $("#dynamic-desc").text("Case studies under " + title + ".");
        } else {
            $btn.attr("href", subCategoryUrl && subCategoryUrl !== "#" ? subCategoryUrl : AHG_NAV.allServices)
                .html('View All Services <i class="fas fa-arrow-right ms-2"></i>');
            $("#dynamic-desc").text("Specialized services available under " + title + ".");
        }

        $("#dynamic-heading").text(title);
    }

    function handlePackages(el) {
        revealMenuContent();
        const title = "Our Packages";

        $(".main-category-link, .group-link, .kb-link, .cs-link").removeClass("active-category");
        $(".packages-link").addClass("active-category");

        $(".service-content-category").hide();
        $(".service-group-item").hide();   // clear group + other items first
        $(".filter-packages").show();

        // Hide the entire services column
        $(".serv-col").hide();

        $("#dynamic-heading").text(title);
        $("#dynamic-desc").text("Explore our service packages and solutions.");
    }

});

