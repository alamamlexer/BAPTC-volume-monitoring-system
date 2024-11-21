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
use App\Models\Facilitator;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\FacilitatorLocationVehicle;
use Illuminate\Support\Facades\DB;

class TradingOutflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
 // Get start and end dates from request, with defaults
 $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
 $endDate = $request->input('end_date', Carbon::now()->toDateString());


 // For the table
 $query = Transaction::where('transaction_type', 'trading outflow')
     ->where('transaction_status', 'regular')
     ->whereBetween('date', [$startDate, $endDate])
     ->with(['staff', 'commodity', 'vehicle_type', 'facilitator']);
     

 $staffId = $request->input('staff_id');
 $timeFilter = $request->input('time_filter');
 $commodityId = $request->input('commodity_filter');
 $municipality = $request->input('municipality_filter');

 // Apply filters if provided
 if ($request->has('start_date') && $request->has('end_date')) {
     $query->whereBetween('date', [$request->start_date, $request->end_date]);
 }
 if ($startDate) {
     $query->where('date', '>=', $startDate); // Use >= to include all transactions from that date onward
 }
 if ($endDate) {
     $query->where('date', '<=', $endDate); // Use <= to include transactions up to that date
 }
 if ($staffId) {
     $query->where('staff_id', $staffId);
 }
 if ($timeFilter) {
     $query->where('time', $timeFilter);
 }
 if ($commodityId) {
     $query->where('commodity_id', $commodityId);
 }
 if ($municipality) {
     $query->where('municipality', $municipality);
 }
 // Fetch the paginated results
 $trading_outflows_graph = $query->get();
 $trading_outflows_table = $query->paginate( 5);

 if ($request->ajax()) {
     return response()->json([
         'data' => $trading_outflows_table->items(),
         'current_page' => $trading_outflows_table->currentPage(),
         'last_page' => $trading_outflows_table->lastPage(),
         'total' => $trading_outflows_table->total(),

     ]);
 }

 // Fetch all commodities
 $commodities = Commodity::all();

 // Fetch all staff members
 $staffs = Staff::all();

 // Fetch all facilitator members
 $facilitators = Facilitator::all();

 // Fetch distinct municipalities for the dropdown
 $municipalities = Transaction::distinct()->pluck('municipality');

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

 foreach ($trading_outflows_graph as $outflow) {
     $date = Carbon::parse($outflow->date)->toDateString();
     $commodity = $outflow->commodity->commodity_name;

     if (!isset($volumes[$commodity][$date])) {
         $volumes[$commodity][$date] = 0;
         $dates[] = $date;
     }

     $volumes[$commodity][$date] += $outflow->volume;

     if (!isset($totalVolumes[$date])) {
         $totalVolumes[$date] = 0;
     }
     $totalVolumes[$date] += $outflow->volume;
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



 //total vehicle today
 $vehicle = Transaction::where('transaction_type', 'trading outflow')
     ->where('transaction_status', 'regular')
     ->whereDate('date', Carbon::today())
     ->count('id');
 $today_vehicle = number_format($vehicle);

 //total volume today
 $volume = Transaction::where('transaction_type', 'trading outflow')
     ->where('transaction_status', 'regular')
     ->whereDate('date', Carbon::today())
     ->sum('volume');
 $today_volume = number_format($volume, 2);


 $user = Auth::user();
 $userId = Auth::id();
 if ($user->type == 0) {
     return view('admin-pages.trading-outflow-report', compact(
         'today_volume',
         'today_vehicle',
         'trading_outflows_graph',
         'trading_outflows_table',
         'request',
         'facilitators',
         'chartData',
         'dates',
         'startDate',
         'endDate',
         'commodities',
         'totalVolumeData',
         'staffs',
         'productionOrigins',
         'municipalities',
         'userId'
     ));
 } elseif ($user->type == 1) {
     return view('staff-pages.staff-trading-outflow-report', compact(
         'today_volume',
         'today_vehicle',
         'trading_outflows_graph',
         'trading_outflows_table',
         'request',
         'facilitators',
         'chartData',
         'dates',
         'startDate',
         'endDate',
         'commodities',
         'totalVolumeData',
         'staffs',
         'productionOrigins',
         'municipalities',
         'userId'
     ));
 }
}



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $currentHour = date('H'); // 24-hour format
        $defaultTime = ($currentHour < 12) ? 'AM' : 'PM';

        $currentDate = Carbon::today()->toDateString();
      
        $temporary_transaction = Transaction::where('transaction_status', 'temporary')
            ->where('transaction_type', 'trading outflow')
            ->whereDate('created_at', $currentDate)
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator']);
    
        // Fetch all commodities

        $staffId = $request->input('staff_id');
        $timeFilter = $request->input('time_filter');
        $commodityId = $request->input('commodity_filter');
        $municipality = $request->input('municipality_filter');
    
        // Apply filters if provided
        if ($staffId) {
            $temporary_transaction->where('staff_id', $staffId);
        }
        if ($timeFilter) {
            $temporary_transaction->where('time', $timeFilter);
        }
        if ($commodityId) {
            $temporary_transaction->where('commodity_id', $commodityId);
        }
        if ($municipality) {
            $temporary_transaction->where('municipality', $municipality);
        }
        
        $temporary_transactions = $temporary_transaction->paginate(5);
            
        if ($request->ajax()) {
            return response()->json([
                'data' => $temporary_transactions->items(),
                'current_page' => $temporary_transactions->currentPage(),
                'last_page' => $temporary_transactions->lastPage(),
                'total' => $temporary_transactions->total(),

            ]);
        }

        
        // Fetch distinct production origins
        $productionOrigins = Location::select('barangay', 'municipality', 'province', 'region')
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
        $municipalities = Transaction::distinct()->pluck('municipality');
        $facilitators = Facilitator::all();
        $logged_in_staff = Auth::id();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $locations= Location::all();
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();


        $user = Auth::user();
        
        
        if ($user->type == 0) {
            return view('admin-pages.trading-outflow-form-create', compact(
                'defaultTime',
                'staffs',
                'productionOrigins',
                'facilitator_location_vehicles',
                'facilitators',
                'temporary_transactions',
                'logged_in_staff',
                'vehicle_types',
                'commodities',
                'municipalities',
                'locations'
             
            ));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-trading-outflow-form-create', compact(
                'defaultTime',
                'staffs',
                'productionOrigins',
                'facilitator_location_vehicles',
                'facilitators',
                'temporary_transactions',
                'logged_in_staff',
                'vehicle_types',
                'commodities',
                'municipalities',
                'locations'
               
            ));
        }
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
            'facilitator_name' => 'nullable',
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
            Location::Create([
                'barangay' => $validatedData['barangay'],
                'municipality' => $validatedData['municipality'],
                'province' => $validatedData['province'],
                'region' => $validatedData['region'],
            ]);
        } else {
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
        
        if(!empty($validatedData['plate_number'])){
        $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();
        if (!$vehicle) {
            $vehicle= Vehicle::create([
                'plate_number' => $validatedData['plate_number'],
                'vehicle_name' => $validatedData['name']?? null,
                'vehicle_type_id' => $validatedData['vehicle_type_id']?? null,
            ]);
        } else {
            $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();
        }
        
        }
        else{
            $vehicle=null;
        }
        
        
        
        
        //storing facilitator
        if(!empty($validatedData['facilitator_name'])){
            $facilitator = Facilitator::where( 'facilitator_name', $validatedData['facilitator_name'])->first();
        }
        else{
            $facilitator=null;
        }
        
        
        if(!empty($vehicle)){
        
            if( !empty($location) && !empty($facilitator)){
            $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                ->where('location_id', $location->location_id)
                ->where('facilitator_id', $facilitator->facilitator_id)
                ->first();
            }
                elseif(!$location && !empty($facilitator)){
                $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                    ->where('location_id', null)
                    ->where('facilitator_id', $facilitator->facilitator_id)
                    ->first();
                }
                else{
                    $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                        ->where('location_id', $location->location_id)
                        ->where('facilitator_id', null)
                        ->first();
                    }
                
            if (!$facilitator_location_vehicles){
            $facilitator_location_vehicles = FacilitatorLocationVehicle::create([
                    'vehicle_id' => $vehicle->vehicle_id,
                    'location_id' => $location->location_id?? null,
                    'facilitator_id' => $facilitator->facilitator_id?? null,
            ]);
            }
        }
        
        //Get the commodity_id that corresponds to the commodity selected in the view
        $commodity = Commodity::where('commodity_name', $validatedData['commodity_name'])->first();

        //Store the transaction
        Transaction::create([
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
            'transaction_type' => $validatedData['transaction_type'],
            'transaction_status' => $validatedData['transaction_status'],
            'staff_id' => $validatedData['staff_id'],
            'commodity_id' => $commodity->commodity_id,
            'volume' => $validatedData['volume'],
            'plate_number' => $validatedData['plate_number'] ?? null,
            'vehicle_type_id' => $validatedData['vehicle_type_id'] ?? null,
            'name' => $validatedData['name'] ?? null,
            'facilitator_id' => $facilitator->facilitator_id?? null,
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
        ]);
       
        session()->flash('success', 'Trading Outflow added successfully!');

        $user = Auth::user();

        $author = Auth::user();

        Log::create([
            'action_type'=>'create',
            'transaction' => implode(', ', array_filter([
                            isset($validatedData['transaction_type']) ? "{$validatedData['transaction_type']}" : null,
                            isset($validatedData['transaction_status']) ? "{$validatedData['transaction_status']}" : null,
                            isset($commodity->commodity_name) ? "{$commodity->commodity_name}" : null,
                            isset($validatedData['volume']) ? "{$validatedData['volume']}" .' kg': null,
                            isset($validatedData['plate_number']) ? "{$validatedData['plate_number']}" : null,
                            isset($validatedData['barangay']) ? "{$validatedData['barangay']}" : null,
                            isset($validatedData['municipality']) ? "{$validatedData['municipality']}" : null,
                            isset($validatedData['province']) ? "{$validatedData['province']}" : null,
                            isset($validatedData['region']) ? "{$validatedData['region']}" : null,
                            isset($facilitator->facilitator_name) ? "{$facilitator->facilitator_name}" : null,
                            ])),
            'author'=> $author->username,
        ]);


    $user = Auth::user();
    if ($user->type == 0) {
        return redirect()->route('trading-outflow.create');
    } elseif ($user->type == 1) {
        return redirect()->route('staff-trading-outflow.create');
    }
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
        $facilitators = Facilitator::all();
        $staffs = Staff::all(); // Fetch all staff
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $logged_in_staff = Auth::id();
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();
        $transactions = Transaction::with(['commodity', 'staff', 'vehicle_type'])->get();

        $user = Auth::user();
        if ($user->type == 0) {
            return view('admin-pages.trading-outflow-form-edit', compact(
                'transactions',
                'productionOrigins',
                'facilitators',
                'facilitator_location_vehicles',
                'trading_outflow',
                'staffs',
                'logged_in_staff',
                'commodities',
                'vehicle_types',
            ));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-trading-outflow-form-edit', compact(
                'transactions',
                'productionOrigins',
                'facilitators',
                'facilitator_location_vehicles',
                'trading_outflow',
                'staffs',
                'logged_in_staff',
                'commodities',
                'vehicle_types',
            ));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $trading_outflow)
    {
        $outdated_data = $trading_outflow->toArray();
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
            'facilitator_name' => 'nullable|exists:facilitators,facilitator_name',
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
        
        if(!empty($validatedData['plate_number'])){
        $vehicle = Vehicle::firstOrCreate(
            [
                'plate_number' => $validatedData['plate_number'],
            ],
            [
                'vehicle_name' => $validatedData['name'],
                'vehicle_type_id' => $validatedData['vehicle_type_id'],
            ]
        );
        }
        
        $facilitator = Facilitator::where('facilitator_name', $validatedData['facilitator_name'])->first();
        // Find or create location_vehicle relationship
        
        if(!empty($validatedData['plate_number'])){
        $facilitator_location_vehicle = FacilitatorLocationVehicle::firstOrCreate(
            [
                'vehicle_id' => $vehicle->vehicle_id,
                'location_id' => $location->location_id,
                'facilitator_id' => $facilitator->facilitator_id,
            ]
        );
        }
        

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
            'plate_number' => $validatedData['plate_number'] ?? null,
            'vehicle_type_id' => $validatedData['vehicle_type_id'] ?? null,
            'facilitator_id' => $facilitator->facilitator_id ?? null,
            'name' => $validatedData['name'] ?? null,
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
        ]);

        session()->flash('success', 'Trading outflow updated successfully!');
        $user = Auth::user();
        $author = Auth::user();
        $updated_data = $trading_outflow->getChanges();
        
        $test = Log::create([
            'action_type' => 'update',
            'transaction' =>  implode(', ', array_filter([
                isset($outdated_data['transaction_type']) ? $outdated_data['transaction_type'] : null,
                isset($outdated_data['transaction_status']) ? $outdated_data['transaction_status'] : null,
                isset($outdated_data['commodity_name']) ? $outdated_data['commodity_name'] : null,
                isset($outdated_data['volume']) ? $outdated_data['volume'] . ' kg' : null, 
                isset($outdated_data['plate_number']) ? $outdated_data['plate_number'] : null,
                isset($outdated_data['barangay']) ? $outdated_data['barangay'] : null,
                isset($outdated_data['municipality']) ? $outdated_data['municipality'] : null,
                isset($outdated_data['province']) ? $outdated_data['province'] : null,
                isset($outdated_data['region']) ? $outdated_data['region'] : null,

                isset($outdated_data['facilitator_name']) ? $outdated_data['facilitator_name'] : null,
            ])) . " || UPDATED -> " . implode(', ', array_filter([
                isset($updated_data['transaction_type']) ? $updated_data['transaction_type'] : null,
                isset($updated_data['transaction_status']) ? $updated_data['transaction_status'] : null,
                isset($commodity->commodity_name) ? $commodity->commodity_name : null,
                isset($updated_data['volume']) ? $updated_data['volume'] . ' kg' : null, 
                isset($updated_data['plate_number']) ? $updated_data['plate_number'] : null,
                isset($updated_data['barangay']) ? 'Barangay: '.$updated_data['barangay'] : null,
                isset($updated_data['municipality']) ? 'Municipality: '.$updated_data['municipality'] : null,
                isset($updated_data['province']) ? 'Province: '.$updated_data['province'] : null,
                isset($updated_data['region']) ?'Region: '. $updated_data['region'] : null,
                isset($facilitator->facilitator_name) ? $facilitator->facilitator_name : null,
            ])),
            'author' => $author->username,
        ]);
        if ($trading_outflow->transaction_status === 'temporary') {
            if ($user->type == 0) {
                return redirect()->route('trading-outflow.create');
            } elseif ($user->type == 1) {
                return redirect()->route('staff-trading-outflow.create');
            }
        }

        if ($user->type == 0) {
            return redirect()->route('trading-outflow.index');
        } elseif ($user->type == 1) {
            return redirect()->route('staff-trading-outflow.index');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user(); // Get the authenticated user

        try {
            // Find the transaction by ID
            $trading_outflow = Transaction::findOrFail($id);

            // Check if the user is authorized to delete the transaction
            if ($user->type == 0 || ($user->type == 1 && $trading_outflow->staff_id == $user->id)) {
                // Delete the transaction
                $trading_outflow->delete();

                // Flash success message
                session()->flash('success', 'Trading outflow deleted successfully!');
                $user = Auth::user();
        
        
        $author = Auth::user();
        Log::create([
            'action_type'=>'delete',
            'transaction' => implode(', ', array_filter([
                            isset($trading_outflow->transaction_type) ? $trading_outflow->transaction_type: null,
                            isset($trading_outflow->transaction_status) ? $trading_outflow->transaction_status: null,
                            isset($trading_outflow->commodity->commodity_name) ? $trading_outflow->commodity->commodity_name: null,
                            isset($trading_outflow->volume) ? $trading_outflow->volume .' kg': null,
                            isset($trading_outflow->plate_number) ? $trading_outflow->plate_number: null,
                            isset($trading_outflow->barangay) ? $trading_outflow->barangay: null,
                            isset($trading_outflow->municipality) ? $trading_outflow->municipality: null,
                            isset($trading_outflow->province) ? $trading_outflow->province: null,
                            isset($trading_outflow->region) ? $trading_outflow->region: null,
                            isset($trading_outflow->facilitator->facilitator_name) ? $trading_outflow->facilitator->facilitator_name: null,
                            ])),
            'author'=> $author->username,
        ]);
            } else {
                session()->flash('error', 'You are not authorized to delete this transaction.');
            }
        } catch (QueryException $e) {
            // Handle any errors, e.g., if the transaction can't be deleted
            session()->flash('error', 'Error deleting trading outflow: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle any other exceptions
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
        
        // Redirect to the appropriate index page based on user type
        if ($user->type == 0) {
            if($trading_outflow->transaction_status=='temporary'){
            return redirect()->route('trading-outflow.create'); // Admin index
            }
            elseif($trading_outflow->transaction_status=='regular'){
            return redirect()->route('trading-outflow.index'); // Admin index
            }
            
        } elseif ($user->type == 1) {
            if($trading_outflow->transaction_status=='temporary'){
                return redirect()->route('staff-trading-outflow.create'); // Admin index
                }
                elseif($trading_outflow->transaction_status=='regular'){
                return redirect()->route('staff-trading-outflow.index'); // Admin index
                }
        }
    }

    public function submit()
    {
        $user = Auth::user(); // Get the authenticated user
        $userId = $user->id; // Get the authenticated user's ID
        // dd($user->type);
        if($user->type == 0){
            $temporary_transactions = Transaction::where('transaction_status', 'temporary')
            ->where('transaction_type', 'trading outflow')
            ->update([
                'transaction_status' => 'regular',
            ]);
            if ($temporary_transactions > 0) {
                $author = Auth::user();
            
                Log::create([
                'action_type'=>'submit',
                'transaction' => 'trading inflow',
                'author'=> $author->username,
            ]);
                session()->flash('success', 'Trading outflow submitted!');
            } else {
                session()->flash('error', 'No trading outflow added!');
            }
        }else{
        $temporary_transactions = Transaction::where('transaction_status', 'temporary')
            ->where('transaction_type', 'trading outflow')
            ->where('staff_id', $userId)
            ->update([
                'transaction_status' => 'regular',
            ]);
            if ($temporary_transactions > 0) {
                session()->flash('success', 'Trading outflow submitted!');
            } else {
                session()->flash('error', 'No trading outflow added!');
            }
        }
        // Update temporary transactions for the authenticated user

        // Redirect to the appropriate index page based on user type
        if ($user->type == 0) {
            return redirect()->route('trading-outflow.index'); // Admin index
        } elseif ($user->type == 1) {
            return redirect()->route('staff-trading-outflow.index'); // Staff index
        }
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
    
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getSheetByName('TRUCKINGS');
    
        if (!$worksheet) {
            return back()->with('error', 'Sheet for trading outflow not found!');
        }
    
        $rows = [];
        $batchSize = 500; // Define batch size for inserts
    
        foreach ($worksheet->getRowIterator(8) as $row) {
            $rowIndex = $row->getRowIndex(); // Current row index
            $province = $worksheet->getCell("F{$rowIndex}")->getValue();
    
            // Fetch location details
            // $location = $barangay 
            //     ? Location::where('barangay', $barangay)->first() 
            //     : null;
    
            $municipality = null;
            $region = null;
            $barangay = null;
    
            $excel_date = $worksheet->getCell("A{$rowIndex}")->getValue();
            $date = is_numeric($excel_date) 
                ? Date::excelToDateTimeObject($excel_date)->format('Y-m-d') 
                : null;
                if (!$date) {
                    continue;
                }
            $time = $worksheet->getCell("B{$rowIndex}")->getValue();
            $staff_name = $worksheet->getCell("G{$rowIndex}")->getValue();
            // $commodity_name = $worksheet->getCell("N{$rowIndex}")->getValue();
            $volume = $worksheet->getCell("E{$rowIndex}")->getValue();
            $plate_number = $worksheet->getCell("C{$rowIndex}")->getValue();
            // $vehicle_type_name = $worksheet->getCell("AI{$rowIndex}")->getValue();
            $name = $worksheet->getCell("D{$rowIndex}")->getValue();
            $facilitator_name = $worksheet->getCell("Q{$rowIndex}")->getValue();
    
    
    if(!$time){
            $time='';
            }
            // Fetch or create related models
            if ($staff_name) {
                $staff = Staff::firstOrCreate(
                    ['staff_name' => $staff_name], // Check for this condition
                    ['staff_name' => $staff_name,
                                'email' => "baptc.2015@gmail.com",
                                'contact_number' => "09999999999",
                                                            ]  // The values to use if a new record is created
                );
            
                // Now, you can directly get the staff_id
                $staff_id = $staff->staff_id;
            } else {
                $staff = Staff::firstOrCreate(
                    ['staff_name' => "Unknown"], // Check for this condition
                    ['staff_name' => "Unknown",
                                'email' => "baptc.2015@gmail.com",
                                'contact_number' => "09999999999",
                                                            ]  // The values to use if a new record is created
                );
            }
            $commodity = Commodity::firstOrCreate(
                ['commodity_name' => "Sari-sari"], // Check for this condition
                ['staff_name' => "Sari-sari",]  // The values to use if a new record is created
            );
                // if ($commodity_name) {
                //     $commodity = Commodity::firstOrCreate(['commodity_name' => $commodity_name]);
                //     $commodity_id = $commodity->commodity_id;
                // } else {
                //     // Handle case when commodity_name is missing
                //     $commodity_id = null; // Or provide a default commodity ID if necessary
                //     // Optionally log or skip this row
                //     continue; // Skip inserting this row if $commodity_name is required
                // }
            $vehicle_type_id = $plate_number 
                ? Vehicle::firstOrCreate(['plate_number' => $plate_number])->vehicle_type_id 
                : null;
    
            $facilitator_id = $facilitator_name 
                ? Facilitator::firstOrCreate(['facilitator_name' => $facilitator_name])->facilitator_id 
                : null;
                // if(!empty($vehicle)){
        
                //     if( !empty($location) && !empty($facilitator)){
                //     $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                //         ->where('location_id', $location->location_id)
                //         ->where('facilitator_id', $facilitator->facilitator_id)
                //         ->first();
                //     }
                //         elseif(!$location && !empty($facilitator)){
                //         $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                //             ->where('location_id', null)
                //             ->where('facilitator_id', $facilitator->facilitator_id)
                //             ->first();
                //         }
                //         else{
                //             $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                //                 ->where('location_id', $location->location_id)
                //                 ->where('facilitator_id', null)
                //                 ->first();
                //             }
                        
                //     if (!$facilitator_location_vehicles){
                //     $facilitator_location_vehicles = FacilitatorLocationVehicle::create([
                //             'vehicle_id' => $vehicle->vehicle_id,
                //             'location_id' => $location->location_id?? null,
                //             'facilitator_id' => $facilitator->facilitator_id?? null,
                //     ]);
                //     }
                // }
            // Prepare the row for insertion
            $rows[] = [
                'date' => $date,
                'time' => $time,
                'transaction_type' => "trading outflow",
                'transaction_status' => "regular",
                'staff_id' => $staff_id,
                'commodity_id' =>$commodity->commodity_id,
                'volume' => $volume,
                'plate_number' => $plate_number,
                'vehicle_type_id' => $vehicle_type_id,
                'name' => $name,
                'facilitator_id' => $facilitator_id,
                'barangay' => 'N/A',
                'municipality' => 'N/A',
                'province' => $province,
                'region' => 'N/A',
                'created_at' => now(),
                'updated_at' => now(),
            ];
    
            // Insert in batches
            if (count($rows) >= $batchSize) {
                Transaction::insert($rows);
                $rows = []; // Reset rows for the next batch
            }
        }
    
        // Insert remaining rows
        if (!empty($rows)) {
            Transaction::insert($rows);
        }
    
        // Log the import
        Log::create([
            'action_type' => 'import',
            'transaction' => "trading outflow",
            'author' => Auth::user()->username,
        ]);
    
        return back()->with('success', 'Trading Outflow imported successfully!');
    }
}