<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Lead;
use App\Models\PumpChoice;
use App\Models\Quotation;
use App\Models\Requirements;
use App\Models\SalesLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function viewQuotation($leadId)
    {
        $leadInfo = Lead::find($leadId);
        if ($leadInfo->current_stage != 'QUOTATION') {
            return back()->with('error', array('The lead is not valid for quotation stage'));
        } else {
            if ($leadInfo->current_subStage == 'APPROVE' && !Helper::permissionCheck(Auth()->user()->id, 'dealApprove')) {
                return back()->with('error', array('You are not authorized'));
            } elseif ($leadInfo->current_subStage == 'MANAGEMENT' && !Helper::permissionCheck(Auth()->user()->id, 'dealTopApprove')) {
                return back()->with('error', array('You are not authorized'));
            } elseif ($leadInfo->current_subStage == 'SUBMIT' && $leadInfo->clientInfo->assign_to != Auth()->user()->assign_to) {
                return back()->with('error', array('You are not authorized'));
            }
        }
        $data['leadInfo'] = Lead::with('clientInfo:id,customer_name,contact_person,address,district')->find($leadId);
        $data['reqInfo'] = Requirements::where(['lead_id' => $leadId])->get();
        $data['pumpInfo'] = PumpChoice::where('lead_id', $leadId)->orderby('id', 'ASC')->get();
        $subPump = false;
        $subSpare = false;
        $subItap = false;
        $subMaxwell = false;
        foreach ($data['pumpInfo'] as $item) {
            if ($item->spare_parts == 1) {
                $subSpare = true;
            } else {
                if ($item->productInfo->brand_name == 'ITAP') {
                    $subItap = true;
                }
                if ($item->productInfo->brand_name == 'MAXWELL') {
                    $subMaxwell = true;
                }
                if ($item->productInfo->brand_name != 'ITAP' && $item->productInfo->brand_name != 'MAXWELL') {
                    $subPump = true;
                }
            }
        }
        $subjectText = 'Price Quotation for the supply of';
        $showAnd = false;
        if ($subPump) {
            $subjectText = $subjectText . ' Electric Water Pump';
            $showAnd = true;
        }
        if ($subSpare) {
            if ($showAnd) {
                $subjectText = $subjectText . ', Accessories';
            } else {
                $subjectText = $subjectText . ' Accessories';
                $showAnd = true;
            }
        }
        if ($subMaxwell) {
            if ($showAnd) {
                $subjectText = $subjectText . ', Maxwell';
            } else {
                $subjectText = $subjectText . ' Maxwell';
                $showAnd = true;
            }
        }
        if ($subItap) {
            if ($showAnd) {
                $subjectText = $subjectText . ', ITAP';
            } else {
                $subjectText = $subjectText . ' ITAP';
                $showAnd = true;
            }
        }
        $data['desgName'] = Designation::find(Auth()->user()->user_desg);
        $data['deptName'] = Department::find(Auth()->user()->user_dept);
        $data['subjectText'] = $subjectText;
        $data['discountRemarks'] = SalesLog::where('log_task', 'like', '%Discount Remarks%')->orderBy('id', 'desc')->first();

        if ($leadInfo->current_stage == 'QUOTATION' && $leadInfo->current_subStage == 'SUBMIT') {
            $quotationInfo = Quotation::where(['lead_id' => $leadId])->get();
            $rowCount = $quotationInfo->count();
            if ($rowCount > 0) {
                $data['reEmail'] = true;
            } else {
                $data['reEmail'] = false;
            }
        }
        return view('sales.quotation', $data);
    }

    public function preQuotationApprove(Request $request)
    {
        $lead_id = $request->lead_id;
        $set_discount = $request->set_discount;

        if (isset($request->approvePumpChoice)) {
            $approvePumpChoice = $request->approvePumpChoice;
            foreach ($approvePumpChoice as $key => $id) {
                $pumpInfo = PumpChoice::find($id);
                $pumpInfo->discount_percentage = $set_discount[$key];
                $pumpInfo->discount_price = $set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01;
                $pumpInfo->net_price = (($pumpInfo->qty  * $pumpInfo->unit_price) - ($set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01));
                $pumpInfo->save();
            }
        }

        $need_discount_approval = 1;
        $need_top_approval = 0;
        $need_credit_approval = 0;
        $current_subStage = 'SUBMIT';
        $logNext = 'Quotation Submit';

        if ($request->credit_approved == 1) {
            $need_credit_approval = 2;
        } else {
            $need_credit_approval = 0;
        }

        $choiceInfo = PumpChoice::where(['lead_id' => $lead_id])->get();

        foreach ($choiceInfo as $row) {
            $proposed_discount = $row->discount_percentage;
            if ($row->spare_parts == 0) {
                $trade_discount = $row->productInfo->TradDiscontInfo->trade_discount;

                if ($proposed_discount > $trade_discount) {
                    $need_discount_approval = 2;
                }

                if ($proposed_discount > ($trade_discount + 3)) {
                    $need_top_approval = 1;
                    $current_subStage = 'MANAGEMENT';
                    $logNext = 'Management Approval';
                }
            }
        }

        $leadInfo = Lead::find($lead_id);
        $customerName = $leadInfo->clientInfo->customer_name;

        if ($need_top_approval == 1) {
            $discountRemarks = SalesLog::where('log_task', 'like', '%Discount Remarks%')->orderBy('id', 'desc')->first();
            $discountRemarks = $discountRemarks->log_task;
            $dealApproveUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="dealTopApprove"');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/quotationCheck/' . $lead_id;
            if ($dealApproveUsersEmail) {
                foreach ($dealApproveUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL, $discountRemarks) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Deal Approval');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<p>Dear Sir, The deal for the customer ' . $customerName . ' is waiting for Managing Director approval. Please approve the deal.<br><br>'.$discountRemarks.'<br><br><a href="' . $leadURL . '">CLICK HERE</a> for approve the lead.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            }
            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $lead_id . '');
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Deal Approval');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a submitted deal for the customer ' . $customerName . ' is approved. Waiting for Managing Director approval.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }
        } else {
            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $lead_id . '');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/quotationCheck/' . $lead_id;
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Deal Approval');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a submitted deal for the customer ' . $customerName . ' is approved. <br> Please <a href="' . $leadURL . '">CLICK HERE</a> to submit the quotation to the customer.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }
        }

        $leadInfo = Lead::find($lead_id);
        $leadInfo->need_credit_approval = $need_credit_approval;
        $leadInfo->need_discount_approval = $need_discount_approval;
        $leadInfo->need_top_approval = $need_top_approval;
        $leadInfo->current_subStage = $current_subStage;
        $leadInfo->save();

        $log_data = array(
            'lead_id' => $lead_id,
            'log_stage' => 'QUOTATION',
            'log_task' => 'Quotation Approved',
            'log_by' => Auth()->user()->id,
            'log_next' => $logNext
        );
        SalesLog::create($log_data);

        return redirect()->route('home');
    }

    public function preQuotationReturn(Request $request){
        $lead_id = $request->lead_id;
        $leadInfo = Lead::find($lead_id);
        $leadInfo->need_discount_approval = 0;
        $leadInfo->need_top_approval = 0;
        $leadInfo->current_stage = 'DEAL';
        $leadInfo->current_subStage = 'FORM';
        $customerName = $leadInfo->clientInfo->customer_name;
        $leadInfo->save();
        $preReturnRemarks = $request->preReturnRemarks;

        $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $lead_id . '');
        $domainName = URL::to('/');
        $leadURL = $domainName . '/dealPage/' . $lead_id;
        foreach ($assignedUsersEmail as $email) {
            $assignEmail = $email->user_email;
            $assignName = $email->user_name;
            Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL, $preReturnRemarks) {
                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Deal Returned');
                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a submitted deal for the customer ' . $customerName . ' is returned.<br><br>Return Remarks: '.$preReturnRemarks.'<br><br> Please <a href="' . $leadURL . '">CLICK HERE</a> to re deal.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
            });
        }

        $log_data = array(
            'lead_id' => $lead_id,
            'log_stage' => 'QUOTATION',
            'log_task' => 'Pre Return: ' . $request->preReturnRemarks,
            'log_by' => Auth()->user()->id,
            'log_next' => 'Re-Deal'
        );
        SalesLog::create($log_data);
        return redirect()->route('home');

    }

    public function topQuotationApprove(Request $request)
    {
        $lead_id = $request->lead_id;
        $set_discount = $request->set_discount;

        if (isset($request->approvePumpChoice)) {
            $approvePumpChoice = $request->approvePumpChoice;

            foreach ($approvePumpChoice as $key => $id) {
                $pumpInfo = PumpChoice::find($id);
                $pumpInfo->discount_percentage = $set_discount[$key];
                $pumpInfo->discount_price = $set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01;
                $pumpInfo->net_price = (($pumpInfo->qty  * $pumpInfo->unit_price) - ($set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01));
                $pumpInfo->save();
            }
        }

        $leadInfo = Lead::find($lead_id);
        $leadInfo->need_top_approval = 2;
        $leadInfo->current_subStage = 'SUBMIT';
        $customerName = $leadInfo->clientInfo->customer_name;
        $leadInfo->save();

        $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $lead_id . '');
        $domainName = URL::to('/');
        $leadURL = $domainName . '/quotationCheck/' . $lead_id;
        foreach ($assignedUsersEmail as $email) {
            $assignEmail = $email->user_email;
            $assignName = $email->user_name;
            Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Deal Approval');
                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a submitted deal for the customer ' . $customerName . ' is approved by the top managment.<br> Please <a href="' . $leadURL . '">CLICK HERE</a> to submit the quotation to the customer.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
            });
        }

        $log_data = array(
            'lead_id' => $lead_id,
            'log_stage' => 'QUOTATION',
            'log_task' => 'Management Approved',
            'log_by' => Auth()->user()->id,
            'log_next' => 'Quotation Submit'
        );
        SalesLog::create($log_data);

        return redirect()->route('home');
    }

    public function topQuotationReturn(Request $request)
    {
        $lead_id = $request->lead_id;
        $leadInfo = Lead::find($lead_id);
        $leadInfo->need_discount_approval = 0;
        $leadInfo->need_top_approval = 0;
        $leadInfo->current_stage = 'DEAL';
        $leadInfo->current_subStage = 'FORM';
        $customerName = $leadInfo->clientInfo->customer_name;
        $leadInfo->save();
        $topReturnRemarks = $request->topReturnRemarks;
        
        $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $lead_id . '');
        $domainName = URL::to('/');
        $leadURL = $domainName . '/dealPage/' . $lead_id;
        foreach ($assignedUsersEmail as $email) {
            $assignEmail = $email->user_email;
            $assignName = $email->user_name;
            Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL, $topReturnRemarks) {
                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Deal Return From Managment');
                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a submitted deal for the customer ' . $customerName . ' is return by the top managment.<br><br>Return Remarks: '.$topReturnRemarks.'<br><br> Please <a href="' . $leadURL . '">CLICK HERE</a> to re deal.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
            });
        }

        $log_data = array(
            'lead_id' => $lead_id,
            'log_stage' => 'QUOTATION',
            'log_task' => 'Management Return: ' . $request->topReturnRemarks,
            'log_by' => Auth()->user()->id,
            'log_next' => 'Re-Deal'
        );
        SalesLog::create($log_data);
        return redirect()->route('home');
    }

    public function quotationReferenceCheck()
    {
        $data['currentDate'] = date('Y-m-d');
        $data['checkQuotationSerial'] = DB::select("SELECT COUNT(*) AS sl FROM quotations WHERE Year(created_at) = " . date('Y') . " AND Month(created_at) = " . date('m') . " AND Day(created_at) = " . date('d') . "");
        return response()->json($data);
    }

    public function submitQuotation(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            // return back()->with('errors', $data['errors']);
        } else {
            //First Handle Other Attachment 
            $otherAttachment = $request->file('otherAttachment');
            $attachmentArr = [];
            if ($otherAttachment) {
                foreach ($otherAttachment as $item) {
                    if ($item) {
                        $attachmentArr[] = $item;
                    }
                }
            }

            $lead = Lead::find($request->leadId);
            $leadEmail = $lead->lead_email;
            $leadName = $lead->clientInfo->customer_name;
            $assignEmail = Auth()->user()->user_email;
            $assignName = Auth()->user()->user_name;

            $customFileName = "Price Quotation_" . $leadName . "_" . date("d-M-Y") . ".pdf";
            $acceptAttachment = storage_path('app/public/' . $request->file('doc')->storeAs('folder', $customFileName, 'public'));

            $ccEmails = $request->input('ccEmails', []);
            $emailRemarks = $request->input('emailRemarks');
            $emailFlag = $request->input('emailFlag');
            if ($emailFlag == 1) {
                $checkMail = $this->html_email($acceptAttachment, $leadEmail, $leadName, $assignEmail, $assignName, $attachmentArr, $ccEmails, $emailRemarks);
            } else {
                $checkMail = true;
            }

            $quotationAttachment = new \Symfony\Component\HttpFoundation\File\File($acceptAttachment);
            $newFileName = time() . "." . $quotationAttachment->getExtension();
            $destinationPath = 'quotations/';
            $quotationAttachment->move($destinationPath, $newFileName);

            //Insert Into Quotation Database Table
            $insert_data = array(
                'lead_id' => $request->leadId,
                'quotation_ref' => $request->quotationRef,
                'quotation_file' => $newFileName,
                'accept_file' => ' ',
                'accept_description' => ' ',
                'return_reason' => ' ',
                'return_description' => ' '
            );
            Quotation::create($insert_data);

            if ($checkMail) {
                //Update Lead Table
                $lead = Lead::find($request->leadId);
                $lead->current_subStage = 'FEEDBACK';
                $lead->save();

                $domainName = URL::to('/');
                $leadURL = $domainName . '/detailsLog/' . $request->leadId;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Waiting For Quotation Feedback Process');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', your submitted quotation is send to the customer. Please take the feedback from customer and update in the software.<br><a href="' . $leadURL . '">CLICK HERE</a> for details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });


                $log_data = array(
                    'lead_id' => $request->leadId,
                    'log_stage' => 'QUOTATION',
                    'log_task' => 'Quotatation Submitted',
                    'log_by' => Auth()->user()->id,
                    'log_next' => 'Quotation feedback'
                );
                SalesLog::create($log_data);
                return redirect()->route('home');
            } else {
                $data['errors'] = "Mail is not sent. Please check lead's email.";
                return back()->with('errors', $data['errors']);
            }
        }
    }

    public function quotationFeedbackForm($leadId)
    {
        $data['leadInfo'] = Lead::find($leadId);
        if ($data['leadInfo']->current_stage != 'QUOTATION' && $data['leadInfo']->current_subStage != 'FEEDBACK') {
            return back()->with('error', array('The lead is not valid for quotation stage'));
        }

        $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $leadId ORDER BY id DESC LIMIT 1");
        if ($quotationRef) {
            $data['quotationId'] = $quotationRef[0]->id;
            $data['quotationRef'] = $quotationRef[0]->quotation_ref;
        }

        return view('sales.quotationFeedback', $data);
    }

    public function acceptLeadQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quotationFeedbackModal_leadId' => 'required|numeric',
            'quotationFeedbackModal_QuotationId' => 'required|numeric',
            'quotationAcceptFile' => 'required|mimes:jpeg,jpg,png,pdf,doc,docx',
            'quotationAcceptFeedback' => 'required',
            'quotationPO' => 'required',
            'quotationPODate' => 'required',
            'quotationAIT' => 'numeric',
            'quotationVAT' => 'numeric',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            //Update Lead Table
            $lead = Lead::find($request->quotationFeedbackModal_leadId);
            $ait = $request->quotationAIT;
            $vat = $request->quotationVAT;
            if ($ait && $ait > 0) {
                $lead->aitAmt = $ait;
            }
            if ($vat && $vat > 0) {
                $lead->vatAmt = $vat;
            }
            $lead->current_stage = 'BOOKING';
            $customerTableID = $lead->clientInfo->id;
            $customerName = $lead->clientInfo->customer_name;
            // Check Customer Has SAP ID 
            $sapId = $lead->clientInfo->sap_id;
            if (!$sapId) {
                $lead->current_subStage = 'CHECKCUSDOC';
                // $lead->current_subStage = 'SAPIDSET';
                $logNext = 'Customer Doc Check';
                $domainName = URL::to('/');
                $leadURL = $domainName . '/customerDocCheck/' . $lead->id;
                $SAPUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="customerDocCheck"');
                if ($SAPUsersEmail) {
                    foreach ($SAPUsersEmail as $email) {
                        $assignEmail = $email->user_email;
                        $assignName = $email->user_name;
                        Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                            $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP ID SET');
                            $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a new lead ' . $customerName . ' is waiting for document checking process.<br><a href="' . $leadURL . '">CLICK HERE</a> to complete document check process.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                        });
                    }
                }
            } else {
                if ($lead->payment_type == 'Cash') {
                    $lead->current_subStage = 'TRANSACTION';
                    $logNext = 'Cash Transaction';
                } elseif ($lead->payment_type == 'Credit') {
                    $lead->current_subStage = 'CREDITSET';
                    $logNext = 'Credit Limit Set';
                    $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapCreditSet"');
                    $domainName = URL::to('/');
                    $leadURL = $domainName . '/creditSetForm/' . $lead->id;
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
                }
            }
            $lead->save();

            // Purchase Order 
            $acceptAttachment = $request->file('quotationAcceptFile');
            $newFileName = time() . "." . $acceptAttachment->getClientOriginalExtension();
            $destinationPath = 'leadQuotationAcceptAttachment/';
            $acceptAttachment->move($destinationPath, $newFileName);

            $customerInfo = Customer::find($customerTableID);
            // Customer TIN
            if ($request->file('customerTIN')) {
                $customerTIN = $request->file('customerTIN');
                $customerTINName = "TIN" . time() . "." . $customerTIN->getClientOriginalExtension();
                $destinationPath = 'customerDocument/';
                $customerTIN->move($destinationPath, $customerTINName);
                $customerInfo->tin = $customerTINName;
            }
            // Customer BIN
            if ($request->file('customerBIN')) {
                $customerBIN = $request->file('customerBIN');
                $customerBINName = "BIN" . time() . "." . $customerBIN->getClientOriginalExtension();
                $destinationPath = 'customerDocument/';
                $customerBIN->move($destinationPath, $customerBINName);
                $customerInfo->bin = $customerBINName;
            }
            // Customer TL
            if ($request->file('customerTL')) {
                $customerTL = $request->file('customerTL');
                $customerTLName = "TL" . time() . "." . $customerTL->getClientOriginalExtension();
                $destinationPath = 'customerDocument/';
                $customerTL->move($destinationPath, $customerTLName);
                $customerInfo->trade_license = $customerTLName;
            }
            $customerInfo->save();

            //Quotation Table Data Update
            $quotationId = $request->quotationFeedbackModal_QuotationId;
            $poDate = date('Y-m-d', strtotime($request->quotationPODate));
            $update_data = array(
                'is_accept' => 1,
                'accept_file' => $newFileName,
                'accept_description' => $request->quotationAcceptFeedback,
                'quotation_po' => $request->quotationPO,
                'quotation_po_date' => $poDate,
                'return_reason' => ' ',
                'return_description' => ' '
            );
            Quotation::where(['lead_id' => $request->quotationFeedbackModal_leadId, 'id' => $quotationId])->update($update_data);

            $log_data = array(
                'lead_id' => $request->quotationFeedbackModal_leadId,
                'log_stage' => 'QUOTATION',
                'log_task' => 'Quotatation accept by lead. Description: ' . $request->quotationAcceptFeedback . '.',
                'log_by' => Auth()->user()->id,
                'log_next' => $logNext
            );
            SalesLog::create($log_data);

            return redirect()->route('dashboard');
        }
    }

    public function notAcceptLeadQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quotationNotFeedbackModal_leadId' => 'required|numeric',
            'quotationNotAcceptReason' => 'required',
            'quotationNotAcceptFeedback' => 'required'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            //Update Lead Table
            $leadId = $request->quotationNotFeedbackModal_leadId;
            $lead = Lead::find($request->quotationNotFeedbackModal_leadId);
            $lead->current_stage = 'DEAL';
            $lead->is_return = 1;
            $lead->current_subStage = 'FORM';
            $lead->save();

            //Quotation Table Data Update
            $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $leadId ORDER BY id DESC LIMIT 1");
            $quotationId = $quotationRef[0]->id;
            $quotationInfo = Quotation::find($quotationId);
            $quotationInfo->is_return = 1;
            $quotationInfo->return_reason = $request->quotationNotAcceptReason;
            $quotationInfo->return_description = $request->quotationNotAcceptFeedback;
            $quotationInfo->save();


            $log_data = array(
                'lead_id' => $request->quotationNotFeedbackModal_leadId,
                'log_stage' => 'QUOTATION',
                'log_task' => 'Quotatation not accept by lead. Reason: ' . $request->quotationNotAcceptReason . '. Description: ' . $request->quotationNotAcceptFeedback . '',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Deal form submission'
            );
            SalesLog::create($log_data);

            return redirect()->route('home');
        }
    }

    public function returnToQuotationStage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'returnRemark' => 'required',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with($data);
        } else {
            $leadId = $request->leadId;
            $leadInfo = Lead::find($leadId);
            $logStage = $leadInfo->current_stage;
            $logSubStage = $leadInfo->current_subStage;
            $leadInfo->current_stage = 'QUOTATION';
            $leadInfo->current_subStage = 'FEEDBACK';
            $leadInfo->accounts_clearance = 0;
            $leadInfo->is_outstanding = 0;
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $leadId,
                'log_stage' => $logStage,
                'log_task' => 'Back to quotation stage. Remark: ' . $request->returnRemark,
                'log_by' => Auth()->user()->id,
                'log_next' => 'Quotation feedback'
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function html_email($attachment, $leadEmail, $leadName, $assignEmail, $assignName, $attachmentArr, $ccEmails, $emailRemarks)
    {

        $data = array('name' => "PNL Holdings Limited");
        Mail::send([], [], function ($message) use ($attachment, $leadEmail, $leadName, $assignEmail, $assignName, $attachmentArr, $ccEmails, $emailRemarks) {
            $message->to($leadEmail, $leadName)->subject('PNL Holdings Limited Price Quotation');
            $message->from('sales@pnlholdings.com', 'PNL Holdings Ltd.');
            $message->cc($assignEmail, $assignName);
            if (!empty($ccEmails)) {
                foreach ($ccEmails as $ccEmail) {
                    $message->cc($ccEmail);
                }
            }
            $message->attach($attachment);
            // Attach additional attachments from the array
            foreach ($attachmentArr as $file) {
                $message->attach($file->getPathname(), [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType()
                ]);
            }
            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Thank you for your interest in PNL Holdings Limited.<br>Please Find the quotation attachment and reply your purchase order/feedback to this email.<br><b>Remarks:</b> ' . $emailRemarks . ' <br>For any query you can call at 16308</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
        });

        if (Mail::failures()) {
            return false;
        } else {
            return true;
        }
    }
}
