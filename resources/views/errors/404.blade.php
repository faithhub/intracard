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
    <title>Intracard |404 Page</title>
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

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="app-blank">

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Signup Free Trial-->
        <div class="d-flex flex-column flex-xl-row flex-column-fluid">
            <div class="container">
                <div class="center-div">
                    <!--begin::Content-->
                    <div class="flex-row-fluid flex-center justfiy-content-xl-first p-10">
                        <!--begin::Wrapper-->
                        <div class="">
                            <!--begin::Form-->

                            <div class="text-center mb-0">
                                <!--begin::Logo-->
                                <a href="{{ url('/') }}" class="mb-0">
                                    <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}"
                                        class="h-100px" />
                                </a>
                            </div>
                            <div class="text-center mb-5">
                                <h1>404 - Page Not Found</h1>
                                <p class="text-black mb-10">The page you are looking for does not exist.</p>
                                <!--begin::Submit button-->
                                <a href="{{ url('/') }}" id="" class="btn btn-lg btn-primary w-100 mb-5">
                                    <span class="indicator-label">
                                        Return to Home Page
                                    </span>
                                </a>
                                <!--end::Submit button-->


                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                </div>
            </div>
            <!--end::Right Content-->
        </div>
        <!--end::Authentication - Signup Free Trial-->
    </div>
    <!--end::Root-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->


    <!--begin::Custom Javascript(used for this page only)-->
    {{-- <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script> --}}
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
