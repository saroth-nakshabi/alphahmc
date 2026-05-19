<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Reply — Alpha Healthcare</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body style="margin:0;padding:0;background-color:#f8fafc;font-family:'Inter', Arial, sans-serif;-webkit-font-smoothing:antialiased;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:60px 16px;">
    <tr>
        <td align="center">
            <!-- Main Content Container -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.04);border:1px solid #e2e8f0;overflow:hidden;">
                
                <!-- ── HEADER / BRANDING ── -->
                <tr>
                    <td style="padding:48px 48px 40px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <div style="font-family:'Outfit', sans-serif;font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;">Alpha Healthcare</div>
                                    <div style="font-size:11px;color:#2563eb;letter-spacing:1px;text-transform:uppercase;margin-top:2px;font-weight:700;">Consultancy Group</div>
                                </td>
                                <td align="right" style="vertical-align:top;">
                                    <span style="font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;background:#f8fafc;padding:6px 14px;border-radius:100px;border:1px solid #e2e8f0;">Official Response</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- ── GREETING ── -->
                <tr>
                    <td style="padding:0 48px 32px;">
                        <div style="font-family:'Outfit', sans-serif;font-size:28px;font-weight:600;color:#0f172a;margin-bottom:12px;letter-spacing:-0.02em;">Dear {{ $inquiry->name }},</div>
                        <p style="margin:0;font-size:15px;color:#475569;line-height:1.7;font-weight:400;">
                            Thank you for your interest in our <span style="color:#2563eb;font-weight:600;">{{ $inquiry->service->name ?? 'healthcare advisory' }}</span> services. Our executive team has reviewed your inquiry and prepared the following guidance.
                        </p>
                    </td>
                </tr>

                <!-- ── THE CONTEXT (SUBTLE QUOTE) ── -->
                <tr>
                    <td style="padding:0 48px 40px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#fcfcfc;border:1px solid #f1f5f9;border-radius:8px;">
                            <tr>
                                <td style="padding:24px;">
                                    <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;font-weight:700;">Original Inquiry</div>
                                    <div style="font-size:14px;color:#334155;line-height:1.7;font-style:italic;">
                                        "{{ $inquiry->message ?? 'Formal request for consultation.' }}"
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- ── THE RESPONSE (MAIN BODY) ── -->
                <tr>
                    <td style="padding:0 48px 50px;">
                        <div style="font-size:11px;color:#2563eb;text-transform:uppercase;letter-spacing:1px;margin-bottom:20px;font-weight:700;display:block;border-bottom:1px solid #f1f5f9;padding-bottom:8px;">Consultant Response</div>
                        <div style="font-size:16px;color:#1e293b;line-height:1.8;font-weight:400;">
                            {!! nl2br(e($replyMessage)) !!}
                        </div>
                    </td>
                </tr>

                <!-- ── SIGNATURE ── -->
                <tr>
                    <td style="padding:0 48px 60px;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="padding-right:20px;border-right:1px solid #e2e8f0;">
                                    <div style="width:48px;height:48px;background:#2563eb;border-radius:8px;color:#ffffff;text-align:center;line-height:48px;font-size:22px;font-family:'Outfit',sans-serif;font-weight:700;">A</div>
                                </td>
                                <td style="padding-left:20px;">
                                    <div style="font-size:14px;font-weight:700;color:#0f172a;font-family:'Outfit',sans-serif;">Alpha Healthcare Consultancy</div>
                                    <div style="font-size:12px;color:#64748b;margin-top:2px;">Advisory & Strategy Division</div>
                                    <a href="{{ config('app.url') }}" style="font-size:12px;color:#2563eb;text-decoration:none;font-weight:600;margin-top:6px;display:block;">Secure Portal Access &rarr;</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- ── SECONDARY FOOTER ── -->
                <tr>
                    <td style="background:#f8fafc;padding:32px 48px;border-top:1px solid #f1f5f9;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <p style="margin:0;font-size:11px;color:#94a3b8;line-height:1.6;">
                                        This is a confidential communication. Sent to <span style="color:#475569;">{{ $inquiry->email }}</span><br>
                                        Ref ID : 0x{{ strtoupper(dechex($inquiry->id ?? 1000)) }} &bull; {{ date('Y') }} Alpha Healthcare
                                    </p>
                                </td>
                                <td align="right">
                                    <div style="font-size:11px;color:#cbd5e1;text-transform:uppercase;letter-spacing:1px;">Confidential</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Unsubscribe/Legal -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin-top:24px;">
                <tr>
                    <td style="text-align:center;font-size:11px;color:#94a3b8;">
                        Alpha Healthcare Consultancy Group &middot; Care, Clarity, Commitment.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>