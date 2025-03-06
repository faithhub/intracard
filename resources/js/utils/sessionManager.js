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

    const keepAliveInterval = setInterval(keepSessionAlive, 10 * 60 * 1000);
    // Your existing code
    sessionTimeoutWarning = setTimeout(() => {
        const toast = useToast();
        toast.info(
            "Your session will expire soon. Please refresh or continue using the app.",
            { timeout: 3000 }
        );
    }, 14 * 60 * 1000);

    sessionTimeout = setTimeout(async () => {
        try {
            await silentRequest.get("/api/ping");
            resetSessionTimers();
        } catch (error) {
            if (error.response?.status === 401) {
                handleSessionTimeout();
            }
        }
    }, 15 * 60 * 1000);

    // Store interval ID so it can be cleared later
    return keepAliveInterval;
}

export function resetSessionTimers() {
    clearTimeout(sessionTimeoutWarning);
    clearTimeout(sessionTimeout);
    startSessionTimers();
}

// Add this function to your sessionManager.js
export async function keepSessionAlive() {
    try {
        await silentRequest.get("/api/keep-alive");
        return true;
    } catch (error) {
        console.error("Failed to keep session alive:", error);
        if (error.response?.status === 401 || error.response?.status === 419) {
            handleSessionTimeout();
        }
        return false;
    }
}
