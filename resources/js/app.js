import "vuetify/styles/main.sass";
import "./bootstrap";
import { createPinia } from "pinia";
import { createApp } from "vue";
import router from "@/router"; // Vue Router
import App from "../views/App.vue"; // Root Vue component
import Toast, { POSITION } from "vue-toastification";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "vue-toastification/dist/index.css";
import "@/scss/style.scss"; // Custom SCSS styles
import { createVuetify } from "vuetify";
import "@mdi/font/css/materialdesignicons.css"; // Import MDI
import "vuetify/styles"; // Vuetify styles
import axios from "@/utils/axios"; // Axios setup
import { useAuthStore } from "@/stores/authStore"; // Adjust the path if necessary
import {
    startSessionTimers,
    resetSessionTimers,
    handleSessionTimeout,
} from "@/utils/sessionManager";

// import * as $ from 'jquery';

// window.$ = window.jQuery = $; // Attach jQuery to the global scope

// import 'jquery-confirm/dist/jquery-confirm.min.js'; // Import the jQuery Confirm plugin
// import 'jquery-confirm/dist/jquery-confirm.min.css'; // Import the CSS for jQuery Confirm

// // Attach jQuery Confirm to the global `$` object
// window.$ = window.jQuery = $;
// $['confirm'] = $.confirm;
// Start session timers
startSessionTimers();

// Consolidated session management for user activity
let activityDebounceTimer;

function handleUserActivity() {
    clearTimeout(activityDebounceTimer);
    activityDebounceTimer = setTimeout(() => {
        resetSessionTimers(); // Reset session timers when user is active
    }, 500); // Debounce activity detection by 500ms
}

document.addEventListener("mousemove", handleUserActivity);
document.addEventListener("keydown", handleUserActivity);

// Periodic session validation (every 5 minutes)
const sessionPingInterval = 300000; // 5 minutes
setInterval(async () => {
    const authStore = useAuthStore();

    if (!authStore.isAuthenticated) {
        // Skip session ping if the user is not authenticated
        return;
    }

    try {
        const response = await axios.get("/api/ping");
        if (response.data.status === "success") {
            console.log("Session is active.");
        }
    } catch (error) {
        if (error.response && error.response.status === 401) {
            console.warn(
                "Session expired or unauthorized. Redirecting to login."
            );
            handleSessionTimeout(); // Handle session timeout if unauthorized
        }
    }
}, sessionPingInterval);

// Vuetify setup
// const vuetify = createVuetify({
//     icons: {
//       defaultSet: 'mdi'
//     }
//   });

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: "mdi",
    },
    theme: {
        defaultTheme: "light",
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: "#6c3baa",
                    secondary: "#1b660a",
                    accent: "#65b338", 
                    success: "#3e8e19",
                    "primary-light": "#e0d4ec",
                    "primary-dark": "#4a2875",
                    "secondary-light": "#2d8c13",
                    "secondary-dark": "#134807",
                    error: "#dc3545",
                    warning: "#ffc107",
                    info: "#0dcaf0",
                    background: "#f8f9fa"
                 },
            },
        },
    },
});

// Vue Toast options
const toastOptions = {
    position: POSITION.TOP_RIGHT,
    timeout: 5000,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    hideProgressBar: false,
    closeButton: "button",
};

// Initialize Vue application
const app = createApp(App);
const pinia = createPinia();

app.use(vuetify);
app.use(router);
app.use(Toast, toastOptions);
app.use(pinia);

// Mount the application
app.mount("#app");
