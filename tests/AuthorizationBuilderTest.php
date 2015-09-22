<?php

namespace JimLind\Pie7o\Tests;

use JimLind\Pie7o\AuthorizationBuilder;
use phpmock\phpunit\PHPMock;

/**
 * Test the JimLind\Pie7o\AuthorizationBuilder class
 */
class AuthorizationBuilderTest extends \PHPUnit_Framework_TestCase
{
    use PHPMock;

    /**
     * Test a variety of input data that is insufficiant
     *
     * @expectedException        JimLind\Pie7o\Pie7oException
     * @expectedExceptionMessage Missing a setting for authorization.
     *
     * @dataProvider authorizationBuilderConstructExceptionProvider
     *
     * @param array $settingList
     */
    public function testAuthorizationBuilderConstructException(array $settingList)
    {
        new AuthorizationBuilder($settingList);
    }

    /**
     * Data provider of insufficiant AuthorizationBuilder input
     *
     * @return array
     */
    public function authorizationBuilderConstructExceptionProvider()
    {
        $empty  = [];
        $aToken  = ['accessToken' => uniqid()];
        $aSecret = ['accessTokenSecret' => uniqid()];
        $cKey    = ['consumerKey' => uniqid()];
        $cSecret = ['consumerSecret' => uniqid()];

        return [
            [$empty],
            [$aSecret + $cKey + $cSecret],
            [$aToken + $cKey + $cSecret],
            [$aToken + $aSecret + $cSecret],
            [$aToken + $aSecret + $cKey],
        ];
    }

    /**
     * Test that the output of the AuthorizationBuilder is what we expect
     *
     * @dataProvider authorizationBuilderProvider
     *
     * @param int    $time
     * @param string $expected
     */
    public function testAuthorizationBuilder($time, $expected)
    {
        $mockTime = $this->getFunctionMock('JimLind\Pie7o', 'time');
        $mockTime->expects($this->any())->willReturn($time);

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

    /**
     * Data provider of AuthorizationBuilder output test
     *
     * @return array
     */
    public function authorizationBuilderProvider()
    {
        return [
            [1439380800, $this->createMockResponse(1439380800, 'D4eDlgNPbgHBS7EN%2Fa7TlJQblEE%3D')],
            [1439467200, $this->createMockResponse(1439467200, 'dqZeuyisJ3w4O1OYyGw7VquMj08%3D')],
        ];
    }

    /**
     * Fill static pieces of authorization string with some input
     *
     * @param int    $time
     * @param string $signature
     * @return string
     */
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
