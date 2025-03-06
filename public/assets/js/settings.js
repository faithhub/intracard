/**
 * Enhanced Form Validation Script for Settings
 * This handles client-side validation before form submission
 */

// Utility function to check if a key exists - with timeout and error handling
async function checkIfKeyExists(key) {
    return new Promise((resolve) => {
        // Set a timeout to prevent hanging
        const timeoutId = setTimeout(() => {
            console.error('Key existence check timed out');
            resolve(false); // Assume it doesn't exist if timeout
        }, 5000); // 5 second timeout

        fetch(`/admin/settings/check-key?key=${encodeURIComponent(key)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                clearTimeout(timeoutId); // Clear timeout on response
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                resolve(data.exists);
            })
            .catch(error => {
                clearTimeout(timeoutId); // Clear timeout on error
                console.error('Error checking key existence:', error);
                resolve(false); // Assume it doesn't exist if there's an error
            });
    });
}

// Display validation errors in a nice format
function displayValidationErrors(errors) {
    let errorHtml = '';

    if (typeof errors === 'object') {
        errorHtml = '<div class="text-start mb-3">';

        for (const field in errors) {
            if (errors.hasOwnProperty(field) && errors[field].length) {
                errorHtml += `<div class="text-danger mb-2"><strong>${field}:</strong> ${errors[field][0]}</div>`;
            }
        }

        errorHtml += '</div>';
    } else if (typeof errors === 'string') {
        errorHtml = `<div class="text-danger">${errors}</div>`;
    }

    return errorHtml;
}

// Validate setting form fields
function validateSettingForm(form, isEdit = false) {
    const errors = {};
    let hasErrors = false;

    // Get form fields
    const keyInput = form.querySelector('[name="key"]');
    const nameInput = form.querySelector('[name="name"]');
    const typeSelect = isEdit ?
        document.getElementById('edit_type') :
        document.getElementById('setting_type');

    // Validate key (only for add, not edit)
    if (!isEdit && keyInput) {
        const key = keyInput.value.trim();
        if (!key) {
            errors.key = ['The key field is required.'];
            hasErrors = true;
        } else if (!/^[a-zA-Z0-9_]+$/.test(key)) {
            errors.key = ['The key may only contain letters, numbers, and underscores.'];
            hasErrors = true;
        }
    }

    // Validate name
    if (nameInput) {
        const name = nameInput.value.trim();
        if (!name) {
            errors.name = ['The name field is required.'];
            hasErrors = true;
        }
    }

    // Validate value based on type
    const type = typeSelect ? typeSelect.value : 'string';

    if (type === 'boolean') {
        // Boolean always has a value (checked or unchecked)
    } else if (type === 'json') {
        const jsonInput = isEdit ?
            document.getElementById('edit_value_json') :
            document.getElementById('value_json');

        if (jsonInput) {
            const jsonValue = jsonInput.value.trim();
            if (!jsonValue) {
                errors.value = ['The value field is required.'];
                hasErrors = true;
            } else {
                try {
                    JSON.parse(jsonValue);
                } catch (e) {
                    errors.value = ['The value must be valid JSON.'];
                    hasErrors = true;
                }
            }
        } else {
            errors.value = ['JSON input field not found.'];
            hasErrors = true;
        }
    } else {
        // String, integer, or file
        const valueInput = isEdit ?
            document.getElementById('edit_value_text') :
            document.getElementById('value_text');

        if (valueInput) {
            const value = valueInput.value.trim();
            if (!value) {
                errors.value = ['The value field is required.'];
                hasErrors = true;
            }
        } else {
            errors.value = ['Value input field not found.'];
            hasErrors = true;
        }
    }

    return { hasErrors, errors };
}

// Initialize validation on document ready
document.addEventListener('DOMContentLoaded', function () {
    // Add Setting Form Validation
    const addSettingForm = document.getElementById('kt_modal_add_setting_form');
    if (addSettingForm) {
        // Replace the existing submit event handler
        addSettingForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Get the submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            if (!submitBtn) {
                console.error('Submit button not found');
                return;
            }

            const originalBtnText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validating...';

            try {
                // Get key value
                const keyInput = this.querySelector('[name="key"]');
                if (!keyInput) {
                    throw new Error('Key input not found');
                }

                const keyValue = keyInput.value.trim();
                if (!keyValue) {
                    throw new Error('Key is required');
                }

                // Check key format
                if (!/^[a-zA-Z0-9_]+$/.test(keyValue)) {
                    throw new Error('Key may only contain letters, numbers, and underscores');
                }

                // Check for name
                const nameInput = this.querySelector('[name="name"]');
                if (!nameInput || !nameInput.value.trim()) {
                    throw new Error('Name is required');
                }

                // Get type and value
                const typeSelect = document.getElementById('setting_type');
                if (!typeSelect) {
                    throw new Error('Type select not found');
                }

                const selectedType = typeSelect.value;
                let settingValue;

                // Validate value based on type
                if (selectedType === 'boolean') {
                    const booleanInput = document.getElementById('value_boolean');
                    settingValue = booleanInput && booleanInput.checked ? '1' : '0';
                } else if (selectedType === 'json') {
                    const jsonInput = document.getElementById('value_json');
                    if (!jsonInput) {
                        throw new Error('JSON input not found');
                    }

                    const jsonValue = jsonInput.value.trim();
                    if (!jsonValue) {
                        throw new Error('JSON value is required');
                    }

                    try {
                        JSON.parse(jsonValue);
                        settingValue = jsonValue;
                    } catch (jsonError) {
                        throw new Error('Invalid JSON format');
                    }
                } else {
                    // String, integer, file
                    const textInput = document.getElementById('value_text');
                    if (!textInput) {
                        throw new Error('Value input not found');
                    }

                    settingValue = textInput.value.trim();
                    if (!settingValue) {
                        throw new Error('Value is required');
                    }
                }

                // Update button text for key check
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking key...';

                // Check if key already exists
                const keyExists = await checkIfKeyExists(keyValue);

                if (keyExists) {
                    throw new Error('The key has already been taken');
                }

                // If we get here, validation passed
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                // Create hidden value input if needed
                let valueInput = this.querySelector('input[name="value"]');
                if (!valueInput) {
                    valueInput = document.createElement('input');
                    valueInput.type = 'hidden';
                    valueInput.name = 'value';
                    this.appendChild(valueInput);
                }
                valueInput.value = settingValue;

                // Submit the form
                const formData = new FormData(this);

                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const responseData = await response.json();

                if (!response.ok) {
                    throw new Error(responseData.message || 'Server error');
                }

                // Success! Close modal and show message
                const modal = bootstrap.Modal.getInstance(document.getElementById('kt_modal_add_setting'));
                if (modal) {
                    modal.hide();
                }

                // Show success message
                Swal.fire({
                    html: `
                        <div class="text-center">
                            <div class="d-flex justify-content-center my-4">
                                <div class="rounded-circle p-3" style="background-color: rgba(144, 238, 144, 0.3);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="mb-3">Success!</h4>
                            <p>${responseData.message || 'Setting added successfully!'}</p>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-lg',
                        confirmButton: 'btn btn-primary px-4 rounded'
                    }
                }).then(() => {
                    // Reload page after clicking OK
                    window.location.reload();
                });

            } catch (error) {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                // Show error
                Swal.fire({
                    title: 'Validation Error',
                    text: error.message || 'Please check your inputs',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }
        });
    }

    /**
     * Fixed Edit Form Script
     * This addresses the issue with the edit form not working properly
     */
    // Edit Setting Form functionality
    const editSettingModal = document.getElementById('kt_modal_edit_setting');
    if (editSettingModal) {
        editSettingModal.addEventListener('show.bs.modal', function (event) {
            // Get data from the button that triggered the modal
            const button = event.relatedTarget;
            if (!button) {
                console.error('Edit button not found');
                return;
            }

            // Extract setting data from button attributes
            const settingId = button.getAttribute('data-setting-id');
            const settingKey = button.getAttribute('data-setting-key');
            const settingName = button.getAttribute('data-setting-name') || '';
            const settingValue = button.getAttribute('data-setting-value');
            const settingType = button.getAttribute('data-setting-type');

            console.log('Edit modal opened with data:', {
                id: settingId,
                key: settingKey,
                name: settingName,
                value: settingValue,
                type: settingType
            });

            // Get the form
            const form = document.getElementById('kt_modal_edit_setting_form');
            if (!form) {
                console.error('Edit form not found');
                return;
            }

            // Update form action URL
            if (form.getAttribute('data-action-url')) {
                const actionUrl = form.getAttribute('data-action-url').replace('__id__', settingId);
                form.action = actionUrl;
            } else {
                // Fallback
                form.action = `/admin/settings/${settingId}`;
            }

            // Populate hidden ID field
            const idField = document.getElementById('edit_id');
            if (idField) idField.value = settingId;

            // Populate key field
            const keyField = document.getElementById('edit_key');
            if (keyField) keyField.value = settingKey;

            // Populate name field
            const nameField = document.getElementById('edit_name');
            if (nameField) nameField.value = settingName;

            // Populate type field
            const typeField = document.getElementById('edit_type');
            if (typeField) typeField.value = settingType;

            // Handle value field based on type
            // First hide all value fields
            const textValueDiv = document.getElementById('edit_value_text_div');
            const booleanValueDiv = document.getElementById('edit_value_boolean_div');
            const jsonValueDiv = document.getElementById('edit_value_json_div');

            if (textValueDiv) textValueDiv.classList.add('d-none');
            if (booleanValueDiv) booleanValueDiv.classList.add('d-none');
            if (jsonValueDiv) jsonValueDiv.classList.add('d-none');

            // Show and populate the appropriate value field
            if (settingType === 'boolean') {
                if (booleanValueDiv) {
                    booleanValueDiv.classList.remove('d-none');
                    const checkbox = document.getElementById('edit_value_boolean');
                    if (checkbox) checkbox.checked = settingValue === '1' || settingValue === 'true';
                }
            } else if (settingType === 'json') {
                if (jsonValueDiv) {
                    jsonValueDiv.classList.remove('d-none');
                    const jsonInput = document.getElementById('edit_value_json');
                    if (jsonInput) {
                        try {
                            // Try to parse and prettify the JSON
                            const parsed = JSON.parse(settingValue);
                            jsonInput.value = JSON.stringify(parsed, null, 2);
                        } catch (e) {
                            // If not valid JSON, just set the raw value
                            jsonInput.value = settingValue || '{}';
                        }
                    }
                }
            } else {
                // Default to text input for string, integer, file, etc.
                if (textValueDiv) {
                    textValueDiv.classList.remove('d-none');
                    const textInput = document.getElementById('edit_value_text');
                    if (textInput) textInput.value = settingValue;
                }
            }
        });

        // Handle form submission
        const editForm = document.getElementById('kt_modal_edit_setting_form');
        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Get submit button
                const submitBtn = this.querySelector('button[type="submit"]');
                if (!submitBtn) {
                    console.error('Submit button not found');
                    return;
                }

                // Store original button text
                const originalBtnText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';

                try {
                    // Get the setting type
                    const settingType = document.getElementById('edit_type').value;
                    if (!settingType) throw new Error('Setting type is missing');

                    // Get the setting value based on type
                    let settingValue;

                    if (settingType === 'boolean') {
                        const boolInput = document.getElementById('edit_value_boolean');
                        settingValue = boolInput && boolInput.checked ? '1' : '0';
                    } else if (settingType === 'json') {
                        const jsonInput = document.getElementById('edit_value_json');
                        if (!jsonInput) throw new Error('JSON input not found');

                        settingValue = jsonInput.value.trim();
                        if (!settingValue) throw new Error('JSON value is required');

                        // Validate JSON
                        try {
                            JSON.parse(settingValue);
                        } catch (jsonError) {
                            throw new Error('Invalid JSON format');
                        }
                    } else {
                        const textInput = document.getElementById('edit_value_text');
                        if (!textInput) throw new Error('Text input not found');

                        settingValue = textInput.value.trim();
                        if (!settingValue) throw new Error('Value is required');
                    }

                    // Create or update hidden value input
                    let valueInput = this.querySelector('input[name="value"]');
                    if (valueInput) {
                        valueInput.value = settingValue;
                    } else {
                        valueInput = document.createElement('input');
                        valueInput.type = 'hidden';
                        valueInput.name = 'value';
                        valueInput.value = settingValue;
                        this.appendChild(valueInput);
                    }

                    // Create FormData
                    const formData = new FormData(this);

                    // Submit form via AJAX
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    // throw new Error(data.message || 'Server error');
                                    throw data;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Success! Close modal and show success message
                            const modal = bootstrap.Modal.getInstance(document.getElementById('kt_modal_edit_setting'));
                            if (modal) modal.hide();

                            // Show success message
                            Swal.fire({
                                html: `
                                <div class="text-center">
                                    <div class="d-flex justify-content-center my-4">
                                        <div class="rounded-circle p-3" style="background-color: rgba(144, 238, 144, 0.3);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6L9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h4 class="mb-3">Success!</h4>
                                    <p>${data.message || 'Setting updated successfully!'}</p>
                                </div>
                            `,
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                customClass: {
                                    popup: 'rounded-lg',
                                    confirmButton: 'btn btn-primary px-4 rounded'
                                }
                            }).then(() => {
                                // Reload page
                                window.location.reload();
                            });
                        })
                        // In your catch block
                        .catch(errorData => {
                            console.log("my error log", errorData);

                            // Reset button state
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;

                            // Extract error message
                            let errorMessage = 'An unexpected error occurred';
                            let errorDetails = '';

                            // Check if it's a validation error with specific field errors
                            if (errorData.errors) {
                                // Combine all error messages for each field
                                errorDetails = Object.entries(errorData.errors)
                                    .map(([field, messages]) =>
                                        `${messages.join(', ')}`
                                    )
                                    .join('\n');
                                    // .map(([field, messages]) =>
                                    //     `${field.charAt(0).toUpperCase() + field.slice(1)}: ${messages.join(', ')}`
                                    // )
                                    // .join('\n');
                            }

                            // Display the error directly from the API response
                            Swal.fire({
                                title: 'Validation Error',
                                text:errorDetails,
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        });
                } catch (error) {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'An error occurred while updating the setting',
                        icon: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                }
            });
        }
    }
});

