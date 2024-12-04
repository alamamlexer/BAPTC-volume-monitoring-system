<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SummaryReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the current year and range type (daily, monthly, yearly, quarterly)
    $currentYear = now()->year;
    $rangeType = $request->input('range_type', 'daily');
    $year = $request->input('year', $currentYear);

    // Set default start and end dates
    $startDate = null;
    $endDate = null;

    // Get the current month for default selection
    $currentMonth = now()->month;

    // Get the selected month from the request, defaulting to the current month if not provided
    $month = $request->input('month', $currentMonth);

    // Set start and end dates based on selected range (for daily, monthly, yearly, quarterly views)
    if ($rangeType == 'daily') {
        $startDate = $request->input('date', Carbon::now()->toDateString());
        $endDate = $startDate;
    } elseif ($rangeType == 'monthly') {
        // Ensure $month is a valid integer (e.g., 1-12 for January-December)
        $startDate = Carbon::createFromDate($year, $month, 1)->toDateString();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
    } elseif ($rangeType == 'custom') {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    }
    else { // For yearly or quarterly range
        $startDate = Carbon::createFromDate($year, 1, 1)->toDateString();
        $endDate = Carbon::createFromDate($year, 12, 31)->toDateString();
    }

    // Fetch commodities and their transactions (both trading inflow and short trip inflow & trading outflow and short trip outflow)
    $commodities = Commodity::with(['transactions' => function ($query) use ($startDate, $endDate) {
        $query->whereBetween('date', [$startDate, $endDate])
              ->where('transaction_status', 'regular')
              ->whereIn('transaction_type', ['trading inflow', 'short trip inflow', 'trading outflow', 'short trip outflow']);
    }])->get();

    // Calculate the total volume for each commodity for both inflow types (trading inflow and short trip inflow)
    $inflow_volumes = $commodities->map(function ($commodity) use ($year, $month) {
        // Calculate trading inflow volume (regular trading inflow)
        $totalVolume = $commodity->transactions
            ->where('transaction_type', 'trading inflow')
            ->sum('volume') ?? 0;

        // Calculate short trip inflow volume
        $shortTripInflowVolume = $commodity->transactions
            ->where('transaction_type', 'short trip inflow')
            ->sum('volume') ?? 0;

        // Calculate short trip inflow percentage share
        $shortTripPercentageShare = ($totalVolume > 0)
            ? ($shortTripInflowVolume / $totalVolume) * 100
            : 0;

        return [
            'commodity_name' => $commodity->commodity_name,
            'total_volume' => $totalVolume,
            'short_trip_inflow_volume' => $shortTripInflowVolume,
            'short_trip_percentage_share' => $shortTripPercentageShare,
            'monthly_volumes' => $this->getMonthlyVolumes($commodity, $year, $month),  // Pass $month here
            'quarterly_volumes' => $this->getQuarterlyVolumes($commodity, $year),
            'monthly_short_trip_inflow_volumes' => $this->getMonthlyShortTripInflowVolumes($commodity, $year, $month),  // Pass $month here
            'quarterly_short_trip_inflow_volumes' => $this->getQuarterlyShortTripInflowVolumes($commodity, $year),
        ];
    });

    // Calculate the total volume for each commodity for both outflow types (trading outflow and short trip outflow)
    $outflow_volumes = $commodities->map(function ($commodity) use ($year, $month) {
        // Calculate trading outflow volume (regular trading outflow)
        $outtotalVolume = $commodity->transactions
            ->where('transaction_type', 'trading outflow')
            ->sum('volume') ?? 0;

        // Calculate short trip outflow volume
        $shortTripOutflowVolume = $commodity->transactions
            ->where('transaction_type', 'short trip outflow')
            ->sum('volume') ?? 0;

        // Calculate short trip outflow percentage share
        $shortTripOutPercentageShare = ($outtotalVolume > 0)
            ? ($shortTripOutflowVolume / $outtotalVolume) * 100
            : 0;

        return [
            'commodity_name' => $commodity->commodity_name,
            'outtotal_volume' => $outtotalVolume,
            'short_trip_outflow_volume' => $shortTripOutflowVolume,
            'short_trip_outpercentage_share' => $shortTripOutPercentageShare,
            'outmonthly_volumes' => $this->getOutMonthlyVolumes($commodity, $year, $month), // Pass $month here
            'outquarterly_volumes' => $this->getOutQuarterlyVolumes($commodity, $year),
            'monthly_short_trip_outflow_volumes' => $this->getMonthlyShortTripOutflowVolumes($commodity, $year, $month), // Pass $month here
            'quarterly_short_trip_outflow_volumes' => $this->getQuarterlyShortTripOutflowVolumes($commodity, $year),
        ];
    });

        // Sort commodities by total volume in descending order for the trading inflow & outflow table
        $inflow_volumes = $inflow_volumes->sortByDesc('total_volume')->values();
        $outflow_volumes = $outflow_volumes->sortByDesc('outtotal_volume')->values();

        // Assign ranks based on the sorted order for trading inflow & outflow table
        $inflow_volumes = $inflow_volumes->map(function ($commodity, $index) {
            $commodity['rank'] = $index + 1;
            return $commodity;
        });
        $outflow_volumes = $outflow_volumes->map(function ($commodity, $index) {
            $commodity['rank'] = $index + 1;
            return $commodity;
        });

        // Filter and sort short trip inflow & outflow volumes
        $short_Trip_Inflow_Volumes = $inflow_volumes->filter(function ($commodity) {
            return $commodity['short_trip_inflow_volume'] > 0;
        });
        $short_Trip_Outflow_Volumes = $outflow_volumes->filter(function ($commodity) {
            return $commodity['short_trip_outflow_volume'] > 0;
        });

        // Sort short trip inflow volumes by short trip inflow & outflow volume in descending order
        $short_Trip_Inflow_Volumes = $short_Trip_Inflow_Volumes->sortByDesc('short_trip_inflow_volume')->values();
        $short_Trip_Outflow_Volumes = $short_Trip_Outflow_Volumes->sortByDesc('short_trip_outflow_volume')->values();
        // Assign ranks to short trip inflow volumes after filtering and sorting
        $short_Trip_Inflow_Volumes = $short_Trip_Inflow_Volumes->map(function ($commodity, $index) {
            $commodity['rank'] = $index + 1;
            return $commodity;
        });
        $short_Trip_Outflow_Volumes = $short_Trip_Outflow_Volumes->map(function ($commodity, $index) {
            $commodity['rank'] = $index + 1;
            return $commodity;
        });

        // Calculate the grand total volume for both inflows & outflow (regular and short trip)
        $grandTotalVolume = $inflow_volumes->sum('total_volume');
        $grandTotalShortTripInflow = $inflow_volumes->sum('short_trip_inflow_volume');

        $grandOutTotalVolume = $outflow_volumes->sum('outtotal_volume');
        $grandTotalShortTripOutflow = $outflow_volumes->sum('short_trip_outflow_volume');

        // Calculate percentage share for each commodity
        $inflow_volumes = $inflow_volumes->map(function ($commodity) use ($grandTotalVolume) {
            $commodity['percentage_share'] = $grandTotalVolume > 0
                ? ($commodity['total_volume'] / $grandTotalVolume) * 100
                : 0;
            return $commodity;
        });
        $outflow_volumes = $outflow_volumes->map(function ($commodity) use ($grandOutTotalVolume) {
            $commodity['outpercentage_share'] = $grandOutTotalVolume > 0
                ? ($commodity['outtotal_volume'] / $grandOutTotalVolume) * 100
                : 0;
            return $commodity;
        });


        // Calculate monthly and quarterly grand totals for short trip inflows & outflow
        $monthlyShortTripGrandTotals = $this->calculateMonthlyShortTripGrandTotals($inflow_volumes);
        $quarterlyShortTripGrandTotals = $this->calculateQuarterlyShortTripGrandTotals($inflow_volumes);

        $monthlyShortTripOutGrandTotals = $this->calculateMonthlyShortTripOutGrandTotals($outflow_volumes);
        $quarterlyShortTripOutGrandTotals = $this->calculateQuarterlyShortTripOutGrandTotals($outflow_volumes);

        // Calculate monthly and quarterly grand totals for trading inflows & outflow
        $monthlyGrandTotals = $this->calculateMonthlyGrandTotals($inflow_volumes);
        $quarterlyGrandTotals = $this->calculateQuarterlyGrandTotals($inflow_volumes);

        $monthlyOutGrandTotals = $this->calculateMonthlyOutGrandTotals($outflow_volumes);
        $quarterlyOutGrandTotals = $this->calculateQuarterlyOutGrandTotals($outflow_volumes);

        $user = Auth::user();
        if ($user->type == 0) {
            // Pass the data to the view
            return view('admin-pages.summary-report', [
                'inflow_volumes' => $inflow_volumes,
                'shortTripInflowVolumes' => $short_Trip_Inflow_Volumes,
                'grandTotalVolume' => $grandTotalVolume,
                'grandTotalShortTripInflow' => $grandTotalShortTripInflow,
                'monthlyGrandTotals' => $monthlyGrandTotals,
                'monthlyShortTripGrandTotals' => $monthlyShortTripGrandTotals,
                'quarterlyShortTripGrandTotals' => $quarterlyShortTripGrandTotals,
                'quarterlyGrandTotals' => $quarterlyGrandTotals,
                'selectedMonth' => $month, // Passing the selected month


                'outflow_volumes' => $outflow_volumes,
                'shortTripOutflowVolumes' => $short_Trip_Outflow_Volumes,
                'grandOutTotalVolume' => $grandOutTotalVolume,
                'grandTotalShortTripOutflow' => $grandTotalShortTripOutflow,
                'monthlyOutGrandTotals' => $monthlyOutGrandTotals,
                'monthlyShortTripOutGrandTotals' => $monthlyShortTripOutGrandTotals,
                'quarterlyShortTripOutGrandTotals' => $quarterlyShortTripOutGrandTotals,
                'quarterlyOutGrandTotals' => $quarterlyOutGrandTotals,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'rangeType' => $rangeType,
                'currentYear' => $currentYear,
                'selectedYear' => $year,
            ]);
        } elseif ($user->type == 1) {
            return view('staff-pages.staff-summary-report', [
                'inflow_volumes' => $inflow_volumes,
                'shortTripInflowVolumes' => $short_Trip_Inflow_Volumes,
                'grandTotalVolume' => $grandTotalVolume,
                'grandTotalShortTripInflow' => $grandTotalShortTripInflow,
                'monthlyGrandTotals' => $monthlyGrandTotals,
                'monthlyShortTripGrandTotals' => $monthlyShortTripGrandTotals,
                'quarterlyShortTripGrandTotals' => $quarterlyShortTripGrandTotals,
                'quarterlyGrandTotals' => $quarterlyGrandTotals,
                'selectedMonth' => $month, // Passing the selected month

                'outflow_volumes' => $outflow_volumes,
                'shortTripOutflowVolumes' => $short_Trip_Outflow_Volumes,
                'grandOutTotalVolume' => $grandOutTotalVolume,
                'grandTotalShortTripOutflow' => $grandTotalShortTripOutflow,
                'monthlyOutGrandTotals' => $monthlyOutGrandTotals,
                'monthlyShortTripOutGrandTotals' => $monthlyShortTripOutGrandTotals,
                'quarterlyShortTripOutGrandTotals' => $quarterlyShortTripOutGrandTotals,
                'quarterlyOutGrandTotals' => $quarterlyOutGrandTotals,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'rangeType' => $rangeType,
                'currentYear' => $currentYear,
                'selectedYear' => $year,
            ]);
        }
    }

    // ==============================
    // Helper Methods for Volume Calculation
    // ==============================

    public function getMonthlyVolumes($commodity, $year, $month)
    {
        $monthlyVolumes = [];

        foreach (range(1, 12) as $month) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $monthlyVolumes[$month] = $commodity->transactions
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('transaction_type', 'trading inflow')
                ->sum('volume') ?? 0;
        }


        return $monthlyVolumes;
    }

    private function getOutMonthlyVolumes($commodity, $year, $month)
    {
        $monthlyVolumes = [];

        foreach (range(1, 12) as $month) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $monthlyVolumes[$month] = $commodity->transactions
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('transaction_type', 'trading outflow')
                ->sum('volume') ?? 0;
        }


        return $monthlyVolumes;
    }

    private function getQuarterlyVolumes($commodity, $year)
    {
        $quarterlyVolumes = [];
        $quarters = [
            1 => ['start' => Carbon::create($year, 1, 1), 'end' => Carbon::create($year, 3, 31)],
            2 => ['start' => Carbon::create($year, 4, 1), 'end' => Carbon::create($year, 6, 30)],
            3 => ['start' => Carbon::create($year, 7, 1), 'end' => Carbon::create($year, 9, 30)],
            4 => ['start' => Carbon::create($year, 10, 1), 'end' => Carbon::create($year, 12, 31)],
        ];

        foreach ($quarters as $quarter => $range) {
            $quarterlyVolumes[$quarter] = $commodity->transactions
                ->whereBetween('date', [$range['start'], $range['end']])
                ->where('transaction_type', 'trading inflow')
                ->sum('volume') ?? 0;
        }

        return $quarterlyVolumes;
    }

    private function getOutQuarterlyVolumes($commodity, $year)
    {
        $quarterlyVolumes = [];
        $quarters = [
            1 => ['start' => Carbon::create($year, 1, 1), 'end' => Carbon::create($year, 3, 31)],
            2 => ['start' => Carbon::create($year, 4, 1), 'end' => Carbon::create($year, 6, 30)],
            3 => ['start' => Carbon::create($year, 7, 1), 'end' => Carbon::create($year, 9, 30)],
            4 => ['start' => Carbon::create($year, 10, 1), 'end' => Carbon::create($year, 12, 31)],
        ];

        foreach ($quarters as $quarter => $range) {
            $quarterlyVolumes[$quarter] = $commodity->transactions
                ->whereBetween('date', [$range['start'], $range['end']])
                ->where('transaction_type', 'trading outflow')
                ->sum('volume') ?? 0;
        }

        return $quarterlyVolumes;
    }

    private function getMonthlyShortTripInflowVolumes($commodity, $year)
    {
        $monthlyVolumes = [];

        foreach (range(1, 12) as $month) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

            $monthlyVolumes[$month] = $commodity->transactions
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('transaction_type', 'short trip inflow')
                ->sum('volume') ?? 0;
        }

        return $monthlyVolumes;
    }

    private function getMonthlyShortTripOutflowVolumes($commodity, $year)
    {
        $monthlyVolumes = [];

        foreach (range(1, 12) as $month) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

            $monthlyVolumes[$month] = $commodity->transactions
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('transaction_type', 'short trip outflow')
                ->sum('volume') ?? 0;
        }

        return $monthlyVolumes;
    }

    private function getQuarterlyShortTripInflowVolumes($commodity, $year)
    {
        $quarterlyVolumes = [];

        $quarters = [
            1 => [
                'start' => Carbon::create($year, 1, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 3, 31)->endOfMonth()->toDateString()
            ],
            2 => [
                'start' => Carbon::create($year, 4, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 6, 30)->endOfMonth()->toDateString()
            ],
            3 => [
                'start' => Carbon::create($year, 7, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 9, 30)->endOfMonth()->toDateString()
            ],
            4 => [
                'start' => Carbon::create($year, 10, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 12, 31)->endOfMonth()->toDateString()
            ],
        ];

        foreach ($quarters as $quarter => $range) {
            $quarterlyVolumes[$quarter] = $commodity->transactions
                ->whereBetween('date', [$range['start'], $range['end']])
                ->where('transaction_type', 'short trip inflow')
                ->sum('volume') ?? 0;
        }

        return $quarterlyVolumes;
    }

    private function getQuarterlyShortTripOutflowVolumes($commodity, $year)
    {
        $quarterlyVolumes = [];

        $quarters = [
            1 => [
                'start' => Carbon::create($year, 1, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 3, 31)->endOfMonth()->toDateString()
            ],
            2 => [
                'start' => Carbon::create($year, 4, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 6, 30)->endOfMonth()->toDateString()
            ],
            3 => [
                'start' => Carbon::create($year, 7, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 9, 30)->endOfMonth()->toDateString()
            ],
            4 => [
                'start' => Carbon::create($year, 10, 1)->startOfMonth()->toDateString(),
                'end' => Carbon::create($year, 12, 31)->endOfMonth()->toDateString()
            ],
        ];

        foreach ($quarters as $quarter => $range) {
            $quarterlyVolumes[$quarter] = $commodity->transactions
                ->whereBetween('date', [$range['start'], $range['end']])
                ->where('transaction_type', 'short trip outflow')
                ->sum('volume') ?? 0;
        }

        return $quarterlyVolumes;
    }



    private function calculateMonthlyGrandTotals($inflowVolumes)
    {
        $monthlyGrandTotals = [];

        foreach (range(1, 12) as $month) {
            $monthlyGrandTotals[$month] = $inflowVolumes->sum(function ($commodity) use ($month) {
                return $commodity['monthly_volumes'][$month] ?? 0;  // Default to 0 if the month doesn't exist
            });
        }

        return $monthlyGrandTotals;
    }

    private function calculateMonthlyOutGrandTotals($outflowVolumes)
    {
        $monthlyOutGrandTotals = [];

        foreach (range(1, 12) as $month) {
            $monthlyOutGrandTotals[$month] = $outflowVolumes->sum(function ($commodity) use ($month) {
                return $commodity['outmonthly_volumes'][$month] ?? 0;  // Default to 0 if the month doesn't exist
            });
        }

        return $monthlyOutGrandTotals;
    }

    private function calculateQuarterlyGrandTotals($inflowVolumes)
    {
        $quarterlyGrandTotals = [
            'Q1' => 0,
            'Q2' => 0,
            'Q3' => 0,
            'Q4' => 0,
        ];

        foreach (range(1, 4) as $quarter) {
            $quarterlyGrandTotals['Q' . $quarter] = $inflowVolumes->sum(function ($commodity) use ($quarter) {
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;
                return ($commodity['monthly_volumes'][$startMonth] ?? 0) +
                    ($commodity['monthly_volumes'][$startMonth + 1] ?? 0) +
                    ($commodity['monthly_volumes'][$endMonth] ?? 0);
            });
        }

        return $quarterlyGrandTotals;
    }

    private function calculateQuarterlyOutGrandTotals($outflowVolumes)
    {
        $quarterlyOutGrandTotals = [
            'Q1' => 0,
            'Q2' => 0,
            'Q3' => 0,
            'Q4' => 0,
        ];

        foreach (range(1, 4) as $quarter) {
            $quarterlyOutGrandTotals['Q' . $quarter] = $outflowVolumes->sum(function ($commodity) use ($quarter) {
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;
                return ($commodity['outmonthly_volumes'][$startMonth] ?? 0) +
                    ($commodity['outmonthly_volumes'][$startMonth + 1] ?? 0) +
                    ($commodity['outmonthly_volumes'][$endMonth] ?? 0);
            });
        }

        return $quarterlyOutGrandTotals;
    }

    private function calculateMonthlyShortTripGrandTotals($inflowVolumes)
    {
        $monthlyShortTripGrandTotals = [];

        foreach (range(1, 12) as $month) {
            $monthlyShortTripGrandTotals[$month] = $inflowVolumes->sum(function ($commodity) use ($month) {
                return $commodity['monthly_short_trip_inflow_volumes'][$month] ?? 0;
            });
        }

        return $monthlyShortTripGrandTotals;
    }

    private function calculateMonthlyShortTripOutGrandTotals($outflowVolumes)
    {
        $monthlyShortTripOutGrandTotals = [];

        foreach (range(1, 12) as $month) {
            $monthlyShortTripOutGrandTotals[$month] = $outflowVolumes->sum(function ($commodity) use ($month) {
                return $commodity['monthly_short_trip_outflow_volumes'][$month] ?? 0;
            });
        }

        return $monthlyShortTripOutGrandTotals;
    }

    private function calculateQuarterlyShortTripGrandTotals($inflowVolumes)
    {
        $quarterlyShortTripGrandTotals = [
            'Q1' => 0,
            'Q2' => 0,
            'Q3' => 0,
            'Q4' => 0,
        ];

        foreach (range(1, 4) as $quarter) {
            $quarterlyShortTripGrandTotals['Q' . $quarter] = $inflowVolumes->sum(function ($commodity) use ($quarter) {
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;
                return ($commodity['monthly_short_trip_inflow_volumes'][$startMonth] ?? 0) +
                    ($commodity['monthly_short_trip_inflow_volumes'][$startMonth + 1] ?? 0) +
                    ($commodity['monthly_short_trip_inflow_volumes'][$endMonth] ?? 0);
            });
        }

        return $quarterlyShortTripGrandTotals;
    }

    private function calculateQuarterlyShortTripOutGrandTotals($outflowVolumes)
    {
        $quarterlyShortTripOutGrandTotals = [
            'Q1' => 0,
            'Q2' => 0,
            'Q3' => 0,
            'Q4' => 0,
        ];

        foreach (range(1, 4) as $quarter) {
            $quarterlyShortTripOutGrandTotals['Q' . $quarter] = $outflowVolumes->sum(function ($commodity) use ($quarter) {
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;
                return ($commodity['monthly_short_trip_outflow_volumes'][$startMonth] ?? 0) +
                    ($commodity['monthly_short_trip_outflow_volumes'][$startMonth + 1] ?? 0) +
                    ($commodity['monthly_short_trip_outflow_volumes'][$endMonth] ?? 0);
            });
        }

        return $quarterlyShortTripOutGrandTotals;
    }
}