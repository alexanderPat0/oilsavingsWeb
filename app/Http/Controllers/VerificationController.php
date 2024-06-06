<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class VerificationController extends Controller
{
   
    protected $auth;
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));

        $this->database = $firebase->createDatabase();
        $this->auth = $firebase->createAuth();
    }


    public function verify($id)
    {
        $adminRef = $this->database->getReference('admins/' . $id);
        $admin = $adminRef->getValue();

        if (!$admin['email_verified_at']) {
            $this->auth->updateUser($id, ['emailVerified' => true]);
            $admin['email_verified_at'] = now()->timestamp;
            $adminRef->set($admin);

            return redirect()->route('login')->with('success', 'Email verified successfully. You can now login.');
        }

        return redirect()->route('login')->with('error', 'Email already verified.');
    }
}