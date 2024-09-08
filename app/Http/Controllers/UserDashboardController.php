<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function user_dashboard(){
        return view('user-pages.user-dashboard');  
    }
}
