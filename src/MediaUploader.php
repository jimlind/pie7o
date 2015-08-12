<?php
namespace JimLind\Pie7o;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

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
        $uri = $this->getURI();

        $mediaStream     = $tweet->getMedia();
        $postData        = ['media_data' => base64_encode($mediaStream->getContents())];
        $authorization   = $this->authorizationBuilder->build($this->apiMethod, (string) $uri, $postData);
        $originalRequest = new Request($this->apiMethod, $uri);
        $updatedRequest  = $originalRequest->withHeader('Authorization', $authorization);

        $client   = new Client();
        $options  = ['form_params' => $postData];
        try {
            $response = $client->send($updatedRequest, $options);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
        }

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        $this->handleMediaId($response, $tweet);

        return true;
    }

    protected function handleMediaId($response, $tweet)
    {
        $bodyString = $response->getBody();
        $bodyJson   = json_decode($bodyString);

        $tweet->setMediaId($bodyJson->{'media_id'});
    }
}
