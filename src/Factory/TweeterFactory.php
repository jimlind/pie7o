<?php

namespace JimLind\Pie7o\Factory;

use GuzzleHttp\Client;
use JimLind\Pie7o\AuthorizationBuilder;
use JimLind\Pie7o\MediaUploader;
use JimLind\Pie7o\StatusUpdater;
use JimLind\Pie7o\Tweeter;

/**
 * Factory for building a Tweeter.
 */
class TweeterFactory
{
    /**
     * Build a Tweeter.
     *
     * @param array $settingList
     *
     * @return Tweeter
     */
    public static function buildTweeter(array $settingList)
    {
        $authorizationBuilder = new AuthorizationBuilder($settingList);
        $guzzleClient = new Client();

        $statusUpdater = new StatusUpdater($authorizationBuilder, $guzzleClient);
        $mediaUploader = new MediaUploader($authorizationBuilder, $guzzleClient);

        return new Tweeter($statusUpdater, $mediaUploader);
    }
}
