<style>
    .table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table td, .table th {
    padding: 0.9rem !important;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
</style>
<div class="card">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center p-5 bg-light">
            <div>
                <h4 class="card-title mb-0">Complete Transaction History</h4>
                <p class="text-muted mb-0">Wallet ID: {{ $wallet->uuid }}</p>
            </div>
            <div>
                <button class="btn btn-sm btn-light-seconday me-2" id="exportCSV">
                    <i class="fas fa-file-csv me-1"></i> Export CSV
                </button>
                <button class="btn btn-sm btn-light-seconday" id="printHistory">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3" id="wallet-history-table">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $index = 1;
                    @endphp
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td>
                                <span class="badge 
                                    @if($transaction['type'] == 'deposit')
                                        bg-success
                                    @elseif($transaction['type'] == 'withdrawal')
                                        bg-danger
                                    @elseif($transaction['type'] == 'payment')
                                        bg-info
                                    @elseif($transaction['type'] == 'refund')
                                        bg-warning
                                    @else
                                        bg-secondary
                                    @endif
                                    text-uppercase">
                                    {{ str_replace('_', ' ', $transaction['type']) }}
                                </span>
                            </td>
                            <td class="fw-bold">
                                @if(in_array($transaction['type'], ['deposit', 'refund']))
                                    <span class="text-success">+${{ number_format($transaction['amount'], 2) }}</span>
                                @else
                                    <span class="text-danger">-${{ number_format($transaction['amount'], 2) }}</span>
                                @endif
                            </td>
                            <td>{{ $transaction['user_name'] }}</td>
                            <td>{{ $transaction['date'] }}</td>
                            <td>
                                <span class="badge 
                                    @if($transaction['status'] == 'completed')
                                        bg-success
                                    @elseif($transaction['status'] == 'pending')
                                        bg-warning
                                    @elseif($transaction['status'] == 'failed')
                                        bg-danger
                                    @else
                                        bg-secondary
                                    @endif">
                                    {{ ucfirst($transaction['status']) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-light" 
                                        onclick="showWalletTransactionDetails('{{ json_encode($transaction) }}')">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Export CSV functionality
document.getElementById('exportCSV').addEventListener('click', function() {
    // Create CSV content
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "ID,Type,Amount,User,Date,Status\n";
    
    @foreach($transactions as $transaction)
        csvContent += "{{ $transaction['id'] }},";
        csvContent += "{{ $transaction['type'] }},";
        csvContent += "{{ $transaction['amount'] }},";
        csvContent += "\"{{ $transaction['user_name'] }}\",";
        csvContent += "\"{{ $transaction['date'] }}\",";
        csvContent += "{{ $transaction['status'] }}\n";
    @endforeach
    
    // Create download link
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "wallet_transactions_{{ date('Y-m-d') }}.csv");
    document.body.appendChild(link);
    
    // Download the CSV file
    link.click();
});

// Print functionality
document.getElementById('printHistory').addEventListener('click', function() {
    window.print();
});
</script>