<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Staff;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class AuthenticationController extends Controller
{
  

    
    public function register_save_staff(Request $request){
    
        $validatedData = $request->validate([
            'staff_name' => 'required|string|max:255|unique:staffs,staff_name',
            'contact_number' => 'required|string|max:15',
            'email'=>'required|string|unique:staffs,email',
            'password' => 'required|string|confirmed',
        ]);

        $staff=Staff::create([
            'staff_name' =>  $validatedData['staff_name'],
            'contact_number' => $validatedData['contact_number'],
            'email' => $validatedData['email'],
        ]);

        User::create([
        'staff_id'=>$staff->id,
        'username' =>  $staff->staff_name,
        'password' => Hash::make($validatedData['password']),
        'type' => '1' //0=admin, 1=inspector, 2=farmer
        ]);
        
        session()->flash('success', 'Account created successfully.');
        
        return redirect()->route('user-management.index');
    }
    public function login(){
        return view('login');
    }

    public function login_action(Request $request)
{
    Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required',
    ])->validate();

    // Attempt to authenticate the user
    if (Auth::attempt($request->only('username', 'password'), $request->boolean('remember'))) {
        // Check if the user is active
        if (Auth::user()->is_active) {
            $request->session()->regenerate(); 

            session()->flash('success', 'Login successful.');

            if (Auth::user()->type == 0) {
            
                $author = Auth::user();
        
                Log::create([
                    'action_type'=>'login',
                    'transaction' => implode(', ', array_filter([
                                    'user type: admin',
                                    ])),
                    'author'=> $author->username,
                ]);
                return redirect()->route('admin.index'); 
            } elseif (Auth::user()->type == 1) {
                $author = Auth::user();
        
                Log::create([
                    'action_type'=>'login',
                    'transaction' => implode(', ', array_filter([
                                    'user type: staff',
                                    ])),
                    'author'=> $author->username,
                ]);
                return redirect()->route('staff-dashboard');
            }
        } else {
            // Log the user out if they are not active
            Auth::logout();
            session()->flash('error', 'Your account is deactivated. Please contact the administrator.');
            return redirect()->route('login');
        }
    }

    // If authentication fails
    throw ValidationException::withMessages([
        'username' => trans('auth.failed')
    ]);
}
    
    public function logout(Request $request){
    $author = Auth::user();
        
                Log::create([
                    'action_type'=>'logout',
                    'transaction' => implode(', ', array_filter([
                                    null,
                                    ])),
                    'author'=> $author->username,
                ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
        
    }
}
