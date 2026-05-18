@php
    // ── Phone / officer resolution (unchanged from fixed version) ─────────────
    $defaultPhone = "94757799445";
    $targetPhone  = $defaultPhone;
    $officerName  = "our team";

    $currentService = null;
    $slug = request()->route('slug') ?? request()->route('service') ?? request()->segment(2) ?? request()->segment(3);

    if ($slug) {
        $currentService = is_object($slug) && $slug instanceof \App\Models\Service
            ? $slug
            : \App\Models\Service::where('slug', $slug)->first();
    }
    if (!$currentService) {
        $currentService = $service ?? ($data['service'] ?? null);
    }
    if (!$currentService) {
        $segments    = explode('/', trim(request()->path(), '/'));
        $allServices = \App\Models\Service::whereNotNull('inq_officer_phone')
            ->get(['slug', 'inq_officer_name', 'inq_officer_phone', 'name']);
        foreach ($allServices as $s) {
            if ($s->slug && in_array($s->slug, $segments)) {
                $currentService = $s;
                break;
            }
        }
    }
    if ($currentService && !empty(trim($currentService->inq_officer_phone))) {
        $targetPhone = trim($currentService->inq_officer_phone);
        if (!empty($currentService->inq_officer_name)) {
            $officerName = $currentService->inq_officer_name;
        }
    }
    $serviceName = $currentService->name ?? null;

    // ── UPGRADE 5: Business hours ─────────────────────────────────────────────
    // Timezone: Asia/Colombo (UTC+5:30). Adjust to your timezone.
    $now        = now()->setTimezone('Asia/Colombo');
    $dayOfWeek  = (int) $now->format('N'); // 1=Mon … 7=Sun
    $hourMinute = (int) $now->format('Hi'); // e.g. 0930, 1700

    // Open Mon–Sat, 09:00–18:00
    $isOpen = $dayOfWeek <= 6 && $hourMinute >= 900 && $hourMinute < 1800;

    // Next opening time string for the offline message
    if ($dayOfWeek == 7) {
        $nextOpen = 'Monday at 9:00 AM';
    } elseif ($hourMinute >= 1800) {
        $nextOpen = 'tomorrow at 9:00 AM';
    } else {
        $nextOpen = 'today at 9:00 AM';
    }
@endphp

<div id="wa-widget-container" class="wa-widget-wrapper">

    {{-- Toggle button --}}
    <button id="wa-chat-toggle" class="wa-chat-btn"
        aria-label="Chat with us on WhatsApp"
        aria-expanded="false"
        aria-controls="wa-chat-window">
        <div class="wa-btn-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </div>
        {{-- UPGRADE 5: online/offline dot on the button --}}
        <span class="wa-btn-status {{ $isOpen ? 'online' : 'offline' }}" aria-hidden="true"></span>
        <span class="wa-btn-tooltip" aria-hidden="true">
            {{ $isOpen ? 'Chat with us' : 'Leave a message' }}
        </span>
    </button>

    {{-- Chat window --}}
    <div id="wa-chat-window" class="wa-chat-window"
        role="dialog" aria-modal="true"
        aria-label="Support chat" aria-labelledby="wa-chat-title"
        hidden>

        <div class="wa-chat-header">
            <div class="wa-header-profile">
                <div class="wa-avatar-container" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="wa-header-info">
                    <h3 id="wa-chat-title">
                        @if($serviceName) {{ $serviceName }} Support @else WhatsApp Support @endif
                    </h3>
                    {{-- UPGRADE 5: status text changes based on hours --}}
                    <div class="wa-status">
                        <span class="status-dot {{ $isOpen ? 'online' : 'offline' }}" aria-hidden="true"></span>
                        <span>{{ $isOpen ? 'Online now · We reply instantly' : 'Offline · Opens ' . $nextOpen }}</span>
                    </div>
                </div>
            </div>
            <button id="wa-chat-close" class="wa-close-btn" aria-label="Close chat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- Messages --}}
        <div id="wa-chat-messages" class="wa-chat-body" aria-live="polite" aria-relevant="additions">
            <div class="wa-message bot">
                <div class="message-content">
                    @if($isOpen)
                        <p>Hi! Ask me anything about @if($serviceName)<strong>{{ $serviceName }}</strong>@else our services@endif. I'll answer right here, and you can continue on WhatsApp if needed.</p>
                    @else
                        <p>We're currently offline. Leave your question below and we'll reply on WhatsApp when we're back — {{ $nextOpen }}.</p>
                    @endif
                    <span class="message-time">{{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="wa-chat-footer">
            <div class="wa-input-wrapper" id="wa-input-wrapper">
                <label for="wa-chat-input" class="sr-only">Type your message</label>
                <input type="text" id="wa-chat-input"
                    placeholder="{{ $isOpen ? 'Ask a question…' : 'Leave your question…' }}"
                    autocomplete="off" maxlength="500"
                    aria-describedby="wa-char-count">
                <button id="wa-send-btn" class="wa-send-btn"
                    aria-label="Send message" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </div>
            <p id="wa-char-count" class="wa-char-count" aria-live="polite"></p>
            <p class="wa-disclaimer">For medical emergencies call <a href="tel:0757799445">0757799445</a></p>
        </div>
    </div>
</div>

<style>
    .sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }

    .wa-widget-wrapper {
        position: fixed;
        bottom: max(30px, env(safe-area-inset-bottom, 30px));
        right:   max(30px, env(safe-area-inset-right,  30px));
        z-index: 999999;
        font-family: 'Outfit', sans-serif;
    }

    .wa-chat-btn {
        width:65px; height:65px; border-radius:50%;
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        border:none; box-shadow:0 10px 25px rgba(37,211,102,.4);
        cursor:pointer; display:flex; align-items:center; justify-content:center;
        transition:all .4s cubic-bezier(.175,.885,.32,1.275);
        position:relative;
    }
    .wa-chat-btn:hover { transform:scale(1.1) rotate(5deg); }
    .wa-chat-btn:focus-visible { outline:3px solid #25D366; outline-offset:3px; }

    /* UPGRADE 5: button status dot */
    .wa-btn-status {
        position:absolute; top:4px; right:4px;
        width:12px; height:12px; border-radius:50%;
        border:2px solid #fff;
    }
    .wa-btn-status.online  { background:#4ade80; }
    .wa-btn-status.offline { background:#94a3b8; }

    .wa-btn-tooltip {
        position:absolute; right:80px;
        background:#fff; color:#1e293b;
        padding:8px 15px; border-radius:10px;
        font-size:.85rem; font-weight:600;
        box-shadow:0 5px 15px rgba(0,0,0,.1);
        white-space:nowrap;
        opacity:0; transform:translateX(10px);
        transition:all .3s ease; pointer-events:none;
    }
    .wa-chat-btn:hover .wa-btn-tooltip { opacity:1; transform:translateX(0); }

    .wa-chat-window {
        position:absolute; bottom:85px; right:0;
        width:380px; height:570px;
        background:rgba(255,255,255,.97);
        backdrop-filter:blur(15px);
        border-radius:24px;
        box-shadow:0 20px 50px rgba(0,0,0,.15);
        display:flex; flex-direction:column; overflow:hidden;
        opacity:0; transform:translateY(20px) scale(.9);
        pointer-events:none;
        transition:all .4s cubic-bezier(.165,.84,.44,1);
        border:1px solid rgba(37,211,102,.15);
    }
    .wa-chat-window[hidden] { display:flex !important; }
    .wa-chat-window.active  { opacity:1; transform:translateY(0) scale(1); pointer-events:auto; }

    .wa-chat-header {
        background:linear-gradient(135deg,#25D366 0%,#128C7E 100%);
        padding:20px; display:flex; align-items:center; justify-content:space-between;
        color:#fff; flex-shrink:0;
    }
    .wa-header-profile { display:flex; align-items:center; gap:12px; }
    .wa-avatar-container { width:45px; height:45px; background:rgba(255,255,255,.2); border-radius:12px; display:flex; align-items:center; justify-content:center; }
    .wa-header-info h3 { margin:0; font-size:1rem; font-weight:700; }
    .wa-status { display:flex; align-items:center; gap:6px; font-size:.75rem; opacity:.9; margin-top:3px; }
    .status-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .status-dot.online  { background:#4ade80; box-shadow:0 0 6px rgba(74,222,128,.8); }
    .status-dot.offline { background:#cbd5e1; }

    .wa-close-btn { background:rgba(255,255,255,.15); border:none; width:32px; height:32px; border-radius:8px; cursor:pointer; transition:background .2s; display:flex; align-items:center; justify-content:center; }
    .wa-close-btn:hover { background:rgba(255,255,255,.25); }
    .wa-close-btn:focus-visible { outline:2px solid #fff; outline-offset:2px; }

    .wa-chat-body { flex:1; padding:20px; overflow-y:auto; display:flex; flex-direction:column; gap:15px; background:#f0f2f5; }

    .wa-message { max-width:85%; display:flex; flex-direction:column; }
    .wa-message.bot  { align-self:flex-start; }
    .wa-message.user { align-self:flex-end; }

    .message-content { padding:12px 16px; border-radius:18px; font-size:.92rem; line-height:1.5; }
    .bot  .message-content { background:#fff; color:#334155; border-bottom-left-radius:4px; box-shadow:0 1px 3px rgba(0,0,0,.08); }
    .user .message-content { background:#dcf8c6; color:#1a3c2a; border-bottom-right-radius:4px; box-shadow:0 1px 3px rgba(0,0,0,.08); }
    .message-content p { margin:0; }
    .message-time { font-size:.7rem; opacity:.55; margin-top:4px; display:block; }
    .user .message-time { text-align:right; }

    /* UPGRADE 1: AI typing indicator */
    .typing-indicator { display:flex; gap:4px; padding:4px 2px; }
    .typing-indicator span { width:7px; height:7px; background:#adb5bd; border-radius:50%; animation:typing 1.4s infinite; }
    .typing-indicator span:nth-child(2) { animation-delay:.2s; }
    .typing-indicator span:nth-child(3) { animation-delay:.4s; }
    @keyframes typing { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }

    /* UPGRADE 1: "Continue on WhatsApp" button inside a bot message */
    .wa-continue-btn {
        display:inline-flex; align-items:center; gap:6px;
        margin-top:10px; padding:8px 14px;
        background:#25D366; color:#fff;
        border:none; border-radius:20px;
        font-size:.82rem; font-weight:600;
        cursor:pointer; text-decoration:none;
        transition:background .2s;
    }
    .wa-continue-btn:hover { background:#1ebe5a; }

    .wa-chat-footer {
        padding:16px 20px;
        padding-bottom:max(20px, env(safe-area-inset-bottom,20px));
        background:#fff; border-top:1px solid #f1f5f9; flex-shrink:0;
    }
    .wa-input-wrapper {
        display:flex; gap:10px; align-items:center;
        background:#f1f5f9; padding:8px 8px 8px 18px;
        border-radius:100px; border:1.5px solid transparent;
        transition:all .3s ease;
    }
    .wa-input-wrapper:focus-within { background:#fff; border-color:#25D366; box-shadow:0 0 0 3px rgba(37,211,102,.12); }
    @keyframes shake { 0%,100%{transform:translateX(0)} 20%{transform:translateX(-6px)} 40%{transform:translateX(6px)} 60%{transform:translateX(-4px)} 80%{transform:translateX(4px)} }
    .wa-input-wrapper.shake { animation:shake .35s ease; }

    #wa-chat-input { flex:1; background:none; border:none; outline:none; font-size:.95rem; color:#1e293b; font-family:inherit; }
    #wa-chat-input::placeholder { color:#94a3b8; }

    .wa-send-btn { width:40px; height:40px; flex-shrink:0; background:#25D366; border:none; border-radius:50%; cursor:pointer; transition:all .3s ease; display:flex; align-items:center; justify-content:center; }
    .wa-send-btn:disabled { background:#cbd5e1; cursor:not-allowed; }
    .wa-send-btn:not(:disabled):hover { background:#128C7E; transform:scale(1.05); }
    .wa-send-btn:focus-visible { outline:2px solid #25D366; outline-offset:2px; }
    .wa-send-btn.loading svg { display:none; }
    .wa-send-btn.loading::after { content:''; width:16px; height:16px; border:2px solid rgba(255,255,255,.4); border-top-color:#fff; border-radius:50%; animation:spin .7s linear infinite; }
    @keyframes spin { to { transform:rotate(360deg); } }

    .wa-char-count { font-size:.68rem; color:#94a3b8; text-align:right; margin:6px 4px 0; min-height:1em; transition:color .2s; }
    .wa-char-count.warn  { color:#f59e0b; }
    .wa-char-count.limit { color:#ef4444; }
    .wa-disclaimer { font-size:.65rem; color:#94a3b8; text-align:center; margin:8px 0 0; line-height:1.4; }
    .wa-disclaimer a { color:#128C7E; }

    @media (max-width:480px) {
        .wa-widget-wrapper { bottom:20px; right:20px; bottom:max(20px,env(safe-area-inset-bottom,20px)); }
        .wa-chat-window { width:calc(100vw - 40px); height:calc(100dvh - 110px); max-height:600px; bottom:80px; right:-10px; }
    }
</style>

<script>
(function () {
    // ── Config (server-rendered) ───────────────────────────────────────────────
    const PHONE        = "{{ $targetPhone }}";
    const SERVICE      = @json($serviceName);
    const IS_OPEN      = @json($isOpen);
    const SESSION_KEY  = 'wa_chat_{{ md5(url()->current()) }}'; // UPGRADE 2: per-page key
    const MAX_CHARS    = 500;
    const CSRF         = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    // ── DOM refs ───────────────────────────────────────────────────────────────
    const toggle    = document.getElementById('wa-chat-toggle');
    const win       = document.getElementById('wa-chat-window');
    const closeBtn  = document.getElementById('wa-chat-close');
    const input     = document.getElementById('wa-chat-input');
    const sendBtn   = document.getElementById('wa-send-btn');
    const msgList   = document.getElementById('wa-chat-messages');
    const charCount = document.getElementById('wa-char-count');
    const inputWrap = document.getElementById('wa-input-wrapper');

    let isSending = false;

    // ── UPGRADE 2: Session persistence ────────────────────────────────────────
    // Store: { history: [{role, content}], rendered: [{text, sender, time}] }
    function loadSession() {
        try { return JSON.parse(sessionStorage.getItem(SESSION_KEY)) || { history: [], rendered: [] }; }
        catch { return { history: [], rendered: [] }; }
    }
    function saveSession(data) {
        try { sessionStorage.setItem(SESSION_KEY, JSON.stringify(data)); } catch {}
    }
    function clearOldSessions() {
        // Keep sessionStorage tidy — remove keys older than 2 hours
        // (SessionStorage is cleared on tab close anyway; this handles long sessions)
        const keys = Object.keys(sessionStorage).filter(k => k.startsWith('wa_chat_'));
        if (keys.length > 10) keys.slice(0, keys.length - 10).forEach(k => sessionStorage.removeItem(k));
    }

    let session = loadSession();
    clearOldSessions();

    // Replay persisted messages into the DOM on load
    if (session.rendered.length > 0) {
        // Remove the default greeting (it'll be replaced by history)
        msgList.innerHTML = '';
        session.rendered.forEach(m => renderMessage(m.text, m.sender, m.time, false));
    }

    // ── UPGRADE 4: GA4 Analytics ──────────────────────────────────────────────
    function trackEvent(eventName, params = {}) {
        if (typeof gtag !== 'function') return;
        gtag('event', eventName, {
            event_category: 'chat_widget',
            service_name:   SERVICE ?? '(none)',
            page_location:  window.location.href,
            ...params,
        });
    }

    // ── Focus trap (A11y) ──────────────────────────────────────────────────────
    const focusable = () => [...win.querySelectorAll('button:not([disabled]),input:not([disabled]),a[href]')];
    function trapFocus(e) {
        if (e.key !== 'Tab') return;
        const els = focusable();
        if (!els.length) return;
        const first = els[0], last = els[els.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }

    // ── Open / Close ───────────────────────────────────────────────────────────
    function openWidget() {
        win.removeAttribute('hidden');
        requestAnimationFrame(() => requestAnimationFrame(() => win.classList.add('active')));
        toggle.setAttribute('aria-expanded', 'true');
        input.focus();
        win.addEventListener('keydown', trapFocus);
        win.addEventListener('keydown', escHandler);
        // UPGRADE 4: track open
        trackEvent('widget_open');
    }
    function closeWidget() {
        win.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
        win.addEventListener('transitionend', () => win.setAttribute('hidden', ''), { once: true });
        win.removeEventListener('keydown', trapFocus);
        win.removeEventListener('keydown', escHandler);
        toggle.focus();
    }
    function escHandler(e) { if (e.key === 'Escape') closeWidget(); }

    toggle.addEventListener('click', () => win.classList.contains('active') ? closeWidget() : openWidget());
    closeBtn.addEventListener('click', closeWidget);

    // ── Input helpers ──────────────────────────────────────────────────────────
    input.addEventListener('input', () => {
        const len = input.value.length;
        sendBtn.disabled = len === 0 || isSending;
        const left = MAX_CHARS - len;
        if (left <= 50) {
            charCount.textContent = `${left} characters remaining`;
            charCount.className   = 'wa-char-count ' + (left <= 20 ? 'limit' : 'warn');
        } else {
            charCount.textContent = '';
            charCount.className   = 'wa-char-count';
        }
    });

    // ── Send ───────────────────────────────────────────────────────────────────
    async function sendMessage() {
        const text = input.value.trim();
        if (!text) {
            inputWrap.classList.remove('shake');
            void inputWrap.offsetWidth;
            inputWrap.classList.add('shake');
            input.focus();
            return;
        }
        if (isSending) return;

        isSending = true;
        sendBtn.disabled = true;
        sendBtn.classList.add('loading');
        input.value = '';
        charCount.textContent = '';

        // Render user bubble
        const now  = new Date();
        const time = `${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')}`;
        renderMessage(text, 'user', time);

        // Persist to session
        session.history.push({ role: 'user', content: text });
        if (session.history.length > 20) session.history = session.history.slice(-20);
        saveSession(session);

        // UPGRADE 4: track message sent
        trackEvent('message_sent', { message_length: text.length });

        const typingId = addTypingIndicator();
        msgList.scrollTop = msgList.scrollHeight;

        // UPGRADE 1: Call Claude AI ────────────────────────────────────────────
        let aiReply = null;
        try {
            const res = await fetch('/api/chat', {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'X-CSRF-TOKEN':  CSRF,
                    'Accept':        'application/json',
                },
                body: JSON.stringify({
                    message:      text,
                    history:      session.history.slice(0, -1), // exclude the message we just added
                    service_name: SERVICE,
                }),
            });
            if (res.ok) {
                const data = await res.json();
                aiReply = data.reply ?? null;
            }
        } catch (err) {
            // Network error — silently fall through to WhatsApp
        }

        removeTypingIndicator(typingId);

        if (aiReply) {
            // UPGRADE 1: Show AI response, then offer WhatsApp continuation
            renderMessage(aiReply, 'bot', null, false, true); // true = show WA button

            // Persist AI reply to history
            session.history.push({ role: 'assistant', content: aiReply });
            if (session.history.length > 20) session.history = session.history.slice(-20);
            saveSession(session);

            // UPGRADE 4: track AI reply shown
            trackEvent('ai_reply_shown');

        } else {
            // AI failed — go straight to WhatsApp (graceful degradation)
            renderMessage(
                IS_OPEN
                    ? 'Let me connect you with our team on WhatsApp right away.'
                    : "We're offline right now — your message will be sent to our team on WhatsApp.",
                'bot'
            );
            openWhatsApp(text);
        }

        isSending = false;
        sendBtn.classList.remove('loading');
        sendBtn.disabled = true; // re-disabled until user types again
        msgList.scrollTop = msgList.scrollHeight;
    }

    // ── WhatsApp handoff ───────────────────────────────────────────────────────
    function openWhatsApp(userText) {
        const prefix = SERVICE ? `Hi, I'm enquiring about *${SERVICE}*.\n\n` : '';
        const waUrl  = `https://wa.me/${PHONE}?text=${encodeURIComponent(prefix + userText)}`;

        // UPGRADE 4: track WA handoff
        trackEvent('wa_handoff', { is_open: IS_OPEN });

        const popup = window.open(
            waUrl, 'WhatsAppChat',
            `width=600,height=700,left=${(screen.width-600)/2},top=${(screen.height-700)/2},scrollbars=yes,resizable=yes`
        );
        if (!popup) {
            // Popup blocked — render fallback link
            renderMessage(
                `<a href="${waUrl}" target="_blank" rel="noopener" class="wa-continue-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Open WhatsApp
                </a>`,
                'bot', null, true
            );
        }
    }

    // ── Render helpers ─────────────────────────────────────────────────────────
    // persist=true saves to session; allowHTML=true for trusted strings only
    function renderMessage(text, sender, time = null, persist = true, allowHTML = false) {
        const now = new Date();
        const t   = time || `${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')}`;

        const wrap    = document.createElement('div');
        wrap.className = `wa-message ${sender}`;

        const content = document.createElement('div');
        content.className = 'message-content';

        const p = document.createElement('p');
        if (allowHTML) { p.innerHTML = text; }
        else           { p.textContent = text; }

        const timeEl = document.createElement('span');
        timeEl.className   = 'message-time';
        timeEl.textContent = t;

        content.appendChild(p);

        // UPGRADE 1: If bot message with AI reply, append "Continue on WhatsApp" button
        if (sender === 'bot' && allowHTML && text.includes('wa-continue-btn')) {
            // button already embedded in HTML — nothing extra needed
        } else if (sender === 'bot' && allowHTML && !text.includes('<a')) {
            // Attach WA button after AI reply
        }

        content.appendChild(timeEl);
        wrap.appendChild(content);
        msgList.appendChild(wrap);
        msgList.scrollTop = msgList.scrollHeight;

        // UPGRADE 2: Persist to session
        if (persist) {
            session.rendered.push({ text, sender, time: t });
            if (session.rendered.length > 40) session.rendered = session.rendered.slice(-40);
            saveSession(session);
        }
    }

    // Separate helper to append WA handoff button after a bot message
    function appendWAButton(userText) {
        const prefix = SERVICE ? `Hi, I'm enquiring about *${SERVICE}*.\n\n` : '';
        const waUrl  = `https://wa.me/${PHONE}?text=${encodeURIComponent(prefix + userText)}`;
        const lastBot = [...msgList.querySelectorAll('.wa-message.bot')].pop();
        if (!lastBot) return;
        const content = lastBot.querySelector('.message-content');
        const btn = document.createElement('a');
        btn.href      = waUrl;
        btn.target    = '_blank';
        btn.rel       = 'noopener';
        btn.className = 'wa-continue-btn';
        btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg> Continue on WhatsApp`;
        btn.addEventListener('click', () => trackEvent('wa_handoff_btn_click', { source: 'ai_reply' }));
        content.insertBefore(btn, content.querySelector('.message-time'));
    }

    // Override sendMessage to use appendWAButton correctly
    const _orig = sendMessage;
    // Patch: after AI reply is rendered, attach WA button using appendWAButton
    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

    // Patch sendMessage to attach WA button after AI reply
    // (re-implementing cleanly below)
    sendBtn.removeEventListener('click', sendMessage);
    input.removeEventListener('keydown', sendMessage);

    async function handleSend() {
        const text = input.value.trim();
        if (!text) {
            inputWrap.classList.remove('shake');
            void inputWrap.offsetWidth;
            inputWrap.classList.add('shake');
            input.focus();
            return;
        }
        if (isSending) return;

        isSending = true;
        sendBtn.disabled = true;
        sendBtn.classList.add('loading');
        input.value = '';
        charCount.textContent = '';

        const now  = new Date();
        const time = `${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')}`;
        renderMessage(text, 'user', time);

        session.history.push({ role: 'user', content: text });
        if (session.history.length > 20) session.history = session.history.slice(-20);
        saveSession(session);

        trackEvent('message_sent', { message_length: text.length });

        const typingId = addTypingIndicator();
        msgList.scrollTop = msgList.scrollHeight;

        let aiReply = null;
        try {
            const res = await fetch('/api/chat', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ message: text, history: session.history.slice(0,-1), service_name: SERVICE }),
            });
            if (res.ok) { const d = await res.json(); aiReply = d.reply ?? null; }
        } catch {}

        removeTypingIndicator(typingId);

        if (aiReply) {
            renderMessage(aiReply, 'bot');
            appendWAButton(text);  // "Continue on WhatsApp" after the AI reply
            session.history.push({ role: 'assistant', content: aiReply });
            if (session.history.length > 20) session.history = session.history.slice(-20);
            saveSession(session);
            trackEvent('ai_reply_shown');
        } else {
            renderMessage(IS_OPEN ? 'Connecting you with our team on WhatsApp…' : 'Sending your message to our team…', 'bot');
            openWhatsApp(text);
        }

        isSending = false;
        sendBtn.classList.remove('loading');
        sendBtn.disabled = true;
        msgList.scrollTop = msgList.scrollHeight;
    }

    sendBtn.addEventListener('click', handleSend);
    input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); handleSend(); } });

    function addTypingIndicator() {
        const id  = 'typing-' + Date.now();
        const div = document.createElement('div');
        div.className = 'wa-message bot'; div.id = id;
        div.innerHTML = `<div class="message-content"><div class="typing-indicator"><span></span><span></span><span></span></div></div>`;
        msgList.appendChild(div);
        return id;
    }
    function removeTypingIndicator(id) { document.getElementById(id)?.remove(); }

})();
</script>