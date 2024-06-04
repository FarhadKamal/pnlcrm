<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Lead;
use App\Models\PumpChoice;
use App\Models\Requirements;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function viewQuotation($leadId)
    {
        $data['leadInfo'] = Lead::with('clientInfo:id,customer_name,contact_person,address,district')->find($leadId);
        $data['reqInfo'] = Requirements::where(['lead_id' => $leadId])->get();
        $data['pumpInfo'] = PumpChoice::with('productInfo:id,mat_name,phase,brand_name,hp,head,kw')->where('lead_id', $leadId)->get();
        $data['desgName'] = Designation::find(Auth()->user()->user_desg);
        $data['deptName'] = Department::find(Auth()->user()->user_dept);
        return view('sales.quotation2', $data);
    }
}
