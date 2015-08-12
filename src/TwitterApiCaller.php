<?php

namespace JimLind\Pie7o;

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
    protected $apiHost = 'api.twitter.com';

    /**
     * @var string
     */
    protected $apiPath = '1.1/statuses/update.json';

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
     * @return Uri
     */
    protected function getURI()
    {
        $uri = new Uri();
        return $uri->withScheme($this->apiScheme)
            ->withHost($this->apiHost)
            ->withPath($this->apiPath);
    }
}
