<?php

namespace App\Http\Controllers;

use App\Models\PermissionCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $data = [];
        $data['users'] = User::all();

        return view('dashboard.user_management.all_users.index', $data);
    }

    /**
     * Per-user access manager: shows the user's roles plus every permission
     * grouped by category, distinguishing DIRECT grants (toggleable here) from
     * those inherited VIA a role (shown with a badge).
     */
    public function permissions($id)
    {
        $user = User::findOrFail($id);

        return view('dashboard.user_management.all_users.permissions', [
            'user'               => $user,
            'roles'              => Role::orderBy('name')->get(),
            'categories'         => PermissionCategory::with('permissions')->get(),
            'userRoles'          => $user->getRoleNames()->toArray(),
            'directPermissions'  => $user->getDirectPermissions()->pluck('name')->toArray(),
            'viaRolePermissions' => $user->getPermissionsViaRoles()->pluck('name')->toArray(),
        ]);
    }

    /**
     * Sync a user's roles and DIRECT permissions. Spatie's hasPermissionTo()
     * (and the permission: middleware) then honours both role + direct grants.
     */
    public function updatePermissions(Request $request, $id)
    {
        $request->validate([
            'roles'         => 'array',
            'roles.*'       => 'exists:roles,name',
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user = User::findOrFail($id);

        $user->syncRoles($request->input('roles', []));
        $user->syncPermissions($request->input('permissions', []));

        return response()->json([
            'success' => true,
            'message' => 'User access updated successfully',
        ]);
    }
}
