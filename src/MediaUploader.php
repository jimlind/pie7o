<?php
namespace JimLind\Pie7o;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

/**
 * Upload media with the Twitter API
 */
class MediaUploader
{
    const METHOD      = 'POST';
    const SCHEME      = 'https';
    const HOST        = 'upload.twitter.com';
    const UPLOAD_PATH = '1.1/media/upload.json';

    protected $authorizationBuilder = null;

    /**
     * @param AuthorizationBuilder $authorizationBuilder
     */
    public function __construct(AuthorizationBuilder $authorizationBuilder)
    {
        $this->authorizationBuilder = $authorizationBuilder;
    }

    /**
     * @param Tweet $tweet
     * @return GuzzleHttp\Psr7\Response
     */
    public function upload(Tweet $tweet)
    {
        $originalUri = new Uri();
        $updatedUri  = $originalUri
            ->withScheme($this::SCHEME)
            ->withHost($this::HOST)
            ->withPath($this::UPLOAD_PATH);

        $mediaStream     = $tweet->getMedia();
        $postData        = ['media_data' => base64_encode($mediaStream->getContents())];
        $authorization   = $this->authorizationBuilder->build($this::METHOD, (string) $updatedUri, $postData);
        $originalRequest = new Request($this::METHOD, $updatedUri);
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
