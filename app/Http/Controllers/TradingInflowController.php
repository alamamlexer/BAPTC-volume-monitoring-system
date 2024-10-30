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
use App\Models\Facilitator;
use App\Models\FacilitatorLocationVehicle;
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
        // For the graph

        // Get start and end dates from request, with defaults
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());


        $amPmFilter = $request->input('amPmFilter');
        $attendantFilter = $request->input('attendantFilter');
        $commodityFilter = $request->input('commodityFilter');
        $productionOriginFilter = $request->input('productionOriginFilter');
        $facilitatorFilter = $request->input('facilitatorFilter');

        $trading_inflows = Transaction::where('transaction_type', 'trading inflow')
            ->where('transaction_status', 'regular')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator'])
            ->get();





        // For the table
        $query = Transaction::where('transaction_type', 'trading inflow')
            ->where('transaction_status', 'regular')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator']);

        $staffId = $request->input('staff_id');
        $timeFilter = $request->input('time_filter');
        $commodityId = $request->input('commodity_filter');
        $municipality = $request->input('municipality_filter');

        // Apply filters if provided
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
        $trading_inflows_graph = $query->get();
        $trading_inflows_table = $query->paginate(5);

        if ($request->ajax()) {
            return response()->json([
                'data' => $trading_inflows_table->items(),
                'current_page' => $trading_inflows_table->currentPage(),
                'last_page' => $trading_inflows_table->lastPage(),
                'total' => $trading_inflows_table->total(),

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

        foreach ($trading_inflows_graph as $inflow) {
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



        //total vehicle today
        $vehicle = Transaction::where('transaction_type', 'trading inflow')
            ->where('transaction_status', 'regular')
            ->whereDate('date', Carbon::today())
            ->count('plate_number');
        $today_vehicle = number_format($vehicle);

        //total volume today
        $volume = Transaction::where('transaction_type', 'trading inflow')
            ->where('transaction_status', 'regular')
            ->whereDate('date', Carbon::today())
            ->sum('volume');
        $today_volume = number_format($volume, 2);


        $user = Auth::user();
        $userId = Auth::id();
        if ($user->type == 0) {
            return view('admin-pages.trading-inflow-report', compact(
                'today_volume',
                'today_vehicle',
                'trading_inflows_graph',
                'trading_inflows_table',
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
            return view('staff-pages.staff-trading-inflow-report', compact(
                'today_volume',
                'today_vehicle',
                'trading_inflows_graph',
                'trading_inflows_table',
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
        
        $amPmFilter = $request->input('amPmFilter');
        $attendantFilter = $request->input('attendantFilter');
        $commodityFilter = $request->input('commodityFilter');
        $productionOriginFilter = $request->input('productionOriginFilter');
        $facilitatorFilter = $request->input('facilitatorFilter');
        
        $temporary_transaction = Transaction::where('transaction_status', 'temporary')
            ->where('transaction_type', 'trading inflow')
            ->where('date', $currentDate)
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
        $municipalities = Transaction::distinct()->pluck('municipality');
        $facilitators = Facilitator::all();
        $logged_in_staff = Auth::id();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();


        $user = Auth::user();
        
        
        if ($user->type == 0) {
            return view('admin-pages.trading-inflow-form-create', compact(
                'defaultTime',
                'staffs',
                'productionOrigins',
                'facilitator_location_vehicles',
                'facilitators',
                'temporary_transactions',
                'logged_in_staff',
                'vehicle_types',
                'commodities',
                'municipalities'
             
            ));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-trading-inflow-form-create', compact(
                'defaultTime',
                'staffs',
                'productionOrigins',
                'facilitator_location_vehicles',
                'facilitators',
                'temporary_transactions',
                'logged_in_staff',
                'vehicle_types',
                'commodities',
                'municipalities'
               
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
        $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();

        if (!$vehicle) {
            
            $vehicle= Vehicle::create([
                'plate_number' => $validatedData['plate_number']?? null,
                'vehicle_name' => $validatedData['name']?? null,
                'vehicle_type_id' => $validatedData['vehicle_type_id']?? null,
            ]);
       
        } else {
            Vehicle::where('plate_number', $validatedData['plate_number'])->first();
        }
        
        
        $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();

        $facilitator = Facilitator::where('facilitator_name', $validatedData['facilitator_name'])->first();
        
        //Storing a link in the address and location if there is no existing record
        $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id,)
            ->where('location_id', $location->location_id?? null)
            ->where('facilitator_id', $facilitator->facilitator_id?? null)
            ->first();
            
        if (!$facilitator_location_vehicles) {
        $facilitator_location_vehicles = FacilitatorLocationVehicle::create([
                'vehicle_id' => $vehicle->vehicle_id,
                'location_id' => $location->location_id,
                'facilitator_id' => $facilitator->facilitator_id?? null,
        ]);
            $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id,)
                ->where('location_id', $location->location_id)
                ->where('facilitator_id', $facilitator->facilitator_id)
                ->first();
        }

        //Get the commodity_id that corresponds to the commodity selected in the view
        $commodity = Commodity::where('commodity_name', $validatedData['commodity_name'])->first();

        //Store the transaction
        $test=Transaction::create([
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
        session()->flash('success', 'Trading inflow added successfully!');

    $user = Auth::user();
    if ($user->type == 0) {
        return redirect()->route('trading-inflow.create');
    } elseif ($user->type == 1) {
        return redirect()->route('staff-trading-inflow.create');
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
    public function edit(Transaction $trading_inflow)
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
            return view('admin-pages.trading-inflow-form-edit', compact(
                'transactions',
                'productionOrigins',
                'facilitators',
                'facilitator_location_vehicles',
                'trading_inflow',
                'staffs',
                'logged_in_staff',
                'commodities',
                'vehicle_types',
            ));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-trading-inflow-form-edit', compact(
                'transactions',
                'productionOrigins',
                'facilitators',
                'facilitator_location_vehicles',
                'trading_inflow',
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
        $trading_inflow->update([
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

        session()->flash('success', 'Trading inflow updated successfully!');
        $user = Auth::user();

        if ($trading_inflow->transaction_status === 'temporary') {
            if ($user->type == 0) {
                return redirect()->route('trading-inflow.create');
            } elseif ($user->type == 1) {
                return redirect()->route('staff-trading-inflow.create');
            }
        }

        if ($user->type == 0) {
            return redirect()->route('trading-inflow.index');
        } elseif ($user->type == 1) {
            return redirect()->route('staff-trading-inflow.index');
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
            $trading_inflow = Transaction::findOrFail($id);

            // Check if the user is authorized to delete the transaction
            if ($user->type == 0 || ($user->type == 1 && $trading_inflow->staff_id == $user->id)) {
                // Delete the transaction
                $trading_inflow->delete();

                // Flash success message
                session()->flash('success', 'Trading inflow deleted successfully!');
            } else {
                session()->flash('error', 'You are not authorized to delete this transaction.');
            }
        } catch (QueryException $e) {
            // Handle any errors, e.g., if the transaction can't be deleted
            session()->flash('error', 'Error deleting trading inflow: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle any other exceptions
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
        
        // Redirect to the appropriate index page based on user type
        if ($user->type == 0) {
            if($trading_inflow->transaction_status=='temporary'){
            return redirect()->route('trading-inflow.create'); // Admin index
            }
            elseif($trading_inflow->transaction_status=='regular'){
            return redirect()->route('trading-inflow.index'); // Admin index
            }
            
        } elseif ($user->type == 1) {
            if($trading_inflow->transaction_status=='temporary'){
                return redirect()->route('staff-trading-inflow.create'); // Admin index
                }
                elseif($trading_inflow->transaction_status=='regular'){
                return redirect()->route('staff-trading-inflow.index'); // Admin index
                }
        }
    }
    public function submit()
    {
        $user = Auth::user(); // Get the authenticated user
        $userId = $user->id; // Get the authenticated user's ID

        // Update temporary transactions for the authenticated user
        $temporary_transactions = Transaction::where('transaction_status', 'temporary')
            ->where('transaction_type', 'trading inflow')
            ->where('staff_id', $userId)
            ->update([
                'transaction_status' => 'regular',
            ]);

        if ($temporary_transactions > 0) {
            session()->flash('success', 'Trading inflow submitted!');
        } else {
            session()->flash('error', 'No trading inflow added!');
        }

        // Redirect to the appropriate index page based on user type
        if ($user->type == 0) {
            return redirect()->route('trading-inflow.index'); // Admin index
        } elseif ($user->type == 1) {
            return redirect()->route('staff-trading-inflow.index'); // Staff index
        }
    }
}
