<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PumpChoice;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function transactionForm($leadId)
    {
        $data['leadId'] = $leadId;
        $data['leadInfo'] = Lead::find($leadId);
        $data['pumpInfo'] = PumpChoice::where(['lead_id'=>$leadId])->get();
        return view('sales.transactionForm', $data);
    }
}
