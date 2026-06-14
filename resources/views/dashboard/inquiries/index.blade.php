@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden" style="width: 1500px; left: -200px;">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Service Inquiries</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted " href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Inquiries</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="datatables" style="width: 1500px; margin-left: -200px;">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex">
                            <h5 class="mb-0">Recent Leads</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="items-table" class="table border table-striped table-bordered display text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inquiries as $inquiry)
                                        <tr>
                                            <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="fw-semibold mb-0">{{ $inquiry->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light-primary text-primary fw-semibold">
                                                    {{ $inquiry->service->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="d-block">{{ $inquiry->email }}</small>
                                                <small class="text-muted">{{ $inquiry->phone }}</small>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST">
                                                    @csrf
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="replied" {{ $inquiry->status == 'replied' ? 'selected' : '' }}>Replied</option>
                                                        <option value="closed" {{ $inquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-sm btn-info fw-semibold" style="border-radius: 6px; transition: all 0.3s ease; border: none; padding: 0.5rem 1rem; box-shadow: 0 2px 6px rgba(13, 110, 253, 0.15);" onmouseover="this.style.boxShadow='0 4px 12px rgba(13, 110, 253, 0.3)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 2px 6px rgba(13, 110, 253, 0.15)'; this.style.transform='translateY(0)';">
                                                        <i class="bi bi-eye-fill"></i> View
                                                    </a>
                                                    <button class="btn btn-sm btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#replyInquiry-{{ $inquiry->id }}" style="border-radius: 6px; transition: all 0.3s ease; border: none; padding: 0.5rem 1rem; box-shadow: 0 2px 6px rgba(13, 110, 253, 0.15); margin: 0 5px;" onmouseover="this.style.boxShadow='0 4px 12px rgba(13, 110, 253, 0.3)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 2px 6px rgba(13, 110, 253, 0.15)'; this.style.transform='translateY(0)';">
                                                        <i class="bi bi-reply-fill"></i> Reply
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger fw-semibold" onclick="confirmDelete({{ $inquiry->id }})" style="border-radius: 6px; transition: all 0.3s ease; border: none; padding: 0.5rem 1rem; box-shadow: 0 2px 6px rgba(220, 53, 69, 0.15);" onmouseover="this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.3)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 2px 6px rgba(220, 53, 69, 0.15)'; this.style.transform='translateY(0)';">
                                                        <i class="bi bi-trash-fill"></i> Delete
                                                    </button>
                                                    <form id="deleteForm-{{ $inquiry->id }}" action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>

                                                {{-- View Modal --}}
                                                <div class="modal fade" id="viewInquiry-{{ $inquiry->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); overflow: hidden; transition: all 0.3s ease;">
                                                            <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 16px 16px 0 0; padding: 2rem; border: none; box-shadow: 0 4px 20px rgba(30, 60, 114, 0.2);">
                                                                <h5 class="modal-title fw-bold text-white" style="font-size: 1.5rem; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px;">
                                                                    <span style="font-size: 1.8rem;">📋</span>
                                                                    <span>Inquiry Details</span>
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0.8);"></button>
                                                            </div>
                                                            <div class="modal-body text-wrap p-5" style="background: rgba(255,255,255,0.6); border-radius: 12px;">
                                                                <div class="mb-4">
                                                                    <p class="mb-3" style="font-weight: 700; color: #0d47a1; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                                                                        <span style="font-size: 1.3rem;">💬</span>
                                                                        <span>Message</span>
                                                                    </p>
                                                                    <div class="p-5 rounded-3 mb-4" style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%); border-left: 5px solid #1565c0; box-shadow: 0 4px 15px rgba(13, 71, 161, 0.08); transition: all 0.3s ease;">
                                                                        <p class="mb-0" style="color: #1a237e; font-size: 1.05rem; line-height: 1.8; word-break: break-word;">{{ $inquiry->message ?? 'No message provided.' }}</p>
                                                                    </div>
                                                                </div>
                                                                @if (!empty($inquiry->reply_history) && is_array($inquiry->reply_history))
                                                                    <div>
                                                                        <p class="mb-4" style="font-weight: 700; color: #0d47a1; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                                                                            <span style="font-size: 1.3rem;">📧</span>
                                                                            <span>Reply History</span>
                                                                            <span class="badge bg-info text-white" style="margin-left: auto; font-size: 0.85rem;">{{ count($inquiry->reply_history) }}</span>
                                                                        </p>
                                                                        <div class="p-4 rounded-3" style="background-color: #f8f9fa; border: 2px solid #e8eef7; border-radius: 12px;">
                                                                            @foreach ($inquiry->reply_history as $reply)
                                                                                <div class="mb-4 pb-4" style="border-bottom: 1px dashed #d0d0d0;">
                                                                                    <p class="mb-3"><small class="fw-semibold" style="font-size: 0.9rem; color: #5e5e5e; display: flex; align-items: center; gap: 6px;">
                                                                        <span style="font-size: 1.1rem;">🕐</span>
                                                                        <span>{{ \Carbon\Carbon::parse($reply['sent_at'] ?? now())->format('M d, Y · h:i A') }}</span>
                                                                    </small></p>
                                                                                    <div class="p-4 rounded-2" style="background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%); border-left: 5px solid #4caf50; box-shadow: 0 2px 8px rgba(76, 175, 80, 0.1); transition: all 0.3s ease;">
                                                                        <p class="mb-0" style="color: #1a1a1a; font-size: 0.95rem; line-height: 1.7; word-break: break-word;">{{ $reply['message'] ?? '' }}</p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                                {{-- Reply Modal --}}
                                                <div class="modal fade" id="replyInquiry-{{ $inquiry->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); overflow: hidden; transition: all 0.3s ease;">
                                                            <form action="{{ route('admin.inquiries.reply', $inquiry->id) }}" method="POST" id="replyForm-{{ $inquiry->id }}">
                                                                @csrf
                                                                <div class="modal-header" style="background: linear-gradient(135deg, #b71c1c 0%, #c62828 100%); border-radius: 16px 16px 0 0; padding: 2rem; border: none; box-shadow: 0 4px 20px rgba(183, 28, 28, 0.2);">
                                                                    <h5 class="modal-title fw-bold text-white" style="font-size: 1.5rem; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px;">
                                                                        <span style="font-size: 1.8rem;">📝</span>
                                                                        <span>Reply to {{ $inquiry->name }}</span>
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0.8);"></button>
                                                                </div>
                                                                <div class="modal-body p-5" style="background: rgba(255,255,255,0.6); border-radius: 12px;">
                                                                    <div class="mb-5">
                                                                        <label class="form-label fw-bold mb-3" style="color: #d32f2f; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                                                                            <span style="font-size: 1.3rem;">✍️</span>
                                                                            <span>Reply Message <span class="text-danger">*</span></span>
                                                                        </label>
                                                                        <textarea name="reply_message" class="form-control" rows="7" required placeholder="Type your reply here..." style="border: 2px solid #e8eef7; border-radius: 12px; padding: 1.25rem; font-size: 0.95rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s ease; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" onfocus="this.style.borderColor='#d32f2f'; this.style.boxShadow='0 4px 12px rgba(211, 47, 47, 0.1)';" onblur="this.style.borderColor='#e8eef7'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)';">{{ old('reply_message') }}</textarea>
                                                                    </div>
                                                                    <div class="p-5 rounded-3" style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border-left: 5px solid #ff9800; border-radius: 12px; box-shadow: 0 3px 10px rgba(255, 152, 0, 0.1);">
                                                                        <p class="mb-3" style="color: #e65100; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; margin: 0;">
                                                                            <span style="font-size: 1.2rem;">📬</span>
                                                                            <span><strong>To:</strong> <span style="color: #bf360c; font-weight: 700;">{{ $inquiry->email }}</span></span>
                                                                        </p>
                                                                        <p class="mb-0" style="color: #e65100; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; margin: 0;">
                                                                            <span style="font-size: 1.2rem;">🔧</span>
                                                                            <span><strong>Service:</strong> <span style="color: #bf360c; font-weight: 700;">{{ $inquiry->service->name ?? 'N/A' }}</span></span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e8eef7; padding: 1.5rem; border-radius: 0 0 16px 16px; gap: 10px;">
                                                                    <button type="button" class="btn fw-semibold" data-bs-dismiss="modal" style="background-color: #757575; border: none; padding: 0.75rem 1.75rem; color: white; border-radius: 8px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(117, 117, 117, 0.2);" onmouseover="this.style.backgroundColor='#616161'; this.style.boxShadow='0 4px 12px rgba(117, 117, 117, 0.3)';" onmouseout="this.style.backgroundColor='#757575'; this.style.boxShadow='0 2px 8px rgba(117, 117, 117, 0.2)';">Cancel</button>
                                                                    <button type="submit" class="btn fw-semibold" style="background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%); border: none; padding: 0.75rem 1.75rem; color: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3); transition: all 0.3s ease; font-size: 1rem;" onmouseover="this.style.boxShadow='0 6px 16px rgba(76, 175, 80, 0.4); transform: translateY(-2px);';" onmouseout="this.style.boxShadow='0 4px 12px rgba(76, 175, 80, 0.3); transform: translateY(0);';">✓ Send Reply</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $inquiries->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Custom styles for the inquiries page */
        .table-responsive {
            border: 1px solid #e8eef7;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .table thead th {
            background-color: #f5f7fa;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #e8eef7;
        }

        .table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .customizer-btn{
            
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
            background-color: #0d47a1;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.3);
            transition: all 0.3s ease;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 Delete Confirmation
        function confirmDelete(inquiryId) {
            Swal.fire({
                title: 'Delete Inquiry',
                html: '<p style="font-size: 1rem; color: #555;">Are you sure you want to delete this inquiry? <br><span style="color: #d32f2f; font-weight: 600;">This action cannot be undone.</span></p>',
                icon: 'warning',
                iconColor: '#d32f2f',
                showCancelButton: true,
                confirmButtonColor: '#d32f2f',
                cancelButtonColor: '#757575',
                confirmButtonText: '✓ Yes, Delete',
                cancelButtonText: 'Cancel',
                buttonsStyling: true,
                allowOutsideClick: false,
                didOpen: (modal) => {
                    modal.style.borderRadius = '16px';
                    const confirmBtn = modal.querySelector('.swal2-confirm');
                    const cancelBtn = modal.querySelector('.swal2-cancel');
                    confirmBtn.style.borderRadius = '8px';
                    cancelBtn.style.borderRadius = '8px';
                    confirmBtn.style.padding = '0.75rem 1.5rem';
                    cancelBtn.style.padding = '0.75rem 1.5rem';
                    confirmBtn.style.fontSize = '0.95rem';
                    cancelBtn.style.fontSize = '0.95rem';
                    confirmBtn.style.fontWeight = '600';
                    cancelBtn.style.fontWeight = '600';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Deleting...',
                        html: '<div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div>',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: (modal) => {
                            modal.style.borderRadius = '16px';
                        }
                    });
                    
                    document.getElementById('deleteForm-' + inquiryId).submit();
                }
            });
        }

        // Form submission success message (if needed from server)
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                iconColor: '#4caf50',
                confirmButtonColor: '#4caf50',
                timer: 3000,
                timerProgressBar: true,
                didOpen: (modal) => {
                    modal.style.borderRadius = '16px';
                    const confirmBtn = modal.querySelector('.swal2-confirm');
                    confirmBtn.style.borderRadius = '8px';
                    confirmBtn.style.padding = '0.75rem 1.5rem';
                    confirmBtn.style.fontSize = '0.95rem';
                    confirmBtn.style.fontWeight = '600';
                }
            });
        @endif

        // Form submission error message (if needed from server)
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                iconColor: '#d32f2f',
                confirmButtonColor: '#d32f2f',
                didOpen: (modal) => {
                    modal.style.borderRadius = '16px';
                    const confirmBtn = modal.querySelector('.swal2-confirm');
                    confirmBtn.style.borderRadius = '8px';
                    confirmBtn.style.padding = '0.75rem 1.5rem';
                    confirmBtn.style.fontSize = '0.95rem';
                    confirmBtn.style.fontWeight = '600';
                }
            });
        @endif

        // Modal animation on show
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                this.style.animation = 'fadeInDown 0.3s ease';
            });
        });

        // Add fade in animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .modal-content {
                animation: fadeInDown 0.3s ease;
            }

            textarea.form-control:focus {
                border-color: #d32f2f !important;
                box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25) !important;
            }

            .btn {
                transition: all 0.3s ease !important;
                position: relative;
            }

            .btn:active {
                transform: scale(0.95) !important;
            }

            @media (max-width: 576px) {
                .modal-lg {
                    max-width: 95vw !important;
                }

                .modal-body {
                    padding: 1.5rem !important;
                }

                .modal-header {
                    padding: 1.25rem !important;
                }

                .modal-title {
                    font-size: 1.2rem !important;
                }

                .btn-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 5px;
                }

                .btn-group .btn {
                    flex: 1;
                    min-width: 70px;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection