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
        $data['pumpInfo'] = PumpChoice::where('lead_id', $leadId)->get();
        $data['desgName'] = Designation::find(Auth()->user()->user_desg);
        $data['deptName'] = Department::find(Auth()->user()->user_dept);
        return view('sales.quotation', $data);
    }

    public function preQuotationApprove(Request $request){
        $lead_id=$request->lead_id;
        $approvePumpChoice =$request->approvePumpChoice;
        $set_discount =$request->set_discount;

        foreach ($approvePumpChoice as $key => $id) {
            $pumpInfo = PumpChoice::find($id);
            $pumpInfo->discount_percentage = $set_discount[$key];
            $pumpInfo->discount_price = $set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01;
            $pumpInfo->net_price =(($pumpInfo->qty  * $pumpInfo->unit_price) - ($set_discount[$key] * $pumpInfo->qty  * $pumpInfo->unit_price * 0.01));
            $pumpInfo->save();
        }

        $need_discount_approval = 1;
        $need_top_approval = 0;
        $need_credit_approval=0;
        $current_subStage='SUBMIT';

        if($request->credit_approved == 1)
        $need_credit_approval = 2;
        else $need_credit_approval = 0;

        $choiceInfo = PumpChoice::where(['lead_id' => $lead_id])->get();

        foreach ($choiceInfo as $row) {
            $proposed_discount = $row->discount_percentage;
            $trade_discount = $row->productInfo->TradDiscontInfo->trade_discount;

            if ($proposed_discount > $trade_discount)
                $need_discount_approval = 2;

            if ($proposed_discount > ($trade_discount + 3))
                {
                    $need_top_approval = 1;
                    $current_subStage='MANAGEMENT';
                }
        }

        $leadInfo = Lead::find($lead_id);
        $leadInfo->need_credit_approval = $need_credit_approval;
        $leadInfo->need_discount_approval = $need_discount_approval;
        $leadInfo->need_top_approval = $need_top_approval;
        $leadInfo->current_subStage =$current_subStage;
        $leadInfo->save();
        return redirect()->route('home');
    }

    public function topQuotationApprove(Request $request){

    }
}
