<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\TradingInflowController;
use App\Http\Controllers\TradingOutflowController;
use App\Http\Controllers\ShortTripInflowAndOutflowController;

Route::controller(AuthenticationController::class)->group(function(){

    Route::get('/register','register')->name('register');
    Route::post('/register-save','register_save')->name('register_save');
    
    Route::get('/','login')->name('login');
    Route::post('/login-action','login_action')->name('login_action');
    
    Route::post('logout','logout')->name('logout');
    
});

Route::middleware(['revalidate_backhistory','admin_access'])->group(function(){

    Route::get('/admin-dashboard',[AdminDashboardController::class,'admin_dashboard'])->name('admin_dashboard');

    Route::resource('trading-inflow', TradingInflowController::class);

    Route::resource('trading-outflow', TradingOutflowController::class);
    
    Route::resource('short-trip-inflow-and-outflow', ShortTripInflowAndOutflowController::class);
});

Route::middleware(['revalidate_backhistory','user_access'])->group(function(){

    Route::get('/user-dashboard',[UserDashboardController::class,'user_dashboard'])->name('user-dashboard');
    
});


