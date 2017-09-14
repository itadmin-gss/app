<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

App::before(function ($request) {
    //
});


App::after(function ($request, $response) {
    //
});

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter('auth', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('/');
        }
    }
});


Route::filter('auth.basic', function () {
    return Auth::basic();
});
/*
  |--------------------------------------------------------------------------
  | Guest Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('guest', function () {
    if (Auth::check()) {
        return Redirect::to('/');
    }
});

/*
  |--------------------------------------------------------------------------
  | CSRF Protection Filter
  |--------------------------------------------------------------------------
  |
  | The CSRF filter is responsible for protecting your application against
  | cross-site request forgery attacks. If this special token in a user
  | session does not match the one given in this request, we'll bail.
  |
 */

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

// This filter will redirect user to login if already logged in user try to access user registration. ----- Start -----
Route::filter('loginCheck', function () {
    if (Auth::check()) {
        return Redirect::to("/");
    }
});


Route::filter('customer', function () {
    $type_id = Auth::user()->type_id;
    $user_type = UserType::getUserTypeByID($type_id);

    $redirect_to = $user_type;

    if ($user_type != 'customer') {
        return Redirect::to($redirect_to);
    }
});

Route::filter('adminCheck', function () {
    $user=Auth::user();
    $usertype=UserType::getUserTypeByID($user->type_id);
    if ($usertype!='admin' && $usertype != 'user') {
        return Redirect::to("/");
    }
});


Route::filter('vendorCheck', function () {
    $user=Auth::user();
    $usertype=UserType::getUserTypeByID($user->type_id);
    if ($usertype!='vendors') {
        return Redirect::to("/");
    }
});


Route::filter('customerCheck', function () {
    $user=Auth::user();
    $usertype=UserType::getUserTypeByID($user->type_id);
    if ($usertype!='customer') {
        return Redirect::to("/");
    }
});

Route::filter('adminRightsCheck', function () {
    
    $check = 1;
    if (Route::currentRouteAction() == 'AdminController@listUser') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AdminController@addUser') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AdminController@editProfileAdmin' || Route::currentRouteAction() == 'AccessLevelController@updateUserAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
    } elseif (Route::currentRouteAction() == 'AdminController@deleteUser') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'User')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'delete');
    } elseif (Route::currentRouteAction() == 'AdminController@addAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AccessLevelController@editAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
    } elseif (Route::currentRouteAction() == 'AdminController@listAccessLevel') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Level')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AccessRightController@index') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Access Rights')
        ->first(array('id'));
        
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'CustomerController@createCustomerAdmin') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Customer')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'CustomerController@listCustomerAdmin') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Customer')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AdminController@addVendor') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Vendor')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AssetController@addAdminAsset') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'AssetController@editAdminAsset') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
    } elseif (Route::currentRouteAction() == 'AssetController@listAdminAssets') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Asset')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'ServiceController@addAdminService') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Service')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'add');
    } elseif (Route::currentRouteAction() == 'ServiceController@listAdminServices') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Service')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    } elseif (Route::currentRouteAction() == 'AdminController@listMaintenanceRequest') {
        // check for access rights of view for list users
        $role_function_id = RoleFunction::where('role_function', '=', 'Service Request')
        ->first(array('id'));
        $check = AccessRightController::checkAccessRight($role_function_id->id, 'view');
    }
    
    //if no rights then rediects back to admin 	Dashboard.
    if (!$check) {
        $mesg = FlashMessage::messages('admin_access.access_denied');
        return Redirect::to("/admin")
        ->with('message', FlashMessage::displayAlert($mesg, 'warning'));
    }
});
// This filter will redirect user to login if already logged in user try to access user registration. ----- End -----
View::composer('*', function ($view) {

    View::share('view_name', $view->getName());
});
