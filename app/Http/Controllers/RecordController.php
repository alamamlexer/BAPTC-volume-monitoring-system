<?php

namespace App\Http\Controllers;
use App\Models\Commodity;
use App\Models\Location;
use App\Models\Facilitator;
use App\Models\FacilitatorLocationVehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;


class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type'); // Determine which table to respond to
    
        // Common pagination settings
        $perPage = $request->get('length', 10);
        $page = ($request->get('start') / $perPage) + 1;
    
        // Locations Table
        if ($type === 'location') {
            $query = Location::query();
            $query->orderBy('barangay');
    
            if ($search = $request->get('search')['value']) {
                $query->where('barangay', 'like', "%{$search}%")
                      ->orWhere('municipality', 'like', "%{$search}%")
                      ->orWhere('province', 'like', "%{$search}%")
                      ->orWhere('region', 'like', "%{$search}%");
            }
    
            $locations = $query->paginate($perPage, ['*'], 'page', $page);
    
            $data = [];
            foreach ($locations as $location) {
                $data[] = [
                    'id' => $location->location_id,
                    'name' => "{$location->barangay}, {$location->municipality}, {$location->province}, {$location->region}",

                ];
            }
    
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $locations->total(),
                'recordsFiltered' => $locations->total(),
                'data' => $data,
            ]);
        }
    
        // Facilitators Table
        if ($type === 'facilitator') {
            $query = Facilitator::query();
            $query->orderBy('facilitator_name');
    
            if ($search = $request->get('search')['value']) {
                $query->where('facilitator_code', 'like', "%{$search}%")
                      ->orWhere('facilitator_name', 'like', "%{$search}%");
            }
    
            $facilitators = $query->paginate($perPage, ['*'], 'page', $page);
    
            $data = [];
            foreach ($facilitators as $facilitator) {
                $data[] = [
                    'id' => $facilitator->facilitator_id,
                    'code' => $facilitator->facilitator_code,
                    'name' => $facilitator->facilitator_name,
                    'action' => '<button class="btn btn-outline-danger" onclick="deleteRecord(\'record/' . $facilitator->id . '?type=facilitator\')">
                                    <i class="bx bxs-trash-alt"></i> Delete
                                 </button>',
                ];
            }
    
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $facilitators->total(),
                'recordsFiltered' => $facilitators->total(),
                'data' => $data,
            ]);
        }
    
        // Commodities Table
        if ($type === 'commodity') {
            $query = Commodity::query();
            $query->orderBy('commodity_name');
            if ($search = $request->get('search')['value']) {
                $query->where('commodity_name', 'like', "%{$search}%");
            }
    
            $commodities = $query->paginate($perPage, ['*'], 'page', $page);
    
            $data = [];
            foreach ($commodities as $commodity) {
                $data[] = [
                    'id' => $commodity->commodity_id,
                    'name' => $commodity->commodity_name,
                    'action' => '<button class="btn btn-outline-danger" onclick="deleteRecord(\'record/' . $commodity->id . '?type=commodity\')">
                                    <i class="bx bxs-trash-alt"></i> Delete
                                 </button>',
                ];
            }
    
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $commodities->total(),
                'recordsFiltered' => $commodities->total(),
                'data' => $data,
            ]);
        }
        
        
        //Plate Number Links Table
        if ($type === 'link') {
            $query = FacilitatorLocationVehicle::with('location', 'vehicle', 'facilitator')
                ->leftJoin('vehicles', 'facilitator_location_vehicles.vehicle_id', '=', 'vehicles.vehicle_id')
                ->leftJoin('locations', 'facilitator_location_vehicles.location_id', '=', 'locations.location_id') // Ensure this matches your table name
                ->leftJoin('facilitators', 'facilitator_location_vehicles.facilitator_id', '=', 'facilitators.facilitator_id') // Ensure this matches your table name
                ->select('facilitator_location_vehicles.*', 
                         'vehicles.plate_number', 
                         'vehicles.vehicle_name', 
                         'locations.barangay', 
                         'locations.municipality', 
                         'locations.province', 
                         'locations.region', 
                         'facilitators.facilitator_name',
                         'facilitators.facilitator_code') // Select relevant fields
                ->orderBy('vehicles.plate_number'); // Order by plate_number from vehicles
        
            // Add search functionality if needed
            if ($search = $request->get('search')['value']) {
                $query->where(function($query) use ($search) {
                    // Search through the related location
                    $query->orWhere('locations.barangay', 'like', "%{$search}%")
                          ->orWhere('locations.municipality', 'like', "%{$search}%")
                          ->orWhere('locations.province', 'like', "%{$search}%")
                          ->orWhere('locations.region', 'like', "%{$search}%")
                          ->orWhere('vehicles.plate_number', 'like', "%{$search}%")
                          ->orWhere('vehicles.vehicle_name', 'like', "%{$search}%")
                          ->orWhere('facilitators.facilitator_name', 'like', "%{$search}%")
                          ->orWhere('facilitators.facilitator_code', 'like', "%{$search}%");
                });
            }
        
            $links = $query->paginate($perPage, ['*'], 'page', $page);
        
            $data = [];
            foreach ($links as $link) {
                $data[] = [
                    'id' => $link->id,
                    'vehicle' => "{$link->plate_number} ({$link->vehicle_name})", // Show N/A if vehicle_name is null
                    'location' => "{$link->barangay}, {$link->municipality}, {$link->province}, {$link->region}", // Show N/A if all location fields are null
                    'facilitator' => !empty($link->facilitator_name) ? "{$link->facilitator_name} ({$link->facilitator_code})" : 'N/A', // Show N/A if facilitator_name is empty
                ];
            }
        
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $links->total(),
                'recordsFiltered' => $links->total(),
                'data' => $data,
            ]);
        }
        
        
        
        $links= FacilitatorLocationVehicle::paginate(10);
        $commodities = Commodity::orderBy('commodity_name')->paginate(10);
        $locations = Location::orderBy('barangay')->paginate(10);
        $facilitators = Facilitator::orderBy('facilitator_name')->paginate(10);
    
        return view('admin-pages.record-list', compact('links','commodities', 'locations', 'facilitators'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $record_type=$request->get('record_type');
        
        if($record_type=='facilitator'){
        
        $validatedData = $request->validate([
            'facilitator_name' => 'required|unique:facilitators,facilitator_name',
            'facilitator_code' => 'required|unique:facilitators,facilitator_code',
        ],[
            'facilitator_name.unique' => session()->flash('error', 'The facilitator name already exists!'),
            'facilitator_code.unique' => session()->flash('error', 'The facilitator code already exists!'),
        ]);
        Facilitator::create([
            'facilitator_name' => $validatedData['facilitator_name'],
            'facilitator_code' => $validatedData['facilitator_code'],
        ]);
        session()->flash('success', 'Facilitator added successfully!');
        }
        elseif($record_type=='commodity'){
            $validatedData = $request->validate([
                'commodity_name' => 'required|unique:commodities,commodity_name',
            ],[
                'commodity_name.unique' => session()->flash('error', 'The commodity already exists!'),
            ]);
            Commodity::create([
                'commodity_name' => $validatedData['commodity_name'],
            ]);
            session()->flash('success', 'Commodity added successfully!');
        }
        else{
            session()->flash('error', 'Record type not found!');
        }
        
         return redirect()->route('record.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
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
    public function destroy(Request $request, string $id) 
        {
            $type = $request->get('type');
                
            try {
                if ($type === "location") {
                    $data=Location::findOrFail($id);
                    if($data->transactions()->exists()){
                        return redirect()->route('record.index')->with('error', 'Cannot delete due to related records.');
                    } 
                    $data->delete();
                } elseif ($type === "commodity") {
                    $data=Commodity::findOrFail($id);
                    if($data->transactions()->exists()){
                        return redirect()->route('record.index')->with('error', 'Cannot delete due to related records.');
                    } 
                    $data->delete();
                } elseif ($type === "facilitator") {
                    $data=Facilitator::findOrFail($id);
                    if($data->transactions()->exists()){
                        return redirect()->route('record.index')->with('error', 'Cannot delete due to related records.');
                    } 
                    $data->delete();
                } elseif ($type === "link") {
                    $data=FacilitatorLocationVehicle::findOrFail($id);
                    if($data->transactions()->exists()){
                        return redirect()->route('record.index')->with('error', 'Cannot delete due to related records.');
                    } 
                    $data->delete();
                } else {
                    return response()->json(['message' => 'Invalid type specified.'], 400);
                }
                
                // Return success response if deletion is successful
                return redirect()->route('record.index')->with('success', 'Record deleted successfully');
                
            } catch (ModelNotFoundException $e) {
                // Handle case where the model is not found
                return redirect()->route('record.index')->with('error', 'Record type not found');
            } catch (\Exception $e) {
                // Handle any other exceptions                
            }
        }

}
