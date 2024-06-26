<?php namespace App\Providers;

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
        //
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
        }
        
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );

        $this->app->bind('xoso', function () {
            return new \App\Helpers\XoSo;
        });

        $this->app->bind('gamePlay', function () {
            return new \App\Helpers\GamePlay;
        });
        $this->app->bind('UserHelpers', function () {
            return new \App\Helpers\UserHelpers;
        });
        $this->app->bind('RoleHelpers', function () {
            return new \App\Helpers\RoleHelpers;
        });
        $this->app->bind('LocationHelpers', function () {
            return new \App\Helpers\LocationHelpers;
        });
        $this->app->bind('GameHelpers', function () {
            return new \App\Helpers\GameHelpers;
        });
        $this->app->bind('HistoryHelpers', function () {
            return new \App\Helpers\HistoryHelpers;
        });
        $this->app->bind('XoSoRecordHelpers', function () {
            return new \App\Helpers\XoSoRecordHelpers;
        });
    }
}
