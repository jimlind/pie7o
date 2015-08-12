<?php

namespace JimLind\Pie7o\Tests;

use JimLind\Pie7o\Tweet;

class TweetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Tweet
     */
    protected $fixture = null;

    protected function setUp()
    {
        $this->fixture = new Tweet();
    }

    public function testEmptyTweet()
    {
        $this->assertNull($this->fixture->getMessage());
        $this->assertNull($this->fixture->getMedia());
        $this->assertSame(0, $this->fixture->getMediaId());
    }

    public function testMessageSetGet()
    {
        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $this->fixture->setMessage($stream);
        $this->assertSame($stream, $this->fixture->getMessage());

        // Make sure other properties are not affected
        $this->assertNull($this->fixture->getMedia());
        $this->assertSame(0, $this->fixture->getMediaId());
    }

    public function testMediaSetGet()
    {
        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $this->fixture->setMedia($stream);
        $this->assertSame($stream, $this->fixture->getMedia());

        // Make sure other properties are not affected
        $this->assertNull($this->fixture->getMessage());
        $this->assertSame(0, $this->fixture->getMediaId());
    }

    public function testMediaIdSetGet()
    {
        $mediaId     = rand();
        $mediaString = (string) $mediaId;

        $this->fixture->setMediaId($mediaString);
        $this->assertSame($mediaId, $this->fixture->getMediaId());

        // Make sure other properties are not affected
        $this->assertNull($this->fixture->getMessage());
        $this->assertNull($this->fixture->getMedia());
    }
}
