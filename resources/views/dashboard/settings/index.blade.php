@extends('dashboard/layout')

@section('content')
    <div class="card bg-light-info shadow-none mb-4">
        <div class="card-body px-4 py-3">
            <h4 class="fw-semibold mb-1"><i class="ti ti-settings me-2"></i>Settings</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-semibold mb-1">AI Project Planner</h5>
                    <p class="text-muted small mb-4">Connect Anthropic Claude to power the interactive planner with AI.
                        When disabled or no key is set, the planner automatically uses smart, DB-driven rules.</p>

                    @php
                        $activeKey = $aiProvider === 'anthropic' ? $hasKey : $hasGeminiKey;
                        $provLabel = $aiProvider === 'anthropic' ? 'Anthropic Claude' : 'Google Gemini';
                    @endphp
                    <div class="alert {{ $aiActive ? 'alert-success' : 'alert-secondary' }} d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $aiActive ? 'bi-broadcast' : 'bi-cpu' }} me-2 fs-5"></i>
                            <div>
                                <strong>AI is currently {{ $aiActive ? 'ACTIVE' : 'OFF (smart-rules mode)' }}.</strong>
                                <div class="small">Provider: <strong>{{ $provLabel }}</strong> — {{ $activeKey ? 'API key is configured.' : 'no API key for this provider yet.' }}</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="testAiBtn"><i class="bi bi-plug"></i> Test connection</button>
                    </div>
                    <div id="testAiResult" class="small mb-3" style="display:none"></div>

                    <form action="{{ route('admin.settings.save') }}" method="POST">
                        @csrf
                        <div class="form-check form-switch mb-4">
                            <input type="checkbox" class="form-check-input" id="ai_planner_enabled" name="ai_planner_enabled" value="1" {{ $aiEnabled ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="ai_planner_enabled">Enable AI for the planner</label>
                        </div>

                        <label class="form-label fw-semibold">AI provider</label>
                        <div class="d-flex gap-3 mb-3 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ai_provider" id="prov_gemini" value="gemini" {{ $aiProvider !== 'anthropic' ? 'checked' : '' }}>
                                <label class="form-check-label" for="prov_gemini">Google Gemini <span class="text-muted">(recommended)</span></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ai_provider" id="prov_anthropic" value="anthropic" {{ $aiProvider === 'anthropic' ? 'checked' : '' }}>
                                <label class="form-check-label" for="prov_anthropic">Anthropic Claude</label>
                            </div>
                        </div>

                        {{-- Gemini --}}
                        <div class="border rounded-3 p-3 mb-3 bg-light">
                            <div class="fw-semibold mb-2"><i class="bi bi-google me-1"></i>Google Gemini</div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold mb-1">Gemini API key</label>
                                <input type="password" name="gemini_api_key" class="form-control" autocomplete="off"
                                    placeholder="{{ $hasGeminiKey ? '•••••••••• (leave blank to keep current)' : 'AIza... your Gemini API key' }}">
                                <div class="form-text">Stored encrypted. Leave blank to keep the current key; type <code>__clear__</code> to remove. Get one at Google AI Studio.</div>
                            </div>
                            <div>
                                <label class="form-label small fw-semibold mb-1">Model</label>
                                <select name="gemini_model" class="form-select form-select-sm">
                                    @php $gm = ['gemini-2.5-flash-lite'=>'Gemini 2.5 Flash-Lite (highest free quota — recommended)','gemini-2.5-flash'=>'Gemini 2.5 Flash (low free quota — 20/day)','gemini-2.5-pro'=>'Gemini 2.5 Pro (smarter, higher cost)','gemini-2.0-flash'=>'Gemini 2.0 Flash']; @endphp
                                    @foreach($gm as $val => $label)
                                        <option value="{{ $val }}" {{ $geminiModel === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Anthropic (alternative) --}}
                        <div class="border rounded-3 p-3 mb-4">
                            <div class="fw-semibold mb-2">Anthropic Claude</div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold mb-1">Anthropic API key</label>
                                <input type="password" name="anthropic_api_key" class="form-control" autocomplete="off"
                                    placeholder="{{ $hasKey ? '•••••••••• (leave blank to keep current)' : 'sk-ant-...' }}">
                            </div>
                            <div>
                                <label class="form-label small fw-semibold mb-1">Model</label>
                                <select name="ai_model" class="form-select form-select-sm">
                                    @php $models = ['claude-haiku-4-5-20251001'=>'Claude Haiku 4.5','claude-sonnet-4-6'=>'Claude Sonnet 4.6']; @endphp
                                    @foreach($models as $val => $label)
                                        <option value="{{ $val }}" {{ $aiModel === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        <label class="form-label fw-semibold">Gemini raw outcome (testing)</label>
                        <p class="text-muted small mb-2">When on, a "Gemini outcome (raw)" panel appears on the planner results page showing the exact model output — for you to test &amp; tune the prompt. Turn it off and the panel never shows to visitors.</p>
                        <div class="form-check form-switch mb-4">
                            <input type="checkbox" class="form-check-input" id="planner_show_raw" name="planner_show_raw" value="1" {{ $showRaw ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="planner_show_raw">Show raw Gemini outcome on the results page</label>
                        </div>

                        <hr class="my-4">
                        <label class="form-label fw-semibold">Contact capture flow (Project Planner)</label>
                        <p class="text-muted small mb-2">Choose when the planner asks visitors for their name, email & mobile.</p>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="planner_contact_timing" id="ct_before" value="before" {{ $contactTiming !== 'after' ? 'checked' : '' }}>
                            <label class="form-check-label" for="ct_before">
                                <strong>Contact before results</strong> — capture details on the last step, then reveal the plan &amp; email it. (Best for leads.)
                            </label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="planner_contact_timing" id="ct_after" value="after" {{ $contactTiming === 'after' ? 'checked' : '' }}>
                            <label class="form-check-label" for="ct_after">
                                <strong>No contact before results</strong> — show the plan immediately; contact is optional afterwards.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy me-1"></i> Save settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ title: 'Saved', text: '{{ session('success') }}', icon: 'success', timer: 2500, timerProgressBar: true });
        @endif
        (function () {
            var btn = document.getElementById('testAiBtn');
            var out = document.getElementById('testAiResult');
            if (!btn) return;
            btn.addEventListener('click', function () {
                btn.disabled = true; var old = btn.innerHTML; btn.innerHTML = 'Testing…';
                out.style.display = 'none';
                fetch('{{ route('admin.settings.test') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(r => r.json()).then(d => {
                    out.style.display = '';
                    out.className = 'small mb-3 p-3 border rounded-3 ' + (d.ok ? 'border-success bg-light-success' : 'border-danger');
                    var reached = d.reached
                        ? '<span class="badge bg-success-subtle text-success">Reached Gemini</span>'
                        : '<span class="badge bg-danger-subtle text-danger">Did NOT reach Gemini</span>';
                    var status = (d.status !== undefined && d.status !== null)
                        ? ' <span class="badge bg-secondary-subtle text-secondary">HTTP ' + d.status + '</span>' : '';
                    var model = d.model ? ' <span class="badge bg-secondary-subtle text-secondary">' + d.model + '</span>' : '';
                    var html = '<div class="mb-2">' + reached + status + model + '</div>'
                        + '<div class="' + (d.ok ? 'text-success' : 'text-danger') + '"><i class="bi '
                        + (d.ok ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill') + ' me-1"></i>' + d.message + '</div>';
                    if (d.error) html += '<pre class="mt-2 mb-0 small text-muted" style="white-space:pre-wrap;max-height:160px;overflow:auto;">' + d.error.replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c])) + '</pre>';
                    if (d.raw) html += '<div class="mt-2 small text-muted">Sample output:</div><pre class="mb-0 small text-muted" style="white-space:pre-wrap;max-height:160px;overflow:auto;">' + d.raw.replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c])) + '</pre>';
                    out.innerHTML = html;
                }).catch(() => {
                    out.style.display = ''; out.className = 'small mb-3 text-danger'; out.textContent = 'Test failed to run.';
                }).finally(() => { btn.disabled = false; btn.innerHTML = old; });
            });
        })();
    </script>
@endsection
