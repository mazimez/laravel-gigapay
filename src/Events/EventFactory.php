<?php


namespace  Mazimez\Gigapay\Events;

use Illuminate\Http\Request;
use Mazimez\Gigapay\Employee;
use Mazimez\Gigapay\Invoice;
use Mazimez\Gigapay\Payout;

class EventFactory
{
    /**
     * create the Event based on the webhook event
     * doc: https://developer.gigapay.se/#events
     *
     * @param string $event_name
     * @return Request $payload
     */
    public static function create($event_name, Request $payload)
    {
        switch ($event_name) {
            case config('gigapay.events_mapping')['Employee.created']:
                return new EmployeeCreated(new Employee($payload));
                break;
            case config('gigapay.events_mapping')['Employee.notified']:
                return new EmployeeNotified(new Employee($payload));
                break;
            case config('gigapay.events_mapping')['Employee.claimed']:
                return new EmployeeClaimed(new Employee($payload));
                break;
            case config('gigapay.events_mapping')['Employee.verified']:
                return new EmployeeVerified(new Employee($payload));
                break;
            case config('gigapay.events_mapping')['Payout.created']:
                return new PayoutCreated(new Payout($payload));
                break;
            case config('gigapay.events_mapping')['Payout.notified']:
                return new PayoutNotified(new Payout($payload));
                break;
            case config('gigapay.events_mapping')['Payout.accepted']:
                return new PayoutAccepted(new Payout($payload));
                break;
            case config('gigapay.events_mapping')['Invoice.created']:
                return new InvoiceCreated(new Invoice($payload));
                break;
            case config('gigapay.events_mapping')['Invoice.paid']:
                return new InvoicePaid(new Invoice($payload));
                break;
        }
    }
}