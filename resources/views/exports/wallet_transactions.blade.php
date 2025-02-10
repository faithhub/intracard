<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Transactions Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f9f9f9;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            color: #210035;
            font-weight: bold;
        }
        .header p {
            font-size: 16px;
            color: #555;
            margin: 5px 0 15px;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
            word-wrap: break-word;
        }
        .table th {
            background: #210035;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }
        .table tbody tr:nth-child(even) {
            background: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        .footer p {
            margin: 5px 0;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 150px;
            color: rgba(33, 0, 53, 0.05); /* Intracard purple with low opacity */
            z-index: -1;
            text-align: center;
            font-weight: bold;
            white-space: nowrap;
        }
        /* Add these styles to your existing CSS */
        .table-container {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fixed layout for better PDF rendering */
        }
        
        .table th {
            font-size: 10px; /* Smaller font for headers */
            padding: 5px;
            background-color: #210035;
            color: white;
            word-wrap: break-word;
        }
        
        .table td {
            font-size: 9px; /* Smaller font for content */
            padding: 5px;
            word-wrap: break-word; /* Allow text to wrap */
            vertical-align: top; /* Align content to top */
        }
        
        /* Define specific column widths */
        .table th:nth-child(1), .table td:nth-child(1) { width: 5%; }  /* S/N */
        .table th:nth-child(2), .table td:nth-child(2) { width: 15%; } /* User */
        .table th:nth-child(3), .table td:nth-child(3) { width: 20%; } /* Wallet */
        .table th:nth-child(4), .table td:nth-child(4) { width: 8%; }  /* Amount */
        .table th:nth-child(5), .table td:nth-child(5) { width: 8%; }  /* Charge */
        .table th:nth-child(6), .table td:nth-child(6) { width: 10%; } /* Type */
        .table th:nth-child(7), .table td:nth-child(7) { width: 8%; }  /* Status */
        .table th:nth-child(8), .table td:nth-child(8) { width: 18%; } /* Details */
        .table th:nth-child(9), .table td:nth-child(9) { width: 8%; }  /* Date */
        
        /* Ensure page breaks don't cut rows */
        .table tr {
            page-break-inside: avoid;
        }
        
        @page {
            margin: 0.5cm;
            size: landscape;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Watermark -->
        <div class="watermark">INTRACARD</div>

        <!-- Header -->
        <div class="header">
            <h1>Wallet Transactions Report</h1>
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
        </div>

        <!-- Transactions Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>User</th>
                        <th>Wallet</th>
                        <th>Amount</th>
                        <th>Charge</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Details</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $transaction)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @isset($transaction->user)
                                    {{ $transaction->user->first_name ?? '' }} {{ $transaction->user->last_name ?? '' }}
                                    <br>
                                    <small>{{ $transaction->user->email }}</small>
                                @else
                                    <span class="text-muted">Unknown User</span>
                                @endisset
                            </td>
                            <td>{{ $transaction->wallet->uuid ?? 'N/A' }}</td>
                            <td>${{ number_format($transaction->amount, 2) }}</td>
                            <td>${{ number_format($transaction->charge, 2) }}</td>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td>
                                @if ($transaction->status === 'completed')
                                    <span style="color: green;">Completed</span>
                                @elseif ($transaction->status === 'pending')
                                    <span style="color: orange;">Pending</span>
                                @else
                                    <span style="color: red;">Failed</span>
                                @endif
                            </td>
                            <td>{{ $transaction->details ?? 'N/A' }}</td>
                            <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: #888;">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Intracard</p>
            <p>Empowering your financial journey</p>
        </div>
    </div>
</body>
</html>
