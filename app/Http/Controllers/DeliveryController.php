<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PumpChoice;
use App\Models\Quotation;
use App\Models\SalesLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            $leadInfo->save();

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

        // URL of the API endpoint
        $url = 'http://103.4.66.107:8989/api/verify_invoice.php?code=' . $inputSAP . '&year=' . $fyear;

        // Make the request and get the response
        $rtnvalue = file_get_contents($url);

        if ($rtnvalue == 1) {
            $response = [
                'status' => 'gotSAP'
            ];
        } else {
            $response = [
                'status' => 'notSAP'
            ];
        }
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
            $leadInfo->current_stage = 'DELIVERY';
            $leadInfo->current_subStage = 'READY';
            $leadInfo->save();

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
        $data['checkDeliverySerial'] = DB::select("SELECT COUNT(*) AS sl FROM leads INNER JOIN customers ON customers.id = leads.customer_id WHERE leads.delivery_challan != '' AND customers.assign_to = '".Auth()->user()->assign_to."' AND Year(leads.updated_at) = " . date('Y') . " AND Month(leads.updated_at) = " . date('m') . " AND Day(leads.updated_at) = " . date('d') . "");
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
