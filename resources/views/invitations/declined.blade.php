<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Declined - IntraCard</title>
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            width: 200px;
            margin-bottom: 20px;
        }
        .tagline {
            color: #392F75;
            font-family: Kodchasan, Arial, sans-serif;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .message {
            color: #333;
            font-size: 18px;
            line-height: 1.6;
            margin: 20px 0;
        }
        .icon {
            width: 64px;
            height: 64px;
            margin: 20px 0;
        }
        .footer {
            color: #888;
            font-size: 14px;
            margin-top: 30px;
        }
        .home-button {
            display: inline-block;
            background-color: #392F75;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .home-button:hover {
            background-color: #2a2357;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('assets/logos/intracard_email.png') }}" alt="IntraCard Logo" class="logo">
        {{-- <div class="tagline">Empowering your financial journey</div> --}}
        
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <path d="M15 9l-6 6M9 9l6 6" />
        </svg>
        
        <div class="message">
            <h2>Invitation Declined</h2>
            <p>You have successfully declined the invitation. Thank you for letting us know.</p>
            <p>If you change your mind or received this by mistake, please contact the person who invited you to send a new invitation.</p>
        </div>
        
        <a href="{{ config('app.url') }}" class="home-button">Return to Homepage</a>
        
        <div class="footer">
            © {{ date('Y') }} IntraCard. All rights reserved.
        </div>
    </div>
</body>
</html>