<?php

namespace App\Http\Controllers\System\guide;

use App\Model\System\Guide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\System\GuideService;
use App\Http\Controllers\System\ResourceController;

class GuideController extends ResourceController
{
    public function __construct(GuideService $guideService)
    {

        parent::__construct($guideService);
    }
    public function storeValidationRequest()
    {
        return 'App\Http\Requests\system\guideRequest';
    }

    public function updateValidationRequest()
    {
        return 'App\Http\Requests\system\guideRequest';
    }

    public function moduleName()
    {
        return 'guides';
    }

    public function viewFolder()
    {
        return 'system.guide';
    }
    public function destroy(Request $request, $id)
    {
      $test = $this->service->delete($request, $id);

      if($test == true){

        $this->setModuleId($id);
      return redirect($this->getUrl())->withErrors(['success' => translate('Successfully deleted.')]);
      }
      return redirect($this->getUrl())->withErrors(['alert-danger' => translate('Cannot delete Guide that is associated to Mansion and Contractor')]);


    }

    public function changeStatus(Request $request)
    {

        $id = $request['id'];
        $data = Guide::where('id', $id)->first();

        if ($data != null) {
            if ($data->status == 1) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }

            $data->save();
            return back();
        } else {
            return redirect($this->redirectUrl)->withErrors(['alert-danger' => 'Sorry,the status couldnot be changed!']);
        }
    }

    public function show($id)
    {
        try {
            $data['title'] = translate('Guide Management');
            $data['breadcrumbs'] = $this->breadcrumbForIndex();
            $data['items'] = Guide::where('id', $id)->first();
            $data['contact_category'] = $this->service->getContactCategory();
            return view('system.guide.view', $data);
        } catch (\Exception $e) {

        }
    }

}
