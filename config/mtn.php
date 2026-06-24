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
    'subscription_key' => env('MTN_SUBSCRIPTION_KEY'),
    'api_user'         => env('MTN_API_USER'),
    'api_key'          => env('MTN_API_KEY'),

    // The target environment header required by MTN API.
    // Use 'sandbox' when testing, 'mtnuganda' for production Uganda.
    'environment'      => env('MTN_ENVIRONMENT', 'sandbox'),

    // Currency for all requests (ISO 4217)
    'currency'         => env('MTN_CURRENCY', 'UGX'),
];
