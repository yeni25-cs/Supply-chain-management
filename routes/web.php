<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RiskController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/risk', [RiskController::class,'index'])->name('risk.index');

Route::resource('suppliers', SupplierController::class);
Route::resource('products', ProductController::class);