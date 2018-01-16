<?php


class importServices
{
    private static $dbuser = "homestead";
    private static $dbpass = "secret";
    private static $dbhost = "127.0.0.1";
    private static $dbname = "homestead";

    private static $dbobj  = false;

    private static $job_types       = ["Preservation" => 1,"Rehab" => 2,"Abatement" => 3];
    private static $ignore_fields   = ["Item", "Abatement Items", "Preservation Items", "Rehab Items"];

    private static $abatement_title = "Abatement Items:City of Plano  -";
    private static $preserv_fnma    = "Preservation Items:Fannie Mae Items:";
    private static $preserv_nonfnma = "Preservation Items:Other Items (Non FNMA):";
    private static $rehab_title     = "Rehab Items:";


    private static function connect()
    {
        if (!self::$dbobj)
        {
            try
            {
                self::$dbobj = new PDO("mysql:host=".self::$bhost.";dbname=".self::$dbname, self::$dbuser, self::$dbpass);
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
            if (!in_array($service[0], self::$ignore_fields))
            {
                $title          = $service[0];
                $desc           = $service[1];
                $vendor_price   = $service[2];
                $customer_price = $service[3];
            }

            if (strpos($title, self::$abatement_title))
            {

            }
            else if (strpos($title, self::$preserv_fnma))
            {

            }
            else if (strpos($title, self::$preserv_nonfnma))
            {

            }
            else if (strpos($title, self::$rehab_title))
            {

            }
            else
            {
                echo "Could not determine line type";
            }
        }

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
importServices::insertData($lines);



