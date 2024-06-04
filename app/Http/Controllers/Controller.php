<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // if (Helper::permissionCheck(Auth()->user()->id, 'leadAssign')) {
        $data['leadStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
        $data['assignList'] = User::with('designation:id,desg_name', 'location:id,loc_name')->get();
        $data['leadButtonLabel'] = 'Assign';
        // } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStageAll')) {
        //     $data['leadStage'] = Lead::with('location:id,lead_location', 'source:id,source_name', 'category:id,category_name', 'createdBy:id,user_name')->where('current_stage', 'LEAD')->get();
        //     $data['assignList'] = User::with('designation:id,desg_name', 'location:id,location_name')->get();
        //     $data['leadButtonLabel'] = 'Details';
        // } elseif (Helper::permissionCheck(Auth()->user()->id, 'leadStage')) {
        //     $data['leadStage'] = Lead::with('location:id,lead_location', 'source:id,source_name', 'category:id,category_name', 'createdBy:id,user_name')->where(['current_stage' => 'LEAD', 'created_by' => Auth()->user()->id])->get();
        //     $data['leadButtonLabel'] = 'Details';
        // }


        //Deal Stage
        // if (Helper::permissionCheck(Auth()->user()->id, 'dealApprove')) {
        $data['dealStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'DEAL')->get();
        // } elseif (Helper::permissionCheck(Auth()->user()->id, 'dealStageAll')) {

        //     $data['dealStage'] = Lead::with('location:id,lead_location', 'source:id,source_name', 'category:id,category_name', 'createdBy:id,user_name', 'assignTo:id,user_name')->where(['current_stage' => 'DEAL'])->get();
        // } elseif (Helper::permissionCheck(Auth()->user()->id, 'dealStage')) {
        //     $data['dealStage'] = Lead::with('location:id,lead_location', 'source:id,source_name', 'category:id,category_name', 'createdBy:id,user_name', 'assignTo:id,user_name')->where(['current_stage' => 'DEAL', 'assign_to' => Auth()->user()->id])->get();
        // }

        // QUOTATION Stage 
        $data['quotationStage'] = Lead::with('clientInfo:id,customer_name,group_name,district,contact_person,contact_mobile', 'source:id,source_name', 'createdBy:id,user_name')->where('current_stage', 'QUOTATION')->get();

        return view('sales.dashboard', $data);
    }
}
