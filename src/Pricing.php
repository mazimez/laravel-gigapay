<?php

namespace  Mazimez\Gigapay;

use Mazimez\Gigapay\Managers\RequestManager;
use Mazimez\Gigapay\Resources\ListResource;
use Exception;

class Pricing
{
    /**
     * Decimal formatted string of the gross salary amount.
     *
     * @var string
     */
    public $amount;

    /**
     * Decimal formatted string of the invoiced amount.
     *
     * @var string
     */
    public $invoiced_amount;

    /**
     * Decimal formatted string of the total salary cost.
     *
     * @var string
     */
    public $cost;

    /**
     * ISO-4217 currency code.
     *
     * @var string
     */
    public $currency;

    /**
     * Decimal formatted string of Gigapay's fee for this Payout.
     *
     * @var string
     */
    public $fee;

    /**
     * Decimal formatted string of the cost of mandated health insurance. Will be none if health insurance is not mandated.
     *
     * @var string
     */
    public $health_insurance;

    /**
     * Decimal formatted string of the payroll taxes. Will be none if payroll taxes are not mandated.
     *
     * @var string
     */
    public $payroll;

    /**
     * Decimal formatted string of the preliminary income taxes the will be reported and paid on behalf of the recipient.
     *
     * @var string
     */
    public $tax;

    /**
     * Decimal formatted string of the VAT for the Payout.
     *
     * @var string/json
     */
    public $vat;

    /**
     * Create a new Pricing instance.
     *
     * @param json $json
     * @return $this
     */
    public function __construct($json)
    {
        $this->amount = $json->amount;
        $this->invoiced_amount = $json->invoiced_amount;
        $this->cost = $json->cost;
        $this->currency = $json->currency;
        $this->fee = $json->fee;
        $this->health_insurance = $json->health_insurance;
        $this->payroll = $json->payroll;
        $this->tax = $json->tax;
        $this->vat = $json->vat;

        return $this;
    }

    /**
     * get the url for Pricing resource
     *
     * @return $string
     */
    static function getUrl()
    {
        return config('gigapay.server_url') . '/pricing';
    }

    /**
     * get List resource of Pricing info for past Payouts.
     * doc: https://developer.gigapay.se/#list-pricing-info
     *
     * @return \Mazimez\Gigapay\Resources\ListResource
     */
    static function list()
    {
        return new ListResource(Pricing::getUrl());
    }

    /**
     * get pricing info of payout instance by it's ID,
     * doc: https://developer.gigapay.se/#retrieve-pricing-info-of-payout
     *
     * @param string $payout_id
     * @return \Mazimez\Gigapay\Pricing
     */
    static function findById($payout_id)
    {
        $url = Pricing::getUrl() . '/' . $payout_id;
        $request_manager = new RequestManager();
        return new Pricing(
            $request_manager->getData('GET', $url)
        );
    }

    /**
     * calculate the pricing info for the payout you would like to make,
     * doc: https://developer.gigapay.se/#calculate-pricing-info
     *
     * @param string $employee
     * @param string $currency
     * @param string $cost
     * @param string $amount
     * @param string $invoiced_amount
     * @param string $description
     * @param boolean $full_salary_specification
     * @param object $metadata
     * @param string $start_at
     * @param string $end_at
     * @return \Mazimez\Gigapay\Pricing
     */
    static function calculatePricing(
        $employee,
        $currency = null,
        $cost = null,
        $amount = null,
        $invoiced_amount = null,
        $description = null,
        $full_salary_specification = null,
        $metadata = null,
        $start_at = null,
        $end_at = null,
        $id = null
    ) {
        $url = Pricing::getUrl();
        if (!$cost && !$amount && !$invoiced_amount) {
            throw new Exception('Either cost or amount or invoiced_amount is required.');
        }
        $params = [];
        if ($employee) {
            $params = array_merge($params, ['employee' => $employee]);
        }
        if ($currency) {
            $params = array_merge($params, ['currency' => $currency]);
        }
        if ($cost) {
            $params = array_merge($params, ['cost' => $cost]);
        }
        if ($amount) {
            $params = array_merge($params, ['amount' => $amount]);
        }
        if ($invoiced_amount) {
            $params = array_merge($params, ['invoiced_amount' => $invoiced_amount]);
        }
        if ($description) {
            $params = array_merge($params, ['description' => $description]);
        }
        if ($full_salary_specification) {
            $params = array_merge($params, ['full_salary_specification' => $full_salary_specification]);
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
        if ($description) {
            $params = array_merge($params, ['description' => $description]);
        }
        if ($id) {
            $params = array_merge($params, ['id' => $id]);
        }
        $request_manager = new RequestManager();
        return new Pricing(
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
     * convert the Invoice instance to json
     *
     * @return json
     */
    public function getJson()
    {
        return json_decode(
            json_encode(
                [
                    "amount" => $this->amount,
                    "invoiced_amount" => $this->invoiced_amount,
                    "cost" => $this->cost,
                    "currency" => $this->currency,
                    "fee" => $this->fee,
                    "health_insurance" => $this->health_insurance,
                    "payroll" => $this->payroll,
                    "tax" => $this->tax,
                    "vat" => $this->vat,
                ]
            )
        );;
    }
}