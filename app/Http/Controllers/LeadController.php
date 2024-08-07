<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Items;
use App\Models\SalesLog;
use App\Models\Lead;
use App\Models\LeadDistrict;
use App\Models\LeadDivision;
use App\Models\LeadSource;

use App\Models\Requirements;

use App\Models\LeadZone;
use App\Models\PumpChoice;
use App\Models\SpareItems;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $data['divisionList'] = LeadDivision::get();
        $data['districtList'] = LeadDistrict::get();
        $data['zoneList'] = LeadZone::get();
        $data['leadSource'] = LeadSource::get();

        return view('sales.customerForm', $data);
    }

    public function storeCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clientName' => 'required',
            'groupName' => 'required',
            'clientAddress' => 'required',
            // 'clientZone' => 'required',
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
            // $data['clientZone'] = $request->clientZone;
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

        $distName = LeadDistrict::find($request->clientDistrict);
        $distName = $distName->dist_name;
        $divName = LeadDivision::find($request->clientDivision);
        $divName = $divName->div_name;

        $insert_client_data = array(
            'customer_name' => $request->clientName,
            'group_name' => $request->groupName,
            'address' => $request->clientAddress,
            // 'zone' => $request->clientZone,
            'zone' => 'N/A',
            'district' => $distName,
            'division' => $divName,
            'tin' => $request->clientTIN,
            'bin' => $request->clientBIN,
            'trade_license' => $request->clientTL,
            'contact_person' => $request->contactPerson,
            'contact_mobile' => $request->contactMobile,
            'contact_email' => $request->contactEmail,
            'created_by' => Auth()->user()->id
        );

        $customerId = Customer::create($insert_client_data);
        $customerId = $customerId->id;


        $insert_lead_data = array(
            'lead_source' => $request->leadSource,
            'product_requirement' => $request->clientReq,
            'customer_id' => $customerId,
            'lead_person' => $request->contactPerson,
            'lead_email' => $request->contactEmail,
            'lead_phone' => $request->contactMobile,
            'current_stage' => 'LEAD',
            'current_subStage' => 'ASSIGN',
            'created_by' =>  Auth()->user()->id
        );

        $leadId = Lead::create($insert_lead_data);
        $leadId = $leadId->id;

        $log_data = array(
            'lead_id' => $leadId,
            'log_stage' => 'Lead',
            'log_task' => 'New Lead Creation',
            'log_by' => Auth()->user()->id,
            'log_next' => 'Assign Sales Person'
        );
        SalesLog::create($log_data);

        //Auth()->user()->id,
        return back()->with('success', 'Corporate Client Generation Success');
    }

    function workLoadCheck()
    {
        $assignList = User::with('designation:id,desg_name', 'location:id,loc_name')->get();

        $html = '';
        foreach ($assignList as $item) {

            if (Helper::permissionCheck($item->id, 'salesPerson')) {
                // $workLoad = DB::select('SELECT COUNT(leads.id) AS count, leads.current_stage FROM leads INNER JOIN customers ON customers.id = leads.customer_id WHERE customers.assign_to LIKE "%' . $item->assign_to . '%"  AND leads.current_stage != "WON" AND leads.current_stage != "LOST" GROUP BY leads.current_stage');
                $workLoad = DB::select('SELECT COUNT(leads.id) AS count, leads.current_stage FROM leads INNER JOIN customers ON customers.id = leads.customer_id WHERE customers.assign_to =' . $item->assign_to . '  AND leads.current_stage != "WON" AND leads.current_stage != "LOST" GROUP BY leads.current_stage');
                $html .= '<tr>';
                $html .= '<td class="p-1">' . $item->user_name . '</td>';
                $html .= '<td class="p-1">' . $item['designation']->desg_name . '</td>';
                $html .= '<td class="p-1">' . $item['location']->loc_name . '</td>';
                $html .= '<td class="p-1">';
                foreach ($workLoad as $loads) {
                    $html .= '<small>' . $loads->current_stage . ' Satge: <span class="bg-info p-2 text-white">' . $loads->count . '</span></small>';
                }
                $html .= '</td>';
                $html .= '<td class="p-1"><input type="radio" name="assign_to" value="' . $item->assign_to . '" ><button type="submit" class="btn btn-sm btn-darkblue p-1 fs-06rem float-end">Assign</button></td>';
                $html .= '</tr>';
            }
        }
        return response()->json($html);
    }

    function assignLeadToSales(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadModal_leadId' => 'required|numeric',
            'assign_to' => 'required',
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $leadInfo = Lead::find($request->leadModal_leadId);
            $customerId = $leadInfo->customer_id;
            $leadInfo->current_stage = "DEAL";
            $leadInfo->current_subStage = "FORM";
            $leadInfo->save();
            $customerInfo = Customer::find($customerId);
            $customerInfo->assign_to = $request->assign_to;
            $customerInfo->save();

            return back()->with('success', 'Lead Assign Success');
        }
    }


    public function leadForm()
    {
        $userTag = Auth()->user()->assign_to;
        // $data['companyList'] = Customer::where(['assign_to'=>$userTag])->get();
        // $data['companyList'] = Customer::where('assign_to', 'like', "%{$userTag}%")->get();
        $data['companyList'] = Customer::where(['assign_to'=>$userTag])->get();
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


    public function storeLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clientId' => 'required|numeric',
            'contactPerson' => 'required',
            'contactMobile' => 'required',
            'contactEmail' => 'nullable|email',
            'leadSource' => 'required',
            'clientReq' => 'required'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            $data['clientId'] = $request->clientId;
            $data['contactPerson'] = $request->contactPerson;
            $data['contactMobile'] = $request->contactMobile;
            $data['contactEmail'] = $request->contactEmail;
            $data['leadSource'] = $request->leadSource;
            $data['clientReq'] = $request->clientReq;
            return back()->with('errorsData', $data);
        }
        $insert_lead_data = array(
            'customer_id' => $request->clientId,
            'created_by' => Auth()->user()->id,
            'lead_source' => $request->leadSource,
            'product_requirement' => $request->clientReq,
            'lead_person' => $request->contactPerson,
            'lead_email' => $request->contactEmail,
            'lead_phone' => $request->contactMobile,
            'current_stage' => 'DEAL',
            'current_subStage' => 'FORM'
        );
        $leadId = Lead::create($insert_lead_data);
        $leadId = $leadId->id;

        if (isset($request->infoPermChange)) {
            $customerInfo = Customer::find($request->clientId);
            $customerInfo->contact_person = $request->contactPerson;
            $customerInfo->contact_mobile = $request->contactMobile;
            $customerInfo->contact_email = $request->contactEmail;
            $customerInfo->save();
        }

        $log_data = array(
            'lead_id' => $leadId,
            'log_stage' => 'Lead',
            'log_task' => 'New Lead Creation',
            'log_by' => Auth()->user()->id,
            'log_next' => 'Pump Selection'
        );
        SalesLog::create($log_data);

        // return $this->dealForm($leadId)->with('success', 'Corporate Client Generation Success');
        //  back()->with('success', 'Corporate Client Generation Success');
        return redirect()->route('home');
    }

    public function dealForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['reqList'] = Requirements::where('lead_id', $leadId)->get();
        $data['selectedPumpList'] = PumpChoice::with('productInfo:id,mat_name,brand_name,hp,min_head,max_head')->where('lead_id', $leadId)->orderBy('id', 'ASC')->get();
        $data['allPumpHP'] = Items::distinct()->orderBy('hp', 'ASC')->get('hp');
        $data['allPumpPhase'] = Items::distinct()->orderBy('phase', 'ASC')->get('phase');
        $data['allPumpModel'] = Items::distinct()->orderBy('mat_name', 'ASC')->get('mat_name');
        $data['allSpareParts'] = SpareItems::distinct()->orderBy('mat_name', 'ASC')->get('mat_name');
        // $data['allPumpHead'] = Items::distinct()->orderBy('head', 'ASC')->get('head');

        return view('sales.dealForm', $data);
    }

    public function updateLeadEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'QleadId' => 'required|numeric',
            'lead_email' => 'required|email',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $leadInfo = Lead::find($request->QleadId);
            $leadInfo->lead_email = $request->lead_email;
            $leadInfo->save();
            return back()->with('success', 'Lead Email Updated');
        }
    }
}
