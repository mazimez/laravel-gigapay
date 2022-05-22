<?php

namespace  Mazimez\Gigapay;

use Mazimez\Gigapay\Managers\RequestManager;
use Mazimez\Gigapay\Resources\ListResource;
use Exception;

class Invoice
{
    /**
     * The Invoice's Unique identifier
     *
     * @var string
     */
    public $id;

    /**
     * The Link to pay invoice in app.
     *
     * @var string
     */
    public $app;

    /**
     * Decimal formatted string of the price.
     *
     * @var bool
     */
    public $price;

    /**
     * ISO-4217 currency code.
     *
     * @var string
     */
    public $currency;

    /**
     * Bank reference.
     *
     * @var string
     */
    public $ocr_number;

    /**
     * JSON-encoded metadata.
     *
     * @var object
     */
    public $metadata;

    /**
     * Whether the Invoice is the currently open one.
     *
     * @var bool
     */
    public $open;

    /**
     * Time at which the Invoice was paid. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $paid_at;

    /**
     * Link to download a pdf version of the Invoice.
     *
     * @var string
     */
    public $pdf;

    /**
     * Time at which the Invoice was created. Displayed as ISO 8601 string.
     *
     * @var string/json
     */
    public $created_at;

    /**
     * Create a new Invoice instance.
     *
     * @param json $json
     * @return $this
     */
    public function __construct($json)
    {
        $this->id = $json->id;
        $this->app = $json->app;
        $this->price = $json->price;
        $this->currency = $json->currency;
        $this->ocr_number = $json->ocr_number;
        $this->metadata = $json->metadata;
        $this->open = $json->open;
        $this->paid_at = $json->paid_at;
        $this->pdf = $json->pdf;
        $this->created_at = $json->created_at;

        return $this;
    }

    /**
     * get the url for invoice resource
     *
     * @return $string
     */
    static function getUrl()
    {
        return config('gigapay.server_url') . '/invoices';
    }

    /**
     * get List resource of invoice
     * doc: https://developer.gigapay.se/#list-all-invoices
     *
     * @return \Mazimez\Gigapay\Resources\ListResource
     */
    static function list()
    {
        return new ListResource(Invoice::getUrl());
    }

    /**
     * get invoice instance by it's ID,
     * doc: https://developer.gigapay.se/#retrieve-an-invoice
     *
     * @param string $invoice_id
     * @return \Mazimez\Gigapay\Invoice
     */
    static function findById($invoice_id)
    {
        $url = Invoice::getUrl() . '/' . $invoice_id;
        $request_manager = new RequestManager();
        return new Invoice(
            $request_manager->getData('GET', $url)
        );
    }

    /**
     * update the invoice's id,
     * doc: https://developer.gigapay.se/#update-an-invoice
     *
     * @param string $new_id
     * @return $this
     */
    public function updateId($new_id)
    {
        $url = Invoice::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'id' => $new_id
                ]
            ]
        );
        $this->id = $new_id;
        return $this;
    }

    /**
     * update the invoice's meta data,
     * doc: https://developer.gigapay.se/#update-an-invoice
     *
     * @param string $new_metadata
     * @return $this
     */
    public function updateMetaDate($new_metadata)
    {
        $url = Invoice::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'metadata' => $new_metadata
                ]
            ]
        );
        $this->metadata = json_decode($new_metadata);
        return $this;
    }

    /**
     * update the invoice's data with Gigapay
     * doc: https://developer.gigapay.se/#update-an-invoice
     *
     * @return $this
     */
    public function save()
    {
        $url = Invoice::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'id' => $this->id,
                    'metadata' => $this->metadata,
                ]
            ]
        );
        $this->metadata = json_decode((string)$this->metadata);
        return $this;
    }

    /**
     * delete the Invoice(only gets deleted if it's not a paid Invoice or an Invoice on credit,
     * doc: https://developer.gigapay.se/#delete-an-invoice
     *
     * @return void
     */
    public function destroy()
    {
        $url = Invoice::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'DELETE',
            $url
        );
        $this->id = null;
        $this->app = null;
        $this->price = null;
        $this->currency = null;
        $this->ocr_number = null;
        $this->metadata = null;
        $this->open = null;
        $this->paid_at = null;
        $this->pdf = null;
        $this->created_at = null;
    }

    /**
     * resend Notification(email) to Employee's mail-id,
     * doc: https://developer.gigapay.se/#resend-a-notification
     *
     * @return \Mazimez\Gigapay\Payout
     */
    public function resend()
    {
        $url = Payout::getUrl() . '/' . $this->id . '/resend';
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url
        );
        return $this->findById($this->id);
    }

    /**
     * convert the Invoice instance to json
     *
     * @return json
     */
    public function getJson()
    {
        return json_decode(
            json_encode(
                [
                    "id" => $this->id,
                    "app" => $this->app,
                    "price" => $this->price,
                    "currency" => $this->currency,
                    "ocr_number" => $this->ocr_number,
                    "metadata" => $this->metadata,
                    "open" => $this->open,
                    "paid_at" => $this->paid_at,
                    "pdf" => $this->pdf,
                    "created_at" => $this->created_at,
                ]
            )
        );;
    }
}
