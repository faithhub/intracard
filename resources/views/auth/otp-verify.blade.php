<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>Auth OTP Page</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Good admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
    <meta name="keywords"
        content="Good, bootstrap, bootstrap 5, admin themes, Asp.Net Core & Django starter kits, admin themes, bootstrap admin, bootstrap dashboard, bootstrap dark mode" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Auth OTP Page" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="Good by Keenthemes" />
    <link rel="shortcut icon" href="good/assets/media/logos/favicon.ico" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->



    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="app-blank">

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Signup Free Trial-->
        <div class="d-flex flex-column flex-xl-row flex-column-fluid">
            <div class="container">
                <div class="center-div">
                    <!--begin::Content-->
                    <div class="flex-row-fluid flex-center justfiy-content-xl-first p-10">
                        <!--begin::Wrapper-->
                        <div class="">
                            <!--begin::Form-->
                            <form class="form" novalidate="novalidate" data-kt-redirect-url="" id=""
                                action="" method="POST">
                                @csrf

                                <!--begin::Heading-->
                                <div class="text-center mb-10">
                                    <!--begin::Logo-->
                                    <a href="" class="mb-15">
                                        <img alt="Logo" src="{{ asset('assets/media/logos/default.svg') }}"
                                            class="h-40px" />
                                    </a>
                                    <!--end::Logo-->
                                    <!--begin::Title-->
                                    <h1 class="text-gray-900 mb-3 mt-3">Verify your account</h1>
                                    <!--end::Title-->
                                    <div class="text-gray-500 fw-semibold fs-4">
                                        Enter the verification code we sent you by email
                                    </div>

                                </div>
                                <!--begin::Heading-->

                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fs-6 fw-bold text-gray-900">Verification Code</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid" type="text"
                                        name="otp_code" autocomplete="off" />
                                    <!--end::Input-->
                                    <span class="error-message text-danger" id="otp_code-error"></span>
                                    @if ($errors->has('otp_code'))
                                        <div class="error-message text-danger">{{ $errors->first('otp_code') }}</div>
                                    @endif
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="text-center">
                                    <!--begin::Submit button-->
                                    <button type="submit" id="kt_submit_code"
                                        class="btn btn-lg btn-dark w-100 mb-5">
                                        <span class="indicator-label">
                                            Submit
                                        </span>

                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                    <!--end::Submit button-->

                                    <!--begin::Link-->
                                    <div class="text-center text-gray-500 fw-semibold fs-6 mt-3">
                                        <a href="#" id="logoutBtn" class="link-dark fw-bolder">Logout</a>
                                    </div>
                                    <!--end::Link-->
                                    <div class="mt-4">
                                        <p class="text-black">If you haven't received your OTP, you can request it again in <span id="countdown">5:00</span></p>
                                        <button id="resend-btn" class="btn btn-secondary" disabled>Resend OTP</button>
                                    </div>

                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                </div>
            </div>
            <!--end::Right Content-->
        </div>
        <!--end::Authentication - Signup Free Trial-->
    </div>
    <!--end::Root-->
    <!--begin::Javascript-->

    @include('includes.logout')
    @include('includes.resent-otp-script')

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Attach event listeners to the form fields to clear error messages when the user starts typing
            document.querySelectorAll('input').forEach(function(input) {
                input.addEventListener('input', function() {
                    clearError(input.name); // Clear the error message when typing
                });
            });

            // Attach an event listener to the submit button
            document.getElementById("kt_submit_code").addEventListener("click", function(event) {
                event.preventDefault(); // Prevent form submission

                // Trigger the AJAX call
                submitForm();
            });
        });

        function submitForm() {
            // Clear previous error messages
            clearError('otp_code');

            // Get form values
            var otp_code = document.querySelector("input[name='otp_code']").value;

            // Validate the inputs
            var isValid = true;

            // otp_code validation
            if (!otp_code) {
                showError('otp_code', 'OTP code is required.');
                isValid = false;
            }

            if (!isValid) {
                return; // Stop form submission if validation fails
            }

            // Get CSRF token from the meta tag (make sure your blade template includes this if needed)
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Show spinner
            showSpinner(true);

            // Perform the AJAX request if validation passes
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('otp.verify.submit') }}", true); // Use your route for reset password
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken); // Add CSRF token in the headers

            
            xhr.onload = function() {
                showSpinner(false);
                var response;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (e) {
                    console.error("Error parsing JSON: ", e);
                    // Handle the case where the server returns HTML (like a 404 or 500 page)
                    Swal.fire({
                        text: "An error occurred. The server returned an unexpected response.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                    return; // Exit the function if parsing fails
                }
                
                if (xhr.status === 200 && response.success) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        timer: 2000,
                        // showConfirmButton: true,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    });

                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 2000);

                } else if (xhr.status === 409) {
                    // Handle email already registered error
                    Swal.fire({
                        text: response.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });

                } else if (xhr.status === 422) {
                    // Handle validation errors
                    var errorMessages = Object.values(response.errors).flat().join('\n');
                    Swal.fire({
                        text: "Validation failed:\n" + errorMessages,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });

                } else {
                    Swal.fire({
                        text: response.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                }
            };



            xhr.send(JSON.stringify({
                otp_code: otp_code
            }));
        }

        // Show the error message below the input field
        function showError(field, message) {
            var errorSpan = document.getElementById(field + '-error');
            if (errorSpan) {
                errorSpan.textContent = message;
            } else {
                var inputField = document.querySelector(`input[name="${field}"]`);
                var errorMsg = document.createElement("span");
                errorMsg.id = field + '-error';
                errorMsg.className = 'error-message text-danger';
                errorMsg.textContent = message;
                inputField.parentNode.appendChild(errorMsg);
            }
        }

        // Clear the error message when the user starts typing
        function clearError(field) {
            var errorSpan = document.getElementById(field + '-error');
            if (errorSpan) {
                errorSpan.textContent = '';
            }
        }

        // Function to show or hide the spinner
        function showSpinner(isVisible) {
            var button = document.getElementById("kt_submit_code");
            var progress = button.querySelector(".indicator-progress");
            var label = button.querySelector(".indicator-label");

            if (isVisible) {
                progress.style.display = "inline-block"; // Show the spinner
                label.style.display = "none"; // Hide the label
                button.disabled = true; // Disable the button
            } else {
                progress.style.display = "none"; // Hide the spinner
                label.style.display = "inline"; // Show the label
                button.disabled = false; // Enable the button
            }
        }
    </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->

    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
