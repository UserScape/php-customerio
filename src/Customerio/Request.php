<?php namespace Customerio;

class Request {

    protected $client;

    protected $auth = array();

    public function __construct($client=null)
    {
        if( ! is_null($client) )
        {
            $this->client = $client;
            return;
        }

        $client = new \Guzzle\Http\Client('https://track.customer.io');
    }

    public function customer($id, $email, $attributes)
    {
        $body = array_merge(array('email' => $email), $attributes);

        $response = $this->client->put('/api/v1/customers/'.$id, null, $body, array(
            'auth' => $this->auth,
        ))->send();

        return new Response;
    }

    public function deleteCustomer($id)
    {
        $response = $this->client->delete('/api/v1/customers/'.$id, null, null, array(
            'auth' => $this->auth,
        ))->send();

        return new Response;
    }

    public function event($id, $name, $data)
    {
        $body = array_merge( array('name' => $name), $this->parseData($data) );

        $response = $this->client->post('/api/v1/customers/'.$id.'/events', null, $body, array(
            'auth' => $this->auth,
        ))->send();

        return new Response;
    }

    protected function parseData(array $data)
    {
        $parsed = array();

        foreach( $data as $key => $value)
        {
            $return['data['.$key.']'] = $value;
        }

        return $parsed;
    }

    public function authenticate($apiKey, $apiSecret)
    {
        $this->auth[0] = $apiKey;
        $this->auth[1] = $apiSecret;

        return $this;
    }

}
