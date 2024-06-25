<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            $data['leadStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
            $data['assignList'] = User::with('designation:id,desg_name', 'location:id,loc_name')->get();
            $data['leadButtonLabel'] = 'Assign';
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStageAll')) {
            $data['leadStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
            $data['assignList'] = User::with('designation:id,desg_name', 'location:id,loc_name')->get();
            $data['leadButtonLabel'] = 'Details';
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStage')) {
            $data['leadStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where(['current_stage' => 'LEAD', 'created_by' => Auth()->user()->id])->get();
            $data['leadButtonLabel'] = 'Details';
        }


        //Deal Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'dealStageAll')) {
            $data['dealStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DEAL')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'dealStage')) {
            $data['dealStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where(['current_stage' => 'DEAL'])->whereHas('clientInfo', function ($query) {
                $query->where('assign_to', 'like', '%' . Auth()->user()->assign_to . '%');
            })->get();
        }

        // QUOTATION Stage 
        if (Helper::permissionCheck(Auth()->user()->id, 'quotationStageAll')) {
            $data['quotationStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->get();
            foreach ($data['quotationStage'] as $item) {
                $quotationRef = DB::select("SELECT id, quotation_ref FROM quotations WHERE lead_id = $item->id ORDER BY id DESC LIMIT 1");
                if ($quotationRef) {
                    $item->quotationId = $quotationRef[0]->id;
                    $item->quotationRef = $quotationRef[0]->quotation_ref;
                }
            }
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'quotationStage')) {
            $data['quotationStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->whereHas('clientInfo', function ($query) {
                $query->where('assign_to', 'like', '%' . Auth()->user()->assign_to . '%');
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
            $data['bookingStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'BOOKING')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'bookingStage')) {
            $data['bookingStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'BOOKING')->whereHas('clientInfo', function ($query) {
                $query->where('assign_to', 'like', '%' . Auth()->user()->assign_to . '%');
            })->get();
        }


        // Delivery Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'deliveryStageAll')) {
            $data['deliveryStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DELIVERY')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'deliveryStage')) {
            $data['deliveryStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DELIVERY')->whereHas('clientInfo', function ($query) {
                $query->where('assign_to', 'like', '%' . Auth()->user()->assign_to . '%');
            })->get();
        }

        // WON Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'wonStageAll')) {
            $data['wonStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'WON')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'wonStage')) {
            $data['wonStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'WON')->whereHas('clientInfo', function ($query) {
                $query->where('assign_to', 'like', '%' . Auth()->user()->assign_to . '%');
            })->get();
        }

        // Lost Stage
        if (Helper::permissionCheck(Auth()->user()->id, 'lostStageAll')) {
            $data['lostStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LOST')->get();
        } elseif (Helper::permissionCheck(Auth()->user()->id, 'lostStage')) {
            $data['lostStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile,assign_to', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LOST')->whereHas('clientInfo', function ($query) {
                $query->where('assign_to', 'like', '%' . Auth()->user()->assign_to . '%');
            })->get();
        }

        return view('sales.dashboard', $data);
    }
}
