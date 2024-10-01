<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ShopController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['prefix' => 'sellers'], function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', function () {return redirect()->route('products.index');});
        Route::resource('products', ProductController::class);
        Route::resource('invoices', InvoiceController::class);
    });
});

Route::group(['prefix' => 'shops'], function () {
    Route::get('/', [ShopController::class, 'index'])->name('shops.index');
    Route::middleware('auth')->group(function () {
        Route::get('invoices', [InvoiceController::class, 'index'])->name('shops.invoices');
    });
});

Route::group(['prefix' => 'api/v1/'], function () {
    Route::get('products', [ShopController::class, 'getProduct'])->name('api.v1.products');
    Route::middleware('auth')->group(function () {
        Route::get('payment_method', [ShopController::class, 'getPaymentMethod'])->name('api.v1.get_payment_method');
        Route::post('products/buy', [ShopController::class, 'buyProduct'])->name('api.v1.products.buy');
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
