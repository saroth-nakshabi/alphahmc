<?php

namespace App\Http\Controllers;

use App\Models\client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class clientsController extends Controller
{
    // Display all clients
    public function index()
    {
        $clients = client::orderBy('sort_order')->orderBy('id')->get();
        return view('dashboard.Clients.index', compact('clients'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:clients,id',
        ]);

        foreach ($request->order as $position => $id) {
            client::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Order saved!']);
    }

    public function toggleFeatured($id)
    {
        $client = client::findOrFail($id);
        $client->is_featured = !$client->is_featured;
        $client->save();

        return response()->json([
            'success'  => true,
            'featured' => (bool) $client->is_featured,
            'message'  => $client->is_featured
                ? $client->name . ' is now featured (shown in the home page carousel).'
                : $client->name . ' removed from featured.',
        ]);
    }

    public function toggleStatus($id)
    {
        $client = client::findOrFail($id);
        $client->status = $client->status ? 0 : 1;
        $client->save();

        return response()->json([
            'success' => true,
            'status'  => (bool) $client->status,
            'message' => $client->status
                ? $client->name . ' is now visible on the website.'
                : $client->name . ' is now hidden from the website.',
        ]);
    }

    // Store a new client
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'logo'              => 'required|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'short_description' => 'required|string|max:500',
            'description'       => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'short_description', 'description']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = (int) client::max('sort_order') + 1;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients'), $filename);
            $data['logo'] = $filename;
        }

        $client = client::create($data);

        return response()->json([
            'message' => 'Client added successfully',
            'data'    => $client
        ]);
    }

    // Get a single client for editing
    public function getClient(Request $request)
    {
        $client = client::findOrFail($request->id);

        return response()->json([
            'data' => $client
        ]);
    }

    // Update a client
    public function update(Request $request, $id)
    {
        $client = client::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'logo'              => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'short_description' => 'required|string|max:500',
            'description'       => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $client->name              = $request->name;
        $client->short_description = $request->short_description;
        $client->description       = $request->description;
        $client->is_featured       = $request->has('is_featured') ? 1 : 0;
        $client->status            = $request->has('status') ? 1 : 0;

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($client->logo && file_exists(public_path('uploads/clients/' . $client->logo))) {
                unlink(public_path('uploads/clients/' . $client->logo));
            }

            $file     = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients'), $filename);
            $client->logo = $filename;
        }

        $client->save();

        return response()->json([
            'message' => 'Client updated successfully',
            'data'    => $client
        ]);
    }

    // Delete a client
    public function destroy($id)
    {
        $client = client::findOrFail($id);

        // Delete logo file if exists
        if ($client->logo && file_exists(public_path('uploads/clients/' . $client->logo))) {
            unlink(public_path('uploads/clients/' . $client->logo));
        }

        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully'
        ]);
    }
}