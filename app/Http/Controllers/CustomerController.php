<?php

class CustomerController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //Show dashboard of customer
        Session::put('clientType', "abcdef"); //Set abnormal values for making customer not to force login with client type
        $clientTypeSession=  Session::get('clientType');


        $user_id = Auth::user()->id;
        $requests = MaintenanceRequest::listMaintenanceRequestByCustomerId2($user_id);
        $maintenanceRequest = MaintenanceRequest::listMaintenanceRequestByCustomerId($user_id, 5);
        $CustomerType=CustomerType::get();
        //Completed ORders
        $list_orders=Order::dashBoardOrders(Auth::user()->id, 2);
        //All workorders
        $list_complete_orders=Order::dashBoardOrders(Auth::user()->id);

        //Recent Assets
        $all_assets=Asset::where('customer_id', '=', Auth::user()->id)
        ->skip(0)
        ->take(5)
        ->orderBy('id', 'desc')->get();
        return View::make('pages.customer.dashboard')
        ->with('maintenanceRequest', $maintenanceRequest)
        ->with('requests', $requests)
        ->with('completeorder', $list_orders)
        ->with('list_complete_orders', $list_complete_orders)
        ->with('assets', $all_assets)
        ->with('CustomerType', $CustomerType)
        ->with('clientTypeSession', $clientTypeSession);
    }

    public function showCompleteProfile()
    {
        if (Auth::check()) { //if logged in show customer profile complete step 1
            $id = Auth::user()->id; // Get current user logged in id
            $profile_status = Auth::user()->profile_status;

            if ($profile_status < 1) {
                $user_detail = User::find($id); // get user detail by id
                //Get all cities from city
                $cities = City::getAllCities();
                //Get all states from city
                $states = State::getAllStates();
                //Send data to view to show user data
                return View::make('pages.customer.customer_profile_complete')->with('user_detail', $user_detail)
                ->with('cities', $cities)
                ->with('states', $states);
            } else {
                return Redirect::to('edit-profile');
            }
        } else {
            //if not logged in return to login page
            return Redirect::to('/');
        }
    }

    public function completeProfile()
    {
        if (Auth::check()) { //if logged in
            $id = Auth::user()->id; // Get current user logged in id
            if (Input::get('save_continue')) {
                $redirect = 'customer';
            } elseif (Input::get('save_exit')) {
                $redirect = 'customer';
            }
            //Set rules for validation
            if (Input::get('create_by_admin') == 'yes') {
                $username = Input::get('username');
                $rules = [
                    'username' => 'required|unique:users',
                    'phone' => 'required|numeric',
                    'address_1' => 'required|min:8|max:100',
                    'zipcode' => 'required',
                    'state_id' => 'required',
                    'city_id' => 'required',
                    ];
            } else {
                $username = Auth::user()->username;
                $rules = [
                    'phone' => 'required|numeric',
                    'address_1' => 'required|min:8|max:100',
                    'zipcode' => 'required',
                    'state_id' => 'required',
                    'city_id' => 'required',
                    ];
            }
            $validator = Validator::make(Input::all(), $rules); // put all rules to validator
            // if validation is failed redirect to page with errorsa
            if ($validator->fails()) {
                return Redirect::to('customer-profile-complete')
                                ->withErrors($validator)// send back all errors to the login form
                                ->withInput(Input::except('profile_picture')); // send back the input (not the password) so that we can repopulate the form
            } else {
                $data = Input::all();
                $file = Input::file('profile_picture');
                //This section will handel profile pictures.
                if ($file) {
                    $destinationPath = Config::get('app.upload_path');
                    $filename = $file->getClientOriginalName();
                    $filename = str_replace('.', '-' . $username . '.', 'profile-' . $filename);
                    $data['profile_picture'] = $filename;
                    Input::file('profile_picture')->move($destinationPath, $filename);
                } else {
                    $data['profile_picture'] = Auth::user()->profile_picture;
                }
                // Get all form data in $data variable
                //  $data = Input::all();


                //User Notification Email for profile completeness
                                
                $email_data = [
                'user_email_template'=>EmailNotification::$user_email_completeness_template];
                Email::send(Auth::user()->email, 'Your profile has been completed', 'emails.user_email_template', $email_data);
 //End Nofication Email Code
                                
                $data['profile_status'] = 1;
                $save = User::profile($data, $id);
                if ($save) {
                    return Redirect::to($redirect);
                }
            }
        } else {
            return Redirect::to('/');
        }
    }

    /*
     * Admin functions
     *
     */

    //Create New Customer by admin
    function createCustomerAdmin()
    {
        // get method Post or Get from route
        $method = Request::method();
        //if Add customer form is submitted
        if ($method == 'POST') {
            //Get all submitted data from form
            $data = Input::all();
            $rules = [
                'first_name' => 'required|min:2|max:80|alpha',
                'last_name' => 'required|min:2|max:80|alpha',
                'email' => 'required|email|unique:users|between:3,64',
                'password' => 'required|between:4,20'
                ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                // Return all error with seperation
                $errors = General::validationErrors($validator);
                return Redirect::back()->withErrors($errors)
                                ->withInput(); // Send back to page wiht input fields inserted;
            } else {
                $user_type_id = UserType::where('title', '=', 'customer')->first();
                $user_types = UserType::find($user_type_id->id);
                $user_roles = UserRole::where('role_name', '=', $user_types->title)->first();
               // $passowrd = rand(); //Get random password to send user
                $passowrd=$data['password'];
                $data['password'] = Hash::make($passowrd);
                $data['status'] = 0;
                $data['user_role_id'] = $user_roles->id;
                $data['type_id'] = $user_type_id->id;
                $add_customer = User::createUser($data); // Add customer
                $data['password'] = $passowrd;
                $data['user_id'] = $add_customer;
                $from_email = Config::get('app.admin_email');
                Mail::send('emails.admin_customer_created', $data, function ($message) use ($from_email) {

                    $message->to(Input::get('email'), Input::get('first_name') . ' ' . Input::get('last_name'))
                    ->subject('Customer Created By Admin!')
                    ->from($from_email, 'GSS');
                });
                
                Mail::send('emails.admin_customer_created_for_admin', $data, function ($message) use ($from_email, $data) {
                    $message->to($from_email, Input::get('Admin'))
                    ->subject('Customer Created!')
                    ->from($data['email'], 'GSS');
                });
            }

            if ($add_customer) {
                return Redirect::to('list-customer')
                ->with('message', FlashMessage::displayAlert("Customer has been created successfully", 'success'));
            }
        }
            $CustomerType  = CustomerType::get();
        

        return View::make('pages.admin.add_customer')
        ->with('CustomerType', $CustomerType);
    }

    function listCustomerAdmin()
    {

        $customers = User::getCustomers();

        $user_table = new User;
        $db_table = $user_table->getTable();



        return View::make('pages.admin.list_customer')
        ->with(['customers' => $customers,
            'db_table' => $db_table
            ]);
        // return View::make('pages.admin.list_customer');
    }
    
    function activeCustomer($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userController = new UserController;
            $redirect = $userController->whereRedirect($user->id);
            $userdata = ['status' => 1 ];
            $save = User::find($id)->update($userdata);
            return $redirect;
        } else {
            // show the form
            $userdata = ['status' => 1 ];
            $save = User::find($id)->update($userdata);
            return View::make('home')->with('active', $id);
        }
    }

    function editCustomerAdmin($customer_id)
    {
        $method = Request::method();
        $customer_detail = User::find($customer_id);

        if ($method == 'POST') {
            $data = Input::all();
            $rules = [
                'first_name' => 'required|min:2|max:80|alpha',
                'last_name' => 'required|min:2|max:80|alpha',
                'email' => 'required|email'
                ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                // Return all error with seperation
                $errors = General::validationErrors($validator);
                return Redirect::back()->withErrors($errors)
                                ->withInput(); // Send back to page wiht input fields inserted;
            } else {
                $save = User::profile($data, $customer_id);
                if ($save) {
                    General::writeMessage('admin.update_customer');
                }
            }
        }
                          $CustomerType  = CustomerType::get();

                        return View::make('pages.admin.edit_customer')
                        ->with('customer', $customer_detail)
                        ->with('CustomerType', $CustomerType);
    }

/*
List all workorders
*/
    public function listWorkOrder()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('customer_id', '=', $user_id)
                    ->orderBy('id', 'desc')
                    ->whereHas('MaintenanceRequest', function ($query) {
                   
                         $query ->whereHas('Asset', function ($query2) {
                            // $query2->where('customer_type', '=',  Session::get('clientType'));
                         });
                    })->get();
        $list_orders = [];
        $i = 0;


        foreach ($orders as $order) {
            $order_details = ($order->orderDetail);
            $customerfirst_name="";
            if (isset($order->customer->first_name)) {
                   $customerfirst_name=$order->customer->first_name;
            }
             $customerlast_name="";
            if (isset($order->customer->last_name)) {
                 $customerlast_name=$order->customer->last_name;
            }



              $vendorfirst_name="";
            if (isset($order->vendor->first_name)) {
                   $vendorfirst_name=$order->vendor->first_name;
            }
             $vendorlast_name="";
            if (isset($order->vendor->last_name)) {
                 $vendorlast_name=$order->vendor->last_name;
            }

            $clientType = "";
            if (isset($order->maintenanceRequest->asset->customerType->title)) {
                 $clientType =$order->maintenanceRequest->asset->customerType->title ;
            } else {
                 $clientType = "";
            }





            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $customerfirst_name. ' ' .  $customerlast_name;
            $list_orders[$i]['vendor_name'] = $vendorfirst_name . ' ' . $vendorlast_name;
            $list_orders[$i]['clientType'] = $clientType;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            $list_orders[$i]['request_status'] = $order->maintenanceRequest->status;
            $list_orders[$i]['order_date'] = '' ;
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;
            $list_orders[$i]['due_date']="";

            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
                $list_orders[$i]['due_date'].=$order_detail->requestedService->due_date . ', <br>';
            }
            $i++;
        }
         return View::make('pages.customer.list_work_orders')->with('orders', $list_orders);
    }
        /*
        List only completed work orders
        */

    public function listCompletedWorkOrder()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('customer_id', '=', $user_id)->where('status', '=', '2')->orderBy('id', 'desc')->get();
        //For all workorder those are recently completed
        $list_orders = [];
        $i = 0;
        foreach ($orders as $order) {
            $order_details = ($order->orderDetail);


            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $order->customer->first_name . ' ' . $order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $order->vendor->first_name . ' ' . $order->vendor->last_name;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['order_date'] = date('d/m/Y h:i:s A', strtotime($order->created_at)) ;
            $list_orders[$i]['service_name'] = '';
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }
        return View::make('pages.customer.list_completed_work_orders')->with('orders', $list_orders);
    }

    /*
    All Orders that need to be approved by customer
    Status=3
    */
    public function listApprovalCompletion()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('customer_id', '=', $user_id)->where('status', '=', '3')->orderBy('id', 'desc')->get();
        $list_orders = [];
        $i = 0;
        foreach ($orders as $order) {
            $order_details = ($order->orderDetail);


            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $order->customer->first_name . ' ' . $order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $order->vendor->first_name . ' ' . $order->vendor->last_name;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['order_date'] = $order->created_at;
            $list_orders[$i]['service_name'] = '';
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }
        return View::make('pages.customer.list_approval_completion_order')->with('orders', $list_orders);
    }


    public function listProcessWorkOrder()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('customer_id', '=', $user_id)->where('status', '=', '1')->orderBy('id', 'desc')->get();
        $list_orders = [];
        $i = 0;
        foreach ($orders as $order) {
            $order_details = ($order->orderDetail);


            $list_orders[$i]['order_id'] = $order->id;
            $list_orders[$i]['customer_name'] = $order->customer->first_name . ' ' . $order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $order->vendor->first_name . ' ' . $order->vendor->last_name;
            $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            $list_orders[$i]['order_date'] = $order->created_at;
            $list_orders[$i]['service_name'] = '';
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
            $i++;
        }
        return View::make('pages.customer.list_process_work_order')->with('orders', $list_orders);
    }


/**
     * List All Assigned requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listBidRequests($status = 1)
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('customer_id', '=', $user_id)->where('status', "=", $status)

            ->whereHas('Asset', function ($query) {
            // $query->where('customer_type', '=',  Session::get('clientType'));
            })
            ->orderBy('id', 'desc')
            ->get();

    
        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)
            ->where('status', '=', 1)
            ->get();


            $assign_requests[$i]['request_id'] = $request->id;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['price'] = '';
            $assign_requests[$i]['customer_name'] = $request->user->first_name .' '. $request->user->last_name;
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
        
              $assign_requests[$i]['property_address'] = $request->asset->address;
               $assign_requests[$i]['zip'] = $request->asset->zip;

            $assign_requests[$i]['city'] = $request->asset->city->name;
               $assign_requests[$i]['state'] = $request->asset->state->name;



            $assign_requests[$i]['request_date'] =date('m/d/Y h:i:s A', strtotime($request->created_at)) ;
            $assign_requests[$i]['due_date'] = $request->created_at;
            $request_id_array=[];
            foreach ($services as $service) {
                $request_id_array[]=$service->service->id;
                $assign_requests[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                $assign_requests[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
            }


            foreach ($request_id_array as $reqs) {
                $Reservices = RequestedService::where('service_id', '=', $reqs)
                ->where('bidding_prince', '<>', 'NULL')
                ->get();

                foreach ($Reservices as $service) {
                       $assign_requests[$i]['price'].='&diams; '.$service->service->title ."--$".$service->bidding_prince. ', <br>';
                }
            }

 

            $i++;
        }


        return View::make('pages.customer.list_bid_requests')
        ->with('assign_requests', $assign_requests)
         ->with('status', $status);
    }

    /**
     * List All Assigned requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listApprovedBidRequests()
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('customer_id', '=', $user_id)->where('status', "=", 2)->orderBy('id', 'desc')->get();

        
        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)->where('status', '=', 1)->orderBy('id', 'desc')->get();
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

        return View::make('pages.customer.list_bid_requests')->with('assign_requests', $assign_requests);
    }

    public function listDeclinedBidRequests()
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('customer_id', '=', $user_id)->where('status', "=", 3)->orderBy('id', 'desc')->get();

        
        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)->where('status', '=', 1)->orderBy('id', 'desc')->get();
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

        return View::make('pages.customer.list_bid_requests')->with('assign_requests', $assign_requests);
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
        
        

        return View::make('pages.customer.viewcustomermaintenancerequest')
        ->with([
            'request_maintenance' => $request_maintenance,
            'assign_requests'=>$assign_requests
            ]);
    }


        /*
         Bid request accepted by customer
         @dafault status =2
        */
    public function acceptBidRequest()
    {
        $input = Input::all();
            
        $BidRequestedService = BidRequestedService::where('request_id', '=', $input['request_id'])
        ->get();
            

        $bidData=[];
        foreach ($BidRequestedService as $biddatavalue) {
            $bidData['request_id']=$biddatavalue->maintenance_request_id;
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
            $bidData['lot_size']    =$biddatavalue->lot_size;
            $bidData['bidding_prince']=$biddatavalue->biding_prince;
           
            $add_requested_service = RequestedService::addRequestedService($bidData);
            $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service
           
           
            $imageDataArray=BidServiceImage::where('requested_id', '=', $biddatavalue->id)->get();
           
            foreach ($imageDataArray as $imageData) {
                $image_detail['requested_id'] = $request_detail_id;
                $image_detail['image_name'] = $imageData->image_name;
                $image_detail['image_type'] = 'request';
                $image_detail['status'] = 1;
                $add_image = ServiceImage::addServiceImage($image_detail);
            }
            $dataRequests['request_id']=$biddatavalue->maintenance_request_id;
            $dataRequests['requested_service_id']=$request_detail_id;
            $dataRequests['vendor_id']=$input['vendor_id'];
            $dataRequests['status']=3;
        

            $accept_request = AssignRequest::create($dataRequests);
        
            $orderDATA= Order::where('request_id', '=', $biddatavalue->maintenance_request_id)
            ->where('vendor_id', '=', $input['vendor_id'])
            ->first();

            $order_details['requested_service_id'] =  $request_detail_id;
            $order_details['order_id'] =$orderDATA->id;
            $order_details['status'] = 1;
            OrderDetail::create($order_details);
        }

       // accepted bid request status
        $data = ['status' => 2 ];
        $save = BidRequest::find($input['request_id'])->update($data);

    
        return "OSR has been accepted. New Work Order will now be generated.";
    }

    /*
    Bid request declined by customer
    @dafault status =3
    */
    public function DeclineBidRequest()
    {
        $input = Input::all();
        // declined bid request status
        $data = ['status' => 3 ];
        $save = BidRequest::find($input['request_id'])->update($data);

        
        return "OSR has been declined";
    }

    public function setClientType($clientType)
    {
        Session::put('clientType', $clientType);
        return Redirect::back();
    }
    public function unSetClientType()
    {
        Session::put('clientType', "");
        return Redirect::to('customer');
    }
}
