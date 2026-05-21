<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\ImageProcessor;

class CkEditorUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if (!$request->hasFile('upload')) {
            return response()->json([
                'uploaded' => 0,
                'error'    => ['message' => 'No file uploaded or upload failed'],
            ], 400);
        }

        $file     = $request->file('upload');
        $baseName = time() . '_' . Str::uuid();
        $dir      = public_path('storage/ckeditor');

        $fileName = ImageProcessor::saveAsJpeg($file, $dir, $baseName);

        return response()->json([
            'uploaded' => 1,
            'fileName' => $fileName,
            'url'      => asset('storage/ckeditor/' . $fileName),
        ]);
    }
}
