@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <h4 class="fw-semibold mb-1"><i class="ti ti-building-hospital me-2"></i>Facility types</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Facility types</li>
            </ol>
        </nav>
    </div>
</div>

@foreach(['success' => 'success', 'error' => 'danger'] as $key => $class)
    @if(session($key))
        <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">{{ session($key) }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
    @endif
@endforeach
@if($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <p class="text-muted small mb-0">
            Cards in "Set up any type of healthcare facility" on
            <a href="{{ route('front.service-category', \App\Models\FacilityTypeCard::HOST_CATEGORY) }}" target="_blank" rel="noopener">the facility licensing page</a>.
            A card links to a category, a published service or a published package only (search engines must be able to index it), so the ads landing pages are never offered.
            A card hides by itself when its page is unpublished or deleted.
        </p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th style="width:90px">Order</th><th>Card</th><th>Links to</th><th style="width:110px">Shown</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($cards as $card)
                    @php $target = $targets[$card->target] ?? null; @endphp
                    <tr>
                        <td class="text-nowrap">
                            <form class="d-inline" method="POST" action="{{ route('admin.facility-types.move', [$card->id, 'up']) }}">@csrf<button class="btn btn-sm btn-light" aria-label="Move {{ $card->name }} up" @disabled($loop->first)><i class="ti ti-arrow-up"></i></button></form>
                            <form class="d-inline" method="POST" action="{{ route('admin.facility-types.move', [$card->id, 'down']) }}">@csrf<button class="btn btn-sm btn-light" aria-label="Move {{ $card->name }} down" @disabled($loop->last)><i class="ti ti-arrow-down"></i></button></form>
                        </td>
                        <td colspan="3">
                            <form method="POST" action="{{ route('admin.facility-types.update', $card->id) }}" class="row g-2 align-items-center">
                                @csrf
                                <div class="col-lg-3"><label class="visually-hidden" for="ftn{{ $card->id }}">Name</label><input class="form-control form-control-sm" id="ftn{{ $card->id }}" name="name" value="{{ $card->name }}" required maxlength="255"></div>
                                <div class="col-lg-4"><label class="visually-hidden" for="ftl{{ $card->id }}">One line</label><input class="form-control form-control-sm" id="ftl{{ $card->id }}" name="line" value="{{ $card->line }}" maxlength="255"></div>
                                <div class="col-lg-3">
                                    <label class="visually-hidden" for="ftt{{ $card->id }}">Links to</label>
                                    <select class="form-select form-select-sm" id="ftt{{ $card->id }}" name="target" required>
                                        @unless($target)<option value="" selected>Page unpublished or deleted: choose another</option>@endunless
                                        @foreach($targets as $key => $t)<option value="{{ $key }}" @selected($key === $card->target)>{{ $t['label'] }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1"><div class="form-check form-switch m-0"><input class="form-check-input" type="checkbox" role="switch" name="active" value="1" id="fta{{ $card->id }}" @checked($card->active)><label class="visually-hidden" for="fta{{ $card->id }}">Shown</label></div></div>
                                <div class="col-lg-1"><button class="btn btn-sm btn-info">Save</button></div>
                            </form>
                            @unless($target)<small class="text-danger">Hidden on the site: its page is unpublished or deleted.</small>@endunless
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.facility-types.destroy', $card->id) }}" onsubmit="return confirm('Delete the {{ addslashes($card->name) }} card?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" aria-label="Delete {{ $card->name }}"><i class="ti ti-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No cards yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Add a card</h6>
        <form method="POST" action="{{ route('admin.facility-types.store') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-lg-3"><label class="form-label small fw-semibold" for="ftNewName">Name <span class="text-danger">*</span></label><input class="form-control form-control-sm" id="ftNewName" name="name" required maxlength="255" placeholder="Dental centres"></div>
            <div class="col-lg-4"><label class="form-label small fw-semibold" for="ftNewLine">One line</label><input class="form-control form-control-sm" id="ftNewLine" name="line" maxlength="255"></div>
            <div class="col-lg-3"><label class="form-label small fw-semibold" for="ftNewTarget">Links to <span class="text-danger">*</span></label>
                <select class="form-select form-select-sm" id="ftNewTarget" name="target" required>
                    <option value="">Choose a page</option>
                    @foreach($targets as $key => $t)<option value="{{ $key }}">{{ $t['label'] }}</option>@endforeach
                </select>
            </div>
            <div class="col-lg-1"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="active" value="1" id="ftNewActive" checked><label class="form-check-label small" for="ftNewActive">Shown</label></div></div>
            <div class="col-lg-1"><button class="btn btn-sm btn-success">Add</button></div>
        </form>
    </div>
</div>
@endsection
