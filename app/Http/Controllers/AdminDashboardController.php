<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

use Ramsey\Collection\AbstractArray;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
    //inflow total vehicle and volume this day
    $inflow_vehicle = Transaction::wherein('transaction_type', ['trading inflow','short trip inflow'])
                    ->where('transaction_status', 'regular')
                    ->whereDate('date', Carbon::today())
                    ->count('id');
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

    //Volume Tally Table
        
    $volume_tally_inflow=Transaction::wherein('transaction_type', ['trading inflow','short trip inflow'])
                                    ->where('transaction_status', 'regular')
                                    ->sum('volume');

    $volume_tally_outflow=Transaction::wherein('transaction_type', ['trading outflow','short trip outflow'])
    ->where('transaction_status', 'regular')
    ->sum('volume');
 
    $volume_tally_variance= abs($volume_tally_inflow - $volume_tally_outflow);
        
    $currentYear = Carbon::now()->year;
    
    // Create an array for years from 1900 to the current year
    $years = range(1900, $currentYear);
    
    // Set the default year and month based on the request or current date
    
    $year = $request->input('year', $currentYear);
    $month = $request->input('month', Carbon::now()->month);
    
    $date = Carbon::createFromDate($year, $month, 1);
    
    $volume_tally_dates = [];  // Array to store all formatted dates
    for ($day = 1; $day <= $date->daysInMonth; $day++) {
        $volume_tally_dates[] = $date->copy()->day($day);  // Format and store date
    }
    
    $volume_tally_data=[];
    foreach($volume_tally_dates as $dates){
        $date=$dates->format('l, F j, Y');
        $x_date=$dates->format('Y-m-d');
        
        $farmer=Transaction::where('transaction_type', 'trading inflow')
                ->where('transaction_status', 'regular')
                ->whereDate('date',  $x_date)
                ->sum('volume');
        $farmers = number_format($farmer);

        $short_trip_in=Transaction::where('transaction_type', 'short trip inflow')
                ->where('transaction_status', 'regular')
                ->whereDate('date',  $x_date)
                ->sum('volume');
        $short_trip_ins = number_format($short_trip_in); 

        $daily_in_total= number_format($farmer+$short_trip_in);

        $trucking=Transaction::where('transaction_type', 'trading outflow')
                ->where('transaction_status', 'regular')
                ->whereDate('date',  $x_date)
                ->sum('volume');
        $truckings = number_format($trucking);

        $short_trip_out=Transaction::where('transaction_type', 'short trip outflow')
                ->where('transaction_status', 'regular')
                ->whereDate('date',  $x_date)
                ->sum('volume');
        $short_trip_outs = number_format($short_trip_out); 

        $daily_out_total= number_format($trucking+$short_trip_out);

        $variance = abs(($farmer+$short_trip_in)-($trucking+$short_trip_out));
        $remarks='';

        $volume_tally_data[] = [
            'date'=>$date,
            'farmers' => $farmers,
            'short_trip_ins' => $short_trip_ins,
            'daily_in_total' => $daily_in_total,
            'truckings' => $truckings,
            'short_trip_outs' => $short_trip_outs,
            'daily_out_total' => $daily_out_total,
            'variance' => number_format($variance),
            'remarks' => $remarks,
        ];
    }
    
    
    
    

        return view('admin-pages.admin-dashboard',compact('years','month','year','volume_tally_data','farmers','date','volume_tally_variance','volume_tally_outflow','volume_tally_inflow','volume_tally_dates','today_inflow_vehicle','today_inflow_volume','today_outflow_vehicle','today_outflow_volume',));
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
