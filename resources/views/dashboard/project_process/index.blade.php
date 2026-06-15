@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="fw-semibold mb-1"><i class="ti ti-route me-2"></i>Project Process Manager</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Project Process Manager</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.project-process.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> New Process
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3 gap-2 flex-wrap">
                <h5 class="mb-0 fw-semibold">All Project Processes</h5>
                <span class="badge bg-light-primary text-primary ms-2">{{ $processes->total() }} total</span>
                <p class="text-muted small mb-0 ms-auto">Create a process once, then assign it to many categories or service groups — they all share the same steps.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-hover border align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Process</th>
                            <th style="width:90px" class="text-center">Steps</th>
                            <th style="width:140px" class="text-center">Categories</th>
                            <th style="width:150px" class="text-center">Service Groups</th>
                            <th style="width:120px">Updated</th>
                            <th style="width:120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($processes as $p)
                            <tr>
                                <td class="fw-semibold">{{ $p->name }}</td>
                                <td class="text-center">{{ $p->step_count }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $p->categories_count ? 'bg-light-success text-success' : 'bg-light-secondary text-muted' }}">{{ $p->categories_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $p->service_groups_count ? 'bg-light-success text-success' : 'bg-light-secondary text-muted' }}">{{ $p->service_groups_count }}</span>
                                </td>
                                <td class="text-muted small">{{ $p->updated_at?->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.project-process.edit', $p->id) }}" class="btn btn-sm btn-info"><i class="bi bi-pencil-fill"></i> Edit</a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="ppDelete({{ $p->id }})"><i class="bi bi-trash-fill"></i></button>
                                        <form id="ppDel-{{ $p->id }}" action="{{ route('admin.project-process.destroy', $p->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No processes yet. Click <strong>New Process</strong> to create your first reusable project process.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $processes->links() }}</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function ppDelete(id) {
            Swal.fire({ title: 'Delete this process?', text: 'Linked categories / service groups keep their last content.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Yes, delete' })
                .then(r => { if (r.isConfirmed) document.getElementById('ppDel-' + id).submit(); });
        }
        @if(session('success'))
            Swal.fire({ title: 'Done', text: @json(session('success')), icon: 'success', timer: 2800, timerProgressBar: true });
        @endif
    </script>
@endsection
