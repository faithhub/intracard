@extends('admin.app-admin')
@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Card-->

        <div class="card">
            <div class="card-header border-0">
                <div class="card-title">
                    Wallet Transactions
                </div>
            </div>
            <!--begin::Card header-->
            <div class="card-header border-0 pt-0">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="fa-solid fa-magnifying-glass fs-3 position-absolute ms-5"></i>
                        <input type="text" id="tableSearch" class="form-control form-control-solid w-250px ps-13"
                            placeholder="Search">
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <!--begin::Filter-->
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="fa fa-filter fs-2 text-white"></i> Filter
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <form id="filterForm" method="GET">
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Status:</label>
                                        <select class="form-select form-select-solid fw-bold" name="status">
                                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Type:</label>
                                        <select class="form-select form-select-solid fw-bold" name="type">
                                            <option value="all" {{ request('type') === 'all' ? 'selected' : '' }}>All</option>
                                            <option value="inbound" {{ request('type') === 'inbound' ? 'selected' : '' }}>Inbound</option>
                                            <option value="outbound" {{ request('type') === 'outbound' ? 'selected' : '' }}>Outbound</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Date Filter -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Date Added:</label>
                                        <input type="date" class="form-control fw-bold" name="date_added"
                                            value="{{ request('date_added') }}">
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Date Range:</label>
                                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control mt-2">
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset"
                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                            onclick="window.location='{{ route('admin.wallet.transaction') }}'">Reset</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6">Apply</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end::Filter-->

                        <!--begin::Export-->
                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_export_cards">
                            <i class="fa fa-download fs-2 text-white"></i> Export
                        </button>
                        <!--end::Export-->
                        <div class="modal fade" id="kt_modal_export_cards" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-400px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="fw-bold">Export Wallet Transactions</h2>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body mx-5 mx-xl-5 my-7">
                                        <form id="kt_modal_export_wallet_transactions_form" method="GET"
                                            action="{{ route('admin.export') }}">
                                            <input type="hidden" name="table" value="wallet_transactions">
                                            <div class="fv-row mb-10">
                                                <label class="required fs-6 fw-semibold form-label mb-2">Select Export
                                                    Format:</label>
                                                <select name="format" class="form-select form-select-solid fw-bold"
                                                    required>
                                                    <option value="excel">Excel</option>
                                                    <option value="pdf">PDF</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="table" value="cards">
                                            <div class="text-center">
                                                <button type="button" class="btn btn-light me-3"
                                                    data-bs-dismiss="modal">Discard</button>
                                                <button type="submit" class="btn btn-primary">Export</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body py-4">
                    <table class="table align-middle table-row-dashed fs-6 gy-5"  id="transactionTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Wallet</th>
                                <th>Amount</th>
                                <th>Charge</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($transactions)
                            @foreach ($transactions as $index => $transaction)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="d-flex align-items-center">
                                        <!-- User details -->
                                        <div class="d-flex flex-column">
                                            @isset($transaction->user)
                                                <a href="{{ route('admin.users.view', ['uuid' => $transaction->user->uuid]) }}"
                                                    class="text-gray-800 text-hover-primary mb-1 bolder">
                                                    {{ $transaction->user->first_name }} {{ $transaction->user->last_name }}
                                                </a>
                                                <span>{{ $transaction->user->email }}</span>
                                            @else
                                                <span class="text-muted">Unknown User</span>
                                            @endisset
                                        </div>
                                    </td>
                                    <td>
                                        @isset($transaction->wallet)
                                            <span class="text-capitalize">{{ $transaction->wallet->uuid ?? 'N/A' }}</span>
                                        @else
                                            <span class="text-muted">Unknown Wallet</span>
                                        @endisset
                                    </td>
                                    <td>
                                        ${{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td>
                                        ${{ number_format($transaction->charge, 2) }}
                                    </td>
                                    <td>
                                        @if ($transaction->type === 'inbound')
                                            <div class="badge badge-light-success fw-bold">Inbound</div>
                                        @else
                                            <div class="badge badge-light-danger fw-bold">Outbound</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaction->status === 'completed')
                                            <div class="badge badge-light-success fw-bold">Completed</div>
                                        @elseif ($transaction->status === 'pending')
                                            <div class="badge badge-light-warning fw-bold">Pending</div>
                                        @else
                                            <div class="badge badge-light-danger fw-bold">Failed</div>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
                                            data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="fa fa-chevron-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a class="menu-link px-3 btn"
                                                    href="{{ route('admin.wallet.transactions.view', ['uuid' => $transaction->uuid]) }}"
                                                    data-type="dark" data-size="s" data-title="Wallet Transaction"
                                                    data-type="purple" data-uuid="{{ $transaction->uuid }}"
                                                    onclick="walletTransactionEvent(event)">
                                                    View
                                                </a>
                                            </div>

                                            <!-- Download Option -->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.wallet.transactions.download', ['uuid' => $transaction->uuid]) }}"
                                                    class="menu-link px-3 btn">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted">No transactions found</td>
                            </tr>
                        @endisset
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                    
            </div>
            <!--end::Card body-->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportForm = document.getElementById('kt_modal_export_wallet_transactions_form');
            const exportButton = exportForm.querySelector('button[type="submit"]');
            const formatSelect = exportForm.querySelector('select[name="format"]');

            // Dynamically set table name (adjustable for dynamic use)
            const currentTable = exportForm.querySelector('input[name="table"]').value || 'card_transactions';

            // Add event listener for form submission
            exportForm.addEventListener('submit', async function(e) {
                e.preventDefault(); // Prevent default form submission

                // Validate the format field
                if (!formatSelect.value) {
                    toastr.error('Please select a format to proceed.', 'Error');
                    return;
                }

                // Set loading state
                exportButton.setAttribute('disabled', true);
                exportButton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Exporting...
        `;

                // Build URL with query parameters
                const params = new URLSearchParams({
                    format: formatSelect.value,
                    table: currentTable,
                }).toString();

                const url = `${exportForm.action}?${params}`;

                // Submit the form using Fetch API
                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`Export failed with status: ${response.status}`);
                    }

                    // Trigger the download
                    const blob = await response.blob();
                    const downloadUrl = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = `${currentTable}`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // Show success toast message
                    toastr.success('Export completed successfully!', 'Success');
                } catch (error) {
                    console.error('Export Error:', error);
                    toastr.error('An error occurred while exporting. Please try again later.', 'Error');
                } finally {
                    // Reset loading state
                    exportButton.removeAttribute('disabled');
                    exportButton.innerHTML = 'Export';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tableSearch');
            const tableRows = document.querySelectorAll('#transactionTable tbody tr');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(query)) {
                        row.style.display = ''; // Show row
                    } else {
                        row.style.display = 'none'; // Hide row
                    }
                });
            });
        });


        document.getElementById('filterForm').addEventListener('submit', function(e) {
            const specificDate = document.querySelector('[name="specific_date"]').value;
            const startDate = document.querySelector('[name="start_date"]').value;
            const endDate = document.querySelector('[name="end_date"]').value;

            if (specificDate && (startDate || endDate)) {
                e.preventDefault();
                alert('Please select either a specific date or a date range, not both.');
            }
        });
    </script>
@endsection
