<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Setup the layout used by the controller.
     *sdfsd
     * @return void
     */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $this->layout = view($this->layout);
        }
    }



    public function __construct()
    {
        if (Auth::check()) {
            $role_id = Auth::user()->user_role_id;

            $role_detail = RoleDetail::where('role_id', '=', $role_id)
            ->get();

                        $access_roles=[];


            foreach ($role_detail as $role) {
                $rol_func = $role->roleFunctions->role_function;


                $access_roles[$rol_func]['add'] = $role['add'];
                $access_roles[$rol_func]['edit'] = $role['edit'];
                $access_roles[$rol_func]['delete'] = $role['delete'];
                $access_roles[$rol_func]['view'] = $role['view'];
            }

            View::share('access_roles', $access_roles);
            $getNotifications    =  Notification::getNotifications(Auth::user()->id);
            $unreadnotifications =  Notification::where('recepient_id', '=', Auth::user()->id)
                                                ->where('is_read', '=', 1)
                                                ->skip(0)
                                                ->take(5)
                                                ->count();
            $CustomerType=CustomerType::get();
            $clientTypeSession=  Session::get('clientType');
            View::share('get_notifications', $getNotifications);
            View::share('unreadnotifications', $unreadnotifications);
            View::share('CustomerType', $CustomerType);
            View::share('clientTypeSession', $clientTypeSession);
        }
    }
    public function notify()
    {
            $getNotifications    =  Notification::getNotifications(Auth::user()->id);
            $unreadnotifications =  Notification::where('recepient_id', '=', Auth::user()->id)
                                                ->where('is_read', '=', 1)
                                                ->skip(0)
                                                ->take(5)
                                                ->count();
            $CustomerType=CustomerType::get();
            $clientTypeSession=  Session::get('clientType');
            //View::share('get_notifications', $getNotifications);
            return $unreadnotifications;
            //View::share('CustomerType', $CustomerType);
            //View::share('clientTypeSession', $clientTypeSession);
    }
}
