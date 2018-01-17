<?php


class importVendors
{
    private static $dbuser = "homestead";
    private static $dbpass = "secret";
    private static $dbhost = "127.0.0.1";
    private static $dbname = "homestead";

    private static $dbobj  = false;


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

        $current        = [];
        $row            = 0;

        foreach($array as $vendor)
        {
            $first_name = "";
            $last_name = "";
            if ($vendor[0] !== "Vendor Full Name")
            {

                if (strlen(trim($vendor[0])) !== 0)
                {
                    $row++;
                    $name_parts = explode(" ", trim($vendor[0]));
                    if (count($name_parts) == 2)
                    {
                        $first_name = $name_parts[0];
                        $last_name = $name_parts[1];
                    } else if (count($name_parts) == 1)
                    {
                        $first_name = $name_parts[0];
                        $last_name = "";
                    }
                    else
                    {
                        $first_name = $vendor[0];
                        $last_name = "";
                    }
                    $current[$row] =
                        [
                            "first_name" => $first_name,
                            "last_name" => $last_name,
                            "company" => $vendor[1],
                            "email" => $vendor[2],
                            "pruvan_user" => $vendor[3],
                            "services" => [$vendor[4]],
                            "zips" => $vendor[5]
                        ];

                }
                else
                {
                    if (isset($vendor[4]))
                    {
                        $current[$row]["services"][] = $vendor[4];
                    }
                }
            }
        }


        foreach($current as $vendor)
        {

            $query = "INSERT INTO users (first_name, last_name, company, email, type_id, status, available_zipcodes)
                      VALUES (:fname, :lname, :company, :email, :type_id, :status, :zipcodes)";
            $binds =
                [
                    ":fname" => trim($vendor["first_name"]),
                    ":lname" => trim($vendor["last_name"]),
                    ":company" => trim($vendor["company"]),
                    ":email" => trim($vendor["email"]),
                    ":type_id" => 3,
                    ":status" => 1,
                    ":zipcodes" => $vendor["zips"]
                ];

            $st = self::$dbobj->prepare($query);
            $st->execute($binds);
            $id = self::$dbobj->lastInsertId();

            $query = "INSERT INTO pruvan_vendors (vendor_id, username, email_address) VALUES (:vendor, :usern, :email)";
            $binds = array(":vendor" => $id, ":usern" => trim($vendor['pruvan_user']), ":email" => trim($vendor['email']));

            $st = self::$dbobj->prepare($query);
            $st->execute($binds);

            foreach($vendor["services"] as $service)
            {
                $query = "SELECT id FROM services WHERE original_title = :title";
                $binds = array(":title" => $service);
                $st = self::$dbobj->prepare($query);
                $st->execute($binds);
                $results = $st->fetch();
                $service_id = $results[0];

                $query = "INSERT INTO vendor_services (vendor_id, service_id, status) VALUES (:vendor, :service, :status)";
                $binds = array(":vendor" => $id, ":service" => $service_id, ":status" => 1);

                $st = self::$dbobj->prepare($query);
                $st->execute($binds);
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

$lines = importVendors::readFile("vendors.csv");
return importVendors::insertData($lines);



