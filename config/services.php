<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'rajaongkir' => [
        'api_key' => env('RAJAONGKIR_API_KEY', 'cSz263cs82fb893c1a357f90EO9W5dpq'),
        'delivery_api_key' => env('RAJAONGKIR_DELIVERY_API_KEY', '9v5gi2zZ82fb893c1a357f90x1MTcnqw'),
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
        'origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY_ID', '250'), // Malang
        'origin_province_id' => env('RAJAONGKIR_ORIGIN_PROVINCE_ID', '11'), // Jawa Timur
        'timeout' => env('RAJAONGKIR_TIMEOUT', 30),
    ],

    'auto_confirmation' => [
        'enabled' => env('AUTO_CONFIRMATION_ENABLED', true),
        'days' => env('AUTO_CONFIRMATION_DAYS', 2),
        'time' => env('AUTO_CONFIRMATION_TIME', '09:00'),
        'send_email' => env('SEND_CONFIRMATION_EMAIL', true),
    ],

    'shipping' => [
        'default_weight' => env('DEFAULT_SHIPPING_WEIGHT', 1000),
        'free_minimum' => env('FREE_SHIPPING_MINIMUM', 100000),
        'insurance_rate' => env('SHIPPING_INSURANCE_RATE', 0.002),
    ],

];
