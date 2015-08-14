<?php
namespace JimLind\Pie7o;

use GuzzleHttp\Psr7\Response;

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
     * 
     * @param Tweet $tweet
     * @return Response
     */
    public function upload(Tweet $tweet)
    {
        $response = $this->sendTwitterRequest($tweet);

        if (200 === $response->getStatusCode()) {
            $this->handleResponse($response, $tweet);
        }

        return $response;
    }

    /**
     *
     * @param Tweet $tweet
     * @return mixed[]
     */
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

    /**
     *
     * @param Response $response
     * @param Tweet $tweet
     */
    protected function handleResponse(Response $response, Tweet $tweet)
    {
        $bodyString = $response->getBody();
        $bodyJson   = json_decode($bodyString);

        $tweet->setMediaId($bodyJson->{'media_id'});
    }
}
