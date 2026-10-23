{{-- ============================================================
     Proactive WhatsApp nudge: appears once, ~10s after page load,
     on every page except Contact/About Us (those keep the "Chat
     with Alpha" widget instead — see layout-2.blade.php).
     Clicking goes straight to WhatsApp, no on-site chat step.
     ============================================================ --}}
@php
    $waDwellLink = \App\Support\WaContext::link();
@endphp

<div class="ahg-wa-dwell" id="ahgWaDwellPopup" role="dialog" aria-live="polite" aria-label="Chat with our team on WhatsApp">
    <button type="button" class="ahg-wa-dwell-close" id="ahgWaDwellClose" aria-label="Dismiss">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="11" height="11" fill="currentColor"><path d="M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z"/></svg>
    </button>
    <a href="{{ $waDwellLink }}" target="_blank" rel="noopener" class="ahg-wa-dwell-link" id="ahgWaDwellLink">
        <span class="ahg-wa-dwell-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="#fff"><path d="M12 2C6.477 2 2 6.477 2 12c0 1.895.523 3.665 1.432 5.18L2 22l4.981-1.398A9.944 9.944 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 01-4.073-1.112l-.292-.17-3.017.847.848-3.073-.186-.303A7.955 7.955 0 014 12c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8zm4.29-5.835c-.232-.116-1.373-.677-1.585-.754-.213-.077-.367-.116-.522.116-.155.232-.598.754-.733.91-.135.154-.27.174-.502.058-.232-.116-.98-.361-1.867-1.15-.69-.615-1.156-1.375-1.292-1.607-.135-.232-.015-.358.101-.474.104-.104.232-.27.348-.406.116-.135.154-.232.232-.386.077-.155.038-.29-.019-.406-.058-.116-.522-1.259-.715-1.722-.188-.45-.38-.389-.522-.396l-.444-.008a.852.852 0 00-.618.29c-.212.232-.81.79-.81 1.926 0 1.136.83 2.233.945 2.388.116.155 1.634 2.494 3.96 3.498.553.24 1.03.382 1.382.49.58.18 1.107.154 1.524.093.465-.069 1.373-.56 1.567-1.1.193-.54.193-1.004.135-1.1-.058-.096-.213-.155-.445-.27z"/></svg>
        </span>
        <span class="ahg-wa-dwell-text">
            <strong>Need help?</strong>
            Chat with our team on WhatsApp
        </span>
    </a>
</div>

<style>
    .ahg-wa-dwell {
        position: fixed; bottom: 96px; right: 28px;
        max-width: 260px;
        background: #fff; border-radius: 14px;
        box-shadow: 0 12px 36px rgba(6,38,42,0.18);
        z-index: 9989; /* just under the floating button (9990) it points at */
        opacity: 0; transform: translateY(12px) scale(0.97);
        pointer-events: none;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .ahg-wa-dwell.is-visible {
        opacity: 1; transform: translateY(0) scale(1);
        pointer-events: auto;
    }
    .ahg-wa-dwell::after {
        content: ''; position: absolute; bottom: -8px; right: 34px;
        width: 16px; height: 16px; background: #fff;
        transform: rotate(45deg);
        box-shadow: 4px 4px 8px rgba(6,38,42,0.06);
    }
    .ahg-wa-dwell-close {
        position: absolute; top: -8px; right: -8px;
        width: 22px; height: 22px; border-radius: 50%;
        background: #14252a; color: #fff; border: 2px solid #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; padding: 0; z-index: 1;
    }
    .ahg-wa-dwell-link {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 16px; text-decoration: none; position: relative;
    }
    .ahg-wa-dwell-icon {
        flex-shrink: 0; width: 38px; height: 38px; border-radius: 50%;
        background: #25D366; display: flex; align-items: center; justify-content: center;
    }
    .ahg-wa-dwell-text {
        font-family: 'Outfit', 'Outfit Fallback', 'Outfit Fallback Android', 'Inter', 'Inter Fallback', 'Inter Fallback Android', sans-serif; font-size: 0.84rem;
        line-height: 1.4; color: #14252a;
    }
    .ahg-wa-dwell-text strong { display: block; font-size: 0.88rem; }

    @media (max-width: 575px) {
        .ahg-wa-dwell { right: 16px; max-width: calc(100vw - 32px); bottom: 88px; }
        .ahg-wa-dwell::after { right: 26px; }
    }
</style>

<script>
(function () {
    var STORAGE_KEY = 'ahg_wa_popup_shown';
    var popup = document.getElementById('ahgWaDwellPopup');
    if (!popup || sessionStorage.getItem(STORAGE_KEY)) return;

    function pushEvent(name, extra) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(Object.assign({ event: name }, extra || {}));
    }

    setTimeout(function () {
        if (sessionStorage.getItem(STORAGE_KEY)) return;
        sessionStorage.setItem(STORAGE_KEY, '1');
        popup.classList.add('is-visible');
        pushEvent('ahg_wa_popup_shown');
    }, 10000);

    var closeBtn = document.getElementById('ahgWaDwellClose');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            popup.classList.remove('is-visible');
        });
    }

    var link = document.getElementById('ahgWaDwellLink');
    if (link) {
        link.addEventListener('click', function () {
            pushEvent('ahg_whatsapp_click', { source: 'dwell_popup' });
        });
    }
})();
</script>
