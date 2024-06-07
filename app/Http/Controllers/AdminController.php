<?php

namespace App\Http\Controllers;

use App\Jobs\SendVerificationEmailJob;
use App\Mail\SendVerificationEmail;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

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

    // Display list of admins
    public function index()
    {
        $admins = $this->database->getReference('admins')->getSnapshot()->getValue();
        return view('admin.index', compact('admins'));
    }

    // Show form to create a new admin
    public function create()
    {
        return view('admin.create');
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


        // $adminData = [
        //     'adminId' => $createdUser->uid,
        //     'email' => $request->email,
        //     'email_verified' => false,
        //     'email_verified_at' => '',
        //     'is_active' => false,
        //     'is_super_admin' => false,
        //     'name' => $request->name,
        //     'password' => Hash::make($request->password),
        //     'remember_token' => Str::random(10),


        // ];
        $createdUser = $this->auth->createUser($userProperties);


        $pendingRef = $this->database->getReference('pending_activation')->push([
            'email' => $request->email,
            'is_sent' => false,
            'created_at' => now()->toString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Admin registered, admin activation pending.']);
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

    // Activate an admin account
    public function activate($id)
    {
        $admin = $this->database->getReference('admins')->getChild($id)->getValue();
        $admin['is_active'] = true;
        $this->database->getReference('admins/' . $id)->set($admin);

        return redirect()->route('admin.index')->with('success', 'Admin activated successfully.');
    }

    public function sendActivation($id)
{
    try {
        $pending = $this->database->getReference('pending_activation/' . $id)->getSnapshot()->getValue();
        $verificationLink = $this->auth->getEmailVerificationLink($pending['email']);
        Mail::to($pending['email'])->send(new SendVerificationEmail($verificationLink));

        // Marcar como enviado
        $this->database->getReference('pending_activation/' . $id)->update(['is_sent' => true]);

        return response()->json(['success' => true, 'message' => 'Verification link sent.']);
    } catch (\Exception $e) {
        Log::error('Failed to send verification link: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to send the link.']);
    }
}


    public function pendingActivation()
    {
        $pendingActivation = $this->database->getReference('pending_activation')  // Asegúrate que la ruta sea correcta
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
