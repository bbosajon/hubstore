<?php

return [
    'client_id' => env('AIRTEL_CLIENT_ID', ''),
    'client_secret' => env('AIRTEL_CLIENT_SECRET', ''),
    'merchant_id' => env('AIRTEL_MERCHANT_ID', ''),
    'service_id' => env('AIRTEL_SERVICE_ID', ''),
    'sandbox' => [
        'base_url' => 'https://sandbox-api.airtel.africa',
        'auth_url' => 'https://sandbox-auth.airtel.africa/oauth2/token',
    ],
    'production' => [
        'base_url' => 'https://api.airtel.africa',
        'auth_url' => 'https://auth.airtel.africa/oauth2/token',
    ]
];
