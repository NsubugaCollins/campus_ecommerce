<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MTN Mobile Money (MoMo) – Collections API
    |--------------------------------------------------------------------------
    |
    | Sandbox base URL  : https://sandbox.momodeveloper.mtn.com
    | Production base URL: https://proxy.momoapi.mtn.com
    |
    */

    'base_url'         => env('MTN_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
    'subscription_key' => env('MTN_SUBSCRIPTION_KEY', '90b58834c35c4b8a9f3a104d59a7cc73'),
    'api_user'         => env('MTN_API_USER', '89280238-ba23-42ed-bb7c-e37bce61c689'),
    'api_key'          => env('MTN_API_KEY', '532e3ce3938b4218800319a293a160e2'),

    // The target environment header required by MTN API.
    // Use 'sandbox' when testing, 'mtnuganda' for production Uganda.
    'environment'      => env('MTN_ENVIRONMENT', 'sandbox'),

    // Currency for all requests (ISO 4217)
    'currency'         => env('MTN_CURRENCY', 'UGX'),
];
