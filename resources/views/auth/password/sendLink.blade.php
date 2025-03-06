<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>Intracard || Reset PAssword</title>
    <meta name="description"
    content="Intracard is the secure and reliable platform for paying rent and mortgages effortlessly. Experience convenience with cutting-edge encryption for peace of mind.">
<meta name="keywords"
    content="Intracard, rent payment, mortgage payment, secure payments, financial platform, encryption, payment solutions">
<meta name="author" content="Intracard Team">

<!-- Open Graph Meta Tags (for social media sharing) -->
<meta property="og:title" content="Intracard | Secure Rent & Mortgage Payments">
<meta property="og:description"
    content="Simplify your rent and mortgage payments with Intracard's secure and user-friendly platform.">
<meta property="og:image" content="https://yourwebsite.com/assets/images/og-image.png">
<!-- Replace with your image URL -->
<meta property="og:url" content="https://yourwebsite.com">
<meta property="og:type" content="website">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Intracard | Secure Rent & Mortgage Payments">
<meta name="twitter:description"
    content="Make rent and mortgage payments hassle-free with Intracard's secure platform.">
<meta name="twitter:image" content="https://yourwebsite.com/assets/images/twitter-card-image.png">
<!-- Replace with your image URL -->

<!-- Favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
<!-- Replace with your favicon URL -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
<!-- Replace with your favicon URL -->
<link rel="manifest" href="https://yourwebsite.com/manifest.json">

<!-- Robots -->
<meta name="robots" content="index, follow">

<!-- Additional Meta Tags -->
<link rel="canonical" href="https://yourwebsite.com">


<meta name="csrf-token" content="{{ csrf_token() }}">
<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->
<link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
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
                                action="{{ route('password.email') }}" method="POST">
                                @csrf

                                <!--begin::Heading-->
                                <div class="text-center mb-10">
                                    <!--begin::Logo-->
                                    <a href="" class="mb-15">
                                        <img alt="Logo" src="{{ asset('assets/logos/intracard.png') }}"
                                            class="h-80px" />
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
                        title: 'Success!',
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        timer: 2000,
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
                        text: response.message || 'Something went wrong. Please try again later.',
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
