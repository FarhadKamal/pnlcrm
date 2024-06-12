<?php

namespace App\Http\Controllers;

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
        $data['leadInfo'] = Lead::with('clientInfo:id,customer_name,contact_person,address,district')->find($leadId);
        $data['reqInfo'] = Requirements::where(['lead_id' => $leadId])->get();
        $data['pumpInfo'] = PumpChoice::where('lead_id', $leadId)->get();
        $data['desgName'] = Designation::find(Auth()->user()->user_desg);
        $data['deptName'] = Department::find(Auth()->user()->user_dept);
        return view('sales.quotation', $data);
    }

    public function preQuotationApprove(Request $request)
    {
        $lead_id = $request->lead_id;
        $approvePumpChoice = $request->approvePumpChoice;
        $set_discount = $request->set_discount;

        foreach ($approvePumpChoice as $key => $id) {
            $pumpInfo = PumpChoice::find($id);
            $pumpInfo->discount_percentage = $set_discount[$key];
            $pumpInfo->discount_price = $set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01;
            $pumpInfo->net_price = (($pumpInfo->qty  * $pumpInfo->unit_price) - ($set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01));
            $pumpInfo->save();
        }

        $need_discount_approval = 1;
        $need_top_approval = 0;
        $need_credit_approval = 0;
        $current_subStage = 'SUBMIT';

        if ($request->credit_approved == 1)
            $need_credit_approval = 2;
        else $need_credit_approval = 0;

        $choiceInfo = PumpChoice::where(['lead_id' => $lead_id])->get();

        foreach ($choiceInfo as $row) {
            $proposed_discount = $row->discount_percentage;
            $trade_discount = $row->productInfo->TradDiscontInfo->trade_discount;

            if ($proposed_discount > $trade_discount)
                $need_discount_approval = 2;

            if ($proposed_discount > ($trade_discount + 3)) {
                $need_top_approval = 1;
                $current_subStage = 'MANAGEMENT';
            }
        }

        $leadInfo = Lead::find($lead_id);
        $leadInfo->need_credit_approval = $need_credit_approval;
        $leadInfo->need_discount_approval = $need_discount_approval;
        $leadInfo->need_top_approval = $need_top_approval;
        $leadInfo->current_subStage = $current_subStage;
        $leadInfo->save();
        return redirect()->route('home');
    }

    public function topQuotationApprove(Request $request)
    {
        $lead_id = $request->lead_id;
        $approvePumpChoice = $request->approvePumpChoice;
        $set_discount = $request->set_discount;

        foreach ($approvePumpChoice as $key => $id) {
            $pumpInfo = PumpChoice::find($id);
            $pumpInfo->discount_percentage = $set_discount[$key];
            $pumpInfo->discount_price = $set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01;
            $pumpInfo->net_price = (($pumpInfo->qty  * $pumpInfo->unit_price) - ($set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01));
            $pumpInfo->save();
        }

        $leadInfo = Lead::find($lead_id);
        $leadInfo->need_top_approval = 2;
        $leadInfo->current_subStage = 'SUBMIT';
        $leadInfo->save();
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
            $lead = Lead::find($request->leadId);
            $leadEmail = $lead->lead_email;
            $leadName = $lead->clientInfo->customer_name;
            $assignEmail = Auth()->user()->user_email;
            $assignName = Auth()->user()->user_name;

            $customFileName = "Price Quotation_" . $leadName . "_" . date("d-M-Y") . ".pdf";
            $acceptAttachment = storage_path('app/public/' . $request->file('doc')->storeAs('folder', $customFileName, 'public'));

            $checkMail = $this->html_email($acceptAttachment, $leadEmail, $leadName, $assignEmail, $assignName);

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
                    $message->from('info@subaru-bd.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', your submitted quotation is send to the customer. Please take the feedback from customer and update in the software.<br><a href="' . $leadURL . '">CLICK HERE</a> for details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });


                // $log_data = array(
                //     'lead_id' => $request->leadId,
                //     'log_stage' => 'QUOTATION',
                //     'log_task' => 'Quotatation Approved. Remarks: ' . $request->signRemark . '',
                //     'log_by' => Auth()->user()->id,
                //     'log_next' => 'Quotation feedback'
                // );
                // SalesLog::create($log_data);
                return redirect()->route('home');
            } else {
                $data['errors'] = "Mail is not sent. Please check lead's email.";
                return back()->with('errors', $data['errors']);
            }
        }
    }

    public function acceptLeadQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quotationFeedbackModal_leadId' => 'required|numeric',
            'quotationFeedbackModal_QuotationId' => 'required|numeric',
            'quotationAcceptFile' => 'required|mimes:jpeg,jpg,png,pdf,doc,docx',
            'quotationAcceptFeedback' => 'required'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            //Update Lead Table
            $lead = Lead::find($request->quotationFeedbackModal_leadId);
            $lead->current_stage = 'BOOKING';

            // Check Customer Has SAP ID 
            $sapId = $lead->clientInfo->sap_id;
            if (!$sapId) {
                $lead->current_subStage = 'SAPIDSET';
            } else {
                if ($lead->payment_type == 'Cash') {
                    $lead->current_subStage = 'TRANSACTION';
                } elseif ($lead->payment_type == 'Credit') {
                    $lead->current_subStage = 'CREDITSET';
                }
            }
            $lead->save();

            $acceptAttachment = $request->file('quotationAcceptFile');
            $newFileName = time() . "." . $acceptAttachment->getClientOriginalExtension();
            $destinationPath = 'leadQuotationAcceptAttachment/';
            $acceptAttachment->move($destinationPath, $newFileName);

            //Quotation Table Data Update
            $quotationId = $request->quotationFeedbackModal_QuotationId;
            $update_data = array(
                'is_accept' => 1,
                'accept_file' => $newFileName,
                'accept_description' => $request->quotationAcceptFeedback,
                'return_reason' => ' ',
                'return_description' => ' '
            );
            Quotation::where(['lead_id' => $request->quotationFeedbackModal_leadId, 'id' => $quotationId])->update($update_data);

            $log_data = array(
                'lead_id' => $request->quotationFeedbackModal_leadId,
                'log_stage' => 'QUOTATION',
                'log_task' => 'Quotatation accept by lead. Description: ' . $request->quotationAcceptFeedback . '.',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Booking Transaction'
            );
            SalesLog::create($log_data);

            return redirect()->route('dashboard');
        }
    }

    // public function notAcceptLeadQuotation(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'quotationNotFeedbackModal_leadId' => 'required|numeric',
    //         'quotationNotAcceptReason' => 'required',
    //         'quotationNotAcceptFeedback' => 'required'
    //     ]);
    //     if ($validator->fails()) {
    //         $data['errors'] = $validator->errors()->all();
    //         // echo "<pre>";
    //         // var_dump($data['errors']);
    //         // echo "</pre>";
    //         // die();
    //         return back()->with('errors', $data['errors']);
    //     } else {
    //         //Update Lead Table
    //         $lead = Lead::find($request->quotationNotFeedbackModal_leadId);
    //         $lead->current_stage = 'DEAL';
    //         $lead->is_return = 1;
    //         $lead->current_subStage = 'FORM';
    //         $lead->save();

    //         $dealInfo = Deal::where('lead_id', $request->quotationNotFeedbackModal_leadId)->orderBy('id', 'desc')->limit(1)->get();

    //         $dealDetails = DealDetail::where(['lead_id' => $request->quotationNotFeedbackModal_leadId, 'deal_id' => $dealInfo[0]->id])->get();
    //         foreach ($dealDetails as $item) {
    //             $inventoryId = $item->inventory_id;
    //             if ($inventoryId) {
    //                 $inventoryInfo = Inventory::find($inventoryId);
    //                 $inventoryInfo->deal_status = 0;
    //                 $inventoryInfo->save();
    //             }
    //         }

    //         //Quotation Table Data Update
    //         $dealId = $lead->current_deal;
    //         $update_data = array(
    //             'accept_file' => ' ',
    //             'accept_description' => ' ',
    //             'is_return' => 1,
    //             'return_reason' => $request->quotationNotAcceptReason,
    //             'return_description' => $request->quotationNotAcceptFeedback
    //         );
    //         Quotation::where(['lead_id' => $request->quotationNotFeedbackModal_leadId, 'deal_id' => $dealId])->update($update_data);


    //         $quotationApproveUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
    //         INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
    //         INNER JOIN users ON users.id=user_permissions.user_id
    //         WHERE permissions.permission_code="quotationApprove"');

    //         foreach ($quotationApproveUsersEmail as $email) {
    //             $assignEmail = $email->user_email;
    //             $assignName = $email->user_name;
    //             Mail::send([], [], function ($message) use ($assignEmail, $assignName) {
    //                 $message->to($assignEmail, $assignName)->subject('Subaru Bangladesh - CRM Quotation Accepted By Customer');
    //                 $message->from('info@subaru-bd.com', 'Subaru Bangladesh');
    //                 $message->setBody('<h3>Greetings From SUBARU BANGLADESH!</h3><p>Dear ' . $assignName . ', a quotation is not accepted by the customer. Need to deal again.</p><p>Regards,<br>Subaru Bangladesh</p>', 'text/html');
    //             });
    //         }

    //         $dealApproveUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
    //         INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
    //         INNER JOIN users ON users.id=user_permissions.user_id
    //         WHERE permissions.permission_code="dealApprove"');

    //         foreach ($dealApproveUsersEmail as $email) {
    //             $assignEmail = $email->user_email;
    //             $assignName = $email->user_name;
    //             Mail::send([], [], function ($message) use ($assignEmail, $assignName) {
    //                 $message->to($assignEmail, $assignName)->subject('Subaru Bangladesh - CRM Quotation Accepted By Customer');
    //                 $message->from('info@subaru-bd.com', 'Subaru Bangladesh');
    //                 $message->setBody('<h3>Greetings From SUBARU BANGLADESH!</h3><p>Dear ' . $assignName . ', a quotation is not accepted by the customer. Need to deal again.</p><p>Regards,<br>Subaru Bangladesh</p>', 'text/html');
    //             });
    //         }


    //         $log_data = array(
    //             'lead_id' => $request->quotationNotFeedbackModal_leadId,
    //             'log_stage' => 'QUOTATION',
    //             'log_task' => 'Quotatation not accept by lead. Reason: ' . $request->quotationNotAcceptReason . '. Description: ' . $request->quotationNotAcceptFeedback . '',
    //             'log_by' => Auth()->user()->id,
    //             'log_next' => 'Deal form submission'
    //         );
    //         SalesLog::create($log_data);

    //         return redirect()->route('sales');
    //     }
    // }


    public function html_email($attachment, $leadEmail, $leadName, $assignEmail, $assignName)
    {

        $data = array('name' => "PNL Holdings Limited");
        Mail::send([], [], function ($message) use ($attachment, $leadEmail, $leadName, $assignEmail, $assignName) {
            $message->to($leadEmail, $leadName)->subject('PNL Holdings Limited Price Quotation');
            $message->from('info@subaru-bd.com', 'PNL Holdings Ltd.');
            $message->cc($assignEmail, $assignName);
            $message->attach($attachment);
            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Thank you for your interest in PNL Holdings Limited.<br>Please Find the quotation attachment and reply your feedback to this email. For any query you can call us directly at +8801844494444</p><p>Regards,<br>Subaru Bangladesh</p>', 'text/html');
        });

        if (Mail::failures()) {
            return false;
        } else {
            return true;
        }
    }
}
