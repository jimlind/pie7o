<?php
namespace JimLind\Pie7o;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

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
     * @return GuzzleHttp\Psr7\Response
     */
    public function update(Tweet $tweet)
    {
        $originalUri = new Uri();
        $updatedUri  = $originalUri
            ->withScheme($this::SCHEME)
            ->withHost($this::HOST)
            ->withPath($this::UPDATE_PATH);

        $postData = [
            'status' => $tweet->getMessage()->getContents(),
            'media_ids' => $tweet->getMediaId(),
        ];

        $authorization   = $this->authorizationBuilder->build($this::METHOD, (string) $updatedUri, $postData);
        $originalRequest = new Request($this::METHOD, $updatedUri);
        $updatedRequest  = $originalRequest->withHeader('Authorization', $authorization);

        $client   = new Client();
        $options  = ['form_params' => $postData];
        try {
            $response = $client->send($updatedRequest, $options);
        } catch(ClientException $exception) {
            $response = $exception->getResponse();
        }

        return $response;
    }

}
