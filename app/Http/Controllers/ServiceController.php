<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceDocument;
use App\Models\ServiceImage;
use App\Models\TapService;
use App\Models\ServiceMagazine;
use App\Models\Faq;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $data = [];
        $data['services'] = Service::with(['categories.mainCategory'])->get();
        $data['categories'] = Category::all();
        $data['agents'] = Agent::all();

        return view('dashboard.services.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['main_categories'] = MainCategory::all();
        $data['agents'] = Agent::all();
        $data['services'] = Service::all();
        $data['announcements'] = \App\Models\Announcement::where('status', true)->get();

        return view('dashboard.services.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:services,slug',
            'hero_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sliding_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'overview' => 'required|string',
            'content' => 'required|string',
            'info_one' => 'required|string',
            'info_two' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
            'agent' => 'nullable|exists:agents,id',
            'tab_services' => 'nullable|array',
            'tab_services.*.tab_service_name' => 'nullable|string|max:255',
            'tab_services.*.tab_service_description' => 'nullable|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:255',
            'faqs.*.answer' => 'nullable|string',
            'info_three' => 'nullable|string',
            'info_four' => 'nullable|string',
            'related_services' => 'nullable|array',
            'related_services.*' => 'integer|exists:services,id',
            'announcement_id' => 'nullable|integer|exists:announcements,id',
            'inq_officer_name' => 'nullable|string|max:255',
            'inq_officer_phone' => 'nullable|string|max:20',
        ];

        // Check if the accreditation body is required based on the user's input
        if ($request->accreditation_body == 'yes') {
            $rules['accreditation_authority'] = 'required';
        }
        if ($request->cme_approval == 'Category 1 CME' || $request->cme_approval == 'Category 2 CME') {
            $rules['cme_authority'] = 'required';
            $rules['cme_hours'] = 'required';
        }

        $messages = [
            'name.required' => 'Service name is required.',
            'name.max' => 'Service name may not exceed 255 characters.',
            'slug.required' => 'Service slug is required.',
            'slug.max' => 'Service slug may not exceed 255 characters.',
            'slug.unique' => 'This service slug is already in use. Please choose another.',
            'hero_image.required' => 'Please upload a hero image.',
            'hero_image.image' => 'Hero image must be a valid image file.',
            'hero_image.mimes' => 'Hero image must be jpeg, png, jpg, or gif.',
            'hero_image.max' => 'Hero image must be smaller than 2MB.',
            'sliding_image.image' => 'Slider image must be a valid image file.',
            'sliding_image.mimes' => 'Slider image must be jpeg, png, jpg, or gif.',
            'sliding_image.max' => 'Slider image must be smaller than 2MB.',
            'overview.required' => 'Overview text is required.',
            'content.required' => 'Hero description is required.',
            'info_one.required' => 'Core service header is required.',
            'info_two.required' => 'Core service description is required.',
            'categories.required' => 'Please select at least one category.',
            'categories.array' => 'Categories must be a valid list.',
            'categories.*.integer' => 'Each category must be a valid ID.',
            'categories.*.exists' => 'One or more selected categories are invalid.',
            'agent.exists' => 'The selected agent is invalid.',
            'related_services.*.integer' => 'Related service IDs must be integers.',
            'related_services.*.exists' => 'One or more related services are invalid.',
            'announcement_id.integer' => 'Announcement selection is invalid.',
            'announcement_id.exists' => 'Selected announcement does not exist.',
            'inq_officer_phone.max' => 'Inquiry officer phone may not exceed 20 characters.',
            'accreditation_authority.required' => 'Please select the accreditation authority.',
            'cme_authority.required' => 'Please provide the CME authority.',
            'cme_hours.required' => 'Please provide the number of CME hours.',
        ];

        $request->validate($rules, $messages);

        $service = Service::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'hero_image' => $this->uploadFile($request, 'hero_image'),
            'sliding_image' => $this->uploadFile($request, 'sliding_image'),
            'overview' => $request->input('overview'),
            'content' => $request->input('content'),
            'info_one' => $request->input('info_one'),
            'info_two' => $request->input('info_two'),
            'info_three' => $request->input('info_three'),
            'info_four' => $request->input('info_four'),
            'related_services' => $request->input('related_services'),
            'announcement_id' => $request->input('announcement_id'),
            'featured'       => $request->input('featured') ?? 0,
            'status'         => in_array($request->input('status'), ['draft', 'published']) ? $request->input('status') : 'published',
            'published_date' => $request->input('published_date') ?: now()->toDateString(),
            'updated_date'   => $request->input('updated_date') ?: now()->toDateString(),
            'meta_title'        => $request->input('meta_title'),
            'meta_description'  => $request->input('meta_description'),
            'meta_keywords'     => $request->input('meta_keywords'),
            'areaServed'        => $request->input('areaServed'),
            'serviceType'       => $request->input('serviceType'),
            'agent_id'          => $request->input('agent') ?? null,
            'inq_officer_name'  => $request->input('inq_officer_name'),
            'inq_officer_phone' => $request->input('inq_officer_phone'),
        ]);




        foreach ($request->input('categories') as $category_id) {
            ServiceCategory::create([
                'service_id' => $service->id,
                'category_id' => $category_id,
            ]);
        }

        // Store tab services if provided
        if ($request->has('tab_services')) {
            foreach ($request->input('tab_services') as $index => $tabService) {
                if (!empty($tabService['tab_service_name'])) {
                    TapService::create([
                        'name' => $tabService['tab_service_name'],
                        'description' => $tabService['tab_service_description'] ?? null,
                        'service_id' => $service->id,
                    ]);
                }
            }
        }

        // Store multiple service images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = ImageProcessor::saveAsJpeg(
                    $image, public_path('uploads/service_images'), time() . '_' . Str::uuid()
                );
                ServiceImage::create(['service_id' => $service->id, 'image' => $imageName]);
            }
        }

        // Create magazines if provided
        if (!empty($request->input('magazines'))) {
            foreach ($request->input('magazines') as $index => $magItem) {
                if (!empty($magItem['title'])) {
                    $magImage = null;
                    if ($request->hasFile("magazines.$index.image")) {
                        $magImage = ImageProcessor::saveAsJpeg(
                            $request->file("magazines.$index.image"),
                            public_path('uploads/magazines'),
                            time() . '_mag_' . Str::uuid()
                        );
                    }
                    ServiceMagazine::create([
                        'title'       => $magItem['title'],
                        'description' => $magItem['description'] ?? null,
                        'image'       => $magImage,
                        'service_id'  => $service->id,
                    ]);
                }
            }
        }

        // Create FAQs if provided
        if (!empty($request->input('faqs'))) {
            foreach ($request->input('faqs') as $faqItem) {
                if (!empty($faqItem['question'])) {
                    Faq::create([
                        'faq_question' => $faqItem['question'],
                        'faq_answer' => $faqItem['answer'] ?? null,
                        'service_id' => $service->id,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $service,
        ], 201);
    }
    

    public function edit($id)
    {
        $data = [];
        $data['categories'] = Category::all();
        $data['service'] = Service::with('agent', 'ServiceTab', 'faq', 'magazines')->findOrFail($id);

        // dd($data['service']);
        $data['agents'] = Agent::all();
        $data['services'] = Service::where('id', '!=', $id)->get();
        $data['announcements'] = \App\Models\Announcement::where('status', true)->get();

        return view('dashboard.services.edit', $data);
    }

    public function show($slug)
{
    $service = Service::with(['faq', 'ServiceTab', 'categories', 'agent'])
                      ->where('slug', $slug)
                      ->firstOrFail();

    return view('frontend.services.show', compact('service'));
}

    
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:services,slug,' . $id,
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sliding_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'overview' => 'required|string',
            'content' => 'required|string',
            'info_one' => 'required|string',
            'info_two' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
            'agent' => 'nullable|exists:agents,id',
            'tab_services' => 'nullable|array',
            'tab_services.*.tab_service_name' => 'nullable|string|max:255',
            'tab_services.*.tab_service_description' => 'nullable|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:255',
            'faqs.*.answer' => 'nullable|string',
            'info_three' => 'nullable|string',
            'info_four' => 'nullable|string',
            'related_services' => 'nullable|array',
            'related_services.*' => 'integer|exists:services,id',
            'announcement_id' => 'nullable|integer|exists:announcements,id',
            'magazines' => 'nullable|array',
            'magazines.*.title' => 'required_with:magazines|string|max:255',
            'magazines.*.description' => 'required_with:magazines|string',
            'magazines.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_date' => 'nullable|date',
            'updated_date'   => 'nullable|date',
        ];

        if ($request->accreditation_body == 'yes') {
            $rules['accreditation_authority'] = 'required';
        }
        if ($request->cme_approval == 'Category 1 CME' || $request->cme_approval == 'Category 2 CME') {
            $rules['cme_authority'] = 'required';
            $rules['cme_hours'] = 'required';
        }
        $request->validate($rules);

        // Find the service to update
        $service = Service::with('images', 'ServiceTab', 'faq', 'magazines')->findOrFail($id);

        // Update service attributes
        $service->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'overview' => $request->input('overview'),
            'content' => $request->input('content'),
            'info_one' => $request->input('info_one'),
            'info_two' => $request->input('info_two'),
            'info_three' => $request->input('info_three'),
            'info_four' => $request->input('info_four'),
            'related_services' => $request->input('related_services'),
            'announcement_id' => $request->input('announcement_id'),
            'featured'          => $request->input('featured') ?? 0,
            'show_testimonials' => $request->input('show_testimonials') ?? 0,
            'status'         => in_array($request->input('status'), ['draft', 'published']) ? $request->input('status') : $service->status,
            'published_date' => $request->input('published_date') ?: $service->published_date?->toDateString(),
            'updated_date'   => $request->input('updated_date') ?: now()->toDateString(),
            'meta_title'        => $request->input('meta_title'),
            'meta_description'  => $request->input('meta_description'),
            'meta_keywords'     => $request->input('meta_keywords'),
            'areaServed'        => $request->input('areaServed'),
            'serviceType'       => $request->input('serviceType'),
            'agent_id'          => $request->input('agent') ?? null,
            'inq_officer_name'  => $request->input('inq_officer_name'),
            'inq_officer_phone' => $request->input('inq_officer_phone'),
        ]);


        if ($request->hasFile('hero_image')) {
            $this->deleteFile($service->hero_image);
            $service->update(['hero_image' => $this->uploadFile($request, 'hero_image')]);
        }

        if ($request->hasFile('sliding_image')) {
            $this->deleteFile($service->sliding_image);
            $service->update(['sliding_image' => $this->uploadFile($request, 'sliding_image')]);
        }



        // Sync the categories
        $service->categories()->sync($request->input('categories'));

        // --- Handle Tab Services (add, update, delete) ---
        $submittedTabServices = $request->input('tab_services', []);
        $processedTabIds = [];

        if (is_array($submittedTabServices)) {
            foreach ($submittedTabServices as $tabData) {
                if (isset($tabData['tab_service_name']) && $tabData['tab_service_name'] !== '') {
                    if (!empty($tabData['id'])) {
                        // Update existing via relationship
                        $tab = $service->ServiceTab()->find($tabData['id']);
                        if ($tab) {
                            $tab->update([
                                'name' => $tabData['tab_service_name'],
                                'description' => $tabData['tab_service_description'] ?? null,
                            ]);
                            $processedTabIds[] = $tab->id;
                        }
                    } else {
                        // Create new via relationship
                        $newTab = $service->ServiceTab()->create([
                            'name' => $tabData['tab_service_name'],
                            'description' => $tabData['tab_service_description'] ?? null,
                        ]);
                        $processedTabIds[] = $newTab->id;
                    }
                }
            }
        }

        // Delete removed tab services
        $service->ServiceTab()->whereNotIn('id', $processedTabIds)->delete();

        // Handle multiple service images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = ImageProcessor::saveAsJpeg(
                    $image, public_path('uploads/service_images'), time() . '_' . Str::uuid()
                );
                ServiceImage::create(['service_id' => $service->id, 'image' => $imageName]);
            }
        }

        // Handle image deletion if requested
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $imageId) {
                $img = ServiceImage::find($imageId);
                if ($img) {
                    $this->deleteFile($img->image);
                    $img->delete();
                }
            }
        }

        // --- Handle FAQs (add, update, delete) ---
        $submittedFaqs = $request->input('faqs', []);
        $processedFaqIds = [];

        if (is_array($submittedFaqs)) {
            foreach ($submittedFaqs as $faqItem) {
                if (isset($faqItem['question']) && $faqItem['question'] !== '') {
                    if (!empty($faqItem['id'])) {
                        // Update existing FAQ via relationship
                        $faq = $service->faq()->find($faqItem['id']);
                        if ($faq) {
                            $faq->update([
                                'faq_question' => $faqItem['question'],
                                'faq_answer' => $faqItem['answer'] ?? null,
                            ]);
                            $processedFaqIds[] = $faq->id;
                        }
                    } else {
                        // Create new FAQ via relationship
                        $newFaq = $service->faq()->create([
                            'faq_question' => $faqItem['question'],
                            'faq_answer' => $faqItem['answer'] ?? null,
                        ]);
                        $processedFaqIds[] = $newFaq->id;
                    }
                }
            }
        }

        // Delete removed FAQs
        $service->faq()->whereNotIn('id', $processedFaqIds)->delete();

        // --- Handle Magazines (add, update, delete) ---
        // Only sync magazines when the request explicitly contains magazine data.
        // The magazine section is managed separately via ServiceMagazineController,
        // so when the main edit form is submitted without inline magazine inputs we
        // must NOT touch existing magazines (otherwise whereNotIn(id, []) deletes them all).
        if ($request->has('magazines')) {
            $submittedMagazines = $request->input('magazines', []);
            $processedMagIds = [];

            if (is_array($submittedMagazines)) {
                foreach ($submittedMagazines as $index => $magData) {
                    if (isset($magData['title']) && $magData['title'] !== '') {
                        if (!empty($magData['id'])) {
                            $existingMag = $service->magazines()->find($magData['id']);
                            $magImage = $existingMag ? $existingMag->image : null;
                        } else {
                            $magImage = null;
                        }
                        if ($request->hasFile("magazines.$index.image")) {
                            if ($magImage && file_exists(public_path('uploads/magazines/' . $magImage))) {
                                unlink(public_path('uploads/magazines/' . $magImage));
                            }
                            $magImage = ImageProcessor::saveAsJpeg(
                                $request->file("magazines.$index.image"),
                                public_path('uploads/magazines'),
                                time() . '_mag_' . Str::uuid()
                            );
                        }

                        if (!empty($magData['id'])) {
                            // Update existing Magazine via relationship
                            $mag = $service->magazines()->find($magData['id']);
                            if ($mag) {
                                $mag->update([
                                    'title' => $magData['title'],
                                    'description' => $magData['description'] ?? null,
                                    'image' => $magImage,
                                ]);
                                $processedMagIds[] = $mag->id;
                            }
                        } else {
                            // Create new Magazine via relationship
                            $newMag = $service->magazines()->create([
                                'title' => $magData['title'],
                                'description' => $magData['description'] ?? null,
                                'image' => $magImage,
                            ]);
                            $processedMagIds[] = $newMag->id;
                        }
                    }
                }
            }

            // Delete removed magazines and their images
            $removedMagazines = $service->magazines()->whereNotIn('id', $processedMagIds)->get();
            foreach ($removedMagazines as $rMag) {
                if ($rMag->image && file_exists(public_path('uploads/magazines/' . $rMag->image))) {
                    unlink(public_path('uploads/magazines/' . $rMag->image));
                }
                $rMag->delete();
            }
        }
        // If $request->has('magazines') is false, existing magazines are left untouched.

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $service->fresh(['ServiceTab', 'faq', 'magazines']),
        ], 200);
    }



    public function destroy($id)
    {
        $item = Service::findOrFail($id);

        // Delete the image file from the storage
        if (file_exists(public_path('uploads/product_images/' . $item->images->first()->image))) {
            unlink(public_path('uploads/product_images/' . $item->images->first()->image));
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getService(Request $request)
    {
        $id = $request->input('id');
        $item = Service::with(["categories"])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    public function featuredHandle(Request $request)
    {
        $id = $request->input('id');
        $featured = $request->input('featured');

        $item = Service::findOrFail($id);
        $item->update([
            'featured' => $featured
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    public function toggleStatus(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $newStatus = $service->status === 'published' ? 'draft' : 'published';
        $service->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus === 'published' ? 'Service published successfully!' : 'Service moved to draft.',
            'status' => $newStatus,
        ]);
    }

    public function uploadDocuments(Request $request, $id)
    {
        // Validate incoming request data
        $rules = [
            'service_curriculum' => 'nullable|mimes:pdf|max:5000', // Make image nullable
            'service_panel' => 'nullable|mimes:pdf|max:5000', // Make image nullable
            'yearly_service_analysis' => 'nullable|mimes:pdf|max:5000', // Make image nullable
        ];
        $request->validate($rules);

        // Find the service to update
        $service = Service::findOrFail($id);

        // Handle document uploads
        // service curriculum
        if ($request->hasFile('service_curriculum')) {
            $existing_document = $service->documents->where('name', 'service_curriculum')->first(); // Assuming images is a relation

            // Delete the existing document file if it exists
            if ($existing_document && file_exists(public_path('uploads/service_documents/' . $existing_document->document))) {
                unlink(public_path('uploads/service_documents/' . $existing_document->document));
                $existing_document->delete(); // Remove the document record from the database
            }

            // Store the new document
            $document = $request->file('service_curriculum');
            $document_name = time() . '_' . Str::uuid() . '.' . $document->getClientOriginalExtension();
            $document->move(public_path('uploads/service_documents'), $document_name);

            // Update or create the service document record
            ServiceDocument::updateOrCreate(
            [
                'service_id' => $service->id,
                'name' => 'service_curriculum'
            ],
            ['file' => $document_name]
            );
        }
        // service panel
        if ($request->hasFile('service_panel')) {
            $existing_document = $service->documents->where('name', 'service_panel')->first(); // Assuming images is a relation

            // Delete the existing document file if it exists
            if ($existing_document && file_exists(public_path('uploads/service_documents/' . $existing_document->document))) {
                unlink(public_path('uploads/service_documents/' . $existing_document->document));
                $existing_document->delete(); // Remove the document record from the database
            }

            // Store the new document
            $document = $request->file('service_panel');
            $document_name = time() . '_' . Str::uuid() . '.' . $document->getClientOriginalExtension();
            $document->move(public_path('uploads/service_documents'), $document_name);

            // Update or create the service document record
            ServiceDocument::updateOrCreate(
            [
                'service_id' => $service->id,
                'name' => 'service_panel'
            ],
            ['file' => $document_name]
            );
        }
        // yearly service analysis
        if ($request->hasFile('yearly_service_analysis')) {
            $existing_document = $service->documents->where('name', 'yearly_service_analysis')->first(); // Assuming images is a relation

            // Delete the existing document file if it exists
            if ($existing_document && file_exists(public_path('uploads/service_documents/' . $existing_document->document))) {
                unlink(public_path('uploads/service_documents/' . $existing_document->document));
                $existing_document->delete(); // Remove the document record from the database
            }

            // Store the new document
            $document = $request->file('yearly_service_analysis');
            $document_name = time() . '_' . Str::uuid() . '.' . $document->getClientOriginalExtension();
            $document->move(public_path('uploads/service_documents'), $document_name);

            // Update or create the service document record
            ServiceDocument::updateOrCreate(
            [
                'service_id' => $service->id,
                'name' => 'yearly_service_analysis'
            ],
            ['file' => $document_name]
            );
        }


        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
        ], 200); // Use 200 status code for a successful update
    }
    private function uploadFile(Request $request, $fieldName)
    {
        if ($request->hasFile($fieldName)) {
            return ImageProcessor::saveAsJpeg(
                $request->file($fieldName),
                public_path('uploads/service_images'),
                time() . '_' . $fieldName . '_' . Str::uuid()
            );
        }
        return null;
    }

    private function deleteFile($fileName)
    {
        if ($fileName && file_exists(public_path('uploads/service_images/' . $fileName))) {
            unlink(public_path('uploads/service_images/' . $fileName));
        }
    }
}