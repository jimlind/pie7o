<?php
namespace JimLind\Pie7o;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * Update a status with the Twitter API
 */
class StatusUpdater extends TwitterApiCaller
{
    /**
     * @param Tweet $tweet
     * @return GuzzleHttp\Psr7\Response
     */
    public function update(Tweet $tweet)
    {
        $uri      = $this->getURI();
        $postData = $this->getPostData($tweet);

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
