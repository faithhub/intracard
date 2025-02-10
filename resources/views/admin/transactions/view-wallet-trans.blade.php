<div class="modal-content">
    <div class="modal-body">
        <p><strong>Wallet Transaction Details</p>
        <p><strong>User:</strong> {{ $transaction->user->name ?? 'N/A' }}</p>
        <p><strong>Wallet:</strong> {{ $transaction->wallet->uuid ?? 'N/A' }}</p>
        <p><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</p>
        <p><strong>Charge:</strong> ${{ number_format($transaction->charge, 2) }}</p>
        <p><strong>Charge:</strong> ${{ number_format($transaction->charge, 2) }}</p>
        <p><strong>Type:</strong> {{ ucfirst($transaction->type) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
        <p><strong>Details:</strong> {{ $transaction->details ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ $transaction->created_at->format('d M Y, h:i A') }}</p>
    </div>
</div>
