<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\Log;
use App\Models\VehicleType;
use App\Models\Commodity;
use App\Models\Location;
use App\Models\LocationVehicle;
use App\Models\Facilitator;
use App\Models\FacilitatorLocationVehicle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ShortTripInflowAndOutflowController extends Controller
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
        $query = Transaction::whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->where('transaction_status', 'regular')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator']);


        $staffId = $request->input('staff_id');
        $timeFilter = $request->input('time_filter');
        $commodityId = $request->input('commodity_filter');
        $municipality = $request->input('municipality_filter');
        $typeFilter = $request->input('type_filter');

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
        if ($typeFilter) {
            $query->where('transaction_type', $typeFilter);
        }
        if ($typeFilter) {
            $query->where('transaction_type', $typeFilter);
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

        // Separate volumes for inflow and outflow
        $inflowVolumes = [];
        $outflowVolumes = [];
        $totalInflowVolumes = [];
        $totalOutflowVolumes = [];
        $dates = [];

        // Process the trading inflows graph
        foreach ($trading_inflows_graph as $transaction) {
            $date = Carbon::parse($transaction->date)->toDateString();
            $commodity = $transaction->commodity->commodity_name;

            // Inflow data
            if ($transaction->transaction_type == 'short trip inflow') {
                if (!isset($inflowVolumes[$commodity][$date])) {
                    $inflowVolumes[$commodity][$date] = 0;
                    $dates[] = $date;
                }
                $inflowVolumes[$commodity][$date] += $transaction->volume;

                if (!isset($totalInflowVolumes[$date])) {
                    $totalInflowVolumes[$date] = 0;
                }
                $totalInflowVolumes[$date] += $transaction->volume;
            }

            // Outflow data
            if ($transaction->transaction_type == 'short trip outflow') {
                if (!isset($outflowVolumes[$commodity][$date])) {
                    $outflowVolumes[$commodity][$date] = 0;
                    $dates[] = $date;
                }
                $outflowVolumes[$commodity][$date] += $transaction->volume;

                if (!isset($totalOutflowVolumes[$date])) {
                    $totalOutflowVolumes[$date] = 0;
                }
                $totalOutflowVolumes[$date] += $transaction->volume;
            }
        }

        // Ensure date range is complete
        $dateRange = [];
        for ($date = Carbon::parse($startDate); $date->lessThanOrEqualTo(Carbon::parse($endDate)); $date->addDay()) {
            $dateRange[] = $date->toDateString();
        }

        // Fill in missing dates for both inflows and outflows
        foreach ($inflowVolumes as $commodity => $data) {
            foreach ($dateRange as $date) {
                if (!isset($data[$date])) {
                    $data[$date] = 0;
                }
            }
            ksort($data);
            $inflowVolumes[$commodity] = $data;
        }

        foreach ($outflowVolumes as $commodity => $data) {
            foreach ($dateRange as $date) {
                if (!isset($data[$date])) {
                    $data[$date] = 0;
                }
            }
            ksort($data);
            $outflowVolumes[$commodity] = $data;
        }

        // Prepare chart data for inflows and outflows
        $inflowChartData = [];
        $outflowChartData = [];

        foreach ($inflowVolumes as $commodity => $data) {
            $inflowChartData[] = [
                'name' => $commodity,
                'data' => array_values($data),
            ];
        }

        foreach ($outflowVolumes as $commodity => $data) {
            $outflowChartData[] = [
                'name' => $commodity,
                'data' => array_values($data),
            ];
        }

        // Map total volume data for inflows and outflows
        $inflowVolumeData = array_values(array_map(function ($date) use ($totalInflowVolumes) {
            return $totalInflowVolumes[$date] ?? 0;
        }, $dateRange));

        $outflowVolumeData = array_values(array_map(function ($date) use ($totalOutflowVolumes) {
            return $totalOutflowVolumes[$date] ?? 0;
        }, $dateRange));

        // Merge dates and sort them
        $dates = array_unique(array_merge($dates, $dateRange));
        sort($dates);

        // Calculate total vehicle and volume today
        $vehicle = Transaction::whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->where('transaction_status', 'regular')
            ->whereDate('date', Carbon::today())
            ->count('id');
        $today_vehicle = number_format($vehicle);

        $volume = Transaction::whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->where('transaction_status', 'regular')
            ->whereDate('date', Carbon::today())
            ->sum('volume');
        $today_volume = number_format($volume, 2);

        // User type check and return view
        $user = Auth::user();
        $userId = Auth::id();
        if ($user->type == 0) {
            return view('admin-pages.short-trip-inflow-and-outflow-report', compact(
                'today_volume',
                'today_vehicle',
                'trading_inflows_graph',
                'trading_inflows_table',
                'request',
                'facilitators',
                'inflowChartData',
                'outflowChartData',
                'dates',
                'startDate',
                'endDate',
                'commodities',
                'inflowVolumeData',
                'outflowVolumeData',
                'staffs',
                'productionOrigins',
                'municipalities',
                'userId'
            ));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-short-trip-inflow-and-outflow-report', compact(
                'today_volume',
                'today_vehicle',
                'trading_inflows_graph',
                'trading_inflows_table',
                'request',
                'facilitators',
                'inflowChartData',
                'outflowChartData',
                'dates',
                'startDate',
                'endDate',
                'commodities',
                'inflowVolumeData',
                'outflowVolumeData',
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
            ->whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->whereDate('created_at', $currentDate)
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator']);
    
        // Get filter inputs
        $staffId = $request->input('staff_id');
        $timeFilter = $request->input('time_filter');
        $commodityId = $request->input('commodity_filter');
        $municipality = $request->input('municipality_filter');
        $typeFilter = $request->input('type_filter'); // Add this line
    
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
        if ($typeFilter) { // Apply type filter
            $temporary_transaction->where('transaction_type', $typeFilter);
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
    
        // Fetch additional data for views
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
        $locations = Location::all();
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();
    
        $user = Auth::user();
        
        if ($user->type == 0) {
            return view('admin-pages.short-trip-inflow-and-outflow-form-create', compact(
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
            return view('staff-pages.staff-short-trip-inflow-and-outflow-form-create', compact(
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
        // Validate the request data
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
            'barangay' => 'nullable|string',
            'municipality' => 'nullable|string',
            'facilitator_name' => 'nullable|string',
            'province' => 'nullable|string',
            'region' => 'nullable|string',
        ]);
    
        // Determine if all location fields are provided (i.e., not null or empty)
        $no_location = !(
            $validatedData['barangay'] &&
            $validatedData['municipality'] &&
            $validatedData['province'] &&
            $validatedData['region']
        );
    
        if (!$no_location) {
            // Create or find the location if all location fields are provided
            $location = Location::firstOrCreate([
                'barangay' => $validatedData['barangay'] ?? null,
                'municipality' => $validatedData['municipality'] ?? null,
                'province' => $validatedData['province'] ?? null,
                'region' => $validatedData['region'] ?? null,
            ]);
        } else {
            // If not all location fields are provided, do not create a location
            $location = null;
        }
    
        // Storing new vehicle
        $vehicle = null;
        if (!empty($validatedData['plate_number'])) {
            $vehicle = Vehicle::firstOrCreate(
                ['plate_number' => $validatedData['plate_number']],
                [
                    'vehicle_name' => $validatedData['name'] ?? null,
                    'vehicle_type_id' => $validatedData['vehicle_type_id'] ?? null,
                ]
            );
        }
    
        // Storing facilitator
        $facilitator = null;
        if (!empty($validatedData['facilitator_name'])) {
            $facilitator = Facilitator::where('facilitator_name', $validatedData['facilitator_name'])->first();
        }
    
        // Handling FacilitatorLocationVehicle creation if plate_number and location are present
        if (!empty($vehicle) && $no_location == false) {
            if (!empty($location) && !empty($facilitator)) {
                $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                    ->where('location_id', $location->location_id)
                    ->where('facilitator_id', $facilitator->facilitator_id)
                    ->first();
            } elseif (!$location && !empty($facilitator)) {
                $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                    ->where('location_id', null)
                    ->where('facilitator_id', $facilitator->facilitator_id)
                    ->first();
            } else {
                $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id)
                    ->where('location_id', $location->location_id)
                    ->where('facilitator_id', null)
                    ->first();
            }
    
            if (!$facilitator_location_vehicles) {
                $facilitator_location_vehicles = FacilitatorLocationVehicle::create([
                    'vehicle_id' => $vehicle->vehicle_id,
                    'location_id' => $location ? $location->location_id : null,
                    'facilitator_id' => $facilitator ? $facilitator->facilitator_id : null,
                ]);
            }
        }
    
        // Get the commodity_id
        $commodity = Commodity::where('commodity_name', $validatedData['commodity_name'])->first();
    
        // Store the transaction
        $test = Transaction::create([
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
            'facilitator_id' => $facilitator ? $facilitator->facilitator_id : null,
            'barangay' => $location ? $location->barangay : null,
            'municipality' => $location ? $location->municipality : null,
            'province' => $location ? $location->province : null,
            'region' => $location ? $location->region : null,
        ]);
    
        session()->flash('success', 'Trading inflow added successfully!');

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
                            isset($validatedData['vehicle_type_id']) ? "{$validatedData['vehicle_type_id']}" : null,
                            isset($validatedData['name']) ? "{$validatedData['name']}" : null,
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
            return redirect()->route('short-trip-inflow-and-outflow.create');
        } elseif ($user->type == 1) {
            return redirect()->route('staff-short-trip-inflow-and-outflow.create');
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
    public function edit(Transaction $short_trip_inflow_and_outflow)
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
            return view('admin-pages.short-trip-inflow-and-outflow-form-edit', compact(
                'transactions',
                'productionOrigins',
                'facilitators',
                'facilitator_location_vehicles',
                'short_trip_inflow_and_outflow',
                'staffs',
                'logged_in_staff',
                'commodities',
                'vehicle_types',
            ));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-short-trip-inflow-and-outflow-form-edit', compact(
                'transactions',
                'productionOrigins',
                'facilitators',
                'facilitator_location_vehicles',
                'short_trip_inflow_and_outflow',
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
    public function update(Request $request, Transaction $short_trip_inflow_and_outflow)
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
            'barangay' => 'nullable|string',
            'municipality' => 'nullable|string',
            'facilitator_name' => 'nullable|string',
            'province' => 'nullable|string',
            'region' => 'nullable|string',
        ]);
        
        $outdated_data = $short_trip_inflow_and_outflow->toArray();
        
        // Determine if location fields are provided
        $no_location = !($validatedData['barangay'] && $validatedData['municipality'] && $validatedData['province'] && $validatedData['region']);
    
        if (!$no_location) {
            // Check if the location exists, and create if necessary
            $location = Location::firstOrCreate([
                'barangay' => $validatedData['barangay'],
                'municipality' => $validatedData['municipality'],
                'province' => $validatedData['province'],
                'region' => $validatedData['region'],
            ]);
        } else {
            $location = null;  // Skip creating/updating location if data is not provided
        }
    
        // If plate_number exists, create or update vehicle
        if (!empty($validatedData['plate_number'])) {
            $vehicle = Vehicle::firstOrCreate(
                [
                    'plate_number' => $validatedData['plate_number'],
                ],
                [
                    'vehicle_name' => $validatedData['name'] ?? null,
                    'vehicle_type_id' => $validatedData['vehicle_type_id'] ?? null,
                ]
            );
        } else {
            $vehicle = null;
        }
    
        // Find or create facilitator
        $facilitator = Facilitator::where('facilitator_name', $validatedData['facilitator_name'])->first();
    
        // If plate_number exists, create or update facilitator_location_vehicle
        if ($vehicle && $location) {
            $facilitator_location_vehicle = FacilitatorLocationVehicle::firstOrCreate(
                [
                    'vehicle_id' => $vehicle->vehicle_id,
                    'location_id' => $location ? $location->location_id : null,
                    'facilitator_id' => $facilitator ? $facilitator->facilitator_id : null,
                ]
            );
        }
    
        // Find the corresponding commodity
        $commodity = Commodity::where('commodity_name', $validatedData['commodity_name'])->first();
    
        // Update the transaction
        $short_trip_inflow_and_outflow->update([
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
            'barangay' => $location ? $location->barangay : null,
            'municipality' => $location ? $location->municipality : null,
            'province' => $location ? $location->province : null,
            'region' => $location ? $location->region : null,
        ]);

        session()->flash('success', 'Short Trip Trading updated successfully!');
        $user = Auth::user();
        $author = Auth::user();
         
       
       
  
        $updated_data = $short_trip_inflow_and_outflow->getChanges();
    
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

        if ($short_trip_inflow_and_outflow->transaction_status === 'temporary') {
            if ($user->type == 0) {
                return redirect()->route('short-trip-inflow-and-outflow.create');
            } elseif ($user->type == 1) {
                return redirect()->route('staff-short-trip-inflow-and-outflow.create');
            }
        }

        if ($user->type == 0) {
            return redirect()->route('short-trip-inflow-and-outflow.index');
        } elseif ($user->type == 1) {
            return redirect()->route('staff-short-trip-inflow-and-outflow.index');
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
                $user = Auth::user();
        
        
        $author = Auth::user();
        Log::create([
            'action_type'=>'delete',
            'transaction' => implode(', ', array_filter([
                            isset($trading_inflow->transaction_type) ? $trading_inflow->transaction_type: null,
                            isset($trading_inflow->transaction_status) ? $trading_inflow->transaction_status: null,
                            isset($trading_inflow->commodity->commodity_name) ? $trading_inflow->commodity->commodity_name: null,
                            isset($trading_inflow->volume) ? $trading_inflow->volume .' kg': null,
                            isset($trading_inflow->plate_number) ? $trading_inflow->plate_number: null,
                            isset($trading_inflow->barangay) ? $trading_inflow->barangay: null,
                            isset($trading_inflow->municipality) ? $trading_inflow->municipality: null,
                            isset($trading_inflow->province) ? $trading_inflow->province: null,
                            isset($trading_inflow->region) ? $trading_inflow->region: null,
                            isset($trading_inflow->facilitator->facilitator_name) ? $trading_inflow->facilitator->facilitator_name: null,
                            ])),
            'author'=> $author->username,
        ]);
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
        $userId = Auth::id();
        $temporary_transactions = Transaction::where('transaction_status', 'temporary')
            ->whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->where('staff_id', $userId)
            ->update([
                'transaction_status' => 'regular',
            ]);
        if ($temporary_transactions > 0) {
            $author = Auth::user();
            
            Log::create([
            'action_type'=>'submit',
            'transaction' => 'short trip',
            'author'=> $author->username,
        ]);
            session()->flash('success', 'Short Trip submitted!');
        } else {
            session()->flash('error', 'No short trip added!');
        }

        $user = Auth::user();
        if ($user->type == 0) {
            return redirect()->route('short-trip-inflow-and-outflow.index');
        } elseif ($user->type == 1) {
            return redirect()->route('staff-short-trip-inflow-and-outflow.index');
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        // Load the Excel file
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Initialize an array to store the rows
        $rows = [];

        foreach ($worksheet->getRowIterator(2) as $row) { // Start from row 2 to skip headers
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue(); // Collect each cell's value
            }

            $excel_date = $worksheet->getCell("A" . $row->getRowIndex())->getValue();
            $time = $worksheet->getCell("B" . $row->getRowIndex())->getValue();
            $transaction_type = $worksheet->getCell("C" . $row->getRowIndex())->getValue();
            $staff_name = $worksheet->getCell("E" . $row->getRowIndex())->getValue();
            $commodity_name = $worksheet->getCell("F" . $row->getRowIndex())->getValue();
            $volume = $worksheet->getCell("G" . $row->getRowIndex())->getValue();
            $plate_number = $worksheet->getCell("H" . $row->getRowIndex())->getValue();
            $vehicle_type_name = $worksheet->getCell("I" . $row->getRowIndex())->getValue();
            $name = $worksheet->getCell("J" . $row->getRowIndex())->getValue();
            $facilitator_name = $worksheet->getCell("K" . $row->getRowIndex())->getValue();
            $barangay = $worksheet->getCell("L" . $row->getRowIndex())->getValue();
            $municipality = $worksheet->getCell("M" . $row->getRowIndex())->getValue();
            $province = $worksheet->getCell("N" . $row->getRowIndex())->getValue();
            $region = $worksheet->getCell("O" . $row->getRowIndex())->getValue();
            
            
            if (is_numeric($excel_date)) {
                $date = Date::excelToDateTimeObject($excel_date)->format('Y-m-d');
            } else {
                // Handle non-numeric date values as needed (e.g., log or throw an error)
                $date = null; // or some default value
            }
            $staff = Staff::where('staff_name', $staff_name)->first();
            if ($staff) {
                $staff_id = $staff->staff_id; 
            } else {
                $staff_id = null; 
            }
            
            $commodity = Commodity::where('commodity_name', $commodity_name)->first();
            if ($commodity) {
                $commodity_id = $commodity->commodity_id; 
            } else {
                $commodity_id = null; 
            }
          
            if($plate_number){
            $vehicle = Vehicle::where('plate_number', $plate_number)->first();
            if ($vehicle) {
                $vehicle_type_id = $vehicle->vehicle_type_id; 
            } else {
                $vehicle_type_id = null; 
            }
            }
            else{
            $vehicle_type_id = null; 
            }
            
            
            $facilitator = Facilitator::where('facilitator_name', $facilitator_name)->first();
            if ($facilitator) {
                $facilitator_id = $facilitator->facilitator_id; 
            } else {
                $facilitator_id = null; 
            }
          
            // Map data to your model fields
            $rows[] = [
                'date' => $date,
                'time' => $time,
                'transaction_type' => $transaction_type,
                'transaction_status' => "temporary",
                'staff_id' => $staff_id,
                'commodity_id' => $commodity_id,
                'volume' => $volume,
                'plate_number' => $plate_number,
                'vehicle_type_id' => $vehicle_type_id,
                'name' => $name,
                'facilitator_id' => $facilitator_id,
                'barangay' => $barangay,
                'municipality' => $municipality,
                'province' => $province,
                'region' => $region,
                'created_at' => now(), 
                'updated_at' => now(),

            ];
            
        }
        
        $author = Auth::user();
        
            Log::create([
            'action_type'=>'import',
            'transaction' => $transaction_type,
            'author'=> $author->username,
        ]);
        // Insert all rows at once for efficiency
        Transaction::insert($rows);

        return back()->with('success', 'Data imported successfully');
    }
}