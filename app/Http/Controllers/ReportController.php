<?php

namespace App\Http\Controllers;

use App\Models\BrandDiscount;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function reportListPage()
    {
        return view('reports.reportList');
    }

    public function discountReport()
    {
        $data['salesPersons'] = User::get();
        $data['brands'] = BrandDiscount::get();
        return view('reports.discountReport', $data);
    }

    public function discountReportPull(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoiceDateFilter' => 'required',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $userId = $request->userId;
            if ($userId == 'all') {
                $userCond = '';
            } else {
                $userCond = 'AND users.id = ' . $userId . '';
            }

            if ($request->brand == 'all') {
                $brandCond = '';
            } else {
                $brandCond = 'AND brand_discounts.brand_name = "' . $request->brand . '"';
            }


            if (strpos($request->invoiceDateFilter, 'to') !== false) {
                list($startDateStr, $endDateStr) = explode(" to ", $request->invoiceDateFilter);
                $startDate = date('Y-m-d', strtotime($startDateStr));
                $endDate = date('Y-m-d', strtotime($endDateStr));
            } else {
                $startDate = date('Y-m-d', strtotime($request->invoiceDateFilter));
                $endDate = date('Y-m-d', strtotime($request->invoiceDateFilter));
            }

            $data['fromDate'] = $startDate;
            $data['toDate'] = $endDate;

            $data['reportData'] = DB::select('SELECT 
                                    leads.sap_invoice, 
                                    leads.invoice_date, 
                                    customers.customer_name, 
                                    customers.sap_id, 
                                    customers.assign_to,
                                    pump_choices.product_id,
                                    pump_choices.spare_parts,
                                    pump_choices.unit_price,
                                    pump_choices.qty,
                                    pump_choices.discount_price,
                                    pump_choices.discount_percentage,
                                    pump_choices.net_price,
                                    COALESCE(items.new_code, spare_items.new_code) AS product_code, 
                                    COALESCE(items.mat_name, spare_items.mat_name) AS mat_name,
                                    COALESCE(items.brand_name, spare_items.brand_name) AS brand_name,
                                    brand_discounts.trade_discount,
                                    users.user_name
                                    FROM leads
                                    INNER JOIN customers ON customers.id = leads.customer_id
                                    INNER JOIN pump_choices ON pump_choices.lead_id = leads.id
                                    LEFT JOIN items ON items.id = pump_choices.product_id AND pump_choices.spare_parts = 0
                                    LEFT JOIN spare_items ON spare_items.id = pump_choices.product_id AND pump_choices.spare_parts = 1
                                    INNER JOIN brand_discounts ON brand_discounts.brand_name=COALESCE(items.brand_name, spare_items.brand_name)
                                    INNER JOIN users ON users.assign_to = customers.assign_to
                                    WHERE leads.invoice_date BETWEEN "' . $startDate . '" AND "' . $endDate . '" ' . $userCond . '' . $brandCond . '
                                    ORDER BY leads.sap_invoice ASC');

            $data['salesPersons'] = User::get();
            $data['brands'] = BrandDiscount::get();
            return view('reports.discountReport', $data);
        }
    }
}
