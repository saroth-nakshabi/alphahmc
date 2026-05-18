<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    public function index()
    {
        $data = [];
        $data['agents'] = Agent::all();

        return view('dashboard.user_management.agents.index', $data);
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Insert data into users table
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        // Handle image uploads
        $image = $request->file('image');
        if (isset($image)) {
            // $filePath = $image->store('product_images', 'public/uploads'); // Save to the 'public/uploads/product_images' directory
            $image_name = time() . '_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/agent_images'), $image_name);
        }

        // Insert data into the agents table
        $agent = Agent::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'short_description' => $request->description,
            'image' => $image_name ?? null,
        ]);

        // Create or find the agent role
        $agentRole = Role::firstOrCreate(['name' => 'Agent']);
        // Assign the role to the user
        $user->assignRole($agentRole);

        $data = Agent::with('user')->find($agent->id);
        return response()->json([
            'success' => true,
            'message' => 'Created successfully!',
            'data' => $data,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:' . User::class . ',phone,' . $agent->user->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $agent->user->id],
        ]);

        $agent->update([
            'title' => $request->title,
            'short_description' => $request->description,
        ]);

        $agent->user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $data = Agent::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $data,
        ], 201);
    }

    public function destroy($id)
    {
        $item = Agent::findOrFail($id);

        $item->user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!',
        ], 201);
    }

    public function getAgent(Request $request)
    {
        $id = $request->input('id');
        $item = Agent::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'data' => $item,
        ], 201);
    }
}