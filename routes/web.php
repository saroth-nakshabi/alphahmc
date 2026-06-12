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
Route::get('/services/{slug}', [MainHomeController::class, 'Service'])->name('front.service');
Route::get('/new_service/{slug}', function ($slug) {
    return redirect()->route('front.service', $slug, 301);
});
Route::get('/service-category/{slug}', [MainHomeController::class, 'serviceCategory'])->name('front.service-category');
Route::get('/all_services', [MainHomeController::class, 'allServices'])->name('front.all-services');
Route::redirect('/services', '/all_services', 301);
Route::post('/inquiry/submit', [MainHomeController::class, 'submitInquiry'])->name('front.inquiry.submit');
Route::get('/healthcare-management-update-insights', [MainHomeController::class, 'blog'])->name('front.new_blog');
Route::redirect('/new_blog', '/healthcare-management-update-insights', 301);
// Route::get('/blog_single/{slug}', [MainHomeController::class, 'singleBlog'])->name('front.singleBlog');
Route::get('/blog_single/{slug}', [MainHomeController::class, 'singleBlog'])->name('front.singleBlog');
Route::get('/project', [MainHomeController::class, 'project'])->name('front.project');
Route::get('/project_details/{slug}', [MainHomeController::class, 'singleProject'])->name('front.project_details');
Route::get('/ahg-updates', [MainHomeController::class, 'newsMedia'])->name('front.ahg-updates');
Route::redirect('/news-media', '/ahg-updates', 301);
Route::get('/Alpha-Health-Group-Branches', [MainHomeController::class, 'brands'])->name('front.brands');
Route::get('/brand/{slug}', [MainHomeController::class, 'singleBrand'])->name('front.singleBrand');
Route::get('/our-clients', [MainHomeController::class, 'ourClients'])->name('front.our-clients');
Route::get('/client-reviews', [MainHomeController::class, 'testimonials'])->name('front.testimonials');
Route::get('/share-your-experience', [MainHomeController::class, 'feedbackForm'])->name('front.feedback');
Route::get('/gdpr-data-protection', function () {
    return view('front.gdpr-terms');
})->name('front.gdpr-terms');

Route::get('/terms-of-service', function () {
    return view('front.terms-of-service');
})->name('front.terms-of-service');

Route::get('/cookie-policy', function () {
    return view('front.cookie-policy');
})->name('front.cookie-policy');
Route::post('/share-your-experience/submit', [MainHomeController::class, 'submitTestimonial'])->name('front.testimonial.submit');

// Route::post('/ckeditor/upload', [TinyMCEUploadController::class, 'upload'])->name('ckeditor.upload');
Route::post('/ckeditor/upload', [CkEditorUploadController::class, 'upload'])->name('ckeditor.upload');

Route::get('/about-alpha-health-group', [MainHomeController::class, 'about'])->name('front.new-about');
Route::redirect('/new-about', '/about-alpha-health-group', 301);

// Route::post('/chat', [ChatWidgetController::class, 'reply'])
//     ->middleware('throttle:30,1');   // 30 requests per minute per IP



Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/send', [MainHomeController::class, 'sendContact'])->name('contact.send');

Route::get('/service-calendar', [HomeController::class, 'serviceCalendar'])->name('service_calendar');
Route::post('/set-timezone', [TimezoneController::class, 'setTimezone'])->name('set_timezone');
Route::get('/blog', [HomeController::class, 'blog'])->name('front.blog');
Route::get('/blogs/{tag_name}', [HomeController::class, 'viewTag'])->name('view_tag');
Route::get('/blog/{slug}', [HomeController::class, 'viewBlog'])->name('view_blog');

Route::get('/how_alpha_work', [HomeController::class, 'how_alpha_work'])->name('how_alpha_work');
Route::get('/healthcare_quality_assurance', [HomeController::class, 'healthcare_quality_assurance'])->name('healthcare_quality_assurance');

// Search routes (public - no auth required)
Route::get('/search', [SearchController::class, 'index'])->name('front.search');
Route::get('/search/live', [SearchController::class, 'live'])->name('front.search.live');
Route::post('/ai-assistant/chat', [ChatAssistantController::class, 'chat'])->name('ai.assistant.chat');


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
    Route::get('/agents', [AgentController::class, 'index'])->name('users.agents');
    Route::post('/agents', [AgentController::class, 'store'])->name('agents.store');
    Route::delete('/agents/delete/{id}', [AgentController::class, 'destroy'])->name('agents.destroy');
    Route::post('/agents/update/{id}', [AgentController::class, 'update'])->name('agents.update');
    Route::post('/agents/get', [AgentController::class, 'getAgent'])->name('agents.get');

    // all users routes
    Route::get('/all-users', [UserController::class, 'index'])->name('all_users.index')->middleware('permission:view users');

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

    // Stub: the live dashboard layout links to route('strategy.index'), but the
    // strategy routes only ever existed in the host's old route cache. Redirects
    // to the dashboard until the real Strategies module routes are restored.
    Route::get('/strategies', function () {
        return redirect()->route('dashboard');
    })->name('strategy.index');
    Route::post('/categories/delete-gallery-image', [CategoryController::class, 'deleteGalleryImage'])->name('categories.delete-gallery-image');

    // service-group routes
    Route::get('/service-group', [ServiceGroupController::class, 'index'])->name('service-group.index');
    Route::get('/service-group/create', [ServiceGroupController::class, 'create'])->name('service-group.create');
    Route::post('/service-group', [ServiceGroupController::class, 'store'])->name('service-group.store');
    Route::post('/service-group/get', [ServiceGroupController::class, 'get'])->name('service-group.get');
    Route::get('/service-group/{id}/edit', [ServiceGroupController::class, 'edit'])->name('service-group.edit');
    Route::put('/service-group/{id}', [ServiceGroupController::class, 'update'])->name('service-group.update');
    Route::post('/service-group/{id}', [ServiceGroupController::class, 'update'])->name('service-group.update.post');
    Route::delete('/service-group/{id}', [ServiceGroupController::class, 'destroy'])->name('service-group.destroy');
    Route::post('/service-group/{id}/toggle-status', [ServiceGroupController::class, 'toggleStatus'])->name('service-group.toggle-status');
    Route::post('/service-group/{id}/toggle-featured', [ServiceGroupController::class, 'toggleFeatured'])->name('service-group.toggle-featured');


    

    // inquiry routes
    Route::get('/dashboard/inquiries', [AdminInquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::post('/dashboard/inquiries/update/{id}', [AdminInquiryController::class, 'update'])->name('admin.inquiries.update');
    Route::post('/dashboard/inquiries/reply/{id}', [AdminInquiryController::class, 'reply'])->name('admin.inquiries.reply');
    Route::delete('/dashboard/inquiries/delete/{id}', [AdminInquiryController::class, 'destroy'])->name('admin.inquiries.destroy');

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
    Route::post('/services/upload-documents/{id}', [ServiceController::class, 'uploadDocuments'])->name('services.upload_documents');

    // -- Service Magazine (Insights) CRUD routes --
    Route::get('/services/{serviceId}/magazines/create', [\App\Http\Controllers\ServiceMagazineController::class, 'create'])->name('service.magazines.create');
    Route::post('/services/{serviceId}/magazines', [\App\Http\Controllers\ServiceMagazineController::class, 'store'])->name('service.magazines.store');
    Route::get('/services/{serviceId}/magazines/{magazineId}/edit', [\App\Http\Controllers\ServiceMagazineController::class, 'edit'])->name('service.magazines.edit');
    Route::put('/services/{serviceId}/magazines/{magazineId}', [\App\Http\Controllers\ServiceMagazineController::class, 'update'])->name('service.magazines.update');
    Route::delete('/services/{serviceId}/magazines/{magazineId}', [\App\Http\Controllers\ServiceMagazineController::class, 'destroy'])->name('service.magazines.destroy');

    // announcement routes
    Route::get('/all-announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements', function () {
        return redirect()->route('announcements.index');
    });
    Route::get('/announcements/create', [\App\Http\Controllers\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/edit/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::post('/announcements/update/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/delete/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

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

    // blog routes
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index')->middleware('permission:view blogs');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store')->middleware('permission:create blogs');
    Route::delete('/blogs/delete/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy')->middleware('permission:delete blogs');
    Route::post('/blogs/update/{id}', [BlogController::class, 'update'])->name('blogs.update')->middleware('permission:edit blogs');
    Route::post('/blogs/get', [BlogController::class, 'getBlog'])->name('blogs.get');
    Route::post('/blogs/featured', [BlogController::class, 'featuredHandle'])->name('blogs.featured.change');

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

    Route::get('/projects-category', [ProjectCategoryController::class, 'index'])->name('project.category.index');
    Route::post('/projects-category', [ProjectCategoryController::class, 'store'])->name('project_categories.store');
    Route::delete('/projects-category/delete/{id}', [ProjectCategoryController::class, 'destroy'])->name('project.category.destroy');
    Route::post('/projects-category/update/{id}', [ProjectCategoryController::class, 'update'])->name('project.category.update');
    Route::post('/projects-category/get', [ProjectCategoryController::class, 'getCategory'])->name('project.category.get');


    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index');
    Route::post('/projects-store', [ProjectController::class, 'store'])->name('project.store');
    Route::post('/projects/get', [ProjectController::class, 'getProject'])->name('project.getProject');
    Route::post('/projects/update/{id}', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/projects/destroy/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');


    //About us page

    Route::prefix('about-us')->group(function () {
        Route::get('/', [About_UsController::class, 'index'])->name('about_us.index');
        Route::post('/', [About_UsController::class, 'store'])->name('about_us.store');

        // AJAX routes
        Route::post('/get', [About_UsController::class, 'get'])->name('about_us.get');
        Route::post('/update/{id}', [About_UsController::class, 'update'])->name('about_us.update');
        Route::delete('/destroy/{id}', [About_UsController::class, 'destroy'])->name('about_us.destroy');
    });

    // About us content routes
    Route::prefix('content')->group(function () {
        Route::get('/', [About_UsContentController::class, 'index'])->name('about_us.content.index');
        Route::post('/', [About_UsContentController::class, 'store'])->name('about_us.content.store');
        Route::post('/get', [About_UsContentController::class, 'get'])->name('about_us.content.get');
        Route::post('/update/{id}', [About_UsContentController::class, 'update'])->name('about_us.content.update');
        Route::delete('/destroy/{id}', [About_UsContentController::class, 'destroy'])->name('about_us.content.destroy');
    });


    //contact page

    Route::prefix('testimonial')->group(function() {
        Route::get('/', [ContactController::class,'index'])->name('testimonial.index');
        Route::post('/store', [ContactController::class, 'store'])->name('testimonial.store');
        Route::post('/get', [ContactController::class, 'getTestimonial'])->name('testimonial.get');
        Route::post('/update/{id}', [ContactController::class, 'update'])->name('testimonial.update');
        Route::delete('/delete/{id}', [ContactController::class, 'destroy'])->name('testimonial.destroy');
        Route::post('/toggle-featured/{id}', [ContactController::class, 'toggleFeatured'])->name('testimonial.toggleFeatured');
        Route::post('/toggle-approved/{id}', [ContactController::class, 'toggleApproved'])->name('testimonial.toggleApproved');
        Route::post('/settings', [ContactController::class, 'saveSettings'])->name('testimonial.saveSettings');
    });

   


    //clients route
    Route::prefix('dashboard/clients')->group(function () {
        Route::get('/',[clientsController::class, 'index'])->name('dashboard.clients.index');
        Route::post('/store',[clientsController::class, 'store'])->name('dashboard.clients.store');
        Route::get('/get',[clientsController::class, 'getClient'])->name('dashboard.clients.get');
        Route::post('/update/{id}',[clientsController::class, 'update'])->name('dashboard.clients.update');
        Route::delete('/destroy/{id}',[clientsController::class, 'destroy'])->name('dashboard.clients.destroy');
        Route::post('/reorder',[clientsController::class, 'reorder'])->name('dashboard.clients.reorder');
        Route::post('/toggle-featured/{id}',[clientsController::class, 'toggleFeatured'])->name('dashboard.clients.toggle-featured');
        Route::post('/toggle-status/{id}',[clientsController::class, 'toggleStatus'])->name('dashboard.clients.toggle-status');
    });

    //brands route
    Route::prefix('dashboard/brands')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('dashboard.brands.index');
        Route::post('/store', [BrandController::class, 'store'])->name('dashboard.brands.store');
        Route::get('/get', [BrandController::class, 'getBrand'])->name('dashboard.brands.get');
        Route::post('/update/{id}', [BrandController::class, 'update'])->name('dashboard.brands.update');
        Route::delete('/destroy/{id}', [BrandController::class, 'destroy'])->name('dashboard.brands.destroy');
        Route::post('/update-hero', [BrandController::class, 'updateHero'])->name('dashboard.brands.updateHero');
    });

    //About_quote routes
    Route::prefix('about-quote')->group(function () {
        Route::get('/', [About_quoteController::class, 'index'])->name('about_quote.index');
        Route::post('/store', [About_quoteController::class, 'store'])->name('about_quote.store');
        Route::post('/get', [About_quoteController::class, 'get'])->name('about_quote.get');
        Route::post('/update/{id}', [About_quoteController::class, 'update'])->name('about_quote.update');
        Route::delete('/destroy/{id}', [About_quoteController::class, 'destroy'])->name('about_quote.destroy');
    });

    //tags routes


Route::get('/global', [globalController::class, 'globaltag'])->name('globaltag');
Route::post('/global/store',   [globalController::class, 'store'])->name('global.store');
Route::post('/global/get',     [globalController::class, 'get'])->name('global.get');
Route::post('/global/update/{id}', [globalController::class, 'update'])->name('global.update');
Route::delete('/global/{id}',  [globalController::class, 'destroy'])->name('global.destroy');



Route::prefix('google')->name('google.')->middleware(['auth'])->group(function () {
    Route::get('/',          [googleController::class, 'googletag'])->name('index');
    Route::post('/store',    [googleController::class, 'store'])->name('store');
    Route::post('/get',      [googleController::class, 'get'])->name('get');
    Route::post('/update/{id}',      [googleController::class, 'update'])->name('update');
    Route::delete('/{id}',   [googleController::class, 'destroy'])->name('destroy');
});


    //eco system routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/eco-systems', [eco_systemController::class, 'index'])->name('eco_system.index');
        Route::post('/eco-systems', [eco_systemController::class, 'store'])->name('eco_system.store');
        Route::post('/eco-systems/get', [eco_systemController::class, 'get'])->name('eco_system.get');
        Route::post('/eco-systems/update/{id}', [eco_systemController::class, 'update'])->name('eco_system.update');
        Route::delete('/eco-systems/destroy/{id}', [eco_systemController::class, 'destroy'])->name('eco_system.destroy');
    });


    //image route
    Route::post('/upload-image', [ImageUploadController::class, 'upload'])->name('upload.image');

    Route::get('/send-test-email', function () {
        Mail::to('nisath.alphatsm@gmail.com')->send(new TestEmail());

        return 'Test email has been sent!';
    });
});



require __DIR__ . '/auth.php';