<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceGroup;
use App\Models\Service;
use App\Models\Agent;
use App\Models\Announcement;
use App\Models\MainCategory;
use App\Models\Faq;
use App\Models\ServiceMagazine;
use App\Models\ServiceImage;
use App\Models\Blog;
use App\Models\Project;
use App\Models\globaltag;
use App\Models\googletag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceGroupController extends Controller
{
    public function front(Request $request, $slug = null)
    {
        $serviceQuery = ServiceGroup::with(['agent.user', 'services', 'faqs', 'magazines', 'images', 'announcement']);
        $service = $slug
            ? $serviceQuery->where('slug', $slug)->first()
            : $serviceQuery->orderByDesc('is_featured')->latest()->first();

        if (!$service) {
            return redirect()->route('front.all-services')->with('error', 'No service group found.');
        }

        // Keep the existing view compatible with service-style relation names.
        $service->setRelation('ServiceTab', collect());

        $data = [];
        $data['service'] = $service;
        $data['featuredServices'] = Service::published()->where('featured', true)->take(3)->get();
        $data['all_services'] = Service::published()->get();
        $data['projects'] = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);
        $data['latest_blogs'] = Blog::latest()->take(3)->get();
        $data['globaltag'] = globaltag::all();
        $data['googletag'] = googletag::all();
        $data['service_groups'] = ServiceGroup::all();

        return view('front.service_group', $data);
    }

    public function allServices($slug)
    {
        $service = ServiceGroup::with(['services'])->where('slug', $slug)->first();

        if (!$service) {
            return redirect()->route('front.all-services')->with('error', 'Service group not found.');
        }

        $data = [];
        $data['service'] = $service;
        $data['selectedServices'] = $service->services;
        $data['featuredServices'] = Service::published()->where('featured', true)->take(3)->get();
        $data['all_services'] = Service::published()->get();
        $data['projects'] = Project::with('projects_images', 'projects_videos', 'projects_documents', 'project_category')->paginate(3);
        $data['latest_blogs'] = Blog::latest()->take(3)->get();
        $data['globaltag'] = globaltag::all();
        $data['googletag'] = googletag::all();
        $data['service_groups'] = ServiceGroup::all();

        return view('front.service_group_all_services', $data);
    }

    public function index()
    {
        $data['service_groups'] = ServiceGroup::all();
        return view('dashboard.service_group.index', $data);
    }

    public function create()
    {
        $data['services'] = Service::all();
        $data['agents'] = Agent::all();
        $data['announcements'] = Announcement::all();
        $data['mainCategories'] = MainCategory::with('categories')->get();
        return view('dashboard.service_group.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|unique:service_groups,slug',
            'service_ids' => 'required|array|min:1',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'hero_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'core_service_header' => 'required|array|min:1',
            'core_service_header.*' => 'required|string|max:255',
            'core_service_description' => 'required|array|min:1',
            'core_service_description.*' => 'required|string',
            'process_header' => 'nullable|array',
            'process_header.*' => 'nullable|string|max:255',
            'process_description' => 'nullable|array',
            'process_description.*' => 'nullable|string',
        ]);

        $coreHeaders = array_values(array_filter($request->input('core_service_header', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $coreDescriptions = array_values(array_filter($request->input('core_service_description', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $processHeaders = array_values(array_filter($request->input('process_header', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $processDescriptions = array_values(array_filter($request->input('process_description', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_sg_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/service_group_images'), $imageName);
        }

        $heroImagePath = null;
        if ($request->hasFile('hero_image')) {
            $hero_image = $request->file('hero_image');
            $hero_image_name = time() . '_hero_' . Str::uuid() . '.' . $hero_image->getClientOriginalExtension();
            $hero_image->move(public_path('uploads/service_group_images'), $hero_image_name);
            $heroImagePath = 'uploads/service_group_images/' . $hero_image_name;
        }

        $slidingImagePath = null;
        if ($request->hasFile('sliding_image')) {
            $sliding_image = $request->file('sliding_image');
            $sliding_image_name = time() . '_sliding_' . Str::uuid() . '.' . $sliding_image->getClientOriginalExtension();
            $sliding_image->move(public_path('uploads/service_group_images'), $sliding_image_name);
            $slidingImagePath = 'uploads/service_group_images/' . $sliding_image_name;
        }

        $serviceGroup = ServiceGroup::create([
            'name'                     => $request->input('name'),
            'slug'                     => $request->input('slug'),
            'description'              => $request->input('description'),
            'image'                    => $imageName,
            'hero_image'               => $heroImagePath,
            'sliding_image'            => $slidingImagePath,
            'is_featured'              => $request->has('featured') ? 1 : 0,
            'status'                   => $request->input('status', 'published'),
            'category_id'              => null,
            'agent_id'                 => $request->input('agent_id'),
            'inq_officer_name'         => $request->input('inq_officer_name'),
            'inq_officer_phone'        => $request->input('inq_officer_phone'),
            'content'                  => $request->input('content'),
            'overview'                 => $request->input('overview'),
            'service_header'           => $request->input('service_header'),
            'core_service_header'      => $coreHeaders,
            'core_service_description' => $coreDescriptions,
            'process_header'           => $processHeaders,
            'process_description'      => $processDescriptions,
            'info_three'               => $request->input('info_three'),
            'info_four'                => $request->input('info_four'),
            'announcement_id'          => $request->input('announcement_id'),
            'related_services'         => $request->input('related_services'),
            'meta_title'               => $request->input('meta_title'),
            'meta_description'         => $request->input('meta_description'),
            'meta_keywords'            => $request->input('meta_keywords'),
        ]);

        if ($request->has('service_ids')) {
            $serviceGroup->services()->sync($request->input('service_ids'));
        }

        $serviceGroup->categories()->sync($request->input('category_ids', []));

        // Handle Gallery Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image_file) {
                $gallery_image_name = time() . '_gallery_' . Str::uuid() . '.' . $image_file->getClientOriginalExtension();
                $image_file->move(public_path('uploads/service_group_images'), $gallery_image_name);
                $serviceGroup->images()->create(['image' => 'uploads/service_group_images/' . $gallery_image_name]);
            }
        }

        // Handle FAQs
        if ($request->has('faqs')) {
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $serviceGroup->faqs()->create([
                        'faq_question' => $faq['question'],
                        'faq_answer'   => $faq['answer'],
                    ]);
                }
            }
        }

        // Handle Magazines
        if ($request->has('magazines')) {
            foreach ($request->file('magazines') as $index => $magazine_data) {
                $title = $request->input("magazines.$index.title");
                $description = $request->input("magazines.$index.description");
                $image_file = $magazine_data['image'] ?? null;

                if ($title && $description) {
                    $mag_image_name = null;
                    if ($image_file) {
                        $mag_image_name = time() . '_mag_' . Str::uuid() . '.' . $image_file->getClientOriginalExtension();
                        $image_file->move(public_path('uploads/service_group_images'), $mag_image_name);
                    }
                    $serviceGroup->magazines()->create([
                        'title'       => $title,
                        'description' => $description,
                        'image'       => $mag_image_name,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Service group created successfully!',
            'data'    => $serviceGroup,
        ], 201);
    }

    public function edit($id)
    {
        $data['service_group'] = ServiceGroup::with(['services', 'faqs', 'magazines', 'images', 'categories'])->findOrFail($id);
        $data['services'] = Service::all();
        $data['agents'] = Agent::all();
        $data['announcements'] = Announcement::all();
        $data['mainCategories'] = MainCategory::with('categories')->get();
        return view('dashboard.service_group.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $serviceGroup = ServiceGroup::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|unique:service_groups,slug,' . $id,
            'service_ids' => 'nullable|array',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'hero_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'core_service_header'        => 'nullable|array',
            'core_service_header.*'      => 'nullable|string',
            'core_service_description'   => 'nullable|array',
            'core_service_description.*' => 'nullable|string',
            'process_header'             => 'nullable|array',
            'process_header.*'           => 'nullable|string',
            'process_description'        => 'nullable|array',
            'process_description.*'      => 'nullable|string',
        ]);

        $coreHeaders = array_values(array_filter($request->input('core_service_header', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $coreDescriptions = array_values(array_filter($request->input('core_service_description', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $processHeaders = array_values(array_filter($request->input('process_header', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $processDescriptions = array_values(array_filter($request->input('process_description', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $imageName = $serviceGroup->image;
        if ($request->hasFile('image')) {
            if ($imageName && file_exists(public_path('uploads/service_group_images/' . $imageName))) {
                unlink(public_path('uploads/service_group_images/' . $imageName));
            }
            $file = $request->file('image');
            $imageName = time() . '_sg_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/service_group_images'), $imageName);
        }

        $heroImagePath = $serviceGroup->hero_image;
        if ($request->hasFile('hero_image')) {
            if ($heroImagePath && file_exists(public_path($heroImagePath))) {
                unlink(public_path($heroImagePath));
            }
            $hero_image = $request->file('hero_image');
            $hero_image_name = time() . '_hero_' . Str::uuid() . '.' . $hero_image->getClientOriginalExtension();
            $hero_image->move(public_path('uploads/service_group_images'), $hero_image_name);
            $heroImagePath = 'uploads/service_group_images/' . $hero_image_name;
        }

        $slidingImagePath = $serviceGroup->sliding_image;
        if ($request->hasFile('sliding_image')) {
            if ($slidingImagePath && file_exists(public_path($slidingImagePath))) {
                unlink(public_path($slidingImagePath));
            }
            $sliding_image = $request->file('sliding_image');
            $sliding_image_name = time() . '_sliding_' . Str::uuid() . '.' . $sliding_image->getClientOriginalExtension();
            $sliding_image->move(public_path('uploads/service_group_images'), $sliding_image_name);
            $slidingImagePath = 'uploads/service_group_images/' . $sliding_image_name;
        }

        $serviceGroup->update([
            'name'                     => $request->input('name'),
            'slug'                     => $request->input('slug'),
            'description'              => $request->input('description'),
            'image'                    => $imageName,
            'hero_image'               => $heroImagePath,
            'sliding_image'            => $slidingImagePath,
            'is_featured'              => $request->has('featured') ? 1 : 0,
            'status'                   => $request->input('status', 'published'),
            'category_id'              => null,
            'agent_id'                 => $request->input('agent_id'),
            'inq_officer_name'         => $request->input('inq_officer_name'),
            'inq_officer_phone'        => $request->input('inq_officer_phone'),
            'content'                  => $request->input('content'),
            'overview'                 => $request->input('overview'),
            'service_header'           => $request->input('service_header'),
            'core_service_header'      => $coreHeaders,
            'core_service_description' => $coreDescriptions,
            'process_header'           => $processHeaders,
            'process_description'      => $processDescriptions,
            'info_three'               => $request->input('info_three'),
            'info_four'                => $request->input('info_four'),
            'announcement_id'          => $request->input('announcement_id'),
            'related_services'         => $request->input('related_services'),
            'meta_title'               => $request->input('meta_title'),
            'meta_description'         => $request->input('meta_description'),
            'meta_keywords'            => $request->input('meta_keywords'),
        ]);

        if ($request->has('service_ids')) {
            $serviceGroup->services()->sync($request->input('service_ids'));
        }

        $serviceGroup->categories()->sync($request->input('category_ids', []));

        // Handle Gallery Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image_file) {
                $gallery_image_name = time() . '_gallery_' . Str::uuid() . '.' . $image_file->getClientOriginalExtension();
                $image_file->move(public_path('uploads/service_group_images'), $gallery_image_name);
                $serviceGroup->images()->create(['image' => 'uploads/service_group_images/' . $gallery_image_name]);
            }
        }

        // Handle FAQs
        $serviceGroup->faqs()->delete();
        if ($request->has('faqs')) {
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $serviceGroup->faqs()->create([
                        'faq_question' => $faq['question'],
                        'faq_answer'   => $faq['answer'],
                    ]);
                }
            }
        }

        // Handle Magazines
        if ($request->has('magazines')) {
            // Optional: delete old magazine images if needed
            $serviceGroup->magazines()->delete();
            foreach ($request->input('magazines') as $index => $mag_data) {
                $title = $mag_data['title'] ?? null;
                $description = $mag_data['description'] ?? null;
                if ($title && $description) {
                    $mag_image_name = null;
                    if ($request->hasFile("magazines.$index.image")) {
                        $image_file = $request->file("magazines.$index.image");
                        $mag_image_name = time() . '_mag_' . Str::uuid() . '.' . $image_file->getClientOriginalExtension();
                        $image_file->move(public_path('uploads/service_group_images'), $mag_image_name);
                    } elseif (isset($mag_data['existing_image'])) {
                        $mag_image_name = $mag_data['existing_image'];
                    }
                    $serviceGroup->magazines()->create([
                        'title'       => $title,
                        'description' => $description,
                        'image'       => $mag_image_name,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Service group updated successfully!',
            'data'    => $serviceGroup->fresh(),
        ], 200);
    }

    public function destroy($id)
    {
        $serviceGroup = ServiceGroup::findOrFail($id);
        if ($serviceGroup->image && file_exists(public_path('uploads/service_group_images/' . $serviceGroup->image))) {
            unlink(public_path('uploads/service_group_images/' . $serviceGroup->image));
        }
        $serviceGroup->delete();
        return response()->json([
            'success' => true,
            'message' => 'Service group deleted successfully!',
        ], 200);
    }

    public function deleteGalleryImage(Request $request)
    {
        $image = ServiceImage::findOrFail($request->id);
        if ($image->image && file_exists(public_path($image->image))) {
            unlink(public_path($image->image));
        }
        $image->delete();
        return response()->json(['success' => true]);
    }
}