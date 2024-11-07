<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffProfileController extends Controller
{
    public function show($id)
    {
        // Fetch the user by ID and eager load the 'staffs' relationship
        $user = User::with('staffs')->findOrFail($id);

        // Return the staff profile view
        return view('staff-pages.staff-profile', compact('user'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'staff_name' => 'required|string|max:255',
        'contact_number' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'current_password' => 'required|string',
        'new_password' => 'nullable|string|min:8', // New password is optional
    ]);

    $user = User::findOrFail($id);
    
    // Verify the current password
    if (!Hash::check($request->current_password, $user->password)) {
        return redirect()->back()
            ->withErrors(['current_password' => 'The provided password does not match our records.'])
            ->withInput(); // Retain input values
    }

    // Update staff information
    $user->staffs->staff_name = $request->staff_name;
    $user->staffs->contact_number = $request->contact_number;
    $user->staffs->email = $request->email;

    // Update password if a new one is provided
    if ($request->filled('new_password')) {
        $user->password = Hash::make($request->new_password); // Hash the new password
    }
    
    // Save the changes
    $user->staffs->save();
    $user->save(); // Save the User model
    $author = Auth::user();
         
       
       
  
    $updated_data = $user->getChanges();

$test = Log::create([
    'action_type' => 'update',
    'transaction' => 'profile' ,
    'author' => $author->username,
]);


    return redirect()->route('staff.profile', $user->id)->with('success', 'Profile updated successfully!');
}

    
}