const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// window.addEventListener("load", () => {
//     const email = document.getElementById("emailInputField").value; // Replace with the actual input field ID
//     sessionStorage.removeItem(`verification_status_${email}`); // Remove specific key
//     console.log(`Session for ${email} reset on page reload.`, `verification_status_${email}`);
// });

const email = document.getElementById("emailInputField").value;
const nextButton = document.getElementById("nextButton");
// function validateNumberInput(inputElement, min, max) {
//     inputElement.addEventListener("focusout", () => {
//         let value = parseInt(inputElement.value, 10);

//         // Check if the input is a valid number, if not, clear the input
//         if (isNaN(value)) {
//             inputElement.value = "";
//             return;
//         }

//         // Enforce minimum limit
//         if (value < min) {
//             inputElement.value = min;
//         }

//         // Enforce maximum limit
//         else if (value > max) {
//             inputElement.value = max;
//         }
//     });
// }

// Call this function and pass in the input element you want to validate
const coApplicanRentPrimaryAmount = document.getElementById(
    "coApplicanRentPrimaryAmount"
); // Replace with your actual input element ID
const coApplicanMortgagePrimaryAmount = document.getElementById(
    "coApplicanMortgagePrimaryAmount"
); // Replace with your actual input element ID
const numberInput = document.getElementById("amount_admin_pay"); // Replace with your actual input element ID
const defaulBillingCardAmount = document.getElementById(
    "defaulBillingCardAmount"
); // Replace with your actual input element ID
// validateNumberInput(coApplicanRentPrimaryAmount, 100, 50000);
// validateNumberInput(coApplicanMortgagePrimaryAmount, 100, 50000);
// validateNumberInput(defaulBillingCardAmount, 100, 1000000);
// validateNumberInput(numberInput, 100, 50000);

// Elements for interaction
const termsContainer = document.getElementById("termsContainer");
const agreeCheckbox = document.getElementById("agreeCheckbox");
const proceedButton = document.getElementById("proceedButton");

// Check if the user has scrolled to the bottom
// termsContainer.addEventListener('scroll', () => {
//     const isScrolledToBottom = termsContainer.scrollTop + termsContainer.clientHeight >= termsContainer.scrollHeight;

//     if (isScrolledToBottom) {
//         agreeCheckbox.disabled = false; // Enable the agree checkbox
//     }
//     console.log(isScrolledToBottom);

// });

termsContainer.addEventListener("scroll", () => {
    if (agreeCheckbox.checked && proceedButton.textContent === "Verified") {
       return;
    }
    // const email = document.getElementById("emailInputField").value; // Replace with the actual email input field ID
    // const verificationStatus = sessionStorage.getItem(
    //     `verification_status_${email}`
    // );
    // console.log(verificationStatus, email);

    // Check if verification status is true
    // if (!verificationStatus || verificationStatus === "false") {
        console.log("Verification status is true. Skipping scroll event.");

        const tolerance = 1; // Tolerance to account for minor discrepancies
        const isScrolledToBottom =
            termsContainer.scrollTop + termsContainer.clientHeight >=
            termsContainer.scrollHeight - tolerance;

        if (isScrolledToBottom) {
            agreeCheckbox.disabled = false; // Enable the agree checkbox
        }

        console.log(isScrolledToBottom);
    // }
});

// Show toast if the checkbox or proceed button is clicked before scrolling through all terms
agreeCheckbox.addEventListener("click", (e) => {
    if (agreeCheckbox.disabled) {
        e.preventDefault();
        Swal.fire({
            text: "Please scroll through all terms before agreeing.",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-danger"
            }
        });
        // showToast("Please scroll through all terms before agreeing.", "error");
    }
});

proceedButton.addEventListener("click", async (e) => {
    e.preventDefault(); // Prevent default click behavior

    // Check if the checkbox is checked before proceeding
    if (!agreeCheckbox.checked) {
        Swal.fire({
            text: "Please scroll through all terms before you proceed.",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-danger"
            }
        });
        // showToast("Please scroll through all terms before you proceed.", "error");
        return;
    }

    proceedButton.classList.add("enabled");
    proceedButton.disabled = true; // Disable button during verification
    nextButton.disabled = true;

    // Change button text to "Verifying..." and start verification process
    proceedButton.textContent = "Verifying...";
    startVerification();
});

async function startVerification() {
    const overlay = document.getElementById("overlay");
    const loader = document.querySelector(".loader");
    const progress = document.querySelector(".progress-verify");
    const successMark = document.querySelector(".success-mark");

    // Reset overlay elements for each start
    overlay.style.display = "flex";
    loader.style.display = "block";
    successMark.style.display = "none";
    progress.style.width = "0%";

    // Start progress animation
    setTimeout(() => {
        progress.style.width = "100%";
    }, 10); // Small delay for smooth animation start

    // Simulate loading delay while making backend request
    try {
        // Prepare data to send to the backend
        const data = {
            agreed: agreeCheckbox.checked,
            userId: 123, // Replace with actual user data as needed
        };

        // Send POST request to backend for verification
        const response = await fetch(verifyAccount, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken, // Include CSRF token for Laravel
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        console.log(result);

        setTimeout(() => {
            loader.style.display = "none";
            if (result.verifyStatus === true) {
                // If verification successful
                // successMark.style.display = 'block';
                proceedButton.textContent = "Verified";
                agreeCheckbox.disabled = true;
                proceedButton.disabled = true;
                nextButton.disabled = false; // Enable Next button
                // Save the status in sessionStorage with a key based on the email
                // sessionStorage.setItem(`verification_status_${email}`, true);

                // Hide the overlay after a brief delay (e.g., 2 seconds)
                    overlay.style.display = "none";
                    progress.style.width = "0%"; // Reset progress bar for next use
                    Swal.fire({
                        text: "Account verified",
                        icon: "success",
                        buttonsStyling: false,
                        timer: 1000,
                        showConfirmButton: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-success",
                        },
                    });
            } else {
                // If verification fails
                // sessionStorage.setItem(`verification_status_${email}`, false);
                overlay.style.display = "none";
                successMark.style.display = "none";
                progress.style.width = "0%"; // Reset progress bar
                proceedButton.classList.remove("enabled");
                proceedButton.textContent = "Proceed"; // Reset button text
                agreeCheckbox.disabled = false;
                agreeCheckbox.checked  = false;
                proceedButton.disabled = true;
                nextButton.disabled = true; // Keep Next button disabled
                // showToast("Verification failed. Kindly cross check your personal information and try again.", "error");
                Swal.fire({
                    text: "Verification failed.  Kindly cross-check your personal information and try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-danger",
                    },
                });
            }
        }, 4000); // After 5 seconds, update the overlay based on verification result
    } catch (error) {
        console.log(error);

        // Handle error: Reset overlay and button states
        console.error("An error occurred:", error);
        overlay.style.display = "none";
        loader.style.display = "none";
        successMark.style.display = "none";
        progress.style.width = "0%";
        proceedButton.textContent = "Try again";
        agreeCheckbox.disabled = false;
        proceedButton.disabled = false;
        nextButton.disabled = true; // Keep Next button disabled
        // showToast("An error occurred during verification.", "error");
        Swal.fire({
            text: "Verification failed. Please try again.",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-danger",
            },
        });
    }
}

// Enable Proceed button when checkbox is checked
agreeCheckbox.addEventListener("change", () => {
    if (agreeCheckbox.checked) {
        proceedButton.classList.add("enabled");
        proceedButton.disabled = false;
    } else {
        proceedButton.classList.remove("enabled");
        proceedButton.disabled = true;
        nextButton.disabled = true;
    }
});

// Initialize the proceed button as disabled
// proceedButton.disabled = true;

// Function to check visibility of verificationDiv
function checkVerificationDivVisibility() {
    const verificationDiv = document.getElementById("verificationDiv");
    return verificationDiv.style.display !== "none"; // Returns true if visible
}

const verificationStatus = sessionStorage.getItem(
    `verification_status_${email}`
);

// Real-time monitoring of verificationDiv visibility
const verificationDiv = document.getElementById("verificationDiv");
const observer = new MutationObserver(() => {
    console.log(verificationStatus, "verificationStatus");

    if (checkVerificationDivVisibility()) {
        agreeCheckbox.disabled = true;
        proceedButton.disabled = true;
        nextButton.disabled = true;
        console.log("The verificationDiv is displayed.");
        if (agreeCheckbox.checked && proceedButton.textContent === "Verified") {
            proceedButton.disabled = true;
            nextButton.disabled = false;
        }
        
        // if (agreeCheckbox.checked) {
        //     proceedButton.disabled = false;
        //     if (!proceedButton.disabled) {
        //         proceedButton.classList.add('enabled');
        //         proceedButton.disabled = false;
        //         nextButton.disabled = false;
        //     } else {
        //         nextButton.disabled = true;
        //     }
        // }
        // Disable next button by default
        // nextButton.disabled = false;
    } else {
        nextButton.disabled = false;
        console.log("The verificationDiv is not displayed.");
    }

    // if (!verificationStatus || verificationStatus === "false" || verificationStatus ===  null) { 
    // }else{
    //     console.log("No session");
    //     agreeCheckbox.disabled = true;
    //     proceedButton.disabled = true;
    //     nextButton.disabled = true;
    // }

});

// Observe changes to the style attribute of verificationDiv
observer.observe(verificationDiv, {
    attributes: true,
    attributeFilter: ["style"],
});

// Array of IDs for divs to monitor
const divIds = ["verificationDiv", "mortgageFinance", "personalInformation"];

// Function to check visibility of a div by ID
function checkDivVisibility(divId) {
    const div = document.getElementById(divId);
    return div && div.style.display !== "none"; // Returns true if visible
}

// Set up MutationObserver for each div in the array
// divIds.forEach((divId) => {
//     const targetDiv = document.getElementById(divId);
//     if (targetDiv) {
//         const observer = new MutationObserver(() => {
//             const isVisible = checkDivVisibility(divId);
//             if (divId === "verificationDiv") {
//                 if (isVisible) {
//                     const verificationStatus = sessionStorage.getItem(`verification_status_${email}`);
//                     console.log(verificationStatus, "verificationStatus");

//                     console.log("The verificationDiv is displayed.");
//                     if (agreeCheckbox.checked) {
//                         if (!proceedButton.disabled) {
//                             proceedButton.classList.add('enabled');
//                             proceedButton.disabled = false;
//                             nextButton.disabled = false;
//                         } else {
//                             nextButton.disabled = true;
//                         }
//                     }
//                     // Disable next button by default
//                     proceedButton.disabled = true;
//                     nextButton.disabled = true;
//                     console.log("The verificationDiv is displayed.");
//                 } else {
//                     console.log("The verificationDiv is not displayed.");
//                     // Add actions specific to verificationDiv being hidden
//                 }
//             } else {
//                 console.warn(`Ignoring div ID: ${divId}`);
//             }
//         });

//         // Observe changes to the style attribute of each target div
//         observer.observe(targetDiv, { attributes: true, attributeFilter: ['style'] });
//     }
// });
