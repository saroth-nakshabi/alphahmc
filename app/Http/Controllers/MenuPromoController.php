<?php

namespace App\Http\Controllers;

use App\Models\MenuPromo;
use Illuminate\Http\Request;

class MenuPromoController extends Controller
{
    /** Manage the mega-menu promo slides (max 3). */
    public function index()
    {
        $promos = MenuPromo::orderBy('sort_order')->orderBy('id')->get();
        $canAdd = $promos->count() < MenuPromo::MAX;
        return view('dashboard.menu_promos.index', compact('promos', 'canAdd'));
    }

    public function store(Request $request)
    {
        if (MenuPromo::count() >= MenuPromo::MAX) {
            return back()->with('error', 'You can have up to ' . MenuPromo::MAX . ' menu promos. Delete one first.');
        }

        $data = $this->validated($request);
        $data['sort_order'] = (int) (MenuPromo::max('sort_order') + 1);
        MenuPromo::create($data);

        return back()->with('success', 'Menu promo added.');
    }

    public function update(Request $request, $id)
    {
        $promo = MenuPromo::findOrFail($id);
        $promo->update($this->validated($request));

        return back()->with('success', 'Menu promo updated.');
    }

    public function destroy($id)
    {
        MenuPromo::findOrFail($id)->delete();
        return back()->with('success', 'Menu promo deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'eyebrow'   => 'nullable|string|max:255',
            'title'     => 'required|string|max:255',
            'text'      => 'nullable|string|max:500',
            'cta_label' => 'nullable|string|max:255',
            'cta_url'   => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
