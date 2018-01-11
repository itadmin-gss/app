<?php

namespace App\Http\Controllers;

use App\AdditionalServiceItem;
use App\Asset;
use App\AssignRequest;
use App\City;
use App\CustomerType;
use App\Helpers\Email;
use App\Helpers\FlashMessage;
use App\Helpers\General;
use App\JobType;
use App\Mail\AdminCustomerCreated;
use App\MaintenanceRequest;
use App\Order;
use App\OrderCustomData;
use App\RequestedService;
use App\RoleDetail;
use App\RoleFunction;
use App\Service;
use App\ServiceCategory;
use App\ServiceFieldDetail;
use App\SpecialPrice;
use App\State;
use App\Tokens;
use App\User;
use App\UserRole;
use App\UserType;
use App\VendorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
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
    public function indexPickGrid($id, $grid)
    {
        $requestsNew = MaintenanceRequest::where('status', '=', 1)->orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();
        $requests = MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();
        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        $numberofrequestids = [];
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
            [
                'requests' => $requests,
                'requestsNew' => $requestsNew,
                'numberofrequestids' => $numberofrequestids,
                'grid' => $grid,
                'id' => $id
            ]
        );

    }


    public function getMaintRequest()
    {

        $requestsNew = MaintenanceRequest::where('status', '=', 1)->orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();
        $requests = MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')->get();

        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        $numberofrequestids = [];
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

        return [$requests, $requestsNew, $numberofrequestids];
    }

    public function underReview()
    {
        $data = self::getWorkOrders(3);
        return view('pages.admin.ajax-dashoboard-grid-orders')
            ->with('orders', $data[0])
            ->with('id', 3)
            ->with('db_table', 'orders')
            ->with('addl_itemz', $data[1]);
    }

    public function completed()
    {
        $data = self::getWorkOrders(2);

        return view('pages.admin.ajax-dashboard-grid-orders-completed')
            ->with('orders', $data[0])
            ->with('db_table', 'orders')
            ->with('addl_itemz', $data[1])
            ->with('addl_itemz_rate', $data[2])
            ->with('addl_itemz_service_type', $data[3])
            ->with('addl_itemz_customerPrice', $data[4]);
    }

    public function inProcess()
    {

            $data = self::getWorkOrders(1);
            return view('pages.admin.ajax-dashoboard-grid-orders')
                ->with('orders', $data[0])
                ->with('id', 1)
                ->with('db_table', 'orders')
                ->with('addl_itemz', $data[1]);

    }
    public function getWorkOrders($id)
    {
        if ($id == 4) {
            $work_orders = Order::where("status", "=", $id)->orderBy('id', 'desc')->take(10)->get();
            // ->take(10)
        } else {
            $work_orders = Order::orderBy('id', 'desc')
                ->where("status", "=", $id)
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

            $customerfirstname = "";
            if (isset($order->customer->first_name)) {
                $customerfirstname = $order->customer->first_name;
            }

            $customerlastname = "";
            if (isset($order->customer->last_name)) {
                $customerlastname = $order->customer->last_name;
            }

            $customertype = "";

            if (isset($order->maintenanceRequest->asset->customerTitle->title)) {
                $customertype = $order->maintenanceRequest->asset->customerTitle->title;
            } else {
                $customertype = "";
            }


            $vendorfirstname = "";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname = $order->vendor->first_name;
            }

            $vendorlastname = "";
            if (isset($order->vendor->last_name)) {
                $vendorlastname = $order->vendor->last_name;
            }
            $vendorcompany = "";
            if (isset($order->vendor->last_name)) {
                $vendorcompany = $order->vendor->company;
            } else {
                $vendorcompany = "";
            }
            $vendorprice = "";
            $customerprice = "";
            $jobtype = "";
            foreach ($order_details as $requestedServiceData) {
                if (!empty($requestedServiceData->requestedService->service->id)) {
                    $SpecialPriceVendor = SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                        ->where('customer_id', '=', $order->vendor_id)
                        ->where('type_id', '=', 3)
                        ->get();
                } else {
                    $SpecialPriceVendor = null;
                }
                if (!empty($SpecialPriceVendor[0])) {
                    if ($requestedServiceData->requestedService->quantity > 0) {
                        $vendorprice = $SpecialPriceVendor[0]->special_price * $requestedServiceData->requestedService->quantity;
                    } else {
                        $vendorprice = $SpecialPriceVendor[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->vendor_price)) {
                        $vendorprice = $requestedServiceData->requestedService->service->vendor_price;
                    } else {
                        $vendorprice = "";
                    }
                }


                if (isset($requestedServiceData->requestedService->service->id)) {
                    $SpecialPriceCustomer = SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                        ->where('customer_id', '=', $order->customer_id)
                        ->where('type_id', '=', 2)
                        ->get();
                } else {
                    $SpecialPriceCustomer = "";
                }
                if (!empty($SpecialPriceCustomer[0])) {
                    if ($requestedServiceData->requestedService->quantity > 0) {
                        $customerprice = $SpecialPriceCustomer[0]->special_price * $requestedServiceData->requestedService->quantity;
                    } else {
                        $customerprice = $SpecialPriceCustomer[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->customer_price)) {
                        $customerprice = $requestedServiceData->requestedService->service->customer_price;
                    } else {
                        $customerprice = "";
                    }
                }
                if (isset($order->maintenanceRequest->jobType->title)) {
                    $jobtype = $order->maintenanceRequest->jobType->title;
                } else {
                    $jobtype = "";
                }

            }


            $additional_service_items = AdditionalServiceItem::where('order_id', '=', $order->id)->orderBy('id', 'desc')->get();

            $vendorsCustomdata = OrderCustomData::where('order_id', $order->id)->get();
            $customerCustomdata = OrderCustomData::where('order_id', '=', $order->id)->get();
            $quantityCustom = OrderCustomData::where('order_id', '=', $order->id)->pluck('quantity');
            $adminQuantityCustom = OrderCustomData::where('order_id', '=', $order->id)->pluck('quantity');
            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['customer_type'] = $customertype;

            $list_orders[$i]['vendor_name'] = $vendorfirstname . ' ' . $vendorlastname;
            $list_orders[$i]['vendor_company'] = $vendorcompany;

            if (isset($vendorsCustomdata->vendors_price)) {
                $list_orders[$i]['vendor_price'] = $vendorsCustomdata->vendors_price * $quantityCustom;
            } else {
                $list_orders[$i]['vendor_price'] = $vendorprice;
            }

            if (isset($customerCustomdata->customer_price)) {
                $list_orders[$i]['customer_price'] = $customerCustomdata->customer_price * $adminQuantityCustom;
            } else {
                $list_orders[$i]['customer_price'] = $customerprice;
            }
            $list_orders[$i]['vendor_submitted'] = $order->vendor_submitted;

            if (isset($order->maintenanceRequest->asset->asset_number))
            {
                $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            }
            else
            {
                $list_orders[$i]['asset_number'] = "";
            }


            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at));
            $list_orders[$i]['updated_at'] = date('m/d/Y h:i:s A', strtotime($order->updated_at));
            $list_orders[$i]['service_name'] = '';

            if (isset($order->maintenanceRequest->asset->id))
            {
                $list_orders[$i]['property_id'] = $order->maintenanceRequest->asset->id;
            }
            else
            {
                $list_orders[$i]['propert_id'] = '';
            }

            if (isset($order->maintenanceRequest->asset->property_address))
            {
                $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            }
            else
            {
                $list_orders[$i]['property_address'] = "";
            }

            if (isset($order->maintenanceRequest->asset->city->name)) {
                $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                $list_orders[$i]['city'] = "";
            }

            if (isset($order->maintenanceRequest->asset->state->name)) {
                $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            } else {
                $list_orders[$i]['state'] = "";
            }

            if (isset($order->maintenanceRequest->asset->zip))
            {
                $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            }
            else
            {
                $list_orders[$i]['zipcode'] = "";
            }

            if (isset($order->maintenanceRequest->asset->UNIT))
            {
                $list_orders[$i]['units'] = $order->maintenanceRequest->asset->UNIT;
            }
            else
            {
                $list_orders[$i]['units'] = "";
            }

            if (isset($order->maintenanceRequest->asset->loan_number))
            {
                $list_orders[$i]['loan_numbers'] = $order->maintenanceRequest->asset->loan_number;
            }
            else
            {
                $list_orders[$i]['loan_numbers'] = "";
            }

            $list_orders[$i]['completion_date'] = $order->completion_date;

            if (isset($order->maintenanceRequest->status))
            {
                $list_orders[$i]['request_status'] = $order->maintenanceRequest->status;
            }
            else
            {
                $list_orders[$i]['request_status'] = "";
            }
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['billing_note'] = $order->billing_note;
            $list_orders[$i]['status_class'] = ($order->status == 1) ? "warning" : $order->status_class;
            $list_orders[$i]['status_text'] = ($order->status == 1) ? "In-Process" : $order->status_text;
            $list_orders[$i]['submited_by'] = "";

            if (isset($order->maintenanceRequest->user2->first_name))
            {
                $first_name = $order->maintenanceRequest->user2->first_name;
            }
            else
            {
                $first_name = false;
            }

            if (isset($order->maintenanceRequest->user2->last_name))
            {
                $last_name = $order->maintenanceRequest->user2->last_name;
            }
            else
            {
                $last_name = false;
            }

            if ($first_name && $last_name)
            {
                $list_orders[$i]['submited_by'] = $first_name." ".substr($last_name, 0,1).".";
            }
            else if ($first_name)
            {
                $list_orders[$i]['submited_by'] = $first_name;
            }
            else if ($last_name)
            {
                $list_orders[$i]['submited_by'] = $last_name;
            }
            else
            {
                $list_orders[$i]['submited_by'] = "Not Set";
            }

            $order_count = 0;
            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date) && ($order_detail->requestedService->due_date != "")) {
                    $style = "";
                    if ((strtotime(date('m/d/Y')) > strtotime($order_detail->requestedService->due_date))) {
                        $style = 'style="background-color:yellow;"';
                    }

                    $due_date = "<p " . $style . " >" . date('m/d/Y', strtotime($order_detail->requestedService->due_date)) . "</p>";
                } else {
                    $due_date = "Not Set";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    if ($order_count > 0)
                    {
                        $list_orders[$i]['service_name'] .= "<br>";
                    }
                    $list_orders[$i]['service_name'] .= $due_date;
                }
                $order_count++;
            }

            $list_orders[$i]['service_type'] = "";

            $type_count = 0;
            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->service->title)) {
                    if ($type_count > 0){
                        $list_orders[$i]['service_type'] .= "<br>";
                    }
                    $list_orders[$i]['service_type'] .= $order_detail->requestedService->service->title;
                }
            }
            if (!empty($additional_service_items)) {
                foreach ($additional_service_items as $item) {

                    $addl_itemz[$order->id][$order->id . "-" . $additional_count] = $item->title;
                    $addl_itemz_rate[$order->id . "-" . $additional_count] = $item->rate * $item->quantity;
                    $addl_itemz_customerPrice[$order->id . "-" . $additional_count] = $item->customer_price * $item->quantity;
                    $addl_itemz_service_type[$order->id . "-" . $additional_count] = $item->title;


                    $additional_count++;
                }
                $additional_count = 1;
            }

            $i++;
        }

        return [$list_orders, $addl_itemz, $addl_itemz_rate, $addl_itemz_service_type, $addl_itemz_customerPrice];

    }


    public function index()
    {

        $data = self::getMaintRequest();


        return view('pages.admin.dashboard')->with(
            [
                'requests' => $data[0],
                'requestsNew' => $data[1],
                'numberofrequestids' => $data[2]
            ]
        );
    }

    public function swapDB()
    {
        return view('pages.admin.showdb');
    }

    public function swapDBNow()
    {
        $data = Request::all();
        if ($data['db'] == 'old') {
            Session::put('swapdb', 'mysql2');

            return Redirect::back()->with('swapped', 'Database has been swapped to OLD!');
        } else {
            if ($data['db'] == 'new') {
                Session::put('swapdb', 'mysql');

                return Redirect::back()->with('swapped', 'Database has been swapped to NEW!');
            }
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

    public function testEmail()
    {
            
            $email_data = array(
            'first_name' => "Jamie",
            'last_name' => "Dunn",
            'username' => "jdunn82k@gmail.com",
            'email' => "jdunn82k@gmail.com",
            'id' =>  123,
            'user_email_template'=> "TEST EMAIL BODY"
                               );

       try{
        Email::send("jdunn82k@gmail.com", "GSS TEST", "emails.customer_registered", $email_data);
       } catch (Exception $e){
           return $e;
       }

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


    public function processAddVendorFromVendorPage()
    {
        $rules = [
            'first_name' => 'required|min:2|max:80|alpha',
            'last_name' => 'required|min:2|max:80|alpha',
            'email' => 'required|email|unique:users|between:3,64',
        ];
        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            $profile_error_messages = General::validationErrors($validator);

            return $profile_error_messages;
        } else {
            $vendor_add_message = FlashMessage::messages('admin.vendor_add_success');
            $data = Request::all();
            $user_type_id = UserType::where('title', '=', 'vendors')->first();
            $user_types = UserType::find($user_type_id->id);
            $user_roles = UserRole::where('role_name', '=', $user_types->title)->first();
            $data['type_id'] = $user_type_id->id;
            // $passowrd = rand(); //Get random password to send user
            $passowrd = $data['password'];
            $data['password'] = Hash::make($passowrd);
            $data['status'] = 1;
            $save = User::createUser($data);
            if ($save) {
                 //Create Token for email link
                 $token = bin2hex(openssl_random_pseudo_bytes(12));
                 $url = env("APP_URL")."/email-link/vendor/".$token;
                 
                //Add token to database
                $token_save = Tokens::addToken($save, $token);
                
                if ($token_save)
                {
                    $vendor_template = '<h2>Vendor Account has been created.</h2>
                    <h2>Email: '.$data['email'].'</h2>
                    <div>
                        Please Login and complete your profile. <a href="'.$url.'">Click here</a><br/>
                    </div>';


                    //Email New Vendor with Link to Login
                    $email_data = [
                        'first_name' => $data['first_name'],
                        'email' => $data['email'],
                        'token' => 'email-link/vendor/'.$token,
                        'user_email_template' => $vendor_template
                    ];

                    Email::send($data['email'], 'Welcome to GSS', 'emails.new_vendor_template', $email_data);

                    //Return success message
                    Session::flash('message', $vendor_add_message);
                    return redirect('list-vendors');
    
                }

            }
        }
    }
    public function processAddVendor()
    {
        $rules = [
            'first_name' => 'required|min:2|max:80|alpha',
            'last_name' => 'required|min:2|max:80|alpha',
            'email' => 'required|email|unique:users|between:3,64'
        ];
        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            $profile_error_messages = General::validationErrors($validator);

            return $profile_error_messages;
        } else {
            $vendor_add_message = FlashMessage::messages('admin.vendor_add_success');
            $data = Request::all();
            $user_type_id = UserType::where('title', '=', 'vendors')->first();
            $user_types = UserType::find($user_type_id->id);
            $user_roles = UserRole::where('role_name', '=', $user_types->title)->first();
            $data['type_id'] = $user_type_id->id;
            $data['status'] = 1;
            $save = User::createUser($data);
            if ($save) {
                


                //Create Token for email link
                $token = bin2hex(openssl_random_pseudo_bytes(12));
                $url = env("APP_URL")."/email-link/vendor/".$token;
                
               //Add token to database
               $token_save = Tokens::addToken($save, $token);
               
               if ($token_save)
               {
                   $vendor_template = '<h2>Vendor Account has been created.</h2>
                   <h2>Email: '.$data['email'].'</h2>
                   <div>
                       Please Login and complete your profile. <a href="'.$url.'">Click here</a><br/>
                   </div>';


                    //Email New Vendor with Link to Login
                    $email_data = [
                        'first_name' => $data['first_name'],
                        'email' => $data['email'],
                        'token' => 'email-link/vendor/'.$token,
                        'user_email_template' => $vendor_template
                    ];

                    Email::send($data['email'], 'Welcome to GSS', 'emails.new_vendor_template', $email_data);

                    //Return success message
                    Session::flash('message', $vendor_add_message);
                    return "Vendor Created!";
    
                } else {
                   return "Something failed";
               }

            }
        }
    }

    public function listVendors()
    {
        $vendors = User::getAllVendors();

        $user = new User();


        $db_table = $user->getTable();

        return view('pages.admin.list_vendors')->with(['vendors' => $vendors, 'db_table' => $db_table]);
    }


    public function listVendorsDynamically()
    {
        $vendors = User::getAllVendorsInDatatable();

        $user = new User();


        $db_table = $user->getTable();
        echo json_encode($vendors);
    }



    public function deleteVendor($vendor_id)
    {
        $user = User::find($vendor_id);
        $user->delete();
        $message = FlashMessage::messages('admin.user_deleted');

        return Redirect::back()
            ->with('message', FlashMessage::displayAlert($message, 'success'));
    }

    /**
     * Gets the user to the edit user page, checks if the form is submitted with updated user data or to
     * show just the form pre filled.
     * @params user_id : the user id that needs to be updated.
     * @return redirects with appropriate message of success or error.
     */
    public function editUser($user_id)
    {
        $update_user = Request::get('update_user');
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
                    [
                        'user' => $user,
                        'user_roles' => $user_roles,
                        'role_id' => $role_id
                    ]
                );
        } else {
            $user = Request::all();
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
     * called via ajax.
     * @params user_id : the user id that needs to be deleted.
     * @return redirects with appropriate message of success or error.
     */
    public function updateUserStatus($user_id)
    {
        if (Request::ajax()) {
            $activityId = Request::get('activityId');
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
     * called via ajax.
     * @params user_id : the user id that needs to be deleted.
     * @return redirects with appropriate message of success or error.
     */
    public function listUser()
    {
        $users = User::getAdminUser();
        $user_roles = UserRole::get(['id', 'role_name']);
        $user_table = new User();
        $db_table = $user_table->getTable();

        foreach ($user_roles as $role) {
            $roles[$role->id] = $role->role_name;
        }

        return view('pages.admin.listuser')
            ->with([
                'users' => $users,
                'userRoles' => $roles,
                'db_table' => $db_table
            ]);
    }

    /**
     * Adds a new admin user.
     * @params data from the add new user form.
     * @return redirects with appropriate message of success or error.
     */

    public function addNewUserFromModal()
    {
        $first_name = Request::get('first_name');
        $last_name = Request::get('last_name');
        $email = Request::get('email');
        $user_role_id = Request::get('role_id');
        $password = Request::get('password');

            $user = new User();
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user_type_id = UserType::where('title', '=', 'user')->first();
            $user->type_id = $user_type_id->id; // for admin users
            // $password = rand(); //Get random password to send user


            $user->password = Hash::make($password);
            $user->user_role_id = $user_role_id;
            $user->status = 1;
            $user_data = Request::all();

            $user_data['password'] = $password;

            $user->save();
//
//        Mail::to(Request::get('email'), Request::get('first_name') . ' ' . Request::get('last_name'))->send(new AdminCustomerCreated($user_data));
//        Mail::to(User::getEmail(Auth::user()->id), Request::get('first_name') . ' ' . Request::get('last_name'))->send(new AdminCustomerCreated($user_data));
//

        return $user;
    }
    public function addNewUser()
    {

        $first_name = Request::get('first_name');
        $last_name = Request::get('last_name');
        $email = Request::get('email');
        $user_role_id = Request::get('role_id');
        $password = Request::get('password');

        $rules = [
            'first_name' => 'required|between:3,55',
            'last_name' => 'required|between:3,55',
            'email' => 'required|email|unique:users|between:3,64',
            'password' => 'required|between:4,20'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Request::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-user')
                ->withErrors($validator)
                ->withInput();
        } else {
            $user = new User();
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user_type_id = UserType::where('title', '=', 'user')->first();
            $user->type_id = $user_type_id->id; // for admin users
            // $password = rand(); //Get random password to send user

            if (Request::get('customer_type_id'))
            {
                $user->customer_type_id = Request::get('customer_type_id');
            }

            $user->password = Hash::make($password);
            $user->user_role_id = $user_role_id;
            $user->status = 1;
            $user_data = Request::all();

            $user_data['password'] = $password;
            //echo '<pre>'; print_r($user_data); exit;

            $user->save();

            Mail::to(Request::get('email'), Request::get('first_name') . ' ' . Request::get('last_name'))->send(new AdminCustomerCreated($user_data));
            Mail::to(User::getEmail(Auth::user()->id), Request::get('first_name') . ' ' . Request::get('last_name'))->send(new AdminCustomerCreated($user_data));

            $message = FlashMessage::messages('admin.user_created');

            return Redirect::back()->with('message', FlashMessage::displayAlert($message, 'success'));
        }

        return view('pages.admin.adduser');
    }

    // func to list city by shm
    public function listCity()
    {
        $cities = City::getAllCities();
        // echo "<pre>"; print_r($cities); exit();
        // $user_roles = UserRole::get(array('id', 'role_name'));
        $city_table = new City();
        $db_table = $city_table->getTable();

        // foreach ($user_roles as $role) {
        //   $roles[$role->id] = $role->role_name;
        // }

        return view('pages.admin.listcity')
            ->with([
                'cities' => $cities,
                // 'userRoles' => $roles,
                'db_table' => $db_table
            ]);
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

        $name = Request::get('name');
        $state_id = Request::get('state_id');

        $rules = [
            'name' => 'required|between:3,55',
            'state_id' => 'required',
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Request::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-city')
                ->withErrors($validator)
                ->withInput();
        } else {
            $city = new City();
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
        $submitted = Request::get('submitted');
        if ($submitted) {
            $message = FlashMessage::messages('admin.access_level_success');
            $rules = [
                'role_name' => 'required',
            ];

            $validator = Validator::make(Request::all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->messages();

                return redirect('add-access-level')
                    ->withErrors($validator);
            } else {
                $user_role = new UserRole();
                $user_role->role_name = Request::get('role_name');
                $user_role->description = Request::get('description');
                $user_role->status = Request::get('status');
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
        $role_obj = new UserRole();
        $db_table = $role_obj->getTable();

        return view('pages.admin.list_access_level')
            ->with([
                'userRoles' => $userRoles,
                'db_table' => $db_table
            ]);
    }


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


        $request_maintenance_obj = new MaintenanceRequest();
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
        $asset_id            = $request_maintenance->asset_id;
        //Get Asset Information
        $property_details =  Asset::where('id', $asset_id)->get();

        if (count($property_details) < 1)
        {
            throw new \Exception ("Could not find Asset");
            exit;
        }

        //Get Customer Information
        $customer_details = User::where('id', $property_details[0]->customer_id)->get();

        //Get City/State Information
        $city = City::where('id', $property_details[0]->city_id)->get()[0]->name;
        $state = State::where('id', $property_details[0]->state_id)->get()[0]->name;

        $geolocation_result = (new Geolocation)->getCoordinates($property_details[0]->property_address, '', $city, $property_details[0]->zip, 'USA');

        //Get all cities from city
        $cities = City::getAllCities();
        //Get all states from city
        $states = State::getAllStates();

        return view('pages.admin.viewmaintenancerequest')
            ->with([
                'request_maintenance' => $request_maintenance,
                'property_details' => $property_details[0],
                'geolocation' => $geolocation_result,
                'city' => $city,
                'state' => $state,
                'cities' => $cities,
                'states' => $states,
                'customer_info' => $customer_details[0]
            ]);
    }




    /*
    Cancel Request @param id
    */
    public function cancelMaintenanceRequest($maintenance_request_id = "")
    {
        MaintenanceRequest::where('id', '=', $maintenance_request_id)
            ->update(['status' => '4']); //Cancelled by Admin

        return Redirect::back()
            ->with('message', FlashMessage::displayAlert("Request has been cancelled", 'success'));
    }


    public function deleteMaintenanceRequest($maintenance_request_id = "")
    {
        MaintenanceRequest::where('id', '=', $maintenance_request_id)
            ->update(['status' => '5']); //deleted by Admin

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

        $RequestedService = RequestedService::where('request_id', '=', $maintenance_request_id)->get();

        $RequestedServiceIDS = [];
        foreach ($RequestedService as $value) {
            $RequestedServiceIDS[] = $value['service_id'];
        }
        $userobj = new User();
        $lat = $request_maintenance->asset->latitude;
        $lon = $request_maintenance->asset->longitude;

        // $vendors = $userobj->getUserByTypeId(3, $lat, $lon, 600, $RequestedServiceIDS);

        $vendors = User::getVendors();
        $techDatalatitude = [];

        foreach ($vendors as $value) {
            $vendorServicesIds = [];

            foreach ($value->vendorService as $dataVendor) {
                $vendorServicesIds[] = $dataVendor->service_id;
            }

            $resultCommonServices = array_intersect($vendorServicesIds, $RequestedServiceIDS);

            $zip_codes_comma_seprated = explode(",", $value->available_zipcodes);
            if ((!empty($resultCommonServices)) && ($zip_codes_comma_seprated != "" && in_array($request_maintenance->asset->zip, $zip_codes_comma_seprated))) {
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
                'techDatalatitude' => $techDatalatitude
            ]);
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
            $customerfirstname = "";
            if (isset($order->customer->first_name)) {
                $customerfirstname = $order->customer->first_name;
            }

            $customerlastname = "";
            if (isset($order->customer->last_name)) {
                $customerlastname = $order->customer->last_name;
            }


            $vendorfirstname = "";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname = $order->vendor->first_name;
            }

            $vendorlastname = "";
            if (isset($order->vendor->last_name)) {
                $vendorlastname = $order->vendor->last_name;
            }
            $jobtype = "";
            if (isset($order->maintenanceRequest->jobType->title)) {
                $jobtype = $order->maintenanceRequest->jobType->title;
            } else {
                $jobtype = "";
            }

            $clientType = "";
            if (isset($order->maintenanceRequest->asset->customerType->title)) {
                $clientType = $order->maintenanceRequest->asset->customerType->title;
            } else {
                $clientType = "";
            }
            $additional_service_items = AdditionalServiceItem::where('order_id', '=', $order->id)->orderBy('id', 'desc')->get();


            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['vendor_name'] = $vendorfirstname . ' ' . $vendorlastname;
            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['clientType'] = $clientType;
            if (isset($oder->maintenanceRequest->asset->asset_number))
            {
                $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;                
            }
            else
            {
                $list_orders[$i]['asset_number'] = 0;
            }
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at));
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['due_date'] = '';

            if (isset($order->maintenanceRequest->asset->property_address))
            {
                $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            }
            else
            {
                $list_orders[$i]['property_address'] = " ";
            }
            

            if (isset($order->maintenanceRequest->asset->city->name)) {
                $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                $list_orders[$i]['city'] = " ";
            }

            if (isset($order->maintenanceRequest->asset->state->name))
            {
                $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            }
            else
            {
                $list_orders[$i]['state'] = " ";
            }
            
            if (isset($order->maintenanceRequest->asset->zip))
            {
                $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            }
            else
            {
                $list_orders[$i]['zipcode'] = " ";
            }
            
    
            $list_orders[$i]['request_status'] = $order->maintenanceRequest->status;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status == 1) ? "warning" : $order->status_class;;
            $list_orders[$i]['status_text'] = ($order->status == 1) ? "In-Process" : $order->status_text;;

            $list_orders[$i]['submit_by'] = "";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                $list_orders[$i]['submit_by'] = $order->maintenanceRequest->user2->first_name . " " . $order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date) && ($order_detail->requestedService->due_date != "")) {
                    $list_orders[$i]['due_date'] .= date('m/d/Y', strtotime($order_detail->requestedService->due_date)) . "<br/>";
                } else {
                    $list_orders[$i]['due_date'] .= "Not Set" . "<br/>";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'] .= $order_detail->requestedService->service->title . ' <br>';
                }
            }
            if (!empty($additional_service_items)) {
                foreach ($additional_service_items as $item) {
                    if ($item->order_id == $order->id) {
                        // print_r($order_id);
                        // echo "<br>";
                        // print_r($order_id);

                        $addl_itemz[$order->id][$order->id . "-" . $additional_count] = $item->title;
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


        return view('pages.admin.list_exported_workouts')
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
            $list_orders[$i]['status_class'] = ($order->status == 1) ? "warning" : $order->status_class;;
            $list_orders[$i]['status_text'] = ($order->status == 1) ? "In-Process" : $order->status_text;;

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
        $services = Service::getAllServices();
        $vendor_services = VendorService::getAllVendorServicesId($id);
        $clientType = CustomerType::get();

        $VendorServiceArray = [];
        foreach ($vendor_services as $value) {
            $VendorServiceArray[] = $value->service_id;
        }

        $servicesDATAoption = '';
        foreach ($clientType as $clientTypeData) {
            $servicesDATAoption .= "<optgroup label='" . $clientTypeData->title . "'>";
            $getserviceBycustomerType = Service::where("customer_type_id", "=", $clientTypeData->id)->get();
            foreach ($getserviceBycustomerType as $getserviceBycustomerTypeDATA) {
                if (in_array($getserviceBycustomerTypeDATA->id, $VendorServiceArray)) {
                    $servicesDATAoption .= "<option value='" . $getserviceBycustomerTypeDATA->id . "' selected=\"selected\">" . $getserviceBycustomerTypeDATA->title . "</option>";
                } else {
                    $servicesDATAoption .= "<option value='" . $getserviceBycustomerTypeDATA->id . "' >" . $getserviceBycustomerTypeDATA->title . "</option>";
                }
            }


            $servicesDATAoption .= "</optgroup>";
        }


        $CustomerType = CustomerType::get();

        return view('pages.admin.edit_profile')
            ->with('cities', $cities)
            ->with('states', $states)
            ->with('user_data', $user_data)
            ->with('user_type', $user_type)
            ->with('services', $services)
            ->with('vendor_services', $VendorServiceArray)
            ->with('CustomerType', $CustomerType)
            ->with('servicesDATAoption', $servicesDATAoption);
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
        $validator = Validator::make(Request::all(), $rules);
        if ($validator->fails()) {
            $validation_messages = $validator->messages()->all();
            $profile_error_messages = '';
            foreach ($validation_messages as $validation_message) {
                $profile_error_messages .= "<h4 class='alert alert-error'>" . $validation_message . "</h4>";
            }

            return $profile_error_messages;
        } else {
            $saved_message = FlashMessage::messages('admin.city_edit_success');
            $id = Request::get('id');
            $data = Request::all();
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
        $data = Request::all();

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

        if (Request::get('change_password')) {
            $rules = [
                'email' => 'required|email|unique:users,email,' . $id,
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
                'email' => 'required|email|unique:users,email,' . $id,
                'first_name' => 'required|min:2|max:80|alpha',
                'last_name' => 'required|min:2|max:80|alpha',
                'phone' => 'required|numeric',
                'address_1' => 'required|min:8|max:100',
                'zipcode' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
            ];
        }


        $validator = Validator::make(Request::all(), $rules, $messages);

        if ($validator->fails()) {
            $validation_messages = $validator->messages()->all();
            $profile_error_messages = '';
            foreach ($validation_messages as $validation_message) {
                $profile_error_messages .= "<h4 class='alert alert-error'>" . $validation_message . "</h4>";
            }

            return $profile_error_messages;
        } else {
            $street = '';
            $streetNumber = '';
            $city_id = Request::get('city_id');
            $city = City::find($city_id)->name;
            $zip = Request::get('zipcode');
            $country = 'United States';
            $result = (new Geolocation)->getCoordinates($street, $streetNumber, $city, $zip, $country);
            $profile_message = FlashMessage::messages('vendor.profile_edit_success');
            $data = Request::all();
            $data['latitude'] = $result['latitude'];
            $data['longitude'] = $result['longitude'];
            if (!Request::get('change_password')) {
                $data['password'] = $user_data->password;
            } else {
                $data['password'] = Hash::make($data['password']);
            }
            $file = Request::file('profile_picture');
            if ($file) {
                $destinationPath = config('app.upload_path');
                $filename = $file->getClientOriginalName();
                $filename = str_replace('.', '-' . $username . '.', 'profile-' . $filename);
                $data['profile_picture'] = $filename;
                Request::file('profile_picture')->move($destinationPath, $filename);
            } else {
                $data['profile_picture'] = Auth::user()->profile_picture;
            }
            $save = User::profile($data, $id);

            if ($user_data->type_id == 3) {
                $affectedRows = VendorService::where('vendor_id', '=', $id)->delete();
                foreach ($data['vendor_services'] as $value) {
                    $dataArray['vendor_id'] = $id;
                    $dataArray['status'] = 1;
                    $dataArray['service_id'] = $value;

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

    function customerCompany()
    {
        $input = Request::all();
        $company = User::getCustomerCompanyById($input['id']);

        return $company;
    }

    //For changing the grid with respect to Status button
    function ajaxDashoboardGridRequests()
    {
        $input = Request::all();
        if ($input['id'] != "null") {
            $requests = MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')
                ->where('status', '=', $input['id'])->get();
        } else {
            $requests = MaintenanceRequest::orderByRaw("FIELD(emergency_request , '1', '0') ASC")->orderBy('id', 'desc')
                ->where('status', '!=', 4)->get();
        }


        $request_ids = [];
        $request_service_ids = [];
        $assigned_request_ids = [];
        $numberofrequestids = [];
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
            [
                'requests' => $requests,
                'statusshow' => $input['statusshow'],
                'numberofrequestids' => $numberofrequestids
            ]
        );
    }


    function ajaxDashoboardGridOrdersPagination()
    {
        $input = Request::all();
        $work_orders = Order::orderBy('id', 'desc')
            ->where("status", "=", $input['id'])
            ->get();


        $list_orders = [];
        $i = 0;


        foreach ($work_orders as $order) {
            $order_details = ($order->orderDetail);
            //Property Address, City, State, Zip fields

            $customerfirstname = "";
            if (isset($order->customer->first_name)) {
                $customerfirstname = $order->customer->first_name;
            }

            $customerlastname = "";
            if (isset($order->customer->last_name)) {
                $customerlastname = $order->customer->last_name;
            }

            $customertype = "";

            if (isset($order->maintenanceRequest->asset->customerTitle->title)) {
                $customertype = $order->maintenanceRequest->asset->customerTitle->title;
            } else {
                $customertype = "";
            }


            $vendorfirstname = "";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname = $order->vendor->first_name;
            }

            $vendorlastname = "";
            if (isset($order->vendor->last_name)) {
                $vendorlastname = $order->vendor->last_name;
            }
            $vendorcompany = "";
            if (isset($order->vendor->last_name)) {
                $vendorcompany = $order->vendor->company;
            } else {
                $vendorcompany = "";
            }
            $vendorprice = "";
            $customerprice = "";
            foreach ($order_details as $requestedServiceData) {
                $SpecialPriceVendor = SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                    ->where('customer_id', '=', $order->vendor_id)
                    ->where('type_id', '=', 3)
                    ->get();

                if (!empty($SpecialPriceVendor[0])) {
                    if ($requestedServiceData->requestedService->quantity > 0) {
                        $vendorprice = $SpecialPriceVendor[0]->special_price * $requestedServiceData->requestedService->quantity;
                    } else {
                        $vendorprice = $SpecialPriceVendor[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->vendor_price)) {
                        $vendorprice = $requestedServiceData->requestedService->service->vendor_price;
                    } else {
                        $vendorprice = "";
                    }
                }


                $SpecialPriceCustomer = SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                    ->where('customer_id', '=', $order->customer_id)
                    ->where('type_id', '=', 2)
                    ->get();

                if (!empty($SpecialPriceCustomer[0])) {
                    if ($requestedServiceData->requestedService->quantity > 0) {
                        $customerprice = $SpecialPriceCustomer[0]->special_price * $requestedServiceData->requestedService->quantity;
                    } else {
                        $customerprice = $SpecialPriceCustomer[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->customer_price)) {
                        $customerprice = $requestedServiceData->requestedService->service->customer_price;
                    } else {
                        $customerprice = "";
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
            $list_orders[$i]['customer_name'] = $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['customer_type'] = $customertype;

            $list_orders[$i]['vendor_name'] = $vendorfirstname . ' ' . $vendorlastname;
            $list_orders[$i]['vendor_company'] = $vendorcompany;
            $list_orders[$i]['vendor_price'] = $vendorprice;
            $list_orders[$i]['customer_price'] = $customerprice;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['asset_id'] = $order->maintenanceRequest->asset->id;
            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at));
            $list_orders[$i]['updated_at'] = date('m/d/Y h:i:s A', strtotime($order->updated_at));
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
            $list_orders[$i]['request_status'] = $order->maintenanceRequest->status;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status == 1) ? "warning" : $order->status_class;;
            $list_orders[$i]['status_text'] = ($order->status == 1) ? "In-Process" : $order->status_text;
            $list_orders[$i]['submited_by'] = "";
            if (isset($order->maintenanceRequest->user2->first_name) && isset($order->maintenanceRequest->user2->last_name)) {
                $list_orders[$i]['submited_by'] = $order->maintenanceRequest->user2->first_name . " " . $order->maintenanceRequest->user2->last_name;
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date) && ($order_detail->requestedService->due_date != "")) {
                    $due_date = date('m/d/Y', strtotime($order_detail->requestedService->due_date));
                } else {
                    $due_date = "Not Set";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'] .= $due_date . ', <br>';
                }
            }

            $list_orders[$i]['service_type'] = "";

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_type'] .= $order_detail->requestedService->service->title . ' / <br>';
                }
            }


            $i++;
        }


        if ($input['id'] == 2) {
            return view('pages.admin.ajax-dashboard-grid-orders-completed')
                ->with('orders', $list_orders)
                ->with('db_table', 'orders');
        } elseif ($input['id'] == 4) {
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
        $input = Request::all();
        // print_r($input);
        // exit();
        if ($input['id'] == 4) {
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

            $customerfirstname = "";
            if (isset($order->customer->first_name)) {
                $customerfirstname = $order->customer->first_name;
            }

            $customerlastname = "";
            if (isset($order->customer->last_name)) {
                $customerlastname = $order->customer->last_name;
            }

            $customertype = "";

            if (isset($order->maintenanceRequest->asset->customerTitle->title)) {
                $customertype = $order->maintenanceRequest->asset->customerTitle->title;
            } else {
                $customertype = "";
            }


            $vendorfirstname = "";
            if (isset($order->vendor->first_name)) {
                $vendorfirstname = $order->vendor->first_name;
            }

            $vendorlastname = "";
            if (isset($order->vendor->last_name)) {
                $vendorlastname = $order->vendor->last_name;
            }
            $vendorcompany = "";
            if (isset($order->vendor->last_name)) {
                $vendorcompany = $order->vendor->company;
            } else {
                $vendorcompany = "";
            }
            $vendorprice = "";
            $customerprice = "";
            foreach ($order_details as $requestedServiceData) {
                if (!empty($requestedServiceData->requestedService->service->id)) {
                    $SpecialPriceVendor = SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                        ->where('customer_id', '=', $order->vendor_id)
                        ->where('type_id', '=', 3)
                        ->get();
                } else {
                    $SpecialPriceVendor = null;
                }
                if (!empty($SpecialPriceVendor[0])) {
                    if ($requestedServiceData->requestedService->quantity > 0) {
                        $vendorprice = $SpecialPriceVendor[0]->special_price * $requestedServiceData->requestedService->quantity;
                    } else {
                        $vendorprice = $SpecialPriceVendor[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->vendor_price)) {
                        $vendorprice = $requestedServiceData->requestedService->service->vendor_price;
                    } else {
                        $vendorprice = "";
                    }
                }


                if (isset($requestedServiceData->requestedService->service->id)) {
                    $SpecialPriceCustomer = SpecialPrice::where('service_id', '=', $requestedServiceData->requestedService->service->id)
                        ->where('customer_id', '=', $order->customer_id)
                        ->where('type_id', '=', 2)
                        ->get();
                } else {
                    $SpecialPriceCustomer = "";
                }
                if (!empty($SpecialPriceCustomer[0])) {
                    if ($requestedServiceData->requestedService->quantity > 0) {
                        $customerprice = $SpecialPriceCustomer[0]->special_price * $requestedServiceData->requestedService->quantity;
                    } else {
                        $customerprice = $SpecialPriceCustomer[0]->special_price;
                    }
                } else {
                    if (isset($requestedServiceData->requestedService->service->customer_price)) {
                        $customerprice = $requestedServiceData->requestedService->service->customer_price;
                    } else {
                        $customerprice = "";
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

            $vendorsCustomdata = OrderCustomData::where('order_id', $order->id)->get();
            $customerCustomdata = OrderCustomData::where('order_id', '=', $order->id)->get();
            $quantityCustom = OrderCustomData::where('order_id', '=', $order->id)->pluck('quantity');
            $adminQuantityCustom = OrderCustomData::where('order_id', '=', $order->id)->pluck('quantity');
            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $customerfirstname . ' ' . $customerlastname;
            $list_orders[$i]['customer_type'] = $customertype;

            $list_orders[$i]['vendor_name'] = $vendorfirstname . ' ' . $vendorlastname;
            $list_orders[$i]['vendor_company'] = $vendorcompany;

            if (isset($vendorsCustomdata->vendors_price)) {
                $list_orders[$i]['vendor_price'] = $vendorsCustomdata->vendors_price * $quantityCustom;
            } else {
                $list_orders[$i]['vendor_price'] = $vendorprice;
            }

            if (isset($customerCustomdata->customer_price)) {
                $list_orders[$i]['customer_price'] = $customerCustomdata->customer_price * $adminQuantityCustom;
            } else {
                $list_orders[$i]['customer_price'] = $customerprice;
            }
            $list_orders[$i]['vendor_submitted'] = $order->vendor_submitted;

            if (isset($order->maintenanceRequest->asset->asset_number))
            {
                $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            }
            else
            {
                $list_orders[$i]['asset_number'] = "";
            }

            if (isset($order->maintenanceRequest->asset->id))
            {
                $list_orders[$i]['asset_id'] = $order->maintenanceRequest->asset->id;
            }


            $list_orders[$i]['job_type'] = $jobtype;
            $list_orders[$i]['order_date'] = date('m/d/Y h:i:s A', strtotime($order->created_at));
            $list_orders[$i]['updated_at'] = date('m/d/Y h:i:s A', strtotime($order->updated_at));
            $list_orders[$i]['service_name'] = '';

            if (isset($order->maintenanceRequest->asset->id))
            {
                $list_orders[$i]['property_id'] = $order->maintenanceRequest->asset->id;                
            } 
            else
            {
                $list_orders[$i]['propert_id'] = '';
            }

            if (isset($order->maintenanceRequest->asset->property_address))
            {
                $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;                
            }
            else
            {
                $list_orders[$i]['property_address'] = "";
            }

            if (isset($order->maintenanceRequest->asset->city->name)) {
                $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                $list_orders[$i]['city'] = "";
            }

            if (isset($order->maintenanceRequest->asset->state->name)) {
                $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            } else {
                $list_orders[$i]['state'] = "";
            }

            if (isset($order->maintenanceRequest->asset->zip))
            {
                $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;                
            }
            else
            {
                $list_orders[$i]['zipcode'] = "";
            }

            if (isset($order->maintenanceRequest->asset->UNIT))
            {
                $list_orders[$i]['units'] = $order->maintenanceRequest->asset->UNIT;                
            }
            else
            {
                $list_orders[$i]['units'] = "";
            }

            if (isset($order->maintenanceRequest->asset->loan_number))
            {
                $list_orders[$i]['loan_numbers'] = $order->maintenanceRequest->asset->loan_number;                
            }
            else
            {
                $list_orders[$i]['loan_numbers'] = "";
            }

            $list_orders[$i]['completion_date'] = $order->completion_date;

            if (isset($order->maintenanceRequest->status))
            {
                $list_orders[$i]['request_status'] = $order->maintenanceRequest->status;
            }
            else
            {
                $list_orders[$i]['request_status'] = "";
            }
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['billing_note'] = $order->billing_note;
            $list_orders[$i]['status_class'] = ($order->status == 1) ? "warning" : $order->status_class;
            $list_orders[$i]['status_text'] = ($order->status == 1) ? "In-Process" : $order->status_text;
            $list_orders[$i]['submited_by'] = "";
            
            if (isset($order->maintenanceRequest->user2->first_name))
            {
                $first_name = $order->maintenanceRequest->user2->first_name;
            }
            else
            {
                $first_name = false;
            }

            if (isset($order->maintenanceRequest->user2->last_name))
            {
                $last_name = $order->maintenanceRequest->user2->last_name;
            }
            else
            {
                $last_name = false;
            }

            if ($first_name && $last_name)
            {
                $list_orders[$i]['submited_by'] = $first_name." ".substr($last_name, 0,1).".";
            } 
            else if ($first_name)
            {
                $list_orders[$i]['submited_by'] = $first_name;
            }
            else if ($last_name)
            {
                $list_orders[$i]['submited_by'] = $last_name;
            }
            else
            {
                $list_orders[$i]['submited_by'] = "Not Set";
            }

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->due_date) && ($order_detail->requestedService->due_date != "")) {
                    $style = "";
                    if ((strtotime(date('m/d/Y')) > strtotime($order_detail->requestedService->due_date))) {
                        $style = 'style="background-color:yellow;"';
                    }

                    $due_date = "<p " . $style . " >" . date('m/d/Y', strtotime($order_detail->requestedService->due_date)) . "</p>";
                } else {
                    $due_date = "Not Set";
                }
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'] .= $due_date . ' <br>';
                }
            }

            $list_orders[$i]['service_type'] = "";

            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_type'] .= $order_detail->requestedService->service->title . '<br>';
                }
            }
            if (!empty($additional_service_items)) {
                foreach ($additional_service_items as $item) {
                    // if ($item->order_id == $order->id) {

                    // print_r($order_id);
                    // echo "<br>";
                    // print_r($order_id);
                    $addl_itemz[$order->id][$order->id . "-" . $additional_count] = $item->title;
                    $addl_itemz_rate[$order->id . "-" . $additional_count] = $item->rate * $item->quantity;
                    $addl_itemz_customerPrice[$order->id . "-" . $additional_count] = $item->customer_price * $item->quantity;
                    $addl_itemz_service_type[$order->id . "-" . $additional_count] = $item->title;
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


        if ($input['id'] == 2) {
            return view('pages.admin.ajax-dashboard-grid-orders-completed')
                ->with('orders', $list_orders)
                ->with('db_table', 'orders')
                ->with('addl_itemz', $addl_itemz)
                ->with('addl_itemz_rate', $addl_itemz_rate)
                ->with('addl_itemz_service_type', $addl_itemz_service_type)
                ->with('addl_itemz_customerPrice', $addl_itemz_customerPrice);
        } elseif ($input['id'] == 4) {
            return view('pages.admin.ajax-dashboard-grid-orders-approved')
                ->with('orders', $list_orders)
                ->with('db_table', 'orders')
                ->with('addl_itemz', $addl_itemz)
                ->with('addl_itemz_rate', $addl_itemz_rate)
                ->with('addl_itemz_service_type', $addl_itemz_service_type)
                ->with('addl_itemz_customerPrice', $addl_itemz_customerPrice);
        } else {
            return view('pages.admin.ajax-dashoboard-grid-orders')
                ->with('orders', $list_orders)
                ->with('id', $input['id'])
                ->with('db_table', 'orders')
                ->with('addl_itemz', $addl_itemz);
        }
    }

    function ajaxDeleteServiceRequest()
    {
        $input = Request::all();
        RequestedService::where('request_id', '=', $input['request_id'])
            ->where('service_id', '=', $input['service_id'])
            ->delete();
    }

    /**
     * List of Service Categories
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
        $CustomerType = CustomerType::find($id);

        return view('pages.admin.editcustomertype')
            ->with('CustomerType', $CustomerType);
    }


    public function addNewServiceCategory()
    {

        $title = Request::get('title');

        $rules = [
            'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Request::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-service-category')
                ->withErrors($validator)
                ->withInput();
        } else {
            $serviceCategory = new ServiceCategory();
            $serviceCategory->title = $title;


            //echo '<pre>'; print_r($user_data); exit;

            $serviceCategory->save();
            $from_email = config('app.admin_email');


            $message = "Service Category has been added";

            return redirect('/list-service-categories')
                ->with('message', FlashMessage::displayAlert($message, 'success'));
        }

        return view('pages.admin.addservicecategory');
    }

    public function editNewCustomerType()
    {

        $title = Request::get('title');
        $id = Request::get('id');

        $rules = [
            'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Request::all(), $rules);
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
                ->update(['title' => $title]);


            $message = "Customer type has been modified";

            return redirect('/list-customer-type')
                ->with('message', FlashMessage::displayAlert($message, 'success'));
        }

        return view('pages.admin.addservicecategory');
    }

    public function addNewCustomerType()
    {

        $title = Request::get('title');

        $rules = [
            'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Request::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-customer-type')
                ->withErrors($validator)
                ->withInput();
        } else {
            $serviceCategory = new CustomerType();
            $serviceCategory->title = $title;


            //echo '<pre>'; print_r($user_data); exit;

            $serviceCategory->save();


            $message = "Customer type has been added";

            return redirect('/list-customer-type')
                ->with('message', FlashMessage::displayAlert($message, 'success'));
        }

        return view('pages.admin.addservicecategory');
    }

    public function addNewJobType()
    {

        $title = Request::get('title');

        $rules = [
            'title' => 'required|between:3,55'
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Request::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $messages = $validator->messages();

            return redirect('/add-job-type')
                ->withErrors($validator)
                ->withInput();
        } else {
            $serviceCategory = new JobType();
            $serviceCategory->title = $title;


            //echo '<pre>'; print_r($user_data); exit;

            $serviceCategory->save();


            $message = "Job type has been added";

            return redirect('/list-job-type')
                ->with('message', FlashMessage::displayAlert($message, 'success'));
        }

        return view('pages.admin.addservicecategory');
    }

    function listJobType()
    {

        $serviceCategories = JobType::get();
        $serv = new Service();
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
        $submitted = Request::get('submitted');
        if ($submitted) {
            $data = Request::all();
            unset($data['_token']);
            unset($data['submitted']);
            //echo '<pre>'; print_r($data); exit;

            $rules = [
                // 'service_code' => 'required',
                'title' => 'required'
            ];

            $validator = Validator::make(Request::all(), $rules); // put all rules to validator
            // if validation is failed redirect to add customer asset with errors
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator);
            } else {
                $data['service_cat_id'] = 8;
                $save = Service::addAdminService($data);
                $serviceID = DB::getPdo()->lastInsertId();
                if ($save) {
                    if (isset($data['number_of_men'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'number_of_men',
                            'service_id' => $serviceID,
                            'field_type' => $data['number_of_men_type'],
                            'field_values' => $data['number_of_men_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['verified_vacancy'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'verified_vacancy',
                            'service_id' => $serviceID,
                            'field_type' => $data['verified_vacancy_type'],
                            'field_values' => $data['verified_vacancy_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['cash_for_keys'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'cash_for_keys',
                            'service_id' => $serviceID,
                            'field_type' => $data['cash_for_keys_type'],
                            'field_values' => $data['cash_for_keys_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['cash_for_keys_trash_out'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'cash_for_keys_trash_out',
                            'service_id' => $serviceID,
                            'field_type' => $data['cash_for_keys_trash_out_type'],
                            'field_values' => $data['cash_for_keys_trash_out_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }
                    if (isset($data['trash_size'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'trash_size',
                            'service_id' => $serviceID,
                            'field_type' => $data['trash_size_type'],
                            'field_values' => $data['trash_size_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['storage_shed'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'storage_shed',
                            'service_id' => $serviceID,
                            'field_type' => $data['storage_shed_type'],
                            'field_values' => $data['storage_shed_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['set_prinkler_system_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'set_prinkler_system_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['set_prinkler_system_type_type'],
                            'field_values' => $data['set_prinkler_system_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['install_temporary_system_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'install_temporary_system_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['install_temporary_system_type_type'],
                            'field_values' => $data['install_temporary_system_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['carpet_service_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'carpet_service_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['carpet_service_type_type'],
                            'field_values' => $data['carpet_service_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['pool_service_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'pool_service_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['pool_service_type_type'],
                            'field_values' => $data['pool_service_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['boarding_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'boarding_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['boarding_type_type'],
                            'field_values' => $data['boarding_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['spruce_up_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'spruce_up_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['spruce_up_type_type'],
                            'field_values' => $data['spruce_up_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }
                    if (isset($data['lot_size'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'lot_size_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['lot_size_type'],
                            'field_values' => $data['lot_size_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['constable_information_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'constable_information_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['constable_information_type_type'],
                            'field_values' => $data['constable_information_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['remove_carpe_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'remove_carpe_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['remove_carpe_type_type'],
                            'field_values' => $data['remove_carpe_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    if (isset($data['remove_blinds_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'remove_blinds_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['remove_blinds_type_type'],
                            'field_values' => $data['remove_blinds_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }


                    if (isset($data['remove_appliances_type'])) {
                        $ServicesFieldsDetailData = [
                            'fieldname' => 'remove_appliances_type',
                            'service_id' => $serviceID,
                            'field_type' => $data['remove_appliances_type_type'],
                            'field_values' => $data['remove_appliances_type_values']
                        ];
                        ServiceFieldDetail::add($ServicesFieldsDetailData);
                    }

                    echo $serviceID;


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
            $ServiceCategory = ServiceCategory::get();
            $CustomerType = CustomerType::get();
            $JobType = JobType::get();
            $typeArray = [
                "select" => "select",
                "text" => "text",
                "checkbox" => "checkbox",
                "radio" => "radio"
            ];

            return view('pages.admin.do-request')
                ->with('typeArray', $typeArray)
                ->with('ServiceCategory', $ServiceCategory)
                ->with('CustomerType', $CustomerType)
                ->with('JobType', $JobType);
        }
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
