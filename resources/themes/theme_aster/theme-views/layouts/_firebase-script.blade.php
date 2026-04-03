@php($fcmCredentials = getWebConfig('fcm_credentials'))
<span id="Firebase_Configuration_Config" data-api-key="{{ $fcmCredentials['apiKey'] ?? '' }}"
      data-auth-domain="{{ $fcmCredentials['authDomain'] ?? '' }}"
      data-project-id="{{ $fcmCredentials['projectId'] ?? '' }}"
      data-storage-bucket="{{ $fcmCredentials['storageBucket'] ?? '' }}"
      data-messaging-sender-id="{{ $fcmCredentials['messagingSenderId'] ?? '' }}"
      data-app-id="{{ $fcmCredentials['appId'] ?? '' }}"
      data-measurement-id="{{ $fcmCredentials['measurementId'] ?? '' }}"
      data-csrf-token="{{ csrf_token() }}"
      data-route="{{ route('system.subscribeToTopic') }}"
      data-recaptcha-store="{{ route('g-recaptcha-response-store') }}"
      data-favicon="{{ $web_config['fav_icon']['path'] }}"
      data-firebase-service-worker-file="{{ dynamicAsset(path: 'firebase-messaging-sw.js') }}"
      data-firebase-service-worker-scope="{{ dynamicAsset(path: 'firebase-cloud-messaging-push-scope') }}"
>
</span>

@if(isset($fcmCredentials['apiKey']) && !empty($fcmCredentials['apiKey']))
    <script src="{{ dynamicAsset(path: 'public/assets/backend/libs/firebase/firebase.min.js') }}"></script>
    <script src="{{ 'https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js' }}"></script>
    <script src="{{ 'https://www.gstatic.com/firebasejs/8.3.2/firebase-auth.js' }}"></script>
    <script src="{{ 'https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js' }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/backend/libs/firebase/firebase-init.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/backend/libs/firebase/firebase-auth.js') }}"></script>

    <script>
        try {
            // List of topics to subscribe to
            const topics = {!! json_encode(getFCMTopicListToSubscribe()) !!};
            subscribeToNotificationTopics(topics);
        } catch (e) {
            console.warn(e);
        }
    </script>
@endif
