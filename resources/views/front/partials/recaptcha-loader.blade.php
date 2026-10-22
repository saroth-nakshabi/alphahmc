{{-- reCAPTCHA v3 on demand (index cleanup brief, 22 Oct 2026): Google's script (about 1.5 s of main
     thread on mobile) loads only when someone focuses or taps a form, or opens the inquiry modal.
     Forms ask window.ahgRecaptcha.token(action) at submit; it resolves to '' after 8 s or on any
     failure, so a form always submits (the server check is unchanged and currently records only). --}}
@php $rcLoaderKey = config('services.recaptcha.site_key', ''); @endphp
<script>
(function () {
    var key = @json($rcLoaderKey);
    var loading = null;

    window.ahgRecaptcha = {
        load: function () {
            if (!key) return Promise.resolve(null);
            if (loading) return loading;
            loading = new Promise(function (resolve) {
                var settled = false;
                function finish(g) { if (!settled) { settled = true; resolve(g); } }
                window.__ahgRecaptchaReady = function () { window.grecaptcha.ready(function () { finish(window.grecaptcha); }); };
                var s = document.createElement('script');
                s.src = 'https://www.google.com/recaptcha/api.js?render=' + encodeURIComponent(key) + '&onload=__ahgRecaptchaReady';
                s.async = true;
                s.onerror = function () { finish(null); };
                document.head.appendChild(s);
            });
            return loading;
        },
        token: function (action, timeoutMs) {
            var self = this;
            return new Promise(function (resolve) {
                var timer = setTimeout(function () { resolve(''); }, timeoutMs || 8000);
                self.load().then(function (g) {
                    if (!g) { clearTimeout(timer); resolve(''); return; }
                    g.execute(key, { action: action }).then(
                        function (t) { clearTimeout(timer); resolve(t || ''); },
                        function () { clearTimeout(timer); resolve(''); }
                    );
                });
            });
        }
    };

    if (!key) return;
    function warm(e) {
        if (e.target && e.target.closest && e.target.closest('form')) window.ahgRecaptcha.load();
    }
    document.addEventListener('focusin', warm, { passive: true });
    document.addEventListener('pointerdown', warm, { passive: true });
    document.addEventListener('show.bs.modal', function (e) {
        if (e.target && e.target.id === 'inquiryModal') window.ahgRecaptcha.load();
    });
})();
</script>
