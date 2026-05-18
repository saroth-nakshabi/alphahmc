<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .card { background: #fff; padding: 30px; border-radius: 10px; max-width: 600px; margin: auto; }
        h2 { color: #066D77; }
        .label { font-weight: bold; color: #507b96; text-transform: uppercase; font-size: 12px; margin-bottom: 4px; }
        .value { margin-bottom: 20px; color: #333; font-size: 15px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <h2>New Contact Message</h2>
        <hr>
        <p class="label">Name</p>
        <p class="value">{{ $data['name'] }}</p>

        <p class="label">Email</p>
        <p class="value">{{ $data['email'] }}</p>

        <p class="label">Subject</p>
        <p class="value">{{ $data['subject'] }}</p>

        <p class="label">Message</p>
        <p class="value">{{ $data['message'] }}</p>

        <div class="footer">This message was sent from the Alpha HMS contact form.</div>
    </div>
</body>
</html>