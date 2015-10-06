<?php

namespace JimLind\Pie7o\Tests;

use JimLind\Pie7o\MediaUploader;
use JimLind\Pie7o\StatusUpdater;
use JimLind\Pie7o\Tweeter;
use PHPUnit_Framework_TestCase;

/**
 * Test the JimLind\Pie7o\Tweeter class
 */
class TweeterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var StatusUpdater
     */
    protected $statusUpdater = null;

    /**
     * @var MediaUploader
     */
    protected $mediaUploader = null;

    /**
     * @var Tweeter
     */
    protected $fixture = null;

    protected function setUp()
    {
        $this->statusUpdater = $this->getMockBuilder('JimLind\Pie7o\StatusUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mediaUploader = $this->getMockBuilder('JimLind\Pie7o\MediaUploader')
            ->disableOriginalConstructor()
            ->getMock();

        $this->fixture = new Tweeter($this->statusUpdater, $this->mediaUploader);
    }

    /**
     * Test a Tweet with no media
     */
    public function testTweeterWithNoMedia()
    {
        $tweet = $this->getMock('JimLind\Pie7o\Tweet');
        $this->statusUpdater->expects($this->once())->method('update')->with($tweet);
        $this->mediaUploader->expects($this->never())->method('upload');

        $this->fixture->tweet($tweet);
    }

    /**
     * Test a Tweet with media
     */
    public function testTweeterWithMedia()
    {
        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $tweet  = $this->getMock('JimLind\Pie7o\Tweet');
        $tweet->method('getMedia')->willReturn($stream);

        $taggedTweet = $this->getMock('JimLind\Pie7o\Tweet');

        $this->mediaUploader->expects($this->once())->method('upload')->with($tweet)->willReturn($taggedTweet);
        $this->statusUpdater->expects($this->once())->method('update')->with($taggedTweet);

        $this->fixture->tweet($tweet);
    }
}
