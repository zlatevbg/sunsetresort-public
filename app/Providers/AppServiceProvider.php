<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \URL::forceSchema('https');

        require app_path('Helpers/helpers.php');

        \Validator::extend('before_or_equal', 'App\Services\Validator@validateBeforeOrEqual');
        \Validator::replacer('before_or_equal', 'App\Services\Validator@replaceBeforeOrEqual');

        /*\DB::listen(function($query) {
            \Log::debug($query->sql);
            \Log::debug($query->bindings);
        });*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Http\Middleware\StartSessionExtend'); // Unikat extended session
    }
}
