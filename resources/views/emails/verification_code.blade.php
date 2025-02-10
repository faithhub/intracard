<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            /* padding: 10px 0; */
            /* background-color: #4CAF50; */
            color: #ffffff;
            font-size: 24px;
            font-family: Kodchasan
        }
        .content {
            margin: 20px 0;
            text-align: left;
            font-size: 18px;
            text-align: justify;
            font-family: coolvetica;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #62c89a;
            text-align: center;
            font-family: Kodchasan;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #888888;
            margin-top: 20px;
        }
        .logo{
            height: 80px;
        }
        .center-text {text-align: center}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- IntraCard Verification --}}
            <img src="{{ asset('assets/logos/intracard_email.png') }}" class="logo" alt="">
        </div>
        <div class="content">
            <p>Hi {{ $name }},</p>
            <p>Thank you for signing up with IntraCard! We’re thrilled to have you onboard.</p>
            <p style="margin-bottom: 5px">To ensure the security of your account, find the unique PIN below. Please enter the PIN to your email verification on your registration page. This step helps us verify that it’s really you completing the signup process.</p>
            <div class="center-text"><b class="code">{{ $code }}</b></div>
            <p style="margin-top: 5px">If you didn’t initiate this registration or encounter any issues, please let us know right away by contacting our support team at <b style="
                color: #2e186c;
            ">support@intracard.com</b>.</p>
            <p>Thank you for choosing IntraCard. We look forward to supporting your financial journey!</p>

            <p>Best regards,<br>
            Admin<br>
            IntraCard Customer Support</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} IntraCard. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
