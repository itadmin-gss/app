<?php


class importProperties
{
    private static $dbuser = "homestead";
    private static $dbpass = "secret";
    private static $dbhost = "127.0.0.1";
    private static $dbname = "homestead";
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

        foreach($array as $prop)
        {
            if ($prop[0] !== "Loan Number")
            {
                $loan_number = $prop[0];
                $reo_id = $prop[1];
                $address = $prop[2];
                $city = $prop[3];
                $state = $prop[4];
                $zip = $prop[5];
                $lender_name = $prop[9];
                $agent_fname = $prop[10];
                $agent_lname = $prop[11];
                $agent_email = $prop[12];
                $agent_phone = $prop[13];
                $agent_addr  = $prop[14];
                $agent_addr2 = $prop[15];
                $agent_city = ucwords($prop[16]);
                $agent_state = trim(strtoupper($prop[17]));
                $agent_zip = $prop[18];

                $query = "SELECT id FROM users WHERE email = :email";
                $st = self::$dbobj->prepare($query);
                $st->execute(array(":email" => $agent_email));
                $results = $st->fetch();

                if (!$results)
                {

                    $query = "SELECT id FROM states WHERE name = :statename";
                    $binds = array(":statename" => self::$states[$agent_state]);

                    $st = self::$dbobj->prepare($query);
                    $st->execute($binds);
                    $results = $st->fetch();

                    $agent_state_id = $results['id'];

                    $query = "SELECT id FROM cities WHERE state_id = :state AND `name` LIKE :cityname";
                    $binds = [":state" => $agent_state_id, ":cityname" => $agent_city];

                    $st = self::$dbobj->prepare($query);
                    $st->execute($binds);
                    $results = $st->fetch();
                    $agent_city_id = $results['id'];


                    $query = "INSERT INTO users (first_name, last_name, email, phone, address_1, address_2, zipcode, type_id, user_role_id, customer_type_id, status, city_id, state_id)
                                  VALUES (:fname, :lname, :email, :phone, :add1, :add2, :zip, :type_id, :user_role, :customer_type, :status, :city, :state)";
                    $binds =
                        [
                            ":fname" => $agent_fname,
                            ":lname" => $agent_lname,
                            ":email" => $agent_email,
                            ":phone" => $agent_phone,
                            ":add1" => $agent_addr,
                            ":add2" => $agent_addr2,
                            ":zip" => $agent_zip,
                            ":type_id" => 2,
                            ":user_role" => 2,
                            ":customer_type" => 1,
                            ":status" => 1,
                            ":city" => $agent_city_id,
                            ":state" => $agent_state_id
                        ];

                    $st = self::$dbobj->prepare($query);
                    $st->execute($binds);
                    $user_id = self::$dbobj->lastInsertId();
                }
                else
                {
                    $user_id = $results['id'];
                }

                $query = "SELECT id FROM states WHERE name = :statename";
                $binds = array(":statename" => self::$states[$state]);

                $st = self::$dbobj->prepare($query);
                $st->execute($binds);
                $results = $st->fetch();
                $state_id = $results['id'];

                $query = "SELECT id FROM cities WHERE state_id = :state AND `name` LIKE :cityname";
                $binds = [":state" => $state_id, ":cityname" => $city];

                $st = self::$dbobj->prepare($query);
                $st->execute($binds);
                $results = $st->fetch();
                $city_id = $results['id'];


                $query = "INSERT INTO assets (asset_number, customer_id, city_id, state_id, zip, loan_number, lender, property_type, agent, property_status, status, property_address, customer_email_address, customer_type)
                          VALUES(:asset_num, :customer_id, :city, :state, :zip, :loan_num, :lender, :property_type, :agent, :property_status, :status, :property_address, :customer_email_address, :customer_type)";
                $prop_binds =
                    [
                        ":asset_num" => $reo_id,
                        ":customer_id" => $user_id,
                        ":city" => $city_id,
                        ":state" => $state_id,
                        ":zip" => $zip,
                        ":loan_num" => $loan_number,
                        ":lender" => $lender_name,
                        ":property_type" => "single-family",
                        ":agent" => $agent_fname." ".$agent_lname,
                        ":property_status" => "active",
                        ":status" => 1,
                        ":property_address" => $address,
                        ":customer_email_address" => $agent_email,
                        ":customer_type" => 1
                    ];

                $st = self::$dbobj->prepare($query);
                $st->execute($prop_binds);
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

$lines = importProperties::readFile("properties.csv");
return importProperties::insertData($lines);



