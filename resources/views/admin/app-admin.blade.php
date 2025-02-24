<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>IntraCard Admin Dashboard ||
        {{ isset($dashboard_title) && $dashboard_title ? $dashboard_title : ' My Account' }}
    </title>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link href="https://preview.keenthemes.com/good/assets/css/style.bundle.css"> --}}

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <!-- Replace with your favicon URL -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* General reset for responsive layout */
        body,
        html {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            overflow-x: hidden !important;
        }

        /* For screens smaller than 768px */
        @media only screen and (max-width: 768px) {
            .app-main {
                padding: 10px !important;
            }

            .container {
                width: 100% !important;
                padding: 15px !important;
            }

            .card {
                margin: 15px 0 !important;
                padding: 10px !important;
                font-size: 14px !important;
                width: 100% !important;
            }

            .setup-bill {
                width: 100% !important;
                margin-top: 15px !important;
            }

            .sidebar {
                display: none !important;
                /* Hide sidebar on smaller screens */
            }

            .top-nav {
                font-size: 14px !important;
            }

            .text-gray-900 {
                font-size: 12px !important;
            }
        }
        .menu-state-bg-light-primary .menu-item.hover:not(.here)>.menu-link:not(.disabled):not(.active):not(.here),
        .menu-state-bg-light-primary .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) {
            transition: color .2s ease;
            background-color: #cb9de4b0 !important;
            color: var(--bs-primary);
        }

        /* Optional: Add styling for a hamburger menu */
        @media only screen and (max-width: 768px) {
            .hamburger-menu {
                display: block !important;
            }

            .sidebar {
                display: none !important;
            }
        }

        .toast-success {
            background-color: #1b660a !important;
            /* Custom background color */
            color: #fff !important;
            /* Optional: Set text color to white for better contrast */
        }

        .toast-error {
            background-color: #d93025 !important;
            /* Custom error color (red) */
            color: #fff !important;
        }

        .toast-warning {
            background-color: #ffcc00 !important;
            /* Custom warning color (yellow) */
            color: #000 !important;
        }

        .toast-info {
            background-color: #0056b3 !important;
            /* Custom info color (blue) */
            color: #fff !important;
        }

        .badge-light-primary{
            background-color: #a000f947 !important;
    color: #210035 !important;
        }
    </style>
    <!-- Optional: SweetAlert script -->
</head>


<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" class="safari-mode app-default">

     <!-- Toast Container -->
     <div id="toast-container" class="toast-container position-fixed top-0 end 0 p-3">
        @if(Session::has('error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ Session::get('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(Session::has('success'))
        <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ Session::get('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(Session::has('warning'))
        <div class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ Session::get('warning') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(Session::has('info'))
        <div class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ Session::get('info') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>


    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            @include('admin.includes.top-nav')
            <!-- Sidebar menu -->
            <div class="app-wrapper  flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('admin.includes.sidebar')
                <!--end::Sidebar-->
                <!-- Dashboard start from here -->
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content  flex-column-fluid">
                            <!--begin::Content container-->
                            {{-- Main content starts here --}}
                            @yield('content')
                            <!--end::Content container-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer ">
                        <!--begin::Footer container-->
                        <div
                            class="app-container  container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                            <!--begin::Copyright-->
                            <div class="text-gray-900 order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">2024&copy; <b>IntraCard</b></span>
                                {{-- <a href="https://keenthemes.com" target="_blank"
                                    class="text-gray-800 text-hover-primary"></a> --}}
                            </div>
                        </div>
                        <!--end::Footer container-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>

            <!--end::Wrapper-->
            <!--end::Page-->
        </div>
    </div>

    @include('admin.includes.logout')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- jquery-confirm JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <!--begin::Vendors Javascript(used for this page only)-->
    {{-- <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script> --}}
    <!--end::Vendors Javascript-->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}

    @include('admin.transactions.view-card-trans-script')
    @include('admin.transactions.view-wallet-trans-script')
    <!--begin::Custom Javascript(used for this page only)-->

    <script src="{{ asset('assets/js/confirm.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/custom/pages/user-profile/general.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script> --}}

    <!-- Move this script after jQuery and before the closing body tag -->
<script>
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",  // This ensures top-right positioning
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Handle session messages
        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}", "Error");
        @endif

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Success");
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}", "Warning");
        @endif

        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}", "Information");
        @endif
    });
</script>
</body>

</html>
