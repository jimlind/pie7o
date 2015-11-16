<?php

namespace JimLind\Pie7o\tests;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use JimLind\Pie7o\MediaUploader;
use PHPUnit_Framework_TestCase;

/**
 * Test the JimLind\Pie7o\MediaUploader class.
 */
class MediaUploaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorizationBuilder
     */
    protected $authorizationBuilder;

    /**
     * @var MediaUploader
     */
    protected $fixture;

    protected function setUp()
    {
        $this->authorizationBuilder = $this->getMockBuilder('JimLind\Pie7o\AuthorizationBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->guzzleClient = $this->getMock('GuzzleHttp\ClientInterface');

        $this->fixture = new MediaUploader($this->authorizationBuilder, $this->guzzleClient);
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
     * @expectedExceptionMessage Could Not Upload Media: ``
     */
    public function testAuthorizationBuilderCalled()
    {
        $method = 'POST';
        $url = 'https://upload.twitter.com/1.1/media/upload.json';
        $post = [];

        $this->authorizationBuilder->expects($this->once())->method('build')->with($method, $url, $post);
        $this->guzzleClient->method('send')->will($this->throwException(new Exception()));

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMedia')->willReturn($stream);

        $this->fixture->upload($tweet);
    }

    /**
     * Test GuzzleClient is called correctly.
     *
     * Short circuit the output with an exception
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Upload Media: ``
     */
    public function testGuzzleClientSendCalledWithRequestAndOptions()
    {
        $auth = uniqid();
        $contents = uniqid();

        $this->authorizationBuilder->method('build')->willReturn($auth);

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $stream->method('getContents')->willReturn($contents);

        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMedia')->willReturn($stream);

        $request = (new Request('POST', 'https://upload.twitter.com/1.1/media/upload.json'))
            ->withHeader('Authorization', $auth);

        $options = ['multipart' => [['name' => 'media', 'contents' => $contents]]];

        $this->guzzleClient->method('send')->with($request, $options)->will($this->throwException(new Exception()));

        $this->fixture->upload($tweet);
    }

    /**
     * Test GuzzleClient throws a BadResponseException.
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Upload Media: `Bad Response Exception Body`
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
        $tweet->method('getMedia')->willReturn($stream);

        $this->fixture->upload($tweet);
    }

    /**
     * Test GuzzleClient throws an Exception.
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Upload Media: `Standard Exception Message`
     */
    public function testGuzzleClientException()
    {
        $exception = new Exception('Standard Exception Message');
        $this->guzzleClient->method('send')->will($this->throwException($exception));

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMedia')->willReturn($stream);

        $this->fixture->upload($tweet);
    }

    /**
     * Test GuzzleClient response is not 200.
     *
     * @expectedException JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Could Not Upload Media: `Status Code Not 200`
     */
    public function testGuzzleClientResponseBadStatusCode()
    {
        $response = $this->getMock('GuzzleHttp\Psr7\Response');
        $response->method('getStatusCode')->willReturn(rand(0, 199));
        $response->method('getBody')->willReturn('Status Code Not 200');

        $this->guzzleClient->method('send')->willReturn($response);

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMedia')->willReturn($stream);

        $this->fixture->upload($tweet);
    }

    /**
     * Test GuzzleClient with good response.
     */
    public function testGuzzleClientResponseGood()
    {
        $mediaId = rand();
        $expected = rand();

        $response = $this->getMock('GuzzleHttp\Psr7\Response');
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn('{"media_id":'.$mediaId.'}');

        $this->guzzleClient->method('send')->willReturn($response);

        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMedia')->willReturn($stream);
        $tweet->expects($this->once())->method('withMediaId')->with($mediaId)->willReturn($expected);

        $actual = $this->fixture->upload($tweet);
        $this->assertEquals($expected, $actual);
    }
}
