@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">{{ $pageTitle }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">SEO Overview</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('public/dashboard/dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $missing = fn ($v) => trim((string) $v) === '';
        $totalMissingTitle = $items->filter(fn ($r) => $missing($r['meta_title']))->count();
    @endphp

    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">{{ $items->count() }} records</h5>
                            @if ($totalMissingTitle > 0)
                                <span class="badge bg-danger">{{ $totalMissingTitle }} missing meta title</span>
                            @else
                                <span class="badge bg-success">All have a meta title</span>
                            @endif
                            <small class="text-muted ms-auto">
                                @if ($canEdit) Click <strong>Edit</strong> on a row to change its SEO fields in a popup — saves instantly. @else Read-only (you lack edit permission). @endif
                            </small>
                        </div>

                        <style>
                            #seo-table td, #seo-table th { white-space: normal; vertical-align: top; word-break: break-word; }
                            #seo-table td.col-name { min-width: 150px; font-weight: 600; }
                            #seo-table .col-mdesc { min-width: 280px; }
                            #seo-table td.col-action, #seo-table th.col-action { white-space: nowrap; width: 1%; text-align: center; }
                            #seo-table .muted-empty { color: #b0b3c0; font-style: italic; }
                        </style>

                        <div class="table-responsive">
                            <table id="seo-table" class="table border table-striped table-bordered display" style="width:100%;"
                                   data-base="{{ url('dashboard/seo/' . $type) }}">
                                <thead>
                                    <tr>
                                        <th class="col-name">Name</th>
                                        <th>Meta Title</th>
                                        <th class="col-mdesc">Meta Description</th>
                                        @if ($showArea)<th>Service Area</th>@endif
                                        <th>Meta Keywords</th>
                                        <th class="col-action">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $row)
                                        <tr data-id="{{ $row['id'] }}">
                                            <td class="col-name">{{ $row['name'] }}</td>
                                            <td class="cell-meta_title">
                                                @if ($missing($row['meta_title']))<span class="badge bg-danger">missing</span>@else{{ $row['meta_title'] }}@endif
                                            </td>
                                            <td class="col-mdesc cell-meta_description">
                                                @if ($missing($row['meta_description']))<span class="badge bg-danger">missing</span>@else{{ $row['meta_description'] }}@endif
                                            </td>
                                            @if ($showArea)
                                                <td class="cell-areaServed">
                                                    @if ($missing($row['area']))<span class="muted-empty">—</span>@else{{ $row['area'] }}@endif
                                                </td>
                                            @endif
                                            <td class="cell-meta_keywords">
                                                @if ($missing($row['meta_keywords']))<span class="badge bg-warning text-dark">missing</span>@else{{ $row['meta_keywords'] }}@endif
                                            </td>
                                            <td class="col-action">
                                                @if ($canEdit)
                                                    <button type="button" class="btn btn-sm btn-primary seo-edit mb-1"
                                                        data-id="{{ $row['id'] }}"
                                                        data-name="{{ $row['name'] }}"
                                                        data-meta_title="{{ $row['meta_title'] }}"
                                                        data-meta_description="{{ $row['meta_description'] }}"
                                                        data-meta_keywords="{{ $row['meta_keywords'] }}"
                                                        data-area="{{ $row['area'] }}">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </button><br>
                                                @endif
                                                <a href="{{ $row['edit_url'] }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Open full editor">
                                                    <i class="bi bi-box-arrow-up-right"></i> Open
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Shared edit modal --}}
    <div class="modal fade" id="seoEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit SEO — <span id="seo-modal-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="seo-row-id">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" id="seo-meta_title" class="form-control" maxlength="255">
                        <small class="text-muted">Recommended ≤ 60 characters.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea id="seo-meta_description" class="form-control" rows="3"></textarea>
                        <small class="text-muted">Recommended ≤ 160 characters.</small>
                    </div>
                    @if ($showArea)
                        <div class="mb-3">
                            <label class="form-label">Service Area</label>
                            <input type="text" id="seo-areaServed" class="form-control" maxlength="255" placeholder="e.g. United Arab Emirates">
                        </div>
                    @endif
                    <div class="mb-1">
                        <label class="form-label">Meta Keywords</label>
                        <textarea id="seo-meta_keywords" class="form-control" rows="2" placeholder="comma, separated, keywords"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="seo-save-btn"><i class="bi bi-floppy me-1"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function () {
            $("#seo-table").DataTable({
                dom: "Bfrtip",
                pageLength: 25,
                autoWidth: false,
                columnDefs: [{ orderable: false, targets: -1 }],
                buttons: ["copy", "csv", "excel", "print"],
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-excel").addClass("btn btn-primary mr-1");

            const BASE = $("#seo-table").data('base');
            const CSRF = $('meta[name="csrf-token"]').attr('content');
            const HAS_AREA = {{ $showArea ? 'true' : 'false' }};
            const modalEl = document.getElementById('seoEditModal');
            const modal = new bootstrap.Modal(modalEl);

            // Open modal pre-filled from the row's data-* (no extra request needed)
            $('#seo-table').on('click', '.seo-edit', function () {
                const d = $(this).data();
                $('#seo-row-id').val(d.id);
                $('#seo-modal-name').text(d.name);
                $('#seo-meta_title').val(d.meta_title ?? '');
                $('#seo-meta_description').val(d.meta_description ?? '');
                $('#seo-meta_keywords').val(d.meta_keywords ?? '');
                if (HAS_AREA) $('#seo-areaServed').val(d.area ?? '');
                modal.show();
            });

            $('#seo-save-btn').on('click', function () {
                const id = $('#seo-row-id').val();
                const payload = {
                    meta_title:       $('#seo-meta_title').val(),
                    meta_description: $('#seo-meta_description').val(),
                    meta_keywords:    $('#seo-meta_keywords').val(),
                };
                if (HAS_AREA) payload.areaServed = $('#seo-areaServed').val();

                const $btn = $(this).prop('disabled', true);
                $.ajax({
                    url: BASE + '/' + id,
                    method: 'POST',
                    data: payload,
                    headers: { 'X-CSRF-TOKEN': CSRF },
                    success: function (res) {
                        const $row = $('#seo-table').find('tr[data-id="' + id + '"]');
                        const cell = (sel, val, emptyHtml) => {
                            const $c = $row.find(sel);
                            $c.html((val && val.trim() !== '') ? $('<div>').text(val).html() : emptyHtml);
                        };
                        cell('.cell-meta_title', payload.meta_title, '<span class="badge bg-danger">missing</span>');
                        cell('.cell-meta_description', payload.meta_description, '<span class="badge bg-danger">missing</span>');
                        cell('.cell-meta_keywords', payload.meta_keywords, '<span class="badge bg-warning text-dark">missing</span>');
                        if (HAS_AREA) cell('.cell-areaServed', payload.areaServed, '<span class="muted-empty">—</span>');

                        // keep the row's Edit button data-* in sync for the next open
                        const $editBtn = $row.find('.seo-edit');
                        $editBtn.attr('data-meta_title', payload.meta_title);
                        $editBtn.attr('data-meta_description', payload.meta_description);
                        $editBtn.attr('data-meta_keywords', payload.meta_keywords);
                        if (HAS_AREA) $editBtn.attr('data-area', payload.areaServed);
                        $editBtn.removeData(); // force jQuery to re-read attrs next click

                        modal.hide();
                        Toast.fire({ icon: 'success', title: res.message || 'Saved' });
                    },
                    error: function (xhr) {
                        let msg = 'Save failed.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                        } else if (xhr.status === 403) {
                            msg = 'You do not have permission to edit this.';
                        }
                        Swal.fire({ icon: 'error', title: 'Failed', text: msg });
                    },
                    complete: function () { $btn.prop('disabled', false); }
                });
            });
        });
    </script>
@endsection
