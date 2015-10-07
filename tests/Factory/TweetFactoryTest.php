<?php

namespace JimLind\Pie7o\Factory\Tests;

use JimLind\Pie7o\Factory\TweetFactory;
use org\bovigo\vfs\vfsStream;

/**
 * Test the JimLind\Pie7o\Factory\TweetFactory class
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
     */
    public function testBuildTweetWithBadFile()
    {
        vfsStream::setup();

        $filePath = vfsStream::url(uniqid());
        $this->setExpectedException(
            'JimLind\Pie7o\Pie7oException',
            'File Does Not Exist: `'.$filePath.'`'
        );

        TweetFactory::buildTweet('', $filePath);
    }

    /**
     * Test building with a file that does exist
     */
    public function testBuildTweetWithGoodFile()
    {
        $filePath     = uniqid();
        $fileContents = uniqid();

        $root = vfsStream::setup('dir');
        vfsStream::newFile($filePath)->at($root)->setContent($fileContents);

        $tweet       = TweetFactory::buildTweet('', vfsStream::url('dir/'.$filePath));
        $mediaStream = $tweet->getMedia();
        $this->assertEquals($fileContents, $mediaStream->getContents());
    }
}
