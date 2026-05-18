@extends('dashboard.layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Announcements</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Announcements</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-end">
                    <a href="{{ route('announcements.create') }}" class="btn btn-primary">Add Announcement</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="announcementsTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>feature</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($announcements as $announcement)
                            <tr>
                                <td>{{ $announcement->title }}</td>
                                <td>
                                    <span class="badge bg-{{ $announcement->status ? 'success' : 'danger' }}">
                                        {{ $announcement->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $announcement -> feature ? 'success' : 'danger'}}">
                                        {{ $announcement -> feature ? 'Featured' : 'Non-Featured'}}
                                    </span>
                                </td>
                                <td>{{ $announcement->created_at->format('Y-m-d') }}</td>
                                
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-6"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="{{ route('announcements.edit', $announcement->id) }}">
                                                    <i class="fs-4 ti ti-edit"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('announcements.destroy', $announcement->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item d-flex align-items-center gap-3 text-danger">
                                                        <i class="fs-4 ti ti-trash"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#announcementsTable').DataTable();
        });
    </script>
@endpush