<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CkEditorUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the image upload
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // Generate a safe filename
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $file->getClientOriginalName());

            // Store the file in the public disk under /storage/uploads/
            $path = $file->storeAs('ckeditor', $filename, 'public');

            // Log the upload (optional)
            Log::info('CKEditor image uploaded: ' . $path);

            // Generate the public URL
            $url = Storage::disk('public')->url($path);

            // CKEditor 5 expects this response format
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }

        // If no file was uploaded
        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'No file uploaded or upload failed'
            ]
        ], 400);
    }
}
