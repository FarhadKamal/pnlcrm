<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\BrandDiscount;
use App\Models\Lead;
use App\Models\PumpChoice;
use App\Models\Quotation;
use App\Models\SalesLog;
use App\Models\SalesTarget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function authMe(Request $request)
    {
        $this->validate($request, [
            'loginEmail' => 'required|email',
            'loginPassword' => 'required'
        ]);

        // Sign In 
        if (!Auth::attempt(['user_email' => $request->loginEmail, 'password' => $request->loginPassword])) {
            return back()->with('error', 'Invalid Credentials');
        } else {
            if (Auth()->user()->is_active != 1) {
                Auth::logout();
                return redirect()->route('/')->with('error', 'Account is not active. Please contact with administrator');
            }
            // return redirect()->route('sales');
            // Get the intended URL from the session or use a default
            $intendedUrl = session('url.intended', route('home'));

            // If the intended URL is the login route, use the default sales route
            if ($intendedUrl == route('login')) {
                $intendedUrl = route('home');
            }

            // Clear the intended URL from the session
            session()->forget('url.intended');

            // dd(exec('getmac'));

            // $log_data = array(
            //     'user_id' => Auth()->user()->id,
            //     'event_type' => 'LOGIN',
            //     'platform' => exec('getmac'),
            //     'browser' => $request->server('HTTP_USER_AGENT'),
            //     'ip_address' => $request->ip()
            // );
            // LoginLog::create($log_data);

            // Redirect the user to the intended URL
            return redirect($intendedUrl);
        }
    }

    public function logoutMe()
    {
        Auth::logout();
        return redirect()->route('/');
    }

    public function salesStage()
    {
        //Lead Stage 
        if (Helper::permissionCheck(Auth()->user()->id, 'leadAssign')) {
            $data['leadStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
            $data['assignList'] = User::with('designation:id,desg_name', 'location:id,loc_name')->get();
            $data['leadButtonLabel'] = 'Assign';
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStageAll')) {
            $data['leadStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
            $data['assignList'] = User::with('designation:id,desg_name', 'location:id,loc_name')->get();
            $data['leadButtonLabel'] = 'Details';
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStage')) {
            $data['leadStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where(['current_stage' => 'LEAD', 'created_by' => Auth()->user()->id])->get();
            $data['leadButtonLabel'] = 'Details';
        }


        //Deal Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'dealStageAll')) {
            $data['dealStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DEAL')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'dealStage')) {
            $data['dealStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where(['current_stage' => 'DEAL'])->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        // QUOTATION Stage 
        if (Helper::permissionCheck(Auth()->user()->id, 'quotationStageAll')) {
            $data['quotationStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->get();
            foreach ($data['quotationStage'] as $item) {
                $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $item->id ORDER BY id DESC LIMIT 1");
                if ($quotationRef) {
                    $item->quotationId = $quotationRef[0]->id;
                    $item->quotationRef = $quotationRef[0]->quotation_ref;
                }
            }
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'quotationStage')) {
            $data['quotationStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
            foreach ($data['quotationStage'] as $item) {
                $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $item->id ORDER BY id DESC LIMIT 1");
                if ($quotationRef) {
                    $item->quotationId = $quotationRef[0]->id;
                    $item->quotationRef = $quotationRef[0]->quotation_ref;
                }
            }
        }

        // Special Fetch For Management Approval 
        if (Helper::permissionCheck(Auth()->user()->id, 'dealTopApprove')) {
            // $data['quotationStage'] = Lead::orderBy('need_top_approval', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->get();
            $data['quotationStage'] = Lead::where('current_stage', 'QUOTATION')
                ->orderByRaw('CASE WHEN need_top_approval = 0 THEN 2 ELSE need_top_approval END ASC')
                ->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')
                ->where('current_stage', 'QUOTATION')
                ->get();

            foreach ($data['quotationStage'] as $item) {
                $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $item->id ORDER BY id DESC LIMIT 1");
                if ($quotationRef) {
                    $item->quotationId = $quotationRef[0]->id;
                    $item->quotationRef = $quotationRef[0]->quotation_ref;
                }
            }
        }


        // Booking Stage 
        if (Helper::permissionCheck(Auth()->user()->id, 'bookingStageAll')) {
            $data['bookingStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'BOOKING')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'bookingStage')) {
            $data['bookingStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'BOOKING')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'bookingStageTask')) {
            $taskStage = [];
            if (Helper::permissionCheck(Auth()->user()->id, 'sapIDCreation')) {
                array_push($taskStage, "SAPIDSET");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'sapCreditSet')) {
                array_push($taskStage, "CREDITSET");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'sapCreditSet')) {
                array_push($taskStage, "CREDITHOLD");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'verifyTransaction')) {
                array_push($taskStage, "TRANSACTION");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'accountsClearance')) {
                array_push($taskStage, "TRANSACTION");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'customerDocCheck')) {
                array_push($taskStage, "CHECKCUSDOC");
            }
            $data['bookingStage'] = Lead::where('current_stage', 'BOOKING')
                ->whereIn('current_subStage', $taskStage)
                ->orderBy('updated_at', 'DESC')
                ->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')
                ->get();
        }


        // Delivery Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'deliveryStageAll')) {
            $data['deliveryStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DELIVERY')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'deliveryStage')) {
            $data['deliveryStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DELIVERY')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        // WON Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'wonStageAll')) {
            $data['wonStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'WON')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'wonStage')) {
            $data['wonStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'WON')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        // Lost Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'lostStageAll')) {
            $data['lostStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LOST')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'lostStage')) {
            $data['lostStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LOST')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        return view('sales.dashboard', $data);
    }

    public function newDash()
    {
        //Lead Stage 
        if (Helper::permissionCheck(Auth()->user()->id, 'leadAssign')) {
            $data['leadStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,division,contact_person,contact_mobile,address,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
            $data['assignList'] = User::with('designation:id,desg_name', 'location:id,loc_name')->get();
            $data['leadButtonLabel'] = 'Assign';
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStageAll')) {
            $data['leadStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,division,contact_person,contact_mobile,address,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
            $data['assignList'] = User::with('designation:id,desg_name', 'location:id,loc_name')->get();
            $data['leadButtonLabel'] = 'Details';
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStage')) {
            $data['leadStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,division,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where(['current_stage' => 'LEAD', 'created_by' => Auth()->user()->id])->get();
            $data['leadButtonLabel'] = 'Details';
        }


        //Deal Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'dealStageAll')) {
            $data['dealStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DEAL')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'dealStage')) {
            $data['dealStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where(['current_stage' => 'DEAL'])->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        // QUOTATION Stage 
        if (Helper::permissionCheck(Auth()->user()->id, 'quotationStageAll')) {
            $data['quotationStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->get();
            foreach ($data['quotationStage'] as $item) {
                $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $item->id ORDER BY id DESC LIMIT 1");
                if ($quotationRef) {
                    $item->quotationId = $quotationRef[0]->id;
                    $item->quotationRef = $quotationRef[0]->quotation_ref;
                }
            }
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'quotationStage')) {
            $data['quotationStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
            foreach ($data['quotationStage'] as $item) {
                $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $item->id ORDER BY id DESC LIMIT 1");
                if ($quotationRef) {
                    $item->quotationId = $quotationRef[0]->id;
                    $item->quotationRef = $quotationRef[0]->quotation_ref;
                }
            }
        }

        // Booking Stage 
        if (Helper::permissionCheck(Auth()->user()->id, 'bookingStageAll')) {
            $data['bookingStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'BOOKING')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'bookingStage')) {
            $data['bookingStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'BOOKING')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'bookingStageTask')) {
            $taskStage = [];
            if (Helper::permissionCheck(Auth()->user()->id, 'sapIDCreation')) {
                array_push($taskStage, "SAPIDSET");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'sapCreditSet')) {
                array_push($taskStage, "CREDITSET");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'sapCreditSet')) {
                array_push($taskStage, "CREDITHOLD");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'verifyTransaction')) {
                array_push($taskStage, "TRANSACTION");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'accountsClearance')) {
                array_push($taskStage, "TRANSACTION");
            }
            if (Helper::permissionCheck(Auth()->user()->id, 'customerDocCheck')) {
                array_push($taskStage, "CHECKCUSDOC");
            }
            $data['bookingStage'] = Lead::where('current_stage', 'BOOKING')
                ->whereIn('current_subStage', $taskStage)
                ->orderBy('updated_at', 'DESC')
                ->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')
                ->get();
        }

        // Delivery Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'deliveryStageAll')) {
            $data['deliveryStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DELIVERY')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'deliveryStage')) {
            $data['deliveryStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DELIVERY')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        // WON Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'wonStageAll')) {
            $data['wonStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'WON')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'wonStage')) {
            $data['wonStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'WON')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        // Lost Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'lostStageAll')) {
            $data['lostStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LOST')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'lostStage')) {
            $data['lostStage'] = Lead::orderBy('updated_at', 'DESC')->with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LOST')->whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
        }

        if (isset($data['leadStage'])) {
            $data['encodedLeadStage'] = json_encode($data['leadStage']);
        } else {
            $data['encodedLeadStage'] = json_encode(['']);
        }

        if (isset($data['quotationStage'])) {
            $data['encodedQuotationStage'] = json_encode($data['quotationStage']);
        } else {
            $data['encodedQuotationStage'] = json_encode(['']);
        }
        if (isset($data['bookingStage'])) {
            $data['encodedBookingStage'] = json_encode($data['bookingStage']);
        } else {
            $data['encodedBookingStage'] = json_encode(['']);
        }
        if (isset($data['deliveryStage'])) {
            $data['encodedDeliveryStage'] = json_encode($data['deliveryStage']);
        } else {
            $data['encodedDeliveryStage'] = json_encode(['']);
        }

        return view('sales.dashboard2', $data);
    }

    public function lostForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        return view('sales.lostForm', $data);
    }

    public function storeLost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lostReason' => 'required',
            'lostDescription' => 'required',
            'lostLead' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->all());
        } else {
            $leadInfo = Lead::find($request->lostLead);
            $leadOldStage = $leadInfo->current_stage;
            $customerName = $leadInfo->clientInfo->customer_name;
            // if ($leadInfo->current_stage == 'DELIVERY' && $leadInfo->current_subStage == 'READY') {
            if ($leadInfo->sap_invoice > 0) {
                $invoiceId = $leadInfo->sap_invoice;
                $invoiceDate = $leadInfo->invoice_date;
                $invoiceByName = $leadInfo->invoiceBy->user_name;
                $domainName = URL::to('/');
                $leadURL = $domainName . '/detailsLog/' . $leadInfo->id;
                $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="sapInvoiceSet" AND users.is_active = 1');
                if ($SAPCreditUsersEmail) {
                    foreach ($SAPCreditUsersEmail as $email) {
                        $assignEmail = $email->user_email;
                        $assignName = $email->user_name;
                        Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $invoiceId, $invoiceDate, $invoiceByName, $leadURL) {
                            $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM SAP INVOICE CANCEL');
                            $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                            $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is lost. Please cancel the invoice no ' . $invoiceId . ' invoice date ' . $invoiceDate . ' genereted by ' . $invoiceByName . '.<br><a href="' . $leadURL . '">CLICK HERE</a> for details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                        });
                    }
                }
            }

            if ($leadInfo->payment_type == 'Cash') {
                $transactionInfo = Transaction::where(['lead_id' => $request->lostLead, 'is_verified' => 1])->get();
                if ($transactionInfo && count($transactionInfo) > 0) {
                    $subStage = 'RETURNCASH';
                    $domainName = URL::to('/');
                    $leadURL = $domainName . '/returnTransaction/' . $leadInfo->id;
                    $verifyTransactionUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="verifyTransaction" AND users.is_active = 1');
                    if ($verifyTransactionUsersEmail) {
                        foreach ($verifyTransactionUsersEmail as $email) {
                            $assignEmail = $email->user_email;
                            $assignName = $email->user_name;
                            Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $leadURL) {
                                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Return Transaction');
                                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is lost. Please return the transaction amount to the customer.<br><a href="' . $leadURL . '">CLICK HERE</a> for return transaction.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                            });
                        }
                    }
                } else {
                    $subStage = 'LOST';
                }
            } else {
                // notify credit setter for lost 
                $subStage = 'LOST';
                if ($leadInfo->accounts_clearance == 1) {
                    $creditLimit = $leadInfo->creditAmt;
                    $domainName = URL::to('/');
                    $leadURL = $domainName . '/detailsLog/' . $leadInfo->id;
                    $SAPCreditUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
                    INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
                    INNER JOIN users ON users.id=user_permissions.user_id
                    WHERE permissions.permission_code="sapCreditSet" AND users.is_active = 1');
                    if ($SAPCreditUsersEmail) {
                        foreach ($SAPCreditUsersEmail as $email) {
                            $assignEmail = $email->user_email;
                            $assignName = $email->user_name;
                            Mail::send([], [], function ($message) use ($assignEmail, $assignName, $customerName, $creditLimit, $leadURL) {
                                $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM CUSTOMER LOST');
                                $message->from('sales@pnlholdings.com', 'PNL Holdings Limited');
                                $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', the lead ' . $customerName . ' is lost. You Set ' . $creditLimit . ' amount of credit limit for that lead.<br><a href="' . $leadURL . '">CLICK HERE</a> for details.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                            });
                        }
                    }
                }
            }

            $leadInfo->current_stage = 'LOST';
            $leadInfo->current_subStage = $subStage;
            $leadInfo->is_lost = 1;
            $leadInfo->lost_reason = $request->lostReason;
            $leadInfo->lost_description = $request->lostDescription;
            $leadInfo->save();

            if ($subStage == 'RETURNCASH') {
                $logNext = 'Return Transaction';
            } else {
                $logNext = '';
            }

            $log_data = array(
                'lead_id' => $leadInfo->id,
                'log_stage' => $leadOldStage,
                'log_task' => 'Lead is lost',
                'log_by' => Auth()->user()->id,
                'log_next' => $logNext
            );
            SalesLog::create($log_data);
            return redirect()->route('home');
        }
    }

    public function salesLog($leadId)
    {
        $data['leadInfo'] = Lead::find($leadId);
        if (!$data['leadInfo']) {
            return back()->with('error', 'No Lead Found');
        }
        if ((Auth()->user()->assign_to != $data['leadInfo']->clientInfo->assign_to) && (!Helper::permissionCheck(Auth()->user()->id, 'salesLog'))) {
            return back()->with('error', 'You Are Not Authorized');
        }
        $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
        $data['quotationInfo'] = Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $leadId, 'is_accept' => 1])->get();
        $data['salesLog'] = SalesLog::where('lead_id', $leadId)->orderBy('id', 'DESC')->get();
        return view('sales.leadDetailsLog', $data);
    }


    public function myProfilePage()
    {
        $userId = Auth()->user()->id;
        $data['userInfo'] = User::with('designation:id,desg_name', 'department:id,dept_name', 'location:id,loc_name')->find($userId);
        return view('myProfile', $data);
    }

    public function myProfileEdit()
    {
        $userId = Auth()->user()->id;
        $data['userInfo'] = User::with('designation:id,desg_name', 'department:id,dept_name', 'location:id,loc_name')->find($userId);
        $data['userEdit'] = true;
        return view('myProfile', $data);
    }

    public function updateMyProfile(Request $request)
    {
        $userId = Auth()->user()->id;
        $userInfo = User::find($userId);
        if ($request->userEmail != $userInfo['user_email']) {
            $validator = Validator::make($request->all(), [
                'userEmail' => 'required|email|unique:users,user_email',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'userName' => 'required|max:255',
            'userPhone' => 'numeric|nullable',
            'userSignature' => 'mimes:jpeg,jpg,png||max:5120'
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->all());
            // var_dump($validator->errors()->all());
        }

        $insert_data = array();
        if (isset($request->userPassword)) {
            $userPassword = $request->userPassword;
            $userHashPassword = Hash::make($userPassword);
            $insert_data['password'] = $userHashPassword;
        }

        if (isset($request->userSignature)) {
            $signature = $request->file('userSignature');
            $newFileName = time() . "." . $signature->getClientOriginalExtension();
            $destinationPath = 'images/userSignature/';
            $signature->move($destinationPath, $newFileName);
            $insert_data['user_signature'] = $newFileName;
        }

        if (isset($request->userPhone)) {
            $insert_data['user_phone'] = $request->userPhone;
        } else {
            $insert_data['user_phone'] = '';
        }

        $insert_data['user_name'] = $request->userName;
        $insert_data['user_email'] = $request->userEmail;

        User::where('id', $userId)->update($insert_data);
        return back()->with('success', 'User Information Updated');
    }

    public function tutorialVisual()
    {
        return view('videoTutorial');
    }

    public function targetForm()
    {
        $data['currentYear'] = date('Y');
        $data['previousYear'] = $data['currentYear'] - 1;
        $data['futureYear'] = $data['currentYear'] + 1;
        $data['salesPersons'] = User::get();
        $data['brands'] = BrandDiscount::where(['is_active' => 1])->get();
        return view('sales.targetForm', $data);
    }

    public function targetStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'financialYear' => 'required',
            'totalTarget' => 'required|numeric',
            'q1Per' => 'required|numeric',
            'q2Per' => 'required|numeric',
            'q3Per' => 'required|numeric',
            'q4Per' => 'required|numeric',
            'julPer' => 'required|numeric',
            'augPer' => 'required|numeric',
            'sepPer' => 'required|numeric',
            'octPer' => 'required|numeric',
            'novPer' => 'required|numeric',
            'decPer' => 'required|numeric',
            'janPer' => 'required|numeric',
            'febPer' => 'required|numeric',
            'marPer' => 'required|numeric',
            'aprPer' => 'required|numeric',
            'mayPer' => 'required|numeric',
            'junPer' => 'required|numeric'

        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->all());
        } else {
            $userInfo = User::find($request->userId);
            $userCode = $userInfo->assign_to;

            $totalTarget = $request->totalTarget;
            $q1Per = $request->q1Per;
            $q2Per = $request->q2Per;
            $q3Per = $request->q3Per;
            $q4Per = $request->q4Per;

            $julPer = $request->julPer;
            $augPer = $request->augPer;
            $sepPer = $request->sepPer;
            $octPer = $request->octPer;
            $novPer = $request->novPer;
            $decPer = $request->decPer;
            $janPer = $request->janPer;
            $febPer = $request->febPer;
            $marPer = $request->marPer;
            $aprPer = $request->aprPer;
            $mayPer = $request->mayPer;
            $junPer = $request->junPer;

            $q1JulTotalAmount = $totalTarget * $request->julPer;
            $q1AugTotalAmount = $totalTarget * $request->augPer;
            $q1SepTotalAmount = $totalTarget * $request->sepPer;
            $q2OctTotalAmount = $totalTarget * $request->octPer;
            $q2NovTotalAmount = $totalTarget * $request->novPer;
            $q2DecTotalAmount = $totalTarget * $request->decPer;
            $q3JanTotalAmount = $totalTarget * $request->janPer;
            $q3FebTotalAmount = $totalTarget * $request->febPer;
            $q3MarTotalAmount = $totalTarget * $request->marPer;
            $q4AprTotalAmount = $totalTarget * $request->aprPer;
            $q4MayTotalAmount = $totalTarget * $request->mayPer;
            $q4JunTotalAmount = $totalTarget * $request->junPer;

            $brands = BrandDiscount::get();
            foreach ($brands as $brand) {
                $brandName = $brand->brand_name;
                $brandFieldNameQ1 = $brandName . 'PerQ1';
                $brandFieldNameQ2 = $brandName . 'PerQ2';
                $brandFieldNameQ3 = $brandName . 'PerQ3';
                $brandFieldNameQ4 = $brandName . 'PerQ4';

                // Quarter One Brand Target 
                if ($request->brandFieldNameQ1) {
                    $q1Amount = $totalTarget * ($q1Per / 100);
                    $q1JulAmount =  $totalTarget * ($julPer / 100);
                    $q1JulBrandAmount = $q1JulAmount * ($request->brandFieldNameQ1 / 100);
                    $q1AugAmount =  $totalTarget * ($augPer / 100);
                    $q1AugBrandAmount = $q1AugAmount * ($request->brandFieldNameQ1 / 100);
                    $q1SepAmount =  $totalTarget * ($sepPer / 100);
                    $q1SepBrandAmount = $q1SepAmount * ($request->brandFieldNameQ1 / 100);

                    $q1JulTotalAmount = $q1JulTotalAmount - $q1JulBrandAmount;
                    $q1AugTotalAmount = $q1AugTotalAmount - $q1AugBrandAmount;
                    $q1SepTotalAmount = $q1SepTotalAmount - $q1SepBrandAmount;
                    $totalTarget = $totalTarget - ($q1JulBrandAmount + $q1AugBrandAmount + $q1SepBrandAmount);
                } else {
                    $q1JulBrandAmount = 0;
                    $q1AugBrandAmount = 0;
                    $q1SepBrandAmount = 0;
                }

                if ($request->brandFieldNameQ2) {
                    $q2Amount = $totalTarget * ($q2Per / 100);
                    $q2OctAmount =  $totalTarget * ($octPer / 100);
                    $q2OctBrandAmount = $q2OctAmount * ($request->brandFieldNameq2 / 100);
                    $q2NovAmount =  $totalTarget * ($novPer / 100);
                    $q2NovBrandAmount = $q2NovAmount * ($request->brandFieldNameq2 / 100);
                    $q2DecAmount =  $totalTarget * ($decPer / 100);
                    $q2DecBrandAmount = $q2DecAmount * ($request->brandFieldNameq2 / 100);

                    $q2OctTotalAmount = $q2OctTotalAmount - $q2OctBrandAmount;
                    $q2NovTotalAmount = $q2NovTotalAmount - $q2NovBrandAmount;
                    $q2DecTotalAmount = $q2DecTotalAmount - $q2DecBrandAmount;
                    $totalTarget = $totalTarget - ($q2OctBrandAmount + $q2NovBrandAmount + $q2DecBrandAmount);
                } else {
                    $q2OctBrandAmount = 0;
                    $q2NovBrandAmount = 0;
                    $q2DecBrandAmount = 0;
                }

                if ($request->brandFieldNameQ3) {
                    $q3Amount = $totalTarget * ($q3Per / 100);
                    $q3JanAmount =  $totalTarget * ($janPer / 100);
                    $q3JanBrandAmount = $q3JanAmount * ($request->brandFieldNameq3 / 100);
                    $q3FebAmount =  $totalTarget * ($febPer / 100);
                    $q3FebBrandAmount = $q3FebAmount * ($request->brandFieldNameq3 / 100);
                    $q3MarAmount =  $totalTarget * ($marPer / 100);
                    $q3MarBrandAmount = $q3MarAmount * ($request->brandFieldNameq3 / 100);

                    $q3JanTotalAmount = $q3JanTotalAmount - $q3JanBrandAmount;
                    $q3FebTotalAmount = $q3FebTotalAmount - $q3FebBrandAmount;
                    $q3MarTotalAmount = $q3MarTotalAmount - $q3MarBrandAmount;
                    $totalTarget = $totalTarget - ($q3JanBrandAmount + $q3FebBrandAmount + $q3MarBrandAmount);
                } else {
                    $q3JanBrandAmount = 0;
                    $q3FebBrandAmount = 0;
                    $q3MarBrandAmount = 0;
                }

                if ($request->brandFieldNameQ4) {
                    $q4Amount = $totalTarget * ($q4Per / 100);
                    $q4AprAmount =  $totalTarget * ($aprPer / 100);
                    $q4AprBrandAmount = $q4AprAmount * ($request->brandFieldNameq4 / 100);
                    $q4MayAmount =  $totalTarget * ($mayPer / 100);
                    $q4MayBrandAmount = $q4MayAmount * ($request->brandFieldNameq4 / 100);
                    $q4JunAmount =  $totalTarget * ($junPer / 100);
                    $q4JunBrandAmount = $q4JunAmount * ($request->brandFieldNameq4 / 100);

                    $q4AprTotalAmount = $q4AprTotalAmount -  $q4AprBrandAmount;
                    $q4MayTotalAmount = $q4MayTotalAmount - $q4MayBrandAmount;
                    $q4JunTotalAmount = $q4JunTotalAmount - $q4JunBrandAmount;
                    $totalTarget = $totalTarget - ($q4AprBrandAmount + $q4MayBrandAmount + $q4JunBrandAmount);
                } else {
                    $q4AprBrandAmount = 0;
                    $q4MayBrandAmount = 0;
                    $q4JunBrandAmount = 0;
                }

                $targetData = array(
                    'financial_year' => $request->financialYear,
                    'user_id' => $request->userId,
                    'bd_code' => $userCode,
                    'brand_name' => $brandName,
                    'july' => $q1JulBrandAmount,
                    'august' => $q1AugBrandAmount,
                    'september' => $q1SepBrandAmount,
                    'october' => $q2OctBrandAmount,
                    'november' => $q2NovBrandAmount,
                    'december' => $q2DecBrandAmount,
                    'january' => $q3JanBrandAmount,
                    'february' => $q3FebBrandAmount,
                    'march' => $q3MarBrandAmount,
                    'april' => $q4AprBrandAmount,
                    'may' => $q4MayBrandAmount,
                    'june' => $q4JunBrandAmount
                );
                SalesTarget::create($targetData);
            }

            if ($totalTarget > 0) {
                $targetData = array(
                    'financial_year' => $request->financialYear,
                    'user_id' => $request->userId,
                    'bd_code' => $userCode,
                    'brand_name' => '',
                    'july' => $q1JulTotalAmount,
                    'august' => $q1AugTotalAmount,
                    'september' => $q1SepTotalAmount,
                    'october' => $q2OctTotalAmount,
                    'november' => $q2NovTotalAmount,
                    'december' => $q2DecTotalAmount,
                    'january' => $q3JanTotalAmount,
                    'february' => $q3FebTotalAmount,
                    'march' => $q3MarTotalAmount,
                    'april' => $q4AprTotalAmount,
                    'may' => $q4MayTotalAmount,
                    'june' => $q4JunTotalAmount
                );
                SalesTarget::create($targetData);
            }
        }
    }
}
