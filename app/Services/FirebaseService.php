<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->create(); 
    }

    public function getAuth()
    {
        return $this->firebase->getAuth();
    }
}
