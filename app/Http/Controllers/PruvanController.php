<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Helpers\Pruvan;

class PruvanController extends Controller
{
    //Validate
    public function validateApp()
    {
        $data = Request::all();

        if (Pruvan::validateApp($data))
        {
            return json_encode(['error' => '', 'validated' => true]);
        }

        return json_encode(['error' => 'invalid username or password', 'validated' => '']);

    }

    //Send Work Orders
    public function getWorkOrders()
    {
        $data = Request::all();
        if (Pruvan::validateApp($data))
        {

        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }

    //Upload Photos
    public function uploadPictures()
    {
        $data = Request::all();
        $file = Request::file('file');
        if (Pruvan::validateApp($data))
        {
            Pruvan::uploadPhoto($data, $file);
            return true;
        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }

    //Status
    public function status()
    {
        $data = Request::all();
        mail("jdunn82k@gmail.com", "PRUVAN TESTING", json_encode($data));

        if (Pruvan::validateApp($data))
        {
            Pruvan::setStatus($data);
        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }
}
