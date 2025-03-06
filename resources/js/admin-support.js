import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import "./bootstrap";
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import AdminSupportChat from './components/AdminSupportChat.vue';

// Create Vuetify instance
const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light'
    }
});

// Create and mount the Vue application
const app = createApp({
    components: {
        AdminSupportChat
    }
});

app.use(vuetify);
app.mount('#admin-support-app');

// Make the CSRF token available for axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Make user ID available to Vue component
window.Laravel = {
    user: {
        id: document.querySelector('meta[name="user-id"]').getAttribute('content')
    }
};