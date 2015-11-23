<?php namespace Customerio;

use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Exception\BadResponseException;

class Request {

    /**
     * An HTTP Client
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * Basic Auth Credentials
     * ['user', 'password']
     * @var array
     */
    protected $auth = array();

    /**
     * Create a new Request
     * Bootlegness for testing
     * @param null $client
     */
    public function __construct($client=null)
    {
        if( ! is_null($client) )
        {
            $this->client = $client;
            return;
        }

        $this->client = new \Guzzle\Http\Client('https://track.customer.io');
    }

    /**
     * Send Create/Update Customer Request
     * @param $id
     * @param $email
     * @param $attributes
     * @return Response
     */
    public function customer($id, $email, $attributes)
    {
        $body = array_merge(array('email' => $email), $attributes);

        try {
            $response = $this->client->put('/api/v1/customers/'.$id, null, $body, array(
                'auth' => $this->auth,
            ))->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (RequestException $e) {
            return new Response($e->getCode(), $e->getMessage());
        }

        return new Response($response->getStatusCode(), $response->getReasonPhrase());
    }

    /**
     * Send Delete Customer Request
     * @param $id
     * @return Response
     */
    public function deleteCustomer($id)
    {
        try {
            $response = $this->client->delete('/api/v1/customers/'.$id, null, null, array(
                'auth' => $this->auth,
            ))->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (RequestException $e) {
            return new Response($e->getCode(), $e->getMessage());
        }

        return new Response($response->getStatusCode(), $response->getReasonPhrase());
    }

    /**
     * Send a pageview to Customer.io
     * @param $id
     * @param $url
     * @param $referrer
     * @return Response
     */
    public function pageview($id, $url, $referrer = '')
    {
        $body = $this->parseData(array('name' => $url, 'type' => 'page', 'referrer' => $referrer));

        try {
            $response = $this->client->post('/api/v1/customers/'.$id.'/events', null, $body, array(
                'auth' => $this->auth,
            ))->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (RequestException $e) {
            return new Response($e->getCode(), $e->getMessage());
        }

        return new Response($response->getStatusCode(), $response->getReasonPhrase());
    }

    /**
     * Send and Event to Customer.io
     * @param $id
     * @param $name
     * @param $data
     * @param $formatJson
     * @return Response
     */
    public function event($id, $name, $data, $formatJson)
    {
        $body = $this->parseData(array('name' => $name, 'data' => $data), $formatJson);

        try {
            $response = $this->client->post('/api/v1/customers/'.$id.'/events', null, $body, array(
                'auth' => $this->auth,
            ))->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (RequestException $e) {
            return new Response($e->getCode(), $e->getMessage());
        }

        return new Response($response->getStatusCode(), $response->getReasonPhrase());
    }

    /**
     * Send an Event to Customer.io not associated to any existing Customer.io user
     * @param $name
     * @param $data
     * @param $formatJson
     * @return Response
     */
    public function anonymousEvent($name, $data, $formatJson)
    {
        $body = $this->parseData(array('name' => $name, 'data' => $data), $formatJson);

        try {
            $response = $this->client->post('/api/v1/events', null, $body, array(
                'auth' => $this->auth,
            ))->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (RequestException $e) {
            return new Response($e->getCode(), $e->getMessage());
        }

        return new Response($response->getStatusCode(), $response->getReasonPhrase());
    }

    /**
     * Set Authentication credentials
     * @param $apiKey
     * @param $apiSecret
     * @return $this
     */
    public function authenticate($apiKey, $apiSecret)
    {
        $this->auth[0] = $apiKey;
        $this->auth[1] = $apiSecret;

        return $this;
    }

    /**
     * Parse data as specified by customer.io
     * @link http://customer.io/docs/api/rest.html
     * @param array $data
     * @param $formatJson
     * @return array
     */
    protected function parseData(array $data, $formatJson)
    {
        if ($formatJson) {
            $isAssoc = array_keys($data) !== range(0, count($data) - 1);
            $parsed = $isAssoc ? '{' : '[';
            $first = true;

            foreach ($data as $key => $value)
            {
                if (is_array($value) || is_object($value)) {
                    $value = $this->parseData($value, $formatJson);
                } else {
                    $value = '"' . $value . '"';
                }

                $parsed .= ($first ? '' : ',') . ($isAssoc ? '"' . $key . '":' : '') . $value;

                $first &= false;
            }

            return $parsed . ($isAssoc ? '}' : ']');
        } else {
            $parsed = array();

            foreach ($data['data'] as $key => $value)
            {
                $parsed['data['.$key.']'] = $value;
            }

            unset($data['data']);

            return array_merge($data, $parsed);
        }
    }

}
