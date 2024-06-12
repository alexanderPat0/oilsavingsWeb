<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class AppServiceProvider extends ServiceProvider
{

    protected $auth;
    protected $database;
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton('Firebase\Auth', function ($app) {
            $firebase = (new Factory)
                ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

            return $firebase->createAuth();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url)
 {
    //  if (env('APP_ENV') == 'production') {
    //      $url->forceScheme('https');
    //  }
 }
}
