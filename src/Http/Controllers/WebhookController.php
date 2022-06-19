<?php


namespace  Mazimez\Gigapay\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mazimez\Gigapay\Events\EventFactory;

class WebhookController extends Controller
{
    /**
     * fire the event that given by Gigapay webhook
     * doc: https://developer.gigapay.se/#events
     *
     * @return void
     */
    public function createEvent($event, Request $request)
    {
        event(EventFactory::create($event, $request));
    }
}