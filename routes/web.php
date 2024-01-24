<?php

use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Monolog\Handler\BrowserConsoleHandler;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('master');
});
Route::resource('/product-category', ProductCategoryController::class);
Route::resource('/products', ProductController::class);
Route::resource('/customers', CustomerController::class);
Route::resource('/suppliers', SupplierController::class);
Route::resource('/purchase', PurchaseController::class);
Route::resource('/sale', SaleController::class);




// money management 
Route::resource('/borrowers', BorrowerController::class);
