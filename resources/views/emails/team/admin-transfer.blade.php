<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Role Transfer</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 200px; margin-bottom: 10px; }
        .content { color: #333; line-height: 1.6; font-size: 16px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #392F75; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; font-size: 14px; color: #888888; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/logos/intracard_email.png') }}" class="logo" alt="Logo">
        </div>
        <div class="content">
            <p>Dear <b>{{ $teamMember->user->first_name }}</b>,</p>
            <p>You have been promoted to admin of the team "{{ $teamMember->team->name }}".</p>
            <div class="features">
                <p>As team admin, you can:</p>
                <ul>
                    <li>Manage team members</li>
                    <li>Adjust contribution percentages</li>
                    <li>Update team settings</li>
                </ul>
            </div>
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/team" class="button">View Team Dashboard</a>
            </div>
            <p>Best regards,<br>Admin</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>