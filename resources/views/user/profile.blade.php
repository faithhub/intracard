@extends('app-user')
@section('content')
    @php
        $addressDetails = json_decode(Auth::user()->address_details, true);
        $account_details = json_decode(Auth::user()->account_details, true);
        $landloard = json_decode(Auth::user()->landlord_or_finance_details, true);
        $finance = json_decode(Auth::user()->landlord_or_finance_details, true);
    @endphp
    <style>
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }

        .modal-body p {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #210035;
            border-color: #210035;
        }

        .btn-light {
            border-color: #dee2e6;
            color: #6c757d;
        }

        .modal-body p {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        #resendCodeLink {
            color: #210035;
            cursor: pointer;
            text-decoration: none;
        }

        #resendCodeLink:hover {
            text-decoration: underline;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-light {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .text-danger {
            font-size: 0.875rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .text-danger {
            font-size: 0.875rem;
        }
    </style>
    <div id="kt_app_content_container" class="app-container  container-fluid ">
        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Aside-->
            <div class="flex-column flex-md-row-auto w-100 w-lg-250px w-xxl-275px">
                <!--begin::Nav-->
                <div class="card mb-6 mb-xl-9" data-kt-sticky="true" data-kt-sticky-name="account-settings"
                    data-kt-sticky-offset="{default: false, lg: 300}" data-kt-sticky-width="{lg: '250px', xxl: '275px'}"
                    data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-zindex="95"
                    style="animation-duration: 0.3s; z-index: 95; position: fixed; top: 100px; width: 250px; left: 330px;"
                    data-kt-sticky-enabled="true">
                    <!--begin::Card body-->
                    <div class="card-body py-10 px-6">
                        <!--begin::Menu-->
                        <ul id="kt_account_settings"
                            class="nav nav-flush menu menu-column menu-rounded menu-title-gray-600 menu-bullet-gray-300 menu-state-bg menu-state-bullet-primary fw-semibold fs-6 mb-2">
                            <li class="menu-item px-3 pt-0 pb-1">
                                <a href="#kt_account_settings_overview" data-kt-scroll-toggle="true"
                                    class="menu-link px-3 nav-link active">
                                    <span class="menu-bullet"><span class="bullet bullet-vertical"></span></span>
                                    <span class="menu-title">Overview</span>
                                </a>
                            </li>

                            @isset($account_details['goal'])
                                @if ($account_details['goal'] == 'mortgage')
                                    @if ($account_details['plan'] == 'pay_mortgage_build')
                                        <li class="menu-item px-3 pt-0 pb-1">
                                            <a href="#kt_account_settings_type" data-kt-scroll-toggle="true"
                                                class="menu-link px-3 nav-link">
                                                <span class="menu-bullet"><span class="bullet bullet-vertical"></span></span>
                                                <span class="menu-title">Co-Owners
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                @elseif ($account_details['goal'] == 'rent')
                                    @if ($account_details['plan'] == 'pay_rent_build')
                                        <li class="menu-item px-3 pt-0 pb-1">
                                            <a href="#kt_account_settings_type" data-kt-scroll-toggle="true"
                                                class="menu-link px-3 nav-link">
                                                <span class="menu-bullet"><span class="bullet bullet-vertical"></span></span>
                                                <span class="menu-title">Co-applicants
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endisset

                            {{-- <li class="menu-item px-3 pt-0 pb-1">
                                <a href="#kt_account_settings_profile_details" data-kt-scroll-toggle="true"
                                    class="menu-link px-3 nav-link">
                                    <span class="menu-bullet"><span class="bullet bullet-vertical"></span></span>
                                    <span class="menu-title">Profile Details</span>
                                </a>
                            </li>
                            <li class="menu-item px-3 pt-0 pb-1">
                                <a href="#kt_account_settings_connected_accounts" data-kt-scroll-toggle="true"
                                    class="menu-link px-3 nav-link">
                                    <span class="menu-bullet"><span class="bullet bullet-vertical"></span></span>
                                    <span class="menu-title">Connected Accounts</span>
                                </a>
                            </li>
                            <li class="menu-item px-3 pt-0">
                                <a href="#kt_account_settings_notifications" data-kt-scroll-toggle="true"
                                    class="menu-link px-3 nav-link">
                                    <span class="menu-bullet"><span class="bullet bullet-vertical"></span></span>
                                    <span class="menu-title">Notifications</span>
                                </a>
                            </li> --}}
                            <li class="menu-item px-3 pt-0">
                                <a href="#kt_account_settings_deactivate" data-kt-scroll-toggle="true"
                                    class="menu-link px-3 nav-link">
                                    <span class="menu-bullet"><span class="bullet bullet-vertical"></span></span>
                                    <span class="menu-title">Deactivate Account</span>
                                </a>
                            </li>
                        </ul>
                        <!--end::Menu-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Nav-->
            </div>
            <!--end::Aside-->

            <!--begin::Layout-->
            <div class="flex-md-row-fluid ms-lg-12">
                <!--begin::Overview-->
                <div class="card  mb-5 mb-xl-10" id="kt_account_settings_overview"
                    data-kt-scroll-offset="{default: 100, md: 125}">
                    <!--begin::Card header-->
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_overview">
                        <div class="card-title">
                            <h3 class="fw-bold m-0">Profile Details</h3>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Content-->
                    <div id="kt_account_settings_overview" class="collapse show">
                        {{-- <div class="card mb-5 mb-xl-10"> --}}
                        <div id="kt_account_settings_profile_details" class="collapse show" tabindex="-1"
                            style="outline: none;">
                            <div class="card-body pt-0 pb-5">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed gy-5"
                                        id="kt_table_users_login_session">
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td>First Name</td>
                                                <td>{{ Auth::user()->first_name ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Last Name</td>
                                                <td>{{ Auth::user()->last_name ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Middle Name</td>
                                                <td>{{ Auth::user()->middle_name ?? '--' }}</td>
                                                <td class="text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td id="userEmail">{{ Auth::user()->email ?? '--' }}</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-icon w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_email">
                                                        <i class="fa fa-pen-to-square fs-3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Phone</td>
                                                <td>{{ Auth::user()->phone ?? '--' }}</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-icon w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_phone">
                                                        <i class="fa fa-pen-to-square fs-3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Content-->
                </div>

                <div class="card mb-5 mb-xl-10" id="kt_account_settings_type">
                    <!-- Card Header -->
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_deactivate" aria-expanded="true" aria-controls="kt_account_deactivate">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">
                                @isset($account_details['goal'])
                                    @if ($account_details['goal'] == 'mortgage' && $account_details['plan'] == 'pay_mortgage_build')
                                        Co-Owners
                                    @elseif ($account_details['goal'] == 'rent' && $account_details['plan'] == 'pay_rent_build')
                                        Co-Applicants
                                    @endif
                                @endisset
                            </h3>
                        </div>
                    </div>
                    <!-- End Card Header -->

                    <!-- Card Content -->
                    <div id="kt_account_settings_overview" class="collapse show">
                        <div id="kt_account_settings_profile_details" class="collapse show" tabindex="-1"
                            style="outline: none;">
                            <div class="card-body pt-0 pb-5">
                                <!-- Check if Co-Applicants exist -->
                                @if (isset($account_details['coApplicants']) && is_array($account_details['coApplicants']))
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed gy-5"
                                            id="kt_table_co_applicants">
                                            <thead>
                                                <tr class="fs-6 fw-semibold text-gray-600">
                                                    <th>#</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    {{-- <th>Rent Amount</th> --}}
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($members as $index => $coApplicant)
                                                    <tr id="coApplicantRow{{ $index }}">
                                                        <td>{{ $index+1 }}</td>
                                                        <td>{{ $coApplicant->first_name }}</td>
                                                        <td>{{ $coApplicant->last_name }}</td>
                                                        <td>{{ $coApplicant->email }}</td>
                                                        {{-- <td>${{ number_format($coApplicant->rentAmount ?? 0, 2) }}</td> --}}
                                                        <td>
                                                            @if ($coApplicant->status === 'active')
                                                                <div class="badge badge-light-success fw-bold">Active</div>
                                                            @else
                                                                <div class="badge badge-light-warning fw-bold">Pending</div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-icon w-30px h-30px ms-auto"
                                                                onclick="editCoApplicant({{ $index }})">
                                                                <i class="fa fa-pen-to-square fs-3"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-icon w-30px h-30px ms-auto"
                                                                onclick="removeCoApplicant({{ $index }})">
                                                                <i class="fa fa-trash fs-3"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No Co-Applicants found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card" id="kt_account_settings_deactivate">
                    <!--begin::Card header-->
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_deactivate" aria-expanded="true"
                        aria-controls="kt_account_deactivate">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Deactivate Account</h3>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Content-->
                    <div id="kt_account_settings_deactivate" class="collapse show" tabindex="-1"
                        style="outline: none;">
                        <!--begin::Form-->
                        <form id="kt_account_deactivate_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                            novalidate="novalidate">

                            <!--begin::Card body-->
                            <div class="card-body border-top p-9">

                                <!--begin::Notice-->
                                <div
                                    class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                                    <div class="d-flex flex-stack flex-grow-1 ">
                                        <div class=" fw-semibold">
                                            <h4 class="text-gray-900 fw-bold">You Are Deactivating Your Account</h4>

                                            <div class="fs-6 text-gray-700 ">For extra security, this requires you to
                                                confirm your email or phone number when you reset yousignr password. <br><a
                                                    class="fw-bold" href="#">Learn more</a></div>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Form input row-->
                                <div class="form-check form-check-solid fv-row fv-plugins-icon-container">
                                    <input name="deactivate" class="form-check-input" type="checkbox" value=""
                                        id="deactivate">
                                    <label class="form-check-label fw-semibold ps-2 fs-6" for="deactivate">I confirm my
                                        account deactivation</label>
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>
                                <!--end::Form input row-->
                            </div>
                            <!--end::Card body-->

                            <!--begin::Card footer-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button id="kt_account_deactivate_account_submit" type="submit"
                                    class="btn btn-danger fw-semibold">Deactivate Account</button>
                            </div>
                            <!--end::Card footer-->

                            <input type="hidden">
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Deactivate Account-->
            </div>
            <!--end::Layout-->
        </div>
    </div>


    <div class="modal fade" id="kt_modal_update_phone" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Phone Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted bg-light-warning rounded border-warning border border-dashed">
                        <i class="fa fa-info-circle"></i>
                        Please ensure the phone number is correct to avoid issues with account verification.
                    </p>
                    <form id="phoneUpdateForm">
                        <div class="mb-3">
                            <label for="phoneNumber" class="form-label">Phone Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phoneNumber" name="phone"
                                value="{{ Auth::user()->phone ?? '' }}" required>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                            <button type="buttom" disabled class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kt_modal_update_email" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Verify and Update Email Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <p class="text-muted bg-light-warning rounded border-warning border border-dashed p-3">
                        <i class="fa fa-info-circle"></i>
                        To update your email, verify your current email and the new email address.
                    </p>

                    <form id="emailUpdateForm">
                        <!-- Step 1: Verify Current Email -->

                        <div id="currentEmailDiv">
                            <div class="fv-row mb-5">
                                <!--begin::Label-->
                                <label class="form-label fw-bold text-gray-900 fs-6 required">Current Email Address</label>
                                <!--end::Label-->
                                <!--begin::Input Group-->
                                <div class="input-group">
                                    <!--begin::Input-->
                                    <input name="email" type="email" required
                                        class="form-control form-control-lg form-control-solid" id="emailInputField"
                                        value="{{ Auth::user()->email }}" readonly>
                                    <!--end::Input-->
                                    <!--begin::Get Code Button-->
                                    <button type="button" class="btn btn-primary" id="getCodeButton"
                                        onclick="sendEmailCodeNew('current')">
                                        <span id="spinner" class="spinner-border spinner-border-sm" role="status"
                                            style="display: none;"></span>
                                        Get Code
                                    </button>
                                    <!--end::Get Code Button-->
                                </div>
                            </div>

                            <div class="fv-row mb-10">
                                <label class="form-label fw-bold text-gray-900 fs-6 required">Code</label>
                                <div class="input-group">
                                    <input type="text" maxlength="6" oninput="enforceNumericInput(this)"
                                        class="form-control form-control-lg form-control-solid" placeholder="Enter code"
                                        id="emailCodeInput" />
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" id="verifyAndUpdateBtn" class="btn btn-primary"
                                    onclick="validateEmailCode('current')">
                                    <span id="validationSpinner" class="spinner-border spinner-border-sm" role="status"
                                        style="display: none;"></span>
                                    Submit</button>
                            </div>
                        </div>
                        <div id="newEmailDiv" style="display: none">
                            <div class="fv-row mb-5">
                                <!--begin::Label-->
                                <label class="form-label fw-bold text-gray-900 fs-6 required">New Email Address</label>
                                <!--end::Label-->
                                <!--begin::Input Group-->
                                <div class="input-group">
                                    <!--begin::Input-->
                                    <input name="email_new" type="email" required
                                        class="form-control form-control-lg form-control-solid" id="emailInputFieldNew">
                                    <!--end::Input-->
                                    <!--begin::Get Code Button-->
                                    <button type="button" class="btn btn-primary" id="getCodeButtonNew"
                                        onclick="sendEmailCodeNew('new')">
                                        <span id="spinnerNew" class="spinner-border spinner-border-sm" role="status"
                                            style="display: none;"></span>
                                        Get Code
                                    </button>
                                    <!--end::Get Code Button-->
                                </div>
                            </div>

                            <div class="fv-row mb-10">
                                <label class="form-label fw-bold text-gray-900 fs-6 required">Code</label>
                                <div class="input-group">
                                    <input type="text" maxlength="6" oninput="enforceNumericInput(this)"
                                        class="form-control form-control-lg form-control-solid" placeholder="Enter code"
                                        id="emailCodeInputNew" />
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" id="verifyAndUpdateBtnNew" class="btn btn-primary"
                                    onclick="validateEmailCode('new')">
                                    <span id="validationSpinnerNew" class="spinner-border spinner-border-sm"
                                        role="status" style="display: none;"></span>
                                    Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script>
        const sendEmailCode = "{{ route('user.sendEmailCode') }}";
        const checkEmailExists = "{{ route('user.checkEmailExists') }}";
        const verifyEmailCode = "{{ route('user.verifyEmailCode') }}";
        const updateEmailEndpoint = "{{ route('user.updateUserEmail') }}";
        const newEmailDiv = document.getElementById("newEmailDiv");
        const currentEmailDiv = document.getElementById("currentEmailDiv");

        function editCoApplicant(index) {
            // Logic to handle editing a co-applicant
            showToast(`Edit functionality for Co-Applicant ${index} not implemented yet.`, 'info');
        }

        function removeCoApplicant(index) {
            // Confirmation dialog before removing
            if (confirm(`Are you sure you want to remove Co-Applicant ${index}?`)) {
                // Remove the row from the table
                const row = document.getElementById(`coApplicantRow${index}`);
                if (row) {
                    row.remove();
                }
                showToast(`Co-Applicant ${index} removed successfully.`, 'success');
                // Optionally send a request to the server to update the backend
            }
        }


        // Function to enforce numeric-only input and limit to 6 digits
        function enforceNumericInput(input) {
            // input.value = input.value.replace(/[^0-9]/g, "");  // Remove non-numeric characters

            // Remove any non-numeric characters
            input.value = input.value.replace(/\D/g, "");

            // Limit input to 6 characters
            if (input.value.length > 6) {
                input.value = input.value.slice(0, 6);
            }
        }

        async function validateEmailCode(type = null) {
            // Determine elements dynamically based on `type`
            const emailFieldId = type === 'current' ? "emailInputField" : "emailInputFieldNew";
            const codeInputId = type === 'current' ? "emailCodeInput" : "emailCodeInputNew";
            const buttonId = type === 'current' ? "verifyAndUpdateBtn" : "verifyAndUpdateBtnNew";
            const spinnerId = type === 'current' ? "validationSpinner" : "validationSpinnerNew";
            const divToShow = type === 'current' ? "newEmailDiv" : null;

            const email = document.getElementById(emailFieldId)?.value;
            const enteredCode = document.getElementById(codeInputId)?.value;
            const validateButton = document.getElementById(buttonId);
            const spinner = document.getElementById(spinnerId);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            // Fetch the current email for the new email validation payload
            const currentEmail = type === 'new' ? document.getElementById("emailInputField").value : null;

            // Validate the code
            if (!enteredCode || enteredCode.length !== 6 || !/^\d{6}$/.test(enteredCode)) {
                showToast("Code must be exactly 6 digits long and numeric.", "error");
                return;
            }

            try {
                // Show spinner and disable button
                spinner.style.display = "inline-block";
                validateButton.disabled = true;

                // Send the validation request
                const response = await fetch(verifyEmailCode, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        email,
                        code: enteredCode
                    }),
                });

                const result = await response.json();

                if (response.ok) {
                    if (type === 'current') {
                        showToast(result.message || "Code verified successfully.", "success");

                        // Hide current email section and show new email section
                        currentEmailDiv.style.display = "none";
                        if (divToShow) document.getElementById(divToShow).style.display = "block";
                    }

                    if (type === 'new') {
                        // Show a temporary loading message
                        document.getElementById("userEmail").textContent = "Loading...";

                        // Update the user's email in the database
                        const updateResponse = await fetch(updateEmailEndpoint, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                            },
                            body: JSON.stringify({
                                current_email: currentEmail,
                                new_email: email,
                            }),
                        });

                        const updateResult = await updateResponse.json();

                        if (updateResponse.ok) {
                            showToast("Your email has been updated successfully.", "success");

                            // Dynamically update the email in the UI
                            document.getElementById("userEmail").textContent = email;

                            // Change button to "Verified"
                            validateButton.textContent = "Verified";
                            validateButton.disabled = true;
                            // Close the modal
                            const modalElement = document.getElementById(
                                'kt_modal_update_email'); // Replace with your modal's ID
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        } else if (updateResponse.status === 422) {
                            // Validation errors
                            const errors = updateResult.errors;
                            Object.keys(errors).forEach((field) => {
                                // Concatenate and display all error messages for each field
                                const errorMessage = errors[field].join(', ');
                                showToast(errorMessage, "error");
                            });
                        } else {
                            // General error handling
                            showToast(result.message || "An error occurred. Please try again.", "error");
                        }
                    }
                } else {
                    showToast(result.message || "Invalid code. Please try again.", "error");
                }
            } catch (error) {
                // Restore original email if the update fails
                document.getElementById("userEmail").textContent = currentEmail;
                if (error.response) {
                    const result = await error.response.json(); // Parse JSON from the response

                    // Handle specific error cases based on the response status code
                    if (error.response.status === 422) {
                        // Validation error (e.g., invalid phone number or email)
                        const errorMessage =
                            result.errors?.email?.[0] ||
                            result.errors?.code?.[0] ||
                            "Validation failed. Please check your input.";
                        showToast(errorMessage, "error");
                    } else if (error.response.status === 403) {
                        // Forbidden (e.g., invalid current email)
                        const errorMessage =
                            result.message || "You are not authorized to perform this action.";
                        showToast(errorMessage, "error");
                    } else if (error.response.status === 500) {
                        // Internal server error
                        const errorMessage =
                            result.error ||
                            "An unexpected error occurred on the server. Please try again later.";
                        showToast(errorMessage, "error");
                    } else {
                        // Generic fallback for other errors
                        const errorMessage = result.message || "An error occurred. Please try again.";
                        showToast(errorMessage, "error");
                    }
                } else {
                    // Network error or no response from server
                    showToast("Unable to connect to the server. Please check your internet connection.", "error");
                }
                console.error(error);
            } finally {
                // Hide spinner and enable button
                spinner.style.display = "none";
                validateButton.disabled = false;
            }
        }


        async function sendEmailCodeNew2(type = null) {
            // Determine elements dynamically based on `type`
            const emailFieldId = type === 'current' ? "emailInputField" : "emailInputFieldNew";
            const buttonId = type === 'current' ? "getCodeButton" : "getCodeButtonNew";
            const spinnerId = type === 'current' ? "#spinner" : "#spinnerNew";

            const email = document.getElementById(emailFieldId)?.value;
            const getCodeButton = document.getElementById(buttonId);
            const spinner = document.querySelector(`${spinnerId}`);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");


            // Validate email
            if (!email || !/^[\w.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                showToast("Please enter a valid email address.", "error");
                return;
            }

            try {
                // Show spinner and disable button
                spinner.style.display = "inline-block";
                getCodeButton.disabled = true;

                // Send the email code
                const response = await fetch(sendEmailCode, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        email,
                        type
                    }),
                });

                const result = await response.json();
                if (response.ok) {
                    showToast(result.message || "Code sent successfully.", "success");
                    startCountdown(getCodeButton, "email");
                } else if (result.status === 422) {
                    // Validation errors
                    const errors = updateResult.errors;
                    console.log(errors);

                    Object.keys(errors).forEach((field) => {
                        // Concatenate and display all error messages for each field
                        const errorMessage = errors[field].join(', ');
                        showToast(errorMessage, "error");
                    });
                } else {
                    // General error handling
                    showToast(result.message || "An error occurred. Please try again.", "error");
                }
            } catch (error) {
                getCodeButton.disabled = false;
                if (error.response) {
                    const result = await error.response.json(); // Parse JSON from the response

                    // Handle specific error cases based on the response status code
                    if (error.response.status === 422) {
                        // Validation error (e.g., invalid phone number or email)
                        const errors = updateResult.errors;
                        console.log(errors, error.response.status);

                        Object.keys(errors).forEach((field) => {
                            // Concatenate and display all error messages for each field
                            const errorMessage = errors[field].join(', ');
                            showToast(errorMessage, "error");
                        });
                    } else if (error.response.status === 403) {
                        // Forbidden (e.g., invalid current email)
                        const errorMessage =
                            result.message || "You are not authorized to perform this action.";
                        showToast(errorMessage, "error");
                    } else if (error.response.status === 500) {
                        // Internal server error
                        const errorMessage =
                            result.error ||
                            "An unexpected error occurred on the server. Please try again later.";
                        showToast(errorMessage, "error");
                    } else {
                        // Generic fallback for other errors
                        const errorMessage = result.message || "An error occurred. Please try again.";
                        console.log(errorMessage, 'errorMessage');

                        showToast(errorMessage, "error");
                    }
                } else {
                    // Network error or no response from server
                    showToast("Unable to connect to the server. Please check your internet connection.", "error");
                }
                console.error(error);
            } finally {
                // Hide spinner and enable button
                spinner.style.display = "none";
                getCodeButton.disabled = false;
            }
        }

        async function sendEmailCodeNew(type = null) {
            // Determine elements dynamically based on `type`
            const emailFieldId = type === 'current' ? "emailInputField" : "emailInputFieldNew";
            const buttonId = type === 'current' ? "getCodeButton" : "getCodeButtonNew";
            const spinnerId = type === 'current' ? "#spinner" : "#spinnerNew";

            const email = document.getElementById(emailFieldId)?.value;
            const getCodeButton = document.getElementById(buttonId);
            const spinner = document.querySelector(`${spinnerId}`);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            // Validate email
            if (!email || !/^[\w.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                showToast("Please enter a valid email address.", "error");
                return;
            }

            try {
                // Show spinner and disable button
                spinner.style.display = "inline-block";
                getCodeButton.disabled = true;

                // Send the email code
                const response = await fetch(sendEmailCode, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        email,
                        type
                    }),
                });

                const result = await response.json();

                // Handle specific response statuses
                if (response.ok) {
                    showToast(result.message || "Code sent successfully.", "success");
                    startCountdown(getCodeButton, "email");
                } else if (response.status === 422) {
                    // Validation errors
                    const errors = result.errors || {};
                    Object.keys(errors).forEach((field) => {
                        const errorMessage = errors[field].join(', ');
                        showToast(errorMessage, "error");
                    });
                } else if (response.status === 403) {
                    // Forbidden error
                    showToast(result.message || "You are not authorized to perform this action.", "error");
                } else if (response.status === 500) {
                    // Internal server error
                    showToast(result.error || "An unexpected error occurred on the server.", "error");
                } else {
                    // Generic fallback for other errors
                    showToast(result.message || "An error occurred. Please try again.", "error");
                }
            } catch (error) {
                // Handle network errors or unexpected exceptions
                showToast("Unable to connect to the server. Please check your internet connection.", "error");
                console.error(error);
            } finally {
                // Hide spinner and enable button
                spinner.style.display = "none";
                getCodeButton.disabled = false;
            }
        }


        function startCountdown(button, type) {
            const currentEmail = document.getElementById("emailInputField").value;

            let timeRemaining = 4 * 60; // 4 minutes in seconds
            button.disabled = true;

            const countdownInterval = setInterval(() => {

                // Update button text with countdown
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                button.innerText = `Resend in ${minutes}:${
                            seconds < 10 ? "0" : ""
                        }${seconds}`;
                timeRemaining--;

                // Stop countdown when time runs out
                if (timeRemaining < 0) {
                    clearInterval(countdownInterval);
                    button.disabled = false;
                    button.innerText = "Get Code";
                }
            }, 1000);
        }
    </script>
@endsection
