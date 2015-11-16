<?php

namespace JimLind\Pie7o\tests;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use JimLind\Pie7o\StatusUpdater;
use PHPUnit_Framework_TestCase;

/**
 * Test the JimLind\Pie7o\StatusUpdater class.
 */
class StatusUpdaterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorizationBuilder
     */
    protected $authorizationBuilder;

    /**
     * @var StatusUpdater
     */
    protected $fixture;

    protected function setUp()
    {
        $this->authorizationBuilder = $this->getMockBuilder('JimLind\Pie7o\AuthorizationBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->guzzleClient = $this->getMock('GuzzleHttp\ClientInterface');

        $this->fixture = new StatusUpdater($this->authorizationBuilder, $this->guzzleClient);
    }

    /**
     * Test fixture has proper inheritance.
     */
    public function testInheritance()
    {
        $this->assertInstanceOf('JimLind\Pie7o\TwitterApiCaller', $this->fixture);
    }

    /**
     * Test AuthorizationBuilder is called correctly.
     *
     * Short circuit the output with an exception
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Update Status: ``
     */
    public function testAuthorizationBuilderCalled()
    {
        $method = 'POST';
        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        $post = [
            'status' => false,
        ];

        $this->authorizationBuilder->expects($this->once())->method('build')->with($method, $url, $post);
        $this->guzzleClient->method('send')->will($this->throwException(new Exception()));

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMessage')->willReturn($stream);
        $tweet->method('getMediaId')->willReturn(0);

        $this->fixture->update($tweet);
    }

    /**
     * Test GuzzleClient is called correctly.
     *
     * Short circuit the output with an exception
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Update Status: ``
     */
    public function testGuzzleClientSendCalledWithRequestAndOptions()
    {
        $auth = uniqid();
        $contents = uniqid();

        $this->authorizationBuilder->method('build')->willReturn($auth);

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $stream->method('getContents')->willReturn($contents);

        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMessage')->willReturn($stream);
        $tweet->method('getMediaId')->willReturn(0);

        $request = (new Request('POST', 'https://api.twitter.com/1.1/statuses/update.json'))
            ->withHeader('Authorization', $auth);

        $options = ['form_params' => ['status' => $contents]];

        $this->guzzleClient->method('send')->with($request, $options)->will($this->throwException(new Exception()));

        $this->fixture->update($tweet);
    }

    /**
     * Test GuzzleClient is called correctly including Media Id.
     *
     * Short circuit the output with an exception
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Update Status: ``
     */
    public function testGuzzleClientSendCalledWithRequestAndOptionsIncludingMediaId()
    {
        $auth = uniqid();
        $contents = uniqid();
        $mediaId = uniqid();

        $this->authorizationBuilder->method('build')->willReturn($auth);

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $stream->method('getContents')->willReturn($contents);

        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMessage')->willReturn($stream);
        $tweet->method('getMediaId')->willReturn($mediaId);

        $request = (new Request('POST', 'https://api.twitter.com/1.1/statuses/update.json'))
            ->withHeader('Authorization', $auth);

        $options = [
            'form_params' => [
                'status' => $contents,
                'media_ids' => $mediaId,
            ],
        ];

        $this->guzzleClient->method('send')->with($request, $options)->will($this->throwException(new Exception()));

        $this->fixture->update($tweet);
    }

    /**
     * Test GuzzleClient throws a BadResponseException.
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Update Status: `Bad Response Exception Body`
     */
    public function testGuzzleClientBadResponseException()
    {
        $request = $this->getMock('Psr\Http\Message\RequestInterface');
        $response = $this->getMock('GuzzleHttp\Psr7\Response');
        $response->method('getBody')->willReturn('Bad Response Exception Body');

        $exception = new BadResponseException(null, $request, $response);

        $this->guzzleClient->method('send')->will($this->throwException($exception));

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMessage')->willReturn($stream);

        $this->fixture->update($tweet);
    }

    /**
     * Test GuzzleClient throws an Exception.
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Update Status: `Standard Exception Message`
     */
    public function testGuzzleClientException()
    {
        $exception = new Exception('Standard Exception Message');
        $this->guzzleClient->method('send')->will($this->throwException($exception));

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMessage')->willReturn($stream);

        $this->fixture->update($tweet);
    }

    /**
     * Test GuzzleClient response is not 200.
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Update Status: `Status Code Not 200`
     */
    public function testGuzzleClientResponseBadStatusCode()
    {
        $response = $this->getMock('GuzzleHttp\Psr7\Response');
        $response->method('getStatusCode')->willReturn(rand(0, 199));
        $response->method('getBody')->willReturn('Status Code Not 200');

        $this->guzzleClient->method('send')->willReturn($response);

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMessage')->willReturn($stream);

        $this->fixture->update($tweet);
    }

    /**
     * Test GuzzleClient with good response.
     */
    public function testGuzzleClientResponseGood()
    {
        $response = $this->getMock('GuzzleHttp\Psr7\Response');
        $response->method('getStatusCode')->willReturn(200);

        $this->guzzleClient->method('send')->willReturn($response);

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMessage')->willReturn($stream);

        $actual = $this->fixture->update($tweet);
        $this->assertNull($actual);
    }
}
