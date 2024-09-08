<?php

namespace App\Http\Controllers;

use App\Models\ShortTripInflowAndOutflow;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShortTripInflowAndOutflow $shortTripInflowAndOutflow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShortTripInflowAndOutflow $shortTripInflowAndOutflow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShortTripInflowAndOutflow $shortTripInflowAndOutflow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortTripInflowAndOutflow $shortTripInflowAndOutflow)
    {
        //
    }
}
