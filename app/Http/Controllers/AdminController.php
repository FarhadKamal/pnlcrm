<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Permission;
use App\Models\SystemLocation;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function index()
    {
        $data['users'] = User::with('department:id,dept_name', 'designation:id,desg_name', 'location:id,loc_name')->get();
        $data['designations'] = Designation::get();
        $data['departments'] = Department::get();
        $data['locations'] = SystemLocation::get();
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
        if (isset($request->userTag)) {
            $insert_data['assign_to'] = $request->userTag;
        } else {
            $insert_data['assign_to'] = '';
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
        $data['userInfo'] = User::with('department:id,dept_name', 'designation:id,desg_name', 'location:id,loc_name')->find($userId);
        return view('admin.userInfo', $data);
    }

    public function userInformationEdit($userId)
    {
        $data['userInfo'] = User::with('department:id,dept_name', 'designation:id,desg_name', 'location:id,loc_name')->find($userId);
        $data['userEdit'] = true;
        $data['designations'] = Designation::get();
        $data['departments'] = Department::get();
        $data['locations'] = SystemLocation::get();
        return view('admin.userInfo', $data);
    }

    public function updateUserInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'userName' => 'required|max:255',
            'userEmail' => 'required|email',
            'userPhone' => 'numeric|nullable',
            'userDesg' => 'required|numeric',
            'userDept' => 'required|numeric',
            'userLoc' => 'required|numeric',
            'userSignature' => 'mimes:jpeg,jpg,png||max:5120'
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->all());
            // var_dump($validator->errors()->all());
        }

        $insert_data = array();

        $userInfo = User::find($request->userId);
        if ($userInfo->user_email != $request->userEmail) {
            // If email not match check existing email 
            $existingEmailCheck = User::where(['user_email' => $request->userEmail])->get();
            if (count($existingEmailCheck) > 0) {
                return back()->with('error', array('User Email Already Exist'));
            }
        }

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
        $insert_data['user_desg'] = $request->userDesg;
        $insert_data['user_dept'] = $request->userDept;
        $insert_data['user_location'] = $request->userLoc;

        User::where('id', $request->userId)->update($insert_data);
        return back()->with('successUserInfo', 'User Information Updated');
    }

    public function userMakeInactive($userId)
    {
        $userInfo = User::find($userId);
        $userInfo->is_active = 0;
        $userInfo->save();
        return back()->with('success', 'User Is In-Activated');
    }

    public function userMakeActive($userId)
    {
        $userInfo = User::find($userId);
        $userInfo->is_active = 1;
        $userInfo->save();
        return back()->with('success', 'User Is Activated');
    }

    //Permission Operation Start
    public function permissionList()
    {
        $data['permissions'] = Permission::get();

        return view('admin.permissions', $data);
    }

    public function storePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permName' => 'required',
            'permCode' => 'required|unique:permissions,permission_code',
            'permDesc' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->with('errors', $error);
        } else {
            $insert_data = array(
                'permission_name' => $request->permName,
                'permission_code' => $request->permCode,
                'permission_description' => $request->permDesc,
            );
            Permission::create($insert_data);
            return back()->with('success', 'New Permission Inserted');
        }
    }

    public function userPermissions($userId)
    {
        if (Auth()->user()->is_admin == 1) {
            $data['userInfo'] = User::with('department:id,dept_name', 'designation:id,desg_name', 'location:id,loc_name')->find($userId);
            $data['permissions'] = Permission::get();
            $data['userPermission'] = UserPermission::select('permission_id')->where(['user_id' => $userId])->get();
            $userPermittedIds = [];
            foreach ($data['userPermission'] as $item) {
                array_push($userPermittedIds, $item->permission_id);
            }
            $data['userPermittedIds'] = $userPermittedIds;
            return view('admin.userPermission', $data);
        } else {
            return back()->with('error', 'You are not authorized');
        }
    }

    public function storeUserPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return back()->with('error', 'User is Required');
        } else {
            UserPermission::where('user_id', $request->userId)->delete();
            if (count((array)$request->permissions) > 0) {
                foreach ($request->permissions as $item) {
                    $insert_data['user_id'] = $request->userId;
                    $insert_data['permission_id'] = $item;
                    UserPermission::create($insert_data);
                }
            } else {
                UserPermission::where('user_id', $request->userId)->delete();
            }
            return back()->with('success', 'User Permission Saved');
        }
    }
}
