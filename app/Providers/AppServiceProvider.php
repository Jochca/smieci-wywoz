<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Psr\Http\Client\ClientInterface::class,
            \GuzzleHttp\Client::class
        );

        $this->app->bind(
            \Psr\Http\Message\RequestFactoryInterface::class,
            \GuzzleHttp\Psr7\HttpFactory::class
        );

        $this->app->bind(
            \Psr\Http\Message\StreamFactoryInterface::class,
            \GuzzleHttp\Psr7\HttpFactory::class
        );

        $this->app->bind(
            \App\Services\SmsDeliveryService\ISmsDeliveryService::class,
            \App\Services\SmsDeliveryService\Concrete\SmsApiSmsDeliveryService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
