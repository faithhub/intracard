{{-- resources/views/emails/layouts/reminder.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .header {
            /* background-color: #6a1b9a; */
            padding: 20px;
            text-align: center;
            /* margin-bottom: 30px; */
            border-radius: 8px 8px 0 0;
        }
        .header img {
            max-width: 200px;
            height: auto;
        }
        .content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .payment-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 25px 0;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #6b21a8;
            margin: 10px 0;
        }
        .due-date {
            color: #dc3545;
            font-weight: bold;
        }
        .contact-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            /* margin: 20px 0; */
            font-size: 14px;
        }
        .signature {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #66666685;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/logos/intracard_email.png') }}" alt="IntraCard">
    </div>

    <div class="content">
        @yield('content')

        <div class="signature">
            <p>Best regards,<br>
            Accounts Team<br>
            IntraCard</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} IntraCard. All rights reserved.<br>
            This is an automated reminder. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>