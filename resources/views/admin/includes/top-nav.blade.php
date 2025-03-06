<style>
    /* Hide by default */
.mobile-only {
    display: none;
}

.desktop-only {
    display: block;
}

/* Show profile picture only on smaller screens */
@media (max-width: 768px) {
    .remove-pp-margin{
        margin-right:0rem !important;
    }
    .mobile-only {
        display: block; /* Show profile picture */
    }
    .desktop-only {
        display: none; /* Hide username and description */
    }
}

/* Show username and description only on larger screens */
@media (min-width: 769px) {
    .mobile-only {
        display: none; /* Hide profile picture */
    }
    .desktop-only {
        display: block; /* Show username and description */
    }
}

</style>
<div id="kt_app_header" class="app-header ">
    <!--begin::Header container-->
    <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between "
        id="kt_app_header_container">
        <!--begin::Mobile menu toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px"
                id="kt_app_sidebar_mobile_toggle">
                <i class="fa fa-bars fs-1" style="font-size: 2.5rem !important;"></i>
                {{-- <i class="fa fa-user fs-1"></i> --}}
            </div>
        </div>
        <!--end::Mobile menu toggle-->
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="" class="d-lg-none">
                <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}"
                    class="h-45px" />
            </a>
        </div>
        <!--end::Mobile logo-->
        <!--begin::Header wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
            id="kt_app_header_wrapper">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1
                    class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Dashboard
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="breadcrumb-link text-muted text-hover-primary">
                            Home
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted" id="topNavText">
                        {{  isset($dashboard_title) && $dashboard_title ? $dashboard_title : ' My Account' }}
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>

            <div class="app-navbar align-items-center flex-shrink-0">
                <div class="app-sidebar-user d-flex flex-stack">
                    <div class="d-flex">
                        <div class="me-5 remove-pp-margin">
                            <div class="symbol symbol-40px cursor-pointer"
                                data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="bottom-start" data-kt-menu-overflow="true">
                                <img src="{{ Auth::guard('admin')->user()->profile_picture ? Storage::url(Auth::guard('admin')->user()->profile_picture) : 'https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid' }}"
                                    alt="Profile" class="header-profile-img" />
                            </div>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content d-flex align-items-center px-3">
                                        <div class="symbol symbol-50px me-5">
                                            <img alt="Profile"
                                                src="{{ Auth::guard('admin')->user()->profile_picture ? Storage::url(Auth::guard('admin')->user()->profile_picture) : asset('assets/images/default-avatar.png') }}" 
                                                class="header-profile-img" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold d-flex align-items-center fs-5 header-user-name">
                                                {{ Auth::guard('admin')->user()->first_name ?? '--' }} {{ Auth::guard('admin')->user()->last_name ?? '--' }}
                                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                                    Admin
                                                </span>
                                            </div>
                                            <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::guard('admin')->user()->email ?? '--' }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator my-2"></div>
                                <div class="menu-item px-5">
                                    <a href="{{ route('admin.profile') }}" class="menu-link px-5">
                                        My Profile
                                    </a>
                                </div>
                                <div class="menu-item px-5">
                                    <a href="{{ route('admin.profile') }}#password" class="menu-link px-5">
                                        <span class="menu-text">Update Password</span>
                                        <span class="menu-badge">
                                            <!-- <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span> -->
                                        </span>
                                    </a>
                                </div>
                                <div class="menu-item px-5">
                                    <button id="logoutBtn"
                                        class="menu-link px-5 logoutBtn">
                                        Sign Out
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="me-2 desktop-only">
                            <!--begin::Username-->
                            <a href="#"
                                class="app-sidebar-username text-gray-800 text-hover-primary fs-6 fw-semibold lh-0 header-user-name">{{ Auth::guard('admin')->user()->first_name ?? '--' }} {{ Auth::guard('admin')->user()->last_name ?? '--' }}</a>
                            <!--end::Username-->

                            <!--begin::Description-->
                            <span class="app-sidebar-deckription text-gray-500 fw-semibold d-block fs-8">My
                                Account</span>
                            <!--end::Description-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header wrapper-->
</div>