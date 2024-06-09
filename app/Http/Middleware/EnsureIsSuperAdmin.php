<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;
use Symfony\Component\HttpFoundation\Response;


class EnsureIsSuperAdmin
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


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Obtiene el token de la cookie
            $sessionCookie = $request->cookies->get('session');
            if (!$sessionCookie) {
                throw new \Exception("No session found.");
            }

            // Decodifica el token para obtener el UID del usuario
            $verifiedIdToken = $this->auth->verifySessionCookie($sessionCookie, true);
            $uid = $verifiedIdToken->claims()->get('sub'); // 'sub' contiene el UID del usuario

            // Verifica si el UID corresponde a un superadmin
            $adminRef = $this->database->getReference('admins/' . $uid);
            $admin = $adminRef->getValue();
            if (is_null($admin) || !$admin['is_super_admin']) {
                throw new \Exception("Access denied.");
            }

            // Permite que la solicitud continúe
            return $next($request);
        } catch (AuthException $e) {
            return response()->json(['error' => 'Authentication error: ' . $e->getMessage()], 403);
        } catch (FirebaseException $e) {
            return response()->json(['error' => 'Firebase error: ' . $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}