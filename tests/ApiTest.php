<?php

require_once('TestCase.php');

use Customerio\Api;
use Customerio\Request;
use Customerio\Response;

class ApiTest extends TestCase  {

    public function testCreateCustomer()
    {
        // User Id
        $id = $this->getRandomString();

        // User Email
        $email = $this->getEmail();

        // Attributes
        $attributes = $this->getAttributes();

        $api = $this->createApi();

        $response = $api->createCustomer($id, $email, $attributes);

        $this->assertTrue( $response->success() );

    }

    public function testUpdateCustomer()
    {
        // User ID
        $id = $this->getRandomString();

        // User Email
        $email = $this->getEmail();

        // Attributes
        $attributes = $this->getAttributes();

        $api = $this->createApi();

        $response = $api->updateCustomer($id, $email, $attributes);

        $this->assertTrue( $response->success() );
    }

    public function testDeleteCustomer()
    {
        $id = $this->getRandomString();

        $api = $this->createApi();

        $response = $api->deleteCustomer($id);

        $this->assertTrue( $response->success() );
    }

    public function testFireEvent()
    {
        $id = $this->getRandomString();

        $name = $this->getRandomString();

        $data = $this->getAttributes();

        $api = $this->createApi();

        $response = $api->fireEvent($id, $name, $attributes);

        $this->assertTrue( $response->success() );
    }

    protected function getEmail()
    {
        return $this->getRandomString(5).'@'.$this->getRandomString(10).'.com';
    }

    protected function getAttributes()
    {
        $key = $this->getRandomString(6);
        $value = $this->getRandomString(6);

        return array(
            $key => $value
        );
    }

    protected function createApi()
    {
        $siteId = $this->getRandomString();
        $apiSecret = $this->getRandomString();

        return new Api($siteId, $apiSecret, $this->mockRequest());
    }

    protected function mockRequest()
    {
        $stub = $this->getMock('Customerio\Request');

        $stub->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($stub));

        $stub->expects($this->any())
            ->method('customer')
            ->will($this->returnValue(new Response));

        $stub->expects($this->any())
            ->method('deleteCustomer')
            ->will($this->returnValue(new Response));

        $stub->expects($this->any())
            ->method('event')
            ->will($this->returnValue(new Response));

        return $stub;
    }

}
