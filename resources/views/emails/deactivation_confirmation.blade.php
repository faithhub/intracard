<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deactivated</title>
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 200px;
            margin-bottom: 10px;
        }

        .content {
            color: #333;
            line-height: 1.6;
            font-size: 16px;
        }

        h1 {
            color: #d9534f;
            text-align: center;
            margin-bottom: 25px;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #888888;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .disclaimer {
            font-size: 12px;
            color: #999;
            font-style: italic;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/logos/intracard_email.png') }}" class="logo" alt="IntraCard Logo">
        </div>
        <div class="content">
            <h1>Your Account Has Been Deactivated</h1>
            
            <p>Dear {{ $name }},</p>

            <p>We are writing to confirm that your account associated with the email address <strong>{{ $email }}</strong> has been successfully deactivated.</p>

            <p>If you did not request this deactivation, please contact our support team immediately at <a href="mailto:hello@intracard.ca">hello@intracard.ca</a>.</p>

            <p>Thank you for using our services.</p>

            <p>Best regards,<br>
                The {{ config('app.name') }} Team</p>
                
            <div class="disclaimer">
                This email was sent from a no-reply address. Please do not reply to this email.
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>