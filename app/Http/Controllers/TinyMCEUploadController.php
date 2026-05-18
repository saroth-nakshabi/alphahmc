<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TinyMCEUploadController extends Controller
{
    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    //     ]);

    //     if ($request->hasFile('upload')) {
    //         $file = $request->file('upload');
    //         $filename = time() . '_' . $file->getClientOriginalName();

    //         // Store the file
    //         $path = $file->storeAs('uploads', $filename, 'public');

    //         // Generate the correct URL
    //         $url = asset('storage/' . $path);

    //         // Return the URL in the format TinyMCE expects
    //          return response()->json(['url' => $url]);
    //     }

    //     return response()->json(['error' => 'No file uploaded.'], 400);
    // }

}
