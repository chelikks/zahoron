<?php
/**
 * @author Maksim Khodyrev<maximkou@gmail.com>
 * 13.07.17
 */

namespace Nutnet\LaravelSms;

use Illuminate\Support\Arr;
use Nutnet\LaravelSms\Providers;

/**
 * Class ServiceProvider
 * @package Nutnet\LaravelSms
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    private $providerAliases = [
        'log' => Providers\Log::class,
        'iqsms' => Providers\IqSmsRu::class,
        'smpp' => Providers\Smpp::class,
        'smscru' => Providers\SmscRu::class,
        'smsru' => Providers\SmsRu::class,
    ];

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config.php' => config_path('nutnet-laravel-sms.php')
        ], 'config');
    }

    public function register()
    {
        $this->app->singleton(SmsSender::class, function ($app) {
            $providerClass = config('nutnet-laravel-sms.provider');
            $options = config('nutnet-laravel-sms.providers.'.$providerClass, []);

            if (array_key_exists($providerClass, $this->providerAliases)) {
                $providerClass = $this->providerAliases[$providerClass];
            }

            return new SmsSender(
                $app->make($providerClass, [
                    'options' => Arr::except($options, 'message_defaults')
                ]),
                (array)Arr::get($options, 'message_defaults', [])
            );
        });
    }
}
