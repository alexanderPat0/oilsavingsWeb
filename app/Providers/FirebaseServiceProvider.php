<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;


class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            $cacheStore = cache()->store();

            $firebase = (new Factory)
                ->withServiceAccount(config('services.firebase.credentials')
                ->withDatabaseUri('services.firebase.database'));

            return $firebase->createFirestore();

        });
    }
}
