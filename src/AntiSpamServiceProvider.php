<?php
namespace Empari\Laravel\AntiSpam;

use Empari\Laravel\AntiSpam\Services\AkismetSpamService;
use Empari\Laravel\AntiSpam\Services\SpamServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
* Class AntiSpamServiceProvider
 * Inpired by: https://github.com/yovanoc/akismet-spam
 *
 * @package Empari\Laravel\AntiSpam.
 *
 * todo: add mailinder.io
*/
class AntiSpamServiceProvider extends ServiceProvider
{	
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configs
        $this->publishes([
            __DIR__.'/../config/anti-spam.php' => config_path('anti-spam.php'),
        ], 'configs');

        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('database/migrations')
        ], 'migrations');

        // Services
        $this->app->bind(SpamServiceInterface::class, AkismetSpamService::class);
        $this->app->singleton(SpamServiceInterface::class, function ($app) {
            return new AkismetSpamService(new \GuzzleHttp\Client);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}