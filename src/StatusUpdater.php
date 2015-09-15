<?php
namespace JimLind\Pie7o;

/**
 * Update a status with the Twitter API
 */
class StatusUpdater extends TwitterApiCaller
{
    /**
     * @var string
     */
    protected $apiHost = 'api.twitter.com';

    /**
     * @var string
     */
    protected $apiPath = '1.1/statuses/update.json';

    /**
     * @param Tweet $tweet
     * @return GuzzleHttp\Psr7\Response
     */
    public function update(Tweet $tweet)
    {
        $response = $this->sendTwitterRequest($tweet);

        if (200 !== $response->getStatusCode()) {
            throw new Exception('Could Not Update Status: `'.$response->getBody().'`');
        }
    }

    protected function getOptions(Tweet $tweet)
    {
        $postData = $this->getPostData($tweet);

        return ['form_params' => $postData];
    }

    protected function getPostData(Tweet $tweet)
    {
        $message = $tweet->getMessage();
        $message->rewind();

        $status = $message->getContents();

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
