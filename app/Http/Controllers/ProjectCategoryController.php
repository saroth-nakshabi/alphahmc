<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectCategory;
use App\Models\Project;

class ProjectCategoryController extends Controller
{
    public function index()
    {

        $projectCategories=ProjectCategory::all();
        return view('dashboard.projects.project_category',compact('projectCategories'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
        ]);


        $item = ProjectCategory::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $item = ProjectCategory::findOrFail($id);
        $item->update([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    public function destroy($id)
    {
        $item = ProjectCategory::findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getCategory(Request $request)
    {
        $id = $request->input('id');
        $item = ProjectCategory::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }
}