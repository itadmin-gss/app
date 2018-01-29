<?php


class importPruvanServices
{
    //Database Variables
    private static $dbuser = "homestead";
    private static $dbpass = "secret";
    private static $dbhost = "127.0.0.1";
    private static $dbname = "homestead";
    private static $dbobj  = false;


    //States and their abbreviations
    private static $states = array(
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming',
    );

    //Connect to Database
    private static function connect()
    {
        if (!self::$dbobj)
        {
            try
            {
                self::$dbobj = new PDO("mysql:host=".self::$dbhost.";dbname=".self::$dbname, self::$dbuser, self::$dbpass);
                self::$dbobj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return true;
            } catch (PDOException $e)
            {
                return $e->getMessage();
            }
        }
    }

    //Insert Data from CSV into Database
    public static function insertData($array)
    {

        //Connect to Database
        self::connect();

        //Error Reporting Array
        $errors         = [];

        //Iterate over each line in CSV file
        foreach($array as $service)
        {

            $asset_id       = null;
            $customer_id    = null;
            $service_id     = null;
            $job_type       = null;

            //Available fields from CSV File
            $cvs =
                [
                    "project_num" => $service[0],
                    "due_date" => $service[1],
                    "start_date" => $service[2],
                    "client_due_date" => $service[3],
                    "address_1" => $service[4],
                    "address_2" => $service[5],
                    "city" => $service[6],
                    "state" => $service[7],
                    "zip" => $service[8],
                    "info" => $service[9],
                    "status" => $service[10],
                    "field_status" => $service[11],
                    "client_status" => $service[12],
                    "tasks" => json_decode($service[13], true),
                    "assigned_to" => $service[14],
                    "coordinator" => $service[15],
                    "client_code" => $service[16],
                    "reference" => $service[17],
                    "description" => $service[18],
                    "src_proj_provider" => $service[19],
                    "src_proj_id" => $service[20],
                    "src_proj_num" => $service[21],
                    "options" => $service[22]
                ];


            //Find Asset/Customer ID OR Add Asset if not found
            $query = "SELECT id, customer_id FROM assets WHERE property_address LIKE :address_1";
            $bind = array(":address_1" => trim(str_replace(",", " ", $cvs["address_1"])));
            $st = self::$dbobj->prepare($query);
            $st->execute($bind);
            $return = $st->fetch();
            if ($return)
            {
                $asset_id    = $return["id"];
                $customer_id = $return["customer_id"];
            }
            else
            {

                //Generate Random Asset Number
                $asset_num = substr(md5(uniqid(mt_rand(), true)), 0, 8);

                //Workground for Texas being used instead of TX
                if ($cvs["state"] == "Texas")
                {
                    $cvs["state"] = "TX";
                }

                //Get State ID
                $query = "SELECT id FROM states WHERE name = :statename";
                $binds = array(":statename" => self::$states[$cvs["state"]]);
                $st = self::$dbobj->prepare($query);
                $st->execute($binds);
                $results = $st->fetch();
                $state_id = $results['id'];

                //Get City ID
                $query = "SELECT id FROM cities WHERE state_id = :state AND `name` LIKE :cityname";
                $binds = [":state" => $state_id, ":cityname" => $cvs["city"]];
                $st = self::$dbobj->prepare($query);
                $st->execute($binds);
                $results = $st->fetch();
                $city_id = $results['id'];

                //Insert new Asset into assets table
                $query = "INSERT INTO assets (asset_number, customer_id, city_id, state_id, zip, loan_number, lender, property_type, agent, property_status, status, property_address, customer_email_address, customer_type)
                          VALUES(:asset_num, :customer_id, :city, :state, :zip, :loan_num, :lender, :property_type, :agent, :property_status, :status, :property_address, :customer_email_address, :customer_type)";
                $prop_binds =
                    [
                        ":asset_num" => $asset_num,
                        ":customer_id" => 0,
                        ":city" => $city_id,
                        ":state" => $state_id,
                        ":zip" => $cvs["zip"],
                        ":loan_num" => 0,
                        ":lender" => "",
                        ":property_type" => "single-family",
                        ":agent" => "",
                        ":property_status" => "active",
                        ":status" => 1,
                        ":property_address" => $cvs["address_1"],
                        ":customer_email_address" => "",
                        ":customer_type" => 0
                    ];

                $st = self::$dbobj->prepare($query);
                $st->execute($prop_binds);
            }

            //Find Service ID / Job Type | Add Service if cannot be found
            $query = "SELECT id, job_type_id FROM services WHERE title LIKE :title";
            $bind = array(":title" => $cvs["reference"]);
            $st = self::$dbobj->prepare($query);
            $st->execute($bind);
            $return = $st->fetch();
            if ($return)
            {
                $service_id = $return["id"];
                $job_type = $return["job_type_id"];
            }
            else
            {
                $query = "INSERT INTO services (original_title, title, customer_price, vendor_price, status, req_date, number_of_men, verified_vacancy, 
                            cash_for_keys, cash_for_keys_trash_out, trash_size, storage_shed, lot_size, set_prinkler_system_type, install_temporary_system_type, carpet_service_type, 
                            pool_service_type, boarding_type, spruce_up_type, constable_information_type, remove_carpe_type, remove_blinds_type, remove_appliances_type, due_date, emergency, recurring, `desc`, service_cat_id, 
                            customer_type_id, job_type_id, bid_flag, service_type, vendor_edit) VALUES (:orig_title, :title, :customer_price, :vendor_price, :status, :req_date, :men, :vacant, :cash_4_keys, :cash_4_keys_trash,
                            :trash_size, :storage_shed, :lot_size, :set_prinkler_system, :install_temp_sys, :carpet_service, :pool_service, :boarding_type, :spruce_up, :constable, :remove_carpe, :remove_blinds, :remove_appliance,
                            :due_date, :emergency, :recurring, :descr, :service_type_id, :customer_type, :job_type, :bid_flag, :service_type, :vendor_edit)";
                $binds = [
                    ":orig_title" => $cvs["reference"],
                    ":title" => $cvs["reference"],
                    ":customer_price" => 0.00,
                    ":vendor_price" => 0.00,
                    ":status" => 0,
                    ":req_date" => 0,
                    ":men" => 0,
                    ":vacant" => 0,
                    ":cash_4_keys" => 0,
                    ":cash_4_keys_trash" => 0,
                    ":trash_size" => 0,
                    ":storage_shed" => 0,
                    ":lot_size" => 0,
                    ":set_prinkler_system" => 0,
                    ":install_temp_sys" => 0,
                    ":carpet_service" => 0,
                    ":pool_service" => 0,
                    ":boarding_type" => 0,
                    ":spruce_up" => 0,
                    ":constable" => 0,
                    ":remove_carpe" => 0,
                    ":remove_blinds" => 0,
                    ":remove_appliance" => 0,
                    ":due_date" => 1,
                    ":emergency" => 0,
                    ":recurring" => 0,
                    ":descr" => $cvs["reference"],
                    ":service_type_id" => 0,
                    ":customer_type" => 0,
                    ":job_type" => 1,
                    ":bid_flag" => 0,
                    ":service_type" => 0,
                    ":vendor_edit" => 0
                ];

                try{
                    $st = self::$dbobj->prepare($query);
                    $st->execute($binds);
                } catch (PDOException $e){
                    echo $e->getMessage();
                }

                $service_id = self::$dbobj->lastInsertId();
                $job_type = 1;
            }


            //Insert into Maintenance Requests
            $maint_binds =
                [
                    ":customer_id" => $customer_id,
                    ":substitutor_id" => 3485,
                    ":asset_id" => $asset_id,
                    ":job_type" => $job_type,
                    ":status" => 1,
                    ":emergency_request" => 0,
                    ":created_at" => "2018-01-01 00:00:00",
                    ":updated_at" => "2018-01-01 00:00:00",
                ];

            $query = "INSERT INTO maintenance_requests (customer_id, substitutor_id, asset_id, job_type, status, emergency_request, created_at, updated_at)
                      VALUES (:customer_id, :substitutor_id, :asset_id, :job_type, :status, :emergency_request, :created_at, :updated_at)";
            $st = self::$dbobj->prepare($query);
            $st->execute($maint_binds);
            $request_id = self::$dbobj->lastInsertId();


            //Insert into Requested Services
            $requested_binds =
                [
                    ":request_id" => $request_id,
                    ":service_id" => $service_id,
                    ":status" => 1,
                    ":created_at" => "2018-01-01 00:00:00",
                    ":updated_at" => "2018-01-01 00:00:00",
                    ":due_date" => date("m/d/Y", strtotime($cvs["due_date"])),
                    ":service_note" => $cvs["tasks"][0]["instructions"],
                    ":quantity" => "1"
                ];

            $query = "INSERT INTO requested_services (request_id, service_id, status, created_at, updated_at, service_note, due_date, quantity)
                      VALUES (:request_id, :service_id, :status, :created_at, :updated_at, :service_note, :due_date, :quantity)";
            $st = self::$dbobj->prepare($query);
            $st->execute($requested_binds);
            $requested_service_id = self::$dbobj->lastInsertId();


            //Check if Vendor Exists in pruvan_vendors. If not, set to dummy account
            $query = "SELECT vendor_id FROM pruvan_vendors WHERE username LIKE :username";
            $bind = array(":username" => $cvs["assigned_to"]);
            $st = self::$dbobj->prepare($query);
            $st->execute($bind);
            $return = $st->fetch();
            if ($return && isset($return["vendor_id"]))
            {
                $vendor_id = $return["vendor_id"];
            }
            else
            {
                $vendor_id = 9999;
            }

            //Check status
            if ($cvs["status"] == "Complete")
            {
                $status = 5;
                $status_text = "Completed";
                $status_class = "black";
            }
            else if ($cvs{"status"} == "Invoiced")
            {
                $status = 6;
                $status_text = "Exported";
                $status_class = "black";
            }
            else
            {
                $errors[$cvs["status"]] = "Could not determine status";
                continue;
            }


            //Insert into orders table

            $order_binds =
                [
                    ":request_id" => $request_id,
                    ":vendor_id" => $vendor_id,
                    ":status" => $status,
                    ":status_class" => $status_class,
                    ":status_text" => $status_text,
                    ":created_at" => "2018-01-01 00:00:00",
                    ":updated_at" => "2018-01-01 00:00:00",
                    ":customer_id" => $customer_id,
                    ":close_property_status" => 0,
                    ":completion_date" => "01/01/2018"
                ];

            $query = "INSERT INTO orders (request_id, vendor_id, status, status_class, status_text, created_at, updated_at, customer_id, close_property_status, completion_date)
                      VALUES (:request_id, :vendor_id, :status, :status_class, :status_text, :created_at, :updated_at, :customer_id, :close_property_status, :completion_date)";
            $st = self::$dbobj->prepare($query);
            $st->execute($order_binds);
            $order_id = self::$dbobj->lastInsertId();

            //Insert into Order Details table
            $order_detail_binds =
                [
                    ":order_id" => $order_id,
                    ":order_date" => "2018-01-01 00:00:00",
                    ":status" => 2,
                    ":created_at" => "2018-01-01 00:00:00",
                    ":updated_at" => "2018-01-01 00:00:00",
                    ":requested_service_id" => $requested_service_id
                ];

            $query = "INSERT INTO order_details (order_id, order_date, status, created_at, updated_at, requested_service_id)
                      VALUES (:order_id, :order_date, :status, :created_at, :updated_at, :requested_service_id)";
            $st = self::$dbobj->prepare($query);
            $st->execute($order_detail_binds);

            //Insert into assign_requests table
            $query = "INSERT INTO assign_requests (request_id, requested_service_id, vendor_id, status, created_at, updated_at)
                      VALUES (:request_id, :requested_service_id, :vendor_id, :status, :created_at, :updated_at)";
            $binds =
                [
                    ":request_id" => $request_id,
                    ":requested_service_id" => $requested_service_id,
                    ":vendor_id" => $vendor_id,
                    ":status" => 3,
                    ":created_at" => "2018-01-01 00:00:00",
                    ":updated_at" => "2018-01-01 00:00:00"
                ];

            $st = self::$dbobj->prepare($query);
            $st->execute($binds);
        }
        return $errors;
    }


    public static function readFile($file)
    {
        if (!file_exists($file))
        {
            return "File does not exist";
        }

        $lines = [];
        $handle = fopen($file, "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== false)
        {
            $lines[] = $data;
        }
        fclose($handle);
        unset($lines[0]);
        return $lines;
    }

}

//Read CSV File
$lines = importPruvanServices::readFile("pruvan.csv");

//Run Inserts and spit out results.
var_dump(importPruvanServices::insertData($lines));



