<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>Good – Bootstrap 5 HTML Asp.Net Core, Blazor, Django & Flask Admin Dashboard Template by KeenThemes</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Good admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
    <meta name="keywords"
        content="Good, bootstrap, bootstrap 5, admin themes, Asp.Net Core & Django starter kits, admin themes, bootstrap admin, bootstrap dashboard, bootstrap dark mode" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Good – Bootstrap 5 HTML Asp.Net Core, Blazor, Django & Flask Admin Dashboard Template by KeenThemes" />
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

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-52YZ3XGZJ6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-52YZ3XGZJ6');
    </script>
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
                                action="{{ route('login-user') }}" method="POST">
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
                                    <h1 class="text-gray-900 mb-3 mt-3">Reset Password</h1>
                                    <!--end::Title-->
                                    <div class="text-gray-500 fw-semibold fs-4">
                                        Enter your email address below. You will receive a link to reset your password.
                                    </div>

                                </div>
                                <!--begin::Heading-->

                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fs-6 fw-bold text-gray-900">Email</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid" type="text"
                                        name="email" autocomplete="off" />
                                    <!--end::Input-->
                                    <span class="error-message text-danger" id="email-error"></span>
                                    @if ($errors->has('email'))
                                        <div class="error-message text-danger">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="text-center">
                                    <!--begin::Submit button-->
                                    <button type="submit" id="kt_send_link_submit"
                                        class="btn btn-lg btn-dark w-100 mb-5">
                                        <span class="indicator-label">
                                            Reset Password
                                        </span>

                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                    <!--end::Submit button-->

                                    <!--begin::Link-->
                                    <div class="text-center text-gray-500 fw-semibold fs-6 mt-3">
                                        Password recovered?
                                        <a href="{{ route('login') }}" class="link-dark fw-bolder">
                                            Sign In
                                        </a>
                                    </div>
                                    <!--end::Link-->

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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Attach event listeners to the form fields to clear error messages when the user starts typing
            document.querySelectorAll('input').forEach(function(input) {
                input.addEventListener('input', function() {
                    clearError(input.name); // Clear the error message when typing
                });
            });
    
            // Attach an event listener to the submit button
            document.getElementById("kt_send_link_submit").addEventListener("click", function(event) {
                event.preventDefault(); // Prevent form submission
    
                // Trigger the AJAX call
                submitForm();
            });
        });
    
        function submitForm() {
            // Clear previous error messages
            clearError('email');
    
            // Get form values
            var email = document.querySelector("input[name='email']").value;
    
            // Validate the inputs
            var isValid = true;
    
            // Email validation
            if (!email) {
                showError('email', 'Email is required.');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('email', 'Please enter a valid email address.');
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
            xhr.open("POST", "{{ route('password.email') }}", true);  // Use your route for reset password
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken); // Add CSRF token in the headers
    
            xhr.onload = function() {
                // Hide spinner after response is received
                showSpinner(false);
                if (xhr.status === 200) {
                    // Handle success response
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert('Reset password link sent to your email.');
                    } else {
                        showError('email', response.message);
                    }
                } else {
                    // Handle error response
                    var response = JSON.parse(xhr.responseText);
                    showError('email', response.message);
                }
            };
    
            xhr.send(JSON.stringify({
                email: email
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
            var button = document.getElementById("kt_send_link_submit");
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
    
        // Function to validate email using a regular expression
        function isValidEmail(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    </script>
    
    <script src="{{ asset('assets/js/csrf-refresh.js') }}"></script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
