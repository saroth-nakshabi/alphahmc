<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceMagazine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceMagazineController extends Controller
{
    /**
     * Show the form to create a new magazine item for a service.
     */
    public function create($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        return view('dashboard.services.magazines.create', compact('service'));
    }

    /**
     * Store a new magazine item.
     */
    public function store(Request $request, $serviceId)
    {
        $service = Service::findOrFail($serviceId);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/magazines'), $imageName);
        }

        $service->magazines()->create([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'image'       => $imageName,
        ]);

        return redirect()
            ->route('services.edit', $serviceId)
            ->with('success', 'Magazine item added successfully.');
    }

    /**
     * Show the form to edit an existing magazine item.
     */
    public function edit($serviceId, $magazineId)
    {
        $service  = Service::findOrFail($serviceId);
        $magazine = ServiceMagazine::where('service_id', $serviceId)->findOrFail($magazineId);
        return view('dashboard.services.magazines.edit', compact('service', 'magazine'));
    }

    /**
     * Update an existing magazine item.
     */
    public function update(Request $request, $serviceId, $magazineId)
    {
        $service  = Service::findOrFail($serviceId);
        $magazine = ServiceMagazine::where('service_id', $serviceId)->findOrFail($magazineId);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $imageName = $magazine->image; // keep existing by default
        if ($request->hasFile('image')) {
            // Delete old image file
            if ($imageName && file_exists(public_path('uploads/magazines/' . $imageName))) {
                unlink(public_path('uploads/magazines/' . $imageName));
            }
            $file = $request->file('image');
            $imageName = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/magazines'), $imageName);
        }

        $magazine->update([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'image'       => $imageName,
        ]);

        return redirect()
            ->route('services.edit', $serviceId)
            ->with('success', 'Magazine item updated successfully.');
    }

    /**
     * Delete a magazine item.
     */
    public function destroy($serviceId, $magazineId)
    {
        $magazine = ServiceMagazine::where('service_id', $serviceId)->findOrFail($magazineId);

        if ($magazine->image && file_exists(public_path('uploads/magazines/' . $magazine->image))) {
            unlink(public_path('uploads/magazines/' . $magazine->image));
        }
        $magazine->delete();

        return redirect()
            ->route('services.edit', $serviceId)
            ->with('success', 'Magazine item deleted successfully.');
    }
}
