<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function index()
    {
        $data = [];
        $data['admins'] = Admin::all();

        return view('dashboard.user_management.admins.index', $data);
    }

    public function store(Request $request)
    {
        // Handle validation
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        // Insert data into users table
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        // Insert data into the admins table
        $admin = Admin::create([
            'user_id' => $user->id,
        ]);

        // Create or find the Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        // Assign the role to the user
        $user->assignRole($adminRole);

        $data = Admin::with('user')->find($admin->id);
        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:' . User::class . ',phone,' . $admin->user->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $admin->user->id],
        ]);

        $admin->user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $data = Admin::with('user')->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $data,
        ], 201);
    }

    public function destroy($id)
    {
        $item = Admin::findOrFail($id);

        $item->user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getAdmin(Request $request)
    {
        $id = $request->input('id');
        $item = Admin::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'data' => $item,
        ], 201);
    }
}









