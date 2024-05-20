<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function index()
    {
        // $data['users'] = User::with('department:id,dept_name', 'designation:id,desg_name', 'location:id,location_name')->get();
        $data['users'] = User::get();
        // $data['designations'] = Designation::get();
        // $data['departments'] = Department::get();
        // $data['locations'] = SystemLocation::get();
        return view('admin.users', $data);
    }

    public function storeUser(Request $request)
    {

        $this->validate($request, [
            'userName' => 'required|max:255',
            'userEmail' => 'required|email',
            'userPhone' => 'numeric|nullable',
            'userDesg' => 'required|numeric',
            'userDept' => 'required|numeric',
            'userLoc' => 'required|numeric',
            'userSignature' => 'mimes:jpeg,jpg,png|max:5120'
        ]);
        $defaultPassword = 123;
        $defaultHashPassword = Hash::make($defaultPassword);

        $insert_data = array(
            'user_name' => $request->userName,
            'user_email' => $request->userEmail,
            'password' => $defaultHashPassword,
            'user_desg' => $request->userDesg,
            'user_dept' => $request->userDept,
            'user_location' => $request->userLoc,
        );

        if (isset($request->userPhone)) {
            $insert_data['user_phone'] = $request->userPhone;
        } else {
            $insert_data['user_phone'] = '';
        }

        if (isset($request->userSignature)) {
            $signature = $request->file('userSignature');
            $newFileName = time() . "." . $signature->getClientOriginalExtension();
            $destinationPath = 'images/userSignature/';
            $signature->move($destinationPath, $newFileName);
            $insert_data['user_signature'] = $newFileName;
        }

        User::create($insert_data);
        return back()->with('success', 'New User Created');
    }

    public function userInformation($userId)
    {
        // $data['userInfo'] = User::with('department:id,dept_name', 'designation:id,desg_name', 'location:id,location_name')->find($userId);
        $data['userInfo'] = User::find($userId);
        return view('admin.userInfo', $data);
    }
}
