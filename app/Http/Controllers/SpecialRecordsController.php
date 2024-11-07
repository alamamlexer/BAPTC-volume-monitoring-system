<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Transaction;
use App\Models\Staff;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Commodity;
use App\Models\Log;
use App\Models\Location;
use App\Models\LocationVehicle;
use App\Models\Facilitator;
use App\Models\FacilitatorLocationVehicle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpecialRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
    $endDate = $request->input('end_date', Carbon::now()->toDateString());

    $transactionType = $request->input('transaction_filter');  // Match the key sent from the frontend

    // Eager-load commodity, facilitator, and staff relationships
    $query = Transaction::with(['commodity', 'facilitator', 'staff'])
        ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
        ->whereBetween('date', [$startDate, $endDate])
        ->orderBy('created_at', 'desc');

    // Apply filters if provided
    if ($transactionType) {
        $query->where('transaction_type', $transactionType);  // Apply the transaction filter
    }
    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }
    if ($request->input('commodity_filter')) {
        $query->where('commodity_id', $request->input('commodity_filter'));
    }
    if ($request->input('municipality_filter')) {
        $query->where('municipality', $request->input('municipality_filter'));
    }

    // Fetch the paginated results
    $specialRecords = $query->paginate(5);

    if ($request->ajax()) {
        return response()->json([
            'data' => $specialRecords->items(),
            'current_page' => $specialRecords->currentPage(),
            'last_page' => $specialRecords->lastPage(),
            'total' => $specialRecords->total(),
        ]);
    }

    $transaction_types = ['dry', 'cold', 'washing', 'intertrading'];
    $commodities = Commodity::all();
    $municipalities = Transaction::distinct()->pluck('municipality');
    $staffs = Staff::all();

    $user = Auth::user();
    $userId = Auth::id();

    if ($user->type == 0){
    return view('admin-pages.special-records-report', compact(
        'startDate',
        'endDate',
        'specialRecords',
        'transactionType',
        'transaction_types',
        'commodities',
        'municipalities',
        'staffs'
    ));
} elseif ($user->type == 1) {
    return view('staff-pages.staff-special-records-report', compact(
        'startDate',
        'endDate',
        'specialRecords',
        'transactionType',
        'transaction_types',
        'commodities',
        'municipalities',
        'staffs'
    ));
}
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        date_default_timezone_set('Asia/Manila');

        $currentHour = date('H'); // 24-hour format
        $defaultTime = ($currentHour < 12) ? 'AM' : 'PM';

        $facilitators = Facilitator::all();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $logged_in_staff = Auth::id(); // Store logged-in staff ID
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();

        $user = Auth::user();
        if ($user->type == 0) {
            return view('admin-pages.special-records-create', compact(
                'defaultTime', 
                'facilitators', 
                'staffs', 
                'commodities', 
                'logged_in_staff', 
                'facilitator_location_vehicles'));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-special-records-create', compact(
                'defaultTime', 
                'facilitators', 
                'staffs', 
                'commodities', 
                'logged_in_staff',
                'facilitator_location_vehicles'));
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'transaction_type' => 'required|string',
        'staff_id' => 'required|exists:staff,staff_id',
        'commodity_name' => 'required|exists:commodities,commodity_name',
        'volume' => 'required|numeric',
        'barangay' => 'nullable|string',
        'municipality' => 'nullable|string',
        'province' => 'nullable|string',
        'region' => 'nullable|string',
        'facilitator_name' => 'nullable|string',
    ]);

    // Initialize $location to null
    $location = null;

    // Check and store location
    if (!empty($validatedData['barangay']) || !empty($validatedData['municipality']) || !empty($validatedData['province']) || !empty($validatedData['region'])) {
        $location = Location::firstOrCreate([
            'barangay' => $validatedData['barangay'] ?? null,
            'municipality' => $validatedData['municipality'] ?? null,
            'province' => $validatedData['province'] ?? null,
            'region' => $validatedData['region'] ?? null,
        ]);
    }

    // Check and store facilitator
    if (!empty($validatedData['facilitator_name'])) {
        $facilitator = Facilitator::firstOrCreate(['facilitator_name' => $validatedData['facilitator_name']]);
    }

    // Get the commodity ID
    $commodity = Commodity::where('commodity_name', $validatedData['commodity_name'])->first();

    // Store the transaction
    Transaction::create([
        'date' => $validatedData['date'],
        'time' => $validatedData['time'],
        'transaction_type' => $validatedData['transaction_type'],
        'transaction_status' => 'special status', // Set the status here
        'staff_id' => $validatedData['staff_id'],
        'commodity_id' => $commodity->commodity_id,
        'volume' => $validatedData['volume'],
        // Only set location values if a location was created
        'barangay' => $location ? $location->barangay : null,
        'municipality' => $location ? $location->municipality : null,
        'province' => $location ? $location->province : null,
        'region' => $location ? $location->region : null,
        'facilitator_id' => $facilitator->facilitator_id ?? null,
    ]);
        $author = Auth::user();

        Log::create([
            'action_type'=>'create',
            'transaction' => implode(', ', array_filter([
                            isset($validatedData['transaction_type']) ? "{$validatedData['transaction_type']}" : null,
                            isset($validatedData['transaction_status']) ? "{$validatedData['transaction_status']}" : null,
                            isset($commodity->commodity_name) ? "{$commodity->commodity_name}" : null,
                            isset($validatedData['volume']) ? "{$validatedData['volume']}" .' kg': null,
                            isset($validatedData['barangay']) ? "{$validatedData['barangay']}" : null,
                            isset($validatedData['municipality']) ? "{$validatedData['municipality']}" : null,
                            isset($validatedData['province']) ? "{$validatedData['province']}" : null,
                            isset($validatedData['region']) ? "{$validatedData['region']}" : null,
                            isset($facilitator->facilitator_name) ? "{$facilitator->facilitator_name}" : null,
                            ])),
            'author'=> $author->username,
        ]);
        session()->flash('success', 'Special record added successfully!');

        $user = Auth::user();
        if ($user->type == 0) {
            return redirect()->route('special-records.create');
        } elseif ($user->type == 1) {
            return redirect()->route('staff-special-record.create');
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
    public function edit($id)
    {
        date_default_timezone_set('Asia/Manila');
        $special_records = Transaction::findOrFail($id);
        $currentHour = date('H'); // 24-hour format
        $defaultTime = ($currentHour < 12) ? 'AM' : 'PM';

        $facilitators = Facilitator::all();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $logged_in_staff = Auth::id(); // Store logged-in staff ID
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();

        $user = Auth::user();
        if ($user->type == 0) {
            return view('admin-pages.special-records-edit', compact(
                'defaultTime', 
                'facilitators', 
                'staffs', 
                'commodities', 
                'logged_in_staff', 
                'facilitator_location_vehicles', 
                'special_records'));
        } elseif  ($user->type == 1) {
            return view('staff-pages.staff-special-records-edit', compact(
                'defaultTime', 
                'facilitators', 
                'staffs', 
                'commodities', 
                'logged_in_staff', 
                'facilitator_location_vehicles', 
                'special_records'));
        }
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'transaction_status' => 'required|string',
            'transaction_type' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'staff_id' => 'required|exists:staff,staff_id',
            'commodity_name' => 'required|exists:commodities,commodity_name',
            'volume' => 'required|numeric',
            'facilitator_name' => 'nullable|exists:facilitators,facilitator_name',
            'barangay' => 'nullable|string',
            'municipality' => 'nullable|string',
            'province' => 'nullable|string',
            'region' => 'nullable|string',
        ]);

        // Load the record you want to update
        $special_records = Transaction::find($id);
        if (!$special_records) {
            session()->flash('error', 'Record not found.');
            return redirect()->back();
        }
        $outdated_data = $special_records->toArray();
              // Check and store location
    if (!empty($validatedData['barangay']) || !empty($validatedData['municipality']) || !empty($validatedData['province']) || !empty($validatedData['region'])) {
        $location = Location::firstOrCreate([
            'barangay' => $validatedData['barangay'] ?? null,
            'municipality' => $validatedData['municipality'] ?? null,
            'province' => $validatedData['province'] ?? null,
            'region' => $validatedData['region'] ?? null,
        ]);
    }

        $facilitator = null;
        if (!empty($validatedData['facilitator_name'])) {
            $facilitator = Facilitator::firstOrCreate([
                'facilitator_name' => $validatedData['facilitator_name'],
            ]);
        }

        $commodity = Commodity::where('commodity_name', $validatedData['commodity_name'])->first();

        if ($commodity) {
            $updated = $special_records->update([
                'date' => $validatedData['date'],
                'time' => $validatedData['time'],
                'transaction_type' => $validatedData['transaction_type'],
                'transaction_status' => $validatedData['transaction_status'],
                'staff_id' => $validatedData['staff_id'],
                'commodity_id' => $commodity->commodity_id,
                'volume' => $validatedData['volume'],
                'facilitator_id' => $facilitator ? $facilitator->facilitator_id : null,
                'barangay' => $location->barangay ?? null,
                'municipality' => $location->municipality ?? null,
                'province' => $location->province ?? null,
                'region' => $location->region ?? null,
            ]);

            if ($updated) {
                $author = Auth::user();
         
       
       
  
            $updated_data = $special_records->getChanges();
            
            $test = Log::create([
                'action_type' => 'update',
                'transaction' =>  implode(', ', array_filter([
                    isset($outdated_data['transaction_type']) ? $outdated_data['transaction_type'] : null,
                    isset($outdated_data['transaction_status']) ? $outdated_data['transaction_status'] : null,
                    isset($outdated_data['commodity_name']) ? $outdated_data['commodity_name'] : null,
                    isset($outdated_data['volume']) ? $outdated_data['volume'] . ' kg' : null, 
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
                    isset($updated_data['barangay']) ? 'Barangay: '.$updated_data['barangay'] : null,
                    isset($updated_data['municipality']) ? 'Municipality: '.$updated_data['municipality'] : null,
                    isset($updated_data['province']) ? 'Province: '.$updated_data['province'] : null,
                    isset($updated_data['region']) ?'Region: '. $updated_data['region'] : null,
                    isset($facilitator->facilitator_name) ? $facilitator->facilitator_name : null,
                ])),
                'author' => $author->username,
            ]);
    
            
            session()->flash('success', 'Special Records updated successfully!');
        } else {
            session()->flash('error', 'Failed to update Special Records.');
        }

        $user = Auth::user();
            return $user->type == 0
                ? redirect()->route('special-records.index')
                : redirect()->route('staff-special-record.index');
        } else {
            session()->flash('error', 'Commodity not found.');
            return redirect()->back();
        }
}

/**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}