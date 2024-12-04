<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountActivationMail;
use App\Models\ActivationToken;
class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('staffs')->whereIn('type',[1,2])->get();
        
        
        return view('admin-pages.user-management',compact('users'));
    }

    public function activate($id)
{
    $user = User::findOrFail($id);
    $user->is_active = true;
    $user->save();

    session()->flash('success', 'User activated successfully.');
    
    $author = Auth::user();
        
        Log::create([
            'action_type'=>'activate',
            'transaction' => implode(', ', array_filter([
                            isset($user->username) ? $user->username: null,
                            ])),
            'author'=> $author->username,
            'user_id'=> $author->id,
            
        ]);
    return redirect()->route('user-management.index');
}

public function deactivate($id)
{
    $user = User::findOrFail($id);
    $user->is_active = false;
    $user->save();
    $author = Auth::user();
        
    Log::create([
        'action_type'=>'deactivate',
        'transaction' => implode(', ', array_filter([
                        isset($user->username) ? $user->username: null,
                        ])),
        'author'=> $author->username,
        'user_id'=> $author->id,
        
    ]);
    session()->flash('success', 'User deactivated successfully.');
    return redirect()->route('user-management.index');
}


public function store(Request $request)
{
     $author = Auth::user();
    $validatedData = $request->validate([
        'staff_name' => 'required|string|max:255|unique:staff,staff_name',
        'contact_number' => 'required|string|max:15',
        'email' => 'required|email|unique:staff,email', // Email is required and unique in the 'staff' table
        'password' => 'required|string|confirmed',
    ]);

    try {
        // Create the Staff record
        $staff = Staff::create([
            'staff_name' => $validatedData['staff_name'],
            'contact_number' => $validatedData['contact_number'],
            'email' => $validatedData['email'],  // Ensure email is passed here correctly
        ]);
 
        // Generate a random temporary password
        $tempPassword = Str::random(8); // 8-character random temporary password

        // Create the User record and link it to the staff, set 'is_active' to false by default
        $user = User::create([
            'staff_id' => $staff->staff_id,
            'username' => $staff->staff_name,
            'password' => Hash::make($tempPassword), // Hash the temporary password
            'type' => 1, // staff type
            'is_active' => false, // Set to inactive by default
            'email' => $staff->email, // Pass the email from the staff record to the user
        ]);

        // Generate an activation token
        $token = Str::random(60); // Generate a 60-character random string

        // Save the activation token
        ActivationToken::create([
            'email' => $staff->email,
            'token' => $token,
        ]);
        Log::create([
        'action_type'=>'create',
        'transaction' => implode(', ', array_filter([
                        isset($user->username) ? "user: {$user->username}" : null,
                        isset($user->contact_number) ? $user->contact_number: null,
                        isset($user->email) ? $user->email: null,
                        ])),
        'author'=> $author->username,
        'user_id'=> $author->id,
        
        ]);
        // Send the activation email with the temporary password and activation URL
        Mail::to($staff->email)->send(new AccountActivationMail($staff, $token, $tempPassword));

        session()->flash('success', 'Account created successfully and activation email sent.');
        return redirect()->route('user-management.index');
    } catch (\Exception $e) {
        session()->flash('error', 'Error creating staff: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }
}


public function activateAccount($token)
{
     $author = Auth::user();
    // Find the activation token in the database
    $activationToken = ActivationToken::where('token', $token)->first();

    // Check if the token exists
    if (!$activationToken) {
        return redirect()->route('login')->with('error', 'Invalid or expired token.');
    }

    // Find the staff member associated with the token's email
    $staff = Staff::where('email', $activationToken->email)->first();

    // Check if the staff exists
    if (!$staff) {
        return redirect()->route('login')->with('error', 'Staff member not found.');
    }

    // Activate the staff member if it's inactive
    if ($staff->is_active === false) {
        $staff->is_active = true;
        $staff->save(); // Save the changes to the staff record
    }

    // Find the user associated with the staff member
    $user = User::where('email', $staff->email)->first();

    // Optionally, update the User model's is_active field
    if ($user) {
        $user->is_active = true;
        $user->save(); // Save the changes to the user record
    }
        Log::create([
            'action_type'=>'activate',
            'transaction' => implode(', ', array_filter([
                            isset($user->username) ? $user->username: null,
                            ])),
            'author'=> $author->username,
            'user_id'=> $author->id,
            
        ]);
    // Delete the activation token after it is used
    $activationToken->delete();

    // Redirect to the login page with a success message
    return redirect()->route('login')->with('success', 'Your account has been activated successfully!');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-pages.user-inspector-assistant-create',);
    }
}
    
