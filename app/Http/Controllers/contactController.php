<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Service;
use App\Models\TestimonialSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $Projects  = Testimonial::with('service')->latest()->get();
        $services  = Service::published()->orderBy('name')->get();
        $settings  = TestimonialSetting::current();
        return view('dashboard.contact.testimonial', compact('Projects', 'services', 'settings'));
    }

    // Store a new testimonial
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'author_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'sometimes|boolean',
            'author_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'testimonial_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['author_name', 'email', 'position', 'company_name', 'content', 'rating', 'service_id']);
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['approved'] = 1;
        $data['source']   = 'admin';

        $data['author_image'] = '';
        if ($request->hasFile('author_image')) {
            $file = $request->file('author_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/testimonials'), $filename);
            $data['author_image'] = $filename;
        }

        $testimonial = Testimonial::create($data);

        if ($request->testimonial_date) {
            $testimonial->timestamps = false;
            $testimonial->created_at = \Carbon\Carbon::parse($request->testimonial_date)->startOfDay();
            $testimonial->save();
        }

        return response()->json([
            'message' => 'Testimonial added successfully',
            'data' => $testimonial
        ]);
    }

    // Get a testimonial for editing
    public function getTestimonial(Request $request)
    {
        $testimonial = Testimonial::findOrFail($request->id);

        return response()->json([
            'data' => $testimonial
        ]);
    }

    // Update a testimonial
    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'author_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'sometimes|boolean',
            'author_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'testimonial_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $testimonial->author_name = $request->author_name;
        $testimonial->email       = $request->email;
        $testimonial->position    = $request->position;
        $testimonial->company_name = $request->company_name;
        $testimonial->content     = $request->content;
        $testimonial->rating      = $request->rating;
        $testimonial->service_id  = $request->service_id ?: null;
        $testimonial->featured    = $request->has('featured') ? 1 : 0;

        if ($request->testimonial_date) {
            $testimonial->timestamps = false;
            $testimonial->created_at = \Carbon\Carbon::parse($request->testimonial_date)->startOfDay();
        }

        if ($request->hasFile('author_image')) {
            $file = $request->file('author_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/testimonials'), $filename);
            $testimonial->author_image = $filename;
        }

        $testimonial->save();

        return response()->json([
            'message' => 'Testimonial updated successfully',
            'data' => $testimonial
        ]);
    }

    // Delete a testimonial
    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial deleted successfully'
        ]);
    }

    public function toggleFeatured(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->featured = !$testimonial->featured;
        $testimonial->save();
        return response()->json(['message' => 'Featured status updated', 'featured' => $testimonial->featured]);
    }

    public function toggleApproved(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->approved = !$testimonial->approved;
        $testimonial->save();
        return response()->json(['message' => 'Approval status updated', 'approved' => $testimonial->approved]);
    }

    public function saveSettings(Request $request)
    {
        $request->validate(['hero_message' => 'required|string|max:500']);
        $settings = TestimonialSetting::current();
        $settings->update(['hero_message' => $request->hero_message]);
        return response()->json(['message' => 'Settings saved']);
    }
}