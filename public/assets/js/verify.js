// Function to add loading state to button
function setButtonLoading(button, isLoading) {
    console.log('Setting button loading state:', isLoading);
    
    if (!button) {
        console.log('Button not found');
        return;
    }
    
    if (isLoading) {
        console.log('Setting loading state');
        // Store original text
        button.dataset.originalText = button.innerHTML;
        
        // Set loading state
        button.disabled = true;
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Processing...
        `;
    } else {
        console.log('Resetting loading state');
        // Check if we're in cooldown before resetting the button
        if (!isInCooldown()) {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText || 'Proceed with verification';
        } else {
            // If in cooldown, checkButtonCooldown will handle the button state
            checkButtonCooldown();
        }
    }
}


// Function to check if button is in cooldown (without modifying button state)
function isInCooldown() {
    const cooldownEnd = localStorage.getItem('verify_cooldown_end');
    if (cooldownEnd) {
        const now = new Date().getTime();
        const endTime = parseInt(cooldownEnd);
        
        // If the cooldown is still active
        if (now < endTime) {
            return true;
        } else {
            // Cooldown has expired
            localStorage.removeItem('verify_cooldown_end');
        }
    }
    
    return false; // No active cooldown
}

// Function to check and handle button cooldown
function checkButtonCooldown() {
    const cooldownEnd = localStorage.getItem('verify_cooldown_end');
    if (cooldownEnd) {
        const now = new Date().getTime();
        const endTime = parseInt(cooldownEnd);
        
        // If the cooldown is still active
        if (now < endTime) {
            const remainingTimeSeconds = Math.floor((endTime - now) / 1000);
            const minutes = Math.floor(remainingTimeSeconds / 60);
            const seconds = remainingTimeSeconds % 60;
            
            // Set button state
            const proceedButton = document.getElementById('verifyProceedButton');
            if (proceedButton) {
                proceedButton.disabled = true;
                proceedButton.innerHTML = `Please wait (${minutes}:${seconds.toString().padStart(2, '0')})`;
                
                // Clear any existing interval
                if (window.cooldownInterval) {
                    clearInterval(window.cooldownInterval);
                }
                
                // Update the countdown every second
                window.cooldownInterval = setInterval(() => {
                    const currentTime = new Date().getTime();
                    const currentRemaining = Math.max(0, Math.floor((endTime - currentTime) / 1000));
                    
                    if (currentRemaining <= 0) {
                        clearInterval(window.cooldownInterval);
                        window.cooldownInterval = null;
                        proceedButton.disabled = false;
                        proceedButton.innerHTML = 'Proceed with verification';
                        localStorage.removeItem('verify_cooldown_end');
                    } else {
                        const mins = Math.floor(currentRemaining / 60);
                        const secs = currentRemaining % 60;
                        proceedButton.innerHTML = `Please wait (${mins}:${secs.toString().padStart(2, '0')})`;
                    }
                }, 1000);
                
                return true; // Cooldown is active
            }
        } else {
            // Cooldown has expired
            localStorage.removeItem('verify_cooldown_end');
        }
    }
    
    return false; // No active cooldown
}

// Function to set button cooldown based on configuration
function setButtonCooldown() {
    const now = new Date().getTime();
    const cooldownEndTime = now + (CONFIG.cooldownDurationMinutes * 60 * 1000); // Convert minutes to milliseconds
    localStorage.setItem('verify_cooldown_end', cooldownEndTime.toString());
}

function getPersonalInfo() {
    return {
        firstName: document.getElementById('first_name')?.value || '',
        lastName: document.querySelector('input[name="last_name"]')?.value || '',
        middleName: document.querySelector('input[name="middle_name"]')?.value || '',
        email: document.getElementById('emailInputField')?.value || '',
        phone: document.getElementById('phoneInput')?.value || '',
        isEmailVerified: isEmailVerified || false,
        isPhoneVerified: isPhoneVerified || false
    };
}

// Process the form data
// Function to process the verification form with personal info
function processVerifyForm(form) {
    // Reset all error messages
    clearAllVerifyErrors(form);
    
    // Get form values for verification
    const idNumber = form.querySelector('[name="verify_idNumber"]')?.value.trim() || '';
    const documentType = form.querySelector('[name="verify_type"]')?.value || 'PASSPORT';
    const documentNumber = form.querySelector('[name="verify_number"]')?.value.trim() || '';
    const fullAddress = form.querySelector('[name="verify_fullAddress"]')?.value.trim() || '';
    const termsAgreed = form.querySelector('[name="verify_terms"]')?.checked || false;
    const countryElement = form.querySelector('[name="verify_country"]');
    const country = countryElement ? countryElement.value.split(' ')[1].replace(/[()]/g, '') : 'CA';
    
    console.log('Form values collected:', {
        idNumber, documentType, documentNumber, fullAddress, termsAgreed, country
    });
    
    // Get personal information from the main form
    const personalInfo = getPersonalInfo();
    
    // Prepare data for API submission by combining both forms
    const formData = {
        // Personal information from main form
        first_name: personalInfo.firstName,
        last_name: personalInfo.lastName,
        email: personalInfo.email,
        phone: personalInfo.phone,
        
        // Verification-specific information
        person: { 
            idNumber: idNumber 
        },
        document: {
            number: documentNumber,
            type: documentType,
            country: country,
            fullAddress: fullAddress
        },
        terms: termsAgreed
    };
    
    console.log('API payload:', formData);
    
    // Get the button reference
    const proceedButton = document.getElementById('verifyProceedButton');
    
    // Validation check before submission
    if (!idNumber || !documentNumber || !fullAddress || !termsAgreed) {
        showToast('Please fill in all required fields', 'error');
        setButtonLoading(proceedButton, false);
        window.isVerifySubmitting = false;
        return false;
    }
    
    // Submit the data to the API
    fetch('/veriff/start', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        // Check for validation errors (422)
        if (response.status === 422) {
            return response.json().then(data => {
                throw { 
                    status: 422, 
                    message: 'Validation failed', 
                    errors: data.errors 
                };
            });
        }
        
        // Check for other errors
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        
        // Handle successful verification session creation
        if (data.status === 'success' && data.verification_url) {
            // Show success toast
            showToast('Verification session created successfully!', 'success');
            
            // Store session info for future reference
            if (data.session_id) {
                localStorage.setItem('veriff_session_id', data.session_id);
            }
            if (data.temp_user_id) {
                localStorage.setItem('veriff_temp_user_id', data.temp_user_id);
            }
            
            // Set button on cooldown for 5 minutes (persists across page refreshes)
            setButtonCooldown();
            
            // Check button cooldown immediately to update the UI
            checkButtonCooldown();
            
            // Set a flag to prevent duplicate redirects
            if (!window.verificationWindowOpened) {
                window.verificationWindowOpened = true;
                
                // Show SweetAlert redirect countdown
                setTimeout(() => {
                    showRedirectCountdown(data.verification_url, CONFIG.redirectCountdownSeconds);
                }, 500);
                
                // Reset the flag after some time
                setTimeout(() => {
                    window.verificationWindowOpened = false;
                }, 5000);
            }
        } else {
            // Show warning toast
            showToast('Verification process initiated but no redirect URL received', 'warning');
            console.log('Session data:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Handle validation errors specifically
        if (error.status === 422) {
            // Display each validation error as a toast
            for (const field in error.errors) {
                showToast(`${error.errors[field].join(", ")}`, 'error');
            }
        } else {
            // Show generic error toast
            showToast('An error occurred while submitting the form. Please try again.', 'error');
        }
    })
    .finally(() => {
        // Get the button again (in case the page structure changed)
        const proceedButton = document.getElementById('verifyProceedButton');
        
        // Reset button state but keep disabled if on cooldown
        if (proceedButton) {
            setButtonLoading(proceedButton, false);
            
            // Re-check cooldown status - button may still be disabled
            checkButtonCooldown();
        }
        
        // Reset submission flag
        window.isVerifySubmitting = false;
    });
}


// Configuration variables
const CONFIG = {
    redirectCountdownSeconds: 3,     // Countdown time in seconds before redirect
    cooldownDurationMinutes: 5       // Cooldown duration in minutes
};

// Function to show countdown for redirect using SweetAlert
function showRedirectCountdown(url, seconds = CONFIG.redirectCountdownSeconds) {
    let timerInterval;
    let progressInterval;
    let progress = 100;
    const progressStep = 100 / (seconds * 10); // Update progress 10 times per second
    
    Swal.fire({
        title: "Information",
        html: `Redirecting to verification in <b>${seconds}</b> seconds...`,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Redirect Now",
        cancelButtonText: "Cancel",
        customClass: {
            confirmButton: "btn btn-dark",
            cancelButton: "btn btn-secondary"
        },
        buttonsStyling: false,
        allowOutsideClick: false,
        didOpen: () => {
            // Add progress bar
            const content = Swal.getHtmlContainer();
            if (content) {
                const progressContainer = document.createElement('div');
                progressContainer.style.marginTop = '1rem';
                progressContainer.innerHTML = `
                    <div class="progress" style="height: 5px; background-color: #e9ecef; border-radius: 3px; overflow: hidden;">
                        <div id="swal-progress-bar" style="height: 100%; width: 100%; background-color: #4cb050; transition: width 0.1s linear;"></div>
                    </div>
                `;
                content.appendChild(progressContainer);
            }
            
            // Start progress bar animation
            const progressBar = document.getElementById('swal-progress-bar');
            
            // Update timer text
            const timerBox = Swal.getHtmlContainer().querySelector('b');
            
            // Set up timer
            timerInterval = setInterval(() => {
                if (timerBox) {
                    const secondsLeft = Math.max(0, Math.ceil(progress / (100 / seconds)));
                    timerBox.textContent = secondsLeft;
                }
            }, 100);
            
            // Set up progress bar
            progressInterval = setInterval(() => {
                progress -= progressStep;
                if (progress <= 0) {
                    progress = 0;
                    clearInterval(progressInterval);
                    clearInterval(timerInterval);
                    
                    // Close the modal and open URL after the timer completes
                    Swal.close();
                    window.open(url, '_blank');
                }
                
                if (progressBar) {
                    progressBar.style.width = `${progress}%`;
                }
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
            clearInterval(progressInterval);
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(url, '_blank');
        }
    });
    
    return { timerInterval, progressInterval };
}

// Function to clear error for a specific field
function clearVerifyFieldError(field) {
    if (!field) return; // Safety check
    
    // Remove is-invalid class
    field.classList.remove('is-invalid');
    
    // Find the error message
    let feedbackElement;
    
    // Special handling for checkbox
    if (field.type === 'checkbox') {
        // Find the closest parent that contains the feedback element
        const parentContainer = field.closest('.position-relative') || field.closest('.fv-row');
        if (parentContainer) {
            feedbackElement = parentContainer.querySelector('.invalid-feedback');
        }
    } else {
        // Standard handling for other inputs
        feedbackElement = field.nextElementSibling;
    }
    
    // If feedback element found, hide it
    if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
        feedbackElement.style.display = 'none';
    }
}


// Form validation and submission
function submitVerifyForm(event) {
    if (event) event.preventDefault();
    
    console.log('Submit function called');
    
    // Double-check the submission flag
    if (window.isVerifySubmitting === true) {
        console.log('Submission already in progress, ignoring click');
        return false;
    }
    
    // Find the form (it might have changed since page load)
    let form = document.getElementById('verify-form');
    if (!form) {
        console.error("Form with ID 'verify-form' not found");
        
        // Try to find the form by other means as fallback
        const allForms = document.querySelectorAll('form');
        console.log('Forms found on page:', allForms.length);
        
        // Use the form containing the verify button as fallback
        const verifyButton = document.getElementById('verifyProceedButton');
        if (verifyButton) {
            const closestForm = verifyButton.closest('form');
            if (closestForm) {
                console.log('Found form via button proximity');
                form = closestForm;
            }
        }
        
        if (!form) {
            console.error('No form found, aborting submission');
            return false;
        }
    }
    
    // Validate before setting loading state or processing
    if (!validateForm(form)) {
        console.log('Form validation failed, aborting submission');
        return false;
    }
    
    // Set the flag to prevent multiple submissions AFTER validation passes
    window.isVerifySubmitting = true;
    
    // Set button to loading state AFTER validation passes
    const verifyButton = document.getElementById('verifyProceedButton');
    if (verifyButton) {
        console.log('Setting button to loading state');
        setButtonLoading(verifyButton, true);
    } else {
        console.log('Button not found for loading state');
    }
    
    // If validation passes, process the form
    processVerifyForm(form);
    return false;
}


// Separate validation function
function validateForm(form) {
    // Reset all error messages
    clearAllVerifyErrors(form);
    
    // Get form values
    const idNumber = form.querySelector('[name="verify_idNumber"]')?.value.trim() || '';
    const documentType = form.querySelector('[name="verify_type"]')?.value || '';
    const documentNumber = form.querySelector('[name="verify_number"]')?.value.trim() || '';
    const fullAddress = form.querySelector('[name="verify_fullAddress"]')?.value.trim() || '';
    const termsAgreed = form.querySelector('[name="verify_terms"]')?.checked || false;
    
    // Validation flag
    let isValid = true;
    
    // Validate each field and show errors
    if (!idNumber) {
        showVerifyFieldError(form.querySelector('[name="verify_idNumber"]'), 'National identification number is required');
        isValid = false;
    }
    
    if (!documentType) {
        showVerifyFieldError(form.querySelector('[name="verify_type"]'), 'Document type is required');
        isValid = false;
    }
    
    if (!documentNumber) {
        showVerifyFieldError(form.querySelector('[name="verify_number"]'), 'Document number is required');
        isValid = false;
    }
    
    if (!fullAddress) {
        showVerifyFieldError(form.querySelector('[name="verify_fullAddress"]'), 'Full address is required');
        isValid = false;
    }
    
    if (!termsAgreed) {
        showVerifyFieldError(form.querySelector('[name="verify_terms"]'), 'You must agree to the terms and conditions');
        isValid = false;
    }
    
    return isValid;
}

// Function to show error for a specific field
function showVerifyFieldError(field, message) {
    if (!field) return; // Safety check
    
    // Add is-invalid class to the field
    field.classList.add('is-invalid');
    
    // Find the error feedback element
    let feedbackElement;
    
    // Special handling for checkbox (look for feedback in parent div)
    if (field.type === 'checkbox') {
        // Find the closest parent div that contains the feedback element
        const parentContainer = field.closest('.position-relative') || field.closest('.fv-row');
        if (parentContainer) {
            feedbackElement = parentContainer.querySelector('.invalid-feedback');
        }
    } else {
        // Standard handling for other inputs
        feedbackElement = field.nextElementSibling;
    }
    
    // If feedback element found, show the message
    if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
        feedbackElement.textContent = message;
        feedbackElement.style.display = 'block';
    }
}

// Function to clear all error messages
function clearAllVerifyErrors(form) {
    if (!form) return; // Safety check
    
    // Get all form fields
    const inputs = form.querySelectorAll('input, select');
    
    // Clear errors for each field
    inputs.forEach(input => {
        clearVerifyFieldError(input);
    });
}

// Address Autocomplete Integration
function initAddressAutocomplete() {
    // Configuration
    const CONFIG = {
        mapboxToken:  'sk.eyJ1IjoiZmFpdGhkaW5ubyIsImEiOiJjbTNlaTBpemYwZGg0MmlxeHBvdmN1Njc1In0.DxTSIOGOsItCew8yHtscJw', // Replace with your actual Mapbox token
        minQueryLength: 3,                       // Minimum characters before triggering search
        debounceTime: 300,                       // Debounce time in milliseconds
        countryCodes: ['ca'],                     // Default country restriction
        resultLimit: 5                           // Maximum number of suggestions to show
    };
    
    // Elements
    const addressInput = document.querySelector('[name="verify_fullAddress"]');
    if (!addressInput) {
        console.error('Address input field not found');
        return;
    }
    
    // Create suggestions container
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'address-suggestions';
    suggestionsContainer.style.cssText = `
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 4px 4px;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    `;
    addressInput.parentNode.style.position = 'relative';
    addressInput.parentNode.appendChild(suggestionsContainer);
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
    
    // Fetch suggestions from Mapbox
    const fetchSuggestions = debounce((query) => {
        // Don't search if query is too short
        if (query.length < CONFIG.minQueryLength) {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.style.display = 'none';
            return;
        }
        
        const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${CONFIG.mapboxToken}&autocomplete=true&country=${CONFIG.countryCodes.join(',')}&limit=${CONFIG.resultLimit}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                renderSuggestions(data.features);
            })
            .catch(error => {
                console.error('Error fetching address suggestions:', error);
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.style.display = 'none';
            });
    }, CONFIG.debounceTime);
    
    // Render suggestions
    function renderSuggestions(features) {
        // Clear existing suggestions
        suggestionsContainer.innerHTML = '';
        
        if (features.length === 0) {
            suggestionsContainer.style.display = 'none';
            return;
        }
        
        // Create suggestion items
        features.forEach(feature => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.style.cssText = `
                padding: 10px 15px;
                cursor: pointer;
                border-bottom: 1px solid #eee;
            `;
            item.textContent = feature.place_name;
            
            // Hover effect
            item.addEventListener('mouseover', () => {
                item.style.backgroundColor = '#f8f9fa';
            });
            
            item.addEventListener('mouseout', () => {
                item.style.backgroundColor = 'white';
            });
            
            // Select address
            item.addEventListener('click', () => {
                addressInput.value = feature.place_name;
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.style.display = 'none';
                
                // Clear any existing errors
                clearVerifyFieldError(addressInput);
                
                // Trigger a change event on the input
                const event = new Event('change', { bubbles: true });
                addressInput.dispatchEvent(event);
            });
            
            suggestionsContainer.appendChild(item);
        });
        
        // Show the suggestions container
        suggestionsContainer.style.display = 'block';
    }
    
    // Input event handler
    addressInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        fetchSuggestions(query);
    });
    
    // Handle keyboard navigation
    addressInput.addEventListener('keydown', (e) => {
        const items = suggestionsContainer.querySelectorAll('.suggestion-item');
        const activeItem = suggestionsContainer.querySelector('.suggestion-item.active');
        
        if (items.length === 0) return;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            
            if (!activeItem) {
                // Activate first item
                items[0].classList.add('active');
                items[0].style.backgroundColor = '#e9ecef';
            } else {
                // Move to next item
                const currentIndex = Array.from(items).indexOf(activeItem);
                const nextIndex = (currentIndex + 1) % items.length;
                
                activeItem.classList.remove('active');
                activeItem.style.backgroundColor = 'white';
                
                items[nextIndex].classList.add('active');
                items[nextIndex].style.backgroundColor = '#e9ecef';
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            
            if (!activeItem) {
                // Activate last item
                items[items.length - 1].classList.add('active');
                items[items.length - 1].style.backgroundColor = '#e9ecef';
            } else {
                // Move to previous item
                const currentIndex = Array.from(items).indexOf(activeItem);
                const prevIndex = (currentIndex - 1 + items.length) % items.length;
                
                activeItem.classList.remove('active');
                activeItem.style.backgroundColor = 'white';
                
                items[prevIndex].classList.add('active');
                items[prevIndex].style.backgroundColor = '#e9ecef';
            }
        } else if (e.key === 'Enter' && activeItem) {
            e.preventDefault();
            activeItem.click();
        } else if (e.key === 'Escape') {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.style.display = 'none';
        }
    });
    
    // Close suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (e.target !== addressInput && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.style.display = 'none';
        }
    });
    
    // Initialize with a message if needed
    console.log('Address autocomplete initialized');
}

// Initialize once the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    initAddressAutocomplete();
});

// If the page might load content dynamically, provide a method to reinitialize
window.initAddressAutocomplete = initAddressAutocomplete;