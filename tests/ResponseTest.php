<?php

require_once('TestCase.php');

use Customerio\Response;

class ResponseTest extends TestCase  {

    public function testSuccessAndMessageSet()
    {
        $response1 = new Response(200, 'Ok');
        $response2 = new Response(301, 'Moved Permanently');
        $response3 = new Response(401, 'Unauthorized');
        $response4 = new Response(500, 'Server Error');

        $this->assertTrue( $response1->success() );
        $this->assertFalse( $response2->success() );
        $this->assertFalse( $response3->success() );
        $this->assertFalse( $response4->success() );

        $this->assertEquals( 'Ok', $response1->message() );
        $this->assertEquals( 'Moved Permanently', $response2->message() );
        $this->assertEquals( 'Unauthorized', $response3->message() );
        $this->assertEquals( 'Server Error', $response4->message() );
    }


}