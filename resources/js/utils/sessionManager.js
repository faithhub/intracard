// import { useToast } from 'vue-toastification'; // Use the package name directly
// import router from '@/router/index.js';
// import axios from '@/utils/axios.js';
// import { useAuthStore } from '@/stores/authStore.js';

// let sessionTimeoutHandled = false; // Flag to prevent multiple alerts
// let sessionTimeoutWarning;
// let sessionTimeout;

// export function handleSessionTimeout() {
//     const authStore = useAuthStore();
//     if (sessionTimeoutHandled || !authStore.isAuthenticated) return;

//     sessionTimeoutHandled = true;

//     const toast = useToast();
//     // Clear localStorage
//     localStorage.removeItem("user");
//     localStorage.removeItem("isAuthenticated");
//     localStorage.removeItem("authToken");

//     // Clear Axios token
//     delete axios.defaults.headers.common["Authorization"];

//     // Display notification
//     toast.warning("Session expired. Please log in again.", {
//         timeout: 5000,
//     });

//     // Redirect to login page
//     router.push({ name: "Login" }); // Use `router.push` instead of `next`

//     sessionTimeoutHandled = false; // Reset flag after navigation
// }

// export function startSessionTimers() {
//     sessionTimeoutWarning = setTimeout(() => {
//         const toast = useToast();
//         toast.info(
//             "Your session will expire soon. Please refresh or continue using the app.",
//             { timeout: 60000 } // 1-minute warning
//         );
//     }, 14 * 60 * 1000); // 14 minutes

//     sessionTimeout = setTimeout(() => {
//         handleSessionTimeout();
//     }, 15 * 60 * 1000); // 15 minutes
// }

// export function resetSessionTimers() {
//     clearTimeout(sessionTimeoutWarning);
//     clearTimeout(sessionTimeout);
//     startSessionTimers();
// }

import { useToast } from "vue-toastification"; // Use the package name directly
import router from "@/router/index.js";
import { silentRequest } from "@/utils/axios.js"; // Use the updated Axios request type
import { useAuthStore } from "@/stores/authStore.js";

let sessionTimeoutHandled = false; // Flag to prevent multiple alerts
let sessionTimeoutWarning;
let sessionTimeout;

export function handleSessionTimeout() {
    const authStore = useAuthStore();
    if (sessionTimeoutHandled || !authStore.isAuthenticated) return;

    sessionTimeoutHandled = true;

    const toast = useToast();

    // Clear localStorage
    localStorage.removeItem("user");
    localStorage.removeItem("isAuthenticated");
    localStorage.removeItem("authToken");

    // Display notification
    toast.warning("Session expired. Please log in again.", {
        timeout: 5000,
    });

    // Clear user session in the store
    authStore.clearAuth();

    // Redirect to login page
    router.push({ name: "Login" });

    sessionTimeoutHandled = false; // Reset flag after navigation
}

export async function startSessionTimers() {
    sessionTimeoutWarning = setTimeout(() => {
        const toast = useToast();
        toast.info(
            "Your session will expire soon. Please refresh or continue using the app.",
            { timeout: 3000 } // 1-minute warning
        );
    }, 14 * 60 * 1000); // 14 minutes

    sessionTimeout = setTimeout(async () => {
        try {
            // Attempt to ping the server to validate session before timeout
            await silentRequest.get("/api/ping");
            resetSessionTimers(); // Reset timers if session is still valid
        } catch (error) {
            if (error.response?.status === 401) {
                handleSessionTimeout(); // Handle session expiration
            }
        }
    }, 15 * 60 * 1000); // 15 minutes
}

export function resetSessionTimers() {
    clearTimeout(sessionTimeoutWarning);
    clearTimeout(sessionTimeout);
    startSessionTimers();
}
