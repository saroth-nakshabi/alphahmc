<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Modern Typography Stack */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;600;700&display=swap');

        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b; 
            margin: 0; 
            padding: 60px 20px; 
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        .wrapper { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #ffffff;
            padding: 48px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        /* Modern Brand Header */
        .brand-header {
            text-align: left;
            margin-bottom: 40px;
            padding-bottom: 32px;
            border-bottom: 1px solid #f1f5f9;
        }

        .brand-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
            color: #0f172a;
        }

        .brand-header p {
            font-size: 13px;
            color: #2563eb; /* Royal Blue Accent */
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 600;
        }

        /* Section Styling */
        .section-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
            display: block;
        }

        .main-service {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 32px;
        }

        /* Modern Data List */
        .info-grid {
            margin-bottom: 40px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 8px 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            text-align: right;
        }

        .accent-text {
            color: #2563eb;
            text-decoration: none;
        }

        /* Message Box */
        .message-container {
            margin-top: 10px;
            padding: 24px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);
        }

        .message-text {
            font-size: 15px;
            color: #334155;
            margin: 0;
            line-height: 1.7;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        .footer strong {
            color: #475569;
            font-weight: 600;
        }

        /* Mobile */
        @media (max-width: 480px) {
            .wrapper { padding: 32px 20px; }
            .info-item { flex-direction: column; }
            .info-value { text-align: left; margin-top: 4px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="brand-header">
            <p>New Concierge Inquiry</p>
            <h1>Alpha HMC</h1>
        </div>

        <span class="section-label">Requested Service : </span>
        <div class="main-service">
            {{ $inquiry->service->name }}
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Client Name : </span>
                <span class="info-value">{{ $inquiry->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email Address : </span>
                <span class="info-value accent-text">{{ $inquiry->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Phone Number : </span>
                <span class="info-value">{{ $inquiry->phone }}</span>
            </div>
        </div>

        <span class="section-label">Consultation Notes : </span>
        <div class="message-container">
            <p class="message-text">
                "{{ $inquiry->message ?? 'The client did not leave a specific message.' }}"
            </p>
        </div>

        <div class="footer">
            Generated by <strong>ALPHA HMC CORE</strong><br>
            Reference ID : #{{ $inquiry->id ?? '000' }} &bull; {{ date('F j, Y') }}
        </div>
    </div>
</body>
</html>