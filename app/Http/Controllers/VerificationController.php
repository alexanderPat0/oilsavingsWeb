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

    // Cambio aquí: Utiliza el objeto Request para obtener el ID
    public function verify(Request $request)
    {
        $id = $request->query('id'); // Obtiene el ID desde el parámetro de URL

        if (is_null($id)) {
            return redirect()->route('login')->with('error', 'Invalid request, no user ID provided.');
        }

        $adminRef = $this->database->getReference('pending_activation/' . $id);
        $admin = $adminRef->getValue();

        if (!$admin || isset($admin['email_verified_at'])) {
            return redirect()->route('home')->with('error', 'Email already verified or user not found.');
        }

        $this->auth->updateUser($id, ['emailVerified' => true]);
        $admin['email_verified_at'] = now()->timestamp;
        $adminRef->set($admin);

        return redirect()->route('home')->with('success', 'Email verified successfully. Welcome!');
    }
}