@extends('admin.app-admin')
@section('content')
    <style>
        .text-active-primary.active {
            color: var(--bs-gray-500) !important;
        }

        .nav-line-tabs .nav-item .nav-link.active {
            background-color: #a000f93b !important;
            padding: 8px !important;
            color: #310431 !important;
        }

        .nav-tabs .nav-link {
            border-top-left-radius: 0px !important;
            border-top-right-radius: 0px !important;
        }

        .img-cc {
            max-width: 70px !important;
        }

        .menu-state-bg-light-primary .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) {
            transition: color .2s ease;
            background-color: #a000f93b !important;
        }

        .user-badge {
            background-color: #a000f93b !important;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
        }

        .profile-card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .detail-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .detail-value {
            color: #2b2b2b;
            font-weight: 500;
        }
    </style>


    <div id="kt_app_content_container" class="app-container container-fluid">
        <!-- Add this at the top of your view, perhaps near the title or in a header section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">User Details</h2>
            <div class="btn-group">
                @if($metadata['status']['text'] === 'Pending')
                    <button type="button" class="btn btn-approve btn-sm" onclick="confirmAction('approve', '{{ $user->uuid }}')">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-reject btn-sm" onclick="confirmAction('reject', '{{ $user->uuid }}')">
                        <i class="fas fa-times"></i> Reject
                    </button>
                @endif
                <button type="button" class="btn btn-delete btn-sm" onclick="confirmAction('delete', '{{ $user->uuid }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>

        <!-- Add this modal at the bottom of your view -->
        <div class="modal fade" id="actionModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="actionModalTitle">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="actionModalBody">
                        Are you sure you want to proceed with this action?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmActionBtn">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-lg-row">
            <!-- Left Sidebar - User Profile -->
            <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                <div class="card profile-card mb-5">
                    <div class="card-body">
                        <!-- User Profile Header -->
                        <div class="d-flex flex-column align-items-center">
                            <div class="symbol symbol-100px symbol-circle mb-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($metadata['full_name']) }}&background=a000f9&color=fff"
                                    alt="{{ $metadata['full_name'] }}" class="img-fluid rounded-circle">
                            </div>

                            <h3 class="mb-2">{{ $metadata['full_name'] }}</h3>

                            <div class="user-badge mb-4">
                                {{ ucfirst($metadata['account_type']) }}
                            </div>

                            <!-- User Details Accordion -->
                            <div class="accordion w-100" id="userDetailsAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#userDetails">
                                            Details
                                        </button>
                                    </h2>
                                    <div id="userDetails" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="d-flex flex-column gap-3">
                                                <div>
                                                    <div class="detail-label">Name</div>
                                                    <div class="detail-value">{{ $metadata['full_name'] }}</div>
                                                </div>
                                                <div>
                                                    <div class="detail-label">Email</div>
                                                    <div class="detail-value">{{ $metadata['email'] }}</div>
                                                </div>
                                                @if (isset($address))
                                                    <div>
                                                        <div class="detail-label">Address</div>
                                                        <div class="detail-value">
                                                            {{ $address['street_name'] }}<br>
                                                            @if ($address['unit_number'])
                                                                Unit {{ $address['unit_number'] }}<br>
                                                            @endif
                                                            {{ $address['city'] }}, {{ $address['province'] }}<br>
                                                            {{ $address['postal_code'] }}
                                                        </div>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="detail-label">Status</div>
                                                    <div class="detail-value">
                                                        <span class="badge bg-{{ $metadata['status']['class'] }}">
                                                            {{ $metadata['status']['text'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="detail-label">Account Created</div>
                                                    <div class="detail-value">{{ $metadata['created_at'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-lg-row-fluid ms-lg-15">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#overview">Overview</a>
                    </li>
                    @if (isset($credit_cards) && $credit_cards->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#credit-cards">Credit Cards</a>
                        </li>
                    @endif
                    @if (isset($address))
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#address-details">Address Details</a>
                        </li>
                    @endif
                    @if (isset($finance_details))
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#finance-details">
                                {{ $finance_details['type'] == 'rent' ? 'Landlord' : 'Mortgage Financer' }} Details
                            </a>
                        </li>
                    @endif
                    @if (isset($team_memberships) && $team_memberships->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#team-members">Team Members</a>
                        </li>
                    @endif
                    @if (isset($recent_transactions) && $recent_transactions->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#transactions">Transactions</a>
                        </li>
                    @endif
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview">
                        <div class="card mb-5">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Account Overview</h3>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="border rounded p-4">
                                            <h4 class="fs-6 mb-3">Personal Information</h4>
                                            <table class="table table-borderless m-0">
                                                <tr>
                                                    <td class="detail-label ps-0">First Name:</td>
                                                    <td class="detail-value">{{ $metadata['first_name'] }}</td>
                                                </tr>
                                                @if ($metadata['middle_name'])
                                                    <tr>
                                                        <td class="detail-label ps-0">Middle Name:</td>
                                                        <td class="detail-value">{{ $metadata['middle_name'] }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="detail-label ps-0">Last Name:</td>
                                                    <td class="detail-value">{{ $metadata['last_name'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="detail-label ps-0">Email:</td>
                                                    <td class="detail-value">{{ $metadata['email'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="detail-label ps-0">Phone:</td>
                                                    <td class="detail-value">{{ $metadata['phone'] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-4">
                                            <h4 class="fs-6 mb-3">Account Information</h4>
                                            <table class="table table-borderless m-0">
                                                <tr>
                                                    <td class="detail-label ps-0" style="width: 120px;">Account Type:</td>
                                                    <td class="detail-value">
                                                        <span
                                                            class="badge">{{ ucfirst($metadata['account_type']) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="detail-label ps-0">Account Goal:</td>
                                                    <td class="detail-value">
                                                        <span
                                                            class="badge">{{ ucfirst($metadata['account_goal']) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="detail-label ps-0">Payment Setup:</td>
                                                    <td class="detail-value">
                                                        <span class="badge">{{ $metadata['payment_setup'] }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="detail-label ps-0">Status:</td>
                                                    <td class="detail-value">
                                                        <span class="badge bg-{{ $metadata['status']['class'] }}">
                                                            {{ $metadata['status']['text'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="detail-label ps-0">OTP Verified:</td>
                                                    <td class="detail-value">
                                                        <span
                                                            class="badge {{ $metadata['otp_verified'] ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $metadata['otp_verified'] ? 'Yes' : 'No' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Credit Cards Tab -->
                    @if (isset($credit_cards) && $credit_cards->isNotEmpty())
                        <div class="tab-pane fade" id="credit-cards">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Credit Cards</h3>
                                    <div class="row g-4">
                                        @foreach ($credit_cards as $card)
                                            <div class="col-md-6">
                                                <div class="border rounded p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h5 class="mb-0">
                                                            {{ $card['is_primary'] ? 'Primary Card' : 'Secondary Card' }}
                                                        </h5>
                                                        <span
                                                            class="badge bg-{{ $card['status'] == 'active' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($card['status']) }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('assets/cards/' . strtolower($card['card_type']) . '.png') }}"
                                                            alt="{{ $card['card_type'] }}" class="me-3 img-cc">
                                                        <div>
                                                            <div class="fs-5 fw-bold mb-1">**** {{ $card['last_four'] }}
                                                            </div>
                                                            <div class="text-muted">Expires {{ $card['expiry_date'] }}
                                                            </div>
                                                            <div class="text-muted">Limit:
                                                                ${{ number_format($card['card_limit'], 2) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Continue the previous code... -->

                    <!-- Address Details Tab -->
                    @if (isset($address))
                        <div class="tab-pane fade" id="address-details">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h3 class="card-title mb-0">Address Details</h3>
                                        <div class="text-muted">
                                            Last edited: {{ $address['last_edit_date'] ?? 'Never' }}
                                            ({{ $address['edit_count'] }} edits this year)
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <!-- Address Information -->
                                        <div class="col-md-6">
                                            <div class="border rounded p-4">
                                                <h4 class="fs-6 mb-3">Location Details</h4>
                                                <table class="table table-borderless m-0">
                                                    @if ($address['unit_number'])
                                                        <tr>
                                                            <td class="detail-label ps-0">Address:</td>
                                                            <td class="detail-value">{{ $address['name'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="detail-label ps-0">Unit Number:</td>
                                                            <td class="detail-value">{{ $address['unit_number'] }}</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="detail-label ps-0">House Number:</td>
                                                        <td class="detail-value">{{ $address['house_number'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label ps-0">Street Name:</td>
                                                        <td class="detail-value">{{ $address['street_name'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label ps-0">City:</td>
                                                        <td class="detail-value">{{ $address['city'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label ps-0">Province:</td>
                                                        <td class="detail-value">{{ $address['province'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label ps-0">Postal Code:</td>
                                                        <td class="detail-value">{{ $address['postal_code'] }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Payment Information -->
                                        <div class="col-md-6">
                                            <div class="border rounded p-4">
                                                <h4 class="fs-6 mb-3">Payment Details</h4>
                                                <table class="table table-borderless m-0">
                                                    <tr>
                                                        <td class="detail-label ps-0">Monthly Amount:</td>
                                                        <td class="detail-value">
                                                            ${{ number_format($address['amount'], 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label ps-0">Payment Day:</td>
                                                        <td class="detail-value">
                                                            {{ $address['reoccurring_monthly_day'] }}th of each month</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label ps-0">Duration:</td>
                                                        <td class="detail-value">
                                                            {{ \Carbon\Carbon::parse($address['duration_from'])->format('M d, Y') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($address['duration_to'])->format('M d, Y') }}
                                                        </td>
                                                    </tr>
                                                    @if ($address['tenancy_agreement'])
                                                        <tr>
                                                            <td class="detail-label ps-0">Agreement:</td>
                                                            <td class="detail-value">
                                                                <a href="#" class="btn btn-sm btn-light">
                                                                    <i class="fas fa-file-pdf me-2"></i>View Agreement
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Landlord/Mortgage Details Tab -->
                    @if (isset($finance_details))
                        <div class="tab-pane fade" id="finance-details">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">{{ $finance_details['display_title'] }} Details</h3>
                                    <div class="border rounded p-4">
                                        <table class="table table-borderless m-0">
                                            <tr>
                                                <td class="detail-label ps-0" style="width: 150px;">Type:</td>
                                                <td class="detail-value">{{ ucfirst($finance_details['type']) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="detail-label ps-0">Payment Method:</td>
                                                <td class="detail-value">{{ ucfirst($finance_details['payment_method']) }}
                                                </td>
                                            </tr>
                                            @foreach ($finance_details['details'] as $key => $value)
                                                <tr>
                                                    <td class="detail-label ps-0">
                                                        {{ ucfirst(str_replace('_', ' ', $key)) }}:</td>
                                                    <td class="detail-value">
                                                        @if (is_array($value))
                                                            <pre class="mb-0">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Team Members Tab -->
                    @if (isset($team_memberships) && $team_memberships->isNotEmpty())
                        <div class="tab-pane fade" id="team-members">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Team Members</h3>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Registration</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($team_memberships as $member)
                                                    <tr>
                                                        <td>{{ $member['name'] }}</td>
                                                        <td>{{ $member['email'] }}</td>
                                                        <td>{{ ucfirst($member['role']) }}</td>
                                                        <td>${{ number_format($member['amount'], 2) }}
                                                            ({{ $member['percentage'] }}%)
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $member['status'] === 'accepted' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($member['status']) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($member['is_registered'])
                                                                <span class="badge bg-success">Registered</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending Registration</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Transactions Tab -->
                    @if (isset($recent_transactions) && $recent_transactions->isNotEmpty())
                        <div class="tab-pane fade" id="transactions">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Recent Transactions</h3>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Card</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recent_transactions as $transaction)
                                                    <tr>
                                                        <td>{{ $transaction['transaction_id'] }}</td>
                                                        <td>${{ number_format($transaction['amount'], 2) }}</td>
                                                        <td>
                                                            <img src="{{ asset('assets/cards/' . strtolower($transaction['card_type']) . '.png') }}"
                                                                alt="{{ $transaction['card_type'] }}"
                                                                class="w-35px me-3">
                                                            **** {{ $transaction['card_last_four'] }}
                                                        </td>
                                                        <td>{{ $transaction['date'] }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $transaction['status'] == 'completed' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($transaction['status']) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-light">View</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- End the previous code... -->
                    <!-- Would you like me to continue with the other tabs? -->
                </div>
            </div>
        </div>
    </div>


    
    <script>
        let actionModal;
        let currentAction;
        let currentUuid;
        
        document.addEventListener('DOMContentLoaded', function() {
            actionModal = new bootstrap.Modal(document.getElementById('actionModal'));
        });
        
        function confirmAction(action, uuid) {
            currentAction = action;
            currentUuid = uuid;
            
            const modalTitle = document.getElementById('actionModalTitle');
            const modalBody = document.getElementById('actionModalBody');
            const confirmBtn = document.getElementById('confirmActionBtn');
            
            switch(action) {
                case 'approve':
                    modalTitle.textContent = 'Confirm Approval';
                    modalBody.textContent = 'Are you sure you want to approve this user?';
                    confirmBtn.className = 'btn btn-success';
                    break;
                case 'reject':
                    modalTitle.textContent = 'Confirm Rejection';
                    modalBody.textContent = 'Are you sure you want to reject this user?';
                    confirmBtn.className = 'btn btn-danger';
                    break;
                case 'delete':
                    modalTitle.textContent = 'Confirm Deletion';
                    modalBody.textContent = 'Are you sure you want to delete this user? This action cannot be undone.';
                    confirmBtn.className = 'btn btn-danger';
                    break;
            }
            
            actionModal.show();
        }
        
        document.getElementById('confirmActionBtn').addEventListener('click', function() {
            const button = this;
            const spinner = button.querySelector('.spinner-border');
            
            // Disable button and show spinner
            button.disabled = true;
            spinner.classList.remove('d-none');
            
            // Prepare the form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            
            if (currentAction === 'delete') {
                formData.append('_method', 'DELETE');
            }
            
            // Send request
            fetch(`/admin/users/${currentUuid}/${currentAction}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect or refresh based on action
                        if (currentAction === 'delete') {
                            window.location.href = '/admin/dashboard';
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    throw new Error(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                // Show error message
                Swal.fire({
                    title: 'Error!',
                    text: error.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            })
            .finally(() => {
                // Re-enable button and hide spinner
                button.disabled = false;
                spinner.classList.add('d-none');
                actionModal.hide();
            });
        });
        </script>

<style>
    .btn-approve {
        background-color: #548719 !important;
        color: white !important;
        border: none !important;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    
    .btn-reject {
        background-color: #ad1818 !important;
        color: white !important;
        border: none !important;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    
    .btn-delete {
        background-color: #ad1818 !important;
        color: white !important;
        border: none !important;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    
    .btn-approve:hover, .btn-reject:hover, .btn-delete:hover {
        opacity: 0.9;
    }
    </style>

@endsection
