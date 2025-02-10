<div class="modal-content">
    <div class="modal-body">
        <p><strong>Transaction Details</strong></p>
        <p><strong>User:</strong> {{ $transaction->user->first_name ?? 'N/A' }} {{ $transaction->user->last_name ?? 'N/A' }}</p>
        <p><strong>Card:</strong> {{ $transaction->card->name_on_card ?? 'N/A' }}</p>
        <p><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</p>
        <p><strong>Charge:</strong> ${{ number_format($transaction->charge, 2) }}</p>
        <p><strong>Type:</strong> {{ ucfirst($transaction->type) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
        <p><strong>Transaction Date:</strong> {{ $transaction->created_at->format('d M Y, h:i A') }}</p>
    </div>
</div>
