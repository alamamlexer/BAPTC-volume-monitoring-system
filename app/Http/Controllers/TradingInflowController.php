<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Transaction;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Commodity;
use App\Models\Location;
use App\Models\LocationVehicle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TradingInflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Get start and end dates from request, with defaults
    $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
    $endDate = $request->input('end_date', Carbon::now());

    // Fetch trading inflows within the date range
    $trading_inflows = Transaction::where('transaction_type', 'trading inflow')
        ->whereBetween('date', [$startDate, $endDate])
        ->with(['staff', 'commodity', 'vehicle_type'])
        ->get();
    
     // Fetch trading inflows within the date range
     $trading_inflows_table = Transaction::where('transaction_type', 'trading inflow')
     ->whereBetween('date', [$startDate, $endDate])
     ->with(['staff', 'commodity', 'vehicle_type'])
     ->paginate(5);

    // Fetch all commodities
    $commodities = Commodity::all();

    // Fetch all staff members
    $staffs = Staff::all();

    // Fetch distinct production origins
    $productionOrigins = Transaction::select('barangay', 'municipality', 'province', 'region')
        ->distinct()
        ->get()
        ->map(function ($location) {
            return [
                'barangay' => $location->barangay,
                'municipality' => $location->municipality,
                'province' => $location->province,
                'region' => $location->region,
                'full_address' => "{$location->barangay}, {$location->municipality}, {$location->province}, {$location->region}"
            ];
        });

    $volumes = [];
    $totalVolumes = [];
    $dates = [];

    foreach ($trading_inflows as $inflow) {
        $date = Carbon::parse($inflow->date)->toDateString();
        $commodity = $inflow->commodity->commodity_name;

        if (!isset($volumes[$commodity][$date])) {
            $volumes[$commodity][$date] = 0;
            $dates[] = $date;
        }

        $volumes[$commodity][$date] += $inflow->volume;

        if (!isset($totalVolumes[$date])) {
            $totalVolumes[$date] = 0;
        }
        $totalVolumes[$date] += $inflow->volume;
    }

    $dateRange = [];
    for ($date = Carbon::parse($startDate); $date->lessThanOrEqualTo(Carbon::parse($endDate)); $date->addDay()) {
        $dateRange[] = $date->toDateString();
    }

    foreach ($volumes as $commodity => $data) {
        foreach ($dateRange as $date) {
            if (!isset($data[$date])) {
                $data[$date] = 0;
            }
        }
        ksort($data);
        $volumes[$commodity] = $data;
    }

    $chartData = [];
    foreach ($volumes as $commodity => $data) {
        $chartData[] = [
            'name' => $commodity,
            'data' => array_values($data),
        ];
    }

    $totalVolumeData = array_values(array_map(function ($date) use ($totalVolumes) {
        return $totalVolumes[$date] ?? 0;
    }, $dateRange));

    $dates = array_unique(array_merge($dates, $dateRange));
    sort($dates);

    return view('admin-pages.trading-inflow-report', compact('trading_inflows','trading_inflows_table', 'chartData', 'dates', 'startDate', 'endDate', 'commodities', 'totalVolumeData', 'staffs', 'productionOrigins'));
}

    
    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $currentDate = Carbon::today()->toDateString(); 

        $temporary_transactions= Transaction::where('transaction_status', 'temporary')
                                            ->where('date', $currentDate) 
                                            ->with(['staff', 'commodity', 'vehicle_type'])
                                            ->paginate(5);
                                             // Fetch all commodities
    
        // Fetch distinct production origins
        $productionOrigins = Transaction::select('barangay', 'municipality', 'province', 'region')
            ->distinct()
            ->get()
            ->map(function ($location) {
                return [
                    'barangay' => $location->barangay,
                    'municipality' => $location->municipality,
                    'province' => $location->province,
                    'region' => $location->region,
                    'full_address' => "{$location->barangay}, {$location->municipality}, {$location->province}, {$location->region}"
                ];
            });
            
        $logged_in_staff=Auth::id();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $location_vehicles = LocationVehicle::with(['vehicle', 'location'])->get();
        return view('admin-pages.trading-inflow-form-create',compact('staffs','productionOrigins','temporary_transactions','logged_in_staff','vehicle_types','commodities','location_vehicles'));
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
            'plate_number' => 'nullable|string',  
            'vehicle_type_id' => 'nullable|exists:vehicle_types,vehicle_type_id', 
            'name' => 'nullable|string', 
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
            'plate_number'=>$validatedData['plate_number']?? null,
            'vehicle_type_id'=>$validatedData['vehicle_type_id']?? null,
            'name'=>$validatedData['name']?? null,
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
    public function edit(Transaction $trading_inflow)
    {
        $staffs = Staff::all(); // Fetch all staff
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $location_vehicles = LocationVehicle::with(['vehicle', 'location'])->get();
        $logged_in_staff = Auth::id();

        $transactions = Transaction::with(['commodity', 'staff', 'vehicle_type'])->get();
        return view('admin-pages.trading-inflow-form-edit', compact('transactions', 'trading_inflow', 'staffs', 'logged_in_staff', 'commodities', 'vehicle_types', 'location_vehicles'));
 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $trading_inflow)
    {
        $validatedData = $request->validate([
            'transaction_status' => 'required',
            'transaction_type' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'staff_id' => 'required|exists:staff,staff_id', 
            'commodity_name' => 'required|exists:commodities,commodity_name', 
            'volume' => 'required|numeric', 
            'plate_number' => 'nullable|string',  
            'vehicle_type_id' => 'nullable|exists:vehicle_types,vehicle_type_id', 
            'name' => 'nullable|string', 
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
        $trading_inflow->update([
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
            'transaction_type' => $validatedData['transaction_type'],
            'transaction_status' => $validatedData['transaction_status'],
            'staff_id' => $validatedData['staff_id'],
            'commodity_id' => $commodity->commodity_id,
            'volume' => $validatedData['volume'],
            'plate_number' => $validatedData['plate_number']?? null,
            'vehicle_type_id' => $validatedData['vehicle_type_id']?? null,
            'name' => $validatedData['name']?? null,
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,  
        ]);

        if($trading_inflow->transaction_status==='temporary'){
            return redirect()->route('trading-inflow.create');
        }

        session()->flash('success', 'Trading inflow updated successfully!');
        return redirect()->route('trading-inflow.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the transaction by ID
            $trading_inflow = Transaction::findOrFail($id);

            // Delete the transaction
            $trading_inflow->delete();

            // Flash success message
            session()->flash('success', 'Trading inflow deleted successfully!');
        } catch (QueryException $e) {
            // Handle any errors, e.g., if the transaction can't be deleted
            session()->flash('error', 'Error deleting trading inflow: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle any other exceptions
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
        }

        // Redirect to the index page or the relevant page
        return redirect()->route('trading-inflow.create');
    }
    public function submit(){
        $userId = Auth::id();
        $temporary_transactions=Transaction::where('transaction_status','temporary')
                                           ->where('staff_id',$userId)
                                        ->update([
                        'transaction_status' => 'regular',
                    ]);
        if ($temporary_transactions > 0) {
           session()->flash('success', 'Trading inflow submitted!');
        }
        else{
             session()->flash('error', 'No trading inflow added!'); 
        }
        return redirect()->route('trading-inflow.index');
          }
    }
   

