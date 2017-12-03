<?php

namespace App\Helpers;

use App\MaintenanceRequest;
use App\Asset;
use App\State;
use App\City;
use App\Order;
use App\PruvanVendors;
use App\User;

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
        //Work Order Info -> Access Code / Lock Code / Other Information Shown for Work Order Info
        $workOrderInfo = "";

        //Intentionally Blank Values -> Unused In App Or Not Set At This Time. Only added for reference / Future purposes
        $address2       = "";
        $clientDueDate  = "";
        $startDate      = "";
        $source_work_order_id = "";
        $source_work_order_number = "";
        $source_work_order_provider = "";
        $options        = [];


        //Address Information
        $asset_id   = MaintenanceRequest::findOrFail($data['request_id'])->asset_id;
        $asset_data = Asset::findOrFail($asset_id);
        $address1   = $asset_data->property_address;
        $city       = City::find($asset_data->city_id)->name;
        $state      = State::find($asset_data->state_id)->name;
        $zip        = $asset_data->zip;

        //Vendor Assigned To Task
        $vendor = PruvanVendors::findOrFail($daata['vendor_id']);

        //Work Order Status
        $status = "assigned";

        //Set 'Description' with task price amount
        $description = "";

        //Set 'Reference' with Task Name
        $reference = "";

        //Set Lat/Long
        $latitude = "";
        $longitude = "";

        //Work Order Due Date
        $dueDate = RequestedService::findOrFail($data['request_id']);

        //'Services' Array
        $services = [];

        //TO-DO FOR PRUVAN//
        ///
        /// Create a new database table to hold Pruvan Status information
        ///
        /// Finish Filling in the above variables
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
                    'gpsLatitude' => $latitude,
                    'gpsLongitude' => $longitude,
//                    'options' => $options,
//                    'startDate' => $startDate,
//                    'source_wo_id' => $source_work_order_id,
//                    'source_wo_number' => $source_work_order_number,
//                    'source_wo_provider' => $source_work_order_provider,
                    'services' => $services
                ]
            ]
        );

        return true;
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