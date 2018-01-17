<?php


class importServices
{
    private static $dbuser = "homestead";
    private static $dbpass = "secret";
    private static $dbhost = "127.0.0.1";
    private static $dbname = "homestead";

    private static $dbobj  = false;

    private static $ignore_fields   = ["Item", "Abatement Items", "Preservation Items", "Rehab Items", "Preservation Items:Fannie Mae Items"];
    private static $abatement_title = "Abatement Items:City of Plano";

    private static $preserv_fnma    = "Preservation Items:Fannie Mae Items:";
    private static $preserv_nonfnma = "Preservation Items:Other Items (Non FNMA):";
    private static $rehab_title     = "Rehab Items:";


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

    public static function insertData($array)
    {
        self::connect();

        foreach($array as $service)
        {
            $job_type_id    = false;
            $desc           = false;
            $vendor_price   = false;
            $customer_price = false;
            $original_title = false;
            $short_title    = false;
            $customer_type  = false;

            if (!in_array($service[0], self::$ignore_fields))
            {

                $original_title     = $service[0];
                $desc               = $service[1];
                $vendor_price       = $service[2];
                $customer_price     = $service[4];

                if (strpos($original_title, self::$preserv_fnma)!== false)
                {
                    $job_type_id = 1;
                    $short_title = trim(str_replace(self::$preserv_fnma, "", $original_title));
                    $customer_type = 1;
                }
                else if (strpos($original_title, self::$preserv_nonfnma)!== false)
                {
                    $job_type_id = 1;
                    $short_title = trim(str_replace(self::$preserv_nonfnma, "", $original_title));
                    $customer_type = 3;
                }

                else if (strpos($original_title, self::$rehab_title)!== false)
                {
                    $job_type_id = 2;
                    $short_title = trim(str_replace(self::$rehab_title , "", $original_title));
                    $customer_type = 3;
                }
                else
                {
                    $job_type_id = 3;
                    $short_title = trim(str_replace(self::$abatement_title, "", $original_title));
                    if (substr($short_title, 0, 1) == "-"){
                        $short_title = trim(substr($short_title, 1));
                    }
                    $customer_type = 2;
                }

                $query = "INSERT INTO services_copy (original_title, title, customer_price, vendor_price, status, req_date, number_of_men, verified_vacancy, 
                            cash_for_keys, cash_for_keys_trash_out, trash_size, storage_shed, lot_size, set_prinkler_system_type, install_temporary_system_type, carpet_service_type, 
                            pool_service_type, boarding_type, spruce_up_type, constable_information_type, remove_carpe_type, remove_blinds_type, remove_appliances_type, due_date, emergency, recurring, `desc`, service_cat_id, 
                            customer_type_id, job_type_id, bid_flag, service_type, vendor_edit) VALUES (:orig_title, :title, :customer_price, :vendor_price, :status, :req_date, :men, :vacant, :cash_4_keys, :cash_4_keys_trash,
                            :trash_size, :storage_shed, :lot_size, :set_prinkler_system, :install_temp_sys, :carpet_service, :pool_service, :boarding_type, :spruce_up, :constable, :remove_carpe, :remove_blinds, :remove_appliance,
                            :due_date, :emergency, :recurring, :descr, :service_type_id, :customer_type, :job_type, :bid_flag, :service_type, :vendor_edit)";
                $binds = [
                    ":orig_title" => $original_title,
                    ":title" => $short_title,
                    ":customer_price" => (float)$customer_price,
                    ":vendor_price" => (float)$vendor_price,
                    ":status" => 1,
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
                    ":descr" => $desc,
                    ":service_type_id" => 0,
                    ":customer_type" => $customer_type,
                    ":job_type" => $job_type_id,
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


            }




        }
        return true;

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
        return $lines;
    }

}

$lines = importServices::readFile("services.csv");
return importServices::insertData($lines);



