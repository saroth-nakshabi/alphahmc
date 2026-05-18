@php
    // 1. Set the global default number
    $defaultPhone = "97158128418";
    $targetPhone = $defaultPhone;
    $officerName = "our experts";

    $currentService = null;

    // Only show inquiry officer phone on specific service detail routes
    // This prevents the inquiry officer phone from appearing on all pages
    $serviceRoutes = ['front.service', 'view_service'];
    $currentRouteName = request()->route() ? request()->route()->getName() : null;

    if (in_array($currentRouteName, $serviceRoutes)) {
        // Determine the current service using explicit route parameters or injected service objects only.
        $route = request()->route();
        if ($route) {
            $routeParams = $route->parameters();
            foreach ($routeParams as $param) {
                if ($param instanceof \App\Models\Service) {
                    $currentService = $param;
                    break;
                }

                if (is_string($param) && $param !== '') {
                    $currentService = \App\Models\Service::where('slug', $param)->first();
                    if ($currentService) {
                        break;
                    }
                }
            }
        }

        // Fallback to a service object passed into the view.
        if (!$currentService) {
            $currentService = $service ?? ($data['service'] ?? null);
        }
    }

    // 3. If a service is found and has an inquiry officer phone, override the default
    if ($currentService && !empty(trim($currentService->inq_officer_phone))) {
        $targetPhone = trim($currentService->inq_officer_phone);
        if (!empty($currentService->inq_officer_name)) {
            $officerName = $currentService->inq_officer_name;
        }
    }
@endphp

<!-- VIRTUAL ASSISTANT WIDGET -->
<div class="ava-widget" id="avaWidget">

    <!-- Floating Trigger Bubble -->
    <button class="ava-trigger" id="avaTrigger" aria-label="Chat with Alpha Virtual Assistant">
        <div class="ava-trigger-inner">
            <div class="ava-trigger-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.906 1.378 5.504 3.544 7.228L4.5 22l4.547-1.5A10.35 10.35 0 0012 20.486c5.523 0 10-4.145 10-9.243C22 6.145 17.523 2 12 2z" fill="currentColor"/>
                </svg>
            </div>
            <span class="ava-trigger-label">Chat with Alpha</span>
        </div>
        <span class="ava-online-dot"></span>
    </button>

    <!-- Chat Panel -->
    <div class="ava-panel" id="avaPanel" aria-hidden="true">
        <!-- Header -->
        <div class="ava-header">
            <div class="ava-header-left">
                <div class="ava-avatar">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.906 1.378 5.504 3.544 7.228L4.5 22l4.547-1.5A10.35 10.35 0 0012 20.486c5.523 0 10-4.145 10-9.243C22 6.145 17.523 2 12 2z" fill="white"/>
                    </svg>
                    <span class="ava-avatar-dot"></span>
                </div>
                <div class="ava-header-info">
                    <span class="ava-name">Alpha Virtual Assistant</span>
                    <span class="ava-status">
                        <span class="ava-status-dot"></span>
                        Online now
                    </span>
                </div>
            </div>
            <button class="ava-close" id="avaClose" aria-label="Close chat">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <!-- Welcome Banner -->
        <div class="ava-welcome-banner">
            <p class="ava-welcome-title">Welcome to Alpha</p>
            <p class="ava-welcome-sub">A Health Authority approved healthcare management consultancy</p>
        </div>

        <!-- Chat Body -->
        <div class="ava-body" id="avaChatBody">
            <!-- Bot intro message -->
            <div class="ava-msg ava-msg-bot" id="avaIntroMsg">
                <div class="ava-msg-avatar">α</div>
                <div class="ava-msg-bubble">
                    <p>Welcome to <strong>Alpha</strong>! You can chat with us about our services, general inquiries, customer feedback &amp; complaints.</p>
                    <span class="ava-msg-time">Just now</span>
                </div>
            </div>

            <!-- Quick Action Chips -->
            <div class="ava-chips" id="avaChips">
                <button class="ava-chip" data-msg="Tell me about your services">Our Services</button>
                <button class="ava-chip" data-msg="I have a general inquiry">General Inquiry</button>
                <button class="ava-chip" data-msg="I'd like to give feedback">Feedback</button>
                <button class="ava-chip" data-msg="I want to file a complaint">Complaint</button>
            </div>

            <!-- Messages will be injected here by JS -->
        </div>

        <!-- WhatsApp CTA -->
        <div class="ava-whatsapp-bar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="#25D366"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.122.553 4.116 1.522 5.847L.057 23.882l6.219-1.432A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.797 9.797 0 01-4.988-1.364l-.358-.212-3.714.856.895-3.606-.232-.372A9.779 9.779 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z" fill="#25D366"/>
            </svg>
            <span>Send an instant WhatsApp message</span>
            <a href="https://wa.me/{{ $targetPhone }}" target="_blank" class="ava-wa-btn">+{{ $targetPhone }}</a>
        </div>

        <!-- Input Area -->
        <div class="ava-footer">
            <div class="ava-input-wrap">
                <input
                    type="text"
                    id="avaInput"
                    class="ava-input"
                    placeholder="Type your message..."
                    autocomplete="off"
                    maxlength="300"
                />
                <button class="ava-send" id="avaSendBtn" aria-label="Send message">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <p class="ava-footer-note">Powered by Alpha Healthcare Consultancy</p>
        </div>
    </div>
</div>


<style>
/* ============================================================
   ALPHA VIRTUAL ASSISTANT WIDGET — STYLES
   ============================================================ */
:root {
    --ava-teal:      #066D77;
    --ava-teal-mid:  #1ea7a1;
    --ava-teal-lite: #e6f7f8;
    --ava-dark:      #0c2f2f;
    --ava-white:     #ffffff;
    --ava-shadow:    0 20px 60px rgba(6,109,119,.22), 0 4px 20px rgba(0,0,0,.10);
    --ava-radius:    20px;
    --ava-font:      'Outfit', sans-serif;
}

/* ----- Widget root ----- */
.ava-widget {
    position: fixed;
    bottom: 32px;
    right: 32px;
    z-index: 99999;
    font-family: var(--ava-font);
    display: flex;
    flex-direction: column-reverse;
    align-items: flex-end;
    gap: 14px;
    /* Only the trigger participates in layout until the panel opens */
    isolation: isolate;
}

/* ----- TRIGGER BUBBLE ----- */
.ava-trigger {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0;
    background: var(--ava-teal);
    color: var(--ava-white);
    border: none;
    border-radius: 50px;
    padding: 0;
    cursor: pointer;
    box-shadow: 0 8px 32px rgba(6,109,119,.45), 0 2px 8px rgba(0,0,0,.15);
    transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s ease;
    overflow: hidden;
}

.ava-trigger:hover {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 16px 40px rgba(6,109,119,.55);
}

.ava-trigger-inner {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 22px 12px 16px;
}

.ava-trigger-icon {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,.18);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ava-trigger-label {
    font-size: .88rem;
    font-weight: 700;
    letter-spacing: .3px;
    white-space: nowrap;
}

.ava-online-dot {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 10px;
    height: 10px;
    background: #4ade80;
    border-radius: 50%;
    border: 2px solid var(--ava-teal);
    animation: avaPulse 2s infinite;
}

@keyframes avaPulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(74,222,128,.6); }
    50%      { box-shadow: 0 0 0 6px rgba(74,222,128,0); }
}

/* ----- CHAT PANEL ----- */
.ava-panel {
    width: 370px;
    max-height: 580px;
    background: var(--ava-white);
    border-radius: var(--ava-radius);
    box-shadow: var(--ava-shadow);
    /* Removed from layout and hit-testing until opened (avoids blocking page UI) */
    display: none;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid rgba(6,109,119,.12);

    opacity: 0;
    visibility: hidden;
    transform: translateY(20px) scale(.97);
    transform-origin: bottom right;
    transition:
        opacity .35s cubic-bezier(.4,0,.2,1),
        visibility .35s,
        transform .35s cubic-bezier(.34,1.56,.64,1);
    pointer-events: none;
}

.ava-panel.ava-open {
    display: flex;
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}

/* ----- HEADER ----- */
.ava-header {
    background: linear-gradient(135deg, var(--ava-teal) 0%, #1ea7a1 100%);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.ava-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.ava-avatar {
    position: relative;
    width: 42px;
    height: 42px;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255,255,255,.4);
    flex-shrink: 0;
}

.ava-avatar-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 11px;
    height: 11px;
    background: #4ade80;
    border-radius: 50%;
    border: 2px solid var(--ava-teal);
}

.ava-header-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.ava-name {
    font-size: .95rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: .2px;
}

.ava-status {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .75rem;
    color: rgba(255,255,255,.85);
}

.ava-status-dot {
    width: 7px;
    height: 7px;
    background: #4ade80;
    border-radius: 50%;
    animation: avaPulse 2s infinite;
}

.ava-close {
    background: rgba(255,255,255,.15);
    border: none;
    color: #fff;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .25s;
    flex-shrink: 0;
}

.ava-close:hover {
    background: rgba(255,255,255,.3);
}

/* ----- WELCOME BANNER ----- */
.ava-welcome-banner {
    background: linear-gradient(135deg, #044f57 0%, var(--ava-teal) 100%);
    padding: 14px 20px;
    flex-shrink: 0;
}

.ava-welcome-title {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 2px;
}

.ava-welcome-sub {
    font-size: .78rem;
    color: rgba(255,255,255,.8);
    margin: 0;
    line-height: 1.4;
}

/* ----- CHAT BODY ----- */
.ava-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px 16px 8px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #f8fafb;
    scroll-behavior: smooth;
}

.ava-body::-webkit-scrollbar { width: 4px; }
.ava-body::-webkit-scrollbar-track { background: transparent; }
.ava-body::-webkit-scrollbar-thumb { background: rgba(6,109,119,.2); border-radius: 2px; }

/* Messages */
.ava-msg {
    display: flex;
    gap: 8px;
    align-items: flex-end;
    animation: avaMsgIn .3s ease forwards;
}

@keyframes avaMsgIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.ava-msg-avatar {
    width: 28px;
    height: 28px;
    background: var(--ava-teal);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    font-weight: 800;
    flex-shrink: 0;
    margin-bottom: 2px;
}

.ava-msg-bubble {
    max-width: 80%;
    background: #fff;
    border: 1px solid rgba(6,109,119,.1);
    border-radius: 16px 16px 16px 4px;
    padding: 10px 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}

.ava-msg-bubble p {
    font-size: .88rem;
    color: #1a1a1a;
    line-height: 1.55;
    margin: 0 0 4px;
}

.ava-msg-time {
    font-size: .68rem;
    color: #94a3b8;
    display: block;
    text-align: right;
}

/* User message */
.ava-msg-user {
    flex-direction: row-reverse;
}

.ava-msg-user .ava-msg-bubble {
    background: var(--ava-teal);
    border-color: transparent;
    border-radius: 16px 16px 4px 16px;
}

.ava-msg-user .ava-msg-bubble p {
    color: #fff;
}

.ava-msg-user .ava-msg-time {
    color: rgba(255,255,255,.65);
    text-align: left;
}

/* Typing indicator */
.ava-typing .ava-msg-bubble {
    padding: 14px 16px;
}

.ava-typing-dots {
    display: flex;
    gap: 5px;
    align-items: center;
}

.ava-typing-dots span {
    width: 7px;
    height: 7px;
    background: var(--ava-teal-mid);
    border-radius: 50%;
    animation: avaDot 1.2s ease-in-out infinite;
}

.ava-typing-dots span:nth-child(2) { animation-delay: .2s; }
.ava-typing-dots span:nth-child(3) { animation-delay: .4s; }

@keyframes avaDot {
    0%,60%,100% { transform: translateY(0); opacity:.4; }
    30%          { transform: translateY(-5px); opacity:1; }
}

/* Quick chips */
.ava-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    padding-left: 36px;
}

.ava-chip {
    background: var(--ava-white);
    border: 1.5px solid var(--ava-teal-mid);
    color: var(--ava-teal);
    font-family: var(--ava-font);
    font-size: .78rem;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 50px;
    cursor: pointer;
    transition: all .25s ease;
}

.ava-chip:hover {
    background: var(--ava-teal);
    color: #fff;
    border-color: var(--ava-teal);
    transform: translateY(-2px);
}

/* ----- WHATSAPP BAR ----- */
.ava-whatsapp-bar {
    padding: 10px 16px;
    background: #f0fdf4;
    border-top: 1px solid #d1fae5;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.ava-whatsapp-bar span {
    font-size: .75rem;
    color: #374151;
    flex: 1;
}

.ava-wa-btn {
    background: #25D366;
    color: #fff;
    text-decoration: none;
    font-size: .75rem;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 50px;
    white-space: nowrap;
    transition: background .25s;
    flex-shrink: 0;
}

.ava-wa-btn:hover {
    background: #1ebe5d;
    color: #fff;
}

/* ----- FOOTER / INPUT ----- */
.ava-footer {
    padding: 12px 14px 10px;
    background: var(--ava-white);
    border-top: 1px solid rgba(6,109,119,.08);
    flex-shrink: 0;
}

.ava-input-wrap {
    display: flex;
    gap: 8px;
    align-items: center;
    background: #f1f5f9;
    border: 1.5px solid rgba(6,109,119,.15);
    border-radius: 50px;
    padding: 6px 6px 6px 16px;
    transition: border-color .25s;
}

.ava-input-wrap:focus-within {
    border-color: var(--ava-teal-mid);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(30,167,161,.08);
}

.ava-input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    font-family: var(--ava-font);
    font-size: .88rem;
    color: #1a1a1a;
}

.ava-input::placeholder { color: #94a3b8; }

.ava-send {
    width: 36px;
    height: 36px;
    background: var(--ava-teal);
    color: #fff;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .25s, transform .2s;
}

.ava-send:hover {
    background: var(--ava-dark);
    transform: scale(1.08);
}

.ava-footer-note {
    font-size: .68rem;
    color: #94a3b8;
    text-align: center;
    margin: 7px 0 0;
}

/* ----- RESPONSIVE ----- */
@media (max-width: 480px) {
    .ava-widget { bottom: 20px; right: 14px; }
    .ava-panel  { width: calc(100vw - 28px); }
    .ava-trigger-label { display: none; }
    .ava-trigger-inner { padding: 12px; }
}
</style>


<script>
(function () {
    'use strict';

    const trigger   = document.getElementById('avaTrigger');
    const panel     = document.getElementById('avaPanel');
    const closeBtn  = document.getElementById('avaClose');
    const input     = document.getElementById('avaInput');
    const sendBtn   = document.getElementById('avaSendBtn');
    const chatBody  = document.getElementById('avaChatBody');
    const chips     = document.getElementById('avaChips');

    if (!trigger || !panel) return;

    /* ---- Canned bot responses ---- */
    const botReplies = {
        default: [
            "Thank you for reaching out to Alpha Healthcare Consultancy! How can we assist you today? Please Conduct us via WhatsApp at +{{ $targetPhone }} for immediate assistance.",
            "Our team of experts is here to help. Could you share more details about your inquiry?",
            "I'll connect you with the right specialist. In the meantime, feel free to WhatsApp us at +{{ $targetPhone }}.",
        ],
        services: "We offer a comprehensive range of healthcare management consultancy services including facility licensing, accreditation support, clinical governance, and operational excellence. Would you like details on any specific service?",
        inquiry:  "We'd be happy to help with your inquiry! Please describe your needs and our team will get back to you promptly. Alternatively, you can reach us directly on WhatsApp.",
        feedback: "We value your feedback! Please share your thoughts and we'll make sure it reaches the right team. Your input helps us improve our services.",
        complaint:"We're sorry to hear you have a complaint. Please provide details and we'll prioritise resolving this for you. You can also call us directly for urgent matters.",
    };

    function getReply(userMsg) {
        const m = userMsg.toLowerCase();
        if (m.includes('service'))   return botReplies.services;
        if (m.includes('inquiry') || m.includes('enquiry')) return botReplies.inquiry;
        if (m.includes('feedback'))  return botReplies.feedback;
        if (m.includes('complaint')) return botReplies.complaint;
        return botReplies.default[Math.floor(Math.random() * botReplies.default.length)];
    }

    function getTime() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function scrollBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function addMsg(text, isUser) {
        const wrap = document.createElement('div');
        wrap.className = 'ava-msg ' + (isUser ? 'ava-msg-user' : 'ava-msg-bot');

        if (!isUser) {
            const av = document.createElement('div');
            av.className = 'ava-msg-avatar';
            av.textContent = 'α';
            wrap.appendChild(av);
        }

        const bubble = document.createElement('div');
        bubble.className = 'ava-msg-bubble';
        const p = document.createElement('p');
        p.textContent = text;
        const t = document.createElement('span');
        t.className = 'ava-msg-time';
        t.textContent = getTime();
        bubble.appendChild(p);
        bubble.appendChild(t);
        wrap.appendChild(bubble);
        chatBody.appendChild(wrap);
        scrollBottom();
        return wrap;
    }

    function showTyping() {
        const wrap = document.createElement('div');
        wrap.className = 'ava-msg ava-msg-bot ava-typing';
        wrap.id = 'avaTyping';

        const av = document.createElement('div');
        av.className = 'ava-msg-avatar';
        av.textContent = 'α';

        const bubble = document.createElement('div');
        bubble.className = 'ava-msg-bubble';
        bubble.innerHTML = '<div class="ava-typing-dots"><span></span><span></span><span></span></div>';

        wrap.appendChild(av);
        wrap.appendChild(bubble);
        chatBody.appendChild(wrap);
        scrollBottom();
        return wrap;
    }

    function sendMessage(text) {
        if (!text.trim()) return;

        // Hide chips after first message
        if (chips) chips.style.display = 'none';

        addMsg(text, true);
        input.value = '';

        const typing = showTyping();
        
        // Prepare WhatsApp URL
        const phoneNumber = "{{ $targetPhone }}";
        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(text)}`;

        setTimeout(function () {
            typing.remove();
            
            // Show the canned bot reply
            addMsg(getReply(text), false);
            
            // Show connecting message and redirect
            setTimeout(() => {
                addMsg("Connecting you to our team on WhatsApp...", false);
                
                // Open in a POPUP window
                const width = 600;
                const height = 700;
                const left = (window.innerWidth / 2) - (width / 2);
                const top = (window.innerHeight / 2) - (height / 2);

                setTimeout(() => {
                    window.open(
                        whatsappUrl, 
                        'WhatsAppChat', 
                        `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
                    );
                }, 1000);
            }, 800);

        }, 1000);
    }

    /* ---- Toggle panel ---- */
    trigger.addEventListener('click', function () {
        const isOpen = panel.classList.contains('ava-open');
        panel.classList.toggle('ava-open', !isOpen);
        panel.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
        if (!isOpen) setTimeout(scrollBottom, 50);
    });

    closeBtn.addEventListener('click', function () {
        panel.classList.remove('ava-open');
        panel.setAttribute('aria-hidden', 'true');
    });

    /* ---- Send on button / Enter ---- */
    sendBtn.addEventListener('click', function () {
        sendMessage(input.value);
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') sendMessage(input.value);
    });

    /* ---- Quick chips ---- */
    if (chips) {
        chips.addEventListener('click', function (e) {
            const chip = e.target.closest('.ava-chip');
            if (chip) sendMessage(chip.getAttribute('data-msg'));
        });
    }
})();
</script>
