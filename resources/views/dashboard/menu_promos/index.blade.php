@extends('dashboard/layout')

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <h4 class="fw-semibold mb-1"><i class="ti ti-layout-navbar-expand me-2"></i>Menu Promos</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Menu Promos</li>
            </ol>
        </nav>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-1"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <p class="text-muted small mb-0">
            These promo slides appear in the <strong>main menu's default panel</strong> (shown when the menu opens).
            They <strong>auto-rotate</strong> — no manual controls. You can have up to <strong>{{ \App\Models\MenuPromo::MAX }}</strong>.
            Order is controlled by the <em>Order</em> value. Inactive promos are hidden.
        </p>
    </div>
</div>

<div class="row">
    {{-- Existing promos --}}
    @foreach($promos as $promo)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="ti ti-ad-2 me-1 text-info"></i> Promo #{{ $loop->iteration }}
                            @if($promo->is_active)
                                <span class="badge bg-light-success text-success ms-1">Active</span>
                            @else
                                <span class="badge bg-light-secondary text-muted ms-1">Hidden</span>
                            @endif
                        </h6>
                        <form action="{{ route('admin.menu-promos.destroy', $promo->id) }}" method="POST"
                              onsubmit="return confirm('Delete this promo?');" class="m-0">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
                        </form>
                    </div>

                    <form action="{{ route('admin.menu-promos.update', $promo->id) }}" method="POST">
                        @csrf
                        @include('dashboard.menu_promos._fields', ['promo' => $promo])
                        <button class="btn btn-info btn-sm"><i class="ti ti-device-floppy me-1"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Add new --}}
    @if($canAdd)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100 border-dashed">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3"><i class="ti ti-plus me-1 text-success"></i> Add Promo</h6>
                    <form action="{{ route('admin.menu-promos.store') }}" method="POST">
                        @csrf
                        @include('dashboard.menu_promos._fields', ['promo' => null])
                        <button class="btn btn-success btn-sm"><i class="ti ti-plus me-1"></i> Add Promo</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="col-lg-6 mb-4">
            <div class="card shadow-none bg-light border-0 h-100 d-flex align-items-center justify-content-center">
                <div class="card-body text-center text-muted">
                    <i class="ti ti-circle-check fs-7 d-block mb-2"></i>
                    Maximum of {{ \App\Models\MenuPromo::MAX }} promos reached. Delete one to add another.
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
