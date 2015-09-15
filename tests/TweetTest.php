<?php

namespace JimLind\Pie7o\Tests;

use JimLind\Pie7o\Tweet;
use PHPUnit_Framework_TestCase;

/**
 * Test the JimLind\Pie7o\Tweet class
 */
class TweetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tweet
     */
    protected $fixture = null;

    protected function setUp()
    {
        $this->fixture = new Tweet();
    }

    /**
     * Test an empty Tweet has not data
     */
    public function testEmptyTweet()
    {
        $this->assertNull($this->fixture->getMessage());
        $this->assertNull($this->fixture->getMedia());
        $this->assertSame(0, $this->fixture->getMediaId());
    }

    /**
     * Test giving a Tweet a message stream allows you to get it
     */
    public function testMessageSetGet()
    {
        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $output = $this->fixture->withMessage($stream);

        $this->assertSame($stream, $output->getMessage());

        // Make sure other properties are not affected
        $this->assertNull($output->getMedia());
        $this->assertSame(0, $output->getMediaId());

        $this->assertNull($this->fixture->getMessage());
        $this->assertNull($this->fixture->getMedia());
        $this->assertSame(0, $this->fixture->getMediaId());
    }

    /**
     * Test giving a Tweet a media stream allows you to get it
     */
    public function testMediaSetGet()
    {
        $stream = $this->getMock('Psr\Http\Message\StreamInterface');
        $output = $this->fixture->withMedia($stream);

        $this->assertSame($stream, $output->getMedia());

        // Make sure other properties are not affected
        $this->assertNull($output->getMessage());
        $this->assertSame(0, $output->getMediaId());

        $this->assertNull($this->fixture->getMedia());
        $this->assertNull($this->fixture->getMessage());
        $this->assertSame(0, $this->fixture->getMediaId());
    }

    /**
     * Test giving a Tweet a media id allows you to get it
     */
    public function testMediaIdSetGet()
    {
        $mediaId     = rand();
        $mediaString = (string) $mediaId;
        $output      = $this->fixture->withMediaId($mediaString);

        $this->assertSame($mediaId, $output->getMediaId());

        // Make sure other properties are not affected
        $this->assertNull($output->getMedia());
        $this->assertNull($output->getMessage());

        $this->assertSame(0, $this->fixture->getMediaId());
        $this->assertNull($this->fixture->getMessage());
        $this->assertNull($this->fixture->getMedia());
    }
}
