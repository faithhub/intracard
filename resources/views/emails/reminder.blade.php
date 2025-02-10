<!DOCTYPE html>
<html>
<head>
    <title>Payment Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333;">Payment Reminder</h2>

        <p>Hello {{ $notifiable->name }},</p>

        <p>This is a reminder that your <strong>{{ ucfirst($paymentSchedule->payment_type) }} payment</strong> is due soon.</p>

        <p><strong>Payment Date:</strong> {{ $paymentSchedule->payment_date }}</p>
        <p><strong>Reminder Type:</strong> {{ $daysBefore }} days before the due date.</p>

        <p>Please ensure your wallet is funded to avoid disruptions.</p>

        <a href="{{ url('/payments') }}" style="display: inline-block; margin: 10px 0; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">View Payment Details</a>

        <p style="margin-top: 20px;">Thank you for using our service!</p>
    </div>
</body>
</html>
