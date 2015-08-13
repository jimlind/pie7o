# Pie7o
The PSR7 Tweeter

#### Features

 * You can tweet
 * You can tweet with a picture
 * Nothing else

#### Documentation

I find that learning by example is the best way so here is an [example file](example.php) for you
to poke at.

There isn't a lot of code, but it is readable. Additional documentation beyond the example code
isn't required.

#### Why Does This Exist?

I needed a Twitter library that was modern, maintainable, and easy to use. After PSR-7 and Guzzle 6
were released it seemed like a good enough excuse to write my own and use the new interfaces.

I've never had a reason to do anything other than post to Twitter via robot so only supporting that
made my job a lot easier.

#### Why Name it Pie7o?

As a post only Twitter client this is basically a Tweeter. Some tweeter speakers utilize piezo
technology. There are already a good number of applications and libraries called "Piezo." Replacing
the Z with a 7 means you get a unique name and a reference to the PSR-7 usage.

#### How do you pronounce it?

If there is ever a reason that two humans might want to mention this library via voice I'd be
shocked.

#### Anything else?

I don't base64 encode the images before uploading them. A lot of other libraries I looked at were
doing that. It's not hard to send the binaries, you just have to read the Twitter documentation.

## Code Quality Metrics

#### 100% Code Coverage (Eventually...)
```sh
composer install
vendor/bin/phpunit --coverage-text
```

#### 100% Code Sniffed
```sh
composer install
bash sniff.sh
```