<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>Sign up Auth Page</title>
    <meta charset="utf-8" />
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
    <div class="container">
        <!-- Main Stepper -->
        <div id="main_stepper" class="stepper">
            <!-- Main Step 1 -->
            <div class="stepper-item" data-kt-stepper-element="content">
                <h2>Main Step 1: Account Information</h2>
                <form id="form_step_1">
                    <div>
                        <label for="account_type">Account Type:</label>
                        <input type="text" name="account_type" id="account_type" required>
                    </div>

                    <!-- Nested Stepper within Main Step 1 -->
                    <div id="nested_stepper_1" class="nested-stepper">
                        <div class="nested-step" data-kt-stepper-element="content">
                            <h3>Nested Step 1.1</h3>
                            <label for="nested_info_1">Nested Info 1:</label>
                            <input type="text" name="nested_info_1" id="nested_info_1" required>
                            <button type="button" data-kt-stepper-action="next-nested">Next Nested Step</button>
                        </div>
                        <div class="nested-step" data-kt-stepper-element="content">
                            <h3>Nested Step 1.2</h3>
                            <label for="nested_info_2">Nested Info 2:</label>
                            <input type="text" name="nested_info_2" id="nested_info_2" required>
                            <button type="button" data-kt-stepper-action="prev-nested">Previous</button>
                            <button type="button" data-kt-stepper-action="next-nested">Next Nested Step</button>
                        </div>
                        <div class="nested-step" data-kt-stepper-element="content">
                            <h3>Nested Step 1.3</h3>
                            <label for="nested_info_3">Nested Info 3:</label>
                            <input type="text" name="nested_info_3" id="nested_info_3" required>
                            <button type="button" data-kt-stepper-action="prev-nested">Previous</button>
                        </div>
                    </div>

                    <button type="button" data-kt-stepper-action="next">Next Main Step</button>
                </form>
            </div>

            <!-- Main Step 2 -->
            <div class="stepper-item" data-kt-stepper-element="content">
                <h2>Main Step 2: Payment Details</h2>
                <form id="form_step_2">
                    <label for="card_number">Card Number:</label>
                    <input type="text" name="card_number" id="card_number" required>
                    <button type="button" data-kt-stepper-action="prev">Previous Main Step</button>
                    <button type="button" data-kt-stepper-action="next">Next Main Step</button>
                </form>
            </div>

            <!-- Main Step 3: Final Confirmation -->
            <div class="stepper-item" data-kt-stepper-element="content">
                <h2>Main Step 3: Review & Submit</h2>
                <p>Please review your information before submitting.</p>
                <button type="button" data-kt-stepper-action="prev">Previous Main Step</button>
                <button type="submit">Submit</button>
            </div>
        </div>
    </div>

    <!--end::Root-->

    <!--begin::Javascript-->
    <script>
       document.addEventListener('DOMContentLoaded', () => {
    // Initialize main stepper
    const mainStepper = document.getElementById('main_stepper');
    const mainSteps = Array.from(mainStepper.querySelectorAll('.stepper-item'));
    let currentMainStep = 0;

    // Initialize nested steppers
    const nestedSteppers = Array.from(document.querySelectorAll('.nested-stepper'));
    const nestedStepsIndex = {}; // Tracks the current step for each nested stepper

    nestedSteppers.forEach((nestedStepper, index) => {
        nestedStepsIndex[`nested_stepper_${index + 1}`] = 0;
        setupNestedStepper(nestedStepper, `nested_stepper_${index + 1}`);
    });

    // Show the initial main step
    showMainStep(currentMainStep);

    // Main Step navigation functions
    document.querySelectorAll('[data-kt-stepper-action="next"]').forEach(button => {
        button.addEventListener('click', () => {
            if (validateForm(`form_step_${currentMainStep + 1}`)) {
                currentMainStep = Math.min(mainSteps.length - 1, currentMainStep + 1);
                showMainStep(currentMainStep);
            }
        });
    });

    document.querySelectorAll('[data-kt-stepper-action="prev"]').forEach(button => {
        button.addEventListener('click', () => {
            currentMainStep = Math.max(0, currentMainStep - 1);
            showMainStep(currentMainStep);
        });
    });

    function showMainStep(index) {
        mainSteps.forEach((step, i) => step.style.display = i === index ? 'block' : 'none');
    }

    function validateForm(formId) {
        const form = document.getElementById(formId);
        return form.checkValidity();
    }

    // Nested Step navigation setup
    function setupNestedStepper(nestedStepper, stepperId) {
        const nestedSteps = Array.from(nestedStepper.querySelectorAll('.nested-step'));
        showNestedStep(stepperId, 0);

        nestedStepper.querySelectorAll('[data-kt-stepper-action="next-nested"]').forEach(button => {
            button.addEventListener('click', () => {
                if (validateForm(nestedStepper.id)) {
                    nestedStepsIndex[stepperId] = Math.min(nestedSteps.length - 1, nestedStepsIndex[stepperId] + 1);
                    showNestedStep(stepperId, nestedStepsIndex[stepperId]);
                }
            });
        });

        nestedStepper.querySelectorAll('[data-kt-stepper-action="prev-nested"]').forEach(button => {
            button.addEventListener('click', () => {
                nestedStepsIndex[stepperId] = Math.max(0, nestedStepsIndex[stepperId] - 1);
                showNestedStep(stepperId, nestedStepsIndex[stepperId]);
            });
        });
    }

    function showNestedStep(stepperId, index) {
        const nestedStepper = document.getElementById(stepperId);
        const nestedSteps = Array.from(nestedStepper.querySelectorAll('.nested-step'));
        nestedSteps.forEach((step, i) => step.style.display = i === index ? 'block' : 'none');
    }
});

    </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    {{-- <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/create-account.js') }}"></script> --}}
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
