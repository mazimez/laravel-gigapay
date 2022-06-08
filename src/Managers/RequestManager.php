<?php

namespace  Mazimez\Gigapay\Managers;

use Mazimez\Gigapay\Exceptions\GigapayException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class RequestManager
{
    /**
     * the token to send into header
     *
     * @var string
     */
    protected $token;

    /**
     * the Integration ID to send into header
     *
     * @var string
     */
    protected $integration_id;

    /**
     * the language to send into header
     *
     * @var string
     */
    protected $lang;

    /**
     * the client instance
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;


    /**
     * Create a new client instance with all required tokens in header,
     * doc: https://developer.gigapay.se/#authentication
     *
     * @return \GuzzleHttp\Client
     */
    protected function setUpClient()
    {
        $this->token = config('gigapay.token');
        $this->integration_id = config('gigapay.integration_id');
        $this->lang = config('gigapay.lang');

        if (!$this->token || !$this->integration_id || !$this->lang) {
            throw new Exception("Please set up .env file");
        }

        $this->client = new Client([
            "headers" => [
                "Authorization" => "Token " . $this->token,
                "Integration-Id" => $this->integration_id,
                "Accept-Language" => $this->lang,
            ]
        ]);
    }

    /**
     * call the API with given method, url and parameters
     *
     * @param  string  $method
     * @param  string  $url
     * @param  array  $params
     * @return json
     */
    public function getData($method, $url, $params = null)
    {
        $this->setUpClient();
        try {
            if ($params) {
                $response = $this->client->request($method, $url, $params);
            } else {
                $response = $this->client->request($method, $url);
            }
        } catch (BadResponseException $th) {
            if ($th->hasResponse()) {
                throw new GigapayException($th);
            } else {
                throw new Exception($th->getMessage());
            }
        } catch (Exception $th) {
            throw new Exception($th->getMessage());
        }
        return json_decode(
            $response->getBody()
        );
    }
}