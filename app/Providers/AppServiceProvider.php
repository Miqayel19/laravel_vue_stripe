<?php

namespace App\Providers;

use App\Http\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Contracts\UserInterface',
            'App\Http\Services\UserService'
        );
        $this->app->bind(
            'App\Http\Contracts\AccountInterface',
            'App\Http\Services\AccountService'
        );
        $this->app->bind(
            'App\Http\Contracts\PlanInterface',
            'App\Http\Services\PlanService'
        );
        $this->app->bind(
            'App\Http\Contracts\CartItemInterface',
            'App\Http\Services\CartItemService'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

    }
}
