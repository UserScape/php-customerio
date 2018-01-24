<?php namespace Customerio;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use stdClass;

class Request {

    /**
     * An HTTP Client
     * @var \GuzzleHttp\Client
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

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://track.customer.io',
        ]);
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
        if (! is_null($email)) {
            $body = array_merge(array('email' => $email), $attributes);
        }

        try {
            $response = $this->client->put('/api/v1/customers/'.$id, array(
                'auth' => $this->auth,
                'json' => $body,
            ));
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
            $response = $this->client->delete('/api/v1/customers/'.$id, array(
                'auth' => $this->auth,
            ));
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
        $body = array_merge( array('name' => $url, 'type' => 'page'), $this->parseData( array( 'referrer' => $referrer ) ) );

        try {
            $response = $this->client->post('/api/v1/customers/'.$id.'/events', array(
                'auth' => $this->auth,
                'json' => $body,
            ));
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
     * @param $timestamp (optional)
     * @return Response
     */
    public function event($id, $name, $data, $timestamp)
    {
        if (is_null($timestamp)) {
            $body = array_merge( array('name' => $name), $this->parseData($data) );
        } else {
            $body = array_merge( array('name' => $name, 'timestamp' => $timestamp), $this->parseData($data) );
        }

        try {
            $response = $this->client->post('/api/v1/customers/'.$id.'/events', array(
                'auth' => $this->auth,
                'json' => $body,
            ));
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
     * @return Response
     */
    public function anonymousEvent($name, $data)
    {
        $body = array_merge( array('name' => $name), $this->parseData($data) );

        try {
            $response = $this->client->post('/api/v1/events', array(
                'auth' => $this->auth,
                'json' => $body,
            ));
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
     * @return array
     */
    protected function parseData(array $data)
    {
		if (empty($data))
		{
			$data = new stdClass();
		}

        return array('data' => $data);
    }

}
