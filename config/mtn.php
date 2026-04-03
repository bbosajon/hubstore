<?php

return [
    'subscription_key' => env('MTN_SUBSCRIPTION_KEY', ''),
    'api_user' => env('MTN_API_USER', ''),
    'api_key' => env('MTN_API_KEY', ''),
    'callback_host' => env('MTN_CALLBACK_HOST', ''),
    'primary_key' => env('MTN_PRIMARY_KEY', ''),
    'sandbox' => [
        'base_url' => 'https://sandbox-api.mtn.com',
        'target_environment' => 'sandbox',
    ],
    'production' => [
        'base_url' => 'https://api.mtn.com',
        'target_environment' => 'production',
    ]
];
