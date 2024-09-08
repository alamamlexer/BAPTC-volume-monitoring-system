<?php

namespace App\Http\Controllers;
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
    
    public function register_save(Request $request){
    
        Validator::make($request->all(),[
            'password' => 'required|confirmed',
        ])->validate();
        
        User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'type'=> "0" //0=user,1=admin
        ]);
        
        session()->flash('success', 'Account created successfully.');
        
        return redirect()->route('register');
    }
    
    public function login(){
        return view('login');
    }

    public function login_action(Request $request){
    
        Validator::make($request->all(),[
            'name' => 'required',
            'password' => 'required',
        ])->validate();
        
        if (!Auth::attempt($request->only('name', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'name' => trans('auth.failed')
            ]);
        }
        
            $request->session()->regenerate(); 
        
            session()->flash('success', 'Login successful.');
            
            if(Auth::user()->type==0){
                return redirect()->route('user_dashboard'); 
            }
            elseif(Auth::user()->type==1){
                return redirect()->route('admin_dashboard');
            }
           
        
    }
    
    public function logout(Request $request){
    
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
        
    }
}
