<?php

namespace App\Http\Controllers;

use App\City;
use App\EmailNotification;
use App\Http\Requests\Request;
use App\RoleDetail;
use App\RoleFunction;
use App\SpecialPrice;
use App\User;
use App\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;




/**

 * AccessLevel Controller Class.

 *

 * Will be handling all the Access level functionlity here.

 * @copyright Copyright 2014 Invortex Technology Development Team

 * @version $Id: 1.0

 */

class CommonController extends Controller
{



    /**

     * Update role status called via ajax.

     * @params user_id : the user id that needs to be deleted.

     * @return redirects with appropriate message of success or error.

     */

    public function updateStatus()
    {

        $user=Auth::user();

        $userrole=UserRole::find($user->user_role_id);

        $type=Input::get('type');

        $status = Input::get('status');

        $db_table = Input::get('db_table');

        $id = Input::get('id');

        if ($type == 'vendor') {
            $rolefunction=RoleFunction::where('role_function', '=', 'Vendor')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->edit == 1) {
                if (Request::ajax()) {
                    DB::table($db_table)

                            ->where('id', $id)

                            ->update(['status' => $status]);
                }



                if ($status==1) {
                    $user=DB::table($db_table)->where('id', $id)->first();

                    $email_data = [

                    'user_email_template'=>EmailNotification::$user_email_approved_template." <a href='".URL::to('/')."'> Please click here to complete your profile.</a>"

                    ];

         



                    Email::send($user->email, 'Your Account Information', 'emails.user_email_template', $email_data);
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'user') {
            $rolefunction=RoleFunction::where('role_function', '=', 'User')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->edit == 1) {
                if (Request::ajax()) {
                    DB::table($db_table)

                            ->where('id', $id)

                            ->update(['status' => $status]);
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'access_level') {
            $rolefunction=RoleFunction::where('role_function', '=', 'Access Level')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->edit == 1) {
                if (Request::ajax()) {
                    DB::table($db_table)

                            ->where('id', $id)

                            ->update(['status' => $status]);
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'access_right') {
        } elseif ($type == 'customer') {
            $rolefunction=RoleFunction::where('role_function', '=', 'Customer')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->edit == 1) {
                if (Request::ajax()) {
                    DB::table($db_table)

                            ->where('id', $id)

                            ->update(['status' => $status]);
                }



                if ($status==1) {
                    $user=DB::table($db_table)->where('id', $id)->first();

                    $email_data = [

                    'user_email_template'=>EmailNotification::$user_email_approved_template." <a href='".URL::to('/')."'> Please click here to complete your profile.</a>"

                    ];

         



                    Email::send($user->email, 'Your Account Information', 'emails.user_email_template', $email_data);
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'asset') {
        } elseif ($type == 'service') {
            $rolefunction=RoleFunction::where('role_function', '=', 'Service')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->edit == 1) {
                if (Request::ajax()) {
                    DB::table($db_table)

                            ->where('id', $id)

                            ->update(['status' => $status]);
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'special_price') {
            if (Request::ajax()) {
                DB::table($db_table)

                    ->where('id', $id)

                    ->update(['status' => $status]);
            }

                return 1;
        } elseif ($type == 'workorder') {
        }
    }



    public function deleteRecord()
    {

        $user=Auth::user();

        $userrole=UserRole::find($user->user_role_id);

        $type=Input::get('type');

        $db_table = Input::get('db_table');

        $id = Input::get('id');

        if ($type == 'vendor') {
            $rolefunction=RoleFunction::where('role_function', '=', 'Vendor')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->delete == 1) {
                if (Request::ajax()) {
                    User::find($id)->delete();
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'user') {
            $rolefunction=RoleFunction::where('role_function', '=', 'User')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->delete == 1) {
                if (Request::ajax()) {
                    User::find($id)->delete();
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'access_level') {
            $rolefunction=RoleFunction::where('role_function', '=', 'Access Level')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->delete == 1) {
                if (Request::ajax()) {
                    RoleFunction::find($id)->delete();
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'access_right') {
        } elseif ($type == 'customer') {
            $rolefunction=RoleFunction::where('role_function', '=', 'Customer')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->delete == 1) {
                if (Request::ajax()) {
                    User::find($id)->delete();
                }

                return 1;
            } else {
                return 0;
            }
        } elseif ($type == 'asset') {
        } elseif ($type == 'service') {
        } elseif ($type == 'special_price') {
            if (Request::ajax()) {
                SpecialPrice::find($id)->delete();
            }

                return 1;
        } elseif ($type == 'workorder') {
        } elseif ($type == 'city') {
            $rolefunction=RoleFunction::where('role_function', '=', 'City')->first();

            $roledetail=RoleDetail::where('role_id', '=', $user->user_role_id)->where('role_function_id', '=', $rolefunction->id)->first();

            if ($roledetail->delete == 1) {
                if (Request::ajax()) {
                    City::find($id)->delete();
                }

                return 1;
            } else {
                return 0;
            }
        }
    }
}
