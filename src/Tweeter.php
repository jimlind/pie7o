<?php
namespace JimLind\Pie7o;

class Tweeter
{
    protected $statusUpdater = null;
    protected $mediaUploader = null;

    /**
     * @param string[] $settingList
     */
    public function __construct(array $settingList)
    {
        $authorizationBuilder = new AuthorizationBuilder($settingList);

        $this->statusUpdater = new StatusUpdater($authorizationBuilder);
        $this->mediaUploader = new MediaUploader($authorizationBuilder);
    }

    /**
     *
     * @param Tweet $tweet
     * @return GuzzleHttp\Psr7\Response
     */
    public function tweet(Tweet $tweet)
    {
        $uploadSuccess = $this->mediaUploader->upload($tweet);

        $updateResponse = $this->statusUpdater->update($tweet);

        echo $updateResponse->getStatusCode();
    }

}
