// import { createRouter, createWebHistory } from "vue-router";
// import { useAuthStore } from "@/stores/authStore";
// import { handleSessionTimeout } from "@/utils/sessionManager";
// import { useLoaderStore } from "@/stores/loaderStore";
// import axios from "axios";
// import userRoutes from "./userRoutes";

// const routes = [
//     ...userRoutes,
//     {
//         path: "",
//         name: "HomeRoot",
//         component: () =>
//             import(/* webpackChunkName: "home" */ "@/components/home/LandingPage.vue"),
//     },
//     {
//         path: "/home",
//         name: "Home",
//         component: () =>
//             import(/* webpackChunkName: "home" */ "@/components/home/LandingPage.vue"),
//     },
//     {
//         path: "/auth/sign-in",
//         name: "Login",
//         component: () =>
//             import(/* webpackChunkName: "auth" */ "@/components/auth/Login.vue"),
//         meta: { guest: true },
//     },
//     {
//         path: "/:pathMatch(.*)*",
//         name: "NotFound",
//         component: () =>
//             import(/* webpackChunkName: "errors" */ "@/components/errors/NotFound.vue"),
//     },
// ];

// const router = createRouter({
//     history: createWebHistory(),
//     routes,
// });

// router.beforeEach(async (to, from, next) => {
//     const loaderStore = useLoaderStore();
//     const authStore = useAuthStore();

//     loaderStore.showLoader(); // Start the loader at the beginning of navigation

//     try {
//         // Skip authentication checks for unrestricted routes
//         if (!to.meta.requiresAuth && !to.meta.guest) {
//             return next(); // Allow access
//         }

//         // Redirect authenticated users from guest-only routes
//         if (to.meta.guest && authStore.isAuthenticated) {
//             console.log("Redirecting authenticated user to Dashboard.");
//             return next({ name: "Dashboard" });
//         }

//         // For protected routes, check authentication status
//         if (to.meta.requiresAuth && !authStore.isAuthenticated) {
//             const response = await axios.get("/auth/status");
//             if (response.data.authenticated) {
//                 authStore.setAuth(response.data.user, null);
//             } else {
//                 console.log("User not authenticated. Redirecting to Login.");
//                 return next({ name: "Login" });
//             }
//         }

//         // Validate session for authenticated users
//         if (authStore.isAuthenticated) {
//             try {
//                 await axios.get("/api/ping");
//             } catch (error) {
//                 if (error.response && error.response.status === 401) {
//                     console.warn("Session expired. Redirecting to Login.");
//                     handleSessionTimeout(); // Clear auth state
//                     return next({ name: "Login" });
//                 }
//             }
//         }

//         // Load user settings if not already loaded
//         if (
//             authStore.isAuthenticated &&
//             (!authStore.settings || !Object.keys(authStore.settings).length)
//         ) {
//             await authStore.fetchSettings();
//         }

//         // Handle OTP routes
//         const otpRequired = authStore.settings?.enable_2fa === "true";
//         const otpVerified = authStore.user?.otp_verified;

//         if (to.name === "OTPVerify") {
//             if (!otpRequired) {
//                 return next({ name: "Dashboard" });
//             }
//             if (otpRequired && otpVerified) {
//                 return next({ name: "Dashboard" });
//             }
//         }

//         if (to.meta.requiresOtp && otpRequired && !otpVerified) {
//             return next({ name: "OTPVerify" });
//         }

//         // Default: Allow the navigation
//         next();
//     } catch (error) {
//         console.error("Error during route navigation:", error);
//         next(error); // Pass error to Vue Router's error handler
//     } finally {
//         loaderStore.hideLoader(); // Ensure the loader is hidden only after everything completes
//     }
// });

// // Handle After Navigation
// router.afterEach(() => {
//     const loaderStore = useLoaderStore();
//     // Ensure loader is hidden after rendering delay
//     setTimeout(() => {
//         loaderStore.hideLoader();
//     }, 500); // Add slight delay for smooth experience
// });

// export default router;


import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/authStore";
import { handleSessionTimeout } from "@/utils/sessionManager";
import { useLoaderStore } from "@/stores/loaderStore";
import { pageRequest, silentRequest } from "@/utils/axios"; // Import the updated Axios request models
import userRoutes from "./userRoutes";

const routes = [
    ...userRoutes,
    {
        path: "",
        name: "HomeRoot",
        component: () =>
            import(/* webpackChunkName: "auth" */ "@/components/auth/Login.vue"),
    },
    {
        path: "/home",
        name: "Home",
        component: () =>
            import(/* webpackChunkName: "auth" */ "@/components/auth/Login.vue"),
    },
    {
        path: "/auth/sign-in",
        name: "Login",
        component: () =>
            import(/* webpackChunkName: "auth" */ "@/components/auth/Login.vue"),
        meta: { guest: true },
    },
    {
        path: '/team/members/decline/:token',
        name: 'decline-invitation',
        component: () =>
            import(/* webpackChunkName: "auth" */ "@/components/auth/DeclineInvitation.vue"),
        // meta: { guest: true },
    },
    {
        path: "/auth/team/:token",
        name: "TeamRegister",
        component: () =>
            import(/* webpackChunkName: "auth" */ "@/components/auth/TeamRegister.vue"),
        // meta: { guest: true },
    },
    {
        path: "/auth/reg",
        name: "Register",
        component: () =>
            import(/* webpackChunkName: "auth" */ "@/components/auth/Register.vue"),
        meta: { guest: true },
    },
    {
        path: "/otp-verify",
        name: "OTPVerify",
        component: () =>
            import(/* webpackChunkName: "auth" */ "@/components/auth/OTPVerify.vue"),
        meta: { requiresOtp: true },
    },
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: () =>
            import(/* webpackChunkName: "errors" */ "@/components/errors/NotFound.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const loaderStore = useLoaderStore();
    const authStore = useAuthStore();

    loaderStore.showLoader(); // Start the loader at the beginning of navigation

    try {
        // Skip authentication checks for unrestricted routes
        if (!to.meta.requiresAuth && !to.meta.guest) {
            return next();
        }

        // Refresh CSRF token before authentication checks
        try {
            await silentRequest.get("/sanctum/csrf-cookie");
        } catch (csrfError) {
            console.error("Failed to refresh CSRF token:", csrfError);
            handleSessionTimeout(); // Clear auth state
            return next({ name: "Login" });
        }

        // Redirect authenticated users from guest-only routes
        if (to.meta.guest && authStore.isAuthenticated) {
            console.log("Redirecting authenticated user to Dashboard.");
            return next({ name: "Dashboard" });
        }

        // For protected routes, check authentication status
        if (to.meta.requiresAuth && !authStore.isAuthenticated) {
            const response = await silentRequest.get("/auth/status");
            if (response.data.authenticated) {
                authStore.setAuth(response.data.user, null);
            } else {
                console.log("User not authenticated. Redirecting to Login.");
                return next({ name: "Login" });
            }
        }

        // Validate session for authenticated users
        if (authStore.isAuthenticated) {
            try {
                await silentRequest.get("/api/ping");
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    console.warn("Session expired. Redirecting to Login.");
                    handleSessionTimeout(); // Clear auth state
                    return next({ name: "Login" });
                }
            }
        }

        // Load user settings if not already loaded
        if (
            authStore.isAuthenticated &&
            (!authStore.settings || !Object.keys(authStore.settings).length)
        ) {
            await pageRequest.get("/api/user-settings");
        }

        // Handle OTP routes
        const otpRequired = authStore.settings?.enable_2fa === "true";
        const otpVerified = authStore.user?.otp_verified;

        if (to.name === "OTPVerify") {
            if (!otpRequired) {
                return next({ name: "Dashboard" });
            }
            if (otpRequired && otpVerified) {
                return next({ name: "Dashboard" });
            }
        }

        if (to.meta.requiresOtp && otpRequired && !otpVerified) {
            return next({ name: "OTPVerify" });
        }


        if (to.meta.requiresTeam && !authStore.userIsTeamMember) {
            return next('/dashboard'); // Redirect to dashboard if not part of a team
        }

        // Default: Allow the navigation
        next();
    } catch (error) {
        console.error("Error during route navigation:", error);
        next(error); // Pass error to Vue Router's error handler
    } finally {
        loaderStore.hideLoader(); // Ensure the loader is hidden only after everything completes
    }
});

// Handle After Navigation
router.afterEach(() => {
    const loaderStore = useLoaderStore();
    // Ensure loader is hidden after rendering delay
    setTimeout(() => {
        loaderStore.hideLoader();
    }, 500); // Add slight delay for smooth experience
});

export default router;
