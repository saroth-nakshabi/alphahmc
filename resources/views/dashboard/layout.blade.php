<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Dashboard</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('public/favicon.png') }}" />
    <!-- Owl Carousel  -->
    <link rel="stylesheet"
        href="{{ asset('public/dashboard/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <!-- datatable  css -->
    <link rel="stylesheet"
        href="{{ asset('public/dashboard/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    {{-- sweetalert --}}
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/sweetalert2/dist/sweetalert2.min.css') }}">

    {{-- bootstrap icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('public/dashboard/dist/libs/select2/dist/css/select2.min.css') }}" />
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="{{ asset('public/dashboard/dist/css/style.min.css') }}" />
    @yield('custom_css')

    <style>
        label.error {
            color: red;
        }

        .select2 {
            display: block;
            height: 36px !important;
        }

        .iti {
            display: block;
        }
    </style>

    {{-- set the cookie in the backend if it doesn't exist --}}
    @if (!request()->cookie('userTimezone'))
        <script>
            // Detect the user's timezone
            let userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

            // Send the timezone to the backend via AJAX
            fetch('{{ route('set_timezone') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                },
                body: JSON.stringify({
                    timezone: userTimezone
                })
            });
            location.reload();
        </script>
    @endif

    {{-- Set a cookie named 'test' with value 'new cookie' and expiration time of 1 minute
    cookie()->queue(cookie('test', 'new cookie1', 1)); // 1 is the expiration time in minutes --}}

</head>

<body>
    <!-- Preloader -->
    {{-- <div class="preloader">
        <img src="{{ asset('public/dashboard/dist/images/logos/favicon.ico" alt="loader"
            class="lds-ripple img-fluid') }}" />
    </div> --}}
    <div class="preloader">
        <img src="{{ asset('public/dashboard/dist/images/logos/favicon.ico') }}" alt="loader"
            class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    {{-- <div class="preloader">
        <img src="{{ asset('public/dashboard/dist/images/logos/favicon.ico" alt="loader"
            class="lds-ripple img-fluid') }}" />
    </div> --}}
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between pt-3">
                    <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                        <img src="{{ asset('public/front/assets/img/alpha-logo.svg') }}" class="dark-logo" height="70"
                            alt="" />
                        <img src="{{ asset('public/logo-footer.png') }}" class="light-logo" height="70" alt="" />
                    </a>
                    <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8 text-muted"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul id="sidebarnav">
                        <!-- ============================= -->
                        <!-- Home -->
                        <!-- ============================= -->
                        <li class="nav-small-cap">
                            <i class=" ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <!-- =================== -->
                        <!-- Dashboard -->
                        <!-- =================== -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-package"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        @can('view profile')
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('profile.edit') }}" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-user-circle"></i>
                                    </span>
                                    <span class="hide-menu">Profile</span>
                                </a>
                            </li>
                        @endcan
                        @canany(['view users', 'view admins', 'view agents'])
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <span class="d-flex">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span class="hide-menu">User Management</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    @can('view users')
                                        <li class="sidebar-item">
                                            <a href="{{ route('all_users.index') }}" class="sidebar-link">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">All Users</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view admins')
                                        <li class="sidebar-item">
                                            <a href="{{ route('users.admins') }}" class="sidebar-link">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Admin</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view agents')
                                        <li class="sidebar-item">
                                            <a href="{{ route('users.agents') }}" class="sidebar-link">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Agents</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @role('Admin')
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span class="hide-menu">Roles & Permissions</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('roles.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Roles</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('roles.permissions.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Permissions</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('roles.permission_categories.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Permission Categories</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endrole
                        <!-- ============================= -->
                        <!-- Apps -->
                        <!-- ============================= -->
                        @canany(['view main categories', 'view categories', 'view services', 'view service groups', 'view pages', 'view menu promos', 'view project process', 'view inquiries', 'view planner', 'view planner builder', 'view settings', 'view home sliders', 'view announcements', 'view blogs', 'view tags', 'view projects', 'view project categories', 'view global tags', 'view google tags', 'view clients', 'view brands', 'view testimonials', 'view about us', 'view about quote', 'view eco system', 'view about counters', 'view about staff'])
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Service</span>
                        </li>
                        @endcanany

                        {{-- Service Manager: groups Categories, Services and Service Groups --}}
                        @canany(['view main categories', 'view categories', 'view services', 'view service groups'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-briefcase"></i>
                                </span>
                                <span class="hide-menu">Service Manager</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view main categories')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('main_categories.index') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Main Categories</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view categories')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('categories.index') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Categories</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view services')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('services.index') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Services</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view service groups')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('service-group.index') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Service Group</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        @can('view pages')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.pages.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-file-text"></i>
                                </span>
                                <span class="hide-menu">Pages &amp; SEO</span>
                            </a>
                        </li>
                        @endcan

                        {{-- SEO Overview: read-only meta listings per content type --}}
                        @canany(['view services', 'view service groups', 'view categories', 'view blogs', 'view brands'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-list-search"></i>
                                </span>
                                <span class="hide-menu">SEO Overview</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view services')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('seo.overview', 'services') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Services</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view service groups')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('seo.overview', 'service-groups') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Service Groups</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view categories')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('seo.overview', 'categories') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Categories</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view blogs')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('seo.overview', 'blogs') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Blogs</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view brands')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="{{ route('seo.overview', 'brands') }}" aria-expanded="false">
                                            <span><i class="ti ti-circle"></i></span>
                                            <span class="hide-menu">Brands</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        @can('view menu promos')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.menu-promos.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-ad-2"></i>
                                </span>
                                <span class="hide-menu">Menu Promos</span>
                            </a>
                        </li>
                        @endcan

                        @can('view project process')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.project-process.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-route"></i>
                                </span>
                                <span class="hide-menu">Project Process Manager</span>
                            </a>
                        </li>
                        @endcan

                        @can('view inquiries')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('admin.inquiries.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-mail"></i>
                                </span>
                                <span class="hide-menu">Service Inquiries</span>
                            </a>
                        </li>
                        @endcan

                        <!-- ============== AI Planner =============== -->
                        @canany(['view planner', 'view planner builder', 'view settings'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-wand"></i>
                                </span>
                                <span class="hide-menu">AI Planner</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view planner')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('admin.planner.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-clipboard-text"></i>
                                        </span>
                                        <span class="hide-menu">Planning Request</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view settings')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('admin.settings.edit') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-settings"></i>
                                        </span>
                                        <span class="hide-menu">Settings</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view planner builder')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('admin.planner.builder') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-layout-grid-add"></i>
                                        </span>
                                        <span class="hide-menu">Planner Builder</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        @can('view home sliders')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('sliders.home.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-slideshow"></i>
                                </span>
                                <span class="hide-menu">Sliders</span>
                            </a>
                        </li>
                        @endcan

                        @can('view announcements')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('announcements.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-notification"></i>
                                </span>
                                <span class="hide-menu">Announcements</span>
                            </a>
                        </li>
                        @endcan


                        <!-- ============ Blogs ================= -->

                        @canany(['view blogs', 'view tags'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-pencil"></i>
                                </span>
                                <span class="hide-menu">Blogs</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view blogs')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('blogs.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">All Blogs</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view tags')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('blog.tags.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Blogs Category</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany


                        <!-- ============== Projects =============== -->

                        @canany(['view projects', 'view project categories'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-briefcase"></i>
                                </span>
                                <span class="hide-menu">Project</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view projects')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('project.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">New Projects</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view project categories')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('project.category.index') }}"
                                        aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Projects Category</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany


                        <!-- Global Tag ============-->
                        @canany(['view global tags', 'view google tags'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-tag"></i>
                                </span>
                                <span class="hide-menu">Tags</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view global tags')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('globaltag') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Global Tags</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view google tags')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('google.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Google Tags</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany



                        <!-- ============== Our Client =============== -->

                        @can('view clients')
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span class="hide-menu">Our Clients</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('dashboard.clients.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Client Details</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan

                        @can('view brands')
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-brand-abstract"></i>
                                </span>
                                <span class="hide-menu">Our Brands</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('dashboard.brands.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Brand Portfolio</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan

                        @can('view testimonials')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('testimonial.index') }}" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-message-star"></i>
                                </span>
                                <span class="hide-menu">Testimonials</span>
                            </a>
                        </li>
                        @endcan


                        <!-- ============== About us =============== -->

                        @canany(['view about us', 'view about quote', 'view eco system', 'view about counters', 'view about staff'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-info-square"></i>
                                </span>
                                <span class="hide-menu">About Us</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view about us')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('about_us.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Hero Section</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('about_us.content.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">About Content</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view about quote')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{route('about_quote.index')}}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">About Quotes</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view eco system')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{route('eco_system.index')}}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Eco System</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view about counters')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('about_counters.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Counters / Stats</span>
                                    </a>
                                </li>
                                @endcan

                                @can('view about staff')
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('about_staff.index') }}" aria-expanded="false">
                                        <span>
                                            <i class="ti ti-circle"></i>
                                        </span>
                                        <span class="hide-menu">Leadership Team</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany


                        <!-- ============= End About Us ================ -->


                        {{-- @canany(['view test questions', 'view test answers'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-chart-donut-3"></i>
                                </span>
                                <span class="hide-menu">Faqs</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view test questions')
                                <li class="sidebar-item">
                                    <a href="{{ route('test_questions.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Questions</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view test answers')
                                <li class="sidebar-item">
                                    <a href="{{ route('test_answers.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Answers</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan --}}



                        {{-- <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Settings</span>
                        </li>
                        @canany(['view home sliders'])
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-chart-donut-3"></i>
                                </span>
                                <span class="hide-menu">Sliders</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('view home sliders')
                                <li class="sidebar-item">
                                    <a href="{{ route('sliders.home.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Home Sliders</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany --}}
                </nav>
                <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3">
                    <div class="hstack gap-3">
                        {{-- <div class="john-img">
                            <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                class="rounded-circle" width="40" height="40" alt="">
                        </div> --}}
                        <div class="john-img">
                            <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                class="rounded-circle" width="40" height="40" alt="User Profile Image">
                        </div>

                        <div class="john-title">
                            <h6 class="mb-0 fs-4 fw-semibold">
                                {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
                            </h6>
                            <span class="fs-2 text-dark">{{ auth()->user()->getRoleNames()->first() }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="border-0 bg-transparent text-primary ms-auto" tabindex="0"
                                type="button" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="logout">
                                <i class="ti ti-power fs-6"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="ti ti-search"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav quick-links d-none d-lg-flex">
                        <li class="nav-item dropdown-hover d-none d-lg-block">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                    </ul>
                    <div class="d-block d-lg-none">
                        <img src="{{ asset('public/logo.png') }}" class="dark-logo" width="180" alt="" />
                        <img src="{{ asset('public/dashboard/dist/images/logos/light-logo.svg') }}" class="light-logo"
                            width="180" alt="" />
                    </div>
                    <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="p-2">
                            <i class="ti ti-dots fs-7"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="javascript:void(0)"
                                class="nav-link d-flex d-lg-none align-items-center justify-content-center"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                aria-controls="offcanvasWithBothOptions">
                                <i class="ti ti-align-justified fs-7"></i>
                            </a>
                            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                                <li class="nav-item dropdown">
                                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-bell-ringing"></i>
                                        <div class="notification bg-primary rounded-circle"></div>
                                    </a>
                                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                        aria-labelledby="drop2">
                                        <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                            <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                            <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
                                        </div>
                                        <div class="message-body" data-simplebar>
                                            <a href="javascript:void(0)"
                                                class="py-6 px-7 d-flex align-items-center dropdown-item">
                                                {{-- <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                                        alt="user" class="rounded-circle" width="48" height="48" />
                                                </span> --}}
                                                <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                                        alt="User Profile Image" class="rounded-circle" width="48"
                                                        height="48" />
                                                </span>
                                                <div class="w-75 d-inline-block v-middle">
                                                    <h6 class="mb-1 fw-semibold">Roman Joined the Team!</h6>
                                                    <span class="d-block">Congratulate him</span>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)"
                                                class="py-6 px-7 d-flex align-items-center dropdown-item">
                                                <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-2.jpg') }}"
                                                        alt="user" class="rounded-circle" width="48" height="48" />
                                                </span>
                                                <div class="w-75 d-inline-block v-middle">
                                                    <h6 class="mb-1 fw-semibold">New message</h6>
                                                    <span class="d-block">Salma sent you new message</span>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)"
                                                class="py-6 px-7 d-flex align-items-center dropdown-item">
                                                <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-3.jpg') }}"
                                                        alt="user" class="rounded-circle" width="48" height="48" />
                                                </span>
                                                <div class="w-75 d-inline-block v-middle">
                                                    <h6 class="mb-1 fw-semibold">Bianca sent payment</h6>
                                                    <span class="d-block">Check your earnings</span>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)"
                                                class="py-6 px-7 d-flex align-items-center dropdown-item">
                                                <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-4.jpg') }}"
                                                        alt="user" class="rounded-circle" width="48" height="48" />
                                                </span>
                                                <div class="w-75 d-inline-block v-middle">
                                                    <h6 class="mb-1 fw-semibold">Jolly completed tasks</h6>
                                                    <span class="d-block">Assign her new tasks</span>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)"
                                                class="py-6 px-7 d-flex align-items-center dropdown-item">
                                                <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-5.jpg') }}"
                                                        alt="user" class="rounded-circle" width="48" height="48" />
                                                </span>
                                                <div class="w-75 d-inline-block v-middle">
                                                    <h6 class="mb-1 fw-semibold">John received payment</h6>
                                                    <span class="d-block">$230 deducted from account</span>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)"
                                                class="py-6 px-7 d-flex align-items-center dropdown-item">
                                                {{-- <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                                        alt="user" class="rounded-circle" width="48" height="48" />
                                                </span> --}}
                                                <span class="me-3">
                                                    <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                                        alt="User Profile Image" class="rounded-circle" width="48"
                                                        height="48" />
                                                </span>
                                                <div class="w-75 d-inline-block v-middle">
                                                    <h6 class="mb-1 fw-semibold">Roman Joined the Team!</h6>
                                                    <span class="d-block">Congratulate him</span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="py-6 px-7 mb-1">
                                            <button class="btn btn-outline-primary w-100"> See All Notifications
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link pe-0" href="javascript:void(0)" id="drop1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{-- <div class="d-flex align-items-center">
                                            <div class="user-profile-img">
                                                <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                                    class="rounded-circle" width="35" height="35" alt="" />
                                            </div>
                                        </div> --}}
                                        <div class="d-flex align-items-center">
                                            <div class="user-profile-img">
                                                <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                                    class="rounded-circle" width="35" height="35"
                                                    alt="User Profile Image" />
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                        aria-labelledby="drop1">
                                        <div class="profile-dropdown position-relative" data-simplebar>
                                            <div class="py-3 px-7 pb-0">
                                                <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                            </div>
                                            <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                                {{-- <img src="dashboard/dist/images/profile/user-1.jpg"
                                                    class="rounded-circle" width="80" height="80" alt="" /> --}}
                                                <img src="{{ asset('public/dashboard/dist/images/profile/user-1.jpg') }}"
                                                    class="rounded-circle" width="80" height="80"
                                                    alt="User Profile Image" />
                                                <div class="ms-3">
                                                    <h5 class="mb-1 fs-3">
                                                        {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
                                                    </h5>
                                                    <span
                                                        class="mb-1 d-block text-dark">{{ auth()->user()->getRoleNames()->first() }}</span>
                                                    <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                                        <i class="ti ti-mail fs-4"></i> {{ auth()->user()->email }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="d-grid py-4 px-7 pt-8">
                                                <form action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-primary w-100">Log
                                                        Out</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <div class="dark-transparent sidebartoggler"></div>
        <div class="dark-transparent sidebartoggler"></div>
    </div>

    <!--  Mobilenavbar -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="mobilenavbar"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <nav class="sidebar-nav scroll-sidebar">
            <div class="offcanvas-header justify-content-between">
                <img src="{{ asset('public/dashboard/dist/images/logos/favicon.ico') }}" alt="" class="img-fluid">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body profile-dropdown mobile-navbar" data-simplebar="" data-simplebar>
                <ul id="sidebarnav">
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span>
                                <i class="ti ti-apps"></i>
                            </span>
                            <span class="hide-menu">Apps</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level my-3">
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-chat.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Chat Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">New messages arrived</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-invoice.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Invoice App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get latest invoice</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-mobile.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Contact Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">2 Unsaved Contacts</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-message-box.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Email App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get new emails</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-cart.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">User Profile</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">learn more information</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-date.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Calendar App</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Get dates</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-lifebuoy.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Contact List Table</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">Add new contact</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item py-2">
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('public/dashboard/dist/images/svgs/icon-dd-application.svg') }}"
                                            alt="" class="img-fluid" width="24" height="24">
                                    </div>
                                    <div class="d-inline-block">
                                        <h6 class="mb-1 bg-hover-primary">Notes Application</h6>
                                        <span class="fs-2 d-block fw-normal text-muted">To-do and Daily tasks</span>
                                    </div>
                                </a>
                            </li>
                            <ul class="px-8 mt-7 mb-4">
                                <li class="sidebar-item mb-3">
                                    <h5 class="fs-5 fw-semibold">Quick Links</h5>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Pricing Page</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Authentication Design</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Register Now</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">404 Error Page</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Notes App</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">User Application</a>
                                </li>
                                <li class="sidebar-item py-2">
                                    <a class="fw-semibold text-dark" href="#">Account Settings</a>
                                </li>
                            </ul>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="app-chat.html" aria-expanded="false">
                            <span>
                                <i class="ti ti-message-dots"></i>
                            </span>
                            <span class="hide-menu">Chat</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="app-calendar.html" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar"></i>
                            </span>
                            <span class="hide-menu">Calendar</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="app-email.html" aria-expanded="false">
                            <span>
                                <i class="ti ti-mail"></i>
                            </span>
                            <span class="hide-menu">Email</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!--  Search Bar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content rounded-1">
                <div class="modal-header border-bottom">
                    <input type="search" class="form-control fs-3" placeholder="Search here" id="search" />
                    <span data-bs-dismiss="modal" class="lh-1 cursor-pointer">
                        <i class="ti ti-x fs-5 ms-3"></i>
                    </span>
                </div>
                <div class="modal-body message-body" data-simplebar="">
                    <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
                    <ul class="list mb-0 py-2">
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Modern</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Dashboard</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Contacts</span>
                                <span class="fs-3 text-muted d-block">/apps/contacts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Posts</span>
                                <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Detail</span>
                                <span
                                    class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Shop</span>
                                <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Modern</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Dashboard</span>
                                <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Contacts</span>
                                <span class="fs-3 text-muted d-block">/apps/contacts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Posts</span>
                                <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Detail</span>
                                <span
                                    class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                            </a>
                        </li>
                        <li class="p-1 mb-1 bg-hover-light-black">
                            <a href="#">
                                <span class="fs-3 text-black fw-normal d-block">Shop</span>
                                <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--  Customizer -->
    <button class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"
        type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="ti ti-settings fs-7" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Settings"></i>
    </button>
    <div class="offcanvas offcanvas-end customizer" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel" data-simplebar="">
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
            <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">Settings</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="theme-option pb-4">
                <h6 class="fw-semibold fs-4 mb-1">Theme Option</h6>
                <div class="d-flex align-items-center gap-3 my-3">
                    <a href="javascript:void(0)"
                        onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style.min.css') }}')"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2 light-theme text-dark">
                        <i class="ti ti-brightness-up fs-7 text-primary"></i>
                        <span class="text-dark">Light</span>
                    </a>
                    <a href="javascript:void(0)"
                        onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style-dark.min.css') }}')"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2 dark-theme text-dark">
                        <i class="ti ti-moon fs-7 "></i>
                        <span class="text-dark">Dark</span>
                    </a>
                </div>
            </div>
            <div class="theme-direction pb-4">
                <h6 class="fw-semibold fs-4 mb-1">Theme Direction</h6>
                <div class="d-flex align-items-center gap-3 my-3">
                    <a href="./index.html"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2">
                        <i class="ti ti-text-direction-ltr fs-6 text-primary"></i>
                        <span class="text-dark">LTR</span>
                    </a>
                    <a href="../rtl/index.html"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2">
                        <i class="ti ti-text-direction-rtl fs-6 text-dark"></i>
                        <span class="text-dark">RTL</span>
                    </a>
                </div>
            </div>
            <div class="theme-colors pb-4">
                <h6 class="fw-semibold fs-4 mb-1">Theme Colors</h6>
                <div class="d-flex align-items-center gap-3 my-3">
                    <ul class="list-unstyled mb-0 d-flex gap-3 flex-wrap change-colors">
                        <li
                            class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)"
                                class="rounded-circle position-relative d-block customizer-bgcolor skin1-bluetheme-primary active-theme "
                                onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style.min.css') }}')"
                                data-color="blue_theme" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="BLUE_THEME"><i
                                    class="ti ti-check text-white d-flex align-items-center justify-content-center fs-5"></i></a>
                        </li>
                        <li
                            class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)"
                                class="rounded-circle position-relative d-block customizer-bgcolor skin2-aquatheme-primary "
                                onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style-aqua.min.css') }}')"
                                data-color="aqua_theme" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="AQUA_THEME"><i
                                    class="ti ti-check  text-white d-flex align-items-center justify-content-center fs-5"></i></a>
                        </li>
                        <li
                            class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)"
                                class="rounded-circle position-relative d-block customizer-bgcolor skin3-purpletheme-primary"
                                onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style-purple.min.css') }}')"
                                data-color="purple_theme" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="PURPLE_THEME"><i
                                    class="ti ti-check  text-white d-flex align-items-center justify-content-center fs-5"></i></a>
                        </li>
                        <li
                            class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)"
                                class="rounded-circle position-relative d-block customizer-bgcolor skin4-greentheme-primary"
                                onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style-green.min.css') }}')"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="GREEN_THEME"><i
                                    class="ti ti-check  text-white d-flex align-items-center justify-content-center fs-5"></i></a>
                        </li>
                        <li
                            class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)"
                                class="rounded-circle position-relative d-block customizer-bgcolor skin5-cyantheme-primary"
                                onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style-cyan.min.css') }}')"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="CYAN_THEME"><i
                                    class="ti ti-check  text-white d-flex align-items-center justify-content-center fs-5"></i></a>
                        </li>
                        <li
                            class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)"
                                class="rounded-circle position-relative d-block customizer-bgcolor skin6-orangetheme-primary"
                                onclick="toggleTheme('{{ asset('public/dashboard/dist/css/style-orange.min.css') }}')"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="ORANGE_THEME"><i
                                    class="ti ti-check  text-white d-flex align-items-center justify-content-center fs-5"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="layout-type pb-4">
                <h6 class="fw-semibold fs-4 mb-1">Layout Type</h6>
                <div class="d-flex align-items-center gap-3 my-3">
                    <a href="./index.html"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2">
                        <i class="ti ti-layout-sidebar fs-6 text-primary"></i>
                        <span class="text-dark">Vertical</span>
                    </a>
                    <a href="../horizontal/index.html"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2">
                        <i class="ti ti-layout-navbar fs-6 text-dark"></i>
                        <span class="text-dark">Horizontal</span>
                    </a>
                </div>
            </div>
            <div class="container-option pb-4">
                <h6 class="fw-semibold fs-4 mb-1">Container Option</h6>
                <div class="d-flex align-items-center gap-3 my-3">
                    <a href="javascript:void(0)"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2 boxed-width text-dark">
                        <i class="ti ti-layout-distribute-vertical fs-7 text-primary"></i>
                        <span class="text-dark">Boxed</span>
                    </a>
                    <a href="javascript:void(0)"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2 full-width text-dark">
                        <i class="ti ti-layout-distribute-horizontal fs-7"></i>
                        <span class="text-dark">Full</span>
                    </a>
                </div>
            </div>
            <div class="sidebar-type pb-4">
                <h6 class="fw-semibold fs-4 mb-1">Sidebar Type</h6>
                <div class="d-flex align-items-center gap-3 my-3">
                    <a href="javascript:void(0)"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2 fullsidebar">
                        <i class="ti ti-layout-sidebar-right fs-7"></i>
                        <span class="text-dark">Full</span>
                    </a>
                    <a href="javascript:void(0)"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center text-dark sidebartoggler gap-2">
                        <i class="ti ti-layout-sidebar fs-7"></i>
                        <span class="text-dark">Collapse</span>
                    </a>
                </div>
            </div>
            <div class="card-with pb-4">
                <h6 class="fw-semibold fs-4 mb-1">Card With</h6>
                <div class="d-flex align-items-center gap-3 my-3">
                    <a href="javascript:void(0)"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2 text-dark cardborder">
                        <i class="ti ti-border-outer fs-7"></i>
                        <span class="text-dark">Border</span>
                    </a>
                    <a href="javascript:void(0)"
                        class="rounded-2 p-9 customizer-box hover-img d-flex align-items-center gap-2 cardshadow">
                        <i class="ti ti-border-none fs-7"></i>
                        <span class="text-dark">Shadow</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--  Customizer -->
    <!--  Import Js Files -->
    <script src="{{ asset('public/dashboard/dist/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!--  core files -->
    <script src="{{ asset('public/dashboard/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/js/app.init.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/js/custom.js') }}"></script>
    {{-- datatable --}}
    <script src="{{ asset('public/dashboard/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    {{-- sweetalert --}}
    <script src="{{ asset('public/dashboard/dist/libs/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    {{-- jquery validate --}}
    <script src="{{ asset('public/dashboard/dist/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    {{-- select2 --}}
    <script src="{{ asset('public/dashboard/dist/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/libs/select2/dist/js/select2.min.js') }}"></script>



    <script src="{{ asset('public/dashboard/dist/js/apps/invoice.js') }}"></script>
    <script src="{{ asset('public/dashboard/dist/js/apps/jquery.PrintArea.js') }}"></script>

    <script>
        // Initialize SweetAlert mixin for success toasts
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>
    @yield('custom_js')
</body>

</html>