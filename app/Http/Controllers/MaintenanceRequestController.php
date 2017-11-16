<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssignRequest;
use App\AssignRequestBid;
use App\AssignRequestBidsImage;
use App\BidRequest;
use App\EmergencyRequest;
use App\EmergencyRequestDetail;
use App\Helpers\Email;
use App\Helpers\FlashMessage;
use App\Helpers\General;
use App\JobType;
use App\MaintenanceBid;
use App\MaintenanceRequest;
use App\Order;
use App\OrderDetail;
use App\OrderImage;
use App\Recurring;
use App\Remainder;
use App\RequestedBid;
use App\RequestedService;
use App\Service;
use App\ServiceImage;
use App\ServiceImageBid;
use App\User;
use \Maintenance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class MaintenanceRequestController extends Controller
{

    /**
     * Redirect to customer maintenace request page
     * @params none
     * @return Redirect to customer maintenace request page
     */
    public function viewRequestForm()
    {
        // Get all customer assets to send in view
        $customer_assets = Asset::getAssetsByCustomerId(Auth::user()->id);

          $jobType=JobType::get();

            $services = Service::getAllServices(); // get all services provided by admin
        $dataService=[];
        foreach ($services as $value) {
            $serviceDataByCategory= Service::getAllServicesBySeviceCategoryId($value->service_cat_id);

            foreach ($serviceDataByCategory as $serviceDat) {
                if (isset($value->serviceCategory->title)) {
                    $dataService[$value->serviceCategory->title][$serviceDat->id]=$serviceDat->title;
                }
            }
        }
        return view('pages.customer.request_service')// return to page
                        ->with('customer_assets', $customer_assets)
                        ->with('services', $dataService)
                        ->with('jobType', $jobType);
    }

    public function viewAdminRequestForm($id)
    {
        // Get all  assets to send in view
        $asset_information = Asset::where('property_status', '<>', 'closed')->get();

        $jobType=JobType::get();
        $services = Service::getAllServices(); // get all services provided by admin
        $dataService=[];
        foreach ($services as $value) {
            $serviceDataByCategory= Service::getAllServicesBySeviceCategoryId($value->service_cat_id, $value->job_type_id);

            foreach ($serviceDataByCategory as $serviceDat) {
                if (isset($value->serviceCategory->title)) {
                    $dataService[$value->serviceCategory->title][$serviceDat->id]=$serviceDat->title;
                }
            }
        }

        $dataService['Not Found Service']['otherflag']="Other";
        return view('pages.customer.request_service')// return to page
                      ->with('customer_assets', $asset_information)
                      ->with('services', $dataService)
                      ->with('property_id', $id)
                      ->with('jobType', $jobType);
    }

    public function editAdminRequestForm($id)
    {
        // Get all  assets to send in view
        $asset_information = Asset::all();

        $RequestedService =  MaintenanceRequest::find($id);
        $services = Service::getAllServices(); // get all services provided by admin

        return view('pages.customer.edit_request_service')   // return to page
                      ->with('customer_assets', $asset_information)
                      ->with('services', $services)
                      ->with('RequestedService', $RequestedService);
    }

    /**
     * show maintenace request listing
     * @params none
     * @return Redirect maintenace request listing
     */
    public function listServiceRequest()
    {

        // Get all maintenance request of current customer logged in
        $requests = MaintenanceRequest::listMaintenanceRequestByCustomerId(Auth::user()->id);
        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $assign_requests[$i]['request_id'] = $request->id; // get reqested id from current table
            // assign first name and last name by relation which is create in model
            $assign_requests[$i]['customer_name'] = $request->user->first_name . ' ' . $request->user->last_name;
            //assign assent number for asset table
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['property_address'] = $request->asset->property_address;
             $assign_requests[$i]['ClientType'] = $request->asset->customerType->title;
            $assign_requests[$i]['city'] = $request->asset->city->name;
            $assign_requests[$i]['state'] = $request->asset->state->name;
            $assign_requests[$i]['status'] = $request->status;
            $assign_requests[$i]['zip'] = $request->asset->zip;
            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->created_at));


             $assign_requests[$i]['serviceType'] =  '';



            foreach ($request->requestedService as $value) {
                if (isset($value->service->title)) {
                     $assign_requests[$i]['serviceType'] .=  $value->service->title  ;
                     $assign_requests[$i]['serviceType'] .=   ', <br>';
                }
        //     if(isset($value->due_date))
        //     {
        //                  $assign_requests[$i]['serviceType'] .= "<br>".    $value->due_date . ', <br>';
        //     }
        //     else
        //     {
        // $assign_requests[$i]['serviceType'] .=   ', <br>';


        //     }
            }
             $i++;
        }

        return view('pages.customer.list_customer_requested_services')
                        ->with('assign_requests', $assign_requests);
    }





       /**
     * show maintenace request listing
     * @params none
     * @return Redirect maintenace request listing
     */
    public function listServiceRequestBid()
    {

        // Get all maintenance request of current customer logged in
        $requests = MaintenanceBid::listMaintenanceRequestByCustomerId(Auth::user()->id);

        $assign_requests = [];
        $i = 0;
        foreach ($requests as $request) {
            $assign_requests[$i]['request_id'] = $request->id; // get reqested id from current table
            // assign first name and last name by relation which is create in model
            $assign_requests[$i]['customer_name'] = $request->user->first_name . ' ' . $request->user->last_name;
            //assign assent number for asset table
            $assign_requests[$i]['asset_number'] = $request->asset->asset_number;
            $assign_requests[$i]['property_address'] = $request->asset->property_address;
               $assign_requests[$i]['ClientType'] = $request->asset->customerType->title;
            $assign_requests[$i]['city'] = $request->asset->city->name;
            $assign_requests[$i]['state'] = $request->asset->state->name;
            $assign_requests[$i]['status'] = $request->status;
            $assign_requests[$i]['zip'] = $request->asset->zip;
            $assign_requests[$i]['request_date'] = date('m/d/Y h:i:s A', strtotime($request->created_at));


             $assign_requests[$i]['serviceType'] =  '';



            foreach ($request->requestedService as $value) {
                if (isset($value->service->title)) {
                     $assign_requests[$i]['serviceType'] .=  $value->service->title  ;
                     $assign_requests[$i]['serviceType'] .=   ', <br>';
                }
        //     if(isset($value->due_date))
        //     {
        //                  $assign_requests[$i]['serviceType'] .= "<br>".    $value->due_date . ', <br>';
        //     }
        //     else
        //     {
        // $assign_requests[$i]['serviceType'] .=   ', <br>';


        //     }
            }
             $i++;
        }

        return view('pages.customer.list_customer_requested_bids')
                        ->with('assign_requests', $assign_requests);
    }

    /**
     * show maintenace request listing
     * @params none
     * @return Redirect maintenace request listing
     */
    public function viewServiceRequest($id)
    {


        // Get all maintenance request of current customer logged in
        $request_detail = MaintenanceRequest::viewDetailByRequestId($id);

        return view('pages.customer.view_customer_requested_service')
                        ->with('request_detail', $request_detail);
    }

      /**
     * show maintenace request listing
     * @params none
     * @return Redirect maintenace request listing
     */
    public function viewServiceBid($id)
    {


        // Get all maintenance request of current customer logged in
        $request_detail = MaintenanceBid::viewDetailByRequestId($id);

        return view('pages.customer.view_customer_requested_bid')
                        ->with('request_detail', $request_detail);
    }
     /**
     * Create Additional maintenace request
     * @params none
     * @return Redirect to maintenance request page
     */
    public function createAdditionalServiceRequest()
    {

        $data = Request::all(); // get all submitted data of user

        $request['substitutor_id'] = Auth::user()->id;
        $request['customer_id'] = Asset::find($data['asset_number'])->customer_id; // assign current logged id to request
        $request['asset_id'] = $data['asset_number']; // assign asset number to request
        $request['status'] = 1; // while generating request status would be active
        $request['emergency_request'] =0;
        $request['job_type'] =$data['job_type'];
        $requested_selected_services=[]; // array for getting the ids of requested servcies it will be used for auto assigning the emergency request

        // Add maintainence request to main table
        $add_request = MaintenanceRequest::addAdditionalMaintenanceRequest($request);
        if ($add_request) {
            $request_id = $add_request;
            //Select all selected service to
            $selected_services = $data['service_ids_selected'];

            /// loop through all selected services
            foreach ($selected_services as $service_id) {
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

                if (isset($data['quantity_' . $service_id])) {
                    $request_detail['quantity'] = $data['quantity_' . $service_id];
                } else {
                    unset($request_detail['quantity']);
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

                if (isset($data['due_date_' . $service_id])) {
                    $request_detail['due_date'] = $data['due_date_' . $service_id];
                } else {
                    unset($request_detail['due_date']);
                }


                if (isset($data['set_prinkler_system_type_' . $service_id])) {
                    $request_detail['set_prinkler_system_type'] = $data['set_prinkler_system_type_' . $service_id];
                } else {
                    unset($request_detail['set_prinkler_system_type']);
                }

                if (isset($data['install_temporary_system_type_' . $service_id])) {
                    $request_detail['install_temporary_system_type'] = $data['install_temporary_system_type_' . $service_id];
                } else {
                    unset($request_detail['install_temporary_system_type']);
                }


                if (isset($data['carpet_service_type_' . $service_id])) {
                    $request_detail['carpet_service_type'] = $data['carpet_service_type_' . $service_id];
                } else {
                    unset($request_detail['carpet_service_type']);
                }


                if (isset($data['pool_service_type_' . $service_id])) {
                    $request_detail['pool_service_type'] = $data['pool_service_type_' . $service_id];
                } else {
                    unset($request_detail['pool_service_type']);
                }

                if (isset($data['boarding_type_' . $service_id])) {
                    $request_detail['boarding_type'] = $data['boarding_type_' . $service_id];
                } else {
                    unset($request_detail['boarding_type']);
                }

                if (isset($data['spruce_up_type_' . $service_id])) {
                    $request_detail['spruce_up_type'] = $data['spruce_up_type_' . $service_id];
                } else {
                    unset($request_detail['spruce_up_type']);
                }

                if (isset($data['constable_information_type_' . $service_id])) {
                    $request_detail['constable_information_type'] = $data['constable_information_type_' . $service_id];
                } else {
                    unset($request_detail['constable_information_type']);
                }

                if (isset($data['remove_carpe_type_' . $service_id])) {
                    $request_detail['remove_carpe_type'] = $data['remove_carpe_type_' . $service_id];
                } else {
                    unset($request_detail['remove_carpe_type']);
                }

                if (isset($data['remove_blinds_type_' . $service_id])) {
                    $request_detail['remove_blinds_type'] = $data['remove_blinds_type_' . $service_id];
                } else {
                    unset($request_detail['remove_blinds_type']);
                }

                if (isset($data['remove_appliances_type_' . $service_id])) {
                    $request_detail['remove_appliances_type'] = $data['remove_appliances_type_' . $service_id];
                } else {
                    unset($request_detail['remove_appliances_type']);
                }

                if (isset($data['lot_size_' . $service_id])) {
                    $request_detail['lot_size'] = $data['lot_size_' . $service_id];
                } else {
                    unset($request_detail['lot_size']);
                }

                if (isset($data['emergency_' . $service_id])) {
                    $request_detail['emergency'] = $data['emergency_' . $service_id];
                } else {
                    unset($request_detail['emergency']);
                }

                //Recurring request information
                $recurringData=[];
                if (isset($data['recurring_' . $service_id])) {
                    $request_detail['recurring'] = $data['recurring_' . $service_id];

                    if (isset($data['end_date_' . $service_id])) {
                        $request_detail['recurring_end_date'] = $data['end_date_' . $service_id];
                        $recurringData['end_date']=date('Y-m-d', strtotime($data['end_date_' . $service_id]));
                    } else {
                        unset($request_detail['recurring_end_date']);
                    }

                    if (isset($data['duration_' . $service_id])) {
                          $request_detail['duration'] = $data['duration_' . $service_id];
                          $recurringData['duration']=$data['duration_' . $service_id];
                    } else {
                        unset($request_detail['duration']);
                    }

                    if (isset($data['start_date_' . $service_id])) {
                          $request_detail['recurring_start_date'] = $data['start_date_' . $service_id];
                          $recurringData['start_date']=date('Y-m-d', strtotime($data['start_date_' . $service_id]));
                    } else {
                        unset($request_detail['recurring_start_date']);
                    }
                } else {
                    unset($request_detail['recurring']);
                    unset($request_detail['recurring_start_date']);
                    unset($request_detail['recurring_end_date']);
                    unset($request_detail['duration']);
                }

                //End recurring Request

                $add_requested_service = RequestedService::addAdditionalRequestedService($request_detail);

                //$request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service

                //Inserting recurring data into recurring table
                if (isset($data['recurring_' . $service_id])) {
                     $recurringData['request_service_id']=     $add_requested_service;
                     $recurringData['status']=1;
                     $recurringData['assignment_type']='single';
                     Recurring::create($recurringData);
                }
                //End inserting
                $requested_selected_services[]=$add_requested_service;
                //check if service is created then insert images of service
                if ($add_requested_service) {
                    if (isset($data['service_image_list_' . $service_id])) {
                        $service_images = $data['service_image_list_' . $service_id];
                        foreach ($service_images as $image) {
                            $image_detail['requested_id'] = $add_requested_service;
                            $image_detail['image_name'] = $image;
                            $image_detail['image_type'] = 'request';
                            $image_detail['status'] = 1;
                            $add_image = ServiceImage::addServiceImage($image_detail);
                        }
                    }
                }
            }
             $service_data['request_id'] = $add_request;
            $service_data['requested_service_id'] = $add_requested_service;
            $service_data['vendor_id'] = $data["vendorId"];
            $service_data['status'] = 1;

            AssignRequest::addRequest($service_data);

            $requested_service_recurring = RequestedService::find($service_id);


            if (isset($requested_service_recurring->recurring) && $requested_service_recurring->recurring==1) {
                Recurring::where('request_service_id', '=', $requested_service_recurring->id)
                                ->update(['vendor_id'=>$data["vendorId"]]);
            }
            $accept_request = AssignRequest::acceptRequest($add_request, $data["vendorId"]);

            if ($accept_request) {
              //Getting  services  ids
                $assigned_requests = AssignRequest::where('request_id', '=', $add_request)
                ->where('vendor_id', '=', $data["vendorId"])
                ->where('status', '!=', 2)
                ->get();
                $order_details = [];
                foreach ($assigned_requests as $request) {
                     //Creating the work order
                    $data['status'] = 0;
                    $data['status_text'] = "New Work Order";
                    $data['status_class'] = "green";

                    $data['request_id'] = $add_request;
                    $data['vendor_id'] = $data["vendorId"];
                    $data['customer_id'] = MaintenanceRequest::find($add_request)->asset->customer_id;
                    $order_id = Order::addOrder($data);

                    $workOrderId = $order_id;




                    $order_details['requested_service_id'] = $request->requested_service_id;
                    $order_details['order_id'] = $order_id;
                    $order_details['status'] = 1;
                    OrderDetail::addOrderDetails($order_details);



                    $OrderDetailID = DB::getPdo()->lastInsertId();


                    $image_detail=[];
                    $destinationPath = config('app.order_images_before');   //2
                    $upload_path = config('app.upload_path')."request";

                    $imageDataArray=ServiceImage::where('requested_id', '=', $request->requested_service_id)->get();

                    foreach ($imageDataArray as $imageData) {
                        //Copy Images for order
                        $order_details_id=$OrderDetailID;
                        $type='before';
                        $tempFile = $upload_path."/".$imageData->image_name;          //3
                        $targetPath = $destinationPath;  //4
                        $originalFile=$imageData->image_name;
                        $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
                        $targetFile = $targetPath . $changedFileName;  //5

                        copy($tempFile, $targetFile);

                        //End coping images

                        $image_detail['order_id'] = $order_id;
                        $image_detail['order_details_id'] = $order_details_id;

                        $image_detail['type'] = 'before';
                        $image_detail['address'] =  $changedFileName;
                        $image_detail['status'] = 1;
                        $add_image = OrderImage::create($image_detail);
                    }




                    $emailUrl="vendor-list-orders?url=".$order_id;
                    $userDAta=User::find($data["vendor_id"]);
                    $email_data = [
                    'first_name' => $userDAta->first_name,
                    'last_name' => $userDAta->last_name,
                    'username' => $userDAta->username,
                    'email' => $userDAta->email,
                    'id' =>  $data["vendor_id"],
                    'user_email_template'=>$order_id ."  has been assigned to you! To view work order, <a href='http://".URL::to($emailUrl)."'>please click here</a>!"
                    ];

                    $customervendor="Vendor";
                    $notification_url="vendor-list-orders";

                   //Vendor to admin notification
                    $notification = NotificationController::doNotification($data["vendor_id"], $data["vendor_id"], "New Work Order ".$order_id ." has been assigned to you!", 1, $email_data, $notification_url);
                    Email::send($userDAta->email, 'GSS Work Order Notification', 'emails.customer_registered', $email_data);
                }

                return redirect('admin');
            }
        }
    }


    /**
     * Create maintenace request
     * @params none
     * @return Redirect to maintenance request page
     */
    public function createServiceRequest()
    {
        $data = Request::all(); // get all submitted data of user
        $customer_id_for_request = "";
        if (Auth::user()->id==1) {
             $customer_id_for_request=Asset::find($data['asset_number'])->customer_id;
        } else {
             $customer_id_for_request = Auth::user()->id;
        }
         $MaintenanceRequestClass='MaintenanceRequest';
          $RequestedServiceClass='RequestedService';
          $ServiceImageClass='ServiceImage';
        if (isset($data['bid_flag']) && $data['bid_flag']==1) {
            $MaintenanceRequestClass='MaintenanceBid';
            $RequestedServiceClass='RequestedBid';
            $ServiceImageClass='ServiceImageBid';
        }
        $request['substitutor_id'] = Auth::user()->id;
        $request['customer_id'] = Asset::find($data['asset_number'])->customer_id; // assign current logged id to request
        $request['asset_id'] = $data['asset_number']; // assign asset number to request
        $request['status'] = 1; // while generating request status would be active

        if (isset($data["emergency_request"]))
        {
            $request['emergency_request'] =$data['emergency_request'];
        }
        else
        {
            $request['emergency_request'] = "";
        }
        $request['job_type'] =$data['job_type'];
        $requested_selected_services=[]; // array for getting the ids of requested servcies it will be used for auto assigning the emergency request

        // Add maintainence request to main table
        $add_request = MaintenanceRequest::addMaintenanceRequest($request);

        // get last id to assign to each service request
        $request_id = DB::getPdo()->lastInsertId();


        //check if request in created then insert services to service table
        if ($add_request) {
            //Select all selected service to
            $selected_services = $data['service_ids_selected'];

            /// loop through all selected services
            foreach ($selected_services as $service_id) {
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

                if (isset($data['quantity_' . $service_id])) {
                    $request_detail['quantity'] = $data['quantity_' . $service_id];
                } else {
                    unset($request_detail['quantity']);
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

                if (isset($data['due_date_' . $service_id])) {
                    $request_detail['due_date'] = $data['due_date_' . $service_id];
                } else {
                    unset($request_detail['due_date']);
                }


                if (isset($data['set_prinkler_system_type_' . $service_id])) {
                    $request_detail['set_prinkler_system_type'] = $data['set_prinkler_system_type_' . $service_id];
                } else {
                    unset($request_detail['set_prinkler_system_type']);
                }

                if (isset($data['install_temporary_system_type_' . $service_id])) {
                    $request_detail['install_temporary_system_type'] = $data['install_temporary_system_type_' . $service_id];
                } else {
                    unset($request_detail['install_temporary_system_type']);
                }


                if (isset($data['carpet_service_type_' . $service_id])) {
                    $request_detail['carpet_service_type'] = $data['carpet_service_type_' . $service_id];
                } else {
                    unset($request_detail['carpet_service_type']);
                }


                if (isset($data['pool_service_type_' . $service_id])) {
                    $request_detail['pool_service_type'] = $data['pool_service_type_' . $service_id];
                } else {
                    unset($request_detail['pool_service_type']);
                }

                if (isset($data['boarding_type_' . $service_id])) {
                    $request_detail['boarding_type'] = $data['boarding_type_' . $service_id];
                } else {
                    unset($request_detail['boarding_type']);
                }

                if (isset($data['spruce_up_type_' . $service_id])) {
                    $request_detail['spruce_up_type'] = $data['spruce_up_type_' . $service_id];
                } else {
                    unset($request_detail['spruce_up_type']);
                }

                if (isset($data['constable_information_type_' . $service_id])) {
                    $request_detail['constable_information_type'] = $data['constable_information_type_' . $service_id];
                } else {
                    unset($request_detail['constable_information_type']);
                }

                if (isset($data['remove_carpe_type_' . $service_id])) {
                    $request_detail['remove_carpe_type'] = $data['remove_carpe_type_' . $service_id];
                } else {
                    unset($request_detail['remove_carpe_type']);
                }

                if (isset($data['remove_blinds_type_' . $service_id])) {
                    $request_detail['remove_blinds_type'] = $data['remove_blinds_type_' . $service_id];
                } else {
                    unset($request_detail['remove_blinds_type']);
                }

                if (isset($data['remove_appliances_type_' . $service_id])) {
                    $request_detail['remove_appliances_type'] = $data['remove_appliances_type_' . $service_id];
                } else {
                    unset($request_detail['remove_appliances_type']);
                }






                if (isset($data['lot_size_' . $service_id])) {
                    $request_detail['lot_size'] = $data['lot_size_' . $service_id];
                } else {
                    unset($request_detail['lot_size']);
                }

                if (isset($data['emergency_' . $service_id])) {
                    $request_detail['emergency'] = $data['emergency_' . $service_id];
                } else {
                    unset($request_detail['emergency']);
                }

                //Recurring request information
                $recurringData=[];
                if (isset($data['recurring_' . $service_id])) {
                    $request_detail['recurring'] = $data['recurring_' . $service_id];

                    if (isset($data['end_date_' . $service_id])) {
                        $request_detail['recurring_end_date'] = $data['end_date_' . $service_id];
                        $recurringData['end_date']=date('Y-m-d', strtotime($data['end_date_' . $service_id]));
                    } else {
                        unset($request_detail['recurring_end_date']);
                    }

                    if (isset($data['duration_' . $service_id])) {
                          $request_detail['duration'] = $data['duration_' . $service_id];
                          $recurringData['duration']=$data['duration_' . $service_id];
                    } else {
                        unset($request_detail['duration']);
                    }

                    if (isset($data['start_date_' . $service_id])) {
                          $request_detail['recurring_start_date'] = $data['start_date_' . $service_id];
                          $recurringData['start_date']=date('Y-m-d', strtotime($data['start_date_' . $service_id]));
                    } else {
                        unset($request_detail['recurring_start_date']);
                    }
                } else {
                    unset($request_detail['recurring']);
                    unset($request_detail['recurring_start_date']);
                    unset($request_detail['recurring_end_date']);
                    unset($request_detail['duration']);
                }

                //End recurring Request

                $add_requested_service = RequestedService::addRequestedService($request_detail);

                $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service

                //Inserting recurring data into recurring table

                if (isset($data['recurring_' . $service_id])) {
                     $recurringData['request_service_id']=     $request_detail_id;
                     $recurringData['status']=1;
                     $recurringData['assignment_type']='single';
                     Recurring::create($recurringData);
                }

                //End inserting
                $requested_selected_services[]=$request_detail_id;
                //check if service is created then insert images of service
                if ($add_requested_service) {
                    if (isset($data['service_image_list_' . $service_id])) {
                        $service_images = $data['service_image_list_' . $service_id];
                        foreach ($service_images as $image) {
                            $image_detail['requested_id'] = $request_detail_id;
                            $image_detail['image_name'] = $image;
                            $image_detail['image_type'] = 'request';
                            $image_detail['status'] = 1;
                            $add_image = ServiceImage::addServiceImage($image_detail);
                        }
                    }
                }
            }
        }



        $emergency_request_message="";
        if (isset($data['emergency_request']) && $data['emergency_request']==1) {
            $assetData=Asset::where('id', '=', $data['asset_number'])->get();


               $dataEmergencyRequest=[
                        'customer_id'=>$request['customer_id'],
                        'request_id'=> $request_id,
                        'status'    => '1' //Emergency request triggers
                      ];
                  EmergencyRequest::create($dataEmergencyRequest);
                  $emergency_request_id = DB::getPdo()->lastInsertId();
                  $nearest_users   = User::getNearestUsers(3, $assetData[0]->latitude, $assetData[0]->longitude, 50);

               if (isset($nearest_users[0])) {
                   foreach ($nearest_users as $userdata) {
                         $dataEmergencyRequestDetail['request_id']          =$request_id;
                         $dataEmergencyRequestDetail['vendor_id']           =$userdata->id;
                         $dataEmergencyRequestDetail['distance']            =$userdata->distance;
                         $dataEmergencyRequestDetail['emergency_request_id']=$emergency_request_id;
                         EmergencyRequestDetail::create($dataEmergencyRequestDetail);
                   }
                    /*
                     Commenting below code for making sure emergency request not being auto assigned assigned
                     */
                      // foreach ( $requested_selected_services as $service_id) {
                      //         $service_data['request_id'] = $request_id;
                      //         $service_data['requested_service_id'] = $service_id;
                      //         $service_data['vendor_id'] = $nearest_users[0]->id;
                      //         $service_data['status'] = 1;

                      //         AssignRequest::addRequest($service_data);


                      //     }
                      //     $data=User::where('id','=', $service_data['vendor_id'])->select('first_name','last_name')->get();

                      //     //Updating the status
                      //     EmergencyRequestDetail::where('vendor_id','=',$nearest_users[0]->id)
                      //                            ->where('emergency_request_id','=',$emergency_request_id)
                      //                            ->update(array('status'=>1)); //Auto Assigned request status, nearest vendor has been assigned
                      //     $emergency_request_message=" Auto assigned to ". $data[0]->first_name." ".$data[0]->last_name;
                      //    }
                     /*
                    End Commenting

                    */
                } else {
                    // $emergency_request_message=" Your Property address is far away from system vendors, admin will assign it manually. ";
                }
        }



        $customer_assets = Asset::getAssetsByCustomerId(Auth::user()->id);
        $services = Service::getAllServices();
        if ($add_request) {
            if (isset($data['bid_flag']) && $data['bid_flag']==1) {
                $message = FlashMessage::messages('customer.request_service_bid_request');
            } else {
                $message = FlashMessage::messages('customer.request_service_add');
            }



                $notification_url="admin";

                // $notification = NotificationController::sendNotification($recepient_id, 'New Customer has been registered.', 1, $email_data);
                $recepient_id = User::getAdminUsersId();
            foreach ($recepient_id as $rec_id) {
                //admin to admin notification
                //admin to admin notification

            //2.    Notification to Admin for New Request
                $userDAta=User::find($rec_id);


                if (isset($data['bid_flag']) && $data['bid_flag']==1) {
                    $notification_url="list-bidding-request";
                    $notification = NotificationController::doNotification($rec_id, $rec_id, "Bid Request ". $request_id ." has been created", 1, [], $notification_url);

                    $assetData=Asset::find($data['asset_number']);

                    $subject="Subject: ".$assetData->property_address." - Bid Requested";
                    $url="list-bidding-request/".$request_id;
                    $UrlMsg='To view Bid details click here: <a href="http://'.URL::to($url).'">please click here</a>!.';

                    $bidEMailContent=" Bid ". $request_id ." has been created 
<br/>".$UrlMsg."<br/>Property Address: ".$assetData->property_address."
<br/>
Status: New Bid Request
";


                    $email_data = [
                    'first_name' => $userDAta['first_name'],
                    'last_name' => $userDAta['last_name'],
                    'username' => $userDAta['username'],
                    'email' => $userDAta['email'],
                    'id' =>  $rec_id,
                    'user_email_template'=>$bidEMailContent
                    ];

                    Email::send($userDAta['email'], $subject, 'emails.customer_registered', $email_data);
                } else {
                     $notification = NotificationController::doNotification($rec_id, $rec_id, "Request ". $request_id ." has been created", 1, [], $notification_url);


                       $email_data = [
                       'first_name' => $userDAta['first_name'],
                       'last_name' => $userDAta['last_name'],
                       'username' => $userDAta['username'],
                       'email' => $userDAta['email'],
                       'id' =>  $rec_id,
                       'user_email_template'=>"Request ". $request_id ." has been created"
                       ];

                       Email::send($userDAta['email'], 'GSS New Request Notification', 'emails.customer_registered', $email_data);
                }
            }





            if (Auth::user()->type_id==1||Auth::user()->type_id==4) {
                if (isset($data['bid_flag']) && $data['bid_flag']==1) {
                    Session::flash('message', $message.  $emergency_request_message);
                    FlashMessage::displayAlert($message.  $emergency_request_message, 'success');
                    return redirect('list-bidding-request') ;
                } else {
                      return redirect('admin')
                      ->with('message', FlashMessage::displayAlert($message.  $emergency_request_message, 'success'));
                }
            } else {
                if (isset($data['bid_flag']) && $data['bid_flag']==1) {
                       Session::flash('message', $message.  $emergency_request_message);
                    FlashMessage::displayAlert($message.  $emergency_request_message, 'success');
                    return redirect('list-customer-requested-bids');
                } else {
                    return redirect('list-customer-requested-services')
                    ->with('message', FlashMessage::displayAlert($message.  $emergency_request_message, 'success'));
                }
            }
        }
    }





     /**
     * Edit maintenace request
     * @params none
     * @return Redirect to maintenance request page
     */
    public function editServiceRequest()
    {
        $data = Request::all(); // get all submitted data of user


        // get last id to assign to each service request
        $request_id =  $data['request_id'];


        //check if request in created then insert services to service table
        if (isset($request_id)) {
            //Select all selected service to
            $selected_services = $data['service_ids_selected'];

            /// loop through all selected services
            foreach ($selected_services as $service_id) {
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

                if (isset($data['quantity_' . $service_id])) {
                    $request_detail['quantity'] = $data['quantity_' . $service_id];
                } else {
                    unset($request_detail['quantity']);
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

                if (isset($data['due_date_' . $service_id])) {
                    $request_detail['due_date'] = $data['due_date_' . $service_id];
                } else {
                    unset($request_detail['due_date']);
                }


                if (isset($data['set_prinkler_system_type_' . $service_id])) {
                    $request_detail['set_prinkler_system_type'] = $data['set_prinkler_system_type_' . $service_id];
                } else {
                    unset($request_detail['set_prinkler_system_type']);
                }

                if (isset($data['install_temporary_system_type_' . $service_id])) {
                    $request_detail['install_temporary_system_type'] = $data['install_temporary_system_type_' . $service_id];
                } else {
                    unset($request_detail['install_temporary_system_type']);
                }


                if (isset($data['carpet_service_type_' . $service_id])) {
                    $request_detail['carpet_service_type'] = $data['carpet_service_type_' . $service_id];
                } else {
                    unset($request_detail['carpet_service_type']);
                }


                if (isset($data['pool_service_type_' . $service_id])) {
                    $request_detail['pool_service_type'] = $data['pool_service_type_' . $service_id];
                } else {
                    unset($request_detail['pool_service_type']);
                }

                if (isset($data['boarding_type_' . $service_id])) {
                    $request_detail['boarding_type'] = $data['boarding_type_' . $service_id];
                } else {
                    unset($request_detail['boarding_type']);
                }

                if (isset($data['spruce_up_type_' . $service_id])) {
                    $request_detail['spruce_up_type'] = $data['spruce_up_type_' . $service_id];
                } else {
                    unset($request_detail['spruce_up_type']);
                }

                if (isset($data['constable_information_type_' . $service_id])) {
                    $request_detail['constable_information_type'] = $data['constable_information_type_' . $service_id];
                } else {
                    unset($request_detail['constable_information_type']);
                }

                if (isset($data['remove_carpe_type_' . $service_id])) {
                    $request_detail['remove_carpe_type'] = $data['remove_carpe_type_' . $service_id];
                } else {
                    unset($request_detail['remove_carpe_type']);
                }

                if (isset($data['remove_blinds_type_' . $service_id])) {
                    $request_detail['remove_blinds_type'] = $data['remove_blinds_type_' . $service_id];
                } else {
                    unset($request_detail['remove_blinds_type']);
                }

                if (isset($data['remove_appliances_type_' . $service_id])) {
                    $request_detail['remove_appliances_type'] = $data['remove_appliances_type_' . $service_id];
                } else {
                    unset($request_detail['remove_appliances_type']);
                }






                if (isset($data['lot_size_' . $service_id])) {
                    $request_detail['lot_size'] = $data['lot_size_' . $service_id];
                } else {
                    unset($request_detail['lot_size']);
                }

                //Recurring request information
                $recurringData=[];
                if (isset($data['recurring_' . $service_id])) {
                    $request_detail['recurring'] = $data['recurring_' . $service_id];

                    if (isset($data['end_date_' . $service_id])) {
                        $request_detail['recurring_end_date'] = $data['end_date_' . $service_id];
                        $recurringData['end_date']=date('Y-m-d', strtotime($data['end_date_' . $service_id]));
                    } else {
                        unset($request_detail['recurring_end_date']);
                    }

                    if (isset($data['duration_' . $service_id])) {
                          $request_detail['duration'] = $data['duration_' . $service_id];
                          $recurringData['duration']=$data['duration_' . $service_id];
                    } else {
                        unset($request_detail['duration']);
                    }

                    if (isset($data['start_date_' . $service_id])) {
                          $request_detail['recurring_start_date'] = $data['start_date_' . $service_id];
                          $recurringData['start_date']=date('Y-m-d', strtotime($data['start_date_' . $service_id]));
                    } else {
                        unset($request_detail['recurring_start_date']);
                    }
                } else {
                    unset($request_detail['recurring']);
                    unset($request_detail['recurring_start_date']);
                    unset($request_detail['recurring_end_date']);
                    unset($request_detail['duration']);
                }

                //End recurring Request
                $alreadyExistsRequestedService=  RequestedService::where('request_id', '=', $request_id)
                      ->where('service_id', '=', $service_id)->count();

                if ($alreadyExistsRequestedService==0) {
                    $add_requested_service = RequestedService::addRequestedService($request_detail);

                    $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service

                //Inserting recurring data into recurring table
                    if (isset($data['recurring_' . $service_id])) {
                         $recurringData['request_service_id']=     $request_detail_id;
                         $recurringData['status']=1;
                         $recurringData['assignment_type']='single';
                         Recurring::create($recurringData);
                    }
                //End inserting
                    $requested_selected_services[]=$request_detail_id;
                //check if service is created then insert images of service
                    if ($add_requested_service) {
                        if (isset($data['service_image_list_' . $service_id])) {
                            $service_images = $data['service_image_list_' . $service_id];
                            foreach ($service_images as $image) {
                                $image_detail['requested_id'] = $request_detail_id;
                                $image_detail['image_name'] = $image;
                                $image_detail['image_type'] = 'request';
                                $image_detail['status'] = 1;
                                $add_image = ServiceImage::addServiceImage($image_detail);
                            }
                        }
                    }

                    $message = FlashMessage::messages('customer.request_service_add');

                    if (Auth::user()->type_id==1||Auth::user()->type_id==4) {
                              return redirect('list-maintenance-request')
                            ->with('message', FlashMessage::displayAlert($message, 'success'));
                    } else {
                        return redirect('list-customer-requested-services')
                        ->with('message', FlashMessage::displayAlert($message, 'success'));
                    }
                }
            }
        }
    }

    public function assignServiceRequest()
    {

       
        $assignment_data = Request::all();

        $userDAta=User::find($assignment_data['vendor']);
        $workOrderId = '';
        $vendorUsername = $userDAta->username;

        foreach ($assignment_data['services'] as $service_id) {
              $ifassignedcount=AssignRequest::where('request_id', '=', $assignment_data['request_id'])
                ->where('requested_service_id', '=', $service_id)

                ->count();

            if ($ifassignedcount>0) {
                continue;
            }
            $service_data['request_id'] = $assignment_data['request_id'];
            $service_data['requested_service_id'] = $service_id;
            $service_data['vendor_id'] = $assignment_data['vendor'];
            $service_data['status'] = 1;

            AssignRequest::addRequest($service_data);

            $requested_service_recurring = RequestedService::find($service_id);


            if (isset($requested_service_recurring->recurring) && ($requested_service_recurring->recurring==1 || $requested_service_recurring->recurring=='true')) {
                Recurring::where('request_service_id', '=', $requested_service_recurring->id)
                                ->update(['vendor_id'=>$assignment_data['vendor']]);
            }
        }

           //Auto Assign accept by vendor and creating work order.

         $accept_request = AssignRequest::acceptRequest($assignment_data['request_id'], $assignment_data['vendor']);


        if ($accept_request) {
            //Getting  services  ids
            $assigned_requests = AssignRequest::where('request_id', '=', $assignment_data['request_id'])
            ->where('vendor_id', '=', $assignment_data['vendor'])
            ->where('status', '!=', 2)
            ->get();
            
            $order_details = [];
            foreach ($assigned_requests as $request) {
                 //Creating the work order
                $data['status'] = 1;
                $data['status_text'] = "IN-PROCESS";
                $data['status_class'] = "yellow";

                $data['request_id'] = $assignment_data['request_id'];
                $data['vendor_id'] = $assignment_data['vendor'];
                $data['customer_id'] = MaintenanceRequest::find($assignment_data['request_id'])->asset->customer_id;
                $order_id = Order::addOrder($data);
                $workOrderId = $order_id;




                $order_details['requested_service_id'] = $request->requested_service_id;
                $order_details['order_id'] = $order_id;
                $order_details['status'] = 1;
                OrderDetail::addOrderDetails($order_details);



                $OrderDetailID = DB::getPdo()->lastInsertId();
                $image_detail=[];
                $destinationPath = config('app.order_images_before');   //2
                $upload_path = config('app.upload_path')."request";

                $imageDataArray=ServiceImage::where('requested_id', '=', $request->requested_service_id)->get();

                foreach ($imageDataArray as $imageData) {
                      //Copy Images for order
                      $order_details_id=$OrderDetailID;
                      $type='before';
                      $tempFile = $upload_path."/".$imageData->image_name;          //3
                      $targetPath = $destinationPath;  //4
                      $originalFile=$imageData->image_name;
                      $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
                      $targetFile = $targetPath . $changedFileName;  //5

                      copy($tempFile, $targetFile);

                      //End coping images

                      $image_detail['order_id'] = $order_id;
                      $image_detail['order_details_id'] = $order_details_id;

                      $image_detail['type'] = 'before';
                      $image_detail['address'] =  $changedFileName;
                      $image_detail['status'] = 1;
                      $add_image = OrderImage::create($image_detail);
                }




                $emailUrl="vendor-list-orders?url=".$order_id;
                $userDAta=User::find($assignment_data['vendor']);
                $email_data = [
                'first_name' => $userDAta->first_name,
                'last_name' => $userDAta->last_name,
                'username' => $userDAta->username,
                'email' => $userDAta->email,
                'id' =>  $assignment_data['vendor'],
                'user_email_template'=>$order_id ."  has been assigned to you! To view work order, <a href='http://".URL::to($emailUrl)."'>please click here</a>!"
                ];

                $customervendor="Vendor";
                $notification_url="vendor-list-orders";

            //Vendor to admin notification
                $notification = NotificationController::doNotification($assignment_data['vendor'], $assignment_data['vendor'], "New Work Order ".$order_id ." has been assigned to you!", 1, $email_data, $notification_url);
                Email::send($userDAta->email, 'GSS Work Order Notification', 'emails.customer_registered', $email_data);
            }
        }
//Ending

        $url = 'https://app.gssreo.com/srv/singleWorkOrder.php';
        $prvData = 'workOrderId='.$workOrderId.'&vendorUsername='.$vendorUsername;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $prvData);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        // var_dump($response);

        $message = FlashMessage::messages('admin.request_service_add');
        return redirect('admin')
                        ->with('message', FlashMessage::displayAlert($message, 'success'));
    }

    public function assignServiceBid()
    {
        $assignment_data = Request::all();

        foreach ($assignment_data['services'] as $service_id) {
              $ifassignedcount=AssignRequestBid::where('request_id', '=', $assignment_data['request_id'])
                ->where('requested_service_id', '=', $service_id)

                ->count();

            if ($ifassignedcount>0) {
                continue;
            }
            $service_data['request_id'] = $assignment_data['request_id'];
            $service_data['requested_service_id'] = $service_id;
            $service_data['vendor_id'] = $assignment_data['vendor'];
            $service_data['status'] = 1;

            AssignRequestBid::addRequest($service_data);
        }

        if ($assignment_data['flagworkorder']!=1) {
            $emailbody='Bid '.$assignment_data['request_id'] .' is still awaiting vendor';
            $url="list-bidding-request/".$assignment_data['request_id'];
            $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';

          //Remainder add
            $RemainderData=[
            'date' => $assignment_data['remindme'],
            'model'=>'BidRequest',
            'status'=>0,
            'remainder_text'=>$emailbody,
            'user_id'=>Auth::user()->id,
            'request_id'=>$assignment_data['request_id']
            ];
            Remainder::create($RemainderData);
//End Remainder
        }


       //                if($assignment_data['flagworkorder']!=1)
       //     {
       //      MaintenanceBid::where('id','=',$assignment_data['request_id'])
       //     ->update(array('status'=>2));
       //    }else{
       //    //Status 6 is for Bid Assignment when no price is set
       //     MaintenanceBid::where('id','=',$assignment_data['request_id'])
       //     ->update(array('status'=>6));

       //     //Notification to Customer
       //      $statusMessage="Awaiting Customer Approval";
       //     $emailbody='Bid Request '.$assignment_data['request_id'] .' status has been changed to '.$statusMessage;


       //             $url="list-customer-requested-bids/".$assignment_data['request_id'];
       //             $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';

       //      $MaintenanceBid=MaintenanceBid::find($assignment_data['request_id']);

       //      $userDAta=User::find($MaintenanceBid->user->id);
       //      $email_data = array(
       //      'first_name' => $userDAta->first_name,
       //      'last_name' => $userDAta->last_name,
       //      'username' => $userDAta->username,
       //      'email' => $userDAta->email,
       //      'id' =>  $MaintenanceBid->user->id,
       //      'user_email_template'=>$emailbody
       //                         );

       //      $customervendor="Customer";
       //      $notification_url="list-customer-requested-bids";

       //      //Vendor to admin notification
       //      $notification = NotificationController::doNotification($MaintenanceBid->user->id,$MaintenanceBid->user->id, 'Bid Request '. $assignment_data['request_id'] .' status has been changed to '. $statusMessage, 1,$email_data,$notification_url);
       //      Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);
       // }


            $message ="";
        if ($assignment_data['flagworkorder']==1) {
  //Create Work Order


            $BidRequest=MaintenanceBid::find($assignment_data['request_id']);


            $dataArray=[

            'customer_id'=>$BidRequest->customer_id,
            'substitutor_id'=>$BidRequest->substitutor_id,
            'asset_id'=>$BidRequest->asset_id,
            'job_type'=>$BidRequest->job_type,
            'status'=>$BidRequest->status,
            'emergency_request'=>$BidRequest->emergency_request,
            'admin_notes'=>$BidRequest->admin_notes,
            'decline_notes'=>$BidRequest->decline_notes,
            'created_at'=>$BidRequest->created_at,
            'updated_at'=>$BidRequest->updated_at
            ];
            MaintenanceRequest::addMaintenanceRequest($dataArray);
            $MaintenanceRequestID = DB::getPdo()->lastInsertId(); // get last id of service



            $BidRequestedService = RequestedBid::where('request_id', '=', $assignment_data['request_id'])
            ->get();


            $bidData=[];
            $i=0;
            foreach ($BidRequestedService as $biddatavalue) {
                $bidData['request_id']= $MaintenanceRequestID;
                $bidData['service_id']=$biddatavalue->service_id;
                $bidData['status']=1;
                $bidData['created_at']=$biddatavalue->created_at;
                $bidData['updated_at']=$biddatavalue->updated_at;
                $bidData['required_date']=$biddatavalue->required_date;
                $bidData['required_time']=$biddatavalue->required_time;
                $bidData['service_men']=$biddatavalue->service_men;
                $bidData['service_note']=$biddatavalue->service_note;
                $bidData['customer_note']=$biddatavalue->customer_notes_bid;
                $bidData['vendor_note']=$biddatavalue->vendor_note_for_bid;
                $bidData['public_notes']=$biddatavalue->public_notes;

                $bidData['verified_vacancy']=$biddatavalue->verified_vacancy;
                $bidData['cash_for_keys']=$biddatavalue->cash_for_keys;
                $bidData['cash_for_keys_trash_out']=$biddatavalue->cash_for_keys_trash_out;
                $bidData['trash_size']=$biddatavalue->trash_size;
                $bidData['storage_shed']=$biddatavalue->storage_shed;
                $bidData['lot_size']=$biddatavalue->lot_size;
                $bidData['bidding_prince']=$biddatavalue->vendor_bid_price;
                $bidData['customer_price']=$biddatavalue->customer_bid_price;
                $bidData['due_date']=$biddatavalue->due_date;






                $i++;

                $add_requested_service = RequestedService::addRequestedService($bidData);

                $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service


                $imageDataArray=ServiceImageBid::where('requested_id', '=', $biddatavalue->id)->get();

                foreach ($imageDataArray as $imageData) {
                       $image_detail['requested_id'] = $request_detail_id;
                       $image_detail['image_name'] = $imageData->image_name;
                       $image_detail['image_type'] = 'request';
                       $image_detail['status'] = 1;
                       $add_image = ServiceImage::addServiceImage($image_detail);
                }
                   $dataRequests['request_id']=$MaintenanceRequestID;
                   $dataRequests['requested_service_id']=$request_detail_id;
                   $dataRequests['vendor_id']=$assignment_data['vendor'];
                   $dataRequests['status']=3;


                   $accept_request = AssignRequest::create($dataRequests);

                   // $orderDATA= Order::where('request_id','=',$biddatavalue->maintenance_request_id)
                   // ->where('vendor_id','=',$input['vendor_id'])
                   // ->first();


                   //Creating the work order
                 $data['status'] = 0;
                 $data['status_text'] = "New Work Order";
                 $data['status_class'] = "green";

                 $data['request_id'] = $MaintenanceRequestID;
                 $data['vendor_id'] = $assignment_data['vendor'];
                 $data['customer_id'] = $BidRequest->customer_id;
                 $data['bid_flag']=1;
                 $order_id = Order::addOrder($data);


                   $order_details['requested_service_id'] =  $request_detail_id;
                   $order_details['order_id'] =$order_id;
                   $order_details['status'] = 1;
                   OrderDetail::create($order_details);
                   $OrderDetailID = DB::getPdo()->lastInsertId();
                   $destinationPath = config('app.order_images_before');   //2
                   $upload_path = config('app.upload_path')."request";
                foreach ($imageDataArray as $imageData) {
                      //Copy Images for order
                      $order_details_id=$OrderDetailID;
                      $type='before';
                      $tempFile = $upload_path."/".$imageData->image_name;          //3
                      $targetPath = $destinationPath;  //4
                      $originalFile=$imageData->image_name;
                      $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
                      $targetFile = $targetPath . $changedFileName;  //5

                      copy($tempFile, $targetFile);

                      //End coping images

                      $image_detail['order_id'] = $order_id;
                      $image_detail['order_details_id'] = $order_details_id;

                      $image_detail['type'] = 'before';
                      $image_detail['address'] =  $changedFileName;
                      $image_detail['status'] = 1;
                      $add_image = OrderImage::create($image_detail);
                }
            }

        // Created Work Order
            $data = ['status' => 4 ];
            $save = MaintenanceBid::find($assignment_data['request_id'])->update($data);




         //Notification to Customer
            $statusMessage="Work Order Generated";
            $emailbody='Work Order '.$order_id .' has been generated ';


            $url="customer-list-work-orders/".$order_id;
            $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';

            $MaintenanceBid=MaintenanceBid::find($assignment_data['request_id']);

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
            $notification_url="customer-list-work-orders";


            $notification = NotificationController::doNotification($MaintenanceBid->user->id, $MaintenanceBid->user->id, 'Work Order '.$order_id .' has been generated ', 1, $email_data, $notification_url);
            Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);


        //Notification to Vendor
            $statusMessage="Work Order Generated";
            $emailbody='Work Order '.$order_id .' has been generated ';


              $url="vendor-list-orders/".$order_id;
              $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';

            $MaintenanceBid=MaintenanceBid::find($assignment_data['request_id']);

            $userDAta=User::find($assignment_data['vendor']);
            $email_data = [
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $MaintenanceBid->user->id,
            'user_email_template'=>$emailbody
                        ];

            $customervendor="Vendor";
            $notification_url="vendor-list-orders";

       //Vendor to admin notification
            $notification = NotificationController::doNotification($MaintenanceBid->user->id, $MaintenanceBid->user->id, 'Work Order '.$order_id .' has been generated ', 1, $email_data, $notification_url);
            Email::send($userDAta->email, ': Work Order Notification', 'emails.customer_registered', $email_data);






            $message = "New work order has been generated due to bid request";
        } else {
            $message = "Your request is now Awaiting Venbdor bid";
          //ASssign to Vendor
            MaintenanceBid::where('id', '=', $assignment_data['request_id'])
            ->update(['status'=>2]);
         //Notification to Vendor
            $statusMessage="Bid has been assigned";
            $emailbody='Bid '.$assignment_data['request_id'] .' has been Assigned ';


            $url="vendor-assigned-bids/".$assignment_data['request_id'];
            $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';

            $MaintenanceBid=MaintenanceBid::find($assignment_data['request_id']);


            $assetData=Asset::find($MaintenanceBid->asset_id);

            $getServicesforEmail=RequestedBid::where('request_id', '=', $MaintenanceBid->id)->get();
            $serviceType="";
            foreach ($getServicesforEmail as $getServicesforEmailData) {
                if (isset($getServicesforEmailData->service->title)) {
                    $serviceType=$getServicesforEmailData->service->title."<br/>";
                }
            }
            $subject="Subject: ".$assetData->property_address." - Bid Requested \n Bid is ".$statusMessage;
            $url="vendor-assigned-bids/".$assignment_data['request_id'];
            $UrlMsg='To view Bid details click here: <a href="http://'.URL::to($url).'">please click here</a>!.';

            $bidEMailContent=$UrlMsg."<br/>
Request ID:".$assignment_data['request_id']."<br/>
Property Address: ".$assetData->property_address."<br/>
Status: Awaiting Bid Approval <br/>
Service Type:".$serviceType;










            $userDAta=User::find($assignment_data['vendor']);
            $email_data = [
            'first_name' => $userDAta->first_name,
            'last_name' => $userDAta->last_name,
            'username' => $userDAta->username,
            'email' => $userDAta->email,
            'id' =>  $userDAta->id,
            'user_email_template'=>$bidEMailContent
                        ];

            $customervendor="Vendor";
            $notification_url="vendor-assigned-bids";

          //Vendor to admin notification
            $notification = NotificationController::doNotification($assignment_data['vendor'], Auth::user()->id, 'Bid Request '.$assignment_data['request_id'].' has been assigned ', 1, $email_data, $notification_url);
            Email::send($userDAta->email, $subject, 'emails.customer_registered', $email_data);
        }



            Session::flash('message', $message);
            FlashMessage::displayAlert($message, 'success');
            return redirect('list-bidding-request');
    }





    public function approveBidRequestStatusChanged()
    {
        $assignment_data = Request::all();
        // Status has been changed to Approved Bid
        $data = ['status' => 8 ];
        $save = MaintenanceBid::find($assignment_data['request_id'])->update($data);


        $MaintenanceBid=MaintenanceBid::find($assignment_data['request_id']);

        $assetData=Asset::find($MaintenanceBid->asset_id);

        $getServicesforEmail=RequestedBid::where('request_id', '=', $MaintenanceBid->id)->get();
        $serviceType="";
        foreach ($getServicesforEmail as $getServicesforEmailData) {
            if (isset($getServicesforEmailData->service->title)) {
                $serviceType=$getServicesforEmailData->service->title."<br/>";
            }
        }
        $subject="Subject: ".$assetData->property_address." - Bid Requested \n Bid is Approved Bid";
        $url="list-bidding-request/".$MaintenanceBid->id;
        $UrlMsg='To view Bid details click here: <a href="http://'.URL::to($url).'">please click here</a>!.';

        $bidEMailContent=$UrlMsg."<br/>
Request ID:".$MaintenanceBid->id."<br/>
Property Address: ".$assetData->property_address."<br/>
Status: Approved Bid<br/>
Service Type:".$serviceType;




      ///Notification to admin

                   $emailbody='Bid  '.$assignment_data['request_id'].'  has been changed to approved bid ';


                   $url="list-bidding-request/".$assignment_data['request_id'];
                   $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';



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
            'user_email_template'=>$bidEMailContent
               ];

            $customervendor="Admin";
            $notification_url="list-bidding-request";

    //Customer to admin notification
            $notification = NotificationController::doNotification($rec_id, $rec_id, 'Bid  '.$assignment_data['request_id'].'  has been changed to approved bid', 1, $email_data, $notification_url);
            Email::send($userDAta->email, $subject, 'emails.customer_registered', $email_data);
        }

              //Update status for not sending remainder emails
            Remainder::where('user_id', '=', Auth::user()->id)
            ->where('request_id', '=', $assignment_data['request_id'])
            ->update(['status'=>1]);

         Session::flash('message', "Bid has been approved");
           FlashMessage::displayAlert("Bid has been approved", 'success');
    }
    public function approveBidRequest()
    {
          $assignment_data = Request::all();

         //Create Work Order


          $BidRequest=MaintenanceBid::find($assignment_data['request_id']);

        $order_id="";
          $dataArray=[

            'customer_id'=>$BidRequest->customer_id,
            'substitutor_id'=>$BidRequest->substitutor_id,
            'asset_id'=>$BidRequest->asset_id,
            'job_type'=>$BidRequest->job_type,
            'status'=>$BidRequest->status,
            'emergency_request'=>$BidRequest->emergency_request,
            'admin_notes'=>$BidRequest->admin_notes,
            'decline_notes'=>$BidRequest->decline_notes,
            'created_at'=>$BidRequest->created_at,
            'updated_at'=>$BidRequest->updated_at
            ];
          MaintenanceRequest::addMaintenanceRequest($dataArray);
          $MaintenanceRequestID = DB::getPdo()->lastInsertId(); // get last id of service



          $BidRequestedService = RequestedBid::where('request_id', '=', $assignment_data['request_id'])
          ->get();


          $bidData=[];
          $i=0;
          foreach ($BidRequestedService as $biddatavalue) {
              $bidData['request_id']= $MaintenanceRequestID;
              $bidData['service_id']=$biddatavalue->service_id;
              $bidData['status']=1;
              $bidData['created_at']=$biddatavalue->created_at;
              $bidData['updated_at']=$biddatavalue->updated_at;
              $bidData['required_date']=$biddatavalue->required_date;
              $bidData['required_time']=$biddatavalue->required_time;
              $bidData['service_men']=$biddatavalue->service_men;
              $bidData['service_note']=$biddatavalue->service_note;
              $bidData['customer_note']=$biddatavalue->customer_notes_bid;
              $bidData['vendor_note']=$biddatavalue->vendor_note_for_bid;
              $bidData['public_notes']=$biddatavalue->public_notes;
              $bidData['verified_vacancy']=$biddatavalue->verified_vacancy;
              $bidData['cash_for_keys']=$biddatavalue->cash_for_keys;
              $bidData['cash_for_keys_trash_out']=$biddatavalue->cash_for_keys_trash_out;
              $bidData['trash_size']=$biddatavalue->trash_size;
              $bidData['storage_shed']=$biddatavalue->storage_shed;
              $bidData['lot_size']=$biddatavalue->lot_size;
              $bidData['bidding_prince']=$biddatavalue->vendor_bid_price;
              $bidData['customer_price']=$biddatavalue->customer_bid_price;
              $bidData['due_date']= $assignment_data ['completion_date'];



              $i++;

              $add_requested_service = RequestedService::addRequestedService($bidData);

              $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service


              $imageDataArray=ServiceImageBid::where('requested_id', '=', $biddatavalue->id)->get();

              foreach ($imageDataArray as $imageData) {
                  $image_detail['requested_id'] = $request_detail_id;
                  $image_detail['image_name'] = $imageData->image_name;
                  $image_detail['image_type'] = 'request';
                  $image_detail['status'] = 1;
                  $add_image = ServiceImage::addServiceImage($image_detail);
                }
                $dataRequests['request_id']=$MaintenanceRequestID;
                $dataRequests['requested_service_id']=$request_detail_id;
                $dataRequests['vendor_id']=$assignment_data['vendor'];
                $dataRequests['status']=3;


                $accept_request = AssignRequest::create($dataRequests);

              // $orderDATA= Order::where('request_id','=',$biddatavalue->maintenance_request_id)
              // ->where('vendor_id','=',$input['vendor_id'])
              // ->first();


                //Creating the work order
                $data['status'] = 0;
                $data['status_text'] = "New Work Order";
                $data['status_class'] = "green";
                $data['request_id'] = $MaintenanceRequestID;
                $data['vendor_id'] = $assignment_data['vendor'];
                $data['customer_id'] = $BidRequest->customer_id;
                $data['bid_flag']=1;
                $order_id = Order::addOrder($data);


                $order_details['requested_service_id'] =  $request_detail_id;
                $order_details['order_id'] =$order_id;
                $order_details['status'] = 1;
                OrderDetail::create($order_details);
                $OrderDetailID = DB::getPdo()->lastInsertId();
                $destinationPath = config('app.order_images_before');   //2
                $upload_path = config('app.upload_path')."request";

                foreach ($imageDataArray as $imageData) {
                    //Copy Images for order
                    $order_details_id=$OrderDetailID;
                    $type='before';
                    $tempFile = $upload_path."/".$imageData->image_name;          //3
                    $targetPath = $destinationPath;  //4
                    $originalFile=$imageData->image_name;
                    $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
                    $targetFile = $targetPath . $changedFileName;  //5

                    copy($tempFile, $targetFile);

                    //End coping images

                    $image_detail['order_id'] = $order_id;
                    $image_detail['order_details_id'] = $order_details_id;

                    $image_detail['type'] = 'before';
                    $image_detail['address'] =  $changedFileName;
                    $image_detail['status'] = 1;
                    $add_image = OrderImage::create($image_detail);
                }


             //Images of Vendor Bid images
                foreach ($BidRequest->assignRequest as $assignRequestData) {
                     $destinationPath = config('app.order_images_before');   //2
                    $upload_path = config('app.bid_images_before');

                     $imageDataArray=AssignRequestBidsImage::where('requested_id', '=', $assignRequestData->id)->get();

                    foreach ($imageDataArray as $imageData) {
                    //Copy Images for order
                        $order_details_id=$OrderDetailID;
                        $type='before';
                        $tempFile = $upload_path."/".$imageData->image_name;          //3
                        $targetPath = $destinationPath;  //4
                        $originalFile=$imageData->image_name;
                        $changedFileName=$order_id.'-'.$order_details_id.'-'.$originalFile;
                        $targetFile = $targetPath . $changedFileName;  //5

                        copy($tempFile, $targetFile);

                    //End coping images

                        $image_detail['order_id'] = $order_id;
                        $image_detail['order_details_id'] = $order_details_id;

                        $image_detail['type'] = 'before';
                        $image_detail['address'] =  $changedFileName;
                        $image_detail['status'] = 1;
                        $add_image = OrderImage::create($image_detail);
                    }
                }
            }

            // Created Work Order
            $data = ['status' => 4 ];
            $save = MaintenanceBid::find($assignment_data['request_id'])->update($data);







///Notification to admin

                   $emailbody='Work Order '.$order_id.'  has been generated due to bid request ';


                   $url="ist-work-order-admin/".$order_id;
                   $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';



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
                $notification_url="list-work-order-admin";

            //Vendor to admin notification
                $notification = NotificationController::doNotification($rec_id, $rec_id, 'Work Order '.$order_id .' has been generated due to bid request', 1, $email_data, $notification_url);
                Email::send($userDAta->email, ': Work Order Notification', 'emails.customer_registered', $email_data);
         //Update status for not sending remainder emails
                Remainder::where('user_id', '=', Auth::user()->id)
                ->where('request_id', '=', $assignment_data['request_id'])
                ->update(['status'=>1]);
            }

                 Session::flash('message', "Bid Approved, Work Order Generated");
            FlashMessage::displayAlert("Bid Approved, Work Order Generated", 'success');
    }


    function declineBidRequest()
    {
        $assignment_data = Request::all();

        // Decline Bid Request
        $data = ['status' => 7,'declinebidnotes'=>$assignment_data['declinebidnotes'] ];
        $save = MaintenanceBid::find($assignment_data['request_id'])->update($data);



    ///Notification to admin

                   $emailbody='Bid Request '.$assignment_data['request_id'] .' status has been changed to Declined';


                   $url="list-bidding-request/".$assignment_data['request_id'];
                   $emailbody.='To view the Bid Request <a href="http://'.URL::to($url).'">please click here</a>!.';



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
            $notification_url="list-bidding-request";

    //Vendor to admin notification
            $notification = NotificationController::doNotification($rec_id, $rec_id, 'Bid Request '.$assignment_data['request_id'] .' status has been changed to Declined', 1, $email_data, $notification_url);
            Email::send($userDAta->email, ': Bid Request Notification', 'emails.customer_registered', $email_data);


            Session::flash('messagedecline', "Bid has been declined");
            FlashMessage::displayAlert("Your Bid has been sent successfully!", 'success');



  //Update status for not sending remainder emails
            Remainder::where('user_id', '=', Auth::user()->id)
            ->where('request_id', '=', $assignment_data['request_id'])
            ->update(['status'=>1]);
        }
    }




    function adminNotes()
    {
           $assignment_data = Request::all();

              MaintenanceRequest::where('id', '=', $assignment_data['request_id'])
                                 ->update(['admin_notes'=>$assignment_data['admin_notes']]);
    }
    function adminNotesBid()
    {
        $assignment_data = Request::all();

          MaintenanceBid::where('id', '=', $assignment_data['request_id'])
                               ->update(['admin_notes'=>$assignment_data['admin_notes']]);
    }


    function adminNotesOsr()
    {
        $assignment_data = Request::all();

        BidRequest::where('id', '=', $assignment_data['request_id'])
                         ->update(['admin_notes'=>$assignment_data['admin_notes']]);
    }

    function customerNotesOsr()
    {
        $assignment_data = Request::all();

        BidRequest::where('id', '=', $assignment_data['request_id'])
                         ->update(['customer_notes'=>$assignment_data['admin_notes']]);
    }

    /*
    Saving vendor notes for bid request
    */
    function vendorNotesBid()
    {
        $assignment_data = Request::all();
        print_r($assignment_data);
          MaintenanceBid::where('id', '=', $assignment_data['request_id'])
                               ->update(['vendor_notes'=>$assignment_data['vendor_notes']]);
    }
    function publicNotes()
    {
         $assignment_data = Request::all();

            RequestedService::where('id', '=', $assignment_data['service_id'])
                               ->update(['public_notes'=>$assignment_data['public_notes']]);
    }

    function publicNotesBid()
    {
           $assignment_data = Request::all();

              RequestedBid::where('id', '=', $assignment_data['service_id'])
                                 ->update(['public_notes'=>$assignment_data['public_notes']]);
    }

    function customerNotesBid()
    {
           $assignment_data = Request::all();

              RequestedBid::where('id', '=', $assignment_data['service_id'])
                                 ->update(['customer_notes_bid'=>$assignment_data['customer_notes_bid']]);
    }
    function changeDueDate()
    {
              $assignment_data = Request::all();

              RequestedService::where('id', '=', $assignment_data['requestedID'])
                                 ->update(['due_date'=>$assignment_data['duedatechange']]);
    }
}
