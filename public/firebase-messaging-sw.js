// public/firebase-messaging-sw.js

// Scripts for firebase and firebase messaging
// Make sure you are using versions compatible with your main Firebase SDK version
importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js'); // Or newer
importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js'); // Or newer

// IMPORTANT: Replace with your project's actual Firebase configuration details
const firebaseConfig = {
    apiKey: "AIzaSyD9v9J5Uj2yKf8g3rMMYjEvhLZvFaVHTzw",
    authDomain: "metaerp-6fc40.firebaseapp.com",
    projectId: "metaerp-6fc40",
    storageBucket: "metaerp-6fc40.firebasestorage.app",
    messagingSenderId: "1003520574563",
    appId: "1:1003520574563:web:609b670dd3d317527ca456",
     measurementId: "G-S9KRRC7VXG" // Optional
};

// Initialize Firebase
if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
} else {
    firebase.app(); // if already initialized, use that one
}


// Retrieve an instance of Firebase Messaging so that it can handle background messages.
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);

    // Customize notification here
    const notificationTitle = payload.notification.title || 'New Notification';
    const notificationOptions = {
        body: payload.notification.body || 'You have a new message.',
        icon: payload.notification.icon || '/images/icons/icon-192x192.png', // Default icon
        data: payload.data // This allows you to pass custom data and handle clicks
    };

    // self.registration is a ServiceWorkerGlobalScope property
    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Optional: Add event listener for notification click
self.addEventListener('notificationclick', function(event) {
    console.log('[firebase-messaging-sw.js] Notification click Received.', event.notification);
    event.notification.close();

    // Example: Open a specific URL or focus the app window
    // You can get URL from event.notification.data set in onBackgroundMessage or from payload
    const clickAction = event.notification.data && event.notification.data.click_action
        ? event.notification.data.click_action
        : '/'; // Default URL

    event.waitUntil(
        clients.matchAll({ type: "window", includeUncontrolled: true }).then(function(clientList) {
            // If a window for your PWA is already open, focus it
            for (var i = 0; i < clientList.length; i++) {
                var client = clientList[i];
                // Adjust the URL check if your PWA can have multiple base URLs or paths
                if (client.url === clickAction && 'focus' in client) {
                    return client.focus();
                }
            }
            // If no window is open, open a new one
            if (clients.openWindow) {
                return clients.openWindow(clickAction);
            }
        })
    );
});

// Optional: Basic caching strategy (Cache First for static assets)
// const CACHE_NAME = 'metaerp-cache-v1';
// const urlsToCache = [
//   '/',
//   '/css/app.css', // Adjust to your actual asset paths
//   '/js/app.js',   // Adjust to your actual asset paths
//   '/images/icons/icon-192x192.png',
//   // Add other important static assets
// ];

// self.addEventListener('install', function(event) {
//   event.waitUntil(
//     caches.open(CACHE_NAME)
//       .then(function(cache) {
//         console.log('Opened cache');
//         return cache.addAll(urlsToCache);
//       })
//   );
// });

// self.addEventListener('fetch', function(event) {
//   event.respondWith(
//     caches.match(event.request)
//       .then(function(response) {
//         if (response) {
//           return response; // Serve from cache
//         }
//         return fetch(event.request); // Fetch from network
//       }
//     )
//   );
// });
