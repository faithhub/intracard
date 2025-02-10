document.addEventListener('DOMContentLoaded', function () {
    const stepperItems = document.querySelectorAll('[data-kt-stepper-element="nav"]');
    const steps = document.querySelectorAll('[data-kt-stepper-element="content"]');
    const backButton = document.getElementById("backButton");
    const nextButton = document.getElementById("nextButton");
    const progressBar = document.getElementById("progressBar");
    const progressPercentage = document.getElementById("progressPercentage");
    const applicantsContainer = document.getElementById("applicantsContainer");

    let currentStep = 0;
    let currentSubStep = 0;
    const navigationStack = [];

    const stepFlow = {
        initial: { divId: "Rent_mortgage_div", next: selected => selected === "rent" ? "rentStep1" : "mortgageStep1", inputName: "rent_account_type" },
        rentStep1: { divId: "RentStep1", next: selected => selected === "pay_rent" ? "rentStep2" : "rentStep3", inputName: "account_plan_rent" },
        rentStep2: { divId: "RentStep2", next: selected => selected === "sole_applicant" ? "rentStep4" : "rentStep5", inputName: "rent_account_plan_type" },
        rentStep3: { divId: "RentStep3", next: "rentStep2" },
        rentStep4: { divId: "RentStep4", next: null },
        rentStep5: { divId: "rentStep5", next: "rentStep4" },
        mortgageStep1: { divId: "MortgageStep1", next: selected => selected === "pay_mortgage" ? "mortgageStep2" : "mortgageStep3", inputName: "account_plan_mortgage" },
        mortgageStep2: { divId: "MortgageStep2", next: selected => selected === "sole_applicant" ? "mortgageStep5" : "mortgageStep4", inputName: "mortgage_account_plan_type" },
        mortgageStep3: { divId: "MortgageStep3", next: "mortgageStep2" },
        mortgageStep5: { divId: "MortgageStep5", next: null },
        mortgageStep4: { divId: "MortgageStep4", next: "mortgageStep5" },
    };

    function updateSidebar() {
        stepperItems.forEach((item, index) => {
            item.classList.remove("current", "completed");
            if (index < currentStep) item.classList.add("completed");
            else if (index === currentStep) item.classList.add("current");
        });
        console.log("Updated sidebar: currentStep =", currentStep);
    }

    function updateProgress() {
        const totalMainSteps = steps.length;
        const progressPercent = (currentStep / (totalMainSteps - 1)) * 100;
        if (progressBar) progressBar.style.width = `${progressPercent}%`;
        if (progressPercentage) progressPercentage.textContent = `${Math.round(progressPercent)}%`;
        console.log("Progress updated: currentStep =", currentStep, "progressPercent =", progressPercent);
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

        // fieldsToValidate.forEach(field => {
        //     const errorSpan = document.querySelector(`.error-message[data-error-id="${field.name}Error"]`);
        //     // console.log(errorSpan);
            
        //     if (!field.value.trim()) {
        //         console.log(errorSpan);
                
        //         if (errorSpan) {
        //             errorSpan.textContent = "This field is required.";
        //             errorSpan.style.display = "block";
        //         }
        //         console.log(`Validation failed: ${field.name} is empty.`);
        //         isValid = false;
        //     } else if (errorSpan) {
        //         errorSpan.style.display = "none";
        //     }
        // });

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

        if (activeSubStep && activeSubStep.id === "rentStep5") {
            isValid = validateApplicants() && isValid;
        }

        console.log("Validation result for current step:", isValid);
        return isValid;
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

            Object.keys(fields).forEach(fieldKey => {
                const field = fields[fieldKey];
                if (!field) return;

                const errorSpan = form.querySelector(`[data-error-id="${fieldKey}Error"]`);
                if (!field.value.trim()) {
                    if (errorSpan) errorSpan.textContent = "This field is required.";
                    isValid = false;
                } else if (errorSpan) {
                    errorSpan.style.display = "none";
                }

                field.addEventListener("input", () => {
                    if (errorSpan) errorSpan.style.display = "none";
                });
            });
        });

        console.log("Validation result for applicants:", isValid);
        return isValid;
    }

    addRealTimeValidation();

    // function moveNextStep() {
    //     console.log("Attempting to move to the next step from currentStep =", currentStep, "currentSubStep =", currentSubStep);
    
    //     if (!validateCurrentStep()) {
    //         console.log("Validation failed. Staying on current step.");
    //         return;
    //     }
    
    //     // Retrieve the current step configuration
    //     const currentStepConfig = stepFlow[Object.keys(stepFlow)[currentStep]] || {};
    //     let nextStepKey;
    
    //     console.log(currentStepConfig);
        
    //     // Determine the next step key based on selected input, if applicable
    //     if (typeof currentStepConfig.next === "function" && currentStepConfig.inputName) {
    //         const selectedOption = document.querySelector(`input[name="${currentStepConfig.inputName}"]:checked`);
    //         if (!selectedOption) {
    //             displayValidationError(currentStepConfig.inputName, "Please select an option to proceed.");
    //             console.log("No selection made for required input, validation failed.");
    //             return;
    //         }
    //         nextStepKey = currentStepConfig.next(selectedOption.value);
    //         console.log("Determined next step key using function:", nextStepKey);
    //     } else {
    //         nextStepKey = currentStepConfig.next;
    //         console.log("Determined next step key as string:", nextStepKey);
    //     }
    
    //     // Proceed with sub-step or main step navigation
    //     const subSteps = steps[currentStep].querySelectorAll(".substep");
    //     if (subSteps.length > 0 && currentSubStep < subSteps.length - 1) {
    //         currentSubStep++;
    //         console.log("Moving to next sub-step:", currentSubStep);
    //     } else {
    //         // Reset sub-step and move to the next main step
    //         currentSubStep = 0;
    //         if (nextStepKey && steps[currentStep + 1]) {
    //             currentStep++;
    //             console.log("Moving to next main step:", currentStep);
    //         } else {
    //             console.log("Reached end of steps or no next step defined.");
    //             return;
    //         }
    //     }
    
    //     // Update the sidebar and step display
    //     updateSidebar();
    //     updateStep();
    // }

    function moveNextStep() {
        console.log("Attempting to move to the next step from currentStep =", currentStep, "currentSubStep =", currentSubStep);
    
        if (!validateCurrentStep()) {
            console.log("Validation failed. Staying on current step.");
            return;
        }
    
        // Retrieve the current step configuration
        const currentStepConfig = stepFlow[Object.keys(stepFlow)[currentStep]] || {};
        let nextStepKey;
    
        console.log("Current Step Config:", currentStepConfig);
    
        // Check if there are sub-steps in the current step
        const subSteps = steps[currentStep].querySelectorAll(".substep");
    
        if (currentStep < steps.length - 1) currentStep++;

        // If sub-steps exist and we haven't reached the last one, move to the next sub-step
        if (subSteps.length > 0 && currentSubStep < subSteps.length - 1) {
            currentSubStep++;
            console.log("Moving to next sub-step:", currentSubStep);
        } else {
            // Reset sub-step if moving to the next main step
            currentSubStep = 0;
    
            // Determine the next main step based on stepFlow configuration
            if (typeof currentStepConfig.next === "function" && currentStepConfig.inputName) {
                const selectedOption = document.querySelector(`input[name="${currentStepConfig.inputName}"]:checked`);
                if (!selectedOption) {
                    displayValidationError(currentStepConfig.inputName, "Please select an option to proceed.");
                    console.log("No selection made for required input, validation failed.");
                    return;
                }
                nextStepKey = currentStepConfig.next(selectedOption.value);
                console.log("Determined next step key using function:", nextStepKey);
            } else {
                nextStepKey = currentStepConfig.next;
                console.log("Determined next step key as string:", nextStepKey);
            }
    
            // If a next step key is defined in the flow, find its index and move to it
            const nextStepIndex = Object.keys(stepFlow).findIndex(key => key === nextStepKey);
    
            if (nextStepKey && nextStepIndex !== -1) {
                currentStep = nextStepIndex;
                console.log("Moving to next main step:", currentStep);
            } else {
                console.log("Reached end of steps or no next step defined.");
                return;
            }
        }
    
        // Update the sidebar and step display
        updateSidebar();
        updateStep();
    }
    
    

    function movePreviousStep() {
        const subSteps = steps[currentStep].querySelectorAll(".substep");
        if (subSteps.length > 0 && currentSubStep > 0) {
            currentSubStep--;
            console.log("Moving to previous sub-step:", currentSubStep);
        } else {
            currentSubStep = 0;
            if (currentStep > 0) {
                currentStep--;
                console.log("Moving to previous main step:", currentStep);
            }
        }

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

        console.log("Current step updated:", currentStep, "Current sub-step:", currentSubStep);
    }

    function displayValidationError(inputName, message) {
        const errorSpan = document.querySelector(`[data-error-id="${inputName}Error"]`);
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = "block";
            console.log("Displayed validation error for", inputName + ":", message);
        }
    }

    nextButton.addEventListener("click", moveNextStep);
    backButton.addEventListener("click", movePreviousStep);

    updateSidebar();
    updateStep();
});
