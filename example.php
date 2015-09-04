<?php
require 'vendor/autoload.php';

/**
 * Configure all the things
 */
$settingList = [
    'accessToken'       => 'YOUR ACCESS TOKEN',
    'accessTokenSecret' => 'YOUR ACCESS TOKEN SECRET',
    'consumerKey'       => 'YOUR CONSUMER KEY',
    'consumerSecret'    => 'YOUR CONSUMER SECRET',
];

$authorizationBuilder = new JimLind\Pie7o\AuthorizationBuilder($settingList);

$statusUpdater = new JimLind\Pie7o\StatusUpdater($authorizationBuilder);
$mediaUploader = new JimLind\Pie7o\MediaUploader($authorizationBuilder);
$tweeter       = new JimLind\Pie7o\Tweeter($statusUpdater, $mediaUploader);

/**
 * Create a Tweet object the fun way
 */
$messageHandle = fopen('php://temp', 'r+');
$messageStream = new GuzzleHttp\Psr7\Stream($messageHandle);
$messageStream->write('This is a pictures of cats.');
$messageStream->rewind();

$mediaHandle = fopen('./cat.jpg', 'r');
$mediaStream = new GuzzleHttp\Psr7\Stream($mediaHandle);

$funTweet (new JimLind\Pie7o\Tweet)
    ->withMessage($messageStream)
    ->withMedia($mediaStream);

/**
 * Tweet and check results
 */
$hardResult = $tweeter->tweet($funTweet);
if ($hardResult) {
    echo 'The first Tweet was successfull.'.PHP_EOL;
} else {
    echo 'The first Tweet has failed.'.PHP_EOL;
}

/**
 * Create a Tweet object the cheating way
 */
$message    = 'This is the same picture of cats again.';
$media      = './cat.jpg';
$cheatTweet = JimLind\Pie7o\TweetFactory::buildTweet($message, $media);

/**
 * Tweet and check results
 */
$cheatResult = $tweeter->tweet($cheatTweet);
if ($cheatResult) {
    echo 'The second Tweet was successfull.'.PHP_EOL;
} else {
    echo 'The second Tweet has failed.'.PHP_EOL;
}