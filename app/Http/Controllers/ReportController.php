<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Existing transaction calculations...
        $transactions = [
            'AM_TRADING' => [
                'inflow' => Transaction::where('time', 'AM')->where('transaction_type', 'trading inflow')->where('transaction_status', 'regular')->sum('volume'),
            ],
            'PM_TRADING' => [
                'inflow' => Transaction::where('time', 'PM')->where('transaction_type', 'trading inflow')->where('transaction_status', 'regular')->sum('volume'),
            ],
            'SHORT_TRIP_IN' => [
                'inflow' => Transaction::where('transaction_type', 'short trip inflow')->where('transaction_status', 'regular')->sum('volume'),
            ],
            'SHORT_TRIP_OUT' => [
                'outflow' => Transaction::where('transaction_type', 'short trip outflow')->where('transaction_status', 'regular')->sum('volume'),
            ],
            'AM_TRUCKINGS' => [
                'outflow' => Transaction::where('time', 'AM')->where('transaction_type', 'trading outflow')->where('transaction_status', 'regular')->sum('volume'),
            ],
            'PM_TRUCKINGS' => [
                'outflow' => Transaction::where('time', 'PM')->where('transaction_type', 'trading outflow')->where('transaction_status', 'regular')->sum('volume'),
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

        // Passing data to the view
        return view('admin-pages.report', compact('transactions', 'R2_peakDayDate', 'R2_peakDay', 'R2_leanDayDate', 'R2_leanDay'));
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
