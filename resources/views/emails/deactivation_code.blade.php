<!DOCTYPE html>
<html>
<head>
    <title>Account Deactivation Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #f44336;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello,</h2>
        <p>We received a request to deactivate your account. For security reasons, please use the following code to confirm your request:</p>
        <h3 style="text-align: center; color: #f44336;">{{ $code }}</h3>
        <p>If you did not request this, please ignore this email or contact our support team.</p>
        <p>Thank you,<br>The {{ config('app.name') }} Team</p>
    </div>
</body>
</html>
