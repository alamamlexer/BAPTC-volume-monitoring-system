<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\TradingInflowController;
use App\Http\Controllers\TradingOutflowController;
use App\Http\Controllers\ShortTripInflowAndOutflowController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\HomeController;


Route::controller(AuthenticationController::class)->group(function(){

    Route::get('/register','register')->name('register');
    Route::post('/register-save-farmer','register_save_farmer')->name('register_save_farmer');
    Route::post('/register-save-staff','register_save_staff')->name('register_save_staff');
    
    Route::get('login')->name('login');
    Route::post('/login-action','login_action')->name('login_action');
    
    Route::post('logout','logout')->name('logout');
    
});

Route::middleware(['revalidate_backhistory','admin_access'])->group(function(){

    Route::get('/admin-dashboard',[AdminDashboardController::class,'admin_dashboard'])->name('admin_dashboard');

    Route::resource('trading-inflow', TradingInflowController::class);
    Route::post('/trading-inflow/submit', [TradingInflowController::class, 'submit'])->name('trading-inflow.submit');
    
    Route::resource('trading-outflow', TradingOutflowController::class);
    
    Route::resource('short-trip-inflow-and-outflow', ShortTripInflowAndOutflowController::class);
    
    Route::resource('user-management', UserManagementController::class);
});

Route::middleware(['revalidate_backhistory','user_access'])->group(function(){

    Route::get('/user-dashboard',[UserDashboardController::class,'user_dashboard'])->name('user-dashboard');
    
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'showLoginForm'])->name('login');


