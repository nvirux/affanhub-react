<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you are free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => env('HASH_DRIVER', 'bcrypt'),

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for when passwords are
    | hashed using the Bcrypt algorithm. This will allow you to control
    | the amount of time it takes to hash the given password value.
    |
    | Setting verify to false prevents RuntimeExceptions when checking accounts
    | with legacy, empty, or non-bcrypt password values.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => env('HASH_VERIFY', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for when passwords are
    | hashed using the Argon algorithm. These options allow you to control
    | the amount of time and memory it takes to hash the value.
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => env('HASH_VERIFY', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rehash On Login
    |--------------------------------------------------------------------------
    |
    | Here you may specify if passwords should be automatically rehashed
    | after authentication. This helps keep passwords secure even as hash
    | configurations and algorithms change over time.
    |
    */

    'rehash_on_login' => true,

];
