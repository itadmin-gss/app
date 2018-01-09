<?php namespace App\Providers;

use App\CustomerType;
use App\Notification;
use App\RoleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view) {
            if (Auth::check()) {
                $role_id = Auth::user()->user_role_id;
                $role_detail = RoleDetail::where('role_id', '=', $role_id)->get();

                if (Session::has('access_roles'))
                {
                    $access_roles = Session::get('access_roles');
                }
                else
                {
                    $access_roles = [];

                    foreach ($role_detail as $role) {
                        $rol_func = $role->roleFunctions->role_function;
                        $access_roles[$rol_func]['add'] = $role['add'];
                        $access_roles[$rol_func]['edit'] = $role['edit'];
                        $access_roles[$rol_func]['delete'] = $role['delete'];
                        $access_roles[$rol_func]['view'] = $role['view'];
                    }
                }


                $view->with('access_roles', $access_roles);

                $CustomerType = CustomerType::get();
                $clientTypeSession = Session::get('clientType');
                $view->with('CustomerType', $CustomerType);
                $view->with('clientTypeSession', $clientTypeSession);
            }
        });
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
    }
}
