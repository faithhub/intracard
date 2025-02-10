<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Transaction Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            position: relative;
            background: #f9f9f9;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, #e9f5ff, #ffffff);
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 40px 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 25px;
            /* color: #1e6aad; */
            margin: 0;
            font-weight: bold;
        }
        .details {
            line-height: 1.8;
            font-size: 16px;
            color: #555;
        }
        .details p span {
            font-weight: bold;
            color: #222;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(30, 106, 173, 0.08); /* Intracard Blue with low opacity */
            z-index: -1;
            text-align: center;
            font-weight: bold;
            white-space: nowrap;
        }
        .logo {
            width: 350px; /* Increased size for the logo */
            margin: 0 auto 0px;
        }
        .logo img {
            width: 100%;
        }
        .details p {
            margin: 10px 0;
        }
        .details p span {
            /* color: #1e6aad; Intracard Blue */
        }
        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #444;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Watermark -->
        <div class="watermark">INTRACARD</div>

        <!-- Logo -->
        <div class="logo">
            <img src="{{ public_path('assets/images/logos/Intracard.svg') }}" alt="Intracard Logo">
        </div>

        <div class="header">
            <h1>Card Transaction Details</h1>
        </div>

        <div class="details">
            <p><span>User:</span> {{ $transaction->user->first_name ?? 'N/A' }} {{ $transaction->user->last_name ?? '' }}</p>
            <p><span>Card:</span> {{ $transaction->card->name_on_card ?? 'N/A' }}</p>
            <p><span>Amount:</span> ${{ number_format($transaction->amount, 2) }}</p>
            <p><span>Charge:</span> ${{ number_format($transaction->charge, 2) }}</p>
            <p><span>Type:</span> {{ ucfirst($transaction->type ?? 'N/A') }}</p>
            <p><span>Status:</span> {{ ucfirst($transaction->status ?? 'N/A') }}</p>
            <p><span>Transaction Date:</span> {{ $transaction->created_at->format('d M Y, h:i A') }}</p>
        </div>

        <div class="footer">
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
            <p>&copy; {{ date('Y') }} Intracard</p>
        </div>
    </div>
</body>
</html>

