<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Add this line

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Check if the request has a file and validate it
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Validate that the file is an image
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // // Store the file and get the path
            // $path = $file->store('public/uploads'); // You can change the storage path as needed

            // Get original file name
            $fileName = $file->getClientOriginalName();

            // Store the file and get the path in the 'public/uploads/service_images' directory
            $path = $file->storeAs('uploads/service_images', $fileName, 'public');

            // Return the image URL (adjust based on your file system)
            return response()->json([
                'location' => Storage::url($path) // Return the public URL of the uploaded image
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
