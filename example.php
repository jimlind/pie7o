<?php
require 'vendor/autoload.php';

$settingList = [
    'accessToken'       => 'YOUR ACCESS TOKEN',
    'accessTokenSecret' => 'YOUR ACCESS TOKEN SECRET',
    'consumerKey'       => 'YOUR CONSUMER KEY',
    'consumerSecret'    => 'YOUR CONSUMER SECRET',
];

$tweet = new JimLind\Pie7o\Tweet();
$tweet->setText('This is a pictures of cats.');

$imageHandle = fopen('/tmp/cats.jpg', 'r');
$imageStream = new GuzzleHttp\Psr7\Stream($imageHandle);
$tweet->setImage($imageStream);

$tweeter  = new JimLind\Pie7o\Tweeter($settingList);
$tweeter->tweet($tweet);