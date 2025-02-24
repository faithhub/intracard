<!-- Begin Sidebar -->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="300px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!-- Begin Logo -->
    <div class="app-sidebar-logo d-flex justify-content-center align-items-center px-8 py-4" id="kt_app_sidebar_logo">
        <a href="">
            <img alt="Logo" src="{{ asset('assets/images/logos/Intracard.svg') }}" class="theme-light-show h-50px" />
        </a>
    </div>
    <!-- End Logo -->

    <div class="separator my-3"></div>

    <div class="app-sidebar-menu hover-scroll-y my-5" id="kt_app_sidebar_menu_wrapper" data-kt-scroll="true"
        data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_toolbar, #kt_app_sidebar_footer"
        data-kt-scroll-offset="0">

        <!-- Begin Menu -->
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold px-3" id="#kt_sidebar_menu"
            data-kt-menu="true">
        
            <div class="menu-item">
                @if (Auth::guard('admin')->check())
                    <!-- Dashboard Link -->
                    <a class="menu-link menu-link-sidebar d-flex align-items-center py-3 px-4 mb-2 {{ Request::is('admin/dashboard') || Request::is('dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <i class="fa fa-house fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title ms-3 fs-6 fw-bold">Dashboard</span>
                    </a>

                    <!-- Manage Admins Link - Restricted to System Admin -->
                    @if(Auth::guard('admin')->user()->can('access-all'))
                        <a class="menu-link menu-link-sidebar d-flex align-items-center py-3 px-4 mb-2 {{ Request::is('admin/admin-users') ? 'active' : '' }}"
                            href="{{ route('admin.admin-users.index') }}">
                            <span class="menu-icon">
                                <i class="fa-regular fa-newspaper fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title ms-3 fs-6 fw-bold">Manage Admins</span>
                        </a>
                    @endif

                    <!-- Onboarding Link - Accessible to System Admin and Admin -->
                    @if(Auth::guard('admin')->user()->can('access-onboarding'))
                        <a class="menu-link menu-link-sidebar d-flex align-items-center py-3 px-4 mb-2 {{ Request::is('admin/onboarding') ? 'active' : '' }}"
                            href="{{ route('admin.onboarding') }}">
                            <span class="menu-icon">
                                <i class="fa-regular fa-newspaper fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title ms-3 fs-6 fw-bold">Onboarding</span>
                        </a>
                    @endif

                    <!-- Manage Users Link - Accessible to System Admin and Admin -->
                    @if(Auth::guard('admin')->user()->can('manage-users-only'))
                        <a class="menu-link menu-link-sidebar d-flex align-items-center py-3 px-4 mb-2 {{ Request::is('admin/users') || Request::is('admin/user/*') ? 'active' : '' }}"
                            href="{{ route('admin.users') }}">
                            <span class="menu-icon"><i class="fa fa-users fx-1">
                                <span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <span class="menu-title">Users</span>
                        </a>
                    @endif

                    <!-- CB Reporting (Restricted by Role) -->
                    @if(Auth::guard('admin')->user()->can('access-support'))
                        <div data-kt-menu-trigger="click"
                            class="menu-link menu-link-sidebar d-flex align-items-center py-3 px-4 mb-2 {{ Request::is('admin/equifax-report') || Request::is('admin/transunion-report') ? 'hover show' : '' }}">
                            <span class="menu-icon">
                                <i class="fa fa-database fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title ms-3 fs-6 fw-bold">CB Reporting</span>
                            <span class="menu-arrow ms-auto"></span>
                        </div>
                    @endif

                    <div class="menu-sub menu-sub-accordion px-4 {{ Request::is('admin/equifax-report') || Request::is('admin/transunion-report') ? 'show' : '' }}">
                        <div class="menu-item">
                            <a class="menu-link menu-link-sidebar py-3 {{ Request::is('admin/equifax-report') ? 'active' : '' }}"
                                href="{{ route('admin.report.equifax') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title ms-2">Equifax</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link menu-link-sidebar py-3 {{ Request::is('admin/transunion-report') ? 'active' : '' }}"
                                href="{{ route('admin.report.transunion') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title ms-2">TransUnion</span>
                            </a>
                        </div>
                    </div>

                    <!-- Transactions -->
                    @if(Auth::guard('admin')->user()->can('access-finance'))
                        <a class="menu-link menu-link-sidebar {{ Request::is('admin/card-transactions') ? 'active' : '' }}"
                            href="{{ route('admin.transaction.card') }}">
                            <span class="menu-icon"><i class="fa fa-credit-card">
                                    <span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <span class="menu-title">Card Transactions</span>
                        </a>
                        <a class="menu-link menu-link-sidebar {{ Request::is('admin/wallet-transactions') ? 'active' : '' }}"
                            href="{{ route('admin.wallet.transaction') }}">
                            <span class="menu-icon"><i class="fa fa-wallet">
                                    <span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <span class="menu-title">Wallet Transaction</span>
                        </a>
                    @endif

                    <!-- Help & Support -->
                    @if(Auth::guard('admin')->user()->can('access-support'))
                        <a class="menu-link menu-link-sidebar {{ Request::is('admin/support') ? 'active' : '' }}"
                            href="{{ route('admin.support') }}">
                            <span class="menu-icon"><i class="fa-brands fa-rocketchat">
                                    <span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <span class="menu-title">Help & Support</span>
                        </a>
                    @endif

                    <!-- Reports -->
                    @if(Auth::guard('admin')->user()->can('access-all'))
                        <a class="menu-link menu-link-sidebar {{ Request::is('admin/reports') ? 'active' : '' }}"
                            href="{{ route('admin.report.index') }}">
                            <span class="menu-icon"><i class="fa-brands fa-rocketchat">
                                    <span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <span class="menu-title">Reports</span>
                        </a>
                    @endif

                    <!-- Settings -->
                    @if(Auth::guard('admin')->user()->can('access-all'))
                        <a class="menu-link menu-link-sidebar {{ Request::is('admin/settings') ? 'active' : '' }}"
                            href="{{ route('admin.settings') }}">
                            <span class="menu-icon"><i class="fa-brands fa-rocketchat">
                                    <span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <span class="menu-title">Settings</span>
                        </a>
                    @endif

                    <!-- Logout -->
                    <a class="menu-link menu-link-sidebar logoutBtn" href="javascript:void(0);" id="logoutBtn">
                        <span class="menu-icon"><i class="fa fa-lock fx-1">
                                <span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title">Logout</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End Sidebar -->


<style>
    .app-sidebar {
        /* background: linear-gradient(180deg, #8f8585 0%, #f8f8f8 100%); */
        background: #21003517;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .menu-link-sidebar {
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
        background: transparent !important;
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }

    .menu-link-sidebar:hover {
        background: rgba(33, 0, 53, 0.05) !important;
        transform: translateX(5px) !important;
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    .menu-link-sidebar.active {
        background: #210035 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(33, 0, 53, 0.2) !important;
    }

    .menu-link-sidebar.active .menu-icon i,
    .menu-link-sidebar.active .menu-title {
        color: #ffffff !important;
    }

    .menu-icon i {
        color: #210035 !important;
        transition: all 0.3s ease !important;
    }

    .menu-title {
        color: #333333 !important;
        transition: all 0.3s ease !important;
    }

    .separator {
        border-color: rgba(33, 0, 53, 0.1);
    }

    .menu-sub-accordion {
        margin-left: 2.5rem;
        border-left: 2px solid rgba(33, 0, 53, 0.1);
    }

    .bullet-dot {
        width: 6px;
        height: 6px;
        background-color: #210035;
        border-radius: 50%;
        display: inline-block;
    }

    .menu-arrow {
        transition: transform 0.3s ease;
    }

    .menu-item.show>.menu-link .menu-arrow {
        transform: rotate(90deg) !important;
    }
</style>
