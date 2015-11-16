<?php

require 'vendor/autoload.php';

$app = new Silex\Application();
$app->register(
    new JimLind\Pie7o\Silex\Pie7oServiceProvider(),
    [
        'twitter.accessToken' => 'YOUR ACCESS TOKEN',
        'twitter.accessTokenSecret' => 'YOUR ACCESS TOKEN SECRET',
        'twitter.consumerKey' => 'YOUR CONSUMER KEY',
        'twitter.consumerSecret' => 'YOUR CONSUMER SECRET',
        'pie7o.logger' => new Psr\Log\NullLogger(),
    ]
);

$tweeted = $app['pie7o.tweet']('This is a cool picture of cats.', './cat.jpg');
if (true === $tweeted) {
    echo 'Tweeting was successful.'.PHP_EOL;
} else {
    echo 'Tweeting failed.'.PHP_EOL;
}
