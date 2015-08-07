<?php
namespace JimLind\Pie7o;

use Psr\Http\Message\StreamInterface;

class Tweeter
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
     * @param StatusUpdater $statusUpdater
     * @param MediaUploader $mediaUploader
     */
    public function __construct(StatusUpdater $statusUpdater, MediaUploader $mediaUploader)
    {
        $this->statusUpdater = $statusUpdater;
        $this->mediaUploader = $mediaUploader;
    }

    /**
     *
     * @param Tweet $tweet
     * @return boolean
     */
    public function tweet(Tweet $tweet)
    {
        if ($tweet->getMedia() instanceof StreamInterface) {
            $uploadSuccess = $this->mediaUploader->upload($tweet);
            if (false === $uploadSuccess) {
                return false;
            }
        }

        $updateResponse = $this->statusUpdater->update($tweet);
        return (200 === $updateResponse->getStatusCode());
    }

}
