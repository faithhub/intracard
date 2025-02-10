// import axios from "axios";
// import { useAuthStore } from "@/stores/authStore";
// import { useLoaderStore } from "@/stores/loaderStore";
// import { handleSessionTimeout } from "@/utils/sessionManager";

// // Axios Defaults
// axios.defaults.withCredentials = true; // Include cookies in requests
// axios.defaults.baseURL = import.meta.env.MIX_APP_API_URL || "/"; // Base URL
// axios.defaults.timeout = 10000; // 10-second timeout

// // Fetch CSRF token once
// const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

// // Fetch fresh CSRF token
// const fetchCsrfToken = async () => {
//     try {
//         const response = await axios.get("/csrf-token");
//         const newToken = response.data.csrfToken;
//         axios.defaults.headers.common["X-CSRF-TOKEN"] = newToken;
//         console.log("CSRF token refreshed:", newToken);
//         return newToken;
//     } catch (error) {
//         console.error("Failed to fetch CSRF token:", error);
//         throw error;
//     }
// };

// // Track active requests
// let activeRequests = 0;

// const incrementRequests = () => {
//     // const loaderStore = useLoaderStore();
//     // activeRequests += 1; // Increment active request count
//     // loaderStore.showLoader(); // Show loader
//     // console.log(`Request started. Active requests: ${activeRequests}`);
// };

// const decrementRequests = () => {
//     const loaderStore = useLoaderStore();
//     activeRequests = Math.max(0, activeRequests - 1); // Decrement active request count safely
//     if (activeRequests === 0) {
//         loaderStore.hideLoader(); // Hide loader if no active requests remain
//         console.log("Loader hidden. All pending tasks completed.");
//     } else {
//         console.log(`Task completed. Remaining active requests: ${activeRequests}`);
//     }
// };

// // Add a request interceptor
// axios.interceptors.request.use(
//     (config) => {
//         const authStore = useAuthStore();

//         incrementRequests(); // Increment active request count and show loader

//         const token = authStore?.token; // Get the authentication token

//         // Add CSRF token if available
//         if (csrfToken) {
//             config.headers["X-CSRF-TOKEN"] = csrfToken;
//         }

//         // Add Authorization header if token is available
//         if (token) {
//             config.headers["Authorization"] = `Bearer ${token}`;
//         }

//         return config;
//     },
//     (error) => {
//         decrementRequests(); // Decrement active request count and update loader
//         return Promise.reject(error);
//     }
// );

// // Add a response interceptor
// axios.interceptors.response.use(
//     (response) => {
//         decrementRequests(); // Decrement active request count and update loader
//         return response;
//     },
//     async (error) => {
//         decrementRequests(); // Decrement active request count and update loader

//         if (error.response) {
//             const status = error.response.status;

//             // Handle 401 Unauthorized errors
//             if (status === 401) {
//                 const authStore = useAuthStore();
//                 authStore.stopSessionPing(); // Stop session ping
//                 authStore.clearAuth(); // Clear authentication state
//                 handleSessionTimeout(); // Trigger session timeout handling
//             }

//             // Handle 419 CSRF token mismatch errors
//             if (status === 419) {
//                 console.warn("CSRF token mismatch detected. Fetching new token...");
//                 try {
//                     const newCsrfToken = await fetchCsrfToken();
//                     const originalRequest = error.config;
//                     originalRequest.headers["X-CSRF-TOKEN"] = newCsrfToken;
//                     return axios(originalRequest); // Retry the original request
//                 } catch (csrfError) {
//                     console.error("Failed to retry request after fetching CSRF token:", csrfError);
//                 }
//             }
//         }

//         return Promise.reject(error);
//     }
// );

// export default axios;


// import axios from "axios";
// import { useAuthStore } from "@/stores/authStore";
// import { useLoaderStore } from "@/stores/loaderStore";
// import { handleSessionTimeout } from "@/utils/sessionManager";

// // Axios Defaults
// axios.defaults.withCredentials = true;
// axios.defaults.baseURL = import.meta.env.MIX_APP_API_URL || "/";
// axios.defaults.timeout = 30000;

// // Track if we're currently refreshing the CSRF token
// let isRefreshingCSRF = false;
// let failedQueue = [];
// let activeRequests = 0;

// // Process failed queue
// const processQueue = (error, token = null) => {
//     failedQueue.forEach(prom => {
//         if (error) {
//             prom.reject(error);
//         } else {
//             prom.resolve(token);
//         }
//     });
//     failedQueue = [];
// };

// // Loader management
// const incrementRequests = () => {
//     const loaderStore = useLoaderStore();
//     activeRequests += 1;
//     loaderStore.showLoader();
//     console.log(`Request started. Active requests: ${activeRequests}`);
// };

// const decrementRequests = () => {
//     const loaderStore = useLoaderStore();
//     activeRequests = Math.max(0, activeRequests - 1);
//     if (activeRequests === 0) {
//         loaderStore.hideLoader();
//         console.log("Loader hidden. All pending tasks completed.");
//     } else {
//         console.log(`Task completed. Remaining active requests: ${activeRequests}`);
//     }
// };

// const fetchCsrfToken = async () => {
//     if (isRefreshingCSRF) {
//         return new Promise((resolve, reject) => {
//             failedQueue.push({ resolve, reject });
//         });
//     }

//     isRefreshingCSRF = true;

//     try {
//         const response = await axios.get("/sanctum/csrf-cookie");
//         const responseData = await axios.get("/csrf-token");
//         const newToken = responseData.data.csrfToken;
//         axios.defaults.headers.common["X-CSRF-TOKEN"] = newToken;
//         // const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
//         // axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
//         isRefreshingCSRF = false;
//         processQueue(null, token);
//         return token;
//     } catch (error) {
//         isRefreshingCSRF = false;
//         processQueue(error);
//         throw error;
//     }
// };

// // Request interceptor
// axios.interceptors.request.use(
//     (config) => {
//         const authStore = useAuthStore();
//         incrementRequests();

//         const token = authStore?.token;
//         if (token) {
//             config.headers["Authorization"] = `Bearer ${token}`;
//         }

//         return config;
//     },
//     (error) => {
//         decrementRequests();
//         return Promise.reject(error);
//     }
// );

// // Response interceptor
// axios.interceptors.response.use(
//     (response) => {
//         decrementRequests();
//         return response;
//     },
//     async (error) => {
//         decrementRequests();

//         const originalRequest = error.config;

//         // Prevent infinite loops
//         if (originalRequest._retry) {
//             return Promise.reject(error);
//         }

//         if (error.response?.status === 419 && !originalRequest._retry) {
//             originalRequest._retry = true;
            
//             try {
//                 const token = await fetchCsrfToken();
//                 originalRequest.headers["X-CSRF-TOKEN"] = token;
//                 return axios(originalRequest);
//             } catch (refreshError) {
//                 console.error("Failed to refresh CSRF token:", refreshError);
//                 // If CSRF refresh fails, handle session timeout
//                 const authStore = useAuthStore();
//                 authStore.stopSessionPing();
//                 authStore.clearAuth();
//                 handleSessionTimeout();
//                 return Promise.reject(refreshError);
//             }
//         }

//         // Handle 401 Unauthorized errors
//         if (error.response?.status === 401) {
//             const authStore = useAuthStore();
//             authStore.stopSessionPing();
//             authStore.clearAuth();
//             handleSessionTimeout();
//         }

//         return Promise.reject(error);
//     }
// );

// export default axios;

import axios from "axios";
import { useAuthStore } from "@/stores/authStore";
import { useLoaderStore, LoadingType } from "@/stores/loaderStore";
import { handleSessionTimeout } from "@/utils/sessionManager";

// Axios Defaults
axios.defaults.withCredentials = true;
axios.defaults.baseURL = import.meta.env.MIX_APP_API_URL || "/";
axios.defaults.timeout = 30000;

// CSRF token refresh management
let isRefreshingCSRF = false;
let failedQueue = [];

// Process failed queue after CSRF refresh
const processQueue = (error, token = null) => {
    failedQueue.forEach(prom => {
        if (error) {
            prom.reject(error);
        } else {
            prom.resolve(token);
        }
    });
    failedQueue = [];
};

// Fetch new CSRF token
const fetchCsrfToken = async () => {
    if (isRefreshingCSRF) {
        return new Promise((resolve, reject) => {
            failedQueue.push({ resolve, reject });
        });
    }

    isRefreshingCSRF = true;

    try {
        const response = await axios.get("/sanctum/csrf-cookie");
        const responseData = await axios.get("/csrf-token");
        const newToken = responseData.data.csrfToken;
        axios.defaults.headers.common["X-CSRF-TOKEN"] = newToken;
        isRefreshingCSRF = false;
        processQueue(null, newToken);
        return newToken;
    } catch (error) {
        isRefreshingCSRF = false;
        processQueue(error);
        throw error;
    }
};

// Request interceptor
axios.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore();
        const loaderStore = useLoaderStore();

        // Handle loading state based on config
        const loadingType = config.loadingType || LoadingType.BACKGROUND;
        if (loadingType !== LoadingType.NONE) {
            loaderStore.showLoader(loadingType);
        }

        // Add loading type to config for response interceptor
        config._loadingType = loadingType;

        // Add auth token if available
        const token = authStore?.token;
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }

        return config;
    },
    (error) => {
        const loaderStore = useLoaderStore();
        const loadingType = error.config?._loadingType;
        
        if (loadingType && loadingType !== LoadingType.NONE) {
            loaderStore.hideLoader(loadingType);
        }
        
        return Promise.reject(error);
    }
);

// Response interceptor
axios.interceptors.response.use(
    (response) => {
        const loaderStore = useLoaderStore();
        const loadingType = response.config._loadingType;
        
        if (loadingType && loadingType !== LoadingType.NONE) {
            loaderStore.hideLoader(loadingType);
        }
        
        return response;
    },
    async (error) => {
        const loaderStore = useLoaderStore();
        const loadingType = error.config?._loadingType;
        
        if (loadingType && loadingType !== LoadingType.NONE) {
            loaderStore.hideLoader(loadingType);
        }

        const originalRequest = error.config;

        // Prevent infinite loops
        if (originalRequest._retry) {
            return Promise.reject(error);
        }

        // Handle CSRF token expiration
        if (error.response?.status === 419 && !originalRequest._retry) {
            originalRequest._retry = true;
            
            try {
                const token = await fetchCsrfToken();
                originalRequest.headers["X-CSRF-TOKEN"] = token;
                return axios(originalRequest);
            } catch (refreshError) {
                console.error("Failed to refresh CSRF token:", refreshError);
                const authStore = useAuthStore();
                authStore.stopSessionPing();
                authStore.clearAuth();
                handleSessionTimeout();
                return Promise.reject(refreshError);
            }
        }

        // Handle unauthorized access
        if (error.response?.status === 401) {
            const authStore = useAuthStore();
            authStore.stopSessionPing();
            authStore.clearAuth();
            handleSessionTimeout();
        }

        return Promise.reject(error);
    }
);

// Create axios instance with defaults
const api = axios.create();

// Helper method to make requests with specific loading types
export const createRequest = (loadingType = LoadingType.BACKGROUND) => ({
    get: (url, config = {}) => api.get(url, { ...config, loadingType }),
    post: (url, data, config = {}) => api.post(url, data, { ...config, loadingType }),
    put: (url, data, config = {}) => api.put(url, data, { ...config, loadingType }),
    delete: (url, config = {}) => api.delete(url, { ...config, loadingType }),
    patch: (url, data, config = {}) => api.patch(url, data, { ...config, loadingType })
});

// Export different request types
export const pageRequest = createRequest(LoadingType.PAGE);
export const backgroundRequest = createRequest(LoadingType.BACKGROUND);
export const overlayRequest = createRequest(LoadingType.OVERLAY);
export const silentRequest = createRequest(LoadingType.NONE);

export default api;