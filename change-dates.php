<?php


class importPruvanServices
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

    public static function insertData()
    {
        self::connect();

        $query = "SELECT id, due_date FROM requested_services";
        $return = self::$dbobj->query($query);
        $fetch = $return->fetchAll();

        foreach($fetch as $item)
        {
            $query = "UPDATE requested_services SET due_date = :due_date WHERE id = :id";
            $binds = array(":id" => $item["id"], ":due_date" => date("Y-m-d H:i:s", strtotime($item["due_date"])));
            $st = self::$dbobj->prepare($query);
            $st->execute($binds);
        }
        return true;
    }



}

importPruvanServices::insertData();



