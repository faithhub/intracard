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
    <title>IntraCard Dashboard || {{ isset($dashboard_title) && $dashboard_title ? $dashboard_title : ' My Account' }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--begin::Fonts(mandatory for all pages)-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="https://preview.keenthemes.com/good/assets/plugins/custom/datatables/datatables.bundle.css"
        rel="stylesheet" type="text/css" />
    <link href="https://preview.keenthemes.com/good/assets/plugins/custom/vis-timeline/vis-timeline.bundle.css"
        rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <!-- Replace with your favicon URL -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
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

        /* Optional: Add styling for a hamburger menu */
        @media only screen and (max-width: 768px) {
            .hamburger-menu {
                display: block !important;
            }

            .sidebar {
                display: none !important;
            }
        }
    </style>
    <!-- Optional: SweetAlert script -->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    {{-- <link href="https://preview.keenthemes.com/good/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
                      <link href="https://preview.keenthemes.com/good/assets/css/style.bundle.css" rel="stylesheet" type="text/css"/> --}}

    <!--end::Head-->
    <!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" class="safari-mode app-default">
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <!--begin::Header-->
            @include('includes.top-nav')
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('admin.includes.sidebar')
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content  flex-column-fluid">
                            <!--begin::Content container-->
                            @yield('content')
                            <!--end::Content container-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer">
                        <!--begin::Footer container-->
                        <div
                            class="app-container  container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3 ">
                            <!--begin::Copyright-->
                            <div class="text-gray-900 order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">2024&copy;</span>
                                <a href="" target="_blank"
                                    class="text-gray-800 text-hover-primary">Intracard</a>
                            </div>
                            <!--end::Copyright-->
                            <!--begin::Menu-->
                            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                                <li class="menu-item"><a href="" target="_blank"
                                        class="menu-link px-2">About</a></li>
                                <li class="menu-item"><a href="" target="_blank"
                                        class="menu-link px-2">Support</a></li>
                                <li class="menu-item"><a href="" target="_blank" class="menu-link px-2">Terms
                                        and Condition</a></li>
                            </ul>
                            <!--end::Menu-->
                        </div>
                        <!--end::Footer container-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    <!--begin::Javascript-->
    <!--begin::Vendors Javascript(used for this page only)-->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="https://preview.keenthemes.com/good/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    <script src="https://preview.keenthemes.com/good/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="https://preview.keenthemes.com/good/assets/js/custom/apps/calendar/calendar.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>

