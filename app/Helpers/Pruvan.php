<?php

namespace App\Helpers;

use App\MaintenanceRequest;
use App\Asset;
use App\State;
use App\City;
use App\Order;
use App\PruvanVendors;
use App\RequestedService;
use App\Service;
use App\User;


//TO-DO FOR PRUVAN//
///
/// Create a new database table to hold Pruvan Status information
///
///
/// Push changes to server to test validation / push work order
///
/// create functions for updating status of a work order
///
/// create functions for uploaded photos
///
/// Fix the inconsistancies between the current 'pruvan_users' database table, the 'Users' list within Pruvan, and the missing Users from Pruvan that do not exist in Pro-Trak
///
/// Probably add some type of 'Pruvan Username/Email Admin' in the Users area. Currently, Pruvan does not support updating Users via pushkey.
///
/// Setup 'Surveys' functions
///
///

class Pruvan
{

    //Validate Application
    public static function validate($data)
    {
        $payload = json_decode($data['payload'], true);
        $given_pass = $payload['password'];

        if (sha1(getenv('PRUVAN_PASS')) == $given_pass)
        {
            return true;
        }

        return false;
    }

    public static function getWorkOrders()
    {
        $pruvan_fields = [
            'workOrderNumber',
            'workOrderInfo',
            'address1',
            'address2',
            'city',
            'state',
            'zip',
            'country',
            'assignedTo',
            'status',
            'dueDate',
            'instructions',
            'clientStatus',
            'clientDueDate',
            'clientInstructions',
            'description',
            'reference',
            'gpsLatitude',
            'gpsLongitude',
            'options',
            'startDate',
            'source_wo_id',
            'source_wo_number',
            'source_wo_provider',
            'services'
        ];
    }

    //Push New Work Order to Pruvan
    public static function pushWorkOrder($data)
    {

        //Intentionally Blank Values -> Unused In App Or Not Set At This Time. Only added for reference / Future purposes
        $address2       = "";
        $clientDueDate  = "";
        $latitude       = "";
        $longitude      = "";
        $startDate      = "";
        $source_work_order_id = "";
        $source_work_order_number = "";
        $source_work_order_provider = "";
        $options        = [];

        //Requested Service Information
        $requested_data = RequestedService::findOrFail($data["requested_service_id"]);

        //Request Information
        $request_data = MaintenanceRequest::findOrFail($data['request_id']);
//
//        //Service Information
        $service_id     = $requested_data->service_id;

        $service_data   = Service::findOrFail($service_id);

        //Address Information
        $asset_id   = $request_data->asset_id;
        $asset_data = Asset::findOrFail($asset_id);

        //Work Order Info -> Access Code / Lock Code / Other Information Shown for Work Order Info
        $access_code = $asset_data->access_code;
        $lockbox     = $asset_data->lock_box;
        $workOrderInfo = "";

        if ($access_code)
        {
            $workOrderInfo .= "AC: ".$access_code." ";
        }

        if ($lockbox)
        {
            $workOrderInfo .= "LB: ".$lockbox." ";
        }

        $address1   = $asset_data->property_address;
        $city       = City::find($asset_data->city_id)->name;
        $state      = State::find($asset_data->state_id)->name;
        $zip        = $asset_data->zip;

        //Vendor Assigned To Task
        $vendor = PruvanVendors::findOrFail($data['vendor_id'])->email_address;

        //Work Order Status
        $status = "assigned";

        //Set 'Description' with task price
        $description = "$".number_format($service_data->vendor_price,2);

        //Set 'Reference' with Task Name
        $reference = $service_data->title;

        //Set Lat/Long

        //Work Order Due Date
        $dueDate = date("Y-m-d", strtotime($requested_data->due_date))." 1200 +5";

        //'Services' Array
        $services = [["service_name" => $service_data->title]];

        //'Instructions'
        $instructions = $requested_data->public_notes;


        $send_data = json_encode(

            ["workOrders" =>
                [
                    'workOrderNumber' => date("Ymd-His"), //Required
                    'workOrderInfo' => $workOrderInfo,
                    'address1' => $address1, //Required
//                    'address2' => $address2,
                    'city' => $city, //Required
                    'state' => $state, //Required
                    'zip' => $zip, //Required
                    'assignedTo' => $vendor,
                    'status' => $status,
                    'dueDate' => $dueDate,
                    'instructions' => $instructions,
//                    'clientDueDate' => $clientDueDate,
                    'clientInstructions' => $instructions,
                    'description' => $description,
                    'reference' => $reference,
//                    'gpsLatitude' => $latitude,
//                    'gpsLongitude' => $longitude,
//                    'options' => $options,
//                    'startDate' => $startDate,
//                    'source_wo_id' => $source_work_order_id,
//                    'source_wo_number' => $source_work_order_number,
//                    'source_wo_provider' => $source_work_order_provider,
                    'services' => $services
                ]
            ]
        );



        return $send_data;

    }

    //Update Work Order (Pro-Trak > Pruvan)
    public static function updateWorkOrderToPruvan()
    {
        return true;

    }

    //Update Work Order (Pruvan -> Pro-Trak)
    public static function updateWorkOrderFromPruvan()
    {
        return true;

    }

    //Upload Photos
    public static function uploadPhoto()
    {
        return true;

    }

    //Add Pruvan Credentials to database
    public static function addPruvanCredsToTable()
    {
        return true;

    }

    //Remove Pruvan Credentials from database
    public static function removePruvanCredsFromTable()
    {
        return true;

    }

    //Check for Vendor Pruvan Credentials
    public static function findPruvanVendor()
    {
        return true;

    }


}