{{-- WhatsApp chat window for the whole site (Saroth, 30 Oct 2026): a green button that opens a small message
     window; "Send" opens WhatsApp with the visitor's text, so the Ref code (whatsapp-ref partial) and the WhatsApp
     conversion (tracking.js) apply through the wa.me link as everywhere else. Opening the window counts nothing.
     Landing pages open the window by themselves after 5 seconds; other pages show a small "Chat with our team now"
     bubble instead (once per session), which opens the window when tapped.
     $waChat (optional, set by the page): link, title, subtitle, welcome, send_label, auto_open_ms, fallback. --}}
@php
    $wc = $waChat ?? [];
    $wcLink = $wc['link'] ?? \App\Support\WaContext::link(null, $service ?? null);
    $wcParts = parse_url($wcLink);
    parse_str($wcParts['query'] ?? '', $wcQuery);
    $wcText = (string) ($wcQuery['text'] ?? '');
    $wcAutoOpen = (int) ($wc['auto_open_ms'] ?? 0);
@endphp
<div class="ahg-wa" id="ahg-wa" data-auto-open="{{ $wcAutoOpen }}" data-teaser="{{ $wcAutoOpen ? '0' : '1' }}"
     data-fallback="{{ $wc['fallback'] ?? 'modal' }}" data-contact-url="{{ route('contact') }}">
    <div class="ahg-wa-window" id="ahg-wa-window" role="dialog" aria-modal="false" aria-labelledby="ahg-wa-title" hidden>
        <div class="ahg-wa-head">
            <span class="ahg-wa-avatar" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.4a.5.5 0 0 0 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .1-1.3c0-.1-.2-.2-.4-.3Z"/></svg></span>
            <span class="ahg-wa-head-text"><strong id="ahg-wa-title">{{ $wc['title'] ?? 'Alpha Health Group' }}</strong><small>{{ $wc['subtitle'] ?? 'Chat with our team on WhatsApp' }}</small></span>
            <button type="button" class="ahg-wa-close" id="ahg-wa-close" aria-label="Close the WhatsApp window">&times;</button>
        </div>
        <div class="ahg-wa-body">
            <p class="ahg-wa-bubble">{{ $wc['welcome'] ?? 'Hello. Tell us what you need, and our team will reply on WhatsApp.' }}</p>
        </div>
        <form class="ahg-wa-compose" id="ahg-wa-compose">
            <label class="ahg-wa-sr" for="ahg-wa-text">Your message</label>
            <textarea id="ahg-wa-text" rows="2">{{ $wcText }}</textarea>
            <a class="ahg-wa-send" id="ahg-wa-send" href="{{ $wcLink }}" target="_blank" rel="noopener" aria-label="{{ $wc['send_label'] ?? 'Send on WhatsApp' }}">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
            </a>
        </form>
        <p class="ahg-wa-fallback">No WhatsApp? <button type="button" id="ahg-wa-inquiry">Send an inquiry</button></p>
    </div>

    <div class="ahg-wa-teaser" id="ahg-wa-teaser" hidden>
        <button type="button" class="ahg-wa-teaser-open" id="ahg-wa-teaser-open">Chat with our team now</button>
        <button type="button" class="ahg-wa-teaser-close" id="ahg-wa-teaser-close" aria-label="Dismiss">&times;</button>
    </div>

    <button type="button" class="ahg-wa-button" id="ahg-wa-button" aria-expanded="false" aria-controls="ahg-wa-window" aria-label="Chat with us on WhatsApp">
        <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.4a.5.5 0 0 0 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.2 2.2 2.2 0 0 0 .1-1.3c0-.1-.2-.2-.4-.3Z"/></svg>
    </button>
</div>
<link rel="stylesheet" href="{{ asset('public/front/assets/css/wa-chat.css') }}?v={{ substr(md5_file(public_path('front/assets/css/wa-chat.css')), 0, 10) }}">
<script src="{{ asset('public/front/assets/js/wa-chat.js') }}?v={{ substr(md5_file(public_path('front/assets/js/wa-chat.js')), 0, 10) }}" defer></script>
