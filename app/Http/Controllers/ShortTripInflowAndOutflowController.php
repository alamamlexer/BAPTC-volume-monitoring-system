<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outflow;
use App\Models\Inflow;

class ShortTripInflowAndOutflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('admin-pages.short-trip-inflow-and-outflow-report');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-pages.short-trip-inflow-and-outflow-form-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transaction=$request->string('transaction');
        try {
            if($transaction=='inflow'){
                Inflow::create([
                  'date' => $request->string('date'),
                  'time' => $request->string('time'),
                  'transaction_status' => $request->string('transaction_status'),
                  'attendant' => $request->string('attendant'),
                  'plate_number' => $request->string('plate_number'),
                  'vehicle_type' => $request->string('vehicle_type'),
                  'name' => $request->string('name'),
                  'commodity' => $request->string('commodity'),
                  'volume' => $request->integer('volume'),
                  'barangay' => $request->string('barangay'),
                  'municipality' => $request->string('municipality'),
                  'province' => $request->string('province'),
                  'region' => $request->string('region'),
                ]);
            }
            else{
                Outflow::create([
                    'date' => $request->string('date'),
                    'time' => $request->string('time'),
                    'transaction_status' => $request->string('transaction_status'),
                    'attendant' => $request->string('attendant'),
                    'plate_number' => $request->string('plate_number'),
                    'vehicle_type' => $request->string('vehicle_type'),
                    'name' => $request->string('name'),
                    'commodity' => $request->string('commodity'),
                    'volume' => $request->integer('volume'),
                    'barangay' => $request->string('barangay'),
                    'municipality' => $request->string('municipality'),
                    'province' => $request->string('province'),
                    'region' => $request->string('region'),
                  ]);
            }
        session()->flash('success', 'Short trip transaction added successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add short trip transaction.');
        }
        return redirect()->route('short-trip-inflow-and-outflow.create');

    }

    /**
     * Display the specified resource.
     */
    public function show(Outflow $Outflow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outflow $Outflow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outflow $Outflow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outflow $shortTripInflowAndOutflow)
    {
        //
    }
}
