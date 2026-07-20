<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CountryComparisonController;
use App\Http\Controllers\AdminController;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');
Route::get('/simulation', [SimulationController::class, 'index'])
    ->name('simulation');

Route::resource('suppliers', SupplierController::class);
Route::get('/ports', [PortController::class, 'index'])
    ->name('ports.index');
Route::view('/country-comparison', 'country-comparison')
    ->name('country.comparison');
Route::view('/favorites', 'favorites')
    ->name('favorites');
Route::post(
    '/ports/update-weather',
    [PortController::class,'updateWeather']
)->name('ports.updateWeather');
Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');
Route::get(
    '/comparison',
    [CountryComparisonController::class,'index']
)->name('comparison.index');
Route::post(
'/favorite/add',
[DashboardController::class,'addFavorite']
)->name('favorite.add');

Route::post(
'/favorite/remove',
[DashboardController::class,'removeFavorite']
)->name('favorite.remove');
Route::get(
    '/admin-dashboard',
    [AdminController::class, 'index']
)->name('admin.dashboard');
Route::get(
    '/admin/users',
    [AdminController::class, 'users']
)->name('admin.users');