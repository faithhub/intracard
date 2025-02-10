@extends('emails.layouts.reminder')

@section('content')
<p>Dear {{ $user->first_name }},</p>

<p>We hope this message finds you well. This is a friendly reminder that your {{ $paymentType }} payment of <strong>${{ $amount }}</strong> is due on <strong>{{ $dueDate }}</strong>.</p>

<p>Please ensure payment is made by the due date to avoid any late payment or non-payment. If you have already scheduled your payment, no further action is required.</p>

<p>If you have questions or need assistance, feel free to reach out to us at <a href="mailto:account@intracard.ca">account@intracard.ca</a>.</p>

<p>Thank you for your prompt attention!</p>
@endsection