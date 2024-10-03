<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\PumpChoice;
use App\Models\Quotation;
use App\Models\SalesLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    public function documentCheckForm($leadId)
    {
        $data['leadInfo'] = Lead::find($leadId);
        return view('sales.documentCheckForm', $data);
    }

    public function documentCheckClear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerId' => 'required|numeric',
            'leadId' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadInfo = Lead::find($request->leadId);
            $leadInfo->current_stage = 'BOOKING';
            $leadInfo->current_subStage = 'SAPIDSET';
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();

            $SAPUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapIDCreation"');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/newSapForm/' . $request->leadId;
            if ($SAPUsersEmail) {
                foreach ($SAPUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP ID SET');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a new lead ' . $customerName . ' is waiting for new SAP ID generation.<br><a href="' . $leadURL . '">CLICK HERE</a> to complete SAP ID creation process.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            }

            $remark = $request->docCheckRemark;
            if ($remark) {
                $remark = 'Remark: ' . $remark;
            } else {
                $remark = '';
            }

            $log_data = array(
                'lead_id' => $request->leadId,
                'log_stage' => 'BOOKING',
                'log_task' => 'New customer document checked. ' . $remark,
                'log_by' => Auth()->user()->id,
                'log_next' => 'New SAP ID Set'
            );
            SalesLog::create($log_data);
            return redirect()->route('dashboard');
        }
    }

    public function documentCheckReturn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'docReturnRemark' => 'required'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $returnRemark = $request->docReturnRemark;
            $leadInfo = Lead::find($request->leadId);
            $leadInfo->current_stage = 'QUOTATION';
            $leadInfo->current_subStage = 'FEEDBACK';
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();

            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $request->leadId . '');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/quotationFeedback/' . $request->leadId;
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $returnRemark, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Return Customer Document Check');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is return to quotaion feedback stage from document check process.<br>Return Remarks: ' . $returnRemark . '.<br><a href="' . $leadURL . '">CLICK HERE</a> for resubmit.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $request->leadId,
                'log_stage' => 'BOOKING',
                'log_task' => 'New customer document checked Failed. Remark:' . $returnRemark,
                'log_by' => Auth()->user()->id,
                'log_next' => 'Quotation Feedback'
            );
            SalesLog::create($log_data);
            return redirect()->route('dashboard');
        }
    }

    public function newSapForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        return view('sales.newSapForm', $data);
    }

    public function insertNewSapID(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerId' => 'required|numeric',
            'newSAP' => 'required|unique:customers,sap_id'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {

            $customerInfo = Customer::find($request->customerId);
            $customerInfo->sap_id = $request->newSAP;
            $customerInfo->save();
            $leadInfo = Lead::where(['customer_id' => $customerInfo->id])->get();
            $leadInfo = Lead::find($leadInfo[0]->id);
            $customerName = $leadInfo->clientInfo->customer_name;

            if ($leadInfo->payment_type == 'Credit') {
                $leadInfo->current_subStage = 'CREDITSET';
                $logNext = 'Credit Limit Set';
                $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapCreditSet"');
                $domainName = URL::to('/');
                $leadURL = $domainName . '/creditSetForm/' . $leadInfo->id;
                if ($SAPCreditUsersEmail) {
                    foreach ($SAPCreditUsersEmail as $email) {
                        $assignEmail = $email->user_email;
                        $assignName = $email->user_name;
                        Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                            $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP CREDIT SET');
                            $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is waiting for new SAP Credit SET.<br><a href="' . $leadURL . '">CLICK HERE</a> for SAP credit set.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                        });
                    }
                }
                $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadInfo->id . '');
                foreach ($assignedUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP ID SET');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a new SAP ID set for the lead ' . $customerName . '. Waiting for SAP Credit Set.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            } else {
                $leadInfo->current_subStage = 'TRANSACTION';
                $logNext = 'Cash Transaction';
                $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadInfo->id . '');
                $domainName = URL::to('/');
                $leadURL = $domainName . '/transaction/' . $leadInfo->id;
                foreach ($assignedUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP ID SET');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a new SAP ID set for the lead ' . $customerName . '.<br>Please <a href="' . $leadURL . '">CLICK HERE</a> to insert transaction.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            }
            $leadInfo->save();
            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => 'BOOKING',
                'log_task' => 'New SAP ID: ' . $request->newSAP . ' set',
                'log_by' => Auth()->user()->id,
                'log_next' => $logNext
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function creditSetForm($leadId)
    {
        if (!Helper::permissionCheck(Auth()->user()->id, 'sapCreditSet')) {
            $data['errors'] = ['You are not authorized'];
            return back()->with('errors', $data['errors']);
        }
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $customerId = $data['leadInfo']->customer_id;
        $data['customerStageInfo'] = Lead::orderBy('created_at', 'ASC')->where(['customer_id' => $customerId, 'is_won' => 0, 'is_lost' => 0])->get();
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        $data['transactionInfo'] = Transaction::where(['lead_id' => $leadId])->get();
        return view('sales.creditSetForm', $data);
    }

    public function insertCredit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'creditLimit' => 'required'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadInfo = Lead::find($request->leadId);
            $customerName = $leadInfo->clientInfo->customer_name;
            $lead_id = $request->leadId;
            $leadInfo->accounts_clearance = 1;
            $leadInfo->is_outstanding = 1;
            $leadInfo->creditAmt = $request->creditLimit;
            $leadInfo->current_stage = 'DELIVERY';
            if ($leadInfo->need_discount_approval > 1) {
                $leadInfo->current_subStage = 'DISCOUNTSET';
                $logNext = 'SAP Discount Set';
                $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapDiscountSet"');
                if ($SAPCreditUsersEmail) {
                    foreach ($SAPCreditUsersEmail as $email) {
                        $assignEmail = $email->user_email;
                        $assignName = $email->user_name;
                        Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName) {
                            $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP DISCOUNT SET');
                            $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a lead ' . $customerName . ' is waiting for SAP Discount SET.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                        });
                    }
                }
                $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadInfo->id . '');
                $domainName = URL::to('/');
                $leadURL = $domainName . '/detailsLog/' . $lead_id;
                foreach ($assignedUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP Credit SET');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', SAP Credit set for the lead ' . $customerName . '. Waiting for Discount Set.<br> Please <a href="' . $leadURL . '">CLICK HERE</a> to check details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            } else {
                $leadInfo->current_subStage = 'INVOICE';
                $logNext = 'SAP Invoice Generation';
                $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapInvoiceSet" AND users.is_active = 1');
                $domainName = URL::to('/');
                $leadURL = $domainName . '/invoiceSetForm/' . $lead_id;
                if ($SAPCreditUsersEmail) {
                    foreach ($SAPCreditUsersEmail as $email) {
                        $assignEmail = $email->user_email;
                        $assignName = $email->user_name;
                        Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                            $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP INVOICE GENERATION');
                            $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a lead ' . $customerName . ' is waiting for SAP Invoice Generation.<br>Please <a href="' . $leadURL . '">CLICK HERE</a> to insert SAP invoice number.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                        });
                    }
                }
                $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadInfo->id . '');
                $domainName = URL::to('/');
                $leadURL = $domainName . '/detailsLog/' . $lead_id;
                foreach ($assignedUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP Credit SET');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', SAP Credit set for the lead ' . $customerName . '. Waiting for Invoice generation.<br> Please <a href="' . $leadURL . '">CLICK HERE</a> to check details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            }
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => 'BOOKING',
                'log_task' => 'New Credit Limit: ' . $request->creditLimit . ' set. Remarks: ' . $request->creditLimitRemark . '',
                'log_by' => Auth()->user()->id,
                'log_next' => $logNext
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function holdCredit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'creditHoldRemark' => 'required'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadInfo = Lead::find($request->leadId);
            $leadInfo->current_subStage = 'CREDITHOLD';
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();
            $holdRemark = $request->creditHoldRemark;
            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $request->leadId . '');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/detailsLog/' . $request->leadId;
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $holdRemark, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Credit Set Hold');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is hold at credit set stage.<br>Hold Remarks: ' . $holdRemark . '.<br><a href="' . $leadURL . '">CLICK HERE</a> for correction and resubmit.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $request->leadId,
                'log_stage' => 'BOOKING',
                'log_task' => 'Credit Set Hold. Remarks: ' . $holdRemark,
                'log_by' => Auth()->user()->id,
                'log_next' => 'Step By Salesperson'
            );
            SalesLog::create($log_data);
            return redirect()->route('dashboard');
        }
    }

    public function reSubmitCredit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadInfo = Lead::find($request->leadId);
            if ($request->file('poFileUpdate')) {
                $quotationInfo = Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $request->leadId, 'is_accept' => 1])->get();
                foreach ($quotationInfo as $item) {
                    $quotationId = $item->id;
                }
                $poFileUpdate = $request->file('poFileUpdate');
                $poFileUpdateName = time() . "." . $poFileUpdate->getClientOriginalExtension();
                $destinationPath = 'leadQuotationAcceptAttachment/';
                $poFileUpdate->move($destinationPath, $poFileUpdateName);
                $quotationInfoUpdate = Quotation::find($quotationId);
                $quotationInfoUpdate->accept_file = $poFileUpdateName;
                $quotationInfoUpdate->save();
            }
            $leadInfo->current_subStage = 'CREDITSET';
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();

            $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapCreditSet"');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/creditSetForm/' . $leadInfo->id;
            if ($SAPCreditUsersEmail) {
                foreach ($SAPCreditUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP CREDIT SET');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is resubmitted for SAP Credit SET.<br><a href="' . $leadURL . '">CLICK HERE</a> for SAP credit set.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            }

            $log_data = array(
                'lead_id' => $request->leadId,
                'log_stage' => 'BOOKING',
                'log_task' => 'Re submit to credit set.',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Credit Limit Set'
            );
            SalesLog::create($log_data);
            return redirect()->route('dashboard');
        }
    }

    public function transactionForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['quotationInfo'] = Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $leadId, 'is_accept' => 1])->get();
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        $data['transactionInfo'] = Transaction::where(['lead_id' => $leadId])->get();
        return view('sales.transactionForm', $data);
    }

    public function storeTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transactionDate' => 'required|date',
            'transactionAmount' => 'required|numeric',
            'transactionLead' => 'required|numeric',
            'transactionQuotation' => 'required|numeric',
            'transactionType' => 'required',
        ]);
        $transactionDate = date('Y-m-d', strtotime($request->transactionDate));
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            if ($request->file('transactionFile')) {
                $transactionFileUpdate = $request->file('transactionFile');
                $transactionFileUpdateName = time() . "." . $transactionFileUpdate->getClientOriginalExtension();
                $destinationPath = 'transactionAttachment/';
                $transactionFileUpdate->move($destinationPath, $transactionFileUpdateName);
            }else{
                $transactionFileUpdateName = '';
            }

            $insert_data = array(
                'lead_id' => $request->transactionLead,
                'quotation_id' => $request->transactionQuotation,
                'deposit_date' => $transactionDate,
                'pay_amount' => $request->transactionAmount,
                'transaction_type' => $request->transactionType,
                'transaction_file' => $transactionFileUpdateName,
                'transaction_remarks' => $request->transactionRemarks
            );

            Transaction::create($insert_data);

            $verifyTransactionUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="verifyTransaction"');
            if ($verifyTransactionUsersEmail) {
                foreach ($verifyTransactionUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Transaction Inserted');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a transaction is inserted. Please verify the transaction.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            }

            $leadInfo = Lead::find($request->transactionLead);
            $currentSatge = $leadInfo->current_stage;
            if($currentSatge == 'BOOKING'){
                $logStage = 'BOOKING';
            }else{
                $logStage = 'OUTSTANDING';
            }
            $log_data = array(
                'lead_id' => $request->transactionLead,
                'log_stage' => $logStage,
                'log_task' => 'Transaction insertion of BDT ' . $request->transactionAmount . '/-',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Verify Transaction'
            );
            SalesLog::create($log_data);
            return back()->with('success', 'Transaction Saved');
        }
    }

    public function verifyTransaction($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        $data['transactionInfo'] = Transaction::where(['lead_id' => $leadId])->get();
        return view('sales.transactionVerification', $data);
    }

    public function verifyTheTransaction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'depositedDate' => 'required|date',
            'transactionId' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $depositedDate = date('Y-m-d', strtotime($request->depositedDate));
            $transaction = Transaction::find($request->transactionId);
            $transaction->is_verified = 1;
            $transaction->verified_by = Auth()->user()->id;
            $transaction->deposited_date = $depositedDate;
            $transaction->deposited_remarks = $request->depositedRemarks;
            $transaction->save();

            $leadId = $transaction->lead_id;
            $amount = $transaction->pay_amount;

            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadId . '');

            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Transaction Verified');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a submitted transaction is verified.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $leadId,
                'log_stage' => 'BOOKING',
                'log_task' => 'Transaction verified of BDT ' . $amount . '/-',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Transaction/Accounts Clearence'
            );
            SalesLog::create($log_data);
            return back()->with('success', 'Transaction Verified');
        }
    }

    public function accountsCleared(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadId = $request->lead_id;
            $quotationInfo =  Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $leadId, 'is_accept' => 1])->get();
            $pumpInfo = PumpChoice::where(['lead_id' => $leadId])->get();
            $totalNetPrice = 0;
            foreach ($pumpInfo as $pumps) {
                $totalNetPrice = $totalNetPrice + $pumps->net_price;
            }
            $transactionInfo = Transaction::where(['lead_id' => $leadId])->get();
            $totalPaid = 0;
            foreach ($transactionInfo as $transactions) {
                $totalPaid = $totalPaid + $transactions->pay_amount;
            }

            $leadInfo = Lead::find($leadId);
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->accounts_clearance = 1;
            if ($totalNetPrice > $totalPaid) {
                $leadInfo->is_outstanding = 1;
            }
            if ($leadInfo->aitAmt > 0 || $leadInfo->vatAmt > 0) {
                $leadInfo->current_stage = 'BOOKING';
                $leadInfo->current_subStage = 'CREDITSET';
                $logNext = 'AIT/VAT Credit Set';
                $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapCreditSet"');
                $domainName = URL::to('/');
                $leadURL = $domainName . '/creditSetForm/' . $leadInfo->id;
                if ($SAPCreditUsersEmail) {
                    foreach ($SAPCreditUsersEmail as $email) {
                        $assignEmail = $email->user_email;
                        $assignName = $email->user_name;
                        Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                            $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP CREDIT SET');
                            $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is waiting for new SAP Credit SET.<br><a href="' . $leadURL . '">CLICK HERE</a> for SAP credit set.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                        });
                    }
                }
            } else {
                $leadInfo->current_stage = 'DELIVERY';
                if ($leadInfo->need_discount_approval != 0) {
                    $leadInfo->current_subStage = 'DISCOUNTSET';
                    $logNext = 'SAP Discount Set';
                    $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapDiscountSet"');
                    if ($SAPCreditUsersEmail) {
                        foreach ($SAPCreditUsersEmail as $email) {
                            $assignEmail = $email->user_email;
                            $assignName = $email->user_name;
                            Mail::send([], [], function ($message) use ($assignEmail, $assignName) {
                                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP DISCOUNT SET');
                                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a lead is waiting for SAP Discount SET.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                            });
                        }
                    }
                } else {
                    $leadInfo->current_subStage = 'INVOICE';
                    $logNext = 'SAP Invoice Generation';
                    $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapInvoiceSet" AND users.is_active = 1');
                    if ($SAPCreditUsersEmail) {
                        foreach ($SAPCreditUsersEmail as $email) {
                            $assignEmail = $email->user_email;
                            $assignName = $email->user_name;
                            Mail::send([], [], function ($message) use ($assignEmail, $assignName) {
                                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP INVOICE GENERATION');
                                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a lead is waiting for SAP Invoice Generation.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                            });
                        }
                    }
                }
            }
            $leadInfo->save();

            $domainName = URL::to('/');
            $leadURL = $domainName . '/detailsLog/' . $leadInfo->id;
            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadInfo->id . '');
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM ACCOUNTS CLEARED');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', The lead ' . $customerName . ' is cleared from accounts. <br><a href="' . $leadURL . '">CLICK HERE</a> for details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $leadId,
                'log_stage' => 'BOOKING',
                'log_task' => 'Accounts Cleared. Remarks: ' . $request->clearRemark . '',
                'log_by' => Auth()->user()->id,
                'log_next' => $logNext
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function outstandingList()
    {
        $userTag = Auth()->user()->assign_to;
        if (Helper::permissionCheck(Auth()->user()->id, 'verifyTransaction') || Helper::permissionCheck(Auth()->user()->id, 'bookingStageAll') || Helper::permissionCheck(Auth()->user()->id, 'bookingStageTask')) {
            $data['outstandings'] = Lead::where(['is_outstanding' => 1, 'is_won' => 1])->get();
        } else if (Helper::permissionCheck(Auth()->user()->id, 'bookingStage')) {
            $data['outstandings'] = Lead::where(['is_outstanding' => 1])->whereHas('clientInfo', function ($query) {
                $query->where('assign_to', '=', Auth()->user()->assign_to);
            })->get();
        }

        return view('sales.outstandingList', $data);
    }

    public function outStandingTransaction($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['quotationInfo'] = Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $leadId, 'is_accept' => 1])->get();
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        $data['transactionInfo'] = Transaction::where(['lead_id' => $leadId])->get();
        return view('sales.outStandingTransaction', $data);
    }

    public function outstandingsCleared(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadId = $request->lead_id;
            $leadInfo = Lead::find($leadId);
            $leadInfo->is_outstanding = 0;
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $leadId,
                'log_stage' => 'OUTSTANDING',
                'log_task' => 'Outstanding Cleared. Remarks: ' . $request->clearRemark . '',
                'log_by' => Auth()->user()->id,
                'log_next' => ''
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function returnTransactionForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['quotationInfo'] = Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $leadId, 'is_accept' => 1])->get();
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        $data['transactionInfo'] = Transaction::where(['lead_id' => $leadId])->get();
        return view('sales.returnTransactionForm', $data);
    }

    public function returnTheTransactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $returnAmt = 0;
            $leadId = $request->leadId;
            $returnDate = date('Y-m-d', strtotime($request->returnDate));
            $transactionInfo = Transaction::where(['lead_id' => $leadId])->get();
            foreach ($transactionInfo as $item) {
                if ($item->is_verified == 1) {
                    // Only Return the verified/deposited transaction 
                    $singleInfo = Transaction::find($item->id);
                    $returnAmt = $returnAmt + $singleInfo->pay_amount;
                    $singleInfo->is_return = 1;
                    $singleInfo->return_date = $returnDate;
                    $singleInfo->return_remarks = $request->returnRemarks;
                    $singleInfo->return_by = Auth()->user()->id;
                    $singleInfo->save();
                }
            }
            $leadInfo = Lead::find($leadId);
            $leadInfo->current_subStage = 'LOST';
            $leadInfo->is_outstanding = 0;
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();

            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadId . '');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/detailsLog/' . $leadId;
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $returnAmt, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Transaction Return');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the ' . $returnAmt . ' amount of transaction is return to the lost lead ' . $customerName . '.<br><a href="' . $leadURL . '">CLICK HERE</a> for details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $leadId,
                'log_stage' => 'LOST',
                'log_task' => 'Transaction return to customer',
                'log_by' => Auth()->user()->id,
                'log_next' => ''
            );
            SalesLog::create($log_data);
            return back()->with('success', 'Transaction Return');
        }
    }
}
