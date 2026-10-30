<?php

use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MainHomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ApprovalAuthorityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TimezoneController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeSliderController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\CourseManagersController;
use App\Http\Controllers\InstructorEvaluationAnswerController;
use App\Http\Controllers\InstructorEvaluationQuestionController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\PermissionCategoryController;
use App\Http\Controllers\ProfessionalCategoryController;
use App\Http\Controllers\TestAnswerController;
use App\Http\Controllers\TestQuestionController;
use App\Http\Controllers\StaffController;
// use App\Http\Controllers\TapServiceController;
use App\Http\Controllers\ProjectCategoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TinyMCEUploadController;
use App\Http\Controllers\CkEditorUploadController;
use App\Http\Controllers\About_UsController;
use App\Http\Controllers\About_UsContentController;
use App\Http\Controllers\About_quoteController;
use App\Http\Controllers\eco_systemController;
use App\Http\Controllers\contactController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ChatAssistantController;
use App\Http\Controllers\clientsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ServiceGroupController;
use App\Http\Controllers\AdminInquiryController;
use GuzzleHttp\Client;
use App\Http\Controllers\globalController;
use App\Http\Controllers\googleController;
use App\Http\Controllers\ChatWidgetController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ProjectPlannerController;
use App\Http\Controllers\AdminPlannerController;
use App\Http\Controllers\AdminPlannerBuilderController;
use App\Http\Controllers\AdminProjectProcessController;
use App\Http\Controllers\AdminSettingsController;

// Front-end routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/robots.txt', function () {
    $content = implode("\n", [
        'User-agent: *',
        'Disallow: /admin/',
        'Disallow: /dashboard/',
        'Disallow: /login',
        'Disallow: /register',
        'Disallow: /password/',
        // Form endpoints that answer 405 to a normal visit; the trailing slash keeps /plan-your-project crawlable.
        'Disallow: /plan-your-project/',
        'Disallow: /share-your-experience/submit',
        'Allow: /',
        '',
        'Sitemap: ' . rtrim(config('app.url'), '/') . '/sitemap.xml',
    ]);
    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('front.robots');
Route::get('/service-packages/{slug}', [ServiceGroupController::class, 'front'])->name('service-packages');
Route::get('/service-group-front', function (\Illuminate\Http\Request $r) {
    $slug = $r->query('slug');
    return $slug
        ? redirect()->route('service-packages', $slug, 301)
        : redirect()->route('front.all-services', [], 301);
})->name('service-group-front');
Route::get('/service-group/{slug}/all-services', [ServiceGroupController::class, 'allServices'])->name('service-group.all-services');


// Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [MainHomeController::class, 'index'])->name('home');
Route::get('/load-more-categories', [MainHomeController::class, 'loadMoreCategories'])->name('front.load-more-categories');
// The UAE licensing service became a package page (Part E). Registered before /services/{slug};
// keeps the query string so ad clicks keep their gclid and utm_* values (Route::redirect drops it).
$toUaeLicensing = fn (\Illuminate\Http\Request $r) => redirect()->to(
    route('service-packages', 'healthcare-professional-licensing-uae') . ($r->getQueryString() ? '?' . $r->getQueryString() : ''),
    301
);
Route::get('/services/healthcare-professional-licensing-uae', $toUaeLicensing);
Route::get('/services/{slug}', [MainHomeController::class, 'Service'])->name('front.service');
// Mega menu third column (services per category, guides per topic, case studies), loaded by nav.js on
// first open so those links are not on every page. Not for search engines.
Route::get('/nav-items', fn () => response()->view('front.partials.nav-items')
    ->header('Cache-Control', 'public, max-age=600')
    ->header('X-Robots-Tag', 'noindex'))->name('nav.items');
// Reviewer bio pages (brief "alphahmc-reviewers-and-linking", 30 Oct 2026).
Route::get('/team/{key}', [\App\Http\Controllers\ReviewerController::class, 'show'])->name('front.reviewer');
// Landing pages built in the admin (Landing pages); drafts are an admin-only preview.
Route::get('/facility-setup/{slug}', [\App\Http\Controllers\LandingPageController::class, 'show'])->name('front.landing');
Route::get('/new_service/{slug}', function ($slug) {
    return redirect()->route('front.service', $slug, 301);
});
Route::get('/service-category/{slug}', [MainHomeController::class, 'serviceCategory'])->name('front.service-category');
Route::get('/all_services', [MainHomeController::class, 'allServices'])->name('front.all-services');
Route::get('/services-by-facility-type', [MainHomeController::class, 'facilityTypes'])->name('front.facility-types');
Route::redirect('/services', '/all_services', 301);

// ── Legacy URL 301 redirects — old indexed pages → current pages ──
Route::get('/new-professional-license', $toUaeLicensing);
Route::redirect('/uae-healthcare-professional-licensing', '/services/uae-healthcare-professional-licensing', 301);
Route::redirect('/transfer-professional-license', '/service-category/healthcare-professional-licensing', 301);
Route::redirect('/healthcare-professional-licensing', '/service-category/healthcare-professional-licensing', 301);
Route::redirect('/bahrain-healthcare-professional-license', '/service-category/healthcare-professional-licensing', 301);
Route::redirect('/medical-engineering-services', '/service-category/engineering-design-approvals', 301);
Route::redirect('/qatar-healthcare-professional-license', '/services/healthcare-professional-licensing-qatar', 301);
Route::redirect('/saudi-healthcare-professional-license', '/services/saudi-healthcare-professional-licensing', 301);
Route::redirect('/moh-product-registration', '/service-category/pharmaceutical-product-services', 301);
Route::redirect('/activate-professional-license', '/services/activate-your-uae-healthcare-license', 301);
Route::redirect('/healthcare-insurance-empanelment', '/service-category/insurance-empanelment', 301);
Route::redirect('/careers', '/service-category/healthcare-professional-resourcing', 301);
Route::redirect('/careers/apply-job', '/service-category/healthcare-professional-resourcing', 301);

// ── Old URLs from Search Console (brief of 22 Oct 2026) ──
// Old pages with a matching page today: one-hop 301, query string kept.
// (/careers/ with a trailing slash and any query is answered by nginx before the slash rewrite.)
foreach ([
    '/healthcare-careers'                       => '/service-category/healthcare-professional-resourcing',
    '/facility-auditing-accreditation'          => '/service-category/healthcare-accreditations-certifications',
    '/how-alpha-works'                          => '/how_alpha_work',
    '/oman-healthcare-professional-license'     => '/services/healthcare-professional-licensing-oman',
    '/healthcare-facility-licensing'            => '/service-category/facility-licensing-setup',
    '/healthcare-business-setup'                => '/service-category/facility-licensing-setup',
    '/alpha-services'                           => '/all_services',
    '/self-processing-assistance'               => '/service-category/healthcare-professional-licensing',
    '/healthcare-management-services'           => '/service-packages/healthcare-management-consultancy-in-uae',
    '/healthcare-quality-assurance'             => '/healthcare_quality_assurance',
    '/healthcare-management-outsourcing'        => '/service-category/healthcare-management-outsourcing',
    '/healthcare-professional-resourcing'       => '/service-category/healthcare-professional-resourcing',
    '/healthcare-professional-development'      => '/service-category/training-education',
    '/professional-development-programs'        => '/service-category/training-education',
    '/professional-development-programs.php'    => '/service-category/training-education',
    '/healthcare-feasibility-study'             => '/service-category/healthcare-feasibility-evaluation',
    '/hospital-conceptual-design'               => '/service-category/hospital-design-master-planning',
    '/healthcare-infrastructure-transformation' => '/services/hospital-infrastructure-planning-engineering-design',
    '/alpha-terms-and-conditions'               => '/terms-of-service',
] as $from => $to) {
    Route::get($from, fn (\Illuminate\Http\Request $r) => redirect()->to(
        url($to) . ($r->getQueryString() ? '?' . $r->getQueryString() : ''),
        301
    ));
}
// Old job board pages: 410 Gone with a short "job is no longer open" page (errors/410).
Route::get('/careers/job/{id}', fn () => abort(410, 'job'));
Route::get('/careers/apply-job/{id}', fn () => abort(410, 'job'));
Route::get('/careers/{page}', fn () => abort(410, 'job'))->whereIn('page', ['register', 'signin', 'forgot-password', 'upcoming_jobs']);
// Old Alpha TSM marketing service pages (now on alphatsm.com): 410 Gone (option A).
foreach (['/healthcare-digital-marketing', '/website-development-solution', '/digital-advertisement-solution',
          '/social-media-management', '/instagram-marketing', '/branding-and-designing',
          '/customer-relationship-management', '/healthcare-branding-web-development'] as $gone) {
    Route::get($gone, fn () => abort(410, 'service'));
}

Route::post('/inquiry/submit', [MainHomeController::class, 'submitInquiry'])->middleware('throttle:5,1')->name('front.inquiry.submit');
Route::get('/healthcare-management-update-insights', [MainHomeController::class, 'blog'])->name('front.new_blog');
Route::redirect('/new_blog', '/healthcare-management-update-insights', 301);
// Route::get('/blog_single/{slug}', [MainHomeController::class, 'singleBlog'])->name('front.singleBlog');
Route::get('/blog_single/{slug}', [MainHomeController::class, 'singleBlog'])->name('front.singleBlog');
// Signed 14-day preview links for the reviewer (drafts, and staged revisions of live posts).
Route::get('/blog-preview/revision/{id}', [MainHomeController::class, 'previewBlogRevision'])->name('blogs.preview.revision')->middleware('signed');
Route::get('/blog-preview/{id}', [MainHomeController::class, 'previewBlog'])->name('blogs.preview')->middleware('signed');
Route::get('/project', [MainHomeController::class, 'project'])->name('front.project');
Route::get('/project_details/{slug}', [MainHomeController::class, 'singleProject'])->name('front.project_details');
Route::get('/ahg-updates', [MainHomeController::class, 'newsMedia'])->name('front.ahg-updates');
Route::redirect('/news-media', '/ahg-updates', 301);
Route::get('/Alpha-Health-Group-Branches', [MainHomeController::class, 'brands'])->name('front.brands');
Route::get('/brand/{slug}', [MainHomeController::class, 'singleBrand'])->name('front.singleBrand');
Route::get('/our-clients', [MainHomeController::class, 'ourClients'])->name('front.our-clients');
Route::get('/client-reviews', [MainHomeController::class, 'testimonials'])->name('front.testimonials');
Route::get('/share-your-experience', [MainHomeController::class, 'feedbackForm'])->name('front.feedback');
// The privacy policy covers GDPR and the UAE PDPL; one policy avoids conflicting statements.
Route::get('/gdpr-data-protection', fn () => redirect()->route('front.privacy-policy', [], 301))->name('front.gdpr-terms');

Route::get('/terms-of-service', function () {
    return view('front.terms-of-service');
})->name('front.terms-of-service');

Route::get('/cookie-policy', function () {
    return view('front.cookie-policy');
})->name('front.cookie-policy');

// Linked from the footer and the inquiry form consent; also the privacy URL for the Google Cloud and Meta apps.
Route::get('/alpha-privacy-policy', function () {
    return view('front.privacy-policy');
})->name('front.privacy-policy');
Route::post('/share-your-experience/submit', [MainHomeController::class, 'submitTestimonial'])->middleware('throttle:5,1')->name('front.testimonial.submit');

// Route::post('/ckeditor/upload', [TinyMCEUploadController::class, 'upload'])->name('ckeditor.upload');
// Editor upload is only used inside the authenticated dashboard — require auth + throttle to stop anonymous disk-fill abuse.
Route::post('/ckeditor/upload', [CkEditorUploadController::class, 'upload'])->middleware(['auth', 'throttle:30,1'])->name('ckeditor.upload');

Route::get('/about-alpha-health-group', [MainHomeController::class, 'about'])->name('front.new-about');
Route::redirect('/new-about', '/about-alpha-health-group', 301);

// Route::post('/chat', [ChatWidgetController::class, 'reply'])
//     ->middleware('throttle:30,1');   // 30 requests per minute per IP



Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/send', [MainHomeController::class, 'sendContact'])->middleware('throttle:5,1')->name('contact.send');

Route::post('/set-timezone', [TimezoneController::class, 'setTimezone'])->name('set_timezone');
Route::get('/blog', [HomeController::class, 'blog'])->name('front.blog');
Route::get('/blogs/{tag_name}', [HomeController::class, 'viewTag'])->name('view_tag');
Route::get('/blog/{slug}', [HomeController::class, 'viewBlog'])->name('view_blog');

Route::get('/how_alpha_work', [HomeController::class, 'how_alpha_work'])->name('how_alpha_work');

// Interactive Project Planner (public)
Route::get('/plan-your-project', [ProjectPlannerController::class, 'page'])->name('planner.page');
Route::post('/plan-your-project/step', [ProjectPlannerController::class, 'step'])->middleware('throttle:30,1')->name('planner.step');
Route::post('/plan-your-project/analyze', [ProjectPlannerController::class, 'analyze'])->middleware('throttle:10,1')->name('planner.analyze');
Route::post('/plan-your-project/contact', [ProjectPlannerController::class, 'contact'])->middleware('throttle:10,1')->name('planner.contact');
Route::post('/plan-your-project/followup', [ProjectPlannerController::class, 'followup'])->middleware('throttle:10,1')->name('planner.followup');
Route::get('/plan-your-project/services-by-category', [ProjectPlannerController::class, 'servicesByCategory'])->name('planner.servicesByCategory');
Route::get('/healthcare_quality_assurance', [HomeController::class, 'healthcare_quality_assurance'])->name('healthcare_quality_assurance');

// Search routes (public - no auth required)
Route::get('/search', [SearchController::class, 'index'])->name('front.search');
Route::get('/search/live', [SearchController::class, 'live'])->name('front.search.live');
Route::post('/ai-assistant/chat', [ChatAssistantController::class, 'chat'])->middleware('throttle:20,1')->name('ai.assistant.chat');


Route::middleware(['auth', 'check_student_profile'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('role:Admin');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('role:Admin');
    Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update')->middleware('role:Admin');
    Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('role:Admin');
    Route::post('/roles/get', [RoleController::class, 'getRole'])->name('roles.get')->middleware('role:Admin');

    // permission routes
    Route::get('/permissions', [PermissionController::class, 'index'])->name('roles.permissions.index')->middleware('permission:view permissions');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('roles.permissions.store')->middleware('permission:create permissions');
    Route::delete('/permissions/delete/{id}', [PermissionController::class, 'destroy'])->name('roles.permissions.destroy')->middleware('permission:delete permissions');
    Route::post('/permissions/update/{id}', [PermissionController::class, 'update'])->name('roles.permissions.update')->middleware('permission:edit permissions');
    Route::post('/permissions/get', [PermissionController::class, 'getPermission'])->name('roles.permissions.get')->middleware('permission:view permissions');
    //permissions category routes
    Route::get('/permission-categories', [PermissionCategoryController::class, 'index'])->name('roles.permission_categories.index')->middleware('permission:view permissions categories');
    Route::post('/permission-categories', [PermissionCategoryController::class, 'store'])->name('roles.permission_categories.store')->middleware('permission:create permissions categories');
    Route::delete('/permission-categories/delete/{id}', [PermissionCategoryController::class, 'destroy'])->name('roles.permission_categories.destroy')->middleware('permission:delete permissions categories');
    Route::post('/permission-categories/update/{id}', [PermissionCategoryController::class, 'update'])->name('roles.permission_categories.update')->middleware('permission:edit permissions categories');
    Route::post('/permission-categories/get', [PermissionCategoryController::class, 'getPermissionCategory'])->name('roles.permission_categories.get')->middleware('permission:view permissions categories');
    // role permission
    Route::get('/role/{roleId}/permissions', [RoleController::class, 'rolePermissions'])->name('role.permissions')->middleware('role:Admin');
    Route::post('/role/{roleId}/update-permissions', [RoleController::class, 'updateRolePermissions'])->name('role.update_permissions')->middleware('role:Admin');

    // admin routes
    Route::get('/admins', [AdminController::class, 'index'])->name('users.admins')->middleware('permission:view admins');
    Route::post('/admins', [AdminController::class, 'store'])->name('admins.store')->middleware('permission:create admins');
    Route::delete('/admins/delete/{id}', [AdminController::class, 'destroy'])->name('admins.destroy')->middleware('permission:delete admins');
    Route::post('/admins/update/{id}', [AdminController::class, 'update'])->name('admins.update')->middleware('permission:edit admins');
    Route::post('/admins/get', [AdminController::class, 'getAdmin'])->name('admins.get');

    // agent routes
    Route::get('/agents', [AgentController::class, 'index'])->name('users.agents')->middleware('permission:view agents');
    Route::post('/agents', [AgentController::class, 'store'])->name('agents.store')->middleware('permission:create agents');
    Route::delete('/agents/delete/{id}', [AgentController::class, 'destroy'])->name('agents.destroy')->middleware('permission:delete agents');
    Route::post('/agents/update/{id}', [AgentController::class, 'update'])->name('agents.update')->middleware('permission:edit agents');
    Route::post('/agents/get', [AgentController::class, 'getAgent'])->name('agents.get')->middleware('permission:view agents');

    // all users routes
    Route::get('/all-users', [UserController::class, 'index'])->name('all_users.index')->middleware('permission:view users');
    // Per-user access manager (roles + direct permissions)
    Route::get('/users/{id}/permissions', [UserController::class, 'permissions'])->name('all_users.permissions')->middleware('permission:edit users');
    Route::post('/users/{id}/permissions', [UserController::class, 'updatePermissions'])->name('all_users.permissions.update')->middleware('permission:edit users');

    // SEO Overview — meta listings per content type, with inline editing (perms checked in controller)
    Route::get('/dashboard/seo/{type}', [\App\Http\Controllers\SeoOverviewController::class, 'show'])
        ->whereIn('type', ['services', 'service-groups', 'categories', 'blogs', 'brands'])->name('seo.overview');
    Route::post('/dashboard/seo/{type}/{id}', [\App\Http\Controllers\SeoOverviewController::class, 'update'])
        ->whereIn('type', ['services', 'service-groups', 'categories', 'blogs', 'brands'])->name('seo.update');

    //main category routes
    Route::get('/all-main-categories', [MainCategoryController::class, 'index'])->name('main_categories.index')->middleware('permission:view main categories');
    Route::post('/main-categories', [MainCategoryController::class, 'store'])->name('main_categories.store')->middleware('permission:create main categories');
    Route::delete('/main-categories/delete/{id}', [MainCategoryController::class, 'destroy'])->name('main_categories.destroy')->middleware('permission:delete main categories');
    Route::post('/main-categories/update/{id}', [MainCategoryController::class, 'update'])->name('main_categories.update')->middleware('permission:edit main categories');
    Route::post('/main-categories/get', [MainCategoryController::class, 'getCategory'])->name('main_categories.get');
    Route::post('/main-categories/reorder', [MainCategoryController::class, 'reorder'])->name('main_categories.reorder');
    // category routes
    Route::get('/all-categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:view categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:create categories');
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:edit categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:create categories');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:delete categories');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:edit categories');
    Route::post('/categories/get', [CategoryController::class, 'getCategory'])->name('categories.get');
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder')->middleware('permission:edit categories');
    Route::post('/categories/toggle-featured/{id}', [CategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured')->middleware('permission:edit categories');
    Route::post('/categories/{id}/map-services', [CategoryController::class, 'mapServices'])->name('categories.map-services')->middleware('permission:edit categories');

    // Stub: the live dashboard layout links to route('strategy.index'), but the
    // strategy routes only ever existed in the host's old route cache. Redirects
    // to the dashboard until the real Strategies module routes are restored.
    Route::get('/strategies', function () {
        return redirect()->route('dashboard');
    })->name('strategy.index');
    Route::post('/categories/delete-gallery-image', [CategoryController::class, 'deleteGalleryImage'])->name('categories.delete-gallery-image');

    // service-group routes
    Route::get('/service-group', [ServiceGroupController::class, 'index'])->name('service-group.index')->middleware('permission:view service groups');
    Route::get('/service-group/create', [ServiceGroupController::class, 'create'])->name('service-group.create')->middleware('permission:create service groups');
    Route::post('/service-group', [ServiceGroupController::class, 'store'])->name('service-group.store')->middleware('permission:create service groups');
    Route::post('/service-group/get', [ServiceGroupController::class, 'get'])->name('service-group.get')->middleware('permission:view service groups');
    Route::get('/service-group/{id}/edit', [ServiceGroupController::class, 'edit'])->name('service-group.edit')->middleware('permission:edit service groups');
    Route::put('/service-group/{id}', [ServiceGroupController::class, 'update'])->name('service-group.update')->middleware('permission:edit service groups');
    Route::post('/service-group/{id}', [ServiceGroupController::class, 'update'])->name('service-group.update.post')->middleware('permission:edit service groups');
    Route::delete('/service-group/{id}', [ServiceGroupController::class, 'destroy'])->name('service-group.destroy')->middleware('permission:delete service groups');
    Route::post('/service-group/{id}/toggle-status', [ServiceGroupController::class, 'toggleStatus'])->name('service-group.toggle-status')->middleware('permission:edit service groups');
    Route::post('/service-group/{id}/toggle-featured', [ServiceGroupController::class, 'toggleFeatured'])->name('service-group.toggle-featured')->middleware('permission:edit service groups');


    

    // inquiry routes
    Route::get('/dashboard/inquiries', [AdminInquiryController::class, 'index'])->name('admin.inquiries.index')->middleware('permission:view inquiries');
    Route::get('/dashboard/inquiries/{id}', [AdminInquiryController::class, 'show'])->name('admin.inquiries.show')->middleware('permission:view inquiries');
    Route::post('/dashboard/inquiries/update/{id}', [AdminInquiryController::class, 'update'])->name('admin.inquiries.update')->middleware('permission:edit inquiries');
    Route::post('/dashboard/inquiries/reply/{id}', [AdminInquiryController::class, 'reply'])->name('admin.inquiries.reply')->middleware('permission:edit inquiries');
    Route::delete('/dashboard/inquiries/delete/{id}', [AdminInquiryController::class, 'destroy'])->name('admin.inquiries.destroy')->middleware('permission:delete inquiries');

    // Project Planner CRM
    Route::get('/dashboard/planner', [AdminPlannerController::class, 'index'])->name('admin.planner.index')->middleware('permission:view planner');
    Route::get('/dashboard/planner/{id}', [AdminPlannerController::class, 'show'])->name('admin.planner.show')->middleware('permission:view planner');
    Route::post('/dashboard/planner/update/{id}', [AdminPlannerController::class, 'update'])->name('admin.planner.update')->middleware('permission:edit planner');
    Route::post('/dashboard/planner/{id}/confirm-meeting', [AdminPlannerController::class, 'confirmMeeting'])->name('admin.planner.confirmMeeting')->middleware('permission:edit planner');
    Route::get('/dashboard/planner-outcomes', [AdminPlannerController::class, 'outcomes'])->name('admin.planner.outcomes')->middleware('permission:view planner');
    Route::post('/dashboard/planner/{id}/save-outcome', [AdminPlannerController::class, 'saveOutcome'])->name('admin.planner.saveOutcome')->middleware('permission:edit planner');
    Route::post('/dashboard/planner/{id}/cache-outcome', [AdminPlannerController::class, 'cacheOutcome'])->name('admin.planner.cacheOutcome')->middleware('permission:edit planner');

    // AI Planner workflow builder
    Route::get('/dashboard/planner-builder', [AdminPlannerBuilderController::class, 'index'])->name('admin.planner.builder')->middleware('permission:view planner builder');
    Route::post('/dashboard/planner-builder/reorder', [AdminPlannerBuilderController::class, 'reorder'])->name('admin.planner.builder.reorder')->middleware('permission:edit planner builder');
    Route::post('/dashboard/planner-builder/add', [AdminPlannerBuilderController::class, 'store'])->name('admin.planner.builder.add')->middleware('permission:create planner builder');
    Route::post('/dashboard/planner-builder/{id}', [AdminPlannerBuilderController::class, 'update'])->name('admin.planner.builder.update')->middleware('permission:edit planner builder');
    Route::delete('/dashboard/planner-builder/{id}', [AdminPlannerBuilderController::class, 'destroy'])->name('admin.planner.builder.destroy')->middleware('permission:delete planner builder');
    Route::delete('/dashboard/planner/delete/{id}', [AdminPlannerController::class, 'destroy'])->name('admin.planner.destroy')->middleware('permission:delete planner');

    // Mega-menu promo slides (max 3, auto-rotating in the menu's default panel)
    // Landing pages (brief "alphahmc-facility-landing-build", 30 Oct 2026)
    Route::get('/dashboard/landing-pages', [App\Http\Controllers\AdminLandingPageController::class, 'index'])->name('admin.landing-pages.index')->middleware('permission:view landing pages');
    Route::get('/dashboard/landing-pages/create', [App\Http\Controllers\AdminLandingPageController::class, 'create'])->name('admin.landing-pages.create')->middleware('permission:create landing pages');
    Route::post('/dashboard/landing-pages', [App\Http\Controllers\AdminLandingPageController::class, 'store'])->name('admin.landing-pages.store')->middleware('permission:create landing pages');
    Route::get('/dashboard/landing-pages/{id}/edit', [App\Http\Controllers\AdminLandingPageController::class, 'edit'])->name('admin.landing-pages.edit')->middleware('permission:view landing pages');
    Route::post('/dashboard/landing-pages/{id}', [App\Http\Controllers\AdminLandingPageController::class, 'update'])->name('admin.landing-pages.update')->middleware('permission:edit landing pages');
    Route::post('/dashboard/landing-pages/{id}/duplicate', [App\Http\Controllers\AdminLandingPageController::class, 'duplicate'])->name('admin.landing-pages.duplicate')->middleware('permission:create landing pages');
    Route::delete('/dashboard/landing-pages/{id}', [App\Http\Controllers\AdminLandingPageController::class, 'destroy'])->name('admin.landing-pages.destroy')->middleware('permission:delete landing pages');
    Route::get('/dashboard/landing-pages/{id}/history', [App\Http\Controllers\AdminLandingPageController::class, 'history'])->name('admin.landing-pages.history')->middleware('permission:view landing pages');
    Route::post('/dashboard/landing-pages/{id}/revisions/{revision}/restore', [App\Http\Controllers\AdminLandingPageController::class, 'restore'])->name('admin.landing-pages.restore')->middleware('permission:edit landing pages');

    Route::get('/dashboard/menu-promos', [App\Http\Controllers\MenuPromoController::class, 'index'])->name('admin.menu-promos.index')->middleware('permission:view menu promos');
    Route::post('/dashboard/menu-promos', [App\Http\Controllers\MenuPromoController::class, 'store'])->name('admin.menu-promos.store')->middleware('permission:create menu promos');
    Route::post('/dashboard/menu-promos/{id}', [App\Http\Controllers\MenuPromoController::class, 'update'])->name('admin.menu-promos.update')->middleware('permission:edit menu promos');
    Route::delete('/dashboard/menu-promos/{id}', [App\Http\Controllers\MenuPromoController::class, 'destroy'])->name('admin.menu-promos.destroy')->middleware('permission:delete menu promos');

    // Pages & SEO — manage standard pages' SEO tags + hero content
    Route::get('/dashboard/pages', [App\Http\Controllers\PageSettingController::class, 'index'])->name('admin.pages.index')->middleware('permission:view pages');
    Route::get('/dashboard/pages/{key}/edit', [App\Http\Controllers\PageSettingController::class, 'edit'])->name('admin.pages.edit')->middleware('permission:edit pages');
    Route::post('/dashboard/pages/{key}', [App\Http\Controllers\PageSettingController::class, 'update'])->name('admin.pages.update')->middleware('permission:edit pages');

    // Project Process Manager — reusable processes assigned to many categories / service groups
    Route::get('/dashboard/project-process', [AdminProjectProcessController::class, 'index'])->name('admin.project-process.index')->middleware('permission:view project process');
    Route::get('/dashboard/project-process/create', [AdminProjectProcessController::class, 'create'])->name('admin.project-process.create')->middleware('permission:create project process');
    Route::post('/dashboard/project-process', [AdminProjectProcessController::class, 'store'])->name('admin.project-process.store')->middleware('permission:create project process');
    Route::get('/dashboard/project-process/{id}/edit', [AdminProjectProcessController::class, 'edit'])->name('admin.project-process.edit')->middleware('permission:edit project process');
    Route::post('/dashboard/project-process/{id}', [AdminProjectProcessController::class, 'update'])->name('admin.project-process.update')->middleware('permission:edit project process');
    Route::delete('/dashboard/project-process/{id}', [AdminProjectProcessController::class, 'destroy'])->name('admin.project-process.destroy')->middleware('permission:delete project process');

    // App / AI settings
    Route::get('/dashboard/settings', [AdminSettingsController::class, 'edit'])->name('admin.settings.edit')->middleware('permission:view settings');
    Route::post('/dashboard/settings', [AdminSettingsController::class, 'save'])->name('admin.settings.save')->middleware('permission:edit settings');
    Route::post('/dashboard/settings/test', [AdminSettingsController::class, 'test'])->name('admin.settings.test')->middleware('permission:edit settings');

    // service routes
    Route::get('all-services', [ServiceController::class, 'index'])->name('services.index')->middleware('permission:view services');
    Route::get('dashboard/services/create', [ServiceController::class, 'create'])->name('services.create')->middleware('permission:view create services');
    Route::post('services', [ServiceController::class, 'store'])->name('services.store')->middleware('permission:create services');
    Route::get('services/edit/{id}', [ServiceController::class, 'edit'])->name('services.edit');
    Route::post('/services/update/{id}', [ServiceController::class, 'update'])->name('services.update')->middleware('permission:edit services');
    Route::delete('/services/delete/{id}', [ServiceController::class, 'destroy'])->name('services.destroy')->middleware('permission:delete services');
    Route::post('/services/get', [ServiceController::class, 'getService'])->name('services.get');
    Route::post('/services/featured', [ServiceController::class, 'featuredHandle'])->name('services.featured.change');
    Route::post('/services/{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle_status');
    Route::post('/services/{id}/move-to-group', [ServiceController::class, 'moveToServiceGroup'])->name('services.move-to-group')->middleware('permission:edit services');
    Route::post('/services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder')->middleware('permission:edit services');
    Route::post('/services/upload-documents/{id}', [ServiceController::class, 'uploadDocuments'])->name('services.upload_documents');

    // -- Service Magazine (Insights) CRUD routes --
    Route::get('/services/{serviceId}/magazines/create', [\App\Http\Controllers\ServiceMagazineController::class, 'create'])->name('service.magazines.create')->middleware('permission:create magazines');
    Route::post('/services/{serviceId}/magazines', [\App\Http\Controllers\ServiceMagazineController::class, 'store'])->name('service.magazines.store')->middleware('permission:create magazines');
    Route::get('/services/{serviceId}/magazines/{magazineId}/edit', [\App\Http\Controllers\ServiceMagazineController::class, 'edit'])->name('service.magazines.edit')->middleware('permission:edit magazines');
    Route::put('/services/{serviceId}/magazines/{magazineId}', [\App\Http\Controllers\ServiceMagazineController::class, 'update'])->name('service.magazines.update')->middleware('permission:edit magazines');
    Route::delete('/services/{serviceId}/magazines/{magazineId}', [\App\Http\Controllers\ServiceMagazineController::class, 'destroy'])->name('service.magazines.destroy')->middleware('permission:delete magazines');

    // announcement routes
    Route::get('/all-announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index')->middleware('permission:view announcements');
    Route::get('/announcements', function () {
        return redirect()->route('announcements.index');
    });
    Route::get('/announcements/create', [\App\Http\Controllers\AnnouncementController::class, 'create'])->name('announcements.create')->middleware('permission:create announcements');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store')->middleware('permission:create announcements');
    Route::get('/announcements/edit/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'edit'])->name('announcements.edit')->middleware('permission:edit announcements');
    Route::post('/announcements/update/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'update'])->name('announcements.update')->middleware('permission:edit announcements');
    Route::delete('/announcements/delete/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy')->middleware('permission:delete announcements');

    // profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:edit profile');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('permission:delete profile');

    // home slider routes
    Route::get('/home-sliders', [HomeSliderController::class, 'index'])->name('sliders.home.index')->middleware('permission:view home sliders');
    Route::post('/home-sliders', [HomeSliderController::class, 'store'])->name('sliders.home.store')->middleware('permission:create home sliders');
    Route::delete('/home-sliders/delete/{id}', [HomeSliderController::class, 'destroy'])->name('sliders.home.destroy')->middleware('permission:delete home sliders');
    Route::post('/home-sliders/update/{id}', [HomeSliderController::class, 'update'])->name('sliders.home.update')->middleware('permission:edit home sliders');
    Route::post('/home-sliders/get', [HomeSliderController::class, 'getSlider'])->name('sliders.home.get');
    Route::post('/home-sliders/status', [HomeSliderController::class, 'statusHandle'])->name('sliders.status.change');

    // blog routes (dashboard blog manager — URL is /manage-blog; route names kept as blogs.* )
    Route::get('/manage-blog', [BlogController::class, 'index'])->name('blogs.index')->middleware('permission:view blogs');
    Route::post('/manage-blog', [BlogController::class, 'store'])->name('blogs.store')->middleware('permission:create blogs');
    Route::delete('/manage-blog/delete/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy')->middleware('permission:delete blogs');
    Route::post('/manage-blog/update/{id}', [BlogController::class, 'update'])->name('blogs.update')->middleware('permission:edit blogs');
    Route::post('/manage-blog/get', [BlogController::class, 'getBlog'])->name('blogs.get')->middleware('permission:view blogs');
    Route::post('/manage-blog/featured', [BlogController::class, 'featuredHandle'])->name('blogs.featured.change');
    Route::post('/manage-blog/reorder', [BlogController::class, 'reorder'])->name('blogs.reorder')->middleware('permission:edit blogs');
    // Drafts and staged revisions from the SEO agent go live only through these (a person, edit blogs).
    Route::post('/manage-blog/publish/{id}', [BlogController::class, 'publish'])->name('blogs.publish')->middleware('permission:edit blogs');
    Route::post('/manage-blog/revision/{id}/publish', [BlogController::class, 'publishRevision'])->name('blogs.revision.publish')->middleware('permission:edit blogs');
    Route::post('/manage-blog/revision/{id}/discard', [BlogController::class, 'discardRevision'])->name('blogs.revision.discard')->middleware('permission:edit blogs');

    // tags Routes (blog)
    Route::get('/tags', [TagController::class, 'index'])->name('blog.tags.index')->middleware('permission:view tags');
    Route::post('/tags', [TagController::class, 'store'])->name('blog.tags.store')->middleware('permission:create tags');
    Route::delete('/tags/delete/{id}', [TagController::class, 'destroy'])->name('blog.tags.destroy')->middleware('permission:delete tags');
    Route::post('/tags/update/{id}', [TagController::class, 'update'])->name('blog.tags.update')->middleware('permission:edit tags');
    Route::post('/tags/get', [TagController::class, 'getTag'])->name('blog.tags.get');

    // Test Question routes
    Route::get('/all-test-questions', [TestQuestionController::class, 'index'])->name('test_questions.index')->middleware('permission:view test questions');
    Route::post('/test-question', [TestQuestionController::class, 'store'])->name('test_questions.store')->middleware('permission:view test questions');
    Route::delete('/test-question/delete/{id}', [TestQuestionController::class, 'destroy'])->name('test_questions.destroy')->middleware('permission:view test questions');
    Route::post('/test-question/update/{id}', [TestQuestionController::class, 'update'])->name('test_questions.update')->middleware('permission:view test questions');
    Route::post('/test-question/get', [TestQuestionController::class, 'getTestQuestion'])->name('test_questions.get');
    // Test Answer routes
    Route::get('/all-test-answers', [TestAnswerController::class, 'index'])->name('test_answers.index')->middleware('permission:view test answers');
    Route::post('/test-answer', [TestAnswerController::class, 'store'])->name('test_answers.store')->middleware('permission:view test answers');
    Route::delete('/test-answer/delete/{id}', [TestAnswerController::class, 'destroy'])->name('test_answers.destroy')->middleware('permission:view test answers');
    Route::post('/test-answer/update/{id}', [TestAnswerController::class, 'update'])->name('test_answers.update')->middleware('permission:view test answers');
    Route::post('/test-answer/get', [TestAnswerController::class, 'getTestAnswer'])->name('test_answers.get');


    // tab-services
    // Route::get('/tab-services', [TapServiceController::class, 'index'])->name('tab_services.index');
    // Route::post('/tab-services', [TapServiceController::class, 'store'])->name('tab_services.store');
    // Route::post('/tab-services/get', [TapServiceController::class, 'getTapService'])->name('tab_services.getTapService');
    // Route::post('/tab-services/update/{id}', [TapServiceController::class, 'update'])->name('tab_services.update');
    // Route::delete('/tab-services/destroy/{id}', [TapServiceController::class, 'destroy'])->name('tab_services.destroy');
    // Route::post('/tags/get', [TagController::class, 'getTag'])->name('blog.tags.get');

    // Projects category

    Route::get('/projects-category', [ProjectCategoryController::class, 'index'])->name('project.category.index')->middleware('permission:view project categories');
    Route::post('/projects-category', [ProjectCategoryController::class, 'store'])->name('project_categories.store')->middleware('permission:create project categories');
    Route::delete('/projects-category/delete/{id}', [ProjectCategoryController::class, 'destroy'])->name('project.category.destroy')->middleware('permission:delete project categories');
    Route::post('/projects-category/update/{id}', [ProjectCategoryController::class, 'update'])->name('project.category.update')->middleware('permission:edit project categories');
    Route::post('/projects-category/get', [ProjectCategoryController::class, 'getCategory'])->name('project.category.get')->middleware('permission:view project categories');


    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index')->middleware('permission:view projects');
    Route::post('/projects-store', [ProjectController::class, 'store'])->name('project.store')->middleware('permission:create projects');
    Route::post('/projects/get', [ProjectController::class, 'getProject'])->name('project.getProject')->middleware('permission:view projects');
    Route::post('/projects/reorder', [ProjectController::class, 'reorder'])->name('project.reorder')->middleware('permission:edit projects');
    Route::post('/projects/update/{id}', [ProjectController::class, 'update'])->name('project.update')->middleware('permission:edit projects');
    Route::delete('/projects/destroy/{id}', [ProjectController::class, 'destroy'])->name('project.destroy')->middleware('permission:delete projects');


    //About us page

    Route::prefix('about-us')->group(function () {
        Route::get('/', [About_UsController::class, 'index'])->name('about_us.index')->middleware('permission:view about us');
        Route::post('/', [About_UsController::class, 'store'])->name('about_us.store')->middleware('permission:create about us');

        // AJAX routes
        Route::post('/get', [About_UsController::class, 'get'])->name('about_us.get')->middleware('permission:view about us');
        Route::post('/update/{id}', [About_UsController::class, 'update'])->name('about_us.update')->middleware('permission:edit about us');
        Route::delete('/destroy/{id}', [About_UsController::class, 'destroy'])->name('about_us.destroy')->middleware('permission:delete about us');
    });

    // About us content routes
    Route::prefix('content')->group(function () {
        Route::get('/', [About_UsContentController::class, 'index'])->name('about_us.content.index')->middleware('permission:view about us');
        Route::post('/', [About_UsContentController::class, 'store'])->name('about_us.content.store')->middleware('permission:create about us');
        Route::post('/get', [About_UsContentController::class, 'get'])->name('about_us.content.get')->middleware('permission:view about us');
        Route::post('/update/{id}', [About_UsContentController::class, 'update'])->name('about_us.content.update')->middleware('permission:edit about us');
        Route::delete('/destroy/{id}', [About_UsContentController::class, 'destroy'])->name('about_us.content.destroy')->middleware('permission:delete about us');
    });


    //contact page

    Route::prefix('testimonial')->group(function() {
        Route::get('/', [ContactController::class,'index'])->name('testimonial.index')->middleware('permission:view testimonials');
        Route::post('/store', [ContactController::class, 'store'])->name('testimonial.store')->middleware('permission:create testimonials');
        Route::post('/get', [ContactController::class, 'getTestimonial'])->name('testimonial.get')->middleware('permission:view testimonials');
        Route::post('/update/{id}', [ContactController::class, 'update'])->name('testimonial.update')->middleware('permission:edit testimonials');
        Route::delete('/delete/{id}', [ContactController::class, 'destroy'])->name('testimonial.destroy')->middleware('permission:delete testimonials');
        Route::post('/delete-bulk', [ContactController::class, 'destroyBulk'])->name('testimonial.destroyBulk')->middleware('permission:delete testimonials');
        Route::post('/toggle-featured/{id}', [ContactController::class, 'toggleFeatured'])->name('testimonial.toggleFeatured')->middleware('permission:edit testimonials');
        Route::post('/toggle-approved/{id}', [ContactController::class, 'toggleApproved'])->name('testimonial.toggleApproved')->middleware('permission:edit testimonials');
        Route::post('/settings', [ContactController::class, 'saveSettings'])->name('testimonial.saveSettings')->middleware('permission:edit testimonials');
    });

   


    //clients route
    Route::prefix('dashboard/clients')->group(function () {
        Route::get('/',[clientsController::class, 'index'])->name('dashboard.clients.index')->middleware('permission:view clients');
        Route::post('/store',[clientsController::class, 'store'])->name('dashboard.clients.store')->middleware('permission:create clients');
        Route::get('/get',[clientsController::class, 'getClient'])->name('dashboard.clients.get')->middleware('permission:view clients');
        Route::post('/update/{id}',[clientsController::class, 'update'])->name('dashboard.clients.update')->middleware('permission:edit clients');
        Route::delete('/destroy/{id}',[clientsController::class, 'destroy'])->name('dashboard.clients.destroy')->middleware('permission:delete clients');
        Route::post('/reorder',[clientsController::class, 'reorder'])->name('dashboard.clients.reorder')->middleware('permission:edit clients');
        Route::post('/hero',[clientsController::class, 'updateHero'])->name('dashboard.clients.updateHero')->middleware('permission:edit clients');
        Route::post('/toggle-featured/{id}',[clientsController::class, 'toggleFeatured'])->name('dashboard.clients.toggle-featured')->middleware('permission:edit clients');
        Route::post('/toggle-status/{id}',[clientsController::class, 'toggleStatus'])->name('dashboard.clients.toggle-status')->middleware('permission:edit clients');
    });

    //brands route
    Route::prefix('dashboard/brands')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('dashboard.brands.index')->middleware('permission:view brands');
        Route::post('/store', [BrandController::class, 'store'])->name('dashboard.brands.store')->middleware('permission:create brands');
        Route::post('/reorder', [BrandController::class, 'reorder'])->name('dashboard.brands.reorder')->middleware('permission:edit brands');
        Route::get('/get', [BrandController::class, 'getBrand'])->name('dashboard.brands.get')->middleware('permission:view brands');
        Route::post('/update/{id}', [BrandController::class, 'update'])->name('dashboard.brands.update')->middleware('permission:edit brands');
        Route::delete('/destroy/{id}', [BrandController::class, 'destroy'])->name('dashboard.brands.destroy')->middleware('permission:delete brands');
        Route::post('/update-hero', [BrandController::class, 'updateHero'])->name('dashboard.brands.updateHero')->middleware('permission:edit brands');
    });

    //About_quote routes
    Route::prefix('about-quote')->group(function () {
        Route::get('/', [About_quoteController::class, 'index'])->name('about_quote.index')->middleware('permission:view about quote');
        Route::post('/store', [About_quoteController::class, 'store'])->name('about_quote.store')->middleware('permission:create about quote');
        Route::post('/get', [About_quoteController::class, 'get'])->name('about_quote.get')->middleware('permission:view about quote');
        Route::post('/update/{id}', [About_quoteController::class, 'update'])->name('about_quote.update')->middleware('permission:edit about quote');
        Route::delete('/destroy/{id}', [About_quoteController::class, 'destroy'])->name('about_quote.destroy')->middleware('permission:delete about quote');
    });

    //About counters (stats strip) routes
    Route::prefix('about-counters')->group(function () {
        Route::get('/', [App\Http\Controllers\AboutCounterController::class, 'index'])->name('about_counters.index')->middleware('permission:view about counters');
        Route::post('/', [App\Http\Controllers\AboutCounterController::class, 'store'])->name('about_counters.store')->middleware('permission:create about counters');
        Route::post('/get', [App\Http\Controllers\AboutCounterController::class, 'get'])->name('about_counters.get')->middleware('permission:view about counters');
        Route::post('/reorder', [App\Http\Controllers\AboutCounterController::class, 'reorder'])->name('about_counters.reorder')->middleware('permission:edit about counters');
        Route::post('/update/{id}', [App\Http\Controllers\AboutCounterController::class, 'update'])->name('about_counters.update')->middleware('permission:edit about counters');
        Route::delete('/destroy/{id}', [App\Http\Controllers\AboutCounterController::class, 'destroy'])->name('about_counters.destroy')->middleware('permission:delete about counters');
    });

    //About staff / leadership team routes
    Route::prefix('about-staff')->group(function () {
        Route::get('/', [App\Http\Controllers\AboutStaffController::class, 'index'])->name('about_staff.index')->middleware('permission:view about staff');
        Route::post('/', [App\Http\Controllers\AboutStaffController::class, 'store'])->name('about_staff.store')->middleware('permission:create about staff');
        Route::post('/get', [App\Http\Controllers\AboutStaffController::class, 'get'])->name('about_staff.get')->middleware('permission:view about staff');
        Route::post('/reorder', [App\Http\Controllers\AboutStaffController::class, 'reorder'])->name('about_staff.reorder')->middleware('permission:edit about staff');
        Route::post('/update/{id}', [App\Http\Controllers\AboutStaffController::class, 'update'])->name('about_staff.update')->middleware('permission:edit about staff');
        Route::delete('/destroy/{id}', [App\Http\Controllers\AboutStaffController::class, 'destroy'])->name('about_staff.destroy')->middleware('permission:delete about staff');
    });

    //tags routes


Route::get('/global', [globalController::class, 'globaltag'])->name('globaltag')->middleware('permission:view global tags');
Route::post('/global/store',   [globalController::class, 'store'])->name('global.store')->middleware('permission:create global tags');
Route::post('/global/get',     [globalController::class, 'get'])->name('global.get')->middleware('permission:view global tags');
Route::post('/global/update/{id}', [globalController::class, 'update'])->name('global.update')->middleware('permission:edit global tags');
Route::delete('/global/{id}',  [globalController::class, 'destroy'])->name('global.destroy')->middleware('permission:delete global tags');



Route::prefix('google')->name('google.')->middleware(['auth'])->group(function () {
    Route::get('/',          [googleController::class, 'googletag'])->name('index')->middleware('permission:view google tags');
    Route::post('/store',    [googleController::class, 'store'])->name('store')->middleware('permission:create google tags');
    Route::post('/get',      [googleController::class, 'get'])->name('get')->middleware('permission:view google tags');
    Route::post('/update/{id}',      [googleController::class, 'update'])->name('update')->middleware('permission:edit google tags');
    Route::delete('/{id}',   [googleController::class, 'destroy'])->name('destroy')->middleware('permission:delete google tags');
});


    //eco system routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/eco-systems', [eco_systemController::class, 'index'])->name('eco_system.index')->middleware('permission:view eco system');
        Route::post('/eco-systems', [eco_systemController::class, 'store'])->name('eco_system.store')->middleware('permission:create eco system');
        Route::post('/eco-systems/get', [eco_systemController::class, 'get'])->name('eco_system.get')->middleware('permission:view eco system');
        Route::post('/eco-systems/update/{id}', [eco_systemController::class, 'update'])->name('eco_system.update')->middleware('permission:edit eco system');
        Route::delete('/eco-systems/destroy/{id}', [eco_systemController::class, 'destroy'])->name('eco_system.destroy')->middleware('permission:delete eco system');
    });


    //image route
    Route::post('/upload-image', [ImageUploadController::class, 'upload'])->name('upload.image');

    Route::get('/send-test-email', function () {
        Mail::to('nisath.alphatsm@gmail.com')->send(new TestEmail());

        return 'Test email has been sent!';
    });
});



require __DIR__ . '/auth.php';