@extends('app-user')
@section('content')
    <style>
        .badge-success {
            color: white !important;
            background-color: #3cd316 !important;
        }

        .badge-i {
            transform: rotate(45deg);
            /* Makes the arrow look like a square root */
        }
    </style>
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!-- Wallet Balance Section -->
        <div class="card mb-5">
            <div class="card-body d-flex justify-content-between align-items-center">
                <!-- Wallet Balance -->
                <div>
                    <h3 class="text-gray-800 fw-bold mb-2">Wallet Balance</h3>
                    <h1 class="text-black fw-bold">$ {{ number_format(1250.5, 2) }}</h1>
                </div>

                <!-- Add Money Button -->
                <div>
                    {{-- <a class="btn btn-primary btn-sm mb-3" data-type="dark" data-size="s" data-title="Wallet"
                        onclick="walletEvent(event)" href="{{ route('modal.user.wallet') }}">
                        <i class="fa fa-eye fs-4"></i> View Wallet
                    </a> --}}
                    <a class="btn btn-primary btn-sm mb-3" data-type="dark" data-size="s" data-title="Wallet"
                        onclick="walletEvent(event)" href="{{ route('modal.user.wallet') }}">
                        <i class="fa fa-wallet fs-4"></i> Fund Wallet
                    </a>
                </div>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="card mb-5">
            <div class="card-header card-header-stretch pb-0">
                <div class="card-title">
                    <h3 class="m-0">Transaction History</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="transactionTable">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase" width="3">
                                <!-- Checkbox Column -->
                                <th class="w-5 pe-2 dt-orderable-none">
                                    <span class="dt-column-title">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#transactionTable .form-check-input" value="1">
                                        </div>
                                    </span>
                                </th>
                                <th width="17%">Transaction ID</th>
                                <th width="17%">Date</th>
                                <th width="15%">Amount</th>
                                <th width="10%">Bill Type</th>
                                <th width="18%">Transaction Type</th>
                                <th width="5%">Status</th>
                                <th class="text-end" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1">
                                    </div>
                                </td>
                                <td>#TRX123456</td>
                                <td>20 Nov 2024</td>
                                <td>$50.00</td>
                                <td>Utility Bill</td>
                                <td>
                                    <span class="badge badge-light-success text-white px-2 text-gray-800 fw-bold fs-5">
                                        Inbound&nbsp;
                                        <i class="fa fa-arrow-down fs-4 text-success badge-i"></i>
                                    </span>
                                </td>
                                <td><span class="badge badge-light-success">Completed</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#transactionDetailsModal"
                                        onclick="showTransactionDetails('#TRX123456', 'Utility Bill', '$50.00', '20 Nov 2024', 'Completed')">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1">
                                    </div>
                                </td>
                                <td>#TRX123457</td>
                                <td>18 Nov 2024</td>
                                <td>$100.00</td>
                                <td>Car Bill</td>
                                <td>
                                    <span class="badge badge-light-warning text-white px-2 text-gray-800 fw-bold fs-5">
                                        Outbound&nbsp;
                                        <i class="fa fa-arrow-up fs-4 text-warning badge-i"></i>
                                    </span>
                                </td>
                                <td><span class="badge badge-light-warning">Pending</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                        data-bs-target="#transactionDetailsModal"
                                        onclick="showTransactionDetails('#TRX123457', 'Car Bill', '$100.00', '18 Nov 2024', 'Pending')">
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Modal to View Transaction Details -->
        <div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transaction Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Transaction ID:</strong> <span id="modalTransactionID"></span></p>
                        <p><strong>Bill Type:</strong> <span id="modalBillType"></span></p>
                        <p><strong>Amount:</strong> <span id="modalAmount"></span></p>
                        <p><strong>Date:</strong> <span id="modalDate"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="downloadReceipt()">Download
                            Receipt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTransactionDetails(id, billType, amount, date, status) {
            document.getElementById('modalTransactionID').textContent = id;
            document.getElementById('modalBillType').textContent = billType;
            document.getElementById('modalAmount').textContent = amount;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalStatus').textContent = status;
        }

        function downloadReceipt() {
            const transactionID = document.getElementById('modalTransactionID').textContent;
            const receiptData = `Receipt for Transaction: ${transactionID}`;
            const blob = new Blob([receiptData], {
                type: 'text/plain'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${transactionID}_receipt.txt`;
            link.click();
        }
    </script>
@endsection
