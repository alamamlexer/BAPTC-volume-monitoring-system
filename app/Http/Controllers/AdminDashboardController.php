<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
    //inflow total vehicle and volume this day
    $inflow_vehicle = Transaction::wherein('transaction_type', ['trading inflow','short trip inflow'])
                    ->where('transaction_status', 'regular')
                    ->whereDate('date', Carbon::today())
                    ->count('plate_number');
    $today_inflow_vehicle = number_format($inflow_vehicle);      

    $inflow_volume = Transaction::wherein('transaction_type', ['trading inflow','short trip inflow'])
                ->where('transaction_status', 'regular')
                ->whereDate('date', Carbon::today())
                ->sum('volume');
    $today_inflow_volume = number_format($inflow_volume, 2);
    
     //inflow total vehicle and volume this day
     $outflow_vehicle = Transaction::wherein('transaction_type', ['trading outflow','short trip outflow'])
     ->where('transaction_status', 'regular')
     ->whereDate('date', Carbon::today())
     ->count('plate_number');
    $today_outflow_vehicle = number_format($outflow_vehicle);      
    
    $outflow_volume = Transaction::wherein('transaction_type', ['trading outflow','short trip outflow'])
     ->where('transaction_status', 'regular')
     ->whereDate('date', Carbon::today())
     ->sum('volume');
    $today_outflow_volume = number_format($outflow_volume, 2);
    
        return view('admin-pages.admin-dashboard',compact('today_inflow_vehicle','today_inflow_volume','today_outflow_vehicle','today_outflow_volume',));
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
