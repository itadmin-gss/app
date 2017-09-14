<?php
/**
 * AccessLevel Controller Class.
 *
 * Will be handling all the Access level functionlity here.
 * @copyright Copyright 2014 Invortex Technology Development Team
 * @version $Id: 1.0
 */

class CronJobController extends \BaseController
{

    public function index()
    {

        

    /*
					Can we set a different time limit for vendor acceptance on emergency orders? We are thinking within 2 hours.
					*/
                    $emergencyData=  MaintenanceRequest::where('emergency_request', '=', 1)->get();
                    
                    $requestIDARray=[];

        foreach ($emergencyData as $value) {
            foreach ($value->assignRequest as $assignRequestss) {
                if ($assignRequestss->status==1) {
                    $date = new DateTime($assignRequestss->created_at);
                    $date->modify("+2 hours");
                    if ($date->format("Y-m-d H:i:s") <= date("Y-m-d H:i:s")) {
                        $requestIDARray[$assignRequestss->request_id]= $assignRequestss->vendor_id;
                    }
                }
            }
        }


        foreach ($requestIDARray as $requestID => $VendorID) {
            AssignRequest::deleteRequest($requestID, $VendorID);
        }





                /*
				5.      Add rule: If work order not accepted within 168 hrs, work order goes back to gss user to schedule	*/
                $emergencyData=  MaintenanceRequest::where('emergency_request', '!=', 1)->get();

                $requestIDARray=[];

        foreach ($emergencyData as $value) {
            foreach ($value->assignRequest as $assignRequestss) {
                if ($assignRequestss->status==1) {
                    $date = new DateTime($assignRequestss->created_at);
                    $date->modify("+168 hours");
                    if ($date->format("Y-m-d H:i:s") <= date("Y-m-d H:i:s")) {
                        $requestIDARray[$assignRequestss->request_id]= $assignRequestss->vendor_id;
                    }
                }
            }
        }


        foreach ($requestIDARray as $requestID => $VendorID) {
            AssignRequest::deleteRequest($requestID, $VendorID);
        }
                



                

                $recurring= Recurring::where('start_date', '<=', date('Y-m-d'))
                ->get();

        foreach ($recurring as $value) {
            $date=date_create($value->start_date);
            date_add($date, date_interval_create_from_date_string("$value->duration days"));
            $nextDate= date_format($date, "Y-m-d");

        //if date meet with current date it will execute the the cron job and create a service request
            if ($nextDate<=date('Y-m-d')) {
                $request_service_id=$value ->request_service_id;
                $RequestedService  =RequestedService::find($request_service_id);
                if ($RequestedService->maintenanceRequest->asset->property_dead_status==1) {
                    continue;
                }

                $MaintenanceRequestData=[
                    'customer_id'=>$RequestedService->maintenanceRequest->customer_id,
                    'job_type'=>$RequestedService->maintenanceRequest->jobType->id,
                    'asset_id'=>$RequestedService->maintenanceRequest->asset_id,
                    'emergency_request'=>$RequestedService->maintenanceRequest->emergency_request,
                    'status'=>1
                    ];
                    //inserting the new request
                MaintenanceRequest::create($MaintenanceRequestData);
                $lastMaintenanceRequestId=DB::getPdo()->lastInsertId();
                            //End Inserting Maintanance Request

                            ///Inserting New Requested Service

                $request_detail=[];
                $request_detail['request_id'] = $lastMaintenanceRequestId; // assign request id to $requested detail
                        $request_detail['service_id'] = $RequestedService->service_id; // assign service_id to $requested detail
                        $request_detail['status'] = 1;
                        $request_detail['required_date'] = $RequestedService->required_date;
                        $request_detail['required_time'] = $RequestedService->required_time;
                        $request_detail['service_men'] = $RequestedService->service_men;
                        $request_detail['service_note'] = $RequestedService->service_note;
                        $request_detail['verified_vacancy'] = $RequestedService->verified_vacancy;
                        $request_detail['cash_for_keys'] = $RequestedService->cash_for_keys;
                        $request_detail['cash_for_keys_trash_out'] = $RequestedService->cash_for_keys_trash_out;
                        $request_detail['trash_size'] = $RequestedService->trash_size;
                        $request_detail['storage_shed'] = $RequestedService->storage_shed;
                        $request_detail['lot_size'] = $RequestedService->lot_size;
                        $request_detail['due_date'] = $nextDate;

                        $request_detail['set_prinkler_system_type'] = $RequestedService->set_prinkler_system_type;

                        $request_detail['install_temporary_system_type'] = $RequestedService->install_temporary_system_type;
                        $request_detail['carpet_service_type'] = $RequestedService->carpet_service_type;
                        $request_detail['pool_service_type'] = $RequestedService->pool_service_type;
                        $request_detail['boarding_type'] = $RequestedService->boarding_type;

                        $request_detail['spruce_up_type'] = $RequestedService->spruce_up_type;




                        $add_requested_service = RequestedService::addRequestedService($request_detail);
                        $request_detail_id = DB::getPdo()->lastInsertId(); // get last id of service

                        //Auto Assigne to Vendor
                        $service_data['request_id'] = $lastMaintenanceRequestId;
                        $service_data['requested_service_id'] = $request_detail_id;
                        $service_data['vendor_id'] = $value->vendor_id;
                        $service_data['status'] = 1;
                        AssignRequest::addRequest($service_data);


                foreach ($RequestedService->serviceImages as $serviceImages) {
                    $image_detail['requested_id'] = $request_detail_id;
                    $image_detail['image_name'] = $serviceImages->image;
                    $image_detail['image_type'] = 'request';
                    $image_detail['status'] = 1;
                    $add_image = ServiceImage::addServiceImage($image_detail);
                }

                        Recurring::find($value->id)
                        ->update(['start_date'=>$nextDate]);



         //Auto Assign accept by vendor and creating work order.

                        $accept_request = AssignRequest::acceptRequest($lastMaintenanceRequestId, $value->vendor_id);


                if ($accept_request) {
            //Getting  services  ids
                    $assigned_requests = AssignRequest::where('request_id', '=', $lastMaintenanceRequestId)
                    ->where('vendor_id', '=', $value->vendor_id)
                    ->where('status', '!=', 2)
                    ->get();
                    $order_details = [];
                    foreach ($assigned_requests as $request) {
        //Creating the work order
                        $data['status'] = 0;
                        $data['status_text'] = "New Work Order";
                        $data['status_class'] = "green";

                        $data['request_id'] = $lastMaintenanceRequestId;
                        $data['vendor_id'] = $value->vendor_id;
                        $data['customer_id'] = MaintenanceRequest::find($lastMaintenanceRequestId)->asset->customer_id;
                        $order_id = Order::addOrder($data);




                        $order_details['requested_service_id'] = $request->requested_service_id;
                        $order_details['order_id'] = $order_id;
                        $order_details['status'] = 1;
                        OrderDetail::addOrderDetails($order_details);
                        $emailUrl="vendor-list-orders?url=".$order_id;
                        $userDAta=User::find($value->vendor_id);
                        $email_data = [
                            'first_name' => $userDAta->first_name,
                            'last_name' => $userDAta->last_name,
                            'username' => $userDAta->username,
                            'email' => $userDAta->email,
                            'id' =>  $value->vendor_id,
                            'user_email_template'=>$order_id ."  has been assigned to you! To view work order, <a href='http://".URL::to($emailUrl)."'>please click here</a>!"
                            ];

                        $workOrderId = $order_id;
                        $vendorUsername = $userDAta->username;

                        $customervendor="Vendor";
                        $notification_url="vendor-list-orders";

        //Vendor to admin notification
                        $notification = NotificationController::doNotification($data['vendor_id'], $data['vendor_id'], "New Work Order ".$order_id ." has been assigned to you!", 1, $email_data, $notification_url);

                        //Email::send($userData->email, 'GSS Work Order Notification', 'emails.customer_registered', $email_data);

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
                    }
                }
//Ending 

                        Recurring::find($value->id)
                        ->update(['start_date'=>$nextDate]);
            }

            $cronjob['recurring_id']=$value->id;
            Cronjob::create($cronjob);
        }



//Remainder Emails Generation 
                        $Remainder = Remainder::where('status', '=', 0)->get();

        foreach ($Remainder as $value) {
            if (date('m/d/y', strtotime($value->date))==date('m/d/y')) {
                $email_data = [
                    'first_name' => $value->user->first_name,
                    'last_name' => $value->user->last_name,
                    'username' => $value->user->username,
                    'email' => $value->user->email,
                    'id' =>  $value->user->id,
                    'user_email_template'=>$value->remainder_text
                    ];


                //Email::send($value->user->email, 'Friendly Remainder', 'emails.customer_registered', $email_data);
            }
        }
        //End of Reminder Emails
    }
}
