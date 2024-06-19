<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DealController;
use App\Http\Controllers\DeliveryController;
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
Route::get('quotationReferenceCheck', [QuotationController::class, 'quotationReferenceCheck'])->name('quotationReferenceCheck')->middleware('auth');
Route::post('submitQuotation', [QuotationController::class, 'submitQuotation'])->name('submitQuotation')->middleware('auth');
Route::post('quotationAccept', [QuotationController::class, 'acceptLeadQuotation'])->name('quotationAccept')->middleware('auth');
// Route::post('quotationNotAccept', [SalesController::class, 'notAcceptLeadQuotation'])->name('quotationNotAccept')->middleware('auth');

Route::get('newSapForm/{leadId}', [BookingController::class, 'newSapForm'])->name('newSapForm')->middleware('auth');
Route::post('newSapInsertion', [BookingController::class, 'insertNewSapID'])->name('newSapInsertion')->middleware('auth');
Route::get('creditSetForm/{leadId}', [BookingController::class, 'creditSetForm'])->name('creditSetForm')->middleware('auth');
Route::post('creditSetInsertion', [BookingController::class, 'insertCredit'])->name('creditSetInsertion')->middleware('auth');
Route::get('transaction/{leadId}', [BookingController::class, 'transactionForm'])->name('transaction')->middleware('auth');
Route::post('insertTransaction', [BookingController::class, 'storeTransaction'])->name('insertTransaction')->middleware('auth');
Route::get('verifyTransaction/{leadId}', [BookingController::class, 'verifyTransaction'])->name('verifyTransaction')->middleware('auth');
Route::post('verifiedTransaction', [BookingController::class, 'verifyTheTransaction'])->name('verifiedTransaction')->middleware('auth');
Route::post('accountsClearance', [BookingController::class, 'accountsCleared'])->name('accountsClearance')->middleware('auth');

Route::get('discountSetForm/{leadId}', [DeliveryController::class, 'discountSetForm'])->name('discountSetForm')->middleware('auth');
Route::post('discountSetInsertion', [DeliveryController::class, 'insertDiscount'])->name('discountSetInsertion')->middleware('auth');
Route::get('invoiceSetForm/{leadId}', [DeliveryController::class, 'invoiceSetForm'])->name('invoiceSetForm')->middleware('auth');
Route::post('checkSAPInvoice', [DeliveryController::class, 'checkSAPInvoice'])->name('checkSAPInvoice')->middleware('auth');
Route::post('invoiceSetInsertion', [DeliveryController::class, 'insertInvoice'])->name('invoiceSetInsertion')->middleware('auth');
Route::get('deliveryPage/{leadId}', [DeliveryController::class, 'deliveryPage'])->name('deliveryPage')->middleware('auth');
Route::post('deliveryInformation', [DeliveryController::class, 'storeDeliveryInformation'])->name('deliveryInformation')->middleware('auth');
Route::post('delivered', [DeliveryController::class, 'storeDelivered'])->name('delivered')->middleware('auth');

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
