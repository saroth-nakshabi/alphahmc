@php
    /* ── Agent resolution ─────────────────────────────────────── */
    $emailAgent    = optional($inquiry->service)->agent;
    if (!$emailAgent) {
        $emailAgent = \App\Models\Agent::with('user')->whereNotNull('whatsapp')->first();
    }
    $agentName     = $emailAgent?->title              ?? 'Our Consultant';
    $agentRole     = $emailAgent?->short_description  ?? 'Healthcare Advisory Specialist';
    $agentEmail    = $emailAgent?->user?->email       ?? 'info@alphatsm.com';
    $agentPhone    = $emailAgent?->user?->phone       ?? '+971 4 272 4064';
    $agentWaNum    = preg_replace('/[^0-9]/', '', $emailAgent?->whatsapp ?? '97142724064');
    $agentWaLink   = 'https://wa.me/' . $agentWaNum  . '?text=' . rawurlencode("Hi, I submitted inquiry #AHC-{$inquiry->id} and would like a faster response.");
    $agentPhotoUrl = ($emailAgent && $emailAgent->image)
        ? asset('public/uploads/about_staff/' . $emailAgent->image)
        : null;

    /* ── Branding URLs ────────────────────────────────────────── */
    $logoUrl       = asset('public/front/assets/img/alpha-logo.png');
    $serviceName   = $inquiry->service->name ?? 'General Enquiry';
    $refId         = '#AHC-' . str_pad($inquiry->id, 5, '0', STR_PAD_LEFT);
    $dateFormatted = $inquiry->created_at->format('d F Y');
    $meetingFmt    = $inquiry->meeting_at
        ? \Illuminate\Support\Carbon::parse($inquiry->meeting_at)->format('l, d F Y · h:i A')
        : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inquiry Received — Alpha Health Group</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; display: block; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; }
        @media only screen and (max-width: 620px) {
            .email-card  { width: 100% !important; border-radius: 0 !important; }
            .pad         { padding: 28px 24px !important; }
            .pad-hero    { padding: 32px 24px !important; }
            .pad-foot    { padding: 28px 24px !important; }
            .h1          { font-size: 24px !important; }
            .btn-row td  { display: block !important; padding: 0 0 10px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#E2EEEF;font-family:Arial,Helvetica,sans-serif;">

{{-- Preheader hidden text (controls inbox snippet) --}}
<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;color:#E2EEEF;">
    Inquiry {{ $refId }} confirmed. Your consultant will be in touch within one business day. — Alpha Health Group
    &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
</div>

<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
<tr>
<td align="center" style="padding: 40px 16px;">

    {{-- ══ EMAIL CARD ══════════════════════════════════════════════ --}}
    <table class="email-card" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 4px 20px rgba(6,109,119,0.10);" role="presentation">

        {{-- TOP ACCENT BAR --}}
        <tr>
            <td style="height:5px;background-color:#066D77;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

        {{-- LOGO HEADER --}}
        <tr>
            <td class="pad" style="padding:32px 48px 28px;background-color:#ffffff;border-bottom:1px solid #D6E8EA;">
                <img src="{{ $logoUrl }}" alt="Alpha Health Group" width="130" height="auto" style="display:block;max-width:130px;height:auto;" />
            </td>
        </tr>

        {{-- HERO GREETING --}}
        <tr>
            <td class="pad-hero" style="padding:40px 48px 36px;background-color:#EDF5F6;">
                <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td>
                            <span style="display:inline-block;background-color:#066D77;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:5px 14px;border-radius:100px;margin-bottom:20px;">Inquiry Confirmed</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h1 class="h1" style="margin:0 0 12px;font-family:Georgia,'Times New Roman',Times,serif;font-size:30px;font-weight:400;color:#0A2D32;line-height:1.25;letter-spacing:-0.01em;">Thank you, {{ $inquiry->name }}.</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#5A7478;line-height:1.65;">Your inquiry about <strong style="color:#066D77;font-weight:700;">{{ $serviceName }}</strong> has been received and is now under executive review. We aim to respond within one business day.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- WHITE BODY --}}
        <tr>
            <td class="pad" style="padding:36px 48px;background-color:#ffffff;">

                {{-- Reference detail box --}}
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-left:4px solid #066D77;background-color:#F5FAFA;border-radius:0 6px 6px 0;" role="presentation">
                    <tr>
                        <td style="padding:22px 26px;">

                            {{-- Reference ID --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    <td style="padding-bottom:14px;border-bottom:1px solid #D6E8EA;">
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#7FA2A5;display:block;margin-bottom:5px;">File Reference</span>
                                        <span style="font-family:Georgia,'Times New Roman',Times,serif;font-size:20px;font-weight:700;color:#B08840;letter-spacing:0.02em;">{{ $refId }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0;border-bottom:1px solid #D6E8EA;">
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#7FA2A5;display:block;margin-bottom:4px;">Advisory Area</span>
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:600;color:#0A2D32;">{{ $serviceName }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0;{{ $meetingFmt ? 'border-bottom:1px solid #D6E8EA;' : '' }}">
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#7FA2A5;display:block;margin-bottom:4px;">Date Received</span>
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:600;color:#0A2D32;">{{ $dateFormatted }}</span>
                                    </td>
                                </tr>
                                @if($meetingFmt)
                                <tr>
                                    <td style="padding:12px 0;">
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#7FA2A5;display:block;margin-bottom:4px;">Requested Consultation</span>
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:600;color:#066D77;">{{ $meetingFmt }}</span>
                                    </td>
                                </tr>
                                @endif
                            </table>

                        </td>
                    </tr>
                </table>

                {{-- Body copy --}}
                <p style="margin:28px 0 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#5A7478;line-height:1.7;">Please keep your reference number for any follow-up correspondence. Our consultants are reviewing your requirements and will be in touch with personalised recommendations.</p>

                {{-- CTA Buttons --}}
                <table class="btn-row" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td style="padding-right:10px;padding-bottom:4px;vertical-align:middle;">
                            <a href="{{ $agentWaLink }}" target="_blank"
                                style="display:inline-block;padding:8px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;color:#1a7a3d;text-decoration:none;border:1px solid #7DD4A0;border-radius:4px;letter-spacing:0.02em;white-space:nowrap;">
                                WhatsApp
                            </a>
                        </td>
                        <td style="padding-bottom:4px;vertical-align:middle;">
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $agentPhone) }}"
                                style="display:inline-block;padding:8px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;color:#066D77;text-decoration:none;border:1px solid #B2D9DC;border-radius:4px;letter-spacing:0.02em;white-space:nowrap;">
                                Call Us
                            </a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>

        {{-- CONSULTANT CARD --}}
        <tr>
            <td style="padding:0 48px;background-color:#ffffff;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #D6E8EA;padding-top:30px;" role="presentation">
                    <tr>
                        <td style="padding:30px 0 36px;">
                            <p style="margin:0 0 18px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#7FA2A5;">Your Assigned Consultant</p>

                            <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    {{-- Photo --}}
                                    @if($agentPhotoUrl)
                                    <td style="padding-right:18px;vertical-align:top;">
                                        <img src="{{ $agentPhotoUrl }}" alt="{{ $agentName }}" width="60" height="60"
                                            style="display:block;width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid #EDF5F6;" />
                                    </td>
                                    @else
                                    <td style="padding-right:18px;vertical-align:top;">
                                        <div style="width:60px;height:60px;border-radius:50%;background-color:#066D77;display:table-cell;text-align:center;vertical-align:middle;font-family:Georgia,serif;font-size:22px;color:#ffffff;font-weight:400;">
                                            {{ strtoupper(substr($agentName, 0, 1)) }}
                                        </div>
                                    </td>
                                    @endif
                                    {{-- Details --}}
                                    <td style="vertical-align:middle;">
                                        <strong style="display:block;font-family:Georgia,'Times New Roman',Times,serif;font-size:17px;font-weight:700;color:#0A2D32;margin-bottom:3px;">{{ $agentName }}</strong>
                                        <span style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#7FA2A5;margin-bottom:10px;letter-spacing:0.02em;">{{ $agentRole }}</span>
                                        <a href="tel:{{ preg_replace('/[^0-9+]/','',$agentPhone) }}" style="font-family:Arial,Helvetica,sans-serif;font-size:13px;font-weight:600;color:#066D77;text-decoration:none;margin-right:16px;">{{ $agentPhone }}</a>
                                        <a href="mailto:{{ $agentEmail }}" style="font-family:Arial,Helvetica,sans-serif;font-size:13px;font-weight:600;color:#066D77;text-decoration:none;">{{ $agentEmail }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- FOOTER --}}
        <tr>
            <td class="pad-foot" style="padding:22px 48px 26px;background-color:#0A2D32;border-radius:0 0 10px 10px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td style="padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,0.06);">
                            <strong style="font-family:Georgia,'Times New Roman',Times,serif;font-size:13px;font-weight:400;color:#ffffff;display:block;margin-bottom:4px;">Alpha Health Group</strong>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#4D7278;line-height:1.5;">1101, 11th Floor, Damas Tower, Al Maktoum Road, Dubai, UAE</span><br>
                            <a href="mailto:info@alphatsm.com" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#4D7278;text-decoration:none;">info@alphatsm.com</a>
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#2E5055;">&nbsp;&middot;&nbsp;</span>
                            <a href="tel:+97142724064" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#4D7278;text-decoration:none;">+971 4 272 4064</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:12px;">
                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#2E5055;line-height:1.6;">
                                This is a transactional confirmation email sent in response to your inquiry submission. &copy; {{ date('Y') }} Alpha Health Group. All rights reserved. This message and any attachments are confidential and intended solely for the named recipient.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
    {{-- ══ END EMAIL CARD ══════════════════════════════════════════ --}}

</td>
</tr>
</table>

</body>
</html>
