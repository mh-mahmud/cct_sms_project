<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('test', function ($attribute, $value, $parameters, $validator) {
            return false;
        });

        Validator::replacer('test', function($message, $attribute, $rule, $parameters) {
            return "Test validator.";
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
