<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>Sign up Auth Page</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Good admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
    <meta name="keywords"
        content="Good, bootstrap, bootstrap 5, admin themes, Asp.Net Core & Django starter kits, admin themes, bootstrap admin, bootstrap dashboard, bootstrap dark mode" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Sign up Auth Page" />
    <meta property="og:url"
        content="https://themes.getbootstrap.com/product/good-bootstrap-5-admin-dashboard-template" />
    <meta property="og:site_name" content="Good by Keenthemes" />
    <link rel="canonical" href="https://preview.keenthemes.com/good/authentication/sign-up/multi-steps.html" />
    <link rel="shortcut icon" href="/good/assets/media/logos/favicon.ico" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->



    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href='https://fonts.googleapis.com/css?family=Kodchasan' rel='stylesheet'>
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="app-blank">

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Multi-steps-->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column"
            id="kt_create_account_stepper">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto w-xl-400px positon-xl-relative bg-light">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-400px scroll-y">
                    <!--begin::Header-->
                    <div class="d-flex flex-row-fluid flex-column align-items-center align-items-lg-start p-10 p-lg-20">
                        <!--begin::Logo-->
                        <a href="/good/index.html" class="mb-10 mb-lg-20">
                            <img alt="Logo" src="{{ asset('assets/media/logos/default.svg') }}" class="h-30px" />
                        </a>
                        <!--end::Logo-->
                    </div>
                    <!--end::Header-->

                    <!--begin::Illustration-->
                    <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-250px"
                        style="background-image: url(/good/assets/media/illustrations/sketchy-1/16.png)">
                    </div>
                    <!--end::Illustration-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--begin::Aside-->
            <style>
                [data-kt-stepper-element="content"] {
                    display: none;
                    /* Hide all steps by default */
                }

                [data-kt-stepper-element="content"].active {
                    display: block;
                    /* Show only the active step */
                }
            </style>
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-800px p-10 p-lg-15 mx-auto">
                        <!--begin::Form-->
                        <form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form">
                            <!-- Outer Stepper (Main Steps) -->
                            <div id="outer-stepper">
                                <!-- Step 1 -->
                                <div data-kt-stepper-element="content" class="active">
                                    <h2>Step 1: Personal Information</h2>
                                    <div id="step1-inner-stepper">
                                        <div data-kt-stepper-element="content" class="active">
                                            <input type="text" placeholder="First Name" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                        </div>
                                        <div data-kt-stepper-element="content">
                                            <input type="text" placeholder="Last Name" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                            <button type="button" data-kt-stepper-action="previous">Previous</button>
                                        </div>
                                        <div data-kt-stepper-element="content">
                                            <input type="date" placeholder="Date of Birth" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                            <button type="button" data-kt-stepper-action="previous">Previous</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div data-kt-stepper-element="content">
                                    <h2>Step 2: Contact Information</h2>
                                    <div id="step2-inner-stepper">
                                        <div data-kt-stepper-element="content" class="active">
                                            <input type="email" placeholder="Email Address" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                        </div>
                                        <div data-kt-stepper-element="content">
                                            <input type="tel" placeholder="Phone Number" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                            <button type="button" data-kt-stepper-action="previous">Previous</button>
                                        </div>
                                        <div data-kt-stepper-element="content">
                                            <input type="text" placeholder="Address" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                            <button type="button" data-kt-stepper-action="previous">Previous</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div data-kt-stepper-element="content">
                                    <h2>Step 3: Account Details</h2>
                                    <div id="step3-inner-stepper">
                                        <div data-kt-stepper-element="content" class="active">
                                            <input type="text" placeholder="Username" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                        </div>
                                        <div data-kt-stepper-element="content">
                                            <input type="password" placeholder="Password" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                            <button type="button" data-kt-stepper-action="previous">Previous</button>
                                        </div>
                                        <div data-kt-stepper-element="content">
                                            <input type="password" placeholder="Confirm Password" required />
                                            <button type="button" data-kt-stepper-action="next">Next</button>
                                            <button type="button" data-kt-stepper-action="previous">Previous</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div data-kt-stepper-element="content">
                                    <h2>Step 4: Review & Submit</h2>
                                    <div id="step4-inner-stepper">
                                        <div data-kt-stepper-element="content" class="active">
                                            <p>Review your details.</p>
                                            <button type="button" data-kt-stepper-action="next">Proceed to
                                                Confirmation</button>
                                        </div>
                                        <div data-kt-stepper-element="content">
                                            <p>Confirm all details are correct.</p>
                                            <button type="submit">Submit</button>
                                            <button type="button" data-kt-stepper-action="previous">Previous</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation for Outer Stepper -->
                                <button type="button" data-kt-stepper-action="previous">Previous</button>
                                <button type="button" data-kt-stepper-action="next">Next</button>
                            </div>

                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Multi-steps-->
    </div>
    <!--end::Root-->

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/create-account.js') }}"></script> --}}
    <!--end::Javascript-->
    <script>
        var MultiStepForm = function() {
            var outerStepper, innerSteppers = [],
                validationSchemas = [];

            return {
                init: function() {
                    // Initialize the Outer Stepper
                    outerStepper = new KTStepper(document.querySelector("#outer-stepper"));

                    // Define each inner stepper by ID
                    innerSteppers = [
                        new KTStepper(document.getElementById('step1-inner-stepper')),
                        new KTStepper(document.getElementById('step2-inner-stepper')),
                        new KTStepper(document.getElementById('step3-inner-stepper')),
                        new KTStepper(document.getElementById('step4-inner-stepper'))
                    ];

                    // Initialize validation schemas for each inner step
                    validationSchemas = [
                        FormValidation.formValidation(document.getElementById('step1-inner-stepper'), {
                            fields: {
                                firstName: {
                                    validators: {
                                        notEmpty: {
                                            message: "First Name is required"
                                        }
                                    }
                                }
                            },
                            plugins: {
                                trigger: new FormValidation.plugins.Trigger(),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: ".fv-row",
                                    eleInvalidClass: "",
                                    eleValidClass: ""
                                })
                            }
                        }),
                        FormValidation.formValidation(document.getElementById('step2-inner-stepper'), {
                            fields: {
                                email: {
                                    validators: {
                                        notEmpty: {
                                            message: "Email is required"
                                        },
                                        emailAddress: {
                                            message: "Invalid email address"
                                        }
                                    }
                                }
                            },
                            plugins: {
                                trigger: new FormValidation.plugins.Trigger(),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: ".fv-row",
                                    eleInvalidClass: "",
                                    eleValidClass: ""
                                })
                            }
                        }),
                        FormValidation.formValidation(document.getElementById('step3-inner-stepper'), {
                            fields: {
                                username: {
                                    validators: {
                                        notEmpty: {
                                            message: "Username is required"
                                        }
                                    }
                                }
                            },
                            plugins: {
                                trigger: new FormValidation.plugins.Trigger(),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: ".fv-row",
                                    eleInvalidClass: "",
                                    eleValidClass: ""
                                })
                            }
                        }),
                        FormValidation.formValidation(document.getElementById('step4-inner-stepper'), {
                            fields: {
                                cardName: {
                                    validators: {
                                        notEmpty: {
                                            message: "Name on card is required"
                                        }
                                    }
                                }
                            },
                            plugins: {
                                trigger: new FormValidation.plugins.Trigger(),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: ".fv-row",
                                    eleInvalidClass: "",
                                    eleValidClass: ""
                                })
                            }
                        })
                    ];

                    // Outer stepper control logic
                    outerStepper.on("kt.stepper.next", function(e) {
                        let stepIndex = outerStepper.getCurrentStepIndex() - 1;

                        // Validate the current step's form
                        if (validationSchemas[stepIndex]) {
                            validationSchemas[stepIndex].validate().then(function(status) {
                                if (status === 'Valid') {
                                    e.goNext();
                                    KTUtil.scrollTop();
                                } else {
                                    Swal.fire({
                                        text: "Please fix the errors to proceed.",
                                        icon: "error",
                                        confirmButtonText: "Ok"
                                    });
                                }
                            });
                        } else {
                            e.goNext(); // Proceed if there's no validation schema for the current step
                            KTUtil.scrollTop();
                        }
                    });

                    outerStepper.on("kt.stepper.previous", function(e) {
                        e.goPrevious();
                        KTUtil.scrollTop();
                    });

                    // Inner stepper navigation and validation
                    innerSteppers.forEach(function(innerStepper, index) {
                        innerStepper.on("kt.stepper.next", function(e) {
                            if (validationSchemas[index]) {
                                validationSchemas[index].validate().then(function(status) {
                                    if (status === 'Valid') {
                                        e.goNext();
                                    } else {
                                        Swal.fire({
                                            text: "Please fill out all required fields.",
                                            icon: "error",
                                            confirmButtonText: "Ok"
                                        });
                                    }
                                });
                            } else {
                                e.goNext();
                            }
                        });

                        innerStepper.on("kt.stepper.previous", function(e) {
                            e.goPrevious();
                        });
                    });
                }
            };
        }();

        // Initialize on document load
        KTUtil.onDOMContentLoaded(function() {
            MultiStepForm.init();
        });
    </script>
</body>
<!--end::Body-->

</html>
