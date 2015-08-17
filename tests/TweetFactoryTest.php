<?php

namespace JimLind\Pie7o\Tests;

use JimLind\Pie7o\TweetFactory;
use phpmock\spy\Spy;

class TweetFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyBuildTweet()
    {
        $tweet = TweetFactory::buildTweet();

        $messageStream = $tweet->getMessage();
        $mediaStream   = $tweet->getMedia();

        $this->assertSame('', $messageStream->getContents());
        $this->assertNull($mediaStream);
    }

    public function testBuildTweetWithMessage()
    {
        $message = (string) rand();
        $tweet   = TweetFactory::buildTweet($message);

        $messageStream = $tweet->getMessage();
        $mediaStream   = $tweet->getMedia();

        $this->assertSame($message, $messageStream->getContents());
        $this->assertNull($mediaStream);
    }

    public function testBuildTweetWithoutFile()
    {
        $filePath = (string) rand();

        $fileExistsSpy = new Spy('JimLind\Pie7o', 'file_exists', function(){return false;});
        $fileExistsSpy->enable();

        $tweet = TweetFactory::buildTweet('', $filePath);
        $mediaStream = $tweet->getMedia();
        $this->assertNull($mediaStream);

        $existsInvocationList = $fileExistsSpy->getInvocations();
        $existsArgumentList   = $existsInvocationList[0]->getArguments();
        $this->assertEquals($filePath, $existsArgumentList[0]);

        $fileExistsSpy->disable();
    }

    public function testBuildTweetWithFile()
    {
        $filePath = (string) rand();

        $fileExistsSpy = new Spy('JimLind\Pie7o', 'file_exists', function(){return true;});
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

        $fileExistsSpy->disable();
        $fileOpenSpy->disable();
    }

    public function returnFileHandle($input)
    {
        $handler =  fopen('php://temp', 'r+');
        fputs($handler, 'fileStart '.$input.' fileEnd');
        rewind($handler);

        return $handler;
    }
}