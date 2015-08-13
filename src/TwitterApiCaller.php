<?php

namespace JimLind\Pie7o;

use GuzzleHttp\Psr7\Request;
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
