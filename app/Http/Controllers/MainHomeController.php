<?php

namespace App\Http\Controllers;

use App\Models\about_content;
use App\Models\about_eco;
use App\Models\about_quote;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use App\Models\Service;
use App\Models\TestQuestion;
use App\Models\TestAnswer;
use App\Models\Agent;
use App\Models\HomeSlider;
use App\Models\Blog;
use App\Models\Category;
use App\Models\TapService;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ProjectCategory;
use App\Models\Tag;
use App\Models\Announcement;
use App\Models\About_us;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\globaltag;
use App\Models\googletag;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceGroup;
use App\Models\Inquiry;
use App\Mail\ServiceInquiryMail;
use App\Mail\CustomerInquiryConfirmationMail;
use App\Models\Brand;
use App\Models\BrandHero;
use App\Models\TestimonialSetting;
use Illuminate\Support\Facades\Http;



class MainHomeController extends Controller
{
    public function index()
    {
        // Cached for 10 minutes (App\Support\SiteCache); the menu and service picker data come
        // from the shared, also cached, $main_categories.
        $data = \App\Support\SiteCache::remember('home', fn () => [
            'homeSliders'               => HomeSlider::where('status', 'active')->get(),
            'blogs'                     => Blog::where('featured', true)->orderBy('sort_order')->orderBy('id')->take(3)->get(),
            'categories_carts'          => Category::where('featured', true)->orderBy('sort_order')->orderBy('id')->take(8)->get(),
            'featured_categories_total' => Category::where('featured', true)->count(),
            'announcements'             => Announcement::where('status', 1)->latest()->get(),
            'projects'                  => Project::with(['project_category', 'projects_images', 'projects_videos', 'projects_documents'])->latest()->take(2)->get(),
            'clients'                   => \App\Models\client::visible()->where('is_featured', 1)->get(),
            'insightBlogs'              => Blog::where('featured', true)->orderBy('sort_order')->orderBy('id')->take(8)->get(),
            // IN-subquery instead of whereHas: whereHas ran a correlated EXISTS per blog row (~23 ms).
            'alphaUpdates'              => Blog::whereIn('id', function ($q) {
                $q->select('blog_tags.blog_id')->from('blog_tags')
                    ->join('tags', 'tags.id', '=', 'blog_tags.tag_id')
                    ->where('tags.name', 'AHG Updates');
            })->orderBy('sort_order')->orderBy('id')->take(4)->get(),
        ]);

        return view('front.index-2', $data);
    }

    public function loadMoreCategories(Request $request)
    {
        $offset = max(0, (int) $request->query('offset', 0));
        $categories = Category::where('featured', true)->orderBy('sort_order')->orderBy('id')->skip($offset)->take(8)->get();
        $total = Category::where('featured', true)->count();

        return response()->json([
            'html' => view('front.partials.category_cards', ['categories' => $categories])->render(),
            'count' => $categories->count(),
            'remaining' => max(0, $total - ($offset + $categories->count())),
        ])->header('X-Robots-Tag', 'noindex'); // AJAX endpoint, not a page (Search Console crawled it)
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data = $request->only(['name', 'email', 'subject', 'message']);
        // The visitor's WhatsApp Ref code, for staff to copy into the CRM (Ref click log brief, 30 Oct 2026).
        $ref = (string) $request->input('ref');
        $data['ref'] = preg_match(\App\Models\RefClick::REF_PATTERN, $ref) ? $ref : null;

        // Spam has zero value here — silently "succeed" without sending mail
        // rather than tipping the bot off that it was blocked.
        if ($this->isSuspiciousSubmission($request, 'contact')) {
            return back()->with('success', '✅ Your message has been sent successfully!');
        }

        try {
            Mail::to('info@alphatsm.com')->send(new ContactMail($data));
            return back()->with('success', '✅ Your message has been sent successfully!');
        } catch (\Exception $e) {
            Log::error('Mail failed: ' . $e->getMessage());
            return back()->with('error', '❌ Failed to send message. Please try again.');
        }
    }


    public function contact()
    {
        $testimonials = \App\Models\Testimonial::all();//where('featured', true)->get()
        return view('front.contact', compact('testimonials'));
    }

    public function Service($slug)
    {
        $data = [];
        $data['service'] = Service::published()->with('agent', 'ServiceTab', 'faq', 'announcement', 'projectProcess')->where('slug', $slug)->first();
        if (!$data['service']) {
            // Retired slugs that search engines still request get a permanent redirect. Any other
            // unknown slug stays temporary: it may be a draft that is published later.
            $query = request()->getQueryString() ? '?' . request()->getQueryString() : '';
            if (in_array($slug, self::GONE_SERVICE_SLUGS, true)) {
                abort(410, 'service');
            }
            if (array_key_exists($slug, self::RETIRED_SERVICE_SLUGS)) {
                $target = self::RETIRED_SERVICE_SLUGS[$slug];
                return $target ? redirect(url($target) . $query, 301) : redirect(route('front.all-services') . $query, 301);
            }
            // Old service URLs whose slug is now a category (index cleanup brief, 22 Oct 2026).
            if (Category::where('slug', $slug)->exists()) {
                return redirect(route('front.service-category', $slug) . $query, 301);
            }
            return redirect()->route('front.all-services', [], 302);
        }

        // Fetch related services
        $categoryIds = $data['service']->categories->pluck('id');

        if ($data['service']->related_services && is_array($data['service']->related_services)) {
            $data['relatedServices'] = Service::published()->whereIn('id', $data['service']->related_services)->get();
        } else {
            // No related services chosen — fall back to others in the same category,
            // prioritising featured services first.
            $data['relatedServices'] = Service::published()
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                })
                ->where('id', '!=', $data['service']->id)
                ->orderByDesc('featured')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }

        $data['featuredServices'] = Service::published()->where('featured', true)->take(3)->get();
        $data['all_services'] = Service::published()->get();

        // Case studies and the related article: related items only; none hides the section.
        $data['projects'] = \App\Support\RelatedContent::projects([$data['service']->id], $categoryIds->all());
        $data['relatedBlog'] = \App\Support\RelatedContent::blog(
            $data['service']->name . ' ' . $data['service']->meta_title,
            $categoryIds->all()
        );

        $data['globaltag'] = globaltag::all();
        $data['googletag'] = googletag::all();
        $data['service_groups'] = ServiceGroup::all();

        return view('front.service', $data);
    }

    public function serviceCategory($slug)
    {
        $data = [];
        $category = Category::with([
            'agent.user',
            'services' => fn ($q) => $q->where('status', 'published')->orderBy('sort_order')->orderBy('id'),
            'faqs',
            'magazines',
            'serviceGroups' => fn ($q) => $q->where('status', 'published'),
        ])->where('slug', $slug)->first();

        if (!$category) {
            return redirect()->route('front.all-services');
        }

        // Keep the existing service_category view compatible with its current variable/relationship names.
        $category->setRelation('faq', $category->faqs ?? collect());
        $category->setRelation('ServiceTab', collect());
        $category->setRelation('categories', collect([$category]));

        $data['service'] = $category;
        $data['featuredServices'] = Service::published()->where('featured', true)->take(3)->get();
        $data['all_services'] = Service::published()->get();
        $data['projects'] = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);
        $data['latest_blogs'] = Blog::orderBy('sort_order')->orderBy('id')->take(3)->get();
        $data['globaltag'] = globaltag::all();
        $data['googletag'] = googletag::all();
        $data['service_groups'] = ServiceGroup::all();

        return view('front.service_category', $data);
    }



    public function blog()
    {
        $blogs = Blog::whereDoesntHave('tags', function($query) {
            $query->where('name', 'AHG Updates');
        })->with('tags')->orderBy('sort_order')->orderBy('id')->get();

        $tags = Tag::where('name', '!=', 'AHG Updates')->get();
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->latest()->take(3)->get();
        $all_services = Service::published()->get();
        return view('front.new-blog', compact('blogs', 'tags', 'projects', 'all_services'));
    }

    public function newsMedia()
    {
        $blogs = Blog::whereHas('tags', function($query) {
            $query->where('name', 'AHG Updates');
        })->with('tags')->orderBy('sort_order')->orderBy('id')->get();

        $tags = Tag::where('name', 'AHG Updates')->get();
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->latest()->take(3)->get();
        $all_services = Service::published()->get();
        return view('front.news-media', compact('blogs', 'tags', 'projects', 'all_services'));
    }

    public function brands()
    {
        $brands = Brand::orderBy('sort_order')->orderBy('id')->get();
        $all_services = Service::published()->get();
        $brandHero = BrandHero::first();
        return view('front.brands', compact('brands', 'all_services', 'brandHero'));
    }

    public function singleBrand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        $all_services = Service::published()->get();
        $brandHero = BrandHero::first();
        return view('front.brand_details', compact('brand', 'all_services', 'brandHero'));
    }


    public function about()
    {
        // return view('front.new-about');

        $about_us = About_us::first();
        $about_content = about_content::latest()->get();
        $about_quotes = about_quote::latest()->get();
        $eco_systems = about_eco::latest()->take(6)->get();
        $clients = \App\Models\client::visible()->get();
        $brands = Brand::orderBy('sort_order')->orderBy('id')->get();
        $about_counters = \App\Models\AboutCounter::orderBy('sort_order')->orderBy('id')->get();
        $about_staff = \App\Models\AboutStaff::orderBy('sort_order')->orderBy('id')->get();
        return view('front.new-about', compact('about_us', 'about_content', 'about_quotes', 'eco_systems', 'clients', 'brands', 'about_counters', 'about_staff'));


        // return view('front.new-about', compact('about_content'));
    }


    public function singleBlog($slug)
    {
        // Drafts 404 here: Blog's PublishedScope only finds published posts.
        return $this->renderBlog(Blog::where('slug', $slug)->firstOrFail(), false);
    }

    /** Signed, noindex preview of a draft (or of a live post as it is) for the reviewer. */
    public function previewBlog($id)
    {
        $blog = Blog::withoutGlobalScope(\App\Models\Scopes\PublishedScope::class)->findOrFail($id);

        return $this->renderBlog($blog, true);
    }

    /** Signed preview of a live post with its staged revision applied in memory (never saved). */
    public function previewBlogRevision($id)
    {
        $blog = Blog::withoutGlobalScope(\App\Models\Scopes\PublishedScope::class)->with('revision')->findOrFail($id);
        abort_unless($blog->revision, 404);
        $rev = $blog->revision;
        $blog->forceFill([
            'title'               => $rev->title,
            'description'         => $rev->description,
            'content'             => $rev->content,
            'meta_title'          => $rev->meta_title,
            'meta_description'    => $rev->meta_description,
            'meta_keywords'       => $rev->meta_keywords,
            'sources'             => $rev->sources,
            'reviewer_name'       => $rev->reviewer_name,
            'reviewer_credential' => $rev->reviewer_credential,
            'reviewer_url'        => $rev->reviewer_url,
            'last_reviewed'       => $rev->last_reviewed,
            'updated_date'        => now()->toDateString(),
        ]);

        return $this->renderBlog($blog, true);
    }

    /** One renderer for the public post and its previews, so the two never drift. */
    private function renderBlog(Blog $blog, bool $preview)
    {
        $blogsByTag = Blog::whereHas('tags', function ($query) use ($blog) {
            $query->whereIn('tags.id', $blog->tags->pluck('id'));
        })->where('id', '!=', $blog->id)->orderBy('sort_order')->orderBy('id')->paginate(3);

        // Get projects with relationships
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);

        $featuredServices = Service::published()->where('featured', true)->take(4)->get();

        // Client logos for the "Trusted by Industry Leaders" carousel
        $clients = \App\Models\client::visible()->get();

        // Pass both blog and projects to the view
        $response = response()->view('front.single_blog_page', compact('blog', 'projects', 'featuredServices', 'blogsByTag', 'clients', 'preview'));

        return $preview ? $response->header('X-Robots-Tag', 'noindex, nofollow') : $response;
    }



    public function project()
    {
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')
            ->orderBy('sort_order')->orderBy('id')->get();
        $featuredProject = Project::with(['projects_images', 'project_category'])
            ->where('featured', true)->first()
            ?? Project::with(['projects_images', 'project_category'])->orderBy('sort_order')->orderBy('id')->first();

        $featuredServices = collect();
        if ($featuredProject && !empty($featuredProject->service_ids)) {
            $featuredServices = Service::whereIn('id', $featuredProject->service_ids)->get();
        }

        $all_services = Service::published()->get();
        $latest_blogs = Blog::orderBy('sort_order')->orderBy('id')->take(3)->get();
        $clients = \App\Models\client::visible()->get();
        return view('front.projects', compact('projects', 'featuredProject', 'featuredServices', 'all_services', 'latest_blogs', 'clients'));
    }

    /**
     * Service slugs that never existed or were retired (none is a draft), with the live page
     * closest to each old topic (redirect map approved 22 Oct 2026). Null = All Services.
     */
    private const RETIRED_SERVICE_SLUGS = [
        'dha-healthcare-licensing'             => '/services/dha-license-healthcare-professionals-dubai',
        'doh-healthcare-licensing'             => '/service-category/healthcare-professional-licensing',
        'doh-licensing-compliance'             => '/services/hospital-compliance-doh-consultation',
        'gcc-healthcare-regulatory-consulting' => '/service-category/regulatory-quality',
        'healthcare-consulting'                => '/service-packages/healthcare-management-consultancy-in-uae',
        'healthcare-digitalisation'            => '/services/hospital-digital-transformation-consulting',
        'healthcare-digitalisation)'           => '/services/hospital-digital-transformation-consulting',
        'healthcare-digitalization'            => '/services/hospital-digital-transformation-consulting',
        'healthcare-financial-consulting'      => null,
        'healthcare-licensing'                 => '/service-category/healthcare-professional-licensing',
        'healthcare-licensing-consultancy'     => '/service-category/healthcare-professional-licensing',
        'healthcare-regulatory-licensing'      => '/service-category/regulatory-quality',
        'hospital-setup'                       => '/service-category/hospitals',
        'infection-control-consulting'         => null,
        'malaffi-nabidh-integration'           => '/service-category/hie-integration',
        'medical-device-registration-uae'      => '/services/medical-equipment-moh-licensing-consulting',
        'operational-audit'                    => '/service-category/audit-consultation',
        'operational-readiness'                => '/services/regulatory-readiness-compliance-assurance',
        'quality-management-consulting'        => '/service-category/regulatory-quality',
        'revenue-cycle-management'             => '/service-category/insurance-empanelment',
        // index cleanup brief, 22 Oct 2026 (1b)
        'uae-healthcare-professional-licensing'      => '/service-packages/healthcare-professional-licensing-uae',
        'transfer-your-uae-healthcare-license'       => '/services/healthcare-professional-license-transfer',
        'medical-centre-setup-licensing-dubai-dha'   => '/services/medical-centre-setup-licensing-dubai',
        'healthcare-acquisition-due-diligence'       => '/services/healthcare-facility-acquisition-due-diligence',
        'healthcare-it-digital-strategy-consulting2' => '/services/hospital-digital-transformation-consulting',
        'gcc-healthcare-consulting'                  => '/service-category/regulatory-quality',
    ];

    /** Service URLs removed on purpose: 410 Gone (Saroth, 22 Oct 2026: not required, unpublished). */
    private const GONE_SERVICE_SLUGS = ['medical-college-setup-licensing'];

    /**
     * Old case-study URLs that were indexed: client websites stored without a scheme became
     * relative links (/project_details/www.reemhospital.com). Numeric IDs are handled generically.
     */
    private const LEGACY_PROJECT_URLS = [
        'beijingtherapy.ae'      => 'beijing-therapy',
        'www.dnahealthcorp.com'  => 'dna-health-corp',
        'www.ihiad.ae'           => 'ihlad-abu-dhabi',
        'www.reemhospital.com'   => 'reem-hospital',
        'revitalhospital.ae'     => 'revital-hospital',
        'royalspanishcenter.com' => 'royal-spanish-center',
    ];

    public function singleProject($slug)
    {
        $project = Project::with(['projects_images', 'projects_videos', 'projects_documents', 'project_category'])->where('slug', $slug)->first();
        if (!$project) {
            // 301 indexed old URLs (/project_details/12, client-website paths) to the slug page.
            if (ctype_digit((string) $slug)) {
                // An old numeric URL: its case study, or the Case Studies page when it was deleted.
                $target = Project::whereKey((int) $slug)->value('slug');
                return $target
                    ? redirect()->route('front.project_details', $target, 301)
                    : redirect()->route('front.project', [], 301);
            }
            $target = self::LEGACY_PROJECT_URLS[strtolower($slug)] ?? null;
            abort_unless($target, 404);
            return redirect()->route('front.project_details', $target, 301);
        }
        $relatedProjects = Project::with(['projects_images', 'projects_videos', 'projects_documents', 'project_category'])
            ->where('project_category_id', $project->project_category_id)
            ->where('id', '!=', $project->id)
            ->take(3)
            ->get();

        $projectServices = collect();
        if (!empty($project->service_ids)) {
            $projectServices = Service::whereIn('id', $project->service_ids)->get();
        }

        $all_services = Service::published()->get();
        return view('front.project_details', compact('project', 'relatedProjects', 'all_services', 'projectServices'));
    }

    // public function allServices(){
    //      $categories = MainCategory::with(['categories', 'categories.services'])->get();
    //     return view('front.all-services', compact('categories'));
    // }

    public function allServices(Request $request)
    {
        $search = $request->input('servicesSearch');

        $categories = MainCategory::with([
            'allCategories',
            'allCategories.services' => function ($query) use ($search) {
                $query->where('status', 'published')->orderBy('sort_order')->orderBy('id');
                if ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                }
            },
            'allCategories.serviceGroups' => function ($query) use ($search) {
                $query->where('status', 'published');
                if ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                }
            },
        ])->orderBy('sort_order')->get();

        return view('front.all-services', ['main_categories' => $categories, 'search' => $search]);
    }

    public function testimonials()
    {
        $testimonials = Testimonial::where('approved', true)->latest()->get();
        $settings     = TestimonialSetting::current();
        $avgRating    = $testimonials->avg('rating') ?? 0;
        $totalCount   = $testimonials->count();
        $breakdown    = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $testimonials->where('rating', $i)->count();
            $breakdown[$i] = ['count' => $count, 'pct' => $totalCount ? round($count / $totalCount * 100) : 0];
        }
        $all_services = Service::published()->get();
        return view('front.testimonials', compact('testimonials', 'settings', 'avgRating', 'totalCount', 'breakdown', 'all_services'));
    }

    public function ourClients()
    {
        $clients = \App\Models\client::visible()->get();
        $all_services = Service::published()->get();
        $clientSetting = \App\Models\ClientSetting::current();
        return view('front.our-clients', compact('clients', 'all_services', 'clientSetting'));
    }

    public function feedbackForm()
    {
        $all_services = Service::published()->get();
        return view('front.share-experience', compact('all_services'));
    }

    /**
     * "Services by Facility Type" — lists the sub-categories of the
     * "By Facility Type" main category, each with its published services.
     */
    public function facilityTypes()
    {
        $main = MainCategory::where('name', 'By Facility Type')->first();

        $facilityTypes = collect();
        if ($main) {
            $facilityTypes = $main->mergedCategories
                ->map(function ($cat) {
                    $cat->setRelation('services', $cat->services()
                        ->where('status', 'published')
                        ->orderBy('name')->get());
                    return $cat;
                })
                ->filter(fn ($cat) => $cat->services->count() > 0)
                ->values();
        }

        $all_services = Service::published()->get();
        return view('front.facility-types', compact('facilityTypes', 'main', 'all_services'));
    }

    /**
     * reCAPTCHA v3 is in record-only mode (Saroth, 21 Oct 2026, for one week): every score
     * is logged to storage/logs/recaptcha-*.log ("reCAPTCHA score", with would_block) and never blocks a submission. Set to
     * true once a threshold has been chosen from the logs.
     */
    private const RECAPTCHA_ENFORCE = false;

    /**
     * Shared anti-spam check: honeypot field (bots fill it, humans never do) +
     * a reCAPTCHA v3 score check (only runs when keys are configured). Threshold
     * is per-caller since inquiries and testimonials/contact treat suspicion
     * differently (fail-open vs. silent-drop).
     */
    private function isSuspiciousSubmission(Request $request, string $action, float $minScore = 0.3): bool
    {
        if (!empty($request->input('website_url'))) {
            return true;
        }

        $secret = config('services.recaptcha.secret_key');
        if (!$secret) {
            return false;
        }

        try {
            $rc = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $request->input('g-recaptcha-response', ''),
                'remoteip' => $request->ip(),
            ]);
            $score = $rc->json('score', 1.0);
            Log::channel('recaptcha')->info('reCAPTCHA score', [
                'action'      => $action,
                'score'       => $rc->json('score'),          // null when no valid token was sent
                'success'     => $rc->json('success'),
                'token_sent'  => $request->filled('g-recaptcha-response'),
                'errors'      => $rc->json('error-codes'),
                'rc_action'   => $rc->json('action'),
                'hostname'    => $rc->json('hostname'),
                'threshold'   => $minScore,
                'would_block' => $score < $minScore,
                // matches an inquiry via hash('sha256', strtolower(email)); no raw email or IP in logs
                'email_hash'  => $request->filled('email')
                    ? substr(hash('sha256', strtolower(trim((string) $request->input('email')))), 0, 12) : null,
            ]);
            return self::RECAPTCHA_ENFORCE && $score < $minScore;
        } catch (\Exception $e) {
            Log::warning("reCAPTCHA v3 check failed for {$action} (submission allowed): " . $e->getMessage());
            return false;
        }
    }

    public function submitTestimonial(Request $request)
    {
        $request->validate([
            'author_name' => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'service_id'  => 'nullable|exists:services,id',
            'rating'      => 'required|integer|min:1|max:5',
            'content'     => 'required|string|max:2000',
        ]);

        $successMsg = 'Thank you! Your review has been submitted and is pending approval.';

        // A fake testimonial has zero value (unlike an inquiry lead), so — unlike
        // submitInquiry — suspicious submissions are silently dropped, not stored.
        $isSuspicious = $this->isSuspiciousSubmission($request, 'testimonial');

        $recentExists = Testimonial::where('email', $request->email)
            ->where('created_at', '>=', now()->subHours(2))
            ->exists();

        if (!$isSuspicious && !$recentExists) {
            Testimonial::create([
                'author_name' => $request->author_name,
                'email'       => $request->email,
                'service_id'  => $request->service_id ?: null,
                'rating'      => $request->rating,
                'content'     => strip_tags($request->content),
                'position'    => strip_tags($request->position ?? ''),
                'company_name'=> strip_tags($request->company_name ?? ''),
                'author_image'=> '',
                'approved'    => false,
                'source'      => 'customer',
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }
        return back()->with('success', $successMsg);
    }

    /** The landing form's source JSON (Ref record and ad parameters), kept only as flat short strings. */
    private function inquirySource(?string $json): ?array
    {
        $data = json_decode((string) $json, true);
        if (!is_array($data)) {
            return null;
        }
        $clean = [];
        foreach ($data as $key => $value) {
            if (is_string($key) && preg_match('/^[a-z_]{1,30}$/', $key) && is_scalar($value)) {
                $clean[$key] = mb_substr((string) $value, 0, 500);
            }
        }

        return $clean ?: null;
    }

    public function submitInquiry(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required_without:phone|nullable|email|max:255',
            'phone'        => ['required_without:email', 'nullable', 'string', 'max:20', 'regex:/^\+[\d\s\-]{7,17}$/'],
            'service_id'   => 'nullable|exists:services,id',
            'message'      => 'nullable|string',
            'meeting_date' => 'nullable|date',
            'meeting_time' => 'nullable|string|max:10',
            // Landing page form (front/landing): its page, the facility and emirate picked, and the ad source.
            'landing_page_id' => 'nullable|integer|exists:landing_pages,id',
            'facility_type'   => 'nullable|string|max:100',
            'emirate'         => 'nullable|string|max:50',
            'source'          => 'nullable|string|max:3000',
        ], [
            'email.required_without' => 'Please provide at least an email address or mobile number.',
            'phone.required_without' => 'Please provide at least a mobile number or email address.',
            'phone.regex'            => 'Please enter a valid international number starting with + (e.g. +971 50 000 0000).',
        ]);

        $successMsg = 'Thank you! Your inquiry has been submitted successfully.';

        // ── 1+2. Honeypot + reCAPTCHA v3 soft-check.
        //    Never a hard block here — we never want to lose a lead; low-confidence
        //    submissions are just flagged as spam for admin review.
        $isSuspicious = $this->isSuspiciousSubmission($request, 'inquiry', 0.3);

        // ── 3. Dedup — same email (or, for phone-only inquiries, same phone) within 2 hours.
        //    Return silent success (user already got a confirmation; just don't double-save).
        if (!$isSuspicious) {
            $dedupField = $request->filled('email') ? 'email' : 'phone';
            $recentExists = Inquiry::where($dedupField, $request->input($dedupField))
                ->where('created_at', '>=', now()->subHours(2))
                ->exists();

            if ($recentExists) {
                return $request->ajax()
                    ? response()->json(['success' => true, 'message' => $successMsg])
                    : back()->with('success', $successMsg);
            }
        }

        // Combine the optional preferred date + time into a single datetime.
        $meetingAt = $request->filled('meeting_date')
            ? trim($request->input('meeting_date') . ' ' . ($request->input('meeting_time') ?: '10:00'))
            : null;

        // Record the contact consent on the CRM message so it's visible in the dashboard.
        $message = $request->input('message');
        // Landing page leads: show the facility, emirate and page on the message, as the CRM list and
        // the alert email show only the message.
        if ($request->filled('landing_page_id')) {
            $landing = \App\Models\LandingPage::find($request->input('landing_page_id'));
            $lines = array_filter([
                $request->filled('facility_type') ? 'Facility: ' . $request->input('facility_type') : null,
                $request->filled('emirate') ? 'Emirate: ' . $request->input('emirate') : null,
                $landing ? 'Page: ' . $landing->url() : null,
            ]);
            $message = trim(implode("
", $lines) . ($message ? "
" . $message : ''));
        }
        if ($request->boolean('consent')) {
            $message = trim(($message ? $message . "\n" : '') . '— Consent: agreed to be contacted (privacy policy accepted).');
        }

        // 1. Save to Database (CRM record) — spam submissions are saved but flagged.
        $inquiry = Inquiry::create([
            'name'       => $request->input('name'),
            'email'      => $request->input('email'),
            'phone'      => $request->input('phone'),
            'service_id' => $request->input('service_id') ?: null,
            'landing_page_id' => $request->input('landing_page_id') ?: null,
            'facility_type'   => $request->input('facility_type') ?: null,
            'emirate'         => $request->input('emirate') ?: null,
            'source'          => $this->inquirySource($request->input('source')),
            'message'    => $message,
            'meeting_at' => $meetingAt,
            'status'     => $isSuspicious ? 'spam' : 'pending',
        ]);

        // Skip notification emails for flagged spam — admin can still review in CRM.
        if (!$isSuspicious) {
            // 2. Send Email Notification to Admin
            try {
                // Proposal requests from the facility landing pages also go to the facility team (Saroth, 30 Oct 2026).
                $adminMail = Mail::to('info@alphatsm.com');
                if ($inquiry->landing_page_id) {
                    $adminMail->cc('mhmrimas.alpha@gmail.com');
                }
                $adminMail->send(new ServiceInquiryMail($inquiry));
            } catch (\Exception $e) {
                Log::error('Inquiry Admin Mail failed: ' . $e->getMessage());
            }

            // 3. Send Confirmation Email to Customer (phone-only inquiries have no email)
            if (!empty($inquiry->email)) {
                try {
                    Mail::to($inquiry->email)->send(new CustomerInquiryConfirmationMail($inquiry));
                } catch (\Exception $e) {
                    Log::error('Inquiry Customer Mail failed: ' . $e->getMessage());
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }

        return back()->with('success', $successMsg);
    }
}