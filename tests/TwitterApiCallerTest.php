<?php

namespace JimLind\Pie7o\Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use JimLind\Pie7o\TwitterApiCaller;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * Test the JimLind\Pie7o\TwitterApiCaller class
 */
class TwitterApiCallerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorizationBuilder
     */
    protected $authorizationBuilder;

    /**
     * @var TwitterApiCaller
     */
    protected $fixture;

    protected function setUp()
    {
        $this->authorizationBuilder = $this->getMockBuilder('JimLind\Pie7o\AuthorizationBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->guzzleClient = $this->getMock('GuzzleHttp\ClientInterface');

        $this->fixture = new TwitterApiCaller($this->authorizationBuilder, $this->guzzleClient);
    }

    /**
     * Test sendTwitterRequest called with default post data
     */
    public function testSendTwitterRequestWithDefaultPostData()
    {
        $this->authorizationBuilder->expects($this->once())->method('build')->with('POST', 'https://', []);

        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $this->callFixtureWithTweet($tweet);
    }

    /**
     * Test sendTwitterRequest called with default options
     */
    public function testSendTwitterRequestWithDefaultOptions()
    {
        $uri     = (new Uri())->withScheme('https://');
        $request = (new Request('POST', $uri))
            ->withHeader('Authorization', null);

        $this->guzzleClient->expects($this->once())->method('send')->with($request, []);

        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $this->callFixtureWithTweet($tweet);
    }

    protected function callFixtureWithTweet($tweet)
    {
        $reflection = new ReflectionClass('JimLind\Pie7o\TwitterApiCaller');
        $method = $reflection->getMethod('sendTwitterRequest');
        $method->setAccessible(true);
        $method->invokeArgs($this->fixture, [$tweet]);
    }
}
