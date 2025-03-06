<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntraCard Verification Code</title>
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

        .tagline {
            color: #392F75;
            font-family: Kodchasan, Arial, sans-serif;
            font-size: 16px;
            margin-top: 5px;
        }

        .content {
            color: #333;
            line-height: 1.6;
            font-size: 16px;
        }

        .verification-code {
            text-align: center;
            margin: 25px 0;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #392F75;
            padding: 15px;
            background-color: #f4f8f9;
            border-radius: 8px;
        }

        .expiry-info {
            text-align: center;
            font-size: 14px;
            color: #888888;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #888888;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/logos/intracard_email.png') }}" class="logo" alt="IntraCard Logo">
        </div>
        <div class="content">
            <p>Hello <b>{{ $name }}</b>,</p>

            <p>You've requested to change your email address on your IntraCard account. To complete this process, please verify your email using the verification code below:</p>

            <div class="verification-code">
                {{ $code }}
            </div>

            <div class="expiry-info">
                This code will expire in 15 minutes.
            </div>

            <p>If you didn't request this change, please ignore this email or contact our support team immediately at <a href="mailto:hello@intracard.ca">hello@intracard.ca</a>.</p>

            <p>For security reasons, please do not share this code with anyone. Our team will never ask you for this code.</p>

            <p>Thank you for using IntraCard!</p>

            <p>Best regards,<br>
                IntraCard Team</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} IntraCard. All rights reserved.</p>
        </div>
    </div>
</body>

</html>