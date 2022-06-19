<?php

use Illuminate\Support\Facades\Route;
use Mazimez\Gigapay\Http\Controllers\WebhookController;

Route::post(
    '/gigapay/webhooks/{event}',
    [WebhookController::class, 'createEvent']
)->name('gigapay.webhooks');