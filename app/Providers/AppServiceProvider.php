<?php

namespace App\Providers;

use App\Services\CurrencyClient\CbrClient;
use App\Services\CurrencyClient\CurrencyClientInterface;
use App\Services\CurrencyService\CurrencyService;
use App\Services\CurrencyService\CurrencyServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        CurrencyServiceInterface::class => CurrencyService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CurrencyServiceInterface::class,fn($app) => $app->make(CurrencyService::class));
        $this->app->bind(CurrencyClientInterface::class,fn($app) => $app->make(CbrClient::class));
    }
}
