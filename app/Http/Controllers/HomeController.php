<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class HomeController extends Controller
{
    public function index()
    {
        $trading_inflows = Transaction::where('transaction_type', 'trading inflow')
    ->with(['staff', 'commodity', 'vehicle_type'])
    ->get();

    // Group inflows by address and count the number of vehicles per address
    $grouped_inflows = $trading_inflows->groupBy('address')->map(function ($group) {
        return [
            'address' => $group->first()->address,
            'vehicle_count' => $group->count(),
        ];
    });

    $trading_outflows = Transaction::where('transaction_type', 'trading outflow')
    ->with(['staff', 'commodity', 'vehicle_type'])
    ->get();

    // Group outnflows by address and count the number of vehicles per address
    $grouped_outflows = $trading_outflows->groupBy('address')->map(function ($group) {
        return [
            'address' => $group->first()->address,
            'vehicle_count' => $group->count(),
        ];
    });

        // Pass the grouped data to the view
        return view('index', [
        'grouped_inflows' => $grouped_inflows,
        'grouped_outflows' => $grouped_outflows,]);
    }

    public function showLoginForm()
    {
        return view('login');
    }
}
