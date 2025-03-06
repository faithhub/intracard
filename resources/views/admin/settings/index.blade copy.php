@extends('admin.app-admin')
@section('content')

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
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    App Settings
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->


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
                            role="tab" tabindex="-1">AutoReply</a>
                    </li>
                    <!--end:::Tab item-->

                </ul>
                <!--end:::Tabs-->

                <!--begin:::Tab content-->
                <div class="tab-content" id="myTabContent">
                    <!--begin:::Tab pane-->
                    <div class="tab-pane fade active show" id="kt_user_view_overview_tab" role="tabpanel">  
                    </div>

                    <div class="tab-pane fade" id="kt_user_view_overview_account_type" role="tabpanel"
                        style="display: block">
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">

                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">AutoReply Messages</h2>

                                    <div class="fs-6 fw-semibold text-muted"></div>
                                </div>
                                <!--end::Card title-->

                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Add-->
                                    <button type="button" class="btn btn-light-primary btn-sm" data-type="dark"
                                        data-size="s" data-title="Create AutoReply Message" onclick="createConfirm(event)"
                                        href="{{ route('admin.auto-replies.create') }}">
                                        <i class="fa fa-plus fs-3 text-white"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span><span class="path5"></span></i> Add
                                    </button>
                                    <!--end::Add-->
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
                                        <thead>
                                            <th width="5%">SN</th>
                                            <th width="20%">Keywords</th>
                                            <th width="65%">Response</th>
                                            <th width="10%">Action</th>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            @isset($autoReplies)
                                                @foreach ($autoReplies as $index => $reply)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            {{ is_array($reply->keywords)
                                                                ? implode(
                                                                    ', ',
                                                                    array_map(function ($item) {
                                                                        return str_replace(',', ', ', $item); // Add space after commas in each array item
                                                                    }, $reply->keywords),
                                                                )
                                                                : implode(', ', json_decode($reply->keywords, true)) }}
                                                        </td>

                                                        <td>{{ $reply->response }}</td>
                                                        <td class="text-end">
                                                            <!-- Edit Button -->
                                                            <button type="button" class="btn btn-icon w-30px h-30px ms-auto"
                                                                data-size="s" data-title="Update AutoReply Message"
                                                                onclick="editConfirm(event)"
                                                                href="{{ route('admin.auto-replies.edit', $reply->id) }}">
                                                                <i class="fa fa-pen-to-square fs-3"></i>
                                                            </button>

                                                            <!-- Delete Button -->
                                                            <button class="btn btn-icon w-30px h-30px ms-auto"
                                                                onclick="deleteAutoReply({{ $reply->id }}, '{{ route('admin.auto-replies.destroy', $reply->id) }}')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset
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
                        {{-- <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">AutoReply Messages</h2>

                                    <div class="fs-6 fw-semibold text-muted"></div>
                                </div>

                                <div class="card-toolbar">
                                    <!--begin::Add-->
                                    <button type="button" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-plus fs-3 text-white"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span><span class="path5"></span></i> Add
                                    </button>
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
                        </div> --}}
                        <!--end::Card-->
                    </div>
                    <!--end:::Tab pane-->

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

    <script>
        function deleteAutoReply(autoReplyId, deleteUrl) {
            Swal.fire({
                text: "Are you sure you want to delete this auto-reply?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-secondary",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the AJAX deletion
                    $.ajax({
                        url: deleteUrl, // Pass the delete URL dynamically
                        type: "POST", // Use POST with the _method spoofed
                        data: {
                            _method: "DELETE", // Method spoofing for DELETE
                            _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token
                        },
                        success: function(response) {
                            Swal.fire({
                                text: response.message || "Auto-reply deleted successfully!",
                                icon: "success",
                                buttonsStyling: false,
                                timer: 2000, // Automatically close after 2 seconds
                                showConfirmButton: false,
                            });

                            // Optionally refresh the page or remove the deleted row dynamically
                            $(`#auto-reply-row-${autoReplyId}`)
                                .remove(); // Assuming row has an ID like auto-reply-row-1
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                text: "Failed to delete the auto-reply. Please try again.",
                                icon: "error",
                                buttonsStyling: false, // Disable default SweetAlert2 button styling
                                showConfirmButton: true,
                                confirmButtonText: "Retry", // Custom button text
                                customClass: {
                                    confirmButton: "btn btn-danger", // Bootstrap or your custom class
                                },
                            });
                        },
                    });
                }
            });
        }
    </script>
@endsection
