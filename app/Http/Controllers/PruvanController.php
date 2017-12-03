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

        if (Pruvan::validate($data))
        {
            return json_encode(['error' => '', 'validated' => true]);
        }

        return json_encode(['error' => 'invalid username or password', 'validated' => '']);

    }

    //Send Work Orders
    public function getWorkOrders()
    {
        $data = Request::all();
        if (Pruvan::validate($data))
        {

        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }

    //Upload Photos
    public function uploadPictures()
    {
        $data = Request::all();
        if (Pruvan::validate($data))
        {

        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }

    //Status
    public function status()
    {
        $data = Request::all();
        if (Pruvan::validate($data))
        {

        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }
}
