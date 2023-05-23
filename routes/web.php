<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes([
    'login' => false,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::get('/sample',[App\Http\Controllers\SampleController::class,'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//ADDRESS
//Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
//Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
//Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);




/*     ADMINSITRATOR          */

Route::middleware(['auth'])->group(function() {

    Route::resource('/dashboard', App\Http\Controllers\Administrator\DashboardController::class);
    Route::get('/load-today-sales', [App\Http\Controllers\Administrator\DashboardController::class, 'loadTodaySales']);


    
    Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
    Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
    
    
    Route::resource('/items', App\Http\Controllers\Administrator\ItemController::class);
    Route::get('/get-items', [App\Http\Controllers\Administrator\ItemController::class, 'getItems']);
    Route::get('/get-pos-items', [App\Http\Controllers\Administrator\ItemController::class, 'getPosItems']);
    
    Route::resource('/stock-in', App\Http\Controllers\Administrator\StockInController::class);
    Route::get('/get-stock-ins', [App\Http\Controllers\Administrator\StockInController::class, 'getStockIns']);

    Route::resource('/inventories', App\Http\Controllers\Administrator\InventoryController::class);
    Route::get('/get-inventories', [App\Http\Controllers\Administrator\InventoryController::class, 'getData']);

    Route::resource('/inventory-adjustment', App\Http\Controllers\Administrator\InventoryAdjustmentController::class);
    Route::get('/get-inventory-adjustments', [App\Http\Controllers\Administrator\InventoryAdjustmentController::class, 'getData']);


    Route::resource('/pos', App\Http\Controllers\Administrator\PointOfSaleController::class);
    Route::get('/get-pos', [App\Http\Controllers\Administrator\PointOfSaleController::class, 'getData']);

    Route::resource('/sales', App\Http\Controllers\Administrator\SalesController::class);
    Route::get('/get-sales', [App\Http\Controllers\Administrator\SalesController::class, 'getData']);
    
});




/*     ADMINSITRATOR          */

Route::get('/session', function(){
    return Session::all();
});

Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();
});


use Illuminate\Http\Response;
Route::get('/test', function(){
   
    $minutes = 1;

    $response = new Response('Hello, world!');
    $response->cookie('cookie_name', 'cookie_value', $minutes);

    return $response;
});