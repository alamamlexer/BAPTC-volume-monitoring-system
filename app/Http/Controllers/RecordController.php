<?php

namespace App\Http\Controllers;
use App\Models\Commodity;
use App\Models\Location;
use App\Models\Facilitator;

use Illuminate\Http\Request;

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
                    'id' => $location->id,
                    'name' => "{$location->barangay}, {$location->municipality}, {$location->province}, {$location->region}",
                    'action' => '<a href="#" class="btn btn-outline-danger m-1"><i class="bx bxs-edit"></i> Delete</a>',
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
    
            if ($search = $request->get('search')['value']) {
                $query->where('facilitator_code', 'like', "%{$search}%")
                      ->orWhere('facilitator_name', 'like', "%{$search}%");
            }
    
            $facilitators = $query->paginate($perPage, ['*'], 'page', $page);
    
            $data = [];
            foreach ($facilitators as $facilitator) {
                $data[] = [
                    'id' => $facilitator->id,
                    'code' => $facilitator->facilitator_code,
                    'name' => $facilitator->facilitator_name,
                    'action' => '<a href="#" class="btn btn-outline-danger m-1"><i class="bx bxs-edit"></i> Delete</a>',
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
    
            if ($search = $request->get('search')['value']) {
                $query->where('commodity_name', 'like', "%{$search}%");
            }
    
            $commodities = $query->paginate($perPage, ['*'], 'page', $page);
    
            $data = [];
            foreach ($commodities as $commodity) {
                $data[] = [
                    'id' => $commodity->id,
                    'name' => $commodity->commodity_name,
                    'action' => '<a href="#" class="btn btn-outline-danger m-1"><i class="bx bxs-edit"></i> Delete</a>',
                ];
            }
    
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $commodities->total(),
                'recordsFiltered' => $commodities->total(),
                'data' => $data,
            ]);
        }
        $commodities = Commodity::orderBy('commodity_name')->get();
        $locations = Location::orderBy('barangay')->paginate(10);
        $facilitators = Facilitator::orderBy('facilitator_name')->get();
    
        return view('admin-pages.record-list', compact('commodities', 'locations', 'facilitators'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
