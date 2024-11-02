<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // For the graph

        // Get start and end dates from request, with defaults
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());


        $amPmFilter = $request->input('amPmFilter');
        $attendantFilter = $request->input('attendantFilter');
        $commodityFilter = $request->input('commodityFilter');
        $productionOriginFilter = $request->input('productionOriginFilter');
        $facilitatorFilter = $request->input('facilitatorFilter');



        $trading_inflows =  Transaction::whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->where('transaction_status', 'regular')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator'])
            ->get();





        // For the table
        $query = Transaction::whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->where('transaction_status', 'regular')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator']);

        $staffId = $request->input('staff_id');
        $timeFilter = $request->input('time_filter');
        $typeFilter = $request->input('type_filter');
        $commodityId = $request->input('commodity_filter');
        $municipality = $request->input('municipality_filter');

        // Apply filters if provided
        if ($staffId) {
            $query->where('staff_id', $staffId);
        }
        if ($timeFilter) {
            $query->where('time', $timeFilter);
        }
        if ($typeFilter) {
            $query->where('transaction_type', $typeFilter);
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
            return view('admin-pages.short-trip-inflow-and-outflow-report', compact(
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
                'user',
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
                'chartData',
                'dates',
                'startDate',
                'endDate',
                'commodities',
                'totalVolumeData',
                'staffs',
                'productionOrigins',
                'municipalities',
                'user',
                'userId'
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

        $currentDate = Carbon::today()->toDateString();

        $temporary_transactions = Transaction::where('transaction_status', 'temporary')
            ->whereIn('transaction_type', ['short trip inflow', 'short trip outflow'])
            ->where('date', $currentDate)
            ->with(['staff', 'commodity', 'vehicle_type', 'facilitator'])
            ->paginate(5);
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
        $facilitators = Facilitator::all();
        $logged_in_staff = Auth::id();
        $staffs = Staff::all();
        $commodities = Commodity::all();
        $vehicle_types = VehicleType::all();
        $location_vehicles = LocationVehicle::with(['vehicle', 'location'])->get();
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();
        $user = Auth::user();
        if ($user->type == 0) {
            return view('admin-pages.short-trip-inflow-and-outflow-form-create', compact('defaultTime', 'staffs', 'productionOrigins', 'facilitator_location_vehicles', 'facilitators', 'temporary_transactions', 'logged_in_staff', 'vehicle_types', 'commodities', 'location_vehicles'));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-short-trip-inflow-and-outflow-form-create', compact('defaultTime', 'staffs', 'productionOrigins', 'facilitator_location_vehicles', 'facilitators', 'temporary_transactions', 'logged_in_staff', 'vehicle_types', 'commodities', 'location_vehicles'));
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
            'facilitator_id' => 'nullable|exists:facilitators,facilitator_id',
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
            session()->flash('success', 'Trading inflow added successfully!');
            Vehicle::create([
                'plate_number' => $validatedData['plate_number'],
                'vehicle_name' => $validatedData['name'],
                'vehicle_type_id' => $validatedData['vehicle_type_id'],
            ]);
        } else {
            Vehicle::where('plate_number', $validatedData['plate_number'])->first();
        }
        $vehicle = Vehicle::where('plate_number', $validatedData['plate_number'])->first();

        $facilitator = Facilitator::where('facilitator_id', $validatedData['facilitator_id'])->first();
        //Storing a link in the address and location if there is no existing record
        $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id,)
            ->where('location_id', $location->location_id)
            ->where('facilitator_id', $facilitator->facilitator_id)
            ->first();
        if (!$facilitator_location_vehicles) {
            $facilitator_location_vehicles = FacilitatorLocationVehicle::create([
                'vehicle_id' => $vehicle->vehicle_id,
                'location_id' => $location->location_id,
                'facilitator_id' => $facilitator->facilitator_id,
            ]);
        } else {
            $facilitator_location_vehicles = FacilitatorLocationVehicle::where('vehicle_id', $vehicle->vehicle_id,)
                ->where('location_id', $location->location_id)
                ->where('facilitator_id', $facilitator->facilitator_id)
                ->first();
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
            'facilitator_id' => $validatedData['facilitator_id'] ?? null,
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
        ]);

        session()->flash('success', 'Short Trip added successfully!');


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
        $location_vehicles = LocationVehicle::with(['vehicle', 'location'])->get();
        $logged_in_staff = Auth::id();
        $facilitator_location_vehicles = FacilitatorLocationVehicle::with(['vehicle', 'location', 'facilitator'])->get();
        $transactions = Transaction::with(['commodity', 'staff', 'vehicle_type'])->get();
        $user = Auth::user();
        if ($user->type == 0) {
            return view('admin-pages.short-trip-inflow-and-outflow-form-edit', compact('transactions', 'productionOrigins', 'facilitators', 'facilitator_location_vehicles', 'short_trip_inflow_and_outflow', 'staffs', 'logged_in_staff', 'commodities', 'vehicle_types', 'location_vehicles'));
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-short-trip-inflow-and-outflow-form-edit', compact('transactions', 'productionOrigins', 'facilitators', 'facilitator_location_vehicles', 'short_trip_inflow_and_outflow', 'staffs', 'logged_in_staff', 'commodities', 'vehicle_types', 'location_vehicles'));
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
            'facilitator_id' => 'nullable|exists:facilitators,facilitator_id',
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
        $facilitator_location_vehicle = FacilitatorLocationVehicle::firstOrCreate(
            [
                'vehicle_id' => $vehicle->vehicle_id,
                'location_id' => $location->location_id,
                'facilitator_id' => $location->location_id,
            ]
        );

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
            'facilitator_id' => $validatedData['facilitator_id'] ?? null,
            'name' => $validatedData['name'] ?? null,
            'barangay' => $location->barangay,
            'municipality' => $location->municipality,
            'province' => $location->province,
            'region' => $location->region,
        ]);


        
        $user = Auth::user();

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


        $user = Auth::user();

        if ($trading_inflow->transaction_status === 'temporary') {
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

        // Redirect to the index page or the relevant page

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
        
        // Insert all rows at once for efficiency
        Transaction::insert($rows);

        return back()->with('success', 'Data imported successfully');
    }
}
