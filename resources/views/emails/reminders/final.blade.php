{{-- resources/views/emails/reminders/final.blade.php --}}
@extends('emails.layouts.reminder')

@section('content')
    <p>Dear {{ $user->first_name }},</p>

    @if($is_team_member ?? false)
        <p>This is a final reminder regarding your portion ({{ $percentage }}%) of the {{ $frequency === 'bi-weekly' ? 'bi-weekly' : 'monthly' }} {{ strtolower($paymentType) }} payment.</p>
        
        <div class="payment-details">
            <div>Your Share:</div>
            <div class="amount">${{ $amount }}</div>
            <div>Total Amount: ${{ $total_amount }}</div>
            <div>Due Date: <span class="due-date">{{ $dueDate }}</span></div>
            <div>(Final Notice - Due in {{ $daysRemaining }} days)</div>
        </div>
    @else
        <p>This is a final reminder that your {{ $frequency === 'bi-weekly' ? 'bi-weekly' : 'monthly' }} {{ strtolower($paymentType) }} payment is due in two days.</p>
        
        <div class="payment-details">
            <div>Amount Due:</div>
            <div class="amount">${{ $amount }}</div>
            <div>Due Date: <span class="due-date">{{ $dueDate }}</span></div>
            <div>(Final Notice - Due in {{ $daysRemaining }} days)</div>
        </div>
    @endif

    <p>Please ensure payment is completed on time to avoid any late payment or non-payment. If you have already made the payment, kindly disregard this message.</p>

    <div class="contact-info">
        <p>Need immediate assistance?</p>
        <p>Contact us at: <a href="mailto:account@intracard.ca">account@intracard.ca</a></p>
    </div>
@endsection