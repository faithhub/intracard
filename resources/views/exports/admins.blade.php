<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users Export</title>
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
        .table th:nth-child(1), .table td:nth-child(1) { width: 5%; }  /* S/N */
        .table th:nth-child(2), .table td:nth-child(2) { width: 20%; } /* Name */
        .table th:nth-child(3), .table td:nth-child(3) { width: 25%; } /* Email */
        .table th:nth-child(4), .table td:nth-child(4) { width: 15%; } /* Phone */
        .table th:nth-child(5), .table td:nth-child(5) { width: 15%; } /* Role */
        .table th:nth-child(6), .table td:nth-child(6) { width: 10%; } /* Status */
        .table th:nth-child(7), .table td:nth-child(7) { width: 15%; } /* Created At */
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
            <h1>Admin Users Report</h1>
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
        </div>

        <!-- Admin Users Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $admin)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->phone ?? 'N/A' }}</td>
                            <td>{{ ucfirst($admin->role->name ?? 'N/A') }}</td>
                            <td>
                                @if ($admin->status === 'active')
                                    <span style="color: green;">Active</span>
                                @elseif ($admin->status === 'pending')
                                    <span style="color: orange;">Pending</span>
                                @elseif ($admin->status === 'inactive')
                                    <span style="color: gray;">Inactive</span>
                                @elseif ($admin->status === 'suspended')
                                    <span style="color: red;">Suspended</span>
                                @else
                                    <span style="color: darkred;">Deleted</span>
                                @endif
                            </td>
                            <td>{{ $admin->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #888;">No admin users found</td>
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
