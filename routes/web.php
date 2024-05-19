<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

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
    return view('login');
});

Route::get('/demo', [LeadController::class, 'demo'])->name('demo')->middleware('guest');
Route::get('/customerForm', [LeadController::class, 'customerForm'])->name('customerForm')->middleware('guest');

// Route::post('login', [Controller::class, 'authMe'])->name('login')->middleware('guest');


Route::get('dashboard', function () {
    return view('sales.dashboard');
})->name('dashboard');

