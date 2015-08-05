<?php
namespace JimLind\Pie7o;

class AuthorizationBuilder
{
    /**
     * @var string
     */
    protected $oathToken = null;

    /**
     * @var string
     */
    protected $oathTokenSecret = null;

    /**
     * @var string
     */
    protected $consumerKey = null;

    /**
     * @var string
     */
    protected $consumerSecret = null;

    /**
     * @param array $settingList
     * @throws Exception
     */
    public function __construct(array $settingList)
    {
        $allSettingsAvailalable = isset(
            $settingList['oauthToken'],
            $settingList['oauthTokenSecret'],
            $settingList['consumerKey'],
            $settingList['consumerSecret']
        );

        if (false === $allSettingsAvailalable) {
            throw new \Exception('Missing a setting for authorization.');
        }

        $this->oathToken       = $settingList['oauthToken'];
        $this->oathTokenSecret = $settingList['oauthTokenSecret'];
        $this->consumerKey     = $settingList['consumerKey'];
        $this->consumerSecret  = $settingList['consumerSecret'];
    }

    public function build($method, $uri, array $postData)
    {
        $rawAuthDataList     = $this->buildValueList($method, $uri, $postData);
        $encodedAuthDataList = array_map('rawurlencode', $rawAuthDataList);

        $authStringList = [];
        foreach ($encodedAuthDataList as $key => $value) {
            $authStringList[] = $key.'="'.$value.'"';
        }

        return 'OAuth '.implode($authStringList, ', ');
    }

    protected function buildValueList($method, $uri, array $postData)
    {
        $queryData = [
            'oauth_consumer_key'     => $this->consumerKey,
            'oauth_nonce'            => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_token'            => $this->oathToken,
            'oauth_version'          => '1.0',
        ];

        $message = $this->buildMessage($queryData, $method, $uri, $postData);
        $key     = $this->buildKey();

        $queryData['oauth_signature'] = base64_encode(hash_hmac('sha1', $message, $key, true));

        return $queryData;
    }

    protected function buildMessage($queryData, $method, $uri, array $postData)
    {
        $queryData += $postData;
        ksort($queryData);

        $queryString = http_build_query($queryData, '', '&', PHP_QUERY_RFC3986);
        $valueList   = [$method, $uri, $queryString];
        $encodedList = array_map('rawurlencode', $valueList);

        return implode('&', $encodedList);
    }

    protected function buildKey()
    {
        $valueList     = [$this->consumerSecret, $this->oathTokenSecret];
        $encodedList = array_map('rawurlencode', $valueList);

        return implode('&', $encodedList);
    }
}
