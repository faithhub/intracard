@extends('app-user')
@section('content')
    @php
        $addressDetails = json_decode(Auth::user()->address_details, true);
        $landloard = json_decode(Auth::user()->landlord_or_finance_details, true);
        $account_details = json_decode(Auth::user()->account_details, true);
        $finance = json_decode(Auth::user()->landlord_or_finance_details, true);
    @endphp
    <style>
        .jconfirm .jconfirm-holder {
            margin: 1.5rem !important;
        }

        /* Setup Bill Section */
        .setup-bill-section {
            display: contents !important;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .fc .fc-button-group {
            display: flow;
            position: relative;
            vertical-align: middle;
        }

        .setup-bill-btn {
            align-self: flex-end;
            margin-top: 1rem;
        }

        .rent-amount {
            font-size: 2rem;
            font-weight: bold;
            color: #43367b;
        }

        .rent-label {
            padding-bottom: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: #6c757d;
        }

        .details-card {
            border: 1px dashed #c8c8c8;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            /* background-color: #f8f9fa; */
        }

        .details-label {
            font-weight: bold;
            color: #6c757d;
        }

        .details-value {
            color: #343a40;
            font-weight: bold;
        }

        .btn-modal-close:hover {
            background-color: black !important;
        }

        .card-toolbar {
            margin-bottom: 1.2rem !important;
        }

        @media (min-width: 768px) {
            .custom-padding {
                padding: 1rem;
                /* Add padding for medium screens and above */
            }
        }

        @media (max-width: 767.98px) {
            .card-header {
                padding: 0rem !important;
                /* Remove padding for small screens and below */
            }

            .calendar-title {
                font-size: 1.3rem !important;
            }
        }

        @media (max-width: 576px) {
            .nav-item span {
                font-size: 10px;
            }

            .nav-item span.d-inline-block {
                width: 12px;
                height: 12px;
            }

            .row>* {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }

        @media (max-width: 767.98px) {
            .fc .fc-header-toolbar {
                flex-direction: column;
                align-items: center !important;
            }

            .fc .fc-header-toolbar .fc-toolbar-chunk:nth-child(1) {
                margin-bottom: 0rem !important;
            }

            .fc .fc-toolbar.fc-header-toolbar {
                margin-bottom: 0rem !important;
            }

            .fc .fc-header-toolbar .fc-toolbar-chunk:nth-child(2) {
                margin-bottom: 0rem !important;
            }
            .fc .fc-button {
                padding: .35rem .75rem !important; 
            }
        }
    </style>

    <div id="kt_app_content_container" class="app-container container-fluid">
        <div id="kt_account_settings_profile_details">
            <div class="card" style="padding: 5px">
                <div class="card-header p-md-5 card-header-stretch d-flex justify-content-between align-items-center">
                    <!-- Title Section -->
                    <div class="card-title d-flex align-items-center">
                        <i class="fa fa-house fs-1 text-gray me-3 lh-0"></i>
                        <h3 class="fw-bold m-0 text-gray-800">Welcome! {{ Auth::user()->first_name }}</h3>
                    </div>

                    <!-- Setup Bill Button -->
                    <div class="card-toolbar">
                        <a class="btn btn-primary px-5 py-3 fw-bold shadow-sm rounded-pill setup-bill-btn" data-type="dark"
                            data-size="s" data-title="Setup Bill" onclick="setupBill(event)"
                            href="{{ route('modal.user.setupBill') }}">
                            <i class="ki-duotone ki-add-circle fs-4"></i>Setup Bill
                        </a>
                    </div>
                </div>

                <div class="row m-1">
                    <div class="col-md-8">
                        <div class="card border-0 card-1 mt-5 text-black" data-bs-theme="light"
                            style="background-color: #f8f6f2">
                            <div class="card-title mb-2 font-bold calendar-title"
                                style="font-size: 1.5rem; font-weight: bold"></div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                    <!-- Payment Duration -->
                                    {{-- <div class="nav-item d-flex align-items-center">
                                        <span class="d-inline-block"
                                            style="width: 16px; height: 16px; background-color: #210035; border-radius: 4px;"></span>
                                        <span class="ms-2 text-gray-600 fw-bold" style="font-size: 12px;">Payment
                                            Duration</span>
                                    </div> --}}
                                    <!-- Bills Payment -->
                                    <div class="nav-item d-flex align-items-center">
                                        <span class="d-inline-block"
                                            style="width: 16px; height: 16px; background-color: #FFC53D; border-radius: 4px;"></span>
                                        <span class="ms-2 text-gray-600 fw-bold" style="font-size: 12px;">Payment due
                                            date</span>
                                    </div>
                                    <!-- Last Payments -->
                                    <div class="nav-item d-flex align-items-center">
                                        <span class="d-inline-block"
                                            style="width: 16px; height: 16px; background-color: #133f1a; border-radius: 4px;"></span>
                                        <span class="ms-2 text-gray-600 fw-bold" style="font-size: 12px;">Fund account
                                            date</span>
                                    </div>
                                    <!-- Upcoming Payments -->
                                    {{-- <div class="nav-item d-flex align-items-center">
                                        <span class="d-inline-block"
                                            style="width: 16px; height: 16px; background-color: #40C057; border-radius: 4px;"></span>
                                        <span class="ms-2 text-gray-600 fw-bold" style="font-size: 12px;">Upcoming
                                            Payments</span>
                                    </div> --}}
                                </div>
                                <div style="background-color: transparent; margin-top: 10px">
                                    <div id='calendar2'></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-1 border-0 mt-5" data-bs-theme="light">
                            <div class="card-body setup-bill-section">
                                <div>
                                    <div class="rent-amount">${{ number_format($addressDetails['rentAmount'] ?? 0, 2) }}
                                    </div>
                                    <div class="rent-label">
                                        {{ Auth::user()->account_type == 'rent' ? 'Rent' : 'Mortgage' }} Amount</div>
                                </div>
                            </div>

                            <!-- Additional Details -->
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                <div class="fs-6 d-flex justify-content-between">
                                    <span class="fw-semibold">Nature of Transaction</span>
                                    <span class="fw-bold text-capitalize">{{ Auth::user()->account_type }}</span>
                                </div>
                            </div>
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                <div class="fw-semibold">Contract Address</div>
                                <div class="text-gray-500">Address: {{ $addressDetails['address'] }}</div>
                                <div class="text-gray-500">Province: {{ $addressDetails['province'] }}</div>
                                <div class="text-gray-500">City: {{ $addressDetails['city'] }}</div>
                                <div class="text-gray-500">Postal Code: {{ $addressDetails['postalCode'] }}</div>
                            </div>
                        </div>

                        {{-- @if ($account_details['plan'] == 'pay_mortgage_build' || $account_details['plan'] == 'pay_rent_build')
                            <div class="card border-0 rounded card-2 shadow" data-bs-theme="light"
                                style="background: #fff; padding: 20px;">
                                <div class="card-body">
                                    <!-- Toggle Section -->
                                    <div class="d-flex justify-content-center align-items-center mb-4" style="gap: 10px;">
                                        <span class="fw-semibold fs-5 text-black"
                                            style="margin-right: 10px;font-weight:900 !important">Equifax</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="scoreToggle"
                                                style="transform: scale(1.2);">
                                        </div>
                                        <span class="fw-semibold fs-5 text-black"
                                            style="margin-left: 10px; font-weight:900 !important">Transunion</span>
                                    </div>
                                </div>
                            </div>



                            <div class="card border-0 rounded card-2" data-bs-theme="light">
                                <div class="card-body">
                                    <!-- Transunion Card -->
                                    <div class="rounded text-center d-none" id="transunionCard">
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                            <div class="fs-6 d-flex justify-content-between">
                                                <span class="fw-semibold">Credit Score</span>
                                                <span class="fw-bold text-capitalize">560</span>
                                            </div>
                                        </div>

                                        <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                            <div class="fs-6 d-flex justify-content-between">
                                                <span class="fw-semibold">Last Update</span>
                                                <span class="fw-bold text-capitalize">May 06, 2024</span>
                                            </div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                            <div class="fs-6 d-flex justify-content-between">
                                                <span class="fw-semibold">Next Update</span>
                                                <span class="fw-bold text-capitalize">Dec 06, 2024</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Equifax Card -->
                                    <div class="rounded text-center d-none" id="equifaxCard">

                                        <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                            <div class="fs-6 d-flex justify-content-between">
                                                <span class="fw-semibold">Credit Score</span>
                                                <span class="fw-bold text-capitalize">600</span>
                                            </div>
                                        </div>

                                        <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                            <div class="fs-6 d-flex justify-content-between">
                                                <span class="fw-semibold">Last Update</span>
                                                <span class="fw-bold text-capitalize">May 07, 2024</span>
                                            </div>
                                        </div>
                                        <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                            <div class="fs-6 d-flex justify-content-between">
                                                <span class="fw-semibold">Next Update</span>
                                                <span class="fw-bold text-capitalize">Dec 26, 2024</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif --}}
                        @if ($account_details['plan'] == 'pay_mortgage_build' || $account_details['plan'] == 'pay_rent_build')
                            <div class="card border-0 rounded card-2 shadow" data-bs-theme="light"
                                style="background: #fff; padding: 20px;">
                                <div class="card-body">
                                    <!-- Toggle Section -->
                                    <div class="d-flex justify-content-center align-items-center mb-4" style="gap: 10px;">
                                        <span class="fw-semibold fs-5 text-black"
                                            style="margin-right: 10px;font-weight:900 !important">Equifax</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="scoreToggle"
                                                style="transform: scale(1.2);">
                                        </div>
                                        <span class="fw-semibold fs-5 text-black"
                                            style="margin-left: 10px; font-weight:900 !important">TransUnion</span>
                                    </div>
                                    <canvas id="creditGauge"></canvas>
                                    <p class="mt-3 fw-bold text-gray-800 fs-5" id="creditScoreLabel"></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header border-0 justify-content-end">
                    <!-- Edit Button -->
                    {{-- <button type="button" class="btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-primary me-2"
                        id="editEvent" data-bs-toggle="tooltip" title="Edit Event">
                        <i class="bi bi-pencil fs-4"></i>
                    </button> --}}
                    <!-- Delete Button -->
                    {{-- <button type="button" class="btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-danger me-2"
                        id="deleteEvent" data-bs-toggle="tooltip" title="Delete Event">
                        <i class="bi bi-trash fs-4"></i>
                    </button> --}}
                    <!-- Close Button -->
                    <button type="button"
                        class="btn-modal-close btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-primary"
                        data-bs-dismiss="modal" title="Close">
                        <i class="fa fa-close fs-4"></i>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 pb-20 px-lg-17">
                    <!-- Event Details -->
                    <div class="d-flex">
                        <i class="fa fa-calendar fs-1 text-muted me-5"></i>
                        <div>
                            <!-- Event Name -->
                            <div class="d-flex align-items-center mb-2">
                                <span class="fs-3 fw-bold me-3" id="eventTitle">Event Title</span>
                                <span class="badge badge-light-success" id="allDayBadge" style="display: none;">All
                                    Day</span>
                            </div>
                            <!-- Event Description -->
                            {{-- <div class="fs-6 text-muted" id="eventDescription">Event Description</div> --}}
                        </div>
                    </div>
                    <!-- Event Start Time -->
                    <div class="d-flex align-items-center mb-2">
                        <span class="bullet bullet-dot h-10px w-10px bg-success ms-2 me-7"></span>
                        <div class="fs-6">
                            <span class="fw-bold">Starts:</span> <span id="eventStart"></span>
                        </div>
                    </div>
                    <!-- Event End Time -->
                    <div class="d-flex align-items-center mb-9">
                        <span class="bullet bullet-dot h-10px w-10px bg-danger ms-2 me-7"></span>
                        <div class="fs-6">
                            <span class="fw-bold">Ends:</span> <span id="eventEnd"></span>
                        </div>
                    </div>
                    <!-- Event Location -->
                    <div class="d-flex align-items-center">
                        <i class="fa fa-location-dot fs-1 text-muted me-5"></i>
                        <div class="fs-6 text-muted" id="eventDescription">Event Location</div>
                        {{-- <div class="fs-6 text-muted" id="eventLocation">Event Location</div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.index-script')
@endsection
