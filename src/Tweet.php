<?php
namespace JimLind\Pie7o;

use Psr\Http\Message\StreamInterface;

class Tweet
{
    protected $text;

    protected $imageStream;

    protected $mediaId;

    public function setText($inputText)
    {
        $this->text = $inputText;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setImage(StreamInterface $stream)
    {
        $this->imageStream = $stream;
    }

    public function getImage()
    {
        return $this->imageStream;
    }

    public function setMediaId($mediaId)
    {
        $this->mediaId = intval($mediaId);
    }

    public function getMediaId()
    {
        return $this->mediaId;
    }
}