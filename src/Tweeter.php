<?php
namespace JimLind\Pie7o;

class Tweeter
{
    /**
     *
     * @var StatusUpdater
     */
    protected $statusUpdater = null;
    /**
     *
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
     * @return GuzzleHttp\Psr7\Response
     */
    public function tweet(Tweet $tweet)
    {
        $uploadSuccess = $this->mediaUploader->upload($tweet);
        var_dump($uploadSuccess);

        $updateResponse = $this->statusUpdater->update($tweet);

        var_dump($updateResponse->getStatusCode());
    }

}
