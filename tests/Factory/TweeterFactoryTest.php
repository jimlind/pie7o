<?php

namespace JimLind\Pie7o\Factory\Tests;

use JimLind\Pie7o\Factory\TweeterFactory;

/**
 * Test the JimLind\Pie7o\Factory\TweeterFactory class
 */
class TweeterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Not really anything testable here. It's mostly configuration.
     */
    public function testBuildTweeter()
    {
        $settings = [
            'accessToken' => uniqid(),
            'accessTokenSecret' => uniqid(),
            'consumerKey' => uniqid(),
            'consumerSecret' => uniqid(),
        ];

        $fixture = TweeterFactory::buildTweeter($settings);

        $this->assertInstanceOf('JimLind\Pie7o\Tweeter', $fixture);
    }
}
