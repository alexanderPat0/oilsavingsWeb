<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class UserController extends Controller
{

    /**
     * Connection to firebase database
     */
    public function connect()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
                    ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
                    
        return $firebase->createDatabase();

    }

    public function index()
    {
        $users = $this->connect()->getReference('users')->getSnapshot()->getValue();

        return view('user-list')->with([
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('user-form')->with([
            'id' => null,
            'role' => 'user',
        ]);
    }

    public function store(Request $request)
    {
        $this->connect()->getReference('users')->push($request->except(['_token']));
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $user = $this->connect()->getReference('users')->getChild($id)->getValue();

        return view('user-form')->with([
            'user' => $user,
            'id' => $id
        ]);
    }

    public function update($id, Request $request)
    {
        $this->connect()->getReference('users/' . $id)->update($request->except(['_token', '_method']));
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $this->connect()->getReference('users/' . $id)->remove();
        return back();
    }
}