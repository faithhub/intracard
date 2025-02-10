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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- 
      <meta name="viewport" content="width=1024">
      <meta name="viewport" content="width=1024, user-scalable=no">
      --}}
    <meta property="og:url"
        content="https://themes.getbootstrap.com/product/good-bootstrap-5-admin-dashboard-template" />
    <meta property="og:site_name" content="Good by Keenthemes" />
    <link rel="canonical" href="https://preview.keenthemes.com/good/authentication/sign-up/multi-steps.html" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="/good/assets/media/logos/favicon.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    {{-- 
      <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
      --}}
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: none;
        }

        .stepper-item .stepper-check {
            display: none;
            /* Hide the check icon by default */
        }

        .stepper-item.completed .stepper-check {
            display: inline;
            /* Show the check icon when the step is completed */
            color: black;
            /* Icon color for completed steps */
        }

        .stepper-item.completed .stepper-number {
            display: none;
            /* Hide the step number when completed */
        }

        .stepper-item.current .stepper-number,
        .stepper-item.current .stepper-title {
            color: black;
            /* White text color for the current step */
        }

        .stepper-item:not(.completed) .stepper-number {
            display: inline;
            /* Show step number for non-completed steps */
        }

        .step {
            display: none;
        }

        .current {
            display: block;
        }

        .error-message {
            font-family: 'Kodchasan';
            color: red;
            font-weight: 600;
            font-size: 1em;
            margin-top: 4px;
            display: none;
        }

        .navigation {
            margin-top: 20px;
        }

        button {
            padding: 8px 16px;
            margin: 4px;
        }

        .progress-bar-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px;
            margin-bottom: 10px;
            height: 20px;
            /* Increased thickness */
        }

        .progress-bar {
            height: 100%;
            background-color: #a000f9;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        #progressPercentage {
            font-weight: bold;
            color: white;
        }

        /* Change text and icon color to white on hover */
        .form-col label:hover .text-gray-900,
        .form-col label:hover .fa-house {
            color: white !important;
        }

        /* Change text and icon color to white when selected */
        .form-col input[type="radio"]:checked+label .text-gray-900,
        .form-col input[type="radio"]:checked+label .fa-house {
            color: white !important;
        }

        /* Optional: Background color change for selected or hovered label */
        .form-col label:hover,
        .form-col input[type="radio"]:checked+label {
            background-color: #0056b3;
            /* Darker background for selected or hovered state */
        }

        /* Mobile-specific styling */

        @media (max-width: 767px) {

            .sm-label {
                padding: 1rem !important;
            }

            /* Apply grid layout to the main container */
            .d-flex.flex-column.flex-lg-row.flex-column-fluid {
                display: grid !important;
                grid-template-columns: 50px 1fr !important;
                /* Narrower sidebar width */
                gap: 0 !important;
                /* Remove gap between sidebar and form */
                overflow-x: hidden !important;
                /* Prevent horizontal scroll */
            }

            /* Ensure stepper items do not overflow in the sidebar */
            .stepper-item,
            .stepper-wrapper,
            .stepper-icon {
                width: 100% !important;
                overflow: hidden !important;
                /* Hide any potential overflow */
            }

            .btn-div-sm {
                padding-bottom: 15px;
            }

            .stepper-icon {
                width: 30px !important;
                /* Increase width */
                height: 30px !important;
                /* Increase height */
                line-height: 30px !important;
                /* Center number vertically */
                font-size: 1.1em !important;
                /* Slightly larger font */
            }

            /* Sidebar adjustments */
            .d-flex.flex-column.flex-lg-row-auto {
                grid-column: 1 !important;
                padding: 0 !important;
                /* Remove sidebar padding */
                background-color: #F8F6F2 !important;
                width: 50px !important;
                /* Fix sidebar width */
            }

            /* Hide CrediPay text in the sidebar */
            .d-flex.flex-column.flex-lg-row-auto h1 {
                display: none !important;
            }

            /* Add "CrediPay" text within form container at the top */
            .w-lg-800px.w-xl-800px.p-10.p-lg-15.mx-auto::before {
                content: "CrediPay";
                display: block;
                text-align: center;
                font-size: 1.2em;
                font-weight: bold;
                margin-bottom: 2px !important;
                /* Minimal margin below "CrediPay" */
                color: #000;
            }

            /* Main content (form) adjustments */
            .d-flex.flex-column.flex-lg-row-fluid {
                grid-column: 2 !important;
                padding: 0 5px !important;
                /* Reduce padding */
                overflow-x: hidden !important;
                /* Prevent horizontal scroll */
            }

            /* Reduced padding for this class on mobile */
            .d-flex.flex-row-fluid.justify-content-center.p-10 {
                padding: 0.25rem !important;
                /* Reduced padding */
            }

            /* Adjust padding and margin for form container */
            .w-sm-650px,
            .w-lg-650px,
            .w-xl-700px,
            .p-10,
            .p-lg-5,
            .mb-5,
            .mx-auto {
                width: 100% !important;
                /* Full width for mobile */
                padding: 0 !important;
                /* Remove all padding */
                margin: 0 !important;
                /* Remove all margin */
                overflow-x: hidden !important;
                /* Prevent horizontal scroll */
            }

            /* Full-width progress bar with default white background */
            .progress-bar-container {
                width: 100% !important;
                /* Full width */
                height: 12px !important;
                /* Increased height for visibility */
                background-color: #ffffff !important;
                /* White background */
                border-radius: 10px !important;
                margin: 2px 0 !important;
                /* Minimal space above and below */
                overflow: hidden;
                /* Ensure the progress bar fits within the container */
            }

            .progress-bar {
                width: 100% !important;
                /* Full width to match container */
                height: 100% !important;
                background-color: #a000f9 !important;
                /* Original color for progress */
                border-radius: 10px !important;
                transition: width 0.3s ease !important;
            }

            /* Remove top padding from this class */
            .d-flex.flex-stack.pt-15 {
                padding-top: 0 !important;
            }

            /* Only show stepper numbers in sidebar */
            .stepper-label {
                display: none !important;
            }

            .stepper-icon {
                margin: 0 auto !important;
            }

            /* Further reduce spacing between form sections */
            .pb-10,
            .pb-lg-15,
            .mb-10 {
                padding-bottom: 2px !important;
                /* Minimal padding below sections */
                margin-bottom: 2px !important;
                /* Minimal margin below sections */
            }

            /* Further reduce spacing between input fields */
            .form-col,
            .fv-row {
                margin: 0 !important;
                /* Remove vertical spacing */
                padding: 0 !important;
            }

            /* Adjust form input fields */
            .form-control {
                height: auto !important;
                /* Remove any fixed height */
                padding: 3px 5px !important;
                /* Tighter padding inside inputs */
                margin-bottom: 1px !important;
                /* Minimal bottom margin */
            }

            /* Adjust form labels and descriptions */
            .form-col label,
            .form-label {
                font-size: 0.9em !important;
                margin-bottom: 1px !important;
                /* Minimal space below labels */
                padding: 0;
            }

            .text-muted.fw-semibold.fs-6 {
                margin-bottom: 1px !important;
                /* Minimal space below description text */
            }

            /* Adjust style for fa-house icon */
            .fa-house {
                font-size: 1.2em !important;
                /* Smaller icon size */
                color: #6c757d !important;
                /* Set to a neutral color */
                margin-right: 3px !important;
                /* Minimal space between icon and text */
                vertical-align: middle !important;
                /* Align icon to middle of text */
            }

            /* Ensure the entire container fits within the viewport */
            body {
                overflow-x: hidden !important;
                /* Disable horizontal scrolling */
                margin: 0 !important;
                /* Remove default body margin */
                padding: 0 !important;
                /* Remove default body padding */
            }
        }

        .stepper-item .fa {
            color: white !important;
            font-size: 1.2em;
            transition: color 0.3s ease, font-size 0.3s ease;
        }

        .stepper-item.current .fa {
            color: black !important;
            font-size: 1.5em;
        }

        /* Input group for phone number */
        .phone-input {
            display: flex;
            align-items: center;
            width: 100%;
        }

        /* Canadian flag styling */
        .flag-icon {
            width: 20px;
            height: auto;
            margin-right: 5px;
        }

        /* Phone input styling */
        #phoneInput {
            flex: 1;
            padding-left: 10px;
        }

        /* Button styling to align with input */
        #getPhoneCodeButton {
            margin-left: 5px;
        }
    </style>
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"> --}}

    <!-- Toast Container -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        /* Fixed height for terms content */
        /* Custom checkbox styling */
        #agreeCheckbox {
            width: 18px;
            height: 18px;
            cursor: not-allowed;
            /* Not clickable initially */
            accent-color: #bbb;
            /* Light gray for disabled */
        }

        /* More visible checkbox when enabled */
        #agreeCheckbox:enabled {
            cursor: pointer;
            /* Change cursor to pointer when enabled */
            accent-color: #007bff;
            /* Custom color for enabled checkbox */
        }

        #termsContainer {
            height: 250px;
            overflow-y: auto;
            border: 2px solid #2100352e;
            padding: 15px;
            margin-bottom: 2rem;
            border-radius: 10px;
        }

        /* Button and checkbox styling */
        .terms-footer {
            align-items: center;
            gap: 10px;
        }

        .btn-proceed.enabled {
            cursor: pointer;
            background-color: #007bff;
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="app-blank" onload="initAutocomplete()">
    <div class="position-fixed top-0 end-0 p-3 z-index-3">
        <div id="toastContainer"></div>
    </div>

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Multi-steps-->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column stepper-multistep"
            id="kt_create_account_stepper">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto w-lg-350px w-xl-500px">
                <div class="d-flex flex-column position-lg-fixed top-0 bottom-0 w-lg-350px w-xl-500px scroll-y bgi-size-cover bgi-position-center"
                    style="background-color:#F8F6F2">
                    <!--begin::Header-->
                    <div class="d-flex flex-center py-10 py-lg-20 mt-lg-20">
                        <h1 class="text-black">CrediPay</h1>
                        <!--begin::Logo-->
                        <a href="">
                            {{-- CrediPay --}}
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
                                        <i class="fa fa fa-user fs-2 stepper-check"></i>
                                        <span class="stepper-number">
                                            <i class="fa fa-user"></i>
                                        </span>
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
                                        <i class="fa fa-file-invoice fs-2 stepper-check"></i>
                                        <span class="stepper-number">
                                            <i class="fa fa-file-invoice"></i>
                                        </span>
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
                                        <i class="fa fa-address-book fs-2 stepper-check"></i>
                                        <span class="stepper-number">
                                            <i class="fa fa-address-book"></i>
                                        </span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2">
                                            Address
                                        </h3>
                                        <div class="stepper-desc fw-normal" id="AddressNameDiv">
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
                                        <i class="fa fa-house fs-2 stepper-check"></i>
                                        <span class="stepper-number">
                                            <i class="fa fa-house"></i>
                                        </span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2" id="landlordDetails2">
                                            Landlord Details
                                        </h3>
                                        <div class="stepper-desc fw-normal" id="landLoradSmall2">
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
                                        <i class="fa fa-lock fs-2 stepper-check"></i>
                                        <span class="stepper-number">
                                            <i class="fa fa-lock"></i>
                                        </span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title fs-2">
                                            Verification
                                        </h3>
                                        <div class="stepper-desc fw-normal">
                                            Verify my information
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
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon">
                                        <i class="fa fa-credit-card fs-2 stepper-check"></i>
                                        <span class="stepper-number">
                                            <i class="fa fa-credit-card"></i>
                                        </span>
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
                                        <i class="fa fa-list fs-2 stepper-check"></i>
                                        <span class="stepper-number">
                                            <i class="fa fa-list text-white"></i>
                                        </span>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <h3 class="stepper-title">
                                            Summary
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
                <div class="stepper">
                    <div class="d-flex flex-center flex-column flex-column-fluid">
                        <!--begin::Wrapper-->
                        <div class="w-lg-800px w-xl-800px p-10 p-lg-15 mx-auto">
                            <div class="d-flex flex-stack pt-15">
                                <div class="w-sm-650px w-lg-650px w-xl-700px p-10 p-lg-5 mb-5 mx-auto">
                                    {{-- 
                              <div class="progress-bar-container">
                                 <div id="progressBar" class="progress-bar"></div>
                              </div>
                              --}}
                                    <div class="progress-bar-container">
                                        <div id="progressBar" class="progress-bar">
                                            <span id="progressPercentage">0%</span> <!-- Percentage display -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--begin::Form-->
                            <form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form">
                                <div class="step current" data-kt-stepper-element="content">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <!--begin::Heading-->
                                        <div class="pb-10 pb-lg-15">
                                            <!--begin::Title-->
                                            <h2 class="fw-bold text-gray-900">Let's get started</h2>
                                            <!--end::Title-->
                                            <!--begin::Notice-->
                                            <div class="text-muted fw-semibold fs-6">You're just a few steps away from
                                                a more rewarding experience
                                            </div>
                                            <!--end::Notice-->
                                        </div>
                                        <!--end::Heading-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label required">First Name</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input name="first_name" type="text"
                                                        class="form-control form-control-lg form-control-solid"
                                                        value="" required id="first_name">
                                                    <span class="error-message"
                                                        data-error-id="first_nameError"></span>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label required">Last Name</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input name="last_name" type="text" required
                                                        class="form-control form-control-lg form-control-solid"
                                                        value="">
                                                    <span class="error-message" data-error-id="last_nameError"></span>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label">Middle Name (<i
                                                    class="">optional</i>)</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input name="middle_name" type="text"
                                                class="form-control form-control-lg form-control-solid"
                                                value="" />
                                            <!--end::Input-->
                                        </div>
                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label required">Email</label>
                                            <!--end::Label-->
                                            <!--begin::Input Group-->
                                            <div class="input-group">
                                                <!--begin::Input-->
                                                <input name="email_address" type="email" required
                                                    class="form-control form-control-lg form-control-solid"
                                                    value="" id="emailInputField">
                                                <!--end::Input-->
                                                <!--begin::Get Code Button-->
                                                <button type="button" class="btn btn-primary" id="getCodeButton"
                                                    onclick="sendEmailCode()">
                                                    <span id="spinner" class="spinner-border spinner-border-sm"
                                                        role="status" style="display: none;"></span>
                                                    Get Code
                                                </button>
                                                <!--end::Get Code Button-->
                                            </div>
                                            <span class="error-message" data-error-id="email_addressError"></span>
                                            <!--end::Input Group-->

                                            <!--begin::Code Input Field (hidden initially)-->
                                            <div class="mt-3" id="emailValidationSection" style="display: none;">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" maxlength="6"
                                                            oninput="enforceNumericInput(this)"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Enter code" id="emailCodeInput" />
                                                        <button type="button" class="btn btn-primary"
                                                            id="validateEmailCodeButton"
                                                            onclick="validateEmailCode()">
                                                            <span id="validationSpinner"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status" style="display: none;"></span>
                                                            Validate
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Code Input Field-->
                                        </div>

                                        <div class="fv-row mb-10">
                                            <!-- Label -->
                                            <label class="form-label required">Phone Number</label>

                                            <!-- Input Group -->
                                            <div class="input-group phone-input">
                                                <!-- Canadian Flag and Country Code -->
                                                <span class="input-group-text">
                                                    <img src="https://cdn.countryflags.com/thumbs/canada/flag-400.png"
                                                        alt="Canada" class="flag-icon">
                                                    +1
                                                </span>

                                                <!-- Phone Number Input -->
                                                <input type="tel" id="phoneInput"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="Enter phone number" maxlength="10" required
                                                    oninput="validateCanadianPhoneNumber()" name="phone_number">

                                                <!-- Get Code Button -->
                                                <button type="button" class="btn btn-primary"
                                                    id="getPhoneCodeButton" onclick="sendPhoneCode()">
                                                    <span id="phoneSpinner" class="spinner-border spinner-border-sm"
                                                        role="status" style="display: none;"></span>
                                                    Get
                                                    Code</button>
                                            </div>
                                            <span class="error-message" data-error-id="phone_numberError"></span>
                                            <!-- Code Validation Section (Hidden Initially) -->
                                            <div class="mt-3" id="phoneCodeValidationSection"
                                                style="display: none;">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input type="text" id="phoneCodeInput"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Enter code"
                                                            oninput="enforceNumericInput(this)" maxlength="6"
                                                            oninput="validateCodeInput()"
                                                            placeholder="Enter Verification Code">
                                                        <button type="button" class="btn btn-primary"
                                                            id="validatePhoneCodeButton"
                                                            onclick="validatePhoneCode()">
                                                            <span id="phoneValidationSpinner"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status" style="display: none;"></span>
                                                            Validate
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="fv-row mb-8">
                                            <div
                                                class="fv-row mb-5 fv-plugins-icon-container fv-plugins-bootstrap5-row-invalid">
                                                <label
                                                    class="form-check form-check-custom form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="email_and_phone_terms" value="email_and_phone_terms" required>
                                                    <span class="form-check-label fw-semibold text-gray-700 fs-6">
                                                        For the above presented mean of contact email, phone and address
                                                    </span>
                                                </label>
                                            </div>
                                            <span class="error-message" data-error-id="email_and_phone_termsError"></span>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--begin::Step 1-->
                                <div class="step" data-step-form="true" data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <div id="multiStepForm">
                                            <div id="Rent_mortgage_div" class="Rent_mortgage_divs substep Rent_mortgage_div">
                                                <!--begin::Heading-->
                                                <div class="pb-10 pb-lg-15">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">What are your primary goals with
                                                        CrediPay?
                                                    </h2>
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
                                                            <input type="radio" class="btn-check" required
                                                                name="rent_account_type" value="rent"
                                                                id="kt_create_account_form_account_type_personal" />
                                                            <label
                                                                class="sm-label btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-10"
                                                                for="kt_create_account_form_account_type_personal">
                                                                <i class="fa fa-house fs-3x me-5"></i>
                                                                <!--begin::Info-->
                                                                <span class="d-block fw-semibold text-start">
                                                                    <span
                                                                        class="text-gray-900 fw-bold d-block fs-4 mb-2">
                                                                        Rent
                                                                    </span>
                                                                    <span class="text-muted fw-semibold fs-6">Pay my
                                                                        rent
                                                                        with a credit or debit card.</span>
                                                                </span>
                                                                <!--end::Info-->
                                                            </label>
                                                        </div>
                                                        <!--end::Col-->
                                                        <!--begin::Col-->
                                                        <div class="col-lg-6 form-col">
                                                            <!--begin::Option-->
                                                            <input type="radio" class="btn-check" required
                                                                name="rent_account_type" value="mortgage"
                                                                id="kt_create_account_form_account_type_corporate" />
                                                            <label
                                                                class="sm-label btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
                                                                for="kt_create_account_form_account_type_corporate">
                                                                <i class="fa fa-house fs-3x me-5"></i>
                                                                <!--begin::Info-->
                                                                <span class="d-block fw-semibold text-start">
                                                                    <span
                                                                        class="text-gray-900 fw-bold d-block fs-4 mb-2">Mortgage</span>
                                                                    <span class="text-muted fw-semibold fs-6">Pay my
                                                                        rent
                                                                        with a credit or debit card.</span>
                                                                </span>
                                                                <!--end::Info-->
                                                            </label>
                                                            <!--end::Option-->
                                                        </div>
                                                        <span class="error-message"
                                                            data-error-id="rent_account_typeError"></span>
                                                        <!--end::Col-->
                                                    </div>
                                                    <!--end::Row-->
                                                </div>
                                            </div>
                                            <div id="RentStep1" class="Rent_mortgage_divs substep RentDivTagStep77">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">Do you want to pay rent or pay
                                                        rent and build credit?
                                                    </h2>
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
                                                                <span class="fs-6 fw-semibold text-muted">I just
                                                                    want
                                                                    to
                                                                    pay my rent with a credit or debit card..</span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="account_plan_rent" value="pay_rent" required>
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
                                                                <span class="fs-6 fw-semibold text-muted">I want to
                                                                    pay
                                                                    my
                                                                    rent with a credit or debit card and build
                                                                    credit
                                                                    for
                                                                    free</span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" required
                                                                name="account_plan_rent" value="pay_rent_build">
                                                        </span>
                                                        <!--end:Input-->
                                                    </label>
                                                    <!--end::Option-->
                                                    <span class="error-message"
                                                        data-error-id="account_plan_rentError"></span>
                                                </div>
                                            </div>
                                            <div id="RentStep2" class="Rent_mortgage_divs substep RentDivTagStep">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">Is it a Sole or Co-application?
                                                    </h2>
                                                    <!--end::Title-->
                                                </div>
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
                                                            name="rent_account_plan_type" value="sole_applicant"
                                                            required>
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
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
                                                            name="rent_account_plan_type" value="co_applicant"
                                                            required>
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <span class="error-message"
                                                    data-error-id="rent_account_plan_typeError"></span>
                                            </div>
                                            <div id="rentStep5" class="Rent_mortgage_divs substep RentDivTagStep">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">Add Co-Applicant</h2>
                                                    <!--end::Title-->
                                                </div>
                                                <div class="form-group rounded border p-5 mb-4">
                                                    <div class="col-md-12">
                                                        <label class="form-label required">Primary applicant rent
                                                            amount:</label>
                                                        <input type="number" name="coApplicanRentPrimaryAmount"
                                                            id="coApplicanRentPrimaryAmount"
                                                            class="form-control mb-2 mb-md-0" placeholder="e.g 1,000"
                                                            required>
                                                    </div>
                                                    <span class="error-message"
                                                        data-error-id="coApplicanRentPrimaryAmountError"></span>
                                                </div>
                                                <div class="form-group text-right pb-3">
                                                    <a href="javascript:;" id="addApplicantBtn"
                                                        class="btn btn-flex flex-center btn-light-primary">
                                                        <i class="fs-3"></i> Add
                                                    </a>
                                                </div>
                                                <div id="applicantsContainer">
                                                    <div class="rounded border mb-2 p-4 addNewApplicant"
                                                        id="addNewApplicant111">
                                                        <!--begin::Repeater-->
                                                        <div id="kt_docs_repeater_basic">
                                                            <div class="form-group row mb-3">
                                                                <div class="col-md-3">
                                                                    <label class="form-label">First Name:</label>
                                                                    <input type="text"
                                                                        name="coApplicantfirstName[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        placeholder="Enter first name" required>
                                                                    <span class="error-message"
                                                                        data-error-id="coApplicantfirstNameError"></span>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Last Name:</label>
                                                                    <input type="text" name="coApplicantlastName[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        placeholder="Enter last name" required>
                                                                    <span class="error-message"
                                                                        data-error-id="coApplicantlastNameError"></span>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Email:</label>
                                                                    <input type="email" name="coApplicantemail[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        placeholder="Enter email address" required>
                                                                    <span class="error-message"
                                                                        data-error-id="coApplicantemailError"></span>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Rent Amount:</label>
                                                                    <input type="number" name="coApplicantamount[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        min="100" max="20000"
                                                                        placeholder="1,000.00" required>
                                                                    <span class="error-message"
                                                                        data-error-id="coApplicantamountError"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Repeater-->
                                                    </div>
                                                </div>

                                                <!--end::Form group-->
                                            </div>
                                            <div id="RentStep3" class="Rent_mortgage_divs substep RentDivTagStep">
                                                <div class="pb-10 pb-lg-5">
                                                    <h2 class="fw-bold text-gray-900">What is your Card limit
                                                        and the due date?
                                                    </h2>
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label required">Credit Card limit new</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input name="credit_card_limit" type="number"
                                                        oninput="validateNumericInput()"
                                                        class="form-control form-control-lg form-control-solid"
                                                        value="" placeholder="eg 1500.00" required
                                                        min="100" max="20000">
                                                    <span class="error-message"
                                                        data-error-id="credit_card_limitError"></span>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <label class="form-label required">Credit Card due date</label>
                                                    <div class="row mb-1">
                                                        <div class="col-md-12">
                                                            <div class="mb-10 fv-row">
                                                                <label class="form-label mb-3 required">Monthly</label>
                                                                <select
                                                                    class="form-control form-control-lg form-control-solid"
                                                                    name="day">
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
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                    <option value="25">25</option>
                                                                    <option value="26">26</option>
                                                                    <option value="27">27</option>
                                                                    <option value="28">28</option>
                                                                    <option value="29">29</option>
                                                                    <option value="30">30</option>
                                                                    <option value="31">31</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="RentStep4" class="Rent_mortgage_divs substep RentDivTagStep">
                                                <!--begin::Heading-->
                                                <div class="pb-10 pb-lg-5">
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
                                                                    <i class="fa fa-home fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">Continue
                                                                    paying an existing rent</span>
                                                                <span class="fs-6 fw-semibold text-muted">I want to
                                                                    switch
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
                                                                    payment for a new rental</span>
                                                                <span class="fs-6 fw-semibold text-muted">I am moving
                                                                    and
                                                                    need to set up payments for a new rental
                                                                    unit.</span>
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
                                            <div id="MortgageStep1" class="Rent_mortgage_divs substep">
                                                <div class="pb-10 pb-lg-15">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">Do you want to pay for mortgage,
                                                        or pay and build credit?
                                                    </h2>
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
                                                                    mortgage</span>
                                                                <span class="fs-6 fw-semibold text-muted">I just
                                                                    want
                                                                    to
                                                                    pay my mortgage with a credit or debit
                                                                    card..</span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="account_plan_mortgage" value="pay_mortgage">
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
                                                                    mortgage and build credit</span>
                                                                <span class="fs-6 fw-semibold text-muted">I want to
                                                                    pay
                                                                    my
                                                                    mortgage with a credit or debit card and build
                                                                    credit
                                                                    for
                                                                    free</span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" required
                                                                name="account_plan_mortgage"
                                                                value="pay_mortgage_build">
                                                        </span>
                                                        <!--end:Input-->
                                                    </label>
                                                    <!--end::Option-->
                                                    <span class="error-message"
                                                        data-error-id="account_plan_mortgageError"></span>
                                                </div>
                                            </div>
                                            <div id="MortgageStep2" class="Rent_mortgage_divs substep MortgageDivTagStep">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">Owner or Co-owner?
                                                    </h2>
                                                    <!--end::Title-->
                                                </div>
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
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Owner</span>
                                                            <span class="fs-6 fw-semibold text-muted">I want to pay
                                                                this for alone</span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->
                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                            name="mortgage_account_plan_type" value="sole_applicant"
                                                            required>
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
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
                                                                class="fw-bold text-gray-800 text-hover-primary fs-5">Co-owner</span>
                                                            <span class="fs-6 fw-semibold text-muted">I want to pay
                                                                with other people</span>
                                                        </span>
                                                        <!--end:Description-->
                                                    </span>
                                                    <!--end:Label-->
                                                    <!--begin:Input-->
                                                    <span class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio"
                                                            name="mortgage_account_plan_type" value="co_applicant"
                                                            required>
                                                    </span>
                                                    <!--end:Input-->
                                                </label>
                                                <span class="error-message" data-error-id="housingError"></span>
                                            </div>
                                            <div id="MortgageStep3" class="Rent_mortgage_divs substep MortgageDivTagStep">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">What is your Card limit
                                                        and the due date?
                                                    </h2>
                                                    <!--end::Title-->
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label required">Credit Card limit new</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input name="mortgage_credit_card_limit" type="number"
                                                        oninput="validateNumericInput()"
                                                        class="form-control form-control-lg form-control-solid"
                                                        value="" placeholder="eg 1500.00" required
                                                        min="100" max="200000">
                                                    <span class="error-message"></span>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <label class="form-label required">Credit Card due date</label>
                                                    <div class="row mb-1">
                                                        <div class="col-md-12">
                                                            <div class="mb-10 fv-row">
                                                                <label class="form-label mb-3 required">Monthly</label>
                                                                <select
                                                                    class="form-control form-control-lg form-control-solid"
                                                                    name="day">
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
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                    <option value="25">25</option>
                                                                    <option value="26">26</option>
                                                                    <option value="27">27</option>
                                                                    <option value="28">28</option>
                                                                    <option value="29">29</option>
                                                                    <option value="30">30</option>
                                                                    <option value="31">31</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-md-6 fv-row">
                                                            <div class="mb-10 fv-row">
                                                                <label class="form-label mb-3 required">Month</label>
                                                                <select
                                                                    class="form-control form-control-lg form-control-solid"
                                                                    id="month" name="month">
                                                                    <option value="01">January</option>
                                                                    <option value="02">February</option>
                                                                    <option value="03">March</option>
                                                                    <option value="04">April</option>
                                                                    <option value="05">May</option>
                                                                    <option value="06">June</option>
                                                                    <option value="07">July</option>
                                                                    <option value="08">August</option>
                                                                    <option value="09">September</option>
                                                                    <option value="10">October</option>
                                                                    <option value="11">November</option>
                                                                    <option value="12">December</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 fv-row">
                                                            <div class="mb-10 fv-row">
                                                                <label class="form-label mb-3 required">Day</label>
                                                                <select
                                                                    class="form-control form-control-lg form-control-solid"
                                                                    name="day">
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
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                    <option value="25">25</option>
                                                                    <option value="26">26</option>
                                                                    <option value="27">27</option>
                                                                    <option value="28">28</option>
                                                                    <option value="29">29</option>
                                                                    <option value="30">30</option>
                                                                    <option value="31">31</option>
                                                                </select>
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="MortgageStep5" class="Rent_mortgage_divs substep MortgageDivTagStep">
                                                <!--begin::Heading-->
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">Setting Up Mortgage Payments</h2>
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
                                                                    <i class="fa fa-home fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">Continue
                                                                    paying an existing mortgage</span>
                                                                <span class="fs-6 fw-semibold text-muted">I want to
                                                                    switch
                                                                    my current mortgage payments to CrediPay. </span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="mortgage_account_plan_type_mode" value="111"
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
                                                                    payment for a new mortgage</span>
                                                                <span class="fs-6 fw-semibold text-muted">I am moving
                                                                    and
                                                                    need to set up payments for a new mortgage
                                                                    unit.</span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="mortgage_account_plan_type_mode" value="222">
                                                        </span>
                                                        <!--end:Input-->
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                            </div>
                                            <div id="MortgageStep4" class="Rent_mortgage_divs substep MortgageDivTagStep">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900">Add Co-owner</h2>
                                                    <!--end::Title-->
                                                </div>
                                                <div class="form-group rounded border p-5 mb-4">
                                                    <div class="col-md-12">
                                                        <label class="form-label required">Owner primary mortgage
                                                            amount:</label>
                                                        <input type="number" name="coApplicanMortgagePrimaryAmount"
                                                            class="form-control mb-2 mb-md-0" placeholder="e.g 1,000">
                                                    </div>
                                                </div>
                                                <div class="form-group text-right pb-3">
                                                    <a href="javascript:;" id="addApplicantBtn2"
                                                        class="btn btn-flex flex-center btn-light-primary">
                                                        <i class="fs-3"></i> Add
                                                    </a>
                                                </div>
                                                <div id="applicantsContainer2">
                                                    <div class="rounded border mb-2 p-4 addNewApplicant2"
                                                        id="addNewApplicant1113333">
                                                        <!--begin::Repeater-->
                                                        <div id="kt_docs_repeater_basic">
                                                            <div class="form-group row mb-3">
                                                                <div class="col-md-3">
                                                                    <label class="form-label">First Name:</label>
                                                                    <input type="text"
                                                                        name="mortgagecoOwnerfirstName[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        placeholder="Enter first name">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Last Name:</label>
                                                                    <input type="text"
                                                                        name="mortgagecoOwnerlastName[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        placeholder="Enter last name">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Email:</label>
                                                                    <input type="email"
                                                                        name="mortgagecoOwneremail[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        placeholder="Enter email address">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Mortgage Amnount:</label>
                                                                    <input type="numbeer" id="coOwneramountAdmin"
                                                                        name="mortgagecoOwneramount[]"
                                                                        class="form-control mb-2 mb-md-0"
                                                                        placeholder="1,000.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Repeater-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Actions-->
                                    <div class="d-flex flex-stack pt-10 btn-div-sm">
                                        <div class="mr-2">
                                            <button type="button" type="button"
                                                class="btn btn-lg btn-primary backButtonStep1"
                                                id="backButtonStep1">Back</button>
                                        </div>
                                        <div>
                                            <button type="button" type="button"
                                                class="btn btn-lg btn-primary nextButtonStep1"
                                                id="nextButtonStep1">Next</button>
                                            </button>
                                        </div>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Step 1-->
                                <!--begin::Step 2-->
                                <div class="step"  data-step-form="true" data-kt-stepper-element="content">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <div id="multiStepForm">
                                            <div id="address_details_div" class="addressDetails1_divs">
                                                <!--begin::Heading-->
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900" id="RentalMortAddress">What's
                                                        your rental address?</h2>
                                                    <!--end::Title-->
                                                    <!--begin::Notice-->
                                                    <div class="text-muted fw-semibold fs-6"
                                                        id="RentalMortAddressMuted">
                                                        Enter your rental address and unit number if applicable..
                                                    </div>
                                                    <!--end::Notice-->
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label mb-3 required">Address</label>
                                                        <input type="text" id="address-input" required
                                                            class="form-control form-control-lg form-control-solid"
                                                            name="address" placeholder="Enter your address" />
                                                        <span class="error-message"
                                                            data-error-id="addressError"></span>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 fv-row">
                                                        <div class="mb-10 fv-row">
                                                            <label class="form-label mb-3 required">Province</label>
                                                            <select id="province" required
                                                                class="form-select form-select-solid" name="province"
                                                                onchange="updateCities()">
                                                                <option value="">--Select a Province--</option>
                                                                <option value="Ontario">Ontario</option>
                                                                <option value="British Columbia">British Columbia
                                                                </option>
                                                                <option value="Quebec">Quebec</option>
                                                                <option value="Alberta">Alberta</option>
                                                                <option value="Manitoba">Manitoba</option>
                                                                <option value="Nova Scotia">Nova Scotia</option>
                                                                <option value="Newfoundland and Labrador">Newfoundland
                                                                    and Labrador</option>
                                                                <option value="New Brunswick">New Brunswick</option>
                                                                <option value="Prince Edward Island">Prince Edward
                                                                    Island</option>
                                                                <option value="Saskatchewan">Saskatchewan</option>
                                                            </select>
                                                            <span class="error-message"
                                                                data-error-id="provinceError"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 fv-row">
                                                        <div class="mb-10 fv-row">
                                                            <label class="form-label mb-3 required">City</label>
                                                            <select id="city" required
                                                                class="form-select form-select-solid">
                                                                <option value="">--Select a City--</option>
                                                            </select>
                                                            <span class="error-message"
                                                                data-error-id="cityError"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <!--begin::Col-->
                                                    <div class="col-md-6 fv-row">
                                                        <div class="mb-10 fv-row">
                                                            <label class="form-label mb-3 required">Postal Code</label>
                                                            <input type=""
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="city" placeholder="" value=""
                                                                required>
                                                        </div>
                                                        <span class="error-message" data-error-id="cityError"></span>
                                                    </div>
                                                    <div class="col-md-6 fv-row">
                                                        <!--begin::Input group-->
                                                        <div class="mb-10 fv-row">
                                                            <label class="form-label mb-3">Unit Number</label>
                                                            <input type="number"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="house_number" placeholder="" value="" />
                                                            <span class="text-muted">Apartment or Condo</span>
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
                                            </div>
                                            <div id="addressDetails1" class="addressDetails1_divs">
                                                <!--begin::Heading-->
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900" id="rentMortgageAmount">Rent
                                                        Amount</h2>
                                                    <!--end::Title-->
                                                </div>
                                                <!--begin::Input group-->
                                                <div class="mb-10 fv-row">
                                                    <!--begin::Input-->
                                                    <label class="form-label mb-3 required">Amount</label>
                                                    <input type="number"
                                                        class="form-control form-control-lg form-control-solid"
                                                        name="how_much_is_rent" id="amount_admin_pay" min="100"
                                                        max="20000" placeholder="eg 2500.00" value=""
                                                        required>
                                                    <div class="text-muted">
                                                        The minimum amount is {{ number_format(100, 2) }} CAD while the
                                                        maximum is {{ number_format(50000, 2) }} CAD.
                                                    </div>
                                                    <!--end::Input-->
                                                    <span class="error-message"
                                                        data-error-id="how_much_is_rentError"></span>
                                                </div>
                                            </div>
                                            <div id="addressDetails2" class="addressDetails1_divs">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900" id="uploadAgreement">Upload
                                                        tenancy agreement</h2>
                                                    <!--end::Title-->
                                                </div>
                                                <div class="mb-10 fv-row">
                                                    <label class="form-label mb-3 required">Upload File</label>
                                                    <input type="file"
                                                        class="form-control form-control-lg form-control-solid"
                                                        name="tenancy_agreement" value="" />
                                                    <span class="error-message"
                                                        data-error-id="tenancy_agreementError"></span>
                                                </div>
                                            </div>
                                            <div id="addressDetails3" class="addressDetails1_divs">
                                                <div class="pb-10 pb-lg-5">
                                                    <h2 class="fw-bold text-gray-900">Re-occuring monthly?</h2>
                                                </div>
                                                <div class="mb-10 fv-row">
                                                    <label class="form-label mb-3 required">Day</label>
                                                    <select class="form-control form-control-lg form-control-solid"
                                                        name="re_occuring_monthly_day">
                                                        <option value="">Select Day</option>
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
                                                        <option value="13">13</option>
                                                        <option value="14">14</option>
                                                        <option value="15">15</option>
                                                        <option value="16">16</option>
                                                        <option value="17">17</option>
                                                        <option value="18">18</option>
                                                        <option value="19">19</option>
                                                        <option value="20">20</option>
                                                        <option value="21">21</option>
                                                        <option value="22">22</option>
                                                        <option value="23">23</option>
                                                        <option value="24">24</option>
                                                        <option value="25">25</option>
                                                        <option value="26">26</option>
                                                        <option value="27">27</option>
                                                        <option value="28">28</option>
                                                        <option value="29">29</option>
                                                        <option value="30">30</option>
                                                        <option value="31">31</option>
                                                    </select>
                                                    <span class="error-message"
                                                        data-error-id="re_occuring_monthly_dayError"></span>
                                                </div>
                                            </div>
                                            <div id="addressDetails4" class="addressDetails1_divs">
                                                <div class="pb-10 pb-lg-5">
                                                    <h2 class="fw-bold text-gray-900">What's the duration?</h2>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-12 fv-row">
                                                            <label class="form-label mb-3 required">From</label>
                                                            <input type="date"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="duration_from" value="">
                                                            <span class="error-message"
                                                                data-error-id="duration_fromError"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-12 fv-row">
                                                            <label class="form-label mb-3 required">To</label>
                                                            <input type="date"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="duration_to" value="">
                                                            <span class="error-message"
                                                                data-error-id="duration_toError"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Wrapper--><!--begin::Actions-->
                                        <div class="d-flex flex-stack pt-10 btn-div-sm">
                                            <div class="mr-2">
                                                <button type="button" type="button"
                                                    class="btn btn-lg btn-primary backButtonAlt"
                                                    id="backButtonAlt">Back</button>
                                            </div>
                                            <div>
                                                <button type="button" type="button"
                                                    class="btn btn-lg btn-primary nextButtonAlt"
                                                    id="nextButtonAlt">Next</button>
                                                </button>
                                            </div>
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                </div>
                                <!--end::Step 2-->
                                <!--begin::Step 3-->
                                <div class="step"  data-kt-stepper-element="content">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <!--end::Heading-->
                                        <div id="multiStepForm">
                                            <div id="landlordDetails1" class="landlordDetails1_divs">
                                                <div class="pb-10 pb-lg-5">
                                                    <!--begin::Title-->
                                                    <h2 class="fw-bold text-gray-900" id="landlordDetails">Landlord
                                                        Details</h2>
                                                    <!--end::Title-->
                                                    <!--begin::Notice-->
                                                    <div class="text-muted fw-semibold fs-6" id="landLoradSmall">
                                                        How does your lanlord accept rent?
                                                    </div>
                                                    <!--end::Notice-->
                                                </div>
                                                <div class="mb-0" id="rentFinance">
                                                    <!--begin:Option-->
                                                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                        <!--begin:Label-->
                                                        <span class="d-flex align-items-center me-2">
                                                            <!--begin::Icon-->
                                                            <span class="symbol symbol-50px me-6">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="fa fa-money-check fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">Interac
                                                                    E-transfer</span>
                                                                <span class="fs-6 fw-semibold text-muted"></span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="landlord_account_mode" value="interac"
                                                                onclick="toggleEmailInput()" required>
                                                        </span>
                                                    </label>
                                                    <div id="emailInput" style="display: none; margin-top: 10px;">
                                                        <label for="email">Enter your landlord's email:</label>
                                                        <input type="" id="" name=""
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="example@example.com" required>
                                                    </div>
                                                    <!--end::Option-->
                                                    <!--begin:Option-->
                                                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                        <!--begin:Label-->
                                                        <span class="d-flex align-items-center me-2">
                                                            <!--begin::Icon-->
                                                            <span class="symbol symbol-50px me-6">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="fa fa-money-check-dollar fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">Cheque</span>
                                                                <span class="fs-6 fw-semibold text-muted"></span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="landlord_account_mode" value="cheque"
                                                                onclick="toggleEmailInput()">
                                                        </span>
                                                        <!--end:Input-->
                                                    </label>
                                                    <!--end::Option-->
                                                    <span class="error-message"
                                                        data-error-id="landlord_account_modeError"></span>
                                                </div>
                                                <div class="mb-0" id="mortgageFinance" style="display:none">
                                                    <!--begin:Option-->
                                                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                        <!--begin:Label-->
                                                        <span class="d-flex align-items-center me-2">
                                                            <!--begin::Icon-->
                                                            <span class="symbol symbol-50px me-6">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="fa fa-money-check fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">EFT
                                                                    (Electronic Funds Transfer)</span>
                                                                <span class="fs-6 fw-semibold text-muted"></span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="mortgage_financer_account_mode" value="EFT"
                                                                onclick="toggleEFTInput()" required>
                                                        </span>
                                                    </label>
                                                    <div id="EFTInputdiv" style="display: none; margin: 2rem;">
                                                        <div class="mb-10 fv-row">
                                                            <div class="row">
                                                                <div class="col-md-6"><label
                                                                        class="form-label mb-3">Bank name:</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-lg form-control-solid"
                                                                        name="" placeholder=""
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label mb-3">Account
                                                                        number:</label>
                                                                    <input type="number"
                                                                        class="form-control form-control-lg form-control-solid"
                                                                        name="" placeholder=""
                                                                        value="" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-10 fv-row">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-label mb-3">Routing
                                                                        number:</label>
                                                                    <input type="number"
                                                                        class="form-control form-control-lg form-control-solid"
                                                                        name="" placeholder=""
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label mb-3">Account
                                                                        type:</label>
                                                                    <select name=""
                                                                        class="form-select form-select-solid">
                                                                        <option value="">---</option>
                                                                        <option value="Checking">Checking</option>
                                                                        <option value="Savings">Savings</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Option-->
                                                    <!--begin:Option-->
                                                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                        <!--begin:Label-->
                                                        <span class="d-flex align-items-center me-2">
                                                            <!--begin::Icon-->
                                                            <span class="symbol symbol-50px me-6">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="fa fa-money-check-dollar fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">ACH
                                                                    (Automated Clearing House)</span>
                                                                <span class="fs-6 fw-semibold text-muted"></span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="mortgage_financer_account_mode" value="ACH"
                                                                onclick="toggleEFTInput()">
                                                        </span>
                                                        <!--end:Input-->
                                                    </label>
                                                    <div id="ACHInputdiv" style="display: none; margin: 2rem;">
                                                        <div class="mb-10 fv-row">
                                                            <label class="form-label mb-3">Bank routing or ABA
                                                                number:</label>
                                                            <input type="number"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="" placeholder="" value="" />
                                                        </div>
                                                        <div class="mb-10 fv-row">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-label mb-3">Recipient's
                                                                        name:</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-lg form-control-solid"
                                                                        name="" placeholder=""
                                                                        value="" />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label mb-3">Account
                                                                        number:</label>
                                                                    <input type="number"
                                                                        class="form-control form-control-lg form-control-solid"
                                                                        name="" placeholder=""
                                                                        value="" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-10 fv-row">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-label mb-3">Account
                                                                        type:</label>
                                                                    <select name=""
                                                                        class="form-select form-select-solid">
                                                                        <option value="">---</option>
                                                                        <option value="Checking">Checking</option>
                                                                        <option value="Savings">Savings</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label mb-3">If they're an
                                                                        individual or a business:</label>
                                                                    <select name=""
                                                                        class="form-select form-select-solid">
                                                                        <option value="">---</option>
                                                                        <option value="individual">Individual</option>
                                                                        <option value="business">Business</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Option-->

                                                    <span class="error-message"
                                                        data-error-id="mortgage_financer_account_modeError"></span>
                                                </div>
                                            </div>
                                            <div id="landlordDetails3" class="landlordDetails1_divs">
                                                <div class="pb-10 pb-lg-5">
                                                    <h2 class="fw-bold text-gray-900">Is your landlord an individual
                                                        or
                                                        business?
                                                    </h2>
                                                    <div class="text-muted fw-semibold fs-6">
                                                    </div>
                                                </div>
                                                <div class="mb-0">
                                                    <!--begin:Option-->
                                                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                        <!--begin:Label-->
                                                        <span class="d-flex align-items-center me-2">
                                                            <!--begin::Icon-->
                                                            <span class="symbol symbol-50px me-6">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="fa fa-money-check fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">Business</span>
                                                                <span class="fs-6 fw-semibold text-muted"></span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="account_plan_type_mode_lanlord"
                                                                value="business"
                                                                onclick="chequeOptionForLandLord()">
                                                        </span>
                                                        <!--end:Input-->
                                                    </label>

                                                    <div id="landlordDetails4" style="display: none; margin: 2rem">
                                                        <div class="fv-row mb-10">
                                                            <label class="form-label required">Business Name</label>
                                                            <input name="" type="text"
                                                                class="form-control form-control-lg form-control-solid"
                                                                value="" />
                                                        </div>
                                                    </div>
                                                    <!--end::Option-->
                                                    <!--begin:Option-->
                                                    <label class="d-flex flex-stack mb-5 cursor-pointer">
                                                        <!--begin:Label-->
                                                        <span class="d-flex align-items-center me-2">
                                                            <!--begin::Icon-->
                                                            <span class="symbol symbol-50px me-6">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="fa fa-money-check-dollar fs-1 text-gray-600"></i>
                                                                </span>
                                                            </span>
                                                            <!--end::Icon-->
                                                            <!--begin::Description-->
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fw-bold text-gray-800 text-hover-primary fs-5">Indivivial</span>
                                                                <span class="fs-6 fw-semibold text-muted"></span>
                                                            </span>
                                                            <!--end:Description-->
                                                        </span>
                                                        <!--end:Label-->
                                                        <!--begin:Input-->
                                                        <span class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio"
                                                                name="account_plan_type_mode_lanlord"
                                                                value="individual"
                                                                onclick="chequeOptionForLandLord()">
                                                        </span>
                                                        <!--end:Input-->
                                                    </label>

                                                    <div id="landlordDetails5" style="display: none; margin: 2rem">
                                                        <div class="fv-row mb-10">
                                                            <label class="form-label required">First Name</label>
                                                            <input name="" type="text"
                                                                class="form-control form-control-lg form-control-solid"
                                                                value="" />
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <label class="form-label required">Last Name</label>
                                                            <input name="" type="text"
                                                                class="form-control form-control-lg form-control-solid"
                                                                value="" />
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <label class="form-label">Middle Name</label>
                                                            <input name="" type="text"
                                                                class="form-control form-control-lg form-control-solid"
                                                                value="" />
                                                        </div>
                                                    </div>
                                                    <!--end::Option-->
                                                </div>
                                            </div>
                                            <div class="d-flex flex-stack pt-10 btn-div-sm">
                                                <div class="mr-2">
                                                    <button type="button" type="button"
                                                        class="btn btn-lg btn-primary backButtonLandLord1"
                                                        id="backButtonLandLord1">Back</button>
                                                </div>
                                                <div>
                                                    <button type="button" type="button"
                                                        class="btn btn-lg btn-primary nextButtonLandLord1"
                                                        id="nextButtonLandLord1" style="display: none">Next</button>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Step 3-->
                                <!--begin::Step 3-->
                                <div class="step" data-kt-stepper-element="content">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <!--begin::Heading-->
                                        <div class="pb-10 pb-lg-15">
                                            <!--begin::Title-->
                                            <h2 class="fw-bold text-gray-900">Verification with Plaid </h2>
                                            <!--end::Title-->
                                            <!--begin::Notice-->
                                            <div class="text-muted fw-semibold fs-6">
                                                Please double-check the details before submitting.
                                            </div>
                                            <!--end::Notice-->
                                            <!-- Terms and Conditions Container -->
                                            <div id="termsContainer" class="mt-10">
                                                <h3>Terms and Conditions</h3>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at
                                                    ipsum orci. Vivamus mollis tortor nec quam condimentum, non
                                                    facilisis nisi tempor. Phasellus lacinia ligula sit amet dictum
                                                    pulvinar.</p>
                                                <p>Curabitur at lacinia sem, in auctor orci. Suspendisse quis metus
                                                    euismod, faucibus libero nec, fermentum erat. Nam scelerisque neque
                                                    a urna sagittis vehicula.</p>
                                                <p>Sed consequat leo eu magna convallis varius. Suspendisse potenti.
                                                    Aenean viverra risus felis, eget interdum enim ultricies ac.</p>
                                                <p>In at eros non ipsum venenatis vehicula. Nam laoreet semper metus,
                                                    sed dictum ligula scelerisque eu. Mauris quis tortor nec est commodo
                                                    tempor.</p>
                                                <p>Ut sit amet massa in urna auctor cursus. Vivamus lacinia lacinia
                                                    arcu, vel pulvinar lectus.</p>
                                                <!-- Add more terms content here as needed -->

                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla at
                                                    ipsum orci. Vivamus mollis tortor nec quam condimentum, non
                                                    facilisis nisi tempor. Phasellus lacinia ligula sit amet dictum
                                                    pulvinar.</p>
                                                <p>Curabitur at lacinia sem, in auctor orci. Suspendisse quis metus
                                                    euismod, faucibus libero nec, fermentum erat. Nam scelerisque
                                                    neque a urna sagittis vehicula.</p>
                                                <!-- Add more text as needed -->
                                                <p>Sed consequat leo eu magna convallis varius. Suspendisse potenti.
                                                    Aenean viverra risus felis, eget interdum enim ultricies ac.</p>
                                                <p>In at eros non ipsum venenatis vehicula. Nam laoreet semper
                                                    metus, sed dictum ligula scelerisque eu. Mauris quis tortor nec
                                                    est commodo tempor.</p>
                                                <!-- Add more text as needed -->
                                            </div>

                                            <div
                                                class="fv-row mb-10 fv-plugins-icon-container fv-plugins-bootstrap5-row-invalid">
                                                <label
                                                    class="form-check form-check-custom form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="terms"
                                                        id="agreeCheckbox" value="1"
                                                        data-gtm-form-interact-field-id="0" disabled>
                                                    <span class="form-check-label fw-semibold text-gray-700 fs-6">
                                                        I Agree to the Terms and conditions
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="terms-footer">
                                                {{-- <input type="checkbox" class="form-check-input form-check-input-new"
                                                    id="agreeCheckbox" > I agree to the Terms and
                                                Conditions<br> --}}
                                                <button type="button" id="proceedButton"
                                                    class="btn btn-sm mt-1 btn-lg btn-primary">Proceed</button>
                                            </div>

                                        </div>
                                        <!--end::Heading-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <div class="step" data-kt-stepper-element="content">
                                    <!-- Billing Details Section -->
                                    <div class="w-100 mb-5">
                                        <h2 class="fw-bold text-gray-900">Billing Details</h2>
                                        <div class="text-muted fw-semibold fs-6">Kindly provide with us your billing
                                            details</div>

                                        <!-- Name on Card Input -->
                                        <div class="d-flex flex-column mb-7 fv-row mt-10">
                                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                <span class="required">Amount to Deduct</span>
                                            </label>
                                            <input type="number" max="100000000000"
                                                class="form-control form-control-solid" placeholder="e.g 1000"
                                                name="" id="defaulBillingCardAmount">
                                        </div>

                                        <!-- Name on Card Input -->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                                <span class="required">Name On Card</span>
                                            </label>
                                            <input type="text" class="form-control form-control-solid"
                                                placeholder="Name on Card" name="card_name">
                                        </div>

                                        <!-- Card Number Input -->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <label class="required fs-6 fw-semibold form-label mb-2">Card
                                                Number</label>
                                            <input type="text" class="form-control form-control-solid"
                                                placeholder="Enter card number" name="card_number">
                                        </div>

                                        <!-- Expiration Date and CVV -->
                                        <div class="row mb-10">
                                            <div class="col-md-8 fv-row">
                                                <label class="required fs-6 fw-semibold form-label mb-2">Expiration
                                                    Date</label>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select name="card_expiry_month"
                                                            class="form-select form-select-solid">
                                                            <option value="">Month</option>
                                                            <!-- Months -->
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <!-- Continue up to 12 -->
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select name="card_expiry_year"
                                                            class="form-select form-select-solid">
                                                            <option value="">Year</option>
                                                            <!-- Years -->
                                                            <option value="2024">2024</option>
                                                            <option value="2025">2025</option>
                                                            <!-- Continue as needed -->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 fv-row">
                                                <label class="required fs-6 fw-semibold form-label mb-2">CVV</label>
                                                <input type="text" class="form-control form-control-solid"
                                                    placeholder="CVV" name="card_cvv" minlength="3"
                                                    maxlength="4">
                                            </div>
                                        </div>

                                        <!-- Amount Admin Pay Input (hidden by default) -->
                                        {{-- <input type="hidden" id="amount_admin_pay" name="amount_admin_pay"
                                            value=""> --}}

                                        <!-- Add Multiple Credit Cards Toggle -->
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox"
                                                id="multipleCardsToggle" onclick="toggleMultipleCards()">
                                            <label class="form-check-label" for="multipleCardsToggle">Add Multiple
                                                Credit Cards</label>
                                        </div>

                                        <!-- Dynamic Credit Card Container -->
                                        <div id="additionalCardsContainer" style="display: none; margin-top:3rem">
                                        </div>

                                        <!-- Add Button for Additional Cards -->
                                        <button type="button"
                                            class="btn btn-lg btn-secondary mt-4 btn-sm text-white"
                                            id="addCardButton" style="display: none; background-color:#a000f987"
                                            onclick="addCreditCard()">Add Another Card</button>
                                    </div>
                                </div>
                                <!--end::Step 3-->
                                <!--begin::Step 3-->
                                <div class="step" data-kt-stepper-element="content">
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
                                        <button type="button" type="button" class="btn btn-lg btn-primary"
                                            id="backButton">Previous</button>
                                        {{-- <button type="button" class="btn btn-lg btn-light-primary me-3"
                                    data-kt-stepper-action="previous">
                                 <i class="fa fa-arrow-left fs-4 me-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                                 Previous
                                 </button> --}}
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-lg btn-primary"
                                            data-kt-stepper-action="submit">
                                            <span class="indicator-label">
                                                Submit
                                                <i class="fa fa-arrow-right fs-4 ms-2"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                        {{-- <button style="display: block" type="button" class="btn btn-lg btn-primary"
                                    id="continue_btn_form" data-kt-stepper-action="next">
                                 Continue
                                 <i class="fa fa-arrow-right fs-4 ms-1"></i> --}}
                                        <button type="button" type="button" class="btn btn-lg btn-primary"
                                            id="nextButton">Continue</button>
                                        </button>
                                    </div>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Multi-steps-->
    </div>
    <!--end::Root-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    {{-- <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script> --}}

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script> --}}
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places" async defer></script> --}}
    <script>
        const sendEmailVerificationCodeUrl = "{{ route('send-email-verification-code') }}";
        const validateEmailCodeUrl = "{{ route('verify-email-verification-code') }}";
        const sendPhoneVerificationCodeUrl = "{{ route('send-phone-verification-code') }}";
        const validatePhoneCodeUrl = "{{ route('verify-phone-verification-code') }}";
    </script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCUfgBqvqVaM0Vpn0n1nlH7MrytaUW5WDE&loading=async&libraries=places&callback=initMap">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{ asset('assets/js/new2-js.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/new-js.js') }}"></script> --}}
    <script src="{{ asset('assets/js/step.js') }}"></script>
    <script src="{{ asset('assets/js/terms-condition.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
