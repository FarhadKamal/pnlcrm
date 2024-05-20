<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesLog;
use Illuminate\Http\Request;

class LeadController extends Controller
{

    public function demo()
    {
        $data['jitu'] = Customer::orderBy('customer_name', 'asc')->get();
        return view('demo', $data);
    }


    public function customerForm()
    {

        // $data['jitu'] = Customer::orderBy('customer_name', 'asc')->get();
        return view('customerForm', $data);

        // $data['divisionList'] = Fetch Division List
        // $data['districtList'] = Fetch District List 
        // $data['zoneList'] = Fetch Zone List 
        // $data['leadSource'] = Fetch Source List 
      
        return view('sales.customerForm');

    }

    public function storeCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerName' => 'required',
            'groupName' => 'required',
            'address' => 'required',
            'zone' => 'required',
            'district' => 'required',
            'division' => 'required',
            'contactPerson' => 'required',
            'contactMobile' => 'required',
            'contactEmail' => 'nullable|email'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            $data['customerName'] = $request->customerName;
            $data['groupName'] = $request->groupName;
            $data['address'] = $request->address;
            $data['zone'] = $request->zone;
            $data['district'] = $request->district;
            $data['division'] = $request->division;
            $data['tin'] = $request->tin;
            $data['bin'] = $request->bin;
            $data['trade_license'] = $request->trade_license;
            $data['contactPerson'] = $request->contactPerson;
            $data['contactMobile'] = $request->contactMobile;
            $data['contactEmail'] = $request->contactEmail;
            return back()->with('errorsData', $data);
        }

        $interested = $request->interested;


        $insert_data = array(
            'customer_name' => $request->leadName,
            'group_name' => $request->leadEmail,
            'address' => $request->leadPhone,
            'zone' => $request->leadAddress,
            'district' => $request->leadLocation,
            'division' => $request->leadCategory,
            'tin' => $request->leadSource,
            'bin' => $request->refName,
            'trade_license' => $request->leadSourceDetails,
            'contact_person' => $request->contact_person,
            'contact_mobile' => $request->contact_mobile,
            'contact_email' => $request->contact_mobile,
            'created_by' => Auth()->user()->id
        );

        $customerId = Customer::create($insert_data);
        $customerId = $customerId->id;

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

        $log_data = array(
            'lead_id' => $customerId,
            'log_stage' => 'Corporate Client',
            'log_task' => 'New Corporate Client Creation',
            'log_by' => Auth()->user()->id,
            'log_next' => 'Corporate Client Creation'
        );
        SalesLog::create($log_data);

        return back()->with('success', 'Corporate Client Generation Success');
    }

}
