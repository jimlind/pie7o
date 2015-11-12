# PIE7O
The PSR7 Tweeter

#### Features

 * You can tweet
 * You can tweet with a picture
 * Nothing else

#### Badges

[![Build Status](https://travis-ci.org/jimlind/pie7o.png?branch=master)](https://travis-ci.org/jimlind/pie7o)
[![Coverage Status](https://coveralls.io/repos/jimlind/pie7o/badge.svg?branch=master&service=github)](https://coveralls.io/github/jimlind/pie7o?branch=master)
[![Code Climate](https://codeclimate.com/github/jimlind/pie7o/badges/gpa.svg)](https://codeclimate.com/github/jimlind/pie7o)
[![Latest Stable Version](https://poser.pugx.org/jimlind/pie7o/v/stable)](https://packagist.org/packages/jimlind/pie7o)
[![Total Downloads](https://poser.pugx.org/jimlind/pie7o/downloads)](https://packagist.org/packages/jimlind/pie7o)
[![License](https://poser.pugx.org/jimlind/pie7o/license)](https://packagist.org/packages/jimlind/pie7o)

#### Documentation

Configuration may seem a little more difficult than it needs to be, but it keeps things flexible.
You'll only ever have to configure it once. I find that learning by example is the best way so here
are some examples:

 - [The easy way](example.php)
 - [The dependency injection way](example.di.php)
 - [The silex service provider way](example.silex.php)

The library will quietly succeed if there are no problems communicating with Twitter, but if there
is a problem it throws a Pie7oException that extends the RuntimeException class.

#### Why Does This Exist?

I needed a Twitter library that was modern, maintainable, and easy to use. After PSR-7 and Guzzle 6
were released it seemed like a good enough excuse to write my own and use the new interfaces.

I've never had a reason to do anything other than post to Twitter via robot so only supporting that
made my job a lot easier.

#### Why Name it Pie7o?

As a post only Twitter client this is basically a Tweeter. Some tweeter speakers are just a piezo.
There are already a good number of applications and libraries called "Piezo." Replacing the Z with
a 7 means you get a unique name and a reference to the PSR-7 usage.

#### How do you pronounce it?

If there is ever a reason that two humans might want to mention this library via voice I'd be
shocked.

#### Anything else?

I don't base64 encode the images before uploading them. A lot of other libraries I looked at were
doing that. It's not hard to send the binaries, you just have to read the Twitter documentation.

## Code Quality Metrics

#### 100% Code Coverage*
```sh
composer install
vendor/bin/phpunit --coverage-text
```

#### 100% Code Sniffed
```sh
composer install
bash sniff.sh
```

*Except the optional Silex Service Provider