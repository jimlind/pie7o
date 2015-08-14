<?php
namespace JimLind\Pie7o;

use Psr\Http\Message\StreamInterface;

/**
 * Uploads images and updates statuses as required.
 */
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
            $uploadResponse = $this->mediaUploader->upload($tweet);
            if (200 !== $uploadResponse->getStatusCode()) {
                return false;
            }
        }

        $updateResponse = $this->statusUpdater->update($tweet);

        return (200 === $updateResponse->getStatusCode());
    }
}
