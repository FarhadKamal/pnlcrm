<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesLog;
use App\Models\Lead;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{

    public function demo()
    {
        $data['jitu'] = Customer::orderBy('customer_name', 'asc')->get();
        return view('demo', $data);
    }


    public function customerForm()
    {



        // $data['divisionList'] = Fetch Division List
        // $data['districtList'] = Fetch District List
        // $data['zoneList'] = Fetch Zone List
        // $data['leadSource'] = Fetch Source List

        return view('sales.customerForm');
    }

    public function storeCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clientName' => 'required',
            'groupName' => 'required',
            'clientAddress' => 'required',
            'clientZone' => 'required',
            'clientDistrict' => 'required',
            'clientDivision' => 'required',
            'contactPerson' => 'required',
            'contactMobile' => 'required',
            'contactEmail' => 'nullable|email',
            'contactPerson' => 'required',
            'leadSource' => 'required',
            'clientReq' => 'required'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            $data['clientName'] = $request->clientName;
            $data['groupName'] = $request->groupName;
            $data['clientAddress'] = $request->clientAddress;
            $data['clientZone'] = $request->clientZone;
            $data['clientDistrict'] = $request->clientDistrict;
            $data['clientDivision'] = $request->clientDivision;
            $data['clientTIN'] = $request->clientTIN;
            $data['clientBIN'] = $request->clientBIN;
            $data['clientTL'] = $request->clientTL;
            $data['contactPerson'] = $request->contactPerson;
            $data['contactMobile'] = $request->contactMobile;
            $data['contactEmail'] = $request->contactEmail;
            $data['leadSource'] = $request->leadSource;
            $data['clientReq'] = $request->clientReq;
            return back()->with('errorsData', $data);
        }

        $insert_client_data = array(
            'customer_name' => $request->clientName,
            'group_name' => $request->groupName,
            'address' => $request->clientAddress,
            'zone' => $request->clientZone,
            'district' => $request->clientDistrict,
            'division' => $request->clientDivision,
            'tin' => $request->clientTIN,
            'bin' => $request->clientBIN,
            'trade_license' => $request->clientTL,
            'contact_person' => $request->contactPerson,
            'contact_mobile' => $request->contactMobile,
            'contact_email' => $request->contactEmail,
            // 'created_by' => Auth()->user()->id
            'created_by' => 1
        );

        $customerId = Customer::create($insert_client_data);
        $customerId = $customerId->id;

        // for later
        // $domainName = URL::to('/');
        // $stoURL = $domainName . '/sales/';
        // $notifyUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
        //     INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
        //     INNER JOIN users ON users.id=user_permissions.user_id
        //     WHERE permissions.permission_code="leadAssign"');
        // foreach ($notifyUsersEmail as $email) {
        //     $assignEmail = $email->user_email;
        //     $assignName = $email->user_name;
        //     Mail::send([], [], function ($message) use ($assignEmail, $assignName, $stoURL) {
        //         $message->to($assignEmail, $assignName)->subject('Subaru Bangladesh - CRM Lead Assign Process');
        //         $message->from('info@subaru-bd.com', 'Subaru Bangladesh');
        //         $message->setBody('<h3>Greetings From SUBARU BANGLADESH!</h3><p>Dear ' . $assignName . ', you have a lead on lead stage for assigning process.<br><a href="' . $stoURL . '">CLICK HERE</a> to assign the lead.</p><p>Regards,<br>Subaru Bangladesh</p>', 'text/html');
        //     });
        // }


        $insert_lead_data = array(
            'lead_source' => $request->leadSource,
            'product_requirement' => $request->clientReq,
            'customer_id' => $customerId,
            'created_by' => 1
        );

        $leadId = Lead::create($insert_lead_data);
        $leadId = $leadId->id;

        $log_data = array(
            'lead_id' => $leadId,
            'log_stage' => 'Lead',
            'log_task' => 'New Lead Creation',
            'log_by' => 1,
            'log_next' => 'Pump Selection'
        );
        SalesLog::create($log_data);

        //Auth()->user()->id,
        return back()->with('success', 'Corporate Client Generation Success');
    }


    public function leadForm()
    {
        $userTag = Auth()->user()->assign_to;
        // $data['companyList'] = Customer::where(['assign_to'=>$userTag])->get();
        $data['companyList'] = Customer::where('assign_to', 'like', "%{$userTag}%")->get();
        $data['sourceList'] = LeadSource::where(['is_active' => 1])->get();
        return view('sales.leadForm', $data);
    }

    public function getSingleClientInfo($clientId)
    {
        $client = Customer::find($clientId);

        if ($client) {
            return response()->json($client);
        } else {
            return response()->json(['error' => 'Client not found'], 404);
        }
    }

    public function dealForm()
    {
        return view('sales.dealForm');
    }
}
