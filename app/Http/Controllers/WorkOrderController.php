<?php

namespace App\Http\Controllers;

use App\Helpers\SSP;
use App\JobType;
use App\CustomerType;
use App\OrderDetail;
use App\User;
use App\MaintenanceRequest;
use App\Asset;
use App\City;
use App\State;
use App\RequestedService;
use App\Service;
use Illuminate\Support\Facades\Request;

class WorkOrderController extends Controller
{

    //Process DataTables Request
     public function index()
     {

        
        //Database Table
        $table = 'orders';

        //DB Primary Key
        $primaryKey = 'id';


        $columns = array(

            //Row 0 -> Contains the request_id, customer details, maintenance request details, etc.
            array(
                'db' => 'request_id', 
                'dt' => 0,
                'formatter' => function($d, $row)
                {
                    //Get Data Related to Work Orders
                    $maintenance_request    = MaintenanceRequest::where('id', $d)->get(['job_type', 'status', 'substitutor_id', 'asset_id']);
                    $customer2_details      = User::getUserNameArray($maintenance_request[0]->substitutor_id);
                    $asset_details          = Asset::where('id', $maintenance_request[0]->asset_id)->get(['asset_number', 'property_address', 'city_id', 'state_id', 'customer_type', 'zip']);
                    $customer_types         = CustomerType::all()->toArray();
                    $job_types              = JobType::all()->toArray();
                    $order_details          = OrderDetail::where('order_id', $d)->get(['requested_service_id']);

                    //Variables
                    $customer_type = 'Unknown';
                    $submitter_name = [];
                    $job_type = "";
                    $due_date = "";
                    $service_names = "";

                    //Check for Customer Type
                    if (isset($asset_details[0]->customer_type))
                    {
                        foreach($customer_types as $cType)
                        {
                            if ($cType['id'] == $asset_details[0]->customer_type)
                            {
                                $customer_type = $cType['title'];
                            }
                        }
                    }

                    

                    //Check for Submitted By
                    if (isset($customer2_details['first_name']))
                    {
                        $submitter_name['first_name'] = $customer2_details['first_name'];
                    }

                    if (isset($customer2_details['last_name']) && isset($customer2_details['first_name']))
                    {
                        $submitter_name['last_name'] = substr($customer2_details['last_name'], 0, 1).".";
                    } else if (isset($customer2_details['last_name'])){
                        $submitter_name['last_name'] = $customer2_details['last_name'];
                    }

                    if (!empty($submitter_name))
                    {
                        $sub_name = implode(" ", $submitter_name);
                    }
                    else
                    {
                        $sub_name = "";
                    }

                    //Check for Property Address
                    if (isset($asset_details[0]->property_address))
                    {
                        $property_address = $asset_details[0]->property_address;
                    }
                    else 
                    {
                        $property_address = "";
                    }

                    //Check for City
                    if (isset($asset_details[0]->city_id))
                    {
                        $property_city = City::where('id', $asset_details[0]->city_id)->get(['name'])[0]->name;
                    }
                    else
                    {
                        $property_city = "";
                    }

                    //Check for State
                    if (isset($asset_details[0]->state_id))
                    {
                        $state = State::where('id', $asset_details[0]->state_id)->get(['name'])[0]->name;
                    }
                    else
                    {
                        $state = "";
                    }

                    //Check for Zip
                    if (isset($asset_details[0]->zip))
                    {
                        $zip = $asset_details[0]->zip;
                    }
                    else
                    {
                        $zip = "";
                    }

                    //Check for Job Type
                    foreach($job_types as $type)
                    {
                        if ($type['id'] == $maintenance_request[0]->job_type)
                        {
                            $job_type = $type['title'];
                        }
                    }

                    //Check for Service Type / Due Dates
                    foreach($order_details as $order_detail)
                    {
                        $request_service = RequestedService::find($order_detail->requested_service_id);                        
                        if (isset($request_service->due_date) && $request_service->due_date != '')
                        {
                            $due_date .= date('m/d/Y', strtotime($request_service->due_date))." <br>";
                        } 
                        else
                        {
                            $due_date .= "Not Set <br>";
                        }
                        $service_details = Service::where('id', $request_service->service_id)->get()->toArray()[0];
                        if (isset($service_details['title']))
                        {
                            $service_names .= $service_details['title']."<br>";
                        }
                    }

                    return [$d, $customer_type, $sub_name, $property_address, $property_city, $state, $zip, $job_type, $due_date, $service_names];
                }
            ),


            // Row 3 -> Customer Details
            array(
                'db' => 'customer_id', 
                'dt' => 3, 
                'formatter' => function($d, $row)
                {
                    $customer_details = User::getUserNameArray($d);
                    return trim($customer_details['first_name']." ".$customer_details['last_name']);
                }
            ),

            // Row 8 -> Vendor Details
            array(
                'db' => 'vendor_id',
                'dt' => 8,
                'formatter' => function($d, $row)
                {
                    $vendor_details = User::getUserNameArray($d);
                    return trim($vendor_details['first_name']." ".$vendor_details['last_name']);
                }
            ),

            //Row 11 & 12 -> Dummy Entries to grab status_class and status_text for status
            array('db' => 'status_class', 'dt' => 11),
            array('db' => 'status_text', 'dt' => 10),
            array(
                'db' => 'status',
                'dt' => 12,
                'formatter' => function($d, $row)
                {
                    if ($d == 4)
                    {
                        return [$d, "<td class='center'><span class='badge badge-summary badge-danger'>Cancelled</span></td>"];
                    }
                    else
                    {
                        
                        switch ($row[3]){
                            case "black":
                                $status_class = "default";
                            break;
                            case "blue":
                                $status_class = "primary";
                            break;
                            case "green":
                                $status_class = "success";
                            break;
                            case "important":
                                $status_class = "danger";
                            break;
                            case "warning":                          
                            case "yellow":
                                $status_class = "warning";
                            break;
                            default:
                                $status_class = "default";
                        }

                        return [$d, "<td class='center'><span class='badge badge-summary badge-".$status_class."'>".$row[4]."</span></td>"];
                    }
                }
            )

        );

        $sql_details = array(
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'db' => env('DB_DATABASE'),
            'host' => env('DB_HOST')
        );

        return SSP::simple(Request::all(), $sql_details, $table, $primaryKey, $columns);

     }
}
