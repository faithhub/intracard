@extends('emails.layouts.reminder')

@section('content')
<p>Dear {{ $user->first_name }},</p>

<p>We're sending this friendly reminder that your {{ $paymentType }} payment of <strong>${{ $amount }}</strong> is due on <strong>{{ $dueDate }}</strong>, just a few days away.</p>

<p>If you've already arranged payment, thank you! If not, please make the payment by the due date to avoid any disruptions.</p>

<p>Should you need assistance or wish to discuss your account, please don't hesitate to contact us at <a href="mailto:account@intracard.ca">account@intracard.ca</a>.</p>

<p>Thank you for being a valued customer!</p>
@endsection