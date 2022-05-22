<?php

namespace  Mazimez\Gigapay\Resources;

use Mazimez\Gigapay\Managers\RequestManager;

class ListResource
{
    /**
     * API URL to call
     *
     * @var string
     */
    protected $url;

    /**
     * Create a new ListResource instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @param  string  $url
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Add the pagination parameter into API
     * doc: https://developer.gigapay.se/#pagination
     *
     * @param  integer  $page
     * @param  integer  $page_size
     * @return $this
     */
    public function paginate($page = 1, $page_size = 10)
    {
        $this->addFilter('page', $page);
        $this->addFilter('page_size', $page_size);
        return $this;
    }

    /**
     * Add the search parameter into API
     *
     * @param  string  $search
     * @return $this
     */
    public function search($search)
    {
        $this->addFilter('search', $search);
        return $this;
    }

    /**
     * Add the expand filter for multiple resources
     * check out doc for more info: https://developer.gigapay.se/#expanding-objects
     *
     * @param  string  $resource_name
     * @return $this
     */
    public function expand($resource_name)
    {
        $this->addFilter('expand', $resource_name);
        return $this;
    }

    /**
     * Add any filter parameter into API,
     * check out doc for more info: https://developer.gigapay.se/#filtering
     *
     * @param  string  $key
     * @param  string  $value
     * @return $this
     */
    public function addFilter($key, $value)
    {
        if (strpos($this->url, '?')) {
            $this->url = $this->url . '&' . $key . '=' . $value;
        } else {
            $this->url = $this->url . '?' . $key . '=' . $value;
        }
        return $this;
    }

    /**
     * Get the JSON response from API
     *
     * @return json
     */
    public function getJson()
    {
        $request_manager = new RequestManager();
        return $request_manager->getData(
            'GET',
            $this->url
        );
    }
}
