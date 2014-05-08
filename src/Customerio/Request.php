<?php namespace Customerio;

use Guzzle\Http\Exception\RequestException;

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
        } catch(RequestException $e)
        {
            $response = $e->getResponse();
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
        }catch(RequestException $e)
        {
            $response = $e->getResponse();
        }


        return new Response($response->getStatusCode(), $response->getReasonPhrase());
    }

    /**
     * Send and Event to Customer.io
     * @param $id
     * @param $name
     * @param $data
     * @return Response
     */
    public function event($id, $name, $data)
    {
        $body = array_merge( array('name' => $name), $this->parseData($data) );

        try {
            $response = $this->client->post('/api/v1/customers/'.$id.'/events', null, $body, array(
                'auth' => $this->auth,
            ))->send();
        }catch(RequestException $e)
        {

            $response = $e->getResponse();
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
        $parsed = array();

        foreach( $data as $key => $value)
        {
            $parsed['data['.$key.']'] = $value;
        }

        return $parsed;
    }

}
