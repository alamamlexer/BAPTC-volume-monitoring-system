<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('farmers','staffs')->whereIn('type',[1,2])->get();
        
        
        return view('admin-pages.user-management',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-pages.user-inspector-assistant-create',);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'transaction_status' => 'required',
            'transaction_type' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'staff_id' => 'required|exists:staff,staff_id', 
            'commodity_name' => 'required|exists:commodities,commodity_name', 
            'volume' => 'required|integer', 
            'plate_number' => 'required',
            'vehicle_type_id' => 'required|exists:vehicle_types,vehicle_type_id', 
            'name' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'region' => 'required',
        ]);  
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
