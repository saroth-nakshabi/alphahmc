<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ProjectVideo;
use App\Models\ProjectDocument;
use App\Models\ProjectCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Service;


class ProjectController extends Controller
{
    public function index()
    {
        $projectsCategories = ProjectCategory::all();
        $Projects = Project::all();
        $services = Service::published()->orderBy('name')->get();

        return view('dashboard.projects.index', compact('Projects', 'projectsCategories', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_category' => 'nullable|exists:project_categories,id',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'document.*' => 'nullable|mimes:pdf,doc,docx,txt|max:5120',
            'name' => 'required|max:255',
            'description' => 'required',
            'slug' => 'required|max:255',
            'featured' => 'nullable|boolean',
            'client_name' => 'nullable|max:255',
            'project_duration' => 'nullable|max:255',
            'project_location' => 'nullable|max:255',
            'regulatory_authority' => 'nullable|max:255',
            'client_website' => 'nullable|max:255',
            'project_scope' => 'nullable',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'challenge_heading' => 'nullable|max:255',
            'challenge_title' => 'nullable',
            'challenge' => 'nullable',
            'resolution' => 'nullable',
        ]);

        // Enforce only one featured project
        if ($request->boolean('featured')) {
            Project::query()->update(['featured' => false]);
        }

        $challengeTitles = $request->input('challenge_title', []);
        $challengeDescriptions = $request->input('challenge', []);
        $challengeResolutions = $request->input('resolution', []);

        if (!is_array($challengeTitles)) {
            $challengeTitles = [$challengeTitles];
        }
        if (!is_array($challengeDescriptions)) {
            $challengeDescriptions = [$challengeDescriptions];
        }
        if (!is_array($challengeResolutions)) {
            $challengeResolutions = [$challengeResolutions];
        }

        $challenges = [];
        $maxCount = max(count($challengeTitles), count($challengeDescriptions), count($challengeResolutions));
        for ($i = 0; $i < $maxCount; $i++) {
            $title = trim($challengeTitles[$i] ?? '');
            $description = trim($challengeDescriptions[$i] ?? '');
            $resolution = trim($challengeResolutions[$i] ?? '');

            if ($title || $description || $resolution) {
                $challenges[] = [
                    'challenge_title' => $title,
                    'challenge' => $description,
                    'resolution' => $resolution,
                ];
            }
        }

        $item = Project::create([
            'project_category_id' => $request->input('project_category') ?: null,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => $request->input('slug'),
            'client_name' => $request->input('client_name'),
            'project_duration' => $request->input('project_duration'),
            'project_location' => $request->input('project_location'),
            'regulatory_authority' => $request->input('regulatory_authority'),
            'client_website' => $request->input('client_website'),
            'project_scope' => $request->input('project_scope'),
            'featured' => $request->boolean('featured'),
            'service_ids' => $request->input('service_ids') ?: null,
            'challenge_heading' => $request->input('challenge_heading') ?: null,
            'challenge_title' => $challengeTitles[0] ?? null,
            'challenge' => $challengeDescriptions[0] ?? null,
            'resolution' => $challengeResolutions[0] ?? null,
            'challenges' => $challenges ?: null,
        ]);

        // Handle multiple image uploads
        $images = $request->file('image');
        if (!empty($images)) {
            $images = is_array($images) ? $images : [$images];
            foreach ($images as $image) {
                $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/project_images'), $image_name);
                ProjectImage::create(['project_id' => $item->id, 'image' => 'uploads/project_images/' . $image_name]);
            }
        }

        // Handle multiple video uploads
        $videos = $request->file('video');
        $video_thumbnails = $request->file('video_thumbnail');
        if (!empty($videos)) {
            $videos = is_array($videos) ? $videos : [$videos];
            foreach ($videos as $index => $video) {
                $video_name = time() . '_' . Str::uuid() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('uploads/project_videos'), $video_name);

                $thumb_path = null;
                if (isset($video_thumbnails[$index])) {
                    $thumb_name = time() . '_thumb_' . Str::uuid() . '.' . $video_thumbnails[$index]->getClientOriginalExtension();
                    $video_thumbnails[$index]->move(public_path('uploads/project_videos/thumbnails'), $thumb_name);
                    $thumb_path = 'uploads/project_videos/thumbnails/' . $thumb_name;
                }

                ProjectVideo::create([
                    'project_id' => $item->id,
                    'video' => 'uploads/project_videos/' . $video_name,
                    'thumbnail' => $thumb_path,
                    'title' => pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME)
                ]);
            }
        }

        // Handle multiple document uploads
        $documents = $request->file('document');
        if (!empty($documents)) {
            $documents = is_array($documents) ? $documents : [$documents];
            foreach ($documents as $document) {
                $doc_name = time() . '_' . Str::uuid() . '.' . $document->getClientOriginalExtension();
                $document->move(public_path('uploads/project_documents'), $doc_name);
                ProjectDocument::create(['project_id' => $item->id, 'document' => 'uploads/project_documents/' . $doc_name, 'title' => pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME)]);
            }
        }


        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $item,
        ], 201);
    }

    public function getProject(Request $request)
    {


        $id = $request->input('id');
        $item = Project::with(["project_category"])->findOrFail($id);


        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, string $id)
    {

        $request->validate([
            'project_category_id' => 'nullable|exists:project_categories,id',
            'name' => 'required|max:255',
            'description' => 'required',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'document.*' => 'nullable|mimes:pdf,doc,docx,txt|max:5120',
            'slug' => 'required|max:255',
            'featured' => 'nullable|boolean',
            'client_name' => 'nullable|max:255',
            'project_duration' => 'nullable|max:255',
            'project_location' => 'nullable|max:255',
            'regulatory_authority' => 'nullable|max:255',
            'client_website' => 'nullable|max:255',
            'project_scope' => 'nullable',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'challenge_heading' => 'nullable|max:255',
            'challenge_title' => 'nullable',
            'challenge' => 'nullable',
            'resolution' => 'nullable',
        ]);

        // Enforce only one featured project
        if ($request->boolean('featured')) {
            Project::where('id', '!=', $id)->update(['featured' => false]);
        }

        $challengeTitles = $request->input('challenge_title', []);
        $challengeDescriptions = $request->input('challenge', []);
        $challengeResolutions = $request->input('resolution', []);

        if (!is_array($challengeTitles)) {
            $challengeTitles = [$challengeTitles];
        }
        if (!is_array($challengeDescriptions)) {
            $challengeDescriptions = [$challengeDescriptions];
        }
        if (!is_array($challengeResolutions)) {
            $challengeResolutions = [$challengeResolutions];
        }

        $challenges = [];
        $maxCount = max(count($challengeTitles), count($challengeDescriptions), count($challengeResolutions));
        for ($i = 0; $i < $maxCount; $i++) {
            $title = trim($challengeTitles[$i] ?? '');
            $description = trim($challengeDescriptions[$i] ?? '');
            $resolution = trim($challengeResolutions[$i] ?? '');

            if ($title || $description || $resolution) {
                $challenges[] = [
                    'challenge_title' => $title,
                    'challenge' => $description,
                    'resolution' => $resolution,
                ];
            }
        }

        $item = Project::findOrFail($id);
        $item->update([
            'project_category_id' => $request->input('project_category_id') ?: null,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => $request->input('slug'),
            'client_name' => $request->input('client_name'),
            'project_duration' => $request->input('project_duration'),
            'project_location' => $request->input('project_location'),
            'regulatory_authority' => $request->input('regulatory_authority'),
            'client_website' => $request->input('client_website'),
            'project_scope' => $request->input('project_scope'),
            'featured' => $request->boolean('featured'),
            'service_ids' => $request->input('service_ids') ?: null,
            'challenge_heading' => $request->input('challenge_heading') ?: null,
            'challenge_title' => $challengeTitles[0] ?? null,
            'challenge' => $challengeDescriptions[0] ?? null,
            'resolution' => $challengeResolutions[0] ?? null,
            'challenges' => $challenges ?: null,
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            $oldImages = ProjectImage::where('project_id', $item->id)->get();
            foreach ($oldImages as $img) {
                if (File::exists(public_path($img->image)))
                    File::delete(public_path($img->image));
                $img->delete();
            }
            $newImages = is_array($request->file('image')) ? $request->file('image') : [$request->file('image')];
            foreach ($newImages as $image) {
                $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/project_images'), $image_name);
                ProjectImage::create(['project_id' => $item->id, 'image' => 'uploads/project_images/' . $image_name]);
            }
        }

        // Handle video update
        if ($request->hasFile('video')) {
            $oldVideos = ProjectVideo::where('project_id', $item->id)->get();
            foreach ($oldVideos as $vid) {
                if (File::exists(public_path($vid->video)))
                    File::delete(public_path($vid->video));
                if ($vid->thumbnail && File::exists(public_path($vid->thumbnail)))
                    File::delete(public_path($vid->thumbnail));
                $vid->delete();
            }
            $newVideos = is_array($request->file('video')) ? $request->file('video') : [$request->file('video')];
            $video_thumbnails = $request->file('video_thumbnail');
            foreach ($newVideos as $index => $video) {
                $video_name = time() . '_' . Str::uuid() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('uploads/project_videos'), $video_name);

                $thumb_path = null;
                if (isset($video_thumbnails[$index])) {
                    $thumb_name = time() . '_thumb_' . Str::uuid() . '.' . $video_thumbnails[$index]->getClientOriginalExtension();
                    $video_thumbnails[$index]->move(public_path('uploads/project_videos/thumbnails'), $thumb_name);
                    $thumb_path = 'uploads/project_videos/thumbnails/' . $thumb_name;
                }

                ProjectVideo::create([
                    'project_id' => $item->id,
                    'video' => 'uploads/project_videos/' . $video_name,
                    'thumbnail' => $thumb_path,
                    'title' => pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME)
                ]);
            }
        }

        // Handle document update
        if ($request->hasFile('document')) {
            $oldDocs = ProjectDocument::where('project_id', $item->id)->get();
            foreach ($oldDocs as $doc) {
                if (File::exists(public_path($doc->document)))
                    File::delete(public_path($doc->document));
                $doc->delete();
            }
            $newDocs = is_array($request->file('document')) ? $request->file('document') : [$request->file('document')];
            foreach ($newDocs as $document) {
                $doc_name = time() . '_' . Str::uuid() . '.' . $document->getClientOriginalExtension();
                $document->move(public_path('uploads/project_documents'), $doc_name);
                ProjectDocument::create(['project_id' => $item->id, 'document' => 'uploads/project_documents/' . $doc_name, 'title' => pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME)]);
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
        $item = Project::findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }
}