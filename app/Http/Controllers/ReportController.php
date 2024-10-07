<?php

namespace App\Http\Controllers;

use App\Models\BrandDiscount;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\SalesTarget;
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
                $userCond = ' AND users.id = ' . $userId . '';
            }

            if ($request->brand == 'all') {
                $brandCond = '';
            } else {
                $brandCond = ' AND brand_discounts.brand_name = "' . $request->brand . '"';
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

    public function outstandingReport()
    {
        $data['salesPersons'] = User::get();
        $data['customerList'] = Customer::get();
        return view('reports.outstandingReport', $data);
    }

    public function outstandingReportPull(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filterDate' => 'required',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $userId = $request->userId;
            if ($userId == 'all') {
                $userCond = '';
            } else {
                $userCond = ' AND users.id = ' . $userId . '';
            }
            $customerId = $request->customerId;
            if ($customerId == 'all') {
                $customerCond = '';
            } else {
                $customerCond = ' AND customers.id = "' . $customerId . '"';
            }

            $filterDate = date('Y-m-d', strtotime($request->filterDate));
            $data['reportData'] = $this->outStandingNetDueQuery($filterDate, $userCond, $customerCond);

            // Now Divide the net due into different interval 
            foreach ($data['reportData'] as $item) {
                $customerSAPID =  $item->sap_id;
                $dueWithin30 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 0, 30);
                $dueWithin31_60 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 30, 60);
                $dueWithin61_90 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 60, 90);
                $dueWithin91_180 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 90, 180);
                $dueWithin180plus = $this->dueIntervalCalculation($customerSAPID, $filterDate, 180, 0);
                $dueWithin365plus = $this->dueIntervalCalculation($customerSAPID, $filterDate, 365, 0);

                $item->dueWithin30 = $dueWithin30[0]->netDue ?? 0;
                $item->dueWithin31_60 = $dueWithin31_60[0]->netDue ?? 0;
                $item->dueWithin61_90 = $dueWithin61_90[0]->netDue ?? 0;
                $item->dueWithin91_180 = $dueWithin91_180[0]->netDue ?? 0;
                $item->dueWithin180plus = $dueWithin180plus[0]->netDue ?? 0;
                $item->dueWithin365plus = $dueWithin365plus[0]->netDue ?? 0;
            }

            $data['salesPersons'] = User::get();
            $data['customerList'] = Customer::get();
            $data['filterDate'] = $filterDate;
            return view('reports.outstandingReport', $data);
        }
    }

    public function outStandingNetDueQuery($filterDate, $userCond, $customerCond)
    {
        $fetchData = DB::select('SELECT 
        customers.sap_id, 
        customers.customer_name, 
        users.user_name,
        users.assign_to,
        COALESCE(pump_totals.total_net_price, 0) AS totalNetPrice,
        COALESCE(transactions.total_verified_paid, 0) AS totalVerifiedPaid,
        COALESCE(pump_totals.total_net_price, 0) - COALESCE(transactions.total_verified_paid, 0) AS netDue

        FROM 
            customers
        INNER JOIN 
            users ON users.assign_to = customers.assign_to
        INNER JOIN (
            SELECT 
                leads.customer_id,
                SUM(pump_choices.net_price) AS total_net_price
            FROM 
                leads
            INNER JOIN 
                pump_choices ON pump_choices.lead_id = leads.id
            WHERE 
                leads.is_outstanding = 1 
                AND DATE(leads.invoice_date) <= DATE("' . $filterDate . '")
            GROUP BY 
                leads.customer_id
        ) AS pump_totals ON pump_totals.customer_id = customers.id
        LEFT JOIN (
            SELECT 
                leads.customer_id,
                SUM(transactions.pay_amount) AS total_verified_paid
            FROM 
                transactions
            INNER JOIN 
                leads ON leads.id = transactions.lead_id
            WHERE 
                leads.is_outstanding = 1 
                AND DATE(leads.invoice_date) <= DATE("' . $filterDate . '")
                AND transactions.is_verified = 1
            GROUP BY 
                leads.customer_id
        ) AS transactions ON transactions.customer_id = customers.id 
        LEFT JOIN 
            leads ON leads.customer_id = customers.id
        LEFT JOIN 
            pump_choices ON pump_choices.lead_id = leads.id 
        WHERE 
            leads.is_outstanding = 1 
            AND DATE(leads.invoice_date) <= DATE("' . $filterDate . '")
            ' . $userCond . '
            ' . $customerCond . '
        GROUP BY 
            customers.id, users.user_name, users.assign_to
        ORDER BY 
            users.assign_to ASC, totalNetPrice DESC');

        return $fetchData;
    }

    public function dueIntervalCalculation($customerSAPID, $filterDate, $firstDate, $lastDate)
    {
        if ($firstDate != 0 && $lastDate != 0) {
            $dateCond = 'DATE(leads.invoice_date) < DATE("' . $filterDate . '") - INTERVAL ' . $firstDate . ' DAY AND DATE(leads.invoice_date) >= DATE("' . $filterDate . '") - INTERVAL ' . $lastDate . ' DAY';
        }
        if ($firstDate == 0 && $lastDate != 0) {
            // Within lastday 
            $dateCond = 'DATE(leads.invoice_date) >= DATE("' . $filterDate . '") - INTERVAL ' . $lastDate . ' DAY';
        }
        if ($firstDate != 0 && $lastDate == 0) {
            // Day PLus 
            $dateCond = 'DATE(leads.invoice_date) < DATE("' . $filterDate . '") - INTERVAL ' . $firstDate . ' DAY';
        }

        $data = DB::select('SELECT 
        customers.sap_id, 
        COALESCE(pump_totals.total_net_price, 0) AS totalNetPrice,
        COALESCE(transactions.total_verified_paid, 0) AS totalVerifiedPaid,
        COALESCE(pump_totals.total_net_price, 0) - COALESCE(transactions.total_verified_paid, 0) AS netDue

        FROM 
            customers
        INNER JOIN 
            users ON users.assign_to = customers.assign_to
        INNER JOIN (
            SELECT 
                leads.customer_id,
                SUM(pump_choices.net_price) AS total_net_price
            FROM 
                leads
            INNER JOIN 
                pump_choices ON pump_choices.lead_id = leads.id
            WHERE 
                leads.is_outstanding = 1 
                AND ' . $dateCond . '
            GROUP BY 
                leads.customer_id
        ) AS pump_totals ON pump_totals.customer_id = customers.id
        LEFT JOIN (
            SELECT 
                leads.customer_id,
                SUM(transactions.pay_amount) AS total_verified_paid
            FROM 
                transactions
            INNER JOIN 
                leads ON leads.id = transactions.lead_id
            WHERE 
                leads.is_outstanding = 1 
                AND ' . $dateCond . '
                AND transactions.is_verified = 1
            GROUP BY 
                leads.customer_id
        ) AS transactions ON transactions.customer_id = customers.id 
        LEFT JOIN 
            leads ON leads.customer_id = customers.id
        LEFT JOIN 
            pump_choices ON pump_choices.lead_id = leads.id 
        WHERE 
            leads.is_outstanding = 1 
            AND ' . $dateCond . '
            AND customers.sap_id = ' . $customerSAPID . '
        GROUP BY 
            customers.id, users.user_name, users.assign_to
        ORDER BY 
            users.assign_to ASC, totalNetPrice DESC');

        return $data;
    }

    public function targetSalesReport()
    {
        $data['salesPersons'] = User::get();
        $data['brands'] = BrandDiscount::get();
        $data['financialYear'] = SalesTarget::distinct()->get(['financial_year']);
        return view('reports.targetSalesReport', $data);
    }

    public function targetSalesReportPull(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'financialYear' =>  'required',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $financialYear = $request->financialYear;
            $userId = $request->userId;
            if ($userId == 'all') {
                $userCond = '';
            } else {
                $userCond = ' WHERE u.id = ' . $userId . '';
            }

            $data['reportData'] = $this->targetSalesReportQuery($userCond, $financialYear);

            $data['reportYear'] = $financialYear;
            $data['salesPersons'] = User::get();
            $data['brands'] = BrandDiscount::get();
            $data['financialYear'] = SalesTarget::distinct()->get(['financial_year']);
            return view('reports.targetSalesReport', $data);
        }
    }

    public function targetSalesReportQuery($userCond, $financialYear)
    {
        $fetchData = DB::select('SELECT 
                                    u.assign_to,
                                    u.user_name,
                                    COALESCE(targets.Q1_Target, 0) AS Q1_Target,
                                    COALESCE(targets.Q2_Target, 0) AS Q2_Target,
                                    COALESCE(targets.Q3_Target, 0) AS Q3_Target,
                                    COALESCE(targets.Q4_Target, 0) AS Q4_Target,
                                    COALESCE(sales.Q1_Sales, 0) AS Q1_Sales,
                                    COALESCE(sales.Q2_Sales, 0) AS Q2_Sales,
                                    COALESCE(sales.Q3_Sales, 0) AS Q3_Sales,
                                    COALESCE(sales.Q4_Sales, 0) AS Q4_Sales
                                FROM 
                                    users u
                                LEFT JOIN (
                                    SELECT 
                                        leads.created_by,
                                        SUM(CASE 
                                            WHEN EXTRACT(MONTH FROM leads.invoice_date) IN (7, 8, 9) THEN pump_choices.net_price 
                                        END) AS Q1_Sales,
                                        SUM(CASE 
                                            WHEN EXTRACT(MONTH FROM leads.invoice_date) IN (10, 11, 12) THEN pump_choices.net_price 
                                        END) AS Q2_Sales,
                                    SUM(CASE 
                                            WHEN EXTRACT(MONTH FROM leads.invoice_date) IN (1, 2, 3) THEN pump_choices.net_price 
                                        END) AS Q3_Sales,
                                    SUM(CASE 
                                            WHEN EXTRACT(MONTH FROM leads.invoice_date) IN (4, 5, 6) THEN pump_choices.net_price 
                                        END) AS Q4_Sales
                                    FROM 
                                        leads
                                    INNER JOIN pump_choices ON pump_choices.lead_id = leads.id
                                    WHERE 
                                        EXTRACT(YEAR FROM leads.invoice_date) = ' . $financialYear . '
                                    GROUP BY 
                                        leads.created_by
                                ) sales ON sales.created_by = u.id
                                INNER JOIN (
                                    SELECT 
                                        ST.user_id,
                                        SUM(ST.july + ST.august + ST.september) AS Q1_Target,
                                        SUM(ST.october + ST.november + ST.december) AS Q2_Target,
                                    SUM(ST.january + ST.february + ST.march) AS Q3_Target,
                                    SUM(ST.april + ST.may + ST.june) AS Q4_Target
                                    FROM 
                                        sales_targets ST
                                    WHERE 
                                        ST.financial_year = ' . $financialYear . '
                                    GROUP BY 
                                        ST.user_id
                                ) targets ON targets.user_id = u.id
                                ' . $userCond . ' ');

        return $fetchData;
    }

    public function transactionReport()
    {
        $data['salesPersons'] = User::get();
        return view('reports.transactionReport', $data);
    }

    public function transactionReportPull(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'invoiceDateFilter' =>  'required',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
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

            $userId = $request->userId;

            if ($userId == 'all') {
                $userCond = '';
            } else {
                $userInfo = User::find($userId);
                $userAssign = $userInfo->assign_to;
                $userCond = ' AND customers.assign_to = "' . $userAssign . '"';
            }

            $data['reportData'] = DB::select('
                                    SELECT 
                                        leads.id, 
                                        leads.invoice_date, 
                                        leads.sap_invoice, 
                                        customers.customer_name, 
                                        customers.sap_id, 
                                        quotations.quotation_po, 
                                        quotations.quotation_po_date,
                                        IFNULL(transaction_sums.baseAmount, 0) AS baseAmount,
                                        IFNULL(transaction_sums.vatAmount, 0) AS vatAmount,
                                        IFNULL(transaction_sums.taxAmount, 0) AS taxAmount,
                                        IFNULL(transaction_sums.otherAmount, 0) AS otherAmount,
                                        SUM(pump_choices.net_price) AS invoice_amount
                                    FROM leads
                                    INNER JOIN customers ON customers.id = leads.customer_id
                                    INNER JOIN quotations ON quotations.lead_id = leads.id
                                    INNER JOIN pump_choices ON pump_choices.lead_id = leads.id
                                    LEFT JOIN (
                                        SELECT lead_id,
                                            SUM(CASE WHEN transaction_type = "base" AND is_verified = 1 AND is_return = 0 THEN pay_amount ELSE 0 END) AS baseAmount,
                                            SUM(CASE WHEN transaction_type = "vat" AND is_verified = 1 AND is_return = 0 THEN pay_amount ELSE 0 END) AS vatAmount,
                                            SUM(CASE WHEN transaction_type = "tax" AND is_verified = 1 AND is_return = 0 THEN pay_amount ELSE 0 END) AS taxAmount,
                                            SUM(CASE WHEN transaction_type = "" AND is_verified = 1 AND is_return = 0 THEN pay_amount ELSE 0 END) AS otherAmount
                                        FROM transactions
                                        WHERE is_verified = 1 AND is_return = 0
                                        GROUP BY lead_id
                                    ) AS transaction_sums ON transaction_sums.lead_id = leads.id
                                    WHERE quotations.is_accept = 1 
                                    AND leads.invoice_date BETWEEN "' . $startDate . '" AND "' . $endDate . '" 
                                    ' . $userCond . '
                                    GROUP BY leads.id, leads.invoice_date, leads.sap_invoice, customers.customer_name, customers.sap_id, quotations.quotation_po, quotations.quotation_po_date');

            $data['salesPersons'] = User::get();
            return view('reports.transactionReport', $data);
        }
    }
}
