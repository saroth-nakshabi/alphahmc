<?php

namespace App\Http\Controllers;

use App\Models\FacilityTypeCard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Dashboard > Facility types: the cards on the facility licensing category page (SEO brief, 30 Oct 2026). */
class AdminFacilityTypeController extends Controller
{
    public function index()
    {
        return view('dashboard.facility_types.index', [
            'cards'   => FacilityTypeCard::orderBy('sort_order')->orderBy('id')->get(),
            'targets' => FacilityTypeCard::targets(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['sort_order'] = (int) FacilityTypeCard::max('sort_order') + 1;
        FacilityTypeCard::create($data);

        return back()->with('success', 'Facility type card added.');
    }

    public function update(Request $request, $id)
    {
        FacilityTypeCard::findOrFail($id)->update($this->validated($request));

        return back()->with('success', 'Facility type card saved.');
    }

    public function move($id, string $direction)
    {
        $cards = FacilityTypeCard::orderBy('sort_order')->orderBy('id')->get()->values();
        $i = $cards->search(fn ($c) => $c->id === (int) $id);
        $j = $direction === 'up' ? $i - 1 : $i + 1;
        if ($i !== false && isset($cards[$j])) {
            $tmp = $cards[$i]; $cards[$i] = $cards[$j]; $cards[$j] = $tmp;
            foreach ($cards as $n => $card) {
                $card->update(['sort_order' => $n + 1]);
            }
        }

        return back();
    }

    public function destroy($id)
    {
        FacilityTypeCard::findOrFail($id)->delete();

        return back()->with('success', 'Facility type card deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'line'   => 'nullable|string|max:255',
            // Only indexable pages: categories and published services or packages, never a /facility-setup/ page.
            'target' => ['required', Rule::in(array_keys(FacilityTypeCard::targets()))],
        ], ['target.in' => 'Choose a published service, package or category page.']);
        $data['active'] = $request->boolean('active');

        return $data;
    }
}
