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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'mappls' => [
        'key' => env('MAPPLS_API_KEY'),
    ],

    'locationiq' => [
        'key' => env('LOCATIONIQ_API_KEY'),
    ],

    'geoapify' => [
        'key' => env('GEOAPIFY_API_KEY'),
    ],

    'openstreetmap' => [
        'overpass_url' => env('OSM_OVERPASS_URL', 'https://overpass-api.de/api/interpreter'),
        'nominatim_url' => env('OSM_NOMINATIM_URL', 'https://nominatim.openstreetmap.org'),
    ],

    // Google Gemini — used for AI Chat Assistant and future AI features.
    // Free tier: https://aistudio.google.com/apikey (no credit card needed).
    'gemini' => [
        'key'   => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
    ],

    // Meta (Facebook/Instagram) — used for Page connection (OAuth) and
    // Lead Ads webhook capture. Requires a Meta for Developers app with
    // Business verification + App Review for the 'leads_retrieval' and
    // 'pages_manage_ads' permissions before this works in production.
    'facebook' => [
        'app_id'        => env('FACEBOOK_APP_ID'),
        'app_secret'    => env('FACEBOOK_APP_SECRET'),
        'verify_token'  => env('FACEBOOK_WEBHOOK_VERIFY_TOKEN'),
        'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v21.0'),
    ],

];
