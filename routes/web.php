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
Route::get('dashboard', function () {
    return view('sales.dashboard');
})->name('dashboard')->middleware('auth');

Route::get('/demo', [LeadController::class, 'demo'])->name('demo')->middleware('guest');
Route::get('/customerForm', [LeadController::class, 'customerForm'])->name('customerForm')->middleware('auth');
Route::post('/customerForm', [LeadController::class, 'storeCustomer'])->name('customerForm')->middleware('auth');
Route::get('newLeadForm', [LeadController::class, 'leadForm'])->name('newLeadForm')->middleware('auth');
Route::post('newLeadForm', [LeadController::class, 'storeLead'])->name('newLeadForm')->middleware('auth');
Route::get('getSingleClientInfo/{clientId}', [LeadController::class, 'getSingleClientInfo'])->name('getSingleClientInfo')->middleware('auth');
Route::get('dealPage', [LeadController::class, 'dealForm'])->name('dealPage')->middleware('auth');
// Route::get('dealPage/{leadId}', [SalesController::class, 'dealPage'])->name('dealPage')->middleware('auth');




// Admin Routes 
Route::get('users', [AdminController::class, 'index'])->name('users')->middleware('auth');
Route::post('users', [AdminController::class, 'storeUser'])->name('users')->middleware('auth');
Route::get('userInfo/{userId}', [AdminController::class, 'userInformation'])->name('userInfo')->middleware('auth');
Route::get('userEdit/{userId}', [AdminController::class, 'userInformationEdit'])->name('userEdit')->middleware('auth');
Route::post('editUsers', [AdminController::class, 'updateUserInformation'])->name('editUsers')->middleware('auth');
Route::get('inactiveUser/{userId}', [AdminController::class, 'userMakeInactive'])->name('inactiveUser')->middleware('auth');
Route::get('activeUser/{userId}', [AdminController::class, 'userMakeActive'])->name('activeUser')->middleware('auth');
// Route::get('userPermission/{userId}', [AdminController::class, 'userPermissions'])->name('userPermission')->middleware('auth');
// Route::post('userPermissions', [AdminController::class, 'storeUserPermission'])->name('userPermissions')->middleware('auth');


