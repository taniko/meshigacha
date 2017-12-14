<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Builder::macro('if', function (bool $condition, callable $func) {
            return $condition ? $func($this) : $this;
        });
        \Validator::extend('base64', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-zA-Z0-9\/+]+=*$/', $value) === 1;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
