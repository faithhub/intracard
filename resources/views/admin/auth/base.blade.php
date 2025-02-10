<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: GoodProduct Version: 1.1.4
Purchase: https://themes.getbootstrap.com/product/good-bootstrap-5-admin-dashboard-template
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <title>Auth Page</title>
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
    <!--end::Global Stylesheets Bundle-->
    <style>
        /* change transition duration to control the speed of fade effect */
        .carousel-item {
            transition: transform 2.6s ease-in-out;
        }

        .carousel-fade .active.carousel-item-start,
        .carousel-fade .active.carousel-item-end {
            transition: opacity 0s 2.6s;
        }

        .long-button {
            display: inline-block;
            padding: 12px 25px;
            /* Top and bottom padding, left and right padding */
            font-size: 18px;
            /* Font size */
            color: white;
            /* Text color */
            background-color: #007bff;
            /* Bootstrap primary color */
            border: none;
            /* Remove border */
            border-radius: 9px;
            /* Rounded corners */
            text-align: center;
            /* Center text */
            text-decoration: none;
            /* Remove underline */
            width: 100%;
            /* Full width */
            max-width: 300px;
            /* Max width of the button */
            cursor: pointer;
            /* Pointer cursor on hover */
            transition: background-color 0.3s ease;
            /* Smooth background color transition */
        }

        .long-button:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
        }
    </style>
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="app-blank">
    <!--begin::Theme mode setup on page load-->
    <!--end::Theme mode setup on page load-->

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Signup Welcome Message -->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Content-->

            <div class="container">
                <div class="center-div">
                    <div class="d-flex flex-row-fluid flex-column flex-column-fluid text-center">
                        <!--begin::Logo-->
                        <a href="" class="pt-lg-10 mb-12">
                            <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}" class="h-100px" />
                        </a>
                        <!--end::Logo-->

                        <!--begin::Carousel-->
                        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-bs-interval="7000">

                                    <!--begin::Logo-->
                                    <h4 class="fw-bold fs-1qx text-gray-800 mb-4">Turn rent into free trips</h4>
                                    <!--end::Logo-->

                                    <!--begin::Message-->
                                    <div class="fw-semibold fs-6 text-muted">Earn enough points for three flights
                                        anywhere in North America
                                    </div>
                                    <img alt="Logo"
                                        src="{{ asset('assets/media/illustrations/Onboarding_asset_3_free_flights_a26da0df5f.png') }}"
                                        class="mw-100 mb-10 h-lg-200px" />
                                </div>

                                <div class="carousel-item" data-bs-interval="7000">
                                    <!--begin::Logo-->
                                    <h1 class="fw-bold fs-1qx text-gray-800 mb-4">Earn up to 4% back on rent</h1>
                                    <!--end::Logo-->

                                    <!--begin::Message-->
                                    <div class="fw-semibold fs-6 text-muted">Get a half a month of rent back every
                                        year
                                    </div>
                                    <img alt="Logo"
                                        src="{{ asset('assets/media/illustrations/Onboarding_asset_4_back_on_rent_7be37cd434.png') }}"
                                        class="mw-100 mb-10 h-lg-200px" />
                                </div>

                                <div class="carousel-item" data-bs-interval="7000">
                                    <!--begin::Logo-->
                                    <h1 class="fw-bold fs-1qx text-gray-800 mb-4">Build credit when you pay on time</h1>
                                    <!--end::Logo-->

                                    <!--begin::Message-->
                                    <div class="fw-semibold fs-6 text-muted">Opt-in to reporting your rent
                                        payments to Equifax to get ahead financially
                                    </div>
                                    <img alt="Logo"
                                        src="{{ asset('assets/media/illustrations/Onboarding_asset_build_credit_751e24b166.png') }}"
                                        class="mw-100 mb-10 h-lg-200px" />
                                </div>
                            </div>
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                            </div>
                            {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button> --}}
                        </div>

                        <!--end::Message-->

                        <!--begin::Action-->
                        <div class="text-center m-1">
                            <a href="{{ route('register') }}" target="blank" class="long-button btn btn-dark">Sign
                                Up</a>
                        </div>
                        <div class="text-center m-1">
                            <a href="{{ route('login') }}" target="blank"
                                class="long-button btn btn-secondary fw-bold">Sign In</a>
                        </div>
                        <!--end::Action-->

                    </div>
                </div>
            </div>

            <!--begin::Illustration-->
            <!--end::Illustration-->
        </div>
        <!--end::Authentication - Signup Welcome Message-->
    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script>
        var hostUrl = "";
    </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->


    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
