<?php

use App\Http\Controllers\AdminController;
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
})->name('/');
Route::get('login', [Controller::class, 'logoutMe'])->name('login')->middleware('guest');
Route::post('login', [Controller::class, 'authMe'])->name('login')->middleware('guest');
Route::get('logout', [Controller::class, 'logoutMe'])->name('logout')->middleware('auth');

Route::get('home', function () {
    return view('sales.dashboard');
})->name('home')->middleware('auth');

Route::get('/demo', [LeadController::class, 'demo'])->name('demo')->middleware('guest');
Route::get('/customerForm', [LeadController::class, 'customerForm'])->name('customerForm')->middleware('auth');
Route::post('/customerForm', [LeadController::class, 'storeCustomer'])->name('customerForm')->middleware('auth');

// Route::post('login', [Controller::class, 'authMe'])->name('login')->middleware('guest');


Route::get('dashboard', function () {
    return view('sales.dashboard');
})->name('dashboard')->middleware('auth');


// Admin Routes 
Route::get('users', [AdminController::class, 'index'])->name('users')->middleware('guest');
Route::post('users', [AdminController::class, 'storeUser'])->name('users')->middleware('guest');

