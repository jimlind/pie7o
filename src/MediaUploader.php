<?php
namespace JimLind\Pie7o;

/**
 * Upload media with the Twitter API
 */
class MediaUploader extends TwitterApiCaller
{
    /**
     * @var string
     */
    protected $apiHost = 'upload.twitter.com';

    /**
     * @var string
     */
    protected $apiPath = '1.1/media/upload.json';

    /**
     * @param Tweet $tweet
     * @return GuzzleHttp\Psr7\Response
     */
    public function upload(Tweet $tweet)
    {
        $response = $this->sendTwitterRequest($tweet);

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        $this->handleMediaId($response, $tweet);

        return true;
    }

    protected function getOptions(Tweet $tweet)
    {
        $mediaStream = $tweet->getMedia();
        $mediaStream->rewind();
        $file = [
            'name' => 'media',
            'contents' => $mediaStream->getContents(),
        ];

        return ['multipart' => [$file]];
    }

    protected function handleMediaId($response, $tweet)
    {
        $bodyString = $response->getBody();
        $bodyJson   = json_decode($bodyString);

        $tweet->setMediaId($bodyJson->{'media_id'});
    }
}
