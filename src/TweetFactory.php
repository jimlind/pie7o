<?php

namespace JimLind\Pie7o;

use GuzzleHttp\Psr7\Stream;

class TweetFactory {

    /**
     * @param string $message
     * @param string $mediaPath
     */
    public static function buildTweet($message = '', $mediaPath = null)
    {
        $tweet = new Tweet();

        $messageStream = self::buildMessageStream($message);
        $tweet->setMessage($messageStream);

        if (false === empty($mediaPath) && file_exists($mediaPath)) {
            $mediaStream = self::buildMediaStream($mediaPath);
            $tweet->setMedia($mediaStream);
        }

        return $tweet;
    }

    /**
     *
     * @param string $message
     * @return Stream
     */
    protected function buildMessageStream($message)
    {
        $messageHandle = fopen('php://temp', 'r+');
        $messageStream = new Stream($messageHandle);
        $messageStream->write($message);
        $messageStream->rewind();

        return $messageStream;
    }

    /**
     *
     * @param string $mediaPath
     * @return Stream
     */
    protected function buildMediaStream($mediaPath)
    {
        $mediaHandle = fopen($mediaPath, 'r');
        $mediaStream = new Stream($mediaHandle);
        $mediaStream->rewind();

        return $mediaStream;
    }
}
