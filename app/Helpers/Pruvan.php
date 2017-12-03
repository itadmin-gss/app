<?php

namespace App\Helpers;

use App\MaintenanceRequest;
use App\Asset;
use App\State;
use App\City;
use App\Order;
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

        //Address Information
        $asset_id   = MaintenanceRequest::findOrFail($data['request_id'])->asset_id;
        $asset_data = Asset::findOrFail($asset_id);
        $address1   = $asset_data->property_address;
        $address2   = ""; //Left Blank Intentionally -> Unused in Pro-Trak
        $city       = City::find($asset_data->city_id)->name;
        $state      = State::find($asset_data->state_id)->name;

        //Vendor Assigned To Task
        $vendor = "";

        //Work Order Status
        $status = "";

        //Work Order Due Date
        $dueDate = "";



        $send_data = json_encode(

            ["workOrders" =>
                [
                    'workOrderNumber' => date("Ymd-His"),
                    'workOrderInfo' => $workOrderInfo,
                    'address1' => $address1,
                    'address2' => $address2,
                    'city' => $city,
                    'state' => $state,
                    'zip' => $zip,
                    'assignedTo' => $vendor,
                    'status' => $status,
                    'dueDate' => $dueDate,
                    'instructions' => $instructions,
                    'clientDueDate' => $clientDueDate,
                    'clientInstructions' => $clientInstructions,
                    'description' => $description,
                    'reference' => $reference,
                    'gpsLatitude' => $latitude,
                    'gpsLongitude' => $longitude,
                    'options' => $options,
                    'startDate' => $startDate,
                    'source_wo_id' => $source_work_order_id,
                    'source_wo_number' => $source_work_order_number,
                    'source_wo_provider' => $source_work_order_provider,
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