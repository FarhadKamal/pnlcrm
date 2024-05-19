<?php

namespace App\Http\Controllers;

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
            $intendedUrl = session('url.intended', route('sales'));

            // If the intended URL is the login route, use the default sales route
            if ($intendedUrl == route('login')) {
                $intendedUrl = route('sales');
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
}
