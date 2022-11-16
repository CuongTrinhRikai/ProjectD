<?php

namespace App\Http\Controllers\System\user;

use App\Http\Controllers\System\ResourceController;
use App\Http\Requests\system\resetPassword;
use App\Services\System\UserService;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends ResourceController
{
    use AuthenticatesUsers;

    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\system\userRequest';
    }

    public function moduleName()
    {
        return 'users';
    }

    public function viewFolder()
    {
        return 'system.user';
    }

    public function changePassword()
    {
        if (authUser()->password_resetted == 0) {
            $data['title'] = 'Please change your password for security reasons.';
            $data['email'] = authUser()->email;
            $data['token'] = encrypt(authUser()->token);
            $data['buttonText'] = "Change Password";
            return view('system.auth.setPassword', $data);
        } else {
            return redirect(PREFIX . '/home');
        }
    }

    public function passwordReset(resetPassword $request)
    {

        $this->service->resetPassword($request);

        if(\Auth::user()->id == $request->id )
        {
            if (authUser() != null) {
                clearRoleCache(authUser());
            }

            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect(PREFIX . '/login')->withErrors(['alert-danger' => 'Session expired!']);
        }
        else
            {
                // Auth::logoutOtherDevices($request->password);
                return redirect($this->getUrl())->withErrors(['success' => translate('Password successfully updated.')]);
        }
    }

    public function show($id)
    {

        try {
            $data['title'] = translate('Users Management');
            $data['breadcrumbs'] = $this->breadcrumbForIndex();
            $data['items'] = User::where('id', $id)->first();
            return view('system.user.view', $data);
        } catch (\Exception $e) {

        }
    }

}
