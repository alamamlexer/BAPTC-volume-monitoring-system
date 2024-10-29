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
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        date_default_timezone_set('Asia/Manila');

        $currentHour = date('H'); // 24-hour format
        $defaultTime = ($currentHour < 12) ? 'AM' : 'PM';

        $currentDate = Carbon::today()->toDateString();

        $temporary_transactions = Transaction::where('transaction_status', 'temporary')
            ->where('transaction_type', 'trading inflow')
            ->where('date', $currentDate)
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator'])
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
        $facilitators = Facilitator::all();
        $logged_in_staff = Auth::id();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $location_vehicles = LocationVehicle::with(['vehicle', 'location'])->get();
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();


        $user = Auth::user();
        if ($user->type == 0) {
            return view('admin-pages.special-records-create', compact(
                'defaultTime',
                'staffs',
                'productionOrigins',
                'facilitator_location_vehicles',
                'facilitators',
                'temporary_transactions',
                'logged_in_staff',
                'vehicle_types',
                'commodities',
                'location_vehicles'
            ));
        } 
        // elseif ($user->type == 1) {
        //     return view('staff-pages.staff-special-records-create', compact(
        //         'defaultTime',
        //         'staffs',
        //         'productionOrigins',
        //         'facilitator_location_vehicles',
        //         'facilitators',
        //         'temporary_transactions',
        //         'logged_in_staff',
        //         'vehicle_types',
        //         'commodities',
        //         'location_vehicles'
        //     ));
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
