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



class MainHomeController extends Controller
{
    public function index()
    {
        $categories = MainCategory::with(['categories.services', 'allCategories.services'])->get();
        $homeSliders = HomeSlider::where('status', 'active')->get();
        $blogs = Blog::where('featured', true)->take(3)->get();
        // $featuredServices = Service::where('featured', true)->take(3)->get();
        $categories_carts = Category::where('featured', true)->take(8)->get();
        $announcements = Announcement::where('status', 1)->latest()->get();
        $projects = Project::with(['project_category', 'projects_images', 'projects_videos', 'projects_documents'])->latest()->take(2)->get();
        return view('front.index-2', compact('categories', 'homeSliders', 'blogs', 'categories_carts', 'announcements', 'projects'));
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
        $data['service'] = Service::published()->with('agent', 'ServiceTab', 'faq', 'announcement')->where('slug', $slug)->first();
        if (!$data['service']) {
            return redirect()->route('front.all-services');
        }

        // Fetch related services
        $categoryIds = $data['service']->categories->pluck('id');

        if ($data['service']->related_services && is_array($data['service']->related_services)) {
            $data['relatedServices'] = Service::published()->whereIn('id', $data['service']->related_services)->get();
        } else {
            $data['relatedServices'] = Service::published()->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })->where('id', '!=', $data['service']->id)->get();
        }

        $data['featuredServices'] = Service::published()->where('featured', true)->take(3)->get();
        $data['all_services'] = Service::published()->get();
        $data['projects'] = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);
        $data['latest_blogs'] = Blog::latest()->take(3)->get();
        $data['globaltag'] = globaltag::all();
        $data['googletag'] = googletag::all();
        $data['service_groups'] = ServiceGroup::all();

        return view('front.service', $data);
    }

    public function serviceCategory($slug)
    {
        $data = [];
        $category = Category::with(['agent.user', 'services', 'faqs', 'magazines', 'serviceGroups'])->where('slug', $slug)->first();

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
        $data['latest_blogs'] = Blog::latest()->take(3)->get();
        $data['globaltag'] = globaltag::all();
        $data['googletag'] = googletag::all();
        $data['service_groups'] = ServiceGroup::all();

        return view('front.service_category', $data);
    }



    public function blog()
    {
        $blogs = Blog::whereDoesntHave('tags', function($query) {
            $query->where('name', 'News')->orWhere('name', 'news');
        })->with('tags')->get();
        
        $tags = Tag::where('name', '!=', 'News')->where('name', '!=', 'news')->get();
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->latest()->take(3)->get();
        $all_services = Service::published()->get();
        return view('front.new-blog', compact('blogs', 'tags', 'projects', 'all_services'));
    }

    public function newsMedia()
    {
        $blogs = Blog::whereHas('tags', function($query) {
            $query->where('name', 'News')->orWhere('name', 'news');
        })->with('tags')->latest()->get();
        
        $tags = Tag::where('name', 'News')->orWhere('name', 'news')->get();
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->latest()->take(3)->get();
        $all_services = Service::published()->get();
        return view('front.news-media', compact('blogs', 'tags', 'projects', 'all_services'));
    }

    public function brands()
    {
        $brands = Brand::latest()->get();
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
        $clients = \App\Models\client::latest()->get();
        return view('front.new-about', compact('about_us', 'about_content', 'about_quotes', 'eco_systems', 'clients'));


        // return view('front.new-about', compact('about_content'));
    }


    public function singleBlog($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $blogsByTag = Blog::whereHas('tags', function ($query) use ($blog) {
            $query->whereIn('tags.id', $blog->tags->pluck('id'));
        })->where('id', '!=', $blog->id)->paginate(3);

        // Get projects with relationships
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);

        $featuredServices = Service::published()->where('featured', true)->take(4)->get();

        // Pass both blog and projects to the view
        return view('front.single_blog_page', compact('blog', 'projects', 'featuredServices', 'blogsByTag'));
    }



    public function project()
    {
        $projects = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->get();
        $all_services = Service::published()->get();
        return view('front.projects', compact('projects', 'all_services'));
    }

    public function singleProject($slug)
    {
        $project = Project::with(['projects_images', 'projects_videos', 'projects_documents', 'project_category'])->where('slug', $slug)->firstOrFail();
        $relatedProjects = Project::with(['projects_images', 'projects_videos', 'projects_documents', 'project_category'])
            ->where('project_category_id', $project->project_category_id)
            ->where('id', '!=', $project->id)
            ->take(3)
            ->get();

        $all_services = Service::published()->get();
        return view('front.project_details', compact('project', 'relatedProjects', 'all_services'));
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
                $query->where('status', 'published');
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
        ])->get();

        return view('front.all-services', ['main_categories' => $categories, 'search' => $search]);
    }

    public function submitInquiry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id',
            'message' => 'nullable|string',
        ]);

        // 1. Save to Database
        $inquiry = Inquiry::create($request->all());

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

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Thank you! Your inquiry has been submitted successfully.']);
        }

        return back()->with('success', 'Thank you! Your inquiry has been submitted successfully.');
    }
}