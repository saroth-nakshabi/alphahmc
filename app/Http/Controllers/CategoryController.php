<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Agent;
use App\Models\Announcement;
use App\Models\Faq;
use App\Models\ServiceMagazine;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $data = [];
        $data['categories'] = Category::with(['mainCategory', 'services', 'serviceGroups'])
            ->orderBy('sort_order')->orderBy('id')->get();
        $data['main_categories'] = MainCategory::all();
        return view('dashboard.categories.index', $data);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:categories,id',
        ]);

        foreach ($request->order as $position => $id) {
            Category::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order saved!']);
    }

    public function toggleFeatured($id)
    {
        $category = Category::findOrFail($id);
        $category->featured = !$category->featured;
        $category->save();

        return response()->json([
            'success'  => true,
            'featured' => (bool) $category->featured,
            'message'  => $category->featured
                ? $category->name . ' is now featured on the home page.'
                : $category->name . ' removed from the home page.',
        ]);
    }

    public function create()
    {
        $data = [];
        $data['main_categories'] = MainCategory::all();
        $data['agents'] = Agent::all();
        $data['announcements'] = Announcement::all();
        $data['services'] = Service::all();
        return view('dashboard.categories.create', $data);
    }

    public function edit($id)
    {
        $data = [];
        $data['category'] = Category::with(['mainCategories', 'faqs', 'magazines', 'images'])->findOrFail($id);
        $data['main_categories'] = MainCategory::all();
        $data['agents'] = Agent::all();
        $data['announcements'] = Announcement::all();
        $data['services'] = Service::all();
        return view('dashboard.categories.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_category_ids'          => 'required|array|min:1',
            'main_category_ids.*'        => 'exists:main_categories,id',
            'name'                       => 'required|max:255',
            'slug'                       => 'required|unique:categories,slug',
            'image'                      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'hero_image'                 => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sliding_image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'description'                => 'required',
            'agent_id'                   => 'required|exists:agents,id',
            'overview'                   => 'nullable|string',
            'service_header'             => 'nullable|max:255',
            'core_service_header'        => 'nullable|array',
            'core_service_header.*'      => 'nullable|string',
            'core_service_description'   => 'nullable|array',
            'core_service_description.*' => 'nullable|string',
            'process_header'             => 'nullable|array',
            'process_header.*'           => 'nullable|string',
            'process_description'        => 'nullable|array',
            'process_description.*'      => 'nullable|string',
        ]);

        $image_name = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/category_images'), $image_name);
        }

        $hero_image_path = null;
        if ($request->hasFile('hero_image')) {
            $hero_image = $request->file('hero_image');
            $hero_image_name = time() . '_hero_' . Str::uuid() . '.' . $hero_image->getClientOriginalExtension();
            $hero_image->move(public_path('uploads/category_images'), $hero_image_name);
            $hero_image_path = 'uploads/category_images/' . $hero_image_name;
        }

        $sliding_image_path = null;
        if ($request->hasFile('sliding_image')) {
            $sliding_image = $request->file('sliding_image');
            $sliding_image_name = time() . '_sliding_' . Str::uuid() . '.' . $sliding_image->getClientOriginalExtension();
            $sliding_image->move(public_path('uploads/category_images'), $sliding_image_name);
            $sliding_image_path = 'uploads/category_images/' . $sliding_image_name;
        }

        $mainCategoryIds = $request->input('main_category_ids');
        $primaryCategoryId = $mainCategoryIds[0];

        $processHeaders = array_values(array_filter($request->input('process_header', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));
        $processDescriptions = array_values(array_filter($request->input('process_description', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $item = Category::create([
            'main_category_id' => $primaryCategoryId,
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'image' => $image_name,
            'hero_image' => $hero_image_path,
            'sliding_image' => $sliding_image_path,
            'description' => $request->input('description'),
            'overview' => $request->input('overview'),
            'agent_id' => $request->input('agent_id'),
            'inq_officer_name' => $request->input('inq_officer_name'),
            'inq_officer_phone' => $request->input('inq_officer_phone'),
            'service_header' => $request->input('service_header'),
            'core_service_header' => $request->input('core_service_header'),
            'core_service_description' => $request->input('core_service_description'),
            'process_header' => $processHeaders,
            'process_description' => $processDescriptions,
            'info_three' => $request->input('info_three'),
            'info_four' => $request->input('info_four'),
            'announcement_id' => !empty($request->input('announcement_id')) ? $request->input('announcement_id') : null,
            'related_services' => $request->input('related_services'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'areaServed' => $request->input('areaServed'),
            'serviceType' => $request->input('serviceType'),
            'featured' => $request->has('featured') ? 1 : 0,
        ]);

        $item->mainCategories()->sync($mainCategoryIds);

        // Handle Gallery Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image_file) {
                $gallery_image_name = time() . '_gallery_' . Str::uuid() . '.' . $image_file->getClientOriginalExtension();
                $image_file->move(public_path('uploads/category_images'), $gallery_image_name);
                $item->images()->create(['image' => 'uploads/category_images/' . $gallery_image_name]);
            }
        }

        // Handle FAQs
        if ($request->has('faqs')) {
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $item->faqs()->create([
                        'faq_question' => $faq['question'],
                        'faq_answer' => $faq['answer'],
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
                        $image_file->move(public_path('uploads/category_images'), $mag_image_name);
                    }

                    $item->magazines()->create([
                        'title' => $title,
                        'description' => $description,
                        'image' => $mag_image_name,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $item = Category::findOrFail($id);

        $request->validate([
            'main_category_ids'          => 'required|array|min:1',
            'main_category_ids.*'        => 'exists:main_categories,id',
            'name'                       => 'required|max:255',
            'slug'                       => 'required|unique:categories,slug,' . $id,
            'image'                      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'hero_image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sliding_image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'description'                => 'required',
            'agent_id'                   => 'required|exists:agents,id',
            'overview'                   => 'nullable|string',
            'service_header'             => 'nullable|max:255',
            'core_service_header'        => 'nullable|array',
            'core_service_header.*'      => 'nullable|string',
            'core_service_description'   => 'nullable|array',
            'core_service_description.*' => 'nullable|string',
            'process_header'             => 'nullable|array',
            'process_header.*'           => 'nullable|string',
            'process_description'        => 'nullable|array',
            'process_description.*'      => 'nullable|string',
        ]);

        $imagePath = $item->image;
        if ($request->hasFile('image')) {
            if ($imagePath && file_exists(public_path('uploads/category_images/' . $imagePath))) {
                unlink(public_path('uploads/category_images/' . $imagePath));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/category_images'), $imageName);
            $imagePath = $imageName;
        }

        $heroImagePath = $item->hero_image;
        if ($request->hasFile('hero_image')) {
            if ($heroImagePath && file_exists(public_path($heroImagePath))) {
                unlink(public_path($heroImagePath));
            }
            $hero_image = $request->file('hero_image');
            $hero_image_name = time() . '_hero_' . Str::uuid() . '.' . $hero_image->getClientOriginalExtension();
            $hero_image->move(public_path('uploads/category_images'), $hero_image_name);
            $heroImagePath = 'uploads/category_images/' . $hero_image_name;
        }

        $slidingImagePath = $item->sliding_image;
        if ($request->hasFile('sliding_image')) {
            if ($slidingImagePath && file_exists(public_path($slidingImagePath))) {
                unlink(public_path($slidingImagePath));
            }
            $sliding_image = $request->file('sliding_image');
            $sliding_image_name = time() . '_sliding_' . Str::uuid() . '.' . $sliding_image->getClientOriginalExtension();
            $sliding_image->move(public_path('uploads/category_images'), $sliding_image_name);
            $slidingImagePath = 'uploads/category_images/' . $sliding_image_name;
        }

        $mainCategoryIds = $request->input('main_category_ids');
        $primaryCategoryId = $mainCategoryIds[0];

        $processHeaders = array_values(array_filter($request->input('process_header', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));
        $processDescriptions = array_values(array_filter($request->input('process_description', []), function ($value) {
            return !is_null($value) && trim(strip_tags((string) $value)) !== '';
        }));

        $item->update([
            'main_category_id' => $primaryCategoryId,
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'image' => $imagePath,
            'hero_image' => $heroImagePath,
            'sliding_image' => $slidingImagePath,
            'description' => $request->input('description'),
            'overview' => $request->input('overview'),
            'agent_id' => $request->input('agent_id'),
            'inq_officer_name' => $request->input('inq_officer_name'),
            'inq_officer_phone' => $request->input('inq_officer_phone'),
            'service_header' => $request->input('service_header'),
            'core_service_header' => $request->input('core_service_header'),
            'core_service_description' => $request->input('core_service_description'),
            'process_header' => $processHeaders,
            'process_description' => $processDescriptions,
            'info_three' => $request->input('info_three'),
            'info_four' => $request->input('info_four'),
            'announcement_id' => !empty($request->input('announcement_id')) ? $request->input('announcement_id') : null,
            'related_services' => $request->input('related_services'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'areaServed' => $request->input('areaServed'),
            'serviceType' => $request->input('serviceType'),
            'featured' => $request->has('featured') ? 1 : 0,
        ]);

        $item->mainCategories()->sync($mainCategoryIds);

        // Handle Gallery Images (Add new ones)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image_file) {
                $gallery_image_name = time() . '_gallery_' . Str::uuid() . '.' . $image_file->getClientOriginalExtension();
                $image_file->move(public_path('uploads/category_images'), $gallery_image_name);
                $item->images()->create(['image' => 'uploads/category_images/' . $gallery_image_name]);
            }
        }

        // Handle FAQs (Delete and Recreate)
        $item->faqs()->delete();
        if ($request->has('faqs')) {
            foreach ($request->input('faqs') as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $item->faqs()->create([
                        'faq_question' => $faq['question'],
                        'faq_answer' => $faq['answer'],
                    ]);
                }
            }
        }

        // Handle Magazines
        $existingMagazines = $item->magazines;
        foreach ($existingMagazines as $mag) {
            if ($mag->image && file_exists(public_path('uploads/category_images/' . $mag->image))) {
                unlink(public_path('uploads/category_images/' . $mag->image));
            }
        }
        $item->magazines()->delete();

        if ($request->has('magazines')) {
            foreach ($request->input('magazines') as $index => $mag_data) {
                $title = $mag_data['title'] ?? null;
                $description = $mag_data['description'] ?? null;
                
                if ($title && $description) {
                    $mag_image_name = null;
                    if ($request->hasFile("magazines.$index.image")) {
                        $image_file = $request->file("magazines.$index.image");
                        $mag_image_name = time() . '_mag_' . Str::uuid() . '.' . $image_file->getClientOriginalExtension();
                        $image_file->move(public_path('uploads/category_images'), $mag_image_name);
                    } elseif (isset($mag_data['existing_image'])) {
                        $mag_image_name = $mag_data['existing_image'];
                    }

                    $item->magazines()->create([
                        'title' => $title,
                        'description' => $description,
                        'image' => $mag_image_name,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 200);
    }

    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 200);
    }

    public function getCategory(Request $request)
    {
        $id = $request->input('id');
        $item = Category::with('mainCategories')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => array_merge($item->toArray(), [
                'main_category_ids' => $item->mainCategories->pluck('id')->toArray(),
            ]),
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