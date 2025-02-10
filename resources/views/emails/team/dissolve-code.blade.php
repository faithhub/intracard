<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Dissolution Code</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 200px; margin-bottom: 10px; }
        .content { color: #333; line-height: 1.6; font-size: 16px; }
        .code-container { text-align: center; margin: 25px 0; padding: 20px; background-color: #f8f9fa; border-radius: 5px; }
        .code { font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #392F75; }
        .footer { text-align: center; font-size: 14px; color: #888888; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/logos/intracard_email.png') }}" class="logo" alt="Logo">
        </div>
        <div class="content">
            <p>Dear <b>{{ Auth::user()->first_name }}</b>,</p>
            <p>You have requested to dissolve your team. To proceed, please use this verification code:</p>
            <div class="code-container">
                <div class="code">{{ $code }}</div>
            </div>
            <p><strong>Note:</strong> This code will expire in 30 minutes.</p>
            <p>This action cannot be undone. All team members will be notified and the team will be permanently dissolved.</p>
            <p>Best regards,<br>Admin</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>