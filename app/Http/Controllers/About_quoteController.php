<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\about_quote;
use Illuminate\Support\Str;

class About_quoteController extends Controller
{
    // ✅ INDEX
    public function index()
    {
        $data = [];
        $data['about_quotes'] = about_quote::latest()->get();

        return view('dashboard.About_us.about_quote', $data);
    }

    // ✅ STORE
    public function store(Request $request)
    {
        $request->validate([
            'quote_title' => 'nullable|max:255',
            // 'quote_sub_title' => 'nullable|max:255',
            'quote' => 'nullable|string',
            'company_name' => 'nullable|max:255',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = about_quote::create([
            'quote_title' => $request->quote_title,
            // 'subtitle' => $request->quote_sub_title,
            'About_quote' => $request->quote,
            'company_name' => $request->company_name,
            // 'image' => $this->uploadFile($request, 'image', 'about_quote'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $item,
        ], 201);
    }

    // ✅ GET (FOR EDIT)
    public function get(Request $request)
    {
        $item = about_quote::find($request->id);

        if (!$item) {
            return response()->json([
                'data' =>$item,
                'success' => false,
                'message' => 'Not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'quote_title' => 'nullable|max:255',
            // 'quote_sub_title' => 'nullable|max:255',
            'quote' => 'nullable|string',
            'company_name' => 'nullable|max:255',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = about_quote::findOrFail($id);

        $item->update([
            'quote_title' => $request->quote_title,
            // 'subtitle' => $request->quote_sub_title,
            'About_quote' => $request->quote,
            'company_name' => $request->company_name,
        ]);

        // Handle Image
        if ($request->hasFile('image')) {
            $this->deleteFile($item->image, 'about_quote');

            $item->update([
                'image' => $this->uploadFile($request, 'image', 'about_quote')
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ]);
    }

    // ✅ DELETE
    public function destroy($id)
    {
        $item = about_quote::findOrFail($id);

        $this->deleteFile($item->image, 'about_quote');

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ]);
    }

    // ✅ COMMON FILE UPLOAD METHOD
    private function uploadFile(Request $request, $fieldName, $folder)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $fileName = time() . '_' . $fieldName . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("uploads/$folder"), $fileName);
            return $fileName;
        }
        return null;
    }

    // ✅ COMMON DELETE METHOD
    private function deleteFile($fileName, $folder)
    {
        if ($fileName && file_exists(public_path("uploads/$folder/" . $fileName))) {
            unlink(public_path("uploads/$folder/" . $fileName));
        }
    }
}
