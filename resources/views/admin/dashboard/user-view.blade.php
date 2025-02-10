@extends('admin.app-admin')
@section('content')
    @php
        $addressDetails = json_decode($user->address_details, true);
        $account_details = json_decode($user->account_details, true);
        $landloard = json_decode($user->landlord_or_finance_details, true);
        $finance = json_decode($user->landlord_or_finance_details, true);
    @endphp
    <style>
        .text-active-primary.active {
            color: var(--bs-gray-500) !important;
        }

        .nav-line-tabs .nav-item .nav-link.active {
            background-color: #a000f93b !important;
            padding: 8px !important;
            color: #310431 !important;
        }

        .nav-tabs .nav-link {
            border-top-left-radius: 0px !important;
            border-top-right-radius: 0px !important;
        }

        .img-cc {
            max-width: 70px !important;
        }

        .menu-state-bg-light-primary .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) {
            transition: color .2s ease;
            background-color: #a000f93b !important;
            color: var(--bs-primary);
        }
    </style>
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">

                <!--begin::Card-->
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Summary-->


                        <!--begin::User Info-->
                        <div class="d-flex flex-center flex-column py-5">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-100px symbol-circle mb-7">
                                <img src="https://img.freepik.com/premium-vector/avatar-icon0002_750950-43.jpg?semt=ais_hybrid"
                                    alt="image">
                            </div>
                            <!--end::Avatar-->

                            <!--begin::Name-->
                            <a href="#"
                                class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ $user->first_name }}
                                {{ $user->last_name }}</a>

                            <div class="mb-9">
                                <div class="badge badge-lg badge-light-primary d-inline"
                                    style="background-color: #a000f93b !important">
                                    @isset($account_details['goal'])
                                        @if ($account_details['goal'] == 'mortgage')
                                            @if ($account_details['plan'] == 'pay_mortgage')
                                                Mortgage
                                            @else
                                                Mortgage and build credit
                                            @endif
                                        @elseif ($account_details['goal'] == 'rent')
                                            @if ($account_details['plan'] == 'pay_rent')
                                                Rent
                                            @else
                                                Rent and build credit
                                            @endif
                                        @endif
                                    @endisset
                                </div>
                            </div>


                            <div class="fw-bold mb-3" style="display: none">
                                Assigned Tickets

                                <span class="ms-2" ddata-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                                    data-bs-content="Number of support tickets assigned, closed and pending this week.">
                                    <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span></i> </span>
                            </div>
                            <div class="d-flex flex-wrap flex-center" style="display: none !important">
                                <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                    <div class="fs-4 fw-bold text-gray-700">
                                        <span class="w-75px">243</span>
                                        <i class="fa fa-chevron-up fs- text-success"><span class="path1"></span><span
                                                class="path2"></span></i>
                                    </div>
                                    <div class="fw-semibold text-muted">Total</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                    <div class="fs-4 fw-bold text-gray-700">
                                        <span class="w-50px">56</span>
                                        <i class="ki-duotone ki-arrow-down fs-3 text-danger"><span
                                                class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <div class="fw-semibold text-muted">Solved</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                    <div class="fs-4 fw-bold text-gray-700">
                                        <span class="w-50px">188</span>
                                        <i class="fa fa-chevron-up fs- text-success"><span class="path1"></span><span
                                                class="path2"></span></i>
                                    </div>
                                    <div class="fw-semibold text-muted">Open</div>
                                </div>
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::User Info--> <!--end::Summary-->

                        <!--begin::Details toggle-->
                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bold rotate collapsible active" data-bs-toggle="collapse"
                                href="#kt_user_view_details" role="button" aria-expanded="true"
                                aria-controls="kt_user_view_details">
                                Details
                                <span class="ms-2 rotate-180">
                                    <i class="fa fa-chevron-down fs-"></i>
                                </span>
                            </div>
                            {{-- <span data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-original-title="Edit customer details" data-kt-initialized="1">
                                <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_update_details">
                                    Edit
                                </a>
                            </span> --}}
                        </div>
                        <!--end::Details toggle-->

                        <div class="separator"></div>

                        <!--begin::Details content-->
                        <div id="kt_user_view_details" class="collapse show" style="">
                            <div class="pb-5 fs-6">
                                <div class="fw-bold mt-5">Name</div>
                                <div class="text-gray-600">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="fw-bold mt-5">Email</div>
                                <div class="text-gray-600"><a href="#"
                                        class="text-gray-600 text-hover-primary">{{ $user->email }}</a></div>
                                <div class="fw-bold mt-5">Address</div>
                                <div class="text-gray-600">{{ $addressDetails['address'] }},
                                    <br>{{ $addressDetails['city'] }}, <br>{{ $addressDetails['province'] }},
                                    <br>{{ $addressDetails['postalCode'] }}
                                </div>
                                <div class="fw-bold mt-5">Language</div>
                                <div class="text-gray-600">English</div>
                                <div class="fw-bold mt-5">Last Login</div>
                                <div class="text-gray-600">10 Nov 2024, 10:10 pm</div>
                            </div>
                        </div>
                        <!--end::Details content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Sidebar-->

            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                    role="tablist">
                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                            href="#kt_user_view_overview_tab" aria-selected="true" role="tab">Overview</a>
                    </li>
                    <!--end:::Tab item-->


                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                            href="#kt_user_view_overview_account_type" data-kt-initialized="1" aria-selected="false"
                            role="tab" tabindex="-1">
                            @isset($account_details['goal'])
                                @if ($account_details['goal'] == 'mortgage')
                                    Mortgage
                                @elseif ($account_details['goal'] == 'rent')
                                    Rent
                                @endif
                            @endisset
                        </a>
                    </li>
                    <!--end:::Tab item-->

                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                            href="#kt_user_view_overview_credit_card" data-kt-initialized="1" aria-selected="false"
                            role="tab" tabindex="-1">Credit Card(s)</a>
                    </li>
                    <!--end:::Tab item-->

                    <!--begin:::Tab item-->
                    @isset($account_details['goal'])
                        @if ($account_details['goal'] == 'mortgage')
                            @if ($account_details['plan'] == 'pay_mortgage_build')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                        data-bs-toggle="tab" href="#kt_user_view_overview_co_owner" data-kt-initialized="1"
                                        aria-selected="false" role="tab" tabindex="-1"> Co-Owners
                                    </a>
                                </li>
                            @endif
                        @elseif ($account_details['goal'] == 'rent')
                            @if ($account_details['plan'] == 'pay_rent_build')
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                        data-bs-toggle="tab" href="#kt_user_view_overview_co_applicant"
                                        data-kt-initialized="1" aria-selected="false" role="tab"
                                        tabindex="-1">Co-applicants
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endisset

                    <!--begin:::Tab item-->
                    @isset($account_details['goal'])
                        @if ($account_details['goal'] == 'mortgage')
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                    href="#kt_user_view_overview_financer" data-kt-initialized="1" aria-selected="false"
                                    role="tab" tabindex="-1">Mortgage Financer
                                </a>
                            </li>
                        @elseif ($account_details['goal'] == 'rent')
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                                    href="#kt_user_view_overview_landlord" data-kt-initialized="1" aria-selected="false"
                                    role="tab" tabindex="-1">Landlord
                                </a>
                            </li>
                        @endif
                    @endisset

                    <!--end:::Tab item-->

                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                            href="#kt_user_view_overview_transaction" data-kt-initialized="1" aria-selected="false"
                            role="tab" tabindex="-1">Transaction</a>
                    </li>
                    <!--end:::Tab item-->

                    <!--begin:::Tab item-->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                            href="#kt_user_view_overview_events_and_logs_tab" aria-selected="false" role="tab"
                            tabindex="-1">Events &amp; Logs</a>
                    </li>
                    <!--end:::Tab item-->

                    <!--begin:::Tab item-->
                    <li class="nav-item ms-auto">
                        <!--begin::Action menu-->
                        <a href="#" class="btn btn-primary ps-7" data-kt-menu-trigger="click"
                            data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                            Actions &nbsp;
                            <i class="fa fa-chevron-down fs- me-0"></i> </a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6"
                            data-kt-menu="true" style="">
                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">
                                    Account
                                </div>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5">
                                    Reports
                                </a>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-5 my-1">
                                <a href="#" class="menu-link px-5">
                                    Account Settings
                                </a>
                            </div>
                            <!--end::Menu item-->

                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link text-danger px-5">
                                    Delete customer
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                        <!--end::Menu-->
                    </li>
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->

                <!--begin:::Tab content-->
                <div class="tab-content" id="myTabContent">
                    <!--begin:::Tab pane-->
                    <div class="tab-pane fade active show" id="kt_user_view_overview_tab" role="tabpanel">

                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Profile</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed gy-5"
                                        id="kt_table_users_login_session">
                                        <tbody class="fs-6 fw-semibold text-gray-600">

                                            <tr>
                                                <td>First Name</td>
                                                <td>{{ $user->first_name ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Last Name</td>
                                                <td>{{ $user->last_name ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Middle Name</td>
                                                <td>{{ $user->last_name ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>{{ $user->email }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Phone</td>
                                                <td>{{ $user->phone }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table wrapper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Address</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed gy-5"
                                        id="kt_table_users_login_session">
                                        <tbody class="fs-6 fw-semibold text-gray-600">

                                            <tr>
                                                <td>Address</td>
                                                <td>{{ $addressDetails['address'] ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Province</td>
                                                <td>{{ $addressDetails['province'] ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>City</td>
                                                <td>{{ $addressDetails['city'] ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Postal Code</td>
                                                <td>{{ $addressDetails['postalCode'] ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>House Number</td>
                                                <td>{{ $addressDetails['houseNumber'] ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Unit Number</td>
                                                <td>{{ $addressDetails['unitNumber'] ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Street Name</td>
                                                <td>{{ $addressDetails['streetName'] ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table wrapper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <div class="tab-pane fade" id="kt_user_view_overview_account_type" role="tabpanel" style="display: none">
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Profile</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed gy-5"
                                        id="kt_table_users_login_session">
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td>Email</td>
                                                <td>smith@kpmg.com</td>
                                                <td class="text-end">
                                                    <button type="button"
                                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_email">
                                                        <i class="ki-duotone ki-pencil fs-3"><span
                                                                class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Password</td>
                                                <td>******</td>
                                                <td class="text-end">
                                                    <button type="button"
                                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_password">
                                                        <i class="ki-duotone ki-pencil fs-3"><span
                                                                class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Role</td>
                                                <td>Administrator</td>
                                                <td class="text-end">
                                                    <button type="button"
                                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_role">
                                                        <i class="ki-duotone ki-pencil fs-3"><span
                                                                class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table wrapper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Two Step Authentication</h2>

                                    <div class="fs-6 fw-semibold text-muted">Keep your account extra secure with a second
                                        authentication step.</div>
                                </div>
                                <!--end::Card title-->

                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Add-->
                                    <button type="button" class="btn btn-light-primary btn-sm"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-duotone ki-fingerprint-scanning fs-3"><span
                                                class="path1"></span><span class="path2"></span><span
                                                class="path3"></span><span class="path4"></span><span
                                                class="path5"></span></i> Add Authentication Step
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-200px py-4"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_add_auth_app">
                                                Use authenticator app
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_add_one_time_password">
                                                Enable one-time password
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                    <!--end::Add-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pb-5">
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Content-->
                                    <div class="d-flex flex-column">
                                        <span>SMS</span>
                                        <span class="text-muted fs-6">+61 412 345 678</span>
                                    </div>
                                    <!--end::Content-->

                                    <!--begin::Action-->
                                    <div class="d-flex justify-content-end align-items-center">
                                        <!--begin::Button-->
                                        <button type="button"
                                            class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto me-5"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_add_one_time_password">
                                            <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span
                                                    class="path2"></span></i> </button>
                                        <!--end::Button-->

                                        <!--begin::Button-->
                                        <button type="button"
                                            class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                            id="kt_users_delete_two_step">
                                            <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span
                                                    class="path2"></span><span class="path3"></span><span
                                                    class="path4"></span><span class="path5"></span></i> </button>
                                        <!--end::Button-->
                                    </div>
                                    <!--end::Action-->
                                </div>
                                <!--end::Item-->

                                <!--begin:Separator-->
                                <div class="separator separator-dashed my-5"></div>
                                <!--end:Separator-->

                                <!--begin::Disclaimer-->
                                <div class="text-gray-600">
                                    If you lose your mobile device or security key, you can <a href="#"
                                        class="me-1">generate a backup code</a> to sign in to your account.
                                </div>
                                <!--end::Disclaimer-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->

                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2>Email Notifications</h2>

                                    <div class="fs-6 fw-semibold text-muted">Choose what messages you’d like to receive
                                        for each of your accounts.</div>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Form-->
                                <form class="form" id="kt_users_email_notification_form">
                                    <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_0"
                                                type="checkbox" value="0" id="kt_modal_update_email_notification_0"
                                                checked="checked">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_0">
                                                <div class="fw-bold">Successful Payments</div>
                                                <div class="text-gray-600">Receive a notification for every successful
                                                    payment.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_1"
                                                type="checkbox" value="1" id="kt_modal_update_email_notification_1">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_1">
                                                <div class="fw-bold">Payouts</div>
                                                <div class="text-gray-600">Receive a notification for every initiated
                                                    payout.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_2"
                                                type="checkbox" value="2" id="kt_modal_update_email_notification_2">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_2">
                                                <div class="fw-bold">Application fees</div>
                                                <div class="text-gray-600">Receive a notification each time you collect a
                                                    fee from an account.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_3"
                                                type="checkbox" value="3" id="kt_modal_update_email_notification_3"
                                                checked="checked">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_3">
                                                <div class="fw-bold">Disputes</div>
                                                <div class="text-gray-600">Receive a notification if a payment is disputed
                                                    by a customer and for dispute resolutions.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_4"
                                                type="checkbox" value="4" id="kt_modal_update_email_notification_4"
                                                checked="checked">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_4">
                                                <div class="fw-bold">Payment reviews</div>
                                                <div class="text-gray-600">Receive a notification if a payment is marked
                                                    as an elevated risk.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_5"
                                                type="checkbox" value="5" id="kt_modal_update_email_notification_5">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_5">
                                                <div class="fw-bold">Mentions</div>
                                                <div class="text-gray-600">Receive a notification if a teammate mentions
                                                    you in a note.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_6"
                                                type="checkbox" value="6" id="kt_modal_update_email_notification_6">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_6">
                                                <div class="fw-bold">Invoice Mispayments</div>
                                                <div class="text-gray-600">Receive a notification if a customer sends an
                                                    incorrect amount to pay their invoice.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_7"
                                                type="checkbox" value="7" id="kt_modal_update_email_notification_7">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_7">
                                                <div class="fw-bold">Webhooks</div>
                                                <div class="text-gray-600">Receive notifications about consistently
                                                    failing webhook endpoints.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->

                                    <div class="separator separator-dashed my-5"></div> <!--begin::Item-->
                                    <div class="d-flex">
                                        <!--begin::Checkbox-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="email_notification_8"
                                                type="checkbox" value="8" id="kt_modal_update_email_notification_8">
                                            <!--end::Input-->

                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_email_notification_8">
                                                <div class="fw-bold">Trial</div>
                                                <div class="text-gray-600">Receive helpful tips when you try out our
                                                    products.</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Checkbox-->
                                    </div>
                                    <!--end::Item-->


                                    <!--begin::Action buttons-->
                                    <div class="d-flex justify-content-end align-items-center mt-12">
                                        <!--begin::Button-->
                                        <button type="button" class="btn btn-light me-5"
                                            id="kt_users_email_notification_cancel">
                                            Cancel
                                        </button>
                                        <!--end::Button-->

                                        <!--begin::Button-->
                                        <button type="button" class="btn btn-primary"
                                            id="kt_users_email_notification_submit">
                                            <span class="indicator-label">
                                                Save
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                        <!--end::Button-->
                                    </div>
                                    <!--begin::Action buttons-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Card body-->

                            <!--begin::Card footer-->

                            <!--end::Card footer-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end:::Tab pane-->

                    <div class="tab-pane fade" id="kt_user_view_overview_credit_card" role="tabpanel">
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2>Credit Card</h2>
                                </div>
                            </div>
                            <div class="card-body pt-5 pb-5">
                                <div class="row gx-9 gy-6">
                                    <!--begin::Col-->
                                    <div class="col-xl-6" data-kt-billing-element="card">
                                        <!--begin::Card-->
                                        <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column py-2">
                                                <!--begin::Owner-->
                                                <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                                    Jacob Holder
                                                    <span class="badge badge-light-success fs-7 ms-2">Primary</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Icon-->
                                                    <img src="{{ asset('assets/cards/mastercard.png') }}" alt=""
                                                        class="me-4 img-cc">
                                                    <div>
                                                        <div class="fs-4 fw-bold">Mastercard **** 2040</div>
                                                        <div class="fs-6 fw-semibold text-gray-500">Card expires at 10/26
                                                        </div>
                                                        <div class="fs-6 fw-semibold text-gray-500">Card limit
                                                            ${{ number_format(1000, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6" data-kt-billing-element="card">
                                        <!--begin::Card-->
                                        <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column py-2">
                                                <!--begin::Owner-->
                                                <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                                    Jacob Holder
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Icon-->
                                                    <img src="{{ asset('assets/cards/mastercard.png') }}" alt=""
                                                        class="me-4 img-cc">
                                                    <div>
                                                        <div class="fs-4 fw-bold">Mastercard **** 2070</div>
                                                        <div class="fs-6 fw-semibold text-gray-500">Card expires at 10/25
                                                        </div>
                                                        <div class="fs-6 fw-semibold text-gray-500">Card limit
                                                            ${{ number_format(1000, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @isset($account_details['goal'])
                        @if ($account_details['goal'] == 'mortgage')
                            @if ($account_details['plan'] == 'pay_mortgage_build')
                                <div class="tab-pane fade" id="kt_user_view_overview_co_owner" role="tabpanel">
                                    <div class="card pt-4 mb-6 mb-xl-9">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h2>Co-Owners</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0 pb-5"></div>
                                    </div>
                                </div>
                            @endif
                        @elseif ($account_details['goal'] == 'rent')
                            @if ($account_details['plan'] == 'pay_rent_build')
                                <div class="tab-pane fade" id="kt_user_view_overview_co_applicant" role="tabpanel">
                                    <div class="card pt-4 mb-6 mb-xl-9">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h2>Co-applicants</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0 pb-5">
                                            
                                        <div class="table-responsive">
                                            <table class="table align-middle table-row-dashed gy-5"
                                                id="kt_table_co_applicants">
                                                <thead>
                                                    <tr class="fs-6 fw-semibold text-gray-600">
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Email</th>
                                                        <th>Rent Amount</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @isset($account_details['coApplicants'])
                                                    @foreach ($account_details['coApplicants'] as $index => $coApplicant)
                                                        <tr id="coApplicantRow{{ $index }}">
                                                            <td>{{ $coApplicant['firstName'] }}</td>
                                                            <td>{{ $coApplicant['lastName'] }}</td>
                                                            <td>{{ $coApplicant['email'] }}</td>
                                                            <td>${{ number_format($coApplicant['rentAmount'] ?? 0, 2) }}</td>
                                                            <td>
                                                                {{-- @if ($coApplicant['status'] === 'active')
                                                                <div class="badge badge-light-success fw-bold">Active</div>
                                                            @else
                                                            @endif --}}
                                                            <div class="badge badge-light-warning fw-bold">Pending</div>
                                                            <td class="text-end">
                                                                <button type="button"
                                                                    class="btn btn-icon w-30px h-30px ms-auto" onclick="editCoApplicant({{ $index }})">
                                                                    <i class="fa fa-pen-to-square fs-3"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-icon w-30px h-30px ms-auto" onclick="removeCoApplicant({{ $index }})">
                                                                    <i class="fa fa-trash fs-3"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endisset


                    @isset($account_details['goal'])
                        @if ($account_details['goal'] == 'mortgage')
                            <div class="tab-pane fade" id="kt_user_view_overview_financer" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>Mortgage Financer</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5"></div>
                                </div>
                            </div>
                        @elseif ($account_details['goal'] == 'rent')
                            <div class="tab-pane fade" id="kt_user_view_overview_landlord" role="tabpanel">
                                <div class="card pt-4 mb-6 mb-xl-9">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h2>Landlord</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-5">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endisset


                    <div class="tab-pane fade" id="kt_user_view_overview_transaction" role="tabpanel">
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2>Transaction</h2>
                                </div>
                                <div class="card-body pt-0 pb-5">
                                    <div id="kt_customers_table_wrapper"
                                        class="dt-container dt-bootstrap5 dt-empty-footer">
                                        <div id="" class="table-responsive">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                id="kt_customers_table" style="width: 100%;">
                                                <colgroup>
                                                    <col data-dt-column="0" style="width: 36.3906px;">
                                                    <col data-dt-column="1" style="width: 132.766px;">
                                                    <col data-dt-column="2" style="width: 156.844px;">
                                                    <col data-dt-column="3" style="width: 191.25px;">
                                                    <col data-dt-column="4" style="width: 170.078px;">
                                                    <col data-dt-column="5" style="width: 187.438px;">
                                                    <col data-dt-column="6" style="width: 111.734px;">
                                                </colgroup>
                                                <thead>
                                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0"
                                                        role="row">
                                                        <th class="w-10px pe-2 dt-orderable-none" data-dt-column="0"
                                                            rowspan="1" colspan="1" aria-label="">
                                                            <span class="dt-column-title">
                                                                <div
                                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        data-kt-check="true"
                                                                        data-kt-check-target="#kt_customers_table .form-check-input"
                                                                        value="1">
                                                                </div>
                                                            </span>
                                                            <span class="dt-column-order"></span>
                                                        </th>
                                                        <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                            data-dt-column="1" rowspan="1" colspan="1"
                                                            aria-label="Customer Name: Activate to sort" tabindex="0">
                                                            <span class="dt-column-title" role="button">Transaction
                                                                ID</span><span class="dt-column-order"></span>
                                                        </th>
                                                        <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                            data-dt-column="2" rowspan="1" colspan="1"
                                                            aria-label="Email: Activate to sort" tabindex="0"><span
                                                                class="dt-column-title" role="button">Amount</span><span
                                                                class="dt-column-order"></span></th>
                                                        <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                            data-dt-column="4" rowspan="1" colspan="1"
                                                            aria-label="Payment Method: Activate to sort" tabindex="0">
                                                            <span class="dt-column-title" role="button"
                                                                id="tableCardType">Credit Card</span><span
                                                                class="dt-column-order"></span>
                                                        </th>
                                                        <th class="min-w-125px dt-orderable-asc dt-orderable-desc"
                                                            data-dt-column="5" rowspan="1" colspan="1"
                                                            aria-label="Created Date: Activate to sort" tabindex="0">
                                                            <span class="dt-column-title" role="button">Date</span><span
                                                                class="dt-column-order"></span>
                                                        </th>
                                                        <th class="text-end min-w-70px dt-orderable-none"
                                                            data-dt-column="6" rowspan="1" colspan="1"
                                                            aria-label="Actions"><span
                                                                class="dt-column-title">Actions</span><span
                                                                class="dt-column-order"></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="1">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            #DFSGDFHGGFDJHGF
                                                        </td>
                                                        <td>
                                                            ${{ number_format(500, 2) }}
                                                        </td>
                                                        <td data-filter="visa">
                                                            <img src="{{ asset('assets/cards/mastercard.png') }}"
                                                                class="w-35px me-3" alt="">
                                                            **** 3215
                                                        </td>
                                                        <td data-order="2020-08-18T15:34:00+01:00">
                                                            18 Aug 2020, 3:34 pm
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="#" class="menu-link px-3">View</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="1">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            #DFSGDFHKJDJHGF
                                                        </td>
                                                        <td>
                                                            ${{ number_format(500, 2) }}
                                                        </td>
                                                        <td data-filter="visa">
                                                            <img src="{{ asset('assets/cards/visa.webp') }}"
                                                                class="w-35px me-3" alt="">
                                                            **** 3267
                                                        </td>
                                                        <td data-order="2020-08-18T15:34:00+01:00">
                                                            20 Aug 2020, 3:34 pm
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="#" class="menu-link px-3">View</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--begin:::Tab pane-->
                    <div class="tab-pane fade" id="kt_user_view_overview_events_and_logs_tab" role="tabpanel">
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Login Sessions</h2>
                                </div>
                                <!--end::Card title-->

                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Filter-->
                                    <button type="button" class="btn btn-sm btn-flex btn-light-primary"
                                        id="kt_modal_sign_out_sesions">
                                        <i class="ki-duotone ki-entrance-right fs-3"><span class="path1"></span><span
                                                class="path2"></span></i> Sign out all sessions
                                    </button>
                                    <!--end::Filter-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed gy-5"
                                        id="kt_table_users_login_session">
                                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-100px">Location</th>
                                                <th>Device</th>
                                                <th>IP Address</th>
                                                <th class="min-w-125px">Time</th>
                                                <th class="min-w-70px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td>
                                                    Australia </td>
                                                <td>
                                                    Chome - Windows </td>
                                                <td>
                                                    207.31.45.280 </td>
                                                <td>
                                                    23 seconds ago </td>
                                                <td>
                                                    Current session </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Australia </td>
                                                <td>
                                                    Safari - iOS </td>
                                                <td>
                                                    207.49.33.78 </td>
                                                <td>
                                                    3 days ago </td>
                                                <td>
                                                    <a href="#" data-kt-users-sign-out="single_user">Sign out</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Australia </td>
                                                <td>
                                                    Chrome - Windows </td>
                                                <td>
                                                    207.49.49.69 </td>
                                                <td>
                                                    last week </td>
                                                <td>
                                                    Expired </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table wrapper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->

                       
                    </div>
                    <!--end:::Tab pane-->
                </div>
                <!--end:::Tab content-->
            </div>
            <!--end::Content-->
        </div>
    </div>
@endsection
