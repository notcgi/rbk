<?php

namespace App\Providers;

use App\src\Application\CurrencyClientInterface;
use App\src\Domain\CurrencyService;
use App\src\Domain\CurrencyServiceInterface;
use App\src\Infrastructure\CbrClient;
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
