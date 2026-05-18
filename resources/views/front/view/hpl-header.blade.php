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

  {{-- <script>
      function toggleContent(section) {
          var contentToShow = document.getElementById('content-' + section);

          // Hide all sections
          var allContents = document.querySelectorAll('.toggle-content');
          allContents.forEach(function(content) {
              content.classList.add('collapse');
          });

          // Show the selected section
          contentToShow.classList.remove('collapse');
      }

      function toggleBackgroundColor(element) {
          // Remove active class and reset background color for all headings
          var headings = document.querySelectorAll('h5');
          headings.forEach(function(heading) {
              heading.classList.remove('active');
              heading.style.backgroundColor = '';
          });

          // Apply background color to the clicked element
          element.classList.add('active');
          element.style.backgroundColor = '#066D77';
      }

      function hoverBackground(element, isHovering) {
          if (!element.classList.contains('active')) {
              element.style.backgroundColor = isHovering ? '#066D77' : '';
          }
      }

      window.onload = function() {
          var firstHeading = document.querySelector('h5');
          firstHeading.classList.add('active');
          firstHeading.style.backgroundColor = '#066D77';

          var firstContent = document.getElementById('content-healthcare-facilities');
          firstContent.classList.remove('collapse');
      };
  </script>

  <style>
      h5:hover {
          background-color: #066D77;
          transition: background-color 0.3s ease;
      }

      h5.active {
          background-color: #066D77 !important;
      }

      .toggle-content {
          display: none;
      }

      .toggle-content:not(.collapse) {
          display: block;
      }
  </style> --}}


  {{-- <script>
      function toggleContent(section) {
          const contentToShow = document.getElementById('content-' + section);

          // Hide all sections
          const allContents = document.querySelectorAll('.toggle-content');
          allContents.forEach(content => {
              content.classList.add('collapse');
          });

          // Show the selected section
          contentToShow.classList.remove('collapse');
      }

      function toggleBackgroundColor(element) {
          // Remove active class and reset background color for all headings
          const headings = document.querySelectorAll('h5');
          headings.forEach(heading => {
              heading.classList.remove('active');
              heading.style.backgroundColor = '';
          });

          // Apply background color to the clicked element
          element.classList.add('active');
          element.style.backgroundColor = '#066D77';
      }

      function hoverBackground(element, isHovering) {
          if (!element.classList.contains('active')) {
              element.style.backgroundColor = isHovering ? '#066D77' : '';
          }
      }

      window.onload = function() {
          const firstHeading = document.querySelector('h5');
          firstHeading.classList.add('active');
          firstHeading.style.backgroundColor = '#066D77';

          const firstContent = document.getElementById('content-healthcare-facilities');
          firstContent.classList.remove('collapse');
      };
  </script>

  <style>
      h5 {
          cursor: pointer;
          padding: 10px 20px;
          margin: 0;
          text-align: center;
          font-size: 16px;
          font-weight: bold;
          color: white;
          background-color: #064f5e;
      }

      h5.active,
      h5:hover {
          background-color: #066D77;
      }

      .toggle-content {
          display: none;
          padding: 15px;
          border: 1px solid #ddd;
          border-radius: 8px;
      }

      .toggle-content:not(.collapse) {
          display: block;
      }
  </style> --}}

  {{-- <script>
      function toggleContent(section) {
          const contentToShow = document.getElementById('content-' + section);

          // Hide all sections
          const allContents = document.querySelectorAll('.toggle-content');
          allContents.forEach(content => {
              content.classList.add('collapse');
          });

          // Show the selected section
          contentToShow.classList.remove('collapse');
      }

      function toggleBackgroundColor(element) {
          // Remove active class and reset background color for all headings
          const headings = document.querySelectorAll('h5');
          headings.forEach(heading => {
              heading.classList.remove('active');
              heading.style.backgroundColor = '';
          });

          // Apply background color to the clicked element
          element.classList.add('active');
          element.style.backgroundColor = '#066D77';
      }

      function hoverBackground(element, isHovering) {
          if (!element.classList.contains('active')) {
              element.style.backgroundColor = isHovering ? '#066D77' : '';
          }
      }

      window.onload = function() {
          const firstHeading = document.querySelector('h5');
          firstHeading.classList.add('active');
          firstHeading.style.backgroundColor = '#066D77';

          const firstContent = document.getElementById('content-healthcare-facilities');
          firstContent.classList.remove('collapse');
      };
  </script>

  <style>
      h5 {
          cursor: pointer;
          font-size: 18px;
          font-weight: bold;
          color: white;
          background-color: #064f5e;
      }

      h5.active,
      h5:hover {
          background-color: #066D77;
      }

      .toggle-content {
          display: none;
          padding: 15px;
          border-radius: 8px;
      }

      .toggle-content:not(.collapse) {
          display: block;
      }
  </style>  --}}


  {{-- <script>
      // Toggling content for the dropdown items
      function toggleContent(section) {
          const contentToShow = document.getElementById('content-' + section);

          // Hide all sections
          const allContents = document.querySelectorAll('.toggle-content');
          allContents.forEach(content => {
              content.classList.add('collapse');
          });

          // Show the selected section
          contentToShow.classList.remove('collapse');
      }

      // Change background color for active items
      function toggleBackgroundColor(element) {
          const headings = document.querySelectorAll('h5');
          headings.forEach(heading => {
              heading.classList.remove('active');
              heading.style.backgroundColor = '';
          });

          element.classList.add('active');
          element.style.backgroundColor = '#066D77';
      }

      // Ensuring the first item is active on page load
      window.onload = function() {
          const firstHeading = document.querySelector('h5');
          firstHeading.classList.add('active');
          firstHeading.style.backgroundColor = '#066D77';

          const firstContent = document.getElementById('content-healthcare-facilities');
          firstContent.classList.remove('collapse');
      };
  </script> --}}

  <script>
      // Toggling content for the dropdown items
      function toggleContent(section) {
          const contentToShow = document.getElementById('content-' + section);

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
          element.style.backgroundColor = '#066D77';
      }

      window.onload = function() {
          const firstHeading = document.querySelector('h5');
          firstHeading.classList.add('active');
          firstHeading.style.backgroundColor = '#066D77';

          const firstContent = document.getElementById('content-healthcare-facilities');
          firstContent.classList.remove('collapse');
      };
  </script>

  {{-- <style>
      h5 {
          cursor: pointer;
          font-size: 18px;
          font-weight: bold;
          color: white;
          background-color: #064f5e;
          transition: background-color 0.3s ease;
      }

      h5.active,
      h5:hover {
          background-color: #066D77;
      }

      .toggle-content {
          display: none;
          padding: 15px;
          border-radius: 8px;
      }

      .toggle-content:not(.collapse) {
          display: block;
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
              display: none;
          }

          h5 {
              margin-bottom: 1rem;
              padding: 12px;
              font-size: 16px;
              border-radius: 20px;
          }

          h5.active {
              background-color: #066D77;
          }
      }
  </style> --}}

  <style>
      h5 {
          cursor: pointer;
          font-size: 18px;
          font-weight: bold;
          color: white;
          background-color: #064f5e;
          transition: background-color 0.3s ease, padding 0.3s ease;
          padding: 10px;
          border-radius: 8px;
          margin-bottom: 10px;
      }

      h5.active,
      h5:hover {
          background-color: #066D77;
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
              background-color: #066D77;
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
                          <a href="{{ route('services') }}" class="dropdown-toggle nav-link active"
                              id="servicesDropdown" aria-haspopup="true" aria-expanded="false">
                              Services
                              <b class="caret"></b>
                          </a>
                          <ul class="dropdown-menu megamenu">
                              <li>
                                  <div class="row">
                                      <div class="col-md-3">
                                          <div class="p-4 mb-4 rounded-3 shadow-lg"
                                              style="background-color: #066D77; background-image: linear-gradient(135deg, rgba(6, 109, 119, 0.7), rgba(0, 0, 0, 0.3));">
                                              <h5 class="mb-3 text-light d-flex align-items-center border-bottom pb-3 fw-semibold fs-5"
                                                  style="gap: 1rem; cursor: pointer; border-radius: 25px; padding: 10px 20px;"
                                                  onmouseover="toggleContent('healthcare-facilities')"
                                                  onmouseout="toggleContent('healthcare-facilities')"
                                                  onclick="this.classList.toggle('active'); toggleBackgroundColor(this);">
                                                  <i class="bi bi-hospital-fill text-success fs-4 pr-3 rounded-circle p-3"
                                                      style="background-color: rgba(0, 0, 0, 0.2); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);"></i>
                                                  <span>For Healthcare Facilities</span>
                                              </h5>

                                              <h5 class="mb-3 text-light d-flex align-items-center border-bottom pb-3 fw-semibold fs-5"
                                                  style="gap: 1rem; cursor: pointer; border-radius: 25px; padding: 10px 20px;"
                                                  onmouseover="toggleContent('product-registration')"
                                                  onmouseout="toggleContent('product-registration')"
                                                  onclick="this.classList.toggle('active'); toggleBackgroundColor(this);">
                                                  <i class="bi bi-boxes text-info fs-4 pr-3 rounded-circle p-3"
                                                      style="background-color: rgba(0, 0, 0, 0.2); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);"></i>
                                                  <span>Product Registration & Medical Engineering</span>
                                              </h5>

                                              <h5 class="mb-3 text-light d-flex align-items-center border-bottom pb-3 fw-semibold fs-5"
                                                  style="gap: 1rem; cursor: pointer; border-radius: 25px; padding: 10px 20px;"
                                                  onmouseover="toggleContent('healthcare-professionals')"
                                                  onmouseout="toggleContent('healthcare-professionals')"
                                                  onclick="this.classList.toggle('active'); toggleBackgroundColor(this);">
                                                  <i class="bi bi-person-circle text-warning fs-4 pr-3 rounded-circle p-3"
                                                      style="background-color: rgba(0, 0, 0, 0.2); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);"></i>
                                                  <span>For Healthcare Professionals</span>
                                              </h5>

                                              <h5 class="mb-3 text-light d-flex align-items-center border-bottom pb-3 fw-semibold fs-5"
                                                  style="gap: 1rem; cursor: pointer; border-radius: 25px; padding: 10px 20px;"
                                                  onmouseover="toggleContent('services-trainings')"
                                                  onmouseout="toggleContent('services-trainings')"
                                                  onclick="this.classList.toggle('active'); toggleBackgroundColor(this);">
                                                  <i class="bi bi-book text-danger fs-4 pr-3 rounded-circle p-3"
                                                      style="background-color: rgba(0, 0, 0, 0.2); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);"></i>
                                                  <span>Courses & Trainings</span>
                                              </h5>
                                          </div>
                                      </div>

                                      <div class="col-md-9">
                                          <div id="content-healthcare-facilities" class="toggle-content">
                                              <div class="card-body text-white">
                                                  <div class="row">
                                                      <div class="col-md-6 col-12 border-left border-right mb-3">
                                                          <h6>Medical Facility Fulfillment Services</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="{{ route('services.healthcare-facility-licensing') }}"
                                                                      class="nav-link dropdown-item">Healthcare Facility
                                                                      Licensing</a></li>
                                                              <li><a href="{{ route('services.healthcare-professional-resourcing') }}"
                                                                      class="nav-link dropdown-item">Professional
                                                                      Resourcing Solution</a></li>
                                                              <li><a href="{{ route('services.healthcare-quality-assurance') }}"
                                                                      class="nav-link dropdown-item">Healthcare Quality
                                                                      Assurance</a></li>
                                                              <li><a href="{{ route('services.facility-auditing-accreditation') }}"
                                                                      class="nav-link dropdown-item">Healthcare Audit
                                                                      Consultation</a></li>
                                                              <li><a href="{{ route('services.healthcare-management-outsourcing') }}"
                                                                      class="nav-link dropdown-item">Healthcare
                                                                      Facility Management Outsourcing</a></li>
                                                              <li><a href="{{ route('services.healthcare-insurance-empanelment') }}"
                                                                      class="nav-link dropdown-item">Healthcare
                                                                      Insurance Empanelment</a></li>
                                                              <li><a href="{{ route('services.healthcare-management-services') }}"
                                                                      class="nav-link dropdown-item">View All</a></li>
                                                          </ul>
                                                      </div>
                                                      <div class="col-md-6 col-12 border-left border-right mb-3">
                                                          <h6>For Investors</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="{{ route('services.healthcare-business-setup') }}"
                                                                      class="nav-link dropdown-item">Healthcare
                                                                      Business Setup</a></li>
                                                              <li><a href="#"
                                                                      class="nav-link dropdown-item">Business Merger &
                                                                      Acquisition</a></li>
                                                              <li><a href="{{ route('services.healthcare-feasibility-study') }}"
                                                                      class="nav-link dropdown-item">Healthcare
                                                                      Feasibility Study</a></li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>

                                          <div id="content-product-registration" class="collapse toggle-content">
                                              <div class="card-body text-white">
                                                  <div class="row">
                                                      <div class="col-md-4 col-12 border-left border-right mb-3">
                                                          <h6>Product Registration Services</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="{{ route('services.moh-product-registration') }}"
                                                                      class="nav-link dropdown-item">MOH Registration
                                                                      of Products</a></li>
                                                              <li><a href="{{ route('services.moh-product-registration') }}"
                                                                      class="nav-link dropdown-item">MOH Classification
                                                                      of Products</a></li>
                                                          </ul>
                                                      </div>
                                                      <div class="col-md-4 col-12 border-left border-right mb-3">
                                                          <h6>Medical Engineering</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="{{ route('services.medical-engineering') }}"
                                                                      class="nav-link dropdown-item">Medical Facility
                                                                      Designing</a></li>
                                                              <li><a href="{{ route('healthcare-digital-marketing.digital-advertisement-solution') }}"
                                                                      class="nav-link dropdown-item">Construction
                                                                      Management</a></li>
                                                              <li><a href="{{ route('healthcare-digital-marketing.digital-advertisement-solution') }}"
                                                                      class="nav-link dropdown-item">Project
                                                                      Management</a></li>
                                                              <li><a href="{{ route('healthcare-digital-marketing.digital-advertisement-solution') }}"
                                                                      class="nav-link dropdown-item">Medical Equipment
                                                                      Management</a></li>
                                                              <li><a href="{{ route('services.medical-engineering') }}"
                                                                      class="nav-link dropdown-item">View All</a></li>
                                                          </ul>
                                                      </div>
                                                      <div class="col-md-4 col-12 border-left border-right mb-3">
                                                          <h6>Digital Marketing Services</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="{{ route('healthcare-digital-marketing.website-development-solution') }}"
                                                                      class="nav-link dropdown-item">Website
                                                                      Development Services</a></li>
                                                              <li><a href="{{ route('healthcare-digital-marketing.digital-advertisement-solution') }}"
                                                                      class="nav-link dropdown-item">Digital Marketing
                                                                      Solution</a></li>
                                                              <li><a href="{{ route('healthcare-digital-marketing.index') }}"
                                                                      class="nav-link dropdown-item">View All</a></li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>

                                          <div id="content-healthcare-professionals" class="collapse toggle-content">
                                              <div class="card-body text-white">
                                                  <div class="row">
                                                      <div class="col-md-6 col-12 border-left border-right mb-3">
                                                          <h6>Healthcare Professional Licensing</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="{{ route('services.uae-healthcare-professional-licensing') }}"
                                                                      class="nav-link dropdown-item">UAE Professional
                                                                      License</a></li>
                                                              <li><a href="{{ route('services.saudi-healthcare-professional-license') }}"
                                                                      class="nav-link dropdown-item">Saudi Professional
                                                                      License</a></li>
                                                              <li><a href="{{ route('services.qatar-healthcare-professional-license') }}"
                                                                      class="nav-link dropdown-item">Qatar Professional
                                                                      License</a></li>
                                                              <li><a href="{{ route('services.bahrain-healthcare-professional-license') }}"
                                                                      class="nav-link dropdown-item">Bahrain
                                                                      Professional License</a></li>
                                                              <li><a href="{{ route('services.oman-healthcare-professional-license') }}"
                                                                      class="nav-link dropdown-item">Oman Professional
                                                                      License</a></li>
                                                              <li><a href="{{ route('services.healthcare-professional-licensing') }}"
                                                                      class="nav-link dropdown-item">View All</a></li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>

                                          <div id="content-services-trainings" class="collapse toggle-content">
                                              <div class="card-body text-white">
                                                  <div class="row">
                                                      <div class="col-md-6 col-12 border-left border-right mb-3">
                                                          <h6>Healthcare Professional Courses & Trainings</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="{{ route('services.healthcare-professional-development') }}"
                                                                      class="nav-link dropdown-item">CME & Short
                                                                      Training Courses</a></li>
                                                              <li><a href="https://alphamedu.com/services"
                                                                      class="nav-link dropdown-item">Medical Speciality
                                                                      Courses</a></li>
                                                              <li><a href="https://alphatsm.com/health-professional-exam-materials"
                                                                      target="blank"
                                                                      class="nav-link dropdown-item">Exam Preparation
                                                                      Courses</a></li>
                                                          </ul>
                                                      </div>
                                                      <div class="col-md-4 col-12 border-left border-right mb-3">
                                                          <h6>Business Management Courses & Trainings</h6>
                                                          <ul class="list-unstyled">
                                                              <li><a href="https://alphamedu.com/services"
                                                                      class="nav-link dropdown-item">Management
                                                                      Courses</a></li>
                                                              <li><a href="https://alphamedu.com/" target="blank"
                                                                      class="nav-link dropdown-item">CPD & Professional
                                                                      Courses</a></li>
                                                              <li><a href="https://alphamedu.com/services/cme"
                                                                      class="nav-link dropdown-item">Professional
                                                                      Membership Programs</a></li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                              </li>
                          </ul>
                      </li>
                      <li class="nav-item"><a href="{{ route('healthcare_quality_assurance') }}"
                              class="nav-link active">Tawqeet</a>
                      </li>
                      <li class="nav-item"><a href="{{ route('about') }}" class="nav-link active">About</a></li>
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
