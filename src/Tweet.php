<?php

namespace JimLind\Pie7o;

use Psr\Http\Message\StreamInterface;

/**
 * Immutable data object for all Tweet information.
 */
class Tweet
{
    /**
     * @var StreamInterface
     */
    protected $messageStream;

    /**
     * @var StreamInterface
     */
    protected $mediaStream;

    /**
     * @var int
     */
    protected $mediaId = 0;

    /**
     * Return a Tweet with added message stream.
     *
     * @param StreamInterface $messageStream
     *
     * @return Tweet
     */
    public function withMessage(StreamInterface $messageStream)
    {
        $new = clone $this;
        $new->messageStream = $messageStream;

        return $new;
    }

    /**
     * Get the message stream.
     *
     * @return StreamInterface
     */
    public function getMessage()
    {
        return $this->messageStream;
    }

    /**
     * Return a Tweet with added media stream.
     *
     * @param StreamInterface $mediaStream
     *
     * @return Tweet
     */
    public function withMedia(StreamInterface $mediaStream)
    {
        $new = clone $this;
        $new->mediaStream = $mediaStream;

        return $new;
    }

    /**
     * Get the media stream.
     *
     * @return StreamInterface
     */
    public function getMedia()
    {
        return $this->mediaStream;
    }

    /**
     * Return a Tweet with added media id.
     *
     * @param int $mediaId
     *
     * @return Tweet
     */
    public function withMediaId($mediaId)
    {
        $new = clone $this;
        $new->mediaId = intval($mediaId);

        return $new;
    }

    /**
     * Get the media id.
     *
     * @return int
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }
}
