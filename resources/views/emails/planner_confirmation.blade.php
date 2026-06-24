@php
    /* ── Agent resolution ─────────────────────────────────────── */
    $emailAgent    = \App\Models\Agent::with('user')->whereNotNull('whatsapp')->first();
    $agentName     = $emailAgent?->title              ?? 'Our Consultant';
    $agentRole     = $emailAgent?->short_description  ?? 'Healthcare Advisory Specialist';
    $agentEmail    = $emailAgent?->user?->email       ?? 'info@alphatsm.com';
    $agentPhone    = $emailAgent?->user?->phone       ?? '+971 4 272 4064';
    $agentWaNum    = preg_replace('/[^0-9]/', '', $emailAgent?->whatsapp ?? '97142724064');
    $agentWaLink   = 'https://wa.me/' . $agentWaNum  . '?text=' . rawurlencode("Hi, I submitted a project plan via Alpha Blueprint AI and would like to discuss it.");
    $agentPhotoUrl = ($emailAgent && $emailAgent->image)
        ? asset('public/uploads/about_staff/' . $emailAgent->image)
        : null;

    /* ── Session data ─────────────────────────────────────────── */
    $logoUrl        = asset('public/front/assets/img/alpha-logo.png');
    $phases         = $brief['phases']     ?? [];
    $summaryText    = $brief['summary']    ?? '';
    $serviceNames   = collect($cards)->pluck('name')->filter()->take(6);
    $meetingFmt     = $session->meeting_at
        ? \Illuminate\Support\Carbon::parse($session->meeting_at)->format('l, d F Y · h:i A')
        : null;
    $regionLine     = implode(' · ', array_filter([
        $session->intent        ?? null,
        $session->facility_type ?? null,
        $session->region        ?? null,
    ]));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Your Healthcare Project Plan — Alpha Health Group</title>
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

{{-- Preheader --}}
<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;color:#E2EEEF;">
    Your AI-generated healthcare project plan is ready. A consultant will follow up personally. — Alpha Health Group
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
                <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td style="vertical-align:middle;">
                            <img src="{{ $logoUrl }}" alt="Alpha Health Group" width="130" height="auto" style="display:block;max-width:130px;height:auto;" />
                        </td>
                        <td style="vertical-align:middle;text-align:right;">
                            <span style="display:inline-block;background-color:#EDF5F6;color:#066D77;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:5px 12px;border-radius:100px;border:1px solid #B2D9DC;">Alpha Blueprint AI</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        {{-- HERO GREETING --}}
        <tr>
            <td class="pad-hero" style="padding:40px 48px 36px;background-color:#EDF5F6;">
                <h1 class="h1" style="margin:0 0 14px;font-family:Georgia,'Times New Roman',Times,serif;font-size:28px;font-weight:400;color:#0A2D32;line-height:1.25;letter-spacing:-0.01em;">Your project plan is ready, {{ $session->name }}.</h1>
                @if($summaryText)
                <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#5A7478;line-height:1.7;">{{ $summaryText }}</p>
                @else
                <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#5A7478;line-height:1.7;">Thank you for using Alpha Blueprint AI. Below is a summary of your personalised healthcare project plan. Our consultants will follow up to discuss the recommendations in detail.</p>
                @endif
                @if($regionLine)
                <p style="margin:14px 0 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#7FA2A5;letter-spacing:0.04em;text-transform:uppercase;font-weight:600;">{{ $regionLine }}</p>
                @endif
            </td>
        </tr>

        {{-- WHITE BODY --}}
        <tr>
            <td class="pad" style="padding:36px 48px;background-color:#ffffff;">

                {{-- Project phases --}}
                @if(count($phases))
                <p style="margin:0 0 18px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#7FA2A5;">Project Roadmap</p>

                <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    @foreach($phases as $i => $phase)
                    <tr>
                        <td style="padding:0 0 12px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    <td style="width:32px;vertical-align:top;padding-top:2px;">
                                        <div style="width:26px;height:26px;background-color:#066D77;border-radius:50%;text-align:center;font-family:Georgia,'Times New Roman',Times,serif;font-size:13px;font-weight:700;color:#ffffff;line-height:26px;mso-line-height-rule:exactly;">{{ $i + 1 }}</div>
                                    </td>
                                    <td style="padding-left:12px;vertical-align:top;padding-bottom:12px;{{ !$loop->last ? 'border-bottom:1px solid #D6E8EA;' : '' }}">
                                        <strong style="display:block;font-family:Georgia,'Times New Roman',Times,serif;font-size:15px;font-weight:700;color:#0A2D32;margin-bottom:4px;">{{ $phase['title'] ?? '' }}</strong>
                                        @if(!empty($phase['detail']))
                                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#5A7478;line-height:1.6;">{{ $phase['detail'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif

                {{-- Recommended services --}}
                @if($serviceNames->count())
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:28px;border-top:1px solid #D6E8EA;" role="presentation">
                    <tr>
                        <td style="padding-top:24px;">
                            <p style="margin:0 0 14px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#7FA2A5;">Recommended Services</p>
                            @foreach($serviceNames as $sName)
                            <span style="display:inline-block;background-color:#EDF5F6;color:#066D77;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;padding:5px 13px;border-radius:100px;margin:0 6px 8px 0;border:1px solid #B2D9DC;">{{ $sName }}</span>
                            @endforeach
                        </td>
                    </tr>
                </table>
                @endif

                {{-- Meeting confirmation --}}
                @if($meetingFmt)
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:24px;border-left:4px solid #B08840;background-color:#FEFAF2;border-radius:0 6px 6px 0;" role="presentation">
                    <tr>
                        <td style="padding:18px 22px;">
                            <span style="font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#B08840;display:block;margin-bottom:5px;">Consultation Request</span>
                            <span style="font-family:Georgia,'Times New Roman',Times,serif;font-size:15px;font-weight:700;color:#6B4F10;">{{ $meetingFmt }}</span>
                            <span style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#9A7D3A;margin-top:4px;">Our team will confirm this slot within 24 hours.</span>
                        </td>
                    </tr>
                </table>
                @endif

                {{-- Body copy --}}
                <p style="margin:28px 0 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#5A7478;line-height:1.7;">A specialist consultant will reach out to discuss your plan, answer questions, and outline the next steps tailored to your facility and region. You can also reach us immediately below.</p>

                {{-- CTA Buttons --}}
                <table class="btn-row" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td style="padding-right:12px;padding-bottom:4px;vertical-align:top;">
                            <a href="{{ $agentWaLink }}" target="_blank" style="display:inline-block;padding:13px 22px;background-color:#25D366;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;text-decoration:none;border-radius:6px;letter-spacing:0.01em;">
                                &#x1F4AC;&nbsp; Discuss on WhatsApp
                            </a>
                        </td>
                        <td style="padding-bottom:4px;vertical-align:top;">
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $agentPhone) }}" style="display:inline-block;padding:12px 22px;background-color:transparent;color:#066D77;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;text-decoration:none;border-radius:6px;border:2px solid #066D77;letter-spacing:0.01em;">
                                &#x1F4DE;&nbsp; Call Us
                            </a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>

        {{-- CONSULTANT CARD --}}
        <tr>
            <td style="padding:0 48px;background-color:#ffffff;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #D6E8EA;" role="presentation">
                    <tr>
                        <td style="padding:30px 0 36px;">
                            <p style="margin:0 0 18px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#7FA2A5;">Your Dedicated Consultant</p>

                            <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    @if($agentPhotoUrl)
                                    <td style="padding-right:18px;vertical-align:top;">
                                        <img src="{{ $agentPhotoUrl }}" alt="{{ $agentName }}" width="60" height="60"
                                            style="display:block;width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid #EDF5F6;" />
                                    </td>
                                    @else
                                    <td style="padding-right:18px;vertical-align:top;">
                                        <div style="width:60px;height:60px;background-color:#066D77;border-radius:50%;text-align:center;font-family:Georgia,serif;font-size:22px;color:#ffffff;line-height:60px;mso-line-height-rule:exactly;">
                                            {{ strtoupper(substr($agentName, 0, 1)) }}
                                        </div>
                                    </td>
                                    @endif
                                    <td style="vertical-align:middle;">
                                        <strong style="display:block;font-family:Georgia,'Times New Roman',Times,serif;font-size:17px;font-weight:700;color:#0A2D32;margin-bottom:3px;">{{ $agentName }}</strong>
                                        <span style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#7FA2A5;margin-bottom:10px;letter-spacing:0.02em;">{{ $agentRole }}</span>
                                        <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                            <tr>
                                                <td style="padding-right:14px;">
                                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $agentPhone) }}" style="font-family:Arial,Helvetica,sans-serif;font-size:13px;font-weight:600;color:#066D77;text-decoration:none;">
                                                        &#x1F4DE; {{ $agentPhone }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="mailto:{{ $agentEmail }}" style="font-family:Arial,Helvetica,sans-serif;font-size:13px;font-weight:600;color:#066D77;text-decoration:none;">
                                                        &#x2709; {{ $agentEmail }}
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
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
            <td class="pad-foot" style="padding:30px 48px 32px;background-color:#0A2D32;border-radius:0 0 10px 10px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td style="padding-bottom:16px;border-bottom:1px solid rgba(255,255,255,0.08);">
                            <strong style="font-family:Georgia,'Times New Roman',Times,serif;font-size:16px;font-weight:400;color:#ffffff;letter-spacing:0.02em;">Alpha Health Consultancies Group</strong>
                            <span style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#7FA2A5;margin-top:4px;">Dubai International Financial Centre (DIFC) &middot; United Arab Emirates</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:16px;">
                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#4D7278;line-height:1.6;">
                                This email was sent because you submitted a project plan through Alpha Blueprint AI. &copy; {{ date('Y') }} Alpha Health Consultancies Group. All rights reserved.<br>
                                This message is confidential and intended solely for the named recipient.
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
