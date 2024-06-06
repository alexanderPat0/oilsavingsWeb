<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            $serviceAccountPath = config('services.firebase.credentials');
            return (new Factory)->withServiceAccount($serviceAccountPath)->create();
        });
    }

    public function boot()
    {
        //
    }
}
