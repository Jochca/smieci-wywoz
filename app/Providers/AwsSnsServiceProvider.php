<?php

namespace App\Providers;

use Aws\Sns\SnsClient;
use Illuminate\Support\ServiceProvider;

class AwsSnsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SnsClient::class, function () {
            return new SnsClient([
                "region" => config("aws.sns.region"),
                "version" => config("aws.sns.version"),
                "credentials" => [
                    "key" => config("aws.sns.credentials.key"),
                    "secret" => config("aws.sns.credentials.secret"),
                ],
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
