{{-- resources/views/emails/reminders/followup.blade.php --}}
@extends('emails.layouts.reminder')

@section('content')
    <p>Dear {{ $user->first_name }},</p>

    @if($is_team_member ?? false)
        <p>We're sending this follow-up reminder regarding your portion ({{ $percentage }}%) of the {{ $frequency === 'bi-weekly' ? 'bi-weekly' : 'monthly' }} {{ strtolower($paymentType) }} payment.</p>
        
        <div class="payment-details">
            <div>Your Share:</div>
            <div class="amount">${{ $amount }}</div>
            <div>Total Amount: ${{ $total_amount }}</div>
            <div>Due Date: <span class="due-date">{{ $dueDate }}</span></div>
            <div>({{ $daysRemaining }} days remaining)</div>
        </div>
    @else
        <p>We're sending this follow-up reminder about your {{ $frequency === 'bi-weekly' ? 'bi-weekly' : 'monthly' }} {{ strtolower($paymentType) }} payment.</p>
        
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
