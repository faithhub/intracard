let currentStep = 0;  // Track main steps
let currentMultiStep = 0;  // Track multi-steps within a main step
let currentCondition = null; // Track the condition for showing multi-steps

// Check if the current step has conditional multi-steps
function stepHasMultiSteps(step) {
    return step === 1; // Only Step 1 (Step 2 in form) has multi-steps based on conditions
}

// Handle selection changes to update the condition without advancing
function handleCondition(selection) {
    currentCondition = selection;
    currentMultiStep = 0;  // Reset to the first multi-step of the current condition
    console.log(`Condition selected: ${currentCondition}`);  // Debugging
    renderForm();
}

// Show the appropriate main step and multi-step based on the current state
function renderForm() {
    // Hide all steps and multi-steps initially
    document.querySelectorAll('.step').forEach(step => step.style.display = 'none');
    document.querySelectorAll('.multi-step').forEach(multiStep => multiStep.style.display = 'none');

    // Show the current main step
    const step = document.querySelector(`.step-${currentStep}`);
    if (step) {
        step.style.display = 'block';
    } else {
        console.warn(`No step found for step number: ${currentStep}`);
        return;
    }

    // Show the appropriate multi-step(s) if in Step 1 and a condition is set
    if (stepHasMultiSteps(currentStep) && currentCondition) {
        const multiSteps = step.querySelectorAll(`.multi-step[data-condition="${currentCondition}"]`);
        
        if (multiSteps.length > 0) {
            multiSteps.forEach((multiStep, index) => {
                if (index === currentMultiStep) {
                    multiStep.style.display = 'block';  // Display only the current multi-step
                }
            });
        } else {
            console.warn(`No matching multi-step found for condition "${currentCondition}" in step ${currentStep}`);
        }
    }
}

// Move to the next step or multi-step
function navigateNext() {
    if (!validateStep()) {
        alert("Please fill all required fields correctly.");
        return;
    }

    // If we are in a step with multi-steps, navigate through multi-steps first
    if (stepHasMultiSteps(currentStep) && currentCondition) {
        const multiStepsInCurrentStep = document.querySelectorAll(`.step-${currentStep} .multi-step[data-condition="${currentCondition}"]`);
        
        if (currentMultiStep < multiStepsInCurrentStep.length - 1) {
            currentMultiStep++;  // Move to the next multi-step within the current main step
        } else {
            currentMultiStep = 0;  // Reset to the first multi-step
            currentStep++;  // Move to the next main step
            currentCondition = null;  // Reset condition for the next step
        }
    } else {
        currentStep++;  // Move to the next main step if no multi-steps are present
    }
    console.log(`Navigated to step: ${currentStep}, multi-step: ${currentMultiStep}, condition: ${currentCondition}`);
    renderForm();
}

// Move to the previous step or multi-step
function navigatePrev() {
    if (stepHasMultiSteps(currentStep) && currentCondition && currentMultiStep > 0) {
        currentMultiStep--;  // Move to the previous multi-step within the current main step
    } else if (currentStep > 0) {
        currentStep--;  // Move to the previous main step
        currentCondition = null;  // Reset condition on moving to the previous main step
        currentMultiStep = 0;  // Reset multi-step to start
    }
    console.log(`Navigated to previous step: ${currentStep}, multi-step: ${currentMultiStep}, condition: ${currentCondition}`);
    renderForm();
}

// Validate inputs in the current visible step
function validateStep() {
    let isValid = true;
    const currentInputs = document.querySelectorAll(`.step-${currentStep} .multi-step[data-condition="${currentCondition}"]:nth-of-type(${currentMultiStep + 1}) input`);
    
    currentInputs.forEach(input => {
        if (!input.checkValidity()) {
            input.classList.add("is-invalid");
            isValid = false;
        } else {
            input.classList.remove("is-invalid");
        }
    });

    return isValid;
}

// Reset the form
function resetForm() {
    currentStep = 0;
    currentMultiStep = 0;
    currentCondition = null;
    document.querySelectorAll('input').forEach(input => {
        input.value = '';
        input.classList.remove('is-invalid');
    });
    console.log("Form has been reset.");  // Debugging
    renderForm();
}

// Initialize form on page load
document.addEventListener('DOMContentLoaded', () => {
    console.log("Form loaded, rendering initial view.");
    renderForm();
});
