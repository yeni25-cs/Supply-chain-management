<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\SimulationController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/risk', [RiskController::class,'index'])->name('risk.index');
Route::get('/simulation', [SimulationController::class, 'index'])
    ->name('simulation');

Route::resource('suppliers', SupplierController::class);
Route::resource('products', ProductController::class);