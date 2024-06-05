<?php

namespace App\Http\Controllers\Auth;

use Kreait\Firebase\Auth;
use App\Http\Controllers\Controller;

class FirebaseAuthController extends Controller
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function login()
    {
        $idToken = request()->input('idToken');
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        // Logica para manejar la sesión del usuario o lo que necesites hacer con el UID
    }
}
