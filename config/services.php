<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
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

    'vtulab' => [
        'base_url' => env('VTULAB_BASE_URL', 'https://vtulab.com/api/v1'),
        'api_key' => env('VTULAB_API_KEY'),
        'is_sandbox' => env('VTULAB_IS_SANDBOX', true),
    ],

    'github_app_builder' => [
        'token' => env('GITHUB_APP_TOKEN'),
        'repo' => env('GITHUB_APP_REPO', 'nvirux/affanhub-mobile-template'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'https://merchant.affanhub.com/auth/google/callback'),
    ],

    'idcore' => [
        'base_url' => env('IDCORE_BASE_URL', 'https://api.idcore.africa/v1'),
        'api_key' => env('IDCORE_API_KEY'),
    ],

    'cloudflare' => [
        'client_id' => env('CLOUDFLARE_OAUTH_CLIENT_ID'),
        'client_secret' => env('CLOUDFLARE_OAUTH_CLIENT_SECRET'),
        'redirect_uri' => env('CLOUDFLARE_OAUTH_REDIRECT_URI', 'https://merchant.affanhub.com/merchant/cloudflare/callback'),
        'fallback_cname' => env('CLOUDFLARE_FALLBACK_CNAME', 'custom.affanhub.com'),
        'scopes' => env('CLOUDFLARE_OAUTH_SCOPES', 'dns.read dns.write zone.read zone.write'),
    ],
];
