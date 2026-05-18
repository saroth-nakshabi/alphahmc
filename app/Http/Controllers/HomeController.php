<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\ApprovalAuthority;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Service;
use App\Models\Faq;
use App\Models\HomeSlider;
use App\Models\Inquiry;
use App\Models\Schedule;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Tag;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];
        $data['featured_services'] = Service::published()->where('featured', 1)->limit(6)->get();
        $data['blogs'] = Blog::limit(6)->get();
        $data['sliders'] = HomeSlider::where('status', 'active')->get();

        return view('front.index', $data);
    }

    public function how_alpha_work()
    {
        return view('front.how_alpha_work');
    }

    public function healthcare_quality_assurance()
    {
        return view('front.healthcare_quality_assurance');
    }

    public function about()
    {
        return view('front.about');
    }


    public function contact()
    {
        return view('front.contact');
    }

    public function services(Request $request )
    {
        $data = [];
        // $data['services'] = Service::paginate(9);

        $search = $request->input('search');
        $data['search'] = $search;

        // Query the posts, optionally applying a search filter if the user entered a search term
        $data['services'] = Service::published()->when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate(10);

        return view('front.services', $data);
    }

    public function viewService($slug)
    {
        $data = [];
        $data['service'] = Service::published()->where('slug', $slug)->first();

        if (!$data['service']) {
            return redirect()->route('front.services');
        }

        // Fetch related services and questions
        $categoryIds = $data['service']->categories->pluck('id');
        $data['related_services'] = Service::published()->whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })->where('id', '!=', $data['service']->id)->get();


        $data['test_questions'] = TestQuestion::where('service_id', $data['service']->id)->get();
        $data['test_answers'] = TestAnswer::whereIn('test_question_id', $data['test_questions']->pluck('id'))->get();
        $data['featuredServices'] = Service::published()->where('featured', true)->take(3)->get();
        $data['agents'] = Agent::all();

        return view('front.view_service', $data);
    }




    public function viewCategory(Request $request, $category_name)
    {
        $data = [];
        // $service_name = Str::slug($service_name);
        $category_name = str_replace('-', ' ', $category_name);
        $data['category'] = Category::where('name', $category_name)->first();

        if (!$data['category']) {
            return redirect()->route('home');
        }

        $search = $request->input('search');
        $data['search'] = $search;

        $data['services'] = Service::published()->with(['categories' => function ($query) use ($category_name) {
            $query->where('name', $category_name);
        }])->when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate(9);

        // Handle dynamic service name and return view
        return view('front.category', $data);
    }

    public function serviceCalendar()
    {
        $data = [];
        $data['services'] = Service::published()->with(['upComingSchedules'])->get();

        return view('front.service_calendar', $data);
    }

    public function blog(Request $request)
    {
        $data = [];

        $search = $request->input('search');
        $data['search'] = $search;
        $data['tags'] = Tag::all();

        // Query the posts, optionally applying a search filter if the user entered a search term
        $data['blogs'] = Blog::when($search, function ($query, $search) {
            return $query->where('title', 'LIKE', '%' . $search . '%');
        })->paginate(9);

        return view('front.blog', $data);
    }
    public function viewBlog($slug)
    {
        $data = [];

        $data['blog'] = Blog::where('slug', $slug)->first();
        $data['tags'] = Tag::all();

        if (!$data['blog']) {
            return redirect()->route('front.blog');
        }

        $data['recent_blogs'] = Blog::where('id', '!=', $data['blog']->id)->orderBy('updated_at', 'DESC')->limit(3)->get();


        // Handle dynamic service name and return view
        return view('front.view_blog', $data);
    }
}
