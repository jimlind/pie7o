<?php

namespace JimLind\Pie7o;

/**
 * Update a status with the Twitter API.
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
    protected $apiPath = '/1.1/statuses/update.json';

    /**
     * Attempt to update status and throw error if unsuccessful.
     *
     * @param Tweet $tweet
     *
     * @throws Pie7oException
     */
    public function update(Tweet $tweet)
    {
        $response = $this->sendTwitterRequest($tweet);

        if (200 !== $response->getStatusCode()) {
            throw new Pie7oException('Could Not Update Status: `'.$response->getBody().'`');
        }
    }

    /**
     * Create options for a form submission.
     *
     * @param Tweet $tweet
     *
     * @return array
     */
    protected function getOptions(Tweet $tweet)
    {
        $postData = $this->getPostData($tweet);

        return ['form_params' => $postData];
    }

    /**
     * Get relevant properly sized data for using the Twitter API.
     *
     * @param Tweet $tweet
     *
     * @return array
     */
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
