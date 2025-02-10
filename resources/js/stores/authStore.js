// import { defineStore } from "pinia";
// import { useToast } from "vue-toastification";
// import router from "@/router";
// import axios from "@/utils/axios";
// import Swal from "sweetalert2";

// export const useAuthStore = defineStore("auth", {
//     state: () => ({
//         loading: false,
//         errors: {},
//         settings: {}, // Initialize settings as an empty object
//         user: JSON.parse(localStorage.getItem("user")) || null,
//         isAuthenticated: !!localStorage.getItem("isAuthenticated"),
//         token: localStorage.getItem("authToken") || null,
//         sessionPingInterval: null,
//     }),
//     actions: {
//         /**
//          * Login the user and initialize state
//          */
//         async updateUserState(data, key = null) {
//             try {
//                 // Update the user state in the Pinia store
//                 if (key) {
//                     // Update only the specified field
//                     if (!this.user) {
//                         this.user = {};
//                     }
//                     this.user[key] = data[key];
//                 } else {
//                     // Update the entire user record
//                     this.user = data;
//                 }

//                 // Also update the user in localStorage
//                 localStorage.setItem("user", JSON.stringify(this.user));

//                 this.isAuthenticated = true;
//             } catch (error) {
//                 console.error("Error updating user state:", error);
//                 this.user = null;
//                 this.isAuthenticated = false;
//             }
//         },
//         async login(email, password) {
//             this.errors = {}; // Clear previous errors
//             this.loading = true; // Start loading state
//             const toast = useToast();

//             // Client-side validation
//             if (!email) {
//                 this.errors.email = "Email is required.";
//             } else if (!this.isValidEmail(email)) {
//                 this.errors.email = "Please enter a valid email address.";
//             }

//             if (!password) {
//                 this.errors.password = "Password is required.";
//             } else if (password.length < 8) {
//                 this.errors.password =
//                     "Password must be at least 8 characters.";
//             }

//             // If there are validation errors, stop further execution
//             if (Object.keys(this.errors).length > 0) {
//                 this.loading = false; // Reset loading state
//                 toast.error("Please fix the validation errors.", {
//                     timeout: 5000,
//                 });
//                 return; // Exit the function
//             }

//             // Proceed to make API request
//             try {
//                 const response = await axios.post("/auth/sign-in", {
//                     email,
//                     password,
//                 });

//                 if (response.data.authenticated) {
//                     this.setAuth(response.data.user, response.data.token); // Set user and token

//                     if (response.data.otp_required) {
//                         toast.info("Two-Factor Authentication required.", {
//                             timeout: 3000,
//                         });
//                         router.push(response.data.redirect_url); // Redirect to OTP verification
//                     } else {
//                         toast.success("Login successful!", { timeout: 3000 });
//                         router.push(response.data.redirect_url); // Redirect to dashboard
//                     }
//                 }
//             } catch (error) {
//                 this.handleAuthErrors(error, toast); // Handle server-side errors
//             } finally {
//                 this.loading = false; // Reset loading state
//             }
//         },
//         isValidEmail(email) {
//             const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email format validation
//             return regex.test(email);
//         },
//         /**
//          * Logout the user
//          */
//         async logout() {
//             const toast = useToast(); // Toast for notifications

//             try {
//                 // Show SweetAlert2 confirmation modal
//                 const result = await Swal.fire({
//                     title: "Are you sure?",
//                     text: "You will be logged out of your account!",
//                     icon: "warning",
//                     showCancelButton: true,
//                     confirmButtonText: "Yes, log me out!",
//                     cancelButtonText: "Cancel",
//                     customClass: {
//                         confirmButton: "btn btn-danger",
//                         cancelButton: "btn btn-secondary",
//                     },
//                     buttonsStyling: false,
//                 });

//                 // Only proceed if user confirms
//                 if (!result.isConfirmed) {
//                     // toast.warning("Logout cancelled.", { timeout: 3000 });
//                     return;
//                 }

//                 this.loading = true; // Show loader during logout

//                 // API call for logout
//                 const response = await axios.post("/logout");

//                 if (response.status === 200 && response.data.success) {
//                     // Clear user state
//                     this.user = null;
//                     this.isAuthenticated = false;
//                     this.token = null;

//                     // Clear localStorage
//                     localStorage.removeItem("user");
//                     localStorage.removeItem("isAuthenticated");
//                     localStorage.removeItem("authToken");

//                     // Remove token from Axios headers
//                     delete axios.defaults.headers.common["Authorization"];
//                     // Fetch a new CSRF token
//                     axios.get("/csrf-token").then((response) => {
//                         const newCsrfToken = response.data.csrfToken;

//                         // Update Axios default headers with the new token
//                         axios.defaults.headers.common["X-CSRF-TOKEN"] =
//                             newCsrfToken;

//                         // Optionally, redirect to the login page or re-render it
//                         // window.location.reload();
//                     });
//                     toast.success("Logged out successfully!", {
//                         timeout: 3000,
//                     });

//                     // Redirect to login page
//                     router.push({ name: "Login" });
//                 } else {
//                     toast.error(
//                         response.data.message ||
//                             "Failed to log out. Please try again.",
//                         { timeout: 5000 }
//                     );
//                 }
//             } catch (error) {
//                 console.error("Logout Error:", error);

//                 toast.error(
//                     error.response?.data?.message ||
//                         "An unexpected error occurred during logout.",
//                     { timeout: 5000 }
//                 );
//             } finally {
//                 this.loading = false; // Reset loading state
//             }
//         },

//         async logout2(showConfirmation = true) {
//             const toast = useToast();

//             // Stop session ping
//             if (this.sessionPingInterval) {
//                 clearInterval(this.sessionPingInterval);
//                 this.sessionPingInterval = null;
//             }

//             if (showConfirmation) {
//                 const confirmed = await this.confirmLogout();
//                 if (!confirmed) {
//                     toast.warning("Logout cancelled.", { timeout: 3000 });
//                     return;
//                 }
//             }

//             this.loading = true;

//             try {
//                 const response = await axios.post("/logout");

//                 if (response.status === 200) {
//                     this.clearAuth();
//                     toast.success("Logged out successfully!", {
//                         timeout: 3000,
//                     });
//                     router.push({ name: "Login" });
//                 }
//             } catch (error) {
//                 console.error("Logout Error:", error);
//                 toast.error("An error occurred during logout.", {
//                     timeout: 5000,
//                 });
//             } finally {
//                 this.loading = false;
//             }
//         },

//         async logoutWithoutConfirmation() {
//             const toast = useToast();

//             // Stop session ping
//             if (this.sessionPingInterval) {
//                 clearInterval(this.sessionPingInterval);
//                 this.sessionPingInterval = null;
//             }

//             this.loading = true;

//             try {
//                 // API call for logout
//                 const response = await axios.post("/logout");
//                 if (response.status === 200 && response.data.success) {
//                     // Clear user state
//                     this.user = null;
//                     this.isAuthenticated = false;
//                     this.token = null;

//                     // Clear localStorage
//                     localStorage.removeItem("user");
//                     localStorage.removeItem("isAuthenticated");
//                     localStorage.removeItem("authToken");

//                     // Remove token from Axios headers
//                     delete axios.defaults.headers.common["Authorization"];
//                     // Fetch a new CSRF token
//                     axios.get("/csrf-token").then((response) => {
//                         const newCsrfToken = response.data.csrfToken;

//                         // Update Axios default headers with the new token
//                         axios.defaults.headers.common["X-CSRF-TOKEN"] =
//                             newCsrfToken;

//                         // Optionally, redirect to the login page or re-render it
//                         // window.location.reload();
//                     });
//                     toast.success("Logged out successfully!", {
//                         timeout: 3000,
//                     });

//                     // Redirect to login page
//                     router.push({ name: "Login" });
//                 } else {
//                     toast.error(
//                         response.data.message ||
//                             "Failed to log out. Please try again.",
//                         { timeout: 5000 }
//                     );
//                 }
//             } catch (error) {
//                 console.error("Logout Error:", error);
//                 toast.error("An error occurred during logout.", {
//                     timeout: 5000,
//                 });
//             } finally {
//                 this.loading = false;
//             }
//         },

//         /**
//          * Fetch app settings
//          */
//         async fetchSettings() {
//             try {
//                 const response = await axios.get("/app/settings");
//                 this.settings = response.data || {}; // Assign fetched settings or default to empty object
//             } catch (error) {
//                 console.error("Error fetching settings:", error);
//                 this.settings = {}; // Ensure settings is always an object
//             }
//         },

//         /**
//          * Fetch authentication status
//          */
//         async fetchAuthStatus() {
//             try {
//                 const response = await axios.get("/auth/status");
//                 if (response.data.authenticated) {
//                     this.setAuth(response.data.user, this.token);
//                 } else {
//                     this.clearAuth();
//                 }
//             } catch (error) {
//                 console.error("Auth Status Error:", error);
//                 this.clearAuth();
//             }
//         },

//         /**
//          * Set user authentication state
//          */
//         setAuth(user, token) {
//             this.user = user;
//             this.token = token;
//             this.isAuthenticated = true;

//             localStorage.setItem("user", JSON.stringify(user));
//             localStorage.setItem("isAuthenticated", "true");
//             localStorage.setItem("authToken", token);

//             axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
//             this.startSessionPing();
//         },

//         /**
//          * Clear authentication state
//          */
//         clearAuth() {
//             this.user = null;
//             this.isAuthenticated = false;
//             this.token = null;

//             localStorage.removeItem("user");
//             localStorage.removeItem("isAuthenticated");
//             localStorage.removeItem("authToken");

//             delete axios.defaults.headers.common["Authorization"];
//             this.stopSessionPing();
//         },

//         async handleSessionTimeout() {
//             const authStore = useAuthStore();
//             // authStore.logout(); // Clear authentication state
//             await axios.post("/logout");
//             console.info("Session timeout handled. User logged out.");
//         },
//         /**
//          * Handle session ping for session validity
//          */
//         startSessionPing() {
//             if (this.sessionPingInterval || !this.isAuthenticated) return; // Ensure user is authenticated and no existing interval

//             this.sessionPingInterval = setInterval(async () => {
//                 try {
//                     await axios.get("/api/ping");
//                 } catch (error) {
//                     if (error.response?.status === 401) {
//                         this.stopSessionPing(); // Stop the ping interval
//                         this.clearAuth(); // Clear authentication data
//                         handleSessionTimeout(); // Call a function to handle session timeout
//                         router.push({ name: "Login" }); // Redirect to login
//                     }
//                 }
//             }, 300000); // 5 minutes
//         },

//         stopSessionPing() {
//             if (this.sessionPingInterval) {
//                 clearInterval(this.sessionPingInterval);
//                 this.sessionPingInterval = null;
//             }
//         },

//         /**
//          * Handle authentication-related errors
//          */
//         handleAuthErrors(error, toast) {
//             // console.log(error.response, error.response.status);

//             if (error.response) {
//                 if (error.response.status === 422) {
//                     // Validation errors from the server
//                     this.errors = error.response.data.errors;
//                     toast.error("Validation failed. Please check your input.", {
//                         timeout: 5000,
//                     });
//                 } else if (
//                     error.response?.status === 200 &&
//                     error.response.data.authenticated
//                 ) {
//                     // Backend session is active but Vue state was lost, rehydrate state
//                     this.setAuth(
//                         error.response.data.user,
//                         error.response.data.token
//                     );
//                     router.push("/dashboard");
//                 } else if (error.response.status === 401) {
//                     toast.error(
//                         error.response.data.message || "Invalid credentials.",
//                         { timeout: 5000 }
//                     );
//                 } else {
//                     toast.error("An error occurred. Please try again.", {
//                         timeout: 5000,
//                     });
//                 }
//             } else if (error.request) {
//                 toast.error(
//                     "Failed to connect to the server. Please try again later.",
//                     { timeout: 5000 }
//                 );
//             } else {
//                 toast.error("An unexpected error occurred.", { timeout: 5000 });
//             }
//         },
//         rehydrateState() {
//             // Rehydrate state from localStorage if available
//             this.updateUserState(JSON.parse(localStorage.getItem("user")));
//             this.isAuthenticated = !!localStorage.getItem("isAuthenticated");
//             this.token = localStorage.getItem("authToken");
//         },

//         /**
//          * Confirm logout action with SweetAlert
//          */
//         async confirmLogout() {
//             const result = await Swal.fire({
//                 title: "Are you sure?",
//                 text: "You will be logged out of your account!",
//                 icon: "warning",
//                 showCancelButton: true,
//                 confirmButtonText: "Yes, log me out!",
//                 cancelButtonText: "Cancel",
//                 customClass: {
//                     popup: "logout-popup",
//                     confirmButton: "btn btn-danger",
//                     cancelButton: "btn btn-secondary",
//                 },
//                 buttonsStyling: false,
//                 backdrop: true, // Dim the background
//             });

//             return result.isConfirmed;
//         },
//     },
// });

import { defineStore } from "pinia";
import { useToast } from "vue-toastification";
import router from "@/router";
import {
    pageRequest,
    silentRequest,
    overlayRequest,
} from "@/utils/axios"; // Import the new Axios request types
import Swal from "sweetalert2";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        loading: false,
        errors: {},
        settings: {}, // Initialize settings as an empty object
        user: JSON.parse(localStorage.getItem("user")) || null,
        isAuthenticated: !!localStorage.getItem("isAuthenticated"),
        token: localStorage.getItem("authToken") || null,
        sessionPingInterval: null,
    }),
    getters: {
        userIsTeamMember(state) {
            return state.user?.is_team && state.user?.team_id !== null;
        },
    },
    actions: {
        /**
         * Login the user and initialize state
         */
        async updateUserState(data, key = null) {
            try {
                if (key) {
                    if (!this.user) this.user = {};
                    this.user[key] = data[key];
                } else {
                    this.user = data;
                }
                localStorage.setItem("user", JSON.stringify(this.user));
                this.isAuthenticated = true;
            } catch (error) {
                console.error("Error updating user state:", error);
                this.user = null;
                this.isAuthenticated = false;
            }
        },
        isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email format validation
            return regex.test(email);
        },
        async login(email, password) {
            this.errors = {};
            this.loading = true;
            const toast = useToast();

            if (!email) {
                this.errors.email = "Email is required.";
            } else if (!this.isValidEmail(email)) {
                this.errors.email = "Please enter a valid email address.";
            }

            if (!password) {
                this.errors.password = "Password is required.";
            } else if (password.length < 8) {
                this.errors.password = "Password must be at least 8 characters.";
            }

            if (Object.keys(this.errors).length > 0) {
                this.loading = false;
                toast.error("Please fix the validation errors.", { timeout: 5000 });
                return;
            }

            try {
                const response = await pageRequest.post("/auth/sign-in", {
                    email,
                    password,
                });

                if (response.data.authenticated) {
                    this.setAuth(response.data.user, response.data.token);

                    if (response.data.otp_required) {
                        toast.info("Two-Factor Authentication required.", {
                            timeout: 3000,
                        });
                        router.push(response.data.redirect_url);
                    } else {
                        toast.success("Login successful!", { timeout: 3000 });
                        router.push(response.data.redirect_url);
                    }
                }
            } catch (error) {
                this.handleAuthErrors(error, toast);
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            const toast = useToast();

            try {
                const result = await Swal.fire({
                    title: "Are you sure?",
                    text: "You will be logged out of your account!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, log me out!",
                    cancelButtonText: "Cancel",
                    customClass: {
                        confirmButton: "btn btn-danger",
                        cancelButton: "btn btn-secondary",
                    },
                    buttonsStyling: false,
                });

                if (!result.isConfirmed) return;

                this.loading = true;

                const response = await overlayRequest.post("/logout");

                if (response.data.success) {
                    this.clearAuth();

                    const csrfResponse = await silentRequest.get("/csrf-token");
                    axios.defaults.headers.common["X-CSRF-TOKEN"] =
                        csrfResponse.data.csrfToken;

                    toast.success("Logged out successfully!", { timeout: 3000 });
                    router.push({ name: "Login" });
                } else {
                    toast.error(response.data.message || "Failed to log out.", {
                        timeout: 5000,
                    });
                }
            } catch (error) {
                console.error("Logout Error:", error);
                toast.error("An unexpected error occurred during logout.", {
                    timeout: 5000,
                });
            } finally {
                this.loading = false;
            }
        },

        async fetchSettings() {
            try {
                const response = await silentRequest.get("/app/settings");
                this.settings = response.data || {};
            } catch (error) {
                console.error("Error fetching settings:", error);
                this.settings = {};
            }
        },

        async fetchAuthStatus() {
            try {
                const response = await silentRequest.get("/auth/status");
                if (response.data.authenticated) {
                    this.setAuth(response.data.user, this.token);
                } else {
                    this.clearAuth();
                }
            } catch (error) {
                console.error("Auth Status Error:", error);
                this.clearAuth();
            }
        },

        setAuth(user, token) {
            this.user = user;
            this.token = token;
            this.isAuthenticated = true;

            localStorage.setItem("user", JSON.stringify(user));
            localStorage.setItem("isAuthenticated", "true");
            localStorage.setItem("authToken", token);

            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            this.startSessionPing();
        },

        clearAuth() {
            this.user = null;
            this.isAuthenticated = false;
            this.token = null;

            localStorage.removeItem("user");
            localStorage.removeItem("isAuthenticated");
            localStorage.removeItem("authToken");

            delete axios.defaults.headers.common["Authorization"];
            this.stopSessionPing();
        },

        async handleSessionTimeout() {
            await overlayRequest.post("/logout");
            console.info("Session timeout handled. User logged out.");
        },

        startSessionPing() {
            if (this.sessionPingInterval || !this.isAuthenticated) return;

            this.sessionPingInterval = setInterval(async () => {
                try {
                    await silentRequest.get("/api/ping");
                } catch (error) {
                    if (error.response?.status === 401) {
                        this.stopSessionPing();
                        this.clearAuth();
                        this.handleSessionTimeout();
                        router.push({ name: "Login" });
                    }
                }
            }, 300000);
        },

        stopSessionPing() {
            if (this.sessionPingInterval) {
                clearInterval(this.sessionPingInterval);
                this.sessionPingInterval = null;
            }
        },

        handleAuthErrors(error, toast) {
            if (error.response) {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                    toast.error("Validation failed. Please check your input.", {
                        timeout: 5000,
                    });
                } else if (error.response.status === 401) {
                    toast.error(
                        error.response.data.message || "Invalid credentials.",
                        { timeout: 5000 }
                    );
                } else {
                    toast.error("An error occurred. Please try again.", {
                        timeout: 5000,
                    });
                }
            } else if (error.request) {
                toast.error("Failed to connect to the server.", {
                    timeout: 5000,
                });
            } else {
                toast.error("An unexpected error occurred.", { timeout: 5000 });
            }
        },
    },
});
