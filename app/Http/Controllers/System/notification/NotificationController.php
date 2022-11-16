<?php

namespace App\Http\Controllers\System\notification;


use App\Model\System\Notification;
use Illuminate\Http\Request;
use App\Services\System\NotificationService;
use App\Http\Controllers\System\ResourceController;

class NotificationController extends ResourceController
{
    public function __construct(NotificationService $notificationService)
    {

        parent::__construct($notificationService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\system\notificationRequest';
    }

    public function updateValidationRequest()
    {
        return 'App\Http\Requests\system\notificationRequest';
    }

    public function moduleName()
    {
        return 'notifications';
    }

    public function viewFolder()
    {
        return 'system.notification';
    }

    public function getBuildingAdminByContractor(Request $request)
    {
        return response()->json($this->service->getbuildingAdminByContractor($request->id));
    }

    public function show($id)
    {

        try {
            $data['title'] = translate('Notification Management');
            $data['breadcrumbs'] = $this->breadcrumbForIndex();
            $data['items'] = Notification::where('id', $id)->first();
            return view('system.notification.view', $data);
        } catch (\Exception $e) {

        }
    }
}
