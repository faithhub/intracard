/**
 * Global Toast Notification System
 * 
 * This script sets up a global error handling system using Axios interceptors
 * and displays toast notifications for various HTTP errors and responses.
 */

// Track active toasts to prevent duplicates
const activeToasts = new Set();
let toastContainer;

// Configure Axios defaults and interceptors
const setupAxiosInterceptors = () => {
    // Add CSRF token to all requests
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Response interceptor to handle errors globally
    axios.interceptors.response.use(
        // Success handler
        response => {
            // If the response has a success message, show it
            if (response.data && response.data.success === true && response.data.message) {
                showToast('success', response.data.message);
            }
            
            return response;
        },
        // Error handler
        error => {
            handleAxiosError(error);
            return Promise.reject(error);
        }
    );
};

// Handle different types of Axios errors
const handleAxiosError = (error) => {
    // Network errors (no response)
    if (!error.response) {
        showToast('error', 'Network error. Please check your connection.');
        return;
    }
    
    const { status, data } = error.response;
    
    // Handle different status codes
    switch (status) {
        case 401: // Unauthorized
            showToast('error', 'Session expired. Please log in again.');
            // Optionally redirect to login page
            setTimeout(() => window.location.href = '/login', 2000);
            break;
            
        case 403: // Forbidden
            showToast('error', 'You do not have permission to perform this action.');
            break;
            
        case 404: // Not Found
            showToast('error', 'The requested resource was not found.');
            break;
            
        case 419: // CSRF token mismatch
            showToast('error', 'Your session has expired. Please refresh the page.');
            break;
            
        case 422: // Validation errors
            handleValidationErrors(data);
            break;
            
        case 429: // Too Many Requests
            showToast('warning', 'Too many requests. Please try again later.');
            break;
            
        case 500: // Server Error
            showToast('error', 'Server error. Please try again later or contact support.');
            break;
            
        default:
            // Default error message
            const message = data.message || 'An unexpected error occurred.';
            showToast('error', message);
    }
};

// Handle validation errors specifically
const handleValidationErrors = (data) => {
    if (data.errors) {
        // Get the first error message from each field
        const errorMessages = Object.values(data.errors)
            .map(fieldErrors => fieldErrors[0]);
        
        // Display each validation error as a separate toast
        errorMessages.forEach(message => {
            showToast('warning', message);
        });
    } else if (data.message) {
        showToast('warning', data.message);
    }
};

// Show toast notification using Bootstrap Toast
const showToast = (type, message) => {
    // Prevent duplicate messages
    const toastKey = `${type}:${message}`;
    if (activeToasts.has(toastKey)) {
        return; // Skip duplicate toast
    }
    
    // Ensure the toast container exists
    initToastContainer();
    
    // Clear existing toasts with similar messages to prevent duplicates
    clearSimilarToasts(message);
    
    // Mark this message as active
    activeToasts.add(toastKey);
    
    // Configure toast based on type
    const bgColor = getToastBgColor(type);
    
    // Create a unique ID for the toast
    const toastId = 'toast-' + Date.now();
    
    // Create toast element
    const toastHtml = `
        <div id="${toastId}" class="toast ${bgColor} border-0 shadow-sm" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center p-3">
                ${getToastIcon(type)}
                <div class="toast-body px-2 py-0 fw-medium">
                    ${message}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Add toast to container
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Initialize and show the toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    
    toast.show();
    
    // Remove toast from DOM after it's hidden
    toastElement.addEventListener('hidden.bs.toast', () => {
        activeToasts.delete(toastKey);
        toastElement.remove();
    });
};

// Initialize toast container once
const initToastContainer = () => {
    if (!toastContainer) {
        // Remove any existing container to prevent duplicates
        const existingContainer = document.getElementById('toast-container');
        if (existingContainer) {
            existingContainer.remove();
        }
        
        // Create new container
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
};

// Clear toasts with similar text to prevent duplicates
const clearSimilarToasts = (message) => {
    const normalizedMessage = message.toLowerCase().trim();
    
    document.querySelectorAll('.toast').forEach(toast => {
        const toastBody = toast.querySelector('.toast-body');
        if (toastBody) {
            const toastText = toastBody.textContent.toLowerCase().trim();
            // Check if messages are similar (substring or contains relationship)
            if (toastText.includes(normalizedMessage) || normalizedMessage.includes(toastText)) {
                const bsToast = bootstrap.Toast.getInstance(toast);
                if (bsToast) {
                    bsToast.hide();
                } else {
                    toast.remove();
                }
            }
        }
    });
};

// Get toast background class based on type
const getToastBgColor = (type) => {
    switch (type) {
        case 'success':
            return 'bg-light-success text-success';
        case 'error':
            return 'bg-light-danger text-danger';
        case 'warning':
            return 'bg-light-warning text-dark';
        case 'info':
            return 'bg-light-info text-info';
        default:
            return 'bg-light-secondary text-secondary';
    }
};

// Get toast icon based on type
const getToastIcon = (type) => {
    switch (type) {
        case 'success':
            return '<div class="rounded-circle bg-success p-2 d-flex align-items-center justify-content-center me-3" style="width: 28px; height: 28px;"><i class="fas fa-check text-white fs-6"></i></div>';
        case 'error':
            return '<div class="rounded-circle bg-danger p-2 d-flex align-items-center justify-content-center me-3" style="width: 28px; height: 28px;"><i class="fas fa-times text-white fs-6"></i></div>';
        case 'warning':
            return '<div class="rounded-circle bg-warning p-2 d-flex align-items-center justify-content-center me-3" style="width: 28px; height: 28px;"><i class="fas fa-exclamation text-white fs-6"></i></div>';
        case 'info':
            return '<div class="rounded-circle bg-info p-2 d-flex align-items-center justify-content-center me-3" style="width: 28px; height: 28px;"><i class="fas fa-info text-white fs-6"></i></div>';
        default:
            return '';
    }
};

// Handle Laravel session flash messages using our toast system
const handleSessionFlashes = () => {
    // Clear any pre-rendered session toast elements
    const flashContainer = document.getElementById('toast-container');
    if (flashContainer) {
        flashContainer.innerHTML = '';
    }
    
    // Check for data attributes on body for flash messages
    const body = document.body;
    
    if (body.dataset.flashSuccess) {
        showToast('success', body.dataset.flashSuccess);
        delete body.dataset.flashSuccess;
    }
    
    if (body.dataset.flashError) {
        showToast('error', body.dataset.flashError);
        delete body.dataset.flashError;
    }
    
    if (body.dataset.flashWarning) {
        showToast('warning', body.dataset.flashWarning);
        delete body.dataset.flashWarning;
    }
    
    if (body.dataset.flashInfo) {
        showToast('info', body.dataset.flashInfo);
        delete body.dataset.flashInfo;
    }
};

// Initialize once DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    setupAxiosInterceptors();
    initToastContainer();
    
    // Clear any existing toasts that might be pre-rendered
    toastContainer.innerHTML = '';
    
    // Handle any Laravel session flashes
    handleSessionFlashes();
});

// Create global function to manually trigger toasts
window.showToast = showToast;