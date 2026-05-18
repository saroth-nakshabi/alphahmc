<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Http\Request;


class PermissionController extends Controller
{
    public function index()
    {
        $data = [];
        $data['permissions'] = Permission::all();
        $data['categories'] = PermissionCategory::all();
        return view('dashboard.roles_permissions.permissions.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category' => 'required|max:255',
        ]);

        $item = Permission::create([
            'name' => $request->input('name'),
            'permission_category_id' => $request->input('category'),
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

        $item = Permission::findOrFail($id);
        $item->update([
            'name' => $request->input('name'),
            'permission_category_id' => $request->input('category'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    public function destroy($id)
    {
        $item = Permission::findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }
    public function getCategory(Request $request)
    {
        $id = $request->input('id');
        $item = Permission::with('permissionCategory')->find($id);

        // Check if the item was found
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found!',
            ], 404);
        }

        // Get existing fields
        $attributes = $item->attributesToArray();

        // Add additional field
        $attributes['permission_category'] = $item->permissionCategory ? $item->permissionCategory->name : null;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $attributes,
        ], 200);
    }
    public function getPermission(Request $request)
    {
        return $this->getCategory($request);
    }
}
