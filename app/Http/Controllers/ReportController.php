<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Commodity;
use Carbon\Carbon; // Import Carbon for date handling
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        //table 1
        $table_one_data = [
            'AM_TRADING' => [
                'inflow' => number_format(
                    Transaction::where('time', 'AM')
                        ->where('transaction_type', 'trading inflow')
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0, // Default to 0 if null
                    0, '.', ',' // Format with thousands separator, no decimal places
                ),
            ],
            'PM_TRADING' => [
                'inflow' => number_format(
                    Transaction::where('time', 'PM')
                        ->where('transaction_type', 'trading inflow')
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'SHORT_TRIP_IN' => [
                'inflow' => number_format(
                    Transaction::where('transaction_type', 'short trip inflow')
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'SHORT_TRIP_OUT' => [
                'outflow' => number_format(
                    Transaction::where('transaction_type', 'short trip outflow')
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'AM_TRUCKINGS' => [
                'outflow' => number_format(
                    Transaction::where('time', 'AM')
                        ->where('transaction_type', 'trading outflow')
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'PM_TRUCKINGS' => [
                'outflow' => number_format(
                    Transaction::where('time', 'PM')
                        ->where('transaction_type', 'trading outflow')
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'GRAND_TOTAL_INFLOW' => [
                'all' => number_format(
                    Transaction::whereIn('transaction_type', ['trading inflow', 'short trip inflow'])
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'GRAND_TOTAL_OUTFLOW' => [
                'all' => number_format(
                    Transaction::whereIn('transaction_type', ['trading outflow', 'short trip outflow'])
                        ->where('transaction_status', 'regular')
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
        ];

        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calculate peak and lean days for the current month
        $R2_peakDay = Transaction::selectRaw('date, SUM(volume) as total_volume')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('transaction_status', 'regular') // Filter by regular transactions
            ->groupBy('date')
            ->orderByDesc('total_volume')
            ->first();

        $R2_leanDay = Transaction::selectRaw('date, SUM(volume) as total_volume')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('transaction_status', 'regular') // Filter by regular transactions
            ->groupBy('date')
            ->orderBy('total_volume')
            ->first();

        // Prepare dates in the desired format
        $R2_peakDayDate = $R2_peakDay ? Carbon::parse($R2_peakDay->date)->format('F j, Y') : 'N/A';
        $R2_leanDayDate = $R2_leanDay ? Carbon::parse($R2_leanDay->date)->format('F j, Y') : 'N/A';
        
        
        //table 3
        
        $totalVolumeTrOu = Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->sum('volume');   
        $totalDaysTrOu = Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->distinct('date') // assuming there's a 'date' column in the transactions table
        ->count('date');
        $dailyAverageTrOu = $totalDaysTrOu > 0 ? $totalVolumeTrOu / $totalDaysTrOu : 0;
        
        $totalVolumeTrIn = Transaction::where('transaction_type', 'trading inflow')
        ->where('transaction_status', 'regular')
        ->sum('volume');   
        $totalDaysTrIn = Transaction::where('transaction_type', 'trading inflow')
        ->where('transaction_status', 'regular')
        ->distinct('date') // assuming there's a 'date' column in the transactions table
        ->count('date');
        $dailyAverageTrIn = $totalDaysTrIn > 0 ? $totalVolumeTrIn / $totalDaysTrIn : 0; 
        
        $totalVolumeShOu = Transaction::where('transaction_type', 'short trip outflow')
        ->where('transaction_status', 'regular')
        ->sum('volume');   
        $totalDaysShOu= Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->distinct('date') // assuming there's a 'date' column in the transactions table
        ->count('date');
        $dailyAverageShOu = $totalDaysShOu > 0 ? $totalVolumeShOu / $totalDaysShOu : 0; 
        
        $totalVolumeShIn = Transaction::where('transaction_type', 'trading inflow')
        ->where('transaction_status', 'regular')
        ->sum('volume');   
        $totalDaysShIn = Transaction::where('transaction_type', 'trading inflow')
        ->where('transaction_status', 'regular')
        ->distinct('date') // assuming there's a 'date' column in the transactions table
        ->count('date');
        $dailyAverageShIn = $totalDaysShIn > 0 ? $totalVolumeShIn / $totalDaysShIn : 0;
        
        $totalCountTrOu = Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->count();
        $dailyAvgCountTrOu = $totalDaysTrOu > 0 ? $totalCountTrOu / $totalDaysTrOu : 0;
        
        $totalCountTrIn = Transaction::where('transaction_type', 'trading inflow')
            ->where('transaction_status', 'regular')
            ->count();
        $dailyAvgCountTrIn = $totalDaysTrIn > 0 ? $totalCountTrIn / $totalDaysTrIn : 0;
        
        $totalCountShOu = Transaction::where('transaction_type', 'short trip outflow')
            ->where('transaction_status', 'regular')
            ->count();
        $dailyAvgCountShOu = $totalDaysShOu > 0 ? $totalCountShOu / $totalDaysShOu : 0;
        
        $totalCountShIn = Transaction::where('transaction_type', 'short trip inflow')
            ->where('transaction_status', 'regular')
            ->count();
        $dailyAvgCountShIn = $totalDaysShIn > 0 ? $totalCountShIn / $totalDaysShIn : 0;
        
        // Store in the array with formatting
        $table_three_data = [
        'trader' => number_format($dailyAverageTrOu, 0, '.', ','), 
        'farmer' => number_format($dailyAverageTrIn, 0, '.', ','), 
        'short_trip_in' => number_format($dailyAverageShIn, 0, '.', ','), 
        'short_trip_out' => number_format($dailyAverageShOu, 0, '.', ','), 
        'trader_count' => $dailyAvgCountShOu,   
        'farmer_count' => $dailyAvgCountShIn,   
        'short_trip_in_count' => $dailyAvgCountShIn, 
        'short_trip_out_count' => $dailyAvgCountShOu, 
        ];
        
        //table 6
        
        $commodities = Commodity::with('transactions')->get();

        // Group transactions by municipality for each commodity and calculate volumes
        $table_six_commodities = $commodities->map(function ($commodity) {
            // Group transactions by municipality and sum the volumes
            $transactionsGrouped = $commodity->transactions->groupBy('municipality')->map(function ($transactions) {
                return [
                    'municipality' => $transactions->first()->municipality, // Get the municipality name
                    'total_volume' => $transactions->sum('volume'), // Sum of volumes for the municipality
                ];
            });
    
            // Calculate the total volume for the commodity
            $totalCommodityVolume = $transactionsGrouped->sum('total_volume');
    
            return [
                'commodity_name' => $commodity->commodity_name,
                'transactions'   => $transactionsGrouped,
                'total_volume'   => $totalCommodityVolume, // Total volume for this commodity
               
            ];
        })->filter(function ($commodity) {
            // Filter out commodities with no transactions
            return $commodity['transactions']->isNotEmpty();
        });
        
        
        $table_six_commodities = $table_six_commodities->sortByDesc('total_volume');
        
        $table_six_grand_total_volume = $table_six_commodities->sum('total_volume');

        // Passing data to the view
        return view('admin-pages.report', compact('table_one_data', 
                                                            'table_three_data', 
                                                                        'R2_peakDayDate', 
                                                                        'R2_peakDay', 
                                                                        'R2_leanDayDate', 
                                                                        'R2_leanDay',
                                                                        'table_six_commodities',
                                                                        'table_six_grand_total_volume',
                                                                        
        ));
        
        }
}
