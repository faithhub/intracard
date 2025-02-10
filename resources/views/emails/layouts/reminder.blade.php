{{-- resources/views/emails/layouts/reminder.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #6b21a8;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/logos/intracard_email.png') }}" alt="IntraCard">
    </div>

    <div class="content">
        @yield('content')

        <p>Best regards,<br>
        Accounts Team<br>
        IntraCard</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} IntraCard. All rights reserved.</p>
    </div>
</body>
</html>