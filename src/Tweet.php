<?php

namespace JimLind\Pie7o;

use Psr\Http\Message\StreamInterface;

/**
 * Immutable data object for all Tweet information
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
     * @return StreamInterface
     */
    public function getMessage()
    {
        return $this->messageStream;
    }

    /**
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
     * @return StreamInterface
     */
    public function getMedia()
    {
        return $this->mediaStream;
    }

    /**
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
     * @return int
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }
}
