{{-- Contact lines and the proposal form. It posts to the site's inquiry endpoint (honeypot, reCAPTCHA,
     server validation); landing-facility.js sends it and reports the conversion. --}}
@php
    $facilityTypes = \App\Support\LandingPageSchema::FACILITY_TYPES;
    if ($page->facility_type && !in_array($page->facility_type, $facilityTypes, true)) {
        array_unshift($facilityTypes, $page->facility_type);
    }
@endphp
{{-- CTA as on the service pages (Saroth, 30 Oct): title and subtitle on the left, Call / WhatsApp / Send an inquiry
     on the right. The form stays closed until "Send an inquiry" (or any "Request a proposal" link) opens it. --}}
<section class="lp-cta" id="contact">
    <div class="lp-wrap">
        <div class="lp-cta-row">
            <div class="lp-cta-text">
                <h2>{{ $d['heading'] }}</h2>
                @if($d['text'] !== '')<p>{{ $d['text'] }}</p>@endif
            </div>
            <div class="lp-cta-actions">
                @if($pg['phone_tel'] !== '' || $pg['phone_display'] !== '')
                <a class="lp-cta-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', $pg['phone_tel'] ?: $pg['phone_display']) }}"><i class="fas fa-phone" aria-hidden="true"></i>{{ $d['call_button'] ?: 'Call' }}</a>
                @endif
                <a class="lp-cta-btn lp-cta-btn-wa" href="{{ $waHref }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp" aria-hidden="true"></i>{{ $d['wa_button'] ?: 'WhatsApp' }}</a>
                <button class="lp-cta-btn lp-cta-btn-primary" type="button" id="lp-form-toggle" aria-expanded="false" aria-controls="lp-form-panel"><i class="fas fa-envelope" aria-hidden="true"></i>{{ $d['form_button'] ?: 'Send an inquiry' }}</button>
            </div>
        </div>

        <div class="lp-form-panel" id="lp-form-panel" hidden>
        <form class="lp-form" id="lp-form" action="{{ route('front.inquiry.submit') }}" method="POST" novalidate>
            @csrf
            <h3>{{ $d['form_heading'] }}</h3>
            <div class="lp-hp" aria-hidden="true">
                <label for="lp-website">Website</label>
                <input type="text" id="lp-website" name="website_url" tabindex="-1" autocomplete="off">
            </div>
            <input type="hidden" name="g-recaptcha-response" id="lp-recaptcha" value="">
            <input type="hidden" name="landing_page_id" value="{{ $page->id }}">
            <input type="hidden" name="source" id="lp-source" value="">

            <div class="lp-alert" id="lp-alert" role="alert" hidden></div>

            <div class="lp-field">
                <label for="lp-name">Full name <span class="lp-req" aria-hidden="true">*</span></label>
                <input id="lp-name" name="name" autocomplete="name" required aria-required="true" aria-describedby="lp-e-name" maxlength="255">
                <span class="lp-err" id="lp-e-name"></span>
            </div>
            <div class="lp-field">
                <label for="lp-phone">Mobile with country code <span class="lp-req" aria-hidden="true">*</span></label>
                <input id="lp-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" placeholder="+971 50 000 0000" required aria-required="true" aria-describedby="lp-e-phone" maxlength="20">
                <span class="lp-err" id="lp-e-phone"></span>
            </div>
            <div class="lp-field">
                <label for="lp-email">Email</label>
                <input id="lp-email" name="email" type="email" autocomplete="email" aria-describedby="lp-e-email" maxlength="255">
                <span class="lp-err" id="lp-e-email"></span>
            </div>
            <div class="lp-two">
                <div class="lp-field">
                    <label for="lp-type">Facility</label>
                    <select id="lp-type" name="facility_type">
                        @unless($page->facility_type)<option value="">Choose a facility</option>@endunless
                        @foreach($facilityTypes as $type)<option @selected($type === $page->facility_type)>{{ $type }}</option>@endforeach
                    </select>
                </div>
                <div class="lp-field">
                    <label for="lp-emirate">Emirate</label>
                    <select id="lp-emirate" name="emirate">
                        @foreach(\App\Support\LandingPageSchema::EMIRATES as $emirate)<option @selected($emirate === $page->emirate)>{{ $emirate }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="lp-field">
                <label for="lp-message">Message</label>
                <textarea id="lp-message" name="message" rows="3" aria-describedby="lp-e-message" maxlength="2000"></textarea>
                <span class="lp-err" id="lp-e-message"></span>
            </div>
            <div class="lp-field">
                <label class="lp-check">
                    <input type="checkbox" id="lp-consent" name="consent" value="1" required aria-required="true" aria-describedby="lp-e-consent">
                    <span>I agree to be contacted by Alpha Health Group about this request and accept the <a href="{{ url('/alpha-privacy-policy') }}" target="_blank" rel="noopener">privacy policy</a>. <span class="lp-req" aria-hidden="true">*</span></span>
                </label>
                <span class="lp-err" id="lp-e-consent"></span>
            </div>
            <button class="lp-btn lp-btn-primary" type="submit" id="lp-submit">{{ $d['submit_label'] }}</button>
            <div class="lp-done" id="lp-done" role="status" tabindex="-1" hidden>{{ $d['success_text'] }}</div>
            @if($d['fine_print'] !== '')<p class="lp-fine">{{ $d['fine_print'] }}</p>@endif
        </form>
        </div>
    </div>
</section>
