<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;

class LeadController extends Controller
{

    public function demo()
    {
        $data['jitu'] = Customer::orderBy('customer_name', 'asc')->get();
        return view('demo', $data);
    }


    public function customerForm()
    {
        // $data['divisionList'] = Fetch Division List
        // $data['districtList'] = Fetch District List 
        // $data['zoneList'] = Fetch Zone List 
        // $data['leadSource'] = Fetch Source List 
      
        return view('sales.customerForm');
    }
}
