<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;
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
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userProperties = [
            'email' => $request->email,
            'emailVerified' => false,
            'password' => $request->password,
            'displayName' => $request->name,
        ];

        $createdUser = $this->auth->createUser($userProperties);

        $adminData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_super_admin' => $request->has('is_super_admin'),
            'is_active' => false,
        ];

        $this->database->getReference('admins/' . $createdUser->uid)->set($adminData);

        // Generate email verification link
        $verificationLink = $this->auth->getEmailVerificationLink($request->email);

        // Send the email verification link
        Mail::to($request->email)->send(new VerifyEmail($verificationLink));

        return redirect()->route('admin.index')->with('success', 'Admin created and verification email sent.');
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

    // Log history of actions
    private function logAction($adminId, $actionType, $targetId, $targetType)
    {
        $actionData = [
            'admin_id' => $adminId,
            'action_type' => $actionType,
            'target_id' => $targetId,
            'performed_at' => now()->timestamp,
        ];

        $this->database->getReference('actions')->push($actionData);
    }

    // Show action history
    public function showActions()
    {
        $actions = $this->database->getReference('actions')->getSnapshot()->getValue();
        $admins = $this->database->getReference('admins')->getSnapshot()->getValue();
        return view('admin.actions', compact('actions', 'admins'));
    }
}
