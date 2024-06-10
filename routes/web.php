<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DealController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\QuotationController;
use Faker\Guesser\Name;

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


// Dashboard Route 
Route::get('home', [Controller::class, 'salesStage'])->name('home')->middleware('auth');
Route::get('dashboard', [Controller::class, 'salesStage'])->name('dashboard')->middleware('auth');

Route::get('/demo', [LeadController::class, 'demo'])->name('demo')->middleware('guest');
Route::get('/customerForm', [LeadController::class, 'customerForm'])->name('customerForm')->middleware('auth');
Route::post('/customerForm', [LeadController::class, 'storeCustomer'])->name('customerForm')->middleware('auth');
Route::get('/workLoadCheck', [LeadController::class, 'workLoadCheck'])->name('workLoadCheck')->middleware('auth');
Route::post('assignLead', [LeadController::class, 'assignLeadToSales'])->name('assignLead')->middleware('auth');

Route::get('newLeadForm', [LeadController::class, 'leadForm'])->name('newLeadForm')->middleware('auth');
Route::post('newLeadForm', [LeadController::class, 'storeLead'])->name('newLeadForm')->middleware('auth');
Route::get('getSingleClientInfo/{clientId}', [LeadController::class, 'getSingleClientInfo'])->name('getSingleClientInfo')->middleware('auth');

Route::get('dealPage/{leadId}', [LeadController::class, 'dealForm'])->name('dealPage')->middleware('auth');
Route::post('requirement', [DealController::class, 'storeRequirement'])->name('requirement')->middleware('auth');
Route::post('deleteDealRequirement', [DealController::class, 'deleteDealRequirement'])->name('deleteDealRequirement')->middleware('auth');
Route::post('getSelectionPumpInfo', [DealController::class, 'getFilterPumpInfo'])->name('getSelectionPumpInfo')->middleware('auth');
Route::post('storeSelectedPump', [DealController::class, 'storePumpChoice'])->name('storeSelectedPump')->middleware('auth');
Route::post('dealFormSubmission', [DealController::class, 'submitTheDeal'])->name('dealFormSubmission')->middleware('auth');

Route::get('quotationCheck/{leadId}', [QuotationController::class, 'viewQuotation'])->name('quotationCheck')->middleware('auth');
Route::post('preQuotationApprove', [QuotationController::class, 'preQuotationApprove'])->name('preQuotationApprove')->middleware('auth');
Route::post('topQuotationApprove', [QuotationController::class, 'topQuotationApprove'])->name('topQuotationApprove')->middleware('auth');

Route::get('transaction/{leadId}', [BookingController::class, 'transactionForm'])->name('transaction')->middleware('auth');

// Admin Routes 
Route::get('users', [AdminController::class, 'index'])->name('users')->middleware('auth');
Route::post('users', [AdminController::class, 'storeUser'])->name('users')->middleware('auth');
Route::get('userInfo/{userId}', [AdminController::class, 'userInformation'])->name('userInfo')->middleware('auth');
Route::get('userEdit/{userId}', [AdminController::class, 'userInformationEdit'])->name('userEdit')->middleware('auth');
Route::post('editUsers', [AdminController::class, 'updateUserInformation'])->name('editUsers')->middleware('auth');
Route::get('inactiveUser/{userId}', [AdminController::class, 'userMakeInactive'])->name('inactiveUser')->middleware('auth');
Route::get('activeUser/{userId}', [AdminController::class, 'userMakeActive'])->name('activeUser')->middleware('auth');

Route::get('permissions', [AdminController::class, 'permissionList'])->name('permissions')->middleware('auth');
Route::post('permissions', [AdminController::class, 'storePermission'])->name('permissions')->middleware('auth');
Route::get('userPermission/{userId}', [AdminController::class, 'userPermissions'])->name('userPermission')->middleware('auth');
Route::post('userPermissions', [AdminController::class, 'storeUserPermission'])->name('userPermissions')->middleware('auth');
