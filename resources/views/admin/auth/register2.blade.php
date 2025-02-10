<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <title>Sign up Auth Page</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Good admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
    <meta name="keywords"
        content="Good, bootstrap, bootstrap 5, admin themes, Asp.Net Core & Django starter kits, admin themes, bootstrap admin, bootstrap dashboard, bootstrap dark mode" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Sign up Auth Page" />
    <meta property="og:url"
        content="https://themes.getbootstrap.com/product/good-bootstrap-5-admin-dashboard-template" />
    <meta property="og:site_name" content="Good by Keenthemes" />
    <link rel="canonical" href="https://preview.keenthemes.com/good/authentication/sign-up/multi-steps.html" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="/good/assets/media/logos/favicon.ico" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->



    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>
    <style>
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Change text and icon color on hover or when the input is selected */
        .form-col label:hover .fa-house,
        .form-col input[type="radio"]:checked+label .fa-house {
            color: white !important;
            /* Change icon color to white */
        }

        .form-col label:hover .text-gray-900,
        .form-col input[type="radio"]:checked+label .text-gray-900 {
            color: white !important;
            /* Change the text color to white */
        }

        .form-col label:hover .text-gray-900,
        .form-col input[type="radio"]:checked+label .text-gray-900 {
            color: white !important;
            /* Change the text color to white */
        }
    </style>
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="app-blank">


    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Multi-steps-->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column stepper-multistep"
            id="kt_create_account_stepper">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto w-lg-350px w-xl-500px">
                <div class="d-flex flex-column position-lg-fixed top-0 bottom-0 w-lg-350px w-xl-500px scroll-y bgi-size-cover bgi-position-center"
                    style="background-image: url({{ asset('assets/media/illustrations/auth-bg2.png') }})">
                    <!--begin::Header-->
                    <div class="d-flex flex-center py-10 py-lg-20 mt-lg-20">
                        <!--begin::Logo-->
                        <a href="/metronic8/demo1/index.html">
                            CrediPay
                            {{-- <img alt="Logo" src="/metronic8/demo1/assets/media/logos/custom-1.png" class="h-70px" /> --}}
                        </a>
                        <!--end::Logo-->
                    </div>
                    <!--end::Header-->

                    <!--begin::Body-->
                    <div class="d-flex flex-row-fluid justify-content-center p-10">
                        <!--begin::Nav-->
                        <div class="stepper-nav">
                            <!--begin::Step 1-->
                            <div class="stepper-item current" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon rounded-3">
                                        <i class="fa fa-check fs-2 stepper-check"></i>
                                        <span class="stepper-number">1</span>
                                    </div>
                                    <!--end::Icon-->

                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2">
                                            Personal Details
                                        </h3>

                                        <div class="stepper-desc fw-normal">
                                            Provide with us your details
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px">
                                </div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 1-->
                            <!--begin::Step 1-->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon rounded-3">
                                        <i class="fa fa-check fs-2 stepper-check"></i>
                                        <span class="stepper-number">2</span>
                                    </div>
                                    <!--end::Icon-->

                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2">
                                            Account Type
                                        </h3>

                                        <div class="stepper-desc fw-normal">
                                            Select your account type
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px">
                                </div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 1-->

                            <!--begin::Step 2-->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon rounded-3">
                                        <i class="fa fa-check fs-2 stepper-check"></i>
                                        <span class="stepper-number">2</span>
                                    </div>
                                    <!--end::Icon-->

                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2">
                                            Address
                                        </h3>
                                        <div class="stepper-desc fw-normal">
                                            Setup your rental address
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px">
                                </div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 2-->

                            <!--begin::Step 3-->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon">
                                        <i class="fa fa-check fs-2 stepper-check"></i>
                                        <span class="stepper-number">3</span>
                                    </div>
                                    <!--end::Icon-->

                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2">
                                            Landlord Details
                                        </h3>
                                        <div class="stepper-desc fw-normal">
                                            Setup your landlord details
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px">
                                </div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 3-->

                            <!--begin::Step 4-->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon">
                                        <i class="fa fa-check fs-2 stepper-check"></i>
                                        <span class="stepper-number">4</span>
                                    </div>
                                    <!--end::Icon-->


                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2">
                                            Billing Details
                                        </h3>
                                        <div class="stepper-desc fw-normal">
                                            Setup your billing details
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px">
                                </div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 4-->

                            <!--begin::Step 5-->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon">
                                        <i class="fa fa-check fs-2 stepper-check"></i>
                                        <span class="stepper-number">5</span>
                                    </div>
                                    <!--end::Icon-->

                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title">
                                            {{-- Completed --}}
                                        </h3>
                                        <div class="stepper-desc fw-normal">
                                            {{-- Your account is created --}}
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Step 5-->
                        </div>
                        <!--end::Nav-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-650px w-xl-700px p-10 p-lg-15 mx-auto">
                        <!--begin::Form-->
                        <form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form">
                            <!--begin::Step 1-->
                            <div class="current" data-kt-stepper-element="content">
                                <!--begin::Wrapper-->
                                <div class="w-100">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-15">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Personal Details</h2>
                                        <!--end::Title-->

                                        <!--begin::Notice-->
                                        <div class="text-muted fw-semibold fs-6">
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label required">First Name</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="first_name" type="text"
                                            class="form-control form-control-lg form-control-solid" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label required">Last Name</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="last_name" type="text"
                                            class="form-control form-control-lg form-control-solid" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label">Middle Name (<i class="">optional</i>)</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="middle_name" type="text"
                                            class="form-control form-control-lg form-control-solid" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label required">Email</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="email" type="email"
                                            class="form-control form-control-lg form-control-solid" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label required">Phone Number</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="phone" type="number"
                                            class="form-control form-control-lg form-control-solid" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Step 1-->
                            <!--begin::Step 1-->
                            <div class="" data-kt-stepper-element="content">
                                <!--begin::Wrapper-->
                                <div class="w-100">
                                    <div id="multiStepForm">
                                        <div class="form-step active" data-step="1">
                                            <!--begin::Heading-->
                                            <div class="pb-10 pb-lg-15">
                                                <!--begin::Title-->
                                                <h2 class="fw-bold text-gray-900">What are your primary goals with
                                                    CrediPay?</h2>
                                                <div class="text-muted fw-semibold fs-6">
                                                    Help us tailor your experience.
                                                </div>
                                                <!--end::Title-->
                                            </div>
                                            <!--end::Heading-->
                                            <!--begin::Input group-->
                                            <div class="fv-row">
                                                <!--begin::Row-->
                                                <div class="row">
                                                    <!--begin::Col-->
                                                    <div class="col-lg-6 form-col">
                                                        <!--begin::Option-->
                                                        <input type="radio" class="btn-check" name="account_type"
                                                            value="personal" checked="checked"
                                                            id="kt_create_account_form_account_type_personal" />
                                                        <label
                                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-10"
                                                            for="kt_create_account_form_account_type_personal">
                                                            <i class="fa fa-house fs-3x me-5"></i>
                                                            <!--begin::Info-->
                                                            <span class="d-block fw-semibold text-start">
                                                                <span class="text-gray-900 fw-bold d-block fs-4 mb-2">
                                                                    Rent
                                                                </span>
                                                                <span class="text-muted fw-semibold fs-6">Pay my rent
                                                                    with a credit or debit card.</span>
                                                            </span>
                                                            <!--end::Info-->
                                                        </label>
                                                    </div>
                                                    <!--end::Col-->

                                                    <!--begin::Col-->
                                                    <div class="col-lg-6 form-col">
                                                        <!--begin::Option-->
                                                        <input type="radio" class="btn-check" name="account_type"
                                                            value="corporate"
                                                            id="kt_create_account_form_account_type_corporate" />
                                                        <label
                                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
                                                            for="kt_create_account_form_account_type_corporate">
                                                            <i class="fa fa-house fs-3x me-5"></i>
                                                            <!--begin::Info-->
                                                            <span class="d-block fw-semibold text-start">
                                                                <span
                                                                    class="text-gray-900 fw-bold d-block fs-4 mb-2">Mortgage</span>
                                                                <span class="text-muted fw-semibold fs-6">Pay my rent
                                                                    with a credit or debit card.</span>
                                                            </span>
                                                            <!--end::Info-->
                                                        </label>
                                                        <!--end::Option-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                        </div>

                                        <div class="form-step" data-step="2">
                                            <div class="pb-10 pb-lg-15">
                                                <!--begin::Title-->
                                                <h2 class="fw-bold text-gray-900">Do you want to pay rent, build credit
                                                    or both?</h2>
                                                <!--end::Title-->
                                            </div>
                                            <div class="mb-0">
                                                <!--begin:Option-->
                                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                    <!--begin:Label-->
                                                    <span class="d-flex align-items-center me-2">
                                                        <!--begin::Icon-->
                                                        <span class="symbol symbol-50px me-6">
                                                            <span class="symbol-label">
                                                                <i class="fa fa-money-bill fs-1 text-gray-600"></i>
                                                            </span>
                                                        </span>
                                                        <!--end::Icon-->

                                                        <!--begin::Description-->
                                                        <span class="d-flex flex-column">
                                                            <span
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Pay
                                                                Rent</span>
                                                            <span class="fs-6 fw-semibold text-muted">I just want to
                                                                pay my rent with a credit or debit card..</span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->

                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                            name="account_plan" value="1"
                                                            data-gtm-form-interact-field-id="0">
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <!--end::Option-->

                                                <!--begin:Option-->
                                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                    <!--begin:Label-->
                                                    <span class="d-flex align-items-center me-2">
                                                        <!--begin::Icon-->
                                                        <span class="symbol symbol-50px me-6">
                                                            <span class="symbol-label">
                                                                <i class="fa fa-money-bill fs-1 text-gray-600"></i>
                                                            </span>
                                                        </span>
                                                        <!--end::Icon-->

                                                        <!--begin::Description-->
                                                        <span class="d-flex flex-column">
                                                            <span
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Pay
                                                                rent and build credit</span>
                                                            <span class="fs-6 fw-semibold text-muted">I want to pay my
                                                                rent with a credit or debit card and build credit for
                                                                free</span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->

                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" checked=""
                                                            name="account_plan" value="2">
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <!--end::Option-->
                                            </div>
                                        </div>

                                        <div class="form-step" data-step="3">
                                            <!--begin::Heading-->
                                            <div class="pb-10 pb-lg-15">
                                                <!--begin::Title-->
                                                <h2 class="fw-bold text-gray-900">Are you paying for alone or with
                                                    someone?</h2>
                                                <!--end::Title-->
                                            </div>
                                            <div class="mb-0">
                                                <!--begin:Option-->
                                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                    <!--begin:Label-->
                                                    <span class="d-flex align-items-center me-2">
                                                        <!--begin::Icon-->
                                                        <span class="symbol symbol-50px me-6">
                                                            <span class="symbol-label">
                                                                <i class="fa fa-user fs-1 text-gray-600"></i>
                                                            </span>
                                                        </span>
                                                        <!--end::Icon-->

                                                        <!--begin::Description-->
                                                        <span class="d-flex flex-column">
                                                            <span
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Sole
                                                                applicant</span>
                                                            <span class="fs-6 fw-semibold text-muted">I want to pay
                                                                this for alone</span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->

                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                            name="account_plan_type" value="11"
                                                            data-gtm-form-interact-field-id="0">
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <!--end::Option-->

                                                <!--begin:Option-->
                                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                    <!--begin:Label-->
                                                    <span class="d-flex align-items-center me-2">
                                                        <!--begin::Icon-->
                                                        <span class="symbol symbol-50px me-6">
                                                            <span class="symbol-label">
                                                                <i class="fa fa-users fs-1 text-gray-600"></i>
                                                            </span>
                                                        </span>
                                                        <!--end::Icon-->

                                                        <!--begin::Description-->
                                                        <span class="d-flex flex-column">
                                                            <span
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Co-applicant</span>
                                                            <span class="fs-6 fw-semibold text-muted">I want to pay
                                                                with other people</span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->

                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                            name="account_plan_type" value="22">
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <!--end::Option-->
                                            </div>
                                        </div>

                                        <div class="form-step" data-step="4">
                                            <!--begin::Heading-->
                                            <div class="pb-10 pb-lg-15">
                                                <!--begin::Title-->
                                                <h2 class="fw-bold text-gray-900">Setting Up Rent Payments</h2>
                                                <!--end::Title-->
                                            </div>
                                            <div class="mb-0">
                                                <!--begin:Option-->
                                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                    <!--begin:Label-->
                                                    <span class="d-flex align-items-center me-2">
                                                        <!--begin::Icon-->
                                                        <span class="symbol symbol-50px me-6">
                                                            <span class="symbol-label">
                                                                <i class="fa fa-home fs-1 text-gray-600"></i> </span>
                                                        </span>
                                                        <!--end::Icon-->

                                                        <!--begin::Description-->
                                                        <span class="d-flex flex-column">
                                                            <span
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Continue
                                                                paying an existing rent</span>
                                                            <span class="fs-6 fw-semibold text-muted">I want to switch
                                                                my current rent payments to CrediPay. </span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->

                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                            name="account_plan_type_mode" value="111"
                                                            data-gtm-form-interact-field-id="0">
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <!--end::Option-->

                                                <!--begin:Option-->
                                                <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                    <!--begin:Label-->
                                                    <span class="d-flex align-items-center me-2">
                                                        <!--begin::Icon-->
                                                        <span class="symbol symbol-50px me-6">
                                                            <span class="symbol-label">
                                                                <i class="fa fa-home fs-1 text-gray-600"></i>
                                                            </span>
                                                        </span>
                                                        <!--end::Icon-->

                                                        <!--begin::Description-->
                                                        <span class="d-flex flex-column">
                                                            <span
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Setup
                                                                payment for a new rental units</span>
                                                            <span class="fs-6 fw-semibold text-muted">I am moving and
                                                                need to set up payments for a new rental unit.</span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->

                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                            name="account_plan_type_mode" value="222">
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <!--end::Option-->
                                            </div>
                                        </div>
                                        <div class="d-flex text-right pt-10">
                                            <div class="mr-2">
                                            </div>
                                            <div>
                                                <button type="button" id="prev_btn_form"
                                                    class="btn btn-lg btn-light-primary" onclick="prevStep()">
                                                    Back</button>
                                                <button type="button" id="next_btn_form"
                                                    class="btn btn-lg btn-light-primary me-3"
                                                    onclick="nextStep()">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Step 1-->

                            <!--begin::Step 2-->
                            <div class="" data-kt-stepper-element="content">
                                <!--begin::Wrapper-->
                                <div class="w-100">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-15">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">What's your rental address?</h2>
                                        <!--end::Title-->

                                        <!--begin::Notice-->
                                        <div class="text-muted fw-semibold fs-6">
                                            Enter your rental address and unit number if applicable..
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Heading-->
                                    <div class="row mb-1">
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label mb-3">House Number</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="house_number" placeholder="" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--begin::Row-->
                                        <div class="col-md-6 fv-row">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label mb-3">Street Name</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type=""
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="house_number" placeholder="" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                    </div>
                                    <!--end::Heading-->
                                    <div class="row mb-1">
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label mb-3">Unit Number</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="house_number" placeholder="" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label mb-3">City</label>
                                                <!--end::Label-->

                                                <!--begin::Input-->
                                                <input type=""
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="house_number" placeholder="" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                    </div>

                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3">Province</label>
                                        <!--end::Label-->

                                        <!--begin::Input-->
                                        <input type="" class="form-control form-control-lg form-control-solid"
                                            name="house_number" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10 fv-row">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3">Postal Code</label>
                                        <!--end::Label-->

                                        <!--begin::Input-->
                                        <input type="" class="form-control form-control-lg form-control-solid"
                                            name="house_number" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Step 2-->

                            <!--begin::Step 3-->
                            <div class="" data-kt-stepper-element="content">
                                <!--begin::Wrapper-->
                                <div class="w-100">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-15">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Landlord Details</h2>
                                        <!--end::Title-->

                                        <!--begin::Notice-->
                                        <div class="text-muted fw-semibold fs-6">
                                            Kindly provide with us your landlord details
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Heading-->
                                </div>
                                <!--end::Wrapper-->

                            </div>
                            <!--end::Step 3-->

                            <!--begin::Step 3-->
                            <div class="" data-kt-stepper-element="content">
                                <!--begin::Wrapper-->
                                <div class="w-100">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-15">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Billing Details</h2>
                                        <!--end::Title-->

                                        <!--begin::Notice-->
                                        <div class="text-muted fw-semibold fs-6">
                                            Kindly provide with us your billing details
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                        <!--begin::Label-->
                                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                            <span class="required">Name On Card</span>


                                            <span class="ms-1" data-bs-toggle="tooltip"
                                                aria-label="Specify a card holder's name"
                                                data-bs-original-title="Specify a card holder's name"
                                                data-kt-initialized="1">
                                                <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span></i></span> </label>
                                        <!--end::Label-->

                                        <input type="text" class="form-control form-control-solid" placeholder=""
                                            name="card_name" value="Max Doe">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                                        <!--begin::Label-->
                                        <label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>
                                        <!--end::Label-->

                                        <!--begin::Input wrapper-->
                                        <div class="position-relative">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid"
                                                placeholder="Enter card number" name="card_number"
                                                value="4111 1111 1111 1111">
                                            <!--end::Input-->

                                            <!--begin::Card logos-->
                                            <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                                                <img src="/metronic8/demo1/assets/media/svg/card-logos/visa.svg"
                                                    alt="" class="h-25px">
                                                <img src="/metronic8/demo1/assets/media/svg/card-logos/mastercard.svg"
                                                    alt="" class="h-25px">
                                                <img src="/metronic8/demo1/assets/media/svg/card-logos/american-express.svg"
                                                    alt="" class="h-25px">
                                            </div>
                                            <!--end::Card logos-->
                                        </div>
                                        <!--end::Input wrapper-->
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row mb-10">
                                        <!--begin::Col-->
                                        <div class="col-md-8 fv-row">
                                            <!--begin::Label-->
                                            <label class="required fs-6 fw-semibold form-label mb-2">Expiration
                                                Date</label>
                                            <!--end::Label-->

                                            <!--begin::Row-->
                                            <div class="row fv-row fv-plugins-icon-container">
                                                <!--begin::Col-->
                                                <div class="col-6">
                                                    <select name="card_expiry_month"
                                                        class="form-select form-select-solid select2-hidden-accessible"
                                                        data-control="select2" data-hide-search="true"
                                                        data-placeholder="Month"
                                                        data-select2-id="select2-data-12-qm14" tabindex="-1"
                                                        aria-hidden="true" data-kt-initialized="1">
                                                        <option data-select2-id="select2-data-14-nuoi"></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select><span
                                                        class="select2 select2-container select2-container--bootstrap5"
                                                        dir="ltr" data-select2-id="select2-data-13-9948"
                                                        style="width: 100%;"><span class="selection"><span
                                                                class="select2-selection select2-selection--single form-select form-select-solid"
                                                                role="combobox" aria-haspopup="true"
                                                                aria-expanded="false" tabindex="0"
                                                                aria-disabled="false"
                                                                aria-labelledby="select2-card_expiry_month-9a-container"
                                                                aria-controls="select2-card_expiry_month-9a-container"><span
                                                                    class="select2-selection__rendered"
                                                                    id="select2-card_expiry_month-9a-container"
                                                                    role="textbox" aria-readonly="true"
                                                                    title="Month"><span
                                                                        class="select2-selection__placeholder">Month</span></span><span
                                                                    class="select2-selection__arrow"
                                                                    role="presentation"><b
                                                                        role="presentation"></b></span></span></span><span
                                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                                    <div
                                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                    </div>
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col-->
                                                <div class="col-6">
                                                    <select name="card_expiry_year"
                                                        class="form-select form-select-solid select2-hidden-accessible"
                                                        data-control="select2" data-hide-search="true"
                                                        data-placeholder="Year" data-select2-id="select2-data-15-60w0"
                                                        tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                                        <option data-select2-id="select2-data-17-u1va"></option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                        <option value="2028">2028</option>
                                                        <option value="2029">2029</option>
                                                        <option value="2030">2030</option>
                                                        <option value="2031">2031</option>
                                                        <option value="2032">2032</option>
                                                        <option value="2033">2033</option>
                                                        <option value="2034">2034</option>
                                                    </select><span
                                                        class="select2 select2-container select2-container--bootstrap5"
                                                        dir="ltr" data-select2-id="select2-data-16-d5cr"
                                                        style="width: 100%;"><span class="selection"><span
                                                                class="select2-selection select2-selection--single form-select form-select-solid"
                                                                role="combobox" aria-haspopup="true"
                                                                aria-expanded="false" tabindex="0"
                                                                aria-disabled="false"
                                                                aria-labelledby="select2-card_expiry_year-4y-container"
                                                                aria-controls="select2-card_expiry_year-4y-container"><span
                                                                    class="select2-selection__rendered"
                                                                    id="select2-card_expiry_year-4y-container"
                                                                    role="textbox" aria-readonly="true"
                                                                    title="Year"><span
                                                                        class="select2-selection__placeholder">Year</span></span><span
                                                                    class="select2-selection__arrow"
                                                                    role="presentation"><b
                                                                        role="presentation"></b></span></span></span><span
                                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                                    <div
                                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                    </div>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-md-4 fv-row fv-plugins-icon-container">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                <span class="required">CVV</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    aria-label="Enter a card CVV code"
                                                    data-bs-original-title="Enter a card CVV code"
                                                    data-kt-initialized="1">
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->

                                            <!--begin::Input wrapper-->
                                            <div class="position-relative">
                                                <!--begin::Input-->
                                                <input type="text" class="form-control form-control-solid"
                                                    minlength="3" maxlength="4" placeholder="CVV" name="card_cvv">
                                                <!--end::Input-->

                                                <!--begin::CVV icon-->
                                                <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                                                    <i class="ki-duotone ki-credit-cart fs-2hx"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                </div>
                                                <!--end::CVV icon-->
                                            </div>
                                            <!--end::Input wrapper-->
                                            <div
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->

                                </div>
                                <!--end::Wrapper-->

                            </div>
                            <!--end::Step 3-->

                            <!--begin::Step 3-->
                            <div class="" data-kt-stepper-element="content">
                                <!--begin::Wrapper-->
                                <div class="w-100">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-15">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Summary </h2>
                                        <!--end::Title-->

                                        <!--begin::Notice-->
                                        <div class="text-muted fw-semibold fs-6">
                                            Please double-check the details before submitting.
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Heading-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Step 3-->

                            <!--begin::Actions-->
                            <div class="d-flex flex-stack pt-15">
                                <div class="mr-2">
                                    <button type="button" class="btn btn-lg btn-light-primary me-3"
                                        data-kt-stepper-action="previous">
                                        <i class="fa fa-arrow-left fs-4 me-1"><span class="path1"></span><span
                                                class="path2"></span></i>
                                        Previous
                                    </button>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-lg btn-primary"
                                        data-kt-stepper-action="submit">
                                        <span class="indicator-label">
                                            Submit
                                            <i class="fa fa-arrow-right fs-4 ms-2"><span class="path1"></span><span
                                                    class="path2"></span></i>
                                        </span>
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>

                                    <button style="display: block" type="button" class="btn btn-lg btn-primary"
                                        id="continue_btn_form" data-kt-stepper-action="next">
                                        Continue
                                        <i class="fa fa-arrow-right fs-4 ms-1"></i>
                                    </button>

                                </div>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Multi-steps-->
    </div>
    <script>
        function validateCurrentStep() {
            const inputField = document.querySelector(`.form-step[data-step="${currentStep}"] input`);
            const errorField = document.querySelector(`#${inputField.id}Error`);

            if (inputField.value.trim() === "") {
                errorField.textContent = "This field is required.";
                return false;
            }

            errorField.textContent = "";
            return true;
        }

        let currentStep = 1;
        const totalSteps = 4; // Update to match the actual number of steps

        function updateButtonVisibility() {
            const prevButton = document.getElementById("prev_btn_form");
            const nextButton = document.getElementById("next_btn_form");

            // Hide "Previous" button on the first step
            prevButton.style.display = currentStep === 1 ? "none" : "inline-block";

            // Hide "Next" button on the last step
            nextButton.style.display = currentStep === totalSteps ? "none" : "inline-block";
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                console.log(currentStep);
                document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('active');
                currentStep++;
                document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');
                updateButtonVisibility();
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.remove('active');
                currentStep--;
                document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');
                updateButtonVisibility();
            }
        }

        // Initialize button visibility on page load
        document.addEventListener("DOMContentLoaded", () => {
            updateButtonVisibility();
        });
    </script>
    <!--end::Root-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/create-account.js') }}"></script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
