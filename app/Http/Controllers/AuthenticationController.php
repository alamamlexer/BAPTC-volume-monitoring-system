<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class AuthenticationController extends Controller
{
    public function register(){
        return view('register');
    }
    
    public function register_save_farmer(Request $request){
    
        
        $validatedData = $request->validate([
            'plate_number' => 'required|string|unique:farmers,plate_number|max:255',
            'farmer_name' => 'string|max:255',
            'contact_number' => 'required|string|max:15',
            'password' => 'required|string|confirmed',
        ]);

        $farmer=Farmer::create([
            'farmer_name' =>  $validatedData['farmer_name'],
            'contact_number' => $validatedData['contact_number'],
            'plate_number' => $validatedData['plate_number'],
        ]);
        User::create([
        'farmer_id'=>$farmer->id,
        'username' =>  $farmer->plate_number,
        'password' => Hash::make($validatedData['password']),
        'type' => '2' //0=admin, 1=inspector, 2=farmer
        ]);
        
        session()->flash('success', 'Account created!');
        
        return redirect()->route('login');
    }
    
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

    public function login_action(Request $request){
    
        Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required',
        ])->validate();
        
        if (!Auth::attempt($request->only('username', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => trans('auth.failed')
            ]);
        }
        
            $request->session()->regenerate(); 
        
            session()->flash('success', 'Login successful.');
            
            if(Auth::user()->type==0){
                return redirect()->route('admin_dashboard'); 
            }
            elseif(Auth::user()->type==1){
                return redirect()->route('admin_dashboard');
            }
            elseif(Auth::user()->type==2){
                return redirect()->route('user_dashboard');
            }
           
        
    }
    
    public function logout(Request $request){
    
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
        
    }
}
