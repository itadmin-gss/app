<?php

namespace App\Http\Controllers;

use App\OrderDetail;
use Illuminate\Support\Facades\Config;
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
            return true;
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
            $payload                = json_decode($data['payload'], true);
            $pro_trak_data          = json_decode($payload['attribute7'], true);
            $requested_service_id   = $pro_trak_data['requested_service_id'];
            $request_id             = $pro_trak_data['request_id'];
            $vendor_id              = $pro_trak_data['vendor_id'];
            $order_id               = $pro_trak_data['order_id'];
            $filename               = $payload['fileName'];
            $type                   = $payload['evidenceType'];

            switch (strtolower($type))
            {
                case "before":
                    $upload_path            = Config::get('app.order_images_before');
                    break;

                case "after":
                    $upload_path            = Config::get('app.order_images_after');
                    break;

                case "during":
                    $upload_path            = Config::get('app.order_images_during');
                    break;

                default:
                    $upload_path            = false;
                    break;
            }

            if (!$upload_path)
            {
                mail("jdunn82k@gmail.com", "Pruvan Testing Again", $type);
                return json_encode(['error' => 'Missing Path']);
            }

            $file->move($upload_path, $filename);

            $image_details = [
                "order_id" => $order_id,
                "order_details_id" => $order_id,
                "type" => strtolower($type),
                "address" => $filename
            ];

            OrderImage::create($image_details);

            return json_encode(["status" => true, "error" => null]);
        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }

    //Status
    public function status()
    {
        $data = Request::all();

        if (Pruvan::validateApp($data))
        {
            Pruvan::setStatus($data);
        }
        return json_encode(['error' => 'invalid username, password, or token', 'validated' => '']);

    }
}
