import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // Use the default export

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // Your Pusher App Key
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Your Pusher App Cluster
    forceTLS: true, // Use TLS (HTTPS)
});
