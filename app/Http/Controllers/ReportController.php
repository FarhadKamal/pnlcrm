<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\BrandDiscount;
use App\Models\Customer;
use App\Models\itemsDemand;
use App\Models\Lead;
use App\Models\PumpChoice;
use App\Models\Quotation;
use App\Models\SalesLog;
use App\Models\SalesTarget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                                    WHERE leads.is_lost != 1 AND leads.invoice_date BETWEEN "' . $startDate . '" AND "' . $endDate . '" ' . $userCond . '' . $brandCond . '
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
                AND leads.is_lost != 1 
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
                AND leads.is_lost != 1 
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
            AND leads.is_lost != 1 
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
                AND leads.is_lost != 1 
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
                AND leads.is_lost != 1 
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
            AND leads.is_lost != 1 
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
                                        AND leads.is_lost != 1 
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

            $data['reportData'] = DB::select('SELECT 
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
                                        IFNULL(pump_totals.invoice_amount, 0) AS invoice_amount
                                    FROM leads
                                    INNER JOIN customers ON customers.id = leads.customer_id
                                    INNER JOIN quotations ON quotations.lead_id = leads.id
                                    LEFT JOIN (
                                        SELECT lead_id, SUM(net_price) AS invoice_amount
                                        FROM pump_choices
                                        GROUP BY lead_id
                                    ) AS pump_totals ON pump_totals.lead_id = leads.id
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
                                    WHERE quotations.is_accept = 1 AND leads.is_lost != 1
                                    AND leads.invoice_date BETWEEN "' . $startDate . '" AND "' . $endDate . '" ' . $userCond . ' 
                                    GROUP BY leads.id');

            $data['salesPersons'] = User::get();
            return view('reports.transactionReport', $data);
        }
    }

    public function graphReportPull()
    {
        // // Annual and Quarter Achievement Start
        $userCond = '';
        $financialYear = date('Y');
        $reportData = $this->targetSalesReportQuery($userCond, $financialYear);
        $annualTarget = 0;
        $annualSales = 0;
        $q1Target = 0;
        $q1Sales = 0;
        $q2Target = 0;
        $q2Sales = 0;
        $q3Target = 0;
        $q3Sales = 0;
        $q4Target = 0;
        $q4Sales = 0;
        foreach ($reportData as $item) {
            $annualTarget = $annualTarget + $item->Q1_Target + $item->Q2_Target + $item->Q3_Target + $item->Q4_Target;
            $annualSales = $annualSales + $item->Q1_Sales + $item->Q2_Sales + $item->Q3_Sales + $item->Q4_Sales;
            $q1Target = $q1Target + $item->Q1_Target;
            $q1Sales = $q1Sales + $item->Q1_Sales;
            $q2Target = $q2Target + $item->Q2_Target;
            $q2Sales = $q2Sales + $item->Q2_Sales;
            $q3Target = $q3Target + $item->Q3_Target;
            $q3Sales = $q3Sales + $item->Q3_Sales;
            $q4Target = $q4Target + $item->Q4_Target;
            $q4Sales = $q4Sales + $item->Q4_Sales;
        }
        $data['annualAchievementPer'] = ($annualSales / $annualTarget) * 100;
        $data['q1AchievementPer'] = ($q1Sales / $q1Target) * 100;
        $data['q2AchievementPer'] = ($q2Sales / $q2Target) * 100;
        $data['q3AchievementPer'] = ($q3Sales / $q3Target) * 100;
        $data['q4AchievementPer'] = ($q3Sales / $q3Target) * 100;
        // // Annual and Quarter Achievement End 

        // Top 5 salesperson ranking Current Quarter Start
        $currentMonth = date('m');
        $topSalesPersonCQ = [];
        $i = 0;
        foreach ($reportData as $item) {
            if ($currentMonth >= 7 && $currentMonth <= 9) {
                $target = $item->Q1_Target;
                $sales = $item->Q1_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            if ($currentMonth >= 10 && $currentMonth <= 12) {
                $target = $item->Q2_Target;
                $sales = $item->Q2_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            if ($currentMonth >= 1 && $currentMonth <= 3) {
                $target = $item->Q3_Target;
                $sales = $item->Q3_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            if ($currentMonth >= 4 && $currentMonth <= 6) {
                $target = $item->Q4_Target;
                $sales = $item->Q4_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            $i++;
        }
        usort($topSalesPersonCQ, function ($a, $b) {
            return $b['per'] <=> $a['per'];
        });
        $data['top5SalesPersonsCQ'] = array_slice($topSalesPersonCQ, 0, 5, true);
        // Top 5 salesperson ranking Current Quarter End

        // Top Sold Product Start 
        $currentMonth = date('m');
        if ($currentMonth >= 7) {
            $startDate = date('Y-07-01');
            $endDate = date('Y-06-30', strtotime('+1 year'));
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        } else {
            $startDate = date('Y-07-01', strtotime('-1 year'));
            $endDate = date('Y-06-30');
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        }
        $limit = 20;
        $data['topSoldProduct'] = $this->topSoldProduct($startDate, $endDate, $limit);
        // Top Sold Product End 

        // Top Sold Brand Start
        $data['topSoldBrand'] = $this->topSoldBrand($startDate, $endDate);
        // Top Sold Brand End

        // Total Outstanding Start 
        $filterDate = date('Y-m-d');
        $customerCond = ''; // All Customer
        $grandTotalOutstanding = 0;
        $grandTotaldueWithin30 = 0;
        $grandTotaldueWithin31_60 = 0;
        $grandTotaldueWithin61_90 = 0;
        $grandTotaldueWithin91_180 = 0;
        $grandTotaldueWithin180plus = 0;
        $grandTotaldueWithin365plus = 0;
        $outstandingList = $this->outStandingNetDueQuery($filterDate, $userCond, $customerCond);
        foreach ($outstandingList as $item) {
            $grandTotalOutstanding = $grandTotalOutstanding + $item->netDue;
            $customerSAPID =  $item->sap_id;
            $dueWithin30 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 0, 30);
            $dueWithin31_60 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 30, 60);
            $dueWithin61_90 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 60, 90);
            $dueWithin91_180 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 90, 180);
            $dueWithin180plus = $this->dueIntervalCalculation($customerSAPID, $filterDate, 180, 0);
            $dueWithin365plus = $this->dueIntervalCalculation($customerSAPID, $filterDate, 365, 0);

            $grandTotaldueWithin30 = $grandTotaldueWithin30 + ($dueWithin30[0]->netDue ?? 0);
            $grandTotaldueWithin31_60 = $grandTotaldueWithin31_60 + ($dueWithin31_60[0]->netDue ?? 0);
            $grandTotaldueWithin61_90 = $grandTotaldueWithin61_90 + ($dueWithin61_90[0]->netDue ?? 0);
            $grandTotaldueWithin91_180 = $grandTotaldueWithin91_180 + ($dueWithin91_180[0]->netDue ?? 0);
            $grandTotaldueWithin180plus = $grandTotaldueWithin180plus + ($dueWithin180plus[0]->netDue ?? 0);
            $grandTotaldueWithin365plus = $grandTotaldueWithin365plus + ($dueWithin365plus[0]->netDue ?? 0);
        }
        $data['grandTotalOutstanding'] = $grandTotalOutstanding;
        $data['grandTotaldueWithin30'] = $grandTotaldueWithin30;
        $data['grandTotaldueWithin31_60'] = $grandTotaldueWithin31_60;
        $data['grandTotaldueWithin61_90'] = $grandTotaldueWithin61_90;
        $data['grandTotaldueWithin91_180'] = $grandTotaldueWithin91_180;
        $data['grandTotaldueWithin180plus'] = $grandTotaldueWithin180plus;
        $data['grandTotaldueWithin365plus'] = $grandTotaldueWithin365plus;
        // Total Outstanding End 

        return view('reports.graphReport', $data);
    }


    function topSoldProduct($startDate, $endDate, $limit)
    {
        $reportData = DB::select('SELECT pump_choices.product_id, 
                                pump_choices.spare_parts, 
                                COUNT(pump_choices.product_id) AS InvoiceQty,
                                SUM(pump_choices.qty) AS totalSoldQty,
                                items.mat_name AS productName,
                                items.brand_name
                            FROM leads
                            INNER JOIN pump_choices ON pump_choices.lead_id = leads.id
                            LEFT JOIN items ON items.id = pump_choices.product_id
                            WHERE leads.is_won = 1 
                            AND DATE(leads.invoice_date) BETWEEN "' . $startDate . '" AND "' . $endDate . '"
                            AND leads.is_lost != 1
                            AND pump_choices.spare_parts = 0
                            GROUP BY pump_choices.product_id 
                            ORDER BY totalSoldQty DESC 
                            LIMIT ' . $limit . '');
        return $reportData;
    }

    function topSoldBrand($startDate, $endDate)
    {
        $reportData = DB::select('SELECT pump_choices.product_id, 
                                pump_choices.spare_parts, 
                                COUNT(pump_choices.product_id) AS InvoiceQty,
                                SUM(pump_choices.qty) AS totalSoldQty,
                                items.mat_name AS productName,
                                items.brand_name
                            FROM leads
                            INNER JOIN pump_choices ON pump_choices.lead_id = leads.id
                            LEFT JOIN items ON items.id = pump_choices.product_id
                            WHERE leads.is_won = 1 AND leads.is_lost != 1
                            AND DATE(leads.invoice_date) BETWEEN "' . $startDate . '" AND "' . $endDate . '"
                            AND pump_choices.spare_parts = 0
                            GROUP BY items.brand_name
                            ORDER BY totalSoldQty DESC');
        return $reportData;
    }

    function leadDetailReport()
    {
        $data['ownLead'] = Lead::whereHas('clientInfo', function ($query) {
            $query->where(['assign_to' => Auth()->user()->assign_to]);
        })->get();
        return view('reports.leadDetailReport', $data);
    }

    function leadDetailReportPull(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadId' => 'required',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        } else {
            $leadId = $request->leadId;
            $data['leadInfo'] = Lead::find($leadId);
            if (!$data['leadInfo']) {
                return back()->with('swError', 'No Lead Found');
            }
            if ((Auth()->user()->assign_to != $data['leadInfo']->clientInfo->assign_to) && (!Helper::permissionCheck(Auth()->user()->id, 'salesLog'))) {
                return back()->with('swError', 'You Are Not Authorized');
            }

            $data['pumpInfo'] = PumpChoice::where(['lead_id' => $leadId])->get();
            $data['quotationInfo'] = Quotation::orderBy('id', 'desc')->take(1)->where(['lead_id' => $leadId, 'is_accept' => 1])->get();
            $data['salesLog'] = SalesLog::where('lead_id', $leadId)->orderBy('id', 'DESC')->get();
            $data['transactionInfo'] = Transaction::where(['lead_id' => $leadId])->get();

            $data['ownLead'] = Lead::whereHas('clientInfo', function ($query) {
                $query->where(['assign_to' => Auth()->user()->assign_to]);
            })->get();
            return view('reports.leadDetailReport', $data);
        }
    }

    function graphReport2()
    {
        $currentMonth = date('m');
        if ($currentMonth >= 7) {
            $startDate = date('Y-07-01');
            $endDate = date('Y-06-30', strtotime('+1 year'));
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        } else {
            $startDate = date('Y-07-01', strtotime('-1 year'));
            $endDate = date('Y-06-30');
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        }
        return view('reports.graphReport2', $data);
    }
    function annualAchieveGraph()
    {
        $userCond = '';
        $financialYear = date('Y');
        $reportData = $this->targetSalesReportQuery($userCond, $financialYear);
        $annualTarget = 0;
        $annualSales = 0;
        $q1Target = 0;
        $q1Sales = 0;
        $q2Target = 0;
        $q2Sales = 0;
        $q3Target = 0;
        $q3Sales = 0;
        $q4Target = 0;
        $q4Sales = 0;
        foreach ($reportData as $item) {
            $annualTarget = $annualTarget + $item->Q1_Target + $item->Q2_Target + $item->Q3_Target + $item->Q4_Target;
            $annualSales = $annualSales + $item->Q1_Sales + $item->Q2_Sales + $item->Q3_Sales + $item->Q4_Sales;
            $q1Target = $q1Target + $item->Q1_Target;
            $q1Sales = $q1Sales + $item->Q1_Sales;
            $q2Target = $q2Target + $item->Q2_Target;
            $q2Sales = $q2Sales + $item->Q2_Sales;
            $q3Target = $q3Target + $item->Q3_Target;
            $q3Sales = $q3Sales + $item->Q3_Sales;
            $q4Target = $q4Target + $item->Q4_Target;
            $q4Sales = $q4Sales + $item->Q4_Sales;
        }
        $data['annualAchievementPer'] = ($annualSales / $annualTarget) * 100;
        $data['q1AchievementPer'] = ($q1Sales / $q1Target) * 100;
        $data['q2AchievementPer'] = ($q2Sales / $q2Target) * 100;
        $data['q3AchievementPer'] = ($q3Sales / $q3Target) * 100;
        $data['q4AchievementPer'] = ($q3Sales / $q3Target) * 100;
        return response()->json($data);
    }

    function top5SalesPersonGraph()
    {
        $userCond = '';
        $financialYear = date('Y');
        $reportData = $this->targetSalesReportQuery($userCond, $financialYear);
        $currentMonth = date('m');
        $topSalesPersonCQ = [];
        $i = 0;
        foreach ($reportData as $item) {
            if ($currentMonth >= 7 && $currentMonth <= 9) {
                $target = $item->Q1_Target;
                $sales = $item->Q1_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            if ($currentMonth >= 10 && $currentMonth <= 12) {
                $target = $item->Q2_Target;
                $sales = $item->Q2_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            if ($currentMonth >= 1 && $currentMonth <= 3) {
                $target = $item->Q3_Target;
                $sales = $item->Q3_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            if ($currentMonth >= 4 && $currentMonth <= 6) {
                $target = $item->Q4_Target;
                $sales = $item->Q4_Sales;
                $achieve = ($sales / $target) * 100;
                $topSalesPersonCQ[$i] = ['name' => $item->user_name, 'per' => $achieve];
            }
            $i++;
        }
        usort($topSalesPersonCQ, function ($a, $b) {
            return $b['per'] <=> $a['per'];
        });
        $data['top5SalesPersonsCQ'] = array_slice($topSalesPersonCQ, 0, 5, true);
        return response()->json($data);
    }

    function topSoldProductGraph()
    {
        $currentMonth = date('m');
        if ($currentMonth >= 7) {
            $startDate = date('Y-07-01');
            $endDate = date('Y-06-30', strtotime('+1 year'));
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        } else {
            $startDate = date('Y-07-01', strtotime('-1 year'));
            $endDate = date('Y-06-30');
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        }
        $limit = 20;
        $data['topSoldProduct'] = $this->topSoldProduct($startDate, $endDate, $limit);
        return response()->json($data);
    }

    function topSoldBrandGraph()
    {
        $currentMonth = date('m');
        if ($currentMonth >= 7) {
            $startDate = date('Y-07-01');
            $endDate = date('Y-06-30', strtotime('+1 year'));
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        } else {
            $startDate = date('Y-07-01', strtotime('-1 year'));
            $endDate = date('Y-06-30');
            $data['financialYear'] = date('Y', strtotime($startDate)) . '-' . date('Y', strtotime($endDate));
        }
        $data['topSoldBrand'] = $this->topSoldBrand($startDate, $endDate);
        return response()->json($data);
    }

    function totalOutstandingGraph()
    {
        $userCond = '';
        $filterDate = date('Y-m-d');
        $customerCond = ''; // All Customer
        $grandTotalOutstanding = 0;
        $grandTotaldueWithin30 = 0;
        $grandTotaldueWithin31_60 = 0;
        $grandTotaldueWithin61_90 = 0;
        $grandTotaldueWithin91_180 = 0;
        $grandTotaldueWithin180plus = 0;
        $grandTotaldueWithin365plus = 0;
        $outstandingList = $this->outStandingNetDueQuery($filterDate, $userCond, $customerCond);
        foreach ($outstandingList as $item) {
            $grandTotalOutstanding = $grandTotalOutstanding + $item->netDue;
            $customerSAPID =  $item->sap_id;
            $dueWithin30 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 0, 30);
            $dueWithin31_60 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 30, 60);
            $dueWithin61_90 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 60, 90);
            $dueWithin91_180 = $this->dueIntervalCalculation($customerSAPID, $filterDate, 90, 180);
            $dueWithin180plus = $this->dueIntervalCalculation($customerSAPID, $filterDate, 180, 0);
            $dueWithin365plus = $this->dueIntervalCalculation($customerSAPID, $filterDate, 365, 0);

            $grandTotaldueWithin30 = $grandTotaldueWithin30 + ($dueWithin30[0]->netDue ?? 0);
            $grandTotaldueWithin31_60 = $grandTotaldueWithin31_60 + ($dueWithin31_60[0]->netDue ?? 0);
            $grandTotaldueWithin61_90 = $grandTotaldueWithin61_90 + ($dueWithin61_90[0]->netDue ?? 0);
            $grandTotaldueWithin91_180 = $grandTotaldueWithin91_180 + ($dueWithin91_180[0]->netDue ?? 0);
            $grandTotaldueWithin180plus = $grandTotaldueWithin180plus + ($dueWithin180plus[0]->netDue ?? 0);
            $grandTotaldueWithin365plus = $grandTotaldueWithin365plus + ($dueWithin365plus[0]->netDue ?? 0);
        }
        $data['grandTotalOutstanding'] = $grandTotalOutstanding;
        $data['grandTotaldueWithin30'] = $grandTotaldueWithin30;
        $data['grandTotaldueWithin31_60'] = $grandTotaldueWithin31_60;
        $data['grandTotaldueWithin61_90'] = $grandTotaldueWithin61_90;
        $data['grandTotaldueWithin91_180'] = $grandTotaldueWithin91_180;
        $data['grandTotaldueWithin180plus'] = $grandTotaldueWithin180plus;
        $data['grandTotaldueWithin365plus'] = $grandTotaldueWithin365plus;
        return response()->json($data);
    }

    function productDemandReport()
    {
        $data['salesPersons'] = User::get();
        return view('reports.productDemandReport');
    }

    function productDemandReportPull(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'searchYear' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors()->all());
        } else {
            $userId = $request->userId;
            if ($userId == 'all') {
                $userCond = '';
            } else {
                $userCond = ' AND created_by = ' . $userId . '';
            }

            $query = itemsDemand::query();
            $year = $request->searchYear;
            $query->whereYear('created_at', $year);
            if ($userId != 'all') {
                $query->where('created_by', $userId);
            }
            $data['reportData'] = $query->get();

            return view('reports.productDemandReport', $data);
        }
    }
}
