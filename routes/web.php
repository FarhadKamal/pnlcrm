<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DealController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductController;
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
Route::get('newDash', [Controller::class, 'newDash'])->name('newDash')->middleware('auth');
Route::get('/demo', [LeadController::class, 'demo'])->name('demo')->middleware('guest');
Route::get('/customerForm', [LeadController::class, 'customerForm'])->name('customerForm')->middleware('auth');
Route::post('/customerForm', [LeadController::class, 'storeCustomer'])->name('customerForm')->middleware('auth');
Route::get('/workLoadCheck', [LeadController::class, 'workLoadCheck'])->name('workLoadCheck')->middleware('auth');
Route::post('assignLead', [LeadController::class, 'assignLeadToSales'])->name('assignLead')->middleware('auth');

Route::get('newLeadForm', [LeadController::class, 'leadForm'])->name('newLeadForm')->middleware('auth');
Route::post('newLeadForm', [LeadController::class, 'storeLead'])->name('newLeadForm')->middleware('auth');
Route::get('getSingleClientInfo/{clientId}', [LeadController::class, 'getSingleClientInfo'])->name('getSingleClientInfo')->middleware('auth');
Route::get('updateLeadEmail', [LeadController::class, 'updateLeadEmail'])->name('updateLeadEmail')->middleware('auth');

Route::get('dealPage/{leadId}', [LeadController::class, 'dealForm'])->name('dealPage')->middleware('auth');
Route::post('requirement', [DealController::class, 'storeRequirement'])->name('requirement')->middleware('auth');
Route::get('SAPstockDetails/{productId}', [DealController::class, 'SAPstockDetails'])->name('SAPstockDetails')->middleware('auth');
Route::post('deleteDealRequirement', [DealController::class, 'deleteDealRequirement'])->name('deleteDealRequirement')->middleware('auth');
Route::post('getSelectionPumpInfo', [DealController::class, 'getFilterPumpInfo'])->name('getSelectionPumpInfo')->middleware('auth');
Route::post('storeSelectedPump', [DealController::class, 'storePumpChoice'])->name('storeSelectedPump')->middleware('auth');
Route::post('dealFormSubmission', [DealController::class, 'submitTheDeal'])->name('dealFormSubmission')->middleware('auth');

Route::get('quotationCheck/{leadId}', [QuotationController::class, 'viewQuotation'])->name('quotationCheck')->middleware('auth');
Route::post('preQuotationApprove', [QuotationController::class, 'preQuotationApprove'])->name('preQuotationApprove')->middleware('auth');
Route::post('topQuotationApprove', [QuotationController::class, 'topQuotationApprove'])->name('topQuotationApprove')->middleware('auth');
Route::get('quotationReferenceCheck', [QuotationController::class, 'quotationReferenceCheck'])->name('quotationReferenceCheck')->middleware('auth');
Route::post('submitQuotation', [QuotationController::class, 'submitQuotation'])->name('submitQuotation')->middleware('auth');
Route::get('quotationFeedback/{leadId}', [QuotationController::class, 'quotationFeedbackForm'])->name('quotationFeedback')->middleware('auth');
Route::post('quotationAccept', [QuotationController::class, 'acceptLeadQuotation'])->name('quotationAccept')->middleware('auth');
Route::post('quotationNotAccept', [QuotationController::class, 'notAcceptLeadQuotation'])->name('quotationNotAccept')->middleware('auth');

Route::post('returnQuotationStage', [QuotationController::class, 'returnToQuotationStage'])->name('returnQuotationStage')->middleware('auth');

Route::get('customerDocCheck/{leadId}', [BookingController::class, 'documentCheckForm'])->name('customerDocCheck')->middleware('auth');
Route::post('customerDocClear', [BookingController::class, 'documentCheckClear'])->name('customerDocClear')->middleware('auth');
Route::post('customerDocReturn', [BookingController::class, 'documentCheckReturn'])->name('customerDocReturn')->middleware('auth');
Route::get('newSapForm/{leadId}', [BookingController::class, 'newSapForm'])->name('newSapForm')->middleware('auth');
Route::post('newSapInsertion', [BookingController::class, 'insertNewSapID'])->name('newSapInsertion')->middleware('auth');
Route::get('creditSetForm/{leadId}', [BookingController::class, 'creditSetForm'])->name('creditSetForm')->middleware('auth');
Route::post('creditSetInsertion', [BookingController::class, 'insertCredit'])->name('creditSetInsertion')->middleware('auth');
Route::post('creditSetHold', [BookingController::class, 'holdCredit'])->name('creditSetHold')->middleware('auth');
Route::post('reSubmitToCredit', [BookingController::class, 'reSubmitCredit'])->name('reSubmitToCredit')->middleware('auth');
Route::get('transaction/{leadId}', [BookingController::class, 'transactionForm'])->name('transaction')->middleware('auth');
Route::post('insertTransaction', [BookingController::class, 'storeTransaction'])->name('insertTransaction')->middleware('auth');
Route::get('verifyTransaction/{leadId}', [BookingController::class, 'verifyTransaction'])->name('verifyTransaction')->middleware('auth');
Route::post('verifiedTransaction', [BookingController::class, 'verifyTheTransaction'])->name('verifiedTransaction')->middleware('auth');
Route::post('accountsClearance', [BookingController::class, 'accountsCleared'])->name('accountsClearance')->middleware('auth');
Route::get('outstandings', [BookingController::class, 'outstandingList'])->name('outstandings')->middleware('auth');
Route::get('outStandingTransaction/{leadId}', [BookingController::class, 'outStandingTransaction'])->name('outStandingTransaction')->middleware('auth');
Route::post('outstandingsClearance', [BookingController::class, 'outstandingsCleared'])->name('outstandingsClearance')->middleware('auth');

Route::get('discountSetForm/{leadId}', [DeliveryController::class, 'discountSetForm'])->name('discountSetForm')->middleware('auth');
Route::post('discountSetInsertion', [DeliveryController::class, 'insertDiscount'])->name('discountSetInsertion')->middleware('auth');
Route::get('invoiceSetForm/{leadId}', [DeliveryController::class, 'invoiceSetForm'])->name('invoiceSetForm')->middleware('auth');
Route::post('checkSAPInvoice', [DeliveryController::class, 'checkSAPInvoice'])->name('checkSAPInvoice')->middleware('auth');
Route::post('invoiceSetInsertion', [DeliveryController::class, 'insertInvoice'])->name('invoiceSetInsertion')->middleware('auth');
Route::get('deliveryPage/{leadId}', [DeliveryController::class, 'deliveryPage'])->name('deliveryPage')->middleware('auth');
Route::get('deliveryReferenceCheck', [DeliveryController::class, 'deliveryReferenceCheck'])->name('deliveryReferenceCheck')->middleware('auth');
Route::post('deliveryInformation', [DeliveryController::class, 'storeDeliveryInformation'])->name('deliveryInformation')->middleware('auth');
Route::post('delivered', [DeliveryController::class, 'storeDelivered'])->name('delivered')->middleware('auth');

Route::get('lost/{leadId}', [Controller::class, 'lostForm'])->name('lost')->middleware('auth');
Route::post('lostEntry', [Controller::class, 'storeLost'])->name('lostEntry')->middleware('auth');

Route::get('detailsLog/{leadId}', [Controller::class, 'salesLog'])->name('detailsLog')->middleware('auth');
Route::post('reDealStage', [LeadController::class, 'reDealing'])->name('reDealStage')->middleware('auth');

Route::get('returnTransaction/{leadId}', [BookingController::class, 'returnTransactionForm'])->name('returnTransaction')->middleware('auth');
Route::post('returnTheTransaction', [BookingController::class, 'returnTheTransactions'])->name('returnTheTransaction')->middleware('auth');

Route::get('myProfile', [Controller::class, 'myProfilePage'])->name('myProfile')->middleware('auth');
Route::get('myProfileEdit', [Controller::class, 'myProfileEdit'])->name('myProfileEdit')->middleware('auth');
Route::post('updateMyProfile', [Controller::class, 'updateMyProfile'])->name('updateMyProfile')->middleware('auth');


Route::get('productEntry', [ProductController::class, 'productForm'])->name('productEntry')->middleware('auth');
Route::post('insertProduct', [ProductController::class, 'storeProduct'])->name('insertProduct')->middleware('auth');

Route::get('tutorialVideo', [Controller::class, 'tutorialVisual'])->name('tutorialVisual')->middleware('auth');

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

Route::get('designations', [AdminController::class, 'designationList'])->name('designations')->middleware('auth');
Route::post('designations', [AdminController::class, 'storeDesignation'])->name('designations')->middleware('auth');
Route::post('editDesignation', [AdminController::class, 'editDesignation'])->name('editDesignation')->middleware('auth');
Route::post('updateDesignation', [AdminController::class, 'updateDesignation'])->name('updateDesignation')->middleware('auth');

Route::get('departments', [AdminController::class, 'departmentList'])->name('departments')->middleware('auth');
Route::post('departments', [AdminController::class, 'storeDepartment'])->name('departments')->middleware('auth');
Route::post('editDepartment', [AdminController::class, 'editDepartment'])->name('editDepartment')->middleware('auth');
Route::post('updateDepartment', [AdminController::class, 'updateDepartment'])->name('updateDepartment')->middleware('auth');

Route::get('locations', [AdminController::class, 'locationList'])->name('locations')->middleware('auth');
Route::post('locations', [AdminController::class, 'storeLocation'])->name('locations')->middleware('auth');
Route::post('editLocation', [AdminController::class, 'editLocation'])->name('editLocation')->middleware('auth');
Route::post('updateLocation', [AdminController::class, 'updateLocation'])->name('updateLocation')->middleware('auth');

Route::get('districts', [AdminController::class, 'districtList'])->name('districts')->middleware('auth');
Route::post('districts', [AdminController::class, 'storeDistrict'])->name('districts')->middleware('auth');
Route::post('editDistrict', [AdminController::class, 'editDistrict'])->name('editDistrict')->middleware('auth');
Route::post('updateDistrict', [AdminController::class, 'updateDistrict'])->name('updateDistrict')->middleware('auth');

Route::get('divisions', [AdminController::class, 'divisionList'])->name('divisions')->middleware('auth');
Route::post('divisions', [AdminController::class, 'storeDivision'])->name('divisions')->middleware('auth');
Route::post('editDivision', [AdminController::class, 'editDivision'])->name('editDivision')->middleware('auth');
Route::post('updateDivision', [AdminController::class, 'updateDivision'])->name('updateDivision')->middleware('auth');

Route::get('leadSources', [AdminController::class, 'leadSourceList'])->name('leadSources')->middleware('auth');
Route::post('leadSources', [AdminController::class, 'storeLeadSource'])->name('leadSources')->middleware('auth');
Route::post('editLeadSource', [AdminController::class, 'editLeadSource'])->name('editLeadSource')->middleware('auth');
Route::post('updateLeadSource', [AdminController::class, 'updateLeadSource'])->name('updateLeadSource')->middleware('auth');