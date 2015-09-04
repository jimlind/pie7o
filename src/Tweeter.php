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
     */
    public function tweet(Tweet $tweet)
    {
        if ($tweet->getMedia() instanceof StreamInterface) {
            $tweet = $this->mediaUploader->upload($tweet);
        }

        $this->statusUpdater->update($tweet);
    }
}
