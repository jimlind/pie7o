<?php
namespace JimLind\Pie7o;

use Psr\Http\Message\StreamInterface;

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
     */
    public function setMessage(StreamInterface $messageStream)
    {
        $this->messageStream = $messageStream;
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
     */
    public function setMedia(StreamInterface $mediaStream)
    {
        $this->mediaStream = $mediaStream;
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
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = intval($mediaId);
    }

    /**
     * @return int
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }
}