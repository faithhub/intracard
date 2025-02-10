@extends('app-user')
@section('content')
    @php
        $addressDetails = json_decode(Auth::user()->address_details, true);
        $landloard = json_decode(Auth::user()->landlord_or_finance_details, true);
        $account_details = json_decode(Auth::user()->account_details, true);
        $finance = json_decode(Auth::user()->landlord_or_finance_details, true);
    @endphp
    <style>
        canvas {
            width: 100%;
            max-width: 300px;
            height: auto;
            margin: 0 auto;
            display: block;
        }

        .fw-bold {
            font-weight: 700;
        }

        .text-gray-800 {
            color: #343a40;
        }
    </style>

    <style>
        /* Change cursor to pointer when hovering over events */
        .fc-event {
            cursor: pointer;
        }

        /* Optional: Add hover effect to highlight the event */
        .fc-event:hover {
            box-shadow: 0px 4px 6px rgba(206, 202, 202, 0.2);
        }

        .card .card-body {
            padding: 0rem;
        }

        .form-switch .form-check-input {
            transition: background-position .40s cubic-bezier(0.18, 0.42, 0.24, 0.55) !important;
        }

        .form-check-input {
            border: 0px;
            height: 2.25rem !important;
        }

        .form-switch .form-check-input {
            width: 5.25rem;
        }

        .card {
            width: 100%;
            /* Adjust the percentage as needed */
            margin: auto;
            /* Center the card */
            padding: 2rem;
        }

        #kt_app_content_container {
            /* max-width: 1200px; */
            /* Adjust as needed */
            margin: auto;
            /* Center the container */
        }

        .card-1 {
            background-color: #43367b;
            color: white;
            margin: 1rem;
            box-shadow: inset 1px 15px 20px 8px rgb(11 33 40 / 89%);
            -webkit-box-shadow: -1px 10px 20px 12px rgba(93, 125, 135, 0.78);
            -moz-box-shadow: -1px 13px 30px 0px rgba(93, 125, 135, 0.78);
        }

        .card-2 {
            background-color: #43367b;
            color: white;
            margin: 1rem;
            box-shadow: inset 1px 15px 20px 8px rgb(11 33 40 / 89%);
            -webkit-box-shadow: -1px 10px 20px 12px rgba(93, 125, 135, 0.78);
            -moz-box-shadow: -1px 13px 30px 0px rgba(93, 125, 135, 0.78);
        }

        .card-3 {
            background-color: #43367b;
            color: white;
            margin: 1rem;
            box-shadow: 1px 11px 39px 16px rgba(159, 131, 225, 0.76);
            -webkit-box-shadow: 1px 11px 39px 16px rgba(159, 131, 225, 0.76);
            -moz-box-shadow: 1px 11px 39px 16px rgba(159, 131, 225, 0.76);
        }

        .form-check-input {
            border-color: #a3b1aa8c;
            background-color: #a3b1aa8c;
        }

        .form-check-input:checked {
            background-color: #000;
            border-color: #000;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .fw-light {
            font-weight: 300 !important;
        }

        .fw-bold {
            font-weight: 600 !important;
        }

        #calendar {
            /* max-width: 1100px; */
            margin: auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .fc-view-multiMonthFourMonth {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* Show 2 months per row */
            gap: 20px;
            /* Spacing between months */
        }

        .fc-license-message {
            display: none !important;
        }

        .nav-item .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            /* Space between the icon and text */
        }

        .nav-item span {
            border-radius: 4px;
            /* Rounded corners for the indicators */
        }
    </style>

    <!-- Tempus Dominus CSS -->
    {{-- <link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'> --}}
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.css" rel="stylesheet">
    <!-- FullCalendar JS -->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="">
            <!-- Begin: Content -->
            <div id="kt_account_settings_profile_details">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card border-0 card-1 mt-5 text-black" data-bs-theme="light"
                            style="background-color: #f8f6f2">
                            <div class="card-body">
                                <ul class="nav py-3">
                                    <!-- Payment Duration -->
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-gray-600 fw-bold px-4 me-1"
                                            style="font-size: 12px;">
                                            <span
                                                style="width: 20px; height: 20px; background-color: #210035; display: inline-block; border-radius: 4px;"></span>
                                            <span class="ms-2">Payment Duration</span>
                                        </a>
                                    </li>
                                    <!-- Bills Payment -->
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-gray-600 fw-bold px-4 me-1"
                                            style="font-size: 12px;">
                                            <span
                                                style="width: 20px; height: 20px; background-color: #FFC53D; display: inline-block; border-radius: 4px;"></span>
                                            <span class="ms-2">Bills Payment</span>
                                        </a>
                                    </li>
                                    <!-- Last Payments -->
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-gray-600 fw-bold px-4 me-1"
                                            style="font-size: 12px;">
                                            <span
                                                style="width: 20px; height: 20px; background-color: #133f1a; display: inline-block; border-radius: 4px;"></span>
                                            <span class="ms-2">Last Payments</span>
                                        </a>
                                    </li>
                                    <!-- Upcoming Payments -->
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-gray-600 fw-bold px-4 me-1"
                                            style="font-size: 12px;">
                                            <span
                                                style="width: 20px; height: 20px; background-color: #40C057; display: inline-block; border-radius: 4px;"></span>
                                            <span class="ms-2">Upcoming Payments</span>
                                        </a>
                                    </li>
                                    <!-- Reminder -->
                                    {{-- <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-gray-600 fw-bold px-4 me-1"
                                                style="font-size: 12px;">
                                                <span
                                                    style="width: 20px; height: 20px; background-color: #2F54EB; display: inline-block; border-radius: 4px;"></span>
                                                <span class="ms-2">Reminder</span>
                                            </a>
                                        </li> --}}
                                </ul>
                                <div style="background-color: transparent">
                                    <div id='calendar2'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 card-1" data-bs-theme="light">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <!-- Rent Amount Section -->
                                <div>
                                    <div class="fs-2hx fw-bold">${{ number_format($addressDetails['rentAmount'] ?? 0, 2) }}</div>
                                    <div class="fs-4 fw-semibold text-gray-500 mb-3">
                                        {{ Auth::user()->account_type == 'rent' ? 'Rent' : (Auth::user()->account_type == 'mortgage' ? 'Mortgage' : '') }}
                                        Amount
                                    </div>
                                </div>
                        
                                <!-- Setup Bill Button -->
                                <div>
                                    <button class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-sm rounded-pill">
                                        Setup Bill
                                    </button>
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
                                <div class="fw-semibold mb-2">Contract Address</div>
                                <div class="text-gray-500">Address: {{ $addressDetails['address'] }}</div>
                                <div class="text-gray-500">Province: {{ $addressDetails['province'] }}</div>
                                <div class="text-gray-500">City: {{ $addressDetails['city'] }}</div>
                                <div class="text-gray-500">Postal Code: {{ $addressDetails['postalCode'] }}</div>
                            </div>
                        </div>
                        
                        <div class="card border-0 card-1" data-bs-theme="light">
                            <div class="card-body text-center">
                                <button class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-sm rounded-pill">
                                    Setup Bill
                                </button>
                            </div>
                        </div>
                        <div class="card border-0 card-1" data-bs-theme="light">
                            <div class="card-body">
                                <div class="fs-2hx fw-bold">${{ number_format($addressDetails['rentAmount'] ?? 0, 2) }}
                                </div>
                                <div class="fs-4 fw-semibold text-gray-500 mb-3">
                                    {{ Auth::user()->account_type == 'rent' ? 'Rent' : (Auth::user()->account_type == 'mortgage' ? 'Mortgage' : '') }}
                                    Amount
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                    <div class="fs-6 d-flex justify-content-between">
                                        <span class="fw-semibold">Nature of Transaction</span>
                                        <span class="fw-bold text-capitalize">{{ Auth::user()->account_type }}</span>
                                    </div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                    <div class="fw-semibold mb-2">Contract Address</div>
                                    <div class="text-gray-500">Address: {{ $addressDetails['address'] }}</div>
                                    <div class="text-gray-500">Province: {{ $addressDetails['province'] }}</div>
                                    <div class="text-gray-500">City: {{ $addressDetails['city'] }}</div>
                                    <div class="text-gray-500">Postal Code: {{ $addressDetails['postalCode'] }}</div>
                                </div>
                                {{-- <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                    <div class="fw-semibold mb-2">Last Payment</div>
                                    <table class="table table-borderless text-white">
                                        <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>$718.00</td>
                                                <td>Apr 15, 2024</td>
                                                <td><span class="badge badge-light-success">Paid</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                    <div class="fw-semibold mb-2">Current Payment Due</div>
                                    <table class="table table-borderless text-white">
                                        <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>$700.00</td>
                                                <td>June 15, 2024</td>
                                                <td><span class="badge badge-light-warning">Pending</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> --}}
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
                                </div>
                            </div>

                            <div class="card border-0 rounded card-2" data-bs-theme="light">
                                <div class="card-body text-center">
                                    <canvas id="creditGauge" width="300" height="300"
                                        data-animation-duration="3500"></canvas>
                                    <p class="mt-3 fw-bold text-gray-800 fs-5" id="creditScoreLabel">600</p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="row mb-5" style="padding: 2rem;">
                    <!-- Left Column -->
                    <div class="col-lg-6"></div>
                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <div class="card border-0 mb-4 card-2" data-bs-theme="light"
                            style="min-height: 300px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div class="card-body">
                                <div class="fs-2hx fw-bold">Setup Bill</div>
                                <!-- Select Dropdown -->
                                <select name="bill_type" class="form-select form-select-solid mb-5 mt-3"
                                    id="billTypeSelect">
                                    <option value="">Select Bill</option>
                                    <option value="Car Bill">Car Bill</option>
                                    <option value="Utility Bill" selected>Utility Bill</option>
                                    <option value="Phone Bill">Phone Bill</option>
                                    <option value="Internet Bill">Internet Bill</option>
                                </select>

                                <!-- Car Bill Details -->
                                <div id="carBillDetails" class="bill-details d-none">
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Amount</div>
                                        <div class="text-gray-500">$5,000.00</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                                <div class="fw-semibold mb-1">Frequency</div>
                                                {{-- <div class="text-gray-500">Bi-weekly</div> --}}
                                                <select name="" class="form-select form-select-solid"
                                                    style="padding: .775rem 3rem .775rem 1rem; padding-top: 0.175rem; padding-right: 1rem; padding-bottom: 0.175rem; padding-left: 1rem;">
                                                    <option value="">Select</option>
                                                    <option value="Bi-weekly">Bi-weekly</option>
                                                    <option value="Monthly">Monthly</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                                <div class="fw-semibold mb-1">Due date</div>
                                                <input type="date" class="form-control form-control-solid"
                                                    name="" id="biWeeklyDueDateInput"
                                                    style="padding: .775rem 3rem .775rem 1rem;
                                                padding-top: 0.175rem;
                                                padding-right: 1rem;
                                                padding-bottom: 0.175rem;
                                                padding-left: 1rem;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Car Model</div>
                                        <div class="text-gray-500">Toyota Corolla</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Car Year</div>
                                        <div class="text-gray-500">2020</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Car VIN</div>
                                        <div class="text-gray-500">1HGCM82633A123456</div>
                                    </div>
                                </div>

                                <!-- Utility Bill Details -->
                                <div id="utilityBillDetails" class="bill-details d-block">
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Amount</div>
                                        <div class="text-gray-500">$5,00.00</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Due Date</div>
                                        <div class="text-gray-500">7th</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Provider</div>
                                        <div class="text-gray-500">ABC Power Company</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Account Number</div>
                                        <div class="text-gray-500">123456789</div>
                                    </div>
                                </div>

                                <!-- Phone Bill Details -->
                                <div id="phoneBillDetails" class="bill-details d-none">
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Amount</div>
                                        <div class="text-gray-500">$9,00.00</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Due Date</div>
                                        <div class="text-gray-500">5th</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Account Number</div>
                                        <div class="text-gray-500">987654321</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Provider</div>
                                        <div class="text-gray-500">XYZ Mobile</div>
                                    </div>
                                </div>

                                <!-- Internet Bill Details -->
                                <div id="internetBillDetails" class="bill-details d-none">
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Amount</div>
                                        <div class="text-gray-500">$7,00.00</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Due Date</div>
                                        <div class="text-gray-500">10th</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Provider</div>
                                        <div class="text-gray-500">NetFast Internet</div>
                                    </div>
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-4">
                                        <div class="fw-semibold">Account Number</div>
                                        <div class="text-gray-500">987654321</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="container mt-5">
                    <div class="row justify-content-center">
                        <!-- TransUnion Gauge -->
                        <div class="col-md-6 mb-4 text-center">
                            <h5 class="fw-bold">TransUnion</h5>
                            <canvas id="transunionGauge"></canvas>
                            <p class="mt-3 fw-bold text-gray-800 fs-5">750</p>
                        </div>

                        <!-- Equifax Gauge -->
                        <div class="col-md-6 mb-4 text-center">
                            <h5 class="fw-bold">Equifax</h5>
                            <canvas id="equifaxGauge"></canvas>
                            <p class="mt-3 fw-bold text-gray-800 fs-5">680</p>
                        </div>
                    </div>
                </div>
                <canvas id="canvas-id" data-type="radial-gauge" data-width="300" data-height="300" data-units="Km/h"
                    data-min-value="0" data-max-value="220" data-major-ticks="0,20,40,60,80,100,120,140,160,180,200,220"
                    data-minor-ticks="2" data-stroke-ticks="true"
                    data-highlights='[
                        {"from": 160, "to": 220, "color": "rgba(200, 50, 50, .75)"}
                    ]'
                    data-color-plate="#fff" data-border-shadow-width="0" data-borders="false" data-needle-type="arrow"
                    data-needle-width="2" data-needle-circle-size="7" data-needle-circle-outer="true"
                    data-needle-circle-inner="false" data-animation-duration="1500"
                    data-animation-rule="linear"></canvas> --}}

            </div>
        </div>
        <!-- End: Content -->
    </div>
    </div>
    <!-- Event Details Modal -->
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
                    <button type="button" class="btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-primary"
                        data-bs-dismiss="modal" title="Close">
                        <i class="bi bi-x-lg fs-4"></i>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 pb-20 px-lg-17">
                    <!-- Event Details -->
                    <div class="d-flex">
                        <i class="bi bi-calendar-event fs-1 text-muted me-5"></i>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-gauges@2.1.7/gauge.min.js"></script>



    <script>
        // Function to render a gauge with animation
        function createGauge(renderTo, value, title) {
            return new RadialGauge({
                renderTo: renderTo, // ID of the canvas
                width: 300,
                height: 300,
                units: title, // Title of the gauge (e.g., Credit Score)
                minValue: 300, // Minimum score
                maxValue: 900, // Maximum score
                majorTicks: ["300", "400", "500", "600", "700", "800", "900"], // Ticks on the gauge
                minorTicks: 2,
                strokeTicks: true,
                highlights: [{
                        from: 300,
                        to: 500,
                        color: "#FF4D4F"
                    }, // Poor range (red)
                    {
                        from: 500,
                        to: 700,
                        color: "#FFC53D"
                    }, // Fair range (yellow)
                    {
                        from: 700,
                        to: 900,
                        color: "#40C057"
                    }, // Good range (green)
                ],
                colorPlate: "#f0f0f0", // Background color
                colorMajorTicks: "#000",
                colorMinorTicks: "#333",
                colorNumbers: "#000",
                colorNeedle: "black",
                colorNeedleEnd: "black",
                needleType: "arrow",
                needleWidth: 3,
                animationDuration: 1500, // Animation duration in milliseconds
                animationRule: "easeOutBounce", // Smooth animation
                value: 300, // Start needle at the minimum value
            }).draw();
        }

        // Initialize gauges
        const transunionGauge = createGauge("transunionGauge", 750, "TransUnion");
        const equifaxGauge = createGauge("equifaxGauge", 680, "Equifax");

        // Animate the needle to the final value
        setTimeout(() => {
            transunionGauge.update({
                value: 750
            }); // Final score for TransUnion
            equifaxGauge.update({
                value: 680
            }); // Final score for Equifax
        }, 500); // Delay before animation starts

        // TransUnion Speedometer
        // new RadialGauge({
        //     renderTo: 'transunionGauge', // ID of the canvas
        //     width: 300,
        //     height: 300,
        //     units: "Credit Score",
        //     minValue: 300, // Minimum score
        //     maxValue: 900, // Maximum score
        //     majorTicks: ["300", "400", "500", "600", "700", "800", "900"], // Ticks on the gauge
        //     minorTicks: 2,
        //     strokeTicks: true,
        //     highlights: [{
        //             from: 300,
        //             to: 500,
        //             color: '#FF4D4F'
        //         }, // Poor range (red)
        //         {
        //             from: 500,
        //             to: 700,
        //             color: '#FFC53D'
        //         }, // Fair range (yellow)
        //         {
        //             from: 700,
        //             to: 900,
        //             color: '#40C057'
        //         }, // Good range (green)
        //     ],
        //     colorPlate: "#f0f0f0", // Background color
        //     colorMajorTicks: "#000",
        //     colorMinorTicks: "#333",
        //     colorNumbers: "#000",
        //     colorNeedle: "black",
        //     colorNeedleEnd: "black",
        //     needleType: "arrow",
        //     needleWidth: 3,
        //     animationDuration: 1500, // Animation duration
        //     animationRule: "bounce",
        //     value: 750, // Current credit score
        // }).draw();

        // // Equifax Speedometer
        // new RadialGauge({
        //     renderTo: 'equifaxGauge', // ID of the canvas
        //     width: 300,
        //     height: 300,
        //     units: "Credit Score",
        //     minValue: 300, // Minimum score
        //     maxValue: 900, // Maximum score
        //     majorTicks: ["300", "400", "500", "600", "700", "800", "900"], // Ticks on the gauge
        //     minorTicks: 2,
        //     strokeTicks: true,
        //     highlights: [{
        //             from: 300,
        //             to: 500,
        //             color: '#FF4D4F'
        //         }, // Poor range (red)
        //         {
        //             from: 500,
        //             to: 700,
        //             color: '#FFC53D'
        //         }, // Fair range (yellow)
        //         {
        //             from: 700,
        //             to: 900,
        //             color: '#40C057'
        //         }, // Good range (green)
        //     ],
        //     colorPlate: "#f0f0f0", // Background color
        //     colorMajorTicks: "#000",
        //     colorMinorTicks: "#333",
        //     colorNumbers: "#000",
        //     colorNeedle: "black",
        //     colorNeedleEnd: "black",
        //     needleType: "arrow",
        //     needleWidth: 3,
        //     animationDuration: 1500, // Animation duration
        //     animationRule: "bounce",
        //     value: 680, // Current credit score
        // }).draw();
    </script>

    <script>
        // var gauge = new RadialGauge({
        //     renderTo: 'canvas-id',
        //     width: 300,
        //     height: 300,
        //     units: "Km/h",
        //     minValue: 0,
        //     maxValue: 220,
        //     majorTicks: [
        //         "0",
        //         "20",
        //         "40",
        //         "60",
        //         "80",
        //         "100",
        //         "120",
        //         "140",
        //         "160",
        //         "180",
        //         "200",
        //         "220"
        //     ],
        //     minorTicks: 2,
        //     strokeTicks: true,
        //     highlights: [{
        //         "from": 160,
        //         "to": 220,
        //         "color": "rgba(200, 50, 50, .75)"
        //     }],
        //     colorPlate: "#fff",
        //     borderShadowWidth: 0,
        //     borders: false,
        //     needleType: "arrow",
        //     needleWidth: 2,
        //     needleCircleSize: 7,
        //     needleCircleOuter: true,
        //     needleCircleInner: false,
        //     animationDuration: 1500,
        //     animationRule: "linear",
        //     value: 80 // Set the initial value to 80
        // }).draw();

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar2');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay,listMonth,multiMonthFourMonth'
                },
                views: {
                    multiMonthFourMonth: {
                        type: 'multiMonth',
                        duration: {
                            months: 12
                        },
                        buttonText: 'Year', // Label for the button
                    }
                },
                events: "{{ route('getCalendarEvents') }}",
                eventClick: function(info) {
                    // Prevent the default behavior
                    info.jsEvent.preventDefault();

                    // Populate modal with event details
                    document.getElementById('eventTitle').innerText = info.event.title;
                    document.getElementById('eventDescription').innerText =
                        info.event.extendedProps.description || 'No description provided.';
                    document.getElementById('eventStart').innerText = info.event.start.toLocaleString();
                    document.getElementById('eventEnd').innerText = info.event.end ?
                        info.event.end.toLocaleString() :
                        'No end time';
                    // document.getElementById('eventLocation').innerText =
                    //     info.event.extendedProps.location || 'No location provided.';

                    // Show "All Day" badge if the event is all-day
                    const allDayBadge = document.getElementById('allDayBadge');
                    if (info.event.allDay) {
                        allDayBadge.style.display = 'inline';
                    } else {
                        allDayBadge.style.display = 'none';
                    }

                    // Show the modal
                    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                        keyboard: true,
                    });
                    eventModal.show();
                },
            });

            calendar.render();
        });

        // Function to handle bill display logic
        function updateBillDetails() {
            // Hide all bill details sections
            document.querySelectorAll('.bill-details').forEach(function(element) {
                element.classList.add('d-none');
            });

            // Get the selected value
            const selectedValue = document.getElementById('billTypeSelect').value;

            // Show the corresponding bill details based on selection
            if (selectedValue === "Car Bill") {
                document.getElementById('carBillDetails').classList.remove('d-none');
            } else if (selectedValue === "Utility Bill") {
                document.getElementById('utilityBillDetails').classList.remove('d-none');
            } else if (selectedValue === "Phone Bill") {
                document.getElementById('phoneBillDetails').classList.remove('d-none');
            } else if (selectedValue === "Internet Bill") {
                document.getElementById('internetBillDetails').classList.remove('d-none');
            }
        }

        // Add event listener for changes to the select element
        document.getElementById('billTypeSelect').addEventListener('change', function() {
            updateBillDetails()
        });

        // Run the update logic on page load
        // document.addEventListener('DOMContentLoaded', function() {
        //     var scoreToggle = document.getElementById('scoreToggle');
        //     var equifaxCard = document.getElementById('equifaxCard');
        //     var transunionCard = document.getElementById('transunionCard');

        //     // Function to handle card visibility
        //     function updateCardsVisibility() {
        //         if (scoreToggle.checked) {
        //             equifaxCard.classList.add('d-none');
        //             transunionCard.classList.remove('d-none');
        //         } else {
        //             equifaxCard.classList.remove('d-none');
        //             transunionCard.classList.add('d-none');
        //         }
        //     }

        //     // Initialize visibility on page load
        //     updateCardsVisibility();

        //     // Add event listener for the toggle
        //     scoreToggle.addEventListener('change', function() {
        //         updateCardsVisibility();
        //     });
        // });

        document.addEventListener('DOMContentLoaded', function() {
            const scoreToggle = document.getElementById('scoreToggle');
            const creditScoreLabel = document.getElementById('creditScoreLabel');
            const creditGauge = new RadialGauge({
                renderTo: 'creditGauge', // Target canvas ID
                width: 300,
                height: 300,
                units: "Credit Score",
                minValue: 300,
                maxValue: 900,
                majorTicks: ["300", "400", "500", "600", "700", "800", "900"],
                minorTicks: 2,
                strokeTicks: true,
                highlights: [{
                        from: 300,
                        to: 500,
                        color: "#FF4D4F"
                    }, // Poor range (red)
                    {
                        from: 500,
                        to: 700,
                        color: "#FFC53D"
                    }, // Fair range (yellow)
                    {
                        from: 700,
                        to: 900,
                        color: "#40C057"
                    }, // Good range (green)
                ],
                colorPlate: "#f0f0f0",
                colorMajorTicks: "#000",
                colorMinorTicks: "#333",
                colorNumbers: "#000",
                colorNeedle: "black",
                colorNeedleEnd: "black",
                needleType: "arrow",
                needleWidth: 3,
                animationDuration: 9000, // Slower animation duration (3 seconds)
                animationRule: "linear",
                value: 300, // Start at the minimum value
            }).draw();

            // Function to update the gauge and label
            function updateGauge(creditScore) {
                creditGauge.update({
                    value: creditScore
                });
                creditScoreLabel.textContent = creditScore;
            }

            // Initialize toggle behavior
            function updateCardsVisibility() {
                if (scoreToggle.checked) {
                    // Display TransUnion score
                    updateGauge(560); // TransUnion score
                } else {
                    // Display Equifax score
                    updateGauge(600); // Equifax score
                }
            }

            // Set initial visibility and animation
            updateCardsVisibility();

            // Add event listener for the toggle switch
            scoreToggle.addEventListener('change', function() {
                updateCardsVisibility();
            });
        });
    </script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.js"></script>
    {{-- <script src="https://preview.keenthemes.com/good/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script> --}}
@endsection
