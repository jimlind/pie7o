<?php

namespace JimLind\Pie7o;

/**
 * Build an Oauth 1 string for the Twitter API.
 */
class AuthorizationBuilder
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $accessTokenSecret;

    /**
     * @var string
     */
    protected $consumerKey;

    /**
     * @var string
     */
    protected $consumerSecret;

    /**
     * @param array $settingList
     *
     * @throws Pie7oException
     */
    public function __construct(array $settingList)
    {
        $settingsIntact = isset(
            $settingList['accessToken'],
            $settingList['accessTokenSecret'],
            $settingList['consumerKey'],
            $settingList['consumerSecret']
        );

        if (false === $settingsIntact) {
            throw new Pie7oException('Missing a setting for authorization.');
        }

        $this->accessToken = $settingList['accessToken'];
        $this->accessTokenSecret = $settingList['accessTokenSecret'];
        $this->consumerKey = $settingList['consumerKey'];
        $this->consumerSecret = $settingList['consumerSecret'];
    }

    /**
     * Create a complete OAuth authorization string.
     *
     * @param string $method
     * @param string $uri
     * @param array  $postData
     *
     * @return string
     */
    public function build($method, $uri, array $postData)
    {
        $rawAuthDataList = $this->buildValueList($method, $uri, $postData);
        $encodedAuthDataList = array_map('rawurlencode', $rawAuthDataList);

        $authStringList = [];
        foreach ($encodedAuthDataList as $key => $value) {
            $authStringList[] = $key.'="'.$value.'"';
        }

        return 'OAuth '.implode($authStringList, ', ');
    }

    /**
     * Create a list of values needed for authentication.
     *
     * @param string $method
     * @param string $uri
     * @param array  $postData
     *
     * @return array
     */
    protected function buildValueList($method, $uri, array $postData)
    {
        $queryData = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->accessToken,
            'oauth_version' => '1.0',
        ];

        $message = $this->buildMessage($queryData, $method, $uri, $postData);
        $key = $this->buildKey();

        $queryData['oauth_signature'] = base64_encode(hash_hmac('sha1', $message, $key, true));

        return $queryData;
    }

    /**
     * Create an escaped string with the message getting encoded.
     *
     * @param array  $queryData
     * @param string $method
     * @param string $uri
     * @param array  $postData
     *
     * @return string
     */
    protected function buildMessage(array $queryData, $method, $uri, array $postData)
    {
        $queryData += $postData;
        ksort($queryData);

        $queryString = http_build_query($queryData, '', '&', PHP_QUERY_RFC3986);
        $valueList = [$method, $uri, $queryString];
        $encodedList = array_map('rawurlencode', $valueList);

        return implode('&', $encodedList);
    }

    /**
     * Create an escaped string with the keys used for encoding.
     *
     * @return string
     */
    protected function buildKey()
    {
        $valueList = [$this->consumerSecret, $this->accessTokenSecret];
        $encodedList = array_map('rawurlencode', $valueList);

        return implode('&', $encodedList);
    }
}
