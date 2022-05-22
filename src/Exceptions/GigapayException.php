<?php

namespace  Mazimez\Gigapay\Exceptions;

use Exception;
use GuzzleHttp\Exception\BadResponseException;

class GigapayException extends Exception
{
    /**
     * exception instance
     *
     * @var \GuzzleHttp\Exception\BadResponseException
     */
    protected BadResponseException $exception;

    /**
     * json regarding the exception
     *
     * @var object
     */
    protected $json;

    /**
     * Create a new GigapayException instance.
     *
     * @param \GuzzleHttp\Exception\BadResponseException
     * @return void
     */
    public function __construct(
        BadResponseException $exception
    ) {
        $this->exception = $exception;
        $this->json = json_decode(
            $this->exception->getResponse()->getBody()->getContents()
        );
        parent::__construct($this->getErrorMessage());
    }

    /**
     * get the json response explaining the Exception
     * doc: https://developer.gigapay.se/#errors
     *
     * @return json
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * get the error message explaining the Exception
     * doc: https://developer.gigapay.se/#errors
     *
     * @return string
     */
    public function getErrorMessage()
    {
        $error_message = null;
        if ($this->json) {
            foreach ($this->json as $key => $value) {

                $problem_message = null;
                $problem_key = null;

                if ($key != "non_field_errors") {
                    $problem_key = $key;
                }

                if (is_array($value)) {
                    $problem_message = $value[0];
                } else {
                    $problem_message = $value;
                }

                if ($problem_key) {
                    if ($error_message) {
                        $error_message = $error_message . ',' . 'Problem with ' . $key . '->' . $problem_message;
                    } else {
                        $error_message = 'Problem with ' . $key . '->' . $problem_message;
                    }
                } else {
                    if ($error_message) {
                        $error_message = $error_message . ',' . $problem_message;
                    } else {
                        $error_message = $problem_message;
                    }
                }
            }
        }

        return $error_message;
    }
}
