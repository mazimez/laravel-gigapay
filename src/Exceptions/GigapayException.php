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
                if (is_numeric($key)) {
                    foreach ($value as $label => $data) {
                        $problem_key = $label;
                        if (is_array($data)) {
                            $problem_message = $data[0];
                        } else {
                            $problem_message = $data;
                        }
                        if ($error_message) {
                            $error_message = $error_message . ',' . 'Problem with ' . $problem_key . '->' . $problem_message;
                        } else {
                            $error_message = 'Problem with ' . $problem_key . '->' . $problem_message;
                        }
                    }
                } else {
                    if ($key != "non_field_errors") {
                        $problem_key = $key;
                    }
                    if (is_array($value)) {
                        $problem_message = $value[0];
                    } else {
                        if (is_object($value)) {
                            if (isset($value->non_field_errors) && is_array($value->non_field_errors)) {
                                $problem_message = $value->non_field_errors[0];
                            }
                        } else {
                            $problem_message = $value;
                        }
                    }


                    if ($problem_key) {
                        if ($problem_key == "events") {
                            foreach ($value as $key => $val) {
                                if (is_array($val)) {
                                    foreach ($val as $v) {
                                        if ($error_message) {
                                            $error_message = $error_message . ',' . 'Problem with ' . $problem_key . '->' . $v;
                                        } else {
                                            $error_message = 'Problem with ' . $problem_key . '->' . $v;
                                        }
                                    }
                                } else {
                                    if ($error_message) {
                                        $error_message = $error_message . ',' . 'Problem with ' . $problem_key . '->' . $val;
                                    } else {
                                        $error_message = 'Problem with ' . $problem_key . '->' . $val;
                                    }
                                }
                            }
                        } else {
                            if ($error_message) {
                                $error_message = $error_message . ',' . 'Problem with ' . $problem_key . '->' . $problem_message;
                            } else {
                                $error_message = 'Problem with ' . $problem_key . '->' . $problem_message;
                            }
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
        }

        return $error_message;
    }
}
