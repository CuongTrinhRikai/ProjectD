<?php

namespace App\Services\Api;

use App\Services\Service;
use App\Model\System\Manual;
use App\Model\System\Mansion;
use App\Exceptions\ResourceNotFoundException;

class ManualService extends Service
{
    public function __construct(Manual $manual)
    {
        parent::__construct($manual);
    }

    public function indexPageData($request)
    {

        try {
            $buildingAdminMansionID = $request->user()->buildingAdminMansion->pluck('id')->toArray();


            $mansion_id = $request->query('mansion_id');

            $limit = $request->query('limit');

            if ($mansion_id) {

                $manual = Manual::where('mansion_id', $mansion_id)
                    ->where('flag', 1)
                    ->orwhere(function($q) {
                        $q->orwhereNull('mansion_id')
                          ->Where('flag', 1);
                    })
                    ->with('mansions')
                    ->orderBy('id', 'DESC')
                    ->paginate($limit ?? 25);

                return $manual;
            }
            $manual = Manual::whereIn('mansion_id', $buildingAdminMansionID)

            ->where('flag', 1)
            // ->orwhereNull('mansion_id')
            ->orwhere(function($q) {
                $q->orwhereNull('mansion_id')
                  ->Where('flag', 1);
            })
            ->with('mansions')
            ->orderBy('id', 'DESC')
            ->paginate($limit ?? 25);

            return $manual;
        } catch (\Exception $e) {
            throw new ResourceNotFoundException('Mansion not found.', 404);
        }
    }
}
