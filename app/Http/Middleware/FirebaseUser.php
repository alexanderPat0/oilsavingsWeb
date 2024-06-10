<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Factory;
use Symfony\Component\HttpFoundation\Response;

class FirebaseUser
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
    public function handle(Request $request, Closure $next): Response
    {
        $sessionCookie = $request->cookie('session') ?? '';
        Log::info('Session Cookie: ' . $sessionCookie);

        if (empty($sessionCookie)) {
            Log::info('Session cookie is empty.');
            return redirect('/')->withErrors('You must be logged in to access this page.');
        }

        try {
            $verifiedSessionCookie = $this->auth->verifySessionCookie($sessionCookie, true);
            $uid = $verifiedSessionCookie->claims()->get('sub');

            $user = $this->auth->getUser($uid);
            $request->attributes->set('firebaseUser', $user);

            // Modify menu configuration here if needed
            config([
                'adminlte.menu' => [
                    [
                        'text' => $user->displayName ?? 'User Profile',
                        'url'  => 'profile',
                        'topnav_right' => true,
                        'icon' => 'fas fa-fw fa-user',
                    ],
                ]
            ]);

            return $next($request);
        } catch (AuthException $e) {
            Log::error('Auth error: ' . $e->getMessage());
            return redirect('/')->withErrors('Your session has expired, please log in again.');
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'An internal error occurred'], 500);
        }
    }
}