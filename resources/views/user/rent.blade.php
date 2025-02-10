@extends('app-user')
@section('content')
    @php
        $addressDetails = json_decode(Auth::user()->address_details, true);
        $account_details = json_decode(Auth::user()->account_details, true);
        $landloard = json_decode(Auth::user()->landlord_or_finance_details, true);
        $finance = json_decode(Auth::user()->landlord_or_finance_details, true);
    @endphp
    <style>
        .text-gray-500 {
            font-size: 12px;
        }

        @media only screen and (max-width: 768px) {
            .card {
                margin: 0 0 !important;
                padding: 10px !important;
                font-size: 14px !important;
                width: 100% !important;
            }
        }

        @media (max-width: 767.98px) {

            /* Adjust the breakpoint as per your design system */
            .card .card-header .card-title {
                margin: 0;
                /* Remove all margin on mobile */
            }
        }

        @media (min-width: 1198px) {
            .custom-max-width {
                max-width: 550px;
                /* Uncomment the line below to center it */
                /* margin: 0 auto; */
            }
        }

        /* Optional: Handle responsiveness for smaller screens */
        @media (max-width: 767px) {
            .address-details {
                flex-direction: column;
                /* Stack columns vertically on mobile */
            }
        }

        .address-details {
            display: flex;
            gap: 1rem;
            /* Space between columns */
        }

        .address-details>div {
            flex: 1;
        }

        .address-item {
            display: flex;
            align-items: center;
            position: relative;
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            /* Offset content for the line */
        }

        .address-item .line {
            position: absolute;
            left: 0;
            /* Align to the left */
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: #e2e2e2;
            /* Adjust to your preferred color */
            border-radius: 2px;
        }

        /* Full width for the Address section */
        .full-row {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            position: relative;
            width: 100%;
        }

        /* Vertical line styling */
        .with-line .line {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: #e2e2e2;
            border-radius: 2px;
        }

        /* Two-column layout for other details */
        .address-details {
            display: flex;
            gap: 1rem;
            /* Space between columns */
        }

        .address-details>div {
            flex: 1;
            /* Equal width for both columns */
        }

        .address-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            /* Offset content for the line */
        }

        /* Adjust width for larger screens */
        @media (min-width: 1198px) {
            .custom-max-width-address {
                max-width: 550px;
            }
        }

        /* Handle responsiveness for smaller screens */
        @media (max-width: 767px) {
            .address-details {
                flex-direction: column;
                /* Stack columns vertically */
            }
        }
    </style>
    <div id="kt_app_content_container" class="app-container  container-fluid">
        <div id="kt_account_settings_profile_details">
            <div class="card">
                <div class="card-header card-header-stretch">
                    <div class="card-title d-flex align-items-center">
                        <i class="fa fa-house fs-1 text-gray me-3 lh-0"></i>
                        <h3 class="fw-bold m-0 text-gray-800">Rent Details</h3>
                    </div>
                    <div class="card-toolbar m-0"></div>
                </div>

                <div class="card-body" style="padding: 1rem 1.25rem">
                    <div class="tab-content">
                        <div id="kt_activity_today" class="card-body p-0 tab-pane fade active show">

                            <div class="d-flex flex-column flex-xl-row gap-7 mb-5 gap-lg-3">
                                <!--begin::Order details-->
                                <div class="card card-flush flex-row-fluid custom-max-width">
                                    <!--begin::Card header-->
                                    <div class="card-header"
                                        style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                        <div class="card-title">

                                            <div class="timeline-item">
                                                <div class="timeline-line"></div>
                                                <div class="timeline-content mb-10 mt-n1">
                                                    <div class="pe-3 mb-5">
                                                        <div class="fs-5 fw-semibold mb-2">
                                                            <i class="fa fa-house-user fs-2 text-gray-500"></i>
                                                            @isset($account_details['plan'])
                                                                @if ($account_details['plan'] == 'pay_rent')
                                                                    Pay Rent
                                                                @endif
                                                                @if ($account_details['plan'] == 'pay_rent_build')
                                                                    Pay Rent and build credit
                                                                @endif
                                                            @endisset
                                                        </div>

                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Application Type
                                                                </div>
                                                                <b
                                                                    class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    @isset($account_details['applicationType'])
                                                                        @if ($account_details['applicationType'] == 'sole_applicant')
                                                                            Sole Applicant
                                                                        @endif
                                                                        @if ($account_details['applicationType'] == 'co_applicant')
                                                                            Co-Applicant
                                                                        @endif
                                                                    @endisset
                                                                </b>
                                                            </div>
                                                        </div>


                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Payment Setup
                                                                </div>
                                                                <b
                                                                    class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    @isset($account_details['paymentSetup'])
                                                                        @if ($account_details['paymentSetup'] == 'Continue_paying_existing_rent')
                                                                            Paying existing rent
                                                                        @endif
                                                                        @if ($account_details['paymentSetup'] == 'Setup_payment_new_rental')
                                                                            Payment for new rent
                                                                        @endif
                                                                    @endisset
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Rent Amount
                                                                </div>
                                                                <b
                                                                    class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    ${{ number_format($addressDetails['rentAmount'] ?? 0, 2) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Timeline details-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Order details-->

                                @isset($account_details['plan'])
                                    @if ($account_details['plan'] == 'pay_rent_build')
                                        <!--begin::Order details-->
                                        <div class="card card-flush flex-row-fluid custom-max-width">
                                            <!--begin::Card header-->
                                            <div class="card-header"
                                                style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                                <div class="card-title">

                                                    <div class="timeline-item">
                                                        <div class="timeline-line"></div>
                                                        <div class="timeline-content mb-10 mt-n1">
                                                            <div class="pe-3 mb-5">
                                                                <div class="fs-5 fw-semibold mb-2">
                                                                    <i class="fa fa-house-user fs-2 text-gray-500"></i>
                                                                    Build Credit
                                                                </div>
                                                                <div class="d-flex flex-stack position-relative mt-8">
                                                                    <div
                                                                        class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                    </div>
                                                                    <div class="fw-semibold ms-5 text-gray-600">
                                                                        <div class="text-gray-500">
                                                                            Credit Card limit
                                                                        </div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            ${{ number_format($account_details['creditCardLimit'], 2) }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex flex-stack position-relative mt-8">
                                                                    @php
                                                                        $CCday =
                                                                            (int) $account_details['creditCardDueDate']; // Convert to integer to handle any string input
                                                                        $CCsuffix = 'th'; // Default suffix

                                                                        // Determine the suffix based on the day
                                                                        if ($CCday % 10 == 1 && $CCday != 11) {
                                                                            $CCsuffix = 'st';
                                                                        } elseif ($CCday % 10 == 2 && $CCday != 12) {
                                                                            $CCsuffix = 'nd';
                                                                        } elseif ($CCday % 10 == 3 && $CCday != 13) {
                                                                            $CCsuffix = 'rd';
                                                                        }
                                                                    @endphp
                                                                    <div
                                                                        class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                    </div>
                                                                    <div class="fw-semibold ms-5 text-gray-600">
                                                                        <div class="text-gray-500">
                                                                            Credit Card due date
                                                                        </div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            {{ $CCday . $CCsuffix }}
                                                                        </b>
                                                                    </div>
                                                                </div>

                                                                @isset($account_details['applicationType'])
                                                                    @if ($account_details['applicationType'] == 'co_applicant')
                                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                                            <div
                                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                            </div>
                                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                                <div class="text-gray-500">
                                                                                    Primary applicant rent amount
                                                                                </div>
                                                                                <b
                                                                                    class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                    ${{ number_format($account_details['primaryRentOrMortgageAmount'], 2) }}
                                                                                </b>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endisset
                                                            </div>
                                                            <!--end::Timeline details-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Card header-->
                                        </div>
                                        <!--end::Order details-->
                                    @endif
                                @endisset


                                @isset($account_details['applicationType'])
                                    @if ($account_details['applicationType'] == 'co_applicant')
                                        <div class="card card-flush flex-row-fluid custom-max-width">
                                            <!--begin::Card header-->
                                            <div class="card-header"
                                                style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                                <div class="card-title">
                                                    <div class="timeline-item">
                                                        <!--begin::Timeline line-->
                                                        <div class="timeline-line"></div>
                                                        <!--end::Timeline line-->
                                                        <!--begin::Timeline icon-->
                                                        <div class="timeline-icon">
                                                        </div>
                                                        <!--end::Timeline icon-->

                                                        <!--begin::Timeline content-->
                                                        <div class="timeline-content mb-10 mt-n1">
                                                            <!--begin::Timeline heading-->
                                                            <div class="pe-3 mb-5">
                                                                <!--begin::Title-->
                                                                <div class="fs-5 fw-semibold mb-5">
                                                                    <i class="fa fa-users fs-2 text-gray-500"></i>
                                                                    Co-Applicant
                                                                </div>
                                                                <!--end::Title-->
                                                                @isset($account_details['coApplicants'])
                                                                    @foreach ($account_details['coApplicants'] as $co_applicant)
                                                                        <!--begin::Description-->
                                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                                            <div
                                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                            </div>
                                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                                <div class="text-gray-500">Name: <b
                                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">{{ $co_applicant['firstName'] }}
                                                                                        {{ $co_applicant['lastName'] }}</b></div>
                                                                                <div class="text-gray-500">Email: <b
                                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">{{ $co_applicant['email'] }}</b>
                                                                                </div>
                                                                                <div class="text-gray-500">Amount: <b
                                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                        <span
                                                                                            class="badge badge-light text-muted">${{ number_format($co_applicant['rentAmount'], 2) }}</span></b>
                                                                                </div>
                                                                                <div class="text-gray-500">Status: <b
                                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2"><span
                                                                                            class="badge badge-light-warning">Pending</span></b>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!--end::Description-->
                                                                    @endforeach
                                                                @endisset
                                                            </div>
                                                            <!--end::Timeline heading-->
                                                        </div>
                                                        <!--end::Timeline content-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endisset

                                <!--begin::Order details-->
                                <div class="card card-flush flex-row-fluid custom-max-width">
                                    <!--begin::Card header-->
                                    <div class="card-header"
                                        style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                        <div class="card-title">

                                            <div class="timeline-item">
                                                <!--begin::Timeline line-->
                                                <div class="timeline-line"></div>
                                                <!--end::Timeline line-->
                                                <!--begin::Timeline icon-->
                                                <div class="timeline-icon">
                                                </div>
                                                <!--end::Timeline icon-->

                                                <!--begin::Timeline content-->
                                                <div class="timeline-content mb-10 mt-n1">
                                                    <!--begin::Timeline heading-->
                                                    <div class="pe-3 mb-5">
                                                        <!--begin::Title-->
                                                        <div class="fs-5 fw-semibold mb-2">
                                                            <i class="fa fa-calendar fs-2 text-gray-500"></i>
                                                            Rent Duration
                                                        </div>
                                                        <!--end::Title-->
                                                        @isset($addressDetails['reOccurringMonthlyDay'])
                                                            @php
                                                                $day = (int) $addressDetails['reOccurringMonthlyDay']; // Convert to integer to handle any string input
                                                                $suffix = 'th'; // Default suffix

                                                                // Determine the suffix based on the day
                                                                if ($day % 10 == 1 && $day != 11) {
                                                                    $suffix = 'st';
                                                                } elseif ($day % 10 == 2 && $day != 12) {
                                                                    $suffix = 'nd';
                                                                } elseif ($day % 10 == 3 && $day != 13) {
                                                                    $suffix = 'rd';
                                                                }
                                                            @endphp
                                                            <div class="d-flex flex-stack position-relative mt-8">
                                                                <div
                                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                </div>
                                                                <div class="fw-semibold ms-5 text-gray-600">
                                                                    <div class="text-gray-500">
                                                                        Re-Occurring Monthly Day
                                                                    </div>
                                                                    <b
                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2 text-capitalize">
                                                                        {{ $day . $suffix }}
                                                                    </b>
                                                                </div>
                                                            </div>
                                                        @endisset
                                                        @isset($addressDetails['duration'])
                                                            <div class="d-flex flex-stack position-relative mt-8">
                                                                <div
                                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                </div>
                                                                <div class="fw-semibold ms-5 text-gray-600">
                                                                    <div class="text-gray-500">
                                                                        Duration From
                                                                    </div>
                                                                    <b
                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2 text-capitalize">
                                                                        {{ \Carbon\Carbon::parse($addressDetails['duration']['from'])->format('D, M j, Y') }}
                                                                    </b>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-stack position-relative mt-8">
                                                                <div
                                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                </div>
                                                                <div class="fw-semibold ms-5 text-gray-600">
                                                                    <div class="text-gray-500">
                                                                        Duration To
                                                                    </div>
                                                                    <b
                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2 text-capitalize">
                                                                        {{ \Carbon\Carbon::parse($addressDetails['duration']['to'])->format('D, M j, Y') }}
                                                                    </b>
                                                                </div>
                                                            </div>
                                                        @endisset
                                                    </div>
                                                    <!--end::Timeline heading-->
                                                </div>
                                                <!--end::Timeline content-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Order details-->


                            </div>

                            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-3">
                                <!--begin::Order details-->
                                <div class="card card-flush flex-row-fluid custom-max-width">
                                    <!--begin::Card header-->
                                    <div class="card-header"
                                        style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                        <div class="card-title">

                                            <div class="timeline-item">
                                                <!--begin::Timeline line-->
                                                <div class="timeline-line"></div>
                                                <!--end::Timeline line-->
                                                <!--begin::Timeline content-->
                                                <div class="timeline-content mb-10 mt-n1">
                                                    <!--begin::Timeline heading-->
                                                    <div class="pe-3 mb-5">
                                                        <!--begin::Title-->
                                                        <div class="fs-5 fw-semibold mb-2">
                                                            <i class="fa fa-bank fs-2 text-gray-500"></i>
                                                            @isset($finance['paymentMethod'])
                                                                @if ($finance['paymentMethod'] == 'interac')
                                                                    Interac E-transfer
                                                                @endif
                                                                @if ($finance['paymentMethod'] == 'cheque')
                                                                    Cheque
                                                                @endif
                                                            @endisset
                                                        </div>

                                                        @isset($finance['landlordType'])
                                                            <div class="d-flex flex-stack position-relative mt-8">
                                                                <div
                                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                </div>
                                                                <div class="fw-semibold ms-5 text-gray-600">
                                                                    <div class="text-gray-500">
                                                                        Landlord Type
                                                                    </div>
                                                                    <b
                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2 text-capitalize">
                                                                        {{ $finance['landlordType'] }}
                                                                    </b>
                                                                </div>
                                                            </div>
                                                        @endisset

                                                        <!--end::Title-->
                                                        @isset($finance['paymentMethod'])
                                                            @if ($finance['paymentMethod'] == 'cheque')
                                                                @isset($finance['landlordInfo']['firstName'])
                                                                    <!--begin::Description-->
                                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                                        <div
                                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                        </div>
                                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                                            <div class="text-gray-500">
                                                                                First Name
                                                                            </div>
                                                                            <b
                                                                                class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                {{ $finance['landlordInfo']['firstName'] ?? '' }}
                                                                            </b>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Description-->
                                                                @endisset
                                                                @isset($finance['landlordInfo']['transitNumber'])
                                                                    <!--begin::Description-->
                                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                                        <div
                                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                        </div>
                                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                                            <div class="text-gray-500">
                                                                                Transit Number
                                                                            </div>
                                                                            <b
                                                                                class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                {{ $finance['landlordInfo']['transitNumber'] ?? '' }}
                                                                            </b>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Description-->
                                                                @endisset
                                                                @isset($finance['landlordInfo']['lastName'])
                                                                    <!--begin::Description-->
                                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                                        <div
                                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                        </div>
                                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                                            <div class="text-gray-500">
                                                                                Last Name
                                                                            </div>
                                                                            <b
                                                                                class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                {{ $finance['landlordInfo']['lastName'] ?? '' }}
                                                                            </b>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Description-->
                                                                @endisset
                                                                @isset($finance['mortgageChequeDetails']['middleName'])
                                                                    <!--begin::Description-->
                                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                                        <div
                                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                        </div>
                                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                                            <div class="text-gray-500">
                                                                                Middle Name
                                                                            </div>
                                                                            <b
                                                                                class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                {{ $finance['mortgageChequeDetails']['middleName'] ?? '' }}
                                                                            </b>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Description-->
                                                                @endisset
                                                            @endif
                                                            @if ($finance['paymentMethod'] == 'interac')
                                                                @isset($finance['email'])
                                                                    <!--begin::Description-->
                                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                                        <div
                                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                        </div>
                                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                                            <div class="text-gray-500">
                                                                                Interac E-tranfer email
                                                                            </div>
                                                                            <b
                                                                                class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                {{ $finance['email'] ?? '' }}
                                                                            </b>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Description-->
                                                                @endisset
                                                            @endif
                                                        @endisset
                                                    </div>
                                                    <!--end::Timeline heading-->
                                                </div>
                                                <!--end::Timeline content-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Order details-->

                                <div class="card card-flush flex-row-fluid custom-max-width-address">
                                    <div class="card-header"
                                        style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                        <div class="card-title">
                                            <div class="timeline-item">
                                                <div class="timeline-line">
                                                </div>
                                                <div class="timeline-content mb-10 mt-n1">
                                                    <div class="pe-3 mb-5">
                                                        <div class="fs-5 fw-semibold mb-2">
                                                            <i class="fa fa-address-book fs-2 text-gray-500"></i>
                                                            Rent Address
                                                        </div>
                                                        <!-- Address section: Full row -->
                                                        <div class="address-item with-line full-row">
                                                            <div class="line"></div>
                                                            <div>
                                                                <div class="text-gray-500">Address</div>
                                                                <b
                                                                    class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $addressDetails['address'] ?? 'N/A' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!-- Other details: Two segments -->
                                                        <div class="address-details">
                                                            <!-- Column 1 -->
                                                            <div>
                                                                <div class="address-item with-line">
                                                                    <div class="line"></div>
                                                                    <div>
                                                                        <div class="text-gray-500">Province</div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            {{ $addressDetails['province'] ?? 'N/A' }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                                <div class="address-item with-line">
                                                                    <div class="line"></div>
                                                                    <div>
                                                                        <div class="text-gray-500">City</div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            {{ $addressDetails['city'] ?? 'N/A' }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                                <div class="address-item with-line">
                                                                    <div class="line"></div>
                                                                    <div>
                                                                        <div class="text-gray-500">Street Name</div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            {{ $addressDetails['streetName'] ?? 'N/A' }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Column 2 -->
                                                            <div>
                                                                <div class="address-item with-line">
                                                                    <div class="line"></div>
                                                                    <div>
                                                                        <div class="text-gray-500">Postal Code</div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            {{ $addressDetails['postalCode'] ?? 'N/A' }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                                <div class="address-item with-line">
                                                                    <div class="line"></div>
                                                                    <div>
                                                                        <div class="text-gray-500">House Number</div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            {{ $addressDetails['houseNumber'] ?? 'N/A' }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                                <div class="address-item with-line">
                                                                    <div class="line"></div>
                                                                    <div>
                                                                        <div class="text-gray-500">Unit Number</div>
                                                                        <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            {{ $addressDetails['unitNumber'] ?? 'N/A' }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Address details end -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Edit Button -->
                                        <div class="edit-icon position-absolute bottom-0 end-0 m-4">
                                            <button type="button" class="btn btn-icon btn-primary">
                                                <span data-bs-toggle="tooltip" aria-label="Edit rent address"
                                                    data-bs-original-title="Edit rent address" data-kt-initialized="1">
                                                    <i class="fa fa-pen-to-square fs-4"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
