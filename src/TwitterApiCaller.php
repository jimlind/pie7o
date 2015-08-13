<?php

namespace JimLind\Pie7o;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;

/**
 * Communicate with the Twitter API
 */
class TwitterApiCaller {

    /**
     * @var string
     */
    protected $apiMethod = 'POST';

    /**
     * @var string
     */
    protected $apiScheme = 'https';

    /**
     * @var string
     */
    protected $apiHost = '';

    /**
     * @var string
     */
    protected $apiPath = '';

    /**
     * @var AuthorizationBuilder
     */
    protected $authorizationBuilder = null;

    /**
     * @param AuthorizationBuilder $authorizationBuilder
     */
    public function __construct(AuthorizationBuilder $authorizationBuilder)
    {
        $this->authorizationBuilder = $authorizationBuilder;
    }

    /**
     *
     * @param Tweet $tweet
     * @return Response
     */
    protected function sendTwitterRequest($tweet)
    {
        $client = new Client();

        $postData = $this->getPostData($tweet);
        $request  = $this->buildRequest($postData);
        $options  = $this->getOptions($tweet);

        return $client->send($request, $options);
    }

    /**
     *
     * @param Tweet $tweet
     * @return mixed[]
     */
    protected function getPostData(Tweet $tweet)
    {
        return [];
    }

    /**
     *
     * @param Tweet $tweet
     * @return mixed[]
     */
    protected function getOptions(Tweet $tweet)
    {
        return [];
    }

    /**
     *
     * @param array $postData
     * @return type
     */
    protected function buildRequest(array $postData)
    {
        $uri = $this->buildURI();

        $authorization   = $this->authorizationBuilder->build($this->apiMethod, (string) $uri, $postData);
        $originalRequest = new Request($this->apiMethod, $uri);
        return $originalRequest->withHeader('Authorization', $authorization);
    }

    /**
     *
     * @return Uri
     */
    protected function buildURI()
    {
        $uri = new Uri();
        return $uri->withScheme($this->apiScheme)
            ->withHost($this->apiHost)
            ->withPath($this->apiPath);
    }
}
