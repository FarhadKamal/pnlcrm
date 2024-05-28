<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Requirements;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    //
    public function storeRequirement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required',
            'type_of_use' => 'required',
            'suction_type' => 'nullable',
            'suction_pipe_dia' => 'nullable',
            'delivery_head' => 'nullable',
            'delivery_pipe_dia' => 'nullable',
            'horizontal_pipe_length' => 'nullable',
            'source_of_water' => 'nullable',
            'water_consumption' => 'nullable',
            'liquid_type' => 'nullable',
            'pump_running_hour' => 'nullable',

        ]);
        if ($validator->fails()) {
            $data['errors'] = $validator->errors()->all();
            $data['type_of_use'] = $request->type_of_use;
            $data['suction_type'] = $request->suction_type;
            $data['suction_pipe_dia'] = $request->suction_pipe_dia;
            $data['delivery_head'] = $request->delivery_head;
            $data['delivery_pipe_dia'] = $request->delivery_pipe_dia;
            $data['horizontal_pipe_length'] = $request->horizontal_pipe_length;
            $data['source_of_water'] = $request->source_of_water;
            $data['water_consumption'] = $request->water_consumption;
            $data['liquid_type'] = $request->liquid_type;
            $data['pump_running_hour'] = $request->pump_running_hour;
            return back()->with('errorsData', $data);
        }



        $insert_req_data = array(
            'lead_id' => $request->clientName,
            'type_of_use' => $request->clientName,
            'suction_type' => $request->groupName,
            'suction_pipe_dia' => $request->clientAddress,
            'delivery_head' => $request->clientZone,
            'delivery_pipe_dia' => $request->clientDistrict,
            'horizontal_pipe_length' => $request->clientDivision,
            'source_of_water' => $request->clientTIN,
            'water_consumption' => $request->clientBIN,
            'liquid_type' => $request->clientTL,
            'pump_running_hour' => $request->contactPerson
        );

        $reqlist = Requirements::create($insert_req_data);
        $customerId = $customerId->id;



        //Auth()->user()->id,
        return back()->with('success', 'Requirement Generation Success');
    }
}
