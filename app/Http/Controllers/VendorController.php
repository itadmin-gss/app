<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssignRequest;
use App\AssignRequestBid;
use App\AssignRequestBidsImage;
use App\BidRequest;
use App\BidRequestedService;
use App\BidServiceImage;
use App\City;
use App\CustomerType;
use App\EmailNotification;
use App\Helpers\Email;
use App\Helpers\FlashMessage;
use App\Helpers\General;
use App\Invoice;
use App\JobType;
use App\MaintenanceBid;
use App\MaintenanceRequest;
use App\Order;
use App\OrderDetail;
use App\OrderImagesPosition;
use App\Service;
use App\State;
use App\User;
use App\Tokens;
use App\VendorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

/**
 * Vendor Controller Class.
 *
 * Handles Vendor Functionalities & redirects.
 * @version $Id: 1.0
 */

class VendorController extends Controller
{

    // Handle login link sent to email for new vendors
    public function emailLink($token)
    {
        //verify that token is valid
        $token_info = Tokens::where('token', $token)->get();

        if (count($token_info) == 0)
        {
            return "<h2>Invalid Link.</h2><p>Click <a href='/'>here</a> to return to login page</p>";
        }

        //verify token was created within last 72 hours
        $dt1 = new \DateTime();
        $dt2 = new \DateTime($token_info[0]->created_at);
        $dt2->add(new \DateInterval('PT72H'));
        if ($dt2 < $dt1)
        {
            return "<h2>Invalid Link.</h2><p>Click <a href='/'>here</a> to return to login page</p>";
        }

        //if we made it this far, token and link are good, redirect to profile completion page
        Auth::loginUsingId($token_info[0]->user_id);
        return redirect('vendor-profile-complete');
    }

    /**
     * Get Vendor to Vendor Dashboard.
     * @params none
     * @return Redirect to Vendor Dashboard.
     */

    public function index()
    {

        $user_id = Auth::user()->id;
        $new_work_order = Order::where('vendor_id', '=', $user_id)->where('status', '=', 0)->take(5)->orderBy('id', 'desc')->get();
        $requests = AssignRequest::where('vendor_id', '=', $user_id)->take(5)->where('status', '!=', 2)->groupBy('request_id')->orderBy('id', 'desc')->get();
        $assign_requests = [];
        $i = 0;



        foreach ($requests as $request) {
            $orderid="No Work Order";
            $InvoiceRequest="";
            $customerprice="";
            $vendorprice="No Invoice";
            $vendorID="No Invoice";
            $customerID="No Invoice";
            $vendorname="";
            foreach ($request->maintenanceRequest->order as $data) {
                $orderid= $data->id;
            }

            foreach ($request->maintenanceRequest->assignRequest as $dataAssigned) {
                if (isset($dataAssigned->requestedService->service->title)) {
                    $vendorname .=$dataAssigned->requestedService->service->title."<br/>";
                } else {
                    $vendorname .="";
                }
            }



            foreach ($request->maintenanceRequest->invoiceRequest as $dataAssigned) {
                if ($dataAssigned->user_type_id==2) {
                     $customerID=$dataAssigned->id;
                     $customerprice =$dataAssigned->total_amount."<br/>";
                } else {
                    $vendorprice =$dataAssigned->total_amount."<br/>";
                    $vendorID =$dataAssigned->id;
                }
            }


            $assign_requests[$i]['order_id'] =  $orderid;
            $assign_requests[$i]['vendor_price'] =  $vendorprice;
            $assign_requests[$i]['invoiceNo'] =  $vendorID;



            $services = AssignRequest::where('vendor_id', '=', $user_id)->where('request_id', '=', $request->maintenanceRequest->id)->where('status', '!=', 2)->orderBy('id', 'desc')->get();
            $assign_requests[$i]['request_id'] = $request->maintenanceRequest->id;
            $assign_requests[$i]['status'] = $request->maintenanceRequest->status;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';

            $assign_requests[$i]['customer_name'] = $request->maintenanceRequest->user->first_name .' '. $request->maintenanceRequest->user->last_name;
            if (isset($request->maintenanceRequest->asset->asset_number)) {
                         $assign_requests[$i]['asset_number'] = $request->maintenanceRequest->asset->asset_number;
            } else {
                         $assign_requests[$i]['asset_number'] =" ";
            }

            if (isset($request->maintenanceRequest->asset->property_address))
            {
                $assign_requests[$i]['property_address'] = $request->maintenanceRequest->asset->property_address;                
            } 
            else
            {
                $assign_requests[$i]['property_address'] = '';
            }

            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->maintenanceRequest->created_at)) ;
            $assign_requests[$i]['emergency_request']=$request->maintenanceRequest->emergency_request;
            $assign_requests[$i]['due_date'] = $request->maintenanceRequest->created_at;

            $countService=0;
            foreach ($services as $service) {
                if (isset($service->requestedService->service->service_code)) {
                    $assign_requests[$i]['service_code'].='&diams; '.$service->requestedService->service->service_code . ', <br>';
                }
                if (isset($service->requestedService->service->title)) {
                    $assign_requests[$i]['service_name'].='&diams; '.$service->requestedService->service->title ;
                }

                if (isset($service->requestedService->due_date)) {
                    $assign_requests[$i]['service_name'] .= "<br>".    $service->requestedService->due_date . ', <br>';
                } else {
                    $assign_requests[$i]['service_name']  .=   ', <br>';
                }

                         $countService++;
            }
            $assign_requests[$i]['no_of_services']=$countService;
            $i++;
        }
        $recent_orders = Order::where('vendor_id', '=', $user_id)->take(5)->get();




        //Recent Bid Requests


        $requests = BidRequest::where('vendor_id', '=', $user_id)->take(5)->where('status', "=", 1)->orderBy('id', 'desc')->get();


        $assign_requests_bids = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)->where('status', '=', 1)->orderBy('id', 'desc')->get();

            $assign_requests_bids[$i]['request_id'] = $request->id;
            $assign_requests_bids[$i]['service_code'] = '';
            $assign_requests_bids[$i]['service_name'] = '';
            $assign_requests_bids[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->created_at));
            $assign_requests_bids[$i]['due_date'] = $request->created_at;
            $assign_requests_bids[$i]['property_address'] = $request->asset->property_address;
            $request_id_array=[];
            foreach ($services as $service) {
                $request_id_array[]=$service->service->id;

                if (isset($service->service->service_code)) {
                    $assign_requests_bids[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                }
                if (isset($service->service->title)) {
                    $assign_requests_bids[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
                }
            }



            $i++;
        }



        //REcent Invoice



        $invoices  = Invoice::where('user_type_id', '=', 3)
        ->where('user_id', '=', $user_id)

        ->take(5)
        ->orderBy('id', 'desc')
        ->get();

        $list_orders = [];
        $i = 0;


        foreach ($invoices as $invoice) {
            $order_details = ($invoice->order->orderDetail);

            $list_orders[$i]['order_id'] = $invoice->order_id;
            $list_orders[$i]['customer_name'] = $invoice->order->customer->first_name . ' ' . $invoice->order->customer->last_name;
            $list_orders[$i]['vendor_name'] = $invoice->order->vendor->first_name . ' ' . $invoice->order->vendor->last_name;
            
            if (isset($invoice->order->maintenanceRequest->asset->asset_number))
            {
                $list_orders[$i]['asset_number'] = $invoice->order->maintenanceRequest->asset->asset_number;                
            }
            else
            {
                $list_orders[$i]['asset_number'] = "";
            }

            if (isset($invoice->order->maintenanceRequest->asset->address))
            {
                $list_orders[$i]['propery_address'] = $invoice->order->maintenanceRequest->asset->address;                
            }
            else
            {
                $list_orders[$i]['property_address'] = "";
            }

            if (isset($invoice->order->maintenanceRequest->asset->zip))
            {
                $list_orders[$i]['zip'] = $invoice->order->maintenanceRequest->asset->zip;                
            }
            else
            {
                $list_orders[$i]['zip'] = "";
            }

            if (isset($invoice->order->maintenanceRequest->asset->city->name))
            {
                $list_orders[$i]['city'] = $invoice->order->maintenanceRequest->asset->city->name;                
            }
            else
            {
                $list_orders[$i]['city'] = "";
            }

            if (isset($invoice->order->maintenanceRequest->asset->state->name))
            {
                $list_orders[$i]['state'] = $invoice->order->maintenanceRequest->asset->state->name;                
            }
            else
            {
                $list_orders[$i]['state'] = "";
            }

            $list_orders[$i]['order_date'] =date('m/d/Y h:i:s A', strtotime($invoice->order->created_at));
            $list_orders[$i]['service_name'] = '';
            $list_orders[$i]['status'] = $invoice->status;
            $list_orders[$i]['price'] = $invoice->total_amount;
            foreach ($order_details as $order_detail) {
                $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
            }
                 $i++;
        }

        return view('pages.vendors.dashboard')
        ->with('assign_requests', $assign_requests)
        ->with('assign_requests_bids', $assign_requests_bids)
        ->with('recent_orders', $recent_orders)
        ->with('list_orders', $list_orders)
        ->with('new_work_orders', $new_work_order);
    }

    /**
     * Get Vendor to profile complete form.
     * @params none
     * @return Redirect to respective page either its add services or dashboard.
     */
    public function showCompleteProfile()
    {
        if (Auth::check()) {
            $id = Auth::user()->id;
            $profile_status = Auth::user()->profile_status;
            $clientType=CustomerType::get();
            $vendor_services = VendorService::getAllVendorServices();
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
            if ($profile_status < 1) {
                $user_detail = User::find($id);
                $cities = City::getAllCities();
                $states = State::getAllStates();
                return view('pages.vendors.vendor_profile_complete')->with('vendor_services', $servicesDATAoption)->with('cities', $cities)->with('states', $states)->with('user_detail', $user_detail);
            } else {
                return redirect('edit-profile');
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Handle profile complete data & populate database accordingly.
     * @params none
     * @return Error messages or redirect to Dashboard.
     */
    public function completeProfile()
    {
        if (Auth::check()) {
            $id = Auth::user()->id;

            $redirect = 'vendors';

            if (Request::get('create_by_admin') == 'yes') {
                $username = Request::get('username');
                $rules = [
                    'username' => 'required|unique:users',
                    'phone' => 'required|numeric',
                    'address_1' => 'required|min:8|max:100',
                    'zipcode' => 'required',
                    'state_id' => 'required',
                    'city_id' => 'required',
                    'password' => 'required|between:4,20'
                    ];
            } else {
                $username = Auth::user()->username;
                $rules = [
                    'phone' => 'required|numeric',
                    'address_1' => 'required|min:8|max:100',
                    'zipcode' => 'required',
                    'state_id' => 'required',
                    'city_id' => 'required',
                    'password' => 'required|between:4,20'
                    ];
            }


                $city_id = Request::get('city_id');
                $city = City::find($city_id)->name;
                $zip = Request::get('zipcode');
                $property_address= Request::get('address_1');
                $state_id = Request::get('state_id');
                $state = State::find($state_id)->name;
                $result  =  Asset::getLatLong($property_address.$zip, $city, $state);
                $data = Request::all();
                $data['latitude'] = 0;
                $data['longitude'] = 0;
                $data['password']  = Hash::make(Request::get('password'));
                $data['available_zipcodes'] = Request::get('zip_list');



                 //User Notification Email for profile completeness

                $email_data = [
                'user_email_template'=>EmailNotification::$user_email_completeness_template];
                Email::send(Auth::user()->email, 'Your profile has been completed', 'emails.user_email_template', $email_data);
                 //End Nofication Email Code

                $data['profile_status'] = 1;
                $file = Request::file('profile_picture');
                //This section will handel profile pictures.
                if ($file) {
                    $destinationPath = config('app.upload_path');
                    $filename = $file->getClientOriginalName();
                    $filename = str_replace('.', '-' . $username . '.', 'profile-' . $filename);
                    $data['profile_picture'] = $filename;
                    Request::file('profile_picture')->move($destinationPath, $filename);
                } else {
                    $data['profile_picture'] = Auth::user()->profile_picture;
                }

                foreach(Request::get('vendor_services') as $srv)
                {
                    VendorService::addVendorServices(array('vendor_id' => $id, 'service_id' => $srv, 'status' => 1));
                }

                $save = User::profile($data, $id);
                if ($save) {
                    return redirect($redirect);
                }

        } else {
            return redirect('/');
        }
    }

    /**
     * List All Assigned requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listAssignedRequests()
    {

        $user_id = Auth::user()->id;
        $requests = AssignRequest::where('vendor_id', '=', $user_id)->where('status', "=", 1)->groupBy('request_id')->orderBy('id', 'desc')->get();
        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = AssignRequest::where('vendor_id', '=', $user_id)->where('request_id', '=', $request->maintenanceRequest->id)->where('status', '!=', 2)->orderBy('id', 'desc')->get();
            $assign_requests[$i]['request_id'] = $request->maintenanceRequest->id;
            $assign_requests[$i]['status'] = $request->maintenanceRequest->status;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';

            $assign_requests[$i]['customer_name'] = $request->maintenanceRequest->user->first_name .' '. $request->maintenanceRequest->user->last_name;
            $assign_requests[$i]['asset_number'] = $request->maintenanceRequest->asset->asset_number;
            $assign_requests[$i]['property_address'] = $request->maintenanceRequest->asset->property_address;
            $assign_requests[$i]['zip'] = $request->maintenanceRequest->asset->zip;
            $assign_requests[$i]['city'] = $request->maintenanceRequest->asset->city->name;
            $assign_requests[$i]['state'] = $request->maintenanceRequest->asset->state->name;

            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->maintenanceRequest->created_at)) ;
            $assign_requests[$i]['emergency_request']=$request->maintenanceRequest->emergency_request;
            $assign_requests[$i]['due_date'] = $request->maintenanceRequest->created_at;
            foreach ($services as $service) {
                if (isset($service->requestedService->service->service_code)) {
                    $assign_requests[$i]['service_code'].='&diams; '.$service->requestedService->service->service_code . ', <br>';
                }

                if (isset($service->requestedService->service->title)) {
                    $assign_requests[$i]['service_name'].='&diams; '.$service->requestedService->service->title . ', <br>';
                }
            }
            $i++;
        }

        return view('pages.vendors.list_assign_requests')->with('assign_requests', $assign_requests);
    }


    public function listAssignedBids()
    {

        $user_id = Auth::user()->id;
        $requests = AssignRequestBid::where('vendor_id', '=', $user_id)->where('status', "=", 1)->groupBy('request_id')->orderBy('id', 'desc')->get();
        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = AssignRequestBid::where('vendor_id', '=', $user_id)->where('request_id', '=', $request->maintenanceRequest->id)->where('status', '!=', 2)->orderBy('id', 'desc')->get();
            $assign_requests[$i]['request_id'] = $request->maintenanceRequest->id;
            $assign_requests[$i]['status'] = $request->maintenanceRequest->status;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $customer_FirstName="";
            if (isset($request->maintenanceRequest->user->first_name)) {
                $customer_FirstName=$request->maintenanceRequest->user->first_name;
            }

             $customer_LastName="";
            if (isset($request->maintenanceRequest->user->last_name)) {
                $customer_LastName=$request->maintenanceRequest->user->last_name;
            }
            $assign_requests[$i]['customer_name'] = $customer_FirstName .' '. $customer_LastName;
            $assign_requests[$i]['asset_number'] = $request->maintenanceRequest->asset->asset_number;
            $assign_requests[$i]['property_address'] = $request->maintenanceRequest->asset->property_address;
            $assign_requests[$i]['zip'] = $request->maintenanceRequest->asset->zip;
            $assign_requests[$i]['city'] = $request->maintenanceRequest->asset->city->name;
            $assign_requests[$i]['state'] = $request->maintenanceRequest->asset->state->name;

            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->maintenanceRequest->created_at)) ;
            $assign_requests[$i]['emergency_request']=$request->maintenanceRequest->emergency_request;
            $assign_requests[$i]['due_date'] = $request->maintenanceRequest->created_at;
            foreach ($services as $service) {
                if (isset($service->requestedService->service->service_code)) {
                    $assign_requests[$i]['service_code'].='&diams; '.$service->requestedService->service->service_code . ', <br>';
                }

                if (isset($service->requestedService->service->title)) {
                    $assign_requests[$i]['service_name'].='&diams; '.$service->requestedService->service->title . ', <br>';
                }
            }
            $i++;
        }

        return view('pages.vendors.list_assign_bids')->with('assign_requests', $assign_requests);
    }

    /**
     * List All Orders.
     * @params none
     * @return List of Orders through AJAX.
     */
    public function listOrders()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('vendor_id', '=', $user_id)->orderBy('id', 'desc')->get();
        $list_orders = [];
        $i = 0;
        foreach ($orders as $order) {
            $order_details = ($order->orderDetail);
            $list_orders[$i]['order_id'] = $order->id;


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
            foreach ($order->maintenanceRequest->requestedService as $req_ser) {
                if (isset($req_ser->due_date)) {
                    $duedate= $req_ser->due_date;
                } else {
                    $duedate="";
                }
            }


            $list_orders[$i]['customer_name'] = $customerfirstname. ' ' . $customerlastname;
            $list_orders[$i]['vendor_name'] =  $vendorfirstname. ' ' .  $vendorlastname;
            if (isset($order->maintenanceRequest->asset->asset_number)) {
                $list_orders[$i]['asset_number'] = $order->maintenanceRequest->asset->asset_number;
            } else {
                $list_orders[$i]['asset_number'] = "";
            }

            // $list_orders[$i]['due_date'] = $duedate;
            $list_orders[$i]['due_date'] = '';
            $list_orders[$i]['service_name'] = '';
            if (isset($order->maintenanceRequest->asset->asset_number)) {
                $list_orders[$i]['property_address'] = $order->maintenanceRequest->asset->property_address;
            } else {
                $list_orders[$i]['property_address'] ="";
            }
            if (isset($order->maintenanceRequest->asset->city->name)) {
                    $list_orders[$i]['city'] = $order->maintenanceRequest->asset->city->name;
            } else {
                         $list_orders[$i]['city'] ="";
            }
            if (isset($order->maintenanceRequest->asset->state->name)) {
                          $list_orders[$i]['state'] = $order->maintenanceRequest->asset->state->name;
            } else {
                          $list_orders[$i]['state'] = "";
            }
            if (isset($order->maintenanceRequest->asset->zip)) {
                           $list_orders[$i]['zipcode'] = $order->maintenanceRequest->asset->zip;
            } else {
                           $list_orders[$i]['zipcode'] = "";
            }




            $list_orders[$i]['request_status'] = $order->maintenanceRequest->status;
            $list_orders[$i]['status'] = $order->status;
            $list_orders[$i]['status_class'] = ($order->status==1)? "warning": $order->status_class;
            ;
            $list_orders[$i]['status_text'] = ($order->status==1)? "In-Process":$order->status_text;
            ;
            foreach ($order_details as $order_detail) {
                if (isset($order_detail->requestedService->service->title)) {
                    $list_orders[$i]['service_name'].=$order_detail->requestedService->service->title . ', <br>';
                } else {
                    $list_orders[$i]['service_name'].= " ";
                }
                if (isset($order_detail->requestedService->due_date)) {
                    $list_orders[$i]['due_date'].=$order_detail->requestedService->due_date . ', <br>';
                } else {
                    $list_orders[$i]['due_date'].= " ";
                }
            }

            $i++;
        }
          return view('pages.vendors.list_orders')->with('orders', $list_orders);
    }



    /**
     * List All Completed Orders.
     * @params none
     *status=2
     * @return List of Completed Orders through AJAX.
     */
    public function listCompletedOrders()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('vendor_id', '=', $user_id)->where('status', '=', '2')->orderBy('id', 'desc')->get();
        $list_orders = [];
        $i = 0;
        foreach ($orders as $order) {
            $order_details = $order->orderDetail;
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
        return view('pages.vendors.list_completed_orders')->with('orders', $list_orders);
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

        $assign_requests = AssignRequest::where('request_id', '=', $maintenance_request_id)
        ->where('status', "!=", 2)
        ->where('vendor_id', "=", Auth::user()->id)
        ->get();

        foreach ($assign_requests as $adata) {
            $assigned_request_ids[] = $adata->requestedService->service->id;
        }


        return view('pages.vendors.viewvendormaintenancerequest')
        ->with([
            'request_maintenance' => $request_maintenance,
            'assign_requests'=>$assign_requests
            ]);
    }


    public function viewBidRequest($maintenance_request_id = "")
    {


        $request_maintenance = MaintenanceBid::find($maintenance_request_id);

        $assign_requests = AssignRequestBid::where('request_id', '=', $maintenance_request_id)
        ->where('status', "!=", 2)
        ->where('vendor_id', "=", Auth::user()->id)
        ->get();

        foreach ($assign_requests as $adata) {
            $assigned_request_ids[] = $adata->requestedService->service->id;
        }


        return view('pages.vendors.viewvendorbiddingrequest')
        ->with([
        'request_maintenance' => $request_maintenance,
        'assign_requests'=>$assign_requests
        ]);
    }


     /*
     * Function Name : viewMaintenanceRequest
     * @param:id
     * @description: This function is begin used for  viewing  all over the  details requests of maintenance in admin
     *
     */

    public function viewOSR($maintenance_request_id = "")
    {


        $request_maintenance = BidRequest::find($maintenance_request_id);

        $assign_requests = BidRequestedService::where('request_id', '=', $maintenance_request_id)

        ->get();



        return view('pages.vendors.viewosr')
        ->with([
        'request_maintenance' => $request_maintenance,
        'assign_requests'=>$assign_requests
        ]);
    }


    public function declineRequest()
    {
        $data = Request::all();
        $delete_request = AssignRequest::deleteRequest($data['request_id'], $data['vendor_id']);


        //BUt is will be for all requests not particular
        if ($data['declined_notes']!="") {
            $declined_notesdata = [
            'decline_notes'       => $data['declined_notes']
            ];

            $save = MaintenanceRequest::where('id', '=', $data['request_id'])
            ->update($declined_notesdata);
        }
        $notification_url="list-maintenance-request";

                // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
        $recepient_id = User::getAdminUsersId();
        foreach ($recepient_id as $rec_id) {
                    //admin to admin notification
            $notification = NotificationController::doNotification($rec_id, $rec_id, "Request ".$data['request_id']." has been declined by vendor", 1, [], $notification_url);
        }


        if ($delete_request) {
            return " Request has been declined. You will not be re-assigned this request.";
        }
    }

    public function acceptRequest()
    {
        $input = Request::all();
        $accept_request = AssignRequest::acceptRequest($input['request_id'], $input['vendor_id']);


        if ($accept_request) {
            //Creating the work order
            $data['status'] = 1;
            $data['request_id'] = $input['request_id'];
            $data['vendor_id'] = $input['vendor_id'];
            $data['customer_id'] = MaintenanceRequest::find($input['request_id'])->asset->customer_id;
            $order_id = Order::addOrder($data);

            $order_details = [];
            //Getting  services  ids
            $assigned_requests = AssignRequest::where('request_id', '=', $data['request_id'])
            ->where('vendor_id', '=', $data['vendor_id'])
            ->where('status', '!=', 2)
            ->get();

            foreach ($assigned_requests as $request) {
                $order_details['requested_service_id'] = $request->requested_service_id;
                $order_details['order_id'] = $order_id;
                $order_details['status'] = 1;
                OrderDetail::addOrderDetails($order_details);
            }


            return " Thank you for accepting the request! You now have a work order marked as “In-Process”. Please upload photos and complete the order once work is performed.";
        }
    }
    public function acceptSingleRequest()
    {
        $input = Request::all();
        $accept_request = AssignRequest::acceptSingleRequest($input['request_id'], $input['vendor_id'], $input['service_id']);
        if ($accept_request) {
            //Creating the work order
            $data['status'] = 1;
            $data['request_id'] = $input['request_id'];
            $data['vendor_id'] = $input['vendor_id'];
            $data['customer_id'] = MaintenanceRequest::find($input['request_id'])->asset->customer_id;
            $order_id = Order::addOrder($data);

            $order_details = [];
            $order_details['requested_service_id'] = $input['service_id'];
            $order_details['order_id'] = $order_id;
            $order_details['status'] = 1;
            OrderDetail::addOrderDetails($order_details);


               //Declined other required
            AssignRequest::deleteSingleRequest($data['request_id'], $data['vendor_id'], $input['service_id']);


            return " Thank you for accepting the request! You now have a work order marked as “In-Process”. Please upload photos and complete the order once work is performed.";
        }
    }
        /*
        Add bid for a particular workorder
        */
    public function addBidRequest($order_id = 0)
    {

      // Get all customer assets to send in view

        $services = Service::getAllServices(1); // get all services provided by admin
        $jobType=JobType::get();

        //get vendor id
        $vendor_id = Auth::user()->id;
      // All in progress work orders
        $orders = Order::where('vendor_id', '=', $vendor_id)
        ->where('status', '=', '1')
        ->get();

        $order_ids=[];
        foreach ($orders as $order) :
            $order_ids[$order->id."--".$order->MaintenanceRequest->Asset->id]=  $order->id."-".$order->MaintenanceRequest->Asset->property_address;
            if ($order_id==$order->id) {
                  $order_id=$order_id."--".$order->MaintenanceRequest->Asset->id;
            }
        endforeach;

        return view('pages.vendors.add-bid')
        ->with('order_ids', $order_ids)
        ->with('services', $services)
        ->with('jobType', $jobType)
        ->with('order_id', $order_id) ;
    }

                /**
     * Create Bid request
     * @params none
     * @return Redirect to maintenance request page
     */
    public function createBidServiceRequest()
    {

        $data = Request::all();
        //$customer_id = Auth::user()->id;
        //for customer id

        $exploded_orderid_asset_id=explode("--", $data['work_order']);
        $orderData= Order::find($exploded_orderid_asset_id[0]);
        $request['vendor_id'] = Auth::user()->id; // assign current logged id to request
        $request['asset_id'] = $exploded_orderid_asset_id[1]; // assign asset number to request
        $request['order_id'] = $exploded_orderid_asset_id[0];
        $request['customer_id'] = $orderData->customer->id;
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


                if (isset($data['biding_prince_' . $service_id])) {
                    $request_detail['biding_prince'] = $data['biding_prince_' . $service_id];
                } else {
                    unset($request_detail['biding_prince']);
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

    /**
     * List All Assigned requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listBidRequests($status = 1)
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('vendor_id', '=', $user_id)->where('status', "=", $status)->orderBy('id', 'desc')->get();


        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)->where('status', '=', 1)->orderBy('id', 'desc')->get();

            $assign_requests[$i]['request_id'] = $request->id;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['price'] = '';
            $assign_requests[$i]['customer_name'] = $request->customer->first_name .' '. $request->customer->last_name;
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['propery_address'] = $request->asset->property_address;
            $assign_requests[$i]['zip'] = $request->asset->zip;

            $assign_requests[$i]['city'] = $request->asset->city->name;
            $assign_requests[$i]['state'] = $request->asset->state->name;






            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->created_at));
            $assign_requests[$i]['due_date'] = $request->created_at;
            $assign_requests[$i]['property_address'] = $request->asset->property_address;
            $request_id_array=[];
            foreach ($services as $service) {
                $assign_requests[$i]['price'].=$service->biding_prince."<br/>";

                $request_id_array[]=$service->service->id;

                if (isset($service->service->service_code)) {
                    $assign_requests[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                }
                if (isset($service->service->title)) {
                    $assign_requests[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
                }
            }




            $i++;
        }

        return view('pages.vendors.list_bid_requests')
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

        $requests = BidRequest::where('vendor_id', '=', $user_id)->where('status', "=", 2)->orderBy('id', 'desc')->get();


        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)->where('status', '=', 1)->orderBy('id', 'desc')->get();
            $assign_requests[$i]['request_id'] = $request->id;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['customer_name'] = $request->customer->first_name .' '. $request->customer->last_name;
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['request_date'] = $request->created_at;
            $assign_requests[$i]['due_date'] = $request->created_at;
            foreach ($services as $service) {
                if (isset($service->service->service_code)) {
                    $assign_requests[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                }

                if (isset($service->service->title)) {
                    $assign_requests[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
                }
            }
            $i++;
        }

        return view('pages.vendors.list_bid_requests')->with('assign_requests', $assign_requests);
    }


    /**
     * List All Assigned requests by admin.
     * @params none
     * @return List of assigned requests through AJAX.
     */
    public function listDeclinedBidRequests()
    {

        $user_id = Auth::user()->id;

        $requests = BidRequest::where('vendor_id', '=', $user_id)->where('status', "=", 3)->orderBy('id', 'desc')->get();


        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $services = BidRequestedService::where('request_id', '=', $request->id)->where('status', '=', 1)->orderBy('id', 'desc')->get();
            $assign_requests[$i]['request_id'] = $request->id;
            $assign_requests[$i]['service_code'] = '';
            $assign_requests[$i]['service_name'] = '';
            $assign_requests[$i]['customer_name'] = $request->customer->first_name .' '. $request->customer->last_name;
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['request_date'] = $request->created_at;
            $assign_requests[$i]['due_date'] = $request->created_at;
            foreach ($services as $service) {
                if (isset($service->service->service_code)) {
                    $assign_requests[$i]['service_code'].='&diams; '.$service->service->service_code . ', <br>';
                }
                if (isset($service->service->title)) {
                    $assign_requests[$i]['service_name'].='&diams; '.$service->service->title . ', <br>';
                }
            }
            $i++;
        }

        return view('pages.vendors.list_bid_requests')->with('assign_requests', $assign_requests);
    }

    function changeVendorPrice()
    {
         $data = Request::all();


         $declined_notesdata = [
            'biding_prince'       => $data['vendorPrice'],

            ];

              $save = BidRequestedService::where('id', '=', $data['assignid'])
              ->update($declined_notesdata);





              $BidRequestedService=BidRequestedService::find($data['assignid']);


              $StatusDATA = [
            'status'       => 1,

              ];

              $save = BidRequest::where('id', '=', $BidRequestedService->request_id)
              ->update($StatusDATA);



                  // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
                $recepient_id = User::getAdminUsersId();
              foreach ($recepient_id as $rec_id) {
                  $BidRequestEmailDATA=  BidRequest::find($BidRequestedService->request_id);
                  $emailbody='OSR '.$BidRequestedService->request_id.' has been modified after decline ';
                  $emailbody.= '<br/>';
                  $emailbody.= 'ID:'.$BidRequestedService->request_id;
                  $emailbody.= '<br/>';
                  $emailbody.= 'Property Address'.$BidRequestEmailDATA->asset->property_address;
                  $emailbody.= '<br/>';
                  $emailbody.= 'City:'.$BidRequestEmailDATA->asset->city->name;
                  $emailbody.= '<br/>';
                  $emailbody.= 'State:'.$BidRequestEmailDATA->asset->state->name;
                  ;
                  $emailbody.= '<br/>';


                  $url="admin-bid-requests/".$BidRequestedService->request_id;
                  $emailbody.='To view the OSR <a href="http://'.URL::to($url).'">please click here</a>!.';



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
                  $notification = NotificationController::doNotification($rec_id, $rec_id, 'OSR '.$BidRequestedService->request_id .' has been modified after decline', 1, $email_data, $notification_url);
                  Email::send($userDAta->email, ': OSR Notification', 'emails.customer_registered', $email_data);
                }
    }

//Add images for bid when vendor viewing bids
    public function addBeforeImages()
    {

        $destinationPath = config('app.bid_images_before');   //2
        if (!empty($_FILES)) {
            $data=Request::all();
            $request_id=$data['requested_id'];
            $type=$data['image_type'];

            $tempFile = $_FILES['file']['tmp_name'];          //3
            $targetPath = $destinationPath;  //4
            $originalFile=$_FILES['file']['name'];
            $changedFileName=$request_id.'-'.$originalFile;
            $targetFile = $targetPath . $changedFileName;  //5
            $moved=move_uploaded_file($tempFile, $targetFile); //6
            if ($moved) {
                $data['image_name']=$changedFileName;
                unset($data['file']);
                unset($data['_token']);

                setcookie('request_id', $request_id);
                setcookie('type', $type);
                $save=AssignRequestBidsImage::create($data);
                if ($save) {
                    $image='<img id="'.$request_id.'-'.$save->image_name.'" src="'.config('app.url').'/'.config('app.bid_images_before').$save->image_name.'" width="80px" height="80px" style="padding: 10px" class="img-thumbnail" alt="">';
                    echo $image;
                }
            }
        }
    }


    public function displayImages()
    {
        $data = Request::all();
        $order_id = $data['id'];

        $type = $data['type'];
        $popDiv='';
        $app_path="";
        if ($type=="after") {
                $app_path="bid_images_after";
        } elseif ($type=="before") {
                $app_path="bid_images_before";
        } elseif ($type=="during") {
                   $app_path="bid_images_during";
        }

        $images=AssignRequestBidsImage::where('requested_id', '=', $order_id)->get();


        $tag_counter = 1;
        $output="";


        foreach ($images as $image) {
             $filecheck=  '/home/gssreo/public_html/'.config('app.'.$app_path).$image->address;
            if (file_exists($filecheck)) {
                $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" data-image_id="'.$image->id.'" class="example6"> <img  src="'.config('app.url').'/'.config('app.'.$app_path).$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';
            } else {
                $popDiv.= '<div  class="imageFrame"> <a  href="'.config('app.url')."/".$image->address.'" data-image_id="'.$image->id.'" class="example6"> <img  src="'.config('app.url')."/".$image->address.'" width="120px" height="120px" class="img-thumbnail" alt="'.$image->address.'"></a><a  href="#" class="deletImg" data-value="'.$image->address.'" onclick="removeImage('.$order_id.','.$order_details_id.',this,\''.$type.'\');" >X</a></div>';
            }
            $OrderImagesPosition     =OrderImagesPosition::where('order_image_id', '=', $image->id)->get();
            $OrderImagesPositionCount=OrderImagesPosition::where('order_image_id', '=', $image->id)->count();


            $tag_counter = 1;

      //Build output

            foreach ($OrderImagesPosition as $tag) {
                if ($tag_counter ==1) {
                    $output .= '<style type="text/css">';
                    $output .=  '.map'.$tag->order_image_id.' { display:none;}';
                }


                    $output .=  '.map'.$tag->order_image_id.' .map .tag_'.$tag_counter.$tag->order_image_id.' { ';
                    // $output .= 'border:1px solid #000;';
                    $output .= 'background:url("'.URL::to('/').'/assets/images/tag_hotspot_62x62.png") no-repeat;';
                    $output .= 'top:'.$tag['y1'].'px;';
                    $output .= 'left:'.$tag['x1'].'px;';
                    $output .= 'width:'.$tag['w'].'px;';
                    $output .= 'height:'.$tag['h'].'px;';

                    $output .= '}';


                   $tag_counter++;
            }
            if ($tag_counter !=1) {
                $output .= '</style>';
            }

            $tag_counter = 1;

            if ($OrderImagesPositionCount>0) {
                foreach ($OrderImagesPosition as $tag) {
                    if ($tag_counter ==1) {
                            $output.= '<div class="map'.$tag->order_image_id.'"><ul class="map">';
                    }
                    $output.=  '<li class="tag_'.$tag_counter.$tag->order_image_id.'" id="uniq'.$tag->id.'"><a  href="javascript:;"><span class="titleDs">'.$tag['comment'].' </span></a><a href="javascript:;" class="removeBtn" onclick="deletePhotoTag('.$tag->id.')">X</a></li>';


                    $tag_counter++;
                }
            } else {
                   $output.= '<div class="mapunique"><ul class="map">';
                $output.= "</ul></div>";
            }


            if ($tag_counter !=1) {
                $output.= "</ul></div>";
            }
        }


        $popDiv.= '<script type="text/javascript">
            $(".example6").fancybox({
                    onStart: function(element){
                        var jquery_element=$(element);
                       
                        $("#order_image_id").val(jquery_element.data("image_id"));
                         setTimeout(function(){
                    
                    var output=\''.$output.'\';
                    
                    $("#fancybox-content").append(output); 
                $(".map"+jquery_element.data("image_id")).show();
            }, 1000);
                    
                 
                    },
                "titlePosition"    : "outside",
                "overlayColor"      : "#000",
                "overlayOpacity"    : "0.9"
                
            }); 
        </script>';
        return $popDiv;
    }
}
