<?php

namespace Stampie\Tests\Adapter;

use Stampie\Adapter\GuzzleHttp;

class GuzzleHttpTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        if (!class_exists('GuzzleHttp\Client')) {
            $this->markTestSkipped('Cannot find GuzzleHttp\Client');
        }


        $this->client = $this->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(array('send'))
            ->getMock();
    }

    public function testAccesibility()
    {
        $adapter = new GuzzleHttp($this->client);
        $this->assertEquals($this->client, $adapter->getClient());
    }

    public function testSend()
    {
        $adapter = new GuzzleHttp($this->client);
        $response = $this->getResponseMock();
        $request = $this->getRequestMock();
        $client = $this->client;

        $request
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response))
        ;

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;

        $response
            ->expects($this->once())
            ->method('getBody')
            ->with($this->equalTo(true))
        ;

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->equalTo('POST'),
                $this->equalTo('http://google.com'),
                $this->equalTo(array(
                    'Content-Type' => 'application/json',
                    'X-Postmark-Server-Token' => 'MySuperToken',
                )),
                $this->equalTo('content')
            )
            ->will(
                $this->returnValue($request)
            )
        ;

        $adapter->send('http://google.com', 'content', array(
            'Content-Type' => 'application/json',
            'X-Postmark-Server-Token' => 'MySuperToken',
        ));
    }

    protected function getRequestMock()
    {
        return $this->getMock('GuzzleHttp\Message\Request', array(), array(), '', null, true);
    }

    protected function getResponseMock()
    {
        return $this->getMock('GuzzleHttp\Message\Response', array(), array(), '', null, true);
    }
}
