{{-- resources/views/emails/reminders/monthly/initial.blade.php --}}
@extends('emails.layouts.reminder')

@section('content')
    <p>Dear {{ $user->first_name }},</p>

    @if($is_team_member ?? false)
        <p>This is a friendly reminder regarding your portion ({{ $percentage }}%) of the upcoming {{ strtolower($paymentType) }} payment.</p>
        
        <div class="payment-details">
            <div>Your Share:</div>
            <div class="amount">${{ $amount }}</div>
            <div>Total Amount: ${{ $total_amount }}</div>
            <div>Due Date: <span class="due-date">{{ $dueDate }}</span></div>
            <div>({{ $daysRemaining }} days remaining)</div>
        </div>
    @else
        <p>This is a friendly reminder regarding your upcoming {{ strtolower($paymentType) }} payment.</p>
        
        <div class="payment-details">
            <div>Amount Due:</div>
            <div class="amount">${{ $amount }}</div>
            <div>Due Date: <span class="due-date">{{ $dueDate }}</span></div>
            <div>({{ $daysRemaining }} days remaining)</div>
        </div>
    @endif

    <p>Please ensure your payment is made by the due date to avoid any late fees or service interruptions. If you've already scheduled your payment, no further action is required.</p>

    <div class="contact-info">
        <p>Need assistance? We're here to help!</p>
        <p>Contact us at: <a href="mailto:account@intracard.ca">account@intracard.ca</a></p>
    </div>
@endsection