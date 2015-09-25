<?php
namespace JimLind\Pie7o;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;

/**
 * Communicate with the Twitter API
 */
class TwitterApiCaller
{
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
    protected $authorizationBuilder;

    /**
     * @var ClientInterface
     */
    protected $guzzleClient;

    /**
     * @param AuthorizationBuilder $authorizationBuilder
     * @param ClientInterface      $guzzleClient
     */
    public function __construct(AuthorizationBuilder $authorizationBuilder, ClientInterface $guzzleClient)
    {
        $this->authorizationBuilder = $authorizationBuilder;
        $this->guzzleClient         = $guzzleClient;
    }

    /**
     * Send the API request and ensure a Guzzle Response is returned
     *
     * @param Tweet $tweet
     *
     * @return Response
     */
    protected function sendTwitterRequest(Tweet $tweet)
    {
        $postData = $this->getPostData($tweet);
        $request  = $this->buildRequest($postData);
        $options  = $this->getOptions($tweet);

        try {
            $response = $this->guzzleClient->send($request, $options);
        } catch (BadResponseException $requestException) {
            $response = $requestException->getResponse();
        } catch (Exception $exception) {
            $response = new Response(0, [], $exception->getMessage());
        }

        return $response;
    }

    /**
     * Default empty array can be overwritten
     *
     * @return array
     */
    protected function getPostData()
    {
        return [];
    }

    /**
     * Default empty array can be overwritten
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Build a Guzzle Request with an authorization header
     *
     * @param array $postData
     *
     * @return Request
     */
    protected function buildRequest(array $postData)
    {
        $uri = $this->buildURI();

        $authorization   = $this->authorizationBuilder->build($this->apiMethod, (string) $uri, $postData);
        $originalRequest = new Request($this->apiMethod, $uri);

        return $originalRequest->withHeader('Authorization', $authorization);
    }

    /**
     * Build a URI for the Twitter API
     *
     * @return Uri
     */
    protected function buildURI()
    {
        return (new Uri())
            ->withScheme($this->apiScheme)
            ->withHost($this->apiHost)
            ->withPath($this->apiPath);
    }
}
