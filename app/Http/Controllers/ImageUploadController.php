<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\ImageProcessor;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file     = $request->file('file');
        $baseName = time() . '_' . Str::uuid();
        $dir      = public_path('uploads/service_images');

        $fileName = ImageProcessor::saveAsJpeg($file, $dir, $baseName);

        return response()->json([
            'location' => asset('uploads/service_images/' . $fileName),
        ]);
    }
}
