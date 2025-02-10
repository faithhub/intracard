document.addEventListener("DOMContentLoaded", () => {
    // Select the radio buttons
    const rentOption = document.getElementById(
        "kt_create_account_form_account_type_personal"
    );
    const mortgageOption = document.getElementById(
        "kt_create_account_form_account_type_corporate"
    );

    // Select the elements where text changes will occur
    const rentFinance = document.getElementById("rentFinance"); // For changing the main title text
    const mortgageFinance = document.getElementById("mortgageFinance"); // For changing the main title text
    const uploadAgreement = document.getElementById("uploadAgreement"); // For changing the main title text
    const rentMortgageAmount = document.getElementById("rentMortgageAmount"); // For changing the main title text
    const RentalMortAddress = document.getElementById("RentalMortAddress"); // For changing the main title text
    const RentalMortAddressMuted = document.getElementById(
        "RentalMortAddressMuted"
    ); // For changing the main title text
    const AddressNameDiv = document.getElementById("AddressNameDiv"); // For changing the main title text
    const landlordDetails2 = document.getElementById("landlordDetails2"); // For changing the main title text
    const landLoradSmall2 = document.getElementById("landLoradSmall2"); // For changing the subtitle text
    const landlordDetails = document.getElementById("landlordDetails"); // For changing the main title text
    const landLoradSmall = document.getElementById("landLoradSmall"); // For changing the subtitle text

    // Function to handle the selection change
    function updateContentBasedOnSelection(event) {
        const selectedValue = event.target.value;

        if (selectedValue === "rent") {
            console.log("Rent option selected");
            rentFinance.style.display = "block";
            mortgageFinance.style.display = "none";
            uploadAgreement.textContent = "Upload tenancy agreement";
            rentMortgageAmount.textContent = "Rent Amount";
            RentalMortAddress.textContent = "What's your rental address?";
            RentalMortAddressMuted.textContent =
                " Enter your rental address and unit number if applicable..";
            AddressNameDiv.textContent = "Setup your rental address";
            landlordDetails.textContent = "Landlord Details";
            landLoradSmall.textContent = "How does your landlord accept rent?";
            landlordDetails2.textContent = "Landlord Details";
            landLoradSmall2.textContent = "Setup your landlord details";
        } else if (selectedValue === "mortgage") {
            console.log("Mortgage option selected");
            rentFinance.style.display = "none";
            mortgageFinance.style.display = "block";
            uploadAgreement.textContent = "Upload mortgage agreement";
            rentMortgageAmount.textContent = "Mortgage Amount";
            RentalMortAddress.textContent = "What's your mortgage address?";
            RentalMortAddressMuted.textContent =
                " Enter your mortgage and unit number if applicable..";
            AddressNameDiv.textContent = "Setup your mortgage address";
            landlordDetails.textContent = "Mortgage Financer";
            landLoradSmall.textContent =
                "How does your mortgage financer accept payment?";
            landlordDetails2.textContent = "Mortgage Financer";
            landLoradSmall2.textContent =
                "Setup your mortgage financer details";
        }

        // Debugging to check the changes
        // console.log(`Main Text: ${landlordDetails.textContent}`);
        // console.log(`Sub Text: ${landLoradSmall.textContent}`);
    }

    // Attach the event listeners to each radio button
    if (rentOption)
        rentOption.addEventListener("change", updateContentBasedOnSelection);
    if (mortgageOption)
        mortgageOption.addEventListener(
            "change",
            updateContentBasedOnSelection
        );
});

document.addEventListener("DOMContentLoaded", function () {
    const paymentFrequencySelect = document.getElementById(
        "paymentFrequencySelect"
    );
    const biWeeklyDueDateDiv = document.getElementById("biWeeklyDueDateDiv");
    const biWeeklyDueDateInput = document.getElementById(
        "biWeeklyDueDateInput"
    );

    // Function to toggle visibility and add/remove data-required
    function toggleBiWeeklyDueDate() {
        if (paymentFrequencySelect.value === "Bi-weekly") {
            // Show the input field and add the "data-required" attribute
            biWeeklyDueDateDiv.style.display = "block";
            biWeeklyDueDateInput.setAttribute("data-required", "true");
        } else {
            // Hide the input field and remove the "data-required" attribute
            biWeeklyDueDateDiv.style.display = "none";
            biWeeklyDueDateInput.removeAttribute("data-required");
        }
    }

    // Call the function on page load to handle the initial state
    toggleBiWeeklyDueDate();

    // Add event listener to handle change in payment frequency
    paymentFrequencySelect.addEventListener("change", toggleBiWeeklyDueDate);
});


let phoneGeneratedCode = "";
// let emailVerified = false;
// let phoneVerified = false;

let isEmailVerified = false;
let isPhoneVerified = false;
// JavaScript to handle requirements
let generatedCode = "";
let countdownInterval; // To hold the interval timer

const usePersistentVerification = false; // Set to false to disable persistence

// Function to store verification details in sessionStorage
function storeVerificationDetails(type, value) {
    if (!usePersistentVerification) return;

    if (type === "email") {
        sessionStorage.setItem("storedEmail", value);
    } else if (type === "phone") {
        sessionStorage.setItem("storedPhone", value);
    }
}

// Function to validate Canadian phone number format
function validateCanadianPhoneNumber() {
    const phoneInput = document.getElementById("phoneInput");

    // Only allow numeric input
    phoneInput.value = phoneInput.value.replace(/\D/g, "");

    // Limit input to 10 digits
    if (phoneInput.value.length > 10) {
        phoneInput.value = phoneInput.value.slice(0, 10);
    }
}

// Function to add additional credit card inputs
let cardCount = 0; // Track the number of additional cards

let typingTimer; // Timer identifier
const typingDelay = 500; // Delay in milliseconds (0.5 seconds)

// Function to handle the visibility of card limit and due date
// function updateCardFieldsVisibility() {
//     const rentPlan = document.querySelector(
//         'input[name="account_plan_rent"]:checked'
//     );
//     const mortgagePlan = document.querySelector(
//         'input[name="account_plan_mortgage"]:checked'
//     );

//     // Determine if we need to show the fields based on the selected option
//     const showFields =
//         (rentPlan && rentPlan.value === "pay_rent_build") ||
//         (mortgagePlan && mortgagePlan.value === "pay_mortgage_build");

//     // Update a global variable or class to track visibility
//     document.body.dataset.showCardFields = showFields ? "true" : "false";
// }

// Attach event listeners to radio buttons
// document
//     .querySelectorAll('input[name="account_plan_rent"]')
//     .forEach((radio) => {
//         radio.addEventListener("change", updateCardFieldsVisibility);
//     });

// document
//     .querySelectorAll('input[name="account_plan_mortgage"]')
//     .forEach((radio) => {
//         radio.addEventListener("change", updateCardFieldsVisibility);
//     });




function formatAndValidateFourDigitInput(input) {
    // Remove all non-digit characters
    input.value = input.value.replace(/\D/g, "");
}


function showToast2(message, type) {
    const toastContainer = document.getElementById("toastContainer");

    // Create a new toast element
    const toast = document.createElement("div");
    toast.className = `toast align-items-center text-bg border-0`;
    //  toast.className = `toast align-items-center text-bg-${type} border-0`;
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    // Define icons and styles based on type
    let icon, backgroundColor, color, borderColor;

    if (type === "success") {
        icon = "✔️";
        // toast.style.backgroundColor = '#e6f7e9';
        color = "#28a745";
        // borderColor = '#28a745';
    } else if (type === "error") {
        icon = "❌";
        // toast.style.backgroundColor = 'rgb(237 113 98 / 80%)';
        color = "#d93025";
        // borderColor = '#d93025';
    } else if (type === "info") {
        icon = "ℹ️";
        // toast.style.backgroundColor = '#e3f2fd';
        color = "#007bff";
        // borderColor = '#007bff';
    } else if (type === "warning") {
        icon = "⚠️";
        // toast.style.backgroundColor = '#fff4e5';
        color = "#ffc107";
        // borderColor = '#ffc107';
    }

    // Define the toast title and background color based on the type
    const title = type.charAt(0).toUpperCase() + type.slice(1); // Capitalize first letter

    // Toast content with close button and auto-dismiss setup
    toast.innerHTML = `
         <div class="toast-header">
             <span class="me-2">${icon}</span>
             <strong class="me-auto">${title}</strong>
             <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
         <div class="toast-body">
             ${message}
         </div>
     `;

    //      toast.innerHTML = `<div class="d-flex">
    //     <div class="toast-body">
    //     ${icon}  ${message}
    //     </div>
    //   </div>`;

    // Append the toast to the container and initialize it
    toastContainer.appendChild(toast);
    const bootstrapToast = new bootstrap.Toast(toast, {
        delay: 3000,
        autohide: true,
    }); // Set autohide with delay
    bootstrapToast.show();

    // Automatically remove the toast element after it hides
    toast.addEventListener("hidden.bs.toast", () => {
        toast.remove();
    });
}

function showToast(message, type) {
    const toastContainer = document.getElementById("toastContainer");

    // Create a new toast element
    const toast = document.createElement("div");
    toast.className = `toast align-items-center border-0`;
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    // Define icons and styles based on type
    let icon, backgroundColor, textColor, borderColor;

    if (type === "success") {
        icon = "✔️";
        backgroundColor = "#e6f7e9";
        textColor = "#28a745";
        borderColor = "#28a745";
    } else if (type === "error") {
        icon = "❌";
        backgroundColor = "#f8d7da";
        textColor = "#d93025";
        borderColor = "#d93025";
    } else if (type === "info") {
        icon = "ℹ️";
        backgroundColor = "#e3f2fd";
        textColor = "#007bff";
        borderColor = "#007bff";
    } else if (type === "warning") {
        icon = "⚠️";
        backgroundColor = "#fff4e5";
        textColor = "#ffc107";
        borderColor = "#ffc107";
    }

    // Define the toast title
    const title = type.charAt(0).toUpperCase() + type.slice(1); // Capitalize first letter

    // Toast content
    toast.innerHTML = `
        <div class="toast-header" style="background-color: ${backgroundColor}; border-left: 4px solid ${borderColor};">
            <span class="me-2" style="color: ${textColor}; font-size: 1.2rem;">${icon}</span>
            <strong class="me-auto" style="color: ${textColor};">${title}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" style="background-color: ${backgroundColor}; color: ${textColor};">
            ${message}
        </div>
    `;

    // Append the toast to the container and initialize it
    toastContainer.appendChild(toast);
    const bootstrapToast = new bootstrap.Toast(toast, {
        delay: 3000,
        autohide: true,
    }); // Set autohide with delay
    bootstrapToast.show();

    // Automatically remove the toast element after it hides
    toast.addEventListener("hidden.bs.toast", () => {
        toast.remove();
    });
}


// Countdown Function
function startCountdown(button, type) {
    const currentEmail = document.getElementById("emailInputField").value;
    const verifiedEmail = sessionStorage.getItem("verifiedEmail");

    // Skip countdown if the current email is already verified
    if (verifiedEmail === currentEmail) {
        button.innerText = "Verified";
        button.disabled = true;
        return;
    }

    let timeRemaining = 4 * 60; // 4 minutes in seconds
    button.disabled = true;

    const countdownInterval = setInterval(() => {
        const verifiedEmailDuringCountdown =
            sessionStorage.getItem("verifiedEmail");

        // Stop countdown if the current email gets verified
        if (verifiedEmailDuringCountdown === currentEmail) {
            clearInterval(countdownInterval);
            button.innerText = "Verified";
            button.disabled = true;
            return;
        }

        // Update button text with countdown
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        button.innerText = `Resend in ${minutes}:${seconds < 10 ? "0" : ""
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

// Function to Send Email Verification Code
async function sendEmailCode() {
    if (sessionStorage.getItem("verifiedEmail")) {
        sessionStorage.removeItem("verifiedEmail");
    }
    const email = document.getElementById("emailInputField").value;
    const getCodeButton = document.getElementById("getCodeButton");
    const spinner = document.querySelector("#getCodeButton #spinner");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // Check if email is already verified
    const verifiedEmail = sessionStorage.getItem("verifiedEmail");
    if (verifiedEmail === email) {
        showToast("This email is already verified.", "success");
        getCodeButton.innerText = "Verified";
        getCodeButton.disabled = true;
        return; // Exit the function as no further action is needed
    }

    if (!email || !/^[\w.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
        showToast("Please enter a valid email address.", "error");
        return;
    }

    try {
        spinner.style.display = "inline-block";
        getCodeButton.disabled = true;

        const response = await fetch(checkEmailExists, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ email }),
        });

        const result = await response.json();

        if (result.status === "error") {
            showToast(result.message, "error");
            spinner.style.display = "none";
            getCodeButton.disabled = false;
            return;
        }

        showToast("Email is available. Proceeding...", "success");

        const sendEmailResponse = await fetch(sendEmailVerificationCodeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ email }),
        });

        const sendEmailResult = await sendEmailResponse.json();

        if (sendEmailResponse.ok) {
            showToast(sendEmailResult.message, "success");
            startCountdown(getCodeButton, "email");
            document.getElementById("emailValidationSection").style.display =
                "block"; // Show code input section only if the email is not verified
        } else {
            showToast(
                sendEmailResult.message || "Failed to send code.",
                "error"
            );
        }
    } catch (error) {
        showToast("An unexpected error occurred.", "error");
        console.error(error);
    } finally {
        spinner.style.display = "none";
    }
}

// Function to Validate Email Code
async function validateEmailCode() {
    const email = document.getElementById("emailInputField").value;
    const enteredCode = document.getElementById("emailCodeInput").value;
    const validateButton = document.getElementById("validateEmailCodeButton");
    const getCodeButton = document.getElementById("getCodeButton");
    const spinner = document.getElementById("validationSpinner");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    if (!enteredCode || enteredCode.length !== 6) {
        showToast("Code must be exactly 6 digits long.", "error");
        return;
    }

    try {
        spinner.style.display = "inline-block";
        validateButton.disabled = true;

        const response = await fetch(validateEmailCodeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ email, code: enteredCode }),
        });

        const result = await response.json();

        if (response.ok) {
            showToast(result.message, "success");
            document.getElementById("emailValidationSection").style.display =
                "none";
            isEmailVerified = true;
            getCodeButton.innerText = "Verified";
            getCodeButton.disabled = true;

            // Save the verified email in sessionStorage
            // sessionStorage.setItem("verifiedEmail", email);
        } else {
            showToast(result.message || "Invalid code.", "error");
        }
    } catch (error) {
        showToast("An error occurred during verification.", "error");
        console.error(error);
    } finally {
        spinner.style.display = "none";
        validateButton.disabled = false;
    }
}

// Function to send phone verification code
async function sendPhoneCode() {
    const phoneInput = document.getElementById("phoneInput").value;
    const getCodeButton = document.getElementById("getPhoneCodeButton");
    const spinner = document.getElementById("phoneSpinner");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // Validate phone number and ensure it's not empty
    if (!phoneInput || phoneInput.length !== 10) {
        showToast("Please enter a valid 10-digit phone number.", "error");
        return;
    }

    // Check if the phone number matches the stored value (only if persistence is enabled)
    const storedPhone = usePersistentVerification
        ? sessionStorage.getItem("storedPhone")
        : null;
    if (storedPhone === phoneInput) {
        showToast("Phone number is already verified.", "success");
        getCodeButton.innerText = "Verified";
        getCodeButton.disabled = true;
        return;
    }

    // If phone number has changed, clear previous verification if persistence is enabled
    if (usePersistentVerification) sessionStorage.removeItem("storedPhone");

    try {
        // Show spinner and disable button
        spinner.style.display = "inline-block";
        getCodeButton.disabled = true;

        // Send the phone number to the server to generate and send the code
        const response = await fetch(sendPhoneVerificationCodeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ phone: "+234" + phoneInput }),
        });

        const result = await response.json();

        if (response.ok) {
            showToast(result.message, "success");

            // Store phone number in sessionStorage if verification is successful and persistence is enabled
            if (usePersistentVerification)
                storeVerificationDetails("phone", phoneInput);

            // Start countdown on the "Get Code" button
            startCountdown(getCodeButton, "phone");

            // Show the code validation input section
            document.getElementById(
                "phoneCodeValidationSection"
            ).style.display = "block";
        } else {
            // Handle specific error cases based on the response status code
            if (response.status === 422) {
                const errorMessage =
                    result.errors?.phone?.[0] || "Invalid phone number format.";
                showToast(errorMessage, "error");
            } else if (response.status === 500) {
                const errorMessage =
                    result.error ||
                    "An error occurred while sending the code via SMS.";
                showToast(errorMessage, "error");
            } else {
                showToast(result.message || "Failed to send code.", "error");
            }
            getCodeButton.disabled = false;
        }
    } catch (error) {
        showToast(
            "An unexpected error occurred while sending the code.",
            "error"
        );
        console.error(error);
        getCodeButton.disabled = false;
    } finally {
        spinner.style.display = "none";
    }
}

async function validatePhoneCode() {
    const phoneInput = document.getElementById("phoneInput").value;
    const enteredCode = document.getElementById("phoneCodeInput").value;
    const validateButton = document.getElementById("validatePhoneCodeButton");
    const getCodeButton = document.getElementById("getPhoneCodeButton");
    const spinner = document.getElementById("phoneValidationSpinner");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    if (!enteredCode || enteredCode.length !== 6) {
        showToast("Code must be exactly 6 digits long.", "error");
        return;
    }

    try {
        // Show spinner and disable validate button
        spinner.style.display = "inline-block";
        validateButton.disabled = true;

        const fullPhoneNumber = "+234" + phoneInput;
        // const fullPhoneNumber = "+2348156129655";

        // Send the phone number and code to the server for verification
        const response = await fetch(validatePhoneCodeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ phone: fullPhoneNumber, code: enteredCode }),
        });

        const result = await response.json();
        console.log(result);

        if (response.ok) {
            showToast(result.message, "success");
            document.getElementById(
                "phoneCodeValidationSection"
            ).style.display = "none";
            getCodeButton.innerText = "Verified";
            isPhoneVerified = true;
            getCodeButton.disabled = true;
            sessionStorage.setItem("phoneVerified", "true"); // Mark phone as verified
            sessionStorage.setItem(
                "verifiedPhone",
                document.getElementById("phoneInput").value
            ); // Store the verified phone
        } else {
            // Handle specific error cases based on response status code
            if (response.status === 422) {
                // Validation error or invalid code
                const errorMessage =
                    result.errors?.phone?.[0] ||
                    result.message ||
                    "Invalid verification code or format.";
                showToast(errorMessage, "error");
            } else if (response.status === 500) {
                // Internal server error
                const errorMessage =
                    result.error ||
                    "An unexpected error occurred during verification.";
                showToast(errorMessage, "error");
            } else {
                // Generic error handling for other cases
                showToast(result.message || "Failed to verify code.", "error");
            }
        }
    } catch (error) {
        showToast("An error occurred during verification.", "error");
        console.error(error);
    } finally {
        spinner.style.display = "none";
        validateButton.disabled = false;
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

function validateInputLength() {
    const codeInput = document.getElementById("codeInput");
    codeInput.value = codeInput.value.replace(/[^0-9]/g, ""); // Allow only numbers
}

function validateNumericInput(inputElement) {
    if (inputElement) {
        // Remove non-numeric characters except digits
        let value = inputElement.value.replace(/[^\d]/g, "");

        // Parse the numeric value, enforce min value of 100, and max value of 500000
        let numericValue = parseInt(value, 10) || 0;
        if (numericValue < 100) numericValue = 100; // Enforce minimum value
        if (numericValue > 500000) numericValue = 500000; // Enforce maximum value

        // Format the value with commas for readability
        // inputElement.value = numericValue.toLocaleString();
    }
}

function toggleEFTInput() {
    const nextButton = document.getElementById("nextButton");
    const EFTInputdiv = document.getElementById("EFTInputdiv");
    const mortgageChequeInputDiv = document.getElementById(
        "mortgageChequeInputDiv"
    );
    const selectedOption = document.querySelector(
        'input[name="mortgage_financer_account_mode"]:checked'
    );
    let isValid = true;

    // Disable next button by default
    nextButton.disabled = true;

    function validateFields(container) {
        isValid = true; // Reset isValid for each validation check
        const requiredFields = container.querySelectorAll("[data-required]");
        requiredFields.forEach((field) => {
            if (field.value.trim() === "") {
                field.classList.add("is-invalid"); // Add error styling
                isValid = false;
            } else {
                field.classList.remove("is-invalid"); // Remove error styling if valid
            }
        });
        nextButton.disabled = !isValid; // Enable or disable the next button based on validation result
    }

    function addInputEventListeners(container) {
        const requiredFields = container.querySelectorAll("[data-required]");
        requiredFields.forEach((field) => {
            field.addEventListener("input", () => validateFields(container));
        });
    }

    if (selectedOption) {
        if (selectedOption.value === "EFT") {
            EFTInputdiv.style.display = "block";
            mortgageChequeInputDiv.style.display = "none";
            validateFields(EFTInputdiv); // Validate fields inside EFTInputdiv
            addInputEventListeners(EFTInputdiv); // Add input event listeners
        } else if (selectedOption.value === "mortgage_cheque") {
            EFTInputdiv.style.display = "none";
            mortgageChequeInputDiv.style.display = "block";
            validateFields(mortgageChequeInputDiv); // Validate fields inside mortgageChequeInputDiv
            addInputEventListeners(mortgageChequeInputDiv); // Add input event listeners
        }
    }
}

function toggleEmailInput() {
    const emailInput = document.getElementById("emailInput");
    const interacEmailInput = document.getElementById("interacEmailInput");
    const nextButton = document.getElementById("nextButton");
    const selectedOption = document.querySelector(
        'input[name="landlord_account_mode"]:checked'
    );

    if (selectedOption && selectedOption.value === "rentInterac") {
        emailInput.classList.add("is-invalid"); // Add error styling
        emailInput.style.display = "block"; // Show email input if "Interac" is selected
        nextButton.disabled = true; // Disable the Next button initially

        // Check if the email is already filled and valid
        if (interacEmailInput.value && validateEmail(interacEmailInput.value)) {
            emailInput.classList.remove("is-invalid"); // Remove error styling if valid
            nextButton.disabled = false; // Enable Next button if email is already valid
        }

        // Attach an event listener to validate email as the user types
        interacEmailInput.addEventListener("input", () => {
            if (validateEmail(interacEmailInput.value)) {
                emailInput.classList.remove("is-invalid"); // Remove error styling if valid
                nextButton.disabled = false; // Enable the Next button if the email is valid
            } else {
                emailInput.classList.add("is-invalid"); // Add error styling
                nextButton.disabled = true; // Keep the Next button disabled if the email is invalid
            }
        });
    } else {
        emailInput.style.display = "none"; // Hide email input if any other option is selected
        nextButton.disabled = false; // Enable the Next button if another option is selected
    }
}

// Helper function to validate email format
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email regex pattern
    return emailRegex.test(email); // Returns true if email matches the pattern
}

function chequeOptionForLandLord() {
    const nextButton = document.getElementById("nextButton");
    const landlordDetails4 = document.getElementById("landlordDetails4");
    const landlordDetails5 = document.getElementById("landlordDetails5");
    const selectedOption = document.querySelector(
        'input[name="account_plan_type_mode_lanlord"]:checked'
    );
    let isValid = true;

    // Disable next button by default
    // nextButton.disabled = true; //Disabled because it's affecting the Continue button

    function validateFields(container) {
        isValid = true; // Reset isValid for each validation check
        const requiredFields = container.querySelectorAll("[data-required]");
        requiredFields.forEach((field) => {
            if (field.value.trim() === "") {
                field.classList.add("is-invalid"); // Add error styling
                isValid = false;
            } else {
                field.classList.remove("is-invalid"); // Remove error styling if valid
            }
        });
        nextButton.disabled = !isValid; // Enable or disable the next button based on validation result
    }

    function addInputEventListeners(container) {
        const requiredFields = container.querySelectorAll("[data-required]");
        requiredFields.forEach((field) => {
            field.addEventListener("input", () => validateFields(container));
        });
    }

    if (selectedOption) {
        if (selectedOption.value === "business") {
            landlordDetails4.style.display = "block";
            landlordDetails5.style.display = "none";
            validateFields(landlordDetails4); // Validate fields inside landlordDetails4
            addInputEventListeners(landlordDetails4); // Add input event listeners
        } else if (selectedOption.value === "individual") {
            landlordDetails4.style.display = "none";
            landlordDetails5.style.display = "block";
            validateFields(landlordDetails5); // Validate fields inside landlordDetails5
            addInputEventListeners(landlordDetails5); // Add input event listeners
        }
    }
}

// Helper function to display error message for a specific field
function displayError(fieldName, message) {
    const errorSpan = document.querySelector(
        `[data-error-id="${fieldName}Error"]`
    );
    if (errorSpan) {
        errorSpan.textContent = message;
        errorSpan.style.display = "block";
    }
}

// Helper function to clear the error message for a specific field when the user interacts
function clearError(field) {
    const errorSpan = document.querySelector(
        `[data-error-id="${field.name}Error"]`
    );
    if (errorSpan) {
        errorSpan.textContent = "";
        errorSpan.style.display = "none";
    }
}

// Function to capture and update summary content
function updateSummary() {
    const firstName = document.getElementById("first_name").value;
    const lastName = document.querySelector("input[name='last_name']").value;
    const middleName =
        document.querySelector("input[name='middle_name']").value || "N/A";
    const email = document.getElementById("emailInputField").value;
    const phone = document.getElementById("phoneInput").value;

    document.getElementById("summaryFirstName").textContent = firstName;
    document.getElementById("summaryLastName").textContent = lastName;
    document.getElementById("summaryMiddleName").textContent = middleName;
    document.getElementById("summaryEmail").textContent = email;
    document.getElementById("summaryPhoneNumber").textContent = phone;

    // Show summary section
    // document.getElementById("summarySection").style.display = "block";
}

// Helper function to format numbers to currency format
function formatCurrency(amount) {
    return amount;
    return parseFloat(amount).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function updateAddressSummary() {
    // Capture input values from the address section
    const address = document.getElementById("address-input").value;
    const province = document.getElementById("province").value;
    const city = document.getElementById("city").value;
    const postalCode = document.querySelector(
        "input[name='postal_code']"
    ).value;
    const unitNumber = document.querySelector(
        "input[name='house_number']"
    ).value;
    const houseNumber = document.querySelector(
        "input[name='house_number']"
    ).value;
    const streetName = document.querySelector(
        "input[name='house_number']"
    ).value;

    // Update summary section with captured values
    document.getElementById("summaryAddress").textContent = address;
    document.getElementById("summaryProvince").textContent = province;
    document.getElementById("summaryCity").textContent = city;
    document.getElementById("summaryPostalCode").textContent = postalCode;
    document.getElementById("summaryUnitNumber").textContent = unitNumber;
    document.getElementById("summaryHouseNumber").textContent = houseNumber;
    document.getElementById("summaryStreetName").textContent = streetName;
    // Show summary section
    // document.getElementById("summarySection").style.display = "block";
}

function updateAccountTypeSummary() {
    // Capture the main account type (Rent or Mortgage)
    const primaryGoal = document.querySelector(
        "input[name='rent_account_type']:checked"
    );
    const primaryGoalText = primaryGoal
        ? primaryGoal.value === "rent"
            ? "Rent"
            : "Mortgage"
        : "N/A";

    // Get references for summary fields
    const summaryPrimaryGoal = document.getElementById("summaryPrimaryGoal");
    const summaryAccountPlan = document.getElementById("summaryAccountPlan");
    const summaryApplicationType = document.getElementById(
        "summaryApplicationType"
    );
    const summaryRentPaymentSetup = document.getElementById(
        "summaryRentPaymentSetup"
    );
    const summaryCardLimit = document.getElementById("summaryCardLimit");
    const summaryCardDueDate = document.getElementById("summaryCardDueDate");

    // Clear the previous co-applicant or co-owner details
    const coApplicantsSummaryContainer = document.getElementById(
        "coApplicantsSummary"
    );
    const coApplicanRentSummaryDiv = document.getElementById(
        "coApplicanRentSummaryDiv"
    );
    coApplicantsSummaryContainer.innerHTML = "";
    coApplicanRentSummaryDiv.style.display = "none";

    const paymentSetup =
        primaryGoalText === "Rent"
            ? "Rent Payment Setup"
            : "Mortgage Payment Setup";

    // Set labels based on primary goal (Rent or Mortgage)
    const paymentSetupLabel =
        primaryGoalText === "Rent"
            ? "Rent Payment Setup"
            : "Mortgage Payment Setup";
    const coApplicantLabel =
        primaryGoalText === "Rent"
            ? "Co-Applicant Details"
            : "Co-Owner Details";
    const primaryAmountLabel =
        primaryGoalText === "Rent"
            ? "Primary Applicant Rent Amount"
            : "Owner Primary Mortgage Amount";

    // Check if the primary applicant rent summary element exists
    const primaryApplicantRentElement = document.getElementById(
        "summaryPrimaryApplicantRent"
    );
    if (!primaryApplicantRentElement) return;

    // Show the dynamically updated title in the summary
    // document.getElementById("summaryPaymentSetupLabel").textContent = paymentSetup;
    // Update labels dynamically
    document.getElementById("summaryPaymentSetupLabel").textContent =
        paymentSetupLabel;
    document.getElementById("summaryCoApplicantLabel").textContent =
        coApplicantLabel;
    document.getElementById("summaryPrimaryAmountLabel").textContent =
        primaryAmountLabel;
    // Determine and populate fields based on the primary goal (Rent or Mortgage)
    if (primaryGoalText === "Rent") {
        const primaryRentAmount =
            document.getElementById("coApplicanRentPrimaryAmount").value || 0;
        primaryApplicantRentElement.textContent = primaryRentAmount;
        // Rent Flow: Populate Rent-specific fields
        const accountPlan = document.querySelector(
            "input[name='account_plan_rent']:checked"
        );
        const applicationType = document.querySelector(
            "input[name='rent_account_plan_type']:checked"
        );
        const rentPaymentSetup = document.querySelector(
            "input[name='rent_account_plan_type_mode']:checked"
        );

        const accountPlanText = accountPlan
            ? accountPlan.value === "pay_rent"
                ? "Pay Rent"
                : "Pay Rent and Build Credit"
            : "N/A";
        const applicationTypeText = applicationType
            ? applicationType.value === "sole_applicant"
                ? "Sole Applicant"
                : "Co-applicant"
            : "N/A";
        const rentPaymentSetupText = rentPaymentSetup
            ? rentPaymentSetup.value === "Continue_paying_existing_rent"
                ? "Continue Paying Existing Rent"
                : "Setup Payment for New Rental"
            : "N/A";

        summaryPrimaryGoal.textContent = primaryGoalText;
        summaryAccountPlan.textContent = accountPlanText;
        summaryApplicationType.textContent = applicationTypeText;
        summaryRentPaymentSetup.textContent = rentPaymentSetupText;

        if (accountPlan && accountPlan.value === "pay_rent_build") {
            // Display credit card limit and due date
            const cardLimit = formatCurrency(
                document.querySelector("input[name='credit_card_limit']")
                    .value || "N/A"
            );
            const cardDueDate = document.querySelector(
                "select[name='rent_monthly_CC_day']"
            ).value;
            summaryCardLimit.textContent = cardLimit;
            summaryCardDueDate.textContent = `Day ${cardDueDate || "N/A"}`;
            summaryCardLimit.parentElement.style.display = "block";
            summaryCardDueDate.parentElement.style.display = "block";
        } else {
            summaryCardLimit.parentElement.style.display = "none";
            summaryCardDueDate.parentElement.style.display = "none";
        }

        // Check for co-applicant details if application type is "Co-applicant"
        if (applicationTypeText === "Co-applicant") {
            displayCoApplicants("Rent");
        }
    } else if (primaryGoalText === "Mortgage") {
        const primaryRentAmount =
            document.getElementById("coApplicanMortgagePrimaryAmount").value ||
            0;
        primaryApplicantRentElement.textContent = primaryRentAmount;
        // Mortgage Flow: Populate Mortgage-specific fields
        const accountPlan = document.querySelector(
            "input[name='account_plan_mortgage']:checked"
        );
        const applicationType = document.querySelector(
            "input[name='mortgage_account_plan_type']:checked"
        );
        const mortgagePaymentSetup = document.querySelector(
            "input[name='mortgage_account_plan_type_mode']:checked"
        );

        const accountPlanText = accountPlan
            ? accountPlan.value === "pay_mortgage"
                ? "Pay Mortgage"
                : "Pay Mortgage and Build Credit"
            : "N/A";
        const applicationTypeText = applicationType
            ? applicationType.value === "owner"
                ? "Owner"
                : "Co-owner"
            : "N/A";
        const mortgagePaymentSetupText = mortgagePaymentSetup
            ? mortgagePaymentSetup.value === "Continue_paying_existing_mortgage"
                ? "Continue Paying Existing Mortgage"
                : "Setup Payment for New Mortgage"
            : "N/A";

        summaryPrimaryGoal.textContent = primaryGoalText;
        summaryAccountPlan.textContent = accountPlanText;
        summaryApplicationType.textContent = applicationTypeText;
        summaryRentPaymentSetup.textContent = mortgagePaymentSetupText;

        if (accountPlan && accountPlan.value === "pay_mortgage_build") {
            // Display mortgage card limit and due date
            const cardLimit = formatCurrency(
                document.querySelector(
                    "input[name='mortgage_credit_card_limit']"
                ).value || "N/A"
            );
            const cardDueDate = document.querySelector(
                "select[name='mortgage_credit_card_limit_monthly_day']"
            ).value;
            summaryCardLimit.textContent = cardLimit;
            summaryCardDueDate.textContent = `Day ${cardDueDate || "N/A"}`;
            summaryCardLimit.parentElement.style.display = "block";
            summaryCardDueDate.parentElement.style.display = "block";
        } else {
            summaryCardLimit.parentElement.style.display = "none";
            summaryCardDueDate.parentElement.style.display = "none";
        }

        // Check for co-owner details if application type is "Co-owner"
        if (applicationTypeText === "Co-owner") {
            displayCoApplicants("Mortgage");
        }
    }
}

// Helper function to display co-applicants or co-owners in the summary based on type
function displayCoApplicants(type) {
    const coApplicantsSummaryContainer = document.getElementById(
        "coApplicantsSummary"
    );
    coApplicantsSummaryContainer.style.display = "block";
    coApplicantsSummaryContainer.innerHTML = ""; // Clear previous entries

    const coApplicanRentSummaryDiv = document.getElementById(
        "coApplicanRentSummaryDiv"
    );
    coApplicanRentSummaryDiv.style.display = "block";

    // Select container and label for Rent or Mortgage co-applicants/co-owners
    const containerId =
        type === "Rent"
            ? "#applicantsContainer .addNewApplicant"
            : "#applicantsContainer2 .addNewApplicant2";
    const coApplicants = document.querySelectorAll(containerId);

    console.log(coApplicants, "coApplicants");

    coApplicants.forEach((applicant, index) => {
        const firstName =
            applicant.querySelector(
                `input[name='${type === "Rent"
                    ? "coApplicantfirstName[]"
                    : "mortgagecoOwnerfirstName[]"
                }']`
            ).value || "N/A";
        const lastName =
            applicant.querySelector(
                `input[name='${type === "Rent"
                    ? "coApplicantlastName[]"
                    : "mortgagecoOwnerlastName[]"
                }']`
            ).value || "N/A";
        const email =
            applicant.querySelector(
                `input[name='${type === "Rent"
                    ? "coApplicantemail[]"
                    : "mortgagecoOwneremail[]"
                }']`
            ).value || "N/A";
        const amount = formatCurrency(
            applicant.querySelector(
                `input[name='${type === "Rent"
                    ? "coApplicantamount[]"
                    : "mortgagecoOwneramount[]"
                }']`
            ).value || "N/A"
        );

        console.log(coApplicants);

        const coApplicantDiv = document.createElement("div");
        coApplicantDiv.classList.add("mb-3", "p-3", "border", "rounded");
        coApplicantDiv.innerHTML = `
            <p><strong>${type === "Rent" ? "Co-Applicant" : "Co-Owner"} ${index + 1
            }:</strong></p>
            <p><strong>First Name:</strong> ${firstName}</p>
            <p><strong>Last Name:</strong> ${lastName}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p><strong>${type === "Rent" ? "Rent" : "Mortgage"
            } Amount:</strong> ${amount}</p>
        `;

        coApplicantsSummaryContainer.appendChild(coApplicantDiv);
    });
}

function togglePasswordVisibility(fieldId, toggleButton) {
    const passwordField = document.getElementById(fieldId);
    const icon = toggleButton.querySelector("i");

    // Toggle between password and text type
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // toggleEmailInput();
    chequeOptionForLandLord();
    const initialStep = "personalInformation";
    const submitButton = document.querySelector(
        '[data-kt-stepper-action="submit"]'
    );
    let rent_account_type; // This will be set based on the user's selection in Rent_mortgage_div

    // function initializeStepFlow(nextButtonId, previousButtonId, hideAllDivsSelector, initialStep) {
    const stepFlow = {
        personalInformation: {
            divId: "personalInformation",
            next: "Rent_mortgage_div",
        },
        Rent_mortgage_div: {
            divId: "Rent_mortgage_div",
            next: (selected) => {
                rent_account_type = selected; // Set the global rent_account_type based on user choice
                return rent_account_type === "rent"
                    ? "rentStep1"
                    : "mortgageStep1";
            },
            inputName: "rent_account_type",
        },
        rentStep1: {
            divId: "RentStep1",
            next: (selected) =>
                selected === "pay_rent" ? "rentStep2" : "rentStep3",
            inputName: "account_plan_rent",
        },
        rentStep2: {
            divId: "RentStep2",
            next: (selected) =>
                selected === "sole_applicant" ? "rentStep4" : "rentStep5",
            inputName: "rent_account_plan_type",
        },
        rentStep3: { divId: "RentStep3", next: "rentStep2" },
        rentStep4: { divId: "RentStep4", next: "address_details_div" },
        rentStep5: { divId: "rentStep5", next: "rentStep4" },
        mortgageStep1: {
            divId: "MortgageStep1",
            next: (selected) =>
                selected === "pay_mortgage" ? "mortgageStep2" : "mortgageStep3",
            inputName: "account_plan_mortgage",
        },
        mortgageStep2: {
            divId: "MortgageStep2",
            next: (selected) =>
                selected === "owner" ? "mortgageStep5" : "mortgageStep4",
            inputName: "mortgage_account_plan_type",
        },
        mortgageStep3: { divId: "MortgageStep3", next: "mortgageStep2" },
        mortgageStep4: { divId: "MortgageStep4", next: "mortgageStep5" },
        mortgageStep5: { divId: "MortgageStep5", next: "address_details_div" },
        address_details_div: {
            divId: "address_details_div",
            next: "addressDetails1",
        },
        addressDetails1: { divId: "addressDetails1", next: "addressDetails2" },
        addressDetails2: { divId: "addressDetails2", next: "addressDetails3" },
        addressDetails3: { divId: "addressDetails3", next: "addressDetails4" },
        addressDetails4: {
            divId: "addressDetails4",
            next: () =>
                rent_account_type === "rent"
                    ? "landlordDetails1"
                    : "landlordDetails6", // Decision based on global rent_account_type
            inputName: "rent_account_type",
        },
        landlordDetails1: {
            divId: "landlordDetails1",
            next: (selected) =>
                selected === "rentInterac" ? "verificationDiv" : "landlordDetails3",
            inputName: "landlord_account_mode",
        },
        landlordDetails6: {
            divId: "landlordDetails6",
            next: "verificationDiv",
        },
        landlordDetails3: {
            divId: "landlordDetails3",
            next: "verificationDiv",
        },
        verificationDiv: {
            divId: "verificationDiv",
            next: "summaryDiv",
        },
        summaryDiv: { divId: "summaryDiv", next: null },
    };

    // Step grouping for side menu items
    const stepToMenuMapping = {
        personalInformation: 0,
        Rent_mortgage_div: 1,
        rentStep1: 1,
        rentStep2: 1,
        rentStep3: 1,
        rentStep4: 1,
        rentStep5: 1,
        mortgageStep1: 1,
        mortgageStep2: 1,
        mortgageStep3: 1,
        mortgageStep4: 1,
        mortgageStep5: 1,
        address_details_div: 2,
        addressDetails1: 2,
        addressDetails2: 2,
        addressDetails3: 2,
        addressDetails4: 2,
        landlordDetails1: 3,
        landlordDetails3: 3,
        landlordDetails6: 3,
        verificationDiv: 4,
        summaryDiv: 5,
    };

    let currentStep = initialStep;
    const navigationStack = [];
    const nextButton = document.getElementById("nextButton");
    const previousButton = document.getElementById("previousButton");

    hideAllSteps();
    navigateToStep(stepFlow[initialStep].divId);

    nextButton.addEventListener("click", function (e) {
        console.log(currentStep);

        // if (!validateStep(currentStep)) {
        //     e.preventDefault();
        //     return;
        // }
        // console.log(validateStep(currentStep));

        // if (!validatePassword()) {
        //     e.preventDefault(); // Prevent form submission if password validation fails
        //     return;
        // }

        // Check if email and phone are verified before proceeding
        if (currentStep === "personalInformation") {
            // if (!isEmailVerified || !isPhoneVerified) {
            // if (!isEmailVerified) {
            //     e.preventDefault(); // Prevent form submission if conditions are not met
            //     displayVerificationErrors(); // Show specific error messages
            //     return; // Exit the function to stop further execution
            // }
        }

        updateSummary();
        updateAddressSummary();
        updateAccountTypeSummary();
        const nextStepKey = getNextStepKey(currentStep);
        if (nextStepKey) {
            navigationStack.push(currentStep);
            currentStep = nextStepKey;
            navigateToStep(stepFlow[currentStep].divId);
        }
    });

    // Update side menu based on step mapping
    function updateSideMenu() {
        const sideMenuItems = document.querySelectorAll(".stepper-item");
        sideMenuItems.forEach((item, index) =>
            item.classList.remove("current")
        );

        const activeMenuIndex = stepToMenuMapping[currentStep];
        if (sideMenuItems[activeMenuIndex]) {
            sideMenuItems[activeMenuIndex].classList.add("current");
        }
    }

    function updateProgressBar() {
        const totalSteps = Object.keys(stepFlow).length;
        const currentStepIndex = Object.keys(stepFlow).indexOf(currentStep);
        const progress = (currentStepIndex / (totalSteps - 1)) * 100;
        document.querySelector(".progress-bar").style.width = `${progress}%`;
    }

    function displayVerificationErrors() {
        const emailField = document.querySelector(
            "input[name='email_address']"
        );
        emailField.classList.remove("is-invalid");

        if (!isEmailVerified) {
            showToast("Email verification required.", "error");
            emailField.classList.add("is-invalid");
        }

        // if (!isPhoneVerified) {
        //     showToast("Phone verification required.", "error");
        // }
    }

    function validatePassword() {
        const passwordField = document.querySelector("input[name='password']");
        const confirmPasswordField = document.querySelector(
            "input[name='password_confirmation']"
        );
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;

        // Remove the invalid class initially in case it was set previously
        passwordField.classList.remove("is-invalid");
        confirmPasswordField.classList.remove("is-invalid");

        // Check if passwords match
        if (password !== confirmPassword) {
            showToast("Passwords do not match.", "error");
            passwordField.classList.add("is-invalid"); // Add invalid class to both fields
            confirmPasswordField.classList.add("is-invalid");
            return false; // Validation failed
        }

        // Check if password meets requirements: 8+ characters, letters, numbers, symbols
        const passwordRegex =
            /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!+%*?&])[A-Za-z\d@$!+%*?&]{8,}$/;
        if (!passwordRegex.test(password)) {
            showToast(
                "Password must be 8 or more characters with a mix of letters, numbers, and symbols.",
                "error"
            );
            passwordField.classList.add("is-invalid"); // Add invalid class to indicate password does not meet requirements
            return false; // Validation failed
        }

        return true; // Validation passed
    }

    function navigateToStep(divId) {
        hideAllSteps();
        document.getElementById(divId).style.display = "block";

        const isLastStep = currentStep === "summaryDiv";

        // Show/Hide buttons based on current step
        previousButton.style.display =
            navigationStack.length === 0 ? "none" : "inline-block";
        nextButton.style.display = isLastStep ? "none" : "inline-block";
        submitButton.style.display = isLastStep ? "inline-block" : "none";

        updateSideMenu();
        updateProgressBar();
    }

    previousButton.addEventListener("click", function () {
        if (navigationStack.length > 0) {
            updateSummary();
            updateAddressSummary();
            updateAccountTypeSummary();
            currentStep = navigationStack.pop();
            navigateToStep(stepFlow[currentStep].divId);
        }
    });

    function getNextStepKey(step) {
        const config = stepFlow[step];
        if (typeof config.next === "function" && config.inputName) {
            const selectedOption = document.querySelector(
                `input[name="${config.inputName}"]:checked`
            );
            if (!selectedOption) {
                displayError(
                    config.inputName,
                    "Please select an option to proceed."
                );
                return null;
            }
            return config.next(selectedOption.value);
        }
        return config.next;
    }

    function hideAllSteps() {
        Object.values(stepFlow).forEach((step) => {
            const stepDiv = document.getElementById(step.divId);
            if (stepDiv) {
                stepDiv.style.display = "none";
            }
        });
    }

    function validateStep(step) {
        clearErrors(); // Clears all existing error messages initially
        const div = document.getElementById(stepFlow[step].divId);
        let isValid = true; // Flag to track if all fields are valid

        // Additional specific validation for "rentStep5"
        if (div && stepFlow[step].divId === "rentStep5") {
            isValid = validateApplicants() && isValid;
        }

        // Additional specific validation for "rentStep4"
        if (div && stepFlow[step].divId === "mortgageStep4") {
            isValid = validateApplicants2() && isValid;
        }

        const fields = div.querySelectorAll(
            "input[required], select[required]"
        );

        console.log(fields);

        // Loop through each field to validate and display errors if needed
        fields.forEach((field) => {
            if (field.type === "checkbox") {
                // Handle grouped checkboxes by name
                if (field.name) {
                    const checkboxes = div.querySelectorAll(
                        `input[name="${field.name}"]`
                    );
                    const isAnyChecked = Array.from(checkboxes).some(
                        (checkbox) => checkbox.checked
                    );

                    if (!isAnyChecked) {
                        field.classList.add("is-invalid"); // Add error styling
                        displayError(field.name, "This field is required.");
                        isValid = false;
                    }
                } else if (!field.checked) {
                    // Handle individual checkboxes
                    displayError(field.name, "This field is required.");
                    field.classList.add("is-invalid"); // Add error styling
                    isValid = false;
                }
            }
            //  else if (field.type === "radio") {
            //     // Handle grouped radio buttons by name
            //     if (field.name) {
            //         const radioButtons = div.querySelectorAll(`input[name="${field.name}"]`);
            //         const isAnySelected = Array.from(radioButtons).some(radio => radio.checked);

            //         if (!isAnySelected) {
            //             displayError(field.name, "Please select an option.");
            //             isValid = false;
            //         }
            //     }

            // }
            else if (!field.value.trim()) {
                // For other input types, check if value is empty
                // console.log(field.value.trim(), "field.value.trim()");
                displayError(field.name, "This field is required.");
                field.classList.add("is-invalid"); // Add error styling
                isValid = false;
            } else {
                field.classList.remove("is-invalid"); // Add error styling
                // console.log(field.value);
            }

            // Attach event listeners to clear errors when the user interacts with a field
            field.addEventListener("input", () => clearError(field)); // For text inputs
            field.addEventListener("change", () => clearError(field)); // For radio, checkbox, and select elements
        });

        return isValid; // Return the overall validity of the form
    }

    function displayError(fieldName, message) {
        const errorSpan = document.querySelector(
            `[data-error-id="${fieldName}Error"]`
        );
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = "block"; // Display the error message
        }
    }

    function clearErrors() {
        document.querySelectorAll(".error-message").forEach((error) => {
            error.textContent = "";
            error.style.display = "none"; // Hide all errors initially
        });
    }

    // Clear the specific error message associated with a field when the user interacts
    function clearError(field) {
        field.classList.remove("is-invalid"); // Add error styling
        const errorSpan = document.querySelector(
            `[data-error-id="${field.name}Error"]`
        );
        if (errorSpan) {
            errorSpan.textContent = ""; // Clear the error message
            errorSpan.style.display = "none"; // Hide the error message
        }
    }

    function validateApplicants() {
        let isValid = true;
        const applicantForms =
            applicantsContainer.querySelectorAll(".addNewApplicant");

        applicantForms.forEach((form, index) => {
            console.log(`Validating applicant form #${index + 1}`);

            const fields = {
                firstName: form.querySelector(
                    "input[name='coApplicantfirstName[]']"
                ),
                lastName: form.querySelector(
                    "input[name='coApplicantlastName[]']"
                ),
                email: form.querySelector("input[name='coApplicantemail[]']"),
                amount: form.querySelector("input[name='coApplicantamount[]']"),
            };

            // Map the field keys to the corresponding error ID names without square brackets
            const errorIds = {
                firstName: "coApplicantfirstNameError",
                lastName: "coApplicantlastNameError",
                email: "coApplicantemailError",
                amount: "coApplicantamountError",
            };

            Object.keys(fields).forEach((fieldKey) => {
                const field = fields[fieldKey];

                if (!field) return; // Skip if field is not found

                // Select the error span using the mapped error ID
                const errorSpan = form.querySelector(
                    `[data-error-id="${errorIds[fieldKey]}"]`
                );
                console.log(
                    `Validating field: ${fieldKey}`,
                    "Error span:",
                    errorSpan
                );

                // Check for validation and display errors if necessary
                if (!field.value.trim()) {
                    if (errorSpan) {
                        errorSpan.textContent = "This field is required.";
                        errorSpan.style.display = "block";
                    }
                    isValid = false;
                } else if (errorSpan) {
                    errorSpan.style.display = "none";
                }

                // Clear error message on input
                field.addEventListener("input", () => {
                    if (errorSpan) {
                        errorSpan.textContent = "";
                        errorSpan.style.display = "none";
                    }
                });
            });
        });

        console.log("Validation result for applicants:", isValid);
        return isValid;
    }
});


// Improve variable naming and add validation for total percentage
const applicantsContainer2 = document.getElementById("applicantsContainer2");
const addApplicantBtn2 = document.getElementById("addApplicantBtn2");
let applicantCount2 = 1; // Tracks number of co-owners

// Add validation for total percentage
function validateTotalPercentage() {
    const primaryPercentage = parseFloat(document.getElementById('coApplicanMortgagePrimaryAmount').value) || 0;
    let coOwnerTotal = 0;

    document.querySelectorAll('input[name="mortgagecoOwneramount[]"]').forEach(input => {
        // coOwnerTotal += parseFloat(input.value) || 0;
    });

    const total = primaryPercentage + coOwnerTotal;
    return total <= 100;
}

function updatePercentageSummary2() {
    const primaryPercentage = parseFloat(document.getElementById('coApplicanMortgagePrimaryAmount').value) || 0;
    let coOwnersTotal = 0;

    document.querySelectorAll('input[name="mortgagecoOwneramount[]"]').forEach(input => {
        // coOwnersTotal += parseFloat(input.value) || 0;
    });

    const totalPercentage = primaryPercentage + coOwnersTotal;
    const remainingPercentage = 100 - totalPercentage;

    // Update summary display
    document.getElementById('primaryOwnerPercentage').textContent = primaryPercentage.toFixed(2);
    document.getElementById('coOwnersPercentage').textContent = coOwnersTotal.toFixed(2);
    document.getElementById('totalPercentage').textContent = totalPercentage.toFixed(2);
    document.getElementById('remainingPercentage').textContent = remainingPercentage.toFixed(2);

    // Add visual feedback
    const totalElement = document.getElementById('totalPercentage');
    const remainingElement = document.getElementById('remainingPercentage');

    if (totalPercentage > 100) {
        totalElement.classList.add('text-danger');
        remainingElement.classList.add('text-danger');
    } else {
        totalElement.classList.remove('text-danger');
        remainingElement.classList.remove('text-danger');
    }
}

function updatePercentageSummary() {
    try {
        const primaryInput = document.getElementById('coApplicanMortgagePrimaryAmount');
        const primaryPercentage = parseFloat(primaryInput?.value) || 0;
        let coOwnersTotal = 0;

        document.querySelectorAll('input[name="mortgagecoOwneramount[]"]').forEach(input => {
            coOwnersTotal += parseFloat(input.value) || 0;
        });

        const totalPercentage = primaryPercentage + coOwnersTotal;
        const remainingPercentage = 100 - totalPercentage;

        // Update summary displays
        const elements = {
            primaryOwnerPercentage: primaryPercentage.toFixed(2),
            coOwnersPercentage: coOwnersTotal.toFixed(2),
            totalPercentage: totalPercentage.toFixed(2),
            remainingPercentage: Math.max(0, remainingPercentage).toFixed(2)
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
                // Add visual feedback for total percentage
                if (id === 'totalPercentage' || id === 'remainingPercentage') {
                    element.classList.toggle('text-danger', totalPercentage > 100);
                }
            }
        });
    } catch (error) {
        console.error('Error updating percentage summary:', error);
    }
}

// Modify your existing validatePercentageInput function to include this update
function validatePercentageInput(input) {
    if (!input) {
        console.error('Input element is undefined');
        return false;
    }

    const rawValue = input.value;
    // Don't convert to float immediately to handle whole numbers correctly
    const errorSpan = input.closest('.col-md-3')?.querySelector('.error-message');
    let valid = true;

    if (errorSpan) {
        errorSpan.textContent = '';
    }
    input.classList.remove('is-invalid');

    // If empty input
    if (!rawValue) {
        if (errorSpan) {
            errorSpan.textContent = 'Percentage is required';
        }
        input.classList.add('is-invalid');
        return false;
    }

    // Convert to number for validation
    const value = parseFloat(rawValue);

    if (isNaN(value)) {
        if (errorSpan) {
            errorSpan.textContent = 'Please enter a valid percentage';
        }
        input.classList.add('is-invalid');
        return false;
    }

    if (value < 1 || value > 100) {
        if (errorSpan) {
            errorSpan.textContent = 'Percentage must be between 1 and 100';
        }
        input.classList.add('is-invalid');
        input.value = '';
        return false;
    }

    let totalPercentage = 0;

    const primaryInput = document.getElementById('coApplicanMortgagePrimaryAmount');
    if (primaryInput) {
        totalPercentage += parseFloat(primaryInput.value) || 0;
    }

    document.querySelectorAll('input[name="mortgagecoOwneramount[]"]').forEach(coOwnerInput => {
        if (coOwnerInput !== input) {
            totalPercentage += parseFloat(coOwnerInput.value) || 0;
        }
    });

    totalPercentage += value;

    if (totalPercentage > 100) {
        if (errorSpan) {
            errorSpan.textContent = 'Total percentage cannot exceed 100%';
        }
        input.classList.add('is-invalid');
        input.value = '';
        return false;
    }

    // Only format to 2 decimal places if the input contains a decimal point
    if (rawValue.includes('.')) {
        input.value = value.toFixed(2);
    } else {
        input.value = Math.floor(value); // Keep whole numbers as is
    }

    updatePercentageSummary();
    return true;
}

// Add this input handler to prevent non-numeric input
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
        input.addEventListener('input', function (e) {
            // Allow numbers and single decimal point
            let value = this.value.replace(/[^\d.]/g, '');

            // Ensure only one decimal point
            const decimalCount = (value.match(/\./g) || []).length;
            if (decimalCount > 1) {
                value = value.slice(0, value.lastIndexOf('.'));
            }

            this.value = value;
        });
    });
});

// Add event listeners
// document.addEventListener('DOMContentLoaded', function() {
//     // Event listener for primary amount
//     const primaryInput = document.getElementById('coApplicanMortgagePrimaryAmount');
//     if (primaryInput) {
//         primaryInput.addEventListener('input', function() {
//             this.value = this.value.replace(/[^\d.]/g, '');
//             validatePercentageInput(this);
//         });
//     }

//     // Event listeners for co-owner amounts
//     document.querySelectorAll('input[name="mortgagecoOwneramount[]"]').forEach(input => {
//         input.addEventListener('input', function() {
//             this.value = this.value.replace(/[^\d.]/g, '');
//             validatePercentageInput(this);
//         });
//     });
// });

// Add event listeners for real-time updates
// document.getElementById('coApplicanMortgagePrimaryAmount').addEventListener('input', function() {
//     validatePercentageInput(this);
// });

// document.querySelectorAll('input[name="mortgagecoOwneramount[]"]').forEach(input => {
//     input.addEventListener('input', function() {
//         validatePercentageInput(this);
//     });
// });

// Call initially to set up summary
updatePercentageSummary();

function toggleAddButton2() {
    addApplicantBtn2.disabled = applicantCount2 >= 3;
}

// Add event listener with improved error handling
addApplicantBtn2.addEventListener("click", () => {
    try {
        if (applicantCount2 < 3) {
            applicantCount2++;
            const newApplicantDiv2 = createNewApplicantDiv();
            applicantsContainer2.appendChild(newApplicantDiv2);
            setupDeleteHandler(newApplicantDiv2);
            toggleAddButton2();
        }
    } catch (error) {
        console.error('Error adding applicant:', error);
    }
});

// Separate function for creating new applicant div
function createNewApplicantDiv() {
    const newApplicantDiv2 = document.createElement("div");
    newApplicantDiv2.classList.add("rounded", "mb-2", "border", "p-4", "addNewApplicant2");
    newApplicantDiv2.id = `addNewApplicant2${applicantCount2 + 110}`;

    // Your existing HTML template
    newApplicantDiv2.innerHTML = `<div class="form-group row mb-3">
                <div class="col-md-3">
                    <label class="form-label">First Name:</label>
                    <input type="text" name="mortgagecoOwnerfirstName[]" class="form-control mb-2 mb-md-0" placeholder="Enter first name" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Last Name:</label>
                    <input type="text" name="mortgagecoOwnerlastName[]" class="form-control mb-2 mb-md-0" placeholder="Enter last name" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="mortgagecoOwneremail[]" class="form-control mb-2 mb-md-0" placeholder="Enter email address" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Percentage Amount:</label>
                    <input type="number" 
                        name="mortgagecoOwneramount[]"  
                        onblur="validatePercentageInput(this)"
                        class="form-control mb-2 mb-md-0"
                        placeholder="Enter percentage (1-100)" 
                        min="1" 
                        max="100" 
                        required>
                    <span class="error-message"></span>
                </div>
                <div class="col-md-2">
                    <a href="javascript:;" class="btn btn-sm btn-flex flex-center btn-light-danger mt-3 mt-md-9 deleteApplicantBtn2">
                        Delete
                    </a>
                </div>
            </div>`; // Keep your existing template

    return newApplicantDiv2;
}

// Separate function for delete handler setup
function setupDeleteHandler(applicantDiv) {
    const deleteBtn = applicantDiv.querySelector(".deleteApplicantBtn2");
    if (deleteBtn) {
        deleteBtn.addEventListener("click", () => {
            applicantDiv.remove();
            applicantCount2--;
            toggleAddButton2();
        });
    }
}

// Initialize delete buttons for existing entries
document.querySelectorAll(".deleteApplicantBtn2").forEach((deleteBtn) => {
    deleteBtn.addEventListener("click", (event) => {
        event.target.closest(".addNewApplicant2").remove();
        applicantCount2--;
        toggleAddButton2();
    });
});

// Initial button state
toggleAddButton2();


// const applicantsContainer = document.getElementById("applicantsContainer");
// const addApplicantBtn = document.getElementById("addApplicantBtn");
// let applicantCount = 1;

// function toggleAddButton() {
//     addApplicantBtn.disabled = applicantCount >= 3;
// }

// addApplicantBtn.addEventListener("click", () => {
//     if (applicantCount < 3) {
//         applicantCount++;
//         const newApplicantDiv = document.createElement("div");
//         newApplicantDiv.classList.add(
//             "rounded",
//             "mb-2",
//             "border",
//             "p-4",
//             "addNewApplicant"
//         );
//         newApplicantDiv.id = `addNewApplicant${applicantCount + 110}`;

//         newApplicantDiv.innerHTML = `
//             <div class="form-group row mb-3">
//                 <div class="col-md-3">
//                     <label class="form-label">First Name:</label>
//                     <input type="text" name="coApplicantfirstName[]" class="form-control mb-2 mb-md-0" placeholder="Enter first name" required>
//                     <span class="error-message" data-error-id="coApplicantfirstNameError"></span>
//                 </div>
//                 <div class="col-md-3">
//                     <label class="form-label">Last Name:</label>
//                     <input type="text" name="coApplicantlastName[]" class="form-control mb-2 mb-md-0" placeholder="Enter last name" required>
//                     <span class="error-message" data-error-id="coApplicantlastNameError"></span>
//                 </div>
//                 <div class="col-md-3">
//                     <label class="form-label">Email:</label>
//                     <input type="email" name="coApplicantemail[]" class="form-control mb-2 mb-md-0" placeholder="Enter email address" required>
//                     <span class="error-message" data-error-id="coApplicantemailError"></span>
//                 </div>
//                 <div class="col-md-3">
//                     <label class="form-label">Rent Amount:</label>
//                     <input type="text" name="coApplicantamount[]" oninput="validateNumericInput(this)" class="form-control mb-2 mb-md-0" min="100" max="20000" placeholder="1,000.00" required>
//                     <span class="error-message" data-error-id="coApplicantamountError"></span>
//                 </div>
//                 <div class="col-md-2">
//                     <a href="javascript:;" class="btn btn-sm btn-flex flex-center btn-light-danger mt-3 mt-md-9 deleteApplicantBtn">
//                         Delete
//                     </a>
//                 </div>
//             </div>
//         `;

//         applicantsContainer.appendChild(newApplicantDiv);

//         newApplicantDiv
//             .querySelector(".deleteApplicantBtn")
//             .addEventListener("click", () => {
//                 newApplicantDiv.remove();
//                 applicantCount--;
//                 toggleAddButton();
//             });

//         toggleAddButton();
//     }
// });

// document.querySelectorAll(".deleteApplicantBtn").forEach((deleteBtn) => {
//     deleteBtn.addEventListener("click", (event) => {
//         event.target.closest(".addNewApplicant").remove();
//         applicantCount--;
//         toggleAddButton();
//     });
// });

// toggleAddButton(); // Initial check for button status

const applicantsContainer = document.getElementById("applicantsContainer");
const addApplicantBtn = document.getElementById("addApplicantBtn");
let applicantCount = 1;

function validateApplicantPercentageInput(input) {
    if (!input) {
        console.error('Input element is undefined');
        return false;
    }

    const rawValue = input.value;
    const errorSpan = input.closest('.col-md-3')?.querySelector('.error-message');
    let valid = true;

    if (errorSpan) {
        errorSpan.textContent = '';
    }
    input.classList.remove('is-invalid');

    if (!rawValue) {
        if (errorSpan) {
            errorSpan.textContent = 'Percentage is required';
        }
        input.classList.add('is-invalid');
        return false;
    }

    const value = parseFloat(rawValue);

    if (isNaN(value)) {
        if (errorSpan) {
            errorSpan.textContent = 'Please enter a valid percentage';
        }
        input.classList.add('is-invalid');
        return false;
    }

    if (value < 1 || value > 100) {
        if (errorSpan) {
            errorSpan.textContent = 'Percentage must be between 1 and 100';
        }
        input.classList.add('is-invalid');
        input.value = '';
        return false;
    }

    if (!validateTotalApplicantPercentage()) {
        if (errorSpan) {
            errorSpan.textContent = 'Total percentage cannot exceed 100%';
        }
        input.classList.add('is-invalid');
        input.value = '';
        return false;
    }

    if (rawValue.includes('.')) {
        input.value = value.toFixed(2);
    } else {
        input.value = Math.floor(value);
    }

    updateApplicantPercentageSummary();
    return true;
}

function validateTotalApplicantPercentage() {
    const primaryInput = document.getElementById('coApplicanRentPrimaryAmount');
    let totalPercentage = parseFloat(primaryInput?.value) || 0;

    document.querySelectorAll('input[name="coApplicantamount[]"]').forEach(input => {
        totalPercentage += parseFloat(input.value) || 0;
    });

    return totalPercentage <= 100;
}

function updateApplicantPercentageSummary() {
    try {
        const primaryInput = document.getElementById('coApplicanRentPrimaryAmount');
        const primaryPercentage = parseFloat(primaryInput?.value) || 0;
        let coApplicantsTotal = 0;

        document.querySelectorAll('input[name="coApplicantamount[]"]').forEach(input => {
            coApplicantsTotal += parseFloat(input.value) || 0;
        });

        const totalPercentage = primaryPercentage + coApplicantsTotal;
        const remainingPercentage = 100 - totalPercentage;

        const elements = {
            primaryApplicantPercentage: primaryPercentage.toFixed(2),
            coApplicantsPercentage: coApplicantsTotal.toFixed(2),
            totalApplicantPercentage: totalPercentage.toFixed(2),
            remainingApplicantPercentage: Math.max(0, remainingPercentage).toFixed(2)
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
                element.classList.toggle('text-danger', totalPercentage > 100);
            }
        });
    } catch (error) {
        console.error('Error updating percentage summary:', error);
    }
}

function toggleAddButton() {
    addApplicantBtn.disabled = applicantCount >= 3;
}

addApplicantBtn.addEventListener("click", () => {
    if (applicantCount < 3) {
        applicantCount++;
        const newApplicantDiv = document.createElement("div");
        newApplicantDiv.classList.add(
            "rounded",
            "mb-2",
            "border",
            "p-4",
            "addNewApplicant"
        );
        newApplicantDiv.id = `addNewApplicant${applicantCount + 110}`;

        newApplicantDiv.innerHTML = `
            <div class="form-group row mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-gray-900">First Name:</label>
                    <input type="text" name="coApplicantfirstName[]" class="form-control mb-2 mb-md-0" placeholder="Enter first name" required>
                    <span class="error-message" data-error-id="coApplicantfirstNameError"></span>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-gray-900">Last Name:</label>
                    <input type="text" name="coApplicantlastName[]" class="form-control mb-2 mb-md-0" placeholder="Enter last name" required>
                    <span class="error-message" data-error-id="coApplicantlastNameError"></span>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-gray-900">Email:</label>
                    <input type="email" name="coApplicantemail[]" class="form-control mb-2 mb-md-0" placeholder="Enter email address" required>
                    <span class="error-message" data-error-id="coApplicantemailError"></span>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-gray-900">Percentage Amount:</label>
                    <input type="number" 
                           name="coApplicantamount[]" 
                           onblur="validateApplicantPercentageInput(this)" 
                           class="form-control mb-2 mb-md-0" 
                           placeholder="Enter percentage (1-100)"
                           min="1"
                           max="100"
                           required>
                    <span class="error-message" data-error-id="coApplicantamountError"></span>
                </div>
                <div class="col-md-2">
                    <a href="javascript:;" class="btn btn-sm btn-flex flex-center btn-light-danger mt-3 mt-md-9 deleteApplicantBtn">
                        Delete
                    </a>
                </div>
            </div>
        `;

        applicantsContainer.appendChild(newApplicantDiv);

        newApplicantDiv
            .querySelector(".deleteApplicantBtn")
            .addEventListener("click", () => {
                newApplicantDiv.remove();
                applicantCount--;
                toggleAddButton();
                updateApplicantPercentageSummary();
            });

        toggleAddButton();
    }
});

document.querySelectorAll(".deleteApplicantBtn").forEach((deleteBtn) => {
    deleteBtn.addEventListener("click", (event) => {
        event.target.closest(".addNewApplicant").remove();
        applicantCount--;
        toggleAddButton();
        updateApplicantPercentageSummary();
    });
});

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function () {
    const primaryInput = document.getElementById('coApplicanRentPrimaryAmount');
    if (primaryInput) {
        primaryInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^\d.]/g, '');
            validateApplicantPercentageInput(this);
        });
    }

    document.querySelectorAll('input[name="coApplicantamount[]"]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^\d.]/g, '');
            validateApplicantPercentageInput(this);
        });
    });
});

toggleAddButton(); // Initial check for button status



document
    .querySelector("[data-kt-stepper-action='submit']")
    .addEventListener("click", async function () {

        function getAccountTypeDetails() {
            const accountType = {
                goal:
                    document.querySelector(
                        "input[name='rent_account_type']:checked"
                    )?.value || null,
                plan: null,
                applicationType: null,
                paymentSetup: null,
                creditCardLimit: null,
                creditCardDueDate: null,
                primaryRentOrMortgageAmount: null,
                coApplicants: [],
            };

            // Determine if the user selected "rent" or "mortgage"
            if (accountType.goal === "rent") {
                accountType.plan =
                    document.querySelector(
                        "input[name='account_plan_rent']:checked"
                    )?.value || null;
                accountType.applicationType =
                    document.querySelector(
                        "input[name='rent_account_plan_type']:checked"
                    )?.value || null;
                accountType.paymentSetup =
                    document.querySelector(
                        "input[name='rent_account_plan_type_mode']:checked"
                    )?.value || null;

                // Capture credit card limit and due date if "Pay Rent and Build Credit" is selected
                if (accountType.plan === "pay_rent_build") {
                    accountType.creditCardLimit = document.querySelector(
                        "input[name='credit_card_limit']"
                    )?.value || 0;
                    accountType.creditCardDueDate =
                        document.querySelector(
                            "select[name='rent_monthly_CC_day']"
                        )?.value || null;
                }

                // Get primary rent amount if Co-Applicant
                if (accountType.applicationType === "co_applicant") {
                    accountType.primaryRentOrMortgageAmount =
                        document.getElementById("coApplicanRentPrimaryAmount")
                            ?.value || 0;

                    // Get each co-applicant's details
                    document
                        .querySelectorAll(
                            "#applicantsContainer .addNewApplicant"
                        )
                        .forEach((applicant, index) => {
                            const coApplicant = {
                                firstName:
                                    applicant.querySelector(
                                        "input[name='coApplicantfirstName[]']"
                                    ).value || "N/A",
                                lastName:
                                    applicant.querySelector(
                                        "input[name='coApplicantlastName[]']"
                                    ).value || "N/A",
                                email:
                                    applicant.querySelector(
                                        "input[name='coApplicantemail[]']"
                                    ).value || "N/A",
                                rentAmount: parseFloat(
                                    applicant.querySelector(
                                        "input[name='coApplicantamount[]']"
                                    ).value || 0
                                ),
                            };
                            accountType.coApplicants.push(coApplicant);
                        });
                }
            } else if (accountType.goal === "mortgage") {
                accountType.plan =
                    document.querySelector(
                        "input[name='account_plan_mortgage']:checked"
                    )?.value || null;
                accountType.applicationType =
                    document.querySelector(
                        "input[name='mortgage_account_plan_type']:checked"
                    )?.value || null;
                accountType.paymentSetup =
                    document.querySelector(
                        "input[name='mortgage_account_plan_type_mode']:checked"
                    )?.value || null;

                // Capture credit card limit and due date if "Pay Mortgage and Build Credit" is selected
                if (accountType.plan === "pay_mortgage_build") {
                    accountType.creditCardLimit = parseFloat(
                        document.querySelector(
                            "input[name='mortgage_credit_card_limit']"
                        )?.value || 0
                    );
                    accountType.creditCardDueDate =
                        document.querySelector(
                            "select[name='mortgage_credit_card_limit_monthly_day']"
                        )?.value || null;
                }

                // Get primary mortgage amount if Co-Owner
                if (accountType.applicationType === "co_owner") {
                    accountType.primaryRentOrMortgageAmount = parseFloat(
                        document.getElementById(
                            "coApplicanMortgagePrimaryAmount"
                        )?.value || 0
                    );

                    // Get each co-owner's details
                    document
                        .querySelectorAll(
                            "#applicantsContainer2 .addNewApplicant2"
                        )
                        .forEach((applicant, index) => {
                            const coOwner = {
                                firstName:
                                    applicant.querySelector(
                                        "input[name='mortgagecoOwnerfirstName[]']"
                                    ).value || "N/A",
                                lastName:
                                    applicant.querySelector(
                                        "input[name='mortgagecoOwnerlastName[]']"
                                    ).value || "N/A",
                                email:
                                    applicant.querySelector(
                                        "input[name='mortgagecoOwneremail[]']"
                                    ).value || "N/A",
                                mortgageAmount: parseFloat(
                                    applicant.querySelector(
                                        "input[name='mortgagecoOwneramount[]']"
                                    ).value || 0
                                ),
                            };
                            accountType.coApplicants.push(coOwner);
                        });
                }
            }

            console.log(accountType);

            return accountType;
        }

        function getLandlordOrFinanceDetails() {
            const financeDetails = {
                paymentMethod: null,
                email: null,
                bankDetails: null,
                landlordType: null,
                landlordInfo: {},
            };

            // Determine the payment method (landlord for rent or mortgage finance)
            const landlordPaymentMethod = document.querySelector(
                "input[name='landlord_account_mode']:checked"
            );
            const mortgageFinanceMethod = document.querySelector(
                "input[name='mortgage_financer_account_mode']:checked"
            );

            if (landlordPaymentMethod) {
                financeDetails.paymentMethod = landlordPaymentMethod.value;
                if (landlordPaymentMethod.value === "rentInterac") {
                    financeDetails.email =
                        document.getElementById("interacEmailInput").value ||
                        null;
                }
            } else if (mortgageFinanceMethod) {
                financeDetails.paymentMethod = mortgageFinanceMethod.value;

                // Collect EFT/ACH Bank Details if selected
                if (mortgageFinanceMethod.value === "EFT") {
                    // Get the values from EFT input fields
                    financeDetails.mortgageEftDetails = {
                        institutionNumber:
                            document.querySelector(
                                "input[name='mortgage_eft_institution_number']"
                            )?.value || null,
                        transitNumber:
                            document.querySelector(
                                "input[name='mortgage_eft_transit_number']"
                            )?.value || null,
                        accountNumber:
                            document.querySelector(
                                "input[name='mortgage_eft_account_number']"
                            )?.value || null,
                        bankAccountNumber:
                            document.querySelector(
                                "input[name='mortgage_eft_bank_account_number']"
                            )?.value || null,
                        lenderName:
                            document.querySelector(
                                "input[name='mortgage_eft_lender_name']"
                            )?.value || null,
                        lenderAddress:
                            document.querySelector(
                                "input[name='mortgage_eft_lender_address']"
                            )?.value || null,
                        refNumber:
                            document.querySelector(
                                "input[name='mortgage_eft_ref_number']"
                            )?.value || null,
                        paymentFrequency:
                            document.querySelector(
                                "select[name='mortgage_eft_payment_frequency']"
                            )?.value || null,
                        biWeeklyDueDate:
                            document.querySelector(
                                "input[name='mortgage_eft_bi_weekly_due_date']"
                            )?.value || null,
                    };
                } else if (mortgageFinanceMethod.value === "mortgage_cheque") {
                    // Get the values from Cheque input fields
                    financeDetails.mortgageChequeDetails = {
                        accountNumber:
                            document.querySelector(
                                "input[name='mortgage_cheque_account_number']"
                            )?.value || null,
                        transitNumber:
                            document.querySelector(
                                "input[name='mortgage_cheque_transit_number']"
                            )?.value || null,
                        institutionNumber:
                            document.querySelector(
                                "input[name='mortgage_cheque_institution_number']"
                            )?.value || null,
                        name:
                            document.querySelector(
                                "input[name='mortgage_cheque_name']"
                            )?.value || null,
                        address:
                            document.querySelector(
                                "input[name='mortgage_cheque_address']"
                            )?.value || null,
                    };
                }
            }

            // Determine landlord type (business or individual) and collect related details
            const landlordType = document.querySelector(
                "input[name='account_plan_type_mode_lanlord']:checked"
            );
            if (landlordType) {
                financeDetails.landlordType = landlordType.value;
                if (landlordType.value === "business") {
                    financeDetails.landlordInfo = {
                        businessName:
                            document.querySelector(
                                "input[name='business_name']"
                            ).value || "N/A",
                    };
                } else if (landlordType.value === "individual") {
                    financeDetails.landlordInfo = {
                        firstName:
                            document.querySelector(
                                "input[name='individual_first_name']"
                            ).value || "N/A",
                        lastName:
                            document.querySelector(
                                "input[name='individual_last_name']"
                            ).value || "N/A",
                        middleName:
                            document.querySelector(
                                "input[name='individual_middle_name']"
                            ).value || "N/A",
                    };
                }
            }

            return financeDetails;
        }

        const formDataJson = {
            personalDetails: {
                firstName: document.querySelector("input[name='first_name']").value,
                password: document.querySelector("input[name='password']").value,
                passwordConfirmation: document.querySelector("input[name='password_confirmation']").value,
                lastName: document.querySelector("input[name='last_name']").value,
                middleName: document.querySelector("input[name='middle_name']").value,
                email: document.querySelector("input[name='email_address']").value,
                phone: document.querySelector("input[name='phone_number']").value,
            },
            accountType: getAccountTypeDetails(),
            getLandlordOrFinanceDetails: getLandlordOrFinanceDetails(),
            addressDetails: {
                // Remove file from JSON structure
                address: document.getElementById("address-input").value || "N/A",
                province: document.getElementById("province").value || "N/A",
                city: document.getElementById("city").value || "N/A",
                postalCode: document.querySelector("input[name='postal_code']").value || "N/A",
                unitNumber: document.querySelector("input[name='house_number']").value || "N/A",
                houseNumber: document.querySelector("input[name='house_number']").value || "N/A",
                streetName: document.querySelector("input[name='house_number']").value || "N/A",
                rentAmount: parseFloat(document.getElementById("amount_admin_pay").value) || 0,
                reOccurringMonthlyDay: document.querySelector("select[name='re_occuring_monthly_day']").value || "N/A",
                duration: {
                    from: document.querySelector("input[name='duration_from']").value || "N/A",
                    to: document.querySelector("input[name='duration_to']").value || "N/A",
                },
            }
        };

        // Create FormData and append both JSON and file
        const formData = new FormData();

        // Convert and append JSON data
        const jsonString = JSON.stringify(formDataJson);
        formData.append('data', jsonString);


        // Get and append file
        const tenancyFile = document.querySelector("input[name='tenancy_agreement']").files[0];
        if (tenancyFile) {
            formData.append('tenancyAgreement', tenancyFile);
        }


        // Send the data to the Laravel endpoint
        saveData(formData);
    });


function saveData(formData) {
    console.log('FormData received in saveData:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    console.log(formData);


    const overlay = document.getElementById("overlayForm");
    const progressBar = document.querySelector(".progress-form");
    const overlayText = document.getElementById("overlayTextForm");

    // Show overlay and initialize progress bar and percentage
    overlay.style.display = "flex";
    overlayText.textContent = "Encrypting your data...";
    progressBar.style.width = "0%";

    // Calculate progress increments for each step
    const totalSteps = 3;
    const incrementWidth = 100 / totalSteps;

    // Start the progress bar animation with initial delay
    setTimeout(() => {
        progressBar.style.width = `${incrementWidth}%`;

        // Step 1: Encrypting (5 seconds)
        setTimeout(() => {
            overlayText.textContent = "Saving your data...";
            progressBar.style.width = `${incrementWidth * 2}%`;

            // Step 2: Saving (another 5 seconds)
            setTimeout(() => {
                overlayText.textContent = "Processing data...";
                progressBar.style.width = `${incrementWidth * 3}%`;

                // overlay.style.display = "none";
                // return;
                // Step 3: Processing (another 5 seconds)
                setTimeout(async () => {
                    try {
                        const response = await fetch(saveFormRoute, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: formData,
                        });
                    
                        if (response.ok) {
                            const data = await response.json();
                            console.log(data);
                    
                            if (data.status === "success") {
                                overlayText.textContent = "Data saved successfully! Redirecting...";
                                progressPercentage.textContent = "100%"; // Complete percentage
                                setTimeout(() => {
                                    overlay.style.display = "none";
                                    Swal.fire({
                                        text: "Registration successful",
                                        icon: "success",
                                        buttonsStyling: false,
                                        timer: 1000,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-success",
                                        },
                                    });
                                }, 2000);
                            } else {
                                Swal.fire({
                                    text: "An error occurred. Please try again.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Retry",
                                    customClass: {
                                        confirmButton: "btn btn-danger",
                                    },
                                });
                            }
                        } else if (response.status === 422) {
                            const errorData = await response.json();
                            console.log(errorData);
                            handleValidationErrors(errorData.errors); // Use the updated function here
                            overlay.style.display = "none";
                            Swal.fire({
                                text: "Failed to save data. Kindly check all the required fields",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger",
                                },
                            });
                        } else {
                            overlay.style.display = "none";
                            showToast("Failed to save data.", "error");
                            Swal.fire({
                                text: "Failed to save data.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger",
                                },
                            });
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        showToast("An error occurred while saving data.", "error");
                        overlay.style.display = "none";
                        Swal.fire({
                            text: "An error occurred while saving data.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-danger",
                            },
                        });
                    }
                    
                }, 3000);
            }, 3000);
        }, 3000);
    }, 300);
}

// function handleValidationErrors(errors) {
//     for (const field in errors) {
//         const errorMessage = `${field}: ${errors[field].join(", ")}`;
//         showToast(errorMessage, "error"); // Display each field error as a toast message
//     }
// }
function handleValidationErrors(errors) {
    for (const field in errors) {
        // Get only the error message(s) without the field name
        const errorMessage = errors[field].join(", "); // Join multiple errors if present
        showValidationMessage(errorMessage); // Use the styled validation message
        showToast(errorMessage, "error"); // Display the error message as a toast
    }
}
// Show validation message
function showValidationMessage(message) {
    const messageContainer = document.getElementById("validationMessage");
    const messageText = document.getElementById("validationMessageText");
  
    messageText.textContent = message; // Set the validation message
    messageContainer.classList.add("flex"); // Add the hidden class
    // messageContainer.style.display = "flex"; // Show the container
  
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
      closeValidationMessage();
    }, 5000);
  }
  
  // Hide validation message
  function closeValidationMessage() {
    const messageContainer = document.getElementById("validationMessage");
    if (messageContainer) {
        messageContainer.classList.add("hidden"); // Add the hidden class
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    const closeButton = document.querySelector("#validationMessage .btn-close");
    if (closeButton) {
        closeButton.addEventListener("click", closeValidationMessage);
    }
});

  

// Function to reset the progress bar
function resetProgressBar() {
    const progress = document.querySelector(".progress-form");
    progress.style.width = "0%";
}

// Function to animate the progress bar
function animateProgressBar() {
    const progressBar = document.querySelector(".progress-form");
    progressBar.style.width = "100%";
}


// Set up the SweetAlert for confirming page reload
// window.addEventListener("beforeunload", function (event) {
//     // We want to prevent the page from leaving unless the user confirms it.
//     event.preventDefault();
//     event.returnValue = ''; // Standard for modern browsers

//     // Custom SweetAlert message when the user tries to reload
//     // Swal.fire({
//     //     text: "Are you sure you want to leave this page?",
//     //     icon: "warning",
//     //     showCancelButton: true,
//     //     buttonsStyling: false,
//     //     confirmButtonText: "Yes, reload",
//     //     cancelButtonText: "No, stay here",
//     //     customClass: {
//     //         confirmButton: "btn btn-danger",
//     //         cancelButton: "btn btn-secondary"
//     //     }
//     // }).then((result) => {
//     //     if (result.isConfirmed) {
//     //         // User confirmed the reload, so proceed with the reload
//     //         window.location.reload(); // Reload the page
//     //     }
//     //     // If not confirmed, do nothing (stay on the page)
//     // });
// });
