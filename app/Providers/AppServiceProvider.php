<?php

namespace App\Providers;

use App\Services\Cbr\CbrService;
use App\Services\Cbr\CurrencyServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        CurrencyServiceInterface::class => CbrService::class,
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
        $this->app->bind(CurrencyServiceInterface::class,fn($app) => $app->make(CbrService::class));
    }
}
