<div class="modal-content">
    <div class="modal-body">
        <p><strong>Transaction Details</strong></p>
        <p><strong>User:</strong> {{ $transaction->user->first_name ?? 'N/A' }} {{ $transaction->user->last_name ?? 'N/A' }}</p>
        @if ($transaction->card)
        <p><strong>Card:</strong> {{ $transaction->card->name_on_card ?? 'N/A' }}</p>
        <p><strong>Card Number:</strong> {{ $transaction->card->card_number ?? 'N/A' }}</p>
        <p><strong>Card Expire Date:</strong> {{ $transaction->card->expiry_month.'/'.$transaction->card->expiry_year ?? 'N/A' }}</p>
        {{-- @else
        <p><strong>Card:</strong> {{ $transaction->wallet->card->name_on_card ?? 'N/A' }}</p>
        <p><strong>Card Number:</strong> {{ $transaction->wallet->card->card_number ?? 'N/A' }}</p>
        <p><strong>Card Expire Date:</strong> {{ $transaction->wallet->card->expiry_month ?? 'N/A' }}</p> --}}
        @endif
        <p><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</p>
        <p><strong>Charge:</strong> ${{ number_format($transaction->charge, 2) }}</p>
        <p><strong>Type:</strong> {{ ucfirst($transaction->type) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
        <p><strong>Transaction Date:</strong> {{ $transaction->created_at->format('d M Y, h:i A') }}</p>
    </div>
</div>
