<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Commodity;
use Carbon\Carbon; // Import Carbon for date handling
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());



        //table 1
        $table_one_data = [
            'AM_TRADING' => [
                'inflow' => number_format(
                    Transaction::where('time', 'AM')
                        ->where('transaction_type', 'trading inflow')
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0, // Default to 0 if null
                    0, '.', ',' // Format with thousands separator, no decimal places
                ),
            ],
            'PM_TRADING' => [
                'inflow' => number_format(
                    Transaction::where('time', 'PM')
                        ->where('transaction_type', 'trading inflow')
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'SHORT_TRIP_IN' => [
                'inflow' => number_format(
                    Transaction::where('transaction_type', 'short trip inflow')
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
           'DRY' => [
                'dry' =>$Transactions = Transaction::with('commodity')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(function ($transaction) {
                        return $transaction->transaction_type === 'dry';
                    })
                    ->sum('volume') ?: 0,
                0, '.', ','
            ],
            'COLD' => [
                'cold' =>$Transactions = Transaction::with('commodity')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(function ($transaction) {
                        return $transaction->transaction_type === 'cold';
                    })
                    ->sum('volume') ?: 0,
                0, '.', ','
            ],
            'WASHING' => [
                'washing' =>$Transactions = Transaction::with('commodity')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(function ($transaction) {
                        return $transaction->transaction_type === 'washing';
                    })
                    ->sum('volume') ?: 0,
                0, '.', ','
            ],
            'INTER_TRADING' => [
                'intertrading' =>$Transactions = Transaction::with('commodity')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(function ($transaction) {
                        return $transaction->transaction_type === 'intertrading';
                    })
                    ->sum('volume') ?: 0,
                0, '.', ','
            ],
            'SHORT_TRIP_OUT' => [
                'outflow' => number_format(
                    Transaction::where('transaction_type', 'short trip outflow')
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'AM_TRUCKINGS' => [
                'outflow' => number_format(
                    Transaction::where('time', 'AM')
                        ->where('transaction_type', 'trading outflow')
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'PM_TRUCKINGS' => [
                'outflow' => number_format(
                    Transaction::where('time', 'PM')
                        ->where('transaction_type', 'trading outflow')
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'GRAND_TOTAL_INFLOW' => [
                'all' => number_format(
                    Transaction::whereIn('transaction_type', ['trading inflow', 'short trip inflow'])
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
            'GRAND_TOTAL_OUTFLOW' => [
                'all' => number_format(
                    Transaction::whereIn('transaction_type', ['trading outflow', 'short trip outflow'])
                        ->where('transaction_status', 'regular')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('volume') ?: 0,
                    0, '.', ','
                ),
            ],
        ];
        //table 2
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calculate peak and lean days for the current month
        $R2_peakDay = Transaction::selectRaw('date, SUM(volume) as total_volume')
            ->where('transaction_status', 'regular') // Filter by regular transactions
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderByDesc('total_volume')
            ->first();

        $R2_leanDay = Transaction::selectRaw('date, SUM(volume) as total_volume')
            ->where('transaction_status', 'regular') // Filter by regular transactions
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('total_volume')
            ->first();

        // Prepare dates in the desired format
        $R2_peakDayDate = $R2_peakDay ? Carbon::parse($R2_peakDay->date)->format('F j, Y') : 'N/A';
        $R2_leanDayDate = $R2_leanDay ? Carbon::parse($R2_leanDay->date)->format('F j, Y') : 'N/A';
        
        
        //table 3
        
        
        $totalVolumeTrOu = Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->sum('volume');   
    $totalDaysTrOu = Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->distinct('date')
        ->count('date');
    $dailyAverageTrOu = $totalDaysTrOu > 0 ? $totalVolumeTrOu / $totalDaysTrOu : 0;
    
    $totalCountTrOu = Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->count();
    $dailyAvgCountTrOu = $totalDaysTrOu > 0 ? $totalCountTrOu / $totalDaysTrOu : 0;
    
    // Calculate daily averages and counts for trading inflow
    $totalVolumeTrIn = Transaction::where('transaction_type', 'trading inflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->sum('volume');   
    $totalDaysTrIn = Transaction::where('transaction_type', 'trading inflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->distinct('date')
        ->count('date');
    $dailyAverageTrIn = $totalDaysTrIn > 0 ? $totalVolumeTrIn / $totalDaysTrIn : 0;
    
    $totalCountTrIn = Transaction::where('transaction_type', 'trading inflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->count();
    $dailyAvgCountTrIn = $totalDaysTrIn > 0 ? $totalCountTrIn / $totalDaysTrIn : 0;
    
    // Calculate daily averages and counts for short trip outflow
    $totalVolumeShOu = Transaction::where('transaction_type', 'short trip outflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->sum('volume');   
    $totalDaysShOu = Transaction::where('transaction_type', 'short trip outflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->distinct('date')
        ->count('date');
    $dailyAverageShOu = $totalDaysShOu > 0 ? $totalVolumeShOu / $totalDaysShOu : 0;
    
    $totalCountShOu = Transaction::where('transaction_type', 'short trip outflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->count();
    $dailyAvgCountShOu = $totalDaysShOu > 0 ? $totalCountShOu / $totalDaysShOu : 0;
    
    // Calculate daily averages and counts for short trip inflow
    $totalVolumeShIn = Transaction::where('transaction_type', 'short trip inflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->sum('volume');   
    $totalDaysShIn = Transaction::where('transaction_type', 'short trip inflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->distinct('date')
        ->count('date');
    $dailyAverageShIn = $totalDaysShIn > 0 ? $totalVolumeShIn / $totalDaysShIn : 0;
    
    $totalCountShIn = Transaction::where('transaction_type', 'short trip inflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->count();
    $dailyAvgCountShIn = $totalDaysShIn > 0 ? $totalCountShIn / $totalDaysShIn : 0;

            // Store in the array with formatted values
            $table_three_data = [
                'trader' => number_format($dailyAverageTrOu, 0, '.', ','), 
                'farmer' => number_format($dailyAverageTrIn, 0, '.', ','), 
                'short_trip_in' => number_format($dailyAverageShIn, 0, '.', ','), 
                'short_trip_out' => number_format($dailyAverageShOu, 0, '.', ','), 
                'trader_count' => number_format($dailyAvgCountTrOu, 0, '.', ','),   
                'farmer_count' => number_format($dailyAvgCountTrIn, 0, '.', ','),   
                'short_trip_in_count' => number_format($dailyAvgCountShIn, 0, '.', ','), 
                'short_trip_out_count' => number_format($dailyAvgCountShOu, 0, '.', ','), 
            ];
        
        //table 6
        
        // Fetch commodities along with their transactions
$commodities = Commodity::with(['transactions' => function ($query) use ($startDate, $endDate) {
    // Filter transactions by date range in the query
    $query->whereBetween('date', [$startDate, $endDate]);
}])->get();

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

    //table 7
        $municipality = $request->input('municipality', null);

        // Fetch all transactions for the specified municipality (or all if null)
        $allTransactions = Transaction::with('commodity')
            ->when($municipality, function ($query) use ($municipality) {
                return $query->where('municipality', $municipality);
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
            ->get();

        // Group by municipality and then by commodity
        $transactionsByMunicipality = $allTransactions
            ->groupBy('municipality')
            ->map(function ($municipalityGroup) {
                $totalMunicipalityVolume = $municipalityGroup->sum('volume');

                return $municipalityGroup->groupBy('commodity_id')->map(function ($group) use ($totalMunicipalityVolume) {
                    $commodity = $group->first()->commodity;
                    $totalVolume = $group->sum('volume');
                    $deliveryFrequency = $group->count();

                    return [
                        'commodity' => $commodity,
                        'total_volume' => $totalVolume,
                        'delivery_frequency' => $deliveryFrequency,
                        'percentage_share' => $totalMunicipalityVolume > 0
                            ? ($totalVolume / $totalMunicipalityVolume) * 100
                            : 0,
                    ];
                });
            });
        
        
        // Prepare totals and subtotals for the view
        $subtotals = $transactionsByMunicipality->map(function ($commodities) {
            return [
                'subtotal_volume' => $commodities->sum('total_volume'),
                'subtotal_frequency' => $commodities->sum('delivery_frequency'),
            ];
        });

        // Calculate the grand totals for all municipalities
        $grandTotalVolume = $transactionsByMunicipality->flatten(1)->sum('total_volume');
        $grandTotalFrequency = $transactionsByMunicipality->flatten(1)->sum('delivery_frequency');

        // Prepare the grand total percentage
        $overallVolume = $allTransactions->sum('volume');
        $totalGrandPercentage = $overallVolume > 0 ? ($grandTotalVolume / $overallVolume) * 100 : 0;


        //table 8
        
        // Fetch and aggregate data for Table 8 by province
        $outflows = Transaction::where('transaction_type', 'trading outflow')
        ->where('transaction_status', 'regular')
        ->whereBetween('date', [$startDate, $endDate])
        ->get()
        ->groupBy('province');
    
    $table_eight_data = [];
    $grandTotalVolume = 0;
    $grandTotalFrequency = 0;
    
    foreach ($outflows as $province => $transactions) {
        $totalVolume = $transactions->sum('volume');
        $frequency = $transactions->count();
        
        $grandTotalVolume += $totalVolume;
        $grandTotalFrequency += $frequency;
        
        $table_eight_data[] = [
            'destination' => $province,
            'volume' => $totalVolume,
            'frequency' => $frequency,
            'percentage_share' => $frequency, // Raw frequency for later calculation
        ];
    }
    
    // Calculate percentage share for each province
    foreach ($table_eight_data as &$data) {
        $data['percentage_share'] = $grandTotalFrequency > 0
            ? number_format(($data['frequency'] / $grandTotalFrequency) * 100, 2) . '%'
            : '0.00%';
    }
    
    // Sort by percentage share in descending order
    usort($table_eight_data, function ($a, $b) {
        $percentA = (float) rtrim($a['percentage_share'], '%');
        $percentB = (float) rtrim($b['percentage_share'], '%');
        return $percentB <=> $percentA; // Sort by percentage in descending order
    });
    
    // Format grand totals
    $formattedGrandTotalVolume = number_format($grandTotalVolume, 0, '.', ',');
    $formattedGrandTotalFrequency = $grandTotalFrequency;

    //table 9 
     // Fetch washing transactions with commodity data
     $washingTransactions = Transaction::with('commodity')
     ->whereBetween('date', [$startDate, $endDate])
     ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
     ->get()
     ->filter(function ($transaction) {
         return $transaction->transaction_type === 'washing';
     });
 
 // Transform the filtered transactions
 $washformattedTransactions = $washingTransactions->map(function ($transaction) {
     return [
         'commodity_name' => $transaction->commodity ? $transaction->commodity->name : 'N/A', // Check for null
         'volume' => $transaction->volume ?? 0, // Default volume to 0 if null
     ];
 });
 $washtotalVolume = $washformattedTransactions->sum('volume');



    //table 10

    //table 11
    $province = $request->input('province', null);
    $intertradingTransactions = Transaction::with('commodity')
    ->when($province, function ($query) use ($province) {
        return $query->where('province', $province);
    })
    ->whereBetween('date', [$startDate, $endDate])
    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
    ->get()
    ->filter(function ($transaction) {
       return $transaction->transaction_type === 'intertrading';
   });
   // Initialize an array to hold the transformed data
   $formattedTransactions = $intertradingTransactions->map(function ($transaction) {
       return [
           'province' => $transaction->province,
           'commodity_name' => $transaction->commodity ? $transaction->commodity->name : 'N/A', // Check for null
           'volume' => $transaction->volume,
       ];
   });
   $totalVolume = $formattedTransactions->sum('volume');


   //table 12
    $currentYear = Carbon::now()->year;

    $past_year = $request->input('year', $currentYear-1);
    $current_year = $request->input('year', $currentYear);
    $month = $request->input('month', Carbon::now()->month);
    
    $month_name = Carbon::createFromFormat('m', $month)->format('F');
    $table_twelve_data = [
    // OUTPAST and OUTCURRENT
        'OUTPAST' => [
            'outflow' => number_format(
                $outPast = Transaction::where('transaction_type', 'trading outflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'OUTCURRENT' => [
            'outflow' => number_format(
                $outCurrent = Transaction::where('transaction_type', 'trading outflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'OUTFLOW_DIFFERENCE' => [
            'difference' => number_format(
                $outCurrent - $outPast,
                0, '.', ','
            ),
            'percentage' => $outPast != 0
                ? number_format((($outCurrent - $outPast) / $outPast) * 100, 2, '.', ',') . '%'
                : ($outCurrent != 0 ? '100%' : '0%'),
        ],

        // INPAST and INCURRENT
        'INPAST' => [
            'inflow' => number_format(
                $inPast = Transaction::where('transaction_type', 'trading inflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'INCURRENT' => [
            'inflow' => number_format(
                $inCurrent = Transaction::where('transaction_type', 'trading inflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'INFLOW_DIFFERENCE' => [
            'difference' => number_format(
                $inCurrent - $inPast,
                0, '.', ','
            ),
            'percentage' => $inPast != 0
                ? number_format((($inCurrent - $inPast) / $inPast) * 100, 2, '.', ',') . '%'
                : ($inCurrent != 0 ? '100%' : '0%'),
        ],

        // INSPAST and INSCURRENT
        'INSPAST' => [
            'inflow' => number_format(
                $inspPast = Transaction::where('transaction_type', 'short trip inflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'INSCURRENT' => [
            'inflow' => number_format(
                $inspCurrent = Transaction::where('transaction_type', 'short trip inflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'SHORT_TRIP_INFLOW_DIFFERENCE' => [
            'difference' => number_format(
                $inspCurrent - $inspPast,
                0, '.', ','
            ),
            'percentage' => $inspPast != 0
                ? number_format((($inspCurrent - $inspPast) / $inspPast) * 100, 2, '.', ',') . '%'
                : ($inspCurrent != 0 ? '100%' : '0%'),
        ],

        // OUTSPAST and OUTSCURRENT
        'OUTSPAST' => [
            'outflow' => number_format(
                $outsPast = Transaction::where('transaction_type', 'short trip outflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'OUTSCURRENT' => [
            'outflow' => number_format(
                $outsCurrent = Transaction::where('transaction_type', 'short trip outflow')
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'SHORT_TRIP_OUTFLOW_DIFFERENCE' => [
            'difference' => number_format(
                $outsCurrent - $outsPast,
                0, '.', ','
            ),
            'percentage' => $outsPast != 0
                ? number_format((($outsCurrent - $outsPast) / $outsPast) * 100, 2, '.', ',') . '%'
                : ($outsCurrent != 0 ? '100%' : '0%'),
        ],


        'PASTDRY' => [
            'dry' => number_format(
                $pastDry = Transaction::with('commodity')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'dry')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'CURRENTDRY' => [
            'dry' => number_format(
                $currentDry = Transaction::with('commodity')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'dry')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'DRY_DIFFERENCE' => [
            'dry' => number_format($currentDry - $pastDry, 0, '.', ','),
            'percentage' => $pastDry != 0
                ? number_format((($currentDry - $pastDry) / $pastDry) * 100, 2, '.', ',') . '%'
                : ($currentDry != 0 ? '100%' : '0%'),
        ],

        // COLD Commodity
        'PASTCOLD' => [
            'cold' => number_format(
                $pastCold = Transaction::with('commodity')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'cold')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'CURRENTCOLD' => [
            'cold' => number_format(
                $currentCold = Transaction::with('commodity')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'cold')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'COLD_DIFFERENCE' => [
            'cold' => number_format($currentCold - $pastCold, 0, '.', ','),
            'percentage' => $pastCold != 0
                ? number_format((($currentCold - $pastCold) / $pastCold) * 100, 2, '.', ',') . '%'
                : ($currentCold != 0 ? '100%' : '0%'),
        ],

        // INTERTRADING Commodity
        'PASTINTERTRADING' => [
            'intertrading' => number_format(
                $pastIntertrading = Transaction::with('commodity')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'intertrading')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'CURRENTINTERTRADING' => [
            'intertrading' => number_format(
                $currentIntertrading = Transaction::with('commodity')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'intertrading')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'INTERTRADING_DIFFERENCE' => [
            'intertrading' => number_format($currentIntertrading - $pastIntertrading, 0, '.', ','),
            'percentage' => $pastIntertrading != 0
                ? number_format((($currentIntertrading - $pastIntertrading) / $pastIntertrading) * 100, 2, '.', ',') . '%'
                : ($currentIntertrading != 0 ? '100%' : '0%'),
        ],

        // WASHING Commodity
        'PASTWASHING' => [
            'washing' => number_format(
                $pastWashing = Transaction::with('commodity')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'washing')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'CURRENTWASHING' => [
            'washing' => number_format(
                $currentWashing = Transaction::with('commodity')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->whereIn('transaction_type', ['dry', 'cold', 'washing', 'intertrading'])
                    ->get()
                    ->filter(fn($transaction) => $transaction->transaction_type === 'washing')
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'WASHING_DIFFERENCE' => [
            'washing' => number_format($currentWashing - $pastWashing, 0, '.', ','),
            'percentage' => $pastWashing != 0
                ? number_format((($currentWashing - $pastWashing) / $pastWashing) * 100, 2, '.', ',') . '%'
                : ($currentWashing != 0 ? '100%' : '0%'),
        ],

        // TOTALPAST_INCOME_VOLUME and TOTALCURRENT_INCOME_VOLUME
        'TOTALPAST_INCOME_VOLUME' => [
            'all' => number_format(
                $totalPastIncome = Transaction::whereIn('transaction_type', ['trading inflow', 'short trip inflow'])
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'TOTALCURRENT_INCOME_VOLUME' => [
            'all' => number_format(
                $totalCurrentIncome = Transaction::whereIn('transaction_type', ['trading inflow', 'short trip inflow'])
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'INCOME_DIFFERENCE' => [
            'all' => number_format(
                $totalCurrentIncome - $totalPastIncome,
                0, '.', ','
            ),
            'percentage' => $totalPastIncome != 0
                ? number_format((($totalCurrentIncome - $totalPastIncome) / $totalPastIncome) * 100, 2, '.', ',') . '%'
                : ($totalCurrentIncome != 0 ? '100%' : '0%'),
        ],

        // TOTALPAST_OUTGOING_VOLUME and TOTALCURRENT_OUTGOING_VOLUME
        'TOTALPAST_OUTGOING_VOLUME' => [
            'all' => number_format(
                $totalPastOutgoing = Transaction::whereIn('transaction_type', ['trading outflow', 'short trip outflow'])
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $past_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'TOTALCURRENT_OUTGOING_VOLUME' => [
            'all' => number_format(
                $totalCurrentOutgoing = Transaction::whereIn('transaction_type', ['trading outflow', 'short trip outflow'])
                    ->where('transaction_status', 'regular')
                    ->whereYear('date', $current_year)
                    ->whereMonth('date', $month)
                    ->sum('volume') ?: 0,
                0, '.', ','
            ),
        ],
        'OUTGOING_DIFFERENCE' => [
            'all' => number_format(
                $totalCurrentOutgoing - $totalPastOutgoing,
                0, '.', ','
            ),
            'percentage' => $totalPastOutgoing != 0
                ? number_format((($totalCurrentOutgoing - $totalPastOutgoing) / $totalPastOutgoing) * 100, 2, '.', ',') . '%'
                : ($totalCurrentOutgoing != 0 ? '100%' : '0%'),
        ],
    ];

   $user = Auth::user();
   if ($user->type == 0) {
        // Passing data to the view
        return view('admin-pages.report', compact(
            'table_one_data',
            'table_three_data',
            'R2_peakDayDate',
            'R2_peakDay',
            'R2_leanDayDate',
            'R2_leanDay',
            'table_six_commodities',
            'table_six_grand_total_volume',
            'transactionsByMunicipality',
            'subtotals',
            'grandTotalVolume',
            'grandTotalFrequency',
            'totalGrandPercentage',
            'table_eight_data',
            'formattedGrandTotalVolume',
            'formattedGrandTotalFrequency',
            'startDate',
            'endDate',
            'totalVolume',
            'formattedTransactions',
            'washformattedTransactions',
            'washingTransactions',
            'washtotalVolume',
            'intertradingTransactions',
            'table_twelve_data',
            'month_name', 'past_year', 'current_year',
            
           
            
        ));
   } elseif ($user->type == 1) {
        // Passing data to the view
        return view('staff-pages.staff-report', compact(
              'table_one_data',
            'table_three_data',
            'R2_peakDayDate',
            'R2_peakDay',
            'R2_leanDayDate',
            'R2_leanDay',
            'table_six_commodities',
            'table_six_grand_total_volume',
            'transactionsByMunicipality',
            'subtotals',
            'grandTotalVolume',
            'grandTotalFrequency',
            'totalGrandPercentage',
            'table_eight_data',
            'formattedGrandTotalVolume',
            'formattedGrandTotalFrequency',
            'startDate',
            'endDate',
            'totalVolume',
            'formattedTransactions',
            'washformattedTransactions',
            'washingTransactions',
            'washtotalVolume',
            'intertradingTransactions',
            'table_twelve_data',
            'month_name', 'past_year', 'current_year',
        ));
   }
       
        
        }
}