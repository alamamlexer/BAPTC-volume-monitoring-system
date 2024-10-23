<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get the municipality from the request; you may also want to validate this input
    $municipality = $request->input('municipality');

    $trading_inflows = Transaction::where('transaction_type', 'trading inflow')
        ->whereDate('date', Carbon::today())
        ->when($municipality, function ($query, $municipality) {
            return $query->where('municipality', $municipality);
        })
        ->with(['vehicle_type'])
        ->get();

    // Group inflows by municipality and count the number of vehicles per municipality
    $grouped_inflows = $trading_inflows->groupBy('municipality')->map(function ($group) {
        return [
            'municipality' => $group->first()->municipality,
            'vehicle_count' => $group->count(),
        ];
    });

    $trading_outflows = Transaction::where('transaction_type', 'trading outflow')
        ->whereDate('date', Carbon::today())
        ->when($municipality, function ($query, $municipality) {
            return $query->where('municipality', $municipality);
        })
        ->with(['vehicle_type'])
        ->get();

    // Group outflows by municipality and count the number of vehicles per municipality
    $grouped_outflows = $trading_outflows->groupBy('municipality')->map(function ($group) {
        return [
            'municipality' => $group->first()->municipality,
            'vehicle_count' => $group->count(),
        ];
    });

    // Pass the grouped data to the view
    return view('index', [
        'grouped_inflows' => $grouped_inflows,
        'grouped_outflows' => $grouped_outflows,
    ]);
    }

    public function showLoginForm()
    {
        return view('login');
    }
}   