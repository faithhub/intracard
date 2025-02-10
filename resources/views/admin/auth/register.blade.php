<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>Sign up auth Page</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Good admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
    <meta name="keywords"
        content="Good, bootstrap, bootstrap 5, admin themes, Asp.Net Core & Django starter kits, admin themes, bootstrap admin, bootstrap dashboard, bootstrap dark mode" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Sign up auth Page" />
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
                    <div class="flex-row-fluid d-flex flex-center justfiy-content-xl-first p-10">
                        <!--begin::Wrapper-->
                        <div class="d-flex">
                            <!--begin::Form-->
                            <form class="form" novalidate="novalidate" data-kt-redirect-url="" id="kt_sign_up_form"
                                action="{{ route('register-user') }}" method="POST">
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
                                    <h1 class="text-gray-900 mb-2 mt-3">
                                        Sign Up
                                    </h1>
                                    <!--end::Title-->

                                    <!--begin::Link-->
                                    {{-- <div class="text-gray-500 fw-semibold fs-4">
                                        Have questions ? Check out

                                        <a href="#" class="link-primary fw-bold">
                                            FAQ
                                        </a>.
                                    </div> --}}
                                    <!--end::Link-->
                                </div>
                                <!--begin::Heading-->

                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <label class="form-label fw-bold text-gray-900 fs-6">Email</label>
                                    <input class="form-control form-control-solid" type="email" placeholder=""
                                        name="email" autocomplete="off" />
                                    <span class="error-message text-danger" id="email-error"></span>
                                    @if ($errors->has('email'))
                                        <div class="error-message text-danger">{{ $errors->first('email') }}</div>
                                    @endif
                                    <!-- Error span for Email -->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-7 fv-row" data-kt-password-meter="true">
                                    <!--begin::Wrapper-->
                                    <div class="mb-1">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold text-gray-900 fs-6">
                                            Password
                                        </label>
                                        <!--end::Label-->

                                        <!--begin::Input wrapper-->
                                        <div class="position-relative mb-3">
                                            <input class="form-control form-control-solid" type="password"
                                                placeholder="" name="password" autocomplete="off" />
                                            <span class="error-message text-danger" id="password-error"></span>
                                            <!-- Error span for Password -->
                                            @if ($errors->has('password'))
                                                <div class="error-message text-danger">{{ $errors->first('password') }}
                                                </div>
                                            @endif
                                        </div>
                                        <!--end::Input wrapper-->

                                        <!--begin::Meter-->
                                        <div class="d-flex align-items-center mb-3"
                                            data-kt-password-meter-control="highlight">
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                            </div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                            </div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                            </div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                        </div>
                                        <!--end::Meter-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Hint-->
                                    <div class="text-muted">
                                        Use 8 or more characters with a mix of letters, numbers & symbols.
                                    </div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Input group--->

                                <!--begin::Row-->
                                <div class="fv-row mb-10">
                                    <label class="form-label fw-bold text-gray-900 fs-6">Confirm Password</label>
                                    <input class="form-control form-control-solid" type="password" placeholder=""
                                        name="password_confirmation" autocomplete="off" />
                                    <span class="error-message text-danger" id="password_confirmation-error"></span>
                                    <!-- Error span for Confirm Password -->
                                    @if ($errors->has('password_confirmation'))
                                        <div class="error-message text-danger">
                                            {{ $errors->first('password_confirmation') }}
                                        </div>
                                    @endif
                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="fv-row mb-8">
                                    <label class="form-check form-check-custom form-check-solid form-check-inline mb-5">
                                        <input class="form-check-input" type="checkbox" name="terms"
                                            value="1" />

                                        <span class="form-check-label fw-semibold text-gray-700">
                                            I Agree with the<a href="#" class="link-dark ms-1"><b>Terms
                                                    &
                                                    Conditions</b></a>.
                                        </span><br>
                                        <span class="error-message text-danger" id="terms-error"></span>
                                        @if ($errors->has('terms'))
                                            <div class="error-message text-danger">{{ $errors->first('terms') }}</div>
                                        @endif
                                    </label>
                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="text-center pb-lg-0 pb-2">
                                    <button type="submit" id="kt_register_new_user_submit"
                                        class="btn btn-lg btn-dark fw-bold">
                                        <span class="indicator-label">
                                            Create an Account
                                        </span>

                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                                <!--end::Row-->
                                <!--begin::Link-->
                                <div class="text-center text-gray-500 fw-semibold fs-7 mt-4">
                                    Already have an account?<br>
                                    <a href="{{ route('login') }}" class="link-dark fw-bolder">
                                        Sign In
                                    </a>
                                </div>
                                <!--end::Link-->
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
            document.getElementById("kt_register_new_user_submit").addEventListener("click", function(event) {
                event.preventDefault(); // Prevent form submission

                // Trigger the AJAX call
                submitForm();
            });
        });

        // Function to show or hide the spinner
        function showSpinner(isVisible) {
            var button = document.getElementById("kt_register_new_user_submit");
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


        function submitForm() {
            // Clear previous error messages
            clearError('email');
            clearError('password');
            clearError('password_confirmation');

            // Get form values
            var email = document.querySelector("input[name='email']").value;
            var password = document.querySelector("input[name='password']").value;
            var password_confirmation = document.querySelector("input[name='password_confirmation']").value;
            var terms = document.querySelector("input[name='terms']").checked;

            // Validate the inputs
            var isValid = true;

            if (!email) {
                showError('email', 'Email is required.');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('email', 'Please enter a valid email address.');
                isValid = false;
            }

            if (!password) {
                showError('password', 'Password is required.');
                isValid = false;
            } else if (!isValidPassword(password)) {
                showError('password',
                    'Password must be at least 8 characters long and contain letters, numbers, and symbols.');
                isValid = false;
            }

            if (!password_confirmation) {
                showError('password_confirmation', 'Confirm password is required.');
                isValid = false;
            }

            if (password !== password_confirmation) {
                showError('password_confirmation', 'Passwords do not match.');
                isValid = false;
            }

            // Terms and conditions checkbox validation
            if (!terms) {
                showError('terms', 'You must agree to the Terms & Conditions.');
                isValid = false;
            }

            if (!isValid) {
                return; // Stop form submission if validation fails
            }


            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            //Show spinner
            showSpinner(true);

            // Perform the AJAX request if validation passes
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('register-user') }}", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken); // Add CSRF token in the headers

            // xhr.onload = function() {
            //     // console.log(xhr);
            //     // Hide spinner after response is received
            //     showSpinner(false);
            //     // var response = JSON.parse(xhr.responseText);

            //     var response = JSON.parse(xhr.responseText);

            //     if (xhr.status === 201 && response.success) {
            //         Swal.fire({
            //             text: response.message,
            //             icon: "success",
            //             buttonsStyling: false,
            //             timer: 5000,
            //             showConfirmButton: false,
            //             customClass: {
            //                 confirmButton: "btn btn-primary"
            //             }
            //         });

            //         setTimeout(function() {
            //             window.location.href = response.redirect_url;
            //         }, 5000);

            //     }


            //     if (xhr.status === 201) {
            //         console.log("Success: ", response.message);

            //         // Display SweetAlert success message for 5 seconds
            //         Swal.fire({
            //             text: response.message,
            //             icon: "success",
            //             buttonsStyling: false,
            //             timer: 5000, // Set SweetAlert to automatically close after 5 seconds
            //             showConfirmButton: false,
            //             customClass: {
            //                 confirmButton: "btn btn-primary"
            //             }
            //         });

            //         // Redirect to login page after 5 seconds
            //         setTimeout(function() {
            //             // window.location.href = xhr.redirect_url; // Adjust this route to your login page route
            //         }, 5000);

            //     } else {
            //         console.error("Error: ", response.message);
            //         // Handle any HTTP errors
            //         Swal.fire({
            //             text: "An error occurred. Please try again later." + response.message,
            //             icon: "error",
            //             buttonsStyling: false,
            //             confirmButtonText: "Ok, got it!",
            //             customClass: {
            //                 confirmButton: "btn btn-danger"
            //             }
            //         });
            //     }
            // };

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

                if (xhr.status === 201 && response.success) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        timer: 5000,
                        showConfirmButton: true,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    });

                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 5000);

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
                        text: "An error occurred: " + response.message,
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
                email: email,
                password: password,
                password_confirmation: password_confirmation,
                terms: terms
            }));

        }

        // Show the error message below the input field
        function showError(field, message) {
            var errorSpan = document.getElementById(field + '-error');
            if (errorSpan) {
                errorSpan.textContent = message;
            }
        }

        // Clear the error message when the user starts typing
        function clearError(field) {
            var errorSpan = document.getElementById(field + '-error');
            if (errorSpan) {
                errorSpan.textContent = '';
            }
        }

        // Function to validate password: 8+ characters, letters, numbers, and symbols
        function isValidPassword(password) {
            var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*+?&])[A-Za-z\d@$!%*+?&]{8,}$/;
            return regex.test(password);
        }

        // Function to validate email using a regular expression
        function isValidEmail(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->


    <!--begin::Custom Javascript(used for this page only)-->
    {{-- <script src="{{ asset('assets/js/custom/authentication/sign-up/free-trial.js') }}"></script> --}}
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
