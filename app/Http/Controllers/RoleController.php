<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['roles'] = Role::all();

        return view('dashboard.roles_permissions.roles.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $role = Role::create(['name' => $request->input('name')]);

        return response()->json([
            'success' => true,
            'message' => 'Role Created Successfully',
            'data' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $item = Role::findOrFail($id);
        $item->update([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Role::findOrFail($id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getRole(Request $request)
    {
        $id = $request->input('id');
        $item = Role::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ], 201);
    }

    public function rolePermissions($roleId)
    {
        $data = [];
        $data['role'] = Role::findOrFail($roleId);
        $data['categories'] = PermissionCategory::with('permissions')->get();

        return view('dashboard.roles_permissions.role_permissions', $data);
    }

    // In the updatePermissions method
    public function updateRolePermissions(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::findOrFail($roleId);
        $permissions = $request->input('permissions', []);

        $role->syncPermissions($permissions);

        return response()->json(['success' => true, 'message' => 'Permissions updated successfully']);
    }
}
