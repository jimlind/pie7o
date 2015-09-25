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
    protected $apiPath = '/1.1/media/upload.json';

    /**
     * Upload media via Twitter API and update Tweet
     *
     * @param Tweet $tweet
     *
     * @return Tweet
     *
     * @throws Pie7oException
     */
    public function upload(Tweet $tweet)
    {
        $response = $this->sendTwitterRequest($tweet);

        if (200 !== $response->getStatusCode()) {
            throw new Pie7oException('Could Not Upload Media: `'.$response->getBody().'`');
        }

        return $this->handleResponse($response, $tweet);
    }

    /**
     * Create options for a multipart binary file upload
     *
     * @param Tweet $tweet
     *
     * @return array
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
     * Parse Guzzle response and add media data to return Tweet
     *
     * @param Response $response
     * @param Tweet $tweet
     *
     * @return Tweet
     */
    protected function handleResponse(Response $response, Tweet $tweet)
    {
        $bodyString = $response->getBody();
        $bodyJson   = json_decode($bodyString);

        return $tweet->withMediaId($bodyJson->{'media_id'});
    }
}
