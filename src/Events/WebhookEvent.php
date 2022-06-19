<?php

namespace  Mazimez\Gigapay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Mazimez\Gigapay\Employee;
use Mazimez\Gigapay\Invoice;
use Mazimez\Gigapay\Payout;

abstract class WebhookEvent
{
    use Dispatchable, SerializesModels;

    /**
     * instance of the resource related to event.
     *
     * @var Employee|Payout|Invoice
     */
    protected $resource;

    /**
     * new Event instance based on event from webhook
     *
     * @param Employee|Payout|Invoice $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * return the resource related to event
     *
     * @return Employee|Payout|Invoice $resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}
