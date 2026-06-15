@extends('front/layout-2')

@push('page_title', 'Alpha Blueprint AI — AI Healthcare Project Planner | Alpha Health Group')

@section('meta_description', 'Alpha Blueprint AI — an AI-powered healthcare project planner. Tell us your goal and it maps the right licensing, accreditation, quality and operational path, with the services best suited to you, in about a minute.')

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('public/front/assets/css/plan-your-project.css') }}?v=12">
@endsection

@section('content')
<section class="pp-wrap">
    <div class="container">
        <div class="pp-head">
            <span class="pp-eyebrow"><span class="pp-ai-dot"></span> Alpha Blueprint AI &nbsp;·&nbsp; AI Healthcare Planner</span>
            <h1 class="pp-title">Plan your healthcare project, intelligently.</h1>
            <p class="pp-sub">Answer a few quick questions and Alpha Blueprint AI maps out how we'd approach it —
                a tailored plan and the services best suited to you. About 60 seconds.</p>
        </div>

        {{-- Progress --}}
        <div class="pp-progress-wrap" id="ppProgressWrap" style="display:none">
            <div class="pp-progress-track"><div class="pp-progress-bar" id="ppProgressBar"></div></div>
            <div class="pp-progress-meta">
                <span id="ppStepLabel">Step 1</span>
                <span id="ppProgressPct">0% complete</span>
            </div>
        </div>

        <div class="pp-card" id="ppCard">

            <button type="button" class="pp-back" id="ppBack" style="display:none">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>

            {{-- INTRO / START --}}
            <div class="pp-step is-active" data-step="start">
                <div class="pp-start">
                    <div class="pp-start-icon"><i class="fa-solid fa-pen-ruler"></i></div>
                    <h2>Your healthcare project, mapped in 60 seconds.</h2>
                    <p>Answer 5 quick questions and Alpha Blueprint AI builds your execution plan — the exact steps,
                        realistic timelines, and the right services to make it happen. No calls. No forms upfront. No pressure.</p>
                    <div class="pp-value-row">
                        <span class="pp-value">Under 60 seconds</span>
                        <span class="pp-value">A real, step-by-step plan</span>
                        <span class="pp-value">Services matched to you</span>
                        <span class="pp-value">Zero commitment</span>
                    </div>
                    <button type="button" class="pp-btn pp-btn-primary pp-btn-lg" id="ppStart">
                        Build my free plan <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <div class="pp-start-meta">Free &nbsp;·&nbsp; Instant &nbsp;·&nbsp; Built by AI, refined by our consultants</div>
                </div>
            </div>

            {{-- DYNAMIC CUSTOMER STEPS (managed in dashboard → Planner Builder) --}}
            @foreach($steps as $i => $s)
                <div class="pp-step" data-step="{{ $s['key'] }}" data-kind="{{ $s['kind'] }}">
                    <span class="pp-step-tag">Step {{ $i + 1 }} of {{ count($steps) }}</span>
                    <h2 class="pp-q">{{ $s['label'] }}</h2>
                    @if(!empty($s['help']))<p class="pp-q-hint">{{ $s['help'] }}</p>@endif

                    @if($s['kind'] === 'text')
                        <textarea class="pp-textarea pp-text-input" data-field="{{ $s['key'] }}" rows="4" maxlength="1500" placeholder="Type here…"></textarea>
                        <div class="pp-nav">
                            <button type="button" class="pp-btn pp-btn-primary" data-next>
                                <span class="pp-next-label">Continue</span> <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    @else
                        <div class="pp-options pp-chips" data-field="{{ $s['key'] }}" data-single="{{ $s['kind'] === 'choice' ? '1' : '0' }}">
                            @foreach($s['options'] as $opt)
                                <button type="button" class="pp-opt pp-chip" data-value="{{ $opt }}">{{ $opt }}</button>
                            @endforeach
                        </div>
                        @if($s['kind'] === 'choice' && in_array('Something else', $s['options']))
                            <div class="pp-other-wrap" data-other="{{ $s['key'] }}" style="display:none">
                                <input type="text" class="pp-textarea pp-other-input" maxlength="160" placeholder="Tell us in your own words…">
                                <div class="pp-nav"><button type="button" class="pp-btn pp-btn-primary" data-other-next>Continue <i class="fa-solid fa-arrow-right"></i></button></div>
                            </div>
                        @endif
                        @if($s['kind'] === 'multichoice')
                            <div class="pp-nav">
                                <button type="button" class="pp-btn pp-btn-primary" data-next>
                                    <span class="pp-next-label">Continue</span> <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach

            {{-- PRE-RESULTS CONTACT (shown when "contact before results" is on) --}}
            <div class="pp-step" data-step="precontact">
                <div class="pp-unlock">
                    <span class="pp-step-tag pp-tag-done"><i class="fa-solid fa-circle-check"></i> Your blueprint is ready</span>
                    <h2 class="pp-q">Your blueprint is one tap away</h2>
                    <p class="pp-sell">You're moments from a consultant-grade blueprint for your facility. It lays out the exact steps,
                        in the right order, with the services that get you there. We'll email your copy the instant it's ready, so it's
                        yours to keep and share with your team. Prefer to talk it through? You can book a free call with an Alpha
                        specialist right below.</p>
                    <div class="pp-trust">
                        <span><i class="fa-solid fa-building-shield"></i> DOH-approved consultancy</span>
                        <span><i class="fa-solid fa-user-shield"></i> Private — your plan only, no spam</span>
                        <span><i class="fa-solid fa-bolt"></i> Delivered in seconds</span>
                    </div>
                </div>

                <form id="ppPreContactForm" novalidate>
                    <div class="pp-field-grid">
                        <div class="pp-field">
                            <label for="pp-name">Full name <span class="pp-req">*</span></label>
                            <input type="text" id="pp-name" name="name" autocomplete="name" placeholder="e.g. Dr. Sarah Ahmed">
                        </div>
                        <div class="pp-field">
                            <label for="pp-email">Work email <span class="pp-req">*</span></label>
                            <input type="email" id="pp-email" name="email" autocomplete="email" placeholder="you@facility.com">
                        </div>
                        <div class="pp-field">
                            <label for="pp-phone">Mobile number <span class="pp-req">*</span></label>
                            <input type="tel" id="pp-phone" name="phone" autocomplete="tel" placeholder="+971 50 000 0000">
                        </div>
                    </div>

                    <label class="pp-consent">
                        <input type="checkbox" id="ppConsent" checked>
                        <span>It's okay for an Alpha consultant to contact me about my project.</span>
                    </label>

                    {{-- Optional: book a free consultation --}}
                    <div class="pp-meeting">
                        <label class="pp-switch">
                            <input type="checkbox" id="ppWantMeeting">
                            <span class="pp-switch-ui"></span>
                            <span class="pp-switch-text"><strong>Book a free consultation</strong> — talk it through with an expert <span class="pp-opt-tag">optional</span></span>
                        </label>
                        <div class="pp-meeting-fields" id="ppMeetingFields" hidden>
                            <div class="pp-field">
                                <label>Preferred date</label>
                                <input type="date" name="meeting_date" id="ppMeetingDate">
                            </div>
                            <div class="pp-field">
                                <label>Preferred time</label>
                                <input type="time" name="meeting_time" id="ppMeetingTime" value="10:00">
                            </div>
                        </div>
                    </div>

                    <p class="pp-pre-alert" id="ppPreAlert" style="display:none"></p>
                    <div class="pp-nav">
                        <button type="submit" class="pp-btn pp-btn-primary pp-btn-lg" id="ppRevealBtn">
                            <span class="pp-reveal-label">Authorise &amp; reveal my blueprint</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                    <p class="pp-pre-note"><i class="fa-solid fa-lock"></i> Used only to send your plan and follow up if you ask. No spam, ever.</p>
                </form>
            </div>

            {{-- RESULTS --}}
            <div class="pp-step" data-step="results">
                <div class="pp-results-head">
                    <span class="pp-step-tag pp-tag-done"><i class="fa-solid fa-circle-check"></i> Your blueprint is ready</span>
                    <h2 class="pp-q" id="ppSummary"></h2>
                    <p class="pp-engine-note" id="ppEngineNote" style="display:none"></p>
                </div>

                {{-- Your custom plan --}}
                <div id="ppPlanBlock" class="pp-section" style="display:none">
                    <h4 class="pp-block-title"><span class="pp-sec-num">01</span> Your custom plan</h4>
                    <p class="pp-section-body" id="ppPlan"></p>
                </div>

                {{-- Regulatory pathway --}}
                <div id="ppRegBlock" class="pp-section" style="display:none">
                    <h4 class="pp-block-title"><span class="pp-sec-num">02</span> Regulatory &amp; licensing pathway</h4>
                    <p class="pp-section-body" id="ppRegulatory"></p>
                </div>

                {{-- Our project plan (delivery phases) --}}
                <div id="ppPhasesBlock" class="pp-section" style="display:none">
                    <h4 class="pp-block-title"><span class="pp-sec-num">03</span> Our project plan</h4>
                    <div class="pp-phases" id="ppPhases"></div>
                </div>

                {{-- Timeline + (conditional) cost --}}
                <div class="pp-estimates" id="ppEstimates" style="display:none">
                    <div class="pp-estimate">
                        <span class="pp-est-label"><i class="fa-regular fa-clock"></i> Indicative timeline</span>
                        <p id="ppTimeline"></p>
                    </div>
                    <div class="pp-estimate" id="ppCostBox" style="display:none">
                        <span class="pp-est-label"><i class="fa-solid fa-coins"></i> Indicative investment</span>
                        <p id="ppCost"></p>
                    </div>
                </div>

                {{-- What Alpha can offer --}}
                <div id="ppOfferBlock" class="pp-section pp-section--offer" style="display:none">
                    <h4 class="pp-block-title"><span class="pp-sec-num">04</span> What Alpha can offer</h4>
                    <p class="pp-section-body" id="ppOffer"></p>
                </div>

                {{-- Our list of services tailored to the client --}}
                <div class="pp-tailored-head">
                    <h4 class="pp-block-title pp-block-title--lg">We've tailor-made these services for you</h4>
                    <p class="pp-tailored-sub">Pulled from our catalogue and matched to your answers — these are where Alpha can help you most.</p>
                </div>
                <div class="pp-recs" id="ppRecs"></div>

                {{-- Single results contact band: step-5 style — captures details + an optional consultation --}}
                <div class="pp-results-band" id="ppResultsBand">
                    <div class="pp-results-band-head">
                        <h4>Want our experts to take it from here?</h4>
                        <p>Share your details and we'll send your blueprint and follow up with the next steps. Want to talk it
                            through? Book a free consultation below and a specialist will walk you through your plan.</p>
                    </div>
                    <form id="ppResultsForm" novalidate>
                        <input type="hidden" name="uuid" id="ppUuid">
                        <div class="pp-field-grid">
                            <div class="pp-field">
                                <label for="pp-rname">Full name <span class="pp-req">*</span></label>
                                <input type="text" id="pp-rname" name="name" autocomplete="name" placeholder="e.g. Dr. Sarah Ahmed">
                            </div>
                            <div class="pp-field">
                                <label for="pp-remail">Work email <span class="pp-req">*</span></label>
                                <input type="email" id="pp-remail" name="email" autocomplete="email" placeholder="you@facility.com">
                            </div>
                            <div class="pp-field">
                                <label for="pp-rphone">Mobile number <span class="pp-req">*</span></label>
                                <input type="tel" id="pp-rphone" name="phone" autocomplete="tel" placeholder="+971 50 000 0000">
                            </div>
                        </div>

                        <label class="pp-consent">
                            <input type="checkbox" id="ppResConsent" checked>
                            <span>It's okay for an Alpha consultant to contact me about my project.</span>
                        </label>

                        <div class="pp-meeting">
                            <label class="pp-switch">
                                <input type="checkbox" id="ppResWantMeeting">
                                <span class="pp-switch-ui"></span>
                                <span class="pp-switch-text">Book a free consultation, talk it through with an expert <span class="pp-opt-tag">optional</span></span>
                            </label>
                            <div class="pp-meeting-fields" id="ppResMeetingFields" hidden>
                                <div class="pp-field">
                                    <label>Preferred date</label>
                                    <input type="date" name="meeting_date" id="ppResMeetingDate">
                                </div>
                                <div class="pp-field">
                                    <label>Preferred time</label>
                                    <input type="time" name="meeting_time" id="ppResMeetingTime" value="10:00">
                                </div>
                            </div>
                        </div>

                        <p class="pp-pre-alert" id="ppResAlert" style="display:none"></p>
                        <div class="pp-nav">
                            <button type="submit" class="pp-btn pp-btn-primary pp-btn-lg" id="ppResBtn">
                                <span class="pp-res-label">Send my details</span> <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                        <p class="pp-pre-note"><i class="fa-solid fa-lock"></i> Used only to send your plan and follow up if you ask. No spam, ever.</p>
                    </form>
                </div>

                {{-- Follow-up (before-flow): we already have their details — only offer consent / a consultation --}}
                <div class="pp-followup" id="ppFollowup" style="display:none">
                    <div class="pp-followup-head">
                        <h4>One more step — want us to reach out?</h4>
                        <p>We already have your details. Give the go-ahead for a consultant to contact you, or book a free consultation to walk through your plan.</p>
                    </div>
                    <form id="ppFollowupForm" novalidate>
                        <label class="pp-consent" id="ppFuConsentWrap">
                            <input type="checkbox" id="ppFuConsent">
                            <span>Yes, an Alpha consultant can contact me about my project.</span>
                        </label>
                        <div class="pp-meeting" id="ppFuMeetingWrap">
                            <label class="pp-switch">
                                <input type="checkbox" id="ppFuWantMeeting">
                                <span class="pp-switch-ui"></span>
                                <span class="pp-switch-text">Book a free consultation, talk it through with an expert <span class="pp-opt-tag">optional</span></span>
                            </label>
                            <div class="pp-meeting-fields" id="ppFuMeetingFields" hidden>
                                <div class="pp-field">
                                    <label>Preferred date</label>
                                    <input type="date" id="ppFuMeetingDate">
                                </div>
                                <div class="pp-field">
                                    <label>Preferred time</label>
                                    <input type="time" id="ppFuMeetingTime" value="10:00">
                                </div>
                            </div>
                        </div>
                        <p class="pp-pre-alert" id="ppFuAlert" style="display:none"></p>
                        <div class="pp-nav">
                            <button type="submit" class="pp-btn pp-btn-primary" id="ppFuBtn">
                                <span class="pp-fu-label">Confirm</span> <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="pp-results-actions">
                    <a href="{{ route('front.all-services') }}" class="pp-btn pp-btn-ghost">Browse all services</a>
                    <button type="button" class="pp-btn pp-btn-ghost" id="ppRestart"><i class="fa-solid fa-rotate-left"></i> Start over</button>
                </div>

                @if($showRaw)
                {{-- Raw model output — rendered only when the admin enables the toggle in Settings --}}
                <div class="pp-debug" id="ppDebug" style="display:none">
                    <div class="pp-debug-head">
                        <span class="pp-debug-tag">Gemini outcome (raw)</span>
                        <button type="button" class="pp-debug-copy" id="ppRawCopy">Copy</button>
                    </div>
                    <pre class="pp-raw" id="ppRaw"></pre>
                    <p class="pp-debug-note">Testing panel — toggle this off in Settings to hide it from visitors. Engine: <span id="ppRawEngine"></span></p>
                </div>
                @endif
            </div>

        </div>

        {{-- choices summary --}}
        <div class="pp-choices" id="ppChoices" style="display:none"></div>
    </div>

    {{-- Blueprint-building loading overlay --}}
    <div class="pp-loading" id="ppLoading" aria-hidden="true">
        <div class="pp-loading-inner">
            <div class="pp-loading-art">
                <span class="pp-bp-grid"></span>
                <span class="pp-bp-ring"></span>
                <i class="fa-solid fa-pen-ruler"></i>
            </div>
            <h3>Alpha Blueprint AI is building your plan</h3>
            <p id="ppLoadingMsg">Reading your answers…</p>
            <div class="pp-loading-bar"><span></span></div>
        </div>
    </div>
</section>
@endsection

@push('inquiry_modal')
    @include('front.partials.inquiry-modal')
@endpush

@push('scripts')
<script>
(function () {
    const CSRF = '{{ csrf_token() }}';
    const URLS = { step: '{{ route('planner.step') }}', analyze: '{{ route('planner.analyze') }}', contact: '{{ route('planner.contact') }}', followup: '{{ route('planner.followup') }}' };
    const TIMING = '{{ $contactTiming }}';
    const ORDER = @json(collect($steps)->pluck('key')->values());
    const answers = {};
    let leadEmail = '';
    let leadConsent = 0;
    let meetingRequested = false;
    let sessionUuid = '';

    const card = document.getElementById('ppCard');
    const steps = {};
    card.querySelectorAll('.pp-step').forEach(s => steps[s.dataset.step] = s);
    const progressWrap = document.getElementById('ppProgressWrap');
    const progressBar = document.getElementById('ppProgressBar');
    const progressPct = document.getElementById('ppProgressPct');
    const stepLabel = document.getElementById('ppStepLabel');
    const choicesEl = document.getElementById('ppChoices');

    function show(name) {
        Object.values(steps).forEach(s => s.classList.remove('is-active'));
        if (!steps[name]) return;
        steps[name].classList.add('is-active');
        steps[name].scrollIntoView({ behavior: 'smooth', block: 'center' });
        const idx = ORDER.indexOf(name);
        if (idx >= 0) {
            progressWrap.style.display = '';
            const pct = Math.round(idx / ORDER.length * 100);
            progressBar.style.width = pct + '%';
            progressPct.textContent = pct + '% complete';
            stepLabel.textContent = 'Step ' + (idx + 1) + ' of ' + ORDER.length;
        } else if (name === 'results') {
            progressBar.style.width = '100%'; progressPct.textContent = '100% complete'; stepLabel.textContent = 'Done';
        }
    }

    // History-aware navigation
    let current = 'start'; const history = [];
    const backBtn = document.getElementById('ppBack');
    function go(name){ history.push(current); current = name; show(name); updateBack(); }
    function back(){ if(!history.length) return; current = history.pop(); show(current); updateBack(); }
    function updateBack(){ backBtn.style.display = (history.length && current !== 'start' && current !== 'results') ? '' : 'none'; }
    backBtn.addEventListener('click', back);

    function advanceFrom(stepKey){ const i = ORDER.indexOf(stepKey); if (i < 0 || i >= ORDER.length - 1) generate(); else go(ORDER[i + 1]); }

    function ack(stepName, value){
        fetch(URLS.step, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json','Accept':'application/json'},
            body: JSON.stringify({ step: stepName, value: value || '' }) })
            .then(r=>r.json()).then(d=>{ if(d && d.understand) renderToast(d.understand); }).catch(()=>{});
    }
    let toastTimer;
    function renderToast(text){ let t=document.getElementById('ppToast'); if(!t){t=document.createElement('div');t.id='ppToast';t.className='pp-toast';document.body.appendChild(t);} t.innerHTML='<i class="fa-solid fa-circle-check"></i> '+text; t.classList.add('show'); clearTimeout(toastTimer); toastTimer=setTimeout(()=>t.classList.remove('show'),3200); }

    function renderChoices(){
        const parts=[];
        ORDER.forEach(k=>{ const v=answers[k]; if(Array.isArray(v)) v.forEach(x=>parts.push(x)); else if(v) parts.push(v); });
        if(!parts.length){ choicesEl.style.display='none'; return; }
        choicesEl.style.display='';
        choicesEl.innerHTML='<span class="pp-choices-label">Your choices:</span> ' + parts.map(p=>'<span class="pp-choice-chip">'+escapeHtml(p)+'</span>').join('');
    }

    document.getElementById('ppStart').addEventListener('click', ()=>{ if(ORDER.length) go(ORDER[0]); if(typeof trackConversion==='function') trackConversion('ahg_planner_started',{}); });

    // Option clicks (single + multi)
    card.querySelectorAll('.pp-options').forEach(group=>{
        const field=group.dataset.field; const single=group.dataset.single==='1';
        group.querySelectorAll('.pp-opt').forEach(opt=>{
            opt.addEventListener('click', ()=>{
                const val=opt.dataset.value; const stepName=group.closest('.pp-step').dataset.step;
                if(single){
                    group.querySelectorAll('.pp-opt').forEach(o=>o.classList.remove('selected'));
                    opt.classList.add('selected');
                    const otherWrap=group.parentElement.querySelector('[data-other]');
                    if(val==='Something else' && otherWrap){ otherWrap.style.display=''; answers[field]=''; otherWrap.querySelector('.pp-other-input').focus(); return; }
                    if(otherWrap) otherWrap.style.display='none';
                    answers[field]=val; renderChoices(); ack(stepName,val); setTimeout(()=>advanceFrom(stepName),360);
                } else {
                    opt.classList.toggle('selected');
                    answers[field]=Array.from(group.querySelectorAll('.pp-opt.selected')).map(o=>o.dataset.value);
                    renderChoices();
                }
            });
        });
    });

    // Continue (multichoice + text steps)
    card.querySelectorAll('[data-next]').forEach(b=>b.addEventListener('click', ()=>{
        const step=b.closest('.pp-step'); const key=step.dataset.step;
        const textInput=step.querySelector('.pp-text-input');
        if(textInput) answers[key]=textInput.value.trim();
        renderChoices(); ack(key, Array.isArray(answers[key])?answers[key].join(', '):(answers[key]||''));
        advanceFrom(key);
    }));

    // "Something else" typed value
    card.querySelectorAll('[data-other-next]').forEach(b=>b.addEventListener('click', ()=>{
        const wrap=b.closest('[data-other]'); const key=wrap.dataset.other;
        const txt=wrap.querySelector('.pp-other-input').value.trim();
        answers[key]=txt||'Something else'; renderChoices(); ack(key, answers[key]); advanceFrom(key);
    }));

    // Blueprint-building loading animation
    const LOADING_MSGS = ['Reading your answers…','Mapping your regulatory pathway…','Sequencing the right steps…','Matching the services to your goal…','Finalising your blueprint…'];
    let loadingTimer=null, loadingStart=0;
    function showLoading(){
        const o=document.getElementById('ppLoading'); if(!o) return;
        let i=0; const m=document.getElementById('ppLoadingMsg'); if(m) m.textContent=LOADING_MSGS[0];
        clearInterval(loadingTimer);
        loadingTimer=setInterval(()=>{ i=(i+1)%LOADING_MSGS.length; if(m) m.textContent=LOADING_MSGS[i]; }, 1500);
        o.classList.add('show'); o.setAttribute('aria-hidden','false'); loadingStart=Date.now();
    }
    function hideLoading(cb){
        const o=document.getElementById('ppLoading');
        const wait=Math.max(0, 1700-(Date.now()-loadingStart)); // let the animation breathe even on fast responses
        setTimeout(()=>{ clearInterval(loadingTimer); if(o){ o.classList.remove('show'); o.setAttribute('aria-hidden','true'); } if(cb) cb(); }, wait);
    }

    // Analyze
    function runAnalyze(extra, btn, labelEl, busyText, idleText){
        if(btn){ btn.disabled=true; if(labelEl) labelEl.textContent=busyText; }
        showLoading();
        fetch(URLS.analyze, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json','Accept':'application/json'},
            body: JSON.stringify(Object.assign({ answers }, extra||{})) })
            .then(r=>r.json()).then(d=>{ renderResults(d); hideLoading(); })
            .catch(()=>{ hideLoading(()=>{ if(btn){ btn.disabled=false; if(labelEl) labelEl.textContent=idleText; } renderToast('Something went wrong — please try again.'); }); });
    }

    function generate(){
        if(TIMING==='before'){ go('precontact'); return; }
        runAnalyze({}, null, null, '', '');
    }

    // Pre-results contact
    const preForm=document.getElementById('ppPreContactForm');
    const wantMeeting=document.getElementById('ppWantMeeting');
    const meetingFields=document.getElementById('ppMeetingFields');
    const meetingDate=document.getElementById('ppMeetingDate');
    if(meetingDate) meetingDate.min=new Date().toISOString().split('T')[0];
    wantMeeting.addEventListener('change', ()=>{ meetingFields.hidden=!wantMeeting.checked; if(wantMeeting.checked && meetingDate && !meetingDate.value){ const d=new Date(); d.setDate(d.getDate()+1); meetingDate.value=d.toISOString().split('T')[0]; } });
    const emailRe=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    ['name','email','phone'].forEach(n=>preForm[n].addEventListener('input', ()=>preForm[n].classList.remove('is-invalid')));
    preForm.addEventListener('submit', e=>{
        e.preventDefault();
        const name=preForm.name.value.trim(), email=preForm.email.value.trim(), phone=preForm.phone.value.trim();
        const pd=phone.replace(/[^0-9]/g,'').length;
        preForm.name.classList.toggle('is-invalid',!name); preForm.email.classList.toggle('is-invalid',!emailRe.test(email)); preForm.phone.classList.toggle('is-invalid',pd<7);
        const alertEl=document.getElementById('ppPreAlert');
        if(!name||!emailRe.test(email)||pd<7){ alertEl.style.display=''; alertEl.textContent='Please complete all three fields — a valid name, email and mobile number.'; const fb=preForm.querySelector('.is-invalid'); if(fb) fb.focus(); return; }
        alertEl.style.display='none'; leadEmail=email;
        leadConsent = document.getElementById('ppConsent').checked ? 1 : 0;
        const extra={ name, email, phone, consent: leadConsent };
        if(wantMeeting.checked && meetingDate.value){ extra.meeting_date=meetingDate.value; extra.meeting_time=document.getElementById('ppMeetingTime').value||'10:00'; meetingRequested=true; }
        const btn=document.getElementById('ppRevealBtn');
        runAnalyze(extra, btn, btn.querySelector('.pp-reveal-label'), 'Building your blueprint…', 'Authorise & reveal my blueprint');
    });

    function renderResults(d){
        document.getElementById('ppSummary').textContent = d.summary || 'Here is your tailored plan.';
        sessionUuid = d.uuid || '';
        const uu = document.getElementById('ppUuid'); if(uu) uu.value = sessionUuid;
        const note=document.getElementById('ppEngineNote');
        if(d.engine==='ai'){ note.style.display=''; note.innerHTML='<i class="fa-solid fa-pen-ruler"></i> Tailored by Alpha Blueprint AI'; }
        if(d.plan){ document.getElementById('ppPlanBlock').style.display=''; document.getElementById('ppPlan').textContent=d.plan; }
        if(d.regulatory){ document.getElementById('ppRegBlock').style.display=''; document.getElementById('ppRegulatory').textContent=d.regulatory; }
        const phases=(d.phases||[]);
        if(phases.length){ document.getElementById('ppPhasesBlock').style.display=''; document.getElementById('ppPhases').innerHTML=phases.map((p,i)=>`<div class="pp-phase" style="animation-delay:${i*90}ms"><div class="pp-phase-num">${String(i+1).padStart(2,'0')}</div><div class="pp-phase-body"><h5>${escapeHtml(p.title)}</h5><p>${escapeHtml(p.detail)}</p></div></div>`).join(''); }
        // Cost block only when the client actually asked about cost.
        const showCost = !!(d.cost_requested && d.cost);
        if(d.timeline || showCost){
            document.getElementById('ppEstimates').style.display='';
            document.getElementById('ppTimeline').textContent=d.timeline||'Shared after a short scope call.';
            if(showCost){ document.getElementById('ppCostBox').style.display=''; document.getElementById('ppCost').textContent=d.cost; }
        }
        if(d.alpha_offer){ document.getElementById('ppOfferBlock').style.display=''; document.getElementById('ppOffer').textContent=d.alpha_offer; }
        const recsEl=document.getElementById('ppRecs');
        if((d.services||[]).length){ recsEl.innerHTML=d.services.map(s=>`<a class="pp-rec" href="{{ url('/services') }}/${s.slug}"><h5>${escapeHtml(s.name)}</h5><p>${escapeHtml(s.reason||s.overview||'')}</p><span class="pp-rec-link">View service <i class="fa-solid fa-arrow-right"></i></span></a>`).join(''); }
        else { recsEl.innerHTML='<p class="pp-muted">Talk to our team and we\'ll match the right services to your needs.</p>'; }
        if(TIMING==='before'){
            const head = 'Your blueprint is on its way' + (leadEmail ? ' to ' + escapeHtml(leadEmail) : '') + (meetingRequested ? ', and your consultation request is in.' : '.');
            showResultsSuccess(head);
            showFollowupIfNeeded();
            if(typeof trackConversion==='function') trackConversion('ahg_planner_contact',{});
        }
        var dbg = document.getElementById('ppDebug'); // only present when the admin toggle is on
        if (dbg) {
            dbg.style.display='';
            document.getElementById('ppRaw').textContent = d.raw || '(No Gemini output — the planner fell back to smart-rules. Open Settings → Test connection to see why, e.g. quota/rate limit.)';
            var re=document.getElementById('ppRawEngine'); if(re) re.textContent = d.engine || 'rules';
        }
        go('results'); progressWrap.style.display='none';
        if(typeof trackConversion==='function') trackConversion('ahg_planner_completed',{ engine: d.engine||'rules' });
    }

    function showResultsSuccess(headline){
        const band=document.getElementById('ppResultsBand'); if(!band) return;
        band.innerHTML='<div class="pp-contact-success"><i class="fa-solid fa-circle-check"></i><h4>'+headline+'</h4>'
            + '<p>Check your inbox for your blueprint. Explore your recommended services above — a consultant will follow up if you asked us to.</p></div>';
    }

    // Results contact form (after flow) — full details + an optional consultation
    const rForm=document.getElementById('ppResultsForm');
    const resWantMeeting=document.getElementById('ppResWantMeeting');
    const resMeetingFields=document.getElementById('ppResMeetingFields');
    const resMeetingDate=document.getElementById('ppResMeetingDate');
    if(resMeetingDate) resMeetingDate.min=new Date().toISOString().split('T')[0];
    if(resWantMeeting) resWantMeeting.addEventListener('change', ()=>{ resMeetingFields.hidden=!resWantMeeting.checked; if(resWantMeeting.checked && resMeetingDate && !resMeetingDate.value){ const d=new Date(); d.setDate(d.getDate()+1); resMeetingDate.value=d.toISOString().split('T')[0]; } });
    if(rForm){
        ['name','email','phone'].forEach(n=>rForm[n].addEventListener('input', ()=>rForm[n].classList.remove('is-invalid')));
        rForm.addEventListener('submit', e=>{
            e.preventDefault();
            const name=rForm.name.value.trim(), email=rForm.email.value.trim(), phone=rForm.phone.value.trim();
            const pd=phone.replace(/[^0-9]/g,'').length;
            rForm.name.classList.toggle('is-invalid',!name); rForm.email.classList.toggle('is-invalid',!emailRe.test(email)); rForm.phone.classList.toggle('is-invalid',pd<7);
            const alertEl=document.getElementById('ppResAlert');
            if(!name||!emailRe.test(email)||pd<7){ alertEl.style.display=''; alertEl.textContent='Please complete all three fields — a valid name, email and mobile number.'; const fb=rForm.querySelector('.is-invalid'); if(fb) fb.focus(); return; }
            alertEl.style.display='none';
            const btn=document.getElementById('ppResBtn'); const lbl=btn.querySelector('.pp-res-label');
            btn.disabled=true; lbl.textContent='Sending…';
            const payload={ uuid: sessionUuid, name, email, phone, consent: document.getElementById('ppResConsent').checked?1:0 };
            if(resWantMeeting.checked && resMeetingDate.value){ payload.meeting_date=resMeetingDate.value; payload.meeting_time=document.getElementById('ppResMeetingTime').value||'10:00'; }
            fetch(URLS.contact, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json','Accept':'application/json'},
                body: JSON.stringify(payload) })
                .then(r=>r.json().then(j=>({ok:r.ok,j}))).then(res=>{
                    if(res.ok && res.j.success){ showResultsSuccess(res.j.message||'Thank you — your details are in.'); if(typeof trackConversion==='function') trackConversion('ahg_planner_contact',{}); }
                    else { alertEl.style.display=''; alertEl.textContent=(res.j.message||'Please check your details.'); btn.disabled=false; lbl.textContent='Send my details'; }
                }).catch(()=>{ alertEl.style.display=''; alertEl.textContent='Something went wrong. Please try again.'; btn.disabled=false; lbl.textContent='Send my details'; });
        });
    }

    // ── Before-flow follow-up: only consent + meeting, editing the existing lead ──
    const fuConsentWrap=document.getElementById('ppFuConsentWrap');
    const fuMeetingWrap=document.getElementById('ppFuMeetingWrap');
    const fuWantMeeting=document.getElementById('ppFuWantMeeting');
    const fuMeetingFields=document.getElementById('ppFuMeetingFields');
    const fuMeetingDate=document.getElementById('ppFuMeetingDate');
    if(fuMeetingDate) fuMeetingDate.min=new Date().toISOString().split('T')[0];
    if(fuWantMeeting) fuWantMeeting.addEventListener('change', ()=>{ fuMeetingFields.hidden=!fuWantMeeting.checked; if(fuWantMeeting.checked && fuMeetingDate && !fuMeetingDate.value){ const d=new Date(); d.setDate(d.getDate()+1); fuMeetingDate.value=d.toISOString().split('T')[0]; } });

    function showFollowupIfNeeded(){
        const needConsent = !leadConsent;
        const needMeeting = !meetingRequested;
        if(!needConsent && !needMeeting) return;          // already consented AND booked — nothing to ask
        const fu=document.getElementById('ppFollowup'); if(!fu) return;
        fuConsentWrap.style.display = needConsent ? '' : 'none';
        fuMeetingWrap.style.display = needMeeting ? '' : 'none';
        fu.style.display='';
    }

    const fuForm=document.getElementById('ppFollowupForm');
    if(fuForm){ fuForm.addEventListener('submit', e=>{
        e.preventDefault();
        const alertEl=document.getElementById('ppFuAlert');
        const consentShown = fuConsentWrap.style.display!=='none';
        const consentVal = consentShown ? (document.getElementById('ppFuConsent').checked?1:0) : 1; // preserve prior consent when hidden
        const meetingShown = fuMeetingWrap.style.display!=='none';
        const wantMeeting = meetingShown && fuWantMeeting.checked && fuMeetingDate.value;
        if(!consentVal && !wantMeeting){ alertEl.style.display=''; alertEl.textContent='Tick consent or pick a consultation time to continue.'; return; }
        alertEl.style.display='none';
        const btn=document.getElementById('ppFuBtn'); const lbl=btn.querySelector('.pp-fu-label');
        btn.disabled=true; lbl.textContent='Saving…';
        const payload={ uuid: sessionUuid, consent: consentVal };
        if(wantMeeting){ payload.meeting_date=fuMeetingDate.value; payload.meeting_time=document.getElementById('ppFuMeetingTime').value||'10:00'; }
        fetch(URLS.followup, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json','Accept':'application/json'},
            body: JSON.stringify(payload) })
            .then(r=>r.json().then(j=>({ok:r.ok,j}))).then(res=>{
                if(res.ok && res.j.success){ document.getElementById('ppFollowup').innerHTML='<div class="pp-contact-success"><i class="fa-solid fa-circle-check"></i><h4>'+escapeHtml(res.j.message)+'</h4></div>'; if(typeof trackConversion==='function') trackConversion('ahg_planner_contact',{}); }
                else { alertEl.style.display=''; alertEl.textContent=(res.j.message||'Please try again.'); btn.disabled=false; lbl.textContent='Confirm'; }
            }).catch(()=>{ alertEl.style.display=''; alertEl.textContent='Something went wrong. Please try again.'; btn.disabled=false; lbl.textContent='Confirm'; });
    }); }

    document.getElementById('ppRestart').addEventListener('click', ()=>location.reload());
    var ppRawCopy = document.getElementById('ppRawCopy');
    if (ppRawCopy) ppRawCopy.addEventListener('click', function(){
        var txt = document.getElementById('ppRaw').textContent || '';
        if (navigator.clipboard) navigator.clipboard.writeText(txt);
        var o = ppRawCopy.textContent; ppRawCopy.textContent = 'Copied'; setTimeout(()=>ppRawCopy.textContent=o, 1200);
    });
    function escapeHtml(str){ return String(str||'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
})();
</script>
@endpush
