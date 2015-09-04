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
        $messageStream = self::buildMessageStream($message);

        $tweet = (new Tweet)->withMessage($messageStream);

        if (null !== $mediaPath) {
            $mediaStream = self::buildMediaStream($mediaPath);
            return $tweet->withMedia($mediaStream);
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
        if (false === file_exists($mediaPath)) {
            throw new Exception('File Does Not Exist: `'.$mediaPath.'`');
        }

        $mediaHandle = fopen($mediaPath, 'r');
        $mediaStream = new Stream($mediaHandle);
        $mediaStream->rewind();

        return $mediaStream;
    }
}
