<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

use Illuminate\Support\Facades\Auth as DefAuth;

class UserController extends Controller
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

    public function indexUsers()
    {
        $allUsers = $this->database->getReference('users')->getSnapshot()->getValue();
        $users = $this->filterUsersByRole($allUsers, 'user');

        return view('user-list')->with(['users' => $users, 'role' => 'User']);
    }

    public function indexAdmins()
    {
        $allUsers = $this->database->getReference('users')->getSnapshot()->getValue();
        $admins = $this->filterUsersByRole($allUsers, 'admin');

        return view('user-list')->with(['users' => $admins, 'role' => 'Admin']);
    }

    private function filterUsersByRole($users, $role)
    {
        return array_filter($users, function ($user) use ($role) {
            return isset($user['role']) && $user['role'] === $role;
        });
    }

    public function create()
    {
        return view('user-form')->with(['id' => null]);
    }

    // public function store(Request $request)
    // {
    //     $userProperties = [
    //         'email' => $request->email,
    //         'emailVerified' => false,
    //         'password' => $request->password,
    //         'displayName' => $request->username,
    //     ];

    //     $createdUser = $this->auth->createUser($userProperties);

    //     $userData = [
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'mainFuel' => $request->mainFuel,
    //     ];

    //     $this->database->getReference('users/' . $createdUser->uid)->set($userData);


    //     // AUTH FUERA DE FIREBASE
    //     $this->logAction(Auth::user()->uid, 'create', $createdUser->uid, 'user');

    //     return redirect()->route('users.indexUsers');
    // }

    public function edit($id)
    {
        $user = $this->database->getReference('users')->getChild($id)->getValue();
        return view('user-form')->with(['user' => $user, 'id' => $id]);
    }

    public function update(Request $request, $id)
    {
        $this->database->getReference('users/' . $id)->update($request->except(['_token', '_method']));

        // AUTH FUERA DE FIREBASE
        $this->logAction(DefAuth::user()->uid, 'update', $id, 'user');

        return redirect()->route('users.indexUsers');
    }

    public function destroy($id)
    {
        $this->database->getReference('users/' . $id)->remove();
        $this->auth->deleteUser($id);


        // AUTH FUERA DE FIREBASE
        $this->logAction(DefAuth::user()->uid, 'delete', $id, 'user');

        return redirect()->route('users.indexUsers');
    }

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
