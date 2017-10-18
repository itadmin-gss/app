<?php

namespace App\Http\Middleware;

use App\Helpers\FlashMessage;
use App\Http\Controllers\AccessRightController;
use App\RoleFunction;
use Closure;

class AdminRightsCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $check = 1;
        $currentRouteAction = $request->route()->getActionName();

        if ($currentRouteAction == 'AdminController@listUser') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'User')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
        } elseif ($currentRouteAction == 'AdminController@addUser') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'User')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
        } elseif ($currentRouteAction == 'AdminController@editProfileAdmin' || $currentRouteAction == 'AccessLevelController@updateUserAccessLevel') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'User')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
        } elseif ($currentRouteAction == 'AdminController@deleteUser') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'User')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'delete');
        } elseif ($currentRouteAction == 'AdminController@addAccessLevel') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
        } elseif ($currentRouteAction == 'AccessLevelController@editAccessLevel') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
        } elseif ($currentRouteAction == 'AdminController@listAccessLevel') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
        } elseif ($currentRouteAction == 'AccessRightController@index') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Access Rights')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
        } elseif ($currentRouteAction == 'CustomerController@createCustomerAdmin') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Customer')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
        } elseif ($currentRouteAction == 'CustomerController@listCustomerAdmin') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Customer')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
        } elseif ($currentRouteAction == 'AdminController@addVendor') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Vendor')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
        } elseif ($currentRouteAction == 'AssetController@addAdminAsset') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
        } elseif ($currentRouteAction == 'AssetController@editAdminAsset') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
        } elseif ($currentRouteAction == 'AssetController@listAdminAssets') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
        } elseif ($currentRouteAction == 'ServiceController@addAdminService') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Service')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
        } elseif ($currentRouteAction == 'ServiceController@listAdminServices') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Service')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
        } elseif ($currentRouteAction == 'AdminController@listMaintenanceRequest') {
            // check for access rights of view for list users
            $role_function_id = RoleFunction::where('role_function', '=', 'Service Request')
                ->first(['id']);
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
        }

        // if no rights then redirect back to admin Dashboard.
        if (!$check) {
            $mesg = FlashMessage::messages('admin_access.access_denied');

            return redirect("/admin")
                ->with('message', FlashMessage::displayAlert($mesg, 'warning'));
        }

        return $next($request);
    }
}
