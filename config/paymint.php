<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayMint Secret Key
    |--------------------------------------------------------------------------
    |
    | This is the secret key used to authenticate with the PayMint API.
    | You can find this in your PayMint Developer Dashboard.
    |
    */
    'secret_key' => env('PAYMINT_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | PayMint Base URL
    |--------------------------------------------------------------------------
    */
    'base_url' => env('PAYMINT_BASE_URL', 'https://api.paymint.africa/v1/'),
];
