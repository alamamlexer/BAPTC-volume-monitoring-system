<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\TradingInflowController;
use App\Http\Controllers\TradingOutflowController;
use App\Http\Controllers\ShortTripInflowAndOutflowController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpecialRecordsController;


Route::controller(AuthenticationController::class)->group(function(){

    Route::get('/register','register')->name('register');
    Route::post('/register-save-farmer','register_save_farmer')->name('register_save_farmer');
    Route::post('/register-save-staff','register_save_staff')->name('register_save_staff');
    
    Route::get('login')->name('login');
    Route::post('/login-action','login_action')->name('login_action');
    
    Route::post('logout','logout')->name('logout');
    
});

Route::middleware(['revalidate_backhistory','admin_access'])->group(function(){

    Route::resource('admin',AdminDashboardController::class); 

    Route::resource('special-records',SpecialRecordsController::class); 
    
    Route::resource('record',RecordController::class); 
    
    Route::resource('report',ReportController::class);

    Route::resource('trading-inflow', TradingInflowController::class);
    Route::post('/trading-inflow/submit', [TradingInflowController::class, 'submit'])->name('trading-inflow.submit');
    
    Route::resource('trading-outflow', TradingOutflowController::class);
    Route::post('/trading-outflow/submit', [TradingOutflowController::class, 'submit'])->name('trading-outflow.submit');
    

    Route::resource('short-trip-inflow-and-outflow', ShortTripInflowAndOutflowController::class);
    Route::post('/short-trip-inflow-and-outflow/submit', [ShortTripInflowAndOutflowController::class, 'submit'])->name('short-trip-inflow-and-outflow.submit');
    
    Route::resource('user-management', UserManagementController::class);
});

Route::middleware(['revalidate_backhistory','user_access'])->group(function(){

    Route::get('/staff-dashboard',[AdminDashboardController::class,'index'])->name('staff-dashboard');
    

    // Trading Inflow Routes For Staff
    Route::get('/staff-trading-inflow',[TradingInflowController::class,'index'])->name('staff-trading-inflow.index');
    Route::get('/staff-trading-inflow/create', [TradingInflowController::class, 'create'])->name('staff-trading-inflow.create');
    Route::post('/staff-trading-inflow/submit', [TradingInflowController::class, 'submit'])->name('staff-trading-inflow.submit');
    Route::post('/staff-trading-inflow', [TradingInflowController::class, 'store'])->name('staff-trading-inflow.store');
    Route::get('/staff-trading-inflow/{trading_inflow}/edit', [TradingInflowController::class, 'edit'])->name('staff-trading-inflow.edit');
    Route::put('/staff-trading-inflow/{trading_inflow}', [TradingInflowController::class, 'update'])->name('staff-trading-inflow.update');
    Route::delete('/staff-trading-inflow/{trading_inflow}', [TradingInflowController::class, 'destroy'])->name('staff-trading-inflow.destroy');


    // Trading Outflow Routes For Staff
    Route::get('/staff-trading-outflow',[TradingOutflowController::class,'index'])->name('staff-trading-outflow.index');
    Route::get('/staff-trading-outflow/create',[TradingOutflowController::class,'create'])->name('staff-trading-outflow.create');
    Route::post('/staff-trading-outflow',[TradingOutflowController::class,'store'])->name('staff-trading-outflow.store');
    Route::post('/staff-trading-outflow/submit', [TradingOutflowController::class, 'submit'])->name('staff-trading-outflow.submit');
    Route::get('/staff-trading-outflow/{trading_outflow}/edit', [TradingOutflowController::class, 'edit'])->name('staff-trading-outflow.edit');
    Route::put('/staff-trading-outflow/{trading_outflow}', [TradingOutflowController::class, 'update'])->name('staff-trading-outflow.update');
    Route::delete('/staff-trading-outflow/{trading_outflow}', [TradingOutflowController::class, 'destroy'])->name('staff-trading-outflow.destroy');

    // Short Trip Inflow and Short Trip Outflow Routes For Staff
    Route::get('/staff-short-trip-inflow-and-outflow',[ShortTripInflowAndOutflowController::class,'index'])->name('staff-short-trip-inflow-and-outflow.index');
    Route::get('/staff-short-trip-inflow-and-outflow/create',[ShortTripInflowAndOutflowController::class,'create'])->name('staff-short-trip-inflow-and-outflow.create');
    Route::post('/staff-short-trip-inflow-and-outflow',[ShortTripInflowAndOutflowController::class,'store'])->name('staff-short-trip-inflow-and-outflow.store');
    Route::post('/staff-short-trip-inflow-and-outflow/submit', [ShortTripInflowAndOutflowController::class, 'submit'])->name('staff-short-trip-inflow-and-outflow.submit');
    Route::get('/staff-short-trip-inflow-and-outflow/{short_trip_inflow_and_outflow}/edit',[ShortTripInflowAndOutflowController::class,'edit'])->name('staff-short-trip-inflow-and-outflow.edit');
    Route::put('/staff-short-trip-inflow-and-outflow/{short_trip_inflow_and_outflow}',[ShortTripInflowAndOutflowController::class,'update'])->name('staff-short-trip-inflow-and-outflow.update');
    Route::delete('/staff-short-trip-inflow-and-outflow/{short_trip_inflow_and_outflow}',[ShortTripInflowAndOutflowController::class,'destroy'])->name('staff-short-trip-inflow-and-outflow.destroy');
    
    
  
    
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'showLoginForm'])->name('login');


