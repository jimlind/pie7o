<?php

namespace JimLind\Pie7o\Tests;

use JimLind\Pie7o\TweetFactory;
use phpmock\spy\Spy;

/**
 * Test the JimLind\Pie7o\TweetFactory class
 */
class TweetFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test contents after building an empty Tweet
     */
    public function testEmptyBuildTweet()
    {
        $tweet = TweetFactory::buildTweet();

        $messageStream = $tweet->getMessage();
        $mediaStream   = $tweet->getMedia();

        $this->assertSame('', $messageStream->getContents());
        $this->assertNull($mediaStream);
    }

    /**
     * Test contents after building a Tweet with just a message
     */
    public function testBuildTweetWithMessage()
    {
        $message = uniqid();
        $tweet   = TweetFactory::buildTweet($message);

        $messageStream = $tweet->getMessage();
        $mediaStream   = $tweet->getMedia();

        $this->assertSame($message, $messageStream->getContents());
        $this->assertNull($mediaStream);
    }

    /**
     * Test building with a file that doesn't exist throws exception
     *
     * @return null
     */
    public function testBuildTweetWithBadFile()
    {
        $filePath = uniqid();
        $this->setExpectedException(
            'JimLind\Pie7o\Pie7oException',
            'File Does Not Exist: `'.$filePath.'`'
        );

        $returnFalse = function () {
            return false;
        };

        $fileExistsSpy = new Spy('JimLind\Pie7o', 'file_exists', $returnFalse);
        $fileExistsSpy->enable();

        TweetFactory::buildTweet('', $filePath);
    }

    /**
     * Test building with a file that does exist
     *
     * @return null
     */
    public function testBuildTweetWithGoodFile()
    {
        $returnTrue = function () {
            return true;
        };

        $filePath = uniqid();

        $fileExistsSpy = new Spy('JimLind\Pie7o', 'file_exists', $returnTrue);
        $fileExistsSpy->enable();

        $fileOpenSpy = new Spy('JimLind\Pie7o', 'fopen', [$this, 'returnFileHandle']);
        $fileOpenSpy->enable();

        $tweet = TweetFactory::buildTweet('', $filePath);
        $mediaStream = $tweet->getMedia();
        $this->assertEquals('fileStart '.$filePath.' fileEnd', $mediaStream->getContents());

        $existsInvocationList = $fileExistsSpy->getInvocations();
        $existsArgumentList   = $existsInvocationList[0]->getArguments();
        $this->assertEquals($filePath, $existsArgumentList[0]);

        $openInvocationList = $fileOpenSpy->getInvocations();
        $openArgumentList   = $openInvocationList[0]->getArguments();
        $this->assertEquals($filePath, $openArgumentList[0]);
    }

    /**
     * Create a resource from input with strings prepended and appended
     *
     * @param string $input
     * @return resource
     */
    public function returnFileHandle($input)
    {
        $handler =  fopen('php://temp', 'r+');
        fputs($handler, 'fileStart '.$input.' fileEnd');
        rewind($handler);

        return $handler;
    }

    /**
     * Disable built-in mocks
     */
    protected function tearDown()
    {
        $fileExistsSpy = new Spy('JimLind\Pie7o', 'file_exists');
        $fileExistsSpy->disable();

        $fileOpenSpy = new Spy('JimLind\Pie7o', 'fopen');
        $fileOpenSpy->disable();
    }
}
