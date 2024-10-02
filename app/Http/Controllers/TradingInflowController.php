<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Inflow;
use App\Models\Transaction;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Commodity;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;


class TradingInflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $trading_inflows=Inflow::where('transaction_type','trading_inflow')
        //                     ->get();
        return view('admin-pages.trading-inflow-report');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $logged_in_staff=Auth::id();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        return view('admin-pages.trading-inflow-form-create',compact('staffs','logged_in_staff','vehicle_types','commodities'));
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
            'commodity_id' => 'required|exists:commodities,commodity_id', 
            'volume' => 'required|integer', 
            'plate_number' => 'required',
            'vehicle_type_id' => 'required|exists:vehicle_types,vehicle_type_id', 
            'name' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'region' => 'required',
        ]);  
        
        //Storing new location 
        
        $location = Location::where('barangay', $validatedData['barangay'])
        ->where('municipality', $validatedData['municipality'])
        ->where('province', $validatedData['province'])
        ->where('region', $validatedData['region'])
        ->first();
    
        if (!$location) {
            // Create the vehicle only if it doesn't exist
            $location= Location::firstOrCreate([
                'barangay' => $validatedData['barangay'],
                'municipality' => $validatedData['municipality'],
                'province' => $validatedData['province'],
                'region' => $validatedData['region'],
            ]);
        }
                
        
        //Storing new vehicle
        
        $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();
    
        if (!$vehicle) {
            // Create the vehicle only if it doesn't exist
            $vehicle=Vehicle::create([
                'plate_number' => $validatedData['plate_number'],
                'vehicle_name' => $validatedData['name'],
                'vehicle_type_id' => $validatedData['vehicle_type_id'],
            ]);
        }

        $pivot=$vehicle->locations()->attach($location->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        dd($pivot);
   
       Transaction::create([
            'date'=> $validatedData['date'],
            'time'=> $validatedData['time'],
            'transaction_type' => $validatedData['transaction_type'],
            'transaction_status'=> $validatedData['transaction_status'],
            'staff_id'=>$validatedData['staff_id'],
            'commodity_id'=>$validatedData['commodity_id'],
            'volume'=>$validatedData['volume'],
            'plate_number'=>$validatedData['plate_number'],
            'vehicle_type_id'=>$validatedData['vehicle_type_id'],
            'name'=>$validatedData['name'],
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
        ]);        
        
        session()->flash('success', 'Trading inflow added successfully!');
        
        

        return redirect()->route('trading-inflow.create');
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
    public function edit(Inflow $trading_inflow)
    {
        return view('admin-pages.trading-inflow-form-edit',compact('trading_inflow'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inflow $trading_inflow)
    {
        $trading_inflow->update($request->all());
        session()->flash('success', 'Trading inflow transaction updated!');
        
        return redirect()->route('trading-inflow.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $inflow=Inflow::findOrFail($id);
            $inflow->delete();
            session()->flash('success', 'Trading inflow transaction deleted successfully.');
            return redirect()->route('trading-inflow.index');
        } catch (QueryException $error){
        
        if ($error->errorInfo[1]==1451){
            session()->flash('error','Trading inflow transaction was not deleted due to related records');
            return redirect()->route('trading-inflow.index');

        }
        }

    }
}
