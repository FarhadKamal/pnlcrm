<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Lead;
use App\Models\PumpChoice;
use App\Models\Quotation;
use App\Models\SalesLog;
use App\Models\SpareItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class DeliveryController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function discountSetForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        return view('sales.discountSetForm', $data);
    }

    public function insertDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadInfo = Lead::find($request->leadId);
            $leadInfo->current_stage = 'DELIVERY';
            $leadInfo->current_subStage = 'INVOICE';
            $customerName = $leadInfo->clientInfo->customer_name;
            $lead_id = $request->leadId;
            $leadInfo->save();

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
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is waiting for SAP Invoice Generation.<br> Please <a href="' . $leadURL . '">CLICK HERE</a> to insert the SAP invoice number.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
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
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM DISCOUNT SET');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', Discount is set for the lead ' . $customerName . '. Waiting for SAP Invoice Generation.<br> Please <a href="' . $leadURL . '">CLICK HERE</a> to check details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => 'DELIVERY',
                'log_task' => 'New Discount Set. Remarks: ' . $request->discountRemark . '',
                'log_by' => Auth()->user()->id,
                'log_next' => 'SAP Invoice Generation'
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function invoiceSetForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        return view('sales.invoiceSetForm', $data);
    }

    public function checkSAPInvoice(Request $request)
    {
        $data = $request->json()->all();
        $inputSAP = $data['inputSAP'];

        if (date('m') > 6)
            $zyear = date('Y');
        else $zyear = date('Y') - 1;

        $y1 = substr($zyear, -2);
        $y2 = $y1 + 1;

        $fyear = $y1 . "-" . $y2;

        // Duplicacy Check in the CRM 
        $duplicate = Lead::where(['sap_invoice' => $inputSAP])->get();

        // URL of the API endpoint
        // $url = 'http://103.4.66.107:8989/api/verify_invoice.php?code=' . $inputSAP . '&year=' . $fyear;
        // $url = 'http://192.168.1.226:8989/api/verify_invoice.php?code=' . $inputSAP . '&year=' . $fyear;
        $url2 = 'http://192.168.1.226:8989/api/verify_invoice2.php?code=' . $inputSAP . '&year=' . $fyear;
        $getInvoiceInfo = json_decode(file_get_contents($url2));

        $response = [
            'status' => $getInvoiceInfo,
            'isDuplicate' => $duplicate
        ];
        // Make the request and get the response
        // $rtnvalue = file_get_contents($url);

        // if ($rtnvalue == 1) {
        //     $response = [
        //         'status' => 'gotSAP'
        //     ];
        // } else {
        //     $response = [
        //         'status' => 'notSAP'
        //     ];
        // }
        return response()->json($response);
    }

    public function insertInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'invoiceID' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $today = date('Y-m-d');
            $leadInfo = Lead::find($request->leadId);
            $leadInfo->sap_invoice = $request->invoiceID;
            $leadInfo->invoice_date = $today;
            $leadInfo->invoice_sap_vatsum = $request->invoiceVatSum;
            $leadInfo->current_stage = 'DELIVERY';
            $leadInfo->current_subStage = 'READY';
            $leadInfo->invoice_by = Auth()->user()->id;
            $customerName = $leadInfo->clientInfo->customer_name;
            $lead_id = $request->leadId;
            $leadInfo->save();

          
            // $response = Http::withHeaders([
            //     'Accept' => 'application/json',
            // ])->post(route('checkSAPInvoice'), [
            //     'inputSAP' => $request->invoiceID
            // ]);

            // $invoiceInfoData = $response->json();

            // if (isset($invoiceInfoData->status) && is_array($invoiceInfoData->status)) {
            //     foreach ($invoiceInfoData->status as $item) {
            //         $itemCode = $item->ItemCode ?? null;
            //         $gTotal = $item->GTotal ?? null;
            //         $vatSum = $item->VatSum ?? null;

            //         if ($itemCode && $gTotal) {
            //             $itemId = Items::where('new_code', $itemCode)->value('id');
            //             if ($itemId) {
            //                 PumpChoice::where('lead_id', $lead_id)
            //                     ->where('product_id', $itemId)
            //                     ->where('net_price', $gTotal)
            //                     ->update(['sap_vatsum' => $vatSum]);
            //             } else {
            //                 $spareId = SpareItems::where('new_code', $itemCode)->value('id');
            //                 PumpChoice::where('lead_id', $lead_id)
            //                     ->where('product_id', $spareId)
            //                     ->where('net_price', $gTotal)
            //                     ->update(['sap_vatsum' => $vatSum]);
            //             }
            //         }
            //     }
            // }


            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadInfo->id . '');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/deliveryPage/' . $lead_id;
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM INVOICE GENERATED');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', Invoice generated for the lead ' . $customerName . '. Waiting for your Delivery process.<br>Please <a href="' . $leadURL . '">CLICK HERE</a> to delivered items.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => 'DELIVERY',
                'log_task' => 'New SAP Invoice ID: ' . $request->invoiceID . ' Generated. Remarks: ' . $request->invoiceRemark . '',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Delivery Information Submission'
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function deliveryPage($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        $data['quotationInfo'] = Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $leadId, 'is_accept' => 1])->get();
        return view('sales.deliveryPage', $data);
    }

    public function deliveryReferenceCheck()
    {
        $data['currentDate'] = date('Y-m-d');
        $data['checkDeliverySerial'] = DB::select("SELECT COUNT(*) AS sl FROM leads INNER JOIN customers ON customers.id = leads.customer_id WHERE leads.delivery_challan != '' AND customers.assign_to = '" . Auth()->user()->assign_to . "' AND Year(leads.updated_at) = " . date('Y') . " AND Month(leads.updated_at) = " . date('m') . " AND Day(leads.updated_at) = " . date('d') . "");
        return response()->json($data);
    }

    public function storeDeliveryInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'challanNo' => 'required',
            'address' => 'required',
            'contactPerson' => 'required',
            'contactMobile' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $leadInfo = Lead::find($request->leadId);
            $leadInfo->delivery_challan = $request->challanNo;
            $leadInfo->delivery_address = $request->address;
            $leadInfo->delivery_person = $request->contactPerson;
            $leadInfo->delivery_mobile = $request->contactMobile;
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => 'DELIVERY',
                'log_task' => 'Delivery Information Submission.',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Delivery'
            );
            SalesLog::create($log_data);
            return back()->with('success', 'Delivery Information stored');
        }
    }

    public function storeDelivered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'deliveryAttachment' => 'required|mimes:jpeg,jpg,png,pdf'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errors', $data['errors']);
        } else {
            $deliveryAttachment = $request->file('deliveryAttachment');
            $newFileName = time() . "." . $deliveryAttachment->getClientOriginalExtension();
            $destinationPath = 'deliveryAttachment/';
            $deliveryAttachment->move($destinationPath, $newFileName);

            $leadInfo = Lead::find($request->leadId);
            $leadInfo->current_stage = 'WON';
            $leadInfo->is_won = 1;
            $leadInfo->current_subStage = '';
            $leadInfo->delivery_attachment = $newFileName;
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => 'DELIVERY',
                'log_task' => 'Delivered Item',
                'log_by' => Auth()->user()->id,
                'log_next' => ''
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }
}
