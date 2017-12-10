<?php

namespace App\Helpers;

use App\MaintenanceRequest;
use App\Asset;
use App\State;
use App\City;
use App\Order;
use App\PruvanPushKeys;
use App\PruvanVendors;
use App\RequestedService;
use App\Service;
use App\User;


//TO-DO FOR PRUVAN//
///
/// Create a new database table to hold Pruvan Status information
///
/// Adjust validate() function for user use
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

    //Validate Application with Pruvan
    public static function validateApp($data)
    {
        $payload = json_decode($data['payload'], true);
        $given_pass = $payload['token'];
        $pushkey    = $payload['pushkey'];

        if (env('PRUVAN_PASS') == $given_pass)
        {
            PruvanPushKeys::updateOrCreate(
                ['vendor_id' => 0, 'application' => 1],
                ['pushkey' => $pushkey]
            );
            return true;
        }

        return false;
    }


    //Validate Pruvan User !!!!!!!!!INCOMPLETE USE FOR VENDORS!!!!!!!!!!!!!
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

        $data_array =

                            [
                                'workOrderNumber' => "1234", //Required
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
                                'attribute7' => json_encode($data),
            //                    'gpsLatitude' => $latitude,
            //                    'gpsLongitude' => $longitude,
            //                    'options' => $options,
            //                    'startDate' => $startDate,
            //                    'source_wo_id' => $source_work_order_id,
            //                    'source_wo_number' => $source_work_order_number,
            //                    'source_wo_provider' => $source_work_order_provider,
                                'services' => $services
                            ];




        //Send Data to Pruvan via cURL

        $pushkey_url = PruvanPushKeys::findOrFail(0)->pushkey;

        $data_string = "{\"workOrders\": ".json_encode($data_array)."}";
//        $teststring = "{
//            \"workOrders\": [{
//            \"workOrderNumber\": \"Simple\",
//            \"address1\": \"110 East Main Street\",
//            \"city\": \"Round Rock\",
//            \"state\": \"TX\",
//            \"zip\": \"78664\",
//            \"services\": [{
//            \"serviceName\": \"Task\"
//            }]
//            }]
//        }";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pushkey_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("workOrders" => $data_string));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_error($ch))
        {
            mail("jdunn82k@gmail.com", "PRUVAN TESTING", curl_error($ch));
        }

        mail("jdunn82k@gmail.com", "PRUVAN TESTING", json_encode($response));
        return $response;

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

    //Upload Photos (Pruvan -> Pro-Trak)
    public static function uploadPhoto($data)
    {
        mail("jdunn82k@gmail.com", "Pruvan Testing", json_encode($data));


        $available_fields = [
            'username',
            'password',
            'token',
            'pictureId',
            'uuId',
            'parentUuid',
            'key1',
            'key2',
            'key3',
            'key4',
            'key5',
            'attribute1',
            'attribute2',
            'attribute3',
            'attribute4',
            'attribute5',
            'attribute6',
            'fileExt',
            'fileName',
            'fileType',
            'evidenceType',
            'survey',
            'template',
            'notes',
            'gpsAccuracy',
            'gpsLatitude',
            'gpsLongitude',
            'gpsTimestamp',
            'authenticated',
            'locationDifference',
            'csrCertifiedTime',
            'csrLocationSource',
            'csrPictureCount',
            'csrTimeStampSource',
            'csrCertifiedLocation',
            'attribute7-15',
            'attribute16-30',
            'deviceId',
            'phoneNumber',
            'status',
            'uploadVersion',
            'batchId',
            'workDay',
            'timestamp',
            'clientCode',
            'createdBy',
            'createdBySubUser',
            'lastUpdatedBy',
            'creationDate',
            'videoUri'

        ];
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



}