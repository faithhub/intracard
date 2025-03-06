import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    // auth: {
    //     headers: {
    //         'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
    //     }
    // },
    enableLogging: true,
});


// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// // Enable detailed logging
// Pusher.logToConsole = true;
// Pusher.log = (msg) => {
//     console.log(`%c[Pusher]%c ${msg}`, 'color: white; font-weight: bold', 'color: white');
// };



// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true,
//     // enabledTransports: ['ws', 'wss'], // Use WebSockets
//     // encrypted: true,
//     // Include CSRF token
//     // csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
//     // Add auth endpoint if you're using private channels
//     // authEndpoint: '/broadcasting/auth'
// });

// window.Echo.private(`ticket.ffab1a55-cea7-4d5e-8f03-acf8679ef8ae`)
//     .listen('GotMessage', (e) => {
//         console.log(e);
//     });

// Global debug event listener
// if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
//     window.Echo.connector.pusher.bind_global((event, data) => {
//         if (event.includes('Message') || event.startsWith('pusher:')) {
//             console.log(`Global Echo event: ${event}`, data);
//         }
//     });
// }