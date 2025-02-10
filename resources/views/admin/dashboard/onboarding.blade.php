@extends('admin.app-admin')
@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card">
            <div class="card-header border-0">
                <div class="card-title">
                    Onboarding Users
                </div>
            </div>

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
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="fa fa-filter fs-2 text-white"></i> Filter
                        </button>

                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <form id="filterForm" method="GET">
                                    <!-- Account Goal -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Account Goal:</label>
                                        <select class="form-select form-select-solid fw-bold" name="account_goal"
                                            id="accountGoal">
                                            <option value="all"
                                                {{ request('account_goal') === 'all' ? 'selected' : '' }}>All</option>
                                            <option value="rent"
                                                {{ request('account_goal') === 'rent' ? 'selected' : '' }}>Rent</option>
                                            <option value="mortgage"
                                                {{ request('account_goal') === 'mortgage' ? 'selected' : '' }}>Mortgage
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Account Type -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Account Type:</label>
                                        <select class="form-select form-select-solid fw-bold" name="account_type"
                                            id="accountType">
                                            <option value="all"
                                                {{ request('account_type') === 'all' ? 'selected' : '' }}>All</option>
                                        </select>
                                    </div>

                                    <!-- Payment Setup -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Payment Setup:</label>
                                        <select class="form-select form-select-solid fw-bold" name="payment_setup">
                                            <option value="all"
                                                {{ request('payment_setup') === 'all' ? 'selected' : '' }}>All</option>
                                            <option value="new"
                                                {{ request('payment_setup') === 'new' ? 'selected' : '' }}>New</option>
                                            <option value="existing"
                                                {{ request('payment_setup') === 'existing' ? 'selected' : '' }}>Existing
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Date Filters -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Date Filter:</label>
                                        <div class="d-flex flex-column gap-2">
                                            <!-- Specific Date -->
                                            <input class="form-control fw-bold" type="date" name="specific_date"
                                                value="{{ request('specific_date') }}">
                                        </div>
                                    </div>
                                    <!-- Date Filters -->
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-semibold">Date Range Filter:</label>
                                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control mt-2">
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset"
                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                            onclick="window.location='{{ route('admin.onboarding') }}'">Reset</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6">Apply</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!--begin::Export-->
                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_export_users">
                            <i class="fa fa-download fs-2 text-white"><span class="path1"></span><span
                                    class="path2"></span></i> Export
                        </button>
                        <!--end::Export-->

                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-400px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2 class="fw-bold">Export Data</h2>
                                        <!--end::Modal title-->

                                        <!--begin::Close-->
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <!--end::Close-->
                                    </div>
                                    <div class="modal-body mx-5 mx-xl-5 my-7">
                                        <form id="kt_modal_export_users_form" method="GET"
                                            action="{{ route('admin.export') }}" novalidate>
                                            <input type="hidden" name="users">
                                            <div class="fv-row mb-10">
                                                <label class="required fs-6 fw-semibold form-label mb-2">Select Export
                                                    Format:</label>
                                                <select name="format" class="form-select form-select-solid fw-bold"
                                                    required>
                                                    <option value="" selected>Select Format</option>
                                                    <option value="excel">Excel</option>
                                                    <option value="pdf">PDF</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="table" value=""> <!-- Dynamically set -->
                                            <div class="text-center">
                                                <button type="button" class="btn btn-light me-3"
                                                    data-bs-dismiss="modal">Discard</button>
                                                <button type="submit" class="btn btn-primary">
                                                    Export
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!--end::Modal body-->
                                </div>
                                <!--end::Modal content-->
                            </div>
                            <!--end::Modal dialog-->
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable" id="userTable">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>S/N</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Account Goal</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        @isset($pendingUsers)
                            @foreach ($pendingUsers as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="d-flex align-items-center">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.users.view', $user->id) }}"
                                                class="text-gray-800 text-hover-primary mb-1 bolder">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </a>
                                            <span>{{ $user->middle_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($user->account_goal) }}</td> <!-- Account Goal Column -->
                                    <td>
                                        <div class="badge badge-light-warning fw-bold">Pending</div>
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
                                            data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="fa fa-chevron-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <div class="menu-link btn">
                                                <a href="{{ route('admin.users.view', $user->uuid) }}"
                                                    class="menu-link px-3">
                                                    View
                                                </a>
                                            </div>
                                            <div class="menu-link px-3 btn">
                                                <form action="" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="menu-link px-3"
                                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-4">
                    {{ $pendingUsers->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportForm = document.getElementById('kt_modal_export_users_form');
            const exportButton = exportForm.querySelector('button[type="submit"]');
            const formatSelect = exportForm.querySelector('select[name="format"]');

            // Dynamically set table name (adjustable for dynamic use)
            const currentTable = exportForm.querySelector('input[name="table"]').value || 'users';

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
            const tableRows = document.querySelectorAll('#userTable tbody tr');

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

        document.addEventListener('DOMContentLoaded', function() {
            const accountGoalSelect = document.getElementById('accountGoal');
            const accountTypeSelect = document.getElementById('accountType');

            // Predefined options for Account Type
            const accountTypes = {
                all: [{
                        value: 'all',
                        label: 'All'
                    },
                    {
                        value: 'sole_applicant',
                        label: 'Sole Applicant'
                    },
                    {
                        value: 'co_applicant',
                        label: 'Co-Applicant'
                    },
                    {
                        value: 'owner',
                        label: 'Owner'
                    },
                    {
                        value: 'co_owner',
                        label: 'Co-Owner'
                    },
                ],
                rent: [{
                        value: 'all',
                        label: 'All'
                    },
                    {
                        value: 'sole_applicant',
                        label: 'Sole Applicant'
                    },
                    {
                        value: 'co_applicant',
                        label: 'Co-Applicant'
                    },
                ],
                mortgage: [{
                        value: 'all',
                        label: 'All'
                    },
                    {
                        value: 'owner',
                        label: 'Owner'
                    },
                    {
                        value: 'co_owner',
                        label: 'Co-Owner'
                    },
                ],
            };

            // Update Account Type options dynamically
            const updateAccountTypeOptions = (goal) => {
                // Clear existing options
                accountTypeSelect.innerHTML = '';

                // Get options based on the selected account goal
                const options = accountTypes[goal] || [];
                options.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.label;
                    opt.selected = option.value ===
                        '{{ request('account_type') }}'; // Preserve selected option
                    accountTypeSelect.appendChild(opt);
                });
            };

            // Trigger the update on page load and when the account goal changes
            accountGoalSelect.addEventListener('change', (e) => updateAccountTypeOptions(e.target.value));
            updateAccountTypeOptions(accountGoalSelect.value);
        });
    </script>
@endsection
