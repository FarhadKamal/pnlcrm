<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\BrandDiscount;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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
            'contactEmail' => 'required|email',
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
            $data['clientTIN'] = $request->customerTIN;
            $data['clientBIN'] = $request->customerBIN;
            $data['clientTL'] = $request->customerTL;
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

        if ($request->file('customerTIN')) {
            $customerTIN = $request->file('customerTIN');
            $customerTINName = "TIN" . time() . "." . $customerTIN->getClientOriginalExtension();
            $destinationPath = 'customerDocument/';
            $customerTIN->move($destinationPath, $customerTINName);
        } else {
            $customerTINName = '';
        }

        if ($request->file('customerBIN')) {
            $customerBIN = $request->file('customerBIN');
            $customerBINName = "BIN" . time() . "." . $customerBIN->getClientOriginalExtension();
            $destinationPath = 'customerDocument/';
            $customerBIN->move($destinationPath, $customerBINName);
        } else {
            $customerBINName = '';
        }
        if ($request->file('customerTL')) {
            $customerTL = $request->file('customerTL');
            $customerTLName = "TL" . time() . "." . $customerTL->getClientOriginalExtension();
            $destinationPath = 'customerDocument/';
            $customerTL->move($destinationPath, $customerTLName);
        } else {
            $customerTLName = '';
        }

        $insert_client_data = array(
            'customer_name' => $request->clientName,
            'group_name' => $request->groupName,
            'address' => $request->clientAddress,
            // 'zone' => $request->clientZone,
            'zone' => 'N/A',
            'district' => $distName,
            'division' => $divName,
            'tin' => $customerTINName,
            'bin' => $customerBINName,
            'trade_license' => $customerTLName,
            'contact_person' => $request->contactPerson,
            'contact_mobile' => $request->contactMobile,
            'contact_email' => $request->contactEmail,
            'assign_to' => Auth()->user()->assign_to,
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
            // 'current_subStage' => 'ASSIGN',
            'current_subStage' => 'CHECKCUSDOC',
            'created_by' =>  Auth()->user()->id
        );

        $leadId = Lead::create($insert_lead_data);
        $leadId = $leadId->id;

        $customerName = $request->clientName;
        $createdByName = Auth()->user()->user_name;
        $domainName = URL::to('/');
        $leadURL = $domainName . '/customerDocCheck/' . $leadId;
        $SAPUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="customerDocCheck" AND users.is_active = 1');
        if ($SAPUsersEmail) {
            foreach ($SAPUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL, $createdByName) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM New Customer Document Check');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a new lead ' . $customerName . ' is waiting for document checking process.<br>Submitted By: ' . $createdByName . '<br><a href="' . $leadURL . '">CLICK HERE</a> to complete document check process.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }
        }

        $log_data = array(
            'lead_id' => $leadId,
            'log_stage' => 'Lead',
            'log_task' => 'New Customer Creation',
            'log_by' => Auth()->user()->id,
            'log_next' => 'Customer Document Check'
        );
        SalesLog::create($log_data);

        //Auth()->user()->id,
        return back()->with('success', 'Corporate Client Generation Success');
    }

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
            $leadInfo->current_subStage = 'ASSIGN';
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();

            $leadAssignUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="leadAssign" AND users.is_active = 1');
            if ($leadAssignUsersEmail) {
                foreach ($leadAssignUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM New Customer Approval');
                        $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<p>Dear Sir, a new customer ' . $customerName . ' is waiting for new customer approval Process. Please approve it from the LEAD stage.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
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
                'log_stage' => 'LEAD',
                'log_task' => 'New customer document checked. ' . $remark,
                'log_by' => Auth()->user()->id,
                'log_next' => 'New Customer Approval'
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
            $leadInfo->current_subStage = 'EDIT';
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();

            $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $request->leadId . '');
            $domainName = URL::to('/');
            $leadURL = $domainName . '/customerInfo/' . $request->leadId;
            foreach ($assignedUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $returnRemark, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Return Customer Document Check');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is return to you from document check process.<br>Return Remarks: ' . $returnRemark . '.<br><a href="' . $leadURL . '">CLICK HERE</a> for update and resubmit.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $request->leadId,
                'log_stage' => 'LEAD',
                'log_task' => 'New customer document checked Failed. Remark:' . $returnRemark,
                'log_by' => Auth()->user()->id,
                'log_next' => 'Customer Re-Submission'
            );
            SalesLog::create($log_data);
            return redirect()->route('dashboard');
        }
    }

    public function approveCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadApproveModal_leadId' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $leadInfo = Lead::find($request->leadApproveModal_leadId);
            $leadInfo->current_subStage = "SAPIDSET";
            $customerName = $leadInfo->clientInfo->customer_name;
            $leadInfo->save();

            $sapSetUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapIDCreation" AND users.is_active = 1');

            foreach ($sapSetUsersEmail as $email) {
                $assignEmail = $email->user_email;
                $assignName = $email->user_name;
                $domainName = URL::to('/');
                $leadURL = $domainName . '/newSapForm/' . $request->leadApproveModal_leadId;
                Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                    $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP ID SET');
                    $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                    $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a new lead ' . $customerName . ' is waiting for new SAP ID generation.<br><a href="' . $leadURL . '">CLICK HERE</a> to complete SAP ID creation process.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                });
            }

            $log_data = array(
                'lead_id' => $request->leadApproveModal_leadId,
                'log_stage' => 'LEAD',
                'log_task' => 'New Customer Approved',
                'log_by' => Auth()->user()->id,
                'log_next' => 'SAP ID Set'
            );
            SalesLog::create($log_data);
            return back()->with('success', 'Customer Approval Success');
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
            $leadInfo->current_stage = 'DEAL';
            $leadInfo->current_subStage = 'FORM';
            $customerName = $leadInfo->clientInfo->customer_name;
            $assignEmail = $customerInfo->assignTo->user_email;
            $assignName = $customerInfo->assignTo->user_name;
            // $assignedUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM leads INNER JOIN customers ON customers.id = leads.customer_id INNER JOIN users ON users.assign_to=customers.assign_to WHERE leads.id=' . $leadInfo->id . '');
            // foreach ($assignedUsersEmail as $email) {
            //     $assignEmail = $email->user_email;
            //     $assignName = $email->user_name;
            $domainName = URL::to('/');
            $leadURL = $domainName . '/dealPage/' . $leadInfo->id;
            Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP ID SET');
                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a new SAP ID set for the lead ' . $customerName . '. Waiting for deal submission.<br><a href="' . $leadURL . '">CLICK HERE</a> to visit Deal Form.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
            });
            // }
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => 'LEAD',
                'log_task' => 'New SAP ID: ' . $request->newSAP . ' set',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Deal Submission'
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    function workLoadCheck()
    {
        $assignList = User::with('designation:id,desg_name', 'location:id,loc_name')->get();

        $html = '';
        foreach ($assignList as $item) {

            if (Helper::permissionCheck($item->id, 'salesPerson')) {
                // $workLoad = DB::select('SELECT COUNT(leads.id) AS count, leads.current_stage FROM leads INNER JOIN customers ON customers.id = leads.customer_id WHERE customers.assign_to LIKE "%' . $item->assign_to . '%"  AND leads.current_stage != "WON" AND leads.current_stage != "LOST" GROUP BY leads.current_stage');
                $workLoad = DB::select('SELECT COUNT(leads.id) AS count, leads.current_stage FROM leads INNER JOIN customers ON customers.id = leads.customer_id WHERE customers.assign_to ="' . $item->assign_to . '"  AND leads.current_stage != "WON" AND leads.current_stage != "LOST" GROUP BY leads.current_stage');
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

            $log_data = array(
                'lead_id' => $request->leadModal_leadId,
                'log_stage' => 'LEAD',
                'log_task' => 'Lead Assigned',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Pump Selection'
            );
            SalesLog::create($log_data);

            return back()->with('success', 'Lead Assign Success');
        }
    }

    public function leadForm()
    {
        $userTag = Auth()->user()->assign_to;
        // $data['companyList'] = Customer::where(['assign_to'=>$userTag])->get();
        // $data['companyList'] = Customer::where('assign_to', 'like', "%{$userTag}%")->get();
        $data['companyList'] = Customer::where(['assign_to' => $userTag])->get();
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
        $data['selectedPumpList'] = PumpChoice::where('lead_id', $leadId)->orderBy('id', 'ASC')->get();
        $data['allPumpHP'] = Items::distinct()->orderBy('hp', 'ASC')->get('hp');
        $data['allPumpPhase'] = Items::distinct()->orderBy('phase', 'ASC')->get('phase');
        $data['allPumpModel'] = Items::distinct()->orderBy('mat_name', 'ASC')->get();
        $data['allSpareParts'] = SpareItems::distinct()->orderBy('mat_name', 'ASC')->get();
        $data['allBrand'] = BrandDiscount::where(['is_active' => 1])->get();

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

    public function reDealing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|numeric',
            'reDealRemark' => 'required',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with($data);
        } else {
            $leadId = $request->leadId;
            $leadInfo = Lead::find($leadId);
            $logStage = $leadInfo->current_stage;
            $logSubStage = $leadInfo->current_subStage;
            $leadInfo->current_stage = 'DEAL';
            $leadInfo->current_subStage = 'FORM';
            $leadInfo->accounts_clearance = 0;
            $leadInfo->is_outstanding = 0;
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $leadId,
                'log_stage' => $logStage,
                'log_task' => 'Back to deal stage. Remark: ' . $request->reDealRemark,
                'log_by' => Auth()->user()->id,
                'log_next' => 'Pump Selection'
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function customerInfoIndividual($leadId)
    {
        $data['leadInfo'] = Lead::find($leadId);
        $data['customerInfo'] = $data['leadInfo']->clientInfo;
        $data['divisionList'] = LeadDivision::orderBy('div_name', 'ASC')->get();
        $data['districtList'] = LeadDistrict::orderBy('dist_name', 'ASC')->get();
        return view('customerInfo', $data);
    }

    public function updateCustomerInfoIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerId' => 'required',
            'clientName' => 'required',
            'groupName' => 'required',
            'clientAddress' => 'required',
            'clientDistrict' => 'required',
            'clientDivision' => 'required',
            'contactPerson' => 'required',
            'contactMobile' => 'required',
            'contactEmail' => 'nullable|email',
            'contactPerson' => 'required'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $distName = LeadDistrict::find($request->clientDistrict);
            $distName = $distName->dist_name;
            $divName = LeadDivision::find($request->clientDivision);
            $divName = $divName->div_name;

            $customerInfo = Customer::find($request->customerId);

            $customerInfo->customer_name = $request->clientName;
            $customerInfo->group_name = $request->groupName;
            $customerInfo->address = $request->clientAddress;
            $customerInfo->district = $distName;
            $customerInfo->division = $divName;
            $customerInfo->contact_person = $request->contactPerson;
            $customerInfo->contact_mobile = $request->contactMobile;
            $customerInfo->contact_email = $request->contactEmail;

            if ($request->file('customerTIN')) {
                $customerTIN = $request->file('customerTIN');
                $customerTINName = "TIN" . time() . "." . $customerTIN->getClientOriginalExtension();
                $destinationPath = 'customerDocument/';
                $customerTIN->move($destinationPath, $customerTINName);
                $customerInfo->tin = $customerTINName;
            }

            if ($request->file('customerBIN')) {
                $customerBIN = $request->file('customerBIN');
                $customerBINName = "BIN" . time() . "." . $customerBIN->getClientOriginalExtension();
                $destinationPath = 'customerDocument/';
                $customerBIN->move($destinationPath, $customerBINName);
                $customerInfo->bin = $customerBINName;
            }
            if ($request->file('customerTL')) {
                $customerTL = $request->file('customerTL');
                $customerTLName = "TL" . time() . "." . $customerTL->getClientOriginalExtension();
                $destinationPath = 'customerDocument/';
                $customerTL->move($destinationPath, $customerTLName);
                $customerInfo->trade_license = $customerTLName;
            }

            $customerInfo->save();

            $leadInfo = Lead::find($request->leadId);
            $leadInfo->current_subStage = 'CHECKCUSDOC';
            $leadInfo->save();

            $log_data = array(
                'lead_id' => $request->leadId,
                'log_stage' => 'Lead',
                'log_task' => 'New Customer Info Update',
                'log_by' => Auth()->user()->id,
                'log_next' => 'Customer Document Check'
            );
            SalesLog::create($log_data);

            return redirect()->route('home');
        }
    }
}
