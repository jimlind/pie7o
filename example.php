<?php
require 'vendor/autoload.php';

use GuzzleHttp\Psr7\Stream;
use JimLind\Pie7o\AuthorizationBuilder;
use JimLind\Pie7o\MediaUploader;
use JimLind\Pie7o\Pie7oException;
use JimLind\Pie7o\StatusUpdater;
use JimLind\Pie7o\Tweeter;
use JimLind\Pie7o\TweetFactory;

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

$statusUpdater = new StatusUpdater($authorizationBuilder);
$mediaUploader = new MediaUploader($authorizationBuilder);
$tweeter       = new Tweeter($statusUpdater, $mediaUploader);

/**
 * Create a Tweet object the fun way
 */
$messageHandle = fopen('php://temp', 'r+');
$messageStream = new Stream($messageHandle);
$messageStream->write('This is a pictures of cats.');
$messageStream->rewind();

$mediaHandle = fopen('./cat.jpg', 'r');
$mediaStream = new Stream($mediaHandle);

$funTweet = (new Tweet)
    ->withMessage($messageStream)
    ->withMedia($mediaStream);

/**
 * Tweet and check results
 */
try {
    $tweeter->tweet($funTweet);
    echo 'The fun Tweet was successful.'.PHP_EOL;
} catch (Pie7oException $exception) {
    echo 'The fun Tweet has failed.'.PHP_EOL;
    echo 'Exception thrown: `'.$exception->getMessage().'`'.PHP_EOL;
}

/**
 * Create a Tweet object the cheating way
 */
$message    = 'This is the same picture of cats again.';
$media      = './cat.jpg';
$cheatTweet = TweetFactory::buildTweet($message, $media);

/**
 * Tweet and check results
 */
try {
    $tweeter->tweet($cheatTweet);
    echo 'The cheat Tweet was successful.'.PHP_EOL;
} catch (Pie7oException $exception) {
    echo 'The cheat Tweet has failed.'.PHP_EOL;
    echo 'Exception thrown: `'.$exception->getMessage().'`'.PHP_EOL;
}