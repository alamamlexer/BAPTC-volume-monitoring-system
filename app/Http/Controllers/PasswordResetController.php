<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Log;
use App\Mail\TemporaryPasswordMail;
use Illuminate\Support\Facades\Auth;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form.
     */
    public function showRequestForm()
    {
        return view('forget-password');  // Blade view where user enters their email
    }

    /**
     * Handle the request for sending the temporary password.
     */
    public function sendTemporaryPassword(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Generate a random temporary password (8 characters)
        $temporaryPassword = Str::random(8);

        // Hash the temporary password before saving it (never store plaintext passwords)
        $hashedPassword = Hash::make($temporaryPassword);

        // Update the user's password with the temporary password
        $user->password = $hashedPassword;
        $user->save();
        
        
        Log::create([
            'action_type'=>'password reset',
            'transaction' => implode(', ', array_filter([
                            isset($user->username) ? $user->username: null,
                            isset($user->email) ? $user->email: null,
                            ])),
            'author'=> $user->username,
        ]);

        // Send an email to the user with the temporary password
        Mail::to($user->email)->send(new TemporaryPasswordMail($user, $temporaryPassword));

        // Redirect back with a success message
        return back()->with('status', 'We have sent you a temporary password to your email address.');
    }
}