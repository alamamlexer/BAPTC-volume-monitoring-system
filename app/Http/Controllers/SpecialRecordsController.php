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

class SpecialRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactionType = $request->input('transaction_type');

        $query = Transaction::with(['commodity', 'facilitator'])
            ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading']) // Include only allowed types
            ->orderBy('created_at', 'desc');

        // Optional: Filter by transaction type if it's provided
        if ($transactionType && in_array($transactionType, ['dry', 'cold', 'washing', 'intertrading'])) {
            $query->where('transaction_type', $transactionType);
        }

        $specialRecords = $query->get();

        return view('admin-pages.special-records-report', compact('specialRecords', 'transactionType'));

    
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
            return view('admin-pages.special-records-create', compact('defaultTime', 'facilitators', 'staffs', 'commodities', 'logged_in_staff', 'facilitator_location_vehicles'));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-special-records-form-create', compact('defaultTime', 'facilitators', 'staffs', 'commodities', 'logged_in_staff'));
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
            'barangay' => 'required|string',
            'municipality' => 'required|string',
            'province' => 'required|string',
            'region' => 'required|string',
            'facilitator_name' => 'nullable',
        ]);

        // Check and store location
        $location = Location::firstOrCreate([
            'barangay' => $validatedData['barangay'],
            'municipality' => $validatedData['municipality'],
            'province' => $validatedData['province'],
            'region' => $validatedData['region'],
        ]);

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
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
            'facilitator_id' => $facilitator->facilitator_id ?? null,
        ]);
        
        session()->flash('success', 'Special record added successfully!');

        $user = Auth::user();
        if ($user->type == 0) {
            return redirect()->route('special-records.index');
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
            return view('admin-pages.special-records-edit', compact('defaultTime', 'facilitators', 'staffs', 'commodities', 'logged_in_staff', 'facilitator_location_vehicles', 'special_records'));
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
            'barangay' => 'required|string',
            'municipality' => 'required|string',
            'province' => 'required|string',
            'region' => 'required|string',
        ]);
        
        // Load the record you want to update
        $special_records = Transaction::find($id);
        if (!$special_records) {
            session()->flash('error', 'Record not found.');
            return redirect()->back();
        }
        
        // Find or create related data
        $location = Location::firstOrCreate([
            'barangay' => $validatedData['barangay'],
            'municipality' => $validatedData['municipality'],
            'province' => $validatedData['province'],
            'region' => $validatedData['region'],
        ]);
        
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
                'barangay' => $location->barangay,
                'municipality' => $location->municipality,
                'province' => $location->province,
                'region' => $location->region,
            ]);
        
            if ($updated) {
                session()->flash('success', 'Special Records updated successfully!');
            } else {
                session()->flash('error', 'Failed to update Special Records.');
            }
        
            $user = Auth::user();
            return $user->type == 0
                ? redirect()->route('special-records.index')
                : redirect()->route('some-other-route');
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