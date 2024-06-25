<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\LeadDistrict;
use App\Models\LeadDivision;
use App\Models\LeadSource;
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

    // Designation Operation Start 
    public function designationList()
    {
        $designations = Designation::get();
        return view('admin.designations', compact('designations'));
    }

    public function storeDesignation(Request $request)
    {
        $this->validate($request, [
            'desgName' => 'required|max:255',
            'desgStatus' => 'required|numeric'
        ]);

        $insert_data = array(
            'desg_name' => $request->desgName,
            'is_active' => $request->desgStatus
        );

        Designation::create($insert_data);

        return back()->with('success', 'Designation is created');
    }

    public function editDesignation(Request $itemId)
    {
        $itemId = $itemId->itemId;
        $desgDeatils = Designation::find($itemId);
        return $desgDeatils;
    }

    public function updateDesignation(Request $request)
    {
        $this->validate($request, [
            'desgEditName' => 'required|max:255',
            'desgEditStatus' => 'required|numeric'
        ]);

        $update_data = array(
            'desg_name' => $request->desgEditName,
            'is_active' => $request->desgEditStatus
        );

        Designation::where('id', $request->itemId)->update($update_data);

        return back()->with('success', 'Designation is updated');
    }
    // Designation Operation End

    // Department Operation Start 
    public function departmentList()
    {
        $departments = Department::get();
        return view('admin.departments', compact('departments'));
    }

    public function storedepartment(Request $request)
    {
        $this->validate($request, [
            'deptName' => 'required|max:255',
            'deptStatus' => 'required|numeric'
        ]);

        $insert_data = array(
            'dept_name' => $request->deptName,
            'is_active' => $request->deptStatus
        );

        Department::create($insert_data);

        return back()->with('success', 'Department is created');
    }

    public function editdepartment(Request $itemId)
    {
        $itemId = $itemId->itemId;
        $deptDeatils = Department::find($itemId);
        return $deptDeatils;
    }

    public function updatedepartment(Request $request)
    {
        $this->validate($request, [
            'deptEditName' => 'required|max:255',
            'deptEditStatus' => 'required|numeric'
        ]);

        $update_data = array(
            'dept_name' => $request->deptEditName,
            'is_active' => $request->deptEditStatus
        );

        Department::where('id', $request->itemId)->update($update_data);

        return back()->with('success', 'Department is updated');
    }
    // Department Operation End

    // System Location Operation Start. For User and Inventory location
    public function locationList()
    {
        $locations = SystemLocation::orderBy('loc_name', 'asc')->get();
        return view('admin.locations', compact('locations'));
    }

    public function storelocation(Request $request)
    {
        $this->validate($request, [
            'locName' => 'required|max:255',
            'locStatus' => 'required|numeric'
        ]);

        $insert_data = array(
            'loc_name' => $request->locName,
            'is_active' => $request->locStatus
        );

        SystemLocation::create($insert_data);

        return back()->with('success', 'Location is created');
    }

    public function editlocation(Request $itemId)
    {
        $itemId = $itemId->itemId;
        $locDeatils = SystemLocation::find($itemId);
        return $locDeatils;
    }

    public function updatelocation(Request $request)
    {
        $this->validate($request, [
            'locEditName' => 'required|max:255',
            'locEditStatus' => 'required|numeric'
        ]);

        $update_data = array(
            'loc_name' => $request->locEditName,
            'is_active' => $request->locEditStatus
        );

        SystemLocation::where('id', $request->itemId)->update($update_data);

        return back()->with('success', 'Location is updated');
    }
    // Location Operation End

    // District Operation Start 
    public function districtList()
    {
        $districts = LeadDistrict::orderBy('dist_name', 'asc')->get();
        return view('admin.districts', compact('districts'));
    }
    public function storeDistrict(Request $request)
    {
        $this->validate($request, [
            'distName' => 'required|max:255',
            'distStatus' => 'required|numeric'
        ]);

        $insert_data = array(
            'dist_name' => $request->distName,
            'is_active' => $request->distStatus
        );

        LeadDistrict::create($insert_data);

        return back()->with('success', 'District is created');
    }

    public function editDistrict(Request $itemId)
    {
        $itemId = $itemId->itemId;
        $distDeatils = LeadDistrict::find($itemId);
        return $distDeatils;
    }

    public function updateDistrict(Request $request)
    {
        $this->validate($request, [
            'distEditName' => 'required|max:255',
            'distEditStatus' => 'required|numeric'
        ]);

        $update_data = array(
            'dist_name' => $request->distEditName,
            'is_active' => $request->distEditStatus
        );

        LeadDistrict::where('id', $request->itemId)->update($update_data);

        return back()->with('success', 'District is updated');
    }
    // District Operation End 

     // Division Operation Start 
     public function divisionList()
     {
         $divisions = LeadDivision::orderBy('div_name', 'asc')->get();
         return view('admin.divisions', compact('divisions'));
     }

     public function storeDivision(Request $request)
    {
        $this->validate($request, [
            'divName' => 'required|max:255',
            'divStatus' => 'required|numeric'
        ]);

        $insert_data = array(
            'div_name' => $request->divName,
            'is_active' => $request->divStatus
        );

        LeadDivision::create($insert_data);

        return back()->with('success', 'Division is created');
    }

    public function editDivision(Request $itemId)
    {
        $itemId = $itemId->itemId;
        $divDeatils = LeadDivision::find($itemId);
        return $divDeatils;
    }
    
    public function updateDivision(Request $request)
    {
        $this->validate($request, [
            'divEditName' => 'required|max:255',
            'divEditStatus' => 'required|numeric'
        ]);

        $update_data = array(
            'div_name' => $request->divEditName,
            'is_active' => $request->divEditStatus
        );

        LeadDivision::where('id', $request->itemId)->update($update_data);

        return back()->with('success', 'Division is updated');
    }
    // Division Operation End 

     // Lead Source Operation Start 
     public function leadSourceList()
     {
         $leadSources = LeadSource::get();
         return view('admin.leadSources', compact('leadSources'));
     }
     public function storeLeadSource(Request $request)
     {
         $this->validate($request, [
             'leadSourceName' => 'required|max:255',
             'leadSourceStatus' => 'required|numeric'
         ]);
 
         $insert_data = array(
             'source_name' => $request->leadSourceName,
             'is_active' => $request->leadSourceStatus
         );
 
         LeadSource::create($insert_data);
 
         return back()->with('success', 'Lead Source is created');
     }
     public function editLeadSource(Request $itemId)
     {
         $itemId = $itemId->itemId;
         $leadSourceDeatils = LeadSource::find($itemId);
         return $leadSourceDeatils;
     }
     public function updateLeadSource(Request $request)
     {
         $this->validate($request, [
             'leadSourceEditName' => 'required|max:255',
             'leadSourceEditStatus' => 'required|numeric'
         ]);
 
         $update_data = array(
             'source_name' => $request->leadSourceEditName,
             'is_active' => $request->leadSourceEditStatus
         );
 
         LeadSource::where('id', $request->itemId)->update($update_data);
 
         return back()->with('success', 'Lead Source is updated');
     }
     // Lead Source Operation End 
}
