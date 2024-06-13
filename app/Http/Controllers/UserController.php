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

    public function index()
    {
        $users = $this->database->getReference('users')->getSnapshot()->getValue();
        if (is_null($users)) {
            $users = [];  // Asegura que $users es un array, incluso si no hay datos.
        }    
        return view('admins.user-list', compact('users'));
    }


    public function edit($id)
    {
        $user = $this->database->getReference('users')->getChild($id)->getValue();
        return view('admins.user-edit')->with(['user' => $user, 'id' => $id]);
    }

    public function update(Request $request, $id)
    {
        $this->database->getReference('users/' . $id)->update($request->except(['_token', '_method']));


        return redirect()->route('admins.user-list');
    }

    public function destroy($id)
    {
        $this->database->getReference('users/' . $id)->remove();
        $this->auth->deleteUser($id);


        // AUTH FUERA DE FIREBASE
        // $this->logAction(DefAuth::user()->uid, 'delete', $id, 'user');

        return redirect()->route('admins.user-list');
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
