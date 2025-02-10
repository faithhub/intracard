
function initAutocomplete() {
    // Get the input element
    const input = document.getElementById("address-input");

    // Set up autocomplete with options for Canada only
    const options = {
        componentRestrictions: { country: "ca" },
        fields: ["address_components", "geometry", "formatted_address"],
        types: ["address"] // Only look for address results
    };

    // Initialize Google Autocomplete
    const autocomplete = new google.maps.places.Autocomplete(input, options);

    // Optional: Add event listener for when a place is selected
    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        console.log("Selected Place:", place);
    });
}

window.initAutocomplete = initAutocomplete;


document.addEventListener("DOMContentLoaded", () => {
    // Select the radio buttons
    const rentOption = document.getElementById("kt_create_account_form_account_type_personal");
    const mortgageOption = document.getElementById("kt_create_account_form_account_type_corporate");

    // Select the elements where text changes will occur
    const rentFinance = document.getElementById("rentFinance"); // For changing the main title text
    const mortgageFinance = document.getElementById("mortgageFinance"); // For changing the main title text
    const uploadAgreement = document.getElementById("uploadAgreement"); // For changing the main title text
    const rentMortgageAmount = document.getElementById("rentMortgageAmount"); // For changing the main title text
    const RentalMortAddress = document.getElementById("RentalMortAddress"); // For changing the main title text
    const RentalMortAddressMuted = document.getElementById("RentalMortAddressMuted"); // For changing the main title text
    const AddressNameDiv = document.getElementById("AddressNameDiv"); // For changing the main title text
    const landlordDetails2 = document.getElementById("landlordDetails2"); // For changing the main title text
    const landLoradSmall2 = document.getElementById("landLoradSmall2");   // For changing the subtitle text
    const landlordDetails = document.getElementById("landlordDetails"); // For changing the main title text
    const landLoradSmall = document.getElementById("landLoradSmall");   // For changing the subtitle text

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
            RentalMortAddressMuted.textContent = " Enter your rental address and unit number if applicable..";
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
            RentalMortAddressMuted.textContent = " Enter your mortgage and unit number if applicable..";
            AddressNameDiv.textContent = "Setup your mortgage address";
            landlordDetails.textContent = "Mortgage Financer";
            landLoradSmall.textContent = "How does your mortgage financer accept payment?";
            landlordDetails2.textContent = "Mortgage Financer";
            landLoradSmall2.textContent = "Setup your mortgage financer details";
        }

        // Debugging to check the changes
        // console.log(`Main Text: ${landlordDetails.textContent}`);
        // console.log(`Sub Text: ${landLoradSmall.textContent}`);
    }

    // Attach the event listeners to each radio button
    if (rentOption) rentOption.addEventListener("change", updateContentBasedOnSelection);
    if (mortgageOption) mortgageOption.addEventListener("change", updateContentBasedOnSelection);
});


function showToast(message, type) {
    const toastContainer = document.getElementById("toastContainer");

    // Create a new toast element
    const toast = document.createElement("div");
    toast.className = `toast align-items-center text-bg border-0`;
    //  toast.className = `toast align-items-center text-bg-${type} border-0`;
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    // Define the toast title and background color based on the type
    const title = type.charAt(0).toUpperCase() + type.slice(1); // Capitalize first letter

    // Toast content with close button and auto-dismiss setup
    toast.innerHTML = `
         <div class="toast-header">
             <span class="me-2">${type === "success" ? "✔️" : type === "error" ? "❌" : "⚠️"}</span>
             <strong class="me-auto">${title}</strong>
             <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
         </div>
         <div class="toast-body">
             ${message}
         </div>
     `;

    // Append the toast to the container and initialize it
    toastContainer.appendChild(toast);
    const bootstrapToast = new bootstrap.Toast(toast, { delay: 3000, autohide: true }); // Set autohide with delay
    bootstrapToast.show();

    // Automatically remove the toast element after it hides
    toast.addEventListener("hidden.bs.toast", () => {
        toast.remove();
    });
}

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

    if (type === 'email') {
        sessionStorage.setItem('storedEmail', value);
    } else if (type === 'phone') {
        sessionStorage.setItem('storedPhone', value);
    }
}


function validateNumericInput() {// Remove any non-numeric characters
    input.value = input.value.replace(/\D/g, "");
}

function validateCodeInput() {// Remove any non-numeric characters
    input.value = input.value.replace(/\D/g, "");

    // Limit input to 6 characters
    if (input.value.length > 6) {
        input.value = input.value.slice(0, 6);
    }
}

async function validatePhoneCode() {
    const phoneInput = document.getElementById("phoneInput").value;
    const enteredCode = document.getElementById("phoneCodeInput").value;
    const validateButton = document.getElementById("validatePhoneCodeButton");
    const getCodeButton = document.getElementById("getPhoneCodeButton");
    const spinner = document.getElementById("phoneValidationSpinner");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ phone: fullPhoneNumber, code: enteredCode }),
        });

        const result = await response.json();
        console.log(result);

        if (response.ok) {
            showToast(result.message, "success");

            // Hide the code input field
            document.getElementById("phoneCodeValidationSection").style.display = "none";

            // Change "Get Code" button to "Verified" and disable it
            getCodeButton.innerText = "Verified";
            getCodeButton.disabled = true;
            isPhoneVerified = true;
        } else {
            // Handle specific error cases based on response status code
            if (response.status === 422) {
                // Validation error or invalid code
                const errorMessage = result.errors?.phone?.[0] || result.message || "Invalid verification code or format.";
                showToast(errorMessage, "error");
            } else if (response.status === 500) {
                // Internal server error
                const errorMessage = result.error || "An unexpected error occurred during verification.";
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

// Function to send phone verification code
async function sendPhoneCode() {
    const phoneInput = document.getElementById("phoneInput").value;
    const getCodeButton = document.getElementById("getPhoneCodeButton");
    const spinner = document.getElementById("phoneSpinner");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Validate phone number and ensure it's not empty
    if (!phoneInput || phoneInput.length !== 10) {
        showToast("Please enter a valid 10-digit phone number.", "error");
        return;
    }

    // Check if the phone number matches the stored value (only if persistence is enabled)
    const storedPhone = usePersistentVerification ? sessionStorage.getItem('storedPhone') : null;
    if (storedPhone === phoneInput) {
        showToast("Phone number is already verified.", "success");
        getCodeButton.innerText = "Verified";
        getCodeButton.disabled = true;
        return;
    }

    // If phone number has changed, clear previous verification if persistence is enabled
    if (usePersistentVerification) sessionStorage.removeItem('storedPhone');

    try {
        // Show spinner and disable button
        spinner.style.display = "inline-block";
        getCodeButton.disabled = true;

        // Send the phone number to the server to generate and send the code
        const response = await fetch(sendPhoneVerificationCodeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ phone: "+234" + phoneInput }),
        });

        const result = await response.json();

        if (response.ok) {
            showToast(result.message, "success");

            // Store phone number in sessionStorage if verification is successful and persistence is enabled
            if (usePersistentVerification) storeVerificationDetails('phone', phoneInput);

            // Start countdown on the "Get Code" button
            startCountdown(getCodeButton, 'phone');

            // Show the code validation input section
            document.getElementById("phoneCodeValidationSection").style.display = "block";
        } else {
            // Handle specific error cases based on the response status code
            if (response.status === 422) {
                const errorMessage = result.errors?.phone?.[0] || "Invalid phone number format.";
                showToast(errorMessage, "error");
            } else if (response.status === 500) {
                const errorMessage = result.error || "An error occurred while sending the code via SMS.";
                showToast(errorMessage, "error");
            } else {
                showToast(result.message || "Failed to send code.", "error");
            }
            getCodeButton.disabled = false;
        }
    } catch (error) {
        showToast("An unexpected error occurred while sending the code.", "error");
        console.error(error);
        getCodeButton.disabled = false;
    } finally {
        spinner.style.display = "none";
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


// Function to handle countdown for the "Get Code" button
// function startCountdown(button, type) {
//     // Check verification flag based on type
//     const isVerified = type === 'email' ? emailVerified : phoneVerified;

//     // Skip countdown if verification is complete
//     if (isVerified) return;

//     let timeRemaining = 4 * 60; // 4 minutes in seconds

//     button.disabled = true;

//     const countdownInterval = setInterval(() => {
//         const isVerifiedDuringCountdown = type === 'email' ? emailVerified : phoneVerified;

//         if (isVerifiedDuringCountdown) {
//             clearInterval(countdownInterval);
//             return;
//         }

//         const minutes = Math.floor(timeRemaining / 60);
//         const seconds = timeRemaining % 60;

//         button.innerText = `Resend in ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
//         timeRemaining--;

//         if (timeRemaining < 0 && !isVerifiedDuringCountdown) {
//             clearInterval(countdownInterval);
//             button.disabled = false;
//             button.innerText = "Get Code"; // Reset button text only if not verified
//         }
//     }, 1000); // Update every second
// }
// Countdown function (existing implementation)

function startCountdown(button, type, usePersistentVerification = false) {
    const isVerified = (type === 'email' && sessionStorage.getItem('storedEmail')) ||
        (type === 'phone' && sessionStorage.getItem('storedPhone'));

    // Only consider isVerified if usePersistentVerification is true
    if (usePersistentVerification && isVerified) {
        console.log("Countdown aborted, already verified.");
        return;
    }

    let timeRemaining = 4 * 60; // 4 minutes in seconds
    button.disabled = true;

    const countdownInterval = setInterval(() => {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        button.innerText = `Resend in ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        timeRemaining--;

        if (timeRemaining < 0) {
            clearInterval(countdownInterval);
            button.disabled = false;
            button.innerText = "Get Code"; // Reset button text
        }
    }, 1000); // Update every second
}


// Function to send email verification code
async function sendEmailCode() {
    const email = document.getElementById("emailInputField").value;
    const name = document.getElementById("first_name").value;
    const getCodeButton = document.getElementById("getCodeButton");
    const spinner = document.getElementById("spinner");
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const emailPattern = /^[\w.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Validate email format and ensure it's not empty
    if (!email || !emailPattern.test(email)) {
        showToast("Please enter a valid email address.", "error");
        return;
    }

    // Check if the email matches the stored value (only if persistence is enabled)
    const storedEmail = usePersistentVerification ? sessionStorage.getItem('storedEmail') : null;
    if (storedEmail === email) {
        showToast("Email is already verified.", "success");
        getCodeButton.innerText = "Verified";
        getCodeButton.disabled = true;
        return;
    }

    // If email has changed, clear previous verification if persistence is enabled
    if (usePersistentVerification) sessionStorage.removeItem('storedEmail');

    try {
        // Show spinner and disable button
        spinner.style.display = "inline-block";
        getCodeButton.disabled = true;

        // Send the email to the server to generate and send the code
        const response = await fetch(sendEmailVerificationCodeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ email, name: name || 'Customer' }),
        });

        const result = await response.json();

        if (response.ok) {
            showToast(result.message, "success");

            // Store email in sessionStorage if verification is successful and persistence is enabled
            if (usePersistentVerification) storeVerificationDetails('email', email);

            // Start countdown on the "Get Code" button
            startCountdown(getCodeButton, 'email');

            // Show the code input section
            document.getElementById("emailValidationSection").style.display = "block";
        } else {
            // Handle specific error cases based on the response status code
            if (response.status === 422) {
                const errorMessage = result.errors?.email?.[0] || "Invalid email format.";
                showToast(errorMessage, "error");
            } else if (response.status === 500) {
                const errorMessage = result.error || "An error occurred while sending the email.";
                showToast(errorMessage, "error");
            } else {
                showToast(result.message || "Failed to send code.", "error");
            }
            getCodeButton.disabled = false;
        }
    } catch (error) {
        showToast("An unexpected error occurred while sending the code.", "error");
        console.error(error);
        getCodeButton.disabled = false;
    } finally {
        spinner.style.display = "none";
    }
}

async function validateEmailCode() {
    const email = document.getElementById("emailInputField").value;
    const enteredCode = document.getElementById("emailCodeInput").value;
    const validateButton = document.getElementById("validateEmailCodeButton");
    const getCodeButton = document.getElementById("getCodeButton");
    const spinner = document.getElementById("validationSpinner"); // Spinner for the validate button
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!enteredCode || enteredCode.length !== 6) {
        showToast("Code must be exactly 6 digits long.", "error");
        return;
    }

    try {
        // Show spinner and disable validate button
        spinner.style.display = "inline-block";
        validateButton.disabled = true;

        // Send the email and entered code to the server for verification
        const response = await fetch(validateEmailCodeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken, // Updated CSRF token
            },
            body: JSON.stringify({ email, code: enteredCode }),
        });

        const result = await response.json();

        if (response.ok) {
            showToast(result.message, "success");

            // Set emailVerified flag to true
            isEmailVerified = true;

            // Hide the code input field
            document.getElementById("emailValidationSection").style.display = "none";

            // Change "Get Code" button to "Verified" and disable it
            getCodeButton.innerText = "Verified";
            getCodeButton.disabled = true;
        } else {
            // Handle specific error cases based on response status code
            if (response.status === 422) {
                // Validation error or invalid code
                const errorMessage = result.errors?.email?.[0] || result.message || "Invalid verification code or format.";
                showToast(errorMessage, "error");
            } else if (response.status === 500) {
                // Internal server error
                const errorMessage = result.error || "An unexpected error occurred during verification.";
                showToast(errorMessage, "error");
            } else {
                // Generic error handling
                showToast(result.message || "Invalid code.", "error");
            }
        }
    } catch (error) {
        showToast("An error occurred during verification.", "error");
        console.error(error);
    } finally {
        // Hide spinner and re-enable validate button
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
    codeInput.value = codeInput.value.replace(/[^0-9]/g, "");  // Allow only numbers
}

// Function to validate the entered code
// function validateCode() {
//     const enteredCode = document.getElementById("codeInput").value;

//     if (!enteredCode) {
//         showToast("Please enter the code.", "error");
//     } else if (enteredCode.length < 6) {
//         showToast("Code must be 6 digits long.", "error");
//     } else if (enteredCode !== generatedCode) {
//         showToast("Invalid code. Please try again.", "error");
//     } else {
//         showToast("Code validated successfully!", "success");
//     }
// }



// Check radio buttons and populate amount_admin_pay based on conditions
function checkConditions() {
    const rentPlan = document.querySelector('input[name="rent_account_plan_type"]:checked');
    const mortgagePlan = document.querySelector('input[name="mortgage_account_plan_type"]:checked');
    const mortgageAmount = document.getElementById("coApplicantamountAdmin")?.value;

    if (rentPlan && rentPlan.value === "co_applicant") {
        document.getElementById("amount_admin_pay").value = mortgageAmount;
    } else if (mortgagePlan && mortgagePlan.value === "co_applicant") {
        document.getElementById("amount_admin_pay").value = mortgageAmount;
    }
    console.log(mortgageAmount);
}


// Toggle multiple credit cards option
function toggleMultipleCards() {
    const container = document.getElementById("additionalCardsContainer");
    const addButton = document.getElementById("addCardButton");
    const isMultiple = document.getElementById("multipleCardsToggle").checked;

    container.style.display = isMultiple ? "block" : "none";
    addButton.style.display = isMultiple ? "inline-block" : "none";

    // Clear existing cards if toggled off
    if (!isMultiple) {
        container.innerHTML = "";
    }
}

// Function to add additional credit card inputs
let cardCount = 0; // Track the number of additional cards

function addCreditCard() {
    // Limit to a maximum of 2 additional cards
    if (cardCount >= 2) {
        alert("You can only add a maximum of two additional cards.");
        return;
    }

    // Create a new card container for the additional card
    const container = document.createElement("div");
    container.classList.add("additional-card", "p-5", "m-3", "border", "rounded", "mt-3");
    container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold">Card ${cardCount + 2}</h5>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeCard(this)">Delete</button>
            </div>
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                    <span class="required">Name On Card</span>
                </label>
                <input type="text" class="form-control form-control-solid" placeholder="Name on Card" name="additional_card_name_${cardCount}">
            </div>
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>
                <input type="text" class="form-control form-control-solid" placeholder="Enter card number" name="additional_card_number_${cardCount}">
            </div>
            <div class="row mb-10">
                <div class="col-md-8 fv-row">
                    <label class="required fs-6 fw-semibold form-label mb-2">Expiration Date</label>
                    <div class="row">
                        <div class="col-6">
                            <select name="additional_card_expiry_month_${cardCount}" class="form-select form-select-solid">
                                <option value="">Month</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <!-- Continue up to 12 -->
                            </select>
                        </div>
                        <div class="col-6">
                            <select name="additional_card_expiry_year_${cardCount}" class="form-select form-select-solid">
                                <option value="">Year</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <!-- Continue as needed -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 fv-row">
                    <label class="required fs-6 fw-semibold form-label mb-2">CVV</label>
                    <input type="text" class="form-control form-control-solid" placeholder="CVV" name="additional_card_cvv_${cardCount}" minlength="3" maxlength="4">
                </div>
            </div>
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="required fs-6 fw-semibold form-label mb-2">Amount to Deduct</label>
                <input type="number" class="form-control form-control-solid" placeholder="Enter amount" name="additional_card_amount_${cardCount}" oninput="validateTotalAmount()">
            </div>
        `;

    // Append the new card container to the additional cards container
    document.getElementById("additionalCardsContainer").appendChild(container);
    cardCount++;
}

// Function to remove a card
function removeCard(button) {
    // Remove the parent card container of the clicked delete button
    button.parentElement.parentElement.remove();
    cardCount--; // Decrement the card count
}

// Validate total amount deduction
function validateTotalAmount() {
    const amountAdminPay = parseFloat(document.getElementById("amount_admin_pay")?.value || 0);
    const additionalAmounts = Array.from(document.querySelectorAll('input[name^="additional_card_amount_"]'))
        .map(input => parseFloat(input.value) || 0);

    const totalAdditionalAmount = additionalAmounts.reduce((sum, amount) => sum + amount, 0);

    if (totalAdditionalAmount > amountAdminPay) {
        alert("The total deduction amount exceeds the amount to be paid.");
    }
}

// Run checkConditions on page load
document.addEventListener("DOMContentLoaded", checkConditions);

function toggleEFTInput() {
    const EFTInputdiv = document.getElementById("EFTInputdiv");
    const ACHInputdiv = document.getElementById("ACHInputdiv");
    const interacHideThisButton = document.getElementById("nextButtonLandLord1");
    const selectedOption = document.querySelector('input[name="mortgage_financer_account_mode"]:checked');

    if (selectedOption) {
        console.log(selectedOption);

        if (selectedOption.value === "EFT") {
            EFTInputdiv.style.display = "block"; // Show email input if "Interac" is selected
            ACHInputdiv.style.display = "none"; // Show email input if "Interac" is selected
            interacHideThisButton.style.display = "none"; // Show email input if "Interac" is selected
        } else if (selectedOption.value === "ACH") {
            interacHideThisButton.style.display = "none"; // Hide email input if any other option is selected
            ACHInputdiv.style.display = "block"; // Hide email input if any other option is selected
            EFTInputdiv.style.display = "none"; // Hide email input if any other option is selected
        } else {
            interacHideThisButton.style.display = "none"; // Hide email input if any other option is selected
            ACHInputdiv.style.display = "none"; // Hide email input if any other option is selected
            EFTInputdiv.style.display = "none"; // Hide email input if any other option is selected
        }
    }
}

function toggleEmailInput() {
    const emailInput = document.getElementById("emailInput");
    const interacHideThisButton = document.getElementById("nextButtonLandLord1");
    const selectedOption = document.querySelector('input[name="landlord_account_mode"]:checked');

    if (selectedOption) {
        console.log(selectedOption);

        if (selectedOption.value === "interac") {
            emailInput.style.display = "block"; // Show email input if "Interac" is selected
            interacHideThisButton.style.display = "none"; // Show email input if "Interac" is selected
        } else {
            interacHideThisButton.style.display = "block"; // Hide email input if any other option is selected
            emailInput.style.display = "none"; // Hide email input if any other option is selected
        }
    }
}

function chequeOptionForLandLord() {
    const landlordDetails4 = document.getElementById("landlordDetails4");
    const landlordDetails5 = document.getElementById("landlordDetails5");
    // const interacHideThisButton = document.getElementById("nextButtonLandLord1");
    const selectedOption = document.querySelector('input[name="account_plan_type_mode_lanlord"]:checked');

    if (selectedOption) {
        console.log(selectedOption);

        if (selectedOption.value === "business") {
            landlordDetails4.style.display = "block"; // Show email input if "Interac" is selected
            landlordDetails5.style.display = "none"; // Show email input if "Interac" is selected
        } else if (selectedOption.value === "individual") {
            landlordDetails5.style.display = "block"; // Hide email input if any other option is selected
            landlordDetails4.style.display = "none"; // Hide email input if any other option is selected
        } else {
            landlordDetails5.style.display = "none"; // Hide email input if any other option is selected
            landlordDetails4.style.display = "none";
        }
    }
}



document.addEventListener("DOMContentLoaded", function () {

    function validateCurrentStep2() {
        let isValid = true;
        const currentStep = document.querySelector(".Rent_mortgage_divs:not([style*='display: none'])");
        console.log("Current Step:", currentStep);

        // Validate input fields (text)
        const requiredInputs = currentStep.querySelectorAll("input[required]:not([type='radio'])");
        requiredInputs.forEach((input) => {
            const errorSpan = document.querySelector(`[data-error-id="${input.getAttribute("name")}Error"]`);
            if (!input.value.trim()) {
                console.log(`Input ${input.name} is empty.`);
                if (errorSpan) {
                    errorSpan.textContent = "This field is required.";
                    errorSpan.style.display = "block";
                }
                isValid = false;
            } else if (errorSpan) {
                errorSpan.textContent = ""; // Clear error message if valid
                errorSpan.style.display = "none";
            }

            // Clear error on input
            input.addEventListener("input", function () {
                if (errorSpan) {
                    errorSpan.textContent = "";
                    errorSpan.style.display = "none";
                }
            });
        });

        const requiredRadioGroups = new Set([...currentContent.querySelectorAll("input[type='radio'][required]")].map(input => input.name));
        requiredRadioGroups.forEach((groupName) => {
            const groupChecked = document.querySelector(`input[name="${groupName}"]:checked`);
            const errorSpan = document.querySelector(`[data-error-id="${groupName}Error"]`);
            if (!groupChecked) {
                console.log(`No selection made for radio group ${groupName}.`);
                if (errorSpan) {
                    errorSpan.textContent = "This field is required.";
                    errorSpan.style.display = "block";
                }
                isValid = false;
            } else if (errorSpan) {
                errorSpan.textContent = "";
                errorSpan.style.display = "none";
            }

            // Clear error on radio selection change
            document.querySelectorAll(`input[name="${groupName}"]`).forEach((radio) => {
                radio.addEventListener("change", function () {
                    if (errorSpan) {
                        errorSpan.textContent = "";
                        errorSpan.style.display = "none";
                    }
                });
            });
        });

        // Validate select fields
        const requiredSelects = currentStep.querySelectorAll("select[required]");
        requiredSelects.forEach((select) => {
            const errorSpan = document.querySelector(`[data-error-id="${select.getAttribute("name")}Error"]`);
            if (!select.value || select.value === "") {
                console.log(`Select ${select.name} has no selected value.`);
                if (errorSpan) {
                    errorSpan.textContent = "Please select an option.";
                    errorSpan.style.display = "block";
                }
                isValid = false;
            } else if (errorSpan) {
                errorSpan.textContent = "";
                errorSpan.style.display = "none";
            }

            // Clear error on select change
            select.addEventListener("change", function () {
                if (errorSpan) {
                    errorSpan.textContent = "";
                    errorSpan.style.display = "none";
                }
            });
        });

        console.log("Validation result:", isValid);
        return isValid;
    }


    const addButton = document.querySelector('[data-repeater-create]');
    const repeaterList = document.querySelector('[data-repeater-list="kt_docs_repeater_basic"]');
    const maxEntries = 3;

    updateAddButtonState(); // Initial button state check

    // Event listener for adding a new entry
    addButton.addEventListener("click", function () {
        const currentCount = repeaterList.querySelectorAll('[data-repeater-item]').length;

        if (currentCount < maxEntries) {
            // Clone the first item and clear its inputs
            const newEntry = repeaterList.querySelector('[data-repeater-item]').cloneNode(true);
            newEntry.querySelectorAll("input").forEach(input => input.value = ""); // Clear input values

            // Add delete button functionality for the new entry
            const deleteButton = newEntry.querySelector('[data-repeater-delete]');
            deleteButton.addEventListener("click", function () {
                newEntry.remove();
                updateAddButtonState(); // Update button state after deletion
            });

            repeaterList.appendChild(newEntry);
            updateAddButtonState(); // Update button state after addition
        }
    });

    // Function to enable/disable the add button based on the number of entries
    function updateAddButtonState() {
        const currentCount = repeaterList.querySelectorAll('[data-repeater-item]').length;
        addButton.disabled = currentCount >= maxEntries;
    }

    // Event listener for delete buttons on the default entry
    repeaterList.querySelectorAll('[data-repeater-delete]').forEach(deleteButton => {
        deleteButton.addEventListener("click", function () {
            const item = deleteButton.closest('[data-repeater-item]');
            item.remove();
            updateAddButtonState(); // Update button state after deletion
        });
    });
});

function updateCities() {
    // Map of provinces to their respective cities
    const provinceToCities = {
        "Ontario": ["Toronto", "Ottawa", "Hamilton"],
        "British Columbia": ["Vancouver", "Victoria", "Surrey"],
        "Quebec": ["Montreal", "Quebec City", "Laval"],
        "Alberta": ["Calgary", "Edmonton", "Red Deer"],
        "Manitoba": ["Winnipeg", "Brandon", "Steinbach"],
        "Nova Scotia": ["Halifax", "Sydney", "Dartmouth"],
        "Newfoundland and Labrador": ["St. John's", "Corner Brook", "Gander"],
        "New Brunswick": ["Moncton", "Fredericton", "Saint John"],
        "Prince Edward Island": ["Charlottetown", "Summerside"],
        "Saskatchewan": ["Regina", "Saskatoon", "Prince Albert"]
    };

    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city");

    // Get the selected province and find the corresponding cities
    const selectedProvince = provinceSelect.value;
    const cities = provinceToCities[selectedProvince] || [];

    // Clear existing city options
    citySelect.innerHTML = "<option value=''>--Select a City--</option>";

    // Add new city options based on the selected province
    cities.forEach(city => {
        const option = document.createElement("option");
        option.value = city;
        option.textContent = city;
        citySelect.appendChild(option);
    });
}

const applicantsContainer2 = document.getElementById('applicantsContainer2');
const addApplicantBtn2 = document.getElementById('addApplicantBtn2');
let applicantCount2 = 1;

function toggleAddButton2() {
    addApplicantBtn2.disabled = applicantCount2 >= 3;
}

addApplicantBtn2.addEventListener('click', () => {
    if (applicantCount2 < 3) {
        applicantCount2++;
        const newApplicantDiv2 = document.createElement('div');
        newApplicantDiv2.classList.add('rounded', 'mb-2', 'border', 'p-4', 'addNewApplicant2');
        newApplicantDiv2.id = `addNewApplicant2${applicantCount2 + 110}`;

        newApplicantDiv2.innerHTML = `
            <div class="form-group row mb-3">
                <div class="col-md-3">
                    <label class="form-label">First Name:</label>
                    <input type="text" name="mortgagecoOwnerfirstName[]" class="form-control mb-2 mb-md-0" placeholder="Enter first name">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Last Name:</label>
                    <input type="text" name="mortgagecoOwnerlastName[]" class="form-control mb-2 mb-md-0" placeholder="Enter last name">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="mortgagecoOwneremail[]" class="form-control mb-2 mb-md-0" placeholder="Enter email address">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mortgage Amount:</label>
                    <input type="numbeer" name="mortgagecoOwneramount[]"
                        class="form-control mb-2 mb-md-0"
                        placeholder="1,000.00">
                </div>
                <div class="col-md-2">
                    <a href="javascript:;" class="btn btn-sm btn-flex flex-center btn-light-danger mt-3 mt-md-9 deleteApplicantBtn2">
                        Delete
                    </a>
                </div>
            </div>
        `;

        applicantsContainer2.appendChild(newApplicantDiv2);

        newApplicantDiv2.querySelector('.deleteApplicantBtn2').addEventListener('click', () => {
            newApplicantDiv2.remove();
            applicantCount2--;
            toggleAddButton2();
        });

        toggleAddButton2();
    }
});

document.querySelectorAll('.deleteApplicantBtn2').forEach(deleteBtn => {
    deleteBtn.addEventListener('click', (event) => {
        event.target.closest('.addNewApplicant2').remove();
        applicantCount2--;
        toggleAddButton2();
    });
});

toggleAddButton2(); // Initial check for button status

document.querySelectorAll('.deleteApplicantBtn2').forEach(deleteBtn => {
    deleteBtn.addEventListener('click', (event) => {
        event.target.closest('.addNewApplicant2').remove();
        applicantCount2--;
    });
});



const applicantsContainer = document.getElementById('applicantsContainer');
const addApplicantBtn = document.getElementById('addApplicantBtn');
let applicantCount = 1;

function toggleAddButton() {
    addApplicantBtn.disabled = applicantCount >= 3;
}

addApplicantBtn.addEventListener('click', () => {
    if (applicantCount < 3) {
        applicantCount++;
        const newApplicantDiv = document.createElement('div');
        newApplicantDiv.classList.add('rounded', 'mb-2', 'border', 'p-4', 'addNewApplicant');
        newApplicantDiv.id = `addNewApplicant${applicantCount + 110}`;

        newApplicantDiv.innerHTML = `
            <div class="form-group row mb-3">
                <div class="col-md-3">
                    <label class="form-label">First Name:</label>
                    <input type="text" name="coApplicantfirstName[]" class="form-control mb-2 mb-md-0" placeholder="Enter first name" required>
                    <span class="error-message" data-error-id="coApplicantfirstNameError"></span>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Last Name:</label>
                    <input type="text" name="coApplicantlastName[]" class="form-control mb-2 mb-md-0" placeholder="Enter last name" required>
                    <span class="error-message" data-error-id="coApplicantlastNameError"></span>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="coApplicantemail[]" class="form-control mb-2 mb-md-0" placeholder="Enter email address" required>
                    <span class="error-message" data-error-id="coApplicantemailError"></span>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rent Amount:</label>
                    <input type="number" name="coApplicantamount[]" class="form-control mb-2 mb-md-0" min="100" max="20000" placeholder="1,000.00" required>
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

        newApplicantDiv.querySelector('.deleteApplicantBtn').addEventListener('click', () => {
            newApplicantDiv.remove();
            applicantCount--;
            toggleAddButton();
        });

        toggleAddButton();
    }
});

document.querySelectorAll('.deleteApplicantBtn').forEach(deleteBtn => {
    deleteBtn.addEventListener('click', (event) => {
        event.target.closest('.addNewApplicant').remove();
        applicantCount--;
        toggleAddButton();
    });
});

toggleAddButton(); // Initial check for button status


document.querySelectorAll('.deleteApplicantBtn').forEach(deleteBtn => {
    deleteBtn.addEventListener('click', (event) => {
        event.target.closest('.addNewApplicant').remove();
        applicantCount--;
    });
});

document.addEventListener("DOMContentLoaded", function () {
    toggleEmailInput();
    chequeOptionForLandLord();

    function initializeStepFlow(nextButtonId, previousButtonId, hideAllDivsSelector, initialStep) {
        const stepFlow = {
            Rent_mortgage_div: { divId: "Rent_mortgage_div", next: selected => selected === "rent" ? "rentStep1" : "mortgageStep1", inputName: "rent_account_type" },
            rentStep1: { divId: "RentStep1", next: selected => selected === "pay_rent" ? "rentStep2" : "rentStep3", inputName: "account_plan_rent" },
            rentStep2: { divId: "RentStep2", next: selected => selected === "sole_applicant" ? "rentStep4" : "rentStep5", inputName: "rent_account_plan_type" },
            rentStep3: { divId: "RentStep3", next: "rentStep2" },
            rentStep4: { divId: "RentStep4", next: null },
            rentStep5: { divId: "rentStep5", next: "rentStep4" },
            mortgageStep1: { divId: "MortgageStep1", next: selected => selected === "pay_mortgage" ? "mortgageStep2" : "mortgageStep3", inputName: "account_plan_mortgage" },
            mortgageStep2: { divId: "MortgageStep2", next: selected => selected === "sole_applicant" ? "mortgageStep5" : "mortgageStep4", inputName: "mortgage_account_plan_type" },
            mortgageStep3: { divId: "MortgageStep3", next: "mortgageStep2" },
            mortgageStep4: { divId: "MortgageStep4", next: "mortgageStep5" },
            mortgageStep5: { divId: "MortgageStep5", next: null },
            address_details_div: { divId: "address_details_div", next: "addressDetails1" },
            addressDetails1: { divId: "addressDetails1", next: "addressDetails2" },
            addressDetails2: { divId: "addressDetails2", next: "addressDetails3" },
            addressDetails3: { divId: "addressDetails3", next: "addressDetails4" },
            addressDetails4: { divId: "addressDetails4", next: null },
            landlordDetails1: { divId: "landlordDetails1", next: selected => selected === "interac" ? "" : "landlordDetails3", inputName: "landlord_account_mode" },
            landlordDetails3: { divId: "landlordDetails3", next: null },
        };

        let currentStep = initialStep;
        const navigationStack = [];
        const nextButton = document.getElementById(nextButtonId);
        const previousButton = document.getElementById(previousButtonId);
        const mainNextButton = document.getElementById("nextButton"); // Restoring mainNextButton functionality

        hideAllSteps();
        navigateToStep(stepFlow[initialStep].divId);

        nextButton.addEventListener("click", function (e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
                return;
            }

            const nextStepKey = getNextStepKey(currentStep);
            if (nextStepKey) {
                navigationStack.push(currentStep);
                currentStep = nextStepKey;
                navigateToStep(stepFlow[currentStep].divId);
            }
        });

        previousButton.addEventListener("click", function () {
            if (navigationStack.length > 0) {
                currentStep = navigationStack.pop();
                navigateToStep(stepFlow[currentStep].divId);
            }
        });

        function getNextStepKey(step) {
            const config = stepFlow[step];
            if (typeof config.next === "function" && config.inputName) {
                const selectedOption = document.querySelector(`input[name="${config.inputName}"]:checked`);
                if (!selectedOption) {
                    displayError(config.inputName, "Please select an option to proceed.");
                    return null;
                }
                return config.next(selectedOption.value);
            }
            return config.next;
        }

        function navigateToStep(divId) {
            hideAllSteps();
            document.getElementById(divId).style.display = "block";
            previousButton.style.display = navigationStack.length === 0 ? "none" : "inline-block";
            nextButton.style.display = stepFlow[currentStep].next ? "inline-block" : "none";

            // Handle mainNextButton for the final step
            // if (stepFlow[currentStep].next === null) {
            //     // mainNextButton.style.display = "inline-block";
            //     mainNextButton.disabled = false;
            // } else {
            //     // mainNextButton.style.display = "none";
            //     mainNextButton.disabled = true;
            // }
        }

        function hideAllSteps() {
            document.querySelectorAll(hideAllDivsSelector).forEach(div => div.style.display = "none");
        }

        function validateStep(step) {
            clearErrors();
            const div = document.getElementById(stepFlow[step].divId);
            console.log(stepFlow[step].divId);

            if (div && stepFlow[step].divId === "rentStep5") {
                console.log(stepFlow[step].divId, "stepFlow[step].divId");

                isValid = validateApplicants() && isValid;
            }
            const fields = div.querySelectorAll("input[required], select[required]");
            return Array.from(fields).every(field => {
                if (!field.value.trim()) {
                    displayError(field.name, "This field is required.");
                    return false;
                }
                return true;
            });
        }

        function displayError(field, message) {
            const errorSpan = document.querySelector(`[data-error-id="${field}Error"]`);
            // if (errorSpan) errorSpan.textContent = message;
            if (errorSpan) {
                errorSpan.textContent = message;
                errorSpan.style.display = "block";
            }
        }
        
        function clearErrors() {
            document.querySelectorAll(".error-message").forEach(error => (error.textContent = ""));
        }

        function validateApplicants() {
            let isValid = true;
            const applicantForms = applicantsContainer.querySelectorAll(".addNewApplicant");

            applicantForms.forEach((form, index) => {
                console.log(`Validating applicant form #${index + 1}`);

                const fields = {
                    firstName: form.querySelector("input[name='coApplicantfirstName[]']"),
                    lastName: form.querySelector("input[name='coApplicantlastName[]']"),
                    email: form.querySelector("input[name='coApplicantemail[]']"),
                    amount: form.querySelector("input[name='coApplicantamount[]']")
                };

                // Map the field keys to the corresponding error ID names without square brackets
                const errorIds = {
                    firstName: "coApplicantfirstNameError",
                    lastName: "coApplicantlastNameError",
                    email: "coApplicantemailError",
                    amount: "coApplicantamountError"
                };

                Object.keys(fields).forEach(fieldKey => {
                    const field = fields[fieldKey];

                    if (!field) return; // Skip if field is not found

                    // Select the error span using the mapped error ID
                    const errorSpan = form.querySelector(`[data-error-id="${errorIds[fieldKey]}"]`);
                    console.log(`Validating field: ${fieldKey}`, "Error span:", errorSpan);

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

    }

    initializeStepFlow("nextButtonLandLord1", "backButtonLandLord1", ".landlordDetails1_divs", "landlordDetails1");
    initializeStepFlow("nextButtonStep1", "backButtonStep1", ".Rent_mortgage_divs", "initial");
    initializeStepFlow("nextButtonAlt", "backButtonAlt", ".addressDetails1_divs", "address_details_div");
});


document.addEventListener('DOMContentLoaded', function () {
    const stepperItems = document.querySelectorAll('[data-kt-stepper-element="nav"]');
    const steps = document.querySelectorAll('[data-kt-stepper-element="content"]');
    const backButton = document.getElementById("backButton");
    const nextButton = document.getElementById("nextButton");
    const progressBar = document.getElementById("progressBar");
    const progressPercentage = document.getElementById("progressPercentage");

    let currentStep = 0;
    let currentSubStep = 0;

    function updateSidebar() {
        stepperItems.forEach((item, index) => {
            item.classList.remove("current", "completed");
            if (index < currentStep) {
                item.classList.add("completed");
            } else if (index === currentStep) {
                item.classList.add("current");
            }
        });
    }

    function updateProgress() {
        const totalMainSteps = steps.length;
        const progressPercent = (currentStep / (totalMainSteps - 1)) * 100;
        if (progressBar) {
            progressBar.style.width = `${progressPercent}%`;
        }
        if (progressPercentage) {
            progressPercentage.textContent = `${Math.round(progressPercent)}%`;
        }
    }

    function addRealTimeValidation() {
        steps.forEach(step => {
            step.querySelectorAll("[required]").forEach(field => {
                const errorSpan = document.querySelector(`.error-message[data-error-id="${field.name}Error"]`);
                field.addEventListener("input", () => {
                    if (errorSpan) errorSpan.style.display = "none";
                });
            });
        });
    }


    function validateCurrentStep() {
        const currentContent = steps[currentStep];
        const subSteps = currentContent.querySelectorAll(".substep");
        const activeSubStep = subSteps[currentSubStep];
        const fieldsToValidate = activeSubStep ? activeSubStep.querySelectorAll("[required]") : currentContent.querySelectorAll("[required]");
        let isValid = true;

        // Validate input fields (text)
        const requiredInputs = currentContent.querySelectorAll("input[required]:not([type='radio'])");
        requiredInputs.forEach((input) => {
            const errorSpan = document.querySelector(`[data-error-id="${input.getAttribute("name")}Error"]`);
            if (!input.value.trim()) {
                console.log(`Input ${input.name} is empty.`);
                if (errorSpan) {
                    errorSpan.textContent = "This field is required.";
                    errorSpan.style.display = "block";
                }
                isValid = false;
            } else if (errorSpan) {
                errorSpan.textContent = ""; // Clear error message if valid
                errorSpan.style.display = "none";
            }

            // Clear error on input
            input.addEventListener("input", function () {
                if (errorSpan) {
                    errorSpan.textContent = "";
                    errorSpan.style.display = "none";
                }
            });
        });

        // Validate radio groups
        const requiredRadioGroups = new Set([...currentContent.querySelectorAll("input[type='radio'][required]")].map(input => input.name));
        const requiredCheckboxes = [...currentContent.querySelectorAll("input[type='checkbox'][required]")];

        // Validate radio groups
        requiredRadioGroups.forEach((groupName) => {
            const groupChecked = document.querySelector(`input[name="${groupName}"]:checked`);
            const errorSpan = document.querySelector(`[data-error-id="${groupName}Error"]`);

            if (!groupChecked) {
                console.log(`No selection made for radio group ${groupName}.`);
                if (errorSpan) {
                    errorSpan.textContent = "This field is required.";
                    errorSpan.style.display = "block";
                }
                isValid = false;
            } else if (errorSpan) {
                errorSpan.textContent = "";
                errorSpan.style.display = "none";
            }

            // Clear error on radio selection change
            document.querySelectorAll(`input[name="${groupName}"]`).forEach((radio) => {
                radio.addEventListener("change", function () {
                    if (errorSpan) {
                        errorSpan.textContent = "";
                        errorSpan.style.display = "none";
                    }
                });
            });
        });

        // Validate checkboxes
        requiredCheckboxes.forEach((checkbox) => {
            const checkboxErrorSpan = document.querySelector(`[data-error-id="${checkbox.name}Error"]`);
            if (!checkbox.checked) {
                console.log(`Checkbox ${checkbox.name} is required but not checked.`);
                if (checkboxErrorSpan) {
                    checkboxErrorSpan.textContent = "This field is required.";
                    checkboxErrorSpan.style.display = "block";
                }
                isValid = false;
            } else if (checkboxErrorSpan) {
                checkboxErrorSpan.textContent = "";
                checkboxErrorSpan.style.display = "none";
            }

            // Clear error on checkbox change
            checkbox.addEventListener("change", function () {
                if (checkboxErrorSpan) {
                    checkboxErrorSpan.textContent = "";
                    checkboxErrorSpan.style.display = "none";
                }
            });
        });


        // Validate select fields
        const requiredSelects = currentContent.querySelectorAll("select[required]");
        requiredSelects.forEach((select) => {
            const errorSpan = document.querySelector(`[data-error-id="${select.getAttribute("name")}Error"]`);
            if (!select.value || select.value === "") {
                console.log(`Select ${select.name} has no selected value.`);
                if (errorSpan) {
                    errorSpan.textContent = "Please select an option.";
                    errorSpan.style.display = "block";
                }
                isValid = false;
            } else if (errorSpan) {
                errorSpan.textContent = "";
                errorSpan.style.display = "none";
            }

            // Clear error on select change
            select.addEventListener("change", function () {
                if (errorSpan) {
                    errorSpan.textContent = "";
                    errorSpan.style.display = "none";
                }
            });
        });

        // if (activeSubStep && activeSubStep.id === "rentStep5") {
        //     isValid = validateApplicants() && isValid;
        // }

        console.log("Validation result for current step:", isValid);
        return isValid;
    }


    // Initialize real-time validation listeners
    addRealTimeValidation();





    function moveNextStep() {
        currentSubStep = 0;  // Reset sub-step to 0 when moving to the next step
        if (currentStep < steps.length - 1) currentStep++;

        // function getCurrentDisplayedDiv() {
        //     const contentDivs = document.querySelectorAll('[data-kt-stepper-element="content"]');
        //     return Array.from(contentDivs).find(div => div.style.display === "block");
        // }

        // // Process the currently displayed div to check its data-step-form attribute
        // const currentDiv = getCurrentDisplayedDiv();
        // if (currentDiv) {
        //     if (currentDiv.getAttribute('data-step-form') === "true") {
        //         // Log to console and do not move to the next step
        //         console.log("Currently displayed div has data-step-form='true'. Action halted.");
        //         return;
        //     } else {

        //         if (!validateCurrentStep()) {
        //             return;  // Stop here if validation fails
        //         }

        //         // Proceed to the next step if data-step-form is not "true"
        //         currentSubStep = 0;  // Reset sub-step to 0 when moving to the next step
        //         if (currentStep < steps.length - 1) currentStep++;

        //         updateSidebar();  // Update sidebar to reflect the current step
        //         updateStep();     // Update the content of the displayed step

        //         // Optionally, log a message to indicate the transition
        //         console.log("Moved to the next step.");
        //     }
        // }
        updateSidebar();
        updateStep();
    }


    function movePreviousStep() {
        currentSubStep = 0;
        if (currentStep > 0) currentStep--;
        const subSteps = steps[currentStep].querySelectorAll(".substep");

        // currentSubStep = 0;
        // if (currentStep > 0) currentStep--;
        // if (subSteps.length > 0 && currentSubStep > 0) {
        //     currentSubStep--;
        // } else {
        //     currentSubStep = 0;
        //     if (currentStep > 0) currentStep--;
        // }
        updateSidebar();
        updateStep();
    }

    function updateStep() {
        steps.forEach((step, index) => {
            step.style.display = index === currentStep ? "block" : "none";
            if (index === currentStep) {
                const subSteps = step.querySelectorAll(".substep");
                subSteps.forEach((subStep, subIndex) => {
                    subStep.style.display = subIndex === currentSubStep ? "block" : "none";
                });
            }
        });

        backButton.style.display = currentStep === 0 ? "none" : "inline-block";
        nextButton.textContent = currentStep === steps.length - 1 ? "Submit" : "Continue";
        updateProgress();
    }

    nextButton.addEventListener("click", () => {
        if (currentStep === steps.length - 1) {
            alert("Form submitted!");
        } else {
            moveNextStep();
        }
    });

    backButton.addEventListener("click", movePreviousStep);

    updateSidebar();
    updateStep();
});
