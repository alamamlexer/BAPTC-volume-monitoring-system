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
                'dry' =>$washingTransactions = Transaction::with('commodity')
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
                'cold' =>$washingTransactions = Transaction::with('commodity')
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
                'washing' =>$washingTransactions = Transaction::with('commodity')
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
                'intertrading' =>$washingTransactions = Transaction::with('commodity')
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
    // Initialize an array to hold the transformed data
    $formattedTransactions = $washingTransactions->map(function ($transaction) {
        return [
            'commodity_name' => $transaction->commodity ? $transaction->commodity->name : 'N/A', // Check for null
            'volume' => $transaction->volume,
        ];
    });
    
    // Calculate total volume
    $totalVolume = $formattedTransactions->sum('volume');


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

   $user = Auth::user();
   $userId = Auth::id();
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
            'washingTransactions',
            'intertradingTransactions',
           
            
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
            'washingTransactions',
            'intertradingTransactions',
           
            
        ));
   }
       
        
        }
}