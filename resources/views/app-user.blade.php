<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>IntraCard Dashboard || {{ isset($dashboard_title) && $dashboard_title ? $dashboard_title : ' My Account' }}</title>
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

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>

    <!-- jquery-confirm CSS -->
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

        /* Optional: Add styling for a hamburger menu */
        @media only screen and (max-width: 768px) {
            .hamburger-menu {
                display: block !important;
            }

            .sidebar {
                display: none !important;
            }
        }

        #toastContainer {
            z-index: 1060 !important;
            position: absolute;
        }
    </style>
    <!-- Optional: SweetAlert script -->
</head>
<!--end::Head-->

<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" class="safari-mode app-default">
    <div class="position-fixed top-0 end-0 p-3" id="toastContainer" style="z-index: 1060;">
        <div id="toastContainer"></div>
    </div>
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <!--begin::Header-->
            @include('includes.top-nav')
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('includes.sidebar')
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content  flex-column-fluid">
                            <!--begin::Content container-->
                            @yield('content')
                            {{-- <div id="app">
                                @yield('content')
                            </div> --}}
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
                                <a href="" target="_blank" class="text-gray-800 text-hover-primary">Intracard</a>
                            </div>
                            <!--end::Copyright-->
                            <!--begin::Menu-->
                            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                                <li class="menu-item"><a href="" target="_blank" class="menu-link px-2">About</a>
                                </li>
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

    @include('includes.logout')

    <script src="https://unpkg.com/vue-final-modal@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-final-modal@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/confirm.js') }}"></script> --}}

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- jquery-confirm JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->

    @include('user.modals.includes.setupBill')
    @include('user.modals.includes.wallet')

    <script>

        function showToast(message, type) {
            const toastContainer = document.getElementById("toastContainer");

            // Create a new toast element
            const toast = document.createElement("div");
            toast.className = `toast align-items-center text-bg border-0`;
            //  toast.className = `toast align-items-center text-bg-${type} border-0`;
            toast.setAttribute("role", "alert");
            toast.setAttribute("aria-live", "assertive");
            toast.setAttribute("aria-atomic", "true");

            // Define icons and styles based on type
            let icon, backgroundColor, color, borderColor;

            if (type === "success") {
                icon = "✔️";
                // toast.style.backgroundColor = '#e6f7e9';
                color = "#28a745";
                // borderColor = '#28a745';
            } else if (type === "error") {
                icon = "❌";
                // toast.style.backgroundColor = 'rgb(237 113 98 / 80%)';
                color = "#d93025";
                // borderColor = '#d93025';
            } else if (type === "info") {
                icon = "ℹ️";
                // toast.style.backgroundColor = '#e3f2fd';
                color = "#007bff";
                // borderColor = '#007bff';
            } else if (type === "warning") {
                icon = "⚠️";
                // toast.style.backgroundColor = '#fff4e5';
                color = "#ffc107";
                // borderColor = '#ffc107';
            }

            // Define the toast title and background color based on the type
            const title = type.charAt(0).toUpperCase() + type.slice(1); // Capitalize first letter

            // Toast content with close button and auto-dismiss setup
            toast.innerHTML = `
         <div class="toast-header">
             <span class="me-2">${icon}</span>
             <strong class="me-auto">${title}</strong>
             <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
         <div class="toast-body">
             ${message}
         </div>
     `;

            //      toast.innerHTML = `<div class="d-flex">
        //     <div class="toast-body">
        //     ${icon}  ${message}
        //     </div>
        //   </div>`;

            // Append the toast to the container and initialize it
            toastContainer.appendChild(toast);
            const bootstrapToast = new bootstrap.Toast(toast, {
                delay: 3000,
                autohide: true,
            }); // Set autohide with delay
            bootstrapToast.show();

            // Automatically remove the toast element after it hides
            toast.addEventListener("hidden.bs.toast", () => {
                toast.remove();
            });
        }
    </script>
</body>
<!--end::Body-->

</html>
