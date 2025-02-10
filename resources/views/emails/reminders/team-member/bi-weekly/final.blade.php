@extends('emails.layouts.reminder')

@section('content')
<p>Dear {{ $user->first_name }},</p>

<p>This is a final courtesy reminder that your team {{ $paymentType }} contribution of <strong>${{ $amount }}</strong> ({{ $percentage }}% of total team payment) is due in Two days, <strong>{{ $dueDate }}</strong>.</p>

<p>Please ensure payment is completed on time to avoid any late payment or non-payment. If you have already made the payment, kindly disregard this message.</p>

<p>We're here to assist if you have any questions or need support. You can reach us at <a href="mailto:account@intracard.ca">account@intracard.ca</a>.</p>

<p>Thank you for your attention to this matter.</p>
@endsection