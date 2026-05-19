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
        return redirect()->route('front.new-about');
    }


    public function contact()
    {
        return view('front.contact');
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
