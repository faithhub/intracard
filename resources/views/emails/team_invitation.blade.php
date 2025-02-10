<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntraCard Invitation</title>
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

        .button-container {
            margin: 25px 0;
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .accept-button {
            background-color: #62c89a;
            color: white;
        }

        .decline-button {
            background-color: #ff4444;
            color: white;
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
            {{-- <div class="tagline">Empowering your financial journey</div> --}}
        </div>
        <div class="content">
            <p>Dear <b>{{ $details['name'] }}</b>,</p>

            <p>We're excited to invite you to join <b>{{ $details['address'] }}</b> as a {{ $details['role'] }}!</p>

            <p><b>{{ $details['admin_name'] }}</b> has listed you as an Occupant for this address, enabling you to
                actively participate in the payment of your bills and enjoy rewards linked to your credit card. As a
                {{ $details['role'] }}, you'll enjoy shared privileges and the ability to contribute to bills, view
                reports, and get rewards.</p>

            <p>Here's how to get started:</p>

            <div class="button-container">
                <a href="{{ config('app.url') }}/auth/team/{{ $details['token'] }}"
                    class="button accept-button">Accept Invitation</a>
                <a href="{{ config('app.url') }}/team/members/decline/{{ $details['token'] }}"
                    class="button decline-button">Decline Invitation</a>
            </div>

            <p>Follow the instructions to complete your setup.</p>

            <p>For security purposes, please ensure you complete the process within 72 hours. If you have any questions
                or run into any issues, feel free to contact us at <a
                    href="mailto:hello@intracard.ca">hello@intracard.ca</a>.</p>

            <p>We're thrilled to have you on board and look forward to your collaboration!</p>

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
