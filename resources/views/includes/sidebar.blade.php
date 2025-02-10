@php
    // $addressDetails = json_decode(Auth::user()->address_details, true);
    // $landloard = json_decode(Auth::user()->landlord_or_finance_details, true);
    // $account_details = json_decode(Auth::user()->account_details, true);
    // $finance = json_decode(Auth::user()->landlord_or_finance_details, true);
@endphp
<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo d-none d-lg-flex flex-stack flex-shrink-0 px-8" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="">
            <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}" class="theme-light-show h-70px" />
            {{-- <img alt="Logo"
            src="{{ asset('assets/logos/credipay.png') }}"
            class="theme-dark-show h-50px" /> --}}
        </a>
        {{-- Intracard --}}
    </div>
    <!--end::Logo-->
    <div class="separator d-none d-lg-block"></div>
    <!--end::Toolbar-->
    <div class="separator"></div>

    <div class="app-sidebar-menu  hover-scroll-y my-5 my-lg-5 mx-3" id="kt_app_sidebar_menu_wrapper"
        data-kt-scroll="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_toolbar, #kt_app_sidebar_footer" data-kt-scroll-offset="0"
        style="height: 924px;">
        <!--begin::Menu-->
        <div class="menu menu-column menu-sub-indention menu-active-bg fw-semibold" id="#kt_sidebar_menu"
            data-kt-menu="true">
            <div class="menu-item">
                <a class="menu-link {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <span class="menu-icon">
                        <i class="fa fa-house fx-1">
                            <span class="path1"></span>
                            <span class="path2"></span></i>
                    </span>
                    <span class="menu-title">Dashboard</span>
                </a>
                @if (Auth::check())
                    <!-- Check if the user is authenticated -->
                    {{-- @if (Auth::user()->account_type == 'rent')
                        <a class="menu-link {{ Request::is('rental-details') ? 'active' : '' }}" href="{{ route('rental') }}">
                            <!-- Example of route usage -->
                            <span class="menu-icon">
                                <i class="fa fa-house fx-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Landlord Details</span>
                        </a>
                    @endif --}}
                    @if (Auth::user()->account_type == 'rent')
                        <a class="menu-link {{ Request::is('rental-details') ? 'active' : '' }}"
                            href="{{ route('rental') }}">
                            <!-- Example of route usage -->
                            <span class="menu-icon">
                                <i class="fa fa-house fx-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Rental Details</span>
                        </a>
                    @endif

                    @if (Auth::user()->account_type == 'mortgage')
                        <a class="menu-link {{ Request::is('mortgage-details') ? 'active' : '' }}"
                            href="{{ route('mortgage') }}">
                            <!-- Example of route usage -->
                            <span class="menu-icon">
                                <i class="fa fa-house-user fx-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Mortgage Details</span>
                        </a>
                    @endif
                @endif
                <a class="menu-link {{ Request::is('billing-details') ? 'active' : '' }}" href="{{ route('billing') }}">
                    <span class="menu-icon"><i class="fa fa-credit-card fx-1">
                            <span class="path1"></span><span class="path2"></span></i>
                    </span>
                    <span class="menu-title">My Cards</span>
                </a>
                <a class="menu-link {{ Request::is('wallet') ? 'active' : '' }}" href="{{ route('wallet') }}">
                    <span class="menu-icon"><i class="fa fa-credit-card fx-1">
                            <span class="path1"></span><span class="path2"></span></i>
                    </span>
                    <span class="menu-title">Wallet</span>
                </a>

                {{-- @if ($account_details['plan'] == 'pay_mortgage_build' || $account_details['plan'] == 'pay_rent_build')
                    <a class="menu-link {{ Request::is('credit-advisory') ? 'active' : '' }}"
                        href="{{ route('advisory') }}">
                        <span class="menu-icon"><i class="fa fa-money-bill fx-1">
                                <span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title">Credit Advisory</span>
                    </a>
                @endif --}}
                <a class="menu-link {{ Request::is('chat-us') ? 'active' : '' }}" href="{{ route('chat-us') }}">
                    <span class="menu-icon"><i class="fa-brands fa-rocketchat fx-1">
                            <span class="path1"></span><span class="path2"></span></i>
                    </span>
                    <span class="menu-title">Chat Support</span>
                </a>
            </div>
        </div>
    </div>
    <div class="app-sidebar-user d-flex flex-stack py-5 px-8">
        <!--begin::User avatar-->
        <div class="d-flex me-5">
            <div class="me-5">
                <a class="menu-link userLogoutBtn" href="javascript:void(0);" id="logoutBtn">
                    <span class="menu-icon"><i class="fa fa-right-from-bracket fx-1"  style="color: red">
                            <span class="path1"></span><span class="path2"></span></i>
                    </span>&nbsp;
                    <span class="menu-title" style="color: red">Logout</span>
                </a>
            </div>
            <div class="me-2">
            </div>
        </div>
    </div>
</div>
