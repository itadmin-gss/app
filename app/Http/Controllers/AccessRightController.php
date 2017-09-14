<?php

namespace App\Http\Controllers;

use App\AccessFunction;
use App\RoleDetail;
use App\RoleFunction;
use App\UserRole;
use App\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

/**
 * AccessRight Controller Class.
 *
 * Will be handling all the Access Rights functionlity here.
 * @copyright Copyright 2014 Invortex Technology Development Team
 * @version $Id: 1.0
 */

class AccessRightController extends Controller
{

   /**
    * Shows Access Rights view.
    * @params none
    * @return redirects back to the same page with approproate message.
    */
    public function index()
    {
        $access_roles = UserRole::listRoles();
        $role_functions = RoleFunction::listRoleFunctions();
        $specific_role_functions = RoleFunction::all();
        $access_functions = AccessFunction::all();
        $newroleFunction = [];

        foreach ($role_functions as $roles) {
            $results = RoleDetail::where('role_function_id', '=', $roles->id)
            ->where('role_id', '=', 1) // getting admin rights by default, which cant be changed.
            ->get(['add','edit','delete', 'view'])->toArray();

            $results = array_shift($results);
            $data[$roles->access_function_id][$roles->role_function] = $results;
        }

        return view('pages.admin.access_rights')
            ->with([
                    'role_id' => 1,
                    'userRoles' => $access_roles,
                    'roleFunctions' => $data]);
    }

   /**
    * Gets the Role Details for every Access Role via ajax.
    * @params role_id passed via ajax.
    * @return returns the html from admin/ajaxHtml/role_functions.blade.php
    */
    public function getRoleDetails()
    {
        $role_id = Request::get('role_id');
        if (Request::ajax()) {
            $access_roles = UserRole::listRoles();
            $role_functions = RoleFunction::listRoleFunctions();
            $specific_role_functions = RoleFunction::all();
            $access_functions = AccessFunction::all();
            $newroleFunction = [];


            foreach ($role_functions as $roles) {
                $results = RoleDetail::where('role_function_id', '=', $roles->id)
                ->where('role_id', '=', $role_id)
                ->get(['add','edit','delete', 'view'])->toArray();

                $results = array_shift($results);
                $data[$roles->access_function_id][$roles->role_function] = $results;
            }

            $data = view('pages.admin.ajaxHtml.role_functions')
                    ->with([
                    'role_id' => $role_id,
                    'userRoles' => $access_roles,
                    'roleFunctions' => $data])->render();
            return Response::json($data);
        }
    }


   /**
    * Update Access Rights for a specific role via ajax.
    * @params
        role_id : passed via ajax.
        data    : contains the chechboxes values against specific rights.
    * @return updates the rights for a role.
    */
    public function updateAccessRights()
    {
        $role_id = Request::get('role_id');
        $data = Request::get('data');
        $users_role = RoleDetail::where('role_id', '=', $role_id)->get();

        foreach ($data as $dat) {
            foreach ($users_role as $user_role) {
                $str =  str_replace(" ", "", $user_role->roleFunctions->role_function);
                $role_funcion = snake_case($str);

                $r = RoleDetail::where('role_function_id', '=', $user_role->role_function_id)
                ->where('role_id', '=', $user_role->role_id)->first(['id']);

                $rr = RoleDetail::find($r->id);

                if (strstr($dat['name'], $role_funcion) && strstr($dat['name'], 'add')) {
                    $rr->add = $dat['value'];
                    $rr->save();
                } else if (strstr($dat['name'], $role_funcion) && strstr($dat['name'], 'edit')) {
                    $rr->edit = $dat['value'];
                    $rr->save();
                } else if (strstr($dat['name'], $role_funcion) && strstr($dat['name'], 'delete')) {
                    $rr->delete = $dat['value'];
                    $rr->save();
                } else if (strstr($dat['name'], $role_funcion) && strstr($dat['name'], 'view')) {
                    $rr->view = $dat['value'];
                    $rr->save();
                }
            } // user_role foreach
        } // data foreach
    }

    public static function checkAccessRight($perm, $action)
    {

        if (Auth::check()) {
            $user=Auth::user();
            $user_role_id=Auth::user()->user_role_id;
            $usertype=UserType::getUserTypeByID($user->type_id);

            $role_detail = RoleDetail::where('role_id', '=', $user_role_id)
            ->where('role_function_id', '=', $perm)->first([$action]);
            if (($usertype=='admin' || $usertype=='user') && $role_detail->$action == 1) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
