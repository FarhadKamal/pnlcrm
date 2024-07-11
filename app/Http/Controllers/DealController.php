<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Lead;
use Illuminate\Http\Request;

use App\Models\Requirements;
use App\Models\PumpChoice;
use App\Models\SalesLog;
use App\Models\SpareItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class DealController extends Controller
{
    //
    public function storeRequirement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required',
            'type_of_use' => 'required',
            'pump_type' => 'required',
            'suction_type' => 'nullable',
            'suction_pipe_dia' => 'nullable',
            'delivery_head' => 'nullable',
            'delivery_pipe_dia' => 'nullable',
            'horizontal_pipe_length' => 'nullable',
            'source_of_water' => 'nullable',
            'water_hour' => 'nullable',
            'water_consumption' => 'nullable',
            'liquid_type' => 'nullable',
            'pump_running_hour' => 'nullable',

        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            $data['type_of_use'] = $request->type_of_use;
            $data['pump_type'] = $request->pump_type;
            $data['suction_type'] = $request->suction_type;
            $data['suction_pipe_dia'] = $request->suction_pipe_dia;
            $data['delivery_head'] = $request->delivery_head;
            $data['delivery_pipe_dia'] = $request->delivery_pipe_dia;
            $data['horizontal_pipe_length'] = $request->horizontal_pipe_length;
            $data['source_of_water'] = $request->source_of_water;
            $data['water_hour'] = $request->water_hour;
            $data['water_consumption'] = $request->water_consumption;
            $data['liquid_type'] = $request->liquid_type;
            $data['pump_running_hour'] = $request->pump_running_hour;
            return back()->with('errorsData', $data);
        }

        if (isset($request->req_id)) {
            $reqInfo = Requirements::find($request->req_id);
            $reqInfo->type_of_use = $request->type_of_use;
            $reqInfo->pump_type = $request->pump_type;
            $reqInfo->suction_type = $request->suction_type;
            $reqInfo->suction_pipe_dia = $request->suction_pipe_dia;
            $reqInfo->delivery_head = $request->delivery_head;
            $reqInfo->delivery_pipe_dia = $request->delivery_pipe_dia;
            $reqInfo->horizontal_pipe_length = $request->horizontal_pipe_length;
            $reqInfo->source_of_water = $request->source_of_water;
            $reqInfo->water_hour = $request->water_hour;
            $reqInfo->water_consumption = $request->water_consumption;
            $reqInfo->liquid_type = $request->liquid_type;
            $reqInfo->pump_running_hour = $request->pump_running_hour;
            $reqInfo->save();
        } else {
            $insert_req_data = array(
                'lead_id' => $request->lead_id,
                'type_of_use' => $request->type_of_use,
                'pump_type' => $request->pump_type,
                'suction_type' => $request->suction_type,
                'suction_pipe_dia' => $request->suction_pipe_dia,
                'delivery_head' => $request->delivery_head,
                'delivery_pipe_dia' => $request->delivery_pipe_dia,
                'horizontal_pipe_length' => $request->horizontal_pipe_length,
                'source_of_water' => $request->source_of_water,
                'water_hour' => $request->water_hour,
                'water_consumption' => $request->water_consumption,
                'liquid_type' => $request->liquid_type,
                'pump_running_hour' => $request->pump_running_hour
            );

            $reqlist = Requirements::create($insert_req_data);
        }

        //Auth()->user()->id,
        return back()->with('success', 'Requirement saved success');
    }

    public function SAPstockDetails($productId)
    {
        $ch = curl_init();
        // $url = "http://103.4.66.107:8989/api/get_item_stock.php?code=" . $productId . "";
        $url = "http://192.168.1.226:8989/api/get_item_stock.php?code=" . $productId . "";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);
        $data['responseData'] = json_decode($response);

        return view('sales.report.sapStockReport', $data);
    }

    public function deleteDealRequirement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'req_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        }
        $getReqInfo = Requirements::find($request->req_id);
        $leadId = $getReqInfo->lead_id;
        $reqId = $request->req_id;
        Requirements::where(['id' => $reqId])->delete();
        PumpChoice::where(['lead_id' => $leadId, 'req_id' => $reqId])->delete();
        return back()->with('success', 'Requirement is deleted!');
    }

    public function getFilterPumpInfo(Request $request)
    {
        $data = $request->json()->all();
        $filterType = $data['filterType'];
        $filterBrand = $data['filterBrand'];
        $filterHP = $data['filterHP'];
        $filterModel = $data['filterModel'];
        $filterHead = $data['filterHead'];
        $filterPhase = $data['filterPhase'];
        $filterSpare = $data['filterSpare'];

        if ($filterType == 'Spare Parts') {
            $query = SpareItems::query();
            if ($filterSpare != 'all') {
                $query->where('mat_name', $filterSpare);
            }
            if ($filterBrand != 'all') {
                $query->where('brand_name', $filterBrand);
            }
        } else {
            $query = Items::query();
            $query->where('pump_type', $filterType);
            if ($filterBrand != 'all') {
                $query->where('brand_name', $filterBrand);
            }
            if ($filterHP != 'all') {
                $query->where('hp', $filterHP);
            }
            if ($filterModel != 'all') {
                $query->where('mat_name', $filterModel);
            }
            if ($filterHead) {
                $query->where(function ($q) use ($filterHead) {
                    $q->where('min_head', '<=', $filterHead)
                        ->where('max_head', '>=', $filterHead);
                });
            }
            if ($filterPhase != 'all') {
                $query->where('phase', $filterPhase);
            }
        }


        $itemInfo = $query->get();

        if (count($itemInfo) > 0) {
            $responseDataAll = [];
            foreach ($itemInfo as $item) {
                $itemCode = $item->new_code;


                $IP =  $this->getClientIp($request);
                //$IP = "192.168.1.226";
                $parts = explode('.', $IP);
                $subnet = $parts[0] . '.' . $parts[1] . '.' . $parts[2];

                // **External API Call (using curl for flexibility)**
                $ch = curl_init();

                if ($subnet == "192.168.1") {
                    $url = "http://192.168.1.226:8989/api/get_price_stock.php?item_code=" . $itemCode . "";
                } else {
                    $url = "http://103.4.66.107:8989/api/get_price_stock.php?item_code=" . $itemCode . "";
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // **Optional: Authentication headers (if required by external API)**
                // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer YOUR_API_KEY'));

                $response = curl_exec($ch);
                $curlError = curl_error($ch);
                curl_close($ch);

                if ($curlError) {
                    // Handle curl error (log the error and consider returning a default value)
                    error_log("Error fetching price and stock data from external API: " . $curlError);
                    $price = null;
                    $stock = null;
                    continue; // Skip to the next item
                }

                $responseData = json_decode($response);
                if ($responseData->price != null) {
                    $price = $responseData->price;
                } else {
                    $price = null;
                }
                if ($responseData->stock != null) {
                    $stock = $responseData->stock;
                } else {
                    $stock = null;
                }
                // Create a new object with pump information and price/stock data
                $pumpData = [
                    'id' => $item->id,
                    'new_code' => $item->new_code,
                    'mat_name' => $item->mat_name,
                    'brand' => $item->brand_name,
                    'hp' => $item->hp,
                    'head' => 'min ' . $item->min_head . '- max ' . $item->max_head,
                    'price' => $price,
                    'stock' => $stock
                ];

                $responseDataAll[] = $pumpData;
            }

            $response = [
                'status' => 'success',
                'data' => $responseDataAll,
                'ip' => $IP
            ];
        } else {
            $response = [
                'status' => 'null'
            ];
        }

        return response()->json($response);
    }

    public function storePumpChoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|numeric',
            'req_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        }

        $leadId = $request->lead_id;
        $reqId = $request->req_id;
        $data = [];
        if (isset($request->product_id) && count($request->product_id) > 0) {
            PumpChoice::where(['lead_id' => $leadId, 'req_id' => $reqId])->delete();
            foreach ($request->product_id as $key => $item) {
                $eachItem = [
                    'lead_id' => $leadId,
                    'req_id' => $reqId,
                    'product_id' => $item,
                    'unit_price' => $request->product_unitPrice[$key],
                    'qty' => $request->product_qty[$key],
                    'discount_price' => $request->product_discountAmt[$key],
                    'discount_percentage' => $request->product_discountPercentage[$key],
                    'net_price' => $request->product_netPrice[$key],
                    'spare_parts' => $request->spare[$key]
                ];
                $data[] = $eachItem;
            }
            usort($data, function ($a, $b) {
                return $b['product_id'] <=> $a['product_id'];
            });
            PumpChoice::insert($data);
            return back()->with('success', 'Pump chocie data saved!');
        } else {
            PumpChoice::where(['lead_id' => $leadId, 'req_id' => $reqId])->delete();
            return back()->with('success', 'Pump chocie data saved!');
        }
    }

    public function submitTheDeal(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|numeric',
            'dealPaymentType' => 'required'
        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            return back()->with('errorsData', $data);
        }

        $leadId = $request->lead_id;
        $choiceInfo = PumpChoice::where(['lead_id' => $leadId])->get();


        $payment_type = $request->dealPaymentType;
        $need_credit_approval = 0;
        $need_discount_approval = 0;
        $need_top_approval = 0;

        if ($payment_type == 'Credit') {
            $need_credit_approval = 1;
        }

        $AllSP = 1; // By Default Assume That All Item Are Spare Parts
        foreach ($choiceInfo as $row) {
            // First Check All Are Spare Item or not 
            if ($row->spare_parts == 0) {
                $AllSP = 0;
            }
            $proposed_discount = $row->discount_percentage;
            if ($row->spare_parts == 0) {
                $trade_discount = $row->productInfo->TradDiscontInfo->trade_discount;
            } else {
                $trade_discount = $proposed_discount; // For Spare Parts Assume for Package
            }

            if ($proposed_discount > $trade_discount) {
                $need_discount_approval = 1;
            }

            if ($proposed_discount > ($trade_discount + 3)) {
                $need_top_approval = 1;
            }
        }

        if ($AllSP == 1) {
            // If All Are Spare Parts and If any proposed discount, need discount approval
            foreach ($choiceInfo as $row) {
                $proposed_discount = $row->discount_percentage;
                if ($proposed_discount > 0) {
                    $need_discount_approval = 1;
                }
            }
        }

        if ($need_credit_approval == 1 || $need_discount_approval == 1) {
            $dealApproveUsersEmail = DB::select('SELECT users.user_email, users.user_name FROM permissions
            INNER JOIN user_permissions ON user_permissions.permission_id = permissions.id
            INNER JOIN users ON users.id=user_permissions.user_id
            WHERE permissions.permission_code="dealApprove"');
            if ($dealApproveUsersEmail) {
                foreach ($dealApproveUsersEmail as $email) {
                    $assignEmail = $email->user_email;
                    $assignName = $email->user_name;
                    Mail::send([], [], function ($message) use ($assignEmail, $assignName) {
                        $message->to($assignEmail, $assignName)->subject('PNL Holdings Ltd. - CRM Deal Submitted');
                        $message->from('info@pnlholdings.com', 'PNL Holdings Limited');
                        $message->setBody('<h3>Greetings From PNL Holdings Limited!</h3><p>Dear ' . $assignName . ', a deal is submitted. Please approve the deal.</p><p>Regards,<br>PNL Holdings Limited</p>', 'text/html');
                    });
                }
            }
        }

        $leadInfo = Lead::find($leadId);
        $leadInfo->payment_type = $payment_type;
        $leadInfo->need_credit_approval = $need_credit_approval;
        $leadInfo->need_discount_approval = $need_discount_approval;
        $leadInfo->need_top_approval = $need_top_approval;
        $leadInfo->current_stage = 'QUOTATION';
        if ($need_credit_approval || $need_discount_approval) {
            $leadInfo->current_subStage = 'APPROVE';
            $logNext = 'Quotation Approve';
        } else {
            $leadInfo->current_subStage = 'SUBMIT';
            $logNext = 'Quotation Submit';
        }
        $leadInfo->save();

        $log_data = array(
            'lead_id' => $leadId,
            'log_stage' => 'DEAL',
            'log_task' => 'Deal Submitted',
            'log_by' => Auth()->user()->id,
            'log_next' => $logNext
        );
        SalesLog::create($log_data);

        return redirect()->route('home');
    }


    public function getClientIp(Request $request)
    {
        // $clientIp = $request->ip(); // This gets the client's IP address
        return $request->ip();
        //return "192.168.1.129";
    }
}
