importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-auth.js');

firebase.initializeApp({
    apiKey: "AIzaSyCFGqSEiWMItei_AFIUgdM53PWrvyGmjFY",
    authDomain: "",
    projectId: "drivevalley-fdb7f",
    storageBucket: "",
    messagingSenderId: "76471554747",
    appId: "1:76471554747:android:3aa5d58a094e2a036d0f9e",
    measurementId: ""
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    return self.registration.showNotification(payload.data.title, {
        body: payload.data.body || '',
        icon: payload.data.icon || ''
    });
});