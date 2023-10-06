<?php

namespace  Mazimez\Gigapay;

use Mazimez\Gigapay\Managers\RequestManager;
use Mazimez\Gigapay\Resources\ListResource;
use Exception;

class Payout
{
    /**
     * The Payout's Unique identifier
     *
     * @var string
     */
    public $id;

    /**
     * The Payout's Decimal formatted string of the gross salary amount.
     *
     * @var string
     */
    public $amount;

    /**
     * The Payout's Decimal formatted string of the invoiced amount.
     *
     * @var string
     */
    public $invoiced_amount;

    /**
     * The Payout's Decimal formatted string of the total salary cost.
     *
     * @var string
     */
    public $cost;

    /**
     * ISO-3166 country code where task was performed.
     *
     * @var string
     */
    public $country;

    /**
     * ISO-4217 currency code.
     *
     * @var json
     */
    public $currency;

    /**
     * String describing the work done, displayed to the recipient. Max 255 characters.
     *
     * @var string
     */
    public $description;

    /**
     * Controls whether to present the payroll taxes and Gigapay's fee on the payslip
     *
     * @var bool
     */
    public $full_salary_specification;

    /**
     * Employee's ID or Employee json object who is related to this payout
     *
     * @var string/object
     */
    public $employee;

    /**
     * Invoice's ID or invoice json object who is related to this payout
     *
     * @var string/object
     */
    public $invoice;

    /**
     * metadata of payout that's related to any other system
     *
     * @var object
     */
    public $metadata;

    /**
     * The time at which the gig/event/work will start. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $start_at;

    /**
     * The time at which the gig/event/work will end. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $end_at;

    /**
     * The time at which the Payout was created at. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $created_at;

    /**
     * The time at which the Employee was notified of the Payout. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $notified_at;

    /**
     * The time at which the Employee accepted the Payout. Displayed as ISO 8601 string.
     *
     * @var string
     */
    public $accepted_at;

    /**
     * Create a new Payout instance.
     *
     * @param object $json
     * @return $this
     */
    public function __construct($json)
    {
        $this->id = $json->id ?? null;
        $this->amount = $json->amount ?? null;
        $this->invoiced_amount = $json->invoiced_amount ?? null;
        $this->cost = $json->cost ?? null;
        $this->country = $json->country ?? null;
        $this->currency = $json->currency ?? null;
        $this->description = $json->description ?? null;
        $this->full_salary_specification = $json->full_salary_specification ?? null;
        $this->employee = $json->employee ?? null;
        $this->invoice = $json->invoice ?? null;
        $this->metadata = $json->metadata ?? null;
        $this->start_at = $json->start_at ?? null;
        $this->end_at = $json->end_at ?? null;
        $this->created_at = $json->created_at ?? null;
        $this->notified_at = $json->notified_at ?? null;
        $this->accepted_at = $json->accepted_at ?? null;

        return $this;
    }

    /**
     * get the url for payout resource
     *
     * @return $string
     */
    static function getUrl()
    {
        return config('gigapay.server_url') . '/payouts';
    }

    /**
     * get the url for payout resource
     * for inline employees
     *
     * @return $string
     */
    static function getInlineUrl()
    {
        return config('gigapay.server_url') . '/payouts/?expand=employee';
    }

    /**
     * create the new Payout with API
     * doc: https://developer.gigapay.se/#register-a-payout
     *
     * @param string $employee
     * @param string $description
     * @param string $amount
     * @param string $cost
     * @param string $invoiced_amount
     * @param string $currency
     * @param object $metadata
     * @param string $start_at
     * @param string $end_at
     * @param string $id
     * @return \Mazimez\Gigapay\Payout
     */
    static function create(
        $employee,
        $description,
        $amount = null,
        $cost = null,
        $invoiced_amount = null,
        $currency = null,
        $metadata = null,
        $start_at = null,
        $end_at = null,
        $id = null
    ) {
        $url = Payout::getUrl();
        if (!$amount && !$cost && !$invoiced_amount) {
            throw new Exception('Either amount, cost or invoiced_amount is required.');
        }
        $params = [];
        if ($id) {
            $params = array_merge($params, ['id' => $id]);
        }
        if ($amount) {
            $params = array_merge($params, ['amount' => $amount]);
        }
        if ($cost) {
            $params = array_merge($params, ['cost' => $cost]);
        }
        if ($currency) {
            $params = array_merge($params, ['currency' => $currency]);
        }
        if ($description) {
            $params = array_merge($params, ['description' => $description]);
        }
        if ($employee) {
            $params = array_merge($params, ['employee' => $employee]);
        }
        if ($invoiced_amount) {
            $params = array_merge($params, ['invoiced_amount' => $invoiced_amount]);
        }
        if ($metadata) {
            $params = array_merge($params, ['metadata' => $metadata]);
        }
        if ($start_at) {
            $params = array_merge($params, ['start_at' => $start_at]);
        }
        if ($end_at) {
            $params = array_merge($params, ['end_at' => $end_at]);
        }
        $request_manager = new RequestManager();
        return new Payout(
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
     * create a new Payout with an Inline employee
     * doc: https://developer.gigapay.se/#register-a-payout-with-an-inline-employee
     *
     * @param string $id
     * @param string $currency
     * @param array $employee
     * @param string $description
     * @param string $invoiced_amount
     * @param object $metadata
     * @return \Mazimez\Gigapay\Payout
     * @throws Exceptions\GigapayException
     */

    static function createInline(
        $id,
        $currency,
        $description,
        $employee,
        $invoiced_amount,
        $metadata = null
    ) {
        $url = Payout::getInlineUrl();
        if (!$invoiced_amount) {
            throw new Exception('Invoiced_amount is required.');
        }
        $params = [];
        if ($id) {
            $params = array_merge($params, ['id' => $id]);
        }
        if ($currency) {
            $params = array_merge($params, ['currency' => $currency]);
        }
        if ($description) {
            $params = array_merge($params, ['description' => $description]);
        }
        if ($employee) {
            $params = array_merge($params, ['employee' => $employee]);
        }
        if ($invoiced_amount) {
            $params = array_merge($params, ['invoiced_amount' => $invoiced_amount]);
        }
        if ($metadata) {
            $params = array_merge($params, ['metadata' => $metadata]);
        }
        $request_manager = new RequestManager();
        return new Payout(
            $request_manager->getData(
                'POST',
                $url,
                [
                    "json" => $params
                ]
            )
        );
    }

    /**
     * create the new Payout with API, by giving Array,
     * doc: https://developer.gigapay.se/#register-a-payout
     *
     * @param string $payout
     * @return \Mazimez\Gigapay\Payout
     */
    static function createByArray(array $payout)
    {
        $url = Payout::getUrl();
        if (!isset($payout['amount']) && !isset($payout['cost']) && !isset($payout['invoiced_amount'])) {
            throw new Exception('Either amount, cost or invoiced_amount is required.');
        }
        $params = [];
        if (isset($payout['id'])) {
            $params = array_merge($params, ['id' => $payout['id']]);
        }
        if (isset($payout['amount'])) {
            $params = array_merge($params, ['amount' => $payout['amount']]);
        }
        if (isset($payout['cost'])) {
            $params = array_merge($params, ['cost' => $payout['cost']]);
        }
        if (isset($payout['currency'])) {
            $params = array_merge($params, ['currency' => $payout['currency']]);
        }
        if (!isset($payout['description'])) {
            throw new Exception('description is required');
        } else {
            $params = array_merge($params, ['description' => $payout['description']]);
        }
        if (!isset($payout['employee'])) {
            throw new Exception('employee is required');
        } else {
            $params = array_merge($params, ['employee' => $payout['employee']]);
        }
        if (isset($payout['invoiced_amount'])) {
            $params = array_merge($params, ['invoiced_amount' => $payout['invoiced_amount']]);
        }
        if (isset($payout['metadata'])) {
            $params = array_merge($params, ['metadata' => $payout['metadata']]);
        }
        if (isset($payout['start_at'])) {
            $params = array_merge($params, ['start_at' => $payout['start_at']]);
        }
        if (isset($payout['end_at'])) {
            $params = array_merge($params, ['end_at' => $payout['end_at']]);
        }
        $request_manager = new RequestManager();
        return new Payout(
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
     * create multiple Payouts with API
     * doc: https://developer.gigapay.se/#register-multiple-payouts
     *
     * @param array $payouts
     * @return array of \Mazimez\Gigapay\Payout
     */
    static function createMultiple(array $payouts)
    {
        $url = Payout::getUrl();
        foreach ($payouts as $payout) {
            if (!isset($payout['employee'])) {
                throw new Exception('employee field is required.');
            }
            if (!isset($payout['description'])) {
                throw new Exception('description field is required.');
            }
            if (
                !isset($payout['amount']) &&
                !isset($payout['cost']) &&
                !isset($payout['invoiced_amount'])
            ) {
                throw new Exception('Either amount, cost or invoiced_amount is required.');
            }
        }
        $request_manager = new RequestManager();
        $payout_jsons =  $request_manager->getData(
            'POST',
            $url,
            [
                'json' => $payouts
            ]
        );
        $payout_instances = [];
        foreach ($payout_jsons as $payout_json) {
            array_push($payout_instances, new Payout($payout_json));
        }
        return $payout_instances;
    }

    /**
     * get List resource of payout
     * doc: https://developer.gigapay.se/#list-all-payouts
     *
     * @return \Mazimez\Gigapay\Resources\ListResource
     */
    static function list()
    {
        return new ListResource(Payout::getUrl());
    }

    /**
     * get payout instance by it's ID,
     * doc: https://developer.gigapay.se/#retrieve-a-payout
     *
     * @param string $payout_id
     * @return \Mazimez\Gigapay\Payout
     */
    static function findById($employee_id)
    {
        $url = Payout::getUrl() . '/' . $employee_id;
        $request_manager = new RequestManager();
        return new Payout(
            $request_manager->getData('GET', $url)
        );
    }

    /**
     * delete the Payout(only gets deleted if payout does not belong to a paid Invoice or an Invoice on credit,
     * doc: https://developer.gigapay.se/#delete-a-payout
     *
     * @return void
     */
    public function destroy()
    {
        $url = Payout::getUrl() . '/' . $this->id;
        $request_manager = new RequestManager();
        $request_manager->getData(
            'DELETE',
            $url
        );
        $this->id = null;
        $this->amount = null;
        $this->invoiced_amount = null;
        $this->cost = null;
        $this->country = null;
        $this->currency = null;
        $this->description = null;
        $this->full_salary_specification = null;
        $this->employee = null;
        $this->invoice = null;
        $this->metadata = null;
        $this->start_at = null;
        $this->end_at = null;
        $this->created_at = null;
        $this->notified_at = null;
        $this->accepted_at = null;
    }

    /**
     * resend notification(email) to Employee's mail-id,
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
     * expand the invoice resource in payout
     *
     * @return $this
     */
    public function expandInvoice()
    {
        if ($this->invoice) {
            $this->invoice = Invoice::findById($this->invoice)->getJson();
        } else {
            throw new Exception('No invoice found');
        }
        return $this;
    }

    /**
     * expand the invoice resource in payout
     *
     * @return $this
     */
    public function expandEmployee()
    {
        if ($this->employee) {
            $this->employee = Employee::findById($this->employee)->getJson();
        } else {
            throw new Exception('No employee found');
        }
        return $this;
    }

    /**
     * convert the Payout instance to json
     *
     * @return json
     */
    public function getJson()
    {
        return json_decode(
            json_encode(
                [
                    "id" => $this->id,
                    "amount" => $this->amount,
                    "invoiced_amount" => $this->invoiced_amount,
                    "cost" => $this->cost,
                    "country" => $this->country,
                    "currency" => $this->currency,
                    "description" => $this->description,
                    "full_salary_specification" => $this->full_salary_specification,
                    "employee" => $this->employee,
                    "invoice" => $this->invoice,
                    "metadata" => $this->metadata,
                    "start_at" => $this->start_at,
                    "end_at" => $this->end_at,
                    "created_at" => $this->created_at,
                    "notified_at" => $this->notified_at,
                    "accepted_at" => $this->accepted_at,
                ]
            )
        );;
    }
}