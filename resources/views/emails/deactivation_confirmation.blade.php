<!DOCTYPE html>
<html>
<head>
    <title>Account Deactivated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h1 {
            color: #d9534f;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Account Has Been Deactivated</h1>
        <p>Dear {{ $name }},</p>
        <p>We are writing to confirm that your account associated with the email address <strong>{{ $email }}</strong> has been successfully deactivated.</p>
        <p>If you did not request this deactivation, please contact our support team immediately.</p>
        <p>Thank you for using our services.</p>
        <p>Best regards,</p>
        <p>The {{ config('app.name') }} Team</p>
        <div class="footer">
            <p>This email was sent from a no-reply address. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
