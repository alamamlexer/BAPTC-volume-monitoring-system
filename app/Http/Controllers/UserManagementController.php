<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Database\QueryException;
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
    return redirect()->route('user-management.index');
}

public function deactivate($id)
{
    $user = User::findOrFail($id);
    $user->is_active = false;
    $user->save();

    session()->flash('success', 'User deactivated successfully.');
    return redirect()->route('user-management.index');
}


    public function store(Request $request)
{
    $validatedData = $request->validate([
        'staff_name' => 'required|string|max:255|unique:staff,staff_name',
        'contact_number' => 'required|string|max:15',
        'email' => 'required|string|unique:staff,email',
        'password' => 'required|string|confirmed',
    ]);

    try {
        // Create the Staff record
        $staff = Staff::create([
            'staff_name' => $validatedData['staff_name'],
            'contact_number' => $validatedData['contact_number'],
            'email' => $validatedData['email'],
        ]);

        // Create the User record and link to the staff, set is_active to false
        User::create([
            'staff_id' => $staff->staff_id, // Use the newly created staff_id
            'username' => $staff->staff_name,
            'password' => Hash::make($validatedData['password']),
            'type' => '1', // 0=admin, 1=staff
            'is_active' => true, // Set to inactive by default
        ]);

        session()->flash('success', 'Account created successfully and is inactive by default.');
        return redirect()->route('user-management.index');
    } catch (\Exception $e) {
        session()->flash('error', 'Error creating staff: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-pages.user-inspector-assistant-create',);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $user=User::findOrFail($id);
            $user->delete();
            session()->flash('success', 'Account deleted successfully.');
            return redirect()->route('user-management.index');
        } catch (QueryException $error){
        
        if ($error->errorInfo[1]==1451){
            session()->flash('error','Account was not deleted due to related records');
            return redirect()->route('user-management.index');

        }
        }
    }
    
}
