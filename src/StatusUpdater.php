<?php
namespace JimLind\Pie7o;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

/**
 * Update a status with the Twitter API
 */
class StatusUpdater
{
    const METHOD      = 'POST';
    const SCHEME      = 'https';
    const HOST        = 'api.twitter.com';
    const UPDATE_PATH = '1.1/statuses/update.json';

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
    public function update(Tweet $tweet)
    {
        $originalUri = new Uri();
        $updatedUri  = $originalUri
            ->withScheme($this::SCHEME)
            ->withHost($this::HOST)
            ->withPath($this::UPDATE_PATH);

        $postData = $this->getPostData($tweet);

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

        return $response;
    }

    private function getPostData(Tweet $tweet)
    {
        $status = $tweet->getMessage()->getContents();

        if (0 === $tweet->getMediaId()) {
            return [
                'status' => substr($status, 0, 140),
            ];
        }

        return [
            'status' => substr($status, 0, 110),
            'media_ids' => $tweet->getMediaId(),
        ];

    }
}
