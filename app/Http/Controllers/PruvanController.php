<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Pruvan;

class PruvanController extends Controller
{
    //Validate
    public function validate()
    {
        $data = Request::all();
        $payload = json_decode($data['payload'], true);
        $given_pass = $payload['password'];
        if (Pruvan::validate($given_pass))
        {
            return json_encode(['error' => '', 'validated' => true]);
        }

        return json_encode(['error' => 'invalid username or password', 'validated' => '']);

    }

    //Send Work Orders
    public function getWorkOrders()
    {

    }

    //Upload Photos
    public function uploadPictures()
    {

    }

    //Status
    public function status()
    {

    }
}
