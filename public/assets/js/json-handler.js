// Function to safely handle JSON values
function safelyHandleJson(jsonString) {
    if (!jsonString) return '{}';
    
    try {
        // Try to parse it to verify it's valid JSON
        const parsed = JSON.parse(jsonString);
        return jsonString; // Return the original string if parsing succeeded
    } catch (e) {
        console.error('Invalid JSON:', e);
        return '{}'; // Return empty object if invalid
    }
}

// Function to safely display JSON in the UI
function displayJsonValue(jsonString) {
    try {
        // Try to parse and prettify
        const parsed = JSON.parse(jsonString);
        return JSON.stringify(parsed, null, 2);
    } catch (e) {
        console.error('Error displaying JSON:', e);
        return jsonString || '{}'; // Return the original or empty if it fails
    }
}

// Function to safely validate JSON input
function validateJsonInput(jsonString) {
    try {
        JSON.parse(jsonString);
        return true;
    } catch (e) {
        return false;
    }
}

// Update the edit form submission handler to handle JSON better
document.addEventListener('DOMContentLoaded', function() {
    // Handle JSON input in edit form
    const editForm = document.getElementById('kt_modal_edit_setting_form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const settingType = document.getElementById('edit_type').value;
            
            // Special handling for JSON type
            if (settingType === 'json') {
                const jsonInput = document.getElementById('edit_value_json');
                if (jsonInput) {
                    const jsonValue = jsonInput.value;
                    
                    // Validate JSON before submission
                    if (!validateJsonInput(jsonValue)) {
                        e.preventDefault();
                        
                        Swal.fire({
                            title: 'Invalid JSON',
                            text: 'Please enter valid JSON data',
                            icon: 'error',
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                        return;
                    }
                }
            }
        });
    }
    
    // Handle JSON display when edit modal is opened
    const editSettingModal = document.getElementById('kt_modal_edit_setting');
    if (editSettingModal) {
        editSettingModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const settingType = button.getAttribute('data-setting-type');
            const settingValue = button.getAttribute('data-setting-value');
            
            if (settingType === 'json') {
                const jsonInput = document.getElementById('edit_value_json');
                if (jsonInput) {
                    try {
                        // Try to format the JSON nicely for editing
                        jsonInput.value = displayJsonValue(settingValue);
                    } catch (e) {
                        // Fallback to the raw value
                        jsonInput.value = settingValue || '{}';
                    }
                }
            }
        });
    }
    
    // Handle JSON input in add form
    const addForm = document.getElementById('kt_modal_add_setting_form');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            const settingType = document.getElementById('setting_type').value;
            
            // Special handling for JSON type
            if (settingType === 'json') {
                const jsonInput = document.getElementById('value_json');
                if (jsonInput) {
                    const jsonValue = jsonInput.value;
                    
                    // Validate JSON before submission
                    if (!validateJsonInput(jsonValue)) {
                        e.preventDefault();
                        
                        Swal.fire({
                            title: 'Invalid JSON',
                            text: 'Please enter valid JSON data',
                            icon: 'error',
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                        return;
                    }
                }
            }
        });
    }
});