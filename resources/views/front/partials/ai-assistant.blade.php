@php
    // A. Global default number — admin-editable in Dashboard → Settings (no code change to update).
    $defaultPhone = \App\Models\AppSetting::get('whatsapp_default_number', '97158128418');
    $targetPhone  = $defaultPhone;
    $officerName  = 'our experts';

    // B. On leader-bearing detail pages, use that page's connected leader (Inquiry Officer WhatsApp).
    //    Each of these models carries inq_officer_phone / inq_officer_name.
    $leaderRoutes = [
        'front.service'          => \App\Models\Service::class,
        'front.service-category' => \App\Models\Category::class,
        'service-packages'       => \App\Models\ServiceGroup::class,
    ];

    $currentRouteName = request()->route() ? request()->route()->getName() : null;
    $leader = null;

    if ($currentRouteName && isset($leaderRoutes[$currentRouteName])) {
        $modelClass = $leaderRoutes[$currentRouteName];
        $route      = request()->route();

        // Prefer an already-bound model instance on the route.
        foreach ($route->parameters() as $param) {
            if ($param instanceof $modelClass) {
                $leader = $param;
                break;
            }
        }

        // Otherwise resolve by the {slug} route parameter.
        if (!$leader) {
            $slug = $route->parameter('slug');
            if (is_string($slug) && $slug !== '') {
                $leader = $modelClass::where('slug', $slug)->first();
            }
        }

        // Last resort: a model object injected into the view.
        if (!$leader) {
            $leader = $service ?? $category ?? ($data['service'] ?? null);
        }
    }

    if ($leader) {
        // Prefer agent's dedicated WhatsApp number (new system).
        $_agent = $leader->agent ?? null;
        if ($_agent && !empty(trim((string) $_agent->whatsapp))) {
            $targetPhone = trim($_agent->whatsapp);
            if (!empty($_agent->title)) {
                $officerName = $_agent->title;
            }
        } elseif (!empty(trim((string) $leader->inq_officer_phone))) {
            // Legacy fallback: inq_officer_phone directly on the entity.
            $targetPhone = trim($leader->inq_officer_phone);
            if (!empty($leader->inq_officer_name)) {
                $officerName = $leader->inq_officer_name;
            }
        }
    }

    // Normalise to digits only (strip +, spaces, dashes, brackets) so wa.me links never break.
    $targetPhone = preg_replace('/\D+/', '', (string) $targetPhone);
    if ($targetPhone === '') {
        $targetPhone = preg_replace('/\D+/', '', (string) $defaultPhone) ?: '97158128418';
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
            <p class="ava-welcome-title">Alpha Health Group</p>
            <p class="ava-welcome-sub">Health Authority approved healthcare management consultancy</p>
        </div>

        <!-- Chat Body -->
        <div class="ava-body" id="avaChatBody">
            <!-- Bot intro message -->
            <div class="ava-msg ava-msg-bot" id="avaIntroMsg">
                <div class="ava-msg-avatar">α</div>
                <div class="ava-msg-bubble">
                    <p>Welcome! This chat assistant is still learning — for an instant reply, send us a <strong>WhatsApp message</strong> using the button below.</p>
                    <span class="ava-msg-time">Just now</span>
                </div>
            </div>

            <!-- Quick Action Chips -->
            <div class="ava-chips" id="avaChips">
                <button class="ava-chip" data-msg="Tell me about your services">Our Services</button>
                <button class="ava-chip" data-msg="I have a general inquiry">General Inquiry</button>
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


<link rel="stylesheet" href="{{ asset('public/front/assets/css/ai-assistant.css') }}?v=1">


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

            // Offer a REAL, user-clicked WhatsApp link. A delayed window.open() loses the
            // click's user-activation and gets stopped by popup blockers — a tap on an
            // anchor never does. wa.me links are normal navigation, so ad blockers ignore them.
            addWhatsAppCta(whatsappUrl);
        }, 1000);
    }

    function addWhatsAppCta(url) {
        const wrap = document.createElement('div');
        wrap.className = 'ava-msg ava-msg-bot';

        const av = document.createElement('div');
        av.className = 'ava-msg-avatar';
        av.textContent = 'α';

        const bubble = document.createElement('div');
        bubble.className = 'ava-msg-bubble';

        const p = document.createElement('p');
        p.textContent = 'Tap below to continue on WhatsApp:';

        const a = document.createElement('a');
        a.href = url;
        a.target = '_blank';
        a.rel = 'noopener';
        a.className = 'ava-wa-cta';
        a.style.cssText = 'display:inline-flex;align-items:center;gap:8px;margin-top:8px;background:#25D366;color:#fff;font-weight:600;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:.9rem;';
        a.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.122.553 4.116 1.522 5.847L.057 23.882l6.219-1.432A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0z" fill="#fff"/></svg> Open WhatsApp';

        const t = document.createElement('span');
        t.className = 'ava-msg-time';
        t.textContent = getTime();

        bubble.appendChild(p);
        bubble.appendChild(a);
        bubble.appendChild(t);
        wrap.appendChild(av);
        wrap.appendChild(bubble);
        chatBody.appendChild(wrap);
        scrollBottom();
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
