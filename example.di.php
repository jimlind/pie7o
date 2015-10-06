<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use JimLind\Pie7o\AuthorizationBuilder;
use JimLind\Pie7o\MediaUploader;
use JimLind\Pie7o\Pie7oException;
use JimLind\Pie7o\StatusUpdater;
use JimLind\Pie7o\Tweet;
use JimLind\Pie7o\Tweeter;

/**
 * Configure all the things
 */
$settingList = [
    'accessToken'       => 'YOUR ACCESS TOKEN',
    'accessTokenSecret' => 'YOUR ACCESS TOKEN SECRET',
    'consumerKey'       => 'YOUR CONSUMER KEY',
    'consumerSecret'    => 'YOUR CONSUMER SECRET',
];

$authorizationBuilder = new AuthorizationBuilder($settingList);
$guzzleClient         = new Client();

$statusUpdater = new StatusUpdater($authorizationBuilder, $guzzleClient);
$mediaUploader = new MediaUploader($authorizationBuilder, $guzzleClient);
$tweeter       = new Tweeter($statusUpdater, $mediaUploader);

/**
 * Create a Tweet
 */
$messageHandle = fopen('php://temp', 'r+');
$messageStream = new Stream($messageHandle);
$messageStream->write('This is a pictures of cats.');
$messageStream->rewind();

$mediaHandle = fopen('./cat.jpg', 'r');
$mediaStream = new Stream($mediaHandle);

$tweet = (new Tweet)
    ->withMessage($messageStream)
    ->withMedia($mediaStream);

/**
 * Tweet and catch exceptions
 */
try {
    $tweeter->tweet($tweet);
    echo 'Tweeting was successful.'.PHP_EOL;
} catch (Pie7oException $exception) {
    echo 'Tweeting failed.'.PHP_EOL;
    echo 'Exception thrown: `'.$exception->getMessage().'`'.PHP_EOL;
}