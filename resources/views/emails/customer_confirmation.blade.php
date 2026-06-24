<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            line-height: 1.6; 
            color: #334155; 
            background-color: #f8fafc; 
            margin: 0; 
            padding: 60px 10px; 
            -webkit-font-smoothing: antialiased;
        }

        .wrapper { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #ffffff; 
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        /* Modern Professional Header */
        .header-section { 
            padding: 60px 50px 40px; 
            text-align: left;
            background: linear-gradient(to bottom, #f8fafc, #ffffff);
            border-bottom: 1px solid #f1f5f9;
        }

        .brand-name {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #2563eb; /* Royal Blue */
            font-weight: 700;
            display: block;
            margin-bottom: 16px;
        }

        h1 { 
            font-family: 'Outfit', sans-serif;
            color: #0f172a; 
            margin: 0; 
            font-size: 32px; 
            font-weight: 700; 
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .main-body { 
            padding: 40px 50px 50px; 
        }

        .greeting { 
            font-family: 'Outfit', sans-serif;
            font-size: 20px; 
            font-weight: 600;
            color: #0f172a; 
            margin-bottom: 16px; 
        }

        .intro-text { 
            font-size: 15px; 
            color: #475569; 
            margin-bottom: 32px; 
            font-weight: 400;
            line-height: 1.7;
        }

        /* Modern Info Box */
        .info-ledger { 
            background: #f8fafc;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 40px;
            border: 1px solid #e2e8f0;
        }

        .ledger-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .ledger-row:last-child {
            border-bottom: none;
        }

        .ledger-label { 
            font-size: 12px; 
            color: #64748b; 
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .ledger-value { 
            font-size: 13px; 
            color: #0f172a; 
            font-weight: 600; 
        }

        /* Action Button */
        .action-area { 
            text-align: left; 
            margin-top: 32px; 
        }
        
        .primary-btn { 
            display: inline-block; 
            padding: 14px 32px; 
            background: #2563eb; 
            color: #ffffff !important; 
            text-decoration: none; 
            font-size: 14px;
            border-radius: 6px;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .footer { 
            margin-top: 40px; 
            font-size: 12px; 
            color: #64748b; 
            text-align: left; 
            padding: 0 50px;
        }

        .signature {
            margin-top: 40px;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            color: #0f172a;
            font-weight: 600;
        }

        /* Responsive */
        @media only screen and (max-width: 480px) {
            .header-section, .main-body, .footer { padding: 32px 24px; }
            h1 { font-size: 26px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header-section">
            <span class="brand-name">Alpha Healthcare Consultancy</span>
            <h1>Commitment to <br>Clinical Excellence.</h1>
        </div>
        
        <div class="main-body">
            <div class="greeting">Dear {{ $inquiry->name }},</div>
            
            <p class="intro-text">
                We acknowledge receipt of your inquiry regarding <strong>{{ $inquiry->service->name ?? 'General enquiry' }}</strong>. Alpha HMC maintains a rigorous standard for all advisory partnerships; your request has been prioritized for executive review.
            </p>

            <div class="info-ledger">
                <div class="ledger-row">
                    <span class="ledger-label">File Reference ID : </span>
                    <span class="ledger-value">#AHC-{{ $inquiry->id }}</span>
                </div>
                <div class="ledger-row">
                    <span class="ledger-label">Advisory Sector : </span>
                    <span class="ledger-value">{{ $inquiry->service->name ?? 'General enquiry' }}</span>
                </div>
                <div class="ledger-row">
                    <span class="ledger-label">Date Recorded : </span>
                    <span class="ledger-value">{{ $inquiry->created_at->format('d F Y') }}</span>
                </div>
                @if($inquiry->meeting_at)
                <div class="ledger-row">
                    <span class="ledger-label">Requested Consultation : </span>
                    <span class="ledger-value">{{ \Illuminate\Support\Carbon::parse($inquiry->meeting_at)->format('l, d F Y · h:i A') }}</span>
                </div>
                @endif
            </div>

            <p class="intro-text" style="font-style: italic;">
                Our consultants will reach out within one business day. For urgent medical-legal or operational inquiries, please contact our regional office.
            </p>

            @php
                $emailWaAgent = \App\Models\Agent::with('user')
                    ->whereHas('user', fn($q) => $q->whereNotNull('phone')->where('phone','!=',''))
                    ->first();
                $emailWaPhone = $emailWaAgent && $emailWaAgent->user
                    ? preg_replace('/[^0-9]/', '', $emailWaAgent->user->phone)
                    : '97142724064';
                $emailWaLink  = 'https://wa.me/' . $emailWaPhone . '?text=' . rawurlencode("Hi, I submitted inquiry #AHC-{$inquiry->id} and would like a faster response.");
            @endphp
            <div class="action-area">
                <a href="{{ route('home') }}" class="primary-btn">Review Client Dashboard</a>
                <a href="{{ $emailWaLink }}"
                   style="display:inline-block;margin-top:12px;background:#25D366;color:#fff;font-family:'Outfit',sans-serif;font-size:15px;font-weight:700;padding:14px 28px;border-radius:100px;text-decoration:none;box-shadow:0 6px 18px rgba(37,211,102,.35);">
                    &#128241; Chat on WhatsApp for a faster reply
                </a>
            </div>

            <div class="signature">
                The Executive Team,<br>
                Alpha Health
            </div>
        </div>
    </div>

    <div class="footer">
        <strong>ALPHA HEALTH CONSULTANCIES GROUP</strong><br>
        Dubai International Financial Centre (DIFC) • UAE<br>
        <div style="margin-top: 15px; opacity: 0.5; border-top: 1px solid #e2e8f0; padding-top: 15px;">
            © {{ date('Y') }}. Confidential & Proprietary Advisory Communication.
        </div>
    </div>
</body>
</html>