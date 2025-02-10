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
                        <h3 class="fw-bold m-0 text-gray-800">Mortgage Details</h3>
                    </div>
                    <div class="card-toolbar m-0"></div>
                </div>

                <div class="card-body" style="padding: 1rem 1.25rem">
                    <div class="tab-content">
                        <div id="kt_activity_today" class="card-body p-0 tab-pane fade active show">

                            <div class="d-flex flex-column flex-xl-row gap-7 mb-5 gap-lg-3">
                                <div class="card card-flush flex-row-fluid custom-max-width">
                                    <!--begin::Card header-->
                                    <div class="card-header"
                                        style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                        <div class="card-title">
                                            <div class="timeline-item">
                                                <div class="timeline-content mb-1 mt-n1">
                                                    <div class="pe-3 mb-5">
                                                        <div class="fs-5 fw-semibold mb-2">
                                                            <i class="fa fa-house-user fs-2 text-gray-500"></i>
                                                            @isset($account_details['plan'])
                                                                @if ($account_details['plan'] == 'pay_mortgage')
                                                                    Pay Mortgage
                                                                @endif
                                                                @if ($account_details['plan'] == 'pay_mortgage_build')
                                                                    Pay Mortgage and build credit
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
                                                                        @if ($account_details['applicationType'] == 'owner')
                                                                            Owner
                                                                        @endif
                                                                        @if ($account_details['applicationType'] == 'co_owner')
                                                                            Co-Owner
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
                                                                        @if ($account_details['paymentSetup'] == 'Continue_paying_existing_mortgage')
                                                                            Paying existing mortgage
                                                                        @endif
                                                                        @if ($account_details['paymentSetup'] == 'Setup_payment_new_mortgage')
                                                                            Payment for new mortgage
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
                                                                    Mortgage Amount
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

                                
                                @isset($account_details['plan'])
                                    @if ($account_details['plan'] == 'pay_mortgage_build')
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
                                                                            ${{ number_format($account_details['creditCardLimit'] ?? 0, 2) }}
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

                                                                @isset($account_details['applicationType'])
                                                                @if ($account_details['applicationType'] == 'co_owner')
                                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                                        <div
                                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                        </div>
                                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                                            <div class="text-gray-500">
                                                                                Owner primary mortgage amount
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
                                    @if ($account_details['applicationType'] == 'co_owner')
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
                                                                <div class="fs-5 fw-semibold mb-5">
                                                                    <i class="fa fa-users fs-2 text-gray-500"></i>
                                                                    Co-Owner
                                                                </div>
                                                                <!--end::Title-->
                                                                @isset($account_details['coApplicants'])
                                                                    @foreach ($account_details['coApplicants'] as $co_owner)
                                                                        <!--begin::Description-->
                                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                                            <div
                                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                            </div>
                                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                                <div class="text-gray-500">Name: <b
                                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary text-capitalize mb-2">{{ $co_owner['firstName'] }}
                                                                                        {{ $co_owner['lastName'] }}</b></div>
                                                                                <div class="text-gray-500">Email: <b
                                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">{{ $co_owner['email'] }}</b>
                                                                                </div>
                                                                                <div class="text-gray-500">Amount: <b
                                                                                        class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                                        <span
                                                                                            class="badge badge-light text-muted">${{ number_format($co_owner['mortgageAmount'], 2) }}</span></b>
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
                                            <!--end::Card header-->
                                        </div>
                                        <!--end::Order details-->
                                    @endif
                                @endisset

                                <div class="card card-flush flex-row-fluid custom-max-width">
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
                                                            <i class="fa fa-calendar fs-2 text-gray-500"></i>
                                                            Mortgage Duration
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
                                </div>

                            </div>

                            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-3" style="">
                                @isset($finance['paymentMethod'])
                                    @if ($finance['paymentMethod'] == 'EFT')
                                    <div class="card card-flush flex-row-fluid custom-max-width-address">
                                        <div class="card-header"
                                            style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                            <div class="card-title">
                                                <div class="timeline-item">
                                                    <div class="timeline-line"></div>
                                                    <div class="timeline-content mb-10 mt-n1">
                                                        <div class="pe-3 mb-5">
                                                            <div class="fs-5 fw-semibold mb-2">
                                                                <i class="fa fa-bank fs-2 text-gray-500"></i>
                                                                        EFT (Electronic Funds Transfer)
                                                            </div>

<!-- Consolidated Details -->
<div class="address-details">
    <!-- Column 1: Mortgage Account and Transit Number -->
    <div>
        @isset($finance['mortgageEftDetails']['accountNumber'])
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Mortgage Account Number</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageEftDetails']['accountNumber'] ?? 'N/A' }}
                </b>
            </div>
        </div>
        @endisset

        @isset($finance['mortgageEftDetails']['transitNumber'])
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Bank Transit Number</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageEftDetails']['transitNumber'] ?? 'N/A' }}
                </b>
            </div>
        </div>
        @endisset
    </div>

    <!-- Column 2: Institution Number and Bank Account -->
    <div>
        @isset($finance['mortgageEftDetails']['institutionNumber'])
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Bank Institution Number</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageEftDetails']['institutionNumber'] ?? 'N/A' }}
                </b>
            </div>
        </div>
        @endisset

        @isset($finance['mortgageEftDetails']['bankAccountNumber'])
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Bank Account Number</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageEftDetails']['bankAccountNumber'] ?? 'N/A' }}
                </b>
            </div>
        </div>
        @endisset
    </div>
</div>
                                                            <!-- Lender Address Section -->
                                                            @isset($finance['mortgageEftDetails']['lenderAddress'])
                                                            <div class="address-item with-line full-row">
                                                                <div class="line"></div>
                                                                <div>
                                                                    <div class="text-gray-500">Lender Address</div>
                                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                        {{ $finance['mortgageEftDetails']['lenderAddress'] ?? 'N/A' }}
                                                                    </b>
                                                                </div>
                                                            </div>
                                                            @endisset
<!-- Consolidated Details -->
<div class="address-details">
    <!-- Column 1: Mortgage Account and Transit Number -->
    <div>
        @isset($finance['mortgageEftDetails']['paymentFrequency'])
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Payment Frequency</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageEftDetails']['paymentFrequency'] ?? 'N/A' }}
                </b>
            </div>
        </div>
        @endisset
    </div>
    <div>
        @isset($finance['mortgageEftDetails']['biWeeklyDueDate'])
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Next due date</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ \Carbon\Carbon::parse($finance['mortgageEftDetails']['biWeeklyDueDate'] ?? '')->format('D, M j, Y') }}
                </b>
            </div>
        </div>
        @endisset
    </div>
</div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endisset
                                @isset($finance['paymentMethod'])
                                    @if ($finance['paymentMethod'] == 'mortgage_cheque')
                                    <div class="card card-flush flex-row-fluid custom-max-width-address">
                                        <div class="card-header"
                                            style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                            <div class="card-title">
                                                <div class="timeline-item">
                                                    <div class="timeline-line"></div>
                                                    <div class="timeline-content mb-10 mt-n1">
                                                        <div class="pe-3 mb-5">
                                                            <div class="fs-5 fw-semibold mb-2">
                                                                <i class="fa fa-bank fs-2 text-gray-500"></i>
                                                                Cheque
                                                            </div>
                                                            <!-- Address Section: Full row -->

<!-- Other Details Section -->
<div class="address-details">
    <!-- Column 1: Account Number and Transit Number -->
    <div>
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Account Number</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageChequeDetails']['accountNumber'] ?? 'N/A' }}
                </b>
            </div>
        </div>
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Transit Number</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageChequeDetails']['transitNumber'] ?? 'N/A' }}
                </b>
            </div>
        </div>
    </div>

    <!-- Column 2: Institution Number and Name -->
    <div>
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Institution Number</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageChequeDetails']['institutionNumber'] ?? 'N/A' }}
                </b>
            </div>
        </div>
        <div class="address-item with-line">
            <div class="line"></div>
            <div>
                <div class="text-gray-500">Name</div>
                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                    {{ $finance['mortgageChequeDetails']['name'] ?? 'N/A' }}
                </b>
            </div>
        </div>
    </div>
</div>

<div class="address-item with-line full-row">
    <div class="line"></div>
    <div>
        <div class="text-gray-500">Address</div>
        <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
            {{ $finance['mortgageChequeDetails']['address'] ?? 'N/A' }}
        </b>
    </div>
</div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endisset

                                <div class="card card-flush flex-row-fluid custom-max-width-address">
                                    <div class="card-header"
                                        style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                        <div class="card-title">
                                            <div class="timeline-item">
                                                <div class="timeline-line"></div>
                                                <div class="timeline-content mb-10 mt-n1">
                                                    <div class="pe-3 mb-5">
                                                        <div class="fs-5 fw-semibold mb-2">
                                                            <i class="fa fa-address-book fs-2 text-gray-500"></i>
                                                            Mortgage Address
                                                        </div>
                                                        <!--end::Title-->
                                                        
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
                                                    <!--end::Timeline heading-->
                                                </div>
                                                <!--end::Timeline content-->
                                            </div>
                                        </div>
                                        <!-- Edit Button -->
                                        <div class="edit-icon position-absolute bottom-0 end-0 m-4">
                                            <button type="button" class="btn btn-icon btn-primary">
                                                <span data-bs-toggle="tooltip" aria-label="Edit mortgage address"
                                                    data-bs-original-title="Edit mortgage address" data-kt-initialized="1">
                                                    <i class="fa fa-pen-to-square fs-4"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Order details-->

                                <!--begin::Order details-->
                                <div class="card card-flush flex-row-fluid" style="display: none">
                                    <!--begin::Card header-->
                                    <div class="card-header"
                                        style="padding-left: 1rem; padding-right: 1rem; padding-top:1rem">
                                        <div class="card-title">
                                            <div class="timeline-item">
                                                <div class="timeline-content mb-1 mt-n1">
                                                    <div class="pe-3 mb-5">
                                                        <div class="fs-5 fw-semibold mb-2">
                                                            <i class="fa fa-house-user fs-2 text-gray-500"></i>
                                                            @isset($account_details['plan'])
                                                                @if ($account_details['plan'] == 'pay_mortgage')
                                                                    Pay Mortgage
                                                                @endif
                                                                @if ($account_details['plan'] == 'pay_mortgage_build')
                                                                    Pay Mortgage and build credit
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
                                                                        @if ($account_details['applicationType'] == 'owner')
                                                                            Owner
                                                                        @endif
                                                                        @if ($account_details['applicationType'] == 'co_owner')
                                                                            Co-Owner
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
                                                                        @if ($account_details['paymentSetup'] == 'Continue_paying_existing_mortgage')
                                                                            Paying existing mortgage
                                                                        @endif
                                                                        @if ($account_details['paymentSetup'] == 'Setup_payment_new_mortgage')
                                                                            Payment for new mortgage
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
                                                                    Mortgage Amount
                                                                </div>
                                                                <b
                                                                    class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    ${{ number_format($addressDetails['rentAmount'] ?? 0, 2) }}
                                                                </b>
                                                            </div>
                                                        </div>

                                                        @isset($account_details['plan'])
                                                            @if ($account_details['plan'] == 'pay_mortgage_build')
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
                                                                            {{ number_format($account_details['creditCardLimit'] ?? 0, 2) }}
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
                                                            @endif
                                                        @endisset

                                                        @isset($account_details['applicationType'])
                                                            @if ($account_details['applicationType'] == 'co_owner')
                                                                <div class="d-flex flex-stack position-relative mt-8">
                                                                    <div
                                                                        class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                    </div>
                                                                    <div class="fw-semibold ms-5 text-gray-600">
                                                                        <div class="text-gray-500">
                                                                            Owner primary mortgage amount
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
                            </div>



                            <div class="timeline timeline-border-dashed" style="display: none">
                                <div class="timeline-item">
                                    <div class="timeline-line"></div>
                                    <div class="timeline-icon ">
                                        <i class="fa fa-house-user fs-2 text-gray-500"></i>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-semibold mb-2">
                                                @isset($account_details['plan'])
                                                    @if ($account_details['plan'] == 'pay_mortgage')
                                                        Pay Mortgage
                                                    @endif
                                                    @if ($account_details['plan'] == 'pay_mortgage_build')
                                                        Pay Mortgage and build credit
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
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        @isset($account_details['applicationType'])
                                                            @if ($account_details['applicationType'] == 'owner')
                                                                Owner
                                                            @endif
                                                            @if ($account_details['applicationType'] == 'co_owner')
                                                                Co-Owner
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
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        @isset($account_details['paymentSetup'])
                                                            @if ($account_details['paymentSetup'] == 'Continue_paying_existing_mortgage')
                                                                Paying existing mortgage
                                                            @endif
                                                            @if ($account_details['paymentSetup'] == 'Setup_payment_new_mortgage')
                                                                Payment for new mortgage
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
                                                        Mortgage Amount
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        ${{ number_format($addressDetails['rentAmount'] ?? 0, 2) }}
                                                    </b>
                                                </div>
                                            </div>

                                            @isset($account_details['plan'])
                                                @if ($account_details['plan'] == 'pay_mortgage_build')
                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                        <div
                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                        </div>
                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                            <div class="text-gray-500">
                                                                Credit Card limit
                                                            </div>
                                                            <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                {{ number_format($account_details['creditCardLimit'] ?? 0, 2) }}
                                                            </b>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                        @php
                                                            $CCday = (int) $account_details['creditCardDueDate']; // Convert to integer to handle any string input
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
                                                            <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                {{ $CCday . $CCsuffix }}
                                                            </b>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endisset

                                            @isset($account_details['applicationType'])
                                                @if ($account_details['applicationType'] == 'co_owner')
                                                    <div class="d-flex flex-stack position-relative mt-8">
                                                        <div
                                                            class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                        </div>
                                                        <div class="fw-semibold ms-5 text-gray-600">
                                                            <div class="text-gray-500">
                                                                Owner primary mortgage amount
                                                            </div>
                                                            <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
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
                                @isset($account_details['applicationType'])
                                    @if ($account_details['applicationType'] == 'co_owner')
                                        <div class="timeline-item">
                                            <!--begin::Timeline line-->
                                            <div class="timeline-line"></div>
                                            <!--end::Timeline line-->
                                            <!--begin::Timeline icon-->
                                            <div class="timeline-icon">
                                                <i class="fa fa-users fs-2 text-gray-500"></i>
                                            </div>
                                            <!--end::Timeline icon-->

                                            <!--begin::Timeline content-->
                                            <div class="timeline-content mb-10 mt-n1">
                                                <!--begin::Timeline heading-->
                                                <div class="pe-3 mb-5">
                                                    <!--begin::Title-->
                                                    <div class="fs-5 fw-semibold mb-5">
                                                        Co-Owner
                                                    </div>
                                                    <!--end::Title-->
                                                    @isset($account_details['coApplicants'])
                                                        @foreach ($account_details['coApplicants'] as $co_owner)
                                                            <!--begin::Description-->
                                                            <div class="d-flex flex-stack position-relative mt-8">
                                                                <div
                                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                                </div>
                                                                <div class="fw-semibold ms-5 text-gray-600">
                                                                    <div class="text-gray-500">Name: <b
                                                                            class="fs-5 fw-bold text-gray-800 text-capitalize text-hover-primary mb-2">{{ $co_owner['firstName'] }}
                                                                            {{ $co_owner['lastName'] }}</b></div>
                                                                    <div class="text-gray-500">Email: <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">{{ $co_owner['email'] }}</b>
                                                                    </div>
                                                                    <div class="text-gray-500">Amount: <b
                                                                            class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                            <span
                                                                                class="badge badge-light text-muted">${{ number_format($co_owner['mortgageAmount'], 2) }}</span></b>
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
                                    @endif
                                @endisset
                                <div class="timeline-item">
                                    <!--begin::Timeline line-->
                                    <div class="timeline-line"></div>
                                    <!--end::Timeline line-->
                                    <!--begin::Timeline icon-->
                                    <div class="timeline-icon">
                                        <i class="fa fa-calendar fs-2 text-gray-500"></i>
                                    </div>
                                    <!--end::Timeline icon-->

                                    <!--begin::Timeline content-->
                                    <div class="timeline-content mb-10 mt-n1">
                                        <!--begin::Timeline heading-->
                                        <div class="pe-3 mb-5">
                                            <!--begin::Title-->
                                            <div class="fs-5 fw-semibold mb-2">
                                                Mortgage Duration
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

                                <div class="timeline-item">
                                    <!--begin::Timeline line-->
                                    <div class="timeline-line"></div>
                                    <!--end::Timeline line-->
                                    <!--begin::Timeline icon-->
                                    <div class="timeline-icon">
                                        <i class="fa fa-bank fs-2 text-gray-500"></i>
                                    </div>
                                    <!--end::Timeline icon-->

                                    <!--begin::Timeline content-->
                                    <div class="timeline-content mb-10 mt-n1">
                                        <!--begin::Timeline heading-->
                                        <div class="pe-3 mb-5">
                                            <!--begin::Title-->
                                            <div class="fs-5 fw-semibold mb-2">
                                                @isset($finance['paymentMethod'])
                                                    @if ($finance['paymentMethod'] == 'EFT')
                                                        EFT (Electronic Funds Transfer)
                                                    @endif
                                                    @if ($finance['paymentMethod'] == 'mortgage_cheque')
                                                        Cheque
                                                    @endif
                                                @endisset
                                            </div>
                                            <!--end::Title-->
                                            @isset($finance['paymentMethod'])
                                                @if ($finance['paymentMethod'] == 'mortgage_cheque')
                                                    @isset($finance['mortgageChequeDetails']['accountNumber'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Account Number
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageChequeDetails']['accountNumber'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageChequeDetails']['transitNumber'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Transit Number
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageChequeDetails']['transitNumber'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageChequeDetails']['institutionNumber'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Institution Number
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageChequeDetails']['institutionNumber'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageChequeDetails']['name'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Name
                                                                </div>
                                                                <b
                                                                    class="fs-5 fw-bold text-gray-800 text-capitalize text-hover-primary mb-2">
                                                                    {{ $finance['mortgageChequeDetails']['name'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageChequeDetails']['address'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Address
                                                                </div>
                                                                <b
                                                                    class="fs-5 fw-bold text-gray-800 text-capitalize text-hover-primary mb-2">
                                                                    {{ $finance['mortgageChequeDetails']['address'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                @endif
                                                @if ($finance['paymentMethod'] == 'EFT')
                                                    @isset($finance['mortgageEftDetails']['accountNumber'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Mortgage Account Number
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageEftDetails']['accountNumber'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageEftDetails']['institutionNumber'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Bank Institution Number
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageEftDetails']['institutionNumber'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageEftDetails']['transitNumber'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Bank Transit Number
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageEftDetails']['transitNumber'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageEftDetails']['bankAccountNumber'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Bank Account Number
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageEftDetails']['bankAccountNumber'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageEftDetails']['lenderAddress'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Lender Address
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageEftDetails']['lenderAddress'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                    @endisset
                                                    @isset($finance['mortgageEftDetails']['paymentFrequency'])
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Payment Frequency
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ $finance['mortgageEftDetails']['paymentFrequency'] ?? '' }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <!--end::Description-->
                                                        @if ($finance['mortgageEftDetails']['paymentFrequency'] == 'Bi-weekly')
                                                        @endif
                                                        <!--begin::Description-->
                                                        <div class="d-flex flex-stack position-relative mt-8">
                                                            <div
                                                                class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                            </div>
                                                            <div class="fw-semibold ms-5 text-gray-600">
                                                                <div class="text-gray-500">
                                                                    Next due date
                                                                </div>
                                                                <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                                    {{ \Carbon\Carbon::parse($finance['mortgageEftDetails']['biWeeklyDueDate'] ?? '')->format('D, M j, Y') }}
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

                                <div class="timeline-item">
                                    <!--begin::Timeline line-->
                                    <div class="timeline-line"></div>
                                    <!--end::Timeline line-->
                                    <!--begin::Timeline icon-->
                                    <div class="timeline-icon">
                                        <i class="fa fa-address-book fs-2 text-gray-500"></i>
                                    </div>
                                    <!--end::Timeline icon-->

                                    <!--begin::Timeline content-->
                                    <div class="timeline-content mb-10 mt-n1">
                                        <!--begin::Timeline heading-->
                                        <div class="pe-3 mb-5">
                                            <!--begin::Title-->
                                            <div class="fs-5 fw-semibold mb-2">
                                                Mortgage Address
                                            </div>
                                            <!--end::Title-->
                                            <!-- Address details display -->
                                            <div class="d-flex flex-stack position-relative mt-8">
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <div class="fw-semibold ms-5 text-gray-600">
                                                    <div class="text-gray-500">
                                                        Address
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        {{ $addressDetails['address'] ?? 'N/A' }}
                                                    </b>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-stack position-relative mt-8">
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <div class="fw-semibold ms-5 text-gray-600">
                                                    <div class="text-gray-500">
                                                        Province
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        {{ $addressDetails['province'] ?? 'N/A' }}
                                                    </b>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-stack position-relative mt-8">
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <div class="fw-semibold ms-5 text-gray-600">
                                                    <div class="text-gray-500">
                                                        City
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        {{ $addressDetails['city'] ?? 'N/A' }}
                                                    </b>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-stack position-relative mt-8">
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <div class="fw-semibold ms-5 text-gray-600">
                                                    <div class="text-gray-500">
                                                        Postal Code
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        {{ $addressDetails['postalCode'] ?? 'N/A' }}
                                                    </b>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-stack position-relative mt-8">
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <div class="fw-semibold ms-5 text-gray-600">
                                                    <div class="text-gray-500">
                                                        House Number
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        {{ $addressDetails['houseNumber'] ?? 'N/A' }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-stack position-relative mt-8">
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <div class="fw-semibold ms-5 text-gray-600">
                                                    <div class="text-gray-500">
                                                        Unit Number
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        {{ $addressDetails['unitNumber'] ?? 'N/A' }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-stack position-relative mt-8">
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <div class="fw-semibold ms-5 text-gray-600">
                                                    <div class="text-gray-500">
                                                        Street Name
                                                    </div>
                                                    <b class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">
                                                        {{ $addressDetails['streetName'] ?? 'N/A' }}
                                                    </b>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Timeline heading-->
                                    </div>
                                    <!--end::Timeline content-->
                                </div>
                                <div class="timeline-item">
                                    <!--begin::Timeline line-->
                                    <div class="timeline-line"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
