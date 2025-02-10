<?php
// Load .env variables (requires a package like vlucas/phpdotenv)
$mapboxToken = getenv('MAPBOX_ACCESS_TOKEN');
?>
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>{{ $pageTitle ?? 'Intracard | Sign In' }}</title>
    <!-- Required Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Meta Tags -->
    <title>Intracard | Secure Rent & Mortgage Payments</title>
    <meta name="description"
        content="Intracard is the secure and reliable platform for paying rent and mortgages effortlessly. Experience convenience with cutting-edge encryption for peace of mind.">
    <meta name="keywords"
        content="Intracard, rent payment, mortgage payment, secure payments, financial platform, encryption, payment solutions">
    <meta name="author" content="Intracard Team">

    <!-- Open Graph Meta Tags (for social media sharing) -->
    <meta property="og:title" content="Intracard | Secure Rent & Mortgage Payments">
    <meta property="og:description"
        content="Simplify your rent and mortgage payments with Intracard's secure and user-friendly platform.">
    <meta property="og:image" content="https://yourwebsite.com/assets/images/og-image.png">
    <!-- Replace with your image URL -->
    <meta property="og:url" content="https://yourwebsite.com">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Intracard | Secure Rent & Mortgage Payments">
    <meta name="twitter:description"
        content="Make rent and mortgage payments hassle-free with Intracard's secure platform.">
    <meta name="twitter:image" content="https://yourwebsite.com/assets/images/twitter-card-image.png">
    <!-- Replace with your image URL -->

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <!-- Replace with your favicon URL -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <!-- Replace with your favicon URL -->
    <link rel="manifest" href="https://yourwebsite.com/manifest.json">

    <!-- Robots -->
    <meta name="robots" content="index, follow">

    <!-- Additional Meta Tags -->
    <link rel="canonical" href="https://yourwebsite.com">


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="/good/assets/media/logos/favicon.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
    {{-- 
      <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
      --}}
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


        .error-message {
            font-family: 'Kodchasan';
            letter-spacing: 1px;
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

        .intracard-logo-mobile {
            display: none;
            margin: 0 auto;
            /* Centers the image horizontally within its container */
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

            /* Hide intracard text in the sidebar */
            .d-flex.flex-column.flex-lg-row-auto h1 {
                display: none !important;
            }

            .intracard-logo-desktop {
                display: none !important;
            }

            .intracard-logo-mobile {
                display: block !important;
                margin: 0 auto;
                /* Centers the image horizontally within its container */
            }

            .text-center-logo {
                display: flex;
                justify-content: center;
                /* Centers the content horizontally */
                align-items: center;
                /* Centers content vertically, if needed */
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
            /* margin-left: 5px; */
        }

        .form-select.form-select-solid {
            min-height: calc(1.9em + 1.65rem + 2px);
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

        .wizardForm {
            display: none;
        }

        .current {
            display: block;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            color: #fff;
            z-index: 1000;
        }

        .loader {
            text-align: center;
        }

        .progress-bar-verify {
            width: 80%;
            background-color: #444;
            height: 10px;
            margin: 20px auto;
            border-radius: 5px;
        }

        .progress-verify {
            height: 100%;
            width: 0%;
            background-color: #4caf50;
            border-radius: 5px;
            transition: width 5s ease;
        }

        .success-mark {
            display: none;
            font-size: 3rem;
            color: #4caf50;
            text-align: center;
            margin-top: 20px;
        }

        .success-message {
            font-size: 2.0rem;
            font-weight: 700;
            font-family: "Kodchasan";
            margin-top: 10px;
            color: #4caf50;
        }

        .summary-section {
            background-color: #F8F6F2;
            /* padding: 30px; */
            border-radius: 8px;
        }

        .section-header {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .summary-content {
            background: #ffffff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .icon-header {
            font-size: 1.5rem;
            color: #4A90E2;
            margin-right: 10px;
        }

        .summary-section .stepper-title {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }

        .summary-section .stepper-desc {
            font-size: 0.9rem;
            color: #666;
        }

        .icon-header {
            font-size: 1.5rem;
            color: #4A90E2;
            margin-right: 10px;
            line-height: 1;
            /* Ensures the icon aligns vertically */
        }

        .section-header {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 0;
            display: inline-block;
            /* Ensures the text aligns next to the icon */
        }

        .overlay-form {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #fff;
            font-size: 1.5rem;
            z-index: 1000;
        }

        #overlayTextForm {
            text-align: center;
            margin-bottom: 20px;
        }

        .progress-bar-form {
            width: 80%;
            height: 10px;
            background-color: #444;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-form {
            height: 100%;
            width: 0%;
            background-color: #4caf50;
            transition: width 5s linear;
            /* 5 seconds for each step */
        }

        .progress-bar-container {
            width: 100%;
            background-color: #e0e0e0;
            /* Background of the container */
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .progress-bar {
            width: 0;
            height: 20px;
            background-color: #4caf50;
            /* Color of the progress bar */
            text-align: center;
            color: white;
            border-radius: 10px 0 0 10px;
            transition: width 0.3s ease;
            /* Smooth transition for the progress bar */
        }

        #progressPercentage {
            font-size: 14px;
            line-height: 20px;
            /* Center the percentage text vertically */
        }

        .suggestions {
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
        }

        .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="app-blank">
    @include('includes.toast-error')
    <div id="overlayForm" class="overlay-form">
        <p id="overlayTextForm">Encrypting your data...</p>
        <div class="progress-bar-form">
            <div class="progress-form"></div>
        </div>
    </div>

    <div id="overlay" class="overlay">
        <div class="loader">
            <p>Verifying your personal information</p>
            <div class="progress-bar-verify">
                <div class="progress-verify"></div>
            </div>
        </div>
        <div class="success-mark">
            &#10004; <!-- Checkmark symbol -->
            <p class="success-message">Account Verified</p>
        </div>
    </div>


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
                        {{-- <h1 class="text-black">intracard</h1> --}}
                        <!--begin::Logo-->
                        <a href="">
                            {{-- intracard --}}
                            <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}"
                                class="h-100px intracard-logo-desktop" />
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
                                            Verify personal information
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
                        <div class="w-lg-800px w-xl-800px p-10 p-lg-15 mx-auto pt-15">
                            <div class="text-center-logo">
                                <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}"
                                    class="h-60px intracard-logo-mobile" />
                            </div>
                            <div class="d-flex flex-stack">
                                <div class="w-sm-650px w-lg-650px w-xl-700px pb-10 pr-10 pl-10 p-lg-5 mb-5 mx-auto"
                                    style="padding-top: 0px !important">
                                    <div class="progress-bar-container" style="display: none">
                                        <div id="progressBar" class="progress-bar">
                                            <span id="progressPercentage">0%</span> <!-- Percentage display -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form">
                                <div class="wizardForm current" id="personalInformation">
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
                                                    <label
                                                        class="form-label fw-bold text-gray-900 fw-bold required">First
                                                        Name</label>
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
                                                    <label
                                                        class="form-label fw-bold text-gray-900 fw-bold required fs-6">Last
                                                        Name</label>
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
                                            <label class="form-label fw-bold text-gray-900 fw-bold">Middle Name (<i
                                                    class="">optional</i>)</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input name="middle_name" type="text"
                                                class="form-control form-control-lg form-control-solid"
                                                value="" />
                                            <!--end::Input-->
                                        </div>
                                        <div class="mb-7 fv-row" data-kt-password-meter="true">
                                            <!--begin::Wrapper-->
                                            <div class="mb-1">
                                                <!--begin::Label-->
                                                <label
                                                    class="form-label fw-bold text-gray-900 fw-bold fs-6 required">Password</label>
                                                <!--end::Label-->

                                                <!--begin::Input wrapper-->
                                                <div class="position-relative mb-3">
                                                    <input
                                                        class="form-control form-control-lg form-control-solid pe-10"
                                                        required type="password" placeholder="" name="password"
                                                        id="password" autocomplete="off" />
                                                    <span class="error-message text-danger"
                                                        id="password-error"></span>

                                                    <!-- Eye Icon for Show/Hide Password -->
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y"
                                                        onclick="togglePasswordVisibility('password', this)">
                                                        <i class="far fa-eye"></i>
                                                    </button>

                                                    <!-- Error span for Password -->
                                                    @if ($errors->has('password'))
                                                        <div class="error-message text-danger">
                                                            {{ $errors->first('password') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <!--end::Input wrapper-->

                                                <!--begin::Meter-->
                                                <div class="d-flex align-items-center mb-1"
                                                    data-kt-password-meter-control="highlight">
                                                    <div
                                                        class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                                    </div>
                                                    <div
                                                        class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                                    </div>
                                                    <div
                                                        class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                                    </div>
                                                    <div
                                                        class="flex-grow-1 bg-secondary bg-active-success rounded h-5px">
                                                    </div>
                                                </div>
                                                <!--end::Meter-->
                                            </div>
                                            <!--end::Wrapper-->

                                            <!--begin::Hint-->
                                            <div class="text-muted">
                                                Use 8 or more characters with a mix of letters, numbers & symbols.
                                            </div>
                                            <!--end::Hint-->
                                            <span class="error-message" data-error-id="passwordError"></span>
                                        </div>

                                        <div class="fv-row mb-10">
                                            <label
                                                class="form-label fw-bold text-gray-900 fw-bold fs-6 required">Confirm
                                                Password</label>
                                            <div class="position-relative">
                                                <input class="form-control form-control-lg form-control-solid pe-10"
                                                    type="password" placeholder="" name="password_confirmation"
                                                    id="password_confirmation" autocomplete="off" required>

                                                <!-- Eye Icon for Show/Hide Confirm Password -->
                                                <button type="button"
                                                    class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y"
                                                    onclick="togglePasswordVisibility('password_confirmation', this)">
                                                    <i class="far fa-eye"></i>
                                                </button>

                                                <!-- Error span for Confirm Password -->
                                                @if ($errors->has('password_confirmation'))
                                                    <div class="error-message text-danger">
                                                        {{ $errors->first('password_confirmation') }}
                                                    </div>
                                                @endif
                                                <span class="error-message"
                                                    data-error-id="password_confirmationError"></span>
                                            </div>
                                        </div>

                                        <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="form-label fw-bold text-gray-900 fs-6 required">Email</label>
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
                                            <label class="form-label fw-bold text-gray-900 fs-6 required">Phone
                                                Number</label>

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
                                                    placeholder="" maxlength="10" required
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
                                                        name="email_and_phone_terms" value="email_and_phone_terms"
                                                        required>
                                                    <span class="form-check-label fw-semibold text-gray-700 fs-6">
                                                        For the above presented mean of contact email, phone and address
                                                    </span>
                                                </label>
                                            </div>
                                            <span class="error-message"
                                                data-error-id="email_and_phone_termsError"></span>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>

                                <div id="Rent_mortgage_div"
                                    class="Rent_mortgage_divs wizardForm substep Rent_mortgage_div">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-15">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">What are your primary goals with
                                            Intracard?
                                        </h2>
                                        <div class="text-muted fw-semibold fs-6">
                                            Help us tailor your experience.
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Input group-->
                                    <div class="fv-row pb-10">
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
                                                        <span class="text-gray-900 fw-bold d-block fs-4 mb-2">
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
                                                        <span class="text-muted fw-semibold fs-6">Pay my mortgage with
                                                            a credit or debit card.</span>
                                                    </span>
                                                    <!--end::Info-->
                                                </label>
                                                <!--end::Option-->
                                            </div>
                                            <span class="error-message" data-error-id="rent_account_typeError"></span>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                </div>

                                <div id="RentStep1" class="Rent_mortgage_divs wizardForm substep RentDivTagStep77">
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Do you want to pay rent or pay
                                            rent and build credit?
                                        </h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="mb-10">
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
                                                    <span class="fw-bold text-gray-800 text-hover-primary fs-5">Pay
                                                        Rent</span>
                                                    <span class="fs-6 fw-semibold text-muted">I just
                                                        want
                                                        to
                                                        pay my rent with a credit or debit card.</span>
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
                                                    <span class="fw-bold text-gray-800 text-hover-primary fs-5">Pay
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
                                        <span class="error-message" data-error-id="account_plan_rentError"></span>
                                    </div>
                                </div>

                                <div id="RentStep2" class="Rent_mortgage_divs wizardForm substep RentDivTagStep">
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
                                                <span class="fw-bold text-gray-800 text-hover-primary fs-5">Sole
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
                                                name="rent_account_plan_type" value="sole_applicant" required>
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
                                                name="rent_account_plan_type" value="co_applicant" required>
                                        </span>
                                        <!--end:Input-->
                                    </label>
                                    <span class="error-message" data-error-id="rent_account_plan_typeError"></span>
                                    <div class="pb-10"></div>
                                </div>

                                <div id="rentStep5" class="Rent_mortgage_divs wizardForm substep RentDivTagStep">
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Add Co-Applicant</h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="form-group rounded border p-5 mb-4">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold text-gray-900 fw-bold required">Primary
                                                applicant rent
                                                amount:</label>
                                            <input type="text" name="coApplicanRentPrimaryAmount"
                                                id="coApplicanRentPrimaryAmount" class="form-control mb-2 mb-md-0"
                                                placeholder="e.g 1,000" required onblur="validateNumericInput(this)">
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
                                        <div class="rounded border mb-2 p-4 addNewApplicant" id="addNewApplicant111">
                                            <!--begin::Repeater-->
                                            <div id="kt_docs_repeater_basic">
                                                <div class="form-group row mb-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold text-gray-900 fw-bold">First
                                                            Name:</label>
                                                        <input type="text" name="coApplicantfirstName[]"
                                                            class="form-control mb-2 mb-md-0"
                                                            placeholder="Enter first name" required>
                                                        <span class="error-message"
                                                            data-error-id="coApplicantfirstNameError"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold text-gray-900 fw-bold">Last
                                                            Name:</label>
                                                        <input type="text" name="coApplicantlastName[]"
                                                            class="form-control mb-2 mb-md-0"
                                                            placeholder="Enter last name" required>
                                                        <span class="error-message"
                                                            data-error-id="coApplicantlastNameError"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold text-gray-900">Email:</label>
                                                        <input type="email" name="coApplicantemail[]"
                                                            class="form-control mb-2 mb-md-0"
                                                            placeholder="Enter email address" required>
                                                        <span class="error-message"
                                                            data-error-id="coApplicantemailError"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold text-gray-900">Rent
                                                            Amount:</label>
                                                        <input type="text" onblur="validateNumericInput(this)"
                                                            name="coApplicantamount[]"
                                                            class="form-control mb-2 mb-md-0" placeholder="1,000.00"
                                                            required>
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

                                <div id="RentStep3" class="Rent_mortgage_divs wizardForm substep RentDivTagStep">
                                    <div class="pb-10 pb-lg-5">
                                        <h2 class="fw-bold text-gray-900">What is your Card limit
                                            and the due date?
                                        </h2>
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold text-gray-900 required">Credit Card
                                            limit</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="credit_card_limit" type="text"
                                            onblur="validateNumericInput(this)"
                                            class="form-control form-control-lg form-control-solid" value=""
                                            placeholder="eg 1500.00" required minlength="3" maxlength="9">
                                        <span class="error-message" data-error-id="credit_card_limitError"></span>
                                        <!--end::Input-->
                                    </div>
                                    <div class="fv-row mb-10">
                                        <label class="form-label fw-bold text-gray-900 required">Credit Card due
                                            date</label>
                                        <div class="row mb-1">
                                            <div class="col-md-12">
                                                <div class="mb-10 fv-row">
                                                    <label
                                                        class="form-label fw-bold text-gray-900 mb-3 required">Monthly</label>
                                                    <select class="form-control form-control-lg form-control-solid"
                                                        name="rent_monthly_CC_day" required>
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
                                                        data-error-id="rent_monthly_CC_dayError"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="RentStep4" class="Rent_mortgage_divs wizardForm substep RentDivTagStep">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Setting Up Rent Payments</h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="mb-10">
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
                                                        my current rent payments to Intracard. </span>
                                                </span>
                                                <!--end:Description-->
                                            </span>
                                            <!--end:Label-->
                                            <!--begin:Input-->
                                            <span class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio"
                                                    name="rent_account_plan_type_mode"
                                                    value="Continue_paying_existing_rent"
                                                    data-gtm-form-interact-field-id="0" required>
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
                                                    <span class="fw-bold text-gray-800 text-hover-primary fs-5">Setup
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
                                                <input class="form-check-input" type="radio" required
                                                    name="rent_account_plan_type_mode"
                                                    value="Setup_payment_new_rental">
                                            </span>
                                            <!--end:Input-->
                                        </label>
                                        <span class="error-message"
                                            data-error-id="rent_account_plan_type_modeError"></span>
                                        <!--end::Option-->
                                    </div>
                                </div>

                                <div id="MortgageStep1" class="Rent_mortgage_divs wizardForm substep">
                                    <div class="pb-10 pb-lg-15">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Do you want to pay for mortgage,
                                            or pay and build credit?
                                        </h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="mb-10">
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
                                                    <span class="fw-bold text-gray-800 text-hover-primary fs-5">Pay
                                                        mortgage</span>
                                                    <span class="fs-6 fw-semibold text-muted">I just want to pay my
                                                        mortgage with a credit or debit card.</span>
                                                </span>
                                                <!--end:Description-->
                                            </span>
                                            <!--end:Label-->
                                            <!--begin:Input-->
                                            <span class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio"
                                                    name="account_plan_mortgage" value="pay_mortgage" required>
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
                                                    <span class="fw-bold text-gray-800 text-hover-primary fs-5">Pay
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
                                                    name="account_plan_mortgage" value="pay_mortgage_build">
                                            </span>
                                            <!--end:Input-->
                                        </label>
                                        <!--end::Option-->
                                        <span class="error-message" data-error-id="account_plan_mortgageError"></span>
                                    </div>
                                </div>

                                <div id="MortgageStep2"
                                    class="Rent_mortgage_divs wizardForm substep MortgageDivTagStep">
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
                                                name="mortgage_account_plan_type" value="owner" required>
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
                                                name="mortgage_account_plan_type" value="co_owner" required>
                                        </span>
                                        <!--end:Input-->
                                    </label>
                                    <span class="error-message"
                                        data-error-id="mortgage_account_plan_typeError"></span>
                                </div>

                                <div id="MortgageStep3"
                                    class="Rent_mortgage_divs wizardForm substep MortgageDivTagStep">
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">What is your Card limit
                                            and the due date?
                                        </h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold text-gray-900 required">Credit Card
                                            limit</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input name="mortgage_credit_card_limit" type="text"
                                            onblur="validateNumericInput(this)"
                                            class="form-control form-control-lg form-control-solid" value=""
                                            placeholder="eg 1500.00" required minlength="3" maxlength="9">
                                        <span class="error-message"
                                            data-error-id="mortgage_credit_card_limitError"></span>
                                        <!--end::Input-->
                                    </div>
                                    <div class="fv-row mb-10">
                                        <label class="form-label fw-bold text-gray-900 required">Credit Card due
                                            date</label>
                                        <div class="row mb-1">
                                            <div class="col-md-12">
                                                <div class="mb-10 fv-row">
                                                    <label
                                                        class="form-label fw-bold text-gray-900 mb-3 required">Monthly</label>
                                                    <select class="form-control form-control-lg form-control-solid"
                                                        name="mortgage_credit_card_limit_monthly_day" required>
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
                                                        data-error-id="mortgage_credit_card_limit_monthly_dayError"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="MortgageStep5"
                                    class="Rent_mortgage_divs wizardForm substep MortgageDivTagStep">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Setting Up Mortgage Payments</h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="mb-10">
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
                                                        my current mortgage payments to Intracard. </span>
                                                </span>
                                                <!--end:Description-->
                                            </span>
                                            <!--end:Label-->
                                            <!--begin:Input-->
                                            <span class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio"
                                                    name="mortgage_account_plan_type_mode"
                                                    value="Continue_paying_existing_mortgage" required
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
                                                    <span class="fw-bold text-gray-800 text-hover-primary fs-5">Setup
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
                                                    name="mortgage_account_plan_type_mode"
                                                    value="Setup_payment_new_mortgage" required>
                                            </span>
                                            <!--end:Input-->
                                        </label>
                                        <!--end::Option-->
                                        <span class="error-message"
                                            data-error-id="mortgage_account_plan_type_modeError"></span>
                                    </div>
                                </div>

                                <div id="MortgageStep4"
                                    class="Rent_mortgage_divs wizardForm substep MortgageDivTagStep">
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900">Add Co-owner</h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="form-group rounded border p-5 mb-4">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold text-gray-900 required">Owner primary
                                                mortgage
                                                amount:</label>
                                            <input type="text" id="coApplicanMortgagePrimaryAmount"
                                                onblur="validateNumericInput(this)" required
                                                name="coApplicanMortgagePrimaryAmount"
                                                class="form-control mb-2 mb-md-0" placeholder="e.g 1,000">
                                            <span class="error-message"
                                                data-error-id="coApplicanMortgagePrimaryAmountError"></span>
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
                                                        <label class="form-label fw-bold text-gray-900">First
                                                            Name:</label>
                                                        <input type="text" name="mortgagecoOwnerfirstName[]"
                                                            class="form-control mb-2 mb-md-0"
                                                            placeholder="Enter first name" required>
                                                        <span class="error-message"
                                                            data-error-id="mortgagecoOwnerfirstName"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold text-gray-900">Last
                                                            Name:</label>
                                                        <input type="text" name="mortgagecoOwnerlastName[]"
                                                            class="form-control mb-2 mb-md-0"
                                                            placeholder="Enter last name" required>
                                                        <span class="error-message"
                                                            data-error-id="mortgagecoOwnerlastName"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold text-gray-900">Email:</label>
                                                        <input type="email" name="mortgagecoOwneremail[]"
                                                            class="form-control mb-2 mb-md-0"
                                                            placeholder="Enter email address" required>
                                                        <span class="error-message"
                                                            data-error-id="mortgagecoOwneremail"></span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold text-gray-900">Mortgage
                                                            Amnount:</label>
                                                        <input type="text" id=""
                                                            name="mortgagecoOwneramount[]"
                                                            onblur="validateNumericInput(this)"
                                                            class="form-control mb-2 mb-md-0" placeholder="1,000.00"
                                                            required>
                                                        <span class="error-message"
                                                            data-error-id="mortgagecoOwneramount"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Repeater-->
                                        </div>
                                    </div>
                                </div>

                                <div id="address_details_div" class="addressDetails1_divs wizardForm">
                                    <!--begin::Heading-->
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900" id="RentalMortAddress">What's
                                            your rental address?</h2>
                                        <!--end::Title-->
                                        <!--begin::Notice-->
                                        <div class="text-muted fw-semibold fs-6" id="RentalMortAddressMuted">
                                            Enter your rental address and unit number if applicable..
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <div class="row mb-1">
                                        <div class="mb-10 fv-row">
                                            <label
                                                class="form-label fw-bold text-gray-900 mb-3 required">Address</label>
                                            <input type="text" id="address-input" required
                                                class="form-control form-control-lg form-control-solid" name="address"
                                                placeholder="Enter your address" />
                                            <span class="error-message" data-error-id="addressError"></span>
                                            <div id="suggestions" class="suggestions"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-6 fv-row">
                                            <div class="mb-10 fv-row">
                                                <label
                                                    class="form-label fw-bold text-gray-900 mb-3 required">Province</label>
                                                <select id="province" required class="form-select form-select-solid"
                                                    name="province" onchange="updateCities()">
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
                                                <span class="error-message" data-error-id="provinceError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <div class="mb-10 fv-row">
                                                <label
                                                    class="form-label fw-bold text-gray-900 mb-3 required">City</label>
                                                <select id="city" required class="form-select form-select-solid">
                                                    <option value="">--Select a City--</option>
                                                </select>
                                                <span class="error-message" data-error-id="cityError"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <div class="mb-10 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3 required">Postal
                                                    Code</label>
                                                <input type=""
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="postal_code" id="postal-code" placeholder=""
                                                    value="" required>
                                                <span class="error-message" data-error-id="postal_codeError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3">Unit
                                                    Number</label>
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="unit_number" placeholder="" value="" />
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
                                                <label class="form-label fw-bold text-gray-900 mb-3">House
                                                    Number</label>
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
                                                <label class="form-label fw-bold text-gray-900 mb-3">Street
                                                    Name</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type=""
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="street_name" placeholder="" value="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                    </div>
                                    <!--end::Heading-->
                                </div>

                                <div id="addressDetails1" class="addressDetails1_divs wizardForm">
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
                                        <label class="form-label fw-bold text-gray-900 mb-3 required">Monthly
                                            Amount</label>
                                        <input type="number" class="form-control form-control-lg form-control-solid"
                                            name="how_much_is_rent" id="amount_admin_pay" min="100"
                                            max="20000" placeholder="eg 2500.00" value="" required>
                                        <div class="text-muted">
                                            The minimum amount is {{ number_format(100, 2) }} CAD while the
                                            maximum is {{ number_format(50000, 2) }} CAD.
                                        </div>
                                        <!--end::Input-->
                                        <span class="error-message" data-error-id="how_much_is_rentError"></span>
                                    </div>
                                </div>

                                <div id="addressDetails2" class="addressDetails1_divs wizardForm">
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900" id="uploadAgreement">Upload
                                            tenancy agreement</h2>
                                        <!--end::Title-->
                                    </div>
                                    <div class="mb-10 fv-row">
                                        <label class="form-label fw-bold text-gray-900 mb-3 required">Upload
                                            File</label>
                                        <input type="file" accept=".pdf, image/*"
                                            class="form-control form-control-lg form-control-solid"
                                            name="tenancy_agreement" value="" required>
                                        <span class="error-message" data-error-id="tenancy_agreementError"></span>
                                    </div>
                                </div>

                                <div id="addressDetails3" class="addressDetails1_divs wizardForm">
                                    <div class="pb-10 pb-lg-5">
                                        <h2 class="fw-bold text-gray-900">Re-occuring monthly?</h2>
                                    </div>
                                    <div class="mb-10 fv-row">
                                        <label class="form-label fw-bold text-gray-900 mb-3 required">Day</label>
                                        <select class="form-control form-control-lg form-control-solid"
                                            name="re_occuring_monthly_day" required>
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

                                <div id="addressDetails4" class="addressDetails1_divs wizardForm">
                                    <div class="pb-10 pb-lg-5">
                                        <h2 class="fw-bold text-gray-900">What's the duration?</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-12 fv-row">
                                                <label
                                                    class="form-label fw-bold text-gray-900 mb-3 required">From</label>
                                                <input type="date"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="duration_from" value="" required>
                                                <span class="error-message"
                                                    data-error-id="duration_fromError"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-12 fv-row">
                                                <label
                                                    class="form-label fw-bold text-gray-900 mb-3 required">To</label>
                                                <input type="date"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="duration_to" value="" required>
                                                <span class="error-message" data-error-id="duration_toError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="landlordDetails1" class="landlordDetails1_divs wizardForm">
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
                                    <div class="mb-10" id="rentFinance">
                                        <!--begin:Option-->
                                        <label class="d-flex flex-stack mb-5 cursor-pointer">
                                            <!--begin:Label-->
                                            <span class="d-flex align-items-center me-2">
                                                <!--begin::Icon-->
                                                <span class="symbol symbol-50px me-6">
                                                    <span class="symbol-label">
                                                        <i class="fa fa-money-check fs-1 text-gray-600"></i>
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
                                            <label for="email"
                                                class="form-label fw-bold text-gray-900 fw-bold required fs-6">Enter
                                                your landlord's email:</label>
                                            <input type="" id="interacEmailInput" name=""
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="example@example.com">
                                        </div>
                                        <!--end::Option-->
                                        <!--begin:Option-->
                                        <label class="d-flex flex-stack mb-5 cursor-pointer">
                                            <!--begin:Label-->
                                            <span class="d-flex align-items-center me-2">
                                                <!--begin::Icon-->
                                                <span class="symbol symbol-50px me-6">
                                                    <span class="symbol-label">
                                                        <i class="fa fa-money-check-dollar fs-1 text-gray-600"></i>
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
                                                <input class="form-check-input" type="radio" required
                                                    name="landlord_account_mode" value="cheque"
                                                    onclick="toggleEmailInput()">
                                            </span>
                                            <!--end:Input-->
                                        </label>
                                        <!--end::Option-->
                                        <span class="error-message"
                                            data-error-id="landlord_account_modeError"></span>
                                    </div>
                                </div>

                                <div id="landlordDetails6" class="landlordDetails1_divs wizardForm">
                                    <div class="pb-10 pb-lg-5">
                                        <!--begin::Title-->
                                        <h2 class="fw-bold text-gray-900" id="landlordDetails">Landlord
                                            Details</h2>
                                        <!--end::Title-->
                                        <!--begin::Notice-->
                                        <div class="text-muted fw-semibold fs-6" id="landLoradSmall">
                                            How does your mortgage financer accept payment?
                                        </div>
                                        <!--end::Notice-->
                                    </div>
                                    <div class="mb-10" id="mortgageFinance">
                                        <!--begin:Option-->
                                        <label class="d-flex flex-stack mb-5 cursor-pointer">
                                            <!--begin:Label-->
                                            <span class="d-flex align-items-center me-2">
                                                <!--begin::Icon-->
                                                <span class="symbol symbol-50px me-6">
                                                    <span class="symbol-label">
                                                        <i class="fa fa-money-check fs-1 text-gray-600"></i>
                                                    </span>
                                                </span>
                                                <!--end::Icon-->
                                                <!--begin::Description-->
                                                <span class="d-flex flex-column">
                                                    <span class="fw-bold text-gray-800 text-hover-primary fs-5">EFT
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
                                            <div class="mb-5 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3">Mortgage Account
                                                    Number:</label>
                                                <input type="text"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="mortgage_eft_account_number" placeholder=""
                                                    value="" data-required>
                                            </div>
                                            <fieldset>
                                                <legend>Lender Details</legend>
                                                <div class="mb-5 fv-row">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-label fw-bold text-gray-900 mb-3">Name:</label>
                                                            <input type="text"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="mortgage_eft_lender_name" placeholder=""
                                                                value="" data-required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-label fw-bold text-gray-900 mb-3">Address:</label>
                                                            <input type="text"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="mortgage_eft_lender_address" placeholder=""
                                                                value="" data-required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <legend>Bank Information</legend>
                                                <div class="mb-5 fv-row">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-label fw-bold text-gray-900 mb-3">Institution
                                                                Number:</label>
                                                            <input type="number"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="mortgage_eft_institution_number"
                                                                placeholder="" value="" data-required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-label fw-bold text-gray-900 mb-3">Transit
                                                                Number:</label>
                                                            <input type="number"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="mortgage_eft_transit_number" placeholder=""
                                                                value="" data-required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-5 fv-row">
                                                    <label class="form-label fw-bold text-gray-900 mb-3">Bank Account
                                                        Number:</label>
                                                    <input type="number"
                                                        class="form-control form-control-lg form-control-solid"
                                                        name="mortgage_eft_bank_account_number" placeholder=""
                                                        value="" data-required>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <legend>Payment Frequency</legend>
                                                <div class="mb-5 fv-row">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-label fw-bold text-gray-900 mb-3">Select
                                                                payment frequency:</label>
                                                            <select name="mortgage_eft_payment_frequency"
                                                                class="form-select form-select-solid"
                                                                id="paymentFrequencySelect">
                                                                <option value="Monthly">Monthly</option>
                                                                <option value="Bi-weekly">Bi-weekly</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6" id="biWeeklyDueDateDiv"
                                                            style="display: none;">
                                                            <label class="form-label fw-bold text-gray-900 mb-3">Next
                                                                due date:</label>
                                                            <input type="date"
                                                                class="form-control form-control-lg form-control-solid"
                                                                name="mortgage_eft_bi_weekly_due_date"
                                                                id="biWeeklyDueDateInput" placeholder=""
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="mb-10 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3">Reference Number
                                                    or Code:</label>
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="mortgage_eft_ref_number" placeholder="" value=""
                                                    data-required>
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
                                                        <i class="fa fa-money-check-dollar fs-1 text-gray-600"></i>
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
                                                    name="mortgage_financer_account_mode" value="mortgage_cheque"
                                                    onclick="toggleEFTInput()">
                                            </span>
                                            <!--end:Input-->
                                        </label>
                                        <div id="mortgageChequeInputDiv" style="display: none; margin: 2rem;">
                                            <div class="mb-10 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3">Account
                                                    Number:</label>
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="mortgage_cheque_account_number" placeholder=""
                                                    value="" data-required>
                                            </div>
                                            <div class="mb-10 fv-row">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-gray-900 mb-3">Transit
                                                            Number:</label>
                                                        <input type="number"
                                                            class="form-control form-control-lg form-control-solid"
                                                            name="mortgage_cheque_transit_number" placeholder=""
                                                            value="" data-required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label fw-bold text-gray-900 mb-3">Institution
                                                            Number:</label>
                                                        <input type="number"
                                                            class="form-control form-control-lg form-control-solid"
                                                            name="mortgage_cheque_institution_number" placeholder=""
                                                            value="" data-required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-10 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3">Name:</label>
                                                <input type="text"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="mortgage_cheque_name" placeholder="" value=""
                                                    data-required>
                                            </div>
                                            <div class="mb-10 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3">Address:</label>
                                                <input type="text"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="mortgage_cheque_address" placeholder="" value=""
                                                    data-required>
                                            </div>
                                            {{-- <div class="mb-10 fv-row">
                                                <label class="form-label fw-bold text-gray-900 mb-3">Bank routing or
                                                    ABA
                                                    number:</label>
                                                <input type="number"
                                                    class="form-control form-control-lg form-control-solid"
                                                    name="ach_bank_routing" placeholder="" value=""
                                                    data-required>
                                            </div>
                                            <div class="mb-10 fv-row">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label fw-bold text-gray-900 mb-3">Recipient's
                                                            name:</label>
                                                        <input type="text"
                                                            class="form-control form-control-lg form-control-solid"
                                                            name="ach_reci_name" placeholder="" value=""
                                                            data-required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-gray-900 mb-3">Account
                                                            number:</label>
                                                        <input type="number"
                                                            class="form-control form-control-lg form-control-solid"
                                                            name="ach_account_number" placeholder=""
                                                            value="" data-required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-10 fv-row">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-gray-900 mb-3">Account
                                                            type:</label>
                                                        <select name="ach_account_type"
                                                            class="form-select form-select-solid" data-required>
                                                            <option value="">---</option>
                                                            <option value="Checking">Checking</option>
                                                            <option value="Savings">Savings</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-gray-900 mb-3">If
                                                            they're an
                                                            individual or a business:</label>
                                                        <select name="ach_acc_buc_ind"
                                                            class="form-select form-select-solid" data-required>
                                                            <option value="">---</option>
                                                            <option value="individual">Individual</option>
                                                            <option value="business">Business</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <!--end::Option-->
                                        <span class="error-message"
                                            data-error-id="mortgage_financer_account_modeError"></span>
                                    </div>
                                </div>

                                <div id="landlordDetails3" class="landlordDetails1_divs wizardForm">
                                    <div class="pb-10 pb-lg-5">
                                        <h2 class="fw-bold text-gray-900">Is your landlord an individual
                                            or
                                            business?
                                        </h2>
                                        <div class="text-muted fw-semibold fs-6">
                                        </div>
                                    </div>
                                    <div class="mb-10">
                                        <!--begin:Option-->
                                        <label class="d-flex flex-stack mb-5 cursor-pointer">
                                            <!--begin:Label-->
                                            <span class="d-flex align-items-center me-2">
                                                <!--begin::Icon-->
                                                <span class="symbol symbol-50px me-6">
                                                    <span class="symbol-label">
                                                        <i class="fa fa-money-check fs-1 text-gray-600"></i>
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
                                                    name="account_plan_type_mode_lanlord" value="business" required
                                                    onclick="chequeOptionForLandLord()">
                                            </span>
                                            <!--end:Input-->
                                        </label>
                                        <!--end::Option-->
                                        <div id="landlordDetails4" style="display: none; margin: 2rem">
                                            <div class="fv-row mb-10">
                                                <label class="form-label fw-bold text-gray-900 required">Business
                                                    Name</label>
                                                <input name="business_name" type="text" data-required
                                                    class="form-control form-control-lg form-control-solid"
                                                    value="" />
                                            </div>
                                        </div>
                                        <!--begin:Option-->
                                        <label class="d-flex flex-stack mb-5 cursor-pointer">
                                            <!--begin:Label-->
                                            <span class="d-flex align-items-center me-2">
                                                <!--begin::Icon-->
                                                <span class="symbol symbol-50px me-6">
                                                    <span class="symbol-label">
                                                        <i class="fa fa-money-check-dollar fs-1 text-gray-600"></i>
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
                                                    name="account_plan_type_mode_lanlord" value="individual"
                                                    required onclick="chequeOptionForLandLord()">
                                            </span>
                                            <!--end:Input-->
                                        </label>

                                        <div id="landlordDetails5" style="display: none; margin: 2rem">
                                            <div class="fv-row mb-10">
                                                <label class="form-label fw-bold text-gray-900 required">First
                                                    Name</label>
                                                <input name="individual_first_name" type="text" data-required
                                                    class="form-control form-control-lg form-control-solid"
                                                    value="" />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="form-label fw-bold text-gray-900 required">Last
                                                    Name</label>
                                                <input name="individual_last_name" type="text" data-required
                                                    class="form-control form-control-lg form-control-solid"
                                                    value="" />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="form-label fw-bold text-gray-900">Middle Name</label>
                                                <input name="individual_middle_name" type="text"
                                                    class="form-control form-control-lg form-control-solid"
                                                    value="" />
                                            </div>
                                        </div>
                                        <!--end::Option-->
                                        <span class="error-message"
                                            data-error-id="account_plan_type_mode_lanlordError"></span>
                                    </div>
                                </div>

                                <div class="wizardForm" id="verificationDiv">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <!--begin::Heading-->
                                        <div class="pb-10 pb-lg-15">
                                            <!--begin::Title-->
                                            <h2 class="fw-bold text-gray-900">Verification with Plaid </h2>
                                            <!--end::Title-->
                                            <!--begin::Notice-->
                                            <div class="text-muted fw-semibold fs-6">
                                                Kindly scroll through the terms and conditions and click on the check
                                                box.
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
                                                    class="btn btn-sm mt-1 btn-lg btn-primary">Proceed with
                                                    verification</button>
                                            </div>

                                        </div>
                                        <!--end::Heading-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>

                                <div class="wizardForm" id="billingDetailsDiv">
                                    <!-- Billing Details Section -->
                                    <div class="w-100 mb-5">
                                        <h2 class="fw-bold text-gray-900">Billing Details</h2>
                                        <div class="text-muted fw-semibold fs-6">Kindly provide with us your billing
                                            details</div>
                                        <span id="amountMismatchError" style="color: red; display: none;">The total
                                            amount does not match.</span>
                                        <!-- Name on Card Input -->
                                        <div class="d-flex flex-column mb-7 fv-row mt-10">
                                            <label
                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2 fw-bold text-gray-900 fw-bold required">Amount
                                                to Deduct
                                            </label>
                                            <input type="number" maxlength="100000000000"
                                                class="form-control form-control-solid deduct_card_amount"
                                                placeholder="e.g 1000" name="defaulBillingCardAmount"
                                                id="defaulBillingCardAmount" readonly required>
                                            <span class="error-message"
                                                data-error-id="defaulBillingCardAmountError"></span>
                                        </div>

                                        <!-- Name on Card Input -->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <label
                                                class="d-flex align-items-center fs-6 fw-semibold form-label mb-2 fw-bold text-gray-900 fw-bold required">Name
                                                On Card
                                            </label>
                                            <input type="text"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Name on Card" name="card_name" maxlength="50"
                                                required>
                                            <span class="error-message" data-error-id="card_nameError"></span>
                                        </div>

                                        <!-- Card Number Input -->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <label
                                                class="fs-6 fw-semibold form-label mb-2 fw-bold text-gray-900 fw-bold required">Card
                                                Number</label>
                                            <!--begin::Input wrapper-->
                                            <div class="position-relative">
                                                <input type=""
                                                    class="form-control form-control-lg form-control-solid"
                                                    oninput="formatCreditCardInput(this)"
                                                    onblur="validateCreditCardNumber(this)"
                                                    placeholder="Enter card number" name="card_number"
                                                    maxlength="19" required>

                                                <!--begin::Card logos-->
                                                <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                                                    <img src="{{ asset('assets/cards/visa.webp') }}" alt=""
                                                        class="h-25px" />
                                                    <img src="{{ asset('assets/cards/mastercard.png') }}"
                                                        alt="" class="h-25px" />
                                                    {{-- <img src="/good/assets/media/svg/card-logos/american-express.svg" alt="" class="h-25px"/> --}}
                                                </div>
                                                <!--end::Card logos-->
                                            </div>
                                            <span class="error-message" data-error-id="card_numberError"></span>
                                        </div>

                                        <!-- Expiration Date and CVV -->
                                        <div class="d-flex flex-column mb-7 fv-row">
                                            <div class="row mb-10">
                                                <div class="col-md-8">
                                                    <label
                                                        class="required fs-6 fw-semibold form-label mb-2 fw-bold text-gray-900 fw-bold">Expiration
                                                        Date</label>
                                                    <div class="row">
                                                        <div class="col-md-6" style="margin-bottom: 1rem">
                                                            <select name="card_expiry_month"
                                                                class="form-select form-select-solid form-control-lg"
                                                                required>
                                                                <option value="">Month</option>
                                                                <!-- Months -->
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
                                                                <!-- Continue up to 12 -->
                                                            </select>
                                                            <span class="error-message"
                                                                data-error-id="card_expiry_monthError"></span>
                                                        </div>
                                                        <div class="col-md-6" style="margin-bottom: 1rem">
                                                            <select name="card_expiry_year"
                                                                class="form-select form-select-solid"
                                                                name="card_expiry_year" required>
                                                                <option value="">Year</option>
                                                                <!-- Years -->
                                                                <option value="2024">2024</option>
                                                                <option value="2025">2025</option>
                                                                <option value="2026">2026</option>
                                                                <option value="2027">2027</option>
                                                                <option value="2028">2028</option>
                                                                <option value="2029">2029</option>
                                                                <!-- Continue as needed -->
                                                            </select>
                                                            <span class="error-message"
                                                                data-error-id="card_expiry_yearError"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label
                                                        class="required fs-6 fw-semibold form-label mb-2  fw-bold text-gray-900 fw-bold">CVV</label>
                                                    <input type="password"
                                                        class="form-control form-control-lg form-control-solid"
                                                        required placeholder="CVV"
                                                        oninput="formatAndValidateFourDigitInput(this)"
                                                        name="card_cvv" minlength="3" maxlength="3">
                                                    <span class="error-message"
                                                        data-error-id="card_cvvError"></span>
                                                </div>
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
                                        <div id="additionalCardsContainer" style="display: block; margin-top:3rem">
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
                                <div class="wizardForm" id="summaryDiv">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <!--begin::Heading-->
                                        <div class="pb-1 pb-lg-5">
                                            <!--begin::Title-->
                                            <h2 class="fw-bold text-gray-900">Intracard Summary</h2>
                                            <!--end::Title-->
                                            <!--begin::Notice-->
                                            <div class="text-muted fw-semibold fs-6">
                                                Review your details below before submission.
                                            </div>
                                            <!--end::Notice-->
                                        </div>
                                        <!--end::Heading-->
                                        <div class="container my-5">
                                            <div class="summary-section">
                                                <!-- Personal Details Section -->
                                                <div class="summary-content mb-4">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-user icon-header"></i>
                                                        <span class="section-header">Personal Details</span>
                                                    </div>
                                                    <!-- Personal Details Summary -->
                                                    <p><strong>First Name:</strong> <span
                                                            id="summaryFirstName"></span></p>
                                                    <p><strong>Last Name:</strong> <span id="summaryLastName"></span>
                                                    </p>
                                                    <p><strong>Middle Name:</strong> <span
                                                            id="summaryMiddleName"></span></p>
                                                    <p><strong>Email:</strong> <span id="summaryEmail"></span></p>
                                                    <p><strong>Phone Number:</strong> <span
                                                            id="summaryPhoneNumber"></span></p>
                                                </div>

                                                <!-- Account Type Section -->
                                                <div class="summary-content mb-4">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-file-invoice icon-header"></i>
                                                        <span class="section-header">Account Type</span>
                                                    </div>
                                                    <p><strong>Primary Goal:</strong> <span
                                                            id="summaryPrimaryGoal">N/A</span></p>
                                                    <p><strong>Account Plan:</strong> <span
                                                            id="summaryAccountPlan">N/A</span></p>
                                                    <p><strong>Application Type:</strong> <span
                                                            id="summaryApplicationType">N/A</span></p>
                                                    <p><strong id="summaryPaymentSetupLabel">Rent Payment
                                                            Setup:</strong> <span
                                                            id="summaryRentPaymentSetup">N/A</span></p>
                                                    <p><strong>Card Limit:</strong> <span
                                                            id="summaryCardLimit"></span></p>
                                                    <p><strong>Card Due Date:</strong> <span
                                                            id="summaryCardDueDate"></span></p>
                                                    <div id="coApplicanRentSummaryDiv" class="mt-5">
                                                        <h5><strong id="summaryCoApplicantLabel">Co-Applicant
                                                                Details</strong></h5>
                                                        <p><strong id="summaryPrimaryAmountLabel">Primary Applicant
                                                                Rent Amount:</strong> <span
                                                                id="summaryPrimaryApplicantRent">N/A</span></p>
                                                        <div id="coApplicantsSummary"></div>
                                                    </div>
                                                </div>

                                                <!-- Address Section -->
                                                <div class="summary-content mb-4">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-address-book icon-header"></i>
                                                        <span class="section-header">Address</span>
                                                    </div>
                                                    <p><strong>Address:</strong> <span id="summaryAddress"></span></p>
                                                    <p><strong>Province:</strong> <span id="summaryProvince"></span>
                                                    </p>
                                                    <p><strong>City:</strong> <span id="summaryCity"></span></p>
                                                    <p><strong>Postal Code:</strong> <span
                                                            id="summaryPostalCode"></span></p>
                                                    <p><strong>Unit Number:</strong> <span
                                                            id="summaryUnitNumber"></span></p>
                                                    <p><strong>House Number:</strong> <span
                                                            id="summaryHouseNumber"></span></p>
                                                    <p><strong>Street Name:</strong> <span
                                                            id="summaryStreetName"></span></p>
                                                </div>

                                                <!-- Landlord Details Section -->
                                                {{-- <div class="summary-content mb-4">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-house icon-header"></i>
                                                        <span class="section-header">Landlord Details</span>
                                                    </div>
                                                    <p><strong>Landlord Name:</strong> Jane Smith</p>
                                                    <p><strong>Contact Number:</strong> +123 456 7891</p>
                                                </div> --}}

                                                <!-- Verification Section -->
                                                <div class="summary-content mb-4">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-lock icon-header"></i>
                                                        <span class="section-header">Verification</span>
                                                    </div>
                                                    <p><strong>Status:</strong> Verified</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Step 3-->
                                <!--begin::Actions-->
                                <div class="d-flex flex-stack pt-15">
                                    <div class="mr-2">
                                        <button type="button" type="button" class="btn btn-lg btn-primary"
                                            id="previousButton">Previous</button>
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
                                        <button type="button" type="button" class="btn btn-lg btn-primary"
                                            id="nextButton">Continue</button>
                                        </button>
                                    </div>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                            <!--begin::Link-->
                            <div class="text-center text-gray-500 fw-semibold fs-6 mt-1">
                                Already have an account?
                                <a href="{{ route('login') }}" class="link-dark fw-bolder">
                                    Login here
                                </a>
                            </div>
                            <!--end::Link-->
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
        const verifyAccount = "{{ route('plaid-verify-account') }}";
        const saveFormRoute = "{{ route('register-user') }}";
        const loginRoute = "{{ route('login') }}";
        const registerUserCities = "{{ route('register-user-getCities', 'province') }}";
        const checkEmailExists = "{{ route('checkEmailExists') }}";
    </script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBw04XUsOmGPqGBZOeJo8t3HuYHWo0i9sY&loading=async&libraries=places">
    </script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
    {{-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCUfgBqvqVaM0Vpn0n1nlH7MrytaUW5WDE&libraries=places"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/address.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/new2-js.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/new-js.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/step.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/terms-condition.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
