<?php

namespace App\Http\Controllers;

use App\Models\BrandDiscount;
use App\Models\Items;
use App\Models\SpareItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function productForm()
    {
        $data['brands'] = BrandDiscount::get();
        return view('product.newProductForm', $data);
    }

    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prType' => 'required',
            'prSAPID' => 'required|numeric',
            'prName' => 'required',
            'prBrand' => 'required',
            'unitName' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->errors()->all());
        } else {
            $prType = $request->prType;
            $prSAPID = $request->prSAPID;
            $prName = $request->prName;
            $prBrandId = $request->prBrand;
            $prItemGrp = $request->prItemGrp;
            $unitName = $request->unitName;

            $brandInfo = BrandDiscount::find($prBrandId);
            $prBrandName = $brandInfo->brand_name;
            $prBrandCountry = $brandInfo->country_name;

            if ($prType != 'Spare Parts') {
                // Items Table 

                // First Check SAP ID Exist or Not 
                $itemInfo = Items::where(['new_code' => $prSAPID])->get();
                if ($itemInfo && count($itemInfo) > 0) {
                    return back()->with('swError', 'SAP ID already exist');
                }

                if ($prType == 'Itap' || $prType == 'Maxwell') {
                    $itemData = array(
                        'new_code' => $prSAPID,
                        'mat_name' => $prName,
                        'brand_name' => $prBrandName,
                        'country_name' => $prBrandCountry,
                        'pump_type' => $prType,
                        'itm_group' => $prItemGrp,
                        'unit_name' => $unitName
                    );
                } else {
                    $itemData = array(
                        'new_code' => $prSAPID,
                        'mat_name' => $prName,
                        'brand_name' => $prBrandName,
                        'country_name' => $prBrandCountry,
                        'pump_type' => $prType,
                        'itm_group' => $prItemGrp,
                        'phase' => $request->phase,
                        'kw' => $request->kw,
                        'hp' => $request->hp,
                        'suction_dia' => $request->suctionDia,
                        'delivery_dia' => $request->deliveryDia,
                        'min_capacity' => $request->minCapacity,
                        'max_capacity' => $request->maxCapacity,
                        'min_head' => $request->minHead,
                        'max_head' => $request->maxHead,
                        'unit_name' => $unitName
                    );
                }
                Items::create($itemData);
                return back()->with('swSuccess', 'Item Inserted');
            } else {
                // Spare Parts Table
                // First Check SAP ID Exist or Not 
                $itemInfo = SpareItems::where(['new_code' => $prSAPID])->get();
                if ($itemInfo && count($itemInfo) > 0) {
                    return back()->with('swError', 'SAP ID already exist');
                }
                $itemData = array(
                    'new_code' => $prSAPID,
                    'mat_name' => $prName,
                    'brand_name' => $prBrandName,
                    'country_name' => $prBrandCountry,
                    'unit_name' => $unitName
                );
                SpareItems::create($itemData);
                return back()->with('swSuccess', 'Item Inserted');
            }
        }
    }

    public function validateSAPNewProduct(Request $request)
    {
        $data = $request->json()->all();
        $inputSAP = $data['inputSAP'];
        $inputType = $data['inputType'];

        // Duplicacy Check in the CRM 
        if ($inputType == 'Spare Parts') {
            $duplicate = SpareItems::where(['new_code' => $inputSAP])->get();
        } else {
            $duplicate = Items::where(['new_code' => $inputSAP])->get();
        }
        $url2 = 'http://192.168.1.226:8989/api/verify_product.php?item_code=' . $inputSAP;
        $getProductInfo = json_decode(file_get_contents($url2));

        $response = [
            'status' => $getProductInfo,
            'isDuplicate' => $duplicate
        ];

        return response()->json($response);
    }
}
