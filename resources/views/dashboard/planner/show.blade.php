@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none mb-4">
        <div class="card-body px-4 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-semibold mb-1"><i class="ti ti-wand me-2"></i>Planner Session #{{ $session->id }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.planner.index') }}">Project Planner</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.planner.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Project inputs</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Goal</dt><dd class="col-sm-8">{{ $session->intent ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Region</dt><dd class="col-sm-8">{{ $session->region ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Facility</dt><dd class="col-sm-8">{{ $session->facility_type ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Areas</dt>
                        <dd class="col-sm-8">
                            @forelse((array) $session->selected_services as $a)
                                <span class="badge bg-light-primary text-primary me-1">{{ $a }}</span>
                            @empty — @endforelse
                        </dd>
                        <dt class="col-sm-4 text-muted">Engine</dt><dd class="col-sm-8">{{ strtoupper($session->engine) }}</dd>
                    </dl>
                    @if($session->free_text)
                        <hr>
                        <h6 class="fw-semibold">Their challenge</h6>
                        <p class="text-muted mb-0">{{ $session->free_text }}</p>
                    @endif
                    @if($session->ai_solution)
                        <hr>
                        <h6 class="fw-semibold">Suggested solution</h6>
                        <p class="text-muted mb-0">{{ $session->ai_solution }}</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Generated brief</h5>
                    <p class="text-muted">{{ $brief['summary'] ?? '' }}</p>
                    @if(!empty($brief['plan']))
                        <h6 class="fw-semibold mt-3">Custom plan</h6>
                        <p class="text-muted">{{ $brief['plan'] }}</p>
                    @endif
                    @if(!empty($brief['regulatory']))
                        <h6 class="fw-semibold mt-3">Regulatory &amp; licensing pathway</h6>
                        <p class="text-muted">{{ $brief['regulatory'] }}</p>
                    @endif
                    @if(!empty($brief['phases']))
                        <h6 class="fw-semibold mt-3">Our project plan</h6>
                    @endif
                    @foreach(($brief['phases'] ?? []) as $i => $p)
                        <div class="d-flex gap-3 mb-2">
                            <span class="fw-bold text-primary">{{ sprintf('%02d', $i+1) }}</span>
                            <div><strong>{{ $p['title'] ?? '' }}</strong><div class="text-muted small">{{ $p['detail'] ?? '' }}</div></div>
                        </div>
                    @endforeach
                    @if(!empty($brief['alpha_offer']))
                        <h6 class="fw-semibold mt-3">What Alpha can offer</h6>
                        <p class="text-muted mb-0">{{ $brief['alpha_offer'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Contact</h5>
                    @if($session->email)
                        <p class="mb-1"><strong>{{ $session->name }}</strong></p>
                        <p class="mb-1"><a href="mailto:{{ $session->email }}">{{ $session->email }}</a></p>
                        <p class="mb-2"><a href="tel:{{ $session->phone }}">{{ $session->phone }}</a></p>
                        @if($session->meeting_at)
                            <p class="mb-2"><span class="badge bg-light-warning text-warning"><i class="bi bi-calendar-event me-1"></i>Wants a consultation: {{ \Carbon\Carbon::parse($session->meeting_at)->format('M d, Y · h:i A') }}</span></p>
                        @endif
                        <p class="mb-3 small text-muted">Consent to be contacted: <strong>{{ $session->consent ? 'Yes' : 'No' }}</strong></p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', (string) $session->phone) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                        @if($session->inquiry_id)
                            <a href="{{ route('admin.inquiries.show', $session->inquiry_id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-envelope"></i> Open in Service Inquiries</a>
                        @endif
                    @else
                        <p class="text-muted mb-0">This visitor did not leave contact details.</p>
                    @endif
                    <hr>
                    <form action="{{ route('admin.planner.update', $session->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                        @csrf
                        <label class="text-muted small mb-0">Status</label>
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="new" {{ $session->status=='new'?'selected':'' }}>New</option>
                            <option value="contacted" {{ $session->status=='contacted'?'selected':'' }}>Contacted</option>
                            <option value="closed" {{ $session->status=='closed'?'selected':'' }}>Closed</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3"><i class="bi bi-calendar-check me-1"></i>Consultation</h5>
                    @if($session->meeting_confirmed)
                        <div class="alert alert-success py-2 px-3 small mb-3"><i class="bi bi-check-circle-fill me-1"></i>Confirmed — client emailed{{ $session->staff ? ', '.trim($session->staff->first_name.' '.$session->staff->last_name).' cc’d' : '' }}.</div>
                    @elseif($session->meeting_at)
                        <div class="alert alert-warning py-2 px-3 small mb-3"><i class="bi bi-clock-history me-1"></i>Client requested: <strong>{{ \Illuminate\Support\Carbon::parse($session->meeting_at)->format('M d, Y · h:i A') }}</strong></div>
                    @endif

                    @if($session->email)
                        <form action="{{ route('admin.planner.confirmMeeting', $session->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold mb-1">Date &amp; time</label>
                                <input type="datetime-local" name="meeting_at" class="form-control form-control-sm" required
                                    value="{{ $session->meeting_at ? \Illuminate\Support\Carbon::parse($session->meeting_at)->format('Y-m-d\TH:i') : '' }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold mb-1">Meeting link <span class="text-muted">(Zoom / Meet / Teams)</span></label>
                                <input type="url" name="meeting_link" class="form-control form-control-sm" placeholder="https://…" value="{{ $session->meeting_link }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold mb-1">Calendar invite link</label>
                                <input type="url" name="calendar_link" class="form-control form-control-sm" placeholder="https://… (.ics or calendar event)" value="{{ $session->calendar_link }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold mb-1">Also invite a colleague</label>
                                <select name="meeting_staff_id" class="form-select form-select-sm">
                                    <option value="">— none —</option>
                                    @foreach($staffList as $u)
                                        <option value="{{ $u->id }}" {{ $session->meeting_staff_id == $u->id ? 'selected' : '' }}>
                                            {{ trim($u->first_name.' '.$u->last_name) ?: $u->email }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success w-100">
                                <i class="bi bi-send-fill me-1"></i>{{ $session->meeting_confirmed ? 'Re-send confirmation email' : 'Confirm & email client' }}
                            </button>
                        </form>
                    @else
                        <p class="text-muted small mb-0">No client email on this session, so a consultation can't be confirmed.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Recommended services</h5>
                    @forelse($services as $svc)
                        <a href="{{ route('front.service', $svc['slug']) }}" target="_blank" class="d-block border rounded p-2 mb-2 text-decoration-none">
                            <strong class="text-dark">{{ $svc['name'] }}</strong>
                            <div class="text-muted small">{{ $svc['overview'] }}</div>
                        </a>
                    @empty
                        <p class="text-muted mb-0">None recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Consultant review panel --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-semibold mb-0">Consultant Review</h5>
                        <span class="badge bg-{{ $session->process_source === 'consultant_reviewed' ? 'success' : ($session->process_source === 'process_mapped' ? 'primary' : 'secondary') }}">
                            {{ str_replace('_', ' ', $session->process_source ?? 'ai_generated') }}
                        </span>
                    </div>

                    @if($session->ai_process_output)
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">AI-generated output (read-only)</label>
                        <div class="bg-light rounded p-3 border" style="max-height:200px;overflow-y:auto;font-size:0.82rem;white-space:pre-wrap;font-family:monospace;">{{ $session->ai_process_output }}</div>
                    </div>
                    @endif

                    <form action="{{ route('admin.planner.saveOutcome', $session->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Consultant outcome <span class="text-muted fw-normal small">(edit and improve the AI plan)</span></label>
                            <textarea name="consultant_outcome" rows="8" class="form-control" placeholder="Paste or write the refined outcome here…">{{ old('consultant_outcome', $session->consultant_outcome) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Internal notes <span class="text-muted fw-normal small">(not shown to client)</span></label>
                            <textarea name="consultant_notes" rows="3" class="form-control" placeholder="Notes for internal follow-up…">{{ old('consultant_notes', $session->consultant_notes) }}</textarea>
                        </div>
                        @if($session->consultant_reviewed_at)
                        <p class="text-muted small mb-2">Last reviewed: {{ $session->consultant_reviewed_at->format('d M Y, H:i') }}
                            @if($session->consultant) by {{ $session->consultant->first_name }} {{ $session->consultant->last_name }}@endif</p>
                        @endif
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-save me-1"></i> Save outcome
                            </button>
                        </div>
                    </form>

                    @if($session->consultant_outcome)
                    <form action="{{ route('admin.planner.cacheOutcome', $session->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm"
                            onclick="return confirm('Approve this outcome for reuse on similar future requests?')">
                            <i class="bi bi-check-circle me-1"></i> Approve for reuse
                        </button>
                        <span class="text-muted small ms-2">Stores this outcome in the similarity cache so future similar requests start from here.</span>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ title: 'Done', text: @json(session('success')), icon: 'success', timer: 4000, timerProgressBar: true });
        @elseif(session('error'))
            Swal.fire({ title: 'Heads up', text: @json(session('error')), icon: 'error' });
        @endif
    </script>
@endsection
