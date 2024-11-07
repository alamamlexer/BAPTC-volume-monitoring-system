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
use App\Http\Controllers\FacilitatorController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\StaffProfileController;  
use App\Http\Controllers\LogController;  


Route::controller(AuthenticationController::class)->group(function(){

    Route::post('/register-save-farmer','register_save_farmer')->name('register_save_farmer');
    Route::post('/register-save-staff','register_save_staff')->name('register_save_staff');
    
    Route::get('login')->name('login');
    Route::post('/login-action','login_action')->name('login_action');
    
    Route::post('logout','logout')->name('logout');
    
});

Route::middleware(['revalidate_backhistory','admin_access'])->group(function(){

    

    Route::resource('admin',AdminDashboardController::class); 
    
    Route::get('/log',[LogController::class,'index'])->name('log.index');
    
    Route::resource('commodity',CommodityController::class); 
    
    Route::resource('facilitator',FacilitatorController::class); 

    Route::resource('special-records',SpecialRecordsController::class); 
    
    Route::resource('record',RecordController::class); 
    
    Route::resource('report',ReportController::class);

    Route::resource('trading-inflow', TradingInflowController::class);
    Route::post('/trading-inflow/submit', [TradingInflowController::class, 'submit'])->name('trading-inflow.submit');
    Route::post('/trading-inflow/import', [TradingInflowController::class, 'import'])->name('trading-inflow.import');
    
    Route::resource('trading-outflow', TradingOutflowController::class);
    Route::post('/trading-outflow/submit', [TradingOutflowController::class, 'submit'])->name('trading-outflow.submit');
    Route::post('/trading-outflow/import', [TradingInflowController::class, 'import'])->name('trading-outflow.import');

    Route::resource('short-trip-inflow-and-outflow', ShortTripInflowAndOutflowController::class);
    Route::post('/short-trip-inflow-and-outflow/submit', [ShortTripInflowAndOutflowController::class, 'submit'])->name('short-trip-inflow-and-outflow.submit');
    Route::post('/short-trip-inflow-and-outflow/import', [ShortTripInflowAndOutflowController::class, 'import'])->name('short-trip-inflow-and-outflow.import');

    Route::resource('user-management', UserManagementController::class);
    Route::post('user-management/{id}/activate', [UserManagementController::class, 'activate'])->name('user-management.activate');
    Route::post('user-management/{id}/deactivate', [UserManagementController::class, 'deactivate'])->name('user-management.deactivate');
});

Route::middleware(['revalidate_backhistory','user_access'])->group(function(){

    Route::get('/staff-dashboard',[AdminDashboardController::class,'index'])->name('staff-dashboard');
    Route::get('/staff/profile/{id}', [StaffProfileController::class, 'show'])->name('staff.profile');
    Route::put('/staff/profile/{id}', [StaffProfileController::class, 'update'])->name('staff.profile.update');
    

    // Trading Inflow Routes For Staff
    Route::get('/staff-trading-inflow',[TradingInflowController::class,'index'])->name('staff-trading-inflow.index');
    Route::get('/staff-trading-inflow/create', [TradingInflowController::class, 'create'])->name('staff-trading-inflow.create');
    Route::post('/staff-trading-inflow/submit', [TradingInflowController::class, 'submit'])->name('staff-trading-inflow.submit');
    Route::post('/staff-trading-inflow', [TradingInflowController::class, 'store'])->name('staff-trading-inflow.store');
    Route::get('/staff-trading-inflow/{trading_inflow}/edit', [TradingInflowController::class, 'edit'])->name('staff-trading-inflow.edit');
    Route::put('/staff-trading-inflow/{trading_inflow}', [TradingInflowController::class, 'update'])->name('staff-trading-inflow.update');
    Route::delete('/staff-trading-inflow/{trading_inflow}', [TradingInflowController::class, 'destroy'])->name('staff-trading-inflow.destroy');
    Route::post('/staff-trading-inflow/import', [TradingInflowController::class, 'import'])->name('staff-trading-inflow.import');


    // Trading Outflow Routes For Staff
    Route::get('/staff-trading-outflow',[TradingOutflowController::class,'index'])->name('staff-trading-outflow.index');
    Route::get('/staff-trading-outflow/create',[TradingOutflowController::class,'create'])->name('staff-trading-outflow.create');
    Route::post('/staff-trading-outflow',[TradingOutflowController::class,'store'])->name('staff-trading-outflow.store');
    Route::post('/staff-trading-outflow/submit', [TradingOutflowController::class, 'submit'])->name('staff-trading-outflow.submit');
    Route::get('/staff-trading-outflow/{trading_outflow}/edit', [TradingOutflowController::class, 'edit'])->name('staff-trading-outflow.edit');
    Route::put('/staff-trading-outflow/{trading_outflow}', [TradingOutflowController::class, 'update'])->name('staff-trading-outflow.update');
    Route::delete('/staff-trading-outflow/{trading_outflow}', [TradingOutflowController::class, 'destroy'])->name('staff-trading-outflow.destroy');
    Route::post('/staff-trading-outflow/import', [TradingInflowController::class, 'import'])->name('staff-trading-outflow.import');

    // Short Trip Inflow and Short Trip Outflow Routes For Staff
    Route::get('/staff-short-trip-inflow-and-outflow',[ShortTripInflowAndOutflowController::class,'index'])->name('staff-short-trip-inflow-and-outflow.index');
    Route::get('/staff-short-trip-inflow-and-outflow/create',[ShortTripInflowAndOutflowController::class,'create'])->name('staff-short-trip-inflow-and-outflow.create');
    Route::post('/staff-short-trip-inflow-and-outflow',[ShortTripInflowAndOutflowController::class,'store'])->name('staff-short-trip-inflow-and-outflow.store');
    Route::post('/staff-short-trip-inflow-and-outflow/submit', [ShortTripInflowAndOutflowController::class, 'submit'])->name('staff-short-trip-inflow-and-outflow.submit');
    Route::get('/staff-short-trip-inflow-and-outflow/{short_trip_inflow_and_outflow}/edit',[ShortTripInflowAndOutflowController::class,'edit'])->name('staff-short-trip-inflow-and-outflow.edit');
    Route::put('/staff-short-trip-inflow-and-outflow/{short_trip_inflow_and_outflow}',[ShortTripInflowAndOutflowController::class,'update'])->name('staff-short-trip-inflow-and-outflow.update');
    Route::delete('/staff-short-trip-inflow-and-outflow/{short_trip_inflow_and_outflow}',[ShortTripInflowAndOutflowController::class,'destroy'])->name('staff-short-trip-inflow-and-outflow.destroy');
    
    //special records
    Route::get('/staff-special-record',[SpecialRecordsController::class,'index'])->name('staff-special-record.index');
    Route::get('/staff-special-record/create',[SpecialRecordsController::class,'create'])->name('staff-special-record.create');
    Route::post('/staff-special-record',[SpecialRecordsController::class,'store'])->name('staff-special-record.store');
    Route::post('/staff-special-record/submit', [SpecialRecordsController::class, 'submit'])->name('staff-special-record.submit');
    Route::get('/staff-special-record/{special_record}/edit', [SpecialRecordsController::class, 'edit'])->name('staff-special-record.edit');
    Route::put('/staff-special-record/{special_record}', [SpecialRecordsController::class, 'update'])->name('staff-special-record.update');
    
    //Report routes
    Route::get('/staff-report',[ReportController::class,'index'])->name('staff-report.index');
    
    Route::get('/staff-record',[RecordController::class,'index'])->name('staff-record.index');
    Route::post('/staff-record',[RecordController::class,'store'])->name('staff-record.store');
    
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'showLoginForm'])->name('login');


