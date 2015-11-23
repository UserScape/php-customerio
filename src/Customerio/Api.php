<?php namespace Customerio;

class Api {

    protected $siteId;

    protected $apiSecret;

    protected $request;

    public function __construct($siteId, $apiSecret, Request $request)
    {
        $this->siteId = $siteId;
        $this->apiSecret = $apiSecret;
        $this->request = $request;
    }

    public function createCustomer($id, $email, $attributes=array())
    {
        return $this->request->authenticate($this->siteId, $this->apiSecret)->customer($id, $email, $attributes);
    }

    public function updateCustomer($id, $email, $attributes=array())
    {
        return $this->createCustomer($id, $email, $attributes);
    }

    public function deleteCustomer($id)
    {
        return $this->request->authenticate($this->siteId, $this->apiSecret)->deleteCustomer($id);
    }

    public function fireEvent($id, $name, $data=array(), $formatJson = false)
    {
        return $this->request->authenticate($this->siteId, $this->apiSecret)->event($id, $name, $data, $formatJson);
    }

    public function fireAnonymousEvent($name, $data=array(), $formatJson = false)
    {
        return $this->request->authenticate($this->siteId, $this->apiSecret)->anonymousEvent($name, $data, $formatJson);
    }

    public function recordPageview($id, $url, $referrer)
    {
        return $this->request->authenticate($this->siteId, $this->apiSecret)->pageview($id, $url, $referrer);
    }
}
