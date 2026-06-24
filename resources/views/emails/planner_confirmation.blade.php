@php
    /* ── Agent: prefer one from a recommended service card ────────── */
    $cardIds    = collect($cards)->pluck('id')->filter()->all();
    $emailAgent = null;
    if (count($cardIds)) {
        $emailAgent = \App\Models\Service::with('agent.user')
            ->whereIn('id', $cardIds)
            ->whereHas('agent')
            ->get()->map(fn($s) => $s->agent)->first();
    }
    if (!$emailAgent) {
        $emailAgent = \App\Models\Agent::with('user')->whereNotNull('whatsapp')->first();
    }

    $agentName     = $emailAgent?->title             ?? 'Our Consultant';
    $agentRole     = $emailAgent?->short_description ?? 'Healthcare Advisory Specialist';
    $agentEmail    = $emailAgent?->user?->email      ?? 'info@alphatsm.com';
    $agentPhone    = $emailAgent?->user?->phone      ?? '+971 4 272 4064';
    $agentWaNum    = preg_replace('/[^0-9]/', '', $emailAgent?->whatsapp ?? '97142724064');
    $agentWaLink   = 'https://wa.me/' . $agentWaNum . '?text=' . rawurlencode("Hi, I submitted a project plan via Alpha Blueprint AI and would like to discuss it.");
    $agentPhotoUrl = ($emailAgent && $emailAgent->image)
        ? asset('public/uploads/about_staff/' . $emailAgent->image) : null;

    /* ── Content ────────────────────────────────────────────────── */
    $logoUrl     = 'https://alphahmc.com/public/front-new/assets/images/AHGlogo.svg';
    $phases      = $brief['phases']  ?? [];
    $summaryText = $brief['summary'] ?? '';
    $serviceNames = collect($cards)->pluck('name')->filter()->take(6);
    $meetingFmt  = $session->meeting_at
        ? \Illuminate\Support\Carbon::parse($session->meeting_at)->format('l, d F Y · h:i A') : null;
    $regionLine  = implode(' · ', array_filter([
        $session->intent ?? null, $session->facility_type ?? null, $session->region ?? null,
    ]));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Your Healthcare Project Plan — Alpha Health Group</title>
    <!--[if mso]><noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript><![endif]-->
    <style>
        body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
        table,td{mso-table-lspace:0pt;mso-table-rspace:0pt}
        img{-ms-interpolation-mode:bicubic;border:0;display:block;outline:none;text-decoration:none}
        body{margin:0!important;padding:0!important}
        @media only screen and (max-width:620px){
            .card{width:100%!important;border-radius:0!important}
            .p48{padding-left:24px!important;padding-right:24px!important}
            .h1{font-size:22px!important}
            .btd{display:block!important;padding:0 0 8px!important}
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#EBF2F3;font-family:Arial,Helvetica,sans-serif;">

<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;color:#EBF2F3;">Your AI-generated healthcare project plan is ready. A consultant will follow up shortly. — Alpha Health Group &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;</div>

<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
<tr><td align="center" style="padding:40px 16px;">

<table class="card" width="600" cellpadding="0" cellspacing="0" border="0" role="presentation"
    style="max-width:600px;width:100%;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 16px rgba(6,109,119,.09);">

    {{-- accent bar --}}
    <tr><td style="height:4px;background:#066D77;font-size:0;line-height:0;">&nbsp;</td></tr>

    {{-- ── LOGO ROW ── --}}
    <tr>
        <td class="p48" style="padding:28px 48px 24px;background:#fff;border-bottom:1px solid #E4EDEF;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                <tr>
                    <td style="vertical-align:middle;">
                        <img src="{{ $logoUrl }}" alt="Alpha Health Group" width="150" height="auto"
                            style="display:block;max-width:150px;height:auto;" />
                    </td>
                    <td style="vertical-align:middle;text-align:right;">
                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#066D77;border:1px solid #B2D9DC;padding:4px 11px;border-radius:3px;">Blueprint AI</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ── HEADING ── --}}
    <tr>
        <td class="p48" style="padding:36px 48px 28px;background:#fff;">
            @if($regionLine)
            <p style="margin:0 0 10px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#9DB9BC;">{{ $regionLine }}</p>
            @endif
            <h1 class="h1" style="margin:0 0 14px;font-family:Georgia,'Times New Roman',Times,serif;font-size:26px;font-weight:400;color:#0A2D32;line-height:1.3;letter-spacing:-0.01em;">Your project plan is ready,<br>{{ $session->name }}.</h1>
            @if($summaryText)
            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#5A7478;line-height:1.75;">{{ $summaryText }}</p>
            @else
            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#5A7478;line-height:1.75;">Thank you for using Alpha Blueprint AI. Below is your personalised healthcare project plan — our consultants will follow up to walk through the recommendations.</p>
            @endif
        </td>
    </tr>

    {{-- ── DIVIDER ── --}}
    <tr><td class="p48" style="padding:0 48px;"><div style="height:1px;background:#E4EDEF;font-size:0;line-height:0;">&nbsp;</div></td></tr>

    {{-- ── ROADMAP ── --}}
    @if(count($phases))
    <tr>
        <td class="p48" style="padding:28px 48px 0;">
            <p style="margin:0 0 20px;font-family:Arial,Helvetica,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#9DB9BC;">Project Roadmap</p>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                @foreach($phases as $i => $phase)
                <tr>
                    <td style="width:28px;vertical-align:top;padding-top:1px;">
                        <span style="display:inline-block;width:20px;height:20px;border:1px solid #066D77;border-radius:50%;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:700;color:#066D77;line-height:18px;mso-line-height-rule:exactly;">{{ $i + 1 }}</span>
                    </td>
                    <td style="padding-left:10px;padding-bottom:18px;vertical-align:top;{{ !$loop->last ? 'border-bottom:1px solid #EEF4F5;margin-bottom:18px;' : '' }}">
                        <strong style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:13px;font-weight:700;color:#0A2D32;margin-bottom:3px;">{{ $phase['title'] ?? '' }}</strong>
                        @if(!empty($phase['detail']))
                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#7A9EA1;line-height:1.6;">{{ $phase['detail'] }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </td>
    </tr>
    @endif

    {{-- ── SERVICES ── --}}
    @if($serviceNames->count())
    <tr>
        <td class="p48" style="padding:20px 48px 0;border-top:1px solid #E4EDEF;">
            <p style="margin:0 0 12px;font-family:Arial,Helvetica,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#9DB9BC;">Recommended Services</p>
            @foreach($serviceNames as $sName)
            <span style="display:inline-block;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#066D77;padding:4px 12px;border:1px solid #C0DADD;border-radius:3px;margin:0 5px 7px 0;">{{ $sName }}</span>
            @endforeach
        </td>
    </tr>
    @endif

    {{-- ── MEETING ── --}}
    @if($meetingFmt)
    <tr>
        <td class="p48" style="padding:20px 48px 0;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                style="background:#FEFAF2;border-left:3px solid #C09B4A;border-radius:0 4px 4px 0;" role="presentation">
                <tr>
                    <td style="padding:14px 18px;">
                        <span style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C09B4A;margin-bottom:4px;">Consultation Requested</span>
                        <span style="font-family:Georgia,'Times New Roman',Times,serif;font-size:14px;color:#6B4F10;">{{ $meetingFmt }}</span>
                        <span style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#B0893A;margin-top:3px;">Our team will confirm this slot within 24 hours.</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endif

    {{-- ── BODY COPY + BUTTONS ── --}}
    <tr>
        <td class="p48" style="padding:24px 48px 32px;">
            <p style="margin:0 0 22px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#5A7478;line-height:1.75;">A specialist consultant will reach out to walk through your plan and outline next steps. To speak with us sooner, choose an option below.</p>

            <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                <tr>
                    <td class="btd" style="padding-right:10px;vertical-align:middle;">
                        <a href="{{ $agentWaLink }}" target="_blank"
                            style="display:inline-block;padding:8px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;color:#1a7a3d;text-decoration:none;border:1px solid #7DD4A0;border-radius:4px;letter-spacing:0.02em;white-space:nowrap;">
                            WhatsApp
                        </a>
                    </td>
                    <td class="btd" style="vertical-align:middle;">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $agentPhone) }}"
                            style="display:inline-block;padding:8px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;color:#066D77;text-decoration:none;border:1px solid #B2D9DC;border-radius:4px;letter-spacing:0.02em;white-space:nowrap;">
                            Call Us
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ── AGENT CARD ── --}}
    <tr>
        <td class="p48" style="padding:24px 48px;background:#F7FAFB;border-top:1px solid #E4EDEF;">
            <p style="margin:0 0 14px;font-family:Arial,Helvetica,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#9DB9BC;">Your Dedicated Consultant</p>
            <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                <tr>
                    @if($agentPhotoUrl)
                    <td style="padding-right:16px;vertical-align:middle;">
                        <img src="{{ $agentPhotoUrl }}" alt="{{ $agentName }}" width="52" height="52"
                            style="display:block;width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #D6E8EA;" />
                    </td>
                    @else
                    <td style="padding-right:16px;vertical-align:middle;">
                        <div style="width:52px;height:52px;background:#066D77;border-radius:50%;text-align:center;line-height:52px;mso-line-height-rule:exactly;font-family:Georgia,serif;font-size:20px;color:#fff;">{{ strtoupper(substr($agentName,0,1)) }}</div>
                    </td>
                    @endif
                    <td style="vertical-align:middle;">
                        <strong style="display:block;font-family:Georgia,'Times New Roman',Times,serif;font-size:15px;font-weight:700;color:#0A2D32;margin-bottom:2px;">{{ $agentName }}</strong>
                        <span style="display:block;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#9DB9BC;margin-bottom:8px;">{{ $agentRole }}</span>
                        <a href="tel:{{ preg_replace('/[^0-9+]/','',$agentPhone) }}" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;color:#066D77;text-decoration:none;margin-right:16px;">{{ $agentPhone }}</a>
                        <a href="mailto:{{ $agentEmail }}" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;color:#066D77;text-decoration:none;">{{ $agentEmail }}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ── FOOTER ── --}}
    <tr>
        <td class="p48" style="padding:22px 48px 26px;background:#0A2D32;border-radius:0 0 8px 8px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                <tr>
                    <td style="padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,.06);">
                        <strong style="font-family:Georgia,'Times New Roman',Times,serif;font-size:13px;font-weight:400;color:#fff;display:block;margin-bottom:4px;">Alpha Health Group</strong>
                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#4D7278;line-height:1.5;">1101, 11th Floor, Damas Tower, Al Maktoum Road, Dubai, UAE</span><br>
                        <a href="mailto:info@alphatsm.com" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#4D7278;text-decoration:none;">info@alphatsm.com</a>
                        <span style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#2E5055;">&nbsp;&middot;&nbsp;</span>
                        <a href="tel:+97142724064" style="font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#4D7278;text-decoration:none;">+971 4 272 4064</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:12px;">
                        <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#2E5055;line-height:1.6;">
                            This email was sent because you submitted a project plan through Alpha Blueprint AI. &copy; {{ date('Y') }} Alpha Health Group. All rights reserved. This message is confidential and intended solely for the named recipient.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>

</td></tr>
</table>
</body>
</html>
