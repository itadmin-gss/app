<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMessage;
use App\RoleDetail;
use App\RoleFunction;
use App\User;
use App\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

/**
 * AccessLevel Controller Class.
 *
 * Will be handling all the Access level functionlity here.
 * @copyright Copyright 2014 Invortex Technology Development Team
 * @version $Id: 1.0
 */

class AccessLevelController extends Controller
{

   /**
    * To delete an Access Level
    * @params integer role_id (must be passed to delete a sepecific role)
    * @return redirects back to the same page with approproate message.
    */
    public function deleteAccessLevel($role_id)
    {
        $user_role = UserRole::find($role_id);
        //$user->delete();
        $message = FlashMessage::messages('admin.access_level_deleted');
                return Redirect::back()
                ->with('message', FlashMessage::displayAlert($message, 'success'));
    }

   /**
    * To Edit an Access Level
    * @params integer role_id (must be passed to delete a sepecific role)
    * @return redirects to the edit access leve page.
    */
    public function editAccessLevel($role_id)
    {
        $update = Request::get('update');
        if (!$update) {
            $user_role = UserRole::find($role_id);
            //$user->delete();
            return view('pages.admin.edit-access-level')
                   ->with(
                       [
                            'user_role' => $user_role,
                            'role_id' => $role_id
                            ]
                   );
        } else {
            $user_role = Request::all();
            $update_user_role = UserRole::updateRole($user_role, $role_id);
            if ($update_user_role) {
                $message = FlashMessage::messages('admin.user_role_updated');
                return Redirect::back()
                ->with('message', FlashMessage::displayAlert($message, 'success'));
            } else {
                $message = FlashMessage::messages('admin.user_role_update_error');
                return Redirect::back()
                ->with('message', FlashMessage::displayAlert($message, 'success'));
            }
        }
    }

   /**
    * To update an access Level for a user.
    * @params none
    * @return
    */
    public function updateUserAccessLevel()
    {
            $user=Auth::user();
            $userrole=UserRole::find($user->user_role_id);
            $rolefunction=RoleFunction::where('role_function', '=', 'User')->first();
            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();
        if ($roledetail->edit == 1) {
            if (Request::ajax()) {
                $user_id = Request::get('user_id');
                $role_id = Request::get('role_id');

                $user = User::find($user_id);
                $user->user_role_id = $role_id ;
                $user->save();
            }
            return $role_id;
        } else {
            return 0;
        }
    }
}
