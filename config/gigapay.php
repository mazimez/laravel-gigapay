<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gigapay Server URL
    |--------------------------------------------------------------------------
    |
    | Gigapay generally has 2 servers, 1 for demo and 1 for production
    | both server has different url, by default it will use the demo server URL
    | but you can change it from your project's .env file.
    | also currently Gigapay's APIs are at version 2, so it will use version 2
    |
    */

    'server_url' => env('GIGAPAY_SERVER_URL', 'https://api.demo.gigapay.se/v2'),

    /*
    |--------------------------------------------------------------------------
    | Gigapay Token
    |--------------------------------------------------------------------------
    |
    | Gigapay uses this token to identify and authenticate requests,
    | you can get this Token from your Gigapay account
    | Note that Tokens are different for demo server and production server
    | define it in your .env.
    |
    */

    'token' => env('GIGAPAY_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Gigapay integration id
    |--------------------------------------------------------------------------
    |
    | Integrations are like Transactions, it's a parent object of all other objects in the Gigapay API
    | whenever you do any action in Gigapay, it will be in integration and it has a integration id
    | Gigapay's API need integration id, you can create integration with Gigapay APIs from https://developer.gigapay.se/#create-an-integration
    |
    */

    'integration_id' => env('GIGAPAY_INTEGRATION_ID'),

    /*
    |--------------------------------------------------------------------------
    | Gigapay lang
    |--------------------------------------------------------------------------
    |
    | Language for the API responses. default will be english(en)
    |
    */

    'lang' => env('GIGAPAY_LANG', 'en'),

];