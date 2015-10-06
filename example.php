<?php
require 'vendor/autoload.php';

use JimLind\Pie7o\Pie7oException;
use JimLind\Pie7o\TweetFactory;
use JimLind\Pie7o\TweeterFactory;

/**
 * Configure all the things
 */
$tweeter = TweeterFactory::buildTweeter(
    [
        'accessToken'       => 'YOUR ACCESS TOKEN',
        'accessTokenSecret' => 'YOUR ACCESS TOKEN SECRET',
        'consumerKey'       => 'YOUR CONSUMER KEY',
        'consumerSecret'    => 'YOUR CONSUMER SECRET',
    ]
);

/**
 * Create a Tweet
 */
$tweet = TweetFactory::buildTweet(
    'This is a cool picture of cats.',
    './cat.jpg'
);

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