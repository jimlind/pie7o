<?php
require 'vendor/autoload.php';

$settingList = [
    'accessToken'       => 'YOUR ACCESS TOKEN',
    'accessTokenSecret' => 'YOUR ACCESS TOKEN SECRET',
    'consumerKey'       => 'YOUR CONSUMER KEY',
    'consumerSecret'    => 'YOUR CONSUMER SECRET',
];

$authorizationBuilder = new JimLind\Pie7o\AuthorizationBuilder($settingList);

$statusUpdater = new JimLind\Pie7o\StatusUpdater($authorizationBuilder);
$mediaUploader = new JimLind\Pie7o\MediaUploader($authorizationBuilder);

$messageHandle = fopen('php://temp', 'r+');
$messageStream = new GuzzleHttp\Psr7\Stream($messageHandle);
$messageStream->write('This is a pictures of cats.');
$messageStream->rewind();

$mediaHandle = fopen('./cat.jpg', 'r');
$mediaStream = new GuzzleHttp\Psr7\Stream($mediaHandle);

$tweet = new JimLind\Pie7o\Tweet();
$tweet->setMessage($messageStream);
$tweet->setMedia($mediaStream);

$tweeter = new JimLind\Pie7o\Tweeter($statusUpdater, $mediaUploader);
$tweeter->tweet($tweet);