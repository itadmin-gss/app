<?php

namespace App\Helpers;

class Pruvan
{

    //Validate Application
    public static function validate($pass)
    {
        if (sha1(getenv('PRUVAN_PASS')) == $pass)
        {
            return true;
        }

        return false;
    }
}