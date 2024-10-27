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
    public function index()
    {
        $commodities = Commodity::orderBy('commodity_name')->get();  
        $locations = Location::orderBy('barangay')->paginate(10);            
        $facilitators = Facilitator::orderBy('facilitator_name')->get();
        
        return view('admin-pages.record-list',compact('commodities',
                                                                            'locations',
                                                                            'facilitators',
        ));
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
