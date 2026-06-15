@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <h4 class="fw-semibold mb-1"><i class="ti ti-route me-2"></i>New Project Process</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.project-process.index') }}">Project Process Manager</a></li>
                    <li class="breadcrumb-item active">New</li>
                </ol>
            </nav>
        </div>
    </div>

    @include('dashboard.project_process._form')
@endsection

@section('custom_js')
    @include('dashboard.project_process._scripts')
@endsection
