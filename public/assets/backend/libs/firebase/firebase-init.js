"use strict";

class FirebaseAppManager {
    constructor(config) {
        if (!config || typeof config !== 'object') {
            throw new Error('Firebase config object is required');
        }

        this.firebaseConfig = config;
        this.app = null;
        this.messaging = null;
    }

    initialize() {
        try {
            if (firebase.apps.length === 0) {
                this.app = firebase.initializeApp(this.firebaseConfig);
                // '✅ Firebase initialized';
            } else {
                this.app = firebase.app();
                // '⚠️ Firebase already initialized';
            }

            if (firebase.messaging.isSupported && firebase.messaging.isSupported()) {
                this.messaging = firebase.messaging();
                // '📩 Firebase messaging initialized';
            }
        } catch (e) {
            console.warn('🔥 Firebase initialization failed:', e);
        }
    }

    getApp() {
        return this.app;
    }

    getMessaging() {
        return this.messaging;
    }
}

let firebaseConfigurationConfig = $('#Firebase_Configuration_Config');
const firebaseManager = new FirebaseAppManager({
    apiKey: firebaseConfigurationConfig.data('api-key'),
    authDomain: firebaseConfigurationConfig.data('auth-domain'),
    projectId: firebaseConfigurationConfig.data('project-id'),
    storageBucket: firebaseConfigurationConfig.data('storage-bucket'),
    messagingSenderId: firebaseConfigurationConfig.data('messaging-sender-id'),
    appId: firebaseConfigurationConfig.data('app-id'),
    measurementId: firebaseConfigurationConfig.data('measurement-id')
});

firebaseManager.initialize();

const app = firebaseManager.getApp();
const messaging = firebaseManager.getMessaging();


function requestNotificationPermission() {
    return Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            return true;
        } else {
            console.warn('Notification permission denied.');
            return false;
        }
    });
}

function subscribeToNotificationTopics(topics) {
    requestNotificationPermission().then(permissionGranted => {
        if (permissionGranted) {
            messaging.getToken().then(token => {
                topics.forEach(topic => {
                    subscribeTokenToBackend(token, topic);
                });
            }).catch(error => {
                console.warn('Error getting token:', error);
            });
        }
    });
}

function subscribeTokenToBackend(token, topic) {
    fetch(firebaseConfigurationConfig.data('route'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': firebaseConfigurationConfig.data('csrf-token')
        },
        body: JSON.stringify({
            token: token,
            topic: topic
        })
    }).then(response => {
        if (response.status < 200 || response.status >= 400) {
            return response.text().then(text => {
                throw new Error(`Error subscribing to topic: ${response.status} - ${text}`);
            });
        }
    }).catch(error => {
        console.warn('Subscription error:', error);
    });
}

function displayNotification(notification) {
    const options = {
        body: notification.body,
        icon: $('#Firebase_Configuration_Config').data('favicon'),
    };
    new Notification(notification.title, options);
}


messaging.onMessage(function (payload) {
    // Check if the notification is related to a specific topic
    if (payload?.data?.type?.includes('product_restock')) {
        productRestockStockLimitStatus(payload.data);
    }

    // You can also display the notification directly
    if (payload.data) {
        displayNotification(payload.data);
    }
});
