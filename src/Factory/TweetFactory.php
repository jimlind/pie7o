<?php

namespace JimLind\Pie7o\Factory;

use GuzzleHttp\Psr7\Stream;
use JimLind\Pie7o\Pie7oException;
use JimLind\Pie7o\Tweet;

/**
 * Factory for building tweet objects.
 */
class TweetFactory
{
    /**
     * Build a Tweet.
     *
     * @param string $message
     * @param string $mediaPath Optional
     *
     * @return Tweet
     */
    public static function buildTweet($message = '', $mediaPath = null)
    {
        $messageStream = self::buildMessageStream($message);

        $tweet = (new Tweet())
            ->withMessage($messageStream);

        if (null !== $mediaPath) {
            $mediaStream = self::buildMediaStream($mediaPath);

            return $tweet->withMedia($mediaStream);
        }

        return $tweet;
    }

    /**
     * Build a message stream from a string.
     *
     * @param string $message
     *
     * @return Stream
     */
    protected static function buildMessageStream($message)
    {
        $messageHandle = fopen('php://memory', 'r+');
        $messageStream = new Stream($messageHandle);
        $messageStream->write($message);
        $messageStream->rewind();

        return $messageStream;
    }

    /**
     * Build a media stream from a filepath.
     *
     * @param string $mediaPath
     *
     * @return Stream
     *
     * @throws Pie7oException
     */
    protected static function buildMediaStream($mediaPath)
    {
        if (false === file_exists($mediaPath)) {
            throw new Pie7oException('File Does Not Exist: `'.$mediaPath.'`');
        }

        $mediaHandle = fopen($mediaPath, 'r');
        $mediaStream = new Stream($mediaHandle);
        $mediaStream->rewind();

        return $mediaStream;
    }
}
