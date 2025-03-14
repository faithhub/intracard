{{-- @extends('emails.layouts.reminder')

@section('content')
<p>Dear {{ $user->first_name }},</p>

<p>We're sending this friendly reminder that your {{ $paymentType }} payment of <strong>${{ $amount }}</strong> is due on <strong>{{ $dueDate }}</strong>, just a few days away.</p>

<p>If you've already arranged payment, thank you! If not, please make the payment by the due date to avoid any disruptions.</p>

<p>Should you need assistance or wish to discuss your account, please don't hesitate to contact us at <a href="mailto:account@intracard.ca">account@intracard.ca</a>.</p>

<p>Thank you for being a valued customer!</p>
@endsection --}}


{{-- resources/views/emails/reminders/bi-weekly/followup.blade.php --}}
@extends('emails.layouts.reminder')

@section('content')
    <p>Dear {{ $user->first_name }},</p>

    @if($is_team_member ?? false)
        <p>We're sending this friendly reminder regarding your portion ({{ $percentage }}%) of the {{ strtolower($paymentType) }} payment.</p>
        
        <div class="payment-details">
            <div>Your Share:</div>
            <div class="amount">${{ $amount }}</div>
            <div>Total Amount: ${{ $total_amount }}</div>
            <div>Due Date: <span class="due-date">{{ $dueDate }}</span></div>
            <div>({{ $daysRemaining }} days remaining)</div>
        </div>
    @else
        <p>We're sending this friendly reminder that your {{ strtolower($paymentType) }} payment is due soon.</p>
        
        <div class="payment-details">
            <div>Amount Due:</div>
            <div class="amount">${{ $amount }}</div>
            <div>Due Date: <span class="due-date">{{ $dueDate }}</span></div>
            <div>({{ $daysRemaining }} days remaining)</div>
        </div>
    @endif

    <p>If you've already arranged payment, thank you! If not, please make the payment by the due date to avoid any disruptions.</p>

    <div class="contact-info">
        <p>Need assistance? We're here to help!</p>
        <p>Contact us at: <a href="mailto:account@intracard.ca">account@intracard.ca</a></p>
    </div>
@endsection