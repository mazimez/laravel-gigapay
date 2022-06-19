<?php

namespace  Mazimez\Gigapay;

use Mazimez\Gigapay\Managers\RequestManager;
use Mazimez\Gigapay\Resources\ListResource;
use Exception;

class Webhook
{
    /**
     * The Webhook's Unique identifier
     *
     * @var string
     */
    public $id;

    /**
     * URL to which the notifications are posted.
     *
     * @var string
     */
    public $url;

    /**
     * List of events to subscribe to.
     *
     * @var array
     */
    public $events;

    /**
     * Secret key used to sign the Webhook notifications.
     *
     * @var string
     */
    public $secret_key;

    /**
     * JSON-encoded metadata.
     *
     * @var object
     */
    public $metadata;

    /**
     * Create a new Invoice instance.
     *
     * @param json $json
     * @return $this
     */
    public function __construct($json)
    {
        $this->id = $json->id ?? null;
        $this->url = $json->url ?? null;
        $this->events = $json->events ?? null;
        $this->secret_key = $json->secret_key ?? null;
        $this->metadata = $json->metadata ?? null;

        return $this;
    }

    /**
     * get the url for webhook resource
     *
     * @return $string
     */
    static function getUrl()
    {
        return config('gigapay.server_url') . '/webhooks';
    }


    /**
     * create the new Webhook with API
     * doc: https://developer.gigapay.se/#register-a-webhook
     *
     * @param string $url
     * @param string $event
     * @param string $secret_key
     * @param object $metadata
     * @param string $id
     * @return \Mazimez\Gigapay\Webhook
     */
    static function create(
        $webhook_url,
        $event,
        $secret_key = null,
        $metadata = null,
        $id = null
    ) {
        $url = Webhook::getUrl();
        $params = [];
        if ($id) {
            $params = array_merge($params, ['id' => $id]);
        }
        if ($url) {
            $params = array_merge($params, ['url' => $webhook_url]);
        }
        if ($event) {
            $params = array_merge($params, ['events' => $event]);
        }
        if ($secret_key) {
            $params = array_merge($params, ['secret_key' => $secret_key]);
        }
        if ($metadata) {
            $params = array_merge($params, ['metadata' => $metadata]);
        }
        $request_manager = new RequestManager();
        return new Webhook(
            $request_manager->getData(
                'POST',
                $url,
                [
                    'form_params' => $params
                ]
            )
        );
    }

    /**
     * create the new Webhook with API, by giving Array
     * doc: https://developer.gigapay.se/#register-a-webhook
     *
     * @param array $webhook
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createByArray(array $webhook)
    {
        $url = Webhook::getUrl();
        if (!isset($webhook['url']) || !isset($webhook['events'])) {
            throw new Exception('Url and events are required');
        }
        $params = [];
        if (isset($webhook['id'])) {
            $params = array_merge($params, ['id' => $webhook['id']]);
        }
        if (isset($webhook['url'])) {
            $params = array_merge($params, ['url' => $webhook['url']]);
        }
        if (isset($webhook['events'])) {
            $params = array_merge($params, ['events' => $webhook['events']]);
        }
        if (isset($webhook['secret_key'])) {
            $params = array_merge($params, ['secret_key' => $webhook['secret_key']]);
        }
        if (isset($webhook['metadata'])) {
            $params = array_merge($params, ['metadata' => $webhook['metadata']]);
        }
        $request_manager = new RequestManager();
        return new Webhook(
            $request_manager->getData(
                'POST',
                $url,
                [
                    'form_params' => $params
                ]
            )
        );
    }


    /**
     * get List resource of Webhook
     * doc: https://developer.gigapay.se/#list-all-registered-webhooks
     *
     * @return \Mazimez\Gigapay\Resources\ListResource
     */
    static function list()
    {
        return new ListResource(Webhook::getUrl());
    }

    /**
     * get webhook instance by it's ID,
     * doc: https://developer.gigapay.se/#retrieve-a-webhook
     *
     * @param string $webhook_id
     * @return \Mazimez\Gigapay\Webhook
     */
    static function findById($webhook_id)
    {
        $url = Webhook::getUrl() . '/' . $webhook_id;
        $request_manager = new RequestManager();
        return new Webhook(
            $request_manager->getData('GET', $url)
        );
    }

    /**
     * update the webhook's id,
     * doc: https://developer.gigapay.se/#update-a-webhook
     *
     * @param string $new_id
     * @return $this
     */
    public function updateId($new_id)
    {
        $url = Webhook::getUrl() . '/' . $this->id;
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
     * update the webhook's URL,
     * doc: https://developer.gigapay.se/#update-a-webhook
     *
     * @param string $new_url
     * @return $this
     */
    public function updateUrl($new_url)
    {
        $url = Webhook::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'url' => $new_url
                ]
            ]
        );
        $this->url = $new_url;
        return $this;
    }

    /**
     * update the webhook's EVENT,
     * doc: https://developer.gigapay.se/#update-a-webhook
     *
     * @param string $new_event
     * @return $this
     */
    public function updateEvent($new_event)
    {
        $url = Webhook::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'events' => $new_event
                ]
            ]
        );
        $this->events = [$new_event];
        return $this;
    }

    /**
     * update the webhook's meta data,
     * doc: https://developer.gigapay.se/#update-a-webhook
     *
     * @param string $new_metadata
     * @return $this
     */
    public function updateMetaDate($new_metadata)
    {
        $url = Webhook::getUrl() . '/' . $this->id;
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
     * update the webhook's secret_key,
     * doc: https://developer.gigapay.se/#update-a-webhook
     *
     * @param string $new_secret_key
     * @return $this
     */
    public function updateSecretKey($new_secret_key)
    {
        $url = Webhook::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'secret_key' => $new_secret_key
                ]
            ]
        );
        $this->secret_key = $new_secret_key;
        return $this;
    }


    /**
     * update the webhook's data with Gigapay
     * doc: https://developer.gigapay.se/#update-a-webhook
     *
     * @return $this
     */
    public function save()
    {
        $url = Webhook::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'PATCH',
            $url,
            [
                'form_params' => [
                    'id' => $this->id,
                    'url' => $this->url,
                    'events' => $this->events,
                    'metadata' => $this->metadata,
                    'secret_key' => $this->secret_key,
                ]
            ]
        );
        $this->metadata = json_decode((string)$this->metadata);
        $this->events = [$this->events];
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
        $url = Webhook::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'DELETE',
            $url
        );
        $this->id = null;
        $this->url = null;
        $this->events = null;
        $this->metadata = null;
        $this->secret_key = null;
    }

    /**
     * create webhook for Employee created event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createEmployeeCreatedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Employee.created']]), 'Employee.created');
    }

    /**
     * create webhook for Employee notified event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createEmployeeNotifiedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Employee.notified']]), 'Employee.notified');
    }

    /**
     * create webhook for Employee claimed event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createEmployeeClaimedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Employee.claimed']]), 'Employee.claimed');
    }

    /**
     * create webhook for Employee verified event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createEmployeeVerifiedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Employee.verified']]), 'Employee.verified');
    }

    /**
     * create webhook for Payout created event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createPayoutCreatedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Payout.created']]), 'Payout.created');
    }

    /**
     * create webhook for Payout notified event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createPayoutNotifiedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Payout.notified']]), 'Payout.notified');
    }

    /**
     * create webhook for Payout accepted event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createPayoutAcceptedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Payout.accepted']]), 'Payout.accepted');
    }

    /**
     * create webhook for Invoice created event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createInvoiceCreatedWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Invoice.created']]), 'Invoice.created');
    }

    /**
     * create webhook for Invoice paid event
     *
     * @return \Mazimez\Gigapay\Webhook
     */
    static function createInvoicePaidWebhook()
    {
        return Webhook::create(route('gigapay.webhooks', ['event' => config('gigapay.events_mapping')['Invoice.paid']]), 'Invoice.paid');
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
                    "url" => $this->url,
                    "events" => $this->events,
                    "secret_key" => $this->secret_key,
                    "metadata" => $this->metadata,
                ]
            )
        );
    }
}
