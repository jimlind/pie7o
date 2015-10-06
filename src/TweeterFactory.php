<?php
namespace JimLind\Pie7o;

use GuzzleHttp\Client;

/**
 * Factory for building a Tweeter
 */
class TweeterFactory
{
    /**
     * Build a Tweeter
     *
     * @param array $settingList
     *
     * @return Tweeter
     */
    public static function buildTweeter(array $settingList)
    {
        $authorizationBuilder = new AuthorizationBuilder($settingList);
        $guzzleClient         = new Client();

        $statusUpdater = new StatusUpdater($authorizationBuilder, $guzzleClient);
        $mediaUploader = new MediaUploader($authorizationBuilder, $guzzleClient);

        return new Tweeter($statusUpdater, $mediaUploader);
    }
}
