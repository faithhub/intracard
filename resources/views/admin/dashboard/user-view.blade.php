@extends('admin.app-admin')
@section('content')
<style>
    .verification-container {
      margin: 15px 0;
    }
    
    .verification-status {
      color: #4f46e5; /* Indigo */
      font-weight: 500;
      margin-bottom: 5px;
    }
    
    .verification-line {
      height: 5px;
      width: 100%;
      background: linear-gradient(to right, #4f46e5, #818cf8);
      position: relative;
      overflow: hidden;
      border-radius: 3px;
      margin-bottom: 15px;
    }
    
    .verification-line::after {
      content: '';
      position: absolute;
      top: 0;
      left: -50%;
      width: 50%;
      height: 100%;
      background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.7), transparent);
      animation: shine 1.5s infinite;
    }
    
    @keyframes shine {
      0% { left: -50%; }
      100% { left: 150%; }
    }
    
    .verification-date {
      color: #6b7280;
      font-size: 0.85rem;
    }
  </style>
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
    <style>
        .$ {
            uniqueModalId
        }

        .swal2-html-container {
            max-height: 800px !important;
            overflow: auto !important;
        }

        .$ {
            uniqueModalId
        }

        .btn-approve {
            background-color: #35d569 !important;
            color: white !important;
            border: none !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .btn-reject {
            background-color: red !important;
            color: white !important;
            border: none !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .btn-delete {
            background-color: red !important;
            color: white !important;
            border: none !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .btn-approve:hover,
        .btn-reject:hover,
        .btn-delete:hover {
            opacity: 0.9;
        }

        #actionModal .modal-header {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        /* Action-specific styles */
        #actionModal .modal-header.bg-approve {
            background-color: #e6f4ea;
            color: #2e7d32;
        }

        #actionModal .modal-header.bg-reject {
            background-color: #fde7e9;
            color: #d32f2f;
        }

        #actionModal .modal-header.bg-delete {
            background-color: #fde7e9;
            color: #d32f2f;
        }

        #actionModal .btn-approve {
            background-color: #2e7d32;
            color: white;
        }

        #actionModal .btn-reject,
        #actionModal .btn-delete {
            background-color: #d32f2f;
            color: white;
        }

        #actionModal .btn-light {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        #actionModal .modal-content {
            border-radius: 0px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #actionModal .modal-dialog-centered {
            max-width: 350px;
            margin: 0 auto;
        }

        #actionModal .modal-header,
        #actionModal .modal-body,
        #actionModal .modal-footer {
            padding-left: 16px;
            padding-right: 16px;
        }

        #actionModal .btn-light {
            background-color: transparent;
            border: none;
        }

        #actionModal .btn-danger {
            background-color: #ff3b30;
        }
    </style>

    <div id="kt_app_content_container" class="app-container container-fluid">
        <!-- Add this at the top of your view, perhaps near the title or in a header section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">User Details</h2>
            <div class="btn-group">
                @if ($metadata['status']['text'] === 'Pending')
                    <button type="button" class="btn btn-approve btn-sm"
                        onclick="confirmAction('approve', '{{ $user->uuid }}')">
                        <i class="fas text-black fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-reject btn-sm"
                        onclick="confirmAction('reject', '{{ $user->uuid }}')">
                        <i class="fas text-black fa-times"></i> Reject
                    </button>
                @endif
                <button type="button" class="btn btn-delete btn-sm"
                    onclick="confirmAction('delete', '{{ $user->uuid }}')">
                    <i class="fas text-black fa-trash"></i> Delete
                </button>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="actionModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0">
                    <div class="modal-header py-5 px-4 border-0">
                        <h5 class="modal-title px-3 fs-6" id="actionModalTitle">Confirm Rejection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-1 px-5 text-muted" id="actionModalBody">
                        Are you sure you want to reject this user?
                    </div>
                    <div class="modal-footer py-4 px-5 border-0">
                        <button type="button" class="btn btn-light text-muted me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmActionBtn">
                            <span class="spinner-border spinner-border-sm d-none me-1" role="status"
                                aria-hidden="true"></span>
                            Reject
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
                    @if (isset($wallet))
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#wallet">Wallet</a>
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
                    @if (isset($tickets) && count($tickets) > 0)
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tickets">Tickets</a>
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
                                    <!-- Verification Status Card -->
                                    <!-- Verification Status Cards -->
                                    <div class="col-md-6">
                                        <div class="border rounded p-4">
                                            <h4 class="fs-6 mb-3">Verification Status</h4>

                                            @if (!isset($metadata['verification']) || !$metadata['verification']['has_session'])
                                                <!-- No verification session exists -->
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-id-card fa-2x mb-2"></i>
                                                    <p class="mb-0">No verification attempts found</p>
                                                </div>
                                            @else
                                                <!-- Session exists -->
                                                <table class="table table-borderless m-0">
                                                    <tr>
                                                        <td class="detail-label ps-0">Session ID:</td>
                                                        <td class="detail-value">
                                                            <span
                                                                class="text-break">{{ $metadata['verification']['basic_info']['session_id'] }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="detail-label ps-0">Status:</td>
                                                        <td class="detail-value">
                                                            @if ($metadata['verification']['is_approved'])
                                                                <span class="badge bg-success">Approved</span>
                                                            @elseif ($metadata['verification']['status'] === 'declined')
                                                                <span class="badge bg-danger">Declined</span>
                                                            @elseif ($metadata['verification']['status'] === 'resubmission_requested')
                                                                <span class="badge bg-warning">Resubmission
                                                                    Requested</span>
                                                            @else
                                                                <span class="badge bg-secondary">Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    @if ($metadata['verification']['is_approved'])
                                                        <!-- Show additional verification details if approved -->
                                                        @if (isset($metadata['verification']['detailed_info']['user_defined_status']))
                                                            <tr>
                                                                <td class="detail-label ps-0">User Defined Status:</td>
                                                                <td class="detail-value">
                                                                    <span class="badge bg-success">
                                                                        {{ $metadata['verification']['detailed_info']['user_defined_status'] }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if (isset($metadata['verification']['detailed_info']['decision_time']))
                                                            <tr>
                                                                <td class="detail-label ps-0">Verification Date:</td>
                                                                <td class="detail-value">
                                                                    {{ $metadata['verification']['detailed_info']['decision_time'] }}
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if (isset($metadata['verification']['detailed_info']['document']['type_display']))
                                                            <tr>
                                                                <td class="detail-label ps-0">Verification Method:</td>
                                                                <td class="detail-value">
                                                                    <span class="badge bg-secondary">
                                                                        {{ $metadata['verification']['detailed_info']['document']['type_display'] }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @else
                                                        <!-- Show pending message -->
                                                        <tr>
                                                            <td colspan="2" class="pt-3">
                                                                <div class="mt-5 text-center">
                                                                    <div class="verification-container">
                                                                        <div class="verification-status">Verification in progress...</div>
                                                                        <div class="verification-line"></div>
                                                                        <div class="verification-date small text-muted mb-0">
                                                                          Verification was initiated on {{ $metadata['verification']['basic_info']['created_at'] }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Only show identity details if verified -->
                                    @if (isset($metadata['verification']) &&
                                            $metadata['verification']['is_approved'] &&
                                            isset($metadata['verification']['detailed_info']['person']))
                                        <div class="col-md-6">
                                            <div class="border rounded p-4">
                                                <h4 class="fs-6 mb-3">Verified Identity</h4>
                                                <table class="table table-borderless m-0">
                                                    @if (isset($metadata['verification']['detailed_info']['person']['full_name']))
                                                        <tr>
                                                            <td class="detail-label ps-0">Full Name:</td>
                                                            <td class="detail-value">
                                                                {{ $metadata['verification']['detailed_info']['person']['full_name'] }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if (isset($metadata['verification']['detailed_info']['person']['date_of_birth']))
                                                        <tr>
                                                            <td class="detail-label ps-0">Date of Birth:</td>
                                                            <td class="detail-value">
                                                                {{ $metadata['verification']['detailed_info']['person']['date_of_birth'] }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if (isset($metadata['verification']['detailed_info']['document']['number']))
                                                        <tr>
                                                            <td class="detail-label ps-0">Document Number:</td>
                                                            <td class="detail-value">
                                                                {{ $metadata['verification']['detailed_info']['document']['number'] }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if (isset($metadata['verification']['detailed_info']['document']['country']))
                                                        <tr>
                                                            <td class="detail-label ps-0">Issuing Country:</td>
                                                            <td class="detail-value">
                                                                {{ $metadata['verification']['detailed_info']['document']['country'] }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if (isset($metadata['verification']['detailed_info']['document']['valid_until']))
                                                        <tr>
                                                            <td class="detail-label ps-0">Valid Until:</td>
                                                            <td class="detail-value">
                                                                {{ $metadata['verification']['detailed_info']['document']['valid_until'] }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if (isset($metadata['verification']['detailed_info']['person']['address']) &&
                                                            isset($metadata['verification']['detailed_info']['person']['address']['full_address']))
                                                        <tr>
                                                            <td class="detail-label ps-0">Address:</td>
                                                            <td class="detail-value">
                                                                {{ $metadata['verification']['detailed_info']['person']['address']['full_address'] }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    @elseif (isset($metadata['verification']) &&
                                            $metadata['verification']['has_session'] &&
                                            !$metadata['verification']['is_approved']
                                    )
                                        <div class="col-md-6">
                                            <div class="border rounded p-4 d-flex align-items-center justify-content-center"
                                                style="min-height: 200px;">
                                                <div class="text-center">
                                                    <div class="mb-4">
                                                        <i class="fas fa-id-card fa-3x text-muted" style="font-size: 2rem"></i>
                                                    </div>
                                                    <h5 class="fs-6">Identity Verification in Progress</h5>
                                                    <p class="text-muted mb-0">Verified identity details will appear here
                                                        once verification is complete.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- End Verification Status Cards -->

                                </div>
                            </div>
                        </div>
                    </div>

                    @if (isset($tickets) && count($tickets) > 0)
                        <div class="tab-pane fade" id="tickets">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h3 class="card-title mb-0">User Tickets</h3>
                                        <span class="badge bg-secondary fs-6">{{ $tickets['total_tickets'] }}
                                            tickets</span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted bg-light">
                                                    <th>SN</th>
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @isset($tickets['recent_tickets'])
                                                    @php
                                                        $ticket_index = 1;
                                                    @endphp
                                                    @foreach ($tickets['recent_tickets'] as $ticket)
                                                        <tr>
                                                            <td>{{ $ticket_index++ }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="d-flex flex-column">
                                                                        <a href="#"
                                                                            class="text-dark fw-bold text-hover-primary mb-1 fs-6"
                                                                            onclick="showTicketDetails('{{ addslashes(json_encode($ticket)) }}')">
                                                                            {{ $ticket['subject'] }}
                                                                        </a>
                                                                        <span
                                                                            class="text-muted fw-semibold text-muted d-block fs-7 text-truncate"
                                                                            style="max-width: 300px;">
                                                                            {{ $ticket['description'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge 
                                            @if ($ticket['status'] == 'pending') bg-warning
                                            @elseif($ticket['status'] == 'resolved')
                                                bg-success
                                            @elseif($ticket['status'] == 'unresolved')
                                                bg-danger
                                            @else
                                                bg-secondary @endif">
                                                                    {{ ucfirst($ticket['status']) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $ticket['created_at'] }}</td>
                                                            <td>
                                                                <div class="d-flex justify-content-end flex-shrink-0">
                                                                    <button type="button"
                                                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                                        onclick="showTicketDetails('{{ addslashes(json_encode($ticket)) }}')">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endisset
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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

                    <!-- Credit Cards Tab -->
                    <!-- Wallet Tab -->
                    @if (isset($wallet))
                        <div class="tab-pane fade" id="wallet">
                            <div class="card mb-5">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">Wallet</h3>

                                    <!-- Wallet Overview -->
                                    <div class="row g-4 mb-5">
                                        <div class="col-md-6">
                                            <div class="border rounded p-4 h-100">
                                                <h4 class="fs-6 mb-3">Wallet Information</h4>
                                                <div class="d-flex align-items-center mb-4">
                                                    <div class="symbol symbol-50px me-4 bg-light-success rounded p-3">
                                                        <i class="fas fa-wallet fs-2 text-success"></i>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted d-block">Current Balance</span>
                                                        <span
                                                            class="fs-2 fw-bold">${{ number_format($wallet['balance'], 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <span class="text-muted">Wallet ID</span>
                                                        <div class="fw-semibold">{{ $wallet['uuid'] }}</div>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="btn btn-sm btn-light-success"
                                                            onclick="confirmViewHistory('{{ $wallet['uuid'] }}')">
                                                            <i class="fas fa-history me-1"></i> View Full History
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick Stats -->
                                        <div class="col-md-6">
                                            <div class="border rounded p-4 h-100">
                                                <h4 class="fs-6 mb-3">Transaction Summary</h4>
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="bg-light-success rounded p-3">
                                                            <div class="text-muted mb-1">Completed Transactions</div>
                                                            <div class="fs-4 fw-bold">
                                                                {{ isset($wallet_transactions) ? $wallet_transactions->where('status', 'completed')->count() : 0 }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-light-warning rounded p-3">
                                                            <div class="text-muted mb-1">Pending Transactions</div>
                                                            <div class="fs-4 fw-bold">
                                                                {{ isset($wallet_transactions) ? $wallet_transactions->where('status', 'pending')->count() : 0 }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Wallet Transactions -->
                                    @if (isset($wallet_transactions) && $wallet_transactions->count() > 0)
                                        <div class="border rounded p-4 mb-5">
                                            <h4 class="fs-6 mb-3">Recent Wallet Transactions</h4>
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                                    <thead>
                                                        <tr class="fw-bold text-muted bg-light">
                                                            <th>ID</th>
                                                            <th>Type</th>
                                                            <th>Amount</th>
                                                            <th>User</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($wallet_transactions as $transaction)
                                                            <tr>
                                                                <td>{{ $transaction['id'] }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge 
                                                @if ($transaction['type'] == 'deposit') bg-success
                                                @elseif($transaction['type'] == 'withdrawal')
                                                    bg-danger
                                                @elseif($transaction['type'] == 'payment')
                                                    bg-success
                                                @elseif($transaction['type'] == 'refund')
                                                    bg-warning
                                                @else
                                                    bg-secondary @endif
                                                text-uppercase">
                                                                        {{ str_replace('_', ' ', $transaction['type']) }}
                                                                    </span>
                                                                </td>
                                                                <td class="fw-bold">
                                                                    @if (in_array($transaction['type'], ['deposit', 'refund']))
                                                                        <span
                                                                            class="text-success">+${{ number_format($transaction['amount'], 2) }}</span>
                                                                    @else
                                                                        <span
                                                                            class="text-danger">-${{ number_format($transaction['amount'], 2) }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $transaction['user_name'] }}</td>
                                                                <td>{{ $transaction['date'] }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge 
                                                @if ($transaction['status'] == 'completed') bg-success
                                                @elseif($transaction['status'] == 'pending')
                                                    bg-warning
                                                @elseif($transaction['status'] == 'failed')
                                                    bg-danger
                                                @else
                                                    bg-secondary @endif">
                                                                        {{ ucfirst($transaction['status']) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-dark">No wallet transactions found.</div>
                                    @endif

                                    <!-- Wallet Allocations -->
                                    <!-- Wallet Allocations -->
                                    @if (isset($wallet_allocations) && $wallet_allocations->count() > 0)
                                        <div class="border rounded p-4">
                                            <h4 class="fs-6 mb-3">Wallet Allocations</h4>
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                                    <thead>
                                                        <tr class="fw-bold text-muted bg-light">
                                                            <th>Bill/Purpose</th>
                                                            <th>Allocated</th>
                                                            <th>Spent</th>
                                                            <th>Remaining</th>
                                                            <th>Progress</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($wallet_allocations as $allocation)
                                                            <tr>
                                                                <td>
                                                                    {{ $allocation['bill'] ? $allocation['bill']->name : 'General Allocation' }}
                                                                </td>
                                                                <td>${{ number_format($allocation['allocated_amount'], 2) }}
                                                                </td>
                                                                <td>${{ number_format($allocation['spent_amount'], 2) }}
                                                                </td>
                                                                <td>${{ number_format($allocation['remaining_amount'], 2) }}
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $percent =
                                                                            $allocation['allocated_amount'] > 0
                                                                                ? round(
                                                                                    ($allocation['spent_amount'] /
                                                                                        $allocation[
                                                                                            'allocated_amount'
                                                                                        ]) *
                                                                                        100,
                                                                                )
                                                                                : 0;
                                                                        $barClass =
                                                                            $percent < 50
                                                                                ? 'bg-success'
                                                                                : ($percent < 75
                                                                                    ? 'bg-warning'
                                                                                    : 'bg-danger');
                                                                    @endphp
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="progress w-100 h-6px">
                                                                            <div class="progress-bar {{ $barClass }}"
                                                                                role="progressbar"
                                                                                style="width: {{ $percent }}%"
                                                                                aria-valuenow="{{ $percent }}"
                                                                                aria-valuemin="0" aria-valuemax="100">
                                                                            </div>
                                                                        </div>
                                                                        <span
                                                                            class="ms-2 text-muted">{{ $percent }}%</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Wallet History Modal -->
                                    <div class="modal fade" id="walletHistoryModal" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content border-0">
                                                <div class="modal-header py-5 px-4 border-0">
                                                    <h5 class="modal-title px-3 fs-6">Wallet Transaction History</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <!-- Loading indicator -->
                                                    <div id="walletHistoryLoading" class="text-center py-5">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <p class="mt-2">Loading transaction history...</p>
                                                    </div>

                                                    <!-- Content will be loaded here -->
                                                    <div id="walletHistoryContent" class="p-5" style="display: none;">
                                                    </div>

                                                    <!-- Error message -->
                                                    <div id="walletHistoryError" class="alert alert-danger m-5"
                                                        style="display: none;">
                                                        An error occurred while loading transaction history.
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-4 px-5 border-0">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Wallet History Confirmation Modal -->
                                    <div class="modal fade" id="historyConfirmModal" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0">
                                                <div class="modal-header py-5 px-4 border-0">
                                                    <h5 class="modal-title px-3 fs-6">View Wallet Transaction History</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body py-1 px-5 text-muted">
                                                    <p>You are about to view the complete transaction history for this
                                                        wallet.</p>
                                                    <p>This may include sensitive financial information. Are you sure you
                                                        want to proceed?</p>
                                                </div>
                                                <div class="modal-footer py-4 px-5 border-0">
                                                    <button type="button" class="btn btn-light text-muted me-2"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="confirmViewHistoryBtn">
                                                        <i class="fas fa-history me-1"></i> View History
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
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
                                                    <tr>
                                                        <td class="detail-label ps-0">Address:</td>
                                                        <td class="detail-value">{{ $address['name'] }}</td>
                                                    </tr>

                                                    @if ($address['unit_number'])
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
                                                                <a href="{{ $address['tenancy_agreement'] }}"
                                                                    target="blank" class="btn btn-sm btn-light">
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

    {{-- <script src="{{ asset('assets.js.wallet-history') }}"></script> --}}
    <script>
        // Initialize modals
        let historyConfirmModal;
        let walletHistoryModal;
        let currentWalletUuid;

        document.addEventListener('DOMContentLoaded', function() {
            historyConfirmModal = new bootstrap.Modal(document.getElementById('historyConfirmModal'));
            walletHistoryModal = new bootstrap.Modal(document.getElementById('walletHistoryModal'));

            // Set up the confirmation button event
            document.getElementById('confirmViewHistoryBtn').addEventListener('click', function() {
                // Hide confirmation modal
                historyConfirmModal.hide();

                // Show wallet history modal
                walletHistoryModal.show();

                // Reset the content
                document.getElementById('walletHistoryLoading').style.display = 'block';
                document.getElementById('walletHistoryContent').style.display = 'none';
                document.getElementById('walletHistoryError').style.display = 'none';

                // Fetch wallet history via AJAX
                fetchWalletHistory(currentWalletUuid);
            });
        });

        function confirmViewHistory(walletUuid) {
            currentWalletUuid = walletUuid;
            historyConfirmModal.show();
        }

        function fetchWalletHistory(walletUuid) {
            fetch(`/admin/user/wallet/${walletUuid}/history`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    // Hide loading indicator
                    document.getElementById('walletHistoryLoading').style.display = 'none';

                    // Show content
                    const contentDiv = document.getElementById('walletHistoryContent');
                    contentDiv.innerHTML = html;
                    contentDiv.style.display = 'block';

                    // Find and execute any scripts in the loaded content
                    const scriptTags = contentDiv.querySelectorAll('script');
                    scriptTags.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => {
                            newScript.setAttribute(attr.name, attr.value);
                        });
                        newScript.textContent = oldScript.textContent;
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });

                    // Initialize any DataTables or other JS components
                    if (typeof initHistoryDataTable === 'function') {
                        initHistoryDataTable();
                    }

                    // Make sure event listeners in the loaded content are set up
                    setupExportAndPrintListeners();
                })
                .catch(error => {
                    console.error('Error fetching wallet history:', error);

                    // Hide loading indicator
                    document.getElementById('walletHistoryLoading').style.display = 'none';

                    // Show error message
                    document.getElementById('walletHistoryError').style.display = 'block';
                });
        }

        // Function to set up event listeners for the export and print buttons
        function setupExportAndPrintListeners() {
            const exportBtn = document.getElementById('exportCSV');
            const printBtn = document.getElementById('printHistory');

            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    // Export CSV functionality will be handled by the script in the loaded content
                });
            }

            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    window.print();
                });
            }
        }

        // Optional: Function to initialize DataTable (if you use it in your response)
        function initHistoryDataTable() {
            if ($.fn.dataTable !== undefined) {
                $('#wallet-history-table').DataTable({
                    pageLength: 25,
                    ordering: true,
                    searching: true,
                    responsive: true
                });
            }
        }


        function showWalletTransactionDetails(transactionJSON) {
            const transaction = JSON.parse(transactionJSON.replace(/&quot;/g, '"'));

            // Generate a unique identifier for this modal instance
            const uniqueModalId = 'transaction-modal-' + Date.now();

            // Parse details if it's a string
            let details = transaction.details;
            if (typeof details === 'string') {
                try {
                    details = JSON.parse(details);
                } catch (e) {
                    // Keep as string if parsing fails
                }
            }

            // Create modal content with expanded dimensions and increased height
            let detailsHTML = `
        <div class="modal-body p-0" style="min-height: 600px;">
            <div class="d-flex flex-column h-100">
                <!-- User Info -->
                <div class="bg-light p-4 text-center border-bottom">
                    <div class="symbol symbol-80px symbol-circle mb-3 mx-auto">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(transaction.user_name)}&background=a000f9&color=fff&size=80" alt="${transaction.user_name}">
                    </div>
                    <h3 class="fw-bold mb-0">${transaction.user_name}</h3>
                </div>
                
                <!-- Transaction Info -->
                <div class="p-5 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Transaction Information</h4>
                        <span class="badge fs-6 px-3 py-2
                            ${transaction.status === 'completed' ? 'bg-success' : 
                              transaction.status === 'pending' ? 'bg-warning' : 
                              transaction.status === 'failed' ? 'bg-danger' : 'bg-secondary'}">
                            ${transaction.status.toUpperCase()}
                        </span>
                    </div>
                    
                    <div class="row gy-4 mb-5">
                        <div class="col-md-6">
                            <div class="border rounded p-4 h-100">
                                <h5 class="fs-6 mb-3">Basic Information</h5>
                                <div class="d-flex flex-column gap-4">
                                    <div>
                                        <p class="text-muted mb-1">Transaction ID</p>
                                        <p class="fw-semibold mb-0">${transaction.uuid || transaction.id}</p>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1">Date & Time</p>
                                        <p class="fw-semibold mb-0">${transaction.date}</p>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1">Type</p>
                                        <span class="badge 
                                            ${transaction.type === 'deposit' ? 'bg-success' : 
                                              transaction.type === 'withdrawal' ? 'bg-danger' : 
                                              transaction.type === 'payment' ? 'bg-light-info' : 
                                              transaction.type === 'refund' ? 'bg-warning' : 'bg-secondary'}
                                            text-uppercase px-3 py-2">
                                            ${transaction.type.replace(/_/g, ' ')}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="border rounded p-4 h-100">
                                <h5 class="fs-6 mb-3">Financial Details</h5>
                                <div class="d-flex flex-column gap-4">
                                    <div>
                                        <p class="text-muted mb-1">Amount</p>
                                        <p class="fw-bold fs-2 mb-0 ${transaction.type === 'deposit' || transaction.type === 'refund' ? 'text-success' : 'text-danger'}">
                                            ${transaction.type === 'deposit' || transaction.type === 'refund' ? '+' : '-'}$${parseFloat(transaction.amount).toFixed(2)}
                                        </p>
                                    </div>
                                    ${transaction.charge ? `
                                                <div>
                                                    <p class="text-muted mb-1">Service Charge</p>
                                                    <p class="fw-semibold mb-0">$${parseFloat(transaction.charge).toFixed(2)}</p>
                                                </div>
                                                ` : ''}
                                    <div>
                                        <p class="text-muted mb-1">Net Amount</p>
                                        <p class="fw-semibold fs-4 mb-0">$${(parseFloat(transaction.amount) - (parseFloat(transaction.charge) || 0)).toFixed(2)}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method and Service Information side by side -->
                    <div class="row gy-4 mb-4">
                        <!-- Display associated card information if available -->
                        <div class="col-md-6">
                            ${transaction.card ? `
                                        <div class="border rounded p-4 h-100">
                                            <h5 class="fs-6 mb-3">Payment Method</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol bg-light rounded p-3 me-3">
                                                    <img src="${getCreditCardIcon(transaction.card.type)}" alt="${transaction.card.type}" class="h-30px">
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-semibold me-2">${transaction.card.number}</span>
                                                        ${transaction.card.is_primary ? '<span class="badge bg-success">Primary</span>' : ''}
                                                    </div>
                                                    <div class="text-muted small">Expires: ${transaction.card.expiry}</div>
                                                </div>
                                            </div>
                                        </div>
                                        ` : `
                                        <div class="border rounded p-4 h-100">
                                            <h5 class="fs-6 mb-3">Payment Method</h5>
                                            <div class="text-muted text-center py-4">No card information available</div>
                                        </div>
                                        `}
                        </div>
                        
                        <!-- Display associated bill/service information if available -->
                        <div class="col-md-6">
                            ${transaction.bill ? `
                                        <div class="border rounded p-4 h-100">
                                            <h5 class="fs-6 mb-3">Service Information</h5>
                                            <div class="d-flex flex-column gap-3">
                                                <div>
                                                    <p class="text-muted mb-1">Service Name</p>
                                                    <p class="fw-semibold mb-0">${transaction.bill.name}</p>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-1">Status</p>
                                                    <span class="badge ${transaction.bill.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                                                        ${transaction.bill.status.toUpperCase()}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        ` : `
                                        <div class="border rounded p-4 h-100">
                                            <h5 class="fs-6 mb-3">Service Information</h5>
                                            <div class="text-muted text-center py-4">No service information available</div>
                                        </div>
                                        `}
                        </div>
                    </div>
                    
                    <!-- Add buttons at the bottom -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light-success export-btn-${uniqueModalId}">
                            <i class="fas fa-file-download me-1"></i> Export
                        </button>
                        <button type="button" class="btn btn-light close-btn-${uniqueModalId}">
                            <i class="fas fa-times me-1"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

            // Add inline style specifically for this modal
            const modalStyle = `
        <style id="style-${uniqueModalId}">
            .${uniqueModalId} .swal2-html-container {
                max-height: 800px !important;
                overflow: auto !important;
            }
            
            .${uniqueModalId} .swal2-popup {
                min-height: 700px !important;
                max-height: 90vh !important;
            }
        </style>
    `;

            // Show in a much larger modal with increased height
            Swal.fire({
                html: modalStyle + detailsHTML,
                width: '800px', // Wide width
                height: '800px', // Increased height
                showCloseButton: true,
                showConfirmButton: false,
                padding: 0,
                customClass: {
                    container: `transaction-details-modal ${uniqueModalId}`,
                    popup: 'border-radius-lg',
                    htmlContainer: 'p-0 m-0 h-100', // Make container take full height
                    closeButton: 'position-absolute end-0 top-0 p-3'
                },
                didOpen: () => {
                    // Add click handler for the close button
                    document.querySelector(`.close-btn-${uniqueModalId}`).addEventListener('click', () => {
                        Swal.close();
                    });

                    // Export button functionality
                    document.querySelector(`.export-btn-${uniqueModalId}`).addEventListener('click', () => {
                        const exportData = {
                            id: transaction.id || transaction.uuid,
                            type: transaction.type,
                            amount: transaction.amount,
                            user: transaction.user_name,
                            date: transaction.date,
                            status: transaction.status,
                            details: details || {},
                            card: transaction.card,
                            bill: transaction.bill
                        };

                        // Create a blob and download it
                        const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON
                            .stringify(exportData, null, 2));
                        const downloadAnchorNode = document.createElement('a');
                        downloadAnchorNode.setAttribute("href", dataStr);
                        downloadAnchorNode.setAttribute("download",
                            `transaction_${transaction.id || 'export'}.json`);
                        document.body.appendChild(downloadAnchorNode);
                        downloadAnchorNode.click();
                        downloadAnchorNode.remove();
                    });
                },
                willClose: () => {
                    // Clean up by removing the style element when modal closes
                    const styleEl = document.getElementById(`style-${uniqueModalId}`);
                    if (styleEl) {
                        styleEl.remove();
                    }
                }
            });
        }

        // Helper function to get credit card icon based on card type
        function getCreditCardIcon(cardType) {
            const type = cardType ? cardType.toLowerCase() : '';

            if (type.includes('visa')) {
                return '/assets/cards/visa.png';
            } else if (type.includes('mastercard')) {
                return '/assets/cards/mastercard.png';
            } else if (type.includes('amex') || type.includes('american')) {
                return '/assets/cards/amex.png';
            } else if (type.includes('discover')) {
                return '/assets/cards/discover.png';
            } else if (type.includes('diners')) {
                return '/assets/cards/diners.png';
            } else if (type.includes('jcb')) {
                return '/assets/cards/jcb.png';
            } else {
                return '/assets/cards/generic.png';
            }
        }
    </script>



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

            switch (action) {
                case 'approve':
                    modalTitle.textContent = 'Confirm Approval';
                    modalBody.textContent = 'Are you sure you want to approve this user?';
                    confirmBtn.className = 'btn btn-success';
                    confirmBtn.textContent = 'Approve';
                    break;
                case 'reject':
                    modalTitle.textContent = 'Confirm Rejection';
                    modalBody.textContent = 'Are you sure you want to reject this user?';
                    confirmBtn.className = 'btn btn-danger';
                    confirmBtn.textContent = 'Reject';
                    break;
                case 'delete':
                    modalTitle.textContent = 'Confirm Deletion';
                    modalBody.textContent = 'Are you sure you want to delete this user?';
                    confirmBtn.className = 'btn btn-danger';
                    confirmBtn.textContent = 'Delete';
                    break;
            }

            actionModal.show();
        }

        document.getElementById('confirmActionBtn').addEventListener('click', function() {
            const button = this;
            const spinner = button.querySelector('.spinner-border');

            // Check if spinner exists before trying to access its classList
            if (spinner) {
                spinner.classList.remove('d-none');
            } else {
                // Create a spinner if it doesn't exist
                const newSpinner = document.createElement('span');
                newSpinner.className = 'spinner-border spinner-border-sm me-1';
                newSpinner.setAttribute('role', 'status');
                newSpinner.setAttribute('aria-hidden', 'true');

                // Insert spinner as the first child of the button
                button.insertBefore(newSpinner, button.firstChild);
            }

            // Disable button and show spinner
            // button.disabled = true;
            // spinner.classList.remove('d-none');

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
                            actionModal.hide();
                            // Redirect or refresh based on action
                            if (currentAction === 'delete') {
                                window.location.href = '/admin/onboarding';
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


        function showTicketDetails(ticketJSON) {
            const ticket = JSON.parse(ticketJSON);

            // Format the date for better readability
            const createdDate = new Date(ticket.created_at);
            const formattedDate = createdDate.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Determine status color and text
            let statusBgColor, statusTextColor;
            switch (ticket.status.toLowerCase()) {
                case 'resolved':
                    statusBgColor = '#4cd964'; // Bright green
                    statusTextColor = '#ffffff';
                    break;
                case 'pending':
                    statusBgColor = '#ffcc00'; // Yellow
                    statusTextColor = '#000000';
                    break;
                case 'unresolved':
                    statusBgColor = '#ff3b30'; // Red
                    statusTextColor = '#ffffff';
                    break;
                default:
                    statusBgColor = '#8e8e93'; // Gray
                    statusTextColor = '#ffffff';
            }

            // Create the modal content with view-only design
            const modalContent = `
        <div class="ticket-modal-container">
            <!-- Header Section -->
            <div style="background-color: ${statusBgColor}; padding: 16px; border-radius: 8px 8px 0 0; position: relative;">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px me-3 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                        <i class="fas ${ticket.status.toLowerCase() === 'resolved' ? 'fa-check' : (ticket.status.toLowerCase() === 'pending' ? 'fa-clock' : 'fa-exclamation')}" style="color: ${statusBgColor};"></i>
                    </div>
                    <div>
                        <h3 class="fs-5 fw-bold mb-0" style="color: ${statusTextColor};">Ticket #${ticket.id}</h3>
                        <span style="color: ${statusTextColor}; opacity: 0.9; font-size: 0.85rem;">Created on ${formattedDate}</span>
                    </div>
                </div>
                <div style="position: absolute; top: 16px; right: 16px;">
                    <span class="badge text-uppercase px-3 py-2" style="background-color: ${statusBgColor}; color: ${statusTextColor}; border: 1px solid ${statusTextColor}; opacity: 0.9;">${ticket.status}</span>
                </div>
            </div>
            
            <!-- Content Section -->
            <div style="padding: 0; background-color: #ffffff;">
                <!-- Subject Section -->
                <div style="padding: 16px; border-bottom: 1px solid #f0f0f0;">
                    <div class="text-muted mb-1" style="font-size: 0.85rem;">Subject</div>
                    <div class="fw-medium">${ticket.subject}</div>
                </div>
                
                <!-- Description Section -->
                <div style="padding: 16px; background-color: #f9f9f9;">
                    <div class="text-muted mb-1" style="font-size: 0.85rem;">Description</div>
                    <div style="white-space: pre-line;">${ticket.description}</div>
                </div>
            </div>
            
            <!-- Simple Footer Section with just Ticket ID and Close button -->
            <div style="padding: 12px 16px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; border-radius: 0 0 8px 8px;">
                <div>
                    <span class="text-muted fs-7">Ticket ID: ${ticket.uuid}</span>
                </div>
                
                <div>
                    <button type="button" class="btn btn-sm" style="background: none; border: none; color: #6c757d; padding: 6px 12px; font-weight: 500;" onclick="Swal.close();">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    `;

            // Custom CSS for the ticket modal
            const modalStyle = `
        <style>
            .ticket-modal .swal2-popup {
                border-radius: 8px;
                padding: 0;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            .ticket-modal .swal2-html-container {
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            .ticket-modal .swal2-close {
                display: none;
            }
            .symbol-label {
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 500;
            }
            .ticket-modal-container {
                max-width: 100%;
            }
        </style>
    `;

            // Show the modal using SweetAlert2
            Swal.fire({
                html: modalStyle + modalContent,
                width: '500px',
                showConfirmButton: false,
                showCloseButton: false,
                customClass: {
                    container: 'ticket-modal',
                    popup: 'shadow-lg',
                    htmlContainer: 'p-0 m-0'
                }
            });
        }
    </script>
@endsection
