<?php

use Mazimez\Gigapay\Events\EmployeeClaimed;
use Mazimez\Gigapay\Events\EmployeeCreated;
use Mazimez\Gigapay\Events\EmployeeNotified;
use Mazimez\Gigapay\Events\EmployeeVerified;
use Mazimez\Gigapay\Events\InvoiceCreated;
use Mazimez\Gigapay\Events\InvoicePaid;
use Mazimez\Gigapay\Events\PayoutAccepted;
use Mazimez\Gigapay\Events\PayoutCreated;
use Mazimez\Gigapay\Events\PayoutNotified;

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


    /*
    |--------------------------------------------------------------------------
    | Gigapay events mapping
    |--------------------------------------------------------------------------
    |
    | mapping of Gigapay's different webhook events to route that will receive the webhook event
    |
    */

    'events_mapping' => [
        'Employee.created' => 'employee-created',
        'Employee.notified' => 'employee-notified',
        'Employee.claimed' => 'employee-claimed',
        'Employee.verified' => 'employee-verified',
        'Payout.created' => 'payout-created',
        'Payout.notified' => 'payout-notified',
        'Payout.accepted' => 'payout-accepted',
        'Invoice.created' => 'invoice-created',
        'Invoice.paid' => 'invoice-paid',
    ],

    /*
    |--------------------------------------------------------------------------
    | Gigapay Events
    |--------------------------------------------------------------------------
    |
    | List of Gigapay events that you can listen to by Listeners.
    |
    */

    'events_list' => [
        EmployeeClaimed::class,
        EmployeeCreated::class,
        EmployeeNotified::class,
        EmployeeVerified::class,
        InvoiceCreated::class,
        InvoicePaid::class,
        PayoutAccepted::class,
        PayoutCreated::class,
        PayoutNotified::class,
    ]
];