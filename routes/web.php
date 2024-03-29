<?php

use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\BorrowerTransectionHistoreyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SystemSettingController;
use Illuminate\Support\Facades\Artisan;
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


//👉 MENU: cache Clear
Route::get('rc', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    notify()->success("Cache Clear Successfully");
    return back();
})->name('rc');

//👉 MENU: authorization
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('/product-category', ProductCategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/purchase', PurchaseController::class);
    Route::get('/purchase-invoice-delete/{id}', [PurchaseController::class, 'delete_purchase_item'])->name('delete_purchase_item');
    Route::get('/purchase-print-invoice/{id}', [PurchaseController::class, 'print_invoice'])->name('purchase_print_invoice');
    
    Route::resource('/sale', SaleController::class);
    Route::get('/sale-invoice-delete/{id}', [SaleController::class, 'delete_sale_item'])->name('delete_sale_item');
    Route::get('/sale-print-invoice/{id}', [SaleController::class, 'print_invoice'])->name('sale_print_invoice');

    // money management 
    Route::resource('/borrowers', BorrowerController::class);
    Route::resource('/borrower-transection', BorrowerTransectionHistoreyController::class);
    Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SystemSettingController::class, 'store'])->name('settings.store');
    Route::get('/profile/{id}', [ManagerController::class, 'edit'])->name('manager.edit');
    Route::put('/change-password/{id}', [ManagerController::class, 'managerChangePassword'])->name('manager.change-password');
    Route::put('/change-profile/{id}', [ManagerController::class, 'update_profile'])->name('manager.change-profile');
});
