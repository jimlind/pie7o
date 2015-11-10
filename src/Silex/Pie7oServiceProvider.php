<?php

namespace JimLind\Pie7o\Silex;

use JimLind\Pie7o\Factory\TweetFactory;
use JimLind\Pie7o\Factory\TweeterFactory;
use JimLind\Pie7o\Pie7oException;
use JimLind\Pie7o\Tweet;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * A Silex Service Provider for Pie7o
 */
class Pie7oServiceProvider implements ServiceProviderInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['pie7o.tweet'] = $app->protect(function ($message, $mediaPath = null) use ($app) {
            $settingList = $this->buildSettingsList($app);
            $tweeter     = TweeterFactory::buildTweeter($settingList);
            $tweet       = TweetFactory::buildTweet($message, $mediaPath);

            $this->logger = $this->getLogger($app);
            $this->logAttempt($message, $mediaPath, $tweet);

            try {
                $tweeter->tweet($tweet);
                $this->logSuccess();
            } catch (Pie7oException $exception) {
                $this->logFailure($exception);

                return false;
            }

            return true;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {

    }

    /**
     * Attempt to find an acceptable logger in the container
     *
     * @param Application $app
     *
     * @return LoggerInterface
     */
    protected function getLogger(Application $app)
    {
        if (isset($app['pie7o.logger']) && $app['pie7o.logger'] instanceof LoggerInterface) {
            return $app['pie7o.logger'];
        }

        return $this->logger;
    }

    /**
     * Build an array of settings for the TweeterFactory
     *
     * @param Application $app
     *
     * @return string[]
     */
    protected function buildSettingsList(Application $app)
    {
        $keyList     = ['accessToken', 'accessTokenSecret', 'consumerKey', 'consumerSecret'];
        $settingList = array_fill_keys($keyList, '');

        foreach ($keyList as $key) {
            if (isset($app['twitter.'.$key])) {
                $settingList[$key] = $app['twitter.'.$key];
            }
        }

        return $settingList;
    }


    /**
     * Log attempt at tweeting as info
     *
     * @param string $message
     * @param string $mediaPath
     * @param Tweet $tweet
     */
    protected function logAttempt($message, $mediaPath, Tweet $tweet)
    {
        $message  = 'Attempting to Tweet with message: `'.$message.'`';
        $message .= $mediaPath ? ' with media id: `'.$tweet->getMediaId.'`' : '';

        $this->logger->info($message);
    }

    /**
     * Log success of tweeting as info
     */
    protected function logSuccess()
    {
        $this->logger->info('Tweet Succeeded');
    }

    /**
     * Log failure of tweeting and why as error
     *
     * @param Pie7oException $exception
     */
    protected function logFailure(Pie7oException $exception)
    {
        $this->logger->error('Tweet Failed');
        $this->logger->error('Exception Message: `'.$exception->getMessage().'`');
    }
}
