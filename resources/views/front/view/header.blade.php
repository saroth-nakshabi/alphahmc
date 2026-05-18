  <!-- Chat Bot Style System -->

  <link rel="stylesheet" href="{{ asset('front/assets/css/floating-wpp.css') }}">
  <script src="{{ asset('public/front/assets/js/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('front/assets/js/floating-wpp.js') }}"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- End of schema.org tags -->
  <script type="application/ld+json">
		    {

		        "@context": "http://schema.org",

		        "@type": "HealthcareOrganization",

		        "name": "Alpha Health Group",
		        "legalName": "Alpha Training & Strategic Management",

		        "url": "https://www.alphahmc.com/",

		        "logo": "https://alphahmc.com/assets/img/alpha-logo.svg",

		        "description": "A comprehensive healthcare management consultancy with over 20 years of expertise in consulting, operating and managing healthcare centers & healthcare professional licensing in UAE.",

		        "contactPoint": [{
		            "@type": "ContactPoint",
		            "telephone": "+97-13-780-2818",
		            "contactType": "customer service",
		            "email": "info@alphatsm.com",
		            "areaServed": [
		                "UAE"
		            ],

		            "availableLanguage": [

		                "English"

		            ]
		        }],
		        "sameAs": [
		            "https://www.facebook.com/alphatsm",
		            "https://www.linkedin.com/company/alphatsm/",
		            "https://www.instagram.com/alpha_tsm/"

		        ],

		        "address":

		        {
		            "@type": "PostalAddress",
		            "name": "Alpha Training & Strategic Management",
		            "addressLocality": "Al Ain",
		            "addressRegion": "Abu Dhabi",
		            "addressCountry": "UAE",
		            "postalCode": "16797",
		            "streetAddress": "3rd Floor, Building 105, Othman Bin Affan St, Al Central District, Al Ain, UAE "

		        }

		    }
		</script>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-695565753"></script>
  <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
          dataLayer.push(arguments);
      }
      gtag('js', new Date());

      gtag('config', 'AW-695565753');
  </script>

  <script type='application/ld+json'>
		    {
		        "@context": "https://schema.org",
		        "@type": "WebSite",
		        "@id": "https://www.alphahmc.com/#website",
		        "url": "https://www.alphahmc.com/",
		        "name": "Alpha - healthcare management consultant",
		        "potentialAction": {
		            "@type": "SearchAction",
		            "target": "https://www.alphahmc.com/?s={search_term_string}",
		            "query-input": "required name=search_term_string"
		        }
		    }
		</script>

  <!-- End of schema.org tags -->
  <!-- Google Tag Manager -->
  <script>
      (function(w, d, s, l, i) {
          w[l] = w[l] || [];
          w[l].push({
              'gtm.start': new Date().getTime(),
              event: 'gtm.js'
          });
          var f = d.getElementsByTagName(s)[0],
              j = d.createElement(s),
              dl = l != 'dataLayer' ? '&l=' + l : '';
          j.async = true;
          j.src =
              'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
          f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', 'GTM-ND5F4WS');
  </script>
  <!-- End Google Tag Manager -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->


  <script>
      // Toggling content for the dropdown items
      function toggleContent(section) {
          //   alert(section);
          const contentToShow = document.getElementById(section);

          // Hide all sections smoothly
          const allContents = document.querySelectorAll('.toggle-content');
          allContents.forEach(content => {
              content.classList.add('collapse');
              content.style.maxHeight = null; // Reset max height for smooth collapse
          });

          // Show the selected section
          contentToShow.classList.remove('collapse');
          contentToShow.style.maxHeight = contentToShow.scrollHeight + "px"; // Set max height to show the section
      }

      // Change background color for active items
      function toggleBackgroundColor(element) {
          const headings = document.querySelectorAll('h5');
          headings.forEach(heading => {
              heading.classList.remove('active');
              heading.style.backgroundColor = '';
          });

          element.classList.add('active');
          element.style.backgroundColor = '#064f5e';
      }
  </script>

  <style>
      .megamenu h5 {
          cursor: pointer;
          font-size: 14px;
          font-weight: 600;
          font-family: 'Outfit', sans-serif;
          line-height: 1.4;
          color: white;
          background-color: #066D77;
          transition: background-color 0.3s ease, padding 0.3s ease;
          padding: 10px 14px;
          border-radius: 8px;
          margin-bottom: 8px;
      }

      .megamenu h5.active,
      .megamenu h5:hover {
          background-color: #064f5e;
      }

      .toggle-content {
          display: block;
          padding: 15px;
          border-radius: 8px;
          max-height: 0;
          /* Hide content by default */
          overflow: hidden;
          transition: max-height 0.3s ease-out, padding 0.3s ease;
          /* Smooth transition for mobile */
      }

      .toggle-content:not(.collapse) {
          max-height: 500px;
          /* Set a max height for the expanded content */
      }

      /* Mobile-specific styles */
      @media (max-width: 768px) {
          .dropdown-menu.megamenu {
              position: relative;
              display: block;
              width: 100%;
          }

          .col-md-3,
          .col-md-9 {
              display: block;
              width: 100%;
          }

          .toggle-content {
              display: block;
              padding: 12px;
              max-height: 0;
              /* Hidden by default */
          }

          h5 {
              margin-bottom: 1rem;
              padding: 12px;
              font-size: 16px;
              border-radius: 20px;
          }

          h5.active {
              background-color: #064f5e;
          }

          .toggle-content:not(.collapse) {
              max-height: 500px;
          }
      }
  </style>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  </head>

  <body>

      <!-- Google Tag Manager (noscript) -->
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ND5F4WS" height="0" width="0"
              style="display:none;visibility:hidden"></iframe></noscript>
      <!-- End Google Tag Manager (noscript) Department of Health Approved Healthcare Management Consultancy-->

      <!-- Google tag (gtag.js) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=AW-695565753"></script>
      <script>
          window.dataLayer = window.dataLayer || [];

          function gtag() {
              dataLayer.push(arguments);
          }
          gtag('js', new Date());

          gtag('config', 'AW-695565753');
      </script>
      <!-- Header Area -->
      <header class="header-area">
          <div class="container">
              <div class="row">
                  <div class="col-lg-3 col-md-3">
                  </div>
                  <div class="col-lg-6 col-md-6">
                      <marquee>
                          <p style="font-size: 1rem;color: white;"> Alpha is a Department of Health & ACTVET Approved,
                              ISO 9001:2015 Certified Healthcare Management Consultancy</p>
                      </marquee>

                  </div>

                  <div class="col-lg-3 col-md-3">
                      <ul class="header-info">
                          <li><a href="healthcare-careers">Healthcare Careers</a></li>
                          <li><a href="careers">Portal</a></li>
                      </ul>
                  </div>
              </div>
          </div>
      </header>
      <!-- End Header Area -->

      <!-- Navbar Area 	<a class="navbar-brand alpha-logo-png" href="/"><img src="assets/img/alpha-logo.png" alt="Alpha Logo"></a> -->
      <nav class="navbar navbar-expand-lg navbar-light b-btm">
          <div class="container">
              <div class="col-lg-3 col-md-12">
                  <a href="{{ route('home') }}" style=" display: unset;"> <img class="navbar-brand"
                          src="{{ asset('public/front/assets/img/alpha-logo.svg') }}" alt="Alpha Logo"></a>

                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                      aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation"
                      style="float: right; margin-top: 15px;">
                      <span class="navbar-toggler-icon"></span>
                  </button>
              </div>

              <div id="navigation" class="collapse navbar-collapse">
                  <ul class="navbar-nav mr-auto">
                      <li class="nav-item"><a href="{{ route('home') }}" class="nav-link active">Home</a></li>
                      <li class="nav-item"><a href="{{ route('how_alpha_work') }}" class="nav-link active">How We
                              Work</a></li>
                      <li class="nav-item dropdown menu-large">
                          <a href="{{ route('front.services') }}" class="dropdown-toggle nav-link active"
                              id="servicesDropdown" aria-haspopup="true" aria-expanded="false">
                              Services
                              <b class="caret"></b>
                          </a>
                          <ul class="dropdown-menu megamenu">
                              <li>
                                  <div class="row">

                                      <div class="col-md-3">
                                          <div class="p-4 mb-4">
                                              @if (isset($main_categories) && count($main_categories) > 0)
                                                  @foreach ($main_categories as $main_category)
                                                      <h5 class="mb-3
                                                                text-light d-flex align-items-center border-bottom pb-3 fw-semibold fs-5 {{ $loop->first ? 'active' : '' }}"
                                                          style="gap: 1rem; cursor: pointer; border-radius: 25px; padding: 10px 20px; {{ $loop->first ? ' background-color: #064f5e;' : '' }}"
                                                          onmouseover="toggleContent('content-{{ $main_category->id }}')"
                                                          onmouseout="toggleContent('content-{{ $main_category->id }}')"
                                                          onclick="this.classList.toggle('active'); toggleBackgroundColor(this);">
                                                          <i class="bi bi-hospital-fill text-white fs-4 pr-3 rounded-circle p-3"
                                                              style="background-color: rgba(0, 0, 0, 0.2); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);"></i>
                                                          <span>{{ $main_category->name }}</span>
                                                      </h5>
                                                  @endforeach
                                              @endif
                                          </div>
                                      </div>

                                      <div class="col-md-9">
                                          @if (isset($main_categories) && count($main_categories) > 0)
                                              @foreach ($main_categories as $main_category)
                                                  <div id="content-{{ $main_category->id }}"
                                                      class="toggle-content {{ $loop->first ? '' : 'collapse' }}">
                                                      <div class="card-body text-white">
                                                          <div class="row">
                                                               @foreach ($main_category->mergedCategories as $category)
                                                                  <div
                                                                      class="col-md-6 col-12 border-left border-right mb-3">
                                                                      <h6>
                                                                          <a href="{{ $category->slug ? route('front.service-category', $category->slug) : '#' }}"
                                                                             class="text-white text-decoration-none d-block">{{ $category->name }}</a>
                                                                      </h6>
                                                                      <ul class="list-unstyled">

                                                                          @foreach ($category->services as $service)
                                                                              <li><a href="{{ route('view_service', $service->slug) }}"
                                                                                      class="nav-link dropdown-item">{{ $service->name }}</a>
                                                                              </li>
                                                                          @endforeach
                                                                          @foreach ($category->serviceGroups as $group)
                                                                              <li><a href="{{ route('service-packages', $group->slug) }}"
                                                                                      class="nav-link dropdown-item d-flex align-items-center gap-1">
                                                                                  <i class="bi bi-collection-fill" style="font-size:.7rem;color:#009095;flex-shrink:0"></i>
                                                                                  {{ $group->name }}
                                                                              </a></li>
                                                                          @endforeach

                                                                      </ul>
                                                                  </div>
                                                              @endforeach
                                                          </div>
                                                      </div>
                                                  </div>
                                              @endforeach
                                          @endif
                                      </div>

                                      {{-- <div class="d-flex w-100 shadow bg-white">
                                          <div class="p-4" style="flex: 0 0 33%;">
                                              <div class="fs-6 text-muted mb-3">Explore our various services.
                                              </div>
                                              @if (isset($categories))
                                                  @foreach ($categories as $index => $category)
                                                      <a href="{{ route('view_category', Str::slug($category->name)) }}"
                                                          class="category-link w-100 d-flex align-items-center p-2 mb-2 bg-light rounded text-decoration-none text-dark {{ $index === 0 ? 'active-category' : '' }}"
                                                          data-services='@json($category->services)'>
                                                          <span
                                                              class="d-flex align-items-center justify-content-center rounded-circle shadow"
                                                              style="width: 40px; height: 40px;">
                                                              <i class="fas fa-book-open"></i>
                                                          </span>
                                                          <span
                                                              class="ms-3 fw-semibold text-dark">{{ $category->name }}</span>
                                                      </a>
                                                  @endforeach
                                              @endif
                                          </div>
                                          <div class="flex-fill bg-light p-4">
                                              <ul class="list-unstyled" id="servicesList">
                                              </ul>
                                          </div>
                                      </div> --}}


                              </li>
                          </ul>
                      </li>
                      <li class="nav-item"><a href="{{ route('healthcare_quality_assurance') }}"
                              class="nav-link active">Tawqeet</a>
                      </li>
                      <li class="nav-item"><a href="{{ route('about') }}" class="nav-link active">About</a></li>
                       <li class="nav-item"><a href="{{ route('front.project') }}" class="nav-link active">Projects</a></li>
                       <li class="nav-item"><a href="{{ route('front.new_blog') }}" class="nav-link active">Blog</a></li>
                      <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link active">Contact</a></li>
                  </ul>
                  <div class="col-lg-2 col-md-12">
                      <div class="navbar-right-side">

                          <div class="modal-taggle-button">
                              <a href="#" data-toggle="modal" data-target="#myModal2"><span></span></a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </nav>
      <div id="myButton"></div>
      <!-- End Navbar Area -->
