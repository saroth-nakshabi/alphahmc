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
        $categories = MainCategory::with([
            'categories',
            'categories.services' => fn ($q) => $q->where('status', 'published'),
            'allCategories',
            'allCategories.services' => fn ($q) => $q->where('status', 'published'),
        ])->orderBy('sort_order')->get();
        $homeSliders = HomeSlider::where('status', 'active')->get();
        $blogs = Blog::where('featured', true)->orderBy('sort_order')->orderBy('id')->take(3)->get();
        // $featuredServices = Service::where('featured', true)->take(3)->get();
        $categories_carts = Category::where('featured', true)->orderBy('sort_order')->orderBy('id')->take(8)->get();
        $featured_categories_total = Category::where('featured', true)->count();
        $announcements = Announcement::where('status', 1)->latest()->get();
        $projects = Project::with(['project_category', 'projects_images', 'projects_videos', 'projects_documents'])->latest()->take(2)->get();
        $clients = \App\Models\client::visible()->where('is_featured', 1)->get();
        $insightBlogs = Blog::where('featured', true)->orderBy('sort_order')->orderBy('id')->take(8)->get();
        $alphaUpdates = Blog::whereHas('tags', function ($q) {
            $q->where('name', 'AHG Updates');
        })->orderBy('sort_order')->orderBy('id')->take(4)->get();
        return view('front.index-2', compact('categories', 'homeSliders', 'blogs', 'categories_carts', 'announcements', 'projects', 'featured_categories_total', 'clients', 'insightBlogs', 'alphaUpdates'));
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
        ]);
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

        try {
        Mail::to('info@alphatsm.com')->send(new ContactMail($data));
        return back()->with('success', '✅ Your message has been sent successfully!');
        } catch (\Exception $e) {
            // Log the error
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
            return redirect()->route('front.all-services');
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
        $data['projects'] = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);
        $data['latest_blogs'] = Blog::orderBy('sort_order')->orderBy('id')->take(3)->get();
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
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $blogsByTag = Blog::whereHas('tags', function ($query) use ($blog) {
            $query->whereIn('tags.id', $blog->tags->pluck('id'));
        })->where('id', '!=', $blog->id)->orderBy('sort_order')->orderBy('id')->paginate(3);

        // Get projects with relationships
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);

        $featuredServices = Service::published()->where('featured', true)->take(4)->get();

        // Client logos for the "Trusted by Industry Leaders" carousel
        $clients = \App\Models\client::visible()->get();

        // Pass both blog and projects to the view
        return view('front.single_blog_page', compact('blog', 'projects', 'featuredServices', 'blogsByTag', 'clients'));
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

    public function singleProject($slug)
    {
        $project = Project::with(['projects_images', 'projects_videos', 'projects_documents', 'project_category'])->where('slug', $slug)->firstOrFail();
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

    public function submitTestimonial(Request $request)
    {
        $request->validate([
            'author_name' => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'service_id'  => 'nullable|exists:services,id',
            'rating'      => 'required|integer|min:1|max:5',
            'content'     => 'required|string|max:2000',
        ]);

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

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Thank you! Your review has been submitted and is pending approval.']);
        }
        return back()->with('success', 'Thank you! Your review has been submitted and is pending approval.');
    }

    public function submitInquiry(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'service_id'   => 'nullable|exists:services,id',
            'message'      => 'nullable|string',
            'meeting_date' => 'nullable|date',
            'meeting_time' => 'nullable|string|max:10',
        ]);

        $successMsg = 'Thank you! Your inquiry has been submitted successfully.';

        // ── 1. Honeypot — bots fill this invisible field, humans never do.
        //    Never block a submission; just flag as spam for admin review.
        $isSuspicious = !empty($request->input('website_url'));

        // ── 2. reCAPTCHA v3 soft-check (only runs when keys are configured).
        //    Low score → spam flag, never a hard block — we never want to lose a lead.
        if (!$isSuspicious && env('RECAPTCHA_V3_SECRET_KEY')) {
            try {
                $rc    = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => env('RECAPTCHA_V3_SECRET_KEY'),
                    'response' => $request->input('g-recaptcha-response', ''),
                    'remoteip' => $request->ip(),
                ]);
                $score = $rc->json('score', 1.0);
                if ($score < 0.3) {
                    $isSuspicious = true;
                }
            } catch (\Exception $e) {
                Log::warning('reCAPTCHA v3 check failed (submission allowed): ' . $e->getMessage());
            }
        }

        // ── 3. Email dedup — same email submitted within the last 2 hours.
        //    Return silent success (user already got a confirmation; just don't double-save).
        if (!$isSuspicious) {
            $recentExists = Inquiry::where('email', $request->input('email'))
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
        if ($request->boolean('consent')) {
            $message = trim(($message ? $message . "\n" : '') . '— Consent: agreed to be contacted (privacy policy accepted).');
        }

        // 1. Save to Database (CRM record) — spam submissions are saved but flagged.
        $inquiry = Inquiry::create([
            'name'       => $request->input('name'),
            'email'      => $request->input('email'),
            'phone'      => $request->input('phone'),
            'service_id' => $request->input('service_id') ?: null,
            'message'    => $message,
            'meeting_at' => $meetingAt,
            'status'     => $isSuspicious ? 'spam' : 'pending',
        ]);

        // Skip notification emails for flagged spam — admin can still review in CRM.
        if (!$isSuspicious) {
            // 2. Send Email Notification to Admin
            try {
                Mail::to('nisath.alphatsm@gmail.com')->send(new ServiceInquiryMail($inquiry));
            } catch (\Exception $e) {
                Log::error('Inquiry Admin Mail failed: ' . $e->getMessage());
            }

            // 3. Send Confirmation Email to Customer
            try {
                Mail::to($inquiry->email)->send(new CustomerInquiryConfirmationMail($inquiry));
            } catch (\Exception $e) {
                Log::error('Inquiry Customer Mail failed: ' . $e->getMessage());
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }

        return back()->with('success', $successMsg);
    }
}