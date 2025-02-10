<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to IntraCard</title>
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
        .content {
            color: #333;
            line-height: 1.6;
            font-size: 16px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .feature {
            padding: 10px 0;
            border-left: 3px solid #6b21a8;
            padding-left: 15px;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            background-color: #281456;
            color: white;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #666;
        }
        .social-links {
            margin-top: 20px;
            font-style: italic;
            color: #666;
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
</style>
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/logos/intracard_email.png') }}" class="logo" alt="IntraCard Logo">
            {{-- <div class="tagline">Empowering your financial journey</div> --}}
        </div>
        <div class="content">
        <h2>Hi {{ $firstName }},</h2>
        
        <p>We're thrilled to have you join the IntraCard family!</p>
        
        <p>At IntraCard, we're committed to making your financial journey seamless, secure, and rewarding. Here's what you can look forward to:</p>
        
        <div class="feature">
            <strong>Instant Card Access:</strong> Manage your payments with ease anytime, anywhere.
        </div>
        
        <div class="feature">
            <strong>Secure Transactions:</strong> Your safety is our top priority with advanced encryption and fraud protection.
        </div>
        
        <div class="feature">
            <strong>Exclusive Perks:</strong> Enjoy personalized rewards and offers tailored just for you.
        </div>

        <p>Ready to explore? Start by logging into your account here:</p>
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
        </div>

        <p>For a quick start, check out our <strong>Getting Started Guide</strong> or contact our support team anytime at <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a> if you have questions.</p>

        <p>Thank you for choosing IntraCard. We're here to empower your financial freedom every step of the way.</p>

        <p>Warm regards,<br>The IntraCard Team</p>

        <div class="social-links">
            <p><em>P.S. Follow us on social media for updates and exclusive tips!</em></p>
            <div style="margin-top: 10px;">
                <a href="https://twitter.com/intracard" style="margin: 0 10px;">Twitter</a>
                <a href="https://facebook.com/intracard" style="margin: 0 10px;">Facebook</a>
                <a href="https://linkedin.com/company/intracard" style="margin: 0 10px;">LinkedIn</a>
                <a href="https://instagram.com/intracard" style="margin: 0 10px;">Instagram</a>
            </div>
        </div>
        </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} IntraCard. All rights reserved.</p>
    </div>
</div>
</div>
</body>
</html>