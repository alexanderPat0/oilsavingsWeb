<?php

namespace App\Http\Controllers;

use App\Jobs\SendVerificationEmailJob;
use App\Mail\SendVerificationEmail;
use App\Mail\VerifyEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Auth\CreateSessionCookie\FailedToCreateSessionCookie;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;


class AdminController extends Controller
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

    //Login as admin
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $idToken = $signInResult->idToken();

            //Para cuando Félix corrija, para comprobar la seguridad de la aplicación mediante la persistencia de cookies, voy a poner que las cookies caduquen cada 5 minutos, $fiveMinutes 
            $fiveMinutes = 300;
            //Si es muy molesto cambiar a este que es para una semana.
            $oneWeek = new \DateInterval('P7D');
            $sessionCookieString = $this->auth->createSessionCookie($idToken, $fiveMinutes);

            //Cookies praa 5 minutos 
            $cookie = cookie('session', $sessionCookieString, 5, null, null, true, true, false, 'Strict');

            //Cookies para 1 semana 
            $cookie = cookie('session', $sessionCookieString, 60 * 24 * 7, null, null, true, true, false, 'Strict');

            return redirect('/users')->withCookie($cookie);
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            return back()->withErrors(['firebase' => 'An error occurred with Firebase Authentication.']);
        }
    }

    //Logout del admin
    public function logout(Request $request)
    {
        // Eliminar la cookie de sesión del lado del servidor
        $cookie = Cookie::forget('session');
        // Redireccionar al usuario a la página de login o de inicio
        return redirect('/')->withCookie($cookie);
    }

    // Display list of admins
    public function index()
    {
        $admins = $this->database->getReference('admins')->getSnapshot()->getValue();
        return view('admin.index', compact('admins'));
    }

    // Store a newly created admin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userProperties = [
            'email' => $request->email,
            'emailVerified' => false,
            'password' => $request->password,
            'displayName' => $request->name,
        ];

        try {
            // Crear usuario en Firebase Auth y obtener el UID
            $createdUser = $this->auth->createUser($userProperties);
            $uid = $createdUser->uid; // UID del usuario creado

            // Guardar datos en pending_activation con UID
            $this->database->getReference('pending_activation/' . $uid)->set([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => false,
                'activatedAt' => '',
                'is_verified' => false,
                'verifiedAt' => '',
                'created_at' => now()->toString(),
            ]);

            // Generar el link de verificación
            // $verificationLink = $this->auth->getEmailVerificationLink($request->email) . '&id=' . $uid;

            // Generar el link de verificación
            $verificationLink1 = $this->auth->getEmailVerificationLink($request->email);
            // http://localhost:8000/email/verify
            // Pasar el UID en el link de verificación
            Log::info('LINK DE VERIFICACIÓN 1: ' . $verificationLink1);
            $verificationLink = $verificationLink1 . '&id=' . $uid;
            Log::info('LINK DE VERIFICACIÓN: ' . $verificationLink);
            Mail::to($request->email)->send(new SendVerificationEmail($verificationLink, $userProperties['displayName'], $uid));

            return response()->json(['success' => true, 'message' => 'Admin registered and email sent.']);

        } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
            return response()->json(['success' => false, 'message' => 'Email already exists.'], 409);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Registration failed.', 'error' => $e->getMessage()], 500);
        }
    }


    // Edit an existing admin
    public function edit($id)
    {
        $admin = $this->database->getReference('admins')->getChild($id)->getValue();
        return view('admin.edit', compact('admin', 'id'));
    }



    // Update an existing admin
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $adminData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->password) {
            $this->auth->changeUserPassword($id, $request->password);
        }

        $this->logAction(auth()->user()->id, 'create', $id, 'admin');

        $this->database->getReference('admins/' . $id)->update($adminData);

        $this->logAction(auth()->user()->id, 'update', $id, 'admin');

        return redirect()->route('admin.index')->with('success', 'Admin updated successfully.');
    }

    // Delete an existing admin
    public function destroy($id)
    {
        $this->database->getReference('admins/' . $id)->remove();
        $this->auth->deleteUser($id);

        $this->logAction(auth()->user()->id, 'delete', $id, 'admin');

        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }

    // Manager activate an admin account
    public function activate($id)
    {
        $admin = $this->database->getReference('admins')->getChild($id)->getValue();
        $admin['is_active'] = true;
        $this->database->getReference('admins/' . $id)->set($admin);

        return redirect()->route('admin.index')->with('success', 'Admin activated successfully.');
    }

    public function pendingActivation()
    {
        $pendingActivation = $this->database->getReference('pending_activation')
            ->orderByChild('is_sent')
            ->equalTo(false)
            ->getSnapshot()
            ->getValue();

        return view('manager.activations', ['pendings' => $pendingActivation]);
    }
    // Show action history
    public function showActions()
    {
        $actions = $this->database->getReference('actions')->getSnapshot()->getValue();
        $admins = $this->database->getReference('admins')->getSnapshot()->getValue();
        return view('admin.actions', compact('actions', 'admins'));
    }

    // Log actions
    private function logAction($adminId, $actionType, $targetId, $targetType)
    {
        $actionData = [
            'admin_id' => $adminId,
            'action_type' => $actionType,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'performed_at' => now()->timestamp,
        ];

        $this->database->getReference('actions')->push($actionData);
    }
}


// public function createSuperAdmin()
// {
//     $userProperties = [
//         'email' => 'admin.principal@google.com',
//         'emailVerified' => true,
//         'password' => 'password123',
//         'displayName' => 'Admin Principal',
//     ];
//     $createdUser = $this->auth->createUser($userProperties);
//     $adminData = [
//         'adminId' => $createdUser->uid,
//         'name' => 'Admin Principal',
//         'email' => 'admin.principal@google.com',
//         'password' => Hash::make('password123'), 
//         'is_super_admin' => true,
//         'is_active' => true,
//         'email_verified_at' => now(),
//         'remember_token' => Str::random(10),
//     ];   
//     $this->database->getReference('admins/' . $createdUser->uid)->set($adminData);
//     return response()->json(['message' => 'Super admin created successfully.']);
// }
// public function createSmolAdmin()
// {
//     $userProperties = [
//         'email' => 'administrador1@gmail.com',
//         'emailVerified' => true,
//         'password' => 'passwordadmin1',
//         'displayName' => 'Admin1',
//     ];
//     $createdUser = $this->auth->createUser($userProperties);
//     $adminData = [
//         'adminId' => $createdUser->uid,
//         'name' => $createdUser->displayName,
//         'email' => $createdUser->email,
//         'password' => Hash::make($userProperties['password']), 
//         'is_super_admin' => false,
//         'is_active' => true,
//         'email_verified_at' => now(),
//         'remember_token' => Str::random(10),
//     ];   
//     $this->database->getReference('admins/' . $createdUser->uid)->set($adminData);
//     $userProperties = [
//         'email' => 'administrador2@gmail.com',
//         'emailVerified' => true,
//         'password' => 'passwordadmin2',
//         'displayName' => 'Admin2',
//     ];
//     $createdUser = $this->auth->createUser($userProperties);
//     $adminData = [
//         'adminId' => $createdUser->uid,
//         'name' => $createdUser->displayName,
//         'email' => $createdUser->email,
//         'password' => Hash::make($userProperties['password']), 
//         'is_super_admin' => false,
//         'is_active' => true,
//         'email_verified_at' => now(),
//         'remember_token' => Str::random(10),
//     ];   
//     $this->database->getReference('admins/' . $createdUser->uid)->set($adminData);
//     $userProperties = [
//         'email' => 'administrador3@gmail.com',
//         'emailVerified' => true,
//         'password' => 'passwordadmin3',
//         'displayName' => 'Admin3',
//     ];
//     $createdUser = $this->auth->createUser($userProperties);
//     $adminData = [
//         'adminId' => $createdUser->uid,
//         'name' => $createdUser->displayName,
//         'email' => $createdUser->email,
//         'password' => Hash::make($userProperties['password']), 
//         'is_super_admin' => false,
//         'is_active' => true,
//         'email_verified_at' => now(),
//         'remember_token' => Str::random(10),
//     ];   
//     $this->database->getReference('admins/' . $createdUser->uid)->set($adminData);
//     return response()->json(['message' => 'Admin created successfully.']);
// }
