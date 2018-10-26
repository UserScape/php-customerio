<?php

require_once('TestCase.php');

use Customerio\Api;
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

        $response = $api->fireEvent($id, $name, $data);

        $this->assertTrue( $response->success() );
    }

    public function testFireBacklogEvent()
    {
        $id = $this->getRandomString();

        $name = $this->getRandomString();

        $data = $this->getAttributes();

        $timestamp = $this->getTimestamp();

        $api = $this->createApi();

        $response = $api->fireEvent($id, $name, $data, $timestamp);

        $this->assertTrue( $response->success() );
    }

    public function testFireAnonymousEvent()
    {
        $name = $this->getRandomString();

        $data = $this->getAttributes();

        $api = $this->createApi();

        $response = $api->fireAnonymousEvent($name, $data);

        $this->assertTrue( $response->success() );
    }

    public function testRecordPageview()
    {
        $id = $this->getRandomString();

        $url = 'http://' . $this->getRandomString(8) . '.com/';

        $referrer = 'http://' . $this->getRandomString(8) . '.com/';

        $api = $this->createApi();

        $response = $api->recordPageview($id, $url, $referrer);

        $this->assertTrue( $response->success() );
    }

    public function testAddToSegment()
    {
        $segmentId = $this->getRandomString();
        $users = [$this->getRandomString(), $this->getRandomString()];

        $api = $this->createApi();
        $response = $api->addToSegment($segmentId, $users);
        
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

    protected function getTimestamp()
    {
        $date = new DateTime();
        return $date->getTimestamp();
    }

    protected function createApi()
    {
        $siteId = $this->getRandomString();
        $apiSecret = $this->getRandomString();

        return new Api($siteId, $apiSecret, $this->mockRequest());
    }

    protected function mockRequest()
    {
        $stub = $this->getMockBuilder('Customerio\Request')
            ->disableOriginalConstructor()
            ->getMOck();

        $stub->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($stub));

        $stub->expects($this->any())
            ->method('customer')
            ->will($this->returnValue(new Response(200, 'Ok')));

        $stub->expects($this->any())
            ->method('deleteCustomer')
            ->will($this->returnValue(new Response(200, 'Ok')));

        $stub->expects($this->any())
            ->method('event')
            ->will($this->returnValue(new Response(200, 'Ok')));

        $stub->expects($this->any())
            ->method('anonymousEvent')
            ->will($this->returnValue(new Response(200, 'Ok')));

        $stub->expects($this->any())
            ->method('pageview')
            ->will($this->returnValue(new Response(200, 'Ok')));
        
        $stub->expects($this->any())
            ->method('addToSegment')
            ->will($this->returnValue(new Response(200, 'OK')));

        return $stub;
    }

}
