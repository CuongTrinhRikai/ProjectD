<?php

namespace App\Services\Api;

use App\Http\Requests\Api\CheckInCheckOutRequest;
use App\Model\System\CheckInCheckOut;
use App\Model\System\Mansion;
use App\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckInCheckOutService extends Service
{
    public function __construct(CheckInCheckOut $checkInCheckOut)
    {
        parent::__construct($checkInCheckOut);

    }

    public function getAll($request)
    {
        $mansion = $request['items'];
        return [
            'items' => $mansion->orderBy('id', 'ASC')->paginate(20)
        ];
    }

    public function getCheckIn($request)
    {
        $data['building_admin_id'] = \Auth::user()->id;
        $data['mansion_id'] = $request['mansion_id'];
        $data['latitude'] = $request['latitude'];
        $data['longitude'] = $request['longitude'];
        $data['check_in'] = Carbon::now()->format('Y-m-d H:i:s');
        if (isset($request['businessCategory']) && !is_null($request['businessCategory'])) {
            $data['business_category'] = $request['businessCategory'];
        } else {
            $dataBusinessCategory = \Auth::user()->business_category;
            $data['business_category'] =  Business($dataBusinessCategory[0]);
        }
        $checkInData = $this->model->create($data);

        Log::info('__start_log_'.__FUNCTION__);
        Log::info('--------------------------------');
        Log::info('data register checkin-checkouts : $data');
        Log::info(json_encode($data));
        Log::info('--------------------------------');
        Log::info('data before save to DB checkin-checkouts : $checkInData');
        Log::info(json_encode($checkInData));
        Log::info('--------------------------------');
        Log::info('__end_log_'.__FUNCTION__);

        return $checkInData;
    }


    public function update($request, $id)
    {
        $data = $request;
        $data['created_at'] = Carbon::parse($request['created_at'])->format('Y-m-d H:i:s');
        $data['updated_at'] = Carbon::parse($request['updated_at'])->format('Y-m-d H:i:s');

        CheckInCheckOut::where('id', $id)->update($data);
        return CheckInCheckOut::find($id);

    }
}
