<?php

namespace JimLind\Pie7o\Tests;

use JimLind\Pie7o\AuthorizationBuilder;

class AuthorizationBuilderTest extends \PHPUnit_Framework_TestCase
{
    // Trait for mocking built-in functions
    use \phpmock\phpunit\PHPMock;

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Missing a setting for authorization.
     *
     * @dataProvider authorizationBuilderConstructExceptionProvider
     */
    public function testAuthorizationBuilderConstructException($settingList)
    {
        new AuthorizationBuilder($settingList);
    }

    public function authorizationBuilderConstructExceptionProvider()
    {
        $empty  = [];
        $aToken  = ['accessToken' => rand()];
        $aSecret = ['accessTokenSecret' => rand()];
        $cKey    = ['consumerKey' => rand()];
        $cSecret = ['consumerSecret' => rand()];

        return [
            [$empty],
            [$aSecret + $cKey + $cSecret],
            [$aToken + $cKey + $cSecret],
            [$aToken + $aSecret + $cSecret],
            [$aToken + $aSecret + $cKey],
        ];
    }

    /**
     * @dataProvider authorizationBuilderProvider
     */
    public function testAuthorizationBuilder($time, $expected)
    {
        $mockTime = $this->getFunctionMock('JimLind\Pie7o', 'time');
        $mockTime->expects($this->exactly(2))->willReturn($time);

        $settingList = [
            'accessToken'       => 'YOUR ACCESS TOKEN',
            'accessTokenSecret' => 'YOUR ACCESS TOKEN SECRET',
            'consumerKey'       => 'YOUR CONSUMER KEY',
            'consumerSecret'    => 'YOUR CONSUMER SECRET',
        ];

        $builder = new AuthorizationBuilder($settingList);

        $method = 'METHOD';
        $uri    = 'URI';
        $post   = ['POST VALUE' => 'POST DATA'];
        $actual = $builder->build($method, $uri, $post);

        $this->assertEquals($expected, $actual);
    }

    public function authorizationBuilderProvider()
    {
        return [
            [1439380800, $this->createMockResponse(1439380800, 'D4eDlgNPbgHBS7EN%2Fa7TlJQblEE%3D')],
            [1439467200, $this->createMockResponse(1439467200, 'dqZeuyisJ3w4O1OYyGw7VquMj08%3D')],
        ];
    }

    protected function createMockResponse($time, $signature)
    {
        $dataCollection = [
            'oauth_consumer_key="YOUR%20CONSUMER%20KEY"',
            'oauth_nonce="'.$time.'"',
            'oauth_signature_method="HMAC-SHA1"',
            'oauth_timestamp="'.$time.'"',
            'oauth_token="YOUR%20ACCESS%20TOKEN"',
            'oauth_version="1.0"',
            'oauth_signature="'.$signature.'"',
        ];

        return 'OAuth '.implode(', ', $dataCollection);
    }
}
