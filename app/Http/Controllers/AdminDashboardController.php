<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Ramsey\Collection\AbstractArray;

class AdminDashboardController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
    //trading inflow total vehicle and volume this day
    $trading_inflow_vehicle = Transaction::where('transaction_type', 'trading inflow')
                    ->where('transaction_status', 'regular')
                    ->whereDate('date', Carbon::today())
                    ->count('id');
    $today_trading_inflow_vehicle = number_format($trading_inflow_vehicle);      

    $trading_inflow_volume = Transaction::where('transaction_type', 'trading inflow')
                ->where('transaction_status', 'regular')
                ->whereDate('date', Carbon::today())
                ->sum('volume');
    $today_trading_inflow_volume = number_format($trading_inflow_volume, 2);
    
    //short trip inflow total vehicle and volume this day
    $short_trip_inflow_vehicle = Transaction::where('transaction_type', 'short trip inflow')
                    ->where('transaction_status', 'regular')
                    ->whereDate('date', Carbon::today())
                    ->count('id');
    $today_short_trip_inflow_vehicle = number_format($short_trip_inflow_vehicle);      

    $short_trip_inflow_volume = Transaction::where('transaction_type', 'short trip inflow')
                ->where('transaction_status', 'regular')
                ->whereDate('date', Carbon::today())
                ->sum('volume');
    $today_short_trip_inflow_volume = number_format($short_trip_inflow_volume, 2);
    
     //trading outflow total vehicle and volume this day
     $trading_outflow_vehicle = Transaction::where('transaction_type', 'trading outflow')
                    ->where('transaction_status', 'regular')
                    ->whereDate('date', Carbon::today())
                    ->count('id');
    $today_trading_outflow_vehicle = number_format($trading_outflow_vehicle);      

    $trading_outflow_volume = Transaction::where('transaction_type', 'trading outflow')
                ->where('transaction_status', 'regular')
                ->whereDate('date', Carbon::today())
                ->sum('volume');
    $today_trading_outflow_volume = number_format($trading_outflow_volume, 2);
    
    //short trip outflow total vehicle and volume this day
    $short_trip_outflow_vehicle = Transaction::where('transaction_type', 'short trip outflow')
    ->where('transaction_status', 'regular')
    ->whereDate('date', Carbon::today())
    ->count('id');
    $today_short_trip_outflow_vehicle = number_format($short_trip_outflow_vehicle);      
    
    $short_trip_outflow_volume = Transaction::where('transaction_type', 'short trip outflow')
    ->where('transaction_status', 'regular')
    ->whereDate('date', Carbon::today())
    ->sum('volume');
    $today_short_trip_outflow_volume = number_format($short_trip_outflow_volume, 2);

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
    
    $user = Auth::user();

   if($user->type==0){
    return view('admin-pages.admin-dashboard',compact('years','month','year','volume_tally_data','farmers','date','volume_tally_variance','volume_tally_outflow','volume_tally_inflow','volume_tally_dates','today_trading_inflow_vehicle','today_trading_inflow_volume','today_trading_outflow_vehicle','today_trading_outflow_volume','today_short_trip_inflow_vehicle','today_short_trip_inflow_volume','today_short_trip_outflow_vehicle','today_short_trip_outflow_volume',));
   }
   elseif($user->type==1){
    return view('staff-pages.staff-dashboard',compact('years','month','year','volume_tally_data','farmers','date','volume_tally_variance','volume_tally_outflow','volume_tally_inflow','volume_tally_dates','today_trading_inflow_vehicle','today_trading_inflow_volume','today_trading_outflow_vehicle','today_trading_outflow_volume','today_short_trip_inflow_vehicle','today_short_trip_inflow_volume','today_short_trip_outflow_vehicle','today_short_trip_outflow_volume',));
   }
    

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
