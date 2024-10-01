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
        Route::resource('products', ProductController::class);
        Route::resource('invoices', InvoiceController::class);
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
