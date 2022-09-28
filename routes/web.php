<?php

use Illuminate\Support\Facades\Route;

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



Route::middleware('auth')->group(function () {
    Route::get('/',function (){ return view('master'); });
    Route::resource('firms', \App\Http\Controllers\FirmController::class);
    Route::resource('worker', \App\Http\Controllers\WorkerController::class);
    Route::resource('work',\App\Http\Controllers\WorkController::class);
    Route::resource('firm_incomes',\App\Http\Controllers\FirmIncomeController::class);
    Route::resource('type',\App\Http\Controllers\TypeController::class);
    Route::resource('worker_gaves',\App\Http\Controllers\WorkerGaveController::class);
    Route::resource('jobs',\App\Http\Controllers\JobsController::class);

});


require __DIR__.'/auth.php';
