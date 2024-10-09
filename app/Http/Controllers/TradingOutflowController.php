<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Outflow;
use App\Models\Transaction;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Commodity;
use App\Models\Location;
use App\Models\LocationVehicle;
use Illuminate\Support\Facades\Auth;

class TradingOutflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trading_outflows=Transaction::where('transaction_type','trading outflow')
                            ->with(['staff','commodity','vehicle_type'])
                            ->get();
        return view('admin-pages.trading-outflow-report',compact('trading_outflows'));
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
        $location_vehicles = LocationVehicle::with(['vehicle', 'location'])->get();
        return view('admin-pages.trading-outflow-form-create',compact('staffs','logged_in_staff','vehicle_types','commodities','location_vehicles'));
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
            'volume' => 'required|numeric', 
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
            Location::   Create([
                'barangay' => $validatedData['barangay'],
                'municipality' => $validatedData['municipality'],
                'province' => $validatedData['province'],
                'region' => $validatedData['region'],
            ]);
        }
        else{
            Location::where('barangay', $validatedData['barangay'])
            ->where('municipality', $validatedData['municipality'])
            ->where('province', $validatedData['province'])
            ->where('region', $validatedData['region'])
            ->first();
        }
        $location = Location::where('barangay', $validatedData['barangay'])
        ->where('municipality', $validatedData['municipality'])
        ->where('province', $validatedData['province'])
        ->where('region', $validatedData['region'])
        ->first();
                
        
        //Storing new vehicle
        $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();
    
        if (!$vehicle) {
            Vehicle::create([
                'plate_number' => $validatedData['plate_number'],
                'vehicle_name' => $validatedData['name'],
                'vehicle_type_id' => $validatedData['vehicle_type_id'],
            ]);
        }
        else{
            Vehicle::where('plate_number', $validatedData['plate_number'])->first();
        }
        $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();

        //Storing a link in the address and location if there is no existing record
        $location_vehicle = LocationVehicle::where('vehicle_id', $vehicle->vehicle_id,)
        ->where('location_id', $location->location_id)
        ->first();
        if (!$location_vehicle) {
            $location_vehicle= LocationVehicle::create([
                'vehicle_id' => $vehicle->vehicle_id,
                'location_id' => $location->location_id,
            ]);
        }
        else{
            $location_vehicle = LocationVehicle::where('vehicle_id', $vehicle->vehicle_id,)
            ->where('location_id', $location->location_id)
            ->first();
        }
    
        //Get the commodity_id that corresponds to the commodity selected in the view
        $commodity=Commodity::where('commodity_name',$validatedData['commodity_name'])->first();
        
        //Store the transaction
       Transaction::create([
            'date'=> $validatedData['date'],
            'time'=> $validatedData['time'],
            'transaction_type' => $validatedData['transaction_type'],
            'transaction_status'=> $validatedData['transaction_status'],
            'staff_id'=>$validatedData['staff_id'],
            'commodity_id'=>$commodity->commodity_id,
            'volume'=>$validatedData['volume'],
            'plate_number'=>$validatedData['plate_number'],
            'vehicle_type_id'=>$validatedData['vehicle_type_id'],
            'name'=>$validatedData['name'],
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
        ]);        
        
        session()->flash('success', 'Trading outflow added successfully!');
        
        return redirect()->route('trading-outflow.create');
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
    public function edit(Transaction $trading_outflow)
    {
        $staffs = Staff::all(); // Fetch all staff
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $location_vehicles = LocationVehicle::with(['vehicle', 'location'])->get();
        $logged_in_staff = Auth::id();

        $transactions = Transaction::with(['commodity', 'staff', 'vehicle_type'])->get();
        return view('admin-pages.trading-outflow-form-edit', compact('transactions', 'trading_outflow', 'staffs', 'logged_in_staff', 'commodities', 'vehicle_types', 'location_vehicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $trading_outflow)
    {
        $validatedData = $request->validate([
            'transaction_status' => 'required',
            'transaction_type' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'staff_id' => 'required|exists:staff,staff_id',
            'commodity_name' => 'required|exists:commodities,commodity_name',
            'volume' => 'required|numeric', 
            'plate_number' => 'required',
            'vehicle_type_id' => 'required|exists:vehicle_types,vehicle_type_id',
            'name' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'region' => 'required',
        ]);

        // Find or create location
        $location = Location::firstOrCreate(
            [
                'barangay' => $validatedData['barangay'],
                'municipality' => $validatedData['municipality'],
                'province' => $validatedData['province'],
                'region' => $validatedData['region'],
            ]
        );

        // Find or create vehicle
        $vehicle = Vehicle::firstOrCreate(
            [
                'plate_number' => $validatedData['plate_number'],
            ],
            [
                'vehicle_name' => $validatedData['name'],
                'vehicle_type_id' => $validatedData['vehicle_type_id'],
            ]
        );

        // Find or create location_vehicle relationship
        $location_vehicle = LocationVehicle::firstOrCreate(
            [
                'vehicle_id' => $vehicle->vehicle_id,
                'location_id' => $location->location_id,
            ]
        );

        // Find the corresponding commodity
        $commodity = Commodity::where('commodity_name', $validatedData['commodity_name'])->first();

        // Update the transaction
        $trading_outflow->update([
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
            'transaction_type' => $validatedData['transaction_type'],
            'transaction_status' => $validatedData['transaction_status'],
            'staff_id' => $validatedData['staff_id'],
            'commodity_id' => $commodity->commodity_id,
            'volume' => $validatedData['volume'],
            'plate_number' => $validatedData['plate_number'],
            'vehicle_type_id' => $validatedData['vehicle_type_id'],
            'name' => $validatedData['name'],
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
        ]);

        session()->flash('success', 'Trading outflow updated successfully!');
        return redirect()->route('trading-outflow.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the transaction by ID
            $trading_outflow = Transaction::findOrFail($id);

            // Delete the transaction
            $trading_outflow->delete();

            // Flash success message
            session()->flash('success', 'Trading outflow deleted successfully!');
        } catch (QueryException $e) {
            // Handle any errors, e.g., if the transaction can't be deleted
            session()->flash('error', 'Error deleting trading inflow: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle any other exceptions
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
        }

        // Redirect to the index page or the relevant page
        return redirect()->route('trading-outflow.index');
    
    }
}
