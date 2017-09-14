<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

// This filter will redirect user to login if already logged in user try to access user registration. ----- Start -----
Route::filter('loginCheck', function () {
    if (Auth::check()) {
        return redirect("/");
    }
});


Route::filter('customer', function () {
    $type_id = Auth::user()->type_id;
    $user_type = UserType::getUserTypeByID($type_id);

    $redirect_to = $user_type;

    if ($user_type != 'customer') {
        return redirect($redirect_to);
    }
});

Route::filter('adminCheck', function () {
    $user = Auth::user();
    $usertype = UserType::getUserTypeByID($user->type_id);
    if ($usertype != 'admin' && $usertype != 'user') {
        return redirect("/");
    }
});


Route::filter('vendorCheck', function () {
    $user = Auth::user();
    $usertype = UserType::getUserTypeByID($user->type_id);
    if ($usertype != 'vendors') {
        return redirect("/");
    }
});


Route::filter('customerCheck', function () {
    $user = Auth::user();
    $usertype = UserType::getUserTypeByID($user->type_id);
    if ($usertype != 'customer') {
        return redirect("/");
    }
});

Route::filter('adminRightsCheck', function () {

    $check = 1;
    if (Route::currentRouteAction() == 'AdminController@listUser') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AdminController@addUser') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AdminController@editProfileAdmin' || Route::currentRouteAction() == 'AccessLevelController@updateUserAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
    } elseif (Route::currentRouteAction() == 'AdminController@deleteUser') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'delete');
    } elseif (Route::currentRouteAction() == 'AdminController@addAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AccessLevelController@editAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
    } elseif (Route::currentRouteAction() == 'AdminController@listAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AccessRightController@index') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Rights')
            ->first(['id']);

        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'CustomerController@createCustomerAdmin') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Customer')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'CustomerController@listCustomerAdmin') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Customer')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AdminController@addVendor') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Vendor')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AssetController@addAdminAsset') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AssetController@editAdminAsset') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
    } elseif (Route::currentRouteAction() == 'AssetController@listAdminAssets') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'ServiceController@addAdminService') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Service')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'ServiceController@listAdminServices') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Service')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AdminController@listMaintenanceRequest') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Service Request')
            ->first(['id']);
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    }

    //if no rights then rediects back to admin 	Dashboard.
    if (!$check) {
        $mesg = FlashMessage::messages('admin_access.access_denied');

        return redirect("/admin")
            ->with('message', FlashMessage::displayAlert($mesg, 'warning'));
    }
});

// This filter will redirect user to login if already logged in user try to access user registration. ----- End -----
View::composer('*', function ($view) {

    View::share('view_name', $view->getName());
});
