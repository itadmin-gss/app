<?php

namespace App\Http\Controllers;

use App\AdditionalServiceItem;
use App\Asset;
use App\AssignRequest;
use App\AssignRequestBid;
use App\AssignRequestBidsImage;
use App\BidRequest;
use App\BidRequestedService;
use App\BidServiceImage;
use App\City;
use App\CustomerType;
use App\Http\Requests\Request;
use App\Invoice;
use App\JobType;
use App\MaintenanceBid;
use App\MaintenanceRequest;
use App\Order;
use App\OrderCustomData;
use App\RequestedBid;
use App\RequestedService;
use App\RoleDetail;
use App\RoleFunction;
use App\Service;
use App\ServiceCategory;
use App\ServiceFieldDetail;
use App\SpecialPrice;
use App\State;
use App\User;
use App\UserRole;
use App\UserType;
use App\VendorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use JeroenDesloovere\Geolocation\Geolocation;

/**
 * Admin Controller Class.
 *
 * Handles Admin redirects.
 * @copyright Copyright 2014 Invortex Technology Development Team
 * @version $Id: 1.0
 */

class AdminController extends Controller
{

    /**
     * Redirects the admin to admin dashboard
     * @params none
     * @return redirects admin to admin dashboard.
     */
    public function index()
    {

        $requestsNew =MaintenanceRequest::where('status', '=', 1)->orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();

        $requests    =MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();


        $orders_process = Order::where('status', '=', 0)->take(5)->get();
        $orders_completed = Order::where('status', '=', 1)->take(5)->get();
        $recent_orders = Order::take(5)->get();
        $recent_assets = Asset::take(5)->get();
        $orderCounterDashboard=[];


        $work_orders_count = DB::table('orders')
        ->select(DB::raw('count(id) as numbers, status'))
        ->groupBy('status')
        ->get();

        foreach ($work_orders_count as $datacounter) {
            $orderCounterDashboard[$datacounter->status]=$datacounter->numbers;
        }
        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        $numberofrequestids=[];
        foreach ($requests as $mdata) {
              $request_service_ids = [];
              $request_ids[] = $mdata->id;
            foreach ($mdata->requestedService as $rqdata) {
                $request_service_ids[] = $rqdata->id;
            }
              $assigned_request_ids = [];
              $assign_requests = AssignRequest::where('request_id', '=', $mdata->id)
              ->where('status', "!=", 2)
              ->select('request_id')->get();

            foreach ($assign_requests as $adata) {
                $assigned_request_ids[] = $adata->request_id;
            }

              $numberofrequestids['requested_services_count'][$mdata->id] = count($request_service_ids);
              $numberofrequestids['assigned_services_count'][$mdata->id] = count($assigned_request_ids);
        }




        return view('pages.admin.dashboard')->with(
            ['requests' => $requests,
            'requestsNew' => $requestsNew,
            'orders_process' => $orders_process,
            'orders_completed' => $orders_completed,
            'recent_orders' => $recent_orders,
            'recent_assets' => $recent_assets,
            'orderCounterDashboard'=> $orderCounterDashboard,
            'numberofrequestids' => $numberofrequestids
            ]
        );
    }

    public function swapDB()
    {
        return view('pages.admin.showdb');
    }

    public function swapDBNow()
    {
        $data = Input::all();
        if ($data['db'] == 'old') {
            Session::put('swapdb', 'mysql2');
            return Redirect::back()->with('swapped', 'Database has been swapped to OLD!');
        } else if ($data['db'] == 'new') {
            Session::put('swapdb', 'mysql');
            return Redirect::back()->with('swapped', 'Database has been swapped to NEW!');
        }
    }

    /**
     * Gets the user to the add user page
     * @params none
     * @return passes user roles to the User Role Drop Down.
     */
    public function addUser()
    {
        $roles = UserRole::where('status', '=', 1)->get(['id', 'role_name']);
        $user_roles = [];
        foreach ($roles as $role) {
            $user_roles[$role->id] = $role->role_name;
        }

        return view('pages.admin.adduser')->with('user_roles', $user_roles);
    }

    /**
     * Gets the user to the add user page
     * @params none
     * @return passes user roles to the User Role Drop Down.
     */
    public function addVendor()
    {
        $user = Auth::user();
        if ($user) {
            return view('pages.admin.addvendor');
        } else {
            return redirect('list-vendors');
        }
    }

    public function processAddVendor()
    {
        $rules = [
        'first_name' => 'required|min:2|max:80|alpha',
        'last_name' => 'required|min:2|max:80|alpha',
        'email' => 'required|email|unique:users|between:3,64',
        'password' => 'required|between:4,20'
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $profile_error_messages = General::validationErrors($validator);
            return $profile_error_messages;
        } else {
            $vendor_add_message = FlashMessage::messages('admin.vendor_add_success');
            $data = Input::all();
            $user_type_id = UserType::where('title', '=', 'vendors')->first();
            $user_types = UserType::find($user_type_id->id);
            $user_roles = UserRole::where('role_name', '=', $user_types->title)->first();
            $data['type_id'] = $user_type_id->id;
            // $passowrd = rand(); //Get random password to send user
            $passowrd=$data['password'];
            $data['password'] = Hash::make($passowrd);
            $data['status'] = 1;
            $save = User::createUser($data);
            if ($save) {
                $data['password'] = $passowrd;
                $from_email = config('app.admin_email');
                Mail::send('emails.admin_customer_created', $data, function ($message) use ($from_email) {

                    $message->to(Input::get('email'), Input::get('first_name') . ' ' . Input::get('last_name'))
                    ->subject('Vendor Created By Admin!')
                    ->from($from_email, 'GSS');
                });

                Session::flash('message', $vendor_add_message);
                return FlashMessage::displayAlert($vendor_add_message . $passowrd, 'success');
            }
        }
    }

    public function listVendors()
    {
        $vendors = User::getAllVendors();

        $user = new User;


        $db_table = $user->getTable();
        return view('pages.admin.list_vendors')->with(['vendors' => $vendors, 'db_table' => $db_table]);
    }
    

    public function listVendorsDynamically()
    {
        $vendors = User::getAllVendorsInDatatable();

        $user = new User;


        $db_table = $user->getTable();
        echo json_encode($vendors);
    }
    public function listVendorsSummary()
    {
        $vendors = User::getAllVendors();

        $user = new User;


        $db_table = $user->getTable();

        $requests = AssignRequest::where('status', "=", 1)->groupBy('request_id')->orderBy('id', 'desc')->get();
        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $customerfirstname="";
            if (isset($request->maintenanceRequest->user->first_name)) {
                $customerfirstname=$request->maintenanceRequest->user->first_name;
            }

            $customerlastname="";
            if (isset($request->maintenanceRequest->user->last_name)) {
                $customerlastname=$request->maintenanceRequest->user->last_name;
            }


            $vendorfirstname="";
            if (isset($request->user->first_name)) {
                $vendorfirstname=$request->user->first_name;
            }

            $vendorlastname="";
            if (isset($request->user->last_name)) {
                $vendorlastname=$request->user->last_name;
            }



            $services = AssignRequest::where('request_id', '=', $request->maintenanceRequest->id)->where('status', '!=', 2)->orderBy('id', 'desc')->get();
            $assign_requests[$i]['request_id'] = $request->maintenanceRequest->id;
            $assign_requests[$i]['status'] = $request->maintenanceRequest->status;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['vendor_name'] = $vendorfirstname .' '.  $vendorlastname;
            $assign_requests[$i]['customer_name'] = $customerfirstname .' '. $customerlastname;
            $assign_requests[$i]['asset_number'] = $request->maintenanceRequest->asset->asset_number;
            $assign_requests[$i]['property_address'] = $request->maintenanceRequest->asset->property_address;

            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->maintenanceRequest->created_at)) ;
            $assign_requests[$i]['emergency_request']=$request->maintenanceRequest->emergency_request;
            $assign_requests[$i]['due_date'] = $request->maintenanceRequest->created_at;
            foreach ($services as $service) {
                $assign_requests[$i]['service_code'].='&diams; '.$service->requestedService->service->service_code . ', <br>';
                $assign_requests[$i]['service_name'].='&diams; '.$service->requestedService->service->title . ', <br>';
            }
            $i++;
        }



        $orders = Order::orderBy('id', 'desc')->get();
        $list_orders = [];
        $i = 0;
        foreach ($orders as $order) {
             $customerfname='';
            if (isset($order->customer->first_name)) {
                $customerfname=$order->customer->first_name;
            }

            $customerlname='';
            if (isset($order->customer->last_name)) {
                  $customerlname=$order->customer->last_name;
            }


            $vendorfname='';
            if (isset($order->vendor->first_name)) {
                  $vendorfname=$order->vendor->first_name;
            }
            $vendorlname='';
            if (isset($order->vendor->last_name)) {
                  $vendorlname=$order->vendor->last_name;
            }


            $order_details = ($order->orderDetail);
            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $customerfname . ' ' . $customerlname;
            $list_orders[$i]['vendor_name'] = $vendorfname. ' ' . $vendorlname;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['order_date'] = $order->created_at;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;


            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;



            foreach ($order_details as $order_detail) {
                  $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }



        $invoices = Invoice::listAll(3);


        $list_invoice = [];
        $i = 0;


        foreach ($invoices as $invoice) {
                $order_details = ($invoice->order->orderDetail);

                $list_invoice[$i]['order_id'] = $invoice->order_id;
                $list_invoice[$i]['customer_name'] = $invoice->order->customer->first_name . ' ' . $invoice->order->customer->last_name;
                $list_invoice[$i]['vendor_name'] = $invoice->order->vendor->first_name . ' ' . $invoice->order->vendor->last_name;
                $list_invoice[$i]['asset_number'] = $invoice->order->maintenanceRequest->asset->asset_number;
                $list_invoice[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($invoice->order->created_at));
                $list_invoice[$i]['service_name'] = '';
                $list_invoice[$i]['status'] = $invoice->status;
                $list_invoice[$i]['price'] = $invoice->total_amount;
            foreach ($order_details as $order_detail) {
                $list_invoice[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
               $i++;
        }






        $requests = BidRequest::orderBy('id', 'desc')->get();


        $assign_bids = [];
        $i = 0;
        foreach ($requests as $request) {
                $services = BidRequestedService::where('request_id', '=', $request->id)
                ->where('status', '=', 1)
                ->get();
                $assign_bids[$i]['request_id'] = $request->id;
                $assign_bids[$i]['service_code'] = '';
                $assign_bids[$i]['service_name'] = '';
                $assign_bids[$i]['customer_name'] = $request->user->first_name .' '. $request->user->last_name;
                $assign_bids[$i]['asset_number'] = $request->asset->asset_number;
                $assign_bids[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->created_at));
                $assign_bids[$i]['due_date'] = $request->created_at;
            foreach ($services as $service) {
                 $assign_bids[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                 $assign_bids[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
            }
                $i++;
        }






        return view('pages.admin.list_vendors_summary')
        ->with('assign_requests', $assign_requests)
        ->with('orders', $list_orders)
        ->with('invoices', $list_invoice)
        ->with('assign_bids', $assign_bids)

        ->with('db_table', $db_table);
    }

    public function deleteVendor($vendor_id)
    {
          $user = User::find($vendor_id);
        //$user->delete();
          $message = FlashMessage::messages('admin.user_deleted');
          return Redirect::back()
          ->with('message', FlashMessage::displayAlert($message, 'success'));
    }

    /**
     * Gets the user to the edit user page, checks if the form is submitted with updated user data or to
      show just the form pre filled.
     * @params user_id : the user id that needs to be updated.
     * @return redirects with appropriate message of success or error.
     */
    public function editUser($user_id)
    {
        $update_user = Input::get('update_user');
        if (!$update_user) {
            $user = User::find($user_id);
            $role_id = $user->userRole->id;
            $roles = UserRole::all();
            $user_roles = [];
            foreach ($roles as $role) {
                $user_roles[$role->id] = $role->role_name;
            }
            return view('pages.admin.edituser')
            ->with(
                ['user' => $user,
                'user_roles' => $user_roles,
                'role_id' => $role_id
                ]
            );
        } else {
            $user = Input::all();
            $save = User::updateAdminUser($user, $user_id);
            if ($save) {
                $message = FlashMessage::messages('admin.user_created');
                return Redirect::back()
                ->with('message', FlashMessage::displayAlert($message, 'success'));
            }
            return $profile_error_messages;
        }
    }

    /**
     * Deletes a user
     * @params user_id : the user id that needs to be deleted.
     * @return redirects with appropriate message of success or error.
     */
    public function deleteUser($user_id)
    {
        $user = User::find($user_id);
        //$user->delete();
        $message = FlashMessage::messages('admin.user_deleted');
        return Redirect::back()
        ->with('message', FlashMessage::displayAlert($message, 'success'));
    }

    /**
     * Updates a User Status to active or inavtive.
      called via ajax.
     * @params user_id : the user id that needs to be deleted.
     * @return redirects with appropriate message of success or error.
     */
    public function updateUserStatus($user_id)
    {
        if (Request::ajax()) {
            $activityId = Input::get('activityId');
            $check = AccessRightController::checkAccessRight($role_function_id->id, 'edit');
            if ($check) {
                if ($activityId == 'active') {
                    DB::update('update users set status = 0 where id = ?', [$user_id]);
                } else {
                    DB::update('update users set status = 1 where id = ?', [$user_id]);
                }
            } else {
                return Response::json('Access Denied!');
            }
        }
    }

    /**
     * Updates a User Status to active or inavtive.
      called via ajax.
     * @params user_id : the user id that needs to be deleted.
     * @return redirects with appropriate message of success or error.
     */
    public function listUser()
    {
        $users = User::getAdminUser();
        $user_roles = UserRole::get(['id', 'role_name']);
        $user_table = new User;
        $db_table = $user_table->getTable();

        foreach ($user_roles as $role) {
            $roles[$role->id] = $role->role_name;
        }

        return view('pages.admin.listuser')
        ->with([
        'users' => $users,
        'userRoles' => $roles,
        'db_table' => $db_table]);
    }

    /**
     * Adds a new admin user.
     * @params data from the add new user form.
     * @return redirects with appropriate message of success or error.
     */
    public function addNewUser()
    {

        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $email = Input::get('email');
        $user_role_id = Input::get('role_id');
        $password = Input::get('password');

        $rules = [
        'first_name' => 'required|between:3,55',
        'last_name' => 'required|between:3,55',
        'email' => 'required|email|unique:users|between:3,64',
        'password' => 'required|between:4,20'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-user')
            ->withErrors($validator)
            ->withInput();
        } else {
            $user = new User;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user_type_id = UserType::where('title', '=', 'user')->first();
            $user->type_id = $user_type_id->id; // for admin users
              // $password = rand(); //Get random password to send user
            

            $user->password = Hash::make($password);
            $user->user_role_id = $user_role_id;
            $user->status = 1;
            $user_data = Input::all();

            $user_data['password'] = $password;
            //echo '<pre>'; print_r($user_data); exit;

            $user->save();
            $from_email = config('app.admin_email');
            Mail::send('emails.admin_customer_created', $user_data, function ($message) use ($from_email) {

                $message->to(Input::get('email'), Input::get('first_name') . ' ' . Input::get('last_name'))
                ->subject('User Created By Admin!')
                ->from($from_email, 'GSS');
            });
            //Send admin email to create user profile
            
            Mail::send('emails.admin_customer_created', $user_data, function ($message) use ($from_email) {

                $id = Auth::user()->id;
                $admin_email= User::getEmail($id);
                $message->to($admin_email, Input::get('first_name') . ' ' . Input::get('last_name'))
                ->subject('User Created By Admin!')
                ->from($from_email, 'GSS');
            });


            
            $message = FlashMessage::messages('admin.user_created');

            return Redirect::back()
            ->with('message', FlashMessage::displayAlert($message, 'success'));
        }
          return view('pages.admin.adduser');
    }

        // func to list city by shm
    public function listCity()
    {
        $cities = City::getAllCities();
        // echo "<pre>"; print_r($cities); exit();
        // $user_roles = UserRole::get(array('id', 'role_name'));
        $city_table = new City;
        $db_table = $city_table->getTable();

        // foreach ($user_roles as $role) {
        //   $roles[$role->id] = $role->role_name;
        // }

        return view('pages.admin.listcity')
        ->with([
        'cities' => $cities,
      // 'userRoles' => $roles,
        'db_table' => $db_table]);
    }

      // func to add new city by shm
    public function addCity()
    {
        $states = State::where('status', '=', 1)->get(['id', 'name']);
        $city_state = [];
        foreach ($states as $state) {
            $city_state[$state->id] = $state->name;
        }

        return view('pages.admin.addcity')->with('city_state', $city_state);
    }

    // fucn to post new city by shm
    public function addNewCity()
    {

        $name = Input::get('name');
        $state_id = Input::get('state_id');

        $rules = [
        'name' => 'required|between:3,55',
        'state_id' => 'required',
        ];

      // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
      // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-city')
            ->withErrors($validator)
            ->withInput();
        } else {
            $city = new City;
            $city->name = $name;
            $city->state_id = $state_id;
            $city->status = 1;


            $city->save();
            $message = FlashMessage::messages('admin.city_created');
            return Redirect::back()
            ->with('message', FlashMessage::displayAlert($message, 'success'));
        }
        return view('pages.admin.addcity');
    }

    /**
     * Shows add new access level/role form.
     * @params data from the add new access level/role form.
     * @return redirects with appropriate message of success or error.
     */
    public function addAccessLevel()
    {
        $submitted = Input::get('submitted');
        if ($submitted) {
            $message = FlashMessage::messages('admin.access_level_success');
            $rules = [
            'role_name' => 'required',
              ];

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                  $messages = $validator->messages();

                  return redirect('add-access-level')
                  ->withErrors($validator);
            } else {
                  $user_role = new UserRole;
                  $user_role->role_name = Input::get('role_name');
                  $user_role->description = Input::get('description');
                  $user_role->status = Input::get('status');
                  $save = $user_role->save();
                if ($save) {
                    $role_id = $user_role->id;
                            // adding defualt access level (i.e 0 for edit update and delete) for the newly created access role.
                    $role_functions = RoleFunction::all()->toArray();
                    foreach ($role_functions as $role_func) {
                        $data['role_id'] = $role_id;
                        $data['role_function_id'] = $role_func['id'];
                        $data['add'] = 0;
                        $data['edit'] = 0;
                        $data['delete'] = 0;
                        $data['view'] = 0;
                        $data['status'] = 1;
                        RoleDetail::addRoleDetail($data);
                    }
                }
                  return Redirect::back()
                  ->with('message', FlashMessage::displayAlert($message, 'success'));
            }
        } else {
            return view('pages.admin.add_access_level');
        }
    }

    /**
     * List all the roles/access levels.
     * @params none.
     * @return none.
     */
    public function listAccessLevel()
    {
        $userRoles = UserRole::getAllRoles();
        $role_obj = new UserRole;
        $db_table = $role_obj->getTable();

        return view('pages.admin.list_access_level')
        ->with([
        'userRoles' => $userRoles,
        'db_table' => $db_table]);
    }

    /*
     * Function Name : listMaintenanceRequest
     * @param:none
     * @description: This function is begin used for listing all over the requests of maintenance in admin
     *
     */

    public function listMaintenanceRequest()
    {

        $request_maintenance = MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')
        ->where('status', '!=', 5)->get();

        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        $numberofrequestids=[];
        foreach ($request_maintenance as $mdata) {
            $request_service_ids = [];
            $request_ids[] = $mdata->id;
            foreach ($mdata->requestedService as $rqdata) {
                $request_service_ids[] = $rqdata->id;
            }
            $assigned_request_ids = [];
            $assign_requests = AssignRequest::where('request_id', '=', $mdata->id)
            ->where('status', "!=", 2)
            ->select('request_id')->get();

            foreach ($assign_requests as $adata) {
                $assigned_request_ids[] = $adata->request_id;
            }

            $numberofrequestids['requested_services_count'][$mdata->id] = count($request_service_ids);
            $numberofrequestids['assigned_services_count'][$mdata->id] = count($assigned_request_ids);
        }




        $request_maintenance_obj = new MaintenanceRequest;
        $db_table = $request_maintenance_obj->getTable();

        return view('pages.admin.listmaintenancerequest')
        ->with([
        'request_maintenance' => $request_maintenance,
        'numberofrequestids' => $numberofrequestids,
        'db_table' => $db_table
        ]);
    }




      /*
     * Function Name : listMaintenanceRequest
     * @param:none
     * @description: This function is begin used for listing all over the requests of maintenance in admin
     *
     */

    public function listBidRequest()
    {

        $request_maintenance = MaintenanceBid::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')
        ->where('status', '!=', 5)->get();

        $request_maintenance_obj = new MaintenanceBID;
        $db_table = $request_maintenance_obj->getTable();

        return view('pages.admin.listmaintenancebid')
        ->with([
        'request_maintenance' => $request_maintenance,
        'db_table' => $db_table
        ]);
    }








     /*
     * Function Name : listMaintenanceRequest
     * @param:none
     * @description: This function is begin used for listing all over the requests of maintenance in admin
     *
     */

    public function listAssignedMaintenanceRequest()
    {

        $request_maintenance = MaintenanceRequest::orderBy('id', 'desc')->get();

        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        foreach ($request_maintenance as $mdata) {
            $request_service_ids = [];
            $request_ids[] = $mdata->id;
            foreach ($mdata->requestedService as $rqdata) {
                $request_service_ids[] = $rqdata->id;
            }
            $assigned_request_ids = [];
            $assign_requests = AssignRequest::where('request_id', '=', $mdata->id)
            ->where('status', "!=", 2)
            ->select('request_id')
            ->get();

            foreach ($assign_requests as $adata) {
                $assigned_request_ids[] = $adata->request_id;
            }

            $numberofrequestids['requested_services_count'][$mdata->id] = count($request_service_ids);
            $numberofrequestids['assigned_services_count'][$mdata->id] = count($assigned_request_ids);
        }


        $request_maintenance_obj = new MaintenanceRequest;
        $db_table = $request_maintenance_obj->getTable();

        return view('pages.admin.listassignedmaintenancerequest')
        ->with([
        'request_maintenance' => $request_maintenance,
        'numberofrequestids' => $numberofrequestids,
        'db_table' => $db_table
        ]);
    }


    /*
     * Function Name : viewMaintenanceRequest
     * @param:id
     * @description: This function is begin used for  viewing  all over the  details requests of maintenance in admin
     *
     */


    public function viewMaintenanceRequest($maintenance_request_id = "")
    {


        $request_maintenance = MaintenanceRequest::find($maintenance_request_id);

//Multiple Services:
//If multiple services are requested and only 1 has been assigned, request should stay in New Request status until remaining services have been assigned. Do NOT move/change to Un Assigned status. Un Assigned status should not be active anymore.
      // MaintenanceRequest::where('id','=',$maintenance_request_id)
      //                       ->update(array('status'=>'2')); //Reviewed by Admin

        return view('pages.admin.viewmaintenancerequest')
        ->with([
        'request_maintenance' => $request_maintenance,
        ]);
    }



                           /*
     * Function Name : viewMaintenanceRequest
     * @param:id
     * @description: This function is begin used for  viewing  all over the  details requests of maintenance in admin
     *
     */


    public function viewBiddingRequest($maintenance_request_id = "")
    {


        $request_maintenance = MaintenanceBid::find($maintenance_request_id);

//Multiple Services:
//If multiple services are requested and only 1 has been assigned, request should stay in New Request status until remaining services have been assigned. Do NOT move/change to Un Assigned status. Un Assigned status should not be active anymore.
      // MaintenanceRequest::where('id','=',$maintenance_request_id)
      //                       ->update(array('status'=>'2')); //Reviewed by Admin
        $layoutBid="viewbiddingrequest";
        if ($request_maintenance->status==8) {
             $layoutBid="viewbiddingrequestwhencustomerapprove";
        } elseif ($request_maintenance->status==3) {
             $layoutBid="viewbiddingrequestwhenvendorcompletedbid";
        }
        return view('pages.admin.'.$layoutBid)
        ->with([
        'request_maintenance' => $request_maintenance,
        ]);
    }


    /*
    Cancel Request @param id
    */
    public function cancelMaintenanceRequest($maintenance_request_id = "")
    {
        MaintenanceRequest::where('id', '=', $maintenance_request_id)
                            ->update(['status'=>'4']); //Cancelled by Admin
                            return Redirect::back()
                            ->with('message', FlashMessage::displayAlert("Request has been cancelled", 'success'));
    }


                            /*
    Cancel Request @param id
    */
    public function cancelBiddingRequest($maintenance_request_id = "")
    {
        MaintenanceBid::where('id', '=', $maintenance_request_id)
                            ->update(['status'=>'5']); //Cancelled by Admin
                            return Redirect::back()
                            ->with('message', FlashMessage::displayAlert("Request has been cancelled", 'success'));
    }

           /*
    Delete Request @param id
    */
    public function deleteMaintenanceRequest($maintenance_request_id = "")
    {
        MaintenanceRequest::where('id', '=', $maintenance_request_id)
                            ->update(['status'=>'5']); //deleted by Admin
                            return Redirect::back()
                            ->with('message', FlashMessage::displayAlert("Request has been deleted", 'success'));
    }

    /*
     * Function Name : assetView
     * @param:$asset_id
     * @description: This function is being used for asset view popup
     *
     */

    public function assetView($asset_id = "")
    {
        $asset = Asset::find($asset_id);

        return view('pages.admin.assetview')
        ->with([
        'asset' => $asset,
        ]);
    }

    /*
     * Function Name : showServices
     * @param:$ma
     * @description: This function is being used for asset view popup
     *
     */

    public function showMaintenanceServices($maintenance_request_id = "")
    {
        $request_maintenance = MaintenanceRequest::find($maintenance_request_id);

        $already_assigned_users = [];
        foreach ($request_maintenance->assignRequest as $assigned_users) {
            if ($assigned_users->status == 2) {
                $already_assigned_users[] = $assigned_users->vendor_id;
            }
        }

        $assign_requests = AssignRequest::where('request_id', '=', $maintenance_request_id)
        ->where("status", "!=", 2)
        ->select('requested_service_id', 'vendor_id', 'status')->get();


        $assignedservice = [];
        foreach ($assign_requests as $data) {
            $assignedservice[] = $data->requested_service_id;
        }
      // For declined request
        $assign_requests_service = AssignRequest::where('request_id', '=', $maintenance_request_id)
        ->where('status', '=', 2)
        ->orwhereIn('requested_service_id', [3, 4])
        ->orwhereIn('request_id', [$maintenance_request_id])
        ->select('requested_service_id', 'vendor_id', 'status')
        ->get();


        $assigned_service_request = [];
        foreach ($assign_requests_service as $data) {
            $assigned_service_request[] = $data->requested_service_id;
        }

        $RequestedService =  RequestedService::where('request_id', '=', $maintenance_request_id)->get();

        $RequestedServiceIDS=[];
        foreach ($RequestedService as $value) {
            $RequestedServiceIDS[]=$value['service_id'];
        }
        $userobj = new User;
        $lat = $request_maintenance->asset->latitude;
        $lon = $request_maintenance->asset->longitude;

     // $vendors = $userobj->getUserByTypeId(3, $lat, $lon, 600, $RequestedServiceIDS);

        $vendors = User::getVendors();
        $techDatalatitude =[];

        foreach ($vendors as $value) {
            $vendorServicesIds=[];

            foreach ($value->vendorService as $dataVendor) {
                $vendorServicesIds[]=$dataVendor->service_id;
            }

            $resultCommonServices = array_intersect($vendorServicesIds, $RequestedServiceIDS);
       
            $zip_codes_comma_seprated = explode(",", $value->available_zipcodes);
            if ((!empty($resultCommonServices))  && ($zip_codes_comma_seprated != "" && in_array($request_maintenance->asset->zip, $zip_codes_comma_seprated))) {
                $techDatalatitude[$value->id] = 1;
            } else {
                $techDatalatitude[$value->id] = 0;
            }
        }

        return view('pages.admin.showmaintenanceservices')
        ->with([
        'request_maintenance' => $request_maintenance,
        'vendors' => $vendors,
        'assigned_services' => $assignedservice,
        'assigned_service_request' => $assigned_service_request,
        'already_assigned_users' => array_unique($already_assigned_users),
        'techDatalatitude' =>$techDatalatitude
        ]);
    }


    public function showBidServices($maintenance_request_id = "", $flagworkorder = "", $customer_bid_price = "", $vendor_bid_price = "", $requestedServiceBidId = "", $due_date = "")
    {

        if ($flagworkorder!="") {
            $dataPricing=['customer_bid_price'=>$customer_bid_price,
            'vendor_bid_price'=>$vendor_bid_price,
            'due_date'=>$due_date
              ];
            $save = RequestedBid::find($requestedServiceBidId)->update($dataPricing);
        }
        $request_maintenance = MaintenanceBid::find($maintenance_request_id);

        $already_assigned_users = [];
        foreach ($request_maintenance->assignRequest as $assigned_users) {
            if ($assigned_users->status == 2) {
                $already_assigned_users[] = $assigned_users->vendor_id;
            }
        }

        $assign_requests = AssignRequestBid::where('request_id', '=', $maintenance_request_id)
        ->where("status", "!=", 2)
        ->select('requested_service_id', 'vendor_id', 'status')->get();


        $assignedservice = [];
        foreach ($assign_requests as $data) {
            $assignedservice[] = $data->requested_service_id;
        }



        //For declined request

        $assign_requests_service = AssignRequestBid::where('request_id', '=', $maintenance_request_id)
        ->where('status', '=', 2)
        ->orwhereIn('requested_service_id', [3, 4])
        ->orwhereIn('request_id', [$maintenance_request_id])
        ->select('requested_service_id', 'vendor_id', 'status')
        ->get();


        $assigned_service_request = [];
        foreach ($assign_requests_service as $data) {
            $assigned_service_request[] = $data->requested_service_id;
        }

        $RequestedService =  RequestedBid::where('request_id', '=', $maintenance_request_id)->get();

        $RequestedServiceIDS=[];
        foreach ($RequestedService as $value) {
            $RequestedServiceIDS[]=$value['service_id'];
        }
        $userobj = new User;
        $lat = $request_maintenance->asset->latitude;
        $lon = $request_maintenance->asset->longitude;

   
         // $vendors = $userobj->getUserByTypeId(3, $lat, $lon, 600, $RequestedServiceIDS);




        $vendors = User::getVendors();
        $techDatalatitude =[];

        foreach ($vendors as $value) {
            $vendorServicesIds=[];

            foreach ($value->vendorService as $dataVendor) {
                $vendorServicesIds[]=$dataVendor->service_id;
            }

            $resultCommonServices = array_intersect($vendorServicesIds, $RequestedServiceIDS);
            if (!empty($resultCommonServices)) {
                $techDatalatitude[$value->id] = 1;
            } else {
                $zip_codes_comma_seprated = explode(",", $value->available_zipcodes);


                if ($zip_codes_comma_seprated!="" && in_array($request_maintenance->asset->zip, $zip_codes_comma_seprated)) {
                    $techDatalatitude[$value->id] = 1;
                } else {
                    $techDatalatitude[$value->id] = 0;
                }
            }
        }




        return view('pages.admin.showbidservices')
        ->with([
        'flagworkorder'=>$flagworkorder,
        'request_maintenance' => $request_maintenance,
        'vendors' => $vendors,
        'assigned_services' => $assignedservice,
        'assigned_service_request' => $assigned_service_request,
        'already_assigned_users' => array_unique($already_assigned_users),
        'techDatalatitude' =>$techDatalatitude
        ]);
    }

    public function listWorkOrder()
    {
          $work_orders = Order::listAllWorkOrder();
          $list_orders = [];
          $i = 0;
          $additional_count = 1;
          $addl_itemz = [];

        foreach ($work_orders as $order) {
            $order_details = ($order->orderDetail);
           //Property Address, City, State, Zip fields
            $customerfirstname="";
            if (isset($order->customer->first_name)) {
                $customerfirstname=$order->customer->first_name;
            }

            $customerlastname="";
            if (isset($order->customer->last_name)) {
                $customerlastname=$order->customer->last_name;
            }


            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname=$order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                $vendorlastname=$order->vendor->last_name;
            }
            $jobtype = "";
            if (isset($order->maintenanceRequest->jobType->title)) {
                $jobtype = $order->maintenanceRequest->jobType->title;
            } else {
                $jobtype = "";
            }

            $clientType = "";
            if (isset($order->maintenanceRequest->asset->customerType->title)) {
                 $clientType =$order->maintenanceRequest->asset->customerType->title ;
            } else {
                 $clientType = "";
            }
            $additional_service_items = AdditionalServiceItem::where('order_id', '=', $order->id)->orderBy('id', 'desc')->get();



            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] =  $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['vendor_name'] = $vendorfirstname. ' ' . $vendorlastname;
            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['clientType'] = $clientType;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at)) ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['due_date'] = '';
            $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;

            if (isset($order->maintenanceRequest->asset->city->name)) {
                 $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                  $list_orders[$i]['city'] =" ";
            }

            $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            $list_orders[$i]['request_status'] =  $order->maintenanceRequest->status;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;

            $list_orders[$i]['submit_by']="";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                  $list_orders[$i]['submit_by'] = $order->maintenanceRequest->user2->first_name." ".$order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date)&&($order_detail->requestedService->due_date!="")) {
                     $list_orders[$i]['due_date'] .=date('m/d/Y', strtotime($order_detail->requestedService->due_date))."<br/>"  ;
                } else {
                    $list_orders[$i]['due_date'].="Not Set"."<br/>";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title .' <br>';
                }
            }
            if (!empty($additional_service_items)) {
                foreach ($additional_service_items as $item) {
                    if ($item->order_id == $order->id) {
                        // print_r($order_id);
                        // echo "<br>";
                        // print_r($order_id);

                        $addl_itemz[$order->id][$order->id."-".$additional_count] = $item->title;
                        //   if (isset($item->title)) {
                        //    $item_id[$order->id]['additional_sevice'.$item->id] = $item->title;
                        //  }else{
                        //   $item_id[$order->id]['additional_sevice'] = "";
                        // }
                    }

                    $additional_count++;
                }
                 $additional_count = 1;
            }
            $i++;
        }


        return view('pages.admin.list_work_order')
        ->with('orders', $list_orders)
        ->with('db_table', 'orders')
        ->with('addl_itemz', $addl_itemz);
    }

//exported
    public function listExportedWorkOrder()
    {
          $work_orders = Order::where('status', '=', 6)->orderBy('id', 'desc')->get();
          $list_orders = [];
          $i = 0;
          $additional_count = 1;
          $addl_itemz = [];

        foreach ($work_orders as $order) {
            $order_details = ($order->orderDetail);
           //Property Address, City, State, Zip fields
            $customerfirstname="";
            if (isset($order->customer->first_name)) {
                $customerfirstname=$order->customer->first_name;
            }

            $customerlastname="";
            if (isset($order->customer->last_name)) {
                $customerlastname=$order->customer->last_name;
            }


            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname=$order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                $vendorlastname=$order->vendor->last_name;
            }
            $jobtype = "";
            if (isset($order->maintenanceRequest->jobType->title)) {
                $jobtype = $order->maintenanceRequest->jobType->title;
            } else {
                $jobtype = "";
            }

            $clientType = "";
            if (isset($order->maintenanceRequest->asset->customerType->title)) {
                 $clientType =$order->maintenanceRequest->asset->customerType->title ;
            } else {
                 $clientType = "";
            }
            $additional_service_items = AdditionalServiceItem::where('order_id', '=', $order->id)->orderBy('id', 'desc')->get();



            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] =  $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['vendor_name'] = $vendorfirstname. ' ' . $vendorlastname;
            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['clientType'] = $clientType;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at)) ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['due_date'] = '';
            $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;

            if (isset($order->maintenanceRequest->asset->city->name)) {
                 $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                  $list_orders[$i]['city'] =" ";
            }

            $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            $list_orders[$i]['request_status'] =  $order->maintenanceRequest->status;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;

            $list_orders[$i]['submit_by']="";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                  $list_orders[$i]['submit_by'] = $order->maintenanceRequest->user2->first_name." ".$order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date)&&($order_detail->requestedService->due_date!="")) {
                     $list_orders[$i]['due_date'] .=date('m/d/Y', strtotime($order_detail->requestedService->due_date))."<br/>"  ;
                } else {
                    $list_orders[$i]['due_date'].="Not Set"."<br/>";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title .' <br>';
                }
            }
            if (!empty($additional_service_items)) {
                foreach ($additional_service_items as $item) {
                    if ($item->order_id == $order->id) {
                        // print_r($order_id);
                        // echo "<br>";
                        // print_r($order_id);

                        $addl_itemz[$order->id][$order->id."-".$additional_count] = $item->title;
                        //   if (isset($item->title)) {
                        //    $item_id[$order->id]['additional_sevice'.$item->id] = $item->title;
                        //  }else{
                        //   $item_id[$order->id]['additional_sevice'] = "";
                        // }
                    }

                    $additional_count++;
                }
                 $additional_count = 1;
            }
            $i++;
        }


        return view('pages.admin.list_work_order')
        ->with('orders', $list_orders)
        ->with('db_table', 'orders')
        ->with('addl_itemz', $addl_itemz);
    }

//exported end
    public function listWorkOrderGrid()
    {
           $orders = Order::get_Data();

           echo json_encode($orders);
    }
    public function viewonly()
    {
          return view('pages.admin.list_work_order_grid');
    }

    public function listCompletedOrders()
    {
          $orders = Order::listCompletedOrders();

          $list_orders = [];
          $i = 0;

        foreach ($orders as $order) {
            $order_details = ($order->orderDetail);

            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $order->customer->first_name . ' ' . $order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $order->vendor->first_name . ' ' . $order->vendor->last_name;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at));
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;

            $i++;
        }


          return view('pages.admin.list_completed_orders')
          ->with('orders', $list_orders);
    }

    public function editProfileAdmin($id)
    {
          $user_data = User::find($id);
          $user_type = UserType::getUserTypeByID($user_data->type_id);
          $cities = City::getAllCities();
          $states = State::getAllStates();
          $services=Service::getAllServices();
          $vendor_services = VendorService::getAllVendorServicesId($id);
          $clientType=CustomerType::get();

          $VendorServiceArray=[];
        foreach ($vendor_services as $value) {
            $VendorServiceArray[]=$value->service_id;
        }

          $servicesDATAoption='';
        foreach ($clientType as $clientTypeData) {
            $servicesDATAoption.="<optgroup label='".$clientTypeData->title."'>";
            $getserviceBycustomerType=Service::where("customer_type_id", "=", $clientTypeData->id)->get();
            foreach ($getserviceBycustomerType as $getserviceBycustomerTypeDATA) {
                if (in_array($getserviceBycustomerTypeDATA->id, $VendorServiceArray)) {
                    $servicesDATAoption.="<option value='".$getserviceBycustomerTypeDATA->id."' selected=\"selected\">".$getserviceBycustomerTypeDATA->title."</option>";
                } else {
                    $servicesDATAoption.="<option value='".$getserviceBycustomerTypeDATA->id."' >".$getserviceBycustomerTypeDATA->title."</option>";
                }
            }



            $servicesDATAoption.="</optgroup>";
        }


        $CustomerType  = CustomerType::get();
        return view('pages.admin.edit_profile')
        ->with('cities', $cities)
        ->with('states', $states)
        ->with('user_data', $user_data)
        ->with('user_type', $user_type)
        ->with('services', $services)
        ->with('vendor_services', $VendorServiceArray)
        ->with('CustomerType', $CustomerType)
        ->with('servicesDATAoption', $servicesDATAoption)
        ;
    }

// func to edit city by shm
    public function editCity($id)
    {
          $city_data = City::find($id);
          $states = State::getAllStates();
          return view('pages.admin.edit_city')
          ->with('city_data', $city_data)
          ->with('states', $states);
    }

// func to save city by shm
    public function saveCity()
    {
          $rules = [
        'name' => 'required|min:2|max:100',
        'state_id' => 'required',
          ];
          $validator = Validator::make(Input::all(), $rules);
          if ($validator->fails()) {
              $validation_messages = $validator->messages()->all();
              $profile_error_messages = '';
              foreach ($validation_messages as $validation_message) {
                  $profile_error_messages.="<h4 class='alert alert-error'>" . $validation_message . "</h4>";
                }
                return $profile_error_messages;
            } else {
                $saved_message = FlashMessage::messages('admin.city_edit_success');
                $id = Input::get('id');
                $data = Input::all();
                $save = City::updateCity($data, $id);
                if ($save) {
                    return FlashMessage::displayAlert($saved_message, 'success');
                  }
          }
    }

    public function editTypeJob($id)
    {
          $user_data = JobType::find($id);

          return view('pages.admin.edit_job_type')

          ->with('user_data', $user_data);
    }

    function saveJobType()
    {
          $data = Input::get();
  
          $save = JobType::find($data['id'])->update($data);
          $message = "Job Type has been modified";
          return redirect('list-job-type')
          ->with('message', FlashMessage::displayAlert($message, 'success'));
    }

    function deleteJobType($id)
    {

         JobType::where('id', '=', $id)->delete();
         $message = "Job Type has been deleted";
         return redirect('list-job-type')
         ->with('message', FlashMessage::displayAlert($message, 'success'));
    }

    /**
     * Process edit profile data & update database accordingly.
     * @params none
     * @return return success & error message through AJAX.
     */
    public function saveUserProfile($id)
    {

        $user_data = User::find($id);
        // $id = Auth::user()->id;
        $username = $user_data->username;
      // Validator::extend('hashmatch', function($attribute, $value, $parameters) {
      //   return Hash::check($value, Auth::user()->$parameters[0]);
      // });

        $messages = [
        'hashmatch' => 'Your current password must match your account password.'
        ];

        if (Input::get('change_password')) {
            $rules = [
            'email' => 'required|email|unique:users,email,'.$id,
            'first_name' => 'required|min:2|max:80|alpha',
            'last_name' => 'required|min:2|max:80|alpha',
            'phone' => 'required|numeric',
            'address_1' => 'required|min:8|max:100',
            'zipcode' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'current_password' => 'hashmatch:password',
            'password' => 'required|between:4,20|confirmed',
            'password_confirmation' => 'same:password',
              ];
        } else {
            $rules = [
            'email' => 'required|email|unique:users,email,'.$id,
            'first_name' => 'required|min:2|max:80|alpha',
            'last_name' => 'required|min:2|max:80|alpha',
            'phone' => 'required|numeric',
            'address_1' => 'required|min:8|max:100',
            'zipcode' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            ];
        }
        $street = '';
        $streetNumber = '';
        $city_id = Input::get('city_id');
        $city = City::find($city_id)->name;
        $zip = Input::get('zipcode');
        $country = 'United States';
        $result = Geolocation::getCoordinates($street, $streetNumber, $city, $zip, $country);

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            $validation_messages = $validator->messages()->all();
            $profile_error_messages = '';
            foreach ($validation_messages as $validation_message) {
                $profile_error_messages.="<h4 class='alert alert-error'>" . $validation_message . "</h4>";
            }
            return $profile_error_messages;
        } else {
            $profile_message = FlashMessage::messages('vendor.profile_edit_success');
            $data = Input::all();
            $data['latitude'] = $result['latitude'];
            $data['longitude'] = $result['longitude'];
            if (!Input::get('change_password')) {
                $data['password'] = $user_data->password;
            } else {
                $data['password'] = Hash::make($data['password']);
            }
            $file = Input::file('profile_picture');
            if ($file) {
                $destinationPath = config('app.upload_path');
                $filename = $file->getClientOriginalName();
                $filename = str_replace('.', '-' . $username . '.', 'profile-' . $filename);
                $data['profile_picture'] = $filename;
                Input::file('profile_picture')->move($destinationPath, $filename);
            } else {
                $data['profile_picture'] = Auth::user()->profile_picture;
            }
            $save = User::profile($data, $id);

            if ($user_data->type_id==3) {
                $affectedRows = VendorService::where('vendor_id', '=', $id)->delete();
                foreach ($data['vendor_services'] as $value) {
                    $dataArray['vendor_id']=$id;
                    $dataArray['status']=1;
                    $dataArray['service_id']=$value;

                    VendorService::create($dataArray);
                }
            }

            if ($save) {
                return FlashMessage::displayAlert($profile_message, 'success');
            }
        }
    }



    /**
     * List All bid requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listBidRequests($status = 1)
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('status', "=", $status)
        ->orderBy('id', 'desc')->get();


        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)
            ->where('status', '=', 1)
            ->get();
            $assign_requests[$i]['request_id'] = $request->id;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['customer_name'] = $request->user->first_name .' '. $request->user->last_name;
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->created_at));
            $assign_requests[$i]['due_date'] = $request->created_at;
            foreach ($services as $service) {
                $assign_requests[$i]['service_code'].='&diams; '.$service->service->service_code . ' <br>';
                $assign_requests[$i]['service_name'].='&diams; '.$service->service->title . ' <br>';
            }
            $i++;
        }

        return view('pages.admin.list_bid_requests')
        ->with('assign_requests', $assign_requests)
        ->with('status', $status);
    }


    /**
     * List All bid requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listApprovedBidRequests()
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('status', "=", 2)
        ->orderBy('id', 'desc')->get();


        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)
            ->where('status', '=', 1)
            ->get();
            $assign_requests[$i]['request_id'] = $request->id;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['customer_name'] = $request->user->first_name .' '. $request->user->last_name;
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['request_date'] = $request->created_at;
            $assign_requests[$i]['due_date'] = $request->created_at;
            foreach ($services as $service) {
                $assign_requests[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                $assign_requests[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
            }
            $i++;
        }

        return view('pages.admin.list_bid_requests')->with('assign_requests', $assign_requests);
    }


    /**
     * List All bid requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listDeclinedBidRequests()
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('status', "=", 3)
        ->orderBy('id', 'desc')->get();


        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)
            ->where('status', '=', 1)
            ->get();
            $assign_requests[$i]['request_id'] = $request->id;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['customer_name'] = $request->user->first_name .' '. $request->user->last_name;
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['request_date'] = $request->created_at;
            $assign_requests[$i]['due_date'] = $request->created_at;
            foreach ($services as $service) {
                $assign_requests[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                $assign_requests[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
            }
            $i++;
        }

        return view('pages.admin.list_bid_requests')->with('assign_requests', $assign_requests);
    }


         /*
        Add bid for a particular workorder
        */
    public function addBidRequest()
    {

      // Get all customer assets to send in view

        $services = Service::getAllServices(1); // get all services provided by admin
        $jobType=JobType::get();

        //get vendor id
        $vendor_id = Auth::user()->id;
      // All in progress work orders
        $orders = Order::where('status', '=', '1')
        ->get();

        $order_ids=[];
        foreach ($orders as $order) :
            $orderDetail= $order->orderDetail;
        
            $serviceType="";

            foreach ($orderDetail as $value) {
                 $serviceType.="\n".$value->requestedService->service->title;
                 $serviceType.="\n";
            }


            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                   $vendorfirstname=$order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                   $vendorlastname=$order->vendor->last_name;
            }
            $vendorcompany="";
            if (isset($order->vendor->last_name)) {
                   $vendorcompany=$order->vendor->company;
            } else {
                   $vendorcompany="";
            }



            $order_ids[$order->id."--".$order->MaintenanceRequest->Asset->id]=  $order->id."-".$order->MaintenanceRequest->Asset->property_address."-".$serviceType."-".$vendorfirstname."-".$vendorlastname."-". $vendorcompany ;
        endforeach;

        return view('pages.admin.add-bid')
        ->with('order_ids', $order_ids)
        ->with('services', $services)
        ->with('jobType', $jobType) ;
    }



          /*
        Add bid for a particular workorder
        */
    public function createBidRequest()
    {

      // Get all customer assets to send in view

        $services = Service::getBidServices(); // get all services provided by admin


        //get vendor id
        $vendor_id = Auth::user()->id;
      // All in progress work orders
        $orders = Order::where('status', '=', '1')
        ->get();

        $order_ids=[];
        foreach ($orders as $order) :
            $order_ids[$order->id."--".$order->MaintenanceRequest->Asset->id]=  $order->id."-".$order->MaintenanceRequest->Asset->property_address;
        endforeach;

        return view('pages.admin.add-bid')
        ->with('order_ids', $order_ids)
        ->with('services', $services);
    }

             /**
     * Create Bid request
     * @params none
     * @return Redirect to maintenance request page
     */
    public function createBidServiceRequest()
    {
        $data = Input::all(); // get all submitted data of user
        //$customer_id = Auth::user()->id;
        //for customer id
        $exploded_orderid_asset_id=explode("--", $data['work_order']);
        $orderData= Order::find($exploded_orderid_asset_id[0]);
        $request['vendor_id'] = $orderData->vendor_id; // assign current logged id to request
        $request['asset_id'] = $exploded_orderid_asset_id[1]; // assign asset number to request
        $request['order_id'] = $exploded_orderid_asset_id[0];
        $request['customer_id'] = $orderData->customer->id;
        $request['job_type'] = $data['job_type'];
        $request['status'] = 1; // while generating request status would be active
        // Add maintainence request to main table
        $add_request = BidRequest::addMaintenanceRequest($request);
        // get last id to assign to each service request
        $request_id = DB::getPdo()->lastInsertId();

        //check if request in created then insert services to service table
        if ($add_request) {
            //Select all selected service to
            $selected_services = $data['service_ids_selected'];

            /// loop through all selected services
            foreach ($selected_services as $service_id) {
                $request_detail['maintenance_request_id'] =$orderData->request_id;

                $request_detail['request_id'] = $request_id; // assign request id to $requested detail
                $request_detail['service_id'] = $service_id; // assign service_id to $requested detail
                $request_detail['status'] = 1;

                if (isset($data['service_required_date_' . $service_id])) {
                    $request_detail['required_date'] = General::displayDate($data['service_required_date_' . $service_id]);
                    $request_detail['required_time'] = General::displayTime($data['time_hours_' . $service_id], $data['time_minutes_' . $service_id], $data['time_meridiem_' . $service_id]);
                } else {
                    unset($request_detail['required_time']);
                    unset($request_detail['required_date']);
                }

                if (isset($data['number_of_men_' . $service_id])) {
                    $request_detail['service_men'] = $data['number_of_men_' . $service_id];
                } else {
                    unset($request_detail['service_men']);
                }
                if (isset($data['service_note_' . $service_id])) {
                    $request_detail['service_note'] = $data['service_note_' . $service_id];
                } else {
                    unset($request_detail['service_men']);
                }
                if (isset($data['verified_vacancy_' . $service_id])) {
                    $request_detail['verified_vacancy'] = $data['verified_vacancy_' . $service_id];
                } else {
                    unset($request_detail['verified_vacancy']);
                }

                if (isset($data['cash_for_keys_' . $service_id])) {
                    $request_detail['cash_for_keys'] = $data['cash_for_keys_' . $service_id];
                } else {
                    unset($request_detail['cash_for_keys']);
                }

                if (isset($data['cash_for_keys_trash_out_' . $service_id])) {
                    $request_detail['cash_for_keys_trash_out'] = $data['cash_for_keys_trash_out_' . $service_id];
                } else {
                    unset($request_detail['cash_for_keys_trash_out']);
                }

                if (isset($data['trash_size_' . $service_id])) {
                    $request_detail['trash_size'] = $data['trash_size_' . $service_id];
                } else {
                    unset($request_detail['trash_size']);
                }

                if (isset($data['storage_shed_' . $service_id])) {
                    $request_detail['storage_shed'] = $data['storage_shed_' . $service_id];
                } else {
                    unset($request_detail['storage_shed']);
                }

                if (isset($data['lot_size_' . $service_id])) {
                    $request_detail['lot_size'] = $data['lot_size_' . $service_id];
                } else {
                    unset($request_detail['lot_size']);
                }
                if (isset($data['biding_prince_' . $service_id])) {
                    $request_detail['biding_prince'] = $data['biding_prince_' . $service_id];
                } else {
                    unset($request_detail['biding_prince']);
                }
                    $add_requested_service = BidRequestedService::addRequestedService($request_detail);
                    $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service
                    //check if service is created then insert images of service
                if ($add_requested_service) {
                    if (isset($data['service_image_list_' . $service_id])) {
                        $service_images = $data['service_image_list_' . $service_id];
                        foreach ($service_images as $image) {
                            $image_detail['requested_id'] = $request_detail_id;
                            $image_detail['image_name'] = $image;
                            $image_detail['image_type'] = 'request';
                            $image_detail['status'] = 1;
                            $add_image = BidServiceImage::addServiceImage($image_detail);
                        }
                    }
                }
            }
        }
        $customer_assets = Asset::getAssetsByCustomerId(Auth::user()->id);
        $services = Service::getAllServices();

  /// loop through all selected services
        $selectedServices="";
        foreach ($selected_services as $service_id) {
              $servicesEMAIL= Service::find($service_id);

              $selectedServices.=$servicesEMAIL->title."<br/>";
        }

        $BidRequestEmailDATA=  BidRequest::find($request_id);
        $emailbody='OSR '.$request_id .' has been created';
        $emailbody.= '<br/>';
        $emailbody.= 'ID:'.$request_id;
        $emailbody.= '<br/>';
        $emailbody.= 'Property Address'.$BidRequestEmailDATA->asset->property_address;
        $emailbody.= '<br/>';
        $emailbody.= 'City:'.$BidRequestEmailDATA->asset->city->name;
        $emailbody.= '<br/>';
        $emailbody.= 'State:'.$BidRequestEmailDATA->asset->state->name;
        ;
        $emailbody.= '<br/>';
        $emailbody.= 'Service Type:'.$selectedServices;
        $emailbody.= '<br/>';

        $url="admin-bid-requests/".$request_id;
        $emailbody.='To view the OSR <a href="http://'.URL::to($url).'">please click here</a>!.';



   // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
        $recepient_id = User::getAdminUsersId();
        foreach ($recepient_id as $rec_id) {
            $userDAta=User::find($rec_id);
            $email_data = [
                 'first_name' => $userDAta->first_name,
                 'last_name' => $userDAta->last_name,
                 'username' => $userDAta->username,
                 'email' => $userDAta->email,
                 'id' =>  $rec_id,
                 'user_email_template'=>$emailbody
                 ];

               $customervendor="Admin";
               $notification_url="admin-bid-requests";

               //Vendor to admin notification
               $notification = NotificationController::doNotification($rec_id, $rec_id, 'OSR '.$request_id .' has been created', 1, $email_data, $notification_url);
               Email::send($userDAta->email, ': OSR Notification', 'emails.customer_registered', $email_data);
        }


        if ($add_request) {
            $message = FlashMessage::messages('customer.request_bid_add');
            return Redirect::back()
            ->with('message', FlashMessage::displayAlert($message, 'success'));
        }
    }

     /*
     * Function Name : viewMaintenanceRequest
     * @param:id
     * @description: This function is begin used for  viewing  all over the  details requests of maintenance in admin
     *
     */

    public function viewBidRequest($maintenance_request_id = "")
    {


        $request_maintenance = BidRequest::find($maintenance_request_id);

        $assign_requests = BidRequestedService::where('request_id', '=', $maintenance_request_id)

        ->get();



        return view('pages.admin.viewadminbidrequest')
        ->with([
        'request_maintenance' => $request_maintenance,
        'assign_requests'=>$assign_requests
        ]);
    }

    /*
    Bid request declined by Admin
    @dafault status =3
    */
    public function DeclineBidRequest()
    {
        $input = Input::all();
        // declined bid request status
        $data = [
        'status' => 3,
        'decline_notes'=>$input['decline_notes']
        ];
        $save = BidRequest::find($input['request_id'])->update($data);





        $userDAta=User::find($input['vendor_id']);
        $email_data = [
        'first_name' => $userDAta->first_name,
        'last_name' => $userDAta->last_name,
        'username' => $userDAta->username,
        'email' => $userDAta->email,
        'id' =>  $input['vendor_id'],
        'user_email_template'=>'OSR '.$input["request_id"].' has been declined'
        ];

        $customervendor="Vendor";
        $notification_url="vendor-bid-requests/3";

            //Vendor to admin notification
        $notification = NotificationController::doNotification($input['vendor_id'], $input['vendor_id'], "OSR ".$input["request_id"]." has been declined", 1, $email_data, $notification_url);
        Email::send($userDAta->email, 'GSS Work Order Notification', 'emails.customer_registered', $email_data);


        return "OSR has been declined";
    }


    /*
         Bid request accepted by admin
         @dafault status =2

Step 1: “Submit Bid to Customer”: This will submit a BID to the Customer. So, it will go to Step 2 on the Bid Process. (Customers do not see OSR, only Bids)



        */
    public function acceptBidRequest()
    {
          $input = Input::all();

          $BidRequest = BidRequest::find($input['request_id']);

          $MaintenanceBidDATA=[
        'customer_id'=>$BidRequest->customer_id,
        'asset_id'=>$BidRequest->asset_id,
        'job_type'=>$BidRequest->job_type,
        'status'=>6

          ];
          $MaintenanceBid=MaintenanceBid::create($MaintenanceBidDATA);
          $MaintenanceBidID = DB::getPdo()->lastInsertId(); // get last id of service

          $BidRequestedService = BidRequestedService::where('request_id', '=', $input['request_id'])
          ->get();


          $bidData=[];
          $i=0;
          foreach ($BidRequestedService as $biddatavalue) {
              $bidData['request_id']= $MaintenanceBidID;
              $bidData['service_id']=$biddatavalue->service_id;
              $bidData['status']=1;
              $bidData['created_at']=$biddatavalue->created_at;
              $bidData['updated_at']=$biddatavalue->updated_at;
              $bidData['required_date']=$biddatavalue->required_date;
              $bidData['required_time']=$biddatavalue->required_time;
              $bidData['service_men']=$biddatavalue->service_men;
              $bidData['service_note']=$biddatavalue->service_note;
              $bidData['customer_note']=$biddatavalue->customer_note;
              $bidData['vendor_note']=$biddatavalue->vendor_note;
              $bidData['verified_vacancy']=$biddatavalue->verified_vacancy;
              $bidData['cash_for_keys']=$biddatavalue->cash_for_keys;
              $bidData['cash_for_keys_trash_out']=$biddatavalue->cash_for_keys_trash_out;
              $bidData['trash_size']=$biddatavalue->trash_size;
              $bidData['storage_shed']=$biddatavalue->storage_shed;
              $bidData['lot_size']=$biddatavalue->lot_size;
              $bidData['vendor_bid_price']=$biddatavalue->biding_prince;
              $bidData['customer_bid_price']=$input['customer_price'][$i];


           

              if (isset($input['vendor_price'][$i]) && ($input['vendor_price'][$i]!="")) {
                  $data = ['biding_prince' => $input['vendor_price'][$i] ];
                  BidRequestedService::find($biddatavalue->id)->update($data);
            
                  $bidData['vendor_bid_price']=$input['vendor_price'][$i];
                }
                if (isset($input['customer_price'][$i]) && ($input['customer_price'][$i]!="")) {
                    $data = ['customer_price' => $input['customer_price'][$i] ];
                    BidRequestedService::find($biddatavalue->id)->update($data);
                }



                $i++;

                $add_requested_service = RequestedBid::addRequestedService($bidData);

                $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service



                $dataRequests['request_id']=$MaintenanceBidID;
                $dataRequests['requested_service_id']=$request_detail_id;
                $dataRequests['vendor_id']=$input['vendor_id'];
                $dataRequests['status']=1;


                $accept_request = AssignRequestBid::create($dataRequests);
                $accept_requestID = DB::getPdo()->lastInsertId(); // get last id of service


                $imageDataArray=BidServiceImage::where('requested_id', '=', $biddatavalue->id)->get();

                foreach ($imageDataArray as $imageData) {
                    $image_detail['requested_id'] =$accept_requestID ;
                    $image_detail['image_name'] = $accept_requestID."-".$imageData->image_name;
                    $image_detail['image_type'] = 'before';
                    $image_detail['status'] = 1;
                    $add_image = AssignRequestBidsImage::create($image_detail);
                }

                $destinationPath = config('app.bid_images_before');   //2
                $upload_path = config('app.upload_path')."request";
                foreach ($imageDataArray as $imageData) {
          //Copy Images for bid

                    $type='before';
                    $tempFile = $upload_path."/".$imageData->image_name;          //3
                    $targetPath = $destinationPath;  //4
                    $originalFile=$imageData->image_name;
                    $changedFileName=$accept_requestID.'-'.$originalFile;
                    $targetFile = $targetPath . $changedFileName;  //5

                    copy($tempFile, $targetFile);

          //End coping images
                }

                //Notification to Customer
                $statusMessage="Awaiting Customer Approval";
                $emailbody='Bid Request '.$MaintenanceBid->id .' status has been changed to '.$statusMessage;


                $url="list-customer-requested-bids/".$MaintenanceBid->id;
                $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';


                $userDAta=User::find($MaintenanceBid->user->id);
                $email_data = [
                'first_name' => $userDAta->first_name,
                'last_name' => $userDAta->last_name,
                'username' => $userDAta->username,
                'email' => $userDAta->email,
                'id' =>  $MaintenanceBid->user->id,
                'user_email_template'=>$emailbody
                ];

                $customervendor="Customer";
                $notification_url="list-customer-requested-bids";

              //Vendor to admin notification
                $notification = NotificationController::doNotification($MaintenanceBid->user->id, $MaintenanceBid->user->id, 'Bid Request '.$MaintenanceBid->id .' status has been changed to '. $statusMessage, 1, $email_data, $notification_url);
                Email::send($userDAta->email, ': Bid Request Notification', 'pages.emails.customer_registered', $email_data);


          //  $emailUrl="vendor-list-orders?url=".$order_id;
              //  $userDAta=User::find($BidRequest->vendor_id);
              // $email_data = array(
              // 'first_name' => $userDAta->first_name,
              // 'last_name' => $userDAta->last_name,
              // 'username' => $userDAta->username,
              // 'email' => $userDAta->email,
              // 'id' =>  $BidRequest->vendor_id,
              // 'user_email_template'=>'OSR has been approved and '.$order_id ."  has been assigned to you! To view work order, <a href='http://".URL::to($emailUrl)."'>please click here</a>!"
              // );

              // $customervendor="Vendor";
              // $notification_url="vendor-bid-requests";

              // //Vendor to admin notification
              // $notification = NotificationController::doNotification($BidRequest->vendor_id,$BidRequest->vendor_id, "OSR has been accepted. New Work Order ".$order_id ." has been assigned to you!", 1,$email_data,$notification_url);
              // Email::send($userDAta->email, 'GSS Work Order Notification', 'pages.emails.customer_registered', $email_data);
            }

            // accepted bid request status
            $data = ['status' => 2 ];
            $save = BidRequest::find($input['request_id'])->update($data);


            return "OSR has been assigned to Customer Approval.";
    }

    function customerCompany()
    {
        $input = Input::all();
        $company=  User::getCustomerCompanyById($input['id']);
        return $company;
    }
    //For changing the grid with respect to Status button
    function ajaxDashoboardGridRequests()
    {
        $input = Input::all();
        if ($input['id']!="null") {
            $requests =MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')
            ->where('status', '=', $input['id'])->get();
        } else {
            $requests =MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')
            ->where('status', '!=', 4)->get();
        }
        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        $numberofrequestids=[];
        foreach ($requests as $mdata) {
            $request_service_ids = [];
            $request_ids[] = $mdata->id;
            foreach ($mdata->requestedService as $rqdata) {
                 $request_service_ids[] = $rqdata->id;
            }
            $assigned_request_ids = [];
            $assign_requests = AssignRequest::where('request_id', '=', $mdata->id)
            ->where('status', "!=", 2)
            ->select('request_id')->get();

            foreach ($assign_requests as $adata) {
                 $assigned_request_ids[] = $adata->request_id;
            }

            $numberofrequestids['requested_services_count'][$mdata->id] = count($request_service_ids);
            $numberofrequestids['assigned_services_count'][$mdata->id] = count($assigned_request_ids);
        }




        return view('pages.admin.ajax-dashoboard-grid-requests')->with(
            ['requests' => $requests,
            'statusshow'=>$input['statusshow'],

            'numberofrequestids' => $numberofrequestids
            ]
        );
    }



    function ajaxDashoboardGridOrdersPagination()
    {
        $input = Input::all();
        $work_orders = Order::orderBy('id', 'desc')
        ->where("status", "=", $input['id'])

        ->get();


        $list_orders = [];
        $i = 0;


        foreach ($work_orders as $order) {
            $order_details = ($order->orderDetail);
       //Property Address, City, State, Zip fields

            $customerfirstname="";
            if (isset($order->customer->first_name)) {
                $customerfirstname=$order->customer->first_name;
            }

            $customerlastname="";
            if (isset($order->customer->last_name)) {
                 $customerlastname=$order->customer->last_name;
            }

            $customertype="";

            if (isset($order->maintenanceRequest->asset->customerTitle->title)) {
                 $customertype=$order->maintenanceRequest->asset->customerTitle->title;
            } else {
                $customertype="";
            }


            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname=$order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                 $vendorlastname=$order->vendor->last_name;
            }
            $vendorcompany="";
            if (isset($order->vendor->last_name)) {
                 $vendorcompany=$order->vendor->company;
            } else {
                 $vendorcompany="";
            }
            $vendorprice="";
            $customerprice="";
            foreach ($order_details as $requestedServiceData) {
                $SpecialPriceVendor=  SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                ->where('customer_id', '=', $order->vendor_id)
                ->where('type_id', '=', 3)
                ->get();

                if (!empty($SpecialPriceVendor[0])) {
                    if ($requestedServiceData->requestedService->quantity>0) {
                        $vendorprice=  $SpecialPriceVendor[0]->special_price*$requestedServiceData->requestedService->quantity;
                    } else {
                        $vendorprice=  $SpecialPriceVendor[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->vendor_price)) {
                        $vendorprice = $requestedServiceData->requestedService->service->vendor_price;
                    } else {
                        $vendorprice="";
                    }
                }




                $SpecialPriceCustomer=  SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                ->where('customer_id', '=', $order->customer_id)
                ->where('type_id', '=', 2)
                ->get();

                if (!empty($SpecialPriceCustomer[0])) {
                    if ($requestedServiceData->requestedService->quantity>0) {
                        $customerprice=  $SpecialPriceCustomer[0]->special_price*$requestedServiceData->requestedService->quantity;
                    } else {
                         $customerprice=  $SpecialPriceCustomer[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->customer_price)) {
                         $customerprice=$requestedServiceData->requestedService->service->customer_price;
                    } else {
                        $customerprice="";
                    }
                }
            }
            $jobtype = "";
            if (isset($order->maintenanceRequest->jobType->title)) {
                 $jobtype = $order->maintenanceRequest->jobType->title;
            } else {
                   $jobtype = "";
            }



            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] =  $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['customer_type'] =  $customertype;

            $list_orders[$i]['vendor_name'] = $vendorfirstname. ' ' . $vendorlastname;
            $list_orders[$i]['vendor_company'] = $vendorcompany ;
            $list_orders[$i]['vendor_price'] = $vendorprice;
            $list_orders[$i]['customer_price'] = $customerprice;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at)) ;
            $list_orders[$i]['updated_at'] = date('m/d/Y h:i:s A', strtotime($order->updated_at)) ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['property_id'] = $order->maintenanceRequest->asset->id;
            $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            if (isset($order->maintenanceRequest->asset->city->name)) {
                    $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                    $list_orders[$i]['city'] = "";
            }
            $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            $list_orders[$i]['units'] = $order->maintenanceRequest->asset->UNIT;
            $list_orders[$i]['loan_numbers'] = $order->maintenanceRequest->asset->loan_number;
            $list_orders[$i]['completion_date'] = $order->completion_date;
            $list_orders[$i]['request_status'] =  $order->maintenanceRequest->status;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            $list_orders[$i]['submited_by']="";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                $list_orders[$i]['submited_by'] = $order->maintenanceRequest->user2->first_name." ".$order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date)&&($order_detail->requestedService->due_date!="")) {
                    $due_date=date('m/d/Y', strtotime($order_detail->requestedService->due_date));
                } else {
                    $due_date="Not Set";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'].=$due_date.', <br>';
                }
            }

            $list_orders[$i]['service_type']="";

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_type'].=$order_detail->requestedService->service->title . ' / <br>';
                }
            }












            $i++;
        }








        if ($input['id']==2) {
                  return view('pages.admin.ajax-dashboard-grid-orders-completed')
                  ->with('orders', $list_orders)
                  ->with('db_table', 'orders');
        } elseif ($input['id']==4) {
                  return view('pages.admin.ajax-dashboard-grid-orders-approved')
                  ->with('orders', $list_orders)
                  ->with('db_table', 'orders');
        } else {
                  return view('pages.admin.ajax-dashoboard-grid-orders')
                  ->with('orders', $list_orders)
                  ->with('db_table', 'orders');
        }
    }
    function ajaxDashoboardGridOrders()
    {
         $input = Input::all();
         // print_r($input);
         // exit();
        if ($input['id']==4) {
             $work_orders = Order::where("status", "=", $input['id'])->orderBy('id', 'desc')->take(10)->get();
             // ->take(10)
        } else {
             $work_orders = Order::orderBy('id', 'desc')
             ->where("status", "=", $input['id'])

             ->get();
        }

         $list_orders = [];
         $i = 0;
         $additional_count = 1;
         $addl_itemz = [];
         $addl_itemz_rate = [];
          $addl_itemz_customerPrice = [];
         $addl_itemz_service_type = [];

            //echo " <pre>";
        foreach ($work_orders as $order) {
            $order_details = ($order->orderDetail);
                // Property Address, City, State, Zip fields

            $customerfirstname="";
            if (isset($order->customer->first_name)) {
                $customerfirstname=$order->customer->first_name;
            }

            $customerlastname="";
            if (isset($order->customer->last_name)) {
                  $customerlastname=$order->customer->last_name;
            }

            $customertype="";

            if (isset($order->maintenanceRequest->asset->customerTitle->title)) {
                  $customertype=$order->maintenanceRequest->asset->customerTitle->title;
            } else {
                 $customertype="";
            }


            $vendorfirstname="";
            if (isset($order->vendor->first_name)) {
                 $vendorfirstname=$order->vendor->first_name;
            }

            $vendorlastname="";
            if (isset($order->vendor->last_name)) {
                 $vendorlastname=$order->vendor->last_name;
            }
            $vendorcompany="";
            if (isset($order->vendor->last_name)) {
                 $vendorcompany=$order->vendor->company;
            } else {
                 $vendorcompany="";
            }
            $vendorprice="";
            $customerprice="";
            foreach ($order_details as $requestedServiceData) {
                if (!empty($requestedServiceData->requestedService->service->id)) {
                    $SpecialPriceVendor=  SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                    ->where('customer_id', '=', $order->vendor_id)
                    ->where('type_id', '=', 3)
                    ->get();
                } else {
                    $SpecialPriceVendor=null;
                }
                if (!empty($SpecialPriceVendor[0])) {
                    if ($requestedServiceData->requestedService->quantity>0) {
                        $vendorprice=  $SpecialPriceVendor[0]->special_price*$requestedServiceData->requestedService->quantity;
                    } else {
                        $vendorprice=  $SpecialPriceVendor[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->vendor_price)) {
                        $vendorprice = $requestedServiceData->requestedService->service->vendor_price;
                    } else {
                        $vendorprice="";
                    }
                }



                if (isset($requestedServiceData->requestedService->service->id)) {
                    $SpecialPriceCustomer=  SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                    ->where('customer_id', '=', $order->customer_id)
                    ->where('type_id', '=', 2)
                    ->get();
                } else {
                     $SpecialPriceCustomer = "";
                }
                if (!empty($SpecialPriceCustomer[0])) {
                    if ($requestedServiceData->requestedService->quantity>0) {
                        $customerprice=  $SpecialPriceCustomer[0]->special_price*$requestedServiceData->requestedService->quantity;
                    } else {
                        $customerprice=  $SpecialPriceCustomer[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->customer_price)) {
                         $customerprice=$requestedServiceData->requestedService->service->customer_price;
                    } else {
                         $customerprice="";
                    }
                }
                if (isset($order->maintenanceRequest->jobType->title)) {
                      $jobtype = $order->maintenanceRequest->jobType->title;
                } else {
                     $jobtype = "";
                }
                 // if ($order->id == 6399) {
                 //   echo "<pre>";
                 // if(DB::connection()->getDatabaseName())
                 // {
                 //    echo "Connected sucessfully to database ".DB::connection()->getDatabaseName().".";
                 // }
                 //  print_r($order->maintenanceRequest->jobType->title);

                 // }
            }


            $additional_service_items = AdditionalServiceItem::where('order_id', '=', $order->id)->orderBy('id', 'desc')->get();

            $vendorsCustomdata = OrderCustomData::where('order_id', '=', $order->id)->pluck('vendors_price');

            $customerCustomdata = OrderCustomData::where('order_id', '=', $order->id)->pluck('customer_price');
            $quantityCustom = OrderCustomData::where('order_id', '=', $order->id)->pluck('quantity');
            $adminQuantityCustom = OrderCustomData::where('order_id', '=', $order->id)->pluck('quantity');
            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] =  $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['customer_type'] =  $customertype;

            $list_orders[$i]['vendor_name'] = $vendorfirstname. ' ' . $vendorlastname;
            $list_orders[$i]['vendor_company'] = $vendorcompany ;
            if (!empty($vendorsCustomdata)) {
                  $list_orders[$i]['vendor_price'] = $vendorsCustomdata*$quantityCustom;
            } else {
                  $list_orders[$i]['vendor_price'] = $vendorprice;
            }

            if (!empty($customerCustomdata)) {
                  $list_orders[$i]['customer_price'] = $customerCustomdata*$adminQuantityCustom;
            } else {
                  $list_orders[$i]['customer_price'] =  $customerprice;
            }
            $list_orders[$i]['vendor_submitted'] = $order->vendor_submitted;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at)) ;
            $list_orders[$i]['updated_at'] = date('m/d/Y h:i:s A', strtotime($order->updated_at)) ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['property_id'] = $order->maintenanceRequest->asset->id;
            $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            if (isset($order->maintenanceRequest->asset->city->name)) {
                  $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                  $list_orders[$i]['city'] ="";
            }
            $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            $list_orders[$i]['units'] = $order->maintenanceRequest->asset->UNIT;
            $list_orders[$i]['loan_numbers'] = $order->maintenanceRequest->asset->loan_number;
            $list_orders[$i]['completion_date'] = $order->completion_date;
            $list_orders[$i]['request_status'] =  $order->maintenanceRequest->status;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['billing_note'] = $order->billing_note;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            $list_orders[$i]['submited_by']="";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                $list_orders[$i]['submited_by'] = $order->maintenanceRequest->user2->first_name." ".$order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date)&&($order_detail->requestedService->due_date!="")) {
                    $style="";
                    if ((strtotime(date('m/d/Y')) >strtotime($order_detail->requestedService->due_date))) {
                         $style = 'style="background-color:yellow;"';
                    }

                    $due_date= "<p ".$style." >".date('m/d/Y', strtotime($order_detail->requestedService->due_date))."</p>";
                } else {
                    $due_date="Not Set";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'].=$due_date.' <br>';
                }
            }

            $list_orders[$i]['service_type']="";

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_type'].=$order_detail->requestedService->service->title . '<br>';
                }
            }
            if (!empty($additional_service_items)) {
                foreach ($additional_service_items as $item) {
                    // if ($item->order_id == $order->id) {

                    // print_r($order_id);
                    // echo "<br>";
                    // print_r($order_id);
                    $addl_itemz[$order->id][$order->id."-".$additional_count] = $item->title;
                    $addl_itemz_rate[$order->id."-".$additional_count] = $item->rate * $item->quantity ;
                    $addl_itemz_customerPrice[$order->id."-".$additional_count] = $item->customer_price * $item->quantity ;
                    $addl_itemz_service_type[$order->id."-".$additional_count] = $item->title;
                   //   if (isset($item->title)) {
                   //    $item_id[$order->id]['additional_sevice'.$item->id] = $item->title;
                   //  }else{
                   //   $item_id[$order->id]['additional_sevice'] = "";
                   // }


                    // }

                    $additional_count++;
                }
                $additional_count = 1;
            }

            $i++;
        }


        //print_r($item_id);



        if ($input['id']==2) {
              return view('pages.admin.ajax-dashboard-grid-orders-completed')
              ->with('orders', $list_orders)
              ->with('db_table', 'orders')
              ->with('addl_itemz', $addl_itemz)
              ->with('addl_itemz_rate', $addl_itemz_rate)
              ->with('addl_itemz_service_type', $addl_itemz_service_type)
             ->with('addl_itemz_customerPrice', $addl_itemz_customerPrice) ;
        } elseif ($input['id']==4) {
              return view('pages.admin.ajax-dashboard-grid-orders-approved')
              ->with('orders', $list_orders)
              ->with('db_table', 'orders')
              ->with('addl_itemz', $addl_itemz)
              ->with('addl_itemz_rate', $addl_itemz_rate)
              ->with('addl_itemz_service_type', $addl_itemz_service_type)
              ->with('addl_itemz_customerPrice', $addl_itemz_customerPrice) ;
        } else {
              return view('pages.admin.ajax-dashoboard-grid-orders')
              ->with('orders', $list_orders)
              ->with('db_table', 'orders')
              ->with('addl_itemz', $addl_itemz)
              ->with('addl_itemz_service_type', $addl_itemz_service_type)
              ->with('addl_itemz_customerPrice', $addl_itemz_customerPrice) ;
        }
    }

    function ajaxDeleteServiceRequest()
    {
          $input = Input::all();
          RequestedService::where('request_id', '=', $input['request_id'])
          ->where('service_id', '=', $input['service_id'])
          ->delete();
    }

     /**
     List of Service Categories
     */
    public function listServiceCategories()
    {
        $serviceCategories = ServiceCategory::get();





        return view('pages.admin.list-service-categories')
        ->with([
        'serviceCategories' => $serviceCategories,

        ]);
    }
      /**
     * Gets the service category to the add service category page page

     */
    public function addServiceCategory()
    {
        $roles = UserRole::where('status', '=', 1)->get(['id', 'role_name']);
        $user_roles = [];
        foreach ($roles as $role) {
            $user_roles[$role->id] = $role->role_name;
        }

        return view('pages.admin.addservicecategory')->with('user_roles', $user_roles);
    }

    public function addJobType()
    {

        return view('pages.admin.addjobtype');
    }
    public function addCustomerType()
    {

        return view('pages.admin.customertype');
    }
    public function editCustomerType($id)
    {
        $CustomerType=CustomerType::find($id);

        return view('pages.admin.editcustomertype')
        ->with('CustomerType', $CustomerType);
    }


    public function addNewServiceCategory()
    {

        $title = Input::get('title');

        $rules = [
        'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-service-category')
            ->withErrors($validator)
            ->withInput();
        } else {
            $serviceCategory = new ServiceCategory;
            $serviceCategory->title =  $title ;


            //echo '<pre>'; print_r($user_data); exit;

            $serviceCategory->save();
            $from_email = config('app.admin_email');





            $message ="Service Category has been added";

            return redirect('/list-service-categories')
            ->with('message', FlashMessage::displayAlert($message, 'success'));
        }
        return view('pages.admin.addservicecategory');
    }

    public function editNewCustomerType()
    {

        $title = Input::get('title');
        $id = Input::get('id');

        $rules = [
        'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect("/edit-client-type/{$id}")
            ->withErrors($validator)
            ->withInput();
        } else {
          // $serviceCategory = new CustomerType;
          // $serviceCategory->title =  $title ;


          //   //echo '<pre>'; print_r($user_data); exit;

          // $serviceCategory->save();

            CustomerType::where('id', '=', $id)
            ->update(['title'=> $title]);





            $message ="Customer type has been modified";

            return redirect('/list-customer-type')
            ->with('message', FlashMessage::displayAlert($message, 'success'));
        }
        return view('pages.admin.addservicecategory');
    }

    public function addNewCustomerType()
    {

        $title = Input::get('title');

        $rules = [
        'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-customer-type')
            ->withErrors($validator)
            ->withInput();
        } else {
            $serviceCategory = new CustomerType;
            $serviceCategory->title =  $title ;


            //echo '<pre>'; print_r($user_data); exit;

            $serviceCategory->save();





            $message ="Customer type has been added";

            return redirect('/list-customer-type')
            ->with('message', FlashMessage::displayAlert($message, 'success'));
        }
        return view('pages.admin.addservicecategory');
    }

    public function addNewJobType()
    {

        $title = Input::get('title');

        $rules = [
        'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-job-type')
            ->withErrors($validator)
            ->withInput();
        } else {
            $serviceCategory = new JobType;
            $serviceCategory->title =  $title ;


            //echo '<pre>'; print_r($user_data); exit;

            $serviceCategory->save();





            $message ="Job type has been added";

            return redirect('/list-job-type')
            ->with('message', FlashMessage::displayAlert($message, 'success'));
        }
        return view('pages.admin.addservicecategory');
    }

    function listJobType()
    {

        $serviceCategories = JobType::get();
        $serv = new Service;
        $db_table = $serv->getTable();



        return view('pages.admin.list-job-types')
        ->with([
        'serviceCategories' => $serviceCategories,
        'db_table' => $db_table

        ]);
    }

    function listCustomerType()
    {

        $serviceCategories = CustomerType::get();


        return view('pages.admin.customer-types')
        ->with([
        'serviceCategories' => $serviceCategories,

        ]);
    }



    public static function doRequest()
    {
        $submitted = Input::get('submitted');
        if ($submitted) {
            $data = Input::all();
            unset($data['_token']);
            unset($data['submitted']);
          //echo '<pre>'; print_r($data); exit;

            $rules = [
                        // 'service_code' => 'required',
            'title' => 'required'
              ];

               $validator = Validator::make(Input::all(), $rules); // put all rules to validator
           // if validation is failed redirect to add customer asset with errors
            if ($validator->fails()) {
                return Redirect::back()
                ->withErrors($validator);
            } else {
                $data['service_cat_id']=8;
                $save = Service::addAdminService($data);
                $serviceID=DB::getPdo()->lastInsertId();
                if ($save) {
                    if (isset($data['number_of_men'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'number_of_men',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['number_of_men_type'],
                         'field_values'=>$data['number_of_men_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['verified_vacancy'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'verified_vacancy',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['verified_vacancy_type'],
                         'field_values'=>$data['verified_vacancy_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['cash_for_keys'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'cash_for_keys',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['cash_for_keys_type'],
                         'field_values'=>$data['cash_for_keys_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['cash_for_keys_trash_out'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'cash_for_keys_trash_out',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['cash_for_keys_trash_out_type'],
                         'field_values'=>$data['cash_for_keys_trash_out_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }
                    if (isset($data['trash_size'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'trash_size',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['trash_size_type'],
                         'field_values'=>$data['trash_size_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['storage_shed'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'storage_shed',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['storage_shed_type'],
                         'field_values'=>$data['storage_shed_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }




                    if (isset($data['set_prinkler_system_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'set_prinkler_system_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['set_prinkler_system_type_type'],
                         'field_values'=>$data['set_prinkler_system_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['install_temporary_system_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'install_temporary_system_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['install_temporary_system_type_type'],
                         'field_values'=>$data['install_temporary_system_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['carpet_service_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'carpet_service_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['carpet_service_type_type'],
                         'field_values'=>$data['carpet_service_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['pool_service_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'pool_service_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['pool_service_type_type'],
                         'field_values'=>$data['pool_service_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }



                    if (isset($data['boarding_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'boarding_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['boarding_type_type'],
                         'field_values'=>$data['boarding_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['spruce_up_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'spruce_up_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['spruce_up_type_type'],
                         'field_values'=>$data['spruce_up_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }
                    if (isset($data['lot_size'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'lot_size_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['lot_size_type'],
                         'field_values'=>$data['lot_size_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['constable_information_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'constable_information_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['constable_information_type_type'],
                         'field_values'=>$data['constable_information_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['remove_carpe_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'remove_carpe_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['remove_carpe_type_type'],
                         'field_values'=>$data['remove_carpe_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['remove_blinds_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'remove_blinds_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['remove_blinds_type_type'],
                         'field_values'=>$data['remove_blinds_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['remove_appliances_type'])) {
                         $ServicesFieldsDetailData=['fieldname'=>'remove_appliances_type',
                         'service_id'=> $serviceID,
                         'field_type'=>$data['remove_appliances_type_type'],
                         'field_values'=>$data['remove_appliances_type_values']
                           ];
                         ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    echo  $serviceID;


                    //   $message = FlashMessage::messages('admin_service.service_added');
                    //   return redirect('list-services')
                    //      ->with('message', FlashMessage::DisplayAlert($message,'success'));
                } else {
                   //   $message = FlashMessage::messages('admin_service.service_error');
                   //   return Redirect::back()
                   //      ->with('message', FlashMessage::DisplayAlert($message,'success'));
                }
            }
        } else {
            $ServiceCategory=ServiceCategory::get();
            $CustomerType=CustomerType::get();
            $JobType=JobType::get();
            $typeArray  =  [  "select"  => "select",
            "text"    => "text",
            "checkbox"  => "checkbox",
            "radio"   => "radio"
            ];

            return view('pages.admin.do-request')
            ->with('typeArray', $typeArray)
            ->with('ServiceCategory', $ServiceCategory)
            ->with('CustomerType', $CustomerType)
            ->with('JobType', $JobType);
        }
    }



    public static function listBidServices()
    {
        $services = Service::getBidServices();
        $serv = new Service;
        $db_table = $serv->getTable();
        return view('pages.admin.list-bid-servies')->with(['services' => $services,
        'db_table' => $db_table ]);
    }

    public static function quantityOfApprovedOrders()
    {

        $orders = Order::where('status', '=', 4)
        ->select(DB::Raw('DATE(updated_at) date'), DB::raw('count(id) as total'), DB::raw('GROUP_CONCAT(id) as order_id'))
        ->groupBy('date')
        ->orderBy('date', 'desc')

        ->get();

        return view('pages.admin.quantityapproved')
        ->with('orders', $orders);
    }
}
